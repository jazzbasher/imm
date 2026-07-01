<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeOffRequest;
use App\Models\TimeClock;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function attendancedash()
    {

        
        /******************************************************************************************** *
        * ***************************        Current Pay Period      ******************************** *
        * ********************************************************************************************/

        $thispayperiod = getPayPeriodDates(now());


        $calendarevents = TimeOffRequest::whereBetween('start', [$thispayperiod['start_date'], $thispayperiod['end_date']])->where('status', 1)->count();

        $timeclockhours = TimeClock::whereBetween('clock_in', [$thispayperiod['start_date'], $thispayperiod['end_date']])->whereNotNull('clock_out')->get();

        $totalclockhours = $timeclockhours->sum(function ($item) {
            // Assuming 'start_time' and 'end_time' are your datetime columns
            $start = Carbon::parse($item->clock_in);
            $end = Carbon::parse($item->clock_out);
            
            return $end->diffInHours($start, true);
        });



        /******************************************************************************************** *
        * ***************************           Last Pay Period      ******************************** *
        * ********************************************************************************************/


        $lastperiodbegin = Carbon::parse($thispayperiod['start_date'])->subDays(7);

        $lastpayperiod = getPayPeriodDates($lastperiodbegin);

        $lastcalendarevents = TimeOffRequest::whereBetween('start', [$lastpayperiod['start_date'], $lastpayperiod['end_date']])->where('status', 1)->count();

        $lasttimeclockhours = TimeClock::whereBetween('clock_in', [$lastpayperiod['start_date'], $lastpayperiod['end_date']])->whereNotNull('clock_out')->get();


        $lasttotalclockhours = $lasttimeclockhours->sum(function ($item) {
            // Assuming 'start_time' and 'end_time' are your datetime columns
            $start = Carbon::parse($item->clock_in);
            $end = Carbon::parse($item->clock_out);
            
            return $end->diffInHours($start, true);
        });

        return view('admin.attendance.dashboard', compact('thispayperiod', 'calendarevents', 'totalclockhours', 'lastpayperiod', 'lastcalendarevents', 'lasttotalclockhours'));
    }






    public function payperiodattendance($period)
    {
        $currentpayperiod = getPayPeriodDates(now());
        $lastperiodbegin = Carbon::parse($currentpayperiod['start_date'])->subDays(7);
        $previouspayperiod = getPayPeriodDates($lastperiodbegin);
        $targethours = 9.5;


        /***********************************************************************/
        /*************   Define payperiod from view paramater passed ***********/


        if($period === 'current') {

            $payperiod = $currentpayperiod;

        } elseif($period === 'last') {

            $payperiod = $previouspayperiod;

        } else {
            return redirect()->back()->with('error', 'Invalid PayPeriod!');
        }



        /***********************************************************************/
        /******   Queries for timeclock and time off requests in payperiod *****/


        $timeclock = TimeClock::whereBetween('clock_in', [$payperiod['start_date'], $payperiod['end_date']])->whereNotNull('clock_out')->with('user')->get();


        $calendar = TimeOffRequest::whereBetween('start', [$payperiod['start_date'], $payperiod['end_date']])->where('status', 1)->with('user')->get();


        $longclocks = TImeClock::selectRaw('user_id, COUNT(*) as longclocks')->whereBetween('clock_in', [$payperiod['start_date'], $payperiod['end_date']])->whereNotNull('clock_out')->whereRaw("TIMESTAMPDIFF(HOUR, clock_in, clock_out) >= ?", [$targethours])->with('user')->groupBy('user_id')->get();


        $clockindups = TImeClock::selectRaw('user_id, DATE(clock_in) as date, COUNT(DISTINCT DATE(clock_in)) as clockdups')->whereBetween('clock_in', [$payperiod['start_date'], $payperiod['end_date']])->with('user')->groupBy('user_id', 'date')->havingRaw('COUNT(*) > 1')->get();



        /***********************************************************************/
        /************   Calendar logic, groupby user and total hours ***********/


        $calendarhours = $calendar->map(function ($event) {

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


        
        /***********************************************************************/
        /**********   TimeClock logic, groupby user and total hours  ***********/


        $timewithhours = $timeclock->map(function ($punch) {

            $start = Carbon::parse($punch->clock_in);
            $end = Carbon::parse($punch->clock_out);


            if($punch->user->lunch_code == 3) { 

                if($punch->lunch_in && $punch->lunch_out) { 

                $lunchin = Carbon::parse($punch->lunch_in);
                $lunchout = Carbon::parse($punch->lunch_out);

                $lunchsubtract = round($lunchin->floatDiffInHours($lunchout),2);

                $punch->lunchhours = $lunchsubtract;

                } else {

                    $punch->lunchhours = 0;
                    $lunchsubtract = 0;
                }

            } elseif($punch->user->lunch_code == 2) { 

                $punch->lunchhours = 1;
                $lunchsubtract = 1;

            } elseif($punch->user->lunch_code == 1 || $punch->user->lunch_code == 0) {

                $punch->lunchhours = 0;
                $lunchsubtract = 0;
            }

            
            $hoursPassed = $start->diffInHours($end, true);
                     
            $punch->ttlhours = round($hoursPassed, 2);

            $punch->nethours = $hoursPassed - $lunchsubtract;

            
            return $punch;
        });


        /***********************************************************************/
        //         Returns hours clocked minus lunch grouped by user


        $grpsumclock = $timewithhours->groupBy('user.name')
            ->map(function ($group) {
                return [
                    'net_clockedhours' => $group->sum('ttlhours'),
                    'nethours' => $group->sum('nethours')
                ];
        });

        $grpsumcalendar = $calendarhours->groupBy('user.name')
            ->map(function ($group) {
                return [
                    'net_calendarhours' => $group->sum('cldrhours')
                ];
        });

        $grplongclocks = $longclocks->groupBy('user.name')
            ->map(function ($group) {
                return [
                    'net_longclocks' => $group->sum('longclocks')
                ];
        });

        $grpclockdups = $clockindups->groupBy('user.name')
            ->map(function ($group) {
                return [
                    'net_clockdups' => $group->sum('clockdups')
                ];
        });


        $merged = $grpsumclock->mergeRecursive($grpsumcalendar)->mergeRecursive($grplongclocks)->mergeRecursive($grpclockdups);


        return view('admin.attendance.summary', compact('merged', 'period'));
        
    }





    public function userattendance($period, $id)
    {
        $currentpayperiod = getPayPeriodDates(now());
        $lastperiodbegin = Carbon::parse($currentpayperiod['start_date'])->subDays(7);
        $previouspayperiod = getPayPeriodDates($lastperiodbegin);
        $targethours = 9.5;



        /***********************************************************************/
        /*************   Define payperiod from view paramater passed ***********/


        if($period === 'current') {

            $payperiod = $currentpayperiod;

        } elseif($period === 'last') {

            $payperiod = $previouspayperiod;

        } else {
            return redirect()->back()->with('error', 'Invalid PayPeriod!');
        }



        $user = User::where('name', $id)->with('lunchcode')->first();
        $uid = $user->id;
        $userlunchcode = $user->lunch_code;
        $userlunchdesc = $user->lunchcode->description;
        $userhourly = $user->hourly;
        $username = $user->name;
        $periodstart = Carbon::parse($payperiod['start_date'])->format('m/d/y');
        $periodend = Carbon::parse($payperiod['end_date'])->format('m/d/y');



        if($userhourly === 1) {

            $usertimeclockdata = TimeClock::where('user_id', $uid)->whereBetween('clock_in', [$payperiod['start_date'], $payperiod['end_date']])->whereNotNull('clock_out')->with('user')->orderBy('clock_in', 'ASC')->get();

            $clockindups = TImeClock::where('user_id', $uid)->selectRaw('DATE(clock_in) as date, COUNT(DISTINCT DATE(clock_in)) as clockdups')->whereBetween('clock_in', [$payperiod['start_date'], $payperiod['end_date']])->groupBy('date')->havingRaw('COUNT(*) > 1')->get();

        } else {

            $usertimeclockdata = collect();
            $clockindups = collect();
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

                    if($start->diffInHours($end, true) > 4) {

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


        return view('admin.attendance.userattendance', compact('usertimeclock', 'userhourly', 'userlunchcode', 'username', 'periodstart', 'periodend', 'userlunchdesc', 'usercalendarhours', 'clockindups', 'period'));


    }


}
