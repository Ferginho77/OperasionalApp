<?php

namespace App\Http\Controllers;

use App\Models\Kehadiran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FingerprintController extends Controller
{
    public function cdata(Request $request)
    {
        $snFromRequest = $request->input('SN');
        $snFromEnv     = env('CODE_MESIN');

        // Simpan log request
        $log = [
            'sn'         => $snFromRequest,
            'option'     => $request->input('option'),
            'data'       => $request->getContent(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('fingerprint_logs')->insert($log);   // contoh nama tabel log

        if ($snFromRequest !== $snFromEnv) {
            return response()->json(['valid' => false]);
        }

        $lines = preg_split('/\r\n|\r|\n/', $request->getContent());
        foreach ($lines as $line) {
            if (empty(trim($line))) {
                continue;
            }

            $data = explode("\t", trim($line));

            // Kolom 0 = ID karyawan dari mesin fingerprint
            $employeeId = $data[0] ?? null;
            $timestamp  = $data[1] ?? null;

            if (!$employeeId || !$timestamp) {
                continue;
            }

            $time  = Carbon::parse($timestamp);
            $today = $time->format('Y-m-d');

            // Pastikan ID karyawan ada di tabel `karyawans`
            $karyawan = DB::table('karyawans')
                          ->where('fingerprint_id', $employeeId)   // pastikan kolom ini ada
                          ->first();

            if (!$karyawan) {
                continue;   // skip jika ID tidak dikenal
            }

            // Cek izin
            $hasIzin = DB::table('izin')
                          ->where('KaryawanId', $karyawan->id)
                          ->whereDate('Tanggal', $today)
                          ->exists();

            if ($hasIzin) {
                continue;
            }

            // Cek apakah sudah ada record kehadiran hari ini
            $kehadiran = Kehadiran::where('KaryawanId', $karyawan->id)
                                   ->whereDate('WaktuMasuk', $today)
                                   ->first();

            if (!$kehadiran) {
                // Tap-in pertama
                Kehadiran::create([
                    'KaryawanId'  => $karyawan->id,
                    'WaktuMasuk'  => $time,
                    'WaktuPulang' => null,
                    'SpbuId'      => 5
                ]);
            } else {
                // Tap-out berikutnya
                $kehadiran->update([
                    'WaktuPulang' => $time
                ]);
            }
        }

        // Tidak ada lagi auto-fill WaktuPulang
        return response()->json(['valid' => true, 'message' => 'Data processed']);
    }
}