<?php

namespace Milestone\Http\Controllers;

use Milestone\UserSettings;
use Milestone\GoalPeriod;
use Milestone\WeeklyGoal;
use Milestone\Calendar\Calendar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class OnboardingController
{
    public function index()
    {
        $settings = UserSettings::where('user_id', auth()->user()->id)->first();
        if ($settings && $settings->onboarded) {
            return redirect()->route('goalSets');
        }

        list($startDates, $endDates) = Calendar::weekDates(Carbon::now(session('timezone'))->startOfWeek(), 104);
        
        return view('milestone::onboarding', compact(['startDates', 'endDates']));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:150',
            'start' => 'required',
            'end' => 'required',
        ]);
        
        $startDate = $request->input('start');
        $endDate = $request->input('end');
        
        $validator->after(function ($validator) use ($startDate, $endDate) {
            if (GoalPeriod::isPeriodStartDateValid($startDate)) {
                $validator->errors()->add('start', 'The date cannot overlap with another goal period.');
            }
            if ($startDate > $endDate) {
                $validator->errors()->add('end', 'The end date must be after the start date.');
            }
            // TODO: make sure the start date is at the start of the week
            // TODO: make sure the end date is at the end of the week
        });

        if ($validator->fails()) {
            return redirect(route('onboarding.index'))
                ->withErrors($validator)
                ->withInput();
        }

        // dd($_POST);

        $period = new GoalPeriod([
            'name' => $request->input('name'), 
            'start' => $request->input('start'), 
            'end' => $request->input('end'), 
            'user_id' => auth()->user()->id
        ]);

        $goals = collect();

        foreach($request->input('goals') as $item) {
            if (!$item['description'] || !$item['completion_type']) {
                continue;
            } 

            $goal = new WeeklyGoal([
                'description' => $item['description'],
                'completion_type' => $item['completion_type'],
                'user_id' => auth()->user()->id
            ]);

            if ($item['completion_type'] !== 'boolean') {
                $goal->completion_value = $item['completion_value'];
            }

            $goals->push($goal);
        }

        $settings = UserSettings::where('user_id', auth()->user()->id)->first();
        if (!$settings) {
            $settings = new UserSettings();
            $settings->user_id = auth()->user()->id;
        }
        $settings->onboarded = true;

        try {
            DB::transaction(function () use ($period, $goals, $settings) {
                $period->save();
                $goals->each( function($item) use ($period){
                    $item->goal_period_id = $period->id;
                    $item->save();
                });
                $settings->save();
            }, 3);
        } catch (\Exception $e) {
            Log::error($e->getMessage);
            return redirect(route('onboarding.index'))->with('error_message', 'Error! Unable to create goal period and goals.');
        }
        
        Artisan::call('milestone:createSets');

        return redirect(route('goalSets'))->with('success_message', 'Success! Your goals have been created.');
    }
}
