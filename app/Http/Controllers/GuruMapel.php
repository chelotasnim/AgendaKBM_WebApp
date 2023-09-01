<?php

namespace App\Http\Controllers;

use App\Models\GuruMapel as ModelsGuruMapel;
use Illuminate\Http\Request;

class GuruMapel extends Controller
{
    public function get_guru_mapel()
    {
        $data = ModelsGuruMapel::with('guru', 'mapel')->join('guru', 'guru_mapels.guru_id', '=', 'guru.id')->orderBy('guru.kode')->orderBy('guru_mapels.guru_mapel')->get();
        return response()->json($data);
    }

    public function add_guru_mapel()
    {
        $get_data = json_decode(request()->getContent(), true);
        $data = $get_data['guru_mapels'];

        foreach ($data as $guru) {
            $guru_mapel = ModelsGuruMapel::where('guru_id', $guru[0])->get();

            $process = true;
            foreach ($guru_mapel as $mapel) {
                if ($mapel->mapel_id == $guru[1]) {
                    $process = false;
                };
            };

            if ($process === true) {
                if (count($guru_mapel) === 0) {
                    ModelsGuruMapel::create([
                        'guru_id' => $guru[0],
                        'mapel_id' => $guru[1],
                        'status' => 1
                    ]);
                } else if (count($guru_mapel) === 1) {
                    ModelsGuruMapel::where('guru_id', $guru[0])->update(['guru_mapel' => 1]);
                    ModelsGuruMapel::create([
                        'guru_id' => $guru[0],
                        'mapel_id' => $guru[1],
                        'guru_mapel' => count($guru_mapel) + 1,
                        'status' => 1
                    ]);
                } else {
                    ModelsGuruMapel::create([
                        'guru_id' => $guru[0],
                        'mapel_id' => $guru[1],
                        'guru_mapel' => count($guru_mapel) + 1,
                        'status' => 1
                    ]);
                };
            };
        };

        return response()->json(['notification' => ['Data Added' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Guru Mapel Baru Berhasil Ditambahkan</div></div>']], 'success' => true]);
    }
}
