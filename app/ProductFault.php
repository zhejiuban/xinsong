<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class ProductFault extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id','car_no','fault_cause_id','occurrenced_at','user_id'
    ];

    public function faultCause()
    {
        return $this->belongsTo('App\FaultCause', 'fault_cause_id');
    }

    public function project(){
        return $this->belongsTo('App\Project');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function getOccurrencedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->toDateString() : null;
    }

    public function scopeBaseSearch($query
        , $search = null, $date = null, $project_id = null, $cause = null)
    {
        return $query->when($search, function ($query) use ($search) {
            return $query->where('car_no', $search);
        })->when($date, function ($query) use ($date) {
            return $query->whereBetween('occurrenced_at', [
                date_start_end($date), date_start_end($date, 'end')
            ]);
        })->when($project_id, function ($query) use ($project_id) {
            return $query->where('project_id', $project_id);
        })->when($cause, function ($query) use ($cause) {
            return $query->where('fault_cause_id', $cause);
        });
    }

    /**
     * 获取个人发布
     * @param $query
     * @return mixed
     */
    public function scopePersonalMalfunction($query)
    {
        return $query->where('user_id', get_current_login_user_info());
    }

    public function scopeCompanySearch($query)
    {
        return $query->whereIn('user_id', get_company_user(null, 'id', false, [1,0]));
    }
}
