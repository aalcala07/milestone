<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WeeklyGoal extends Model
{

    protected $fillable = ['description', 'completion_type', 'completion_value', 'weekly_goal_list_id', 'goal_period_id', 'user_id'];
    
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function goalList()
    {
        return $this->belongsTo(\App\WeeklyGoalList::class);
    }
}
