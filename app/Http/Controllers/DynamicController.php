<?php

namespace App\Http\Controllers;

use App\Dynamic;
use App\Http\Requests\DynamicRequest;
use App\Project;
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
        return view('dynamic.default.create', compact('project_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DynamicRequest $request)
    {
        $request->offsetSet('user_id', get_current_login_user_info());
        $request->offsetSet('status', 0);
        $dynamic = Dynamic::create($request->input());
        if ($dynamic) {
            activity('项目日志')->performedOn(Project::find($dynamic->project_id))
                ->withProperties($dynamic->toArray())
                ->log('发布动态');
            return _success('发布成功', $dynamic->toArray(), get_redirect_url());
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
        if ($dynamic) {
            return view('dynamic.default.edit', compact('dynamic'));
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
        if ($dynamic) {
            $dynamic->content = $request->input('content');
            $dynamic->onsite_user = $request->onsite_user;
            if ($dynamic->save()) {
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
        if ($dynamic->delete($id)) {
            activity('项目日志')->performedOn(Project::find($dynamic->project_id))
                ->withProperties($dynamic->toArray())
                ->log('删除动态');
            return _success('操作成功', $dynamic->toArray(), get_redirect_url());
        } else {
            return _error();
        }
    }
}
