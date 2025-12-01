<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors(['login' => 'The provided credentials do not match our records.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
