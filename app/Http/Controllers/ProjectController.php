<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            //管理员获取获取所有项目
            //分部管理员获取分部所有项目
            $project = Project::with([
                'department', 'leaderUser', 'agentUser'
            ])->where(
                'title', 'like',
                "%{$request->input('datatable.query.search')}%"
            )->whereOr('no', 'like',
                "%{$request->input('datatable.query.search')}%")
                ->orderBy(
                $sort_field
                , $sort)->paginate(
                $prepage
                , ['*']
                , 'datatable.pagination.page'
            );
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
     * @param  \Illuminate\Http\Request  $request
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
        $project->remark = $request->remark;
        $project->customers = $request->customers;
        $project->customers_tel = $request->customers_tel;
        $project->customers_address = $request->customers_address;

        if($project->save()){
            //关联写入项目参与人
            $p_user = (array) $request->project_user;
            array_push($p_user,$project->leader);
            $users = array_unique($p_user);
            $project->users()->attach($users);
            //关联写入设备类型信息
            $device = $request->device_project;
            if($device){
                $d_data = [];
                foreach ($device as $k=>$v){
                    $d_data[$v['device_id']] = [
                        'number'=>$v['number']
                    ];
                }
                $project->devices()->attach($d_data);
            }
            //关联写入建设阶段信息
            $project->phases()->createMany($request->project_phases);
            //关联写入文件信息
            if($files = $request->input('file_project')){
                $project->files()->attach((array)$files);
            }
            //记录日志
            activity()->performedOn($project)
                ->withProperties($project->toArray())
                ->log('项目添加成功');
            return _success('项目添加成功',null,get_redirect_url());
        }else{
            return _error('添加失败');
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
        return view('project.default.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!check_permission('project/projects/edit')) {
            return _404('无权操作！');
        }
        $project = Project::with([
            'devices','phases','users','files','department','leaderUser','agentUser'
        ])->find($id);
//        dump($project);
        if(!$project){ return _404(); }
        return view('project.default.edit',compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectRequest $request, $id)
    {
        if (!check_permission('project/projects/edit')) {
            return _404('无权操作！');
        }
        $project = Project::find($id);
        if($project){
            $project->title = $request->title;
            $project->no = $request->no;
            $project->department_id = $request->department_id;
            $project->leader = $request->leader;
            $project->agent = $request->agent;
            $project->remark = $request->remark;
            $project->customers = $request->customers;
            $project->customers_tel = $request->customers_tel;
            $project->customers_address = $request->customers_address;
            if($project->save()){
                //关联写入项目参与人
                $p_user = (array) $request->project_user;
                array_push($p_user,$project->leader);
                $users = array_unique($p_user);
                $project->users()->sync($users);
                //关联写入设备类型信息
                $device = $request->device_project;
                if($device){
                    $d_data = [];
                    foreach ($device as $k=>$v){
                        $d_data[$v['device_id']] = [
                            'number'=>$v['number']
                        ];
                    }
                    $project->devices()->sync($d_data);
                }
                //关联写入建设阶段信息
                $phases = $request->project_phases;
                $phases_ids = collect($phases)->pluck('id')->toArray();

                $haved_phases =  $project->phases()->get();//获取已有建设阶段
                $haved_phases_ids = collect($haved_phases)->pluck('id');

                $del_phases_id = $haved_phases_ids->diff($phases_ids)->all();

                if(!empty($del_phases_id)){
                    //删除原有关联
                    $project->phases()->delete($del_phases_id);
                }
                $phases_add = [];
                //需要更新的阶段
                foreach ($phases as $ks=>$phase){
                    if(!isset($phase['id'])){
                        $phases_add[] = $phase;
                    }else{
                        $up_id = $phase['id'];
                        unset($phase['id']);
                        $project->phases()->where('id',$up_id)->update($phase);
                    }
                }
                if(!empty($phases_add)){
                    //增加新建设阶段
                    $project->phases()->createMany($phases_add);
                }
                //关联写入文件信息
                if($files = $request->input('file_project')){
                    $project->files()->sync((array)$files);
                }
                //记录日志
                activity()->performedOn($project)
                    ->withProperties($project->toArray())
                    ->log('项目编辑成功');
                return _success('编辑成功',null,get_redirect_url());
            }else{
                return _error('编辑失败');
            }
        }else{
            return _error();
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
        //
    }
}
