<?php

namespace App\Http\Controllers;

use App\Dynamic;
use App\Http\Requests\DynamicRequest;
use App\Project;
use App\ProjectPhase;
use Illuminate\Http\Request;

class DynamicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $project_id = request('project_id');
        $project = Project::find($project_id);
        return view('dynamic.default.create', compact(['project_id','project']));
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
        $request->offsetSet('user_id', get_current_login_user_info());
        $request->offsetSet('status', 0);
        $dynamic = Dynamic::create($request->input());
        if ($dynamic) {
            //判断是否改变状态
            $phase_id = $request->phase_id;
            $phase_status = $request->phase_status;
            if($phase_status !== null){
                ProjectPhase::where('id',$phase_id)->where('status','<',2)->update(['status'=>intval($phase_status) + 1]);
                //如果是完成某个阶段，自动启动下一个阶段

                //更新项目状态
                $project->updateStatus();
            }
            activity('项目日志')->performedOn($project)
                ->withProperties($dynamic->toArray())
                ->log('上传日志');
            return _success('上传成功', $dynamic->toArray(), get_redirect_url());
        } else {
            return _error('操作失败');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $project  = $dynamic->project;
        if(!check_project_owner($project,'edit')
            && $dynamic->user->id != get_current_login_user_info()){
            return _404('无权操作');
        }
        if ($dynamic) {
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
        if(!check_project_owner($project,'edit')
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
                    ProjectPhase::where('id',$phase_id)->where('status','<',2)->update(['status'=>intval($phase_status) + 1]);
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
        if(!check_project_owner($dynamic->project,'edit')){
            return _404('无权操作');
        }
        if ($dynamic->delete($id)) {
            activity('项目日志')->performedOn(Project::find($dynamic->project_id))
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
        $list = $user->dynamics()->when($project_id, function ($query) use ($project_id) {
            return $query->where('project_id', $project_id);
        })->orderBy('id', 'desc')->paginate(config('common.page.per_page'));
        set_redirect_url();
        return view('dynamic.default.personal', compact('list'));
    }
}
