<?php

namespace App\Imports;

use App\Models\Guru;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class GuruImport implements ToModel, WithStartRow
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
            0 => 'required|unique:guru,kode',
            1 => 'required|max:100',
            2 => 'required|min:3|max:25|unique:guru,username|unique:siswa,username',
            3 => 'required|max:255|email:dns|unique:guru,email|unique:siswa,email|unique:users,email',
            4 => 'required',
            5 => 'required|max:1'
        ]);

        $data = [
            'kode' => $row[0],
            'name' => $row[1],
            'username' => $row[2],
            'email' => $row[3],
            'password' => bcrypt($row[4]),
            'status' => $row[5],
            'hidden' => 0,
            'action_by' => Auth::user()->id
        ];

        if ($validator->fails()) {
            $existingGuru = Guru::where('kode', $data['kode'])->where('hidden', 1)->orWhere('username', $data['username'])->where('hidden', 1)->orWhere('email', $data['email'])->where('hidden', 1)->first();

            if ($existingGuru !== null) {
                $existingGuru->update($data);
            };
            return null;
        }

        Guru::create($data);

        return null;
    }
}
