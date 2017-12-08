<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;
    //设备类型
    public function devices(){
        return $this->belongsToMany('App\Device')
            ->withPivot('number');
    }
    //建设阶段
    public function phases(){
        return $this->hasMany('App\ProjectPhase')->orderBy('id');
    }
    //项目参与人
    public function users(){
        return $this->belongsToMany('App\User');
    }
    //项目相关文件
    public function files(){
        return $this->belongsToMany('App\File');
    }
    //所属部门
    public function department(){
        return $this->belongsTo('App\Department');
    }
    //项目负责人
    public function leaderUser(){
        return $this->belongsTo('App\User','leader');
    }
    //现场代理负责人
    public function agentUser(){
        return $this->belongsTo('App\User','agent');
    }
    //项目任务
    public function tasks(){
        return $this->hasMany('App\Task');
    }
    //项目执行动态
    public function dynamics(){
        return $this->hasMany('App\Dynamic');
    }
    //项目相关问题
    public function questions(){
        return $this->hasMany('App\Question');
    }

    /**
     * 任务已完成百分比
     * @return float
     */
    public function finishPrecent(){
        $all_task = $this->tasks()->count();
        $finish_task = $this->tasks()->where('status',1)->count();
        $processing_task = $this->tasks()->where('status',0)->count();
        if($all_task){
            $finish_precent = round($finish_task / $all_task *100);
        }else{
            $finish_precent = 0;
        }
        return $finish_precent;
    }
}
