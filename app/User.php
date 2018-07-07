<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable{
        notify as protected laravelNotify;
    }
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'tel', 'department_id', 'avatar', 'sex', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function notify($instance){
        $this->increment('notification_count');
        $this->laravelNotify($instance);
    }
    //标记所有未读消息为已读
    public function markAllAsRead(){
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }
    //标记某个未读消息为已读
    public function markSingleAsRead($id){
        if($this->notifications()->where('id',$id)
            ->whereNull('read_at')->update(['read_at' => Carbon::now()])){
            $this->decrement('notification_count');
        }
    }

    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function projects()
    {
        return $this->belongsToMany('App\Project');
    }

    public function leaderTasks()
    {
        return $this->hasMany('App\Task', 'leader');
    }

    public function dynamics()
    {
        return $this->hasMany('App\Dynamic');
    }

    public function files(){
        return $this->hasMany('App\File');
    }

    public function questions(){
        return $this->hasMany('App\Question');
    }

    public function receiveQuestions(){
        return $this->hasMany('App\Question','receive_user_id');
    }

    public function malfunctions(){
        return $this->hasMany('App\Malfunction');
    }

    public function userMonthScores(){
        return $this->hasMany(UserMonthScore::class);
    }

    public function assessments(){
        return $this->hasMany(Assessment::class);
    }
    /**
     * 获取某个用户所在公司
     * @return null
     */
    public function company($field = true)
    {
        if ($this->department) {
            if ($this->department->company_id) {
                return Department::info($this->department->company_id, $field);
            } else {
                return Department::info($this->department->id, $field);
            }
        } else {
            return null;
        }
    }
    public function scopeStatus($query,$status = [0,1]){
        return $query->whereIn('status',(array) $status);
    }
}
