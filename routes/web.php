<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\JadwalOperatorController;
use App\Http\Controllers\ManajemenController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AbsensiPdfController;
use App\Exports\AbsensiExport;
use App\Http\Controllers\FingerprintController;
use App\Http\Controllers\PenjualanController;
use App\Models\Penjualan;
use Maatwebsite\Excel\Facades\Excel;

// ===================
// AUTH & LOGIN ROUTES
// ===================
Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::get('/kehadiran', [KehadiranController::class, 'index'])->name('kehadiran');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ===================
// DASHBOARD
// ===================
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// ===================
// ABSENSI
// ===================
Route::middleware('auth')->group(function () {
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.form');
    Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::post('/absensi/istirahat', [AbsensiController::class, 'istirahat'])->name('absensi.istirahat');
    Route::post('/absensi/kembali', [AbsensiController::class, 'kembaliNozle'])->name('absensi.kembali');
    Route::post('/absensi/pulang', [AbsensiController::class, 'pulang'])->name('absensi.pulang');
    Route::post('/absensi/mulai-backup', [AbsensiController::class, 'mulaiBackup'])->name('absensi.mulaiBackup');
    Route::post('/absensi/selesaiBackup', [AbsensiController::class, 'selesaiBackup'])->name('absensi.selesaiBackup');
    Route::get('/rekapabsensi', [AbsensiController::class, 'rekap'])->name('rekap.absensi');
    Route::get('/download-absensi', function () {
        return Excel::download(new AbsensiExport, 'absensi_karyawan.xls');
    });
    Route::get('/download-absensi-pdf', [AbsensiPdfController::class, 'export']);
    Route::get('/download-rekap', [AbsensiController::class, 'ExportRekap'])->name('absensi.download.xls');
    Route::get('/download-absensi-pdf', [AbsensiPdfController::class, 'rekap']);
    Route::get('/jamkerja/{id}', [AbsensiController::class, 'hitungJamKerja'])->name('absensi.jamkerja');
});

// ===================
// JADWAL OPERATOR
// ===================
Route::middleware('auth')->group(function () {
    Route::get('/jadwal', [JadwalOperatorController::class, 'index'])->name('jadwal');
    Route::post('/jadwal', [JadwalOperatorController::class, 'store'])->name('jadwal.store');
    Route::post('/jadwal/update/{id}', [JadwalOperatorController::class, 'update'])->name('jadwal.update');
    Route::delete('/jadwal/{id}', [JadwalOperatorController::class, 'destroy'])->name('jadwal.destroy');
    Route::get('/download-jadwal', [JadwalOperatorController::class, 'downloadJadwalXls'])->name('jadwal.download.xls');
    Route::get('/download-jadwal-pdf', [AbsensiPdfController::class, 'download'])->name('jadwal.pdf.download');
    Route::get('/kalender', [JadwalOperatorController::class, 'kalender'])->name('kalender');
    Route::get('/kalender-api', [JadwalOperatorController::class, 'kalenderApi'])->name('kalender.api');
    Route::post('/jadwal/upload', [JadwalOperatorController::class, 'storeExcel'])->name('jadwal.upload');
});

// ===================
// MANAJEMEN
// ===================
Route::middleware('auth')->group(function () {
    Route::get('/manajemen', [ManajemenController::class, 'index'])->name('manajemen');
    Route::post('/karyawan-store', [ManajemenController::class, 'storeKaryawan'])->name('karyawan.store');
    Route::post('/karyawan/update', [ManajemenController::class, 'UpdateKaryawan'])->name('karyawan.update');
    Route::delete('/karyawan/{id}', [ManajemenController::class, 'destroyKaryawan'])->name('karyawan.destroy');
    Route::post('/nozle-store', [ManajemenController::class, 'storeNozle'])->name('nozle.store');
    Route::post('/nozle/update', [ManajemenController::class, 'UpdateNozle'])->name('nozle.update');
    Route::delete('/nozle/{id}', [ManajemenController::class, 'destroyNozle'])->name('nozle.destroy');
    Route::post('/edit/produk', [ManajemenController::class, 'EditProduk'])->name('produk.edit');
});
// ===================
// OWNER
// ===================
Route::middleware('auth')->group(function () {
    Route::get('/owner', [OwnerController::class, 'index'])->name('owner');
    Route::get('/absensiKaryawan', [OwnerController::class, 'ShowAbsen'])->name('absensi.karyawan');
    Route::get('/owner/spbu/{id}', [OwnerController::class, 'showSpbu'])->name('owner.spbu.show');
    Route::get('/api/kalender/{nomorSpbu}', [OwnerController::class, 'kalenderApi'])->name('owner.kalender.api');
    Route::get('/owner/{nomorSpbu}/download-jadwal', [OwnerController::class, 'downloadJadwalXls'])->name('owner.jadwal.download.xls');
    Route::get('/owner/{nomorSpbu}/download-jadwal-pdf', [OwnerController::class, 'downloadJadwalPdf'])->name('owner.jadwal.download.pdf');
    Route::get('/owner/spbu/{id}/absensi', [OwnerController::class, 'absensiDetil'])->name('owner.spbu.absensi');
    Route::get('/absensiDetil-excel/{id}', [OwnerController::class, 'exportAbsensiDetilExcel'])->name('owner.absensiDetil.excel');
    Route::get('/absensiDetil-pdf/{id}', [OwnerController::class, 'exportAbsensiDetilPdf'])->name('owner.absensiDetil.pdf');
    Route::get('/kehadiranKaryawan', [OwnerController::class, 'ShowKehadiran'])->name('kehadiran.karyawan');
    Route::get('/owner/spbu/{id}/kehadiran', [OwnerController::class, 'kehadiranDetil'])->name('owner.kehadiran.detil');
    Route::get('/kehadiran-detil-pdf/{id}', [OwnerController::class, 'exportKehadiranDetilPdf'])->name('owner.kehadiran.detil.pdf');
    Route::get('/kehadiran-detil-excel/{id}', [OwnerController::class, 'exportKehadiranDetilExcel'])->name('owner.kehadiran.detil.excel');
});

// ===================
// PENJUALAN
// ===================

Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
Route::post('/penjualan/store', [PenjualanController::class, 'store'])->name('penjualan.store');
Route::get('/penjualan/generate-laporan', [PenjualanController::class, 'generateLaporan'])->name('penjualan.generateLaporan');
Route::put('/penjualan/{id}', [PenjualanController::class, 'update'])->name('penjualan.update');
Route::get('/penjualan/{id}/edit', function ($id) {
    $penjualan = Penjualan::with(['nozle', 'pulau', 'produk'])->findOrFail($id);
    return response()->json($penjualan);
})->name('penjualan.edit_json');

// ===================
// KEHADIRAN
// ===================

Route::get('/kehadiran', [KehadiranController::class, 'index'])->name('kehadiran')->middleware('auth');
Route::get('/download-kehadiran-pdf', [AbsensiPdfController::class, 'kehadiran'])->name('kehadiran.pdf.download');
Route::get('/download-kehadiran', [KehadiranController::class, 'downloadkehadiranXls'])->name('kehadiran.download.xls');


// ===================
// DIVASE MESIN FINGERPRINT
// ===================

Route::prefix('iclock')->group(function () {
    Route::get('cdata',  [FingerprintController::class, 'handshake']);   // handshake
    Route::get('getrequest',   [FingerprintController::class, 'getrequest']);  // dummy (bisa kosong)
    Route::post('cdata',      [FingerprintController::class, 'cdata']);       // terima data
});


Route::get('status', [FingerprintController::class, 'status']);
