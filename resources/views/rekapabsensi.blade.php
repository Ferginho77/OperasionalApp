@extends('layouts.header')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h3>Rekap Absensi Operator SPBU</h3>
            <div class="mb-3">
               <a href="{{ url('/download-rekap') }}" class="btn btn-success">
                    Download Dokumen <i class="fa-solid fa-download"></i>
            </a>
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
                            <th>Jam Kerja</th> <!-- Tambahkan ini -->
                            <th>Nozle</th>
                            <th>Produk</th>
                            <th>Totalizer</th>
                            <th>Insentif</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($rekap as $i => $r)
                            <tr>
                                <td>{{ $i + 1 }}</td>
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
                                <td>{{ $r['jam_kerja'] }}</td> <!-- Tambahan -->
                                <td>{{ $r['nozle'] }}</td>
                                <td>{{ $r['produk'] }}</td>
                                <td>
                                    Utama: {{ number_format($r['totalizer_utama'], 0) }}L <br>
                                    Backup: {{ number_format($r['totalizer_backup'], 0) }}L
                                </td>
                                <td>Rp {{ number_format($r['insentif'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center">Tidak ada data jadwal.</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
@endsection
