<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Project;
use App\Question;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Jenssegers\Agent\Facades\Agent;

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
        if(!Agent::isMobile()){
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
                $status = $request->input('datatable.query.status');
                if (check_user_role(null, '总部管理员')) {
                    $role = Question::with([
                        'category', 'project', 'user', 'receiveUser'
                    ])->baseQuestion($status,$search,$date,$project_id)->orderBy(
                        $sort_field
                        , $sort)->paginate(
                        $prepage
                        , ['*']
                        , 'datatable.pagination.page'
                    );
                } else{
                    $role = Question::with([
                        'category', 'project', 'user', 'receiveUser'
                    ])->baseQuestion($status,$search,$date,$project_id)->companyQuestion()->orderBy(
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
            return view('question.default.index');
        }else{
            $search = $request->input('search');
            $project_id = $request->input('project_id');
            $date = $request->input('date');
            $status = $request->input('status');
            if (check_user_role(null, '总部管理员')) {
                $list = Question::with([
                    'category', 'project', 'user', 'receiveUser'
                ])->baseQuestion($status,$search,$date,$project_id)->orderBy(
                    'id'
                    , 'desc')->paginate(config('common.page.per_page'));
            } else {
                $list = Question::with([
                    'category', 'project', 'user', 'receiveUser'
                ])->baseQuestion($status,$search,$date,$project_id)->companyQuestion()->orderBy(
                    'id'
                    , 'desc')->paginate(config('common.page.per_page'));
            }
            set_redirect_url();
            return view('question.default.index_mobile',compact('list'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!check_permission('question/questions/create')) {
            return _404('无权操作！');
        }
        if($request->ajax()){
            return view('question.default.create_ajax', ['back' => null]);
        }else{
            return view('question.default.create', ['back' => null]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
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
        $result->status = 1;
        $result->click = 0;
        if ($result->save()) {
            //记录日志
            activity('项目日志')->performedOn(Project::find($result->project_id))
                ->withProperties($result->toArray())->log('添加问题:' . $result->title);
            //接收人消息提醒

            return response()->json([
                'message' => '添加成功'
                , 'status' => 'success'
                , 'data' => $result->toArray()
                , 'url' => get_redirect_url()
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
        if (!check_permission('question/questions/show')) {
            return _404('无权操作！');
        }
        $question = Question::with([
            'user', 'category', 'receiveUser', 'project'
        ])->find($id);

        if ($question) {
            if (!$question->status && $question->receive_user_id == get_current_login_user_info()) {
                //设置接收时间
                $question->status = 1;//已接收，待处理
                $question->received_at = Carbon::now();
                $question->click = intval($question->click) + 1;
                $question->save();
                //写入日志
                activity('项目日志')->performedOn($question->project)
                    ->withProperties($question)->log('问题接收:' . $question->title);
            } else {
                $question->increment('click', 1);
            }
            return view('question.default.show', compact('question'));
        } else {
            return _404();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        $question = Question::with([
            'category', 'receiveUser', 'project'
        ])->find($id);
        if ($question) {
            if (!is_administrator()
                && $question->user_id != get_current_login_user_info()) {
                return _error('无权操作');
            }elseif(!is_administrator() && $question->status > 1){
                return _error('问题已提交，不可修改');
            }
            if($request->ajax()){
                return view('question.default.edit_ajax', compact('question'));
            }else{
                return view('question.default.edit', compact('question'));
            }
        } else {
            return _404();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(QuestionRequest $request, $id)
    {
        $question = Question::find($id);
        if ($question) {
            //判断信息是否可编辑
            if (!is_administrator()
                && $question->user_id != get_current_login_user_info()) {
                return _error('无权操作');
            }elseif(!is_administrator() && $question->status > 1){
                return _error('问题已提交，不可修改');
            }
            //修改数据
            $question->title = $request->title;
            $question->content = $request->input('content');
            $question->question_category_id = $request->question_category_id;
            $question->receive_user_id = $request->receive_user_id;
            $question->project_id = $request->project_id;
            $question->file = $request->input('files') ? arr2str($request->input('files')) : null;
            if ($question->save()) {
                //记录日志
                activity('项目日志')->performedOn(Project::find($question->project_id))
                    ->withProperties($question->toArray())->log('更新问题:' . $question->title);
                //接收人消息提醒

                return _success('编辑成功', $question->toArray(), get_redirect_url());
            } else {
                return _error('编辑失败');
            }
        } else {
            return _404();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id = 0)
    {
        if (!check_permission('question/questions/destroy')) {
            return _404('无权操作！');
        }
        if (!$id) {
            $id = request('id');
        }
        $id = (array)$id;
        if (count($id) < 1 || !current($id)) {
            return _404('请选择要操作的数据');
        }
        //删除操作
        if (is_administrator()) {
            $res = Question::whereIn('id', $id)->delete();
        } else {
            $res = Question::whereIn('id', $id)->where(
                'user_id', get_current_login_user_info()
            )->where('status', '<', 1)->delete();
        }
        if ($res) {
            activity('项目日志')->withProperties($id)->log('删除问题');
            return response()->json([
                'message' => '删除成功', 'data' => $id,
                'status' => 'success', 'url' => null
            ]);
        } else {
            return _error('删除失败');
        }
    }

    public function personalCreate()
    {
        if (!check_permission('question/questions/create')) {
            return _404('无权操作！');
        }
        return view('question.default.create', ['back' => 'personal']);
    }

    public function personal(Request $request)
    {
        if (!check_permission('question/personal')) {
            return _404('无权操作！');
        }
        if (!Agent::isMobile()){
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
                $status = $request->input('datatable.query.status');
                $role = Question::with([
                    'category', 'project', 'receiveUser'
                ])->baseQuestion($status,$search,$date,$project_id)->personalQuestion()->orderBy(
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
        }else{
            $search = $request->input('search');
            $project_id = $request->input('project_id');
            $date = $request->input('date');
            $status = $request->input('status');
            $list = Question::with([
                'category', 'project', 'receiveUser'
            ])->baseQuestion($status,$search,$date,$project_id)->personalQuestion()->orderBy(
                'id'
                , 'desc')->paginate(config('common.page.per_page'));
            set_redirect_url();
            return view('question.default.personal_mobile',compact('list'));
        }
    }

    public function pending(Request $request)
    {
        if (!check_permission('question/pending')) {
            return _404('无权操作！');
        }
        if (!Agent::isMobile()){
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
                $status = $request->input('datatable.query.status');

                $role = Question::with([
                    'category', 'project', 'receiveUser', 'user'
                ])->baseQuestion($status,$search,$date,$project_id)->receiveQuestion()->orderBy(
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
        }else{
            $search = $request->input('search');
            $project_id = $request->input('project_id');
            $date = $request->input('date');
            $status = $request->input('status');

            $list = Question::with([
                'category', 'project', 'receiveUser', 'user'
            ])->baseQuestion($status,$search,$date,$project_id)->receiveQuestion()->orderBy(
                'id'
                , 'desc')->paginate(config('common.page.per_page'));

            set_redirect_url();
            return view('question.default.pending_mobile',compact('list'));
        }
    }

    //问题回复设置
    public function reply(Request $request)
    {
        //检测权限
        if (!check_permission('question/reply')) {
            return _404('无权操作！');
        }
        $info = Question::find($request->question);
        //获取问题详情
        if(!$info || $info->status > 2){
            return _404('无权操作！');
        }
        if(!check_user_role(null,'总部管理员')
            && $info->receive_user_id != get_current_login_user_info()){
            return _404('无权操作！');
        }
        if ($request->isMethod('post')) {
            //检测回复内容
            $this->validate($request, [
                'reply_content' => 'required'
            ], ['reply_content.required' => '请填写回复内容']);
            $info->reply_content = $request->reply_content;
            $info->replied_at = Carbon::now();
            $info->status = 2; //设置已回复
            if ($info->save()) {
                //增加回复提醒

                activity('项目日志')->performedOn(Project::find($info->project_id))
                    ->withProperties($info)->log('回复问题:' . $info->title);
                return _success('回复成功');
            } else {
                return _error('回复失败');
            }
        } else {
            if (!$info->status) {
                //设置接收时间
                $info->status = 1;//已接收，待处理
                $info->received_at = Carbon::now();
                $info->save();
                //写入日志
                activity('项目日志')->performedOn(Project::find($info->project_id))
                    ->withProperties($info)->log('问题接收:' . $info->title);
            }
            return view('question.default.reply', ['question' => $info]);
        }
    }

    //关闭设置
    public function finished(Request $request)
    {
        //检测权限
        if (!check_permission('question/finished')) {
            return _404('无权操作！');
        }
        $question = (array)$request->question;
        if (!count($question) || !current($question)) {
            return _error('请选择要操作的数据');
        }
        if (is_administrator()) {
            $update = Question::whereIn('id', $question)->update([
                'status' => 3, 'finished_at' => Carbon::now()
            ]);
        } else {
            $update = Question::whereIn('id', $question)->where(
                'user_id', get_current_login_user_info()
            )->where(
                'status', '<=', 2
            )->update([
                'status' => 3, 'finished_at' => Carbon::now()
            ]);
        }
        if ($update) {
            //记录日志
            if (count($question) == 1) {
                $info = Question::find($question[0]);
                activity('项目日志')->performedOn(Project::find($info->project_id))
                    ->withProperties($info)->log('关闭问题:' . $info->title);
            } else {
                activity('项目日志')->withProperties($question)->log('关闭问题');
            }
            return _success();
        } else {
            return _error('操作失败！');
        }
    }
}
