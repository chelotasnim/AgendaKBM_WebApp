<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Guru_Api extends Controller
{
    public function get_main($id_guru)
    {
        $main = Guru::where('id', $id_guru)->first();

        $data = array(
            'main_data' => $main,
        );

        return response()->json($data);
    }
}
