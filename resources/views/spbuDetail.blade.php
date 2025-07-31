@extends('layouts.app')

@section('title', 'Manajemen SPBU - ' . $spbu->Nama)

@section('content')
<div class="container m-3 p-2">
    <a href="/owner" class="btn btn-outline-danger">Kembali</a>
     <h2>Manajemen SPBU - {{ $spbu->NamaSPBU }}</h2>
    <h5><strong>Nomor SPBU:</strong> {{ $spbu->NomorSPBU }}</h5>
    <h5><strong>Alamat:</strong> {{ $spbu->Alamat ?? '-' }}</h5>
        {{-- Operator Aktif --}}
        <div class="row mt-2">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-info text-white mb-4">
                    <div class="card-body">Operator Aktif<i class="fa-solid fa-user-check"></i></div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <span class="small text-white">{{ $operatorAktif }} Operator</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">Pengawas Aktif <i class="fa-solid fa-user-tie"></i></div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="small text-white">{{ $pengawasAktif }} Pengawas</span>
                </div>
            </div>
        </div>
        </div>
    <h4 class="mt-4">Operator di SPBU ini:</h4>
   <div class="section-card">
    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
        <div class="section-title mb-0">
            <img src="{{ asset('img/staff.png') }}" alt="Karyawan">
            Manajemen Karyawan
        </div>
        <form method="GET" action="{{ route('manajemen') }}" class="d-flex" style="gap:8px; min-width:250px;">
            <input type="text" name="search_karyawan" class="form-control form-control-sm" placeholder="Cari Nama Karyawan..." value="{{ request('search_karyawan') }}">
            <button class="btn btn-outline-primary btn-sm" type="submit">Cari</button>
            @if(request('search_karyawan'))
                <a href="{{ route('manajemen') }}" class="btn btn-outline-danger btn-sm">Reset</a>
            @endif
        </form>
    </div>
    <div class="mb-2">
        <button 
        data-bs-toggle="modal"
        data-bs-target="#TambahKaryawan"
        class="btn btn-success btn-sm">Tambah Karyawan</button>
    </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NIP</th>
                    <th>Jabatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($karyawans as $k)
                <tr>
                    <td>{{ $k->Nama }}</td>
                    <td>{{ $k->Nip }}</td>
                    <td>{{ $k->Role }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#EditModal"
                        data-id="{{ $k->id }}"
                        data-nama="{{ $k->Nama }}"
                        data-nip="{{ $k->Nip }}"
                        >Edit</button>
                        <form action="{{ route('karyawan.destroy', $k->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus karyawan ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section-card">
    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
        <div class="section-title mb-0">
            <img src="{{ asset('img/gas-pump.png') }}" alt="Nozzle">
            Manajemen Nozzle
        </div>
        <form method="GET" action="{{ route('manajemen') }}" class="d-flex" style="gap:8px; min-width:250px;">
            <input type="text" name="search_nozle" class="form-control form-control-sm" placeholder="Cari Nama Nozzle..." value="{{ request('search_nozle') }}">
            <button class="btn btn-outline-primary btn-sm" type="submit">Cari</button>
            @if(request('search_nozle'))
                <a href="{{ route('manajemen') }}" class="btn btn-outline-danger btn-sm">Reset</a>
            @endif
        </form>
    </div>
    <div class="mb-2">
        <button 
        data-bs-toggle="modal"
        data-bs-target="#TambahNozle"
        class="btn btn-success btn-sm">Tambah Nozzle</button>
    </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Nama Nozzle</th>
                    <th>Pulau</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($nozle as $n)
                <tr>
                    <td>{{ $n->NamaNozle }}</td>
                    <td>{{ $n->pulau->NamaPulau ?? '-' }}</td>
                    <td>
                        <button
                        data-bs-toggle="modal"
                        data-bs-target="#EditNozle"
                        data-id="{{ $n->id }}"
                        data-nama="{{ $n->NamaNozle }}"
                        data-pulau="{{ $n->PulauId ?? '' }}"
                        href="#" class="btn btn-primary btn-sm">Edit</button>
                       <form action="{{ route('nozle.destroy', $n->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus nozle ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h4 class="mt-4">Kalender Operator:</h4>
    <div class="row mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div id="calendarMini"></div>
                </div>
            </div>
    </div>
    <div class="jadwal-header mt-3">
        <img src="{{ asset('img/operator.png') }}" alt="Operator">
        <h2>Jadwal Operator</h2>
    </div>
    <div class="row">
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="section-card mt-3">
                    <h4>Daftar Jadwal</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Operator</th>
                            <th>Tanggal</th>
                            <th>Shift</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jadwals as $jadwal)
                            <tr>
                                <td>{{ $jadwal->karyawan->Nama }}</td>
                                <td>{{ $jadwal->Tanggal }}</td>
                                <td>{{ ucfirst($jadwal->Shift) }}</td>
                            </tr>
                        @endforeach

                        @if ($jadwals->isEmpty())
                            <tr><td colspan="4" class="text-center">Belum ada jadwal</td></tr>
                        @endif
                    </tbody>
                </table>
                 <a href="{{ route('owner.jadwal.download.xls', $spbu->NomorSPBU) }}" class="btn btn-success">
    Download Dokumen <i class="fa-solid fa-download"></i>
</a>
<a href="{{ route('owner.jadwal.download.pdf', $spbu->NomorSPBU) }}" class="btn btn-primary">
    Download PDF <i class="fa-solid fa-download"></i>
</a>
            </div>
        </div>
        <div class="col-md-6 d-flex justify-content-center">
              <img  class="img-fluid" src="/img/calender.png" alt="Logo Mesin" style="max-width: 250px; max-height: 350px; object-fit: contain;">
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendarMini');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 350, 
        aspectRatio: 1.1, 
        events: "/api/kalender/{{ $spbu->NomorSPBU }}",

    });
    calendar.render();
});
</script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>


@include('modals.tambahkaryawan')
@include('modals.tambahnozle')
@include('modals.editkaryawan')
@include('modals.editnozle')
@include('modals.editproduk')
@endsection
