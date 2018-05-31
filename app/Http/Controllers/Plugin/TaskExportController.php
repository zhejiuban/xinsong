<?php

namespace App\Http\Controllers\Plugin;

use App\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class TaskExportController extends Controller
{
    public function index(Request $request)
    {
        Excel::create(date('Y-m-d') . '任务汇总导出', function ($excel) use ($request) {
            $status = $request->input('status');
            $search = $request->input('search');
            $project_id = $request->input('project_id');
            $user_id = $request->input('user_id');
            //管理员或总部管理员获取所有
            if (check_user_role(null, '总部管理员')) {
                $data = Task::with([
                    'user', 'leaderUser', 'project', 'project.plans', 'project.committedPlans'
                ])->baseSearch($status, $search, $project_id, $user_id)->orderBy('status', 'asc')->orderBy(
                    'id', 'desc')->get();
            } else {
                //获取分部所有用户
                $user = get_company_user(null, 'id');
                $data = Task::with([
                    'user', 'leaderUser', 'project', 'project.plans', 'project.committedPlans'
                ])->whereIn('leader', $user)->baseSearch($status, $search, $project_id, $user_id)
                    ->orderBy('status', 'asc')->orderBy(
                        'id', 'desc')->get();
            }

            // Call them separately
            $excel->setDescription('任务汇总');

            $excel->sheet('任务列表', function ($sheet) use ($data) {
                $export = [
                    ['序号', '任务内容', '所属项目', '执行人', '开始时间'
                        , '完成时间', '接收时间', '分配人', '去现场日期', '离开现场日期', '任务完成情况']
                ];
                if ($data) {
                    foreach ($data as $key => $val) {
                        $export[] = [
                            $key + 1,
                            $val->content,
                            $val->project ? $val->project->title : null,
                            $val->leaderUser ? $val->leaderUser->name : null,
                            $val->start_at,
                            $val->finished_at,
                            $val->received_at,
                            $val->user ? $val->user->name : null,
                            $val->builded_at,
                            $val->leaved_at,
                            $val->result
                        ];
                    }
                }
                // Sheet manipulation
                $sheet->fromArray($export, null, 'A1', false, false);
            });
        })->download('xlsx');
    }
}
