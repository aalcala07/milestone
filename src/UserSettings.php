<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{

    protected $table = "user_settings";

    protected $fillable = ['timezone', 'user_id'];
    
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
