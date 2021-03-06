<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ProjectPhase extends Model
{
    protected $fillable = [
        'project_id','name','started_at','finished_at',
        'status','created_at','updated_at'
    ];
    public function project(){
        return $this->belongsTo('App\Project');
    }
    public function getStartedAtAttribute($value){
        return $value ? Carbon::parse($value)->toDateString() : null;
    }
    public function getFinishedAtAttribute($value){
        return $value ? Carbon::parse($value)->toDateString() : null;
    }
    public function tasks(){
        return $this->hasMany('App\Task');
    }
}
