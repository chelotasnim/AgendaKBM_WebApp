<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Users extends Controller
{
    public function login()
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect('/')->with('credential_failed', 'Data Kredensial Salah');
        };

        $credentials = array(
            'email' => request()->input('email'),
            'password' => request()->input('password')
        );
        if (Auth::attempt($credentials)) {
            request()->session()->regenerateToken();
            return redirect()->intended('dashboard/mapel');
        } else {
            return redirect('/')->with('credential_failed', 'Data Kredensial Salah');
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
