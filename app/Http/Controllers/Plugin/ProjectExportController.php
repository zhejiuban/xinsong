<?php

namespace App\Http\Controllers\Plugin;

use App\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ProjectExportController extends Controller
{
    /**
     * 项目导出
     */
    public function index(Request $request){
        Excel::create(date('Y-m-d').'项目导出',function ($excel){
            //获取所有项目信息
            if(check_user_role(null, '总部管理员')){
                $data = Project::with([
                    'devices','phases','users','department'
                    ,'leaderUser','companyUser','agentUser'
                ])->orderBy('id', 'desc')->get();
            }elseif(check_company_admin()){
                $data = Project::with([
                    'devices','phases','users','department'
                    ,'leaderUser','companyUser','agentUser'
                ])->where('department_id', get_user_company_id())
                    ->orderBy('id', 'desc')->get();
            }else{
                $data = null;
            }

            // Call them separately
            $excel->setDescription('项目汇总');

            $excel->sheet('项目列表', function($sheet) use ($data) {
                $export = [
                    ['序号','项目名称','项目编号','总部项目负责人','所属办事处','办事处项目负责人',
                        '现场代理负责人','参与人','客户对接人','客户联系电话','客户地址','项目状态']
                ];
                if($data){
                    foreach ($data as $key=>$val){
                        $phases = [];
                        foreach($val->phases as $phase){
                            $phases[] =  $phase->name . '('.project_phases_status($phase->status,'title').')';
                        }
                        $export[] = [
                            $key+1,
                            $val->title,
                            $val->no,
                            $val->leaderUser ? $val->leaderUser->name : null,
                            $val->department ? $val->department->name : null,
                            $val->companyUser ? $val->companyUser->name : null,
                            $val->agentUser ? $val->agentUser->name : null,
                            format_project_users($val->users,'name'),
                            $val->customers,
                            $val->customers_tel,
                            $val->customers_address,
                            arr2str($phases,','.PHP_EOL)
                        ];
                    }
                }
                // Sheet manipulation
                $sheet->fromArray($export, null, 'A1', false, false);
            });
        })->download('xlsx');
    }
}
