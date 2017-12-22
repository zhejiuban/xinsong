<?php

namespace App\Http\Controllers\Plugin;

use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DynamicCountController extends Controller
{
    public function index(){

    }

    public function needAddDynamicCount(Request $request){
        $start = $request->start;
        $project_id = $request->input('project_id');
        //获取分部所有用户
        $user = get_company_user(null,'id');
        //获取某日需上传日志的所有用户
        $all = Task::needAddDynamic($start,$project_id,$user)->get();
        $needAddDynamicTask = Task::needAddDynamic($start,$project_id,$user)
            ->doesntHaveDynamic($start)->get();
        return view('plugin.need_add_dynamic_count',compact([
            'user','all','needAddDynamicTask'
        ]));
    }
    public function needAddDynamicUser(Request $request){
        $start = $request->start;
        $project_id = $request->input('project_id');
        //获取分部所有用户
        $user = get_company_user(null,'id');
        //获取某日需上传日志的所有用户
        $all = Task::needAddDynamic($start,$project_id,$user)->get();
        $needAddDynamicTask = Task::needAddDynamic($start,$project_id,$user)
            ->doesntHaveDynamic($start)->get();
        return view('plugin.need_add_dynamic_user',compact([
            'user','all','needAddDynamicTask'
        ]));
    }
}
