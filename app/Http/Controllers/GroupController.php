<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $sort_field = $request->input('datatable.sort.field')
                ? $request->input('datatable.sort.field') : 'id';
            $sort = $request->input('datatable.sort.sort')
                ? $request->input('datatable.sort.sort') : 'desc';
            $prepage = $request->input('datatable.pagination.perpage')
                ? (int)$request->input('datatable.pagination.perpage') : 20;
            $role = Role::where(
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
        return view('user.group.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.group.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        $result = Role::create([
            'name' => $request->name,
            'remark' => $request->remark,
            'is_system' => 0
        ]);
        if ($result) {
            return response()->json([
                'message' => '添加成功'
                , 'status' => 'success'
                , 'data' => $result->toArray()
                , 'url' => route('groups.index')
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
    public function edit($id)
    {
        $role = Role::find($id);
        if ($role) {
            return view('user.group.edit', ['role' => $role]);
        } else {
            return view('layouts._error', [
                'message'=>'你访问信息不存在'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $id)
    {
        $role = Role::find($id);
        if(!$role){
            return response()->json([
                'status'=>'error','message'=>'你访问信息不存在',
                'data'=>null,'url'=>''
            ]);
        }else{
            $role->name = $request->name;
            $role->remark = $request->remark;
            $role->updated_at = date('Y-m-d H:i:s');
            if($role->save()){
                return response()->json([
                    'status'=>'success','message'=>'编辑成功',
                    'data'=>$role->toArray(),'url'=>route('groups.index')
                ]);
            }else{
                return response()->json([
                    'status'=>'error','message'=>'保存失败',
                    'data'=>null,'url'=>''
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
        $result = [
            'status' => 'success',
            'message' => '操作成功',
            'data' => '',
            'url' => '',
        ];
        $dp = Role::find($id);
        if ($dp) {
            $flag = 1;
            //判断角色是否有关联用户
            //判断角色是否有管理权限
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
