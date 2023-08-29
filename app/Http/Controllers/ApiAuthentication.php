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
        ]);

        $teacher = Guru::where('email', $credentials['email'])->first();
        $student = Siswa::where('email', $credentials['email'])->first();

        if ($student != null) {
            if (Hash::check($credentials['password'], $student->password)) {
                $token = array('token' => $student->createToken('Student')->plainTextToken);
                return $token;
            };
        } else if ($teacher != null) {
            if (Hash::check($credentials['password'], $teacher->password)) {
                $token = array('token' => $teacher->createToken('Teacher')->plainTextToken);
                return $token;
            };
        };

        throw ValidationException::withMessages([
            'email' => 'Data Kredensial Salah'
        ]);
    }

    public function logout()
    {
        request()->user()->currentAccessToken()->delete();
    }
}
