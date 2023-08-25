<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SiswaImport implements ToModel, WithStartRow
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
            0 => 'required|min:3|max:12',
            1 => 'required|min:9|max:10|unique:siswa,username|unique:guru,username',
            2 => 'required|max:100',
            3 => 'required|max:255|email:dns|unique:siswa,email|unique:guru,email|unique:users,email',
            4 => 'required',
            5 => 'required|max:1'
        ]);

        $data = [
            'name' => $row[2],
            'username' => $row[1],
            'email' => $row[3],
            'password' => bcrypt($row[4]),
            'status' => $row[5],
            'hidden' => 0,
            'action_by' => Auth::guard('web')->user()->id
        ];

        $split_kelas = explode(' ', $row[0]);
        $get_jenjang = $split_kelas[0];
        $get_kelas = str_replace($get_jenjang . ' ', '', $row[0]);

        $data_kelas = Kelas::whereHas('jenjang', function ($query) use ($get_jenjang) {
            $query->where('jenjang', $get_jenjang);
            $query->where('hidden', 0);
            $query->where('status', 1);
        })->with('jenjang')->where('name', $get_kelas)->where('hidden', 0)->where('status', 1)->select('id')->first();

        if ($data_kelas != null) {
            $data['kelas_id'] = $data_kelas->id;
        } else {
            return null;
        };

        if ($validator->fails()) {
            $existingSiswa = Siswa::where('username', $data['username'])->where('hidden', 1)->orWhere('email', $data['email'])->where('hidden', 1)->first();

            if ($existingSiswa !== null) {
                $existingSiswa->update($data);
            };
            return null;
        };

        Siswa::create($data);

        return null;
    }
}
