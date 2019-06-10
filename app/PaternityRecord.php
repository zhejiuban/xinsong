<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class PaternityRecord extends Model
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

    public function getClosedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->toDateString() : null;
    }

    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->toDateString() : null;
    }

    public function scopeBaseSearch($query
        , $search = null, $date = null, $project_id = null, $is_handle = null)
    {
        return $query->when($search, function ($query) use ($search) {
            return $query->where(function ($query) use ($search) {
                $query->where('question', 'like', "%$search%")
                    ->orWhere('solution', 'like', "%$search%")
                    ->orWhere('car_no', $search);
            });
        })->when($date, function ($query) use ($date) {
            return $query->whereBetween('closed_at', [
                date_start_end($date), date_start_end($date, 'end')
            ]);
        })->when($project_id, function ($query) use ($project_id) {
            return $query->where('project_id', $project_id);
        })->when($is_handle != '', function ($query) use ($is_handle) {
            return $query->where('is_handle', $is_handle);
        });
    }

    public function scopePersonalRecord($query)
    {
        return $query->where('user_id', get_current_login_user_info());
    }

    public function scopeCompanySearch($query)
    {
        return $query->whereIn('user_id', get_company_user(null, 'id', false, [1,0]));
    }

}
