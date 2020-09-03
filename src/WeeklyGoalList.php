<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WeeklyGoalList extends Model
{

    protected $fillable = ['name', 'user_id'];
    
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    // public function goalSet()
    // {
    //     return $this->belongsTo(\App\GoalSet::class);
    // }
}
