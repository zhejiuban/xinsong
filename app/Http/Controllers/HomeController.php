<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

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
//        if(check_user_role(null,'总部管理员')){
//            return view('home');
//        }elseif(check_company_admin()){
//            return view('home');
//        }else{
            //获取当前用户未上传日志的任务
            $user = get_current_login_user_info(true);
            $needAddDynamicTask = $user->leaderTasks()->where('status',0)->whereDate(
                'start_at', '<=', Carbon::now()->toDateString()
            )->whereDoesntHave('dynamics',function ($query){
                return $query->whereBetween('created_at', [
                    date_start_end(), date_start_end(null, 'end')
                ]);
            })->get();
            set_redirect_url();
            return view('home',compact(['user','needAddDynamicTask']));
//        }
    }
}
