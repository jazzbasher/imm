<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function sendResetLink(Request $request)
    {

        $request->validate(['email' => 'required|email']);

  
        $status = Password::broker()->sendResetLink(
            $request->only('email')
        );

     
        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Password reset link sent to your email.'], 200);
        }

        return response()->json(['error' => 'Could not find a user with that email address.'], 404);
    }
    
}
