<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JadwalOperatorController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ManajemenController;
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
Route::get('/kalender-api', [JadwalOperatorController::class, 'kalenderApi'])->name('kalender.api');

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
Route::get('/absensi/totalizer-akhir', [AbsensiController::class, 'getTotalizerAkhir']);

// Route Owner
Route::get('/owner', [OwnerController::class, 'index'])->name('owner')->middleware('auth');
Route::get('/absensiKaryawan', [OwnerController::class, 'ShowAbsen'])->name('absensi.karyawan')->middleware('auth');

//Route Kehadiran
Route::get('/kehadiran', [KehadiranController::class, 'index'])->name('kehadiran')->middleware('guest');

// Satu halaman utama
Route::get('/jadwal', [JadwalOperatorController::class, 'index'])->name('jadwal');

// Aksi store & update di form yang sama
Route::post('/jadwal', [JadwalOperatorController::class, 'store'])->name('jadwal.store');
Route::post('/jadwal/update/{id}', [JadwalOperatorController::class, 'update'])->name('jadwal.update');

// Hapus jadwal
Route::delete('/jadwal/{id}', [JadwalOperatorController::class, 'destroy'])->name('jadwal.destroy');

// Halaman kalender (view)
Route::get('/kalender', [JadwalOperatorController::class, 'kalender'])->name('kalender');

// API kalender (data JSON, dsb)
Route::get('/kalender-api', [JadwalOperatorController::class, 'kalenderApi'])->name('kalender.api');

// Halaman manajemen
Route::get('/manajemen', [ManajemenController::class, 'index'])->name('manajemen')->middleware('auth');
Route::post('/karyawan-store', [ManajemenController::class, 'storeKaryawan'])->name('karyawan.store');
Route::delete('/karyawan/{id}', [ManajemenController::class, 'destroyKaryawan'])->name('karyawan.destroy');
Route::post('/karyawan/update', [ManajemenController::class, 'UpdateKaryawan'])->name('karyawan.update');
Route::post('/nozle-store', [ManajemenController::class, 'storeNozle'])->name('nozle.store');
Route::post('/nozle/update', [ManajemenController::class, 'UpdateNozle'])->name('nozle.update');
Route::delete('/nozle/{id}', [ManajemenController::class, 'destroyNozle'])->name('nozle.destroy');