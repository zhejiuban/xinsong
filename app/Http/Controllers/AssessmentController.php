<?php

namespace App\Http\Controllers;

use App\Assessment;
use App\AssessmentRule;
use App\Department;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!check_permission('assessment/assessments')) {
            return _404('无权操作');
        }
        if ($request->ajax()) {
            $sort_field = $request->input('datatable.sort.field')
                ? $request->input('datatable.sort.field') : 'id';
            $sort = $request->input('datatable.sort.sort')
                ? $request->input('datatable.sort.sort') : 'desc';
            $prepage = $request->input('datatable.pagination.perpage')
                ? (int)$request->input('datatable.pagination.perpage') : 20;
            $search = $request->input('datatable.query.search');
            $department = $request->input('datatable.query.department_id');
            $start = $request->input('datatable.query.start');
            $end = $request->input('datatable.query.end');
            if (is_administrator() || check_user_role(null, '总部管理员')) {
                $user = User::with([
                    'department',
                    'userMonthScores' => function ($query) use ($start, $end) {
                        $query->month($start, $end)->orderBy('month', 'desc');
                    },
                    'assessments' => function ($query) use ($start, $end) {
                        $query->month($start, $end)->orderBy('id', 'desc');
                    }
                ])
                    ->when($search, function ($query) use ($search) {
                        return $query->where(function ($query) use ($search) {
                            $query->where('name', 'like', "%$search%")
                                ->orWhere('email', 'like', "%$search%")
                                ->orWhere('username', 'like', "%$search%")
                                ->orWhere('tel', 'like', "%$search%");
                        });
                    })->when($department, function ($query) use ($department) {
                        $department_arr = (array)get_company_deparent($department);
                        return $query->whereIn('department_id', array_unique($department_arr));
                    })->when(!is_administrator(), function ($query) {
                        return $query->where('id', '!=', config('auth.administrator'));
                    })->status(1)->orderBy(
                        $sort_field
                        , $sort)->paginate(
                        $prepage
                        , ['*']
                        , 'datatable.pagination.page'
                    );
            } else {
                $user = User::with([
                    'department',
                    'userMonthScores' => function ($query) use ($start, $end) {
                        $query->month($start, $end)->orderBy('month', 'desc');
                    },
                    'assessments' => function ($query) use ($start, $end) {
                        $query->month($start, $end)->orderBy('id', 'desc');
                    }
                ])
                    ->when($search, function ($query) use ($search) {
                        return $query->where(function ($query) use ($search) {
                            $query->where('name', 'like', "%$search%")
                                ->orWhere('email', 'like', "%$search%")
                                ->orWhere('username', 'like', "%$search%")
                                ->orWhere('tel', 'like', "%$search%");
                        });
                    })->when($department, function ($query) use ($department) {
                        $department_arr = (array)get_company_deparent($department);
                        return $query->whereIn('department_id', array_unique($department_arr));
                    })->when(!is_administrator(), function ($query) {
                        return $query->where('id', '!=', config('auth.administrator'));
                    })->where(function ($query) {
                        //获取用户所属分部所有部门
                        $query->whereIn('department_id', get_company_deparent(get_user_company_id()));
                    })->status(1)->orderBy(
                        $sort_field
                        , $sort)->paginate(
                        $prepage
                        , ['*']
                        , 'datatable.pagination.page'
                    );
            }
            if ($user) {
                foreach ($user as $key => $val) {
                    $user[$key]->company = $val->company();
                    $user[$key]->score = $val->userMonthScores->sum('score')
                        + $val->assessments->sum('score');
                }
            }
            $meta = [
                'field' => $sort_field,
                'sort' => $sort,
                'page' => $user->currentPage(),
                'pages' => $user->hasMorePages(),
                'perpage' => $prepage,
                'total' => $user->total()
            ];
            $data = $user->toArray();
            $data['meta'] = $meta;
            return response()->json($data);
        }
        set_redirect_url();
        return view('assessment.default.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!check_permission('assessment/assessments/create')) {
            return _404('无权操作！');
        }
        return view('assessment.default.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!check_permission('assessment/assessments/create')) {
            return _404('无权操作！');
        }
        $rule_id = $request->assessment_rule_id;
        $rule = AssessmentRule::find($rule_id);
        if (!$rule) {
            return _error('考核细则不存在');
        }
        $assessment = Assessment::create([
            'user_id' => $request->user_id,
            'assessment_rule_id' => $rule_id,
            'score' => $rule->score,
            'remark' => $request->remark,
            'assessment_user_id' => get_current_login_user_info()
        ]);
        if ($assessment) {
            activity()->performedOn($assessment)
                ->withProperties($assessment->toArray())
                ->log('添加成功');
            return _success();
        } else {
            return _error();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if (!check_permission('assessment/assessments/personal')) {
            return _404('无权操作！');
        }
        $user = User::with(['assessments', 'userMonthScores', 'department'])
            ->findOrFail(get_current_login_user_info());
        if ($request->ajax()) {
            $sort_field = $request->input('datatable.sort.field')
                ? $request->input('datatable.sort.field') : 'id';
            $sort = $request->input('datatable.sort.sort')
                ? $request->input('datatable.sort.sort') : 'desc';
            $prepage = $request->input('datatable.pagination.perpage')
                ? (int)$request->input('datatable.pagination.perpage') : 20;
            $start = $request->input('datatable.query.start');
            $end = $request->input('datatable.query.end');
            $list = $user->assessments()->month($start, $end)
                ->with(['user', 'assessmentRule', 'assessmentUser'])
                ->orderBy(
                    $sort_field
                    , $sort)->paginate(
                    $prepage
                    , ['*']
                    , 'datatable.pagination.page'
                );
            $meta = [
                'field' => $sort_field,
                'sort' => $sort,
                'page' => $list->currentPage(),
                'pages' => $list->hasMorePages(),
                'perpage' => $prepage,
                'total' => $list->total(),
                'date' => [$start, $end],
                'score' => $user->userMonthScores()
                        ->month($start,$end)->sum('score')
                        + $user->assessments()->month($start,$end)->sum('score')
            ];
            $data = $list->toArray();
            $data['meta'] = $meta;
            return response()->json($data);
        } else {
            $user->company = $user->company();
            if ($user->department || $user->company) {
                $user->department_info = ($user->company ? $user->company->name : '')
                    . ($user->department && $user->department->level == 3 ? '/' . $user->department->name : '');
            }
            //本月考核
            $userMonthScore = $user->userMonthScores()->month(date('Y-m'))->sum('score');
            $assessment = $user->assessments()->month(date('Y-m'))->sum('score');
            $monthScore = $userMonthScore + $assessment;
            //总考核
            $userAllMonthScore = $user->userMonthScores->sum('score');
            $assessmentAll = $user->assessments->sum('score');
            $allScore = $userAllMonthScore + $assessmentAll;
            $allMonth = [$user->userMonthScores->min('month'), $user->userMonthScores->max('month')];
            return view('assessment.default.personal', compact(['user', 'monthScore', 'allScore', 'allMonth']));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!check_permission('assessment/assessments/edit')) {
            return _404('无权操作！');
        }
        $assessment = Assessment::with('user', 'assessmentRule', 'assessmentUser')
            ->find($id);
        if ($assessment) {
            return view('assessment.default.edit', ['assessment' => $assessment]);
        } else {
            return _404('你访问的信息不存在');
        }
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
        if (!check_permission('assessment/assessments/edit')) {
            return _404('无权操作！');
        }
        $rule_id = $request->assessment_rule_id;
        $rule = AssessmentRule::find($rule_id);
        if (!$rule) {
            return _error('考核细则不存在');
        }
        $assessment = Assessment::find($id);
        if ($assessment) {
            $assessment->update([
                'user_id' => $request->user_id,
                'assessment_rule_id' => $rule_id,
                'score' => $rule->score,
                'remark' => $request->remark,
                'assessment_user_id' => get_current_login_user_info()
            ]);
            activity()->performedOn($assessment)
                ->withProperties($assessment->toArray())
                ->log('编辑成功');
            return _success();
        } else {
            return _error('你访问的信息不存在');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!check_permission('assessment/assessments/destroy')) {
            return _404('无权操作！');
        }
        $result = [
            'status' => 'success',
            'message' => '操作成功',
            'data' => '',
            'url' => get_redirect_url(),
        ];
        $dp = Assessment::find($id);
        if ($dp) {
            if ($dp->delete()) {
                activity()->performedOn($dp)
                    ->withProperties($dp->toArray())->log('删除成功');
            } else {
                $result['status'] = 'error';
                $result['message'] = '删除失败';
            }
        } else {
            $result['status'] = 'error';
            $result['message'] = '您操作的信息不存在';
        }
        return response()->json($result);
    }

    public function detail($user, Request $request)
    {
        if (!check_permission('assessment/user/assessments')) {
            return _404('无权操作！');
        }
        $user = User::with(['assessments', 'userMonthScores', 'department'])->findOrFail($user);
        if ($request->ajax()) {
            $sort_field = $request->input('datatable.sort.field')
                ? $request->input('datatable.sort.field') : 'id';
            $sort = $request->input('datatable.sort.sort')
                ? $request->input('datatable.sort.sort') : 'desc';
            $prepage = $request->input('datatable.pagination.perpage')
                ? (int)$request->input('datatable.pagination.perpage') : 20;
            $start = $request->input('datatable.query.start');
            $end = $request->input('datatable.query.end');
            $list = $user->assessments()->month($start, $end)
                ->with(['user', 'assessmentRule', 'assessmentUser'])
                ->orderBy(
                    $sort_field
                    , $sort)->paginate(
                    $prepage
                    , ['*']
                    , 'datatable.pagination.page'
                );
            $meta = [
                'field' => $sort_field,
                'sort' => $sort,
                'page' => $list->currentPage(),
                'pages' => $list->hasMorePages(),
                'perpage' => $prepage,
                'total' => $list->total(),
                'date' => [$start, $end],
                'score' => $user->userMonthScores()
                        ->month($start,$end)->sum('score')
                    + $user->assessments()->month($start,$end)->sum('score')
            ];
            $data = $list->toArray();
            $data['meta'] = $meta;
            return response()->json($data);
        } else {
            $user->company = $user->company();
            if ($user->department || $user->company) {
                $user->department_info = ($user->company ? $user->company->name : '')
                    . ($user->department && $user->department->level == 3 ? '/' . $user->department->name : '');
            }
            //本月考核
            $userMonthScore = $user->userMonthScores()->month(date('Y-m'))->sum('score');
            $assessment = $user->assessments()->month(date('Y-m'))->sum('score');
            $monthScore = $userMonthScore + $assessment;
            //总考核
            $userAllMonthScore = $user->userMonthScores->sum('score');
            $assessmentAll = $user->assessments->sum('score');
            $allScore = $userAllMonthScore + $assessmentAll;
            $allMonth = [$user->userMonthScores->min('month'), $user->userMonthScores->max('month')];
            return view('assessment.default.detail', compact(['user', 'monthScore', 'allScore', 'allMonth']));
        }
    }

    public function export(Request $request)
    {
        if (!check_permission('assessment/assessments/export')) {
            return _404('无权操作！');
        }
        Excel::create(date('Y-m-d') . '绩效汇总导出', function ($excel) use ($request) {
            $search = $request->input('search');
            $department = $request->input('department_id');
            $start = $request->input('start');
            $end = $request->input('end');
            if (is_administrator() || check_user_role(null, '总部管理员')) {
                $data = User::with([
                    'department',
                    'userMonthScores' => function ($query) use ($start, $end) {
                        $query->month($start, $end)->orderBy('month', 'desc');
                    },
                    'assessments' => function ($query) use ($start, $end) {
                        $query->month($start, $end)->orderBy('id', 'desc');
                    }
                ])
                    ->when($search, function ($query) use ($search) {
                        return $query->where(function ($query) use ($search) {
                            $query->where('name', 'like', "%$search%")
                                ->orWhere('email', 'like', "%$search%")
                                ->orWhere('username', 'like', "%$search%")
                                ->orWhere('tel', 'like', "%$search%");
                        });
                    })->when($department, function ($query) use ($department) {
                        $department_arr = (array)get_company_deparent($department);
                        return $query->whereIn('department_id', array_unique($department_arr));
                    })->when(!is_administrator(), function ($query) {
                        return $query->where('id', '!=', config('auth.administrator'));
                    })->status(1)->get();
            } else {
                $data = User::with([
                    'department',
                    'userMonthScores' => function ($query) use ($start, $end) {
                        $query->month($start, $end)->orderBy('month', 'desc');
                    },
                    'assessments' => function ($query) use ($start, $end) {
                        $query->month($start, $end)->orderBy('id', 'desc');
                    }
                ])
                    ->when($search, function ($query) use ($search) {
                        return $query->where(function ($query) use ($search) {
                            $query->where('name', 'like', "%$search%")
                                ->orWhere('email', 'like', "%$search%")
                                ->orWhere('username', 'like', "%$search%")
                                ->orWhere('tel', 'like', "%$search%");
                        });
                    })->when($department, function ($query) use ($department) {
                        $department_arr = (array)get_company_deparent($department);
                        return $query->whereIn('department_id', array_unique($department_arr));
                    })->when(!is_administrator(), function ($query) {
                        return $query->where('id', '!=', config('auth.administrator'));
                    })->where(function ($query) {
                        //获取用户所属分部所有部门
                        $query->whereIn('department_id', get_company_deparent(get_user_company_id()));
                    })->status(1)->get();
            }
            $date = [];
            if ($start && (!$end || $start == $end)) {
                $date[] = $start;
            } elseif ($start && $end && $start < $end) {
                $date[] = $start;
                $date[] = $end;
            }
            // Call them separately
            $excel->setDescription('绩效汇总');

            $excel->sheet('用户考核列表', function ($sheet) use ($data, $date) {
                $export = [
                    ['序号', '姓名', '部门', '时间', '考核结果', '状态']
                ];
                if ($data) {
                    foreach ($data as $key => $val) {
                        //获取所属部门
                        if ($val->department) {
                            if ($val->department->company_id) {
                                $company = Department::info($val->department->company_id, true);
                            } else {
                                $company = Department::info($val->department->id, true);
                            }
                        } else {
                            $company = null;
                        }
                        $export[] = [
                            $key + 1,
                            $val->name,
                            ($company ? $company->name : '')
                            . ($val->department && $val->department->level == 3 ? '/' . $val->department->name : ''),
                            !empty($date) ? arr2str($date, '~') : '所有时间',
                            $val->userMonthScores->sum('score') + $val->assessments->sum('score'),
                            $val->status ? '可用' : '禁用'
                        ];
                    }
                }
                // Sheet manipulation
                $sheet->fromArray($export, null, 'A1', false, false);
            });


        })->download('xlsx');
    }
}
