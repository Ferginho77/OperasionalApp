<?php

namespace App\Exports;

use App\Models\Kehadiran;
use App\Models\Spbu;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KehadiranExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $user = Auth::user();
        $spbu = Spbu::where('NomorSPBU', $user->NomorSPBU)->first();
        $SpbuId = $spbu->id;
        

        // Ambil data dan petakan hanya kolom yang diinginkan
        return Kehadiran::with('karyawan')
            ->where('SpbuId', $SpbuId)
            ->orderBy('WaktuMasuk', 'desc')
            ->get()
            ->map(function ($kehadiran) {
                return [
                    'Nama Karyawan' => $kehadiran->karyawan->Nama ?? 'Tidak Diketahui',
                    'Waktu Masuk' => $kehadiran->WaktuMasuk,
                    'Waktu Pulang' => $kehadiran->WaktuPulang ?? '-',
                ];
            });
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nama Karyawan',
            'Waktu Masuk',
            'Waktu Pulang',
        ];
    }
}