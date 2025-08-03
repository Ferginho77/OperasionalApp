<?php

namespace App\Console\Commands;

use App\Models\Kehadiran;
use App\Models\MesinAbsen;
use Illuminate\Console\Command;
use App\Services\ZkService;
use App\Models\Absensi;

use Carbon\Carbon;

class GetAbsensiData extends Command
{
    protected $signature = 'absensi:get';
    protected $description = 'Ambil data absensi dari semua mesin ZKTeco dan simpan ke database.';

    public function handle()
    {
        // Ambil semua mesin dari database
        $mesinAbsensi = MesinAbsen::all();

        if ($mesinAbsensi->isEmpty()) {
            $this->error('Tidak ada mesin absensi yang terdaftar di database.');
            return;
        }

        foreach ($mesinAbsensi as $mesin) {
            $ipAddress = $mesin->ip_address;
            $port = $mesin->port;
            $idMesin = $mesin->id_mesin;

            $this->info("Memproses mesin {$idMesin} di {$ipAddress}:{$port}...");

            $zk = new ZkService($ipAddress, $port);

            if ($zk->connect()) {
                $this->info("  -> Berhasil terhubung.");
                $attendanceData = $zk->getAttendance();

                if ($attendanceData) {
                    $this->info("  -> Menemukan " . count($attendanceData) . " data absensi.");
                    foreach ($attendanceData as $data) {
                      $karyawanId = $data['id'];  // <-- Ganti dengan $data['uid']
                     $timestamp = Carbon::parse($data['timestamp']);

                        // Logika penyimpanan data ke tabel 'absensi'
                        $today_record = Kehadiran::where('KaryawanId', $karyawanId)
                                               ->where('SpbuId', $idMesin)
                                               ->whereDate('WaktuMasuk', $timestamp->toDateString())
                                               ->first();

                        if ($today_record) {
                            if (is_null($today_record->WaktuPulang) || $timestamp->gt($today_record->WaktuPulang)) {
                                $today_record->WaktuPulang = $timestamp;
                                $today_record->save();
                            }
                        } else {
                            Kehadiran::create([
                                'KaryawanId' => $karyawanId,
                                'SpbuId' => $idMesin,
                                'WaktuMasuk' => $timestamp,
                            ]);
                        }
                    }
                } else {
                    $this->warn("  -> Tidak ada data absensi baru.");
                }
                $zk->disconnect();
                $this->info("  -> Koneksi diputus.");
            } else {
                $this->error("  -> Gagal terhubung.");
            }
        }
    }
}