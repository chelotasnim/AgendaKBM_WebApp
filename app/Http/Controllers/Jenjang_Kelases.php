<?php

namespace App\Http\Controllers;

use App\Models\Jenjang_Kelas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Jenjang_Kelases extends Controller
{
    // public function get_data()
    // {
    //     $data = Jenjang_Kelas::where('hidden', 0)->get();
    //     return response()->json($data)->header('content-type', 'application/json, charset="utf-8"');
    // }

    // public function store()
    // {
    //     $validator = Validator::make(request()->all(), [
    //         'jenjang' => 'required|max:3',
    //     ], [
    //         'jenjang.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memasukkan Jenjang Kelas</div></div>',
    //         'jenjang.min' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Nama Jenjang Kelas Harap Diisi Lengkap</div></div>',
    //         'jenjang.max' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Nama Jenjang Kelas Maksimal 3 Karakter</div></div>'
    //     ]);


    //     if ($validator->fails()) {
    //         return response()->json(['notification' => $validator->errors()]);
    //     };

    //     if ($validator->passes()) {
    //         $check_backup = Jenjang_Kelas::where('hidden', 1)->where('jenjang', request()->input('jenjang'))->first();
    //         if ($check_backup == null) {
    //             $validator2 = Validator::make(request()->all(), [
    //                 'jenjang' => 'unique:jenjang_kelas,jenjang',
    //             ], [
    //                 'jenjang.unique' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Nama Jenjang Kelas Tidak Boleh Sama</div></div>'
    //             ]);

    //             if ($validator2->fails()) {
    //                 return response()->json(['notification' => $validator2->errors()]);
    //             };

    //             if ($validator2->passes()) {
    //                 $data = array(
    //                     'jenjang' => request()->input('jenjang'),
    //                     'status' => request()->input('status'),
    //                     'hidden' => 0,
    //                     'action_by' => Auth::user()->id

    //                 );

    //                 Jenjang_Kelas::create($data);

    //                 return response()->json(['notification' => ['Data Added' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Jenjang Kelas Baru Ditambahkan</div></div>']], 'success' => true]);
    //             };
    //         } else {
    //             Jenjang_Kelas::where('id', $check_backup->id)->update([
    //                 'status' => request()->input('status'),
    //                 'hidden' => 0,
    //                 'action_by' => Auth::user()->id
    //             ]);
    //             return response()->json(['notification' => ['Data Added' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Jenjang Kelas Baru Ditambahkan</div></div>']], 'success' => true]);
    //         };
    //     };
    // }

    // public function edit()
    // {
    //     $validator = Validator::make(request()->all(), [
    //         'jenjang' => 'required|max:3',
    //     ], [
    //         'jenjang.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memasukkan Nama Jenjang Kelas</div></div>',
    //         'jenjang.min' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Nama Jenjang Kelas Harap Diisi Lengkap</div></div>',
    //         'jenjang.max' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Nama Jenjang Kelas Maksimal 3 Karakter</div></div>'
    //     ]);

    //     if ($validator->passes()) {
    //         $old_data = Jenjang_Kelas::where('jenjang', request()->input('confirm'))->first();

    //         if (request()->input('jenjang') == $old_data->jenjang && request()->input('status') ==  $old_data->status) {
    //             return response()->json(['notification' => ['Update Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Tidak Ada Perubahan Dilakukan</div></div>']]);
    //         } else {
    //             $new_data_check = Jenjang_Kelas::where('jenjang', request()->input('jenjang'))->where('id', '!=', $old_data->id)->first();
    //             if ($new_data_check != null) {
    //                 if ($new_data_check->hidden == 1) {
    //                     return response()->json(['notification' => ['Update Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Jenjang ' . $new_data_check->jenjang . ' Pernah Dihapus. Tambah kembali jenjang' . $new_data_check->jenjang . ' jika ingin menggunakannya kembali</div></div>']]);
    //                 } else {
    //                     return response()->json(['notification' => ['Update Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Jenjang Tidak Boleh Sama</div></div>']]);
    //                 };
    //             } else {
    //                 Jenjang_Kelas::where('jenjang', request()->input('confirm'))->update([
    //                     'jenjang' => request()->input('jenjang'),
    //                     'status' => request()->input('status'),
    //                     'action_by' => Auth::user()->id
    //                 ]);

    //                 return response()->json([
    //                     'notification' => ['Data Updated' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Jenjang Berhasil Dirubah</div></div>']],
    //                     'success' => true
    //                 ]);
    //             };
    //         };
    //     };

    //     if ($validator->fails()) {
    //         return response()->json(['notification' => $validator->errors()]);
    //     };
    // }

    // public function delete()
    // {
    //     if (request()->input('confirm') === request()->input('param')) {
    //         Jenjang_Kelas::where('jenjang', request()->input('confirm'))->update([
    //             'hidden' => 1,
    //             'action_by' => Auth::user()->id
    //         ]);

    //         return response()->json(['notification' => ['Data Deleted' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Jenjang Kelas Berhasil Dihapus</div></div>']], 'success' => true]);
    //     } else {
    //         return response()->json(['notification' => ['Delete Failed' => ['<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Konfirmasi Gagal Penghapusan Dibatalkan</div></div>']]]);
    //     };
    // }
}
