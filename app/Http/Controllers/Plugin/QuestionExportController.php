<?php

namespace App\Http\Controllers\Plugin;

use App\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class QuestionExportController extends Controller
{
    public function index(Request $request)
    {
        Excel::create(date('Y-m-d') . '问题汇总导出', function ($excel) use ($request) {
            $search = $request->input('search');
            $project_id = $request->input('project_id');
            $date = $request->input('date');
            $status = $request->input('status');
            $user_id = $request->input('user_id');
            $receive_user_id = $request->input('receive_user_id');
            //获取所有用户目信息
            if (check_user_role(null, '总部管理员')) {
                $data = Question::with([
                    'category', 'project', 'user', 'receiveUser'
                ])->baseQuestion($status, $search, $date
                    , $project_id, $user_id, $receive_user_id)
                    ->orderBy('id', 'desc')->get();
            } else {
                $data = Question::with([
                    'category', 'project', 'user', 'receiveUser'
                ])->baseQuestion($status, $search, $date
                    , $project_id, $user_id, $receive_user_id)
                    ->companyQuestion()
                    ->orderBy('id', 'desc')->get();
            }

            // Call them separately
            $excel->setDescription('问题汇总');

            $excel->sheet('问题列表', function ($sheet) use ($data) {
                $export = [
                    ['序号', '标题', '所属项目', '所属分类', '上报人', '创建时间'
                        , '问题内容', '接收人', '回复时间', '回复内容', '状态', '关闭时间']
                ];
                if ($data) {
                    foreach ($data as $key => $val) {
                        $export[] = [
                            $key + 1,
                            $val->title,
                            $val->project ? $val->project->title : null,
                            $val->category ? $val->category->name : null,
                            $val->user ? $val->user->name : null,
                            $val->created_at,
                            $val->content,
                            $val->receiveUser ? $val->receiveUser->name : null,
                            $val->replied_at,
                            $val->reply_content,
                            question_status($val->status),
                            $val->finished_at
                        ];
                    }
                }
                // Sheet manipulation
                $sheet->fromArray($export, null, 'A1', false, false);
            });
        })->download('xlsx');

    }
}
