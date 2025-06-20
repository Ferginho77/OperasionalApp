<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AbsensiController extends Controller
{

  public function index()
{
    $absensi = Absensi::with(['karyawan', 'nozle', 'produk'])
        ->orderByDesc('created_at')
        ->get()
        ->map(function ($a) {
            $a->TotalLiter = $a->TotalizerAkhir && $a->TotalizerAwal
                ? $a->TotalizerAkhir - $a->TotalizerAwal
                : 0;

            $a->Insentif = round($a->TotalLiter * 100); // hitungan insentif

            return $a;
        });

    $karyawan = Karyawan::all();
    $nozle = DB::table('nozle')->get();
    $produk = DB::table('produk')->get();
    $absenTerakhir = Absensi::latest()->first();

    return view('absensi', compact('absensi', 'karyawan', 'nozle', 'produk', 'absenTerakhir'));
}




    public function store(Request $request)
    {
        $request->validate([
            'KaryawanId' => 'required|exists:karyawan,id',
            'NozleId' => 'required|exists:nozle,id',
            'ProdukId' => 'required|exists:produk,id',
            'Pulau' => 'required|string',
            'TotalizerAwal' => 'required|numeric',
        ]);

        Absensi::create([
            'KaryawanId' => $request->KaryawanId,
            'Tanggal' => date('Y-m-d'),
            'JamMasuk' => now(),
            'NozleId' => $request->NozleId,
            'ProdukId' => $request->ProdukId,
            'Pulau' => $request->Pulau,
            'TotalizerAwal' => $request->TotalizerAwal
        ]);

        return back()->with('success', 'Absensi masuk berhasil.');
    }

    public function istirahat(Request $request, $id)
    {
        $request->validate([
            'TotalizerAkhir' => 'required|numeric'
        ]);

        $absen = Absensi::findOrFail($id);
        $absen->update([
            'JamIstirahatMulai' => now(),
            'TotalizerAkhir' => $request->TotalizerAkhir
        ]);

        return back()->with('success', 'Istirahat dicatat.');
    }

    public function pindahNozle(Request $request, $id)
    {
        $request->validate([
            'NozleId' => 'required|exists:nozle,id',
            'TotalizerAwal' => 'required|numeric'
        ]);

        $absen = Absensi::findOrFail($id);
        $absen->update([
            'JamPindahNozle' => now(),
            'NozleId' => $request->NozleId,
            'TotalizerAwal' => $request->TotalizerAwal
        ]);

        return back()->with('success', 'Pindah nozzle berhasil.');
    }

    public function kembaliNozle(Request $request, $id)
    {
        $request->validate([
            'TotalizerAkhir' => 'required|numeric'
        ]);

        $absen = Absensi::findOrFail($id);
        $absen->update([
            'JamKembaliNozle' => now(),
            'TotalizerAkhir' => $request->TotalizerAkhir
        ]);

        return back()->with('success', 'Kembali ke nozzle awal dicatat.');
    }

    public function pulang(Request $request, $id)
    {
        $request->validate([
            'TotalizerAkhir' => 'required|numeric'
        ]);

        $absen = Absensi::findOrFail($id);
        $absen->update([
            'JamPulang' => now(),
            'TotalizerAkhir' => $request->TotalizerAkhir
        ]);

        return back()->with('success', 'Absensi pulang dicatat.');
    }
}

