<?php

namespace App\Http\Controllers\Plugin;

use App\Dynamic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class DynamicExportController extends Controller
{
    public function index(Request $request)
    {
        Excel::create(date('Y-m-d') . '日志汇总导出', function ($excel) use ($request) {
            $project_id = $request->input('project_id');
            $user_id = $request->input('user_id');
            $search = $request->input('search');
            $date = $request->input('date');
            //管理员或总部管理员获取所有
            if (check_user_role(null, '总部管理员')) {
                $data = Dynamic::with([
                    'user', 'project', 'project.committedPlans', 'project.plans', 'task'
                ])->when($search, function ($query) use ($search) {
                    return $query->where(function ($query) use ($search) {
                        $query->where(
                            'content', 'like',
                            "%{$search}%"
                        );
                    });
                })->when($project_id, function ($query) use ($project_id) {
                    return $query->where('project_id', $project_id);
                })->when($user_id, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })->when($date, function ($query) use ($date) {
                    return $query->whereBetween('created_at', [
                        date_start_end($date), date_start_end($date, 'end')
                    ]);
                })->orderBy('id', 'desc')->get();
            } else {
                //分部管理员获取分部所有项目
                //获取分部所有用户
                $user = get_company_user(null, 'id');
                $data = Dynamic::with([
                    'user', 'project', 'project.committedPlans', 'project.plans', 'task'
                ])->whereIn('user_id', $user)->when($search, function ($query) use ($search) {
                    return $query->where(function ($query) use ($search) {
                        $query->where(
                            'content', 'like',
                            "%{$search}%"
                        );
                    });
                })->when($date, function ($query) use ($date) {
                    return $query->whereBetween('created_at', [
                        date_start_end($date), date_start_end($date, 'end')
                    ]);
                })->when($project_id, function ($query) use ($project_id) {
                    return $query->where('project_id', $project_id);
                })->when($user_id, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })->orderBy('id', 'desc')->get();
            }

            // Call them separately
            $excel->setDescription('日志汇总');

            $excel->sheet('故障记录列表', function ($sheet) use ($data) {
                $export = [
                    ['序号', '日志内容', '所属项目', '所属任务', '上报人', '上报时间', '是否补填']
                ];
                if ($data) {
                    foreach ($data as $key => $val) {
                        $export[] = [
                            $key + 1,
                            $val->content,
                            $val->project ? $val->project->title : null,
                            $val->task ? $val->task->content : null,
                            $val->user ? $val->user->name : null,
                            $val->created_at,
                            $val->fill ? '是' : '否'
                        ];
                    }
                }
                // Sheet manipulation
                $sheet->fromArray($export, null, 'A1', false, false);
            });
        })->download('xlsx');
    }
}
