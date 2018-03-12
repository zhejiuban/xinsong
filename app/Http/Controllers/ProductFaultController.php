<?php

namespace App\Http\Controllers;

use App\FaultCause;
use App\Http\Requests\ProductFaultRequest;
use App\ProductFault;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Facades\Agent;
use Maatwebsite\Excel\Facades\Excel;

class ProductFaultController extends Controller
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
                $fault_cause_id = $request->input('datatable.query.fault_cause_id');
                if (check_user_role(null, '总部管理员')) {
                    $role = ProductFault::with([
                        'project', 'user', 'faultCause'
                    ])->baseSearch($search, $date, $project_id, $fault_cause_id)->orderBy(
                        $sort_field
                        , $sort)->paginate(
                        $prepage
                        , ['*']
                        , 'datatable.pagination.page'
                    );
                } else {
                    $role = ProductFault::with([
                         'project', 'user', 'faultCause'
                    ])->baseSearch($search, $date, $project_id, $fault_cause_id)->companySearch()->orderBy(
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
            return view('produce.fault.index');
        } else {
            $search = $request->input('search');
            $project_id = $request->input('project_id');
            $date = $request->input('date');
            $fault_cause_id = $request->input('fault_cause_id');
            if (check_user_role(null, '总部管理员')) {
                $list = ProductFault::with([
                    'project', 'user', 'faultCause'
                ])->baseSearch($search, $date, $project_id, $fault_cause_id)->orderBy(
                    'id'
                    , 'desc')->paginate(config('common.page.per_page'));
            } else {
                $list = ProductFault::with([
                    'phase', 'project', 'user', 'device'
                ])->baseSearch($search, $date, $project_id, $fault_cause_id)->companySearch()->orderBy(
                    'id'
                    , 'desc')->paginate(config('common.page.per_page'));
            }
            set_redirect_url();
            return view('produce.fault.index_mobile', compact('list'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('produce.fault.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductFaultRequest $request)
    {
        $result = new ProductFault();
        $result->car_no = $request->car_no;
        $result->user_id = get_current_login_user_info();
        $result->project_id = $request->project_id;
        $result->fault_cause_id = $request->fault_cause_id;
        $result->occurrenced_at = $request->occurrenced_at;
        if ($result->save()) {
            //记录日志
            activity('项目日志')->performedOn(Project::find($result->project_id))
                ->withProperties($result)->log('添加陪产故障');
            return _success('添加成功'
                , $result->toArray()
                , get_redirect_url());
        } else {
            return _error('添加失败');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductFault  $productFault
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        $fault = ProductFault::with([
            'project', 'user', 'faultCause'
        ])->find($id);
        if ($fault) {
            if (!is_administrator()
                && $fault->user_id != get_current_login_user_info()
                && !check_project_owner($fault->project,'company')) {
                return _error('无权操作');
            }
            return view('produce.fault.edit', compact('fault'));
        } else {
            return _404();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductFault  $productFault
     * @return \Illuminate\Http\Response
     */
    public function update(ProductFaultRequest $request, $id)
    {
        $result = ProductFault::find($id);
        if(!$result){
            return _error();
        }
        if (!is_administrator()
            && $result->user_id != get_current_login_user_info()
            && !check_project_owner($result->project,'company')) {
            return _error('无权操作');
        }
        $result->car_no = $request->car_no;
        $result->project_id = $request->project_id;
        $result->fault_cause_id = $request->fault_cause_id;
        $result->occurrenced_at = $request->occurrenced_at;
        if ($result->save()) {
            //记录日志
            activity('项目日志')->performedOn($result->project)
                ->withProperties($result)->log('编辑陪产故障');
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
     * @param  \App\ProductFault  $productFault
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $malfunction  = ProductFault::find($id);
        if(!$malfunction){
            return _error();
        }
        if (!is_administrator()
            && $malfunction->user_id != get_current_login_user_info()
            && !check_project_owner($malfunction->project,'company')) {
            return _error('无权操作');
        }
        if ($malfunction->delete()) {
            //记录日志
            activity('项目日志')->performedOn($malfunction->project)
                ->withProperties($malfunction)->log('删除陪产故障');
            return _success('删除成功'
                , $malfunction->toArray()
                , get_redirect_url());
        } else {
            return _error('编辑失败');
        }
    }

    public function export(Request $request){
        if($request->isMethod('post')){
            $project = $request->input('project_id');
            $start_at = $request->input('start_at');
            $end_at = $request->input('end_at');
            //获取要导出的xm
            if(!$project && !is_administrator()){
                //获取各分部项目记录
                $projects = Project::companySearch(get_user_company_id())->get();
                $project = collect($projects)->pluck('id')->all();
            }
            //获取导出的开始时间和结束时间
            if(!$start_at){
                $start_at = ProductFault::min('occurrenced_at');
            }
            if(!$end_at){
                $end_at = ProductFault::max('occurrenced_at');
            }
            $date_list = getDateRange($start_at,$end_at);
            //获取所有故障现象座位表头
            $list = FaultCause::lists();
            $export = [
                ['日期']
            ];
            if($list){
                foreach ($list as $value){
                    $export[0][] = $value->name.'(次)';
                }
            }
            if ($date_list){
                foreach ($date_list as $k=>$v){
                    $data = [];
                    $data[] = $v;
                    if($list){
                        foreach ($list as $value){
                            if(!is_administrator()){
                                if(!$project){
                                    $data[] = 0;
                                }else{
                                    $data[] = ' '.ProductFault::whereDate('occurrenced_at', $v)
                                        ->where('fault_cause_id',$value->id)
                                        ->whereIn('project_id', (array)$project)->count();
                                }
                            }else{
                                $data[] = ' '.ProductFault::whereDate('occurrenced_at', $v)
                                    ->where('fault_cause_id',$value->id)
                                    ->when($project,function ($query) use ($project){
                                        $query->whereIn('project_id', (array)$project);
                                    })->count();
                            }
                        }
                    }
                    $export[] = $data;
                }
            }

            Excel::create(date('Y-m-d').'-陪产故障统计导出',function ($excel) use ($export){
                // Call them separately
                $excel->setDescription('陪产故障统计');

                $excel->sheet('陪产故障统计', function($sheet) use ($export) {
                    // Sheet manipulation
                    $sheet->fromArray($export, null, 'A1', false, false);
                });
            })->download('xlsx');


        }else{
            return view('produce.fault.export');
        }
    }
}
