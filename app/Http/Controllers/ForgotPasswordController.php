<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function sendResetLink(Request $request)
    {
        // 1. Validate the email
        $request->validate(['email' => 'required|email']);

        // 2. Send the reset link via Laravel's Password broker
        $status = Password::broker()->sendResetLink(
            $request->only('email')
        );

        // 3. Return a response
        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Password reset link sent to your email.'], 200);
        }

        return response()->json(['error' => 'Could not find a user with that email address.'], 404);
    }
    
}
