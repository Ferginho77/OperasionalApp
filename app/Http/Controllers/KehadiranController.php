<?php

namespace App\Http\Controllers;

use App\Exports\KehadiranExport;
use App\Models\Kehadiran; // Pastikan model Kehadiran sudah di-import
use App\Models\Spbu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Untuk logging
use Maatwebsite\Excel\Facades\Excel;

class KehadiranController extends Controller
{

   public function index()
{
    $user = Auth::user();
    $spbu = Spbu::where('NomorSPBU', $user->NomorSPBU)->first();

    if (!$spbu) {
        // Handle the case where the SPBU is not found
        return view('kehadiran', ['kehadirans' => collect([])]);
    }

    $SpbuId = $spbu->id;

    $kehadirans = Kehadiran::with('karyawan')
        ->where('SpbuId', $SpbuId) // Replace with correct column name
        ->orderBy('WaktuMasuk', 'desc')
        ->get();

    return view('kehadiran', compact('kehadirans'));
}


    /**
     * Menyimpan record absensi masuk baru.
     * Hanya untuk absen masuk pertama kali dalam sehari.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'KaryawanId' => 'required|integer',
            'WaktuMasuk' => 'required|date',
            'SpbuId' => 'required|integer',
        ]);

        $karyawanId = $request->KaryawanId;
        $spbuId = $request->SpbuId;
        $waktuMasuk = $request->WaktuMasuk; // Gunakan WaktuMasuk sebagai waktu scan
        $tanggal = date('Y-m-d', strtotime($waktuMasuk));

        Log::info("API Store: Menerima permintaan untuk KaryawanId: {$karyawanId}, Tanggal: {$tanggal}");

        // Cek apakah sudah ada absen masuk hari ini untuk karyawan ini
        $absen = Kehadiran::where('KaryawanId', $karyawanId)
            ->whereDate('WaktuMasuk', $tanggal)
            ->first();

        if ($absen) {
            // Jika record absen masuk sudah ada, kembalikan konflik
            Log::warning("API Store: Absen masuk sudah ada untuk KaryawanId: {$karyawanId} pada {$tanggal}.");
            return response()->json(['message' => 'Sudah absen masuk hari ini.'], 409); // 409 Conflict
        }

        // Absen belum ada, maka buat data absen masuk baru
        try {
            Kehadiran::create([
                'KaryawanId' => $karyawanId,
                'WaktuMasuk' => $waktuMasuk,
                'SpbuId'     => $spbuId,
                // WaktuPulang dibiarkan null secara default
            ]);
            Log::info("API Store: Absen masuk berhasil untuk KaryawanId: {$karyawanId} pada {$waktuMasuk}.");
            return response()->json(['message' => 'Absen masuk berhasil.'], 201); // 201 Created
        } catch (\Exception $e) {
            Log::error("API Store: Gagal membuat absen masuk untuk KaryawanId: {$karyawanId}. Error: " . $e->getMessage());
            return response()->json(['message' => 'Gagal menyimpan absen masuk.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Menampilkan status kehadiran untuk karyawan pada tanggal tertentu.
     * Digunakan oleh Python untuk mengecek apakah sudah absen masuk/pulang.
     */
    public function show($karyawanId, $tanggal)
    {
        Log::info("API Show: Menerima permintaan untuk KaryawanId: {$karyawanId}, Tanggal: {$tanggal}");

        $kehadiran = Kehadiran::where('KaryawanId', $karyawanId)
            ->whereDate('WaktuMasuk', $tanggal) // Cari berdasarkan tanggal WaktuMasuk
            ->first();

        if (!$kehadiran) {
            Log::info("API Show: Tidak ditemukan record untuk KaryawanId: {$karyawanId} pada {$tanggal}.");
            return response()->json(null, 204); // 204 No Content jika tidak ditemukan
        }

        Log::info("API Show: Record ditemukan untuk KaryawanId: {$karyawanId} pada {$tanggal}.");
        return response()->json($kehadiran, 200); // 200 OK dengan data kehadiran
    }

    /**
     * Memperbarui record absensi (khususnya untuk WaktuPulang).
     */
    public function update(Request $request, $karyawanId)
    {
        // Validasi input
        $request->validate([
            'WaktuPulang' => 'required|date',
            'SpbuId' => 'required|integer', // Meskipun tidak digunakan untuk update, tetap validasi jika diperlukan
        ]);

        $waktuPulang = $request->WaktuPulang;
        $tanggalHariIni = date('Y-m-d', strtotime($waktuPulang)); // Ambil tanggal dari WaktuPulang

        Log::info("API Update: Menerima permintaan untuk KaryawanId: {$karyawanId}, WaktuPulang: {$waktuPulang}");

        // Cari record absen masuk untuk hari ini
        $absen = Kehadiran::where('KaryawanId', $karyawanId)
            ->whereDate('WaktuMasuk', $tanggalHariIni)
            ->first();

        if (!$absen) {
            Log::warning("API Update: Record absen masuk tidak ditemukan untuk KaryawanId: {$karyawanId} pada {$tanggalHariIni}.");
            return response()->json(['message' => 'Absen masuk belum ada untuk hari ini.'], 404); // 404 Not Found
        }

        if ($absen->WaktuPulang) {
            Log::info("API Update: KaryawanId: {$karyawanId} sudah absen pulang hari ini.");
            return response()->json(['message' => 'Sudah absen pulang hari ini.'], 200); // Sudah absen pulang
        }

        // Update WaktuPulang
        try {
            $absen->update([
                'WaktuPulang' => $waktuPulang,
            ]);
            Log::info("API Update: Absen pulang berhasil untuk KaryawanId: {$karyawanId} pada {$waktuPulang}.");
            return response()->json(['message' => 'Absen pulang berhasil.'], 200); // 200 OK
        } catch (\Exception $e) {
            Log::error("API Update: Gagal memperbarui absen pulang untuk KaryawanId: {$karyawanId}. Error: " . $e->getMessage());
            return response()->json(['message' => 'Gagal menyimpan absen pulang.', 'error' => $e->getMessage()], 500);
        }
    }

   public function downloadKehadiranXls()
{
    return Excel::download(new KehadiranExport(), 'kehadiran_karyawan.xls');
}

}
