<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeClock;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TimeClockController extends Controller
{
    public function index()
    {
        $currentpayperiod = getPayPeriodDates(now());
        $lastperiodbegin = Carbon::parse($currentpayperiod['start_date'])->subDays(7);
        $previouspayperiod = getPayPeriodDates($lastperiodbegin);

        $user = Auth::user();
        
        // Find if the user is currently clocked in
        $currentAttendance = TimeClock::where('user_id', $user->id)
            ->whereNull('clock_out')->first();

       // Declare variable and set a value to prevent blade errors
       $lunchstatus = 200;


        // check if user is currently clocked in and if so, determine lunch status and logic for lunch clocks

        if($user->lunch_code === 3 && !is_null($currentAttendance)) { 

            if(is_null($currentAttendance->lunch_in) && is_null($currentAttendance->lunch_out)) {

                $lunchstatus = 0;

            } elseif(!is_null($currentAttendance->lunch_in) && is_null($currentAttendance->lunch_out)) {

                $lunchstatus = 1;

            } elseif(!is_null($currentAttendance->lunch_in) && !is_null($currentAttendance->lunch_out)) {

                $lunchstatus = 2;

            } else {

                $lunchstatus = 100;
            }
        }
   

        // Get past attendance logs
        $history = TimeClock::where('user_id', $user->id)->whereBetween('clock_in', [$currentpayperiod['start_date'], $currentpayperiod['end_date']])->orderBy('created_at', 'desc')->get();


        return view('timeclock.index', compact('currentAttendance', 'lunchstatus', 'history'));
    }


    public function toggle(Request $request)
    {
        $user = Auth::user();

        // Check for an active session
        $attendance = TimeClock::where('user_id', $user->id)
            ->whereNull('clock_out')
            ->first();

        if ($attendance) {
            // Clock Out
            $attendance->update([
                'clock_out' => Carbon::now()
            ]);
            return redirect()->back()->with('success', 'Successfully clocked out!');
        } else {
            // Clock In
            TimeClock::create([
                'user_id' => $user->id,
                'clock_in' => Carbon::now()
            ]);
            return redirect()->back()->with('success', 'Successfully clocked in!');
        }
    }


    public function lunchtoggle(Request $request)
    {

        $user = Auth::user();

        if($user->lunch_code === 3)
        {


                // Check for an active session
                $lunch = TimeClock::where('user_id', $user->id)
                    ->whereNull('clock_out')->first();




                if (is_null($lunch->lunch_in)) {

      
                        // Clock Out
                        $lunch->update([
                            'lunch_in' => Carbon::now()
                        ]);

                        return redirect()->back()->with('success', 'Enjoy Your Lunch!');
                } else {
        
                        // Clock In
                        $lunch->update([
                            'lunch_out' => Carbon::now()
                        ]);

                        return redirect()->back()->with('success', 'Welcome Back!');
                }



        }
    }
    // public function report()
    // {
    //    $payperiod = getPayPeriodDates('2026-06-09');

    //    $test = TimeClock::where('user_id', 1)->whereBetween('clock_in', [$payperiod['start_date'], $payperiod['end_date']])->get();

    // dd($test);

    // }





    public function clockeventdetail($id, $period, $user)
    {
        $event = TimeClock::where('id', $id)->with('user')->get();

        $nextday = TimeClock::where('id', $id)->whereRaw('DATE(clock_in) != DATE(clock_out)')->count();

        return view('admin.attendance.punchdetails', compact('event', 'nextday', 'period', 'user'));

    }



    public function editpunch(Request $request, $id, $period, $user)
    {

        $request->merge([
            'clock_in' => str_replace('T', ' ', $request->input('clock_in')),
            'clock_out' => str_replace('T', ' ', $request->input('clock_out')),
        ]);

        $request->validate([
            'clock_in' => 'required|date_format:Y-m-d H:i',
            'clock_out' => 'required|date_format:Y-m-d H:i|after:clock_in'

        ]);

        $clockin = Carbon::parse($request->input('clock_in'));
        $clockout = Carbon::parse($request->input('clock_out'));

        if (! $clockin->isSameDay($clockout)) {
            throw ValidationException::withMessages([
                'clock_out' => ['Clock in and clock out are not on the same day'],
            ]);

        } else {

            if($request->filled('lunch_in')) {

                $lunchin =  $clockin->format('Y-m-d ') . $request->input('lunch_in');

                $request->merge([
                        'lunch_in' => $lunchin,
                ]);
            }

            if($request->filled('lunch_out')) {

                $lunchout =  $clockout->format('Y-m-d ') . $request->input('lunch_out');

                $request->merge([
                'lunch_out' => $lunchout,
                ]);
            }
        }
     
        $updatepunch = TimeClock::find($id);
        $updatepunch->update($request->all());

        return redirect()->route('attendance.details', ['period' => $period, 'id' => $user])->with('success', 'Time Clock Punch Updated Successfully');

    }



    public function destroy(Request $request, $period, $user)
    {
        $id = $request->input('id');
        $destroy = TimeClock::findOrFail($id);
        $destroy->delete();

        return redirect()->route('attendance.details', ['period' => $period, 'id' => $user])->with('success', 'Time Clock Punch Deleted');

    }



}
