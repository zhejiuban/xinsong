<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlanRequest;
use App\Plan;
use App\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    protected $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($project, Request $request)
    {
        $project = $this->project->find($project);

        if ($request->ajax()) {
            $sort_field = $request->input('datatable.sort.field')
                ? $request->input('datatable.sort.field') : 'sort';
            $sort = $request->input('datatable.sort.sort')
                ? $request->input('datatable.sort.sort') : 'asc';
            $prepage = $request->input('datatable.pagination.perpage')
                ? (int)$request->input('datatable.pagination.perpage') : 100;
            $date = $request->input('datatable.query.date');
            $search = $request->input('datatable.query.search');
            //管理员或总部管理员获取所有
            $list = $project->plans()->with('user')
                ->when($search, function ($query) use ($search) {
                    return $query->where(function ($query) use ($search) {
                        $query->where(
                            'content', 'like',
                            "%{$search}%"
                        );
                    });
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
        } else {
            return view('project.plan.index', compact('project'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($project)
    {
        $project = $this->project->find($project);
        if (!check_project_leader($project, 1)) {
            return _404('无权操作');
        }
        return view('project.plan.create', compact('project'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store($project, PlanRequest $request)
    {
        $project = $this->project->find($project);
        if (!$project) {
            return _error('参数有误');
        }
        if (!check_project_leader($project, 1)) {
            return _404('无权操作');
        }
        $data = $request->input();
        $last_finished_at = $request->input('last_finished_at');
        $finished_at = $request->input('finished_at');
        if ($finished_at && $last_finished_at) {
            if (Carbon::parse($last_finished_at)
                ->gt(Carbon::parse($finished_at))) {
                $data['is_finished'] = 0;
            } else {
                $data['is_finished'] = 1;
            }
        }
        $plan = Plan::create($data);
        if ($plan) {
            activity('项目日志')->performedOn($plan)
                ->withProperties($plan)->log('添加项目实施计划');
            return _success();
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($project, $id)
    {
        $project = $this->project->find($project);
        if (!check_project_leader($project, 1)) {
            return _404('无权操作');
        }
        $plan = Plan::find($id);
        if ($plan) {
            return view('project.plan.edit', compact(['plan', 'project']));
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
    public function update(PlanRequest $request, $project, $id)
    {
        $project = $this->project->find($project);
        if (!check_project_leader($project, 1)) {
            return _404('无权操作');
        }
        $plan = Plan::find($id);
        $data = $request->input();
        $last_finished_at = $request->input('last_finished_at');
        $finished_at = $request->input('finished_at');
        if ($finished_at && $last_finished_at) {
            if (Carbon::parse($last_finished_at)
                ->gt(Carbon::parse($finished_at))) {
                $data['is_finished'] = 0;
            } else {
                $data['is_finished'] = 1;
                $data['reason'] = null;
            }
        } else {
            $data['is_finished'] = null;
            $data['reason'] = null;
        }
        if ($plan->update($data)) {
            activity('项目日志')->performedOn($project)
                ->withProperties($plan->toArray())
                ->log('更新计划');
            return _success('操作成功', $plan->toArray());
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
    public function destroy($project, $id)
    {
        $project = $this->project->find($project);
        $plan = Plan::find($id);
        //判断计划删除权限
        if (!check_project_leader($project, 1)) {
            return _404('无权操作');
        }
        if ($plan->delete($id)) {
            activity('项目日志')->performedOn($project)
                ->withProperties($plan->toArray())
                ->log('删除计划');
            return _success('操作成功', $plan->toArray());
        } else {
            return _error();
        }
    }

    public function batchDelete(Request $request, $project)
    {
        //判断计划删除权限
        if (!check_project_leader($this->project->find($project), 1)) {
            return _404('无权操作');
        }

        $id = $request->input('id');
        if (count($id) < 1 || !current($id)) {
            return _404('请选择要操作的数据');
        }
        //删除操作
        $res = Plan::whereIn('id', $id)->delete();

        if ($res) {
            activity('项目日志')->withProperties($id)->log('删除计划');
            return response()->json([
                'message' => '删除成功', 'data' => $id,
                'status' => 'success', 'url' => null
            ]);
        } else {
            return _error('删除失败');
        }
    }

    public function import(Request $request, $project)
    {
        //判断计划删除权限
        if (!check_project_leader($project = $this->project->find($project), 1)) {
            return _404('无权操作');
        }
        if ($request->isMethod('post')) {
            $temp = config('common.project_plan_temp');
            $temp_id = $request->input('temp_cate');
            if (isset($temp[$temp_id])) {
                $list = $temp[$temp_id]['list'];
                foreach ($list as $key => $value) {
                    Plan::create([
                        'sort' => $key + 1,
                        'content' => $value,
                        'project_id' => $project->id
                    ]);
                }
                return _success();
            } else {
                return _error('导入模板不存在');
            }
        } else {
            return view('project.plan.import', compact('project'));
        }
    }
}
