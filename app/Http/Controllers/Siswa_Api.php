<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
                    $schedule['bg'] = 'rgb(255, 50, 50)';
                    $schedule['color'] = 'rgb(255, 255, 255)';
                } else if (Carbon::now()->format('H:i') >= Carbon::parse($hour_data[$join_index]['mulai'])->format('H:i') && Carbon::now()->format('H:i') <= Carbon::parse($hour_data[$join_index]['selesai'])->format('H:i')) {
                    $schedule['keterangan'] = 'Berlangsung';
                    $schedule['bg'] = 'rgb(32, 201, 151)';
                    $schedule['color'] = 'rgb(255, 255, 255)';
                } else if (Carbon::now()->format('H:i') > Carbon::parse($hour_data[$join_index]['selesai'])->format('H:i')) {
                    $schedule['keterangan'] = 'Telah Berakhir';
                    $schedule['bg'] = 'rgb(225, 225, 225)';
                    $schedule['color'] = 'rgb(100, 100, 100)';
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

    public function edit()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|max:100',
            'username' => 'required|min:9|max:10|unique:guru,username',
            'email' => 'required|max:255|email:dns|unique:guru,email|unique:users,email'
        ], [
            'name.required' => 'Wajib Memasukkan Nama Asli',
            'name.max' => 'Nama Maksimal 100 Karakter',
            'username.required' => 'Wajib Memasukkan Username',
            'username.min' => 'Username Minimal 5 Karakter',
            'username.max' => 'Username Maksimal 255 Karakter',
            'email.required' => 'Wajib Memasukkan Email',
            'email.email' => 'Format Email Harus Valid',
            'email.max' => 'Email Maksimal 255 Karakter',
            'password.required' => 'Wajib Memasukkan Password'
        ]);

        if ($validator->passes()) {
            $old_data = Siswa::where('id', Auth::user()->id)->first();

            if (request()->input('name') == $old_data->name && request()->input('username') == $old_data->username && request()->input('email') == $old_data->email) {
                if (request()->input('password') != null) {
                    Siswa::where('id', Auth::user()->id)->update(['password' => bcrypt(request()->input('password'))]);

                    return response()->json([
                        'notification' => ['Data Updated' => ['Data Siswa Berhasil Dirubah']],
                        'success' => true
                    ]);
                } else {
                    return response()->json(['notification' => ['Update Failed' => 'Tidak Ada Perubahan Dilakukan']]);
                };
            } else {
                $new_data_check = Siswa::where('username', request()->input('username'))->where('id', '!=', $old_data->id)->orWhere('email', request()->input('email'))->where('id', '!=', $old_data->id)->first();
                if ($new_data_check != null) {
                    if ($new_data_check->hidden == 1) {
                        return response()->json(['notification' => ['Update Failed' => 'Akun Siswa ' . $new_data_check->username . ' Pernah Dihapus. Tambah kembali siswa' . $new_data_check->username . ' jika ingin menggunakannya kembali']]);
                    } else {
                        return response()->json(['notification' => ['Update Failed' => 'Akun Siswa Tidak Boleh Sama']]);
                    };
                } else {
                    $new_data = array(
                        'name' => request()->input('name'),
                        'username' => request()->input('username'),
                        'email' => request()->input('email'),
                        'action_by' => Auth::user()->id
                    );
                    if (request()->input('password') != null) {
                        $new_data['password'] = request()->input('password');
                    };

                    Siswa::where('id', Auth::user()->id)->update($new_data);

                    return response()->json([
                        'notification' => ['Data Updated' => ['Akun Siswa Berhasil Dirubah']],
                        'success' => true
                    ]);
                };
            };
        };

        if ($validator->fails()) {
            return response()->json(['notification' => $validator->errors()]);
        };
    }
}
