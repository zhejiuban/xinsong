<?php

namespace App\Http\Controllers\Plugin;

use App\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectSelectorController extends Controller
{
    /**
     * 项目选择器
     */
    public function index()
    {
        echo true;
    }

    /**
     * 选择器数据
     */
    public function data(Request $request)
    {
        $search = $request->input('q');
        if (check_user_role(null, '总部管理员')) {
            $list = Project::when($search, function ($query) use ($search) {
                return $query->where(function ($query) use($search){
                    $query->where('title', 'like', "%$search%")
                        ->orWhere('no', 'like', "%$search%");
                });
            })->where('status', '=', 1)
                ->orderBy('id','desc')
                ->paginate(config('common.page.per_page'));
        } elseif (check_company_admin()) {
            $list = Project::when($search, function ($query) use ($search) {
                return $query->where(function ($query) use($search){
                    $query->where('title', 'like', "%$search%")
                        ->orWhere('no', 'like', "%$search%");
                });
            })->where('status', '=', 1)
                ->where('department_id', get_user_company_id())
                ->orderBy('id','desc')->paginate(config('common.page.per_page'));
        }else{
            $user = get_current_login_user_info(true);
            $list = $user->projects()->when($search, function ($query) use ($search) {
                return $query->where('title', 'like', "%$search%")
                    ->orWhere('no', 'like', "%$search%");
            })->where('status', '=', 1)
                ->orderBy('id','desc')
                ->paginate(config('common.page.per_page'));
        }
        return $list;
    }
}
