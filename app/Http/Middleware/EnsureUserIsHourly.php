<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsHourly
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in and is hourly employee
        if ($request->user() && $request->user()->hourly()) {
            return $next($request);
        }

        
        // abort(403, 'You are not an hourly employee.');
        return redirect()->route('calendar')->with('error', 'You are not an hourly employee.');
    }


}
