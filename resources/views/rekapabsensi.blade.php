@extends('layouts.header')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
             <h3>Rekap Absensi Operator SPBU</h3>
    <div class="mb-3">
        <a href="{{ url('/download-absensi') }}" class="btn btn-success">Download Excel <i class="fa-solid fa-download"></i></a>
        <a href="{{ url('/download-absensi-pdf') }}" class="btn btn-primary">Download PDF <i class="fa-solid fa-download"></i></a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Operator</th>
                    <th>Tanggal</th>
                    <th>Shift</th>
                    <th>Status</th>
                    <th>Jam Masuk</th>
                    <th>Jam Istirahat</th>
                    <th>Jam Pulang</th>
                    <th>Nozle</th>
                    <th>Produk</th>
                    <th>Totalizer</th>
                    <th>Insentif</th>
                </tr>
            </thead>
            <tbody>
                            @forelse($rekap as $i => $r)
                    @php
                        // Ambil data absensi sesuai nama dan tanggal
                        $absen = \App\Models\Absensi::whereHas('karyawan', function($q) use ($r) {
                                        $q->where('Nama', $r['nama']);
                                    })
                                    ->where('Tanggal', $r['tanggal'])
                                    ->first();

                        // Hitung Totalizer Utama
                        $literUtama = ($absen && $absen->TotalizerAkhir && $absen->TotalizerAwal)
                            ? $absen->TotalizerAkhir - $absen->TotalizerAwal
                            : 0;

                        // Hitung Totalizer Backup
                        $literBackup = 0;
                        if ($absen) {
                            $literBackup = \App\Models\BackupSession::where('AbsensiId', $absen->id)
                                ->whereNotNull('TotalizerAkhir')
                                ->sum(\DB::raw('TotalizerAkhir - TotalizerAwal'));
                        }

                        // Hitung insentif
                        $insentif = ($literUtama + $literBackup) * 100;
                    @endphp
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $r['nama'] }}</td>
                        <td>{{ $r['tanggal'] }}</td>
                        <td>{{ $r['shift'] }}</td>
                        <td>
                            @if($r['status'] == 'Hadir')
                                <span class="badge bg-success">Hadir</span>
                            @else
                                <span class="badge bg-danger">Tidak Hadir</span>
                            @endif
                        </td>
                        <td>{{ $r['jam_masuk'] }}</td>
                        <td>{{ $r['jam_istirahat'] }}</td>
                        <td>{{ $r['jam_pulang'] }}</td>
                        <td>{{ $r['nozle'] }}</td>
                        <td>{{ $r['produk'] }}</td>
                        <td>
                            Utama: {{ number_format($literUtama, 0) }}L <br>
                            Backup: {{ number_format($literBackup, 0) }}L
                        </td>
                        <td>Rp {{ number_format($insentif, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center">Tidak ada data jadwal.</td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>
        </div>
    </div>
</div>
@endsection
