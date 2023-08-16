<?php

namespace App\Http\Controllers;

use App\Models\Detail_Jadwal;
use App\Models\Guru;
use App\Models\Jurnal_Kelas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Guru_Api extends Controller
{
    public function get_main($id_guru)
    {
        $main = Guru::with([
            'jam_mengajar' => function ($query) {
                $query->where('hari', strtolower(Carbon::now()->isoFormat('dddd')))
                    ->with(['mapel' => function ($subQuery) {
                        $subQuery->select('id', 'nama_mapel');
                    }])
                    ->with(['jadwal' => function ($subQuery) {
                        $subQuery->select('id', 'kelas_id')
                            ->where('status', 1)
                            ->where('hidden', 0)
                            ->with(['kelas' => function ($subSubQuery) {
                                $subSubQuery->select('id', 'jenjang_kelas_id', 'name')
                                    ->with(['jenjang' => function ($subSubSubQuery) {
                                        $subSubSubQuery->select('id', 'jenjang');
                                    }]);
                            }]);
                    }]);
            }
        ])->where('id', $id_guru)->first();

        $data = array(
            'main_data' => $main,
            'now_date' => array(
                'day_name' => Carbon::now()->isoFormat('dddd'),
                'date' => Carbon::now()->isoFormat('D MMM YYYY')
            )
        );

        $jurnal_today = Jurnal_Kelas::where('guru_id', $id_guru)->whereDate('tanggal', Carbon::now()->format('Y-m-d'))->get();

        foreach ($main->jam_mengajar as $jadwal) {
            if (Carbon::now()->format('H:i') < Carbon::parse($jadwal->jam_mulai)->format('H:i')) {
                $jadwal->keterangan = 'Akan Dimulai';
            } else if (Carbon::now()->format('H:i') >= Carbon::parse($jadwal->jam_mulai)->format('H:i') && Carbon::now()->format('H:i') <= Carbon::parse($jadwal->jam_selesai)->format('H:i')) {
                $jadwal->keterangan = 'Berlangsung';
            } else if (Carbon::now()->format('H:i') > Carbon::parse($jadwal->jam_selesai)->format('H:i')) {
                $jadwal->keterangan = 'Telah Berakhir';
            };

            foreach ($jurnal_today as $jurnal) {
                if ($jurnal->jam_mulai == $jadwal->jam_mulai && $jurnal->jam_selesai == $jadwal->jam_selesai) {
                    $jadwal->keterangan = 'Sudah Mengisi';
                };
            };

            if ($jadwal->keterangan == 'Berlangsung' || $jadwal->keterangan == 'Telah Berakhir') {
                $jadwal->next_access = url('teacher/jurnal/' . $jadwal->id);
            };
        };

        return response()->json($data);
    }

    public function send_jurnal()
    {
        $validator = Validator::make(request()->all(), [
            'total_siswa' => 'required',
            'tidak_hadir' => 'required',
            'materi' => 'required|max:255'
        ], [
            'total_siswa.required' => 'Jumlah Total Siswa Wajib Diisi',
            'tidak_hadir.required' => 'Jumlah Siswa Tidak Hadir Wajib Diisi',
            'materi.required' => 'Materi Pembelajaran Wajib Diisi',
            'materi.max' => 'Materi Pembelajaran Maksimal 255 Karakter'
        ]);

        if ($validator->fails()) {
            return response()->json(['notification' => $validator->errors()]);
        } else {
            $jadwal = Detail_Jadwal::where('id', request()->input('jadwal_id'))->first();
            $data = array(
                'guru_id' => $jadwal->guru_id,
                'mapel_id' => $jadwal->mapel_id,
                'tanggal' => Carbon::now()->format('Y-m-d'),
                'jam_mulai' => $jadwal->jam_mulai,
                'jam_selesai' => $jadwal->jam_selesai,
                'total_siswa' => request()->input('total_siswa'),
                'tidak_hadir' => request()->input('tidak_hadir'),
                'materi' => request()->input('materi'),
                'action_by' => $jadwal->guru_id
            );

            Jurnal_Kelas::create($data);

            return response()->json(['success' => true]);
        };
    }
}
