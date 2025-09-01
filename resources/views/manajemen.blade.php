@extends('layouts.header')

@section('title', 'Manajemen Data')

@section('content')
<style>
  

</style>
<div class="container mt-4">

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
                    <th>ID FIngerPrint</th>
                    <th>NIK</th>
                    <th>CV</th>
                    <th>Data Pribadi</th>
                    <th>Jabatan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
              @foreach($karyawan as $k)
<tr>
    <td>{{ $k->Nama }}</td>
    <td>{{ $k->id ?? '-' }}</td>
    <td>{{ $k->Nip }}</td>
    <td>
        @if($k->Cv)
            <a href="{{ asset('storage/'.$k->Cv) }}" target="_blank" class="btn btn-sm btn-outline-info">Lihat CV</a>
        @else
            <span class="text-muted">-</span>
        @endif
    </td>
    <td>
        @if($k->FilePribadi)
            <a href="{{ asset('storage/'.$k->FilePribadi) }}" target="_blank" class="btn btn-sm btn-outline-info">Lihat File</a>
        @else
            <span class="text-muted">-</span>
        @endif
    </td>
    <td>{{ $k->Role }}</td>
    <td>
        @if($k->Status == 'Aktif')
            <span class="fs-6 badge bg-success">Aktif</span>
        @elseif($k->Status == 'PHK')
            <span class="fs-6 badge bg-danger">PHK</span>
        @elseif($k->Status == 'Resign')
            <span class="fs-6 badge bg-dark">Resign</span>    
        @elseif($k->Status == 'SP')
            <span class="fs-6 badge bg-warning text-dark">SP</span>    
        @else
            <span class="fs-6 badge bg-secondary">NonAktif</span>
        @endif
    </td>
    <td>
        <button class="btn btn-primary btn-sm"
            data-bs-toggle="modal"
            data-bs-target="#EditModal"
            data-id="{{ $k->id }}"
            data-nama="{{ $k->Nama }}"
            data-role="{{ $k->Role }}"
            data-nip="{{ $k->Nip }}"
            data-cv="{{ $k->Cv }}"
            data-filepribadi="{{ $k->FilePribadi }}"
            data-status="{{ $k->Status }}"
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

    <div class="section-card">
        <div class="section-title">
            <img src="{{ asset('img/price.png') }}" alt="Produk">
            Manajemen Produk
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Harga Per Liter</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produk as $p)
                <tr>
                    <td>{{ $p->NamaProduk }}</td>
                    <td>Rp.{{ number_format($p->HargaPerLiter, 0, ',', '.') }}</td>
                    <td>
                        <button
                        data-bs-toggle="modal"
                        data-bs-target="#EditProduk"
                        data-id="{{ $p->id }}"
                        data-nama="{{ $p->NamaProduk }}"
                        data-harga="{{ $p->HargaPerLiter }}"
                        href="#" class="btn btn-primary btn-sm">Edit</button>
                       
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="section-card">
        <div class="section-title">
            <img src="{{ asset('img/oil-truck.png') }}" alt="Tangki">
            Manajemen Tangki
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Nomor Tangki</th>
                    <th>Produk</th>
                    <th>Ukuran</th>
                    <th>Jumlah Pulau</th>
                    <th>Jumlah Dispenser</th>
                    <th>Jumlah Nozzle</th>
                  
                </tr>
            </thead>
            <tbody>
                    @foreach($tangki as $t)
                    <tr>
                         <td>{{ $loop->iteration }}</td>
                         <td>{{ $t->Produk }}</td>
                         <td>{{ $t->Ukuran }}</td>
                         <td>{{ $t->Pulau }}</td>
                        <td>{{ $t->Dispenser }}</td>
                        <td>{{ $t->Nozle }}</td>
                             
                    </tr>  
                    @endforeach
            </tbody>
        </table>
    </div>
</div>

@include('modals.tambahkaryawan')
@include('modals.tambahnozle')
@include('modals.editkaryawan')
@include('modals.editnozle')
@include('modals.editproduk')
@endsection