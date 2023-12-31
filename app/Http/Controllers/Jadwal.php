<?php

namespace App\Http\Controllers;

use App\Imports\JadwalImport;
use App\Models\Jadwal as ModelsJadwal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class Jadwal extends Controller
{
    public function get_jadwal()
    {
        $validator = Validator::make(request()->all(), [
            'kelas' => 'required'
        ], [
            'kelas.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Harap Memilih Kelas</div></div>',
        ]);

        if ($validator->fails()) {
            return response()->json(['notification' => $validator->errors()]);
        };

        $data = ModelsJadwal::with(['guru_mapel' => function ($query) {
            $query->with(['guru' => function ($subQuery) {
                $subQuery->select('id', 'name');
            }]);
            $query->with(['mapel' => function ($subQuery) {
                $subQuery->select('id', 'nama_mapel');
            }]);
        }, 'jam'])->where('kelas_id', request()->input('kelas'))->where('hari', request()->input('hari'))->get();

        if (!isset($data[0])) {
            return response()->json(['success' => true, 'found' => false]);
        };

        $join_index = 0;
        if ($data[0]->jam_ke_nol == 0) {
            $join_index++;
            $data[0]['previous_null'] = true;
        };

        $hour_data = array();
        for ($i = 0; $i < $data[0]->jam->jumlah; $i++) {
            if ($i == 1 && request()->input('hari') == 'Senin') {
                $this_time = array(
                    'mulai' => Carbon::parse($hour_data[$i - 1]['selesai'])->addMinutes(5)->format('H:i'),
                    'selesai' => Carbon::parse($hour_data[$i - 1]['selesai'])->addMinutes((5 + $data[0]->jam->durasi))->format('H:i')
                );
                array_push($hour_data, $this_time);
            } else if ($i == 0 && request()->input('hari') == 'Senin') {
                $this_time = array(
                    'mulai' => $data[0]->jam->mulai,
                    'selesai' => Carbon::parse($data[0]->jam->mulai)->addMinutes(($data[0]->jam->durasi + $data[0]->jam->durasi))->format('H:i')
                );
                array_push($hour_data, $this_time);
            } else if ($i == 4) {
                $this_time = array(
                    'mulai' => Carbon::parse($hour_data[$i - 1]['selesai'])->addMinutes(30)->format('H:i'),
                    'selesai' => Carbon::parse($hour_data[$i - 1]['selesai'])->addMinutes((30 + $data[0]->jam->durasi))->format('H:i')
                );
                array_push($hour_data, $this_time);
            } else if ($i == 7 && request()->input('hari') == 'Senin') {
                $this_time = array(
                    'mulai' => Carbon::parse($hour_data[$i - 1]['selesai'])->addMinutes(60)->format('H:i'),
                    'selesai' => Carbon::parse($hour_data[$i - 1]['selesai'])->addMinutes((65 + $data[0]->jam->durasi))->format('H:i')
                );
                array_push($hour_data, $this_time);
            } else if ($i == 7) {
                $this_time = array(
                    'mulai' => Carbon::parse($hour_data[$i - 1]['selesai'])->addMinutes(60)->format('H:i'),
                    'selesai' => Carbon::parse($hour_data[$i - 1]['selesai'])->addMinutes((60 + $data[0]->jam->durasi))->format('H:i')
                );
                array_push($hour_data, $this_time);
            } else if ($i == 0) {
                $this_time = array(
                    'mulai' => $data[0]->jam->mulai,
                    'selesai' => Carbon::parse($data[0]->jam->mulai)->addMinutes($data[0]->jam->durasi)->format('H:i')
                );
                array_push($hour_data, $this_time);
            } else {
                $this_time = array(
                    'mulai' => Carbon::parse($hour_data[$i - 1]['selesai'])->format('H:i'),
                    'selesai' => Carbon::parse($hour_data[$i - 1]['selesai'])->addMinutes($data[0]->jam->durasi)->format('H:i')
                );
                array_push($hour_data, $this_time);
            };
        };

        foreach ($data as $key => $schedule) {
            if (isset($hour_data[$join_index])) {
                $schedule['jam_ke'] = $join_index;
                $schedule['mulai'] = $hour_data[$join_index]['mulai'];
                $schedule['selesai'] = $hour_data[$join_index]['selesai'];

                if ($join_index == 4 || $join_index == 7) {
                    $schedule['rest'] = true;
                };

                $join_index++;
            } else {
                unset($data[$key]);
            };
        };

        return response()->json([
            'main_data' => $data,
            'success' => true
        ]);
    }

    public function edit_jadwal()
    {
        $old_data = ModelsJadwal::where('id', request()->input('confirm'))->first();
        if (request()->input('guru_id') == $old_data) {
            return response()->json(['notification' => ['Update Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Tidak Ada Perubahan Dilakukan</div></div>']]);
        };

        ModelsJadwal::where('id', request()->input('confirm'))->update([
            'guru_mapel_id' => request()->input('guru_id')
        ]);

        return response()->json(['notification' => ['Data Added' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Perubahan Diterapkan</div></div>']], 'success' => true]);
    }

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
