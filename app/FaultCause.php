<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FaultCause extends Model
{
    use SoftDeletes;
    protected $fillable = [
    	'name','status'
    ];
    public function productFaults(){
    	return $this->hasMany('App\ProductFault');
    }

    public static function lists($status = 1){
        return self::where('status',$status)->get();
    }
}
