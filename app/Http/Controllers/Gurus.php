<?php

namespace App\Http\Controllers;

use App\Imports\GuruImport;
use App\Models\Guru;
use App\Models\Jurnal_Kelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class Gurus extends Controller
{
    public function get_data()
    {
        $data = Guru::where('hidden', 0)->get();
        return response()->json($data)->header('content-type', 'application/json, charset="utf-8"');
    }

    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|max:100',
            'username' => 'required|min:5|max:25|unique:siswa,username',
            'email' => 'required|max:255|email:dns|unique:siswa,email|unique:users,email',
            'password' => 'required'
        ], [
            'name.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memasukkan Nama Asli</div></div>',
            'name.max' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Nama Maksimal 100 Karakter</div></div>',
            'username.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memasukkan Username</div></div>',
            'username.min' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Username Minimal 5 Karakter</div></div>',
            'username.max' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Username Maksimal 255 Karakter</div></div>',
            'email.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memasukkan Email</div></div>',
            'email.email' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Format Email Harus Valid</div></div>',
            'email.max' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Email Maksimal 255 Karakter</div></div>',
            'password.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memasukkan Password</div></div>'
        ]);

        if ($validator->fails()) {
            return response()->json(['notification' => $validator->errors()]);
        };

        if ($validator->passes()) {
            $check_backup = Guru::where('username', request()->input('username'))->where('hidden', 1)->orWhere('email', request()->input('email'))->where('hidden', 1)->first();
            if ($check_backup == null) {
                $validator2 = Validator::make(request()->all(), [
                    'username' => 'unique:guru,username',
                    'email' => 'unique:guru,email'
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
                        'status' => request()->input('status'),
                        'hidden' => 0,
                        'action_by' => Auth::user()->id
                    );

                    Guru::create($data);

                    return response()->json(['notification' => ['Data Added' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Akun Guru Berhasil Didaftarkan</div></div>']], 'success' => true]);
                };
            } else {
                Guru::where('id', $check_backup->id)->update([
                    'status' => request()->input('status'),
                    'hidden' => 0,
                    'action_by' => Auth::user()->id
                ]);
                return response()->json(['notification' => ['Data Added' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Akun Guru Berhasil Didaftarkan</div></div>']], 'success' => true]);
            };
        };
    }

    public function edit()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|max:100',
            'username' => 'required|min:5|max:25|unique:siswa,username',
            'email' => 'required|max:255|email:dns|unique:siswa,email|unique:users,email'
        ], [
            'name.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memasukkan Nama Asli</div></div>',
            'name.max' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Nama Maksimal 100 Karakter</div></div>',
            'username.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memasukkan Username</div></div>',
            'username.min' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Username Minimal 5 Karakter</div></div>',
            'username.max' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Username Maksimal 255 Karakter</div></div>',
            'email.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memasukkan Email</div></div>',
            'email.email' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Format Email Harus Valid</div></div>',
            'email.max' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Email Maksimal 255 Karakter</div></div>',
        ]);

        if ($validator->passes()) {
            $old_data = Guru::where('username', request()->input('confirm'))->orWhere('email', request()->input('confirm2'))->first();

            if (request()->input('name') == $old_data->name && request()->input('username') == $old_data->username && request()->input('email') == $old_data->email && request()->input('status') ==  $old_data->status) {
                if (request()->input('password') != '') {
                    Guru::where('username', request()->input('confirm'))->update(['password' => bcrypt(request()->input('password'))]);

                    return response()->json([
                        'notification' => ['Data Updated' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Guru Berhasil Dirubah</div></div>']],
                        'success' => true
                    ]);
                } else {
                    return response()->json(['notification' => ['Update Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Tidak Ada Perubahan Dilakukan</div></div>']]);
                };
            } else {
                $new_data_check = Guru::where('username', request()->input('username'))->where('id', '!=', $old_data->id)->orWhere('email', request()->input('email'))->where('id', '!=', $old_data->id)->first();
                if ($new_data_check != null) {
                    if ($new_data_check->hidden == 1) {
                        return response()->json(['notification' => ['Update Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Akun Guru ' . $new_data_check->username . ' Pernah Dihapus. Tambah kembali guru' . $new_data_check->username . ' jika ingin menggunakannya kembali</div></div>']]);
                    } else {
                        return response()->json(['notification' => ['Update Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Akun Guru Tidak Boleh Sama</div></div>']]);
                    };
                } else {
                    $new_data = array(
                        'name' => request()->input('name'),
                        'username' => request()->input('username'),
                        'email' => request()->input('email'),
                        'status' => request()->input('status'),
                        'hidden' => 0,
                        'action_by' => Auth::user()->id
                    );
                    if (request()->input('password') != null) {
                        $new_data['password'] = request()->input('password');
                    };

                    Guru::where('username', request()->input('confirm'))->orWhere('email', request()->input('confirm2'))->update($new_data);

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
            Guru::where('username', request()->input('confirm'))->update([
                'hidden' => 1,
                'action_by' => Auth::user()->id
            ]);

            return response()->json(['notification' => ['Data Deleted' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Akun Guru Berhasil Dihapus</div></div>']], 'success' => true]);
        } else {
            return response()->json(['notification' => ['Delete Failed' => ['<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Konfirmasi Gagal Penghapusan Dibatalkan</div></div>']]]);
        };
    }

    public function import()
    {
        $validator = Validator::make(request()->all(), [
            'guru_excel' => 'required|mimes:xlsx,xls'
        ]);

        if ($validator->fails()) {
            return response()->json(['notification' => ['Import Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Harap Menyertakan Template Excel Yang Valid</div></div>']]);
        };

        if ($validator->passes()) {
            Excel::import(new GuruImport, request()->file('guru_excel'));

            return response()->json(['notification' => ['Data Imported' => '<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Akun Guru Berhasil Diimpor</div></div>'], 'success' => true]);
        };
    }

    public function self_regist()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|max:100',
            'username' => 'required|min:5|max:25|unique:siswa,username',
            'email' => 'required|max:255|email:dns|unique:siswa,email|unique:users,email',
            'password' => 'required'
        ], [
            'name.required' => 'Wajib Memasukkan Nama Asli',
            'name.max' => 'Nama Maksimal 100 Karakter',
            'username.required' => 'Wajib Memasukkan Username',
            'username.min' => 'Username Minimal 5 Karakter',
            'username.max' => 'Username Maksimal 255 Karakter',
            'email.required' => 'Wajib Memasukkan Email',
            'email.email' => 'Format Email Harus Valid',
            'email.max' => 'Email Maksimal 255 Karakter',
            'password.required' => 'Wajib Memasukkan Password'
        ]);

        if ($validator->fails()) {
            return response()->json(['notification' => $validator->errors()]);
        };

        if ($validator->passes()) {
            $check_backup = Guru::where('username', request()->input('username'))->where('hidden', 1)->orWhere('email', request()->input('email'))->where('hidden', 1)->first();
            if ($check_backup == null) {
                $validator2 = Validator::make(request()->all(), [
                    'username' => 'unique:guru,username',
                    'email' => 'unique:guru,email'
                ], [
                    'username.unique' => 'Akun Guru Sudah Terdaftar',
                    'email.unique' => 'Akun Guru Sudah Terdaftar'

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
                        'status' => 1,
                        'hidden' => 0,
                        'action_by' => 0
                    );

                    Guru::create($data);

                    return response()->json(['notification' => ['Data Added' => ['Akun Guru Berhasil Didaftarkan']], 'success' => true]);
                };
            } else {
                Guru::where('id', $check_backup->id)->update([
                    'status' => 1,
                    'hidden' => 0,
                    'action_by' => 0
                ]);
                return response()->json(['notification' => ['Data Added' => ['Akun Guru Berhasil Didaftarkan']], 'success' => true]);
            };
        };
    }

    public function jurnal()
    {
        $validator = Validator::make(request()->all(), [
            'guru_id' => 'required',
            'mapel_id' => 'required',
            'kelas' => 'required',
            'tanggal' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'total_siswa' => 'required',
            'tidak_hadir' => 'required',
            'materi' => 'required',
        ], [
            'guru_id.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memilih Guru Pengajar</div></div>',
            'mapel_id.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memilih Mata Pelajaran</div></div>',
            'kelas.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memilih Kelas</div></div>',
            'tanggal.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Mengisi Tanggal</div></div>',
            'jam_mulai.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Mengisi Jam Mulai</div></div>',
            'jam_selesai.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Mengisi Jam Selesai</div></div>',
            'total_siswa.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Mengisi Total Siswa</div></div>',
            'tidak_hadir.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Mengisi Total Siswa Tidak Hadir</div></div>',
            'materi.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Mengisi Materi KBM</div></div>',
        ]);

        if ($validator->fails()) {
            return response()->json(['notification' => $validator->errors()]);
        };

        if ($validator->passes()) {
            $data = array(
                'kelas' => request()->input('kelas'),
                'guru_id' => request()->input('guru_id'),
                'mapel_id' => request()->input('mapel_id'),
                'tanggal' => request()->input('tanggal'),
                'jam_mulai' => request()->input('jam_mulai'),
                'jam_selesai' => request()->input('jam_selesai'),
                'total_siswa' => request()->input('total_siswa'),
                'tidak_hadir' => request()->input('tidak_hadir'),
                'materi' => request()->input('materi'),
                'action_by' => Auth::guard('web')->user()->id
            );

            Jurnal_Kelas::create($data);

            return response()->json(['notification' => ['Data Added' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Jurnal Kelas Ditambahkan</div></div>']], 'success' => true]);
        };
    }

    public function get_jurnal()
    {
        $validator = Validator::make(request()->all(), [
            'dari_sampai' => 'required',
            'kelas' => 'required'
        ], [
            'dari_sampai.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Rentang Tanggal Wajib Ditentukan</div></div>',
            'kelas.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Kelas Wajib Diisi</div></div>'
        ]);

        if ($validator->fails()) {
            return response()->json(['notification' => $validator->errors()]);
        }

        if ($validator->passes()) {
            $split_date = explode('-', request()->input('dari_sampai'));
            $from = $split_date[0];
            $to = $split_date[1];
            $get_jurnal = Jurnal_Kelas::with('guru', 'mapel')->where('kelas', request()->input('kelas'))->whereDate('tanggal', '>=', Carbon::parse($from)->format('Y-m-d'))->whereDate('tanggal', '<=', Carbon::parse($to)->format('Y-m-d'))->get();

            return response()->json(['main_data' => $get_jurnal, 'success' => true]);
        }
    }
}
