<?php

namespace App\Http\Controllers\Plugin;

use App\User;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserSelectorController extends Controller
{
    /**
     * 用户选择器
     */
    public function index()
    {
    }

    /**
     * 选择器数据
     */
    public function data(Request $request)
    {
        $search = $request->input('q');
        if($request->input('type') == 'all'){ //超级管理员或总部管理员
            $list = User::with(['department'])->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('username', 'like', "%$search%")
                    ->orWhere('tel', 'like', "%$search%");
            })->paginate(config('common.page.per_page'));
        }elseif($request->input('type') == 'sub_and_headquarters'){
            //获取分部以及总部所有用户信息
            $list = User::with(['department'])->when($search, function ($query) use ($search) {
                return $query->where(function ($query) use ($search){
                    $query->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('username', 'like', "%$search%")
                        ->orWhere('tel', 'like', "%$search%");
                });
            })->whereIn('department_id'
                , get_company_deparent(get_user_company_id()))
                ->orWhere('id'
                    , get_current_login_user_info())
                ->orWhereIn('department_id',get_company_deparent(headquarters('id')))
                ->paginate(config('common.page.per_page'));
        }elseif($request->input('type') == 'headquarters'){
            $list = User::with(['department'])->when($search, function ($query) use ($search) {
                return $query->where(function ($query) use ($search){
                    $query->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('username', 'like', "%$search%")
                        ->orWhere('tel', 'like', "%$search%");
                });
            })->WhereIn('department_id',get_company_deparent(headquarters('id')))
                ->paginate(config('common.page.per_page'));
        }else{
            $list = User::with(['department'])->when($search, function ($query) use ($search) {
                return $query->where(function ($query) use ($search){
                    $query->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('username', 'like', "%$search%")
                        ->orWhere('tel', 'like', "%$search%");
                });
            })->whereIn('department_id'
                , get_company_deparent(get_user_company_id()))
                ->orWhere('id'
                    , get_current_login_user_info())->paginate(config('common.page.per_page'));
        }
        if($list){
            foreach ($list as $key=>$val){
                $list[$key]->company = $val->company();
            }
        }
        return $list;
    }
}
