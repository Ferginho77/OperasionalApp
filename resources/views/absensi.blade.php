@extends('layouts.header')

@section('content')
<div class="container">
    <div class="row align-items-center">
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="card shadow">
                <div class="card-header">
                    <h3>Absen Masuk</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('absensi.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="KaryawanId" class="form-label">Karyawan</label>
                            <select name="KaryawanId" id="karyawanSelect" class="form-select">
                                @foreach ($karyawan as $k)
                                    <option value="{{ $k->id }}" data-totalizer="{{ $k->totalizerAkhirTerakhir->TotalizerAkhir ?? '0' }}">{{ $k->Nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="NozleId" class="form-label">Nozzle</label>
                            <select name="NozleId" id="nozleSelect" class="form-select">
                                @foreach ($nozle as $n)
                                    <option value="{{ $n->id }}" data-pulau="{{ $n->pulau->NamaPulau ?? '' }}">
                                        {{ $n->NamaNozle }} {{ $n->pulau->NamaPulau ?? '' }}
                                    </option>
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
                            <input type="text" name="Pulau" id="pulauInput" class="form-control" readonly value="">
                        </div>

                        <div class="mb-3">
                            <label for="TotalizerAwal" class="form-label">Totalizer Awal</label>
                            <input type="number" step="0.01" name="TotalizerAwal" id="TotalizerAwal" class="form-control" readonly>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Absen Masuk</button>
                    </form>
                </div>
            </div>
        </div>
        <div  class="col-md-6 d-flex justify-content-center">
            <img  class="img-fluid" src="/img/mesin.png" alt="Logo Mesin" style="max-width: 350px; max-height: 450px; object-fit: contain;">
        </div>
    </div>
</div>

<div class="container mt-4">
    <div class="row g-3">
        <div class="col-md-3 col-6">
            <h5 class="text-center mb-2">Absen Istirahat</h5>
            <button type="button" class="btn btn-warning w-100"
                data-bs-toggle="modal"
                data-bs-target="#IstirahatModal">
                Absen Istirahat
            </button>
        </div>
        <div class="col-md-3 col-6">
            <h5 class="text-center mb-2">Pindah Nozzle</h5>
            <button type="button" class="btn btn-info w-100"
                data-bs-toggle="modal"
                data-bs-target="#PindahModal">
                Pindah Nozzle
            </button>
        </div>
        <div class="col-md-3 col-6">
            <h5 class="text-center mb-2">Kembali ke Nozzle Awal</h5>
            <button type="button" class="btn btn-success w-100"
                data-bs-toggle="modal"
                data-bs-target="#KembaliModal">
                Kembali ke Nozzle Awal
            </button>
        </div>
        <div class="col-md-3 col-6">
            <h5 class="text-center mb-2">Absen Pulang</h5>
            <button type="button" class="btn btn-danger w-100"
                data-bs-toggle="modal"
                data-bs-target="#PulangModal">
                Absen Pulang
            </button>
        </div>
    </div>
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
<script>
  AOS.init();
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nozleSelect = document.getElementById('nozleSelect');
    const pulauInput = document.getElementById('pulauInput');

    function updatePulau() {
        const selectedOption = nozleSelect.options[nozleSelect.selectedIndex];
        pulauInput.value = selectedOption.getAttribute('data-pulau') || '';
    }

    nozleSelect.addEventListener('change', updatePulau);

    // Set pulau saat halaman pertama kali dimuat
    updatePulau();
});

document.addEventListener('DOMContentLoaded', function(){
    const karyawanSelect = document.getElementById('karyawanSelect');
    const TotalizerInput = document.getElementById('TotalizerAwal');

    function updateTotalizer() {
        const selectedOption = karyawanSelect.options[karyawanSelect.selectedIndex];
        TotalizerInput.value = selectedOption.getAttribute('data-totalizer') || '';
    }

    karyawanSelect.addEventListener('change', updateTotalizer);

    // Set totalizer saat halaman pertama kali dimuat
    updateTotalizer();
});
</script>

@endsection
