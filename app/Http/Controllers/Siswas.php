<?php

namespace App\Http\Controllers;

use App\Imports\SiswaImport;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class Siswas extends Controller
{
    public function get_data()
    {
        $data = Siswa::whereHas('kelas', function ($query) {
            $query->where('hidden', 0);
            $query->where('status', 1);
            $query->whereHas('jenjang', function ($jenjangQuery) {
                $jenjangQuery->where('hidden', 0);
                $jenjangQuery->where('status', 1);
            });
        })->with('kelas', 'kelas.jenjang')->where('hidden', 0)->get();

        return response()->json($data)->header('content-type', 'application/json, charset="utf-8"');
    }

    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|max:100',
            'username' => 'required|min:5|max:25',
            'email' => 'required|max:255|email:dns',
            'password' => 'required',
            'kelas_id' => 'required'
        ], [
            'name.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memasukkan Nama Asli</div></div>',
            'name.max' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Nama Maksimal 100 Karakter</div></div>',
            'username.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memasukkan Username</div></div>',
            'username.min' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Username Minimal 5 Karakter</div></div>',
            'username.max' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Username Maksimal 255 Karakter</div></div>',
            'email.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memasukkan Email</div></div>',
            'email.email' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Format Email Harus Valid</div></div>',
            'email.max' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Email Maksimal 255 Karakter</div></div>',
            'password.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memasukkan Password</div></div>',
            'kelas_id.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memilih Kelas Siswa</div></div>'
        ]);

        if ($validator->fails()) {
            return response()->json(['notification' => $validator->errors()]);
        };

        if ($validator->passes()) {
            $check_backup = Siswa::where('username', request()->input('username'))->where('hidden', 1)->orWhere('email', request()->input('email'))->where('hidden', 1)->first();
            if ($check_backup == null) {
                $validator2 = Validator::make(request()->all(), [
                    'username' => 'unique:siswa,username',
                    'email' => 'unique:siswa,email'
                ], [
                    'username.unique' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Username Tidak Boleh Sama</div></div>',
                    'email.unique' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Email Tidak Boleh Sama</div></div>'

                ]);

                if ($validator2->fails()) {
                    return response()->json(['notification' => $validator2->errors()]);
                };

                if ($validator2->passes()) {
                    $data = array(
                        'name' => request()->input('name'),
                        'username' => request()->input('username'),
                        'email' => request()->input('email'),
                        'password' => bcrypt(request()->input('password')),
                        'kelas_id' => request()->input('kelas_id'),
                        'status' => request()->input('status'),
                        'hidden' => 0,
                        'action_by' => Auth::user()->id
                    );

                    Siswa::create($data);

                    return response()->json(['notification' => ['Data Added' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Akun Siswa Berhasil Didaftarkan</div></div>']], 'success' => true]);
                };
            } else {
                Siswa::where('id', $check_backup->id)->update([
                    'status' => request()->input('status'),
                    'hidden' => 0,
                    'action_by' => Auth::user()->id
                ]);
                return response()->json(['notification' => ['Data Added' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Akun Siswa Berhasil Didaftarkan</div></div>']], 'success' => true]);
            };
        };
    }

    public function edit()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|max:100',
            'username' => 'required|min:5|max:25',
            'email' => 'required|max:255|email:dns',
            'kelas_id' => 'required'
        ], [
            'name.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memasukkan Nama Asli</div></div>',
            'name.max' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Nama Maksimal 100 Karakter</div></div>',
            'username.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memasukkan Username</div></div>',
            'username.min' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Username Minimal 5 Karakter</div></div>',
            'username.max' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Username Maksimal 255 Karakter</div></div>',
            'email.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memasukkan Email</div></div>',
            'email.email' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Format Email Harus Valid</div></div>',
            'email.max' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Email Maksimal 255 Karakter</div></div>',
            'password.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memasukkan Password</div></div>',
            'kelas_id.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memilih Kelas Siswa</div></div>'
        ]);

        if ($validator->passes()) {
            $old_data = Siswa::where('username', request()->input('confirm'))->orWhere('email', request()->input('confirm2'))->first();

            if (request()->input('name') == $old_data->name && request()->input('username') == $old_data->username && request()->input('email') == $old_data->email && request()->input('kelas_id') ==  $old_data->kelas_id && request()->input('status') ==  $old_data->status) {
                if (request()->input('password') != '') {
                    Siswa::where('username', request()->input('confirm'))->update(['password' => bcrypt(request()->input('password'))]);

                    return response()->json([
                        'notification' => ['Data Updated' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Siswa Berhasil Dirubah</div></div>']],
                        'success' => true
                    ]);
                } else {
                    return response()->json(['notification' => ['Update Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Tidak Ada Perubahan Dilakukan</div></div>']]);
                };
            } else {
                $new_data_check = Siswa::where('username', request()->input('username'))->where('id', '!=', $old_data->id)->orWhere('email', request()->input('email'))->where('id', '!=', $old_data->id)->first();
                if ($new_data_check != null) {
                    if ($new_data_check->hidden == 1) {
                        return response()->json(['notification' => ['Update Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Akun Siswa ' . $new_data_check->username . ' Pernah Dihapus. Tambah kembali siswa' . $new_data_check->username . ' jika ingin menggunakannya kembali</div></div>']]);
                    } else {
                        return response()->json(['notification' => ['Update Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Akun Siswa Tidak Boleh Sama</div></div>']]);
                    };
                } else {
                    $new_data = array(
                        'name' => request()->input('name'),
                        'username' => request()->input('username'),
                        'email' => request()->input('email'),
                        'kelas_id' => request()->input('kelas_id'),
                        'status' => request()->input('status'),
                        'hidden' => 0,
                        'action_by' => Auth::user()->id
                    );
                    if (request()->input('password') != null) {
                        $new_data['password'] = request()->input('password');
                    };

                    Siswa::where('username', request()->input('confirm'))->orWhere('email', request()->input('confirm2'))->update($new_data);

                    return response()->json([
                        'notification' => ['Data Updated' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Akun Siswa Berhasil Dirubah</div></div>']],
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
            Siswa::where('username', request()->input('confirm'))->update([
                'hidden' => 1,
                'action_by' => Auth::user()->id
            ]);

            return response()->json(['notification' => ['Data Deleted' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Akun Siswa Berhasil Dihapus</div></div>']], 'success' => true]);
        } else {
            return response()->json(['notification' => ['Delete Failed' => ['<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Konfirmasi Gagal Penghapusan Dibatalkan</div></div>']]]);
        };
    }

    public function import()
    {
        $validator = Validator::make(request()->all(), [
            'siswa_excel' => 'required|mimes:xlsx,xls'
        ]);

        if ($validator->fails()) {
            return response()->json(['notification' => ['Import Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Harap Menyertakan Template Excel Yang Valid</div></div>']]);
        };

        if ($validator->passes()) {
            Excel::import(new SiswaImport, request()->file('siswa_excel'));

            return response()->json(['notification' => ['Data Imported' => '<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Akun Siswa Berhasil Diimpor</div></div>'], 'success' => true]);
        };
    }

    public function self_regist()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|max:100',
            'username' => 'required|min:5|max:25',
            'email' => 'required|max:255|email:dns',
            'password' => 'required',
            'kelas_id' => 'required'
        ], [
            'name.required' => 'Wajib Memasukkan Nama Asli',
            'name.max' => 'Nama Maksimal 100 Karakter',
            'username.required' => 'Wajib Memasukkan Username',
            'username.min' => 'Username Minimal 5 Karakter',
            'username.max' => 'Username Maksimal 255 Karakter',
            'email.required' => 'Wajib Memasukkan Email',
            'email.email' => 'Format Email Harus Valid',
            'email.max' => 'Email Maksimal 255 Karakter',
            'password.required' => 'Wajib Memasukkan Password',
            'kelas_id.required' => 'Wajib Memilih Kelas Sisw>'
        ]);

        if ($validator->fails()) {
            return response()->json(['notification' => $validator->errors()]);
        };

        if ($validator->passes()) {
            $check_backup = Siswa::where('username', request()->input('username'))->where('hidden', 1)->orWhere('email', request()->input('email'))->where('hidden', 1)->first();
            if ($check_backup == null) {
                $validator2 = Validator::make(request()->all(), [
                    'username' => 'unique:siswa,username',
                    'email' => 'unique:siswa,email'
                ], [
                    'username.unique' => 'Akun Siswa Sudah Terdaftar',
                    'email.unique' => 'Akun Siswa Sudah Terdaftar'

                ]);

                if ($validator2->fails()) {
                    return response()->json(['notification' => $validator2->errors()]);
                };

                if ($validator2->passes()) {
                    $data = array(
                        'name' => request()->input('name'),
                        'username' => request()->input('username'),
                        'email' => request()->input('email'),
                        'password' => bcrypt(request()->input('password')),
                        'kelas_id' => request()->input('kelas_id'),
                        'status' => 1,
                        'hidden' => 0,
                        'action_by' => 0
                    );

                    Siswa::create($data);

                    return response()->json(['notification' => ['Data Added' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Akun Siswa Berhasil Didaftarkan</div></div>']], 'success' => true]);
                };
            } else {
                Siswa::where('id', $check_backup->id)->update([
                    'status' => 1,
                    'hidden' => 0,
                    'action_by' => 0
                ]);
                return response()->json(['notification' => ['Data Added' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Akun Siswa Berhasil Didaftarkan</div></div>']], 'success' => true]);
            };
        };
    }
}
