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
            0 => 'required|max:100',
            1 => 'required|min:5|max:25|unique:guru,username',
            2 => 'required|max:255|email:dns|unique:guru,email',
            3 => 'required',
            4 => 'required|max:1'
        ]);

        $data = [
            'name' => $row[0],
            'username' => $row[1],
            'email' => $row[2],
            'password' => bcrypt($row[3]),
            'status' => $row[4],
            'hidden' => 0,
            'action_by' => Auth::user()->id
        ];

        if ($validator->fails()) {
            $existingGuru = Guru::where('username', $data['username'])->where('hidden', 1)->orWhere('email', $data['email'])->where('hidden', 1)->first();

            if ($existingGuru !== null) {
                $existingGuru->update($data);
            };
            return null;
        }

        Guru::create($data);

        return null;
    }
}
