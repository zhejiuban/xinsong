<?php

namespace App\Http\Controllers\Plugin;

use App\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaskSelectorController extends Controller
{
    public function data(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('q');
        $project_id = $request->input('project_id');
        $user_id = $request->input('user_id');
        if (check_user_role(null, '总部管理员')) {
            $task = Task::with([
                'user', 'leaderUser', 'project'
            ])->baseSearch($status, $search, $project_id ? $project_id : null, $user_id)
                ->orderBy('id', 'desc')
                ->paginate(config('common.page.per_page'));
        } else {
            //获取分部所有用户
            $user = get_company_user(null, 'id');
            $task = Task::with([
                'user', 'leaderUser', 'project'
            ])->whereIn('leader', $user)
                ->baseSearch($status, $search, $project_id, $user_id)->orderBy('id', 'desc')
                ->paginate(config('common.page.per_page'));
        }
        return $task;
    }
}
