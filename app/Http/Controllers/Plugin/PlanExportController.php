<?php

namespace App\Http\Controllers\Plugin;

use App\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class PlanExportController extends Controller
{
    public function index($project, Request $request)
    {
        $project = Project::find($project);
        Excel::create(date('Y-m-d') . $project->title . '-实施计划导出'
            , function ($excel) use ($project, $request) {
                $data = $project->plans()->with('user')
                    ->orderBy('sort', 'asc')->get();
                // Call them separately
                $excel->setDescription($project->title . '-实施计划汇总');

                $excel->sheet($project->title . '-实施计划汇总', function ($sheet) use ($data) {
                    $export = [
                        ['序号', '是否按计划完成', '计划内容', '开始时间', '结束时间', '执行人'
                            , '延期（天）', '时间完成时间', '	未按计划完成原因说明', '状态']
                    ];
                    if ($data) {
                        foreach ($data as $key => $val) {
                            $export[] = [
                                $key + 1,
                                $val->is_finished == 1 ? '是' : ($val->is_finished != null ? '否' : ''),
                                $val->content,
                                $val->started_at,
                                $val->finished_at,
                                $val->user ? $val->user->name : null,
                                $val->delay,
                                $val->last_finished_at,
                                $val->reason,
                                $val->status ? '已提交' : '草稿'
                            ];
                        }
                    }
                    // Sheet manipulation
                    $sheet->fromArray($export, null, 'A1', false, false);
                });
            })->download('xlsx');
    }
}
