<?php

namespace App\Http\Controllers;

use App\User;
use App\UserMonthScore;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserMonthScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!check_permission('assessment/scores/create')) {
            return _404('无权操作！');
        }
        return view('assessment.default.init');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $start = $request->start;
        $end = $request->end;
        //获取单位所有人员
        if (is_administrator() || check_user_role(null, '总部管理员')) {
            $user = User::get()->pluck('id')->all();
        } else {
            $user = get_company_user();
        }
//        try {
            if ($user && $start && (!$end || $start == $end)) {
                //获取已存在初始化信息的
                $list = UserMonthScore::where('month'
                    , $start)->get();
                foreach ($user as $u) {
                    if (!$list->where('month'
                        , $start)->where('user_id', $u)->all()) {
                        UserMonthScore::create([
                            'month' => $start,
                            'user_id' => $u,
                            'score' => 100
                        ]);
                    }
                }
            } elseif ($user && $start && $end && ($start < $end)) {
                $list = UserMonthScore::where('month'
                    , '>=', $start)->where('month'
                    , '<=', $end)->get();
                $month = month_list(strtotime($start), strtotime($end));
                if ($month) {
                    foreach ($user as $u) {
                        foreach ($month as $m) {
                            if (!$list->where('month'
                                , $m)->where('user_id', $u)->all()) {
                                UserMonthScore::create([
                                    'month' => $m,
                                    'user_id' => $u,
                                    'score' => 100
                                ]);
                            }
                        }
                    }
                }
            }
            return _success();
//        } catch(\Exception $exception){
//            return _error();
//        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
