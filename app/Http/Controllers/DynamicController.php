<?php

namespace App\Http\Controllers;

use App\Dynamic;
use App\Http\Requests\DynamicRequest;
use App\Plan;
use App\Project;
use App\ProjectPhase;
use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Jenssegers\Agent\Facades\Agent;

class DynamicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
                $project_id = $request->input('datatable.query.project_id');
                $user_id = $request->input('datatable.query.user_id');
                $search = $request->input('datatable.query.search');
                $date = $request->input('datatable.query.date');
                //管理员或总部管理员获取所有
                if (check_user_role(null, '总部管理员')) {
                    $task = Dynamic::with([
                        'user', 'project','project.committedPlans','project.plans'
                    ])->when($search, function ($query) use ($search) {
                        return $query->where(function ($query) use ($search) {
                            $query->where(
                                'content', 'like',
                                "%{$search}%"
                            );
                        });
                    })->when($date,function ($query) use ($date) {
                        return $query->whereBetween('created_at', [
                            date_start_end($date),date_start_end($date,'end')
                        ]);
                    })->when($project_id,function ($query) use ($project_id) {
                        return $query->where('project_id', $project_id);
                    })->when($user_id,function ($query) use ($user_id) {
                        return $query->where('user_id', $user_id);
                    })->orderBy(
                        $sort_field
                        , $sort)->paginate(
                        $prepage
                        , ['*']
                        , 'datatable.pagination.page'
                    );
                } else{
                    //分部管理员获取分部所有项目
                    //获取分部所有用户
                    $user = get_company_user(null,'id', false, [1,0]);
                    $task = Dynamic::with([
                        'user',  'project','project.committedPlans','project.plans'
                    ])->whereIn('user_id',$user)->when($search, function ($query) use ($search) {
                        return $query->where(function ($query) use ($search) {
                            $query->where(
                                'content', 'like',
                                "%{$search}%"
                            );
                        });
                    })->when($date,function ($query) use ($date) {
                        return $query->whereBetween('created_at', [
                            date_start_end($date),date_start_end($date,'end')
                        ]);
                    })->when($project_id,function ($query) use ($project_id) {
                        return $query->where('project_id', $project_id);
                    })->when($user_id,function ($query) use ($user_id) {
                        return $query->where('user_id', $user_id);
                    })->orderBy(
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
            return view('dynamic.default.index');
        }else{
            $project_id = $request->input('project_id');
            $user_id = $request->input('user_id');
            $search = $request->input('search');
            $date = $request->input('date');
            //管理员或总部管理员获取所有
            if (check_user_role(null, '总部管理员')) {
                $list = Dynamic::with([
                    'user', 'project','project.committedPlans','project.plans'
                ])->when($search, function ($query) use ($search) {
                    return $query->where(function ($query) use ($search) {
                        $query->where(
                            'content', 'like',
                            "%{$search}%"
                        );
                    });
                })->when($project_id,function ($query) use ($project_id) {
                    return $query->where('project_id', $project_id);
                })->when($user_id,function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })->when($date,function ($query) use ($date) {
                    return $query->whereBetween('created_at', [
                        date_start_end($date),date_start_end($date,'end')
                    ]);
                })->orderBy(
                    'id'
                    , 'desc')->paginate(config('common.page.per_page'));
            } else{
                //分部管理员获取分部所有项目
                //获取分部所有用户
                $user = get_company_user(null,'id', false, [1,0]);
                $list = Dynamic::with([
                    'user',  'project','project.committedPlans','project.plans'
                ])->whereIn('user_id',$user)->when($search, function ($query) use ($search) {
                    return $query->where(function ($query) use ($search) {
                        $query->where(
                            'content', 'like',
                            "%{$search}%"
                        );
                    });
                })->when($date,function ($query) use ($date) {
                    return $query->whereBetween('created_at', [
                        date_start_end($date),date_start_end($date,'end')
                    ]);
                })->when($project_id,function ($query) use ($project_id) {
                    return $query->where('project_id', $project_id);
                })->when($user_id,function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })->orderBy(
                    'id'
                    , 'desc')->paginate(config('common.page.per_page'));
            }
            set_redirect_url();
            return view('dynamic.default.mobile',compact('list'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $project_id = request('project_id');
        $task_id = request('task_id');
        $fill_date = request('date');
        $is_fill = request('fill');
        $project = Project::find($project_id);
        if (!$project || !check_project_owner($project, 'look')) {
            return _404('无权操作');
        }
        $task = Task::find($task_id);
        $task->received();
        return view('dynamic.default.create', compact(['project_id','project','task','fill_date','is_fill']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DynamicRequest $request)
    {
        $project = Project::find($request->project_id);
        if (!$project || !check_project_owner($project, 'look')) {
            return _404('无权操作');
        }
        $request->offsetSet('user_id', get_current_login_user_info());
        $request->offsetSet('status', 0);
        if($request->task_status){ //如果设置完成任务
            //验证任务的信息
            $this->validate($request, [
//                'task_builded_at' => 'bail|required',
//                'task_leaved_at' => 'bail|required|after_or_equal:builded_at',
                'task_leaved_at' => 'bail|required',
                'task_result' => 'bail|required'
            ], [
                'task_builded_at.required' => '请输入去现场时间',
                'task_leaved_at.required' => '请输入离开现场时间',
                'task_leaved_at.after' => '截止时间要大于开始时间',
                'task_result.required' => '请输入完成情况',
            ]);
        }

        if($request->plan_id && $request->is_finished == null){ //如果计划延期
            //验证计划的信息
            $this->validate($request, [
                'delay' => 'bail|required|numeric',
            ], [
                'delay.required' => '请输入延期天数',
                'delay.numeric' => '延期天数必须是数字',
            ]);
        }

        $data = $request->input();
        $fill_date = $request->input('fill_date');
        if($fill_date && Carbon::parse($fill_date) != Carbon::parse(current_date())){
            $data['created_at'] = Carbon::parse($fill_date)->addHour(9);
            $data['fill'] = 1;
        }
        $dynamic = Dynamic::create($data);
        if ($dynamic) {
            //判断是否改变状态
            $phase_id = $request->phase_id;
            $phase_status = $request->phase_status;
            if($phase_status !== null){
                $update = ['status'=>intval($phase_status) + 1];
                if(!$phase_status){
                    $update['started_at'] = Carbon::now();
                }else{
                    $update['finished_at'] = Carbon::now();
                }
                ProjectPhase::where('id',$phase_id)
                    ->where('status','<',2)
                    ->update($update);
                //如果是完成某个阶段，自动启动下一个阶段
                //更新项目状态
                $project->updateStatus();
            }
            $plan_id = $request->plan_id;
            $plan_status = $request->is_finished;
            $delay = $request->input('delay');
            if($plan_id && $plan = Plan::find($plan_id)){
                if($plan_status == null){
                    $update_plan = [
                        'is_finished' => 0,
                        'reason'=>$data['content'],
                        'delay'=>$delay + $plan->delay
                    ];
                    Plan::where('id',$plan_id)->update($update_plan);
                    //该计划之后的计划自动延期
                    Plan::autoPlanAfterDelay($plan,$delay);
                }else{
                    $update_plan = [
                        'is_finished' => 1,
                        'last_finished_at' => Carbon::now(),
                        'reason'=>null,
                        'delay'=>null
                    ];
                    if($plan->delay){
                        //已是延期的计划
                        $update_plan['is_finished'] = 0;
                        unset($update_plan['reason']);
                        unset($update_plan['delay']);
                    }
                    Plan::where('id',$plan_id)->update($update_plan);
                }
            }
            $task = Task::find($request->task_id);
            if($request->task_status){
                if(!$task->builded_at){
                    $task->builded_at = $request->task_builded_at;
                }
                $task->leaved_at = $request->task_leaved_at;
                $task->result = $request->task_result;
                $task->finished_at = Carbon::now();
                $task->status = 1;
                $task->save();
                activity('项目日志')->performedOn($project)
                    ->withProperties($task)
                    ->log('完成任务');
            }elseif(!$task->builded_at){
                if(!$fill_date){
                    $task->builded_at = Carbon::now();
                }
                $task->save();
            }else{
                $task->leaved_at = null;
                $task->result = null;
                $task->finished_at = null;
                $task->status = 0;
                $task->save();
            }
            activity('项目日志')->performedOn($project)
                ->withProperties($dynamic->toArray())
                ->log('上传日志');
            return _success('上传成功', $dynamic->toArray(), get_redirect_url());
        } else {
            return _error('操作失败');
        }
    }

    public function show($id){
        $dynamic = Dynamic::find($id);
        return view('dynamic.default.show',compact('dynamic'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dynamic = Dynamic::find($id);
        if ($dynamic) {
            $project  = $dynamic->project;
            if(!check_project_owner($project,'company')
                && $dynamic->user->id != get_current_login_user_info()){
                return _404('无权操作');
            }
            return view('dynamic.default.edit', compact(['dynamic','project']));
        } else {
            return _404();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DynamicRequest $request, $id)
    {
        $dynamic = Dynamic::find($id);
        $project = $dynamic->project;
        if(!check_project_owner($project,'company')
            && $dynamic->user->id != get_current_login_user_info()){
            return _404('无权操作');
        }
        if ($dynamic) {
            $dynamic->content = $request->input('content');
//            $dynamic->onsite_user = $request->onsite_user;
            if ($dynamic->save()) {
                //判断是否改变状态
                $phase_id = $request->phase_id;
                $phase_status = $request->phase_status;
                if($phase_status !== null){
                    $update = ['status'=>intval($phase_status) + 1];
                    if(!$phase_status){
                        $update['started_at'] = Carbon::now();
                    }else{
                        $update['finished_at'] = Carbon::now();
                    }
                    ProjectPhase::where('id',$phase_id)
                        ->where('status','<',2)
                        ->update($update);
                    //更新项目状态
                    $project->updateStatus();
                }
                activity('项目日志')->performedOn(Project::find($dynamic->project_id))
                    ->withProperties($dynamic->toArray())
                    ->log('编辑动态');
                return _success('操作成功', $dynamic->toArray());
            } else {
                return _error();
            }
        } else {
            return _404();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dynamic = Dynamic::find($id);
        if(!check_project_owner($dynamic->project,'company')){
            return _404('无权操作');
        }
        if ($dynamic->delete($id)) {
            activity('项目日志')->performedOn($dynamic->project)
                ->withProperties($dynamic->toArray())
                ->log('删除动态');
            return _success('操作成功', $dynamic->toArray(), get_redirect_url());
        } else {
            return _error();
        }
    }

    /**
     * 个人日志
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function personal(Request $request){
        $user = get_current_login_user_info(true);
        $project_id = $request->input('project_id');
        $date = $request->input('date');
        $list = $user->dynamics()->with([
            'project','project.plans'
        ])->when($project_id, function ($query) use ($project_id) {
            return $query->where('project_id', $project_id);
        })->when($date,function ($query) use ($date){
            return $query->whereBetween('created_at', [
                date_start_end($date),date_start_end($date,'end')
            ]);
        })->orderBy('id', 'desc')->paginate(config('common.page.per_page'));
        set_redirect_url();
        return view('dynamic.default.personal', compact('list'));
    }

    public function fillIn(){
        set_redirect_url();
        return view('dynamic.default.fill');
    }

}
