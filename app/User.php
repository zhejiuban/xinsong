<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
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
}
