<?php

namespace App\Http\Controllers;

use App\FaultCause;
use App\Http\Requests\FaultCauseRequest;
use Illuminate\Http\Request;

class FaultCauseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!check_permission('produce/fault_causes')) {
            return _404('无权操作！');
        }
        if ($request->ajax()) {
            $sort_field = $request->input('datatable.sort.field')
                ? $request->input('datatable.sort.field') : 'id';
            $sort = $request->input('datatable.sort.sort')
                ? $request->input('datatable.sort.sort') : 'desc';
            $prepage = $request->input('datatable.pagination.perpage')
                ? (int)$request->input('datatable.pagination.perpage') : 20;
            $role = FaultCause::where(
                'name', 'like',
                "%{$request->input('datatable.query.search')}%"
            )->orderBy(
                $sort_field
                , $sort)->paginate(
                $prepage
                , ['*']
                , 'datatable.pagination.page'
            );
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
        return view('produce.cause.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!check_permission('produce/fault_causes/create')) {
            return _404('无权操作！');
        }
        return view('produce.cause.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(FaultCauseRequest $request)
    {
        if (!check_permission('produce/fault_causes/create')) {
            return _404('无权操作！');
        }
        $result = FaultCause::create([
            'name' => $request->name,
            'status' => $request->status ? 1 : 0
        ]);
        if ($result) {
            //记录日志
            activity()->withProperties($result->toArray())->log('故障现象添加成功');
            return _success('添加成功', $result->toArray(), get_redirect_url());
        } else {
            return _error();
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
        if (!check_permission('produce/fault_causes/edit')) {
            return _404('无权操作！');
        }
        $cause = FaultCause::find($id);
        if ($cause) {
            return view('produce.cause.edit', ['cause' => $cause]);
        } else {
            return _404('你访问的信息不存在');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(FaultCauseRequest $request, $id)
    {
        if (!check_permission('produce/fault_causes/edit')) {
            return _404('无权操作！');
        }
        $role = FaultCause::find($id);
        if (!$role) {
            return _404();
        } else {
            $role->name = $request->name;
            $role->status = $request->status ? 1 : 0;
//            $role->updated_at = date('Y-m-d H:i:s');
            if ($role->save()) {
                activity()->performedOn($role)
                    ->withProperties($role->toArray())->log('故障现象编辑成功');
                return _success('操作成功', $role->toArray(), get_redirect_url());
            } else {
                return _error();
            }
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
        if (!check_permission('produce/fault_causes/destroy')) {
            return _404('无权操作！');
        }
        $result = [
            'status' => 'success',
            'message' => '操作成功',
            'data' => '',
            'url' => get_redirect_url(),
        ];
        $dp = FaultCause::find($id);
        if ($dp) {
            $flag = 1;
            //判断是否有关联项目
            if (count($dp->productFaults)) {
                $flag = 0;
                $result['status'] = 'error';
                $result['message'] = '存在关联故障记录，不能删除';
            }
            //删除
            if ($flag) {
                if ($dp->delete()) {
                    activity()->performedOn($dp)
                        ->withProperties($dp->toArray())->log('故障现象删除成功');
                } else {
                    $result['status'] = 'error';
                    $result['message'] = '删除失败';
                }
            }
        } else {
            $result['status'] = 'error';
            $result['message'] = '您操作的信息不存在';
        }
        return response()->json($result);
    }
}
