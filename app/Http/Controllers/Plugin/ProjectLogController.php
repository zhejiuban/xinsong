<?php

namespace App\Http\Controllers\Plugin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class ProjectLogController extends Controller
{
    //获取项目日志
    public function index(){
       $subject_id = request('id');
       $list = Activity::inLog('项目日志')->with('causer')->where('subject_id',$subject_id)->orderBy('id','desc')->get();
       return view('plugin.project_log',compact('list'));
    }
}
