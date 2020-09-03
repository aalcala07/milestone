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

    'timezones' => [
        'America/New_York' => 'Eastern Time - New York',
        'America/Chicago' => 'Central Time - Chicago',
        'America/Denver' => 'Mountain Time - Denver',
        'America/Phoenix' => 'Mountain Time - Phoenix (no daylight savings)',
        'America/Los_Angeles' => 'Pacific Time - Los Angeles',
        'America/Anchorage' => 'Alaska Time - Anchorage',
        'America/Adak' => 'Hawaii-Aleutian - Adak',
        'Pacific/Honolulu' => 'Hawaii-Aleutian Time - Honolulu (no daylight savings)'
    ]

];