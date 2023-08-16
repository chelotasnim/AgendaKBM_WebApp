<?php

namespace App\Imports;

use App\Models\Jadwal;
use App\Models\Jenjang_Kelas;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class KelasImport implements ToModel
{
    public function startRow(): int
    {
        return 3;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $validator = Validator::make($row, [
            0 => 'required|max:3',
            1 => 'required|min:3|max:12',
            2 => 'required|max:1'
        ]);

        if ($validator->passes()) {
            $get_jenjang = Jenjang_Kelas::where('jenjang', $row[0])->where('hidden', 0)->first();
            if ($get_jenjang != null) {
                $data = array(
                    'jenjang_kelas_id' => $get_jenjang->id,
                    'name' => $row[1],
                    'status' => $row[2],
                    'hidden' => 0,
                    'action_by' => Auth::user()->id
                );
                $check_kelas = Kelas::where('jenjang_kelas_id', $get_jenjang->id)->where('name', $row[1])->first();
                if ($check_kelas == null) {
                    Kelas::create($data);

                    $get_kelas = Kelas::with('jenjang')->where('jenjang_kelas_id', $get_jenjang->id)->where('name', $row[1])->first();

                    Jadwal::create([
                        'kelas_id' => $get_kelas->id,
                        'nama_jadwal' => $get_kelas->jenjang->jenjang . ' ' . $get_kelas->name,
                        'deskripsi_jadwal' => 'Jadwal Normal Kelas ' . $get_kelas->jenjang->jenjang . ' ' . $get_kelas->name,
                        'status' => 1,
                        'hidden' => 0,
                        'action_by' => Auth::user()->id
                    ]);


                    return null;
                } else {
                    if ($check_kelas->hidden == 1) {
                        Kelas::where('id', $check_kelas->id)->update($data);
                    };
                    return null;
                };
            } else {
                return null;
            };
        };

        return null;
    }
}
