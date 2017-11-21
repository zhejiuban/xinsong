<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!check_permission('question/questions')) {
            return _404('无权操作！');
        }
        if ($request->ajax()) {
            $sort_field = $request->input('datatable.sort.field')
                ? $request->input('datatable.sort.field') : 'id';
            $sort = $request->input('datatable.sort.sort')
                ? $request->input('datatable.sort.sort') : 'desc';
            $prepage = $request->input('datatable.pagination.perpage')
                ? (int)$request->input('datatable.pagination.perpage') : 20;
            $role = Question::with([
                'category','project','user','receiveUser'
            ])->where(
                'title', 'like',
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
        return view('question.default.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!check_permission('question/questions/create')) {
            return _404('无权操作！');
        }
        return view('question.default.create',['back'=>null]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionRequest $request)
    {
        if (!check_permission('question/questions/create')) {
            return _404('无权操作！');
        }
        $result = new Question();
        $result->title = $request->title;
        $result->content = $request->input('content');
        $result->question_category_id = $request->question_category_id;
        $result->user_id = get_current_login_user_info();
        $result->receive_user_id = $request->receive_user_id;
        $result->project_id = $request->project_id;
        $result->file = $request->input('files') ? arr2str($request->input('files')) : null;
        $result->status = 0;
        if ($result->save()) {
            //记录日志
            activity()->withProperties($result->toArray())->log('问题添加成功');
            //接收人消息提醒

            return response()->json([
                'message' => '添加成功'
                , 'status' => 'success'
                , 'data' => $result->toArray()
                , 'url' => $request->back == 'personal'
                    ? route('question.personal',['mid'=>md5('question/personal')])
                    : route('questions.index',['mid'=>md5('question/questions')])
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!check_permission('question/questions/show')) {
            return _404('无权操作！');
        }
        if(is_administrator()){
            $question = Question::with([
                'user','category','receiveUser','project'
            ])->find($id);
        }else{
            $user_id = get_current_login_user_info();
            $question = Question::with([
                'user','category','receiveUser','project'
            ])->where('user_id',$user_id)
                ->orWhere('receive_user_id',$user_id)->find($id);
        }
        if($question){
            return view('question.default.show',compact('question'));
        }else{
            return _404();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function personalCreate(){
        if (!check_permission('question/questions/create')) {
            return _404('无权操作！');
        }
        return view('question.default.create',['back'=>'personal']);
    }
    public function personal(Request $request){
        if (!check_permission('question/personal')) {
            return _404('无权操作！');
        }
        if ($request->ajax()) {
            $sort_field = $request->input('datatable.sort.field')
                ? $request->input('datatable.sort.field') : 'id';
            $sort = $request->input('datatable.sort.sort')
                ? $request->input('datatable.sort.sort') : 'desc';
            $prepage = $request->input('datatable.pagination.perpage')
                ? (int)$request->input('datatable.pagination.perpage') : 20;
            $role = Question::with([
                'category','project','receiveUser'
            ])->where(
                'title', 'like',
                "%{$request->input('datatable.query.search')}%"
            )->where('user_id',get_current_login_user_info())->orderBy(
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
        return view('question.default.personal');
    }
    public function pending(Request $request){
        if (!check_permission('question/pending')) {
            return _404('无权操作！');
        }
        if ($request->ajax()) {
            $sort_field = $request->input('datatable.sort.field')
                ? $request->input('datatable.sort.field') : 'id';
            $sort = $request->input('datatable.sort.sort')
                ? $request->input('datatable.sort.sort') : 'desc';
            $prepage = $request->input('datatable.pagination.perpage')
                ? (int)$request->input('datatable.pagination.perpage') : 20;
            $role = Question::with([
                'category','project','receiveUser'
            ])->where(
                'title', 'like',
                "%{$request->input('datatable.query.search')}%"
            )->where('receive_user_id',get_current_login_user_info())->orderBy(
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
        return view('question.default.pending');
    }
    public function reply(){

    }
}
