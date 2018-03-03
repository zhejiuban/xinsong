<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id', 'task_id', 'content', 'started_at', 'finished_at',
        'last_finished_at', 'user_id', 'is_finished', 'reason', 'created_at', 'updated_at',
        'deleted_at', 'sort', 'delay', 'status',
    ];

    public function task()
    {
        return $this->belongsTo('App\Task');
    }

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getStartedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->toDateString() : null;
    }

    public function getFinishedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->toDateString() : null;
    }

    public function getLastFinishedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->toDateString() : null;
    }
    public function scopeStatus($query, $status){
        return  $query->where('status',$status);
    }
    public function scopeProcessing($query, $date)
    {
        if (!$date) {
            $date = current_date();
        }
        return $query->whereNull('last_finished_at')
            ->whereNotNull('started_at')
            ->whereNotNull('finished_at')
            ->whereDate('started_at', '<=', $date)
            ->where('status',1)
            ->orderBy('sort', 'asc');
    }

    public static function autoPlanAfterDelay($plan, $day)
    {
        if (is_numeric($plan)) {
            $plan = self::find($plan);
        }
        //获取当前计划之后的所有的计划
        $list = self::whereDate('started_at', '>=', $plan->started_at)
            ->whereNotNull('started_at')
            ->whereNotNull('finished_at')
            ->where('sort', '>', $plan->sort)->get();
        if ($list) {
            $temp_start = null;
            $temp_end = null;
            foreach ($list as $key => $val) {
                $update = [
                    'started_at' => Carbon::parse($val->started_at)->addDay($day),
                    'finished_at' => Carbon::parse($val->finished_at)->addDay($day)
                ];
                if ($val->delay) {
                    //$update['delay'] = $val->delay + $day;
                }
                if ($val->last_finished_at) {
                    $update['last_finished_at'] = Carbon::parse($val->last_finished_at)->addDay($day);
                }
                $val->update($update);
            }
        }
    }

    public static function autoPlanDeforeSub($plan, $day)
    {
        if (is_numeric($plan)) {
            $plan = self::find($plan);
        }
        //获取当前计划之后的所有的计划
        $list = self::whereDate('started_at', '>=', $plan->started_at)
            ->whereNotNull('started_at')
            ->whereNotNull('finished_at')
            ->where('sort', '>', $plan->sort)->get();
        if ($list) {
            $temp_start = null;
            $temp_end = null;
            foreach ($list as $key => $val) {
                $update = [
                    'started_at' => Carbon::parse($val->started_at)->subDay($day),
                    'finished_at' => Carbon::parse($val->finished_at)->subDay($day)
                ];
                if ($val->delay) {
                    //$update['delay'] = $val->delay - $day;
                }
                if ($val->last_finished_at) {
                    $update['last_finished_at'] = Carbon::parse($val->last_finished_at)->subDay($day);
                }
                $val->update($update);
            }
        }
    }
}
