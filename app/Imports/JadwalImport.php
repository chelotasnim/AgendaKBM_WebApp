<?php

namespace App\Imports;

use App\Models\Detail_Jadwal;
use App\Models\Guru;
use App\Models\Mapel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class JadwalImport implements ToModel, WithStartRow
{
    protected $jadwal_id;

    public function __construct($jadwal_id)
    {
        $this->jadwal_id = $jadwal_id;
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
            1 => 'required',
            2 => 'required|max:6|min:4',
            3 => 'required|max:2',
            4 => 'required',
            5 => 'required',
        ]);

        if ($validator->fails()) {
            return null;
        };

        if (Mapel::where('id', $row[0])->exists() && Guru::where('id', $row[1])->exists()) {
            $data = array(
                'jadwal_id' => $this->jadwal_id,
                'mapel_id' => $row[0],
                'guru_id' => $row[1],
                'hari' => $row[2],
                'jam_ke' => $row[3],
                'jam_mulai' => Carbon::parse($row[4])->format('H:i'),
                'jam_selesai' => Carbon::parse($row[5])->format('H:i'),
                'action_by' => Auth::guard('web')->user()->id
            );

            Detail_Jadwal::create($data);
        };

        return null;
    }
}
