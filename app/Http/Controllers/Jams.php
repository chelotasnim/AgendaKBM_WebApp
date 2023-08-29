<?php

namespace App\Http\Controllers;

use App\Models\Jam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Jams extends Controller
{
    public function get_jam()
    {
        $data = Jam::get();
        return response()->json($data);
    }

    public function add_jam()
    {
        $validator = Validator::make(request()->all(), [
            'hari' => 'required',
            'mulai' => 'required',
            'jumlah' => 'required',
            'durasi' => 'required'
        ], [
            'hari.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Memilih Hari</div></div>',
            'mulai.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Menetapkan Jam Mulai Pertama</div></div>',
            'jumlah.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Jumlah Jam KBM Wajib Diisi</div></div>',
            'durasi.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Durasi Per Jam KBM Wajib Diisi</div></div>'
        ]);

        if ($validator->fails()) {
            return response()->json(['notification' => $validator->errors()]);
        };

        Jam::create(request()->all());

        return response()->json(['notification' => ['Data Added' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Pengaturan Jam Baru Ditambahkan</div></div>']], 'success' => true]);
    }

    public function edit_jam()
    {
        $validator = Validator::make(request()->all(), [
            'mulai' => 'required',
            'jumlah' => 'required',
            'durasi' => 'required'
        ], [
            'mulai.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Wajib Menetapkan Jam Mulai Pertama</div></div>',
            'jumlah.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Jumlah Jam KBM Wajib Diisi</div></div>',
            'durasi.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Durasi Per Jam KBM Wajib Diisi</div></div>'
        ]);

        if ($validator->fails()) {
            return response()->json(['notification' => $validator->errors()]);
        };

        $old_data = Jam::where('id', request()->input('confirm'))->first();
        if (request()->input('mulai') == $old_data->mulai && request()->input('jumlah') == $old_data->jumlah && request()->input('durasi') == $old_data->durasi) {
            return response()->json(['notification' => ['Update Failed' => ['<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Tidak Ada Perubahan Dilakukan</div></div>']]]);
        } else {
            $data = array(
                'mulai' => request()->input('mulai'),
                'jumlah' => request()->input('jumlah'),
                'durasi' => request()->input('durasi'),
            );

            Jam::where('id', request()->input('confirm'))->update($data);

            return response()->json(['notification' => ['Data Updated' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Perubahan Pengaturan Jam Berhasil</div></div>']], 'success' => true]);
        };
    }
}
