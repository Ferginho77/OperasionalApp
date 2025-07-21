<?php

namespace App\Exports;

use App\Models\JadwalOperator;
use App\Models\Absensi;
use App\Models\BackupSession;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AbsensiDetilExport implements FromCollection, WithHeadings
{
    protected $nomorSpbu;

    public function __construct($nomorSpbu)
    {
        $this->nomorSpbu = $nomorSpbu;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $jadwals = JadwalOperator::with('karyawan')
            ->where('NomorSPBU', $this->nomorSpbu)
            ->orderBy('Tanggal', 'desc')
            ->get();

        $absensis = Absensi::with(['karyawan', 'nozle', 'produk'])
            ->whereHas('karyawan', function($q) {
                $q->where('NomorSPBU', $this->nomorSpbu);
            })
            ->get();

        $rows = [];
        $no = 1;
        foreach ($jadwals as $jadwal) {
            $absen = $absensis
                ->where('KaryawanId', $jadwal->KaryawanId)
                ->where('Tanggal', $jadwal->Tanggal)
                ->first();

            $hadir = $absen ? true : false;
            $totalizer_utama = ($hadir && $absen->TotalizerAkhir && $absen->TotalizerAwal)
                ? ($absen->TotalizerAkhir - $absen->TotalizerAwal)
                : 0;
            $totalizer_backup = 0;
            if ($hadir) {
                $totalizer_backup = BackupSession::where('AbsensiId', $absen->id)
                    ->whereNotNull('TotalizerAkhir')
                    ->sum(DB::raw('TotalizerAkhir - TotalizerAwal'));
            }
            $insentif = ($totalizer_utama + $totalizer_backup) * 2.5;

            $rows[] = [
                'No' => $no++,
                'Nama Karyawan' => $jadwal->karyawan->Nama ?? '-',
                'Role' => $jadwal->karyawan->Role ?? '-',
                'Tanggal' => $jadwal->Tanggal,
                'Status' => $hadir ? 'Hadir' : 'Tidak Hadir',
                'Jam Masuk' => $hadir && $absen->JamMasuk ? date('H:i', strtotime($absen->JamMasuk)) : '-',
                'Jam Istirahat' => $hadir && $absen->JamIstirahatMulai ? date('H:i', strtotime($absen->JamIstirahatMulai)) : '-',
                'Jam Kembali' => $hadir && $absen->JamIstirahatKembali ? date('H:i', strtotime($absen->JamIstirahatKembali)) : '-',
                'Jam Pulang' => $hadir && $absen->JamPulang ? date('H:i', strtotime($absen->JamPulang)) : '-',
                'Nozzle' => $hadir && $absen->nozle ? $absen->nozle->NamaNozle : '-',
                'Produk' => $hadir && $absen->produk ? $absen->produk->NamaProduk : '-',
                'Totalizer Utama' => $totalizer_utama,
                'Totalizer Backup' => $totalizer_backup,
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
            'Role',
            'Tanggal',
            'Status',
            'Jam Masuk',
            'Jam Istirahat',
            'Jam Kembali',
            'Jam Pulang',
            'Nozzle',
            'Produk',
            'Totalizer Utama',
            'Totalizer Backup',
            'Insentif (Rp)',
        ];
    }
}
