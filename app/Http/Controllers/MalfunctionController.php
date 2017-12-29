<?php

namespace App\Http\Controllers;

use App\Http\Requests\MalfunctionRequest;
use App\Malfunction;
use App\Project;
use Illuminate\Http\Request;
use Jenssegers\Agent\Facades\Agent;

class MalfunctionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Agent::isMobile()) {
            if ($request->ajax()) {
                $sort_field = $request->input('datatable.sort.field')
                    ? $request->input('datatable.sort.field') : 'id';
                $sort = $request->input('datatable.sort.sort')
                    ? $request->input('datatable.sort.sort') : 'desc';
                $prepage = $request->input('datatable.pagination.perpage')
                    ? (int)$request->input('datatable.pagination.perpage') : 20;
                $search = $request->input('datatable.query.search');
                $project_id = $request->input('datatable.query.project_id');
                $date = $request->input('datatable.query.date');
                $device_id = $request->input('datatable.query.device_id');
                if (check_user_role(null, '总部管理员')) {
                    $role = Malfunction::with([
                        'phase', 'project', 'user', 'device'
                    ])->baseSearch($search, $date, $project_id, $device_id)->orderBy(
                        $sort_field
                        , $sort)->paginate(
                        $prepage
                        , ['*']
                        , 'datatable.pagination.page'
                    );
                } else {
                    $role = Malfunction::with([
                        'phase', 'project', 'user', 'device'
                    ])->baseSearch($search, $date, $project_id, $device_id)->companySearch()->orderBy(
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
                    'page' => $role->currentPage(),
                    'pages' => $role->hasMorePages(),
                    'perpage' => $prepage,
                    'total' => $role->total()
                ];
                $data = $role->toArray();
                $data['meta'] = $meta;
                return response()->json($data);
            }
            set_redirect_url();
            return view('malfunction.default.index');
        } else {
            $search = $request->input('search');
            $project_id = $request->input('project_id');
            $date = $request->input('date');
            $device_id = $request->input('device_id');
            if (check_user_role(null, '总部管理员')) {
                $list = Malfunction::with([
                    'phase', 'project', 'user', 'device'
                ])->baseSearch($search, $date, $project_id, $device_id)->orderBy(
                    'id'
                    , 'desc')->paginate(config('common.page.per_page'));
            } else {
                $list = Malfunction::with([
                    'phase', 'project', 'user', 'device'
                ])->baseSearch($search, $date, $project_id, $device_id)->companyQuestion()->orderBy(
                    'id'
                    , 'desc')->paginate(config('common.page.per_page'));
            }
            set_redirect_url();
            return view('malfunction.default.default.index_mobile', compact('list'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('malfunction.default.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(MalfunctionRequest $request)
    {
        $result = new Malfunction();
        $result->car_no = $request->car_no;
        $result->user_id = get_current_login_user_info();
        $result->project_id = $request->project_id;
        $result->project_phase_id = $request->project_phase_id;
        $result->device_id = $request->device_id;
        $result->handled_at = $request->handled_at;
        $result->content = $request->input('content');
        $result->reason = $request->reason;
        $result->result = $request->result;
        if ($result->save()) {
            //记录日志
            activity('项目日志')->performedOn(Project::find($result->project_id))
                ->withProperties($result)->log('添加故障');
            //接收人消息提醒

            return _success('添加成功'
                , $result->toArray()
                , get_redirect_url());
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
        $malfunction = Malfunction::with([
            'phase', 'project', 'user', 'device'
        ])->find($id);

        if ($malfunction) {
            return view('malfunction.default.show', compact('malfunction'));
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
    public function edit(Request $request,$id)
    {
        $malfunction = Malfunction::with([
            'phase', 'project', 'user', 'device'
        ])->find($id);
        if ($malfunction) {
            if (!is_administrator()
                && $malfunction->user_id != get_current_login_user_info()) {
                return _error('无权操作');
            }
            if($request->ajax()){
                return view('malfunction.default.edit_ajax', compact('malfunction'));
            }else{
                return view('malfunction.default.edit', compact('malfunction'));
            }
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
    public function update(MalfunctionRequest $request, $id)
    {
        $result = Malfunction::find($id);
        if(!$result){
            return _error();
        }
        if (!is_administrator()
            && $result->user_id != get_current_login_user_info()) {
            return _error('无权操作');
        }
        $result->car_no = $request->car_no;
        $result->project_id = $request->project_id;
        $result->project_phase_id = $request->project_phase_id;
        $result->device_id = $request->device_id;
        $result->handled_at = $request->handled_at;
        $result->content = $request->input('content');
        $result->reason = $request->reason;
        $result->result = $request->result;
        if ($result->save()) {
            //记录日志
            activity('项目日志')->performedOn($result->project)
                ->withProperties($result)->log('编辑故障');
            return _success('编辑成功'
                , $result->toArray()
                , get_redirect_url());
        } else {
            return _error('编辑失败');
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
        //
    }
}
