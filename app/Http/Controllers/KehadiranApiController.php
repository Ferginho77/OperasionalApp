<?php

namespace App\Http\Controllers;

use App\Models\Absensi; // Pastikan model Absensi di-import
use App\Models\Kehadiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Untuk logging, opsional

class KehadiranApiController extends Controller
{
      /**
     * Mengambil data absensi berdasarkan KaryawanId (UID) dan Spbuid (ID Mesin).
     * Endpoint: GET /api/{uid}/{id_mesin}
     *
     * @param string $user_id KaryawanId dari mesin absensi
     * @param string $id_mesin Spbuser_id (ID unik mesin absensi)
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAbsensi($user_id, $id_mesin)
    {
        Log::info("API Absensi: Menerima permintaan untuk user_id: {$user_id}, ID Mesin: {$id_mesin}");

        // Cari data absensi berdasarkan KaryawanId dan Spbuser_id
        // Mengambil record terakhir berdasarkan WaktuMasuk (data terbaru)
        $data_absensi = Kehadiran::where('KaryawanId', $user_id)
                               ->where('SpbuId', $id_mesin)
                               ->orderBy('WaktuMasuk', 'desc')
                               ->first();

        if ($data_absensi) {
            Log::info("API Absensi: Data ditemukan untuk user_id: {$user_id}, ID Mesin: {$id_mesin}.");
            return response()->json([
                'status' => 'success',
                'data' => $data_absensi
            ], 200); // 200 OK
        }

        Log::warning("API Absensi: Data tidak ditemukan untuk user_id: {$user_id}, ID Mesin: {$id_mesin}.");
        return response()->json([
            'status' => 'error',
            'message' => 'Data absensi tidak ditemukan.'
        ], 404); // 404 Not Found
    }
}
