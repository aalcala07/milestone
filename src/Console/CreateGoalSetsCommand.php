<?php

namespace Milestone\Console\Commands;

use Milestone\User;
use Milestone\UserSettings;
use Milestone\WeeklyGoalSet;
use Milestone\WeeklyGoalSetGoal;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateGoalSets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'goals:createSets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create goal sets for users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::all();
        foreach ($users as $user) {
            if (empty($user->weeklyGoals)) {
                continue;
            }

            $settings = UserSettings::where('user_id', 1)->first();
            $start = Carbon::now($settings->timezone ?? config('app.timezone'))->startOfWeek()->setTimezone(config('app.timezone'));
            
            if ($user->goalSets->first()) {
                $nextStart = (new Carbon($user->goalSets->first()->start))->addWeek()->startOfWeek();
                if ($nextStart->toDateTimeString() >= $start->toDateTimeString()) {
                    continue;
                }
            }

            $set = new WeeklyGoalSet([
                'start' => $start->toDateTimeString(),
                'end' => $start->copy()->endOfWeek()->toDateTimeString(),
                'user_id' => $user->id
            ]);

            $set->save();

            $goals = [];
            foreach ($user->weeklyGoals as $weeklyGoal) {
                $goals[] = [
                    'completion_value' => $weeklyGoal->completion_value,
                    'weekly_goal_id' => $weeklyGoal->id,
                    'weekly_goal_set_id' => $set->id
                ];
            }

            WeeklyGoalSetGoal::insert($goals);

            echo "Goal Set created for {$user->name} with " . count($goals) . " goals.\n";
        }
    }
}
