<?php

use Illuminate\Database\Seeder;
use App\WeeklyGoalList;

class GoalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WeeklyGoalList::insert([
            [
                'name' => 'List A',
                'user_id' => 1
            ],
            [
                'name' => 'List B',
                'user_id' => 1
            ]
        ]);

        // DB::table('goal_sets')->insert([
        //     'user_id' => 1,
        //     'start_date' => '2020-01-01',
        //     'end_date' => '2020-02-15',
        // ]);

        // DB::table('goal_lists')->insert([
        //     'user_id' => 1,
        //     'goal_set_id' => 1,
        //     'name' => 'Fitness Goals',
        // ]);

        // DB::table('goals')->insert([
        //     'user_id' => 1,
        //     'goal_list_id' => 1,
        //     'goal' => "Lose 20 lbs",
        // ]);

        // DB::table('goals')->insert([
        //     'user_id' => 1,
        //     'goal_list_id' => 1,
        //     'goal' => "Run a marathon",
        // ]);

        // DB::table('goals')->insert([
        //     'user_id' => 1,
        //     'goal_list_id' => 1,
        //     'goal' => "Do 100 pushups",
        // ]);

    }
}
