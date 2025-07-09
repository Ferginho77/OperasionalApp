{{-- filepath: resources/views/absensiDetil.blade.php --}}
@extends('layouts.header')

@section('content')
<div class="container mt-4">
    <a href="/absensiKaryawan" class="btn btn-outline-danger">Kembali</a>
    <h3>Detail Absensi SPBU: {{ $spbu->Nama ?? $spbu->NomorSPBU }}</h3>
    <div class="card mt-3">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Karyawan</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Istirahat</th>
                        <th>Jam Kembali</th>
                        <th>Jam Pulang</th>
                        <th>Nozzle</th>
                        <th>Produk</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensis as $i => $absen)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $absen->karyawan->Nama ?? '-' }}</td>
                            <td>{{ $absen->Tanggal }}</td>
                            <td>{{ $absen->JamMasuk ? date('H:i', strtotime($absen->JamMasuk)) : '-' }}</td>
                            <td>{{ $absen->JamIstirahatMulai ? date('H:i', strtotime($absen->JamIstirahatMulai)) : '-' }}</td>
                            <td>{{ $absen->JamIstirahatSelesai ? date('H:i', strtotime($absen->JamIstirahatSelesai)) : '-' }}</td>
                            <td>{{ $absen->JamPulang ? date('H:i', strtotime($absen->JamPulang)) : '-' }}</td>
                            <td>{{ $absen->nozle->NamaNozle ?? '-' }}</td>
                            <td>{{ $absen->produk->NamaProduk ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Belum ada data absensi untuk SPBU ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
             <a href="{{ url('/download-absensi') }}" class="btn btn-success">
                    Download Dokumen <i class="fa-solid fa-download"></i>
            </a>
            <a href="{{ url('/download-absensi-pdf') }}" class="btn btn-primary">Download PDF <i class="fa-solid fa-download"></i></a>
        </div>
    </div>
</div>
@endsection