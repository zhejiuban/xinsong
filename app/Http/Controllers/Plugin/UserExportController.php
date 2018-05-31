<?php

namespace App\Http\Controllers\Plugin;

use App\Department;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class UserExportController extends Controller
{
    public function index(){
        Excel::create(date('Y-m-d').'用户信息导出',function ($excel){
            //获取所有用户目信息
            if (is_administrator() || check_user_role(null,'总部管理员')) {
                $data = User::with(['department', 'roles'])->get();
            } else {
                $data = User::with(['department', 'roles'])->where(function ($query) {
                    //获取用户所属分部所有部门
                    $query->whereIn('department_id', get_company_deparent(get_user_company_id()));
                })->get();
            }
            // Call them separately
            $excel->setDescription('用户信息汇总');

            $excel->sheet('用户列表', function($sheet) use ($data) {
                $export = [
                    ['序号','用户名','角色','姓名','性别','手机号','邮箱',
                        '部门','状态']
                ];
                if($data){
                    foreach ($data as $key=>$val){
                        //获取所属部门
                        if ($val->department) {
                            if ($val->department->company_id) {
                                $company = Department::info($val->department->company_id, true);
                            } else {
                                $company =  Department::info($val->department->id, true);
                            }
                        }else{
                            $company = null;
                        }
                        //获取所属角色
                        $roles = [];
                        foreach ($val->roles as $r){
                            $roles[] = $r->name;
                        }

                        $export[] = [
                            $key+1,
                            $val->username,
                            config('auth.administrator') == $val->id ? '超级管理员':arr2str($roles),
                            $val->name,
                            $val->sex,
                            $val->tel,
                            $val->email,
                            ($company ? $company->name : '')
                            . ($val->department && $val->department->level == 3 ? '/' . $val->department->name : ''),
                            $val->status ? '可用':'禁用'
                        ];
                    }
                }
                // Sheet manipulation
                $sheet->fromArray($export, null, 'A1', false, false);
            });
        })->download('xlsx');
    }
}
