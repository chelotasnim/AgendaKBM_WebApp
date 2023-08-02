<?php

namespace App\Http\Controllers;

use App\Imports\MapelImport;
use App\Models\Mapel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class Mapels extends Controller
{
    public function get_data()
    {
        $data = Mapel::where('hidden', 0)->get();
        return response()->json($data)->header('content-type', 'application/json, charset="utf-8"');
    }

    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'nama_mapel' => 'required|min:5|max:255',
        ], [
            'nama_mapel.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memasukkan Nama Mata Pelajaran</div></div>',
            'nama_mapel.min' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Nama Mata Pelajaran Harap Diisi Lengkap</div></div>',
            'nama_mapel.max' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Nama Mata Pelajaran Maksimal 255 Karakter</div></div>'
        ]);


        if ($validator->fails()) {
            return response()->json(['notification' => $validator->errors()]);
        };

        if ($validator->passes()) {
            $check_backup = Mapel::where('hidden', 1)->where('nama_mapel', request()->input('nama_mapel'))->first();
            if ($check_backup == null) {
                $validator2 = Validator::make(request()->all(), [
                    'nama_mapel' => 'unique:mapel,nama_mapel',
                ], [
                    'nama_mapel.unique' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Nama Mata Pelajaran Tidak Boleh Sama</div></div>'
                ]);

                if ($validator2->fails()) {
                    return response()->json(['notification' => $validator2->errors()]);
                };

                if ($validator2->passes()) {
                    $data = array(
                        'nama_mapel' => request()->input('nama_mapel'),
                        'status' => request()->input('status'),
                        'hidden' => 0,
                        'action_by' => Auth::user()->id
                    );

                    Mapel::create($data);

                    return response()->json(['notification' => ['Data Added' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Mata Pelajaran Baru Ditambahkan</div></div>']], 'success' => true]);
                };
            } else {
                Mapel::where('id', $check_backup->id)->update([
                    'status' => request()->input('status'),
                    'hidden' => 0,
                    'action_by' => Auth::user()->id
                ]);
                return response()->json(['notification' => ['Data Added' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Mata Pelajaran Baru Ditambahkan</div></div>']], 'success' => true]);
            };
        };
    }

    public function edit()
    {
        $validator = Validator::make(request()->all(), [
            'nama_mapel' => 'required|min:5|max:255',
        ], [
            'nama_mapel.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memasukkan Nama Mata Pelajaran</div></div>',
            'nama_mapel.min' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Nama Mata Pelajaran Harap Diisi Lengkap</div></div>',
            'nama_mapel.max' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Nama Mata Pelajaran Maksimal 255 Karakter</div></div>'
        ]);

        if ($validator->passes()) {
            $old_data = Mapel::where('nama_mapel', request()->input('confirm'))->first();

            if (request()->input('nama_mapel') == $old_data->nama_mapel && request()->input('status') ==  $old_data->status) {
                return response()->json(['notification' => ['Update Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Tidak Ada Perubahan Dilakukan</div></div>']]);
            } else {
                $new_data_check = Mapel::where('nama_mapel', request()->input('nama_mapel'))->where('id', '!=', $old_data->id)->first();
                if ($new_data_check != null) {
                    if ($new_data_check->hidden == 1) {
                        return response()->json(['notification' => ['Update Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Mapel ' . $new_data_check->nama_mapel . ' Pernah Dihapus. Tambah kembali mapel' . $new_data_check->nama_mapel . ' jika ingin menggunakannya kembali</div></div>']]);
                    } else {
                        return response()->json(['notification' => ['Update Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Mapel Tidak Boleh Sama</div></div>']]);
                    };
                } else {
                    Mapel::where('nama_mapel', request()->input('confirm'))->update([
                        'nama_mapel' => request()->input('nama_mapel'),
                        'status' => request()->input('status'),
                        'action_by' => Auth::user()->id
                    ]);

                    return response()->json([
                        'notification' => ['Data Updated' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Mapel Berhasil Dirubah</div></div>']],
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
            Mapel::where('nama_mapel', request()->input('confirm'))->update([
                'hidden' => 1,
                'action_by' => Auth::user()->id
            ]);

            return response()->json(['notification' => ['Data Deleted' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Mata Pelajaran Berhasil Dihapus</div></div>']], 'success' => true]);
        } else {
            return response()->json(['notification' => ['Delete Failed' => ['<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Konfirmasi Gagal Penghapusan Dibatalkan</div></div>']]]);
        };
    }

    public function import()
    {
        $validator = Validator::make(request()->all(), [
            'mapel_excel' => 'required|mimes:xlsx,xls'
        ]);

        if ($validator->fails()) {
            return response()->json(['notification' => ['Import Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Harap Menyertakan Template Excel Yang Valid</div></div>']]);
        };

        if ($validator->passes()) {
            Excel::import(new MapelImport, request()->file('mapel_excel'));

            return response()->json(['notification' => ['Data Imported' => '<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Mata Pelajaran Berhasil Diimpor</div></div>'], 'success' => true]);
        };
    }
}
