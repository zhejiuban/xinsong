<?php

namespace App\Http\Controllers\Plugin;

use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DynamicCountController extends Controller
{
    public function index()
    {

    }

    /**
     * 获取某日未上传日志统计
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function needAddDynamicCount(Request $request)
    {
        $start = $request->input('start', current_date());
        $start = $start ? $start : current_date();
        $project_id = $request->input('project_id');
        //获取分部所有用户
        $user = get_company_user(null, 'id');
        //获取某日需上传日志的所有用户
        $all = Task::needAddDynamic($start, $project_id, $user)->get();
        $needAddDynamicTask = Task::needAddDynamic($start, $project_id, $user)
            ->doesntHaveDynamic($start)->get();
        return view('plugin.need_add_dynamic_count', compact([
            'user', 'all', 'needAddDynamicTask'
        ]));
    }

    /**
     * 获取某日未上传日志人员统计
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function needAddDynamicUser(Request $request)
    {
        $start = $request->input('start', current_date());
        $start = $start ? $start : current_date();
        $project_id = $request->input('project_id');
        //获取分部所有用户
        $user = get_company_user(null, 'id');
        //获取某日需上传日志的所有用户
        $all = Task::needAddDynamic($start, $project_id, $user)->get();
        $needAddDynamicTask = Task::needAddDynamic($start, $project_id, $user)
            ->doesntHaveDynamic($start)->get();
        return view('plugin.need_add_dynamic_user', compact([
            'user', 'all', 'needAddDynamicTask'
        ]));
    }

    public function userNeedFillDynamics(Request $request)
    {
        $start = $request->input('start', current_date());
        $start = $start ? $start : current_date();
        $project_id = $request->input('project_id');
        //获取当前登录用户
        $user = get_current_login_user_info();
        $needFillDynamicTask = Task::needAddDynamic($start, $project_id, $user)
            ->doesntHaveDynamic($start)->get();
        return view('plugin.user_need_fill_dynamics', compact(['needFillDynamicTask'
        ]));
    }
}
