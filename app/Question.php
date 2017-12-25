<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;

    public function category()
    {
        return $this->belongsTo('App\QuestionCategory', 'question_category_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function receiveUser()
    {
        return $this->belongsTo('App\User', 'receive_user_id');
    }

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function getFileAttribute($value)
    {
        if ($value) {
            return File::whereIn('id', str2arr($value))->get();
        }
        return $value;
    }

    public function scopeBaseQuestion($query
        , $status = null, $search = null, $date = null, $project_id = null){
        return $query->when($status, function ($query) use ($status) {
            return $query->where('status', $status);
        }, function ($query) use ($status) {
            if ($status !== null) {
                return $query->where('status', $status);
            }
        })->when($search,function ($query) use ($search){
            return $query->where(
                'title', 'like',
                "%$search%"
            );
        })->when($date,function ($query) use ($date) {
            return $query->whereBetween('created_at', [
                date_start_end($date),date_start_end($date,'end')
            ]);
        })->when($project_id,function ($query) use ($project_id) {
            return $query->where('project_id', $project_id);
        });
    }

    /**
     * 获取个人发布的问题
     * @param $query
     * @return mixed
     */
    public function scopePersonalQuestion($query)
    {
        return $query->where('user_id', get_current_login_user_info());
    }

    /**
     * 获取个人待处理问题
     * @param $query
     * @return mixed
     */
    public function scopeReceiveQuestion($query){
        return $query->where('receive_user_id', get_current_login_user_info());
    }

    public function scopeCompanyQuestion($query){
        return $query->whereIn('user_id',get_company_user(null, 'id'));
    }
}
