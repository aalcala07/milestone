<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Milestone\Goal;
use Faker\Generator as Faker;

$factory->define(Goal::class, function (Faker $faker) {
    return [
        'user_id' => factory(\App\User::class),
        'goal_list_id' => factory(\Milestone\GoalList::class),
        'goal' => $faker->word,
        'date_completed' => $faker->date(),
    ];
});
