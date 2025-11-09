<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'no_hp' => ['required'],
            'password' => ['required']
        ]);

        if(Auth::guard('web')->attempt(['no_hp' => $credentials['no_hp'], 'password' => $credentials['password']])){
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['login' => 'Nomor HP atau Password Salah.'])->onlyInput('no_hp');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
