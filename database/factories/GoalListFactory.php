<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\GoalList;
use Faker\Generator as Faker;

$factory->define(GoalList::class, function (Faker $faker) {
    return [
        'user_id' => factory(\App\User::class),
        'goal_set_id' => factory(\App\GoalSet::class),
        'name' => $faker->name,
    ];
});
