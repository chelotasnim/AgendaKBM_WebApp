<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Jurnal_Kelas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Guru_Feature extends Controller
{
    public function get_main($id_guru, $day_selected = 'today')
    {
        $selected_day = $day_selected;
        if ($day_selected == 'today') {
            $selected_day = Carbon::now()->isoFormat('dddd');
        };
        $dayNames = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $dayIndex = array_search($selected_day, $dayNames);

        $main = Guru::with([
            'guru_mapel' => function ($query) use ($selected_day) {
                $query->where('status', 1)
                    ->select('id', 'guru_id')
                    ->whereHas('jadwal', function ($subQuery) use ($selected_day) {
                        $subQuery->where('hari', $selected_day)
                            ->select('id', 'kelas_id', 'guru_mapel_id');
                    });
            }
        ])->where('id', $id_guru)->first();

        $final_response = array(
            'main_data' => $main,
            'now_date' => array(
                'day_name' => $selected_day,
                'date' => Carbon::now()->isoFormat('D MMM YYYY')
            ),
            'yesterday' => $dayNames[($dayIndex - 1 + 5) % 5],
            'tomorrow' => $dayNames[($dayIndex + 1) % 5]
        );

        if (!isset($main->guru_mapel[0])) {
            $final_response['found'] = false;
            return response()->json($final_response);
        };

        $class_list = array();
        $schedule_id = array();
        foreach ($main->guru_mapel as $guru_mapel) {
            foreach ($guru_mapel->jadwal as $jadwal) {
                array_push($schedule_id, $jadwal->id);
                if (!in_array($jadwal->kelas_id, $class_list)) {
                    array_push($class_list, $jadwal->kelas_id);
                };
            };
        };

        $schedule_data = array();
        foreach ($class_list as $class_id) {
            array_push($schedule_data, Jadwal::with([
                'kelas' => function ($query) {
                    $query->with('jenjang');
                },
                'guru_mapel' => function ($query) {
                    $query->with('mapel');
                }
            ])->where('kelas_id', $class_id)->where('hari', $selected_day)->get());
        };

        $jurnal = array();
        foreach ($schedule_data as $schedule) {
            $join_index = 0;
            if ($schedule[0]->jam_ke_nol == 0) {
                $join_index++;
                $schedule[0]['previous_null'] = true;
            };

            $hour_data = array();
            for ($i = 0; $i < $schedule[0]->jam->jumlah; $i++) {
                if ($i == 1 && $selected_day == 'Senin') {
                    $this_time = array(
                        'mulai' => Carbon::parse($hour_data[$i - 1]['selesai'])->addMinutes(5)->format('H:i'),
                        'selesai' => Carbon::parse($hour_data[$i - 1]['selesai'])->addMinutes((5 + $schedule[0]->jam->durasi))->format('H:i')
                    );
                    array_push($hour_data, $this_time);
                } else if ($i == 0 && $selected_day == 'Senin') {
                    $this_time = array(
                        'mulai' => $schedule[0]->jam->mulai,
                        'selesai' => Carbon::parse($schedule[0]->jam->mulai)->addMinutes(($schedule[0]->jam->durasi + $schedule[0]->jam->durasi))->format('H:i')
                    );
                    array_push($hour_data, $this_time);
                } else if ($i == 4) {
                    $this_time = array(
                        'mulai' => Carbon::parse($hour_data[$i - 1]['selesai'])->addMinutes(30)->format('H:i'),
                        'selesai' => Carbon::parse($hour_data[$i - 1]['selesai'])->addMinutes((30 + $schedule[0]->jam->durasi))->format('H:i')
                    );
                    array_push($hour_data, $this_time);
                } else if ($i == 7 && $selected_day == 'Senin') {
                    $this_time = array(
                        'mulai' => Carbon::parse($hour_data[$i - 1]['selesai'])->addMinutes(60)->format('H:i'),
                        'selesai' => Carbon::parse($hour_data[$i - 1]['selesai'])->addMinutes((65 + $schedule[0]->jam->durasi))->format('H:i')
                    );
                    array_push($hour_data, $this_time);
                } else if ($i == 7) {
                    $this_time = array(
                        'mulai' => Carbon::parse($hour_data[$i - 1]['selesai'])->addMinutes(60)->format('H:i'),
                        'selesai' => Carbon::parse($hour_data[$i - 1]['selesai'])->addMinutes((60 + $schedule[0]->jam->durasi))->format('H:i')
                    );
                    array_push($hour_data, $this_time);
                } else if ($i == 0) {
                    $this_time = array(
                        'mulai' => $schedule[0]->jam->mulai,
                        'selesai' => Carbon::parse($schedule[0]->jam->mulai)->addMinutes($schedule[0]->jam->durasi)->format('H:i')
                    );
                    array_push($hour_data, $this_time);
                } else {
                    $this_time = array(
                        'mulai' => Carbon::parse($hour_data[$i - 1]['selesai'])->format('H:i'),
                        'selesai' => Carbon::parse($hour_data[$i - 1]['selesai'])->addMinutes($schedule[0]->jam->durasi)->format('H:i')
                    );
                    array_push($hour_data, $this_time);
                };
            };

            foreach ($schedule as $key => $schedule_hour) {
                if (isset($hour_data[$join_index])) {
                    if (in_array($schedule_hour->id, $schedule_id)) {
                        $schedule_hour['jam_ke'] = $join_index;
                        $schedule_hour['mulai'] = $hour_data[$join_index]['mulai'];
                        $schedule_hour['selesai'] = $hour_data[$join_index]['selesai'];

                        if ($join_index == 4 || $join_index == 7) {
                            $schedule_hour['rest'] = true;
                        };

                        if (Carbon::now()->format('H:i') < Carbon::parse($hour_data[$join_index]['mulai'])->format('H:i')) {
                            $schedule_hour['keterangan'] = 'Akan Dimulai';
                        } else if (Carbon::now()->format('H:i') >= Carbon::parse($hour_data[$join_index]['mulai'])->format('H:i') && Carbon::now()->format('H:i') <= Carbon::parse($hour_data[$join_index]['selesai'])->format('H:i')) {
                            $schedule_hour['keterangan'] = 'Berlangsung';
                            if (!Jurnal_Kelas::where('kelas', $schedule_hour->kelas->jenjang->jenjang . ' ' . $schedule_hour->kelas->name)->where('tanggal', Carbon::now()->format('Y-m-d'))->where('jam_mulai', $hour_data[$join_index]['mulai'])->where('jam_selesai', $hour_data[$join_index]['selesai'])->exists()) {
                                $schedule_hour['next_access'] = true;
                            };
                        } else if (Carbon::now()->format('H:i') > Carbon::parse($hour_data[$join_index]['selesai'])->format('H:i')) {
                            $schedule_hour['keterangan'] = 'Telah Berakhir';
                            if (!Jurnal_Kelas::where('kelas', $schedule_hour->kelas->jenjang->jenjang . ' ' . $schedule_hour->kelas->name)->where('tanggal', Carbon::now()->format('Y-m-d'))->where('jam_mulai', $hour_data[$join_index]['mulai'])->where('jam_selesai', $hour_data[$join_index]['selesai'])->exists()) {
                                $schedule_hour['next_access'] = true;
                            };
                        };

                        array_push($jurnal, $schedule_hour);
                    };
                    $join_index++;
                } else {
                    unset($schedule[$key]);
                };
            };
        };

        if ($jurnal == null) {
            $final_response['found'] = false;
            return response()->json($final_response);
        };

        $final_response['schedules'] = $jurnal;
        return response()->json($final_response);
    }
}
