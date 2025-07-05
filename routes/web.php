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

Route::middleware(['auth'])->group(function() {
    Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::post('/absensi/istirahat', [AbsensiController::class, 'istirahat'])->name('absensi.istirahat');
    Route::post('/absensi/kembali', [AbsensiController::class, 'kembaliNozle'])->name('absensi.kembali');
    Route::post('/absensi/pulang', [AbsensiController::class, 'pulang'])->name('absensi.pulang');
    Route::post('/absensi/mulai-backup', [AbsensiController::class, 'mulaiBackup'])->name('absensi.mulaiBackup');
    Route::post('/absensi/selesaiBackup', [AbsensiController::class, 'selesaiBackup'])->name('absensi.selesaiBackup');
});

// Route Owner
Route::get('/owner', [OwnerController::class, 'index'])->name('owner')->middleware('auth');
Route::get('/absensiKaryawan', [OwnerController::class, 'ShowAbsen'])->name('absensi.karyawan')->middleware('auth');
Route::get('/owner/spbu/{id}', [OwnerController::class, 'showSpbu'])->name('owner.spbu.show');
Route::get('/api/kalender/{nomorSpbu}', [OwnerController::class, 'kalenderApi'])->name('owner.kalender.api');
Route::get('/owner/{nomorSpbu}/download-jadwal', [OwnerController::class, 'downloadJadwalXls'])->name('owner.jadwal.download.xls');
Route::get('/owner/{nomorSpbu}/download-jadwal-pdf', [OwnerController::class, 'downloadJadwalPdf'])->name('owner.jadwal.download.pdf');


//Route Kehadiran
Route::get('/kehadiran', [KehadiranController::class, 'index'])->name('kehadiran')->middleware('guest');

// Satu halaman utama
Route::get('/jadwal', [JadwalOperatorController::class, 'index'])->name('jadwal');

// Aksi store & update di form yang sama
Route::post('/jadwal', [JadwalOperatorController::class, 'store'])->name('jadwal.store');
Route::post('/jadwal/update/{id}', [JadwalOperatorController::class, 'update'])->name('jadwal.update');

// Hapus jadwal
Route::delete('/jadwal/{id}', [JadwalOperatorController::class, 'destroy'])->name('jadwal.destroy');
Route::get('/download-jadwal', [JadwalOperatorController::class, 'downloadJadwalXls'])->name('jadwal.download.xls');
Route::get('/download-jadwal-pdf', [AbsensiPdfController::class, 'download'])->name('jadwal.pdf.download');

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