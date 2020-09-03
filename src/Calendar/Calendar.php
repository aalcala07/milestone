<?php

namespace Milestone\Calendar;

use Carbon\Carbon;

class Calendar
{

    public static function weekDates(Carbon $start, $limit)
    {
        // TODO: Set start date to next available start date

        $startDates = collect();
        $endDates = collect();
        while($startDates->count() < $limit) {
            $startDates->push([
                'name' => $start->toFormattedDateString(),
                'value' => $start->copy()->setTimezone(config('app.timezone'))->toDateTimeString()
            ]);
            $end = $start->copy()->endOfWeek();
            $endDates->push([
                'name' => $end->toFormattedDateString(),
                'value' => $end->copy()->setTimezone(config('app.timezone'))->toDateTimeString()
            ]);
            $start->addWeek();
        }

        return [$startDates, $endDates];
    }

}