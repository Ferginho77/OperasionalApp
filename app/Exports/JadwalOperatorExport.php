<?php

namespace App\Exports;

use App\Models\JadwalOperator;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JadwalOperatorExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $nomorSpbu;

    public function __construct($nomorSpbu)
    {
        $this->nomorSpbu = $nomorSpbu;
    }

    public function collection()
    {
        return JadwalOperator::with('karyawan')
            ->where('NomorSPBU', $this->nomorSpbu)
            ->get()
            ->map(function ($jadwal) {
                return [
                    'Nama Operator' => $jadwal->karyawan->Nama ?? '-',
                    'Tanggal' => $jadwal->Tanggal,
                    'Shift' => ucfirst($jadwal->Shift),
                ];
            });
    }

    public function headings(): array
    {
        return ['Nama Operator', 'Tanggal', 'Shift'];
    }
}
