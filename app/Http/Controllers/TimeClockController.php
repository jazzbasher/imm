<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeClock;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TimeClockController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Find if the user is currently clocked in
        $currentAttendance = TimeClock::where('user_id', $user->id)
            ->whereNull('clock_out')
            ->first();

        // Get past attendance logs
        $history = TimeClock::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();



        return view('timeclock.index', compact('currentAttendance', 'history'));
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
            return redirect()->back()->with('status', 'Successfully clocked out!');
        } else {
            // Clock In
            TimeClock::create([
                'user_id' => $user->id,
                'clock_in' => Carbon::now()
            ]);
            return redirect()->back()->with('status', 'Successfully clocked in!');
        }
    }
}
