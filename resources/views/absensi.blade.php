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
                            <label for="KaryawanId" class="form-label">Operator</label>
                            <select name="KaryawanId" id="karyawanSelect" class="form-select">
                                @foreach ($karyawan as $k)
                                    <option value="{{ $k->id }}" data-totalizer="{{ $k->totalizerAkhirTerakhir->TotalizerAkhir ?? '0' }}">
                                        {{ $k->Nama }}
                                    </option>
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
                            <input type="number" step="0.01" name="TotalizerAwal" id="TotalizerAwal" class="form-control" readonly required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Absen Masuk</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6 d-flex justify-content-center">
            <img class="img-fluid" src="/img/mesin.png" alt="Logo Mesin" style="max-width: 350px; max-height: 450px; object-fit: contain;">
        </div>
    </div>
</div>

<div class="container mt-4">
    <div class="row g-3">
        <div class="col-md-2 col-6">
            <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#IstirahatModal">
                Istirahat
            </button>
        </div>
        <div class="col-md-2 col-6">
            <button type="button" class="btn btn-info w-100" data-bs-toggle="modal" data-bs-target="#PindahModal">
                Mulai Backup
            </button>
        </div>
        <div class="col-md-2 col-6">
            <button type="button" class="btn btn-secondary w-100" data-bs-toggle="modal" data-bs-target="#SelesaiBackupModal">
                Selesai Backup
            </button>
        </div>
        <div class="col-md-3 col-6">
            <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#KembaliModal">
                Kembali Nozzle
            </button>
        </div>
        <div class="col-md-3 col-6">
            <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#PulangModal">
                Pulang
            </button>
        </div>
    </div>
</div>

<div class="container mt-4">
    <h3>Data Penugasan</h3>
    <table class="table table-bordered">
        @if ($tidakHadir->count() > 0)
    <div class="alert alert-danger">
        <strong>Operator Belum Absen Hari Ini:</strong>
        <ul>
            @foreach ($tidakHadir as $operator)
                <li>{{ $operator->Nama }}</li>
            @endforeach
        </ul>
    </div>
@endif

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
        @php
            // These calculations should ideally be done in the controller or a mutator
            // on the Absensi model for better separation of concerns and performance.
            // For now, keeping them here as per your original code, but note the
            // addition of isPerformingBackup and isBeingBackedUp flags.
            $literUtama = ($a->TotalizerAkhir && $a->TotalizerAwal) ? ($a->TotalizerAkhir - $a->TotalizerAwal) : 0;
            $literBackup = \App\Models\BackupSession::where('AbsensiId', $a->id)
                ->whereNotNull('TotalizerAkhir')
                ->sum(\DB::raw('TotalizerAkhir - TotalizerAwal'));
            $totalLiter = $literUtama + $literBackup;
            $insentif = $totalLiter * 2.5;

            // Flags set in the controller
            $backupAktifUntukAbsensiIni = $a->isBeingBackedUp;
            $melakukanBackup = $a->isPerformingBackup;
        @endphp
        <tr>
            <td>{{ $a->karyawan->Nama }}</td>
            <td>
                @if ($melakukanBackup)
                    <span class="text-info">Melakukan Backup</span>
                @elseif ($backupAktifUntukAbsensiIni)
                    <span class="text-secondary">Sedang Di-Backup</span>
                @elseif ($a->JamPulang)
                    <span class="text-success">Sudah Pulang</span>
                @elseif ($a->JamIstirahatKembali)
                    <span class="text-info">Kembali Istirahat</span>
                @elseif ($a->JamIstirahatMulai)
                    <span class="text-warning">Istirahat</span>
                @else
                    <span class="text-primary">Masuk</span>
                @endif
            </td>
            <td>{{ $a->Tanggal }}</td>
            <td>{{ $a->JamMasuk }}</td>
            <td>{{ $a->JamIstirahatMulai }}</td>
            <td>{{ $a->nozle->NamaNozle ?? '-' }}</td>
            <td>{{ $a->produk->NamaProduk ?? '-' }}</td>
            <td>
                Utama: {{ number_format($a->totalizer_utama, 0) }}L <br>
                Backup: {{ number_format($a->totalizer_backup, 0) }}L
            </td>
            <td>
                Rp {{ number_format($a->insentif, 0, ',', '.') }}
            </td>
        </tr>
    @endforeach
</tbody>
    </table>
</div>

@include('modals.istirahat')
@include('modals.pindahnozle')
@include('modals.selesaibackup')
@include('modals.kembalinozle')
@include('modals.pulang')

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nozleSelect = document.getElementById('nozleSelect');
    const pulauInput = document.getElementById('pulauInput');
    function updatePulau() {
        const selectedOption = nozleSelect.options[nozleSelect.selectedIndex];
        pulauInput.value = selectedOption.getAttribute('data-pulau') || '';
    }
    nozleSelect.addEventListener('change', updatePulau);
    updatePulau();

    const karyawanSelect = document.getElementById('karyawanSelect');
    const totalizerInput = document.getElementById('TotalizerAwal');
    function updateTotalizer() {
        const selectedOption = karyawanSelect.options[karyawanSelect.selectedIndex];
        totalizerInput.value = selectedOption.getAttribute('data-totalizer') || '';
    }
    karyawanSelect.addEventListener('change', updateTotalizer);
    updateTotalizer();
});
</script>
@endsection
