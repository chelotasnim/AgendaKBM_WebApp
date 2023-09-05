<?php

namespace App\Imports;

use App\Models\GuruMapel;
use App\Models\Jadwal;
use App\Models\Kelas;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class JadwalImport implements ToModel, WithStartRow
{
    protected $hari;

    public function __construct($hari)
    {
        $this->hari = $hari;
    }

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
            0 => 'required',
            1 => '',
            2 => 'required',
            3 => 'required',
            4 => 'required',
            5 => 'required',
            6 => 'required',
            7 => 'required',
            8 => 'required',
            9 => 'required',
            10 => 'required'
        ]);

        if ($validator->fails()) {
            return null;
        };

        $id_jam = 2;

        if ($this->hari == 'Senin') {
            $id_jam = 1;
        };

        $split_kelas = explode(' ', $row[0]);
        $get_jenjang = $split_kelas[0];
        $get_kelas = str_replace($get_jenjang . ' ', '', $row[0]);
        $data_kelas = Kelas::whereHas('jenjang', function ($query) use ($get_jenjang) {
            $query->where('jenjang', $get_jenjang);
            $query->where('hidden', 0);
            $query->where('status', 1);
        })->with('jenjang')->where('name', $get_kelas)->where('hidden', 0)->where('status', 1)->select('id')->first();


        if ($data_kelas != null) {
            for ($i = 1; $i <= count($row) - 1; $i++) {
                if ($row[$i] != '') {
                    $kode_guru = null;
                    $kode_mapel = null;
                    if (str_contains($row[$i], ',')) {
                        $kode_guru = explode(',', $row[$i])[0];
                        $kode_mapel = explode(',', $row[$i])[1];
                    } else {
                        $kode_guru = $row[$i];
                    };

                    $guru_mapel = null;
                    if ($kode_mapel != null) {
                        $guru_mapel = GuruMapel::whereHas('guru', function ($query) use ($kode_guru) {
                            $query->where('kode', $kode_guru);
                        })->where('guru_mapel', $kode_mapel)->first();
                    } else {
                        $guru_mapel = GuruMapel::whereHas('guru', function ($query) use ($kode_guru) {
                            $query->where('kode', $kode_guru);
                        })->where('guru_mapel', null)->first();
                    };

                    $jam_ke_nol = 0;
                    if ($i == 1) {
                        $jam_ke_nol = 1;
                    };

                    if ($guru_mapel != null) {
                        Jadwal::create([
                            'kelas_id' => $data_kelas->id,
                            'hari' => $this->hari,
                            'jam_id' => $id_jam,
                            'guru_mapel_id' => $guru_mapel->id,
                            'jam_ke_nol' => $jam_ke_nol
                        ]);
                    };
                };
            };
        };

        return null;
    }
}
