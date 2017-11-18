<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Device extends Model
{
//  use LogsActivity;
    protected $fillable = [
        'name', 'remark','status'
    ];
    public function projects(){
        return $this->belongsToMany('App\Project');
    }
    public static function lists(){
        return self::where('status',1)->get();
    }
}
