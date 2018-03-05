<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Project extends Model
{
    use SoftDeletes;

    //设备类型
    public function devices()
    {
        return $this->belongsToMany('App\Device')
            ->withPivot('number');
    }

    //建设阶段
    public function phases()
    {
        return $this->hasMany('App\ProjectPhase')->orderBy('id');
    }

    //项目参与人
    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    //项目相关文件
    public function files()
    {
        return $this->belongsToMany('App\File')->withPivot('project_folder_id');
    }

    //所属部门
    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    //新松项目负责人
    public function leaderUser()
    {
        return $this->belongsTo('App\User', 'leader');
    }

    //分部负责人
    public function companyUser()
    {
        return $this->belongsTo('App\User', 'subcompany_leader');
    }

    //现场代理负责人
    public function agentUser()
    {
        return $this->belongsTo('App\User', 'agent');
    }

    //项目任务
    public function tasks()
    {
        return $this->hasMany('App\Task');
    }

    //项目执行动态
    public function dynamics()
    {
        return $this->hasMany('App\Dynamic');
    }

    //项目相关问题
    public function questions()
    {
        return $this->hasMany('App\Question');
    }

    public function folders()
    {
        return $this->hasMany('App\ProjectFolder');
    }

    public function malfunctions()
    {
        return $this->hasMany('App\Malfunction');
    }

    public function plans(){
        return $this->hasMany('App\Plan');
    }

    public function committedPlans(){
        return $this->hasMany('App\Plan')->where('status',1);
    }

    /**
     * 任务已完成百分比
     * @return float
     */
    public function finishPrecent()
    {
        $all_task = $this->tasks()->count();
        $finish_task = $this->tasks()->where('status', 1)->count();
        $processing_task = $this->tasks()->where('status', 0)->count();
        if ($all_task) {
            $finish_precent = round($finish_task / $all_task * 100);
        } else {
            $finish_precent = 0;
        }
        return $finish_precent;
    }

    /**
     * 更新项目状态
     * @param null $status
     * @return bool
     */
    public function updateStatus($status = null)
    {
        if ($status === null) {
            //自动判断
            //判断所有阶段状态
            $all = $this->phases()->count();
            $unstart = $this->phases()->where('status', 0)->count();
            $end = $this->phases()->where('status', 2)->count();
            if ($all == $unstart) {
                $this->status = 0;
            } elseif ($all == $end) {
                $this->status = 2;
            } else {
                $this->status = 1;
            }
        } else {
            //直接设置
            $this->status = $status;
        }
        return $this->save();
    }

    public function checkDayLog($user = null)
    {
        if (!$user) {
            $user = get_current_login_user_info();
        }
        return $this->dynamics()->whereBetween('created_at', [
            date_start_end(), date_start_end(null, 'end')
        ])->where('user_id', $user)->first();
    }

    /**
     * 检测用户是否有该项目的任务
     * @param null $user
     * @return Model|null|static
     */
    public function checkUserTask($user = null)
    {
        if (!$user) {
            $user = get_current_login_user_info();
        }
        return $this->tasks()->whereDate(
            'start_at', '<=', Carbon::now()->toDateString()
        )->where('leader', $user)->where(
            'status', '=', 0
        )->first();
    }

    public function checkUserTaskDayDynamic($user = null)
    {
        if (!$user) {
            $user = get_current_login_user_info();
        }
        $date = current_date();
        return $this->tasks()->where(function ($query) use ($date) {
            $query->where(function ($query) use ($date) {
                $query->where('status', 0)->whereDate(
                    'start_at', '<=', $date
                );
            })->orWhere(function ($query) use ($date) {
                $query->where('status', 1)->whereDate(
                    'start_at', '<=', $date
                )->whereDate(
                    'finished_at', '>=', $date
                );
            });
        })->where('leader', $user)->whereDoesntHave('dynamics', function ($query) {
            return $query->whereBetween('created_at', [
                date_start_end(), date_start_end(null, 'end')
            ]);
        })->first();
    }

    /**
     * 获取当日未上传日志的任务
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getUnAddUserTaskDynamic($date = null)
    {
        $date = $date ? $date : current_date();
        return $this->tasks()->needAddDynamic($date)->doesntHaveDynamic($date)->get();
    }

    public function scopeBaseSearch($query
        , $status = null, $search = null
        , $department_id = null, $phase_name = null, $phase_status = null)
    {
        return $query->when($department_id, function ($query) use ($department_id) {
            return $query->where('department_id', $department_id);
        })->when($status, function ($query) use ($status) {
            return $query->where('status', $status);
        }, function ($query) use ($status) {
            if ($status !== null) {
                return $query->where('status', $status);
            }
        })->when($search, function ($query) use ($search) {
            return $query->where(function ($query) use ($search) {
                $query->where(
                    'title', 'like',
                    "%{$search}%"
                )->orWhere('no', 'like',
                    "%{$search}%");
            });
        })->when($phase_name && !is_null($phase_status), function ($query) use ($phase_name, $phase_status) {
            return $query->whereHas('phases', function ($query) use ($phase_name, $phase_status) {
                $query->where('name', $phase_name)->where('status', $phase_status);
            });
        });
    }

    public function scopeCompanySearch($query, $company)
    {
        return $query->where('department_id', $company);
    }

    public function addDefaulfolders($project_id = null)
    {
        if (!$project_id) {
            $project_id = $this->id;
        }
        $default = config('common.project_default_folder');
        $user_id = get_current_login_user_info();
        foreach ($default as $key=>$val){
            $data = [
                'name'=>$val['name'],
                'parent_id'=>0,
                'project_id'=>$project_id,
                'user_id'=>$user_id
            ];
            $project_folder = ProjectFolder::create($data);
            if($val['children']){
                foreach ($val['children'] as $k=>$v){
                    $child = [
                        'name'=>$v['name'],
                        'parent_id'=>$project_folder->id,
                        'project_id'=>$project_id,
                        'user_id'=>$user_id
                    ];
                    ProjectFolder::create($child);
                }
            }
        }
    }
}
