<?php

namespace App\Http\Controllers;

use App\Imports\JadwalImport;
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

        if ($validator->passes()) {
            Excel::import(new JadwalImport(request()->input('hari')), request()->file('jadwal_excel'));

            return response()->json(['notification' => ['Data Imported' => '<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Jadwal Terbaru Berhasil Diimpor</div></div>'], 'success' => true]);
        };
    }
}
