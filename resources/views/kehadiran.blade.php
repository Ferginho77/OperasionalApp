@extends('layouts.header')
@section('content')
<div class="container mt-4">
    <h2>Data Absensi</h2>
<div class="card">
    <div class="card-body">
         <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Karyawan</th>
                <th>Jabatan</th>
                <th>Waktu Masuk</th>
                <th>Waktu Pulang</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kehadirans as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->Karyawan->Nama }}</td>
                <td>{{ $item->Karyawan->Role }}</td>
                <td>{{ $item->WaktuMasuk }}</td>
                <td>{{ $item->WaktuPulang ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Belum ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
     <div class="mb-3">
               <a href="{{ url('/download-kehadiran') }}" class="btn btn-success">
                    Download Dokumen <i class="fa-solid fa-download"></i>
            </a>
            <a href="{{ url('/download-kehadiran-pdf') }}" class="btn btn-primary">Download PDF <i class="fa-solid fa-download"></i></a>
            </div>
    </div>
</div>
</div>
@endsection
