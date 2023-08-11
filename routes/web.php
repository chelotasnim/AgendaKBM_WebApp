<?php

use App\Http\Controllers\Gurus;
use App\Http\Controllers\Jadwals;
use App\Http\Controllers\Kelases;
use App\Http\Controllers\Mapels;
use App\Http\Controllers\Siswas;
use App\Http\Controllers\Users;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Jenjang_Kelas;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('login');
    })->name('login');

    Route::post('/', [Users::class, 'login']);

    Route::get('regist', function () {
        $data = array(
            'all_kelas' => Kelas::whereHas('jenjang', function ($query) {
                $query->where('hidden', 0);
                $query->where('status', 1);
            })->with('jenjang')->where('hidden', 0)->where('status', 1)->get()
        );

        return view('regist', $data);
    });
});

Route::middleware('auth:web')->group(function () {
    Route::get('logout', [Users::class, 'logout']);
    // Route::get('dashboard/jenjang_kelas', function () {
    //     $data = array(
    //         'page' => 'jenjang_kelas'
    //     );
    //     return view('dashboard.jenjang_kelas.index', $data);
    // });

    Route::get('dashboard/mapel', function () {
        $data = array(
            'page' => 'mapel'
        );
        return view('dashboard.mapel.index', $data);
    });

    Route::get('dashboard/kelas', function () {
        $data = array(
            'page' => 'kelas',
            'jenjang' => Jenjang_Kelas::where('hidden', 0)->where('status', 1)->orderBy('jenjang', 'asc')->get()
        );
        return view('dashboard.kelas.index', $data);
    });

    Route::get('dashboard/jadwal/{id}', function ($id) {
        $data = array(
            'page' => 'kelas',
            'kelas' => Kelas::with('jenjang')->where('id', $id)->first(),
            'guru' => Guru::where('hidden', 0)->where('status', 1)->select('id', 'name')->get(),
            'mapel' => Mapel::where('hidden', 0)->where('status', 1)->select('id', 'nama_mapel')->get()
        );
        return view('dashboard.jadwal.index', $data);
    });

    Route::get('dashboard/jadwal/edit/{id}', function ($id) {
        $data = array(
            'page' => 'kelas',
            'jadwal' => Jadwal::with('kelas', 'kelas.jenjang', 'details', 'details.mapel', 'details.guru')->where('id', $id)->first(),
            'guru' => Guru::where('hidden', 0)->where('status', 1)->select('id', 'name')->get(),
            'mapel' => Mapel::where('hidden', 0)->where('status', 1)->select('id', 'nama_mapel')->get()
        );
        return view('dashboard.jadwal.edit', $data);
    });

    Route::get('dashboard/jadwal/column/{id}', function ($id) {
        $data = array(
            'page' => 'kelas',
            'jadwal' => Jadwal::with('kelas', 'kelas.jenjang', 'details', 'details.mapel', 'details.guru')->where('id', $id)->first(),
            'guru' => Guru::where('hidden', 0)->where('status', 1)->select('id', 'name')->get(),
            'mapel' => Mapel::where('hidden', 0)->where('status', 1)->select('id', 'nama_mapel')->get()
        );
        return view('dashboard.jadwal.column',  $data);
    });

    Route::get('dashboard/jadwal/column_remove/{id}', function ($id) {
        $data = array(
            'page' => 'kelas',
            'jadwal' => Jadwal::with('kelas', 'kelas.jenjang', 'details', 'details.mapel', 'details.guru')->where('id', $id)->first()
        );
        return view('dashboard.jadwal.delete',  $data);
    });

    Route::get('dashboard/guru', function () {
        $data = array(
            'page' => 'guru',
        );
        return view('dashboard.guru.index', $data);
    });

    Route::get('dashboard/admin', function () {
        $data = array(
            'page' => 'admin',
        );
        return view('dashboard.admin.index', $data);
    });

    Route::get('dashboard/siswa', function () {
        $data = array(
            'page' => 'siswa',
            'kelas' => Kelas::whereHas('jenjang', function ($query) {
                $query->where('hidden', 0);
                $query->where('status', 1);
            })->with('jenjang')->where('hidden', 0)->where('status', 1)->get()
        );
        return view('dashboard.siswa.index', $data);
    });

    Route::get('dashboard/get_mapel', [Mapels::class, 'get_data']);
    Route::post('dashboard/mapel', [Mapels::class, 'store']);
    Route::post('dashboard/edit_mapel', [Mapels::class, 'edit']);
    Route::post('dashboard/delete_mapel', [Mapels::class, 'delete']);
    Route::post('dashboard/import_mapel', [Mapels::class, 'import']);

    Route::get('dashboard/get_kelas', [Kelases::class, 'get_data']);
    Route::post('dashboard/kelas', [Kelases::class, 'store']);
    Route::post('dashboard/edit_kelas', [Kelases::class, 'edit']);
    Route::post('dashboard/delete_kelas', [Kelases::class, 'delete']);
    Route::post('dashboard/import_kelas', [Kelases::class, 'import']);
    Route::post('dashboard/naik_kelas', [Kelases::class, 'up']);

    Route::get('dashboard/jadwal/get_jadwal/{id}', [Jadwals::class, 'get_jadwal']);
    Route::post('dashboard/jadwal/store/main', [Jadwals::class, 'store']);
    Route::post('dashboard/jadwal/store/schedule', [Jadwals::class, 'store_schedule']);
    Route::post('dashboard/jadwal/edit/main', [Jadwals::class, 'update']);
    Route::post('dashboard/jadwal/edit/schedule', [Jadwals::class, 'update_schedule']);
    Route::post('dashboard/jadwal/delete/schedule', [Jadwals::class, 'remove_schedule']);

    Route::get('dashboard/get_admin', [Users::class, 'get_data']);
    Route::post('dashboard/admin', [Users::class, 'store']);
    Route::post('dashboard/edit_admin', [Users::class, 'edit']);
    Route::post('dashboard/delete_admin', [Users::class, 'delete']);

    Route::get('dashboard/get_guru', [Gurus::class, 'get_data']);
    Route::post('dashboard/guru', [Gurus::class, 'store']);
    Route::post('dashboard/edit_guru', [Gurus::class, 'edit']);
    Route::post('dashboard/delete_guru', [Gurus::class, 'delete']);
    Route::post('dashboard/import_guru', [Gurus::class, 'import']);

    Route::get('dashboard/get_siswa', [Siswas::class, 'get_data']);
    Route::post('dashboard/siswa', [Siswas::class, 'store']);
    Route::post('dashboard/edit_siswa', [Siswas::class, 'edit']);
    Route::post('dashboard/delete_siswa', [Siswas::class, 'delete']);
    Route::post('dashboard/import_siswa', [Siswas::class, 'import']);
});

Route::middleware('auth:student')->group(function () {
    Route::get('student_logout', [Users::class, 'student_logout']);

    Route::get('student', function () {
        return view('mobile.student.index');
    });
});

Route::middleware('auth:teacher')->group(function () {
    Route::get('teacher_logout', [Users::class, 'teacher_logout']);

    Route::get('teacher', function () {
        return view('mobile.teacher.index');
    });
});
