<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\GuruMapel as ModelsGuruMapel;
use App\Models\Mapel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class GuruMapel implements ToModel, WithStartRow
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
            0 => 'required',
            1 => 'required',
            2 => 'required'
        ]);

        if ($validator->fails()) {
            return null;
        };

        $guru = Guru::where('kode', $row[0])->select('id', 'kode')->first();
        $mapel = Mapel::where('id', $row[1]);
        if ($guru != null && $mapel->exists()) {
            $guru_mapel = ModelsGuruMapel::where('guru_id', $guru->id)->get();

            $process = true;
            foreach ($guru_mapel as $mapel) {
                if ($mapel->mapel_id == $row[1]) {
                    $process = false;
                };
            };

            if ($process === true) {
                if (count($guru_mapel) === 0) {
                    ModelsGuruMapel::create([
                        'guru_id' => $guru->id,
                        'mapel_id' => $row[1],
                        'status' => $row[2]
                    ]);
                } else if (count($guru_mapel) === 1) {
                    ModelsGuruMapel::where('guru_id', $guru->id)->update(['guru_mapel' => 1]);
                    ModelsGuruMapel::create([
                        'guru_id' => $guru->id,
                        'mapel_id' => $row[1],
                        'guru_mapel' => count($guru_mapel) + 1,
                        'status' => $row[2]
                    ]);
                } else {
                    ModelsGuruMapel::create([
                        'guru_id' => $guru->id,
                        'mapel_id' => $row[1],
                        'guru_mapel' => count($guru_mapel) + 1,
                        'status' => $row[2]
                    ]);
                };
            };
        };

        return null;
    }
}
