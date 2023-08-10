<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Siswa_Api extends Controller
{
    public function get_main($id_siswa)
    {
        $main = Siswa::with([
            'kelas' => function ($query) {
                $query->where('status', 1)
                    ->whereHas('jadwal', function ($subQuery) {
                        $subQuery->select('id', 'status');
                        $subQuery->where('status', 1);
                    })
                    ->with(['jadwal' => function ($subSubQuery) {
                        $subSubQuery->with(['details' => function ($subSubSubQuery) {
                            $subSubSubQuery->with(['mapel' => function ($subSubSubSubQuery) {
                                $subSubSubSubQuery->select('id', 'nama_mapel');
                            }]);
                            $subSubSubQuery->with(['guru' => function ($subSubSubSubQuery) {
                                $subSubSubSubQuery->select('id', 'name');
                            }]);
                            $subSubSubQuery->orderBy('jam_ke', 'desc');
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
                'day_name' => Carbon::now()->isoFormat('dddd'),
                'date' => Carbon::now()->isoFormat('D MMM YYYY')
            ),
            'today_schedule' => array()
        );

        foreach ($main->kelas->jadwal[0]->details as $detail) {
            if ($detail->hari == strtolower(Carbon::now()->isoFormat('dddd'))) {
                if (Carbon::now() < Carbon::parse($detail->jam_mulai)) {
                    $detail->keterangan = 'Akan Dimulai';
                } else if (Carbon::now() >= Carbon::parse($detail->jam_mulai) && Carbon::now() <= Carbon::parse($detail->jam_selesai)) {
                    $detail->keterangan = 'Berlangsung';
                } else if (Carbon::now() > Carbon::parse($detail->jam_selesai)) {
                    $detail->keterangan = 'Telah Berakhir';
                };

                array_push($data['today_schedule'], $detail);
            };
        };

        return response()->json($data);
    }
}
