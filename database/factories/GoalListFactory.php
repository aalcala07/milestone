<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Milestone\GoalList;
use Faker\Generator as Faker;

$factory->define(GoalList::class, function (Faker $faker) {
    return [
        'user_id' => factory(\App\User::class),
        'goal_set_id' => factory(\Milestone\GoalSet::class),
        'name' => $faker->name,
    ];
});
