<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Siswa_Api extends Controller
{
    public function get_main($day_selected = 'today')
    {
        $selected_day = $day_selected;
        if ($day_selected == 'today') {
            $selected_day = Carbon::now()->isoFormat('dddd');
        };
        $dayNames = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $dayIndex = array_search($selected_day, $dayNames);

        $main = Siswa::with([
            'kelas' => function ($query) use ($selected_day) {
                $query->select('id', 'jenjang_kelas_id', 'name')
                    ->where('status', 1)
                    ->with(['jadwal' => function ($subQuery) use ($selected_day) {
                        $subQuery->where('hari', $selected_day)
                            ->with(['guru_mapel' => function ($subSubQuery) {
                                $subSubQuery->with(['guru' => function ($subSubSubQuery) {
                                    $subSubSubQuery->select('id', 'name');
                                }]);
                                $subSubQuery->with(['mapel' => function ($subSubSubQuery) {
                                    $subSubSubQuery->select('id', 'nama_mapel');
                                }]);
                            }])
                            ->with('jam');
                    }])
                    ->with(['jenjang' => function ($subQuery) {
                        $subQuery->select('id', 'jenjang');
                    }]);
            },
        ])->where('id', Auth::user()->id)->first();

        $schedule_data = $main->kelas->jadwal;

        if (!isset($schedule_data[0])) {
            return response()->json([
                'main_data' => $main,
                'now_date' => array(
                    'day_name' => $selected_day,
                    'date' => Carbon::now()->isoFormat('D MMM YYYY')
                ),
                'yesterday' => $dayNames[($dayIndex - 1 + 5) % 5],
                'tomorrow' => $dayNames[($dayIndex + 1) % 5],
                'found' => false
            ]);
        };

        $join_index = 0;
        if ($schedule_data[0]->jam_ke_nol == 0) {
            $join_index++;
            $schedule_data[0]['previous_null'] = true;
        };

        $hour_data = array();
        for ($i = 0; $i < $schedule_data[0]->jam->jumlah; $i++) {
            if ($i == 1 && $selected_day == 'Senin') {
                $this_time = array(
                    'mulai' => Carbon::parse($hour_data[$i - 1]['selesai'])->addMinutes(5)->format('H:i'),
                    'selesai' => Carbon::parse($hour_data[$i - 1]['selesai'])->addMinutes((5 + $schedule_data[0]->jam->durasi))->format('H:i')
                );
                array_push($hour_data, $this_time);
            } else if ($i == 0 && $selected_day == 'Senin') {
                $this_time = array(
                    'mulai' => $schedule_data[0]->jam->mulai,
                    'selesai' => Carbon::parse($schedule_data[0]->jam->mulai)->addMinutes(($schedule_data[0]->jam->durasi + $schedule_data[0]->jam->durasi))->format('H:i')
                );
                array_push($hour_data, $this_time);
            } else if ($i == 4) {
                $this_time = array(
                    'mulai' => Carbon::parse($hour_data[$i - 1]['selesai'])->addMinutes(30)->format('H:i'),
                    'selesai' => Carbon::parse($hour_data[$i - 1]['selesai'])->addMinutes((30 + $schedule_data[0]->jam->durasi))->format('H:i')
                );
                array_push($hour_data, $this_time);
            } else if ($i == 7 && $selected_day == 'Senin') {
                $this_time = array(
                    'mulai' => Carbon::parse($hour_data[$i - 1]['selesai'])->addMinutes(60)->format('H:i'),
                    'selesai' => Carbon::parse($hour_data[$i - 1]['selesai'])->addMinutes((65 + $schedule_data[0]->jam->durasi))->format('H:i')
                );
                array_push($hour_data, $this_time);
            } else if ($i == 7) {
                $this_time = array(
                    'mulai' => Carbon::parse($hour_data[$i - 1]['selesai'])->addMinutes(60)->format('H:i'),
                    'selesai' => Carbon::parse($hour_data[$i - 1]['selesai'])->addMinutes((60 + $schedule_data[0]->jam->durasi))->format('H:i')
                );
                array_push($hour_data, $this_time);
            } else if ($i == 0) {
                $this_time = array(
                    'mulai' => $schedule_data[0]->jam->mulai,
                    'selesai' => Carbon::parse($schedule_data[0]->jam->mulai)->addMinutes($schedule_data[0]->jam->durasi)->format('H:i')
                );
                array_push($hour_data, $this_time);
            } else {
                $this_time = array(
                    'mulai' => Carbon::parse($hour_data[$i - 1]['selesai'])->format('H:i'),
                    'selesai' => Carbon::parse($hour_data[$i - 1]['selesai'])->addMinutes($schedule_data[0]->jam->durasi)->format('H:i')
                );
                array_push($hour_data, $this_time);
            };
        };

        foreach ($schedule_data as $key => $schedule) {
            if (isset($hour_data[$join_index])) {
                $schedule['jam_ke'] = $join_index;
                $schedule['mulai'] = $hour_data[$join_index]['mulai'];
                $schedule['selesai'] = $hour_data[$join_index]['selesai'];

                if ($join_index == 4 || $join_index == 7) {
                    $schedule['rest'] = true;
                };

                if (Carbon::now()->format('H:i') < Carbon::parse($hour_data[$join_index]['mulai'])->format('H:i')) {
                    $schedule['keterangan'] = 'Akan Dimulai';
                } else if (Carbon::now()->format('H:i') >= Carbon::parse($hour_data[$join_index]['mulai'])->format('H:i') && Carbon::now()->format('H:i') <= Carbon::parse($hour_data[$join_index]['selesai'])->format('H:i')) {
                    $schedule['keterangan'] = 'Berlangsung';
                } else if (Carbon::now()->format('H:i') > Carbon::parse($hour_data[$join_index]['selesai'])->format('H:i')) {
                    $schedule['keterangan'] = 'Telah Berakhir';
                };

                $join_index++;
            } else {
                unset($schedule_data[$key]);
            };
        };

        $data = array(
            'main_data' => $main,
            'now_date' => array(
                'day_name' => $selected_day,
                'date' => Carbon::now()->isoFormat('D MMM YYYY')
            ),
            'yesterday' => $dayNames[($dayIndex - 1 + 5) % 5],
            'tomorrow' => $dayNames[($dayIndex + 1) % 5]
        );

        return response()->json($data);
    }
}
