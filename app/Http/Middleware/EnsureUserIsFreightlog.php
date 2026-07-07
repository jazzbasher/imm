<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsFreightlog
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in and authorized to enter freightlogs
        if ($request->user() && $request->user()->freight()) {
            return $next($request);
        }

        
        // abort(403, 'You are not an hourly employee.');
        return redirect()->back()->with('error', 'You are not authorized to add freight charges.');
    }
}
