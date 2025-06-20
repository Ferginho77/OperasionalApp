@extends('layouts.header')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
             <h3>Absen Masuk</h3>
        </div>
        <div class="card-body">
            
    <form action="{{ route('absensi.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="KaryawanId" class="form-label">Karyawan</label>
            <select name="KaryawanId" class="form-select">
                @foreach ($karyawan as $k)
                    <option value="{{ $k->id }}">{{ $k->Nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="NozleId" class="form-label">Nozzle</label>
            <select name="NozleId" class="form-select">
                @foreach ($nozle as $n)
                    <option value="{{ $n->id }}">{{ $n->NamaNozle }} (Pulau {{ $n->Pulau }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="ProdukId" class="form-label">Produk</label>
            <select name="ProdukId" class="form-select">
                @foreach ($produk as $p)
                    <option value="{{ $p->id }}">{{ $p->NamaProduk }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="Pulau" class="form-label">Pulau</label>
            <input type="text" name="Pulau" class="form-control">
        </div>

        <div class="mb-3">
            <label for="TotalizerAwal" class="form-label">Totalizer Awal</label>
            <input type="number" step="0.01" name="TotalizerAwal" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Absen Masuk</button>
    </form>
        </div>
    </div>
</div>

<div class="container">
    <h3>Absen Istirahat</h3>
        <button type="submit" class="btn btn-warning"
                     data-bs-toggle="modal"
                     data-bs-target="#IstirahatModal"
                     style="width: 100%;"
                    >Absen Istirahat</button>
</div>

<div class="container">
    <h3>Pindah Nozzle</h3>
        <button type="submit" class="btn btn-info"
                    data-bs-toggle="modal"
                     data-bs-target="#PindahModal"
                     style="width: 100%;"
        >Pindah Nozzle</button>
</div>

<div class="container">
    <h3>Kembali ke Nozzle Awal</h3>
        <button type="submit" class="btn btn-success"
                     data-bs-toggle="modal"
                     data-bs-target="#KembaliModal"
                     style="width: 100%;"
        >Kembali ke Nozzle Awal</button>
</div>

<div class="container">
    <h3>Absen Pulang</h3>
        <button type="submit" class="btn btn-danger"
                    data-bs-toggle="modal"
                     data-bs-target="#PulangModal"
                     style="width: 100%;"
        >Absen Pulang</button>
</div>

@include('modals.istirahat')
@include('modals.pindahnozle')
@include('modals.kembalinozle')
@include('modals.pulang')
<div class="container">
    <h3>Data Absensi</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Istirahat</th>
                <th>Nozle</th>
                <th>Produk</th>
                <th>Totalizer</th>
                <th>Insentif</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($absensi as $a)
                <tr>
                    <td>{{ $a->karyawan->Nama }}</td>
                    <td>
                    @if ($a->JamPulang)
                        <span class="text-success">Sudah Pulang</span>
                    @elseif ($a->JamIstirahatKembali)
                        <span class="text-info">Kembali dari Istirahat</span>
                    @elseif ($a->JamKembaliNozle)
                        <span class="text-warning">Kembali dari Nozle</span>
                    @elseif ($a->JamPindahNozle)
                        <span class="text-warning">Sedang ke Nozle</span>
                    @elseif ($a->JamIstirahatMulai)
                        <span class="text-warning">Istirahat Dimulai</span>
                    @elseif ($a->JamMasuk)
                        <span class="text-primary">Sudah Masuk</span>
                    @else
                        <span class="text-danger">Belum Absen</span>
                    @endif
                </td>
                    <td>{{ $a->Tanggal }}</td>
                    <td>{{ $a->JamMasuk }}</td>
                    <td>{{ $a->JamIstirahatMulai }}</td>
                    <td>{{ $a->nozle->NamaNozle ?? '-' }}</td>
                    <td>{{ $a->produk->NamaProduk ?? '-' }}</td>
                    <td>
                        {{ $a->TotalizerAwal ?? '-' }} - {{ $a->TotalizerAkhir ?? '-' }}<br>
                        {{ number_format($a->TotalLiter, 2) }} Liter
                    </td>
                    <td>Rp {{ number_format($a->Insentif, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
