<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OwnerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsensiController;
use App\Exports\AbsensiExport;
use App\Http\Controllers\AbsensiPdfController;
use Maatwebsite\Excel\Facades\Excel;

// Route::get('/', function () {
//     return view('welcome');
// });
//Route Login
Route::get('/', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->name('login.post')->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Route Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Route Absensi
Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.form')->middleware('auth');
Route::get('/download-absensi', function () {
    return Excel::download(new AbsensiExport, 'absensi_karyawan.xls');
});
Route::get('/download-absensi-pdf', [AbsensiPdfController::class, 'export']);
// Fungsi post untuk setiap aksi
Route::resource('absensi', AbsensiController::class);
Route::put('absensi/{id}/istirahat', [AbsensiController::class, 'istirahat'])->name('absensi.istirahat');
Route::put('absensi/{id}/pindah', [AbsensiController::class, 'pindahNozle'])->name('absensi.pindah');
Route::put('absensi/{id}/kembali', [AbsensiController::class, 'kembaliNozle'])->name('absensi.kembali');
Route::put('absensi/{id}/pulang', [AbsensiController::class, 'pulang'])->name('absensi.pulang');

// Route Owner
Route::get('/owner', [OwnerController::class, 'index'])->name('owner')->middleware('auth');
Route::get('/absensiKaryawan', [OwnerController::class, 'ShowAbsen'])->name('absensi.karyawan')->middleware('auth');