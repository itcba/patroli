<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'login' => ['required','string'],
            'password' => ['required'],
        ]);

        $login = $data['login'];
        $password = $data['password'];

        // Try find user by email or by name (username)
        $user = User::where('email', $login)->orWhere('name', $login)->first();

        // Only allow admin accounts to authenticate
        if ($user && Hash::check($password, $user->password)) {
            if ($user->role !== 'admin') {
                return back()->withErrors(['login' => 'Only administrator accounts can sign in.']);
            }

            Auth::login($user);
            $request->session()->regenerate();

            // Prevent redirecting back to API endpoints that return JSON (e.g. /api/patrols)
            $intended = session()->get('url.intended');
            if ($intended) {
                // Normalize to path portion so we detect full URLs like http://host/api/... as well
                $path = parse_url($intended, PHP_URL_PATH) ?: $intended;
                if (Str::startsWith($path, '/api')) {
                    // Clear intended and send to homepage instead
                    session()->forget('url.intended');
                    return redirect('/');
                }
            }

            return redirect()->intended('/');
        }

        return back()->withErrors(['login' => 'The provided credentials do not match our records.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
