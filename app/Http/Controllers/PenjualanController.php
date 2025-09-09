<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Nozle;
use App\Models\Produk;
use App\Models\Pulau;
use App\Models\Penjualan;
use App\Models\SPBU;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $nomorspbu = $user ? $user->NomorSPBU : null;

        // Find SPBU based on the user's NomorSPBU
        $spbu = SPBU::where('NomorSPBU', $nomorspbu)->first();
        $spbuId = $spbu ? $spbu->id : null;
        $namaspbu = $spbu ? $spbu->NamaSPBU : '-';

        // Data for filters, specific to the SPBU
        $nozzles = Nozle::where('SpbuId', $spbuId)->get();
        $produks = Produk::all();
        $pulaus = Pulau::where('SpbuId', $spbuId)->get();

        // Base query for Penjualan
        $query = Penjualan::with(['nozle', 'pulau', 'produk'])
            ->where('SpbuId', $spbuId); // Filter by SpbuId

        // Filter by month
        if ($request->filled('bulan')) {
            $query->whereMonth('created_at', $request->bulan);
        }

        // Filter by year
        if ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        }

        // New and improved search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('TelerAwal', 'like', "%$search%")
                    ->orWhere('TelerAkhir', 'like', "%$search%")
                    ->orWhere('Jumlah', 'like', "%$search%")
                    ->orWhere('JumlahRupiah', 'like', "%$search%")
                    ->orWhereHas('nozle', function ($q) use ($search) {
                        $q->where('NamaNozle', 'like', "%$search%");
                    })
                    ->orWhereHas('pulau', function ($q) use ($search) {
                        $q->where('NamaPulau', 'like', "%$search%");
                    })
                    ->orWhereHas('produk', function ($q) use ($search) {
                        $q->where('NamaProduk', 'like', "%$search%");
                    });
            });
        }

        // Pagination and ordering
        $penjualans = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('penjual_view', compact(
            'penjualans',
            'nozzles',
            'produks',
            'pulaus',
            'nomorspbu',
            'namaspbu',
            'request'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $nomorspbu = $user ? $user->NomorSPBU : null;
        $spbu = SPBU::where('NomorSPBU', $nomorspbu)->first();
        $spbuId = $spbu ? $spbu->id : null;

        $validated = $request->validate([
            'NozzelId'     => 'required|exists:nozle,id',
            'PulauId'      => 'required|exists:pulau,id',
            'ProdukId'     => 'required|exists:produk,id',
            'TelerAwal'    => 'required',
            'TelerAkhir'   => 'required',
            'Jumlah'       => 'required',
            'JumlahRupiah' => 'required',
        ]);

        // Konversi format Indonesia ke numeric
        $convertedData = [
            'SpbuId' => $spbuId, // Tambahkan SpbuId
            'NozzelId' => $validated['NozzelId'],
            'PulauId' => $validated['PulauId'],
            'ProdukId' => $validated['ProdukId'],
            'TelerAwal' => $this->convertToNumeric($validated['TelerAwal']),
            'TelerAkhir' => $this->convertToNumeric($validated['TelerAkhir']),
            'Jumlah' => $this->convertToNumeric($validated['Jumlah']),
            'JumlahRupiah' => $this->convertToNumeric($validated['JumlahRupiah']),
        ];

        Penjualan::create($convertedData);

        return redirect()->back()->with('success', 'Penjualan berhasil ditambahkan');
    }

    // Helper function untuk konversi format Indonesia ke numeric
    private function convertToNumeric($value)
    {
        if (is_numeric($value)) {
            return $value;
        }

        // Hapus semua titik (pemisah ribuan) dan ganti koma dengan titik (pemisah desimal)
        $numericValue = str_replace('.', '', $value);
        $numericValue = str_replace(',', '.', $numericValue);

        return (float) $numericValue;
    }

    public function generateLaporan(Request $request)
    {
        $user = Auth::user();
        $nomorspbu = $user ? $user->NomorSPBU : null;

        // Cari SPBU berdasarkan NomorSPBU user
        $spbu = SPBU::where('NomorSPBU', $nomorspbu)->first();

        if (!$spbu) {
            return redirect()->back()->with('error', 'SPBU tidak ditemukan');
        }

        $spbuId = $spbu->id;

        // Ambil parameter filter
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // Ambil data penjualan dengan relasi
        $penjualans = Penjualan::with(['nozle', 'pulau', 'produk'])
            ->where('SpbuId', $spbuId)
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->get();

        // Kelompokkan data berdasarkan Pulau terlebih dahulu, kemudian Produk
        $pulaus = Pulau::where('SpbuId', $spbuId)->get();
        $dataPerPulau = [];

        foreach ($pulaus as $pulau) {
            $pulauPenjualans = $penjualans->where('PulauId', $pulau->id);

            if ($pulauPenjualans->count() > 0) {
                $dataPerPulau[$pulau->id] = [
                    'pulau' => $pulau,
                    'produk' => [],
                    'total_pulau_liter' => 0,
                    'total_pulau_rupiah' => 0
                ];

                // Kelompokkan berdasarkan produk dalam pulau ini
                $produks = Produk::all();
                foreach ($produks as $produk) {
                    $produkPenjualans = $pulauPenjualans->where('ProdukId', $produk->id);

                    if ($produkPenjualans->count() > 0) {
                        $dataPerPulau[$pulau->id]['produk'][$produk->id] = [
                            'produk' => $produk,
                            'penjualans' => $produkPenjualans,
                            'total_liter' => 0,
                            'total_rupiah' => 0
                        ];

                        foreach ($produkPenjualans as $penjualan) {
                            $dataPerPulau[$pulau->id]['produk'][$produk->id]['total_liter'] += $penjualan->Jumlah;
                            $dataPerPulau[$pulau->id]['produk'][$produk->id]['total_rupiah'] += $penjualan->JumlahRupiah;
                        }

                        // Tambahkan ke total pulau
                        $dataPerPulau[$pulau->id]['total_pulau_liter'] += $dataPerPulau[$pulau->id]['produk'][$produk->id]['total_liter'];
                        $dataPerPulau[$pulau->id]['total_pulau_rupiah'] += $dataPerPulau[$pulau->id]['produk'][$produk->id]['total_rupiah'];
                    }
                }
            }
        }

        // Array nama bulan
        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $data = [
            'spbu' => $spbu,
            'dataPerPulau' => $dataPerPulau,
            'bulan' => $namaBulan[(int)$bulan] ?? 'Bulan tidak valid',
            'tahun' => $tahun,
            'totalPendapatan' => $penjualans->sum('JumlahRupiah'),
            'tanggal' => now()->format('d F Y H:i A'),
            'shift' => 'Shift 1'
        ];

        $pdf = Pdf::loadView('exports.penjualan_pdf', $data);
        return $pdf->download('laporan-shift-' . $spbu->NomorSPBU . '-' . now()->format('Y-m-d') . '.pdf');
    }

    public function update(Request $request, $id)
    {
        // Fungsi helper untuk membersihkan format angka dari string
        $cleanNumber = function ($value) {
            // Hilangkan titik sebagai pemisah ribuan, lalu ganti koma menjadi titik untuk desimal
            return (float) str_replace(',', '.', str_replace('.', '', $value));
        };

        // Konversi data TelerAwal dan TelerAkhir sebelum validasi dan penggunaan
        $telerAwalClean = $cleanNumber($request->input('TelerAwal'));
        $telerAkhirClean = $cleanNumber($request->input('TelerAkhir'));

        $request->merge([
            'TelerAwal' => $telerAwalClean,
            'TelerAkhir' => $telerAkhirClean,
        ]);

        $request->validate([
            'NozzelId' => 'required|exists:nozle,id', // Ganti 'nozzles' menjadi 'nozles'
            'PulauId' => 'required|exists:pulau,id',
            'ProdukId' => 'required|exists:produk,id',
            'TelerAwal' => 'required|numeric',
            'TelerAkhir' => 'required|numeric|gte:TelerAwal',
        ]);

        $produk = Produk::findOrFail($request->ProdukId);
        $jumlah = $request->TelerAkhir - $request->TelerAwal;
        $jumlahRupiah = $jumlah * $produk->HargaPerLiter;

        $penjualan = Penjualan::findOrFail($id);
        $penjualan->update([
            'NozzelId' => $request->NozzelId,
            'PulauId' => $request->PulauId,
            'ProdukId' => $request->ProdukId,
            'TelerAwal' => $request->TelerAwal,
            'TelerAkhir' => $request->TelerAkhir,
            'Jumlah' => $jumlah,
            'JumlahRupiah' => $jumlahRupiah,
        ]);

        return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil diperbarui');
    }
}
