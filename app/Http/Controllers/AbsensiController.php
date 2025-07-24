<?php

namespace App\Http\Controllers;

use App\Exports\AbsensiDetilExport;
use App\Models\BackupSession;
use App\Models\JadwalOperator;
use App\Models\Nozle;
use App\Models\Produk;
use App\Models\Absensi;
use App\Models\Karyawan;
use App\Models\Spbu;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AbsensiController extends Controller
{
    public function index()
{
    $user = Auth::user();
    $nomorSpbu = $user->NomorSPBU;
    $tanggalHariIni = now()->toDateString();

    // Karyawan yang dijadwalkan hari ini
    $jadwalKaryawanIds = JadwalOperator::where('NomorSPBU', $nomorSpbu)
        ->where('Tanggal', $tanggalHariIni)
        ->pluck('KaryawanId');

    $karyawan = Karyawan::with(['totalizerAkhirTerakhir'])
        ->whereIn('id', $jadwalKaryawanIds)
        ->get();

    // Nozle di SPBU ini
    $nozle = Nozle::with('pulau')->whereHas('spbu', function($query) use ($nomorSpbu) {
        $query->where('NomorSPBU', $nomorSpbu);
    })->get();

    // Produk global
    $produk = Produk::all();

    // Absensi hari ini untuk SPBU ini
    $absensi = Absensi::with(['karyawan', 'nozle', 'produk'])
        ->whereHas('karyawan', function($q) use ($nomorSpbu) {
            $q->where('NomorSPBU', $nomorSpbu);
        })
        ->where('Tanggal', $tanggalHariIni)
        ->get();

    // Attach backup session information to absensi entries
    foreach ($absensi as $a) {
        // Check if this absensi is being backed up
        $a->isBeingBackedUp = BackupSession::where('AbsensiId', $a->id)
            ->whereNull('JamSelesai')
            ->exists();

        // Check if this operator (from absensi) is currently doing a backup
        $a->isPerformingBackup = BackupSession::where('BackupOperatorId', $a->KaryawanId)
            ->whereNull('JamSelesai')
            ->exists();

        // Calculate totalizers and insentif (as you already have)
        $literUtama = ($a->TotalizerAkhir && $a->TotalizerAwal) ? $a->TotalizerAkhir + $a->TotalizerAwal : 0;
        $literBackup = BackupSession::where('AbsensiId', $a->id)
            ->whereNotNull('TotalizerAkhir')
            ->sum(DB::raw('TotalizerAkhir - TotalizerAwal'));
        $totalLiter = $literUtama + $literBackup;
        $a->insentif = $totalLiter * 2.5;

        // Add 'totalizer_utama' and 'totalizer_backup' to the absensi object
        // You'll need to fetch these properly from your models.
        // For demonstration, let's assume you have TotalizerAwal and TotalizerAkhir directly on Absensi for 'utama'.
        // For 'backup', you'd need to sum from BackupSession where AbsensiId matches.
        $a->totalizer_utama = ($a->TotalizerAkhir && $a->TotalizerAwal) ? ($a->TotalizerAkhir - $a->TotalizerAwal) : 0; // Corrected calculation for primary totalizer
        $a->totalizer_backup = BackupSession::where('AbsensiId', $a->id)
            ->whereNotNull('TotalizerAkhir')
            ->sum(DB::raw('TotalizerAkhir - TotalizerAwal'));
    }


    // Backup session aktif
    $backupSessions = BackupSession::with(['backupOperator', 'absensi.nozle'])
        ->whereNotNull('JamMulai')
        ->whereNull('JamSelesai')
        ->get();

    // Karyawan dijadwalkan tapi belum absen
    $idYangAbsen = $absensi->pluck('KaryawanId')->toArray();
    $tidakHadir = Karyawan::whereIn('id', $jadwalKaryawanIds)
        ->whereNotIn('id', $idYangAbsen)
        ->get();

    return view('absensi', compact('karyawan', 'nozle', 'produk', 'absensi', 'backupSessions', 'tidakHadir'));
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

   public function rekap()
{
    $user = Auth::user();
    $nomorSpbu = $user->NomorSPBU;

    $jadwal = JadwalOperator::with('karyawan')
        ->where('NomorSPBU', $nomorSpbu)
        ->orderBy('Tanggal', 'desc')
        ->get();

    $absensi = Absensi::with(['karyawan', 'nozle', 'produk'])
        ->whereHas('karyawan', function ($q) use ($nomorSpbu) {
            $q->where('NomorSPBU', $nomorSpbu);
        })
        ->get();

    $rekap = [];
    foreach ($jadwal as $j) {
        $absen = $absensi
            ->where('KaryawanId', $j->KaryawanId)
            ->where('Tanggal', $j->Tanggal)
            ->first();

        $totalizer_utama = ($absen && $absen->TotalizerAkhir && $absen->TotalizerAwal)
            ? ($absen->TotalizerAkhir - $absen->TotalizerAwal)
            : 0;

        $totalizer_backup = 0;
        if ($absen) {
            $totalizer_backup = BackupSession::where('AbsensiId', $absen->id)
                ->whereNotNull('TotalizerAkhir')
                ->sum(DB::raw('TotalizerAkhir - TotalizerAwal'));
        }

        $insentif = ($totalizer_utama + $totalizer_backup) * 2.5;

        // Hitung jam kerja
       $jamKerjaFormatted = '-'; // Default value jika tidak ada absensi atau data tidak lengkap
            if ($absen && $absen->JamMasuk && $absen->JamPulang) {
                try {
                    $jamMasuk = Carbon::parse($absen->JamMasuk);
                    $jamPulang = Carbon::parse($absen->JamPulang);

                    // Hitung total durasi kerja dalam menit (dari masuk sampai pulang)
                    // Menggunakan 'true' sebagai parameter kedua untuk mendapatkan nilai absolut (positif)
                    $totalDurasiKerjaMenit = $jamPulang->diffInMinutes($jamMasuk, true);

                    $totalDurasiIstirahatMenit = 0;
                    // Pastikan kedua waktu istirahat ada sebelum menghitung durasi istirahat
                    if ($absen->JamIstirahatMulai && $absen->JamIstirahatKembali) {
                        $istirahatMulai = Carbon::parse($absen->JamIstirahatMulai);
                        $istirahatKembali = Carbon::parse($absen->JamIstirahatKembali);
                        // Menggunakan 'true' untuk mendapatkan nilai absolut (positif)
                        $totalDurasiIstirahatMenit = $istirahatKembali->diffInMinutes($istirahatMulai, true);
                    }

                    // Hitung jam kerja bersih dalam menit
                    // Menggunakan max(0, ...) untuk memastikan hasilnya tidak negatif
                    $jamKerjaBersihMenit = max(0, $totalDurasiKerjaMenit - $totalDurasiIstirahatMenit);

                    // Konversi total menit bersih ke format jam dan menit
                    $jam = floor($jamKerjaBersihMenit / 60);
                    $menit = $jamKerjaBersihMenit % 60;

                    $jamKerjaFormatted = $jam . ' jam ' . $menit . ' menit';

                } catch (\Exception $e) {
                    // Log error jika parsing tanggal gagal
                    Log::error('Error parsing datetime for absensi ID: ' . ($absen->id ?? 'N/A') . ' - ' . $e->getMessage());
                    $jamKerjaFormatted = 'Error Hitung Jam Kerja';
                }
            }

            $rekap[] = [
                'nama'             => $j->karyawan->Nama ?? '-',
                'tanggal'          => $j->Tanggal,
                'shift'            => ucfirst($j->Shift),
                'status'           => $absen ? 'Hadir' : 'Tidak Hadir',
                'jam_masuk'        => $absen && $absen->JamMasuk ? date('H:i:s', strtotime($absen->JamMasuk)) : '-',
                'jam_istirahat'    => $absen && $absen->JamIstirahatMulai && $absen->JamIstirahatKembali ?
                                      date('H:i:s', strtotime($absen->JamIstirahatMulai)) . ' - ' . date('H:i:s', strtotime($absen->JamIstirahatKembali)) : '-', // Menampilkan rentang istirahat
                'jam_pulang'       => $absen && $absen->JamPulang ? date('H:i:s', strtotime($absen->JamPulang)) : '-',
                'nozle'            => $absen && $absen->nozle ? $absen->nozle->NamaNozle : '-',
                'produk'           => $absen && $absen->produk ? $absen->produk->NamaProduk : '-',
                'totalizer_utama'  => $totalizer_utama,
                'totalizer_backup' => $totalizer_backup,
                'insentif'         => $insentif,
                'jam_kerja'        => $jamKerjaFormatted,
            ];
        }


    return view('rekapabsensi', compact('rekap'));
}


    public function ExportRekap()
{
    $nomorSpbu = Auth::user()->NomorSPBU;
    return Excel::download(new AbsensiDetilExport($nomorSpbu), 'absensi_'.$nomorSpbu.'.xlsx');
}


}

