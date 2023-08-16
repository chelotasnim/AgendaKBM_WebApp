<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Siswa_Api extends Controller
{
    public function get_main($id_siswa, $day_selected = 'today')
    {
        $selected_day = $day_selected;
        if ($day_selected == 'today') {
            $selected_day = strtolower(Carbon::now()->isoFormat('dddd'));
        };
        $dayNames = ['senin', 'selasa', 'rabu', 'kamis', 'jumat'];
        $dayIndex = array_search($selected_day, $dayNames);

        $main = Siswa::with([
            'kelas' => function ($query) use ($selected_day) {
                $query->select('id', 'jenjang_kelas_id', 'name')
                    ->where('status', 1)
                    ->with(['jadwal' => function ($subQuery) use ($selected_day) {
                        $subQuery->select('id', 'kelas_id', 'status')
                            ->where('status', 1)
                            ->where('hidden', 0)
                            ->with(['details' => function ($subSubQuery) use ($selected_day) {
                                $subSubQuery->where('hari', $selected_day);
                                $subSubQuery->with(['mapel' => function ($subSubSubQuery) {
                                    $subSubSubQuery->select('id', 'nama_mapel');
                                }]);
                                $subSubQuery->with(['guru' => function ($subSubSubQuery) {
                                    $subSubSubQuery->select('id', 'name');
                                }]);
                            }]);
                    }])
                    ->with(['jenjang' => function ($subQuery) {
                        $subQuery->select('id', 'jenjang');
                    }]);
            },
        ])->where('id', $id_siswa)->first();

        $data = array(
            'main_data' => $main,
            'now_date' => array(
                'day_name' => $selected_day,
                'date' => Carbon::now()->isoFormat('D MMM YYYY')
            ),
            'yesterday' => $dayNames[($dayIndex - 1 + 5) % 5],
            'tomorrow' => $dayNames[($dayIndex + 1) % 5]
        );

        foreach ($main->kelas->jadwal[0]->details as $detail) {
            if ($detail->hari == strtolower($selected_day)) {
                if (Carbon::now()->format('H:i') < Carbon::parse($detail->jam_mulai)->format('H:i')) {
                    $detail->keterangan = 'Akan Dimulai';
                } else if (Carbon::now()->format('H:i') >= Carbon::parse($detail->jam_mulai)->format('H:i') && Carbon::now()->format('H:i') <= Carbon::parse($detail->jam_selesai)->format('H:i')) {
                    $detail->keterangan = 'Berlangsung';
                } else if (Carbon::now()->format('H:i') > Carbon::parse($detail->jam_selesai)->format('H:i')) {
                    $detail->keterangan = 'Telah Berakhir';
                };
            };
        };

        return response()->json($data);
    }
}
