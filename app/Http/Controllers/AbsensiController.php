<?php

namespace App\Http\Controllers;

use App\Models\BackupSession;
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

    $backupSessions = BackupSession::with(['backupOperator', 'absensi.nozle'])
    ->whereNotNull('JamMulai')
    ->whereNull('JamSelesai')->get();

    return view('absensi', compact('karyawan', 'nozle', 'produk', 'absensi', 'backupSessions'));
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
            'Tanggal' => now()->toDateString(),
            'JamMasuk' => now(),
            'NozleId' => $request->NozleId,
            'ProdukId' => $request->ProdukId,
            'Pulau' => $request->Pulau,
            'TotalizerAwal' => $request->TotalizerAwal
        ]);

        return back()->with('success', 'Absensi masuk berhasil.');
    }


     public function istirahat(Request $request)
    {
        $request->validate([
            'TotalizerAkhir' => 'required|numeric'
        ]);

        $absen = Absensi::findOrFail($request->id);
        $absen->update([
            'JamIstirahatMulai' => now(),
            'TotalizerAkhir' => $request->TotalizerAkhir
        ]);
        
        return back()->with('success', 'Istirahat dimulai.');
    }


     public function mulaiBackup(Request $request)
    {
        $request->validate([
            'AbsensiId' => 'required|exists:absensi,id',
            'BackupOperatorId' => 'required|exists:karyawan,id',
            'TotalizerAwal' => 'required|numeric'
        ]);

        BackupSession::create([
            'AbsensiId' => $request->AbsensiId,
            'BackupOperatorId' => $request->BackupOperatorId,
            'JamMulai' => now(),
            'TotalizerAwal' => $request->TotalizerAwal
        ]);

        return back()->with('success', 'Backup nozzle dimulai.');
    }

    public function selesaiBackup(Request $request)
    {
        $request->validate([
            'TotalizerAkhir' => 'required|numeric'
        ]);

        $backup = BackupSession::findOrFail($request->id);
        $backup->update([
            'JamSelesai' => now(),
            'TotalizerAkhir' => $request->TotalizerAkhir
        ]);

        return back()->with('success', 'Backup nozzle selesai.');
    }

    public function kembaliNozle(Request $request)
    {
        $request->validate([
            'TotalizerAwal' => 'required|numeric'
        ]);

        $absen = Absensi::findOrFail($request->id);
        $absen->update([
            'JamIstirahatKembali' => now(),
            'TotalizerAwal' => $request->TotalizerAwal
        ]);

        return back()->with('success', 'Kembali ke nozzle awal.');
    }

    public function pulang(Request $request)
    {
        $request->validate([
            'TotalizerAkhir' => 'required|numeric'
        ]);

        $absen = Absensi::findOrFail($request->id);
        $absen->update([
            'JamPulang' => now(),
            'TotalizerAkhir' => $request->TotalizerAkhir
        ]);

        return back()->with('success', 'Absensi pulang dicatat.');
    }
}

