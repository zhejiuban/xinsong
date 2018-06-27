<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMonthScore extends Model
{
    protected $fillable = [
        'user_id', 'month', 'score'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeMonth($query, $start = null, $end = null)
    {
        if ($start && (!$end || $start == $end)) {
            return $query->where('month', $start);
        } elseif ($start && $end && $start < $end) {
            return $query->where('month', '>=', $start)
                ->where('month', '<=', $end);
        } else {
            return $query;
        }
    }
}
