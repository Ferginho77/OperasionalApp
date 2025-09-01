<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Karyawan;
use App\Models\Spbu;
use App\Models\Kehadiran;


class FingerprintController extends Controller
{
    /* ----------------------------------------------------------
     *  1) HANDSHAKE – balas konfigurasi push mesin test
     * ---------------------------------------------------------- */
    public function handshake(Request $request)
    {
        Log::info('[HANDSHAKE]', $request->all());

        DB::table('device_log')->insert([
            'url'    => json_encode($request->all()),
            'data'   => $request->getContent(),
            'sn'     => $request->input('SN'),
            'option' => $request->input('option'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('devices')->updateOrInsert(
            ['no_sn' => $request->input('SN')],
            ['online' => now()]
        );

        return "GET OPTION FROM: {$request->input('SN')}\r\n" .
               "Stamp=9999\r\n" .
               "OpStamp=" . time() . "\r\n" .
               "ErrorDelay=60\r\n" .
               "Delay=30\r\n" .
               "ResLogDay=18250\r\n" .
               "ResLogDelCount=10000\r\n" .
               "ResLogCount=50000\r\n" .
               "TransTimes=00:00;14:05\r\n" .
               "TransInterval=1\r\n" .
               "TransFlag=1111000000\r\n" .
               "Realtime=1\r\n" .
               "Encrypt=0";
    }

    /* ----------------------------------------------------------
     *  2) TERIMA DATA ABSENSI (cdata)
     * ---------------------------------------------------------- */
    public function cdata(Request $request)
    {
        Log::info('[CDATA]', $request->all());
        Log::info('[CDATA RAW]', ['raw' => $request->getContent()]);

        DB::table('finger_log')->insert([
            'url'  => json_encode($request->all()),
            'data' => $request->getContent(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $snFromRequest = $request->input('SN');
        $snFromEnv = env('CODE_MESIN');

        if ($snFromRequest !== $snFromEnv) {
            Log::warning('[CDATA] SN mismatch');
            return response("ERROR: SN mismatch\n", 400);
        }

        $arr = preg_split('/\r\n|\r|\n/', $request->getContent());
        $tot = 0;

        if ($request->input('table') === 'OPERLOG') {
            foreach ($arr as $line) {
                if (trim($line) !== '') $tot++;
            }
            Log::info("[OPERLOG] Total line: $tot");
            return "OK: $tot";
        }

        foreach ($arr as $line) {
            if (empty(trim($line))) continue;

            $data = explode("\t", trim($line));

            $row = [
                'sn'          => $snFromRequest,
                'table'       => $request->input('table'),
                'stamp'       => $request->input('Stamp'),
                'employee_id' => $data[0],
                'timestamp'   => $data[1],
                'status1'     => $this->toInt($data[2] ?? null),
                'status2'     => $this->toInt($data[3] ?? null),
                'status3'     => $this->toInt($data[4] ?? null),
                'status4'     => $this->toInt($data[5] ?? null),
                'status5'     => $this->toInt($data[6] ?? null),
                'created_at'  => now(),
                'updated_at'  => now(),
            ];

            DB::table('attendances')->insert($row);

            try {
                $employeeId = $row['employee_id'];
                $status     = $row['status1'];
                $timestamp  = $row['timestamp'];

                $karyawan = Karyawan::find($employeeId);
                if (!$karyawan) {
                    Log::warning("[SKIP] Karyawan ID $employeeId not found");
                    continue;
                }

                $spbu = Spbu::where('NomorSPBU', $karyawan->NomorSPBU)->first();

                if ($status === 0) {
                    $kehadiran = Kehadiran::create([
                        'KaryawanId'  => $karyawan->id,
                        'WaktuMasuk'  => $timestamp,
                        'WaktuPulang' => null,
                        'SpbuId'      => $spbu?->id,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]);
                    Log::info("[CREATE] Kehadiran ID: {$kehadiran->id} untuk Karyawan ID: {$karyawan->id}");
                } elseif ($status === 1) {
                    $last = Kehadiran::where('KaryawanId', $karyawan->id)
                                     ->whereNull('WaktuPulang')
                                     ->latest('WaktuMasuk')
                                     ->first();

                    if ($last) {
                        $last->update([
                            'WaktuPulang' => $timestamp,
                            'updated_at'  => now(),
                        ]);
                        Log::info("[UPDATE] Kehadiran ID: {$last->id} WaktuPulang diupdate");
                    } else {
                        Log::info("[SKIP] Tidak ada record masuk untuk Karyawan ID: {$karyawan->id}");
                    }
                }
            } catch (\Exception $e) {
                Log::error('[CDATA ERROR] ' . $e->getMessage());
            }

            $tot++;
        }

        Log::info("[CDATA] Total processed: $tot");
        return "OK: $tot";
    }

    /* ----------------------------------------------------------
     *  3) DUMMY getrequest (biar gak error)
     * ---------------------------------------------------------- */
    public function getrequest(Request $request)
    {
        Log::info('[GETREQUEST]', $request->all());
        return "OK";
    }

    /* ----------------------------------------------------------
     *  4) STATUS DEVICE
     * ---------------------------------------------------------- */
    public function status()
    {
        $device = DB::table('devices')->where('no_sn', env('CODE_MESIN'))->first();

        $lastSeen = optional($device)->online;
        $status = $lastSeen && now()->diffInMinutes($lastSeen) <= 5 ? 'online' : 'offline';

        $tapInToday = DB::table('attendances')
            ->whereDate('timestamp', today())
            ->pluck('employee_id')
            ->unique()
            ->values()
            ->toArray();

        return response()->json([
            'machine_status' => $status,
            'last_seen'      => $lastSeen,
            'tap_in_today'   => $tapInToday,
        ]);
    }

    /* ----------------------------------------------------------
     *  5) UTILITAS
     * ---------------------------------------------------------- */
    private function toInt($value)
    {
        return isset($value) && $value !== '' ? (int)$value : null;
    }
}