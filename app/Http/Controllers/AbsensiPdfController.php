<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Barryvdh\DomPDF\Facade\Pdf;

class AbsensiPdfController extends Controller
{
    public function export()
    {
        $absensi = Absensi::with('karyawan')->get();
        $pdf = PDF::loadView('exports.absensi_pdf', compact('absensi'));
        return $pdf->download('absensi_karyawan.pdf');
    }
}
