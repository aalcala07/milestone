<?php

namespace Milestone;

use Illuminate\Database\Eloquent\Model;

class WeeklyGoalSetGoal extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'completion_value',
        'weekly_goal_id',
        'weekly_goal_set_id',
    ];


    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function goal()
    {
        return $this->belongsTo('Milestone\WeeklyGoal', 'weekly_goal_id');
    }
}
