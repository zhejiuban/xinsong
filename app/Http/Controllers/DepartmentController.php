<?php

namespace App\Http\Controllers;

use App\Department;
use App\Http\Requests\CompanyRequest;
use App\Http\Requests\DepartmentRequest;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * 所有组织机构信息
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //获取部门信息
        $menu = Department::get()->toArray();
        $list = formatTreeData($menu);
        return view('user.department.index', compact('list'));
    }

    /**
     * 分部添加
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('user.department.create');
    }

    /**
     * 分部新增
     * @param CompanyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CompanyRequest $request)
    {
        $menu = new Department();
        $menu->parent_id = headquarters('id');
        $menu->name = $request->name;
        $menu->status = $request->status;
        $menu->sort = $request->sort ? intval($request->sort) : 0;
        $menu->remark = $request->remark ? $request->remark : '';
        $menu->level = 2;
        $menu->company_id = 0;
        if ($menu->save()) {
            //权限同步
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

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    //分部编辑
    public function edit($id)
    {
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
        $menu = Department::find($id);
        if ($menu) {
            $headquarters = headquarters('id');
            if($menu->id != $headquarters){
                $menu->status = $request->status;
                $menu->parent_id = $headquarters;
            }
            $menu->name = $request->name;
            $menu->sort = $request->sort ? intval($request->sort) : 0;
            $menu->remark = $request->remark ? $request->remark : '';
            if ($menu->save()) {
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
        //判断是否总部

        //判断是否有子部门信息



    }
    //新增部门
    public function subCreate(DepartmentRequest $request)
    {
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
                //权限同步
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
        return view('user.department.sub_create');
    }
    //编辑部门
    public function subUpdate(DepartmentRequest $request, $id)
    {
        $menu = Department::find($id);
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
