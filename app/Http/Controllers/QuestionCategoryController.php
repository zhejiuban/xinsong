<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionCategoryRequest;
use App\QuestionCategory;
use Illuminate\Http\Request;

class QuestionCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!check_permission('question/categories')) {
            return _404('无权操作！');
        }
        if ($request->ajax()) {
            $sort_field = $request->input('datatable.sort.field')
                ? $request->input('datatable.sort.field') : 'id';
            $sort = $request->input('datatable.sort.sort')
                ? $request->input('datatable.sort.sort') : 'desc';
            $prepage = $request->input('datatable.pagination.perpage')
                ? (int)$request->input('datatable.pagination.perpage') : 20;
            $role = QuestionCategory::where(
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
        return view('question.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!check_permission('question/categories/create')) {
            return _404('无权操作！');
        }
        return view('question.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionCategoryRequest $request)
    {
        if (!check_permission('question/categories/create')) {
            return _404('无权操作！');
        }
        $result = QuestionCategory::create([
            'name' => $request->name,
            'remark' => $request->remark,
            'status' => $request->status ? 1 : 0
        ]);
        if ($result) {
            //记录日志
            activity()->performedOn($result)->withProperties($result->toArray())->log('版块添加成功');
            return response()->json([
                'message' => '添加成功'
                , 'status' => 'success'
                , 'data' => $result->toArray()
                , 'url' => route('categories.index')
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
        if (!check_permission('question/categories/edit')) {
            return _404('无权操作！');
        }
        $category = QuestionCategory::find($id);
        if ($category) {
            return view('question.category.edit', ['category' => $category]);
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
    public function update(QuestionCategoryRequest $request, $id)
    {
        if (!check_permission('question/categories/edit')) {
            return _404('无权操作！');
        }
        $role = QuestionCategory::find($id);
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
                activity()->performedOn($role)
                    ->withProperties($role->toArray())->log('版块编辑成功');
                return response()->json([
                    'status' => 'success', 'message' => '编辑成功',
                    'data' => $role->toArray(), 'url' => route('categories.index')
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
        if (!check_permission('question/categories/destroy')) {
            return _404('无权操作！');
        }
        $result = [
            'status' => 'success',
            'message' => '操作成功',
            'data' => '',
            'url' => '',
        ];
        $dp = QuestionCategory::find($id);
        if ($dp) {
            $flag = 1;
            //判断是否有关联项目
            if(count($dp->questions)){
                $flag = 0;
                $result['status'] = 'error';
                $result['message'] = '存在关联问题，请先删除相关问题';
            }
            //删除
            if ($flag) {
                if ($dp->delete()) {
                    activity()->performedOn($dp)
                        ->withProperties($dp->toArray())->log('版块删除成功');
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
