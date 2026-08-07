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
                       ->get(['id', 'title', 'start', 'end', 'reason', 'allDay']);


        $formattedEvents = $events->map(function ($event) {

            if($event->allDay === 1) {

                $alld = Carbon::parse($event->end)->addDay()->toIso8601String();

            } else {

                $alld = Carbon::parse($event->end)->toIso8601String();
            }

            return [
                'id'    => $event->id,
                'title' => $event->title,
                'reason' => $event->reason,
                // Format to ISO 8601 string (e.g., 2026-06-15T14:30:00)
                'start' => Carbon::parse($event->start)->toIso8601String(), 
                'end'   => $alld,
                'allDay'=> $event->allDay,
             
            ];
        });


        return response()->json($formattedEvents);
    }


    public function requestform()
    {
        return view('timeoffrequest.leaverequest');
    }

    public function adminrequest()
    {
        $employees = User::where('active', 1)->whereNotIn('name', ['House Account', 'Flight Safety'])->orderBy('name', 'ASC')->pluck('name', 'id');

        return view('admin.attendance.employeetimeoff', compact('employees'));
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
            'endtime' => 'required_if:allDay,0|after:starttime|nullable',
            'type'   => 'nullable',
            'user_id' => 'required',
            'reason'  => 'nullable'
        ], [

            'starttime.required_if' => 'A start time must be entered if partial day selected',
            'endtime.required_if' => 'An end time must be entered if partial day selected',
            'endtime.after' => 'The end time must be after start time if partial day selected',
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


    // ***************  ADMIN Store of Employee time off ********************** //
    // ***************  No approval routing or notification needed ************ //

    public function adminrequeststore(Request $request)
    {
        $allday = $request->input('allDay');
 
        $request->validate([
            'user_id' => 'required'
        ]);

        $usr = $request->input('user_id');

        $empname = User::where('id', $usr)->value('name');



         $request->validate([
            'start' => 'required_if:allDay,1|date|nullable',
            'end' => 'required_if:allDay,1|date|after_or_equal:start|nullable',
            'partialstartdata' => 'required_if:allDay,0|date|nullable',
            'allDay' => 'required|boolean',
            'starttime' => 'required_if:allDay,0|nullable',
            'endtime' => 'required_if:allDay,0|after:starttime|nullable',
            'type'   => 'nullable',
            'reason'  => 'nullable',
            'manager_id'  => 'required'
        ], [

            'starttime.required_if' => 'A start time must be entered if partial day selected',
            'endtime.required_if' => 'An end time must be entered if partial day selected',
            'endtime.after' => 'The end time must be after start time if partial day selected',
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
            'title' => $empname,
            'allDay' => $allday,
            'status' => 1
        ]);


    

        $timeoffsubmit = TimeOffRequest::create($request->all());

         

        return redirect()->route('calendar')->with('success','Employee leave added to calendar!');


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

        return redirect()->back()->with('deny', 'Request successfully denied and NOT added to calendar.  Requestor notified.');
    }


    public function businesstime()
    {
        $start = Carbon::parse('2026-06-17 12:00:00');
        $end = Carbon::parse('2026-06-17 17:00:00');

        $test = $start->diffInBusinessHours($end);

        dd($test);
    }


    public function calendardetail($id, $period, $user)
    {
        $cals = TimeOffRequest::where('id', $id)->with('user')->with('manager')->with('requesttype')->get();

        return view('admin.attendance.calendardetails', compact('cals', 'period', 'user'));
    }


    public function editleaverequest(Request $request, $id, $period, $user)
    {


        $allday = $request->input('allDay');

        $request->merge([
            'start' => str_replace('T', ' ', $request->input('start')),
            'end' => str_replace('T', ' ', $request->input('end')),
        ]);


        if($allday == 0) {

            $request->validate([
                'start' => 'required|date_format:Y-m-d H:i',
                'end' => 'required|date_format:Y-m-d H:i|after_or_equal:start'
            ]);

        } else {

            $request->validate([
                'start' => 'required|date_format:Y-m-d',
                'end' => 'required|date_format:Y-m-d|after_or_equal:start'
            ]);

        }
        
        

        $updateleave = TimeOffRequest::find($id);
        $updateleave->update($request->all());

         return redirect()->route('attendance.details', ['period' => $period, 'id' => $user])->with('success', 'Leave Request Updated Successfully');

    }


    public function destroy(Request $request, $period, $user)
    {
        $id = $request->input('id');
        $destroy = TimeOffRequest::findOrFail($id);
        $destroy->delete();


        return redirect()->route('attendance.details', ['period' => $period, 'id' => $user])->with('success', 'Leave Request Deleted');
    }

 
   
}
