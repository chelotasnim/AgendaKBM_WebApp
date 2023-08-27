<?php

use App\Http\Controllers\ApiAuthentication;
use App\Http\Controllers\Guru_Api;
use App\Http\Controllers\Gurus;
use App\Http\Controllers\Siswa_Api;
use App\Http\Controllers\Siswas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//Registrasi Akun Pengguna
Route::post('teacher/self_registration', [Gurus::class, 'self_regist']);
Route::post('siswa/self_registration', [Siswas::class, 'self_regist']);

//API Login
Route::post('login', [ApiAuthentication::class, 'login']);

//API Terautentikasi
Route::middleware('auth:sanctum')->group(function () {

    //API Autorisasi Siswa
    Route::get('student/{day}', [Siswa_Api::class, 'get_main']);

    //API Autorisasi Guru
    Route::get('teacher', [Guru_Api::class, 'get_main']);

    Route::get('get_jurnal/{id}', [Guru_Api::class, 'get_jurnal']);

    Route::get('get_all_jurnal', [Guru_Api::class, 'get_all_jurnal']);

    Route::post('send_jurnal', [Guru_Api::class, 'send_jurnal']);

    Route::get('logout', [ApiAuthentication::class, 'logout']);
});
