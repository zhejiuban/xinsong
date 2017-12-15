<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Project;
use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        $project_id = request('project_id');
        return view('task.default.create', compact('project_id'));
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
        if(!check_project_owner($project,'edit')){
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
        if (!empty($data) && DB::table('tasks')->insert($data)) {
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
            $task->project_phase_id = $request->project_phase_id;
            $task->start_at = $request->start_at;
            $task->end_at = $request->end_at;
            $task->is_need_plan = $request->is_need_plan;
            $task->content = $request->input('content');
            $task->leader = $request->leader;
            if ($task->save()) {
                activity('项目日志')->performedOn(Project::find($task->project_id))
                    ->withProperties($task->toArray())
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
        $project = Project::find($task->project_id);
        //判断任务删除权限
        if (!check_project_owner($project, 'edit')) {
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
        $task = Task::find($id);
        if ($task) {
            if ($request->isMethod('post')) {
                $this->validate($request, [
                    'builded_at' => 'bail|required',
                    'leaved_at' => 'bail|required|after_or_equal:builded_at',
                    'result' => 'bail|required'
                ], [
                    'builded_at.required' => '请输入去现场时间',
                    'leaved_at.required' => '请输入离开现场时间',
                    'leaved_at.after' => '截止时间要大于开始时间',
                    'result.required' => '请输入完成情况',
                ]);
                $task->builded_at = $request->builded_at;
                $task->leaved_at = $request->leaved_at;
                $task->result = $request->result;
                $task->finished_at = Carbon::now();
                $task->status = 1;
                if ($task->save()) {
                    activity('项目日志')->performedOn(Project::find($task->project_id))
                        ->withProperties($task->toArray())
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
        })->orderBy('id', 'desc')->paginate(config('common.page.per_page'));
        set_redirect_url();
        return view('task.default.personal', compact('list'));
    }
}
