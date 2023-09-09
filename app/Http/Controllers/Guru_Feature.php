<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Guru_Feature extends Controller
{
    public function get_main($id_guru, $day_selected = 'today')
    {
        $selected_day = $day_selected;
        if ($day_selected == 'today') {
            $selected_day = Carbon::now()->isoFormat('dddd');
        };
        $dayNames = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $dayIndex = array_search($selected_day, $dayNames);

        $main = Guru::with([
            'guru_mapel' => function ($query) use ($selected_day) {
                $query->where('status', 1)
                    ->with([
                        'jadwal' => function ($subQuery) use ($selected_day) {
                            $subQuery->where('hari', $selected_day)
                                ->with('jam');
                        }
                    ])
                    ->with('mapel');
            }
        ])->where('id', $id_guru)->first();

        if (!isset($main->guru_mapel)) {
            return response()->json([
                'main_data' => $main,
                'now_date' => array(
                    'day_name' => $selected_day,
                    'date' => Carbon::now()->isoFormat('D MMM YYYY')
                ),
                'yesterday' => $dayNames[($dayIndex - 1 + 5) % 5],
                'tomorrow' => $dayNames[($dayIndex + 1) % 5],
                'found' => false
            ]);
        };
    }
}
