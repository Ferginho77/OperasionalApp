<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FingerprintController extends Controller
{
    // Simulasi storage sementara (offline-friendly)
    protected static $lastSeen = null;
    protected static $tapInToday = [];

    // Fungsi untuk POST dari mesin (cdata)
    public function cdata(Request $request)
    {
        $snFromRequest = $request->input('SN');
        $snFromEnv     = env('CODE_MESIN');

        if ($snFromRequest !== $snFromEnv) {
            Log::warning('SN tidak cocok', ['sn_request' => $snFromRequest]);
            return response()->json(['valid' => false]);
        }

        $lines = preg_split('/\r\n|\r|\n/', $request->getContent());
        $tapInList = [];

        foreach ($lines as $line) {
            if (empty(trim($line))) continue;

            $data = explode("\t", trim($line));
            $employeeId = $data[0] ?? null;
            $timestamp  = $data[1] ?? null;

            if (!$employeeId || !$timestamp) continue;

            $today = date('Y-m-d', strtotime($timestamp));

            if (!isset(self::$tapInToday[$today])) {
                self::$tapInToday[$today] = [];
            }

            if (!in_array($employeeId, self::$tapInToday[$today])) {
                self::$tapInToday[$today][] = $employeeId;
                $tapInList[] = $employeeId;
            }

            // Update last seen mesin
            self::$lastSeen = $timestamp;
        }

        Log::info('Data received from fingerprint', [
            'sn' => $snFromRequest,
            'tap_in_today' => $tapInList,
            'last_seen' => self::$lastSeen
        ]);

        return response()->json([
            'valid' => true,
            'tap_in_today' => $tapInList,
            'last_seen' => self::$lastSeen
        ]);
    }

    // Fungsi GET untuk status mesin
    public function status()
    {
        $status = 'offline';
        if (self::$lastSeen) {
            $lastSeenTime = strtotime(self::$lastSeen);
            $now = time();
            $status = ($now - $lastSeenTime <= 300) ? 'online' : 'offline'; // 5 menit dianggap online
        }

        return response()->json([
            'machine_status' => $status,
            'last_seen' => self::$lastSeen,
            'tap_in_today' => self::$tapInToday[date('Y-m-d')] ?? []
        ]);
    }
}
