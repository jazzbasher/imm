<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAccounting
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in and is accounting
        if ($request->user() && $request->user()->accounting()) {
            return $next($request);
        }

        
        // abort(403, 'You do not have accounting access.');
        return redirect()->back()->with('error', 'You do not have accounting access.');
    }
}
