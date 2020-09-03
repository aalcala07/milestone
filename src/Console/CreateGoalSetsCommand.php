<?php

namespace Milestone\Console;

use Milestone\UserSettings;
use Milestone\WeeklyGoal;
use Milestone\WeeklyGoalSet;
use Milestone\WeeklyGoalSetGoal;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateGoalSetsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'milestone:createSets';

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
        $users = \App\User::all();
        foreach ($users as $user) {

            $goals = WeeklyGoal::where('user_id', $user->id);

            // Skip if the user doesn't have any goals
            if (empty($goals)) {
                continue;
            }

            $settings = UserSettings::where('user_id', $user->id)->first();
            $start = Carbon::now($settings->timezone ?? config('app.timezone'))->startOfWeek()->setTimezone(config('app.timezone'));
            
            $goalSets = WeeklyGoalSet::where('user_id', $user->id)->orderBy('start', 'desc')->get();
            if ($goalSets->count()) {
                $nextStart = (new Carbon($goalSets->first()->start))->addWeek()->startOfWeek();

                // Skip if the it's too soon to create the next goal set
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

            $goalSetGoals = [];
            foreach ($goals as $weeklyGoal) {
                $goalSetGoals[] = [
                    'completion_value' => $weeklyGoal->completion_value,
                    'weekly_goal_id' => $weeklyGoal->id,
                    'weekly_goal_set_id' => $set->id
                ];
            }

            WeeklyGoalSetGoal::insert($goalSetGoals);

            echo "Goal Set created for {$user->id} with " . count($goalSetGoals) . " goals.\n";
        }
    }
}
