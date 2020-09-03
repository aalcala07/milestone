<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WeeklyGoalSet extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'start',
        'end',
    ];

    protected $appends = ['start_local_friendly', 'end_local_friendly'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
    ];


    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function goals()
    {
        return $this->hasMany('App\WeeklyGoalSetGoal');
    }

    
    public function getStartLocalFriendlyAttribute()
    {
        return (new Carbon($this->start, config('app.timezone')))->setTimezone(session('timezone'))->format('n/j/y');
    }

    public function getEndLocalFriendlyAttribute()
    {
        return (new Carbon($this->end, config('app.timezone')))->setTimezone(session('timezone'))->format('n/j/y');
    }
}
