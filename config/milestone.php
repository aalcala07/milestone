<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Base Route
    |--------------------------------------------------------------------------
    |
    | The path where Milestone will be accessible.
    |
    */

    'path' => env('MILESTONE_PATH_NAME', 'milestone'),

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | Specify the user model for authentication and defining the relationship
    | between a user and goals.
    |
    */

    'user' => Illuminate\Foundation\Auth\User::class,

    /*
    |--------------------------------------------------------------------------
    | Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will be attached to every route in Milestone.
    |
    */

    'middleware' => [
        'web',
        'auth',
    ],


];