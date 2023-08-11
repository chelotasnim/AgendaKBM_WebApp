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
                $query->where('status', 1)
                    ->whereHas('jadwal', function ($subQuery) {
                        $subQuery->select('id', 'status');
                        $subQuery->where('status', 1);
                    })
                    ->with(['jadwal' => function ($subSubQuery) use ($selected_day) {
                        $subSubQuery->with(['details' => function ($subSubSubQuery) use ($selected_day) {
                            $subSubSubQuery->where('hari', $selected_day);
                            $subSubSubQuery->with(['mapel' => function ($subSubSubSubQuery) {
                                $subSubSubSubQuery->select('id', 'nama_mapel');
                            }]);
                            $subSubSubQuery->with(['guru' => function ($subSubSubSubQuery) {
                                $subSubSubSubQuery->select('id', 'name');
                            }]);
                        }]);
                    }]);
            },
            'kelas.jenjang' => function ($query) {
                $query->select('id', 'jenjang');
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
