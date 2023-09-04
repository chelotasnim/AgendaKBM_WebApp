<?php

namespace App\Http\Controllers;

use App\Imports\JadwalImport;
use App\Models\Jadwal as ModelsJadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class Jadwal extends Controller
{
    public function import()
    {
        $validator = Validator::make(request()->all(), [
            'jadwal_excel' => 'required|mimes:xlsx,xls'
        ]);

        if ($validator->fails()) {
            return response()->json(['notification' => ['Import Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Harap Menyertakan Template Excel Yang Valid</div></div>']]);
        };

        if (ModelsJadwal::where('hari', request()->input('hari'))->exists()) {
            return response()->json(['notification' => ['Import Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Harap Mereset Jadwal Pada Hari ' . request()->input('hari') . ' Terlebih Dahulu</div></div>']]);
        };

        Excel::import(new JadwalImport(request()->input('hari')), request()->file('jadwal_excel'));

        return response()->json(['notification' => ['Data Imported' => '<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Jadwal Terbaru Berhasil Diimpor</div></div>'], 'success' => true]);
    }

    public function reset()
    {
        if (request()->input('hari') === 'Semua') {
            ModelsJadwal::where('hari', '!=', null)->delete();
        } else {
            ModelsJadwal::where('hari', request()->input('hari'))->delete();
        };

        return response()->json(['notification' => ['Data Imported' => '<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Jadwal Lama Berhasil Direset</div></div>'], 'success' => true]);
    }
}
