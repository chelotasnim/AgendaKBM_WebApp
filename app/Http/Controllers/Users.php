<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        if (Auth::guard('student')->attempt($credentials)) {
            if (Auth::guard('student')->user()->status === 1) {
                request()->session()->regenerate();
                return redirect()->intended('student');
            };
        } else if (Auth::guard('teacher')->attempt($credentials)) {
            if (Auth::guard('teacher')->user()->status === 1) {
                request()->session()->regenerate();
                return redirect()->intended('teacher');
            };
        } else if (Auth::guard('web')->attempt($credentials)) {
            if (Auth::guard('web')->user()->status === 1) {
                request()->session()->regenerate();
                return redirect()->intended('dashboard/mapel');
            };
        };

        return redirect('/')->with('credential_failed', 'Data Kredensial Salah');
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }

    public function student_logout()
    {
        Auth::guard('student')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }

    public function teacher_logout()
    {
        Auth::guard('teacher')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }

    public function get_data()
    {
        $data = User::where('hidden', 0)->get();
        return response()->json($data)->header('content-type', 'application/json, charset="utf-8"');
    }

    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|max:255',
            'email' => 'required|max:255|email:dns',
            'password' => 'required'
        ], [
            'name.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memasukkan Nama</div></div>',
            'name.max' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Nama Maksimal 255 Karakter</div></div>',
            'email.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memasukkan Email</div></div>',
            'email.email' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Format Email Harus Valid</div></div>',
            'email.max' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Email Maksimal 255 Karakter</div></div>',
            'password.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memasukkan Password</div></div>'
        ]);

        if ($validator->fails()) {
            return response()->json(['notification' => $validator->errors()]);
        };

        if ($validator->passes()) {
            $check_backup = User::where('email', request()->input('email'))->where('hidden', 1)->first();
            if ($check_backup == null) {
                $validator2 = Validator::make(request()->all(), [
                    'email' => 'unique:users,email'
                ], [
                    'email.unique' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Email Tidak Boleh Sama</div></div>'

                ]);

                if ($validator2->fails()) {
                    return response()->json(['notification' => $validator2->errors()]);
                };

                if ($validator2->passes()) {
                    $data = array(
                        'name' => request()->input('name'),
                        'email' => request()->input('email'),
                        'password' => bcrypt(request()->input('password')),
                        'status' => request()->input('status'),
                        'hidden' => 0
                    );

                    User::create($data);

                    return response()->json(['notification' => ['Data Added' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Akun Admin Berhasil Didaftarkan</div></div>']], 'success' => true]);
                };
            } else {
                User::where('id', $check_backup->id)->update([
                    'status' => request()->input('status'),
                    'hidden' => 0,
                    'action_by' => Auth::user()->id
                ]);
                return response()->json(['notification' => ['Data Added' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Akun Admin Berhasil Didaftarkan</div></div>']], 'success' => true]);
            };
        };
    }

    public function edit()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|max:255',
            'email' => 'required|max:255|email:dns'
        ], [
            'name.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memasukkan Nama</div></div>',
            'name.max' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Nama Maksimal 255 Karakter</div></div>',
            'email.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memasukkan Email</div></div>',
            'email.email' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Format Email Harus Valid</div></div>',
            'email.max' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Email Maksimal 255 Karakter</div></div>',
        ]);

        if ($validator->passes()) {
            $old_data = User::where('email', request()->input('confirm'))->first();

            if (request()->input('name') == $old_data->name && request()->input('email') == $old_data->email && request()->input('status') ==  $old_data->status) {
                if (request()->input('password') != '') {
                    User::where('email', request()->input('confirm'))->update(['password' => bcrypt(request()->input('password'))]);

                    return response()->json([
                        'notification' => ['Data Updated' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Admin Berhasil Dirubah</div></div>']],
                        'success' => true
                    ]);
                } else {
                    return response()->json(['notification' => ['Update Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Tidak Ada Perubahan Dilakukan</div></div>']]);
                };
            } else {
                $new_data_check = User::where('email', request()->input('email'))->where('id', '!=', $old_data->id)->first();
                if ($new_data_check != null) {
                    if ($new_data_check->hidden == 1) {
                        return response()->json(['notification' => ['Update Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Akun Admin ' . $new_data_check->name . ' Pernah Dihapus. Tambah kembali guru' . $new_data_check->name . ' jika ingin menggunakannya kembali</div></div>']]);
                    } else {
                        return response()->json(['notification' => ['Update Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Akun Admin Tidak Boleh Sama</div></div>']]);
                    };
                } else {
                    $new_data = array(
                        'name' => request()->input('name'),
                        'email' => request()->input('email'),
                        'status' => request()->input('status'),
                        'hidden' => 0
                    );
                    if (request()->input('password') != null) {
                        $new_data['password'] = request()->input('password');
                    };

                    User::where('email', request()->input('confirm'))->update($new_data);

                    return response()->json([
                        'notification' => ['Data Updated' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Akun Guru Berhasil Dirubah</div></div>']],
                        'success' => true
                    ]);
                };
            };
        };

        if ($validator->fails()) {
            return response()->json(['notification' => $validator->errors()]);
        };
    }

    public function delete()
    {
        if (request()->input('confirm') === request()->input('param')) {
            User::where('username', request()->input('confirm'))->update([
                'hidden' => 0
            ]);

            return response()->json(['notification' => ['Data Deleted' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Akun Admin Berhasil Dihapus</div></div>']], 'success' => true]);
        } else {
            return response()->json(['notification' => ['Delete Failed' => ['<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Konfirmasi Gagal Penghapusan Dibatalkan</div></div>']]]);
        };
    }
}
