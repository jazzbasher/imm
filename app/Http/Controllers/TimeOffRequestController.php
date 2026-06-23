<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeOffRequest;
use App\Notifications\TimeOffRequested;
use App\Notifications\TimeOffAction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
                       ->where('end', '<=', $request->end)->where('status', 1)
                       ->get(['id', 'title', 'start', 'end', 'allDay']);


        $formattedEvents = $events->map(function ($event) {
            return [
                'id'    => $event->id,
                'title' => $event->title,
                // Format to ISO 8601 string (e.g., 2026-06-15T14:30:00)
                'start' => Carbon::parse($event->start)->toIso8601String(), 
                'end'   => Carbon::parse($event->end)->toIso8601String(),
                'allDay'=> $event->allDay,
             
            ];
        });


        return response()->json($formattedEvents);
    }


    public function requestform()
    {
        return view('timeoffrequest.leaverequest');
    }




    public function leavestore(Request $request)
    {
        $allday = $request->input('allDay');
        $timemanager = User::where('timemanager', 1)->first();
        $loggeduser = Auth::user()->name;

         $request->validate([
            'start' => 'required_if:allDay,1|date|nullable',
            'end' => 'required_if:allDay,1|date|after_or_equal:start|nullable',
            'partialstartdata' => 'required_if:allDay,0|date|nullable',
            'allDay' => 'required|boolean',
            'starttime' => 'required_if:allDay,0|nullable',
            'endtime' => 'required_if:allDay,0|nullable',
            'type'   => 'nullable',
            'user_id' => 'required',
            'reason'  => 'nullable'
        ], [

            'starttime.required_if' => 'A start time must be entered if partial day selected',
            'endtime.required_if' => 'An end time must be entered if partial day selected',
            'partialstartdata.required_if' => 'A date must be entered with time if partial day selected',

        ]);

         $from = Carbon::parse($request->input('start'))->format('m/d/y');
         $to = Carbon::parse($request->input('end'))->format('m/d/y');

        if($allday == 0)
        {
            $start = $request->input('partialstartdata') . ' ' . $request->input('starttime') . ':00';
            $end = $request->input('partialstartdata') . ' ' . $request->input('endtime') . ':00';

            $request->merge([
                'start' => $start,
                'end'   => $end,
            ]);
        }


        $request->merge([
            'manager_id' => $timemanager->id,
            'title' => $loggeduser,
            'allDay' => $allday,
        ]);


    

        $timeOffRequest = ['user' => $loggeduser, 'from' => $from, 'to' => $to];

        $timeoffsubmit = TimeOffRequest::create($request->all());

        if($timeoffsubmit)
        {
            $timemanager->notify(new TimeOffRequested($timeOffRequest));
        }

         

        return redirect()->route('calendar')->with('success','Leave Submitted For Approval!');


    }

    public function pendingrequests()
    {
        $requests = TimeOffRequest::where('status', 0)->with('user')->with('requesttype')->get();

        return view('admin.attendance.pendingrequests', compact('requests'));
    }


    // public function submitforapproval()
    // {
    //     $manager = User::where('is_admin', 1)->first();

    //     $timeOffRequest = ['id' => '10293', 'total' => 49.99];

    //     $manager->notify(new TimeOffRequested($timeOffRequest));
    // }



    public function adminapprove(Request $request, $id)
    {
       
        $request->validate([
            'status' => 'required|integer',
        ]);


        $approve = TimeOffRequest::findOrFail($id);

        $user = $approve->user_id;
        $useremail = User::where('id', $user)->first();
        

        $approve->status = $request->input('status');
        $approve->save();

        
            $timeOffAction = ['action' => 'approved'];

            $useremail->notify(new TimeOffAction($timeOffAction));
        

        return redirect()->back()->with('success', 'Request approved, added to calendar, and requestor notified!');
    }


    public function adminreject(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|integer',
        ]);


        $approve = TimeOffRequest::findOrFail($id);

        $user = $approve->user_id;
        $useremail = User::where('id', $user)->first();

        $approve->status = $request->input('status');
        $approve->save();

        $timeOffAction = ['action' => 'denied'];

        $useremail->notify(new TimeOffAction($timeOffAction));

        return redirect()->back()->with('success', 'Request denied and was not added to calendar.  Requestor notified.');
    }


    public function businesstime()
    {
        $start = Carbon::parse('2026-06-17 12:00:00');
        $end = Carbon::parse('2026-06-17 17:00:00');

        $test = $start->diffInBusinessHours($end);

        dd($test);
    }


   
}
