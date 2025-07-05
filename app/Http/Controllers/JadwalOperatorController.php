<?php

namespace App\Http\Controllers;

use App\Exports\AbsensiExport;
use App\Exports\JadwalOperatorExport;
use App\Models\JadwalOperator;
use App\Models\Karyawan;
use App\Models\Spbu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class JadwalOperatorController extends Controller
{
    // Halaman utama (jadwal + form create/edit)
    public function index(Request $request)
    {
        $nomorSpbu = Auth::user()->NomorSPBU;
        

        $jadwals = JadwalOperator::with('karyawan')
            ->where('NomorSPBU', $nomorSpbu)
            ->orderBy('Tanggal', 'desc')
            ->get();

        $operators = Karyawan::where('Role', 'Operator')
            ->where('NomorSPBU', $nomorSpbu)
            ->get();

        $editJadwal = null;
        if ($request->has('edit') && $request->filled('edit')) {
            $editJadwal = JadwalOperator::find($request->edit);
        }

        return view('jadwal', compact('jadwals', 'operators', 'editJadwal'));
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'KaryawanId' => 'required|exists:karyawan,id',
            'Tanggal' => 'required|date',
            'Shift' => 'required|in:pagi,sore',
        ]);

        $nomorSpbu = Auth::user()->NomorSPBU;

        // $bentrok = JadwalOperator::where('Tanggal', $request->Tanggal)
        //     ->where('Shift', $request->Shift)
        //     ->where('NomorSPBU', $nomorSpbu)
        //     ->exists();

        // if ($bentrok) {
        //     return back()->withErrors('Shift ini sudah diisi oleh operator lain di SPBU ini.');
        // }

        JadwalOperator::create([
            'KaryawanId' => $request->KaryawanId,
            'Tanggal' => $request->Tanggal,
            'Shift' => $request->Shift,
            'NomorSPBU' => $nomorSpbu,
        ]);

        return redirect()->route('jadwal')->with('success', 'Jadwal berhasil dibuat.');
    }

    // Update data jadwal
    public function update(Request $request, $id)
    {
        $jadwal = JadwalOperator::findOrFail($id);

        $request->validate([
            'KaryawanId' => 'required|exists:karyawan,id',
            'Tanggal' => 'required|date',
            'Shift' => 'required|in:pagi,sore',
        ]);

        $bentrok = JadwalOperator::where('Tanggal', $request->Tanggal)
            ->where('Shift', $request->Shift)
            ->where('NomorSPBU', $jadwal->NomorSPBU)
            ->where('id', '!=', $jadwal->id)
            ->exists();

        if ($bentrok) {
            return back()->withErrors('Shift ini sudah digunakan di Tanggal tersebut.');
        }

        $jadwal->update([
            'KaryawanId' => $request->KaryawanId,
            'Tanggal' => $request->Tanggal,
            'Shift' => $request->Shift,
        ]);

        return redirect()->route('jadwal')->with('success', 'Jadwal berhasil diperbarui.');
    }

    // Hapus jadwal
    public function destroy($id)
    {
        $jadwal = JadwalOperator::findOrFail($id);

        if ($jadwal->NomorSPBU !== Auth::user()->NomorSPBU) {
            abort(403);
        }

        $jadwal->delete();

        return back()->with('success', 'Jadwal berhasil dihapus.');
    }

    // Laporan Mingguan
    public function laporanMingguan()
    {
        $nomorSpbu = Auth::user()->NomorSPBU;
        $TanggalAwal = now()->startOfWeek();
        $TanggalAkhir = now()->endOfWeek();

        $jadwals = JadwalOperator::with('karyawan')
            ->where('NomorSPBU', $nomorSpbu)
            ->whereBetween('Tanggal', [$TanggalAwal, $TanggalAkhir])
            ->orderBy('Tanggal')
            ->orderBy('Shift')
            ->get();

        $operators = Karyawan::where('Role', 'Operator')
            ->where('NomorSPBU', $nomorSpbu)
            ->get();

        return view('jadwal', compact('jadwals', 'TanggalAwal', 'TanggalAkhir', 'operators'));
    }

    // View kalender
    public function kalender()
    {
        $nomorspbu = Auth::user()->NomorSPBU;
        $namaspbu = Spbu::where('NomorSPBU', $nomorspbu)->value('NamaSPBU');
        return view('kalender' , compact('nomorspbu', 'namaspbu'));
    }

    // API kalender untuk FullCalendar
    public function kalenderApi()
    {
        $nomorSpbu = Auth::user()->NomorSPBU;

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

    public function downloadJadwalXls()
{
   $nomorSpbu = Auth::user()->NomorSPBU;
    return Excel::download(new JadwalOperatorExport($nomorSpbu), 'jadwal_operator.xls');
}
}
