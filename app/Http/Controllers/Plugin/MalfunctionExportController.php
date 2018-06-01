<?php

namespace App\Http\Controllers\Plugin;

use App\Malfunction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class MalfunctionExportController extends Controller
{
    public function index(Request $request)
    {
        Excel::create(date('Y-m-d') . '故障记录汇总导出', function ($excel) use ($request) {
            $search = $request->input('search');
            $project_id = $request->input('project_id');
            $date = $request->input('date');
            $device_id = $request->input('device_id');
            if (check_user_role(null, '总部管理员')) {
                $data = Malfunction::with([
                    'phase', 'project', 'user', 'device'
                ])->baseSearch($search, $date, $project_id, $device_id)->orderBy(
                    'id', 'desc')->get();
            } else {
                $data = Malfunction::with([
                    'phase', 'project', 'user', 'device'
                ])->baseSearch($search, $date, $project_id, $device_id)->companySearch()->orderBy(
                    'id', 'desc')->get();
            }

            // Call them separately
            $excel->setDescription('故障记录汇总');

            $excel->sheet('故障记录列表', function ($sheet) use ($data) {
                $export = [
                    ['序号', '故障现象', '所属项目', '小车编号', '设备类型'
                        , '故障来自', '故障处理人', '处理时间', '故障原因', '故障处理']
                ];
                if ($data) {
                    foreach ($data as $key => $val) {
                        $export[] = [
                            $key + 1,
                            $val->content,
                            $val->project ? $val->project->title : null,
                            $val->car_no,
                            $val->device ? $val->device->name : null,
                            $val->phase ? $val->phase->name : null,
                            $val->user ? $val->user->name : null,
                            $val->handled_at,
                            $val->reason,
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
