<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'These credentials do not match our records.']);
        }

        $user = Auth::user();

        if ($user->status === 'suspended') {
            Auth::logout();
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Your account has been suspended. Contact the ICT Department.']);
        }

        $request->session()->regenerate();

        ActivityLog::record('login', 'User logged in');

        return $this->redirectByRole();
    }

    public function logout(Request $request)
    {
        ActivityLog::record('logout', 'User logged out');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function redirectByRole()
    {
        return Auth::user()->hasAdminAccess()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('teacher.dashboard');
    }
}
