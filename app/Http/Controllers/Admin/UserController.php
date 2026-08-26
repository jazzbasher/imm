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
        return view('admin.users.create'); 
    }

    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'branch' => ['nullable', 'integer'],
            'extension' => ['nullable', 'integer'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'freightlog' => ['required'],
            'outside_sales'   => ['required'],
            'accounting'  => ['required'],
            'hourly'   => ['required'],
        ]);
  
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'branch' => $validated['branch'],
            'extension' => $validated['extension'],
            'password' => Hash::make($validated['password']),
            'freightlog' => $validated['freightlog'],
            'outside_sales' => $validated['outside_sales'],
            'accounting'  => $validated['accounting'],
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
            'branch' => 'nullable|integer',
            'extension' => 'nullable|integer',
            'outside_sales'   => ['required'],
            'freightlog' => 'required|boolean',
            'accounting' => 'required|boolean',
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

    public function destroyuser(Request $request, $id)
    {
        $request->merge([
            'active' => 0,
        ]);

        $user = User::findOrFail($id);
        $user->update($request->all());
        $user->save();

        return redirect()->route('admin.manageusers')->with('success', 'User Inactivated');
    }

    public function editPassword(User $user)
    {
        return view('admin.users.changepass', compact('user'));
    }

    public function updatePassword(Request $request, User $user)
    {
         
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
       
        $user->update([
            'password' => Hash::make($request->password),
        ]);
   
        return redirect()
            ->route('admin.users.manage')
            ->with('success', "Password for {$user->name} has been updated successfully.");
    }



}