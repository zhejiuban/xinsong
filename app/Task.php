<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'project_id', 'user_id', 'start_at', 'end_at',
        'leader', 'content', 'is_need_plan', 'project_phase_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function phase()
    {
        return $this->belongsTo('App\ProjectPhase', 'project_phase_id');
    }

    public function leaderUser()
    {
        return $this->belongsTo('App\User', 'leader');
    }

    public function plans()
    {
        return $this->hasMany('App\Plan');
    }

    public function getStartAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->toDateString() : null;
    }

    public function getEndAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->toDateString() : null;
    }

    public function dynamics()
    {
        return $this->hasMany('App\Dynamic');
    }

    public function getBuildedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->toDateString() : null;
    }

    public function getLeavedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->toDateString() : null;
    }

    /**
     * 接收任务
     * @param $task
     * @return bool
     */
    public function received()
    {
        if (!$this->received_at && $this->leader == get_current_login_user_info()) {
            $this->received_at = Carbon::now();
            $this->save();
            //记录日志
            activity('项目日志')->performedOn(Project::find($this->project_id))
                ->withProperties($this->toArray())
                ->log('接收任务');
        }
        return true;
    }

    /**
     * 获取需要上传日志的任务
     * @param $query
     * @param $date
     * @param $project_id
     * @param $user
     * @return mixed
     */
    public function scopeNeedAddDynamic($query, $date, $project_id = null, $user = null)
    {
        return $query->when($project_id, function ($query) use ($project_id) {
            if (is_array($project_id)) {
                return $query->whereIn('project_id', $project_id);
            }
            return $query->where('project_id', $project_id);
        })->when($user, function ($query) use ($user) {
            if (is_array($user)) {
                return $query->whereIn('leader', $user);
            }
            return $query->where('leader', $user);
        })->where(function ($query) use ($date) {
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
        });
    }

    /**
     * 获取没有管理日志的任务
     * @param $query
     * @param $date
     * @return mixed
     */
    public function scopeDoesntHaveDynamic($query, $date)
    {
        return $query->whereDoesntHave('dynamics', function ($query) use ($date) {
            $query->whereBetween('created_at', [
                date_start_end($date), date_start_end($date, 'end')
            ]);
        });
    }
}
