<?php

use Carbon\Carbon;

function getPayPeriodDates($punchDate) 
{
    $startDate = Carbon::parse(env('PAY_PERIOD_START_DATE', '2026-06-07'));
    $punch = Carbon::parse($punchDate);

    // Get number of full 14-day periods since the starting base date
    $periodCount = (int) floor($startDate->diffInDays($punch) / 14);

    // Calculate exact start and end of that specific pay period
    $periodStart = $startDate->copy()->addDays($periodCount * 14);
    $periodEnd = $periodStart->copy()->addDays(13)->endOfDay(); // Inclusive of 14th day

    return [
        'start_date' => $periodStart,
        'end_date' => $periodEnd,
    ];
}