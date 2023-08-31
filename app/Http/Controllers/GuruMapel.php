<?php

namespace App\Http\Controllers;

use App\Models\GuruMapel as ModelsGuruMapel;
use Illuminate\Http\Request;

class GuruMapel extends Controller
{
    public function get_guru_mapel()
    {
        $data = ModelsGuruMapel::with('guru', 'mapel')->get();
        return response()->json($data);
    }

    public function add_guru_mapel()
    {
        $get_data = json_decode(request()->getContent(), true);
        $data = $get_data['guru_mapels'];

        foreach ($data as $guru) {
            $guru_mapel = ModelsGuruMapel::where('guru_id', $guru[0])->orderBy('guru_mapel', 'desc')->first();

            if (isset($guru_mapel)) {
                if ($guru_mapel->guru_mapel == null) {
                    ModelsGuruMapel::where('id', $guru_mapel->id)->update(['guru_mapel' => 1]);
                    ModelsGuruMapel::create([
                        'guru_id' => $guru[0],
                        'mapel_id' => $guru[1],
                        'guru_mapel' => 2
                    ]);
                } else {
                    ModelsGuruMapel::create([
                        'guru_id' => $guru[0],
                        'mapel_id' => $guru[1],
                        'guru_mapel' => $guru_mapel->guru_mapel++
                    ]);
                };
            } else {
                ModelsGuruMapel::create([
                    'guru_id' => $guru[0],
                    'mapel_id' => $guru[1]
                ]);
            };
        };

        return response()->json(['notification' => ['Data Added' => ['<div class="toast toast-success" aria-live="assertive"><div class="toast-message">Guru Mapel Baru Berhasil Ditambahkan</div></div>']], 'success' => true]);
    }
}
