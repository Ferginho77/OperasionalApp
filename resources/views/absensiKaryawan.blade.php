<div>
  @extends('layouts.app')

@section('title', 'Dashboard Page')

@section('content')
<main>
    <div class="card mt-3">
        <div class="card-header">
           <h3> Table Absensi Karyawan</h3>
        </div>
        <div class="card-body">
           <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Karyawan</th>
                    <th>Tanggal</th>
                    <th>Jam Hadir</th>
                    <th>Jam Istirahat</th>
                    <th>Jam Kembali Istirahat</th>
                    <th>Jam Pulang</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                @foreach($absensi as $index => $x)
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $x->karyawan ? $x->karyawan->Nama : 'Karyawan Tidak Ditemukan' }}</td>
                    <td>{{ $x->Tanggal }}</td>
                    <td>{{ $x->JamMasuk ? $x->JamMasuk : 'Belum Absen' }}</td>
                    <td>{{ $x->JamIstirahat ? $x->JamIstirahat : 'Belum Absen' }}</td>
                    <td>{{ $x->JamKembali ? $x->JamKembali : 'Belum Absen' }}</td>
                    <td>{{ $x->JamKeluar ? $x->JamKeluar : 'Belum Absen' }}</td>
                     <td>
                        @if ($x->JamMasuk && !$x->JamIstirahat)
                           <div class="text-primary"><p>Hadir</p></div> 
                        @elseif ($x->JamIstirahat && !$x->JamKembali)
                            <div class="text-warning"><p>Sedang Istirahat</p></div> 
                        @elseif ($x->JamKembali && !$x->JamKeluar)
                            <div class="text-info"><p>Sudah Beres Istirahat</p></div> 
                        @elseif ($x->JamKeluar)
                            <div class="text-success"><p>Sudah Pulang</p></div>
                        @else
                            <div class="text-danger"><p>Belum Absen</p></div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            </table>
            <a href="{{ url('/download-absensi') }}" class="btn btn-success">
                    Download Dokumen <i class="fa-solid fa-download"></i>
            </a>
            <a href="{{ url('/download-absensi-pdf') }}" class="btn btn-primary">Download PDF <i class="fa-solid fa-download"></i></a>
        </div>
    </div>
</main>
@endsection
</div>
