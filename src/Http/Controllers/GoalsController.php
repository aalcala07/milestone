<?php

namespace Milestone\Http\Controllers;

use Milestone\WeeklyGoal;
use Milestone\WeeklyGoalSet;
use Milestone\WeeklyGoalSetGoal;
use Milestone\GoalPeriod;
use Milestone\Calendar\Calendar;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class GoalsController
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $period = GoalPeriod::where('user_id', auth()->user()->id)
            ->where('end', '>', Carbon::now(config('app.timezone')))
            ->orderBy('start', 'asc')
            ->first();

        if ($period) {
            return redirect()->route('goals.periods.show', $period->id);
        }

        $otherPeriods = GoalPeriod::where('user_id', auth()->user()->id)->where('end', '<', Carbon::now(config('app.timezone')))->orderBy('start', 'desc')->get();
        $goals = collect();
        $stats = null;

        return view('milestone::goals.periods', compact('period', 'otherPeriods', 'goals', 'stats'));
    }

    public function sets(Request $request, WeeklyGoalSet $goalSet)
    {
        $set = null;
        $otherSets = null;
        $showOne = false;

        if (!$goalSet->id) {
            $sets = auth()->user()->goalSets;
            $set = $sets->shift();
            $otherSets = $sets;
        } else {
            $showOne = true;
            $set = $goalSet;
            $otherSets = auth()->user()->goalSets;
        }

        return view ('milestone::goals.sets', compact('showOne', 'set', 'otherSets'));
    }

    public function updateSet(Request $request)
    {
        $goals = $request->input('goals');

        $errors = 0;

        foreach ($goals as $id => $value) {
            $goalSetGoal = WeeklyGoalSetGoal::find($id);
            if ($goalSetGoal->goal->completion_type === 'boolean') {
                $goalSetGoal->complete = $value === "1";
            } else {
                $goalSetGoal->progress_value = $value;
                $goalSetGoal->complete = $value >= $goalSetGoal->goal->completion_value;
            }

            if (!$goalSetGoal->save()) {
                $errors++;
            }
        }

        if (!$errors) {
            
            return redirect()->back()->with('success_message', 'Success! Goal set saved.');
        } else {
            return redirect()->back()->with('error_message', 'Error! Unable to save goal set.');
        }
    }

    public function import(Request $request)
    {
        $period = $request->has('period') ? GoalPeriod::find($request->input('period')) : null;
        // $selectablePeriods = $request->has('period') ? GoalPeriod::find($request->input('period')) : null;
        $periods = GoalPeriod::all();
        return view('milestone::goals.import', compact('period', 'periods'));
    }

    public function storeImport(Request $request)
    {
        Validator::make($request->all(), [
            'period_id' => 'required',
            'csv_file' => 'required'
        ])->validate();
        

        $file = $request->file('csv_file');
        $csv = array_map('str_getcsv', file($file));
        $goals = [];

        foreach ($csv as $columns) {
            $goals[] = [
                'description' => $columns[0],
                'completion_type' => $columns[1],
                'completion_value' => $columns[2],
                'weekly_goal_list_id' => 1,
                'goal_period_id' => $request->input('period_id'),
                'user_id' => auth()->user()->id
            ];
        }

        if (WeeklyGoal::insert($goals)) {
            return redirect(route('goals.periods.show', $request->input('period_id')))->with('success_message', 'Success! ' . count($goals) . " goals have been imported.");
        } else {
            $params = !$request->has('selectable_periods') ? [ 'period' => $request->input('period_id') ] : null;
            return redirect(route('goals.import', $params))->with('error_message', 'Error! Unable to import goals.');
        }
    }

    public function showPeriod(Request $request, GoalPeriod $goalPeriod)
    {
        $period = $goalPeriod;
        // dd($period->total_weeks);
        // $stats = $period->stats();
        $stats = [
            'total_points' => 0,
            'total_points_possible' => 0,
            'percent_complete' => 0
        ];
        $goals = [];

        foreach ($period->goals as $goal) {
            $goal = $goal->toArray();
            // $goal['total_points_possible'] = ($goal['completion_type'] === 'boolean' ? 1 : $goal['completion_value']) * $period->total_weeks;
            $goal['points'] = 0;
            $goals[$goal['id']] = $goal;
        }

        // $stats['total_points_possible'] = $period->total_weeks * count($goals);
        $sets = WeeklyGoalSet::where('user_id', auth()->user()->id)->get();

        foreach ($sets as $set) {
            if ($set->start < $period->start || $set->start > $period->end) {
                continue;
            }
            
            foreach ($set->goals as $goal) {
                if (!isset($goals[$goal->weekly_goal_id])) {
                    continue;
                }
                $stats['total_points_possible']++;
                if ($goal->completed || $goal->progress_value >= $goal->completion_value) {
                    $goals[$goal->weekly_goal_id]['points']++;
                    $stats['total_points']++;
                }
            }
        }

        $weeksRemaining = $period->total_weeks - $sets->count();
        $stats['total_points_possible'] += $weeksRemaining * count($goals);
        $stats['percent_complete'] = $stats['total_points_possible'] ? round($stats['total_points'] / $stats['total_points_possible'] * 100) : 0;

        $goals = collect($goals);

        $otherPeriods = GoalPeriod::where('user_id', auth()->user()->id)->get();
        return view('milestone::goals.periods', compact('period', 'otherPeriods', 'goals', 'stats'));
    }

    public function createPeriod()
    {
        // TODO: Set start date to next available start date
        list($startDates, $endDates) = Calendar::weekDates(Carbon::now(session('timezone'))->startOfWeek(), 104);
        return view('milestone::goals.createPeriod', compact('startDates', 'endDates'));
    }

    public function storePeriod(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:150',
            'start' => 'required',
            'end' => 'required'
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
            return redirect(route('goals.periods.create'))
                ->withErrors($validator)
                ->withInput();
        }
        
        $result = GoalPeriod::insert([
            'name' => $request->input('name'), 
            'start' => $request->input('start'), 
            'end' => $request->input('end'), 
            'user_id' => auth()->user()->id
        ]);

        if ($result) {
            return redirect(route('goals.periods'))->with('success_message', 'Success! Goal period created.');
        } else {
            return redirect(route('goals.periods'))->with('error_message', 'Error! Unable to create goal period.');
        }
    }


}
