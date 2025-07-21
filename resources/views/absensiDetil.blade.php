{{-- filepath: resources/views/absensiDetil.blade.php --}}
@extends('layouts.app')

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
                        <th>Role</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Jam Masuk</th>
                        <th>Jam Istirahat</th>
                        <th>Jam Kembali</th>
                        <th>Jam Pulang</th>
                        <th>Nozzle</th>
                        <th>Produk</th>
                        <th>Totalizer</th>
                        <th>Insentif</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $i => $row)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $row['nama'] }}</td>
                            <td>{{ $row['role'] }}</td>
                            <td>{{ $row['tanggal'] }}</td>
                             <td>
                                    @if($row['status'] == 'Hadir')
                                        <span class="badge bg-success">Hadir</span>
                                    @else
                                        <span class="badge bg-danger">Tidak Hadir</span>
                                    @endif
                                </td>
                            <td>{{ $row['jam_masuk'] }}</td>
                            <td>{{ $row['jam_istirahat'] }}</td>
                            <td>{{ $row['jam_kembali'] }}</td>
                            <td>{{ $row['jam_pulang'] }}</td>
                            <td>{{ $row['nozle'] }}</td>
                            <td>{{ $row['produk'] }}</td>
                            <td>
                                Utama: {{ number_format($row['totalizer_utama'], 0) }}L <br>
                                Backup: {{ number_format($row['totalizer_backup'], 0) }}L
                            </td>
                            <td>Rp {{ number_format($row['insentif'], 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="text-center">Belum ada data absensi untuk SPBU ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
           @if($spbu)
                <a href="{{ route('owner.absensiDetil.excel', ['id' => $spbu->id]) }}" class="btn btn-success">
                    Download Excel <i class="fa-solid fa-file-excel"></i>
                </a>
            @endif

            <a href="{{ route('owner.absensiDetil.pdf', $spbu->id) }}" class="btn btn-primary">
                Download PDF <i class="fa-solid fa-file-pdf"></i>
            </a>

        </div>
    </div>
</div>
@endsection