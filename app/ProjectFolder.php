<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectFolder extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name','parent_id','project_id','user_id'
    ];
}
