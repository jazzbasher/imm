<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeClock;
use Carbon\Carbon;
use App\Models\User;
use App\Models\TimeOffRequest;
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






    public function viewlastpayperiod()
    {
        $me = Auth::user();

        if($me->hourly === 1) {

        $currentpayperiod = getPayPeriodDates(now());
        $lastperiodbegin = Carbon::parse($currentpayperiod['start_date'])->subDays(7);
        $previouspayperiod = getPayPeriodDates($lastperiodbegin);
        $targethours = 9.5;



        /***********************************************************************/
        /*************   Define payperiod from view paramater passed ***********/


            $payperiod = $previouspayperiod;

       

        $user = User::where('id', $me->id)->with('lunchcode')->first();
        $uid = $user->id;
        $userlunchcode = $user->lunch_code;
        $userlunchdesc = $user->lunchcode->description;
        $userhourly = $user->hourly;
        $username = $user->name;
        $periodstart = Carbon::parse($payperiod['start_date'])->format('m/d/y');
        $periodend = Carbon::parse($payperiod['end_date'])->format('m/d/y');



        if($userhourly === 1) {

            $usertimeclockdata = TimeClock::where('user_id', $uid)->whereBetween('clock_in', [$payperiod['start_date'], $payperiod['end_date']])->whereNotNull('clock_out')->with('user')->orderBy('clock_in', 'ASC')->get();

            

        } else {

            $usertimeclockdata = collect();
          
        }


        /***********************************************************************/
        /*************              Time Clock Section               ***********/
        /***********************************************************************/


        if($usertimeclockdata->isNotEmpty()) {

            $usertimeclock = $usertimeclockdata->map(function ($punch) use ($userlunchcode) {

                $start = Carbon::parse($punch->clock_in);
                $end = Carbon::parse($punch->clock_out);


                if($userlunchcode === 3) { 

                    if($punch->lunch_in && $punch->lunch_out) { 

                    $lunchin = Carbon::parse($punch->lunch_in);
                    $lunchout = Carbon::parse($punch->lunch_out);

                    $lunchsubtract = round($lunchin->floatDiffInHours($lunchout),2);

                    } else {

                        $lunchsubtract = 0;
                    }


                } elseif($userlunchcode === 2) { 

                    if($start->diffInHours($end, true) >= 7) {

                        $lunchsubtract = 1;
                    } else {

                        $lunchsubtract = 0;
                    }
                    

                } elseif($userlunchcode === 1 || $userlunchcode === 0) {

                    $lunchsubtract = 0;
                }

                
                $hoursPassed = $start->diffInHours($end, true);
                         
                $punch->ttlhours = round($hoursPassed, );

                $punch->nethours = $hoursPassed - $lunchsubtract;

                $punch->lunchtotal = $lunchsubtract;
      
                return $punch;
            });


        } else {

            $usertimeclock = collect();
        }



        /***********************************************************************/
        /*************        Calendar/Leave Request Section         ***********/
        /***********************************************************************/


        $calendardata = TimeOffRequest::where('user_id', $uid)->whereBetween('start', [$payperiod['start_date'], $payperiod['end_date']])->where('status', 1)->with('user')->orderBy('start', 'ASC')->get();



        if($calendardata->isNotEmpty()) {

            $usercalendarhours = $calendardata->map(function ($event) {

                $eventstart = Carbon::parse($event->start);
                $eventend = Carbon::parse($event->end);

            
                if($event->allDay === 0) {

                    $calchours = $eventstart->diffInHours($eventend, true);

                } elseif($event->allDay === 1) {

                    $calchours = (($eventstart->diffInDays($eventend) + 1) * 8);

                } else {

                    $calchours = 0;
                }
                     

            $event->cldrhours = round($calchours, 2);
            
            return $event;

            });

        } else {

            $usercalendarhours = collect();
        }


        return view('timeclock.lastpayperiodreport', compact('usertimeclock', 'userhourly', 'userlunchcode', 'username', 'periodstart', 'periodend', 'userlunchdesc', 'usercalendarhours'));



        } else {

            dd('you are not hourly');

        }

         
    }
        



}
