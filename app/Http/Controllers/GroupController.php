<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Permission;
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
        if (!check_permission('user/groups')) {
            return _404('无权操作！');
        }
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
        set_redirect_url();
        return view('user.group.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!check_permission('user/groups/create')) {
            return _404('无权操作！');
        }
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
        if (!check_permission('user/groups/create')) {
            return _404('无权操作！');
        }
        $result = Role::create([
            'name' => $request->name,
            'remark' => $request->remark,
            'is_system' => 0,
            'is_call' => $request->is_call
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
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!check_permission('user/groups/edit')) {
            return _404('无权操作！');
        }
        $role = Role::find($id);
        if ($role) {
            return view('user.group.edit', ['role' => $role]);
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
    public function update(RoleRequest $request, $id)
    {
        if (!check_permission('user/groups/edit')) {
            return _404('无权操作！');
        }
        $role = Role::find($id);
        if (!$role) {
            return response()->json([
                'status' => 'error', 'message' => '你访问信息不存在',
                'data' => null, 'url' => ''
            ]);
        } else {
            if (!$role->is_system) {
                $role->name = $request->name;
            }
            $role->is_call = $request->is_call;
            $role->remark = $request->remark;
            $role->updated_at = date('Y-m-d H:i:s');
            if ($role->save()) {
                return response()->json([
                    'status' => 'success', 'message' => '编辑成功',
                    'data' => $role->toArray(), 'url' => route('groups.index')
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
        if (!check_permission('user/groups/destroy')) {
            return _404('无权操作！');
        }
        $result = [
            'status' => 'success',
            'message' => '操作成功',
            'data' => '',
            'url' => '',
        ];
        $dp = Role::find($id);
        if ($dp) {
            $flag = 1;
            //判断是不是系统角色
            if ($dp->is_system) {
                $flag = 0;
                $result['status'] = 'error';
                $result['message'] = '系统角色不允许删除';
            }
            //判断角色是否有关联用户

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

    public function power(Request $request, $id)
    {
        if (!check_permission('user/groups/power')) {
            return _404('无权操作！');
        }
        $role = Role::find($id);
        if ($request->method() == 'POST') {
            if ($role) {
                if ($result = $role->syncPermissions($request->permission)) {
                    return response()->json([
                        'status' => 'success', 'message' => '授权成功',
                        'data' => $role->toArray(), 'url' => get_redirect_url()
                    ]);
                } else {
                    return _404('授权失败');
                }
            } else {
                return _404('您操作的信息不存在');
            }
        }
        if ($role) {
            //获取节点数据
            $node_list = Menu::nodes(false);
            $all_rules = Permission::select('name', 'id')->get()->toArray();
            $rules = [];
            if ($all_rules) {
                foreach ($all_rules as $k => $v) {
                    $rules[$v['name']] = $v['id'];
                }
            }
            if ($node_list) {
                foreach ($node_list as $k => $v) {
                    $node_list[$k]['rules'] = isset($rules[$v['url']]) ? $rules[$v['url']] : '';
                    $node_list[$k]['permission'] = $v['url'];
                    unset($node_list[$k]['url']);
                }
            }
            $haved = [];
            foreach ($role->permissions()->get() as $per) {
                $haved[] = $per->id;
            }

            return view('user.group.power', [
                'role' => $role, 'haved' => $haved, 'auth_rules' => $all_rules, 'node_list' => $node_list
            ]);
        } else {
            return _404('你访问信息不存在');
        }
    }
}
