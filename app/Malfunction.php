<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Malfunction extends Model
{
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function device()
    {
        return $this->belongsTo('App\Device');
    }

    public function phase()
    {
        return $this->belongsTo('App\ProjectPhase','project_phase_id');
    }

    public function getHandledAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->toDateString() : null;
    }

    public function scopeBaseSearch($query
        , $search = null, $date = null, $project_id = null, $device = null)
    {
        return $query->when($search, function ($query) use ($search) {
            return $query->where(
                'content', 'like',
                "%$search%"
            );
        })->when($date, function ($query) use ($date) {
            return $query->whereBetween('handled_at', [
                date_start_end($date), date_start_end($date, 'end')
            ]);
        })->when($project_id, function ($query) use ($project_id) {
            return $query->where('project_id', $project_id);
        })->when($device, function ($query) use ($device) {
            return $query->where('device_id', $device);
        });
    }

    /**
     * 获取个人发布的问题
     * @param $query
     * @return mixed
     */
    public function scopePersonalMalfunction($query)
    {
        return $query->where('user_id', get_current_login_user_info());
    }

    public function scopeCompanySearch($query){
        return $query->whereIn('user_id',get_company_user(null, 'id'));
    }
}
