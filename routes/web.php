<?php

use App\Http\Controllers\Guru_Feature;
use App\Http\Controllers\GuruMapel;
use App\Http\Controllers\Gurus;
use App\Http\Controllers\Jadwal as ControllersJadwal;
use App\Http\Controllers\Jams;
use App\Http\Controllers\Kelases;
use App\Http\Controllers\Mapels;
use App\Http\Controllers\Siswa_Feature;
use App\Http\Controllers\Siswas;
use App\Http\Controllers\Users;
use App\Models\Guru;
use App\Models\GuruMapel as ModelsGuruMapel;
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

//Pengguna Tidak Terautentikasi
Route::middleware('guest')->group(function () {
    //Login
    Route::get('/', function () {
        return view('login');
    })->name('login');

    //Aksi
    Route::post('/', [Users::class, 'login']);



    //Registrasi
    // Route::get('regist', function () {
    //     $data = array(
    //         'all_kelas' => Kelas::whereHas('jenjang', function ($query) {
    //             $query->where('hidden', 0);
    //             $query->where('status', 1);
    //         })->with('jenjang')->where('hidden', 0)->where('status', 1)->get()
    //     );

    //     return view('regist', $data);
    // });
});

//Autentikasi Super Admin
Route::middleware('auth:web')->group(function () {
    //Logout
    Route::get('logout', [Users::class, 'logout']);

    //Pengaturan Jam
    Route::get('dashboard/jam', function () {
        $data = array(
            'page' => 'jam'
        );

        return view('dashboard.jam.index', $data);
    });

    //Aksi
    Route::get('dashboard/get_jam', [Jams::class, 'get_jam']);
    Route::post('dashboard/jam', [Jams::class, 'add_jam']);
    Route::post('dashboard/edit_jam', [Jams::class, 'edit_jam']);



    //Mata Pelajaran
    Route::get('dashboard/mapel', function () {
        $data = array(
            'page' => 'mapel'
        );
        return view('dashboard.mapel.index', $data);
    });

    //Aksi
    Route::get('dashboard/get_mapel', [Mapels::class, 'get_data']);
    Route::post('dashboard/mapel', [Mapels::class, 'store']);
    Route::post('dashboard/edit_mapel', [Mapels::class, 'edit']);
    Route::post('dashboard/delete_mapel', [Mapels::class, 'delete']);
    Route::post('dashboard/import_mapel', [Mapels::class, 'import']);



    //Kelas
    Route::get('dashboard/kelas', function () {
        $data = array(
            'page' => 'kelas',
            'jenjang' => Jenjang_Kelas::where('hidden', 0)->where('status', 1)->orderBy('jenjang', 'asc')->get()
        );
        return view('dashboard.kelas.index', $data);
    });

    //Aksi
    Route::get('dashboard/get_kelas', [Kelases::class, 'get_data']);
    Route::post('dashboard/kelas', [Kelases::class, 'store']);
    Route::post('dashboard/edit_kelas', [Kelases::class, 'edit']);
    Route::post('dashboard/delete_kelas', [Kelases::class, 'delete']);
    Route::post('dashboard/import_kelas', [Kelases::class, 'import']);
    Route::post('dashboard/naik_kelas', [Kelases::class, 'up']);



    //Guru Mapel
    Route::get('dashboard/guru_mapel', function () {
        $data = array(
            'page' => 'guru_mapel',
            'guru' => Guru::where('hidden', 0)->where('status', 1)->get(),
            'mapel' => Mapel::where('hidden', 0)->where('status', 1)->get()
        );
        return view('dashboard.guru_mapel.index', $data);
    });

    //Aksi
    Route::get('dashboard/get_guru_mapel', [GuruMapel::class, 'get_guru_mapel']);
    Route::post('dashboard/guru_mapel', [GuruMapel::class, 'add_guru_mapel']);
    Route::post('dashboard/edit_guru_mapel', [GuruMapel::class, 'edit_guru_mapel']);
    Route::post('dashboard/import_guru_mapel', [GuruMapel::class, 'import']);



    //Jadwal
    Route::get('dashboard/jadwal', function () {
        $data = array(
            'page' => 'jadwal',
            'guru_mapel' => ModelsGuruMapel::where('status', 1)->get(),
            'kelas' => Kelas::get()
        );

        return view('dashboard.jadwal.index', $data);
    });

    //Aksi
    Route::post('dashboard/get_jadwal', [ControllersJadwal::class, 'get_jadwal']);
    Route::post('dashboard/edit_jadwal', [ControllersJadwal::class, 'edit_jadwal']);
    Route::post('dashboard/reset_jadwal', [ControllersJadwal::class, 'reset']);
    Route::post('dashboard/import_jadwal', [ControllersJadwal::class, 'import']);



    //Guru
    Route::get('dashboard/guru', function () {
        $data = array(
            'page' => 'guru',
        );
        return view('dashboard.guru.index', $data);
    });

    //Aksi
    Route::get('dashboard/get_guru', [Gurus::class, 'get_data']);
    Route::post('dashboard/guru', [Gurus::class, 'store']);
    Route::post('dashboard/edit_guru', [Gurus::class, 'edit']);
    Route::post('dashboard/delete_guru', [Gurus::class, 'delete']);
    Route::post('dashboard/import_guru', [Gurus::class, 'import']);
    Route::post('dashboard/add_jurnal', [Gurus::class, 'jurnal']);
    Route::post('dashboard/edit_jurnal', [Gurus::class, 'edit_jurnal']);
    Route::post('dashboard/get_jurnal', [Gurus::class, 'get_jurnal']);



    //Admin
    Route::get('dashboard/admin', function () {
        $data = array(
            'page' => 'admin',
        );
        return view('dashboard.admin.index', $data);
    });

    //Aksi
    Route::get('dashboard/get_admin', [Users::class, 'get_data']);
    Route::post('dashboard/admin', [Users::class, 'store']);
    Route::post('dashboard/edit_admin', [Users::class, 'edit']);
    Route::post('dashboard/delete_admin', [Users::class, 'delete']);



    //Siswa
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

    //Aksi
    Route::get('dashboard/get_siswa', [Siswas::class, 'get_data']);
    Route::post('dashboard/siswa', [Siswas::class, 'store']);
    Route::post('dashboard/edit_siswa', [Siswas::class, 'edit']);
    Route::post('dashboard/delete_siswa', [Siswas::class, 'delete']);
    Route::post('dashboard/import_siswa', [Siswas::class, 'import']);



    //Jurnal
    Route::get('dashboard/jurnal', function () {
        $data = array(
            'page' => 'laporan',
            'guru_mapel' => ModelsGuruMapel::where('status', 1)->get(),
            'kelas' => Kelas::with('jenjang')->get()
        );
        return view('dashboard.jurnal.index', $data);
    });

    //Aksi
    Route::get('dashboard/add_jurnal', function () {
        $data = array(
            'page' => 'add_jurnal',
            'kelas' => Kelas::where('status', 1)->where('hidden', 0)->get(),
            'guru_mapel' => ModelsGuruMapel::where('status', 1)->get()
        );
        return view('dashboard.jurnal.add', $data);
    });
});

//Autentikasi Siswa
Route::middleware('auth:student')->group(function () {
    //Log Out
    Route::get('student_logout', [Users::class, 'student_logout']);

    //Laman Utama
    Route::get('student', function () {
        return view('mobile.student.index');
    });

    Route::get('student/{day}', [Siswa_Feature::class, 'get_main']);

    Route::post('student/update', [Siswa_Feature::class, 'edit']);
});

//Autentikasi Guru
Route::middleware('auth:teacher')->group(function () {
    //Log Out
    Route::get('teacher_logout', [Users::class, 'teacher_logout']);

    //Laman Utama
    Route::get('teacher', function () {
        return view('mobile.teacher.index');
    });
    Route::get('teacher/{day}', [Guru_Feature::class, 'get_main']);

    //Laman Jurnal
    Route::get('teacher/jurnal/{id}', function () {
        return view('mobile.teacher.jurnal');
    });
    Route::post('send_jurnal', [Guru_Feature::class, 'send_jurnal']);

    Route::post('teacher/update', [Guru_Feature::class, 'edit']);
});
