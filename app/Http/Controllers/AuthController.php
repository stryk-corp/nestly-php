<?php

namespace App\Http\Controllers;

use App\Models\Hostel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // ---------------------------------------------------------------- Register
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register', [
            'hostels' => Hostel::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function register(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $role = $request->input('role', User::ROLE_STUDENT);
        $validRoles = [User::ROLE_STUDENT, User::ROLE_PORTER];

        $request->validate([
            'email'      => ['required', 'email', 'unique:users,email'],
            'password'   => ['required', 'confirmed', Password::min(8)],
            'first_name' => ['required', 'string', 'max:150'],
            'last_name'  => ['required', 'string', 'max:150'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'role'       => ['required', 'in:' . implode(',', $validRoles)],
            'hostel_id'  => ['nullable', 'exists:hostels,id'],
        ]);

        // Students are approved immediately; porters wait for admin
        $approved = ($role === User::ROLE_STUDENT);

        $hostelId = $request->input('hostel_id');

        $user = User::create([
            'email'       => strtolower(trim($request->email)),
            'password'    => Hash::make($request->password),
            'first_name'  => $request->first_name,
            'last_name'   => $request->last_name,
            'phone'       => $request->phone ?? '',
            'role'        => $role,
            'hostel_id'   => $hostelId ?: null,
            'is_approved' => $approved,
        ]);

        if ($role === User::ROLE_STUDENT) {
            Auth::login($user);
            return redirect()->route('dashboard')
                ->with('success', 'Your account is ready. Welcome to Nestly!');
        }

        return redirect()->route('login')
            ->with('success', 'Account created. An administrator must approve it before you can sign in.');
    }

    // ---------------------------------------------------------------- Login
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $email = strtolower(trim($request->email));
        $creds = ['email' => $email, 'password' => $request->password, 'is_active' => true];

        if (!Auth::attempt($creds, $request->boolean('remember'))) {
            return back()->withInput()->withErrors([
                'email' => 'Incorrect email or password.',
            ]);
        }

        $user = Auth::user();

        if (!$user->is_approved) {
            Auth::logout();
            return back()->withInput()
                ->with('warning', 'Your account is waiting for administrator approval.');
        }

        $request->session()->regenerate();

        $next = $request->query('next');
        if ($next && str_starts_with($next, '/')) {
            return redirect($next);
        }

        return redirect()->route('dashboard');
    }

    // ---------------------------------------------------------------- Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'You have been signed out.');
    }

    // ---------------------------------------------------------------- Dashboard router
    public function dashboard()
    {
        $user = Auth::user();

        if (!$user->is_approved || !$user->is_active) {
            Auth::logout();
            return redirect()->route('login')
                ->with('warning', 'Your account is waiting for administrator approval.');
        }

        return redirect()->route(match ($user->role) {
            User::ROLE_STUDENT => 'student.dashboard',
            User::ROLE_PORTER  => 'porter.dashboard',
            User::ROLE_ADMIN   => 'admin.dashboard',
            default            => 'home',
        });
    }
}
