<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create()
    {
        return view('admin.users.create'); // Return your form view here
    }

    public function store(Request $request)
    {
        // 1. Validate the form payload
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'outside_sales'   => ['required'],
            'hourly'   => ['required'],
        ]);
  
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'outside_sales' => $validated['outside_sales'],
            'hourly' => $validated['hourly'],
        ]);

        
        return redirect()->route('admin.users.create')->with('success', 'User registered successfully!');
    }

    public function manageusers()
    {
        $users = User::all();

        return view('admin.users.manage', compact('users'));

    }

    public function editPassword(User $user)
    {
        return view('admin.users.changepass', compact('user'));
    }

    public function updatePassword(Request $request, User $user)
    {
         // 1. Validate the new password inputs
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // 2. Update the user record with the new hashed password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // 3. Redirect back with a success notice
        return redirect()
            ->route('admin.users.manage')
            ->with('success', "Password for {$user->name} has been updated successfully.");
    }


}