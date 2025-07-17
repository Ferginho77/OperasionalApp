@extends('layouts.header')

@section('content')
{{-- Styling untuk halaman jadwal operator --}}
<style>
   
</style>

<div class="container">
    <div class="jadwal-header mt-3 {{ $editJadwal ? 'edit-mode' : '' }}">
        <img src="{{ asset('img/operator.png') }}" alt="Operator">
        <h2>{{ $editJadwal ? 'Edit Jadwal Operator' : 'Buat Jadwal Operator' }}</h2>
    </div>

    {{-- Form Create/Edit --}}
    <form action="{{ $editJadwal ? route('jadwal.update', $editJadwal->id) : route('jadwal.store') }}" method="POST" class="mb-5">
    @csrf
    <div class="row">
        <div class="col-md-4 mb-3">
            <label>Operator</label>
            <select name="KaryawanId" class="form-control" required>
                @foreach($operators as $operator)
                    <option value="{{ $operator->id }}"
                        {{ old('KaryawanId', $editJadwal->KaryawanId ?? '') == $operator->id ? 'selected' : '' }}>
                        {{ $operator->Nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 mb-3">
            <label>Tanggal</label>
            <input type="date" name="Tanggal" class="form-control" value="{{ old('Tanggal', $editJadwal->Tanggal ?? '') }}" required>
        </div>
        <div class="col-md-3 mb-3">
            <label>Shift</label>
            <select name="Shift" class="form-control" required>
                <option value="pagi" {{ old('Shift', $editJadwal->Shift ?? '') == 'pagi' ? 'selected' : '' }}>Pagi</option>
                <option value="sore" {{ old('Shift', $editJadwal->Shift ?? '') == 'sore' ? 'selected' : '' }}>Sore</option>
                <option value="malam" {{ old('Shift', $editJadwal->Shift ?? '') == 'malam' ? 'selected' : '' }}>Malam</option>
            </select>
        </div>
        <div class="col-md-2 mb-3 d-flex align-items-end">
            <button class="btn btn-{{ $editJadwal ? 'warning' : 'success' }} w-100">
                {{ $editJadwal ? 'Perbarui' : 'Simpan' }}
            </button>
        </div>
    </div>
    </form>

    <a href="/kalender" class="btn btn-outline-primary"> Lihat Jadwal Operator</a>
   <form id="uploadForm" action="{{ route('jadwal.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <!-- Hidden File Input -->
    <input type="file" name="file" id="fileInput" class="d-none" accept=".xlsx,.xls" required>

    <!-- Styled Upload Button -->
    <button type="button" class="btn btn-outline-success" onclick="document.getElementById('fileInput').click();">
        Upload Dokumen
    </button>
</form>
    {{-- Tabel Jadwal --}}
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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jadwals as $jadwal)
                            <tr>
                                <td>{{ $jadwal->karyawan->Nama }}</td>
                                <td>{{ $jadwal->Tanggal }}</td>
                                <td>{{ ucfirst($jadwal->Shift) }}</td>
                                <td>
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('jadwal', ['edit' => $jadwal->id]) }}" class="btn btn-sm btn-primary">Edit</a>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('jadwal.destroy', $jadwal->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        @if ($jadwals->isEmpty())
                            <tr><td colspan="4" class="text-center">Belum ada jadwal</td></tr>
                        @endif
                    </tbody>
                </table>
                 <a href="{{ url('/download-jadwal') }}" class="btn btn-success">
                    Download Dokumen <i class="fa-solid fa-download"></i>
            </a>
            <a href="{{ url('/download-jadwal-pdf') }}" class="btn btn-primary">Download PDF <i class="fa-solid fa-download"></i></a>
            </div>
        </div>
        <div class="col-md-6 d-flex justify-content-center">
              <img  class="img-fluid" src="/img/calender.png" alt="Logo Mesin" style="max-width: 250px; max-height: 350px; object-fit: contain;">
        </div>
    </div>
    
</div>
<script>
    document.getElementById('fileInput').addEventListener('change', function () {
        if (this.files.length > 0) {
            document.getElementById('uploadForm').submit();
        }
    });
</script>
@endsection
