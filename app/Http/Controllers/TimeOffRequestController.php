<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeOffRequest;
use App\Notifications\TimeOffRequested;
use App\Models\User;
use Carbon\Carbon;

class TimeOffRequestController extends Controller
{
     public function index()
    {
        // Render the main calendar view page
        return view('timeoffrequest.timeoffcalendar');
    }

    public function getEvents(Request $request)
    {
        // FullCalendar automatically sends 'start' and 'end' query parameters
        $events = TimeOffRequest::where('start', '>=', $request->start)
                       ->where('end', '<=', $request->end)
                       ->get(['id', 'title', 'start', 'end']);


        $formattedEvents = $events->map(function ($event) {
            return [
                'id'    => $event->id,
                'title' => $event->title,
                // Format to ISO 8601 string (e.g., 2026-06-15T14:30:00)
                'start' => Carbon::parse($event->start)->toIso8601String(), 
                'end'   => Carbon::parse($event->end)->toIso8601String(),
                'allDay'=> false,
             
            ];
        });


        return response()->json($formattedEvents);
    }


    public function submitforapproval()
    {
        $manager = User::where('is_admin', 1)->first();

        $timeOffRequest = ['id' => '10293', 'total' => 49.99];

        $manager->notify(new TimeOffRequested($timeOffRequest));
    }



   
}
