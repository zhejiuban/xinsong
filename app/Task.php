<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'project_id','user_id','start_at','end_at',
        'leader','content','is_need_plan','project_phase_id',
        'status'
    ];
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function project(){
        return $this->belongsTo('App\Project');
    }
    public function phase(){
        return $this->belongsTo('App\ProjectPhase','project_phase_id');
    }
    public function leaderUser(){
        return $this->belongsTo('App\User','leader');
    }
    public function plans(){
        return $this->hasMany('App\Plan');
    }
    public function getStartAtAttribute($value){
        return Carbon::parse($value)->toDateString();
    }
    public function getEndAtAttribute($value){
        return Carbon::parse($value)->toDateString();
    }
    public function dynamics(){
        return $this->hasMany('App\Dynamic');
    }
    /**
     * 接收任务
     * @param $task
     * @return bool
     */
    public function received(){
        if(!$this->received_at && $this->leader == get_current_login_user_info() ){
            $this->received_at = Carbon::now();
            $this->save();
            //记录日志
            activity('项目日志')->performedOn(Project::find($this->project_id))
                ->withProperties($this->toArray())
                ->log('接收任务');
        }
        return true;
    }
}
