<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    //@desc Show Login form
    //@route GET /login
    public function login(): View
    {
        return view('auth.login');
    }

    //@desc authenticate user
    //@route POST /login
    public function authenticate(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'email' => 'required|string|email|max:100',
            'password' => 'required|string|min:8',
        ]);

        if(Auth::attempt($validatedData)) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'))->with('success', 'You are now Logged In!.');
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email');
    }

    //@desc Logout
    //@route POST /logout
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
