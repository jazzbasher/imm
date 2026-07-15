<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimeClockLunchCode;
use Illuminate\Validation\Rule;
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
        // House Account and Flight Safety exists for dynamic freight log. Exclude them from User query
        $excludenonusers = ['House Account', 'Flight Safety'];
        $users = User::whereNotIn('name', $excludenonusers)->get();

        return view('admin.users.manage', compact('users'));

    }


    public function edituser($id)
    {
        $user = User::where('id', $id)->get();

        $lunchselects = TimeCLockLunchCode::all();

        return view('admin.users.edit', compact('user', 'lunchselects'));
    }


    public function updateuser(Request $request, $id)
    {
      

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => 'required', 'email', 'unique:users,email,' .$id,
            'outside_sales'   => ['required'],
            'freightlog' => 'required|boolean',
            'hourly'   => 'required|boolean',
            'lunch_code' => [
               Rule::when($request->boolean('hourly'), [
                'required',
                    Rule::in([1,2,3])
                    ]),
                ],

        ]);

        $user = User::findOrFail($id);

        $user->fill($validated);

        if($user->isCLean()) {

            return redirect()->back()->with('error', 'No changes were made');

        } else {

            $user->save();

            return redirect()->route('admin.manageusers')->with('success', 'User Information Updated');
        }




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

    // public function usermgmt()
    // {
    //     $users = User::all();
    // }


}