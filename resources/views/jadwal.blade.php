@extends('layouts.header')

@section('content')
<div class="container">
    <h2 class="mb-4">{{ $editJadwal ? 'Edit Jadwal Operator' : 'Buat Jadwal Operator' }}</h2>

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
    {{-- Tabel Jadwal --}}
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
</div>
@endsection
