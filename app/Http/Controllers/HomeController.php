<?php

namespace App\Http\Controllers;

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
        if(check_user_role(null,'总部管理员')){
            return view('home');
        }elseif(check_company_admin()){
            return view('home');
        }else{
            return view('home');
        }
    }
}
