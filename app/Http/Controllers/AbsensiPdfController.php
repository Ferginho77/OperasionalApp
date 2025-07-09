<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\JadwalOperator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

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
}
