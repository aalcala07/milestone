<?php

namespace Milestone;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class GoalPeriod extends Model
{

    protected $fillable = ['name', 'start', 'end', 'user_id'];

    protected $appends = ['start_local_friendly', 'end_local_friendly', 'total_weeks'];
    
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function goals()
    {
        return $this->hasMany(\Milestone\WeeklyGoal::class);
    }

    public function getStartLocalFriendlyAttribute()
    {
        return (new Carbon($this->start, config('app.timezone')))->setTimezone(session('timezone'))->format('n/j/y');
    }

    public function getEndLocalFriendlyAttribute()
    {
        return (new Carbon($this->end, config('app.timezone')))->setTimezone(session('timezone'))->format('n/j/y');
    }

    // Make sure period does not overlap with another goal period
    public static function isPeriodStartDateValid($start)
    {
        $periods = GoalPeriod::where('user_id', auth()->user()->id)->get();
        foreach ($periods as $period) {
            if ($start >= $period->start && $start <= $period->end) {
                return true;
            }
        }
        return false;
    }

    protected function getTotalWeeksAttribute()
    {
        return ((new Carbon($this->end))->diffInDays(new Carbon($this->start)) + 1) / 7;
    }
}
