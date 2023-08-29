<?php

namespace App\Http\Controllers;

use App\Imports\KelasImport;
use App\Models\Jadwal;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class Kelases extends Controller
{
    public function get_data()
    {
        $data = Kelas::whereHas('jenjang', function ($query) {
            $query->where('hidden', 0);
            $query->where('status', 1);
        })->with('jenjang')->where('hidden', 0)->get();
        return response()->json($data)->header('content-type', 'application/json, charset="utf-8"');
    }

    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'jenjang_kelas_id' => 'required',
            'name' => 'required|min:3|max:12'
        ], [
            'jenjang_kelas_id.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memilih Jenjang Kelas</div></div>',
            'name.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memasukkan Nama Kelas</div></div>',
            'name.min' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Nama Kelas Harap Diisi Lengkap</div></div>',
            'name.max' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Nama Kelas Maksimal 255 Karakter</div></div>'
        ]);


        if ($validator->fails()) {
            return response()->json(['notification' => $validator->errors()]);
        };

        if ($validator->passes()) {
            $check_backup = Kelas::where('hidden', 1)->where('jenjang_kelas_id', request()->input('jenjang_kelas_id'))->where('name', request()->input('name'))->first();
            if ($check_backup == null) {
                if (Kelas::where('jenjang_kelas_id', request()->input('jenjang_kelas_id'))->where('name', request()->input('name'))->first() == null) {
                    $data = array(
                        'jenjang_kelas_id' => request()->input('jenjang_kelas_id'),
                        'name' => request()->input('name'),
                        'status' => request()->input('status'),
                        'hidden' => 0,
                        'action_by' => Auth::user()->id
                    );

                    Kelas::create($data);

                    return response()->json(['notification' => ['Data Added' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Kelas Baru Ditambahkan</div></div>']], 'success' => true]);
                } else {
                    return response()->json(['notification' => ['Update Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Kelas Tidak Boleh Sama</div></div>']]);
                };
            } else {
                Kelas::where('id', $check_backup->id)->update([
                    'jenjang_kelas_id' => request()->input('jenjang_kelas_id'),
                    'status' => request()->input('status'),
                    'hidden' => 0,
                    'action_by' => Auth::user()->id
                ]);
                return response()->json(['notification' => ['Data Added' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Kelas Baru Ditambahkan</div></div>']], 'success' => true]);
            };
        };
    }

    public function edit()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|min:3|max:12',
        ], [
            'name.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memasukkan Nama Kelas</div></div>',
            'name.min' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Nama Kelas Harap Diisi Lengkap</div></div>',
            'name.max' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Nama Kelas Maksimal 255 Karakter</div></div>'
        ]);

        if ($validator->passes()) {
            $old_data = Kelas::where('jenjang_kelas_id', request()->input('confirm2'))->where('name', request()->input('confirm'))->first();

            if (request()->input('jenjang_kelas_id') == $old_data->jenjang_kelas_id && request()->input('name') == $old_data->name && request()->input('status') ==  $old_data->status) {
                return response()->json(['notification' => ['Update Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Tidak Ada Perubahan Dilakukan</div></div>']]);
            } else {
                $new_data_check = Kelas::with('jenjang')->where('jenjang_kelas_id', request()->input('jenjang_kelas_id'))->where('name', request()->input('name'))->where('id', '!=', $old_data->id)->first();
                if ($new_data_check != null) {
                    if ($new_data_check->hidden == 1) {
                        return response()->json(['notification' => ['Update Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Kelas ' . $new_data_check->jenjang->jenjang . ' ' . $new_data_check->name . ' Pernah Dihapus. Tambah kembali ' . $new_data_check->jenjang->jenjang . ' ' . $new_data_check->name . ' jika ingin menggunakannya kembali</div></div>']]);
                    } else {
                        return response()->json(['notification' => ['Update Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Kelas Tidak Boleh Sama</div></div>']]);
                    };
                } else {
                    Kelas::where('jenjang_kelas_id', request()->input('confirm2'))->where('name', request()->input('confirm'))->update([
                        'jenjang_kelas_id' => request()->input('jenjang_kelas_id'),
                        'name' => request()->input('name'),
                        'status' => request()->input('status'),
                        'action_by' => Auth::user()->id
                    ]);

                    return response()->json([
                        'notification' => ['Data Updated' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Kelas Berhasil Dirubah</div></div>']],
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
            Kelas::where('id', request()->input('confirm'))->update([
                'hidden' => 1,
                'action_by' => Auth::user()->id
            ]);

            return response()->json(['notification' => ['Data Deleted' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Kelas Berhasil Dihapus</div></div>']], 'success' => true]);
        } else {
            return response()->json(['notification' => ['Delete Failed' => ['<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Konfirmasi Gagal Penghapusan Dibatalkan</div></div>']]]);
        };
    }

    public function import()
    {
        $validator = Validator::make(request()->all(), [
            'kelas_excel' => 'required|mimes:xlsx,xls'
        ]);

        if ($validator->fails()) {
            return response()->json(['notification' => ['Import Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Harap Menyertakan Template Excel Yang Valid</div></div>']]);
        };

        if ($validator->passes()) {
            Excel::import(new KelasImport, request()->file('kelas_excel'));

            return response()->json(['notification' => ['Data Imported' => '<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Kelas Berhasil Diimpor</div></div>'], 'success' => true]);
        };
    }

    public function up()
    {
        Kelas::where('hidden', 1)->delete();
        Kelas::where('jenjang_kelas_id', 1)->update([
            'jenjang_kelas_id' => 4,
            'action_by' => Auth::user()->id
        ]);
        Kelas::where('jenjang_kelas_id', 2)->update([
            'jenjang_kelas_id' => 1,
            'action_by' => Auth::user()->id
        ]);
        Kelas::where('jenjang_kelas_id', 3)->update([
            'jenjang_kelas_id' => 2,
            'action_by' => Auth::user()->id
        ]);

        return response()->json(['notification' => ['Data Updated' => '<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Naik Jenjang Berhasil Dilakukan</div></div>'], 'success' => true]);
    }
}
