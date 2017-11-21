<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function category(){
        return $this->belongsTo('App\QuestionCategory','question_category_id');
    }
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function receiveUser(){
        return $this->belongsTo('App\User','receive_user_id');
    }
    public function project(){
        return $this->belongsTo('App\Project');
    }
    public function getFileAttribute($value)
    {
        if($value){
            return File::whereIn('id',str2arr($value))->get();
        }
        return $value;
    }
}
