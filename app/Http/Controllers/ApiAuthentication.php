<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ApiAuthentication extends Controller
{
    public function login()
    {
        $credentials = Validator::make(request()->all(), [
            'email' => 'required|max:255|email:dns',
            'password' => 'required'
        ], [
            'email.required' => 'Email Wajib Diisi',
            'email.max' => 'Email Maksimal 255 Karakter',
            'email.email' => 'Email Harus Valid',
            'password.required' => 'Password Wajib Diisi'
        ]);

        if ($credentials->fails()) {
            return response()->json(['notifications' => $credentials->errors()]);
        };

        $teacher = Guru::where('email', request()->input('email'))->first();
        $student = Siswa::where('email', request()->input('email'))->first();

        if ($student != null) {
            if (Hash::check(request()->input('password'), $student->password)) {
                $token = array('token' => $student->createToken('Student')->plainTextToken);
                return response()->json($token);
            };
        } else if ($teacher != null) {
            if (Hash::check(request()->input('password'), $teacher->password)) {
                $token = array('token' => $teacher->createToken('Teacher')->plainTextToken);
                return response()->json($token);
            };
        };

        return response()->json(['notifications' => ['fail' => ['Data Kredensial Salah']]]);
    }

    public function logout()
    {
        request()->user()->currentAccessToken()->delete();
        return response()->json(['logout' => true]);
    }
}
