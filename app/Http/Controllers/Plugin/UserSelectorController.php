<?php

namespace App\Http\Controllers\Plugin;

use App\Project;
use App\User;
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
        $per_page = $request->per_page ? $request->per_page : config('common.page.per_page');
        if($request->input('type') == 'all' || check_user_role(null,'总部管理员')){ //超级管理员或总部管理员
            $list = User::with(['department'])->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('username', 'like', "%$search%")
                    ->orWhere('tel', 'like', "%$search%");
            })->when($request->input('is_assessment'),function ($query){
                return $query->isAssessment(1);
            })->status(1)->paginate($per_page);
        }elseif($request->input('type') == 'sub_and_headquarters'){
            //获取分部以及总部所有用户信息
            $list = User::with(['department'])->when($search, function ($query) use ($search) {
                return $query->where(function ($query) use ($search){
                    $query->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('username', 'like', "%$search%")
                        ->orWhere('tel', 'like', "%$search%");
                });
            })->where(function ($query){
                $query->whereIn('department_id'
                    , get_company_deparent(get_user_company_id()))
                    ->orWhere('id'
                        , get_current_login_user_info())
                    ->orWhereIn('department_id',get_company_deparent(headquarters('id')));
            })->when($request->input('is_assessment'),function ($query){
                    return $query->isAssessment(1);
                })->status(1)->paginate($per_page);
        }elseif($request->input('type') == 'headquarters'){
            $list = User::with(['department'])->when($search, function ($query) use ($search) {
                return $query->where(function ($query) use ($search){
                    $query->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('username', 'like', "%$search%")
                        ->orWhere('tel', 'like', "%$search%");
                });
            })->whereIn('department_id',get_company_deparent(headquarters('id')))
                ->when($request->input('is_assessment'),function ($query){
                    return $query->isAssessment(1);
                })->status(1)->paginate($per_page);
        }else{
            $list = User::with(['department'])->when($search, function ($query) use ($search) {
                return $query->where(function ($query) use ($search){
                    $query->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('username', 'like', "%$search%")
                        ->orWhere('tel', 'like', "%$search%");
                });
            })->where(function ($query){
                $query->whereIn('department_id'
                    , get_company_deparent(get_user_company_id()))
                    ->orWhere('id'
                        , get_current_login_user_info());
            })->when($request->input('is_assessment'),function ($query){
                    return $query->isAssessment(1);
                })->status(1)->paginate($per_page);
        }
        if($list){
            foreach ($list as $key=>$val){
                $list[$key]->company = $val->company();
            }
        }
        return $list;
    }

    /**
     * 项目参与人
     */
    public function projectUserData(Request $request){
        $project_id = $request->input('id');
        $project = Project::find($project_id);
        $list = $project->users;
        if($request->input('need_plan')){
            foreach ($list as $key=>$val){
                if ($project->subcompany_leader != $val->id && $project->agent != $val->id){
                    unset($list[$key]);
                }
            }
        }
        return response()->json([
            'message' => '用户列表'
            , 'status' => 'success'
            , 'data' => $list
            , 'url' => ''
        ]);
    }
}
