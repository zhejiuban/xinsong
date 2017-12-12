<?php

namespace App\Http\Controllers;

use App\File;
use App\Http\Requests\ProjectRequest;
use App\Project;
use App\ProjectPhase;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!check_permission('project/projects')) {
            return _404('无权操作！');
        }

        if ($request->ajax()) {
            $sort_field = $request->input('datatable.sort.field')
                ? $request->input('datatable.sort.field') : 'id';
            $sort = $request->input('datatable.sort.sort')
                ? $request->input('datatable.sort.sort') : 'desc';
            $prepage = $request->input('datatable.pagination.perpage')
                ? (int)$request->input('datatable.pagination.perpage') : 20;
            $status = $request->input('datatable.query.status');
            $search = $request->input('datatable.query.search');
            //管理员或总部管理员获取所有项目
            if (check_user_role(null, '总部管理员')) {
                $project = Project::with([
                    'department', 'leaderUser', 'agentUser'
                ])->when($status, function ($query) use ($status) {
                    return $query->where('status', $status);
                }, function ($query) use ($status) {
                    if ($status !== null) {
                        return $query->where('status', $status);
                    }
                })->when($search, function ($query) use ($search) {
                    return $query->where(
                        'title', 'like',
                        "%{$search}%"
                    )->orWhere('no', 'like',
                        "%{$search}%");
                })->orderBy(
                    $sort_field
                    , $sort)->paginate(
                    $prepage
                    , ['*']
                    , 'datatable.pagination.page'
                );
            } elseif (check_company_admin()) {
                //分部管理员获取分部所有项目
                $project = Project::with([
                    'department', 'leaderUser', 'agentUser'
                ])->when($status, function ($query) use ($status) {
                    return $query->where('status', $status);
                }, function ($query) use ($status) {
                    if ($status !== null) {
                        return $query->where('status', $status);
                    }
                })->when($search, function ($query) use ($search) {
                    return $query->where(
                        'title', 'like',
                        "%{$search}%"
                    )->orWhere('no', 'like',
                        "%{$search}%");
                })->where('department_id', get_user_company_id())->orderBy(
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
                'page' => $project->currentPage(),
                'pages' => $project->hasMorePages(),
                'perpage' => $prepage,
                'total' => $project->total()
            ];
            $data = $project->toArray();
            $data['meta'] = $meta;
            return response()->json($data);
        }
        set_redirect_url();
        return view('project.default.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!check_permission('project/projects/create')) {
            return _404('无权操作！');
        }
        return view('project.default.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequest $request)
    {
        if (!check_permission('project/projects/create')) {
            return _404('无权操作！');
        }
        $project = new Project();
        $project->title = $request->title;
        $project->no = $request->no;
        $project->department_id = $request->department_id;
        $project->leader = $request->leader;
        $project->agent = $request->agent;
        $project->subcompany_leader = $request->subcompany_leader;
        $project->remark = $request->remark;
        $project->customers = $request->customers;
        $project->customers_tel = $request->customers_tel;
        $project->customers_address = $request->customers_address;

        if ($project->save()) {
            //关联写入项目参与人
            $p_user = (array)$request->project_user;
            array_push($p_user, $project->agent);
            $users = array_unique($p_user);
            $project->users()->attach($users);
            //关联写入设备类型信息
            $device = $request->device_project;
            if ($device) {
                $d_data = [];
                foreach ($device as $k => $v) {
                    $d_data[$v['device_id']] = [
                        'number' => $v['number']
                    ];
                }
                $project->devices()->attach($d_data);
            }
            //关联写入建设阶段信息
            $project->phases()->createMany($request->project_phases);
            //关联写入文件信息
            if ($files = $request->input('file_project')) {
                $project->files()->attach((array)$files);
            }
            //记录日志
            activity('项目日志')->performedOn($project)
                ->withProperties($project->toArray())
                ->log('项目添加成功');
            return _success('项目添加成功', null, get_redirect_url());
        } else {
            return _error('添加失败');
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
        dd('查看项目基本信息');
        if (!check_permission('project/projects/show')) {
            return _404('无权操作！');
        }
        $project = Project::find($id);
        if (!$project) {
            return _404();
        }
        //检测项目权限
        if (!check_project_owner($project, 'look')) {
            return _404('无权操作');
        }
        set_redirect_url();
        return view('project.default.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!check_permission('project/projects/edit')) {
            return _404('无权操作！');
        }
        $project = Project::with([
            'devices', 'phases', 'users', 'files', 'department', 'leaderUser', 'agentUser'
        ])->find($id);
        if (!$project) {
            return _404();
        }
        //检测项目权限
        if (!check_project_owner($project, 'edit')) {
            return _404('无权操作');
        }
        return view('project.default.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectRequest $request, $id)
    {
        if (!check_permission('project/projects/edit')) {
            return _404('无权操作！');
        }
        $project = Project::find($id);
        if ($project) {
            //检测项目权限
            if (!check_project_owner($project, 'edit')) {
                return _404('无权操作');
            }

            $project->title = $request->title;
            $project->no = $request->no;
            $project->department_id = $request->department_id;
            $project->leader = $request->leader;
            $project->agent = $request->agent;
            $project->remark = $request->remark;
            $project->customers = $request->customers;
            $project->customers_tel = $request->customers_tel;
            $project->customers_address = $request->customers_address;
            if ($project->save()) {
                //关联写入项目参与人
                $p_user = (array)$request->project_user;
                array_push($p_user, $project->leader);
                $users = array_unique($p_user);
                $project->users()->sync($users);
                //关联写入设备类型信息
                $device = $request->device_project;
                if ($device) {
                    $d_data = [];
                    foreach ($device as $k => $v) {
                        $d_data[$v['device_id']] = [
                            'number' => $v['number']
                        ];
                    }
                    $project->devices()->sync($d_data);
                }
                //关联写入建设阶段信息
                $phases = $request->project_phases;
                $phases_ids = collect($phases)->pluck('id')->toArray();

                $haved_phases = $project->phases()->get();//获取已有建设阶段
                $haved_phases_ids = collect($haved_phases)->pluck('id');

                $del_phases_id = $haved_phases_ids->diff($phases_ids)->all();

                if (!empty($del_phases_id)) {
                    //删除原有关联
                    $project->phases()->delete($del_phases_id);
                }
                $phases_add = [];
                //需要更新的阶段
                foreach ($phases as $ks => $phase) {
                    if (!isset($phase['id'])) {
                        $phases_add[] = $phase;
                    } else {
                        $up_id = $phase['id'];
                        unset($phase['id']);
                        $project->phases()->where('id', $up_id)->update($phase);
                    }
                }
                if (!empty($phases_add)) {
                    //增加新建设阶段
                    $project->phases()->createMany($phases_add);
                }
                //关联写入文件信息
                if ($files = $request->input('file_project')) {
                    $project->files()->sync((array)$files);
                }
                //记录日志
                activity('项目日志')->performedOn($project)
                    ->withProperties($project->toArray())
                    ->log('项目编辑成功');
                return _success('编辑成功', null, get_redirect_url());
            } else {
                return _error('编辑失败');
            }
        } else {
            return _error();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::find($id);
        //检测项目权限
        if (!check_project_owner($project, 'edit')) {
            return _404('无权操作');
        }
        if ($project->delete($id)) {
            activity('项目日志')->performedOn($project)
                ->withProperties($project)
                ->log('删除项目');
            return _success('操作成功', $project->toArray(),
                request('calendar') ?
                    route('projects.index', ['mid' => 'bd128edbfd250c9c5eff5396329011cd'])
                    : get_redirect_url());
        } else {
            return _error();
        }
    }
    /*public function startedAndFinished(Request $request, $id)
    {
        if (!check_permission('project/projects/start_or_finish')) {
            return _404('无权操作！');
        }
        $project = Project::find($id);
        if ($project) {
            if ($request->status == 2) {
                $project->status = 2;
            } else {
                $project->status = 1;
            }
            if ($project->save()) {
                activity('项目日志')->performedOn($project)
                    ->withProperties($project->toArray())
                    ->log($project->status == 2 ? '项目设置已完成' : '启动项目');
                return _success('操作成功', $project, get_redirect_url());
            } else {
                return _error('操作失败');
            }
        } else {
            return _error();
        }
    }*/

    //获取某个项目的所有任务
    public function tasks(Request $request, $id)
    {
        if ($project = Project::find($id)) {
            $tasks = $project->tasks()->orderBy('id', 'desc')->paginate(config('common.page.per_page'));
            set_redirect_url();
            return view('project.default.task', compact(['project', 'tasks']));
        } else {
            return _404();
        }
    }

    //获取某个项目动态
    public function dynamics(Request $request, $id)
    {
        if ($project = Project::find($id)) {
            $dynamics = $project->dynamics()->orderBy('id', 'desc')->paginate(config('common.page.per_page'));
            set_redirect_url();
            return view('project.default.dynamic', compact(['project', 'dynamics']));
        } else {
            return _404();
        }
    }

    //获取某个项目相关问题
    public function questions(Request $request, $id)
    {
        if ($project = Project::find($id)) {
            $questions = $project->questions()->orderBy('id', 'desc')->paginate(config('common.page.per_page'));
            set_redirect_url();
            return view('project.default.question', compact(['project', 'questions']));
        } else {
            return _404();
        }
    }

    /**
     * 获取项目所有文档
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function files(Request $request, $id)
    {
        if ($project = Project::find($id)) {
            $files = $project->files()->orderBy('id', 'desc')->paginate(config('common.page.per_page'));
            set_redirect_url();
            return view('project.default.file', compact(['project', 'files']));
        } else {
            return _404();
        }
    }

    /**
     * 项目文档上传
     */
    public function filesCreate(Request $request, $id)
    {
        $project = Project::find($id);
        if ($project) {
            if ($request->isMethod('post')) {
                $this->validate($request, [
                    'file_project' => 'required'
                ], [
                    'file_project.required' => '请选择上传文件'
                ]);
                $file = $request->input('file_project');
                if ($project->files()->attach($file) !== false) {
                    activity('项目日志')->performedOn($project)
                        ->withProperties($file)
                        ->log('上传文档');
                    return _success('上传成功', $request->file_project, get_redirect_url());
                } else {
                    return _error('操作失败');
                }
            } else {
                return view('project.default.file_create', compact('project'));
            }
        } else {
            return _404();
        }
    }

    public function filesDestroy($id, $file)
    {
        $project = Project::find($id);
        if ($project) {
            if ($project->files()->detach($file) !== false) {
                $files = File::find($file);
                activity('项目日志')->performedOn($project)
                    ->withProperties($files->toArray())
                    ->log('删除文档:' . $files->old_name);
                return _success('操作成功', $files->toArray(), get_redirect_url());
            } else {
                return _error('操作失败');
            }
        } else {
            return _404();
        }
    }

    //阶段状态修改
    public function phaseUpdate(Request $request, $id)
    {
        if (!check_permission('project/phases/edit')) {
            return _404('无权操作！');
        }
        $phase = ProjectPhase::find($id);
        $project = $phase->project;
        //检测项目权限
        if (!check_project_owner($project, 'edit')) {
            return _404('无权操作');
        }
        if ($phase) {
            if ($request->isMethod('put')) {
                $phase->started_at = $request->started_at;
                $phase->finished_at = $request->finished_at;
                $phase->status = $request->status;
                if($phase->save()){
                    activity('项目日志')->performedOn($project)
                        ->withProperties($phase)
                        ->log('编辑项目状态');
                    return _success('操作成功',$phase->toArray(),get_redirect_url());
                }else{
                    return _error('操作失败');
                }
            } else {
                return view('project.phase.edit', compact('phase'));
            }
        } else {
            return _404();
        }
    }
}
