<?php

namespace App\Imports;

use App\Models\Mapel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MapelImport implements ToModel, WithStartRow
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
            0 => 'required|min:5|max:255|unique:mapel,nama_mapel',
            1 => 'required|max:1'
        ]);

        $data = [
            'nama_mapel' => $row[0],
            'status' => $row[1],
            'hidden' => 0,
            'action_by' => Auth::user()->id
        ];

        if ($validator->fails()) {
            $existingMapel = Mapel::where('hidden', 1)->where('nama_mapel', $data['nama_mapel'])->first();

            if ($existingMapel !== null) {
                $existingMapel->update($data);
            };
            return null;
        }

        Mapel::create($data);

        return null;
    }
}
