<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentRule extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'content', 'remark', 'status', 'score'
    ];

    public static function lists($status = 1)
    {
        return self::status($status)->orderBy('id', 'desc')->get();
    }

    public function scopeStatus($query, $status = 1)
    {
        return $query->whereIn('status', (array)$status);
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }
}
