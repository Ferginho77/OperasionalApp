@extends('layouts.header')

@section('title', 'Manajemen Data')

@section('content')
<style>
    .table thead {
        background: linear-gradient(90deg, #007bff 0%, #00c6ff 100%);
        color: #fff;
    }
    .table tbody tr:hover {
        background-color: #f1f7ff;
        transition: background 0.2s;
    }
    .btn-success {
        background: linear-gradient(90deg, #00A94B 0%, #6fdc6f 100%);
        border: none;
    }
    .btn-primary {
        background: linear-gradient(90deg, #007bff 0%, #00c6ff 100%);
        border: none;
    }
    .btn-danger {
        background: linear-gradient(90deg, #dc3545 0%, #ff6f6f 100%);
        border: none;
    }
    .badge-role {
        background: #ffc107;
        color: #212529;
        font-size: 0.9em;
        padding: 0.4em 0.7em;
        border-radius: 0.5em;
        font-weight: 500;
    }
    h2 {
        border-left: 5px solid #007AFF;
        padding-left: 10px;
        margin-bottom: 30px;
        margin-top: 40px;
        background: #F5F7FB;
        border-radius: 4px;
        font-weight: 600;
    }
    .section-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        padding: 25px 20px 10px 20px;
        margin-bottom: 30px;
    }
</style>
<div class="container mt-4">
    <h2>Manajemen Karyawan</h2>
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
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($karyawan as $k)
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
                    <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2 class="mt-5">Manajemen Nozzle</h2>
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
                    data-pulau="{{ $n->pulau->NamaPulau ?? '' }}"
                    href="#" class="btn btn-primary btn-sm">Edit</button>
                    <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2 class="mt-5">Manajemen Produk</h2>
    <div class="mb-2">
        <a href="#" class="btn btn-success btn-sm">Tambah Produk</a>
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
                    <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@include('modals.tambahkaryawan')
@include('modals.tambahnozle')
@include('modals.editkaryawan')
@include('modals.editnozle')
@include('modals.editproduk')
@endsection

@if($pulau)
    @foreach($pulau as $p)
        <option value="{{ $p->PulauId }}">{{ $p->NamaPulau }}</option>
    @endforeach
@endif