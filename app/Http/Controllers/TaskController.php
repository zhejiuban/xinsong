<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Project;
use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Facades\Agent;

class TaskController extends Controller
{

    public function index(Request $request)
    {
        if(!Agent::isMobile()){
            if ($request->ajax()) {
                $sort_field = $request->input('datatable.sort.field')
                    ? $request->input('datatable.sort.field') : 'id';
                $sort = $request->input('datatable.sort.sort')
                    ? $request->input('datatable.sort.sort') : 'desc';
                $prepage = $request->input('datatable.pagination.perpage')
                    ? (int)$request->input('datatable.pagination.perpage') : 20;
                $status = $request->input('datatable.query.status');
                $search = $request->input('datatable.query.search');
                $project_id = $request->input('datatable.query.project_id');
                $user_id = $request->input('datatable.query.user_id');
                //管理员或总部管理员获取所有
                if (check_user_role(null, '总部管理员')) {
                    $task = Task::with([
                        'user', 'leaderUser', 'project'
                    ])->baseSearch($status,$search,$project_id,$user_id)
                        ->orderBy('status', 'asc')
                        ->orderBy($sort_field, $sort)->paginate(
                        $prepage
                        , ['*']
                        , 'datatable.pagination.page'
                    );
                } else{
                    //获取分部所有用户
                    $user = get_company_user(null,'id');
                    $task = Task::with([
                        'user', 'leaderUser', 'project'
                    ])->whereIn('leader',$user)->baseSearch($status,$search,$project_id,$user_id)
                        ->orderBy('status', 'asc')->orderBy(
                        $sort_field
                        , $sort)->paginate(
                        $prepage
                        , ['*']
                        , 'datatable.pagination.page'
                    );
                }

                $meta = [
                    'field' => $sort_field,
                    'sort' => $sort,
                    'page' => $task->currentPage(),
                    'pages' => $task->hasMorePages(),
                    'perpage' => $prepage,
                    'total' => $task->total()
                ];
                $data = $task->toArray();
                $data['meta'] = $meta;
                return response()->json($data);
            }
            set_redirect_url();
            return view('task.default.index');
        }else{
            $status = $request->input('status');
            $search = $request->input('search');
            $project_id = $request->input('project_id');
            $user_id = $request->input('user_id');
            //管理员或总部管理员获取所有
            if (check_user_role(null, '总部管理员')) {
                $list = Task::with([
                    'user', 'leaderUser', 'project'
                ])->baseSearch($status,$search,$project_id,$user_id)->orderBy('status', 'asc')->orderBy(
                    'id'
                    , 'desc')->paginate(config('common.page.per_page'));
            } else{
                //获取分部所有用户
                $user = get_company_user(null,'id');
                $list = Task::with([
                    'user', 'leaderUser', 'project'
                ])->whereIn('leader',$user)->baseSearch($status,$search,$project_id,$user_id)
                    ->orderBy('status', 'asc')->orderBy(
                    'id'
                    , 'desc')->paginate(config('common.page.per_page'));
            }
            set_redirect_url();
            return view('task.default.mobile',compact('list'));
        }
    }

    public function create()
    {
        //判断发布任务权限
        $project_id = request('project_id');
        if(request()->ajax()){
            return view('task.default.create', compact('project_id'));
        }else{
            return view('task.default.create_default', compact('project_id'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request)
    {
        $project = Project::find($request->project_id);
        //检测项目权限
        if(!check_project_leader($project,3)){
            return _404('无权操作');
        }
        $data = [];
        if($request->leader){
            $user = get_current_login_user_info();
            foreach(array_unique($request->leader) as $leader){
                $insert['user_id'] = $user;
                $insert['project_id'] = $request->project_id;
                $insert['leader'] = $leader;
                $insert['content'] = $request->input('content');
                $insert['status'] = 0;
                $insert['is_need_plan'] = $request->is_need_plan;
                $insert['start_at'] = $request->start_at;
                $insert['created_at'] = Carbon::now();
                $insert['updated_at'] = Carbon::now();
                $data[] = $insert;
            }
        }
        if (!empty($data)) { // && DB::table('tasks')->insert($data)
            foreach ($data as $insert){
                Task::create($insert);
            }
            activity('项目日志')->performedOn(Project::find($request->project_id))
                ->withProperties($data)
                ->log('发布任务');

            return _success('发布成功', $data, get_redirect_url());
        } else {
            return _error('操作失败');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id);
        if ($task) {
            //更新接收日期
            $task->received();
            return view('task.default.show', compact('task'));
        } else {
            return _404();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::find($id);
        if ($task) {
            //检测项目权限
            $project = $task->project;
            if(!check_project_leader($project,3)){
                return _404('无权操作');
            }
            return view('task.default.edit', compact('task'));
        } else {
            return _404();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(TaskRequest $request, $id)
    {
        $task = Task::find($id);
        if ($task) {
            $project = $task->project;
            if(!check_project_leader($project,3)){
                return _404('无权操作');
            }
            $task->project_phase_id = $request->project_phase_id;
            $task->start_at = $request->start_at;
            $task->end_at = $request->end_at;
            $task->is_need_plan = $request->is_need_plan;
            $task->content = $request->input('content');
            $task->leader = $request->leader;
            if ($task->save()) {
                activity('项目日志')->performedOn($project)
                    ->withProperties($task)
                    ->log('编辑任务');
                return _success('操作成功', $task->toArray());
            } else {
                return _error();
            }
        } else {
            return _404();
        }
    }

    /**
     * 删除任务
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        $project = $task->project;
        if($task->dynamics->isNotEmpty()){
            return _404('存在关联数据，不能删除');
        }
        //判断任务删除权限
        if (!check_project_leader($project,3)) {
            return _404('无权操作');
        }
        if ($task->delete($id)) {
            activity('项目日志')->performedOn($project)
                ->withProperties($task->toArray())
                ->log('删除任务');
            return _success('操作成功', $task->toArray(), get_redirect_url());
        } else {
            return _error();
        }
    }

    //任务完成情况
    public function finish(Request $request, $id)
    {
        $task = Task::where('status',0)->find($id);
        if ($task) {
            $project = $task->project;
            //判断权限
            if($task->leader != get_current_login_user_info()){
                if (!check_project_leader($project,3)) {
                    return _404('无权操作');
                }
            }
            if ($request->isMethod('post')) {
                $this->validate($request, [
                    'builded_at' => 'bail|required',
                    'leaved_at' => 'bail|required|after_or_equal:builded_at',
                    'result' => 'bail|required'
                ], [
                    'builded_at.required' => '请输入去现场时间',
                    'leaved_at.required' => '请输入离开现场时间',
                    'leaved_at.after_or_equal' => '截止时间要大于开始时间',
                    'result.required' => '请输入完成情况',
                ]);
                $task->builded_at = $request->builded_at;
                $task->leaved_at = $request->leaved_at;
                $task->result = $request->result;
                $task->finished_at = Carbon::now();
                $task->status = 1;
                if ($task->save()) {
                    activity('项目日志')->performedOn($project)
                        ->withProperties($task)
                        ->log('任务完成');
                    return _success('操作成功', $task->toArray(), get_redirect_url());
                } else {
                    return _error('操作失败');
                }
            } else {
                $task->received();
                return view('task.default.finish', compact('task'));
            }
        } else {
            return _error();
        }
    }

    public function personal(Request $request){
        $user = get_current_login_user_info(true);
        $project_id = $request->input('project_id');
        $status = $request->input('status');
        $list = $user->leaderTasks()->when($project_id, function ($query) use ($project_id) {
            return $query->where('project_id', $project_id);
        })->when($status, function ($query) use ($status) {
            return $query->where('status', $status);
        }, function ($query) use ($status) {
            if ($status !== null) {
                return $query->where('status', $status);
            }
        })->orderBy('status')->orderBy('id', 'desc')->paginate(config('common.page.per_page'));
        set_redirect_url();
        return view('task.default.personal', compact('list'));
    }

    public function dynamics($id,Request $request)
    {
        $task = Task::find($id);
        if(!$task){
            return _error('无权操作');
        }
        if (!Agent::isMobile()) {
            if ($request->ajax()) {
                $sort_field = $request->input('datatable.sort.field')
                    ? $request->input('datatable.sort.field') : 'id';
                $sort = $request->input('datatable.sort.sort')
                    ? $request->input('datatable.sort.sort') : 'desc';
                $prepage = $request->input('datatable.pagination.perpage')
                    ? (int)$request->input('datatable.pagination.perpage') : 20;
                $date = $request->input('datatable.query.date');
                $search = $request->input('datatable.query.search');
                //管理员或总部管理员获取所有
                $list = $task->dynamics()
                    ->when($search, function ($query) use ($search) {
                        return $query->where(function ($query) use ($search) {
                            $query->where(
                                'content', 'like',
                                "%{$search}%"
                            );
                        });
                    })->when($date, function ($query) use ($date) {
                        return $query->whereBetween('created_at', [
                            date_start_end($date), date_start_end($date, 'end')
                        ]);
                    })->orderBy(
                        $sort_field
                        , $sort)->paginate(
                        $prepage
                        , ['*']
                        , 'datatable.pagination.page'
                    );
                $meta = [
                    'field' => $sort_field,
                    'sort' => $sort,
                    'page' => $list->currentPage(),
                    'pages' => $list->hasMorePages(),
                    'perpage' => $prepage,
                    'total' => $list->total()
                ];
                $data = $list->toArray();
                $data['meta'] = $meta;
                return response()->json($data);
            }
            return view('task.default.dynamic',compact('task'));
        }else{
            $user_id = $request->input('user_id');
            $date = $request->input('date');
            $search = $request->input('search');
            $list = $task->dynamics()
                ->when($search, function ($query) use ($search) {
                    return $query->where(function ($query) use ($search) {
                        $query->where(
                            'content', 'like',
                            "%{$search}%"
                        );
                    });
                })->when($date, function ($query) use ($date) {
                    return $query->whereBetween('created_at', [
                        date_start_end($date), date_start_end($date, 'end')
                    ]);
                })
                ->orderBy('id','desc')
                ->paginate(config('common.page.per_page'));
            return view('task.default.dynamic_mobile'
                , compact(['list', 'task']));
        }
    }
}
