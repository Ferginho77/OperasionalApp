<?php

namespace App\Http\Controllers;

use App\Exports\AbsensiExport;
use App\Exports\JadwalOperatorExport;
use App\Imports\JadwalImport;
use App\Models\JadwalOperator;
use App\Models\Karyawan;
use App\Models\Spbu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

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
            'Shift' => 'required|in:pagi,sore,malam',
        ]);

        $nomorSpbu = Auth::user()->NomorSPBU;

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
        // Temukan jadwal berdasarkan ID atau gagal
        $jadwal = JadwalOperator::findOrFail($id);

        // Validasi input dari form
        $request->validate([
            'KaryawanId' => 'required|exists:karyawan,id',
            'Tanggal' => 'required|date',
            'Shift' => 'required|in:pagi,sore,malam', // Perbaikan sintaks array 'in:'
            // 'NomorSPBU' => 'required|string|max:50', // Tambahkan jika NomorSPBU diupdate dari form
        ]);

        // Update data jadwal
        $jadwal->update([
            'KaryawanId' => $request->KaryawanId,
            'Tanggal' => $request->Tanggal,
            'Shift' => $request->Shift,
            // Jika NomorSPBU bisa diubah dari form, tambahkan baris di bawah:
            // 'NomorSPBU' => $request->NomorSPBU,
            // Jika NomorSPBU tidak diubah, tidak perlu ditambahkan di sini,
            // nilai lama akan tetap dipertahankan.
        ]);

        // Redirect kembali ke halaman daftar jadwal dengan pesan sukses
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

     public function storeExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Excel::import(new JadwalImport, $request->file('file'));
            return redirect()->back()->with('success', 'Jadwal berhasil diunggah!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengunggah jadwal: ' . $e->getMessage());
        }
    }
}
