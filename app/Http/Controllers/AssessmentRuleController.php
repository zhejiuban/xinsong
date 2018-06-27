<?php

namespace App\Http\Controllers;

use App\AssessmentRule;
use Illuminate\Http\Request;

class AssessmentRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!check_permission('assessment/rules')) {
            return _404('无权操作！');
        }
        if ($request->ajax()) {
            $sort_field = $request->input('datatable.sort.field')
                ? $request->input('datatable.sort.field') : 'id';
            $sort = $request->input('datatable.sort.sort')
                ? $request->input('datatable.sort.sort') : 'desc';
            $prepage = $request->input('datatable.pagination.perpage')
                ? (int)$request->input('datatable.pagination.perpage') : 20;
            $role = AssessmentRule::where(
                'name', 'like',
                "%{$request->input('datatable.query.search')}%"
            )->status([0, 1])->orderBy(
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
        return view('assessment.rule.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!check_permission('assessment/rules/create')) {
            return _404('无权操作！');
        }
        return view('assessment.rule.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!check_permission('assessment/rules/create')) {
            return _404('无权操作！');
        }
        $data = [
            'name' => $request->name,
            'content' => $request->input('content'),
            'remark' => $request->remark,
            'score' => floatval($request->score),
            'status' => $request->status ? 1 : 0
        ];
        $result = AssessmentRule::create($data);
        if ($result) {
            //记录日志
            activity()->withProperties($result->toArray())->log('考核细则添加成功');
            return _success('添加成功', $result->toArray(), get_redirect_url());
        } else {
            return _error();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AssessmentRule $assessmentRule
     * @return \Illuminate\Http\Response
     */
    public function show(AssessmentRule $assessmentRule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AssessmentRule $assessmentRule
     * @return \Illuminate\Http\Response
     */
    public function edit($id, AssessmentRule $assessmentRule)
    {
        if (!check_permission('assessment/rules/edit')) {
            return _404('无权操作！');
        }
        $cause = $assessmentRule::find($id);
        if ($cause) {
            return view('assessment.rule.edit', ['cause' => $cause]);
        } else {
            return _404('你访问的信息不存在');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\AssessmentRule $assessmentRule
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request, AssessmentRule $assessmentRule)
    {
        if (!check_permission('assessment/rule/edit')) {
            return _404('无权操作！');
        }
        $role = $assessmentRule::find($id);
        if (!$role) {
            return _404();
        } else {
            $role->name = $request->name;
            $role->content = $request->input('content');
            $role->remark = $request->remark;
            $role->score = $request->score;
            $role->status = $request->status ? 1 : 0;
            if ($role->save()) {
                activity()->performedOn($role)
                    ->withProperties($role->toArray())->log('考核细则编辑成功');
                return _success('操作成功', $role->toArray(), get_redirect_url());
            } else {
                return _error();
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AssessmentRule $assessmentRule
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, AssessmentRule $assessmentRule)
    {
        if (!check_permission('assessment/rules/destroy')) {
            return _404('无权操作！');
        }
        $result = [
            'status' => 'success',
            'message' => '操作成功',
            'data' => '',
            'url' => get_redirect_url(),
        ];
        $dp = AssessmentRule::find($id);
        if ($dp) {
            $flag = 1;
            //判断是否有关联项目
            if (count($dp->assessments)) {
                $flag = 0;
                $result['status'] = 'error';
                $result['message'] = '存在考核记录，不能删除';
            }
            //删除
            if ($flag) {
                if ($dp->update(['status' => -1])) {
                    activity()->performedOn($dp)
                        ->withProperties($dp->toArray())->log('删除成功');
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
