<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function devices(){
        return $this->belongsToMany('App\Device');
    }
}
