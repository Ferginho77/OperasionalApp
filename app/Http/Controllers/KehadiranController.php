<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Kehadiran;
use Illuminate\Http\Request;

class KehadiranController extends Controller
{
    public function store(Request $request)
    {
        Kehadiran::create([
            'KaryawanId' => $request->KaryawanId,
            'WaktuMasuk' => $request->WaktuMasuk,
            'WaktuPulang' => $request->WaktuPulang,
            'SpbuId' => $request->SpbuId,
        ]);

        return response()->json(['message' => 'Data absensi berhasil disimpan'], 200);
    }

     public function index()
    {
        $kehadiran = Kehadiran::orderBy('WaktuMasuk', 'desc')->get();
        return view('kehadiran', compact('kehadiran'));
    }
}
