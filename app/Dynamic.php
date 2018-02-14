<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dynamic extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id','project_id','content','onsite_user','task_id','created_at','fill'
    ];
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function project(){
        return $this->belongsTo('App\Project');
    }
    public function task(){
        return $this->belongsTo('App\Task');
    }
    public function scopeCompanySearch($query)
    {
        return $query->whereIn('user_id', get_company_user(null, 'id'));
    }
}
