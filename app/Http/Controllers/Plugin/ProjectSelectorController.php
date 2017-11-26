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
        echo 1;
    }

    /**
     * 选择器数据
     */
    public function data(Request $request)
    {
        $search = $request->input('q');
        $list = Project::when($search, function ($query) use ($search) {
            return $query->where('title', 'like', "%$search%")
                ->orWhere('no', 'like', "%$search%");
        })->where('status',1)->paginate(config('common.page.per_page'));
        return $list;
    }
}
