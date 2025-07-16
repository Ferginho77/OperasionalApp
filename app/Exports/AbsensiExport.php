<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Models\BackupSession;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AbsensiExport implements FromCollection, WithHeadings
{
    public function collection()
{
    $absensi = Absensi::with(['karyawan', 'nozle', 'produk'])->get();
    $rows = [];
    $no = 1;

    foreach ($absensi as $item) {
        $totalizer_utama = ($item->TotalizerAkhir && $item->TotalizerAwal)
            ? $item->TotalizerAkhir - $item->TotalizerAwal
            : 0;

        $totalizer_backup = BackupSession::where('AbsensiId', $item->id)
            ->whereNotNull('TotalizerAkhir')
            ->sum(DB::raw('TotalizerAkhir - TotalizerAwal'));

        $total_liter = $totalizer_utama + $totalizer_backup;
        $insentif = $total_liter * 100;

        $rows[] = [
            'No' => $no++,
            'Nama Karyawan' => $item->karyawan ? $item->karyawan->Nama : 'Karyawan Tidak Ditemukan',
            'Tanggal' => $item->Tanggal,
            'Jam Masuk' => $item->JamMasuk ? date('H:i', strtotime($item->JamMasuk)) : '-',
            'Jam Istirahat' => $item->JamIstirahatMulai ? date('H:i', strtotime($item->JamIstirahatMulai)) : '-',
            'Jam Kembali' => $item->JamIstirahatSelesai ? date('H:i', strtotime($item->JamIstirahatSelesai)) : '-',
            'Jam Pulang' => $item->JamPulang ? date('H:i', strtotime($item->JamPulang)) : '-',
            'Nozzle' => $item->nozle->NamaNozle ?? '-',
            'Produk' => $item->produk->NamaProduk ?? '-',
            'Totalizer Utama' => $totalizer_utama,
            'Totalizer Backup' => $totalizer_backup,
            'Total Liter' => $total_liter,
            'Insentif' => $insentif,
        ];
    }

    return collect($rows);
}

public function headings(): array
{
    return [
        'No',
        'Nama Karyawan',
        'Tanggal',
        'Jam Masuk',
        'Jam Istirahat',
        'Jam Kembali',
        'Jam Pulang',
        'Nozzle',
        'Produk',
        'Totalizer Utama',
        'Totalizer Backup',
        'Total Liter',
        'Insentif (Rp)',
    ];
}

}

