<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(){
        $user = get_current_login_user_info(true);
        $list = $user->notifications()->paginate(config('common.page.per_page'));
        return view('notification.index',compact('list'));
    }
    public function read($id){
        $user = get_current_login_user_info(true);
        $user->markSingleAsRead($id);
        $notify = $user->notifications()->where('id',$id)->first();
        return redirect($notify->data['url']);
    }
    public function markAsRead(Request $request){
        $user = get_current_login_user_info(true);
        $user->markAllAsRead();
        if($request->ajax()){
            return _success();
        }
        return redirect('notifications')->with('success','操作成功');
    }
}
