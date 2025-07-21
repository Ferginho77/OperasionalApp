<?php

namespace App\Exports;

use App\Models\Kehadiran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class KehadiranDetilExport implements FromView
{
    protected $spbu;

    public function __construct($spbu)
    {
        $this->spbu = $spbu;
    }

    public function view(): View
    {
        $kehadirans = Kehadiran::with('karyawan')
            ->where('SpbuId', $this->spbu->id)
            ->orderBy('WaktuMasuk', 'desc')
            ->get();

        return view('exports.kehadiran_detil_excel', [
            'spbu' => $this->spbu,
            'kehadirans' => $kehadirans
        ]);
    }
}

