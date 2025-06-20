<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AbsensiExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Absensi::with('karyawan')->get()->map(function ($item) {
            return [
                'Nama Karyawan' => $item->karyawan ? $item->karyawan->Nama : 'Karyawan Tidak Ditemukan',
                'Tanggal' => $item->Tanggal,
                'Jam Hadir' => $item->JamMasuk ?? 'Belum Absen',
                'Jam Istirahat' => $item->JamIstirahat ?? 'Belum Absen',
                'Jam Kembali Istirahat' => $item->JamKembali ?? 'Belum Absen',
                'Jam Pulang' => $item->JamKeluar ?? 'Belum Absen',
                'Status' => $this->getStatus($item)
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Karyawan',
            'Tanggal',
            'Jam Hadir',
            'Jam Istirahat',
            'Jam Kembali Istirahat',
            'Jam Pulang',
            'Status',
        ];
    }

    private function getStatus($x)
    {
        if ($x->JamMasuk && !$x->JamIstirahat) {
            return 'Hadir';
        } elseif ($x->JamIstirahat && !$x->JamKembali) {
            return 'Sedang Istirahat';
        } elseif ($x->JamKembali && !$x->JamKeluar) {
            return 'Sudah Beres Istirahat';
        } elseif ($x->JamKeluar) {
            return 'Sudah Pulang';
        } else {
            return 'Belum Absen';
        }
    }
}

