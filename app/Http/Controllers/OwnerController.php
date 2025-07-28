<?php

namespace App\Http\Controllers;

use App\Exports\AbsensiDetilExport;
use App\Exports\KehadiranDetilExport;
use App\Models\Absensi;
use App\Models\BackupSession;
use App\Models\JadwalOperator;
use App\Models\Karyawan;
use App\Models\Kehadiran;
use App\Models\Nozle;
use App\Models\Pulau;
use App\Models\Spbu;
use Illuminate\Http\Request;
use App\Exports\JadwalOperatorExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

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

    public function showAbsen(){
        $karyawan = Karyawan::all();
        $absensi = Absensi::with('karyawan')->get();
         $spbus = Spbu::all();

        return view('absensiKaryawan', compact('karyawan', 'absensi', 'spbus'));
    }

    public function ShowKehadiran(){
         $spbus = Spbu::all();

        return view('kehadiranKaryawan', compact('spbus'));
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

public function absensiDetil($id)
{
    $spbu = Spbu::findOrFail($id);

    $jadwals = JadwalOperator::with('karyawan')
        ->where('NomorSPBU', $spbu->NomorSPBU)
        ->orderBy('Tanggal', 'desc')
        ->get();

    $absensis = Absensi::with(['karyawan', 'nozle', 'produk'])
        ->whereHas('karyawan', function($q) use ($spbu) {
            $q->where('NomorSPBU', $spbu->NomorSPBU);
        })
        ->orderBy('Tanggal', 'desc')
        ->get();

        $rekap_operator = $absensis->groupBy('KaryawanId')->map(function ($group) {
        return [
            'nama' => $group->first()->karyawan->Nama ?? '-',
            'role' => $group->first()->karyawan->Role ?? '-',
            'total_hadir' => $group->pluck('Tanggal')->unique()->count()
        ];
    });

    $data = [];
    foreach ($jadwals as $jadwal) {
        $absen = $absensis
            ->where('KaryawanId', $jadwal->KaryawanId)
            ->where('Tanggal', $jadwal->Tanggal)
            ->first();

        $hadir = $absen ? true : false;

        $totalizer_utama = ($hadir && $absen->TotalizerAkhir && $absen->TotalizerAwal)
            ? ($absen->TotalizerAkhir - $absen->TotalizerAwal)
            : 0;

        $totalizer_backup = $hadir
            ? BackupSession::where('AbsensiId', $absen->id)
                ->whereNotNull('TotalizerAkhir')
                ->sum(DB::raw('TotalizerAkhir - TotalizerAwal'))
            : 0;

        $data[] = [
            'nama'             => $jadwal->karyawan->Nama ?? '-',
            'role'             => $jadwal->karyawan->Role ?? '-',
            'tanggal'          => $jadwal->Tanggal,
            'status'           => $hadir ? 'Hadir' : 'Tidak Hadir',
            'jam_masuk'        => $hadir && $absen->JamMasuk ? date('H:i', strtotime($absen->JamMasuk)) : '-',
            'jam_istirahat'    => $hadir && $absen->JamIstirahatMulai ? date('H:i', strtotime($absen->JamIstirahatMulai)) : '-',
            'jam_kembali'      => $hadir && $absen->JamIstirahatKembali ? date('H:i', strtotime($absen->JamIstirahatKembali)) : '-',
            'jam_pulang'       => $hadir && $absen->JamPulang ? date('H:i', strtotime($absen->JamPulang)) : '-',
            'nozle'            => $hadir ? ($absen->nozle->NamaNozle ?? '-') : '-',
            'produk'           => $hadir ? ($absen->produk->NamaProduk ?? '-') : '-',
            'totalizer_utama'  => $totalizer_utama,
            'totalizer_backup' => $totalizer_backup,
            'insentif'         => ($totalizer_utama + $totalizer_backup) * 2.5,
        ];
    }

    return view('absensiDetil', compact('spbu', 'data', 'rekap_operator'));
}

 public function kehadiranDetil($id)
{
    $spbu = Spbu::findOrFail($id);

    $kehadirans = Kehadiran::with('karyawan')
        ->where('SpbuId', $spbu->id)
        ->orderBy('WaktuMasuk', 'desc')
        ->get();

    return view('kehadiranDetil', compact('spbu', 'kehadirans'));
}


public function exportAbsensiDetilExcel($id)
{
    $spbu = Spbu::findOrFail($id);
    return Excel::download(new AbsensiDetilExport($spbu->NomorSPBU), 'penugasan_'.$spbu->NomorSPBU.'.xlsx');
}

public function exportAbsensiDetilPdf($id)
{
    $spbu = Spbu::findOrFail($id);

    // Copy logic dari absensiDetil untuk $data
    $jadwals = JadwalOperator::with('karyawan')
        ->where('NomorSPBU', $spbu->NomorSPBU)
        ->orderBy('Tanggal', 'desc')
        ->get();

    $absensis = Absensi::with(['karyawan', 'nozle', 'produk'])
        ->whereHas('karyawan', function($q) use ($spbu) {
            $q->where('NomorSPBU', $spbu->NomorSPBU);
        })
        ->orderBy('Tanggal', 'desc')
        ->get();

    $data = [];
    foreach ($jadwals as $jadwal) {
        $absen = $absensis
            ->where('KaryawanId', $jadwal->KaryawanId)
            ->where('Tanggal', $jadwal->Tanggal)
            ->first();

        $hadir = $absen ? true : false;
        $totalizer_utama = ($hadir && $absen->TotalizerAkhir && $absen->TotalizerAwal)
            ? ($absen->TotalizerAkhir - $absen->TotalizerAwal)
            : 0;
        $totalizer_backup = 0;
        if ($hadir) {
            $totalizer_backup = BackupSession::where('AbsensiId', $absen->id)
                ->whereNotNull('TotalizerAkhir')
                ->sum(DB::raw('TotalizerAkhir - TotalizerAwal'));
        }
        $insentif = ($totalizer_utama + $totalizer_backup) * 2.5;
         $total_hadir = $absensis->groupBy('KaryawanId')->map->count();


        $data[] = [
            'nama'             => $jadwal->karyawan->Nama ?? '-',
            'role'             => $jadwal->karyawan->Role ?? '-',
            'tanggal'          => $jadwal->Tanggal,
            'status'           => $hadir ? 'Hadir' : 'Tidak Hadir',
            'jam_masuk'        => $hadir && $absen->JamMasuk ? date('H:i', strtotime($absen->JamMasuk)) : '-',
            'jam_istirahat'    => $hadir && $absen->JamIstirahatMulai ? date('H:i', strtotime($absen->JamIstirahatMulai)) : '-',
            'jam_kembali'      => $hadir && $absen->JamIstirahatKembali ? date('H:i', strtotime($absen->JamIstirahatKembali)) : '-',
            'jam_pulang'       => $hadir && $absen->JamPulang ? date('H:i', strtotime($absen->JamPulang)) : '-',
            'nozle'            => $hadir && $absen->nozle ? $absen->nozle->NamaNozle : '-',
            'produk'           => $hadir && $absen->produk ? $absen->produk->NamaProduk : '-',
            'totalizer_utama'  => $totalizer_utama,
            'totalizer_backup' => $totalizer_backup,
            'insentif'         => $insentif,
             'total_hadir'      => $total_hadir->get($jadwal->KaryawanId, 0),
        ];
    }

    $pdf = Pdf::loadView('exports.absensi_detil_pdf', compact('spbu', 'data'));
    return $pdf->download('penugasan'.$spbu->NomorSPBU.'.pdf');
}

public function exportKehadiranDetilPdf($id)
{
    $spbu = Spbu::findOrFail($id);

    $kehadirans = Kehadiran::with('karyawan')
        ->where('SpbuId', $spbu->id)
        ->orderBy('WaktuMasuk', 'desc')
        ->get();

    $pdf = Pdf::loadView('exports.kehadiran_detil_pdf', compact('spbu', 'kehadirans'));

    return $pdf->download('kehadiran_' . $spbu->NomorSPBU . '.pdf');
}

public function exportKehadiranDetilExcel($id)
{
    $spbu = Spbu::findOrFail($id);
    return Excel::download(new KehadiranDetilExport($spbu), 'kehadiran_' . $spbu->NomorSPBU . '.xlsx');
}
}
