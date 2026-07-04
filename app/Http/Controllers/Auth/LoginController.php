<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Allow login with either email or mobile number.
        $field = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

        $attempt = Auth::attempt([
            $field => $credentials['login'],
            'password' => $credentials['password'],
            'is_active' => true,
        ], $request->boolean('remember'));

        if (! $attempt) {
            throw ValidationException::withMessages([
                'login' => 'These credentials do not match our records, or the account is inactive.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
