<?php

namespace App\Http\Controllers;

use App\PaternityRecord;
use App\Project;
use Illuminate\Http\Request;
use Jenssegers\Agent\Facades\Agent;

class PaternityRecordController extends Controller
{
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
                $is_handle = $request->input('datatable.query.is_handle');
                if (check_user_role(null, '总部管理员')) {
                    $role = PaternityRecord::with([
                        'project', 'user'
                    ])->baseSearch($search, $date, $project_id, $is_handle)->orderBy(
                        $sort_field
                        , $sort)->paginate(
                        $prepage
                        , ['*']
                        , 'datatable.pagination.page'
                    );
                } else {
                    $role = PaternityRecord::with([
                        'project', 'user'
                    ])->baseSearch($search, $date, $project_id, $is_handle)->companySearch()->orderBy(
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
            return view('paternity_record.default.index');
        } else {
            $search = $request->input('search');
            $project_id = $request->input('project_id');
            $date = $request->input('date');
            $is_handle = $request->input('is_handle');
            if (check_user_role(null, '总部管理员')) {
                $list = PaternityRecord::with([
                    'project', 'user'
                ])->baseSearch($search, $date, $project_id, $is_handle)->orderBy(
                    'id'
                    , 'desc')->paginate(config('common.page.per_page'));
            } else {
                $list = PaternityRecord::with([
                    'project', 'user'
                ])->baseSearch($search, $date, $project_id, $is_handle)->companySearch()->orderBy(
                    'id'
                    , 'desc')->paginate(config('common.page.per_page'));
            }
            set_redirect_url();
            return view('paternity_record.default.index_mobile', compact('list'));
        }
    }

    public function create()
    {
        if(request()->ajax()){
            return view('paternity_record.default.create_ajax');
        }
        return view('paternity_record.default.create');
    }

    public function store(Request $request)
    {
        $result = new PaternityRecord();
        $result->car_no = $request->car_no;
        $result->user_id = get_current_login_user_info();
        $result->project_id = $request->project_id;
        $result->is_handle = $request->is_handle;
        $result->closed_at = $request->closed_at;
        $result->question = $request->input('question');
        $result->solution = $request->solution;
        $result->remark = $request->remark;
        if ($result->save()) {
            //记录日志
            activity('项目日志')->performedOn(Project::find($result->project_id))
                ->withProperties($result)->log('添加陪产记录');
            //接收人消息提醒

            return _success('添加成功'
                , $result->toArray()
                , get_redirect_url());
        } else {
            return _error('添加失败');
        }
    }

    public function edit(Request $request,$id)
    {
        $malfunction = PaternityRecord::with([
            'project', 'user'
        ])->find($id);
        if ($malfunction) {
            if (!is_administrator()
                && $malfunction->user_id != get_current_login_user_info()
                && !check_project_owner($malfunction->project,'company')) {
                return _error('无权操作');
            }
            if($request->ajax()){
                return view('paternity_record.default.edit_ajax', compact('malfunction'));
            }else{
                return view('paternity_record.default.edit', compact('malfunction'));
            }
        } else {
            return _404();
        }
    }

    public function update(Request $request, $id)
    {
        $result = PaternityRecord::find($id);
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
        $result->is_handle = $request->is_handle;
        $result->closed_at = $request->closed_at;
        $result->question = $request->input('question');
        $result->solution = $request->solution;
        $result->remark = $request->remark;
        if ($result->save()) {
            //记录日志
            activity('项目日志')->performedOn($result->project)
                ->withProperties($result)->log('编辑陪产记录');
            return _success('编辑成功'
                , $result->toArray()
                , get_redirect_url());
        } else {
            return _error('编辑失败');
        }
    }

    public function show($id)
    {
        $malfunction = PaternityRecord::with([
            'project', 'user'
        ])->find($id);

        if ($malfunction) {
            return view('paternity_record.default.show', compact('malfunction'));
        } else {
            return _404();
        }
    }

    public function destroy($id)
    {
        $malfunction  = PaternityRecord::find($id);
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
                ->withProperties($malfunction)->log('删除陪产记录');
            return _success('删除成功'
                , $malfunction->toArray()
                , get_redirect_url());
        } else {
            return _error('删除失败');
        }
    }

}
