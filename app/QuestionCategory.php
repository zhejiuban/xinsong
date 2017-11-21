<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionCategory extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'remark', 'status'
    ];
    public static function lists(){
        return self::where('status',1)->get();
    }
    public function questions(){
        return $this->hasMany('App\Question');
    }
}
