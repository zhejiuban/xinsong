<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id','task_id','content','started_at','finished_at',
        'last_finished_at','user_id','is_finished','reason','created_at','updated_at',
        'deleted_at','sort'
    ];

    public function task()
    {
        return $this->belongsTo('App\Task');
    }

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function user(){
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
}
