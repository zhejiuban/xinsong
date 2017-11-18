<?php

namespace App\Http\Controllers\Plugin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UserSelectorController extends Controller
{
    /**
     * 用户选择器
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
        $list = User::with(['department'])->when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('username', 'like', "%$search%")
                ->orWhere('tel', 'like', "%$search%");
        })->paginate(config('common.page.per_page'));
        return $list;
    }
}
