<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = [
        'user_id', 'assessment_rule_id', 'score', 'assessment_user_id', 'remark'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assessmentRule()
    {
        return $this->belongsTo(AssessmentRule::class);
    }

    public function assessmentUser()
    {
        return $this->belongsTo(User::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->toDateString() : null;
    }
    public function getUpdatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->toDateString() : null;
    }

    public function scopeMonth($query, $start = null, $end = null)
    {
        if ($start && (!$end || $start == $end)) {
            $year = Carbon::parse($start)->year;
            $month = Carbon::parse($start)->month;
            return $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
        } elseif ($start && $end && $start < $end) {
            $startYear = Carbon::parse($start)->year;
            $startMonth = Carbon::parse($start)->month;
            $endYear = Carbon::parse($end)->year;
            $endMonth = Carbon::parse($end)->month;
            return $query->whereYear('created_at', '>=', $startYear)
                ->whereYear('created_at', '<=', $endYear)
                ->whereMonth('created_at', '>=', $startMonth)
                ->whereMonth('created_at', '<=', $endMonth);
        } else {
            return $query;
        }
    }
}
