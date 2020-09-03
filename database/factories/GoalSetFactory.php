<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Milestone\GoalSet;
use Faker\Generator as Faker;

$factory->define(GoalSet::class, function (Faker $faker) {
    return [
        'user_id' => factory(\App\User::class),
        'start_date' => $faker->date(),
        'end_date' => $faker->date(),
    ];
});
