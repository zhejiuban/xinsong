<?php

namespace App\Http\Controllers;

use App\Dynamic;
use App\Project;
use App\Question;
use App\Task;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Jenssegers\Agent\Facades\Agent;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //获取当前用户未上传日志的任务
        $user = get_current_login_user_info(true);
        $needAddDynamicTask = $user->leaderTasks()
            ->needAddDynamic(current_date())->doesntHaveDynamic(current_date())->get();

        if(check_user_role(null,'总部管理员')){
            //获取所有项目
            $project = [
                'all'=>Project::count(),
                'finished' => Project::where('status',2)->count(),
                'unstart' => Project::where('status',0)->count(),
                'processing' => Project::where('status',1)->count(),
                'day_add'=>Project::whereBetween('created_at', [
                    date_start_end(), date_start_end(null, 'end')
                ])->count()
            ];
            $task = [
                'all'=>Task::count(),
                'finished'=>Task::where('status',1)->count(),
                'processing'=>Task::where('status',0)->count(),
                'day_processing'=>Task::where('status',0)->whereDate(
                    'start_at', '<=', Carbon::now()->toDateString()
                )->count()
            ];
            $question = [
                'all'=>Question::count(),
                'reply'=>Question::where('status',1)->count(),
                'replyed'=>Question::where('status',2)->count(),
                'receive'=>Question::where('status',0)->count(),
                'close'=>Question::where('status',3)->count(),
                'day_add'=>Question::whereBetween('created_at', [
                    date_start_end(), date_start_end(null, 'end')
                ])->count(),
                'need_reply'=>Question::whereIn('status',[0,1])
                    ->receiveQuestion()->get()
            ];
            set_redirect_url();
            return view('index',compact([
                'project','task','question','needAddDynamicTask'
            ]));
        }elseif(check_company_admin()){
            //获取分部所有用户
            $user = get_company_user(null,'id');
            //获取今日需上传日志的所有用户
            $all = Task::whereIn('leader',$user)->where('status',0)->whereDate(
                'start_at', '<=', Carbon::now()->toDateString()
            )->get();

            //获取所有分部项目
            $company = get_user_company_id();
            $project = [
                'all'=>Project::where('department_id',$company)->count(),
                'finished' => Project::where('department_id',$company)->where('status',2)->count(),
                'unstart' => Project::where('department_id',$company)->where('status',0)->count(),
                'processing' => Project::where('department_id',$company)->where('status',1)->count(),
                'day_add'=>Project::where('department_id',$company)->whereBetween('created_at', [
                    date_start_end(), date_start_end(null, 'end')
                ])->count()
            ];
            $task = [
                'all'=>Task::whereIn('leader',$user)->count(),
                'finished'=>Task::whereIn('leader',$user)->where('status',1)->count(),
                'processing'=>Task::whereIn('leader',$user)->where('status',0)->count(),
                'day_processing'=>$all->count()
            ];
            $question = [
                'all'=>Question::whereIn('user_id',$user)->count(),
                'reply'=>Question::whereIn('user_id',$user)->where('status',1)->count(),
                'replyed'=>Question::whereIn('user_id',$user)->where('status',2)->count(),
                'receive'=>Question::whereIn('user_id',$user)->where('status',0)->count(),
                'close'=>Question::whereIn('user_id',$user)->where('status',3)->count(),
                'day_add'=>Question::whereIn('user_id',$user)->whereBetween('created_at', [
                    date_start_end(), date_start_end(null, 'end')
                ])->count(),
                'need_reply'=>Question::whereIn('status',[0,1])
                    ->receiveQuestion()->get()
            ];
            set_redirect_url();
            return view('company',compact([
                'user','all'
                ,'project','task','question','needAddDynamicTask'
            ]));
        }else{
            $question = [
                'need_reply'=>Question::whereIn('status',[0,1])
                    ->receiveQuestion()->get()
            ];
            set_redirect_url();
            return view('home',compact(['user','needAddDynamicTask','question']));
        }
    }
}
