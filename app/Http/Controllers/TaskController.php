<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Project;
use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        $request->offsetSet('user_id', get_current_login_user_info());
        $request->offsetSet('status', 0);
        $task = Task::create($request->input());
        if ($task) {
            activity('项目日志')->performedOn(Project::find($task->project_id))
                ->withProperties($task->toArray())
                ->log('发布任务');
            return _success('发布成功', $task->toArray(), get_redirect_url());
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
        if ($task->delete($id)) {
            activity('项目日志')->performedOn(Project::find($task->project_id))
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
}