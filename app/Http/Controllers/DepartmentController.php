<?php

namespace App\Http\Controllers;

use App\Department;
use App\Http\Requests\CompanyRequest;
use App\Http\Requests\DepartmentRequest;
use App\User;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * 所有组织机构信息
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if (!check_permission('user/departments')) {
            return _404('无权操作');
        }
        //获取部门信息
        if (is_administrator()) {
            $menu = Department::get()->toArray();
            $list = formatTreeData($menu);
        } else {
            //获取所属分部
            $company_id = get_user_company_id();
            //获取分部所有部门信息
            $menu = Department::where(
                'company_id', $company_id
            )->orWhere('id', $company_id)->get()->toArray();
            $list = formatTreeData($menu, 'id', 'parent_id');
        }
        set_redirect_url();
        return view('user.department.index', compact('list'));
    }

    /**
     * 分部添加
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        if (!check_permission('user/departments/create')) {
            return _404('无权操作');
        }
        return view('user.department.create');
    }

    /**
     * 分部新增
     * @param CompanyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CompanyRequest $request)
    {
        if (!check_permission('user/departments/create')) {
            return _404('无权操作');
        }
        $menu = new Department();
        $menu->parent_id = 0;
        $menu->name = $request->name;
        $menu->status = $request->status;
        $menu->sort = $request->sort ? intval($request->sort) : 0;
        $menu->remark = $request->remark ? $request->remark : '';
        $menu->level = 2;
        $menu->company_id = 0;
        if ($menu->save()) {
            //权限同步
            activity('系统日志')->performedOn($menu)
                ->withProperties($menu)->log('添加分部信息');
            return response()->json([
                'message' => '添加成功', 'url' => route('departments.index'),
                'data' => $menu->toArray(), 'status' => 'success'
            ]);
        } else {
            return response()->json([
                'message' => '保存失败', 'url' => null,
                'data' => null, 'status' => 'error'
            ]);
        }
    }

    //分部编辑
    public function edit($id)
    {
        if (!check_permission('user/departments/edit')) {
            return _404('无权操作');
        }
        $menu = Department::find($id);
        if ($menu) {
            return view('user.department.edit', compact('menu'));
        } else {
            return _404('你访问的页面不存在');
        }
    }

    //分部编辑更新
    public function update(CompanyRequest $request, $id)
    {
        if (!check_permission('user/departments/edit')) {
            return _404('无权操作');
        }
        $menu = Department::find($id);
        if ($menu) {
            $headquarters = headquarters('id');
            if ($menu->id != $headquarters) {
                $menu->status = $request->status;
            }
            $menu->name = $request->name;
            $menu->sort = $request->sort ? intval($request->sort) : 0;
            $menu->remark = $request->remark ? $request->remark : '';
            if ($menu->save()) {
                activity('系统日志')->performedOn($menu)
                    ->withProperties($menu)->log('编辑分部信息');
                return response()->json([
                    'message' => '编辑成功', 'url' => route('departments.index'),
                    'data' => $menu->toArray(), 'status' => 'success'
                ]);
            } else {
                return response()->json([
                    'message' => '保存失败', 'url' => null,
                    'data' => null, 'status' => 'error'
                ]);
            }
        } else {
            return response()->json([
                'message' => '你访问信息不存在', 'status' => 'error'
            ]);
        }
    }

    //分部删除
    public function destroy($id)
    {
        if (!check_permission('user/departments/destroy')) {
            return _404('无权操作');
        }
        //判断是否总部
        if (headquarters('id') == $id) {
            return _404('总部信息不能删除');
        }
        //判断是否有子部门信息
        if (Department::where('parent_id', $id)->first()) {
            return _404('不能删除，请先删除子部门信息');
        }
        if (User::where('department_id', $id)->first()) {
            return _404('不能删除，请先删除部门人员信息');
        }
        $menu = Department::find($id);
        if ($menu->delete()) {
            activity('系统日志')->performedOn($menu)
                ->withProperties($menu)->log('删除部门信息');
            return response()->json([
                'message' => '您操作的数据已被删除', 'data' => null
                , 'url' => route('departments.index'), 'status' => 'success'
            ]);
        } else {
            return _404('删除失败，未知错误');
        }
    }

    //新增部门
    public function subCreate(DepartmentRequest $request)
    {
        if (!check_permission('user/departments/sub/create')) {
            return _404('无权操作');
        }
        if ($request->method() == 'POST') {
            $menu = new Department();
            $menu->parent_id = $request->parent_id;
            $menu->name = $request->name;
            $menu->status = $request->status;
            $menu->sort = $request->sort ? intval($request->sort) : 0;
            $menu->remark = $request->remark ? $request->remark : '';
            $menu->level = 3;
            if ($company = $menu::info($request->parent_id, 'company_id')) {
                $menu->company_id = $company;
            } else {
                $menu->company_id = $request->parent_id;
            }
            if ($menu->save()) {
                activity('系统日志')->performedOn($menu)
                    ->withProperties($menu)->log('添加部门信息');
                return response()->json([
                    'message' => '添加成功', 'url' => get_redirect_url(),
                    'data' => $menu->toArray(), 'status' => 'success'
                ]);
            } else {
                return response()->json([
                    'message' => '保存失败', 'url' => null,
                    'data' => null, 'status' => 'error'
                ]);
            }
        }
        return view('user.department.sub_create');
    }

    //编辑部门
    public function subUpdate(DepartmentRequest $request, $id)
    {
        if (!check_permission('user/departments/sub/create')) {
            return _404('无权操作');
        }
        if (is_administrator()) {
            $menu = Department::find($id);
        } else {
            $menu = Department::where('company_id', get_user_company_id())->find($id);
        }
        if ($request->method() == 'PUT') {
            if ($menu) {
                $menu->parent_id = $request->parent_id;
                $menu->name = $request->name;
                $menu->status = $request->status;
                $menu->sort = $request->sort ? intval($request->sort) : 0;
                $menu->remark = $request->remark ? $request->remark : '';
                if ($company = $menu::info($request->parent_id, 'company_id')) {
                    $menu->company_id = $company;
                } else {
                    $menu->company_id = $request->parent_id;
                }
                if ($menu->save()) {
                    activity('系统日志')->performedOn($menu)
                        ->withProperties($menu)->log('编辑部门信息');
                    return response()->json([
                        'message' => '编辑成功', 'url' => route('departments.index'),
                        'data' => $menu->toArray(), 'status' => 'success'
                    ]);
                } else {
                    return response()->json([
                        'message' => '保存失败', 'url' => null,
                        'data' => null, 'status' => 'error'
                    ]);
                }
            } else {
                return _404('你访问的信息不存在');
            }
        }
        if ($menu) {
            return view('user.department.sub_edit', compact('menu'));
        } else {
            return _404('你访问的页面不存在');
        }
    }
}
