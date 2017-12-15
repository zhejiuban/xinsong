<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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
            'status','=',0
        )->first();
    }
}
