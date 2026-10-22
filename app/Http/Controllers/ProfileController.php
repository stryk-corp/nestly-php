<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{

    public function show()
    {
        return view('profile', ['user' => Auth::user()]);
    }

    /** Update name / phone. */
    public function update(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:150'],
            'last_name'  => ['required', 'string', 'max:150'],
            'phone'      => ['nullable', 'string', 'max:20'],
        ]);

        $user = Auth::user();
        $user->update($request->only('first_name', 'last_name', 'phone'));

        return back()->with('success', 'Profile updated.');
    }

    /** Change password. */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        Auth::user()->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password changed.');
    }
}
