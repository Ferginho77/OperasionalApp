@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <a href="/kehadiranKaryawan" class="btn btn-outline-danger mb-3">Kembali</a>

    <h3 class="mb-4">Detail Absensi SPBU: {{ $spbu->Nama ?? $spbu->NomorSPBU }}</h3>
<div class="card">
    <div class="card-body">
         <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Nama Karyawan</th>
                    <th>Jabatan</th>
                    <th>Waktu Masuk</th>
                    <th>Waktu Pulang</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kehadirans as $index => $kehadiran)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $kehadiran->karyawan->Nama ?? '-' }}</td>
                        <td>{{ $kehadiran->karyawan->Role ?? '-' }}</td>
                        <td>{{ $kehadiran->WaktuMasuk ? date('d-m-Y H:i', strtotime($kehadiran->WaktuMasuk)) : '-' }}</td>
                        <td>{{ $kehadiran->WaktuPulang ? date('d-m-Y H:i', strtotime($kehadiran->WaktuPulang)) : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data kehadiran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
        <div class="mb-3">
    <a href="{{ route('owner.kehadiran.detil.pdf', $spbu->id) }}" class="btn btn-danger">
        Export PDF <i class="fa-solid fa-file-pdf"></i>
    </a>
    <a href="{{ route('owner.kehadiran.detil.excel', $spbu->id) }}" class="btn btn-success">
        Export Excel <i class="fa-solid fa-file-excel"></i>
    </a>
</div>

    </div>
</div>
</div>
@endsection
