<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\JadwalOperator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Models\BackupSession;
use Illuminate\Support\Facades\DB;
use App\Models\Spbu;

class AbsensiPdfController extends Controller
{
    
    
 public function export()
{
    $NoSpbu = Auth::user()->NomorSPBU;

    $absensi = Absensi::with(['karyawan', 'nozle', 'produk'])
        ->whereHas('karyawan', function ($query) use ($NoSpbu) {
            $query->where('NomorSPBU', $NoSpbu);
        })
        ->orderBy('Tanggal', 'desc')
        ->get();

    $pdf = PDF::loadView('exports.absensi_pdf', compact('absensi'));
    return $pdf->download('absensi_karyawan.pdf');
}

    public function download()
    {

         $nomorSpbu = Auth::user()->NomorSPBU;
      
        $jadwals = JadwalOperator::with('karyawan')
            ->where('NomorSPBU', $nomorSpbu)
            ->orderBy('Tanggal', 'desc')
            ->get();
        $pdf = PDF::loadView('exports.jadwal_pdf', compact('jadwals'));
        return $pdf->download('jadwal_operator.pdf');
    }

    public function rekap(){
        
     $nomorSpbu = Auth::user()->NomorSPBU;
     $spbu = Spbu::where('NomorSPBU', $nomorSpbu)->first();

    $jadwals = JadwalOperator::with('karyawan')
        ->where('NomorSPBU', $nomorSpbu)
        ->orderBy('Tanggal', 'desc')
        ->get();

    $absensis = Absensi::with(['karyawan', 'nozle', 'produk'])
        ->whereHas('karyawan', function($q) use ($nomorSpbu) {
            $q->where('NomorSPBU', $nomorSpbu);
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
        $insentif = ($totalizer_utama + $totalizer_backup) * 100;

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
        ];
    }

    $pdf = Pdf::loadView('exports.absensi_detil_pdf', compact('spbu', 'data'));
    return $pdf->download('absensi_'.$nomorSpbu.'.pdf');
    }
}
