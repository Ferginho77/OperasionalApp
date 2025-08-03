<?php

use App\Http\Controllers\KehadiranApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KehadiranController; // Pastikan controller di-import

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

// Rute untuk membuat record absen masuk (POST)
Route::post('/kehadiran', [KehadiranController::class, 'store']);

// Rute untuk mendapatkan status kehadiran (GET)
// {karyawanId} dan {tanggal} adalah parameter yang akan diterima oleh metode show
Route::get('/kehadiran/{karyawanId}/{tanggal}', [KehadiranController::class, 'show']);

// Rute untuk memperbarui record absen pulang (PUT)
// {karyawanId} adalah parameter untuk mengidentifikasi karyawan yang akan diupdate
Route::put('/kehadiran/{karyawanId}', [KehadiranController::class, 'update']);

// Contoh rute user jika ada
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route baru untuk mengambil data absensi dari mesin ZKTeco
Route::get('/{uid}/{id_mesin}', [KehadiranApiController::class, 'getAbsensi']);

