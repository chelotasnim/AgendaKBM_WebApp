<?php

namespace App\Http\Controllers;

use App\Imports\JadwalImport;
use App\Models\Detail_Jadwal;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class Jadwals extends Controller
{
    public function get_jadwal($id)
    {
        $data = Jadwal::where('kelas_id', $id)->get();
        return response()->json($data)->header('content-type', 'application/json, charset="utf-8"');
    }

    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'kelas_id' => 'required',
            'nama_jadwal' => 'required|max:100',
            'deskripsi_jadwal' => 'max:255',
            'status' => 'required'
        ], [
            'kelas_id.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Jadwal Wajib Merujuk Pada Kelas Tertentu</div></div>',
            'nama_jadwal.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Nama Jadwal Wajib Diisi</div></div>',
            'nama_jadwal.max' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Nama Jadwal Maksimal 100 Karakter</div></div>',
            'deskripsi_jadwal.max' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Deskripsi Jadwal Maksimal 255 Karakter</div></div>',
            'status.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Status Jadwal Wajib Diisi</div></div>'
        ]);

        if ($validator->fails()) {
            return response()->json(['notification' => $validator->errors()]);
        };

        if ($validator->passes()) {
            $data = array(
                'kelas_id' => request()->input('kelas_id'),
                'nama_jadwal' => request()->input('nama_jadwal'),
                'deskripsi_jadwal' => request()->input('deskripsi_jadwal'),
                'status' => request()->input('status'),
                'hidden' => 0,
                'action_by' => Auth::user()->id
            );

            if (request()->input('status') == 1) {
                Jadwal::where('kelas_id', request()->input('kelas_id'))->update(['status' => 0]);
            };

            Jadwal::create($data);

            return response()->json(['notification' => ['Data Added' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Jadwal Baru Ditambahkan</div></div>']], 'success' => true]);
        };
    }

    public function store_schedule()
    {
        $get_data = json_decode(request()->getContent(), true);
        $schedule_group = $get_data['schedule_group'];
        $schedule_name = $get_data['nama_jadwal'];

        $count = 0;
        $now_schedule = Jadwal::where('nama_jadwal', $schedule_name)->select('id')->first();
        foreach ($schedule_group as $schedule) {
            $count++;
            Detail_Jadwal::create([
                'jadwal_id' => $now_schedule->id,
                'mapel_id' => $schedule[0],
                'guru_id' => $schedule[1],
                'hari' => $schedule[2],
                'jam_ke' => $schedule[3],
                'jam_mulai' => $schedule[4],
                'jam_selesai' => $schedule[5],
                'action_by' => Auth::user()->id
            ]);
        };

        return response()->json(['notification' => ['Data Added' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">' . $count . ' Jam KBM Baru Ditambahkan</div></div>']], 'success' => true]);
    }

    public function update()
    {
        $validator = Validator::make(request()->all(), [
            'nama_jadwal' => 'required|max:100',
            'deskripsi_jadwal' => 'max:255',
            'status' => 'required'
        ], [
            'nama_jadwal.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Nama Jadwal Wajib Diisi</div></div>',
            'nama_jadwal.max' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Nama Jadwal Maksimal 100 Karakter</div></div>',
            'deskripsi_jadwal.max' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Deskripsi Jadwal Maksimal 255 Karakter</div></div>',
            'status.required' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Status Jadwal Wajib Diisi</div></div>'
        ]);

        if ($validator->fails()) {
            return response()->json(['notification' => $validator->errors()]);
        };

        if ($validator->passes()) {
            $check_data = Jadwal::where('nama_jadwal', request()->input('nama_jadwal'))->first();
            if ($check_data == null) {
                if (request()->input('status') == 1) {
                    Jadwal::where('kelas_id', request()->input('kelas_id'))->update(['status' => 0]);
                };

                Jadwal::where('id', request()->input('id_jadwal'))->update([
                    'nama_jadwal' => request()->input('nama_jadwal'),
                    'deskripsi_jadwal' => request()->input('deskripsi_jadwal'),
                    'status' => request()->input('status'),
                    'action_by' => Auth::user()->id
                ]);

                return response()->json(['notification' => ['Data Updated' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Data Jadwal Diperbarui</div></div>']], 'success' => true]);
            } else {
                if ($check_data->id == request()->input('id_jadwal')) {
                    if (request()->input('status') == 1) {
                        Jadwal::where('kelas_id', request()->input('kelas_id'))->update(['status' => 0]);
                    };

                    Jadwal::where('id', request()->input('id_jadwal'))->update([
                        'nama_jadwal' => request()->input('nama_jadwal'),
                        'deskripsi_jadwal' => request()->input('deskripsi_jadwal'),
                        'status' => request()->input('status'),
                        'action_by' => Auth::user()->id
                    ]);

                    return response()->json(['notification' => ['Data Updated' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Data Jadwal Diperbarui</div></div>']], 'success' => true]);
                } else {
                    return response()->json(['notification' => ['Update Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Nama Jadwal Tidak Boleh Sama</div></div>']]);
                };
            }
        };
    }

    public function update_schedule()
    {
        $get_data = json_decode(request()->getContent(), true);
        $schedule_group = $get_data['schedule_group'];

        foreach ($schedule_group as $schedule) {
            Detail_Jadwal::where('id', $schedule[0])->update([
                'mapel_id' => $schedule[1],
                'guru_id' => $schedule[2],
                'jam_mulai' => $schedule[3],
                'jam_selesai' => $schedule[4]
            ]);
        };

        return response()->json(['notification' => ['Data Updated' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Jam KBM Sudah Terkini</div></div>']], 'success' => true]);
    }

    public function remove_schedule()
    {
        $get_data = json_decode(request()->getContent(), true);
        $schedules = $get_data['schedules_id'];

        if (count($schedules) > 0) {
            foreach ($schedules as $schedule) {
                Detail_Jadwal::where('id', $schedule)->delete();
            };

            return response()->json(['notification' => ['Data Deleted' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Pengurangan Jam KBM Berhasil</div></div>']], 'success' => true]);
        } else {
            return response()->json(['notification' => ['Delete Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Tidak Ada Pengurangan Jam KBM</div></div>']]);
        };
    }

    public function import()
    {
        $validator = Validator::make(request()->all(), [
            'jam_excel' => 'required|mimes:xlsx,xls'
        ]);

        if ($validator->fails()) {
            return response()->json(['notification' => ['Import Failed' => '<div class="toast toast-error" aria-live="assertive"><div class="toast-message">Harap Menyertakan Template Excel Yang Valid</div></div>']]);
        };

        if ($validator->passes()) {
            Excel::import(new JadwalImport(request()->input('jadwal_id')), request()->file('jam_excel'));

            return response()->json(['notification' => ['Data Imported' => '<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Jam KBM Berhasil Diimpor</div></div>'], 'success' => true]);
        };
    }
}
