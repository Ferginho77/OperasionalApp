<?php

namespace App\Http\Controllers;

use App\Models\Nozle;
use App\Models\Produk;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AbsensiController extends Controller
{

 public function index()
{
      $user = Auth::user();
    $nomorSpbu = $user->NomorSPBU;

    // Ambil data karyawan hanya yang memiliki NomorSPBU sesuai user
    $karyawan = Karyawan::with(['totalizerAkhirTerakhir'])
        ->whereHas('spbu', function($query) use ($nomorSpbu) {
            $query->where('NomorSPBU', $nomorSpbu);
        })->get();

    // Ambil semua nozle di SPBU ini
    $nozle = Nozle::with('pulau')->whereHas('spbu', function($query) use ($nomorSpbu) {
        $query->where('NomorSPBU', $nomorSpbu);
    })->get();

    // Produk bisa global
    $produk = Produk::all();

    // Ambil absensi hari ini untuk SPBU ini
    $absensi = Absensi::with(['karyawan', 'nozle', 'produk'])
        ->whereDate('Tanggal', now())
        ->whereHas('karyawan', function($q) use ($nomorSpbu) {
            $q->where('NomorSPBU', $nomorSpbu);
        })
        ->get();

    // Tidak perlu $totalizer global lagi

    return view('absensi', compact('karyawan', 'nozle', 'produk', 'absensi'));
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

