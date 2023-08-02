<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class Users extends Controller
{
    public function login()
    {
        $credentials = request()->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            request()->session()->regenerateToken();
            return redirect()->intended('dashboard/mapel');
        } else {
            return redirect('/')->with('failed', 'Email atau Password Salah');
        };
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerate();

        return redirect('/');
    }
}
