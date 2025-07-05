<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\JadwalOperator;
use App\Models\Karyawan;
use App\Models\Nozle;
use App\Models\Pulau;
use App\Models\Spbu;
use Illuminate\Http\Request;
use App\Exports\JadwalOperatorExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class OwnerController extends Controller
{
    /**
     * Dashboard Owner
     */
    public function index()
    {
        $karyawan = Karyawan::count();
        $spbu = Spbu::count();

        // Hitung role karyawan
        $adminCount = Karyawan::where('Role', 'admin')->count();
        $accountingCount = Karyawan::where('Role', 'accounting')->count();
        $pengawasCount = Karyawan::where('Role', 'pengawas')->count();

        // Ambil semua data SPBU untuk card per SPBU
        $spbus = Spbu::all();

        return view('owner', compact(
            'karyawan',
            'spbu',
            'adminCount',
            'accountingCount',
            'pengawasCount',
            'spbus'
        ));
    }

    /**
     * Halaman Absensi Semua Karyawan
     */
    public function showAbsen()
    {
        $karyawan = Karyawan::all();
        $absensi = Absensi::with('karyawan')->get();

        return view('absensiKaryawan', compact('karyawan', 'absensi'));
    }

    /**
     * Halaman Detail Manajemen per SPBU
     */
   public function showSpbu(Request $request, $id)
{
    // Ambil data SPBU
    $spbu = Spbu::findOrFail($id);

      $SpbuId = $spbu->id;

    // Cari karyawan berdasarkan NomorSPBU
    $karyawans = Karyawan::where('NomorSPBU', $spbu->NomorSPBU)->get();

    // Cari absensi karyawan SPBU ini
    $absensis = Absensi::whereHas('karyawan', function($q) use ($spbu) {
        $q->where('NomorSPBU', $spbu->NomorSPBU);
    })->get();

     $nozle = Nozle::with('pulau')
        ->where('SpbuId', $SpbuId)
        ->get();

            $karyawanQuery = Karyawan::where('NomorSPBU', $spbu->NomorSPBU);
    if ($request->filled('search_karyawan')) {
        $karyawanQuery->where('Nama', 'like', '%' . $request->search_karyawan . '%');
    }
    $karyawan = $karyawanQuery->orderBy('Nama')->get();

    // Filter Nozle
    $nozleQuery = Nozle::with('pulau')->where('SpbuId', $SpbuId);
    if ($request->filled('search_nozle')) {
        $nozleQuery->where('NamaNozle', 'like', '%' . $request->search_nozle . '%');
    }
    $nozle = $nozleQuery->get();

      $pulau = Pulau::where('SpbuId', $SpbuId)->get();

      $jadwals = JadwalOperator::with('karyawan')
            ->where('NomorSPBU', $spbu->NomorSPBU)
            ->orderBy('Tanggal', 'desc')
            ->get();

    return view('spbuDetail', compact('spbu', 'karyawans', 'absensis', 'nozle', 'pulau', 'SpbuId', 'jadwals', 'karyawan'));
}

public function kalenderApi($nomorSpbu)
{
    $jadwals = JadwalOperator::with('karyawan')
        ->where('NomorSPBU', $nomorSpbu)
        ->get();

    $events = $jadwals->map(function ($jadwal) {
        // Tentukan warna berdasarkan shift
        $color = '#007bff'; // default biru
        if (strtolower($jadwal->Shift) === 'Sore') {
            $color = '#dc3545'; // merah
        }

        return [
            'title' => $jadwal->karyawan->Nama . ' (' . ucfirst($jadwal->Shift) . ')',
            'start' => $jadwal->Tanggal,
            'allDay' => true,
            'color' => $color,
        ];
    });

    return response()->json($events);
}

public function downloadJadwalXls($nomorSpbu)
{
    return Excel::download(new JadwalOperatorExport($nomorSpbu), 'jadwal_operator_'.$nomorSpbu.'.xls');
}

public function downloadJadwalPdf($nomorSpbu)
{
    $jadwals = JadwalOperator::with('karyawan')
        ->where('NomorSPBU', $nomorSpbu)
        ->orderBy('Tanggal', 'desc')
        ->get();
    $pdf = Pdf::loadView('exports.jadwal_pdf', compact('jadwals'));
    return $pdf->download('jadwal_operator_'.$nomorSpbu.'.pdf');
}
}
