<?php

namespace App\Http\Controllers;

use App\Device;
use App\Http\Requests\DeviceRequest;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!check_permission('project/devices')) {
            return _404('无权操作！');
        }
        if ($request->ajax()) {
            $sort_field = $request->input('datatable.sort.field')
                ? $request->input('datatable.sort.field') : 'id';
            $sort = $request->input('datatable.sort.sort')
                ? $request->input('datatable.sort.sort') : 'desc';
            $prepage = $request->input('datatable.pagination.perpage')
                ? (int)$request->input('datatable.pagination.perpage') : 20;
            $role = Device::where(
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
        return view('project.device.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!check_permission('project/devices/create')) {
            return _404('无权操作！');
        }
        return view('project.device.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(DeviceRequest $request)
    {
        if (!check_permission('project/devices/create')) {
            return _404('无权操作！');
        }
        $result = Device::create([
            'name' => $request->name,
            'remark' => $request->remark,
            'status' => $request->status ? 1 : 0
        ]);
        if ($result) {
            return response()->json([
                'message' => '添加成功'
                , 'status' => 'success'
                , 'data' => $result->toArray()
                , 'url' => route('devices.index')
            ]);
        } else {
            return response()->json([
                'message' => '添加失败'
                , 'status' => 'error'
                , 'data' => null
                , 'url' => null
            ]);
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
        if (!check_permission('project/devices/edit')) {
            return _404('无权操作！');
        }
        $device = Device::find($id);
        if ($device) {
            return view('project.device.edit', ['device' => $device]);
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
    public function update(DeviceRequest $request, $id)
    {
        if (!check_permission('project/devices/edit')) {
            return _404('无权操作！');
        }
        $role = Device::find($id);
        if (!$role) {
            return response()->json([
                'status' => 'error', 'message' => '你访问信息不存在',
                'data' => null, 'url' => ''
            ]);
        } else {
            $role->name = $request->name;
            $role->status = $request->status ? 1 : 0;
            $role->remark = $request->remark;
            $role->updated_at = date('Y-m-d H:i:s');
            if ($role->save()) {
                return response()->json([
                    'status' => 'success', 'message' => '编辑成功',
                    'data' => $role->toArray(), 'url' => route('devices.index')
                ]);
            } else {
                return response()->json([
                    'status' => 'error', 'message' => '保存失败',
                    'data' => null, 'url' => ''
                ]);
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
        if (!check_permission('project/devices/destroy')) {
            return _404('无权操作！');
        }
        $result = [
            'status' => 'success',
            'message' => '操作成功',
            'data' => '',
            'url' => '',
        ];
        $dp = Device::find($id);
        if ($dp) {
            $flag = 1;
            //判断是否有关联项目

            //删除
            if ($flag && !$dp->delete()) {
                $result['status'] = 'error';
                $result['message'] = '删除失败';
            }
        } else {
            $result['status'] = 'error';
            $result['message'] = '您操作的信息不存在';
        }
        return response()->json($result);
    }
}
