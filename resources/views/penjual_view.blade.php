@extends('layouts.header')

@section('title', 'Manajemen Data Penjualan')

@section('content')
    <div class="container mt-4">
        <div class="section-card">
            {{-- Header + Filter --}}
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <div class="section-title d-flex align-items-center mb-2">
                    <img src="https://www.thiings.co/_next/image?url=https%3A%2F%2Flftz25oez4aqbxpq.public.blob.vercel-storage.com%2Fimage-V5g1BA4Fg3Mgks7vN6n3eaXb8KBz0g.png&w=1000&q=75"
                        alt="Penjualan" width="32" class="me-2" />
                    <h4 class="mb-0">Manajemen Penjualan</h4>
                </div>

                <div class="filter-section d-flex align-items-center flex-wrap gap-2">
                    <form action="{{ route('penjualan.index') }}" method="GET" class="d-flex flex-wrap gap-2">
                        <div class="form-group mb-0">
                            <select name="bulan" class="form-select form-select-sm">
                                <option value="">Pilih Bulan</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}"
                                        {{ ($request->bulan ?? '') == $i ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <select name="tahun" class="form-select form-select-sm">
                                <option value="">Pilih Tahun</option>
                                @for ($year = date('Y'); $year >= 2020; $year--)
                                    <option value="{{ $year }}"
                                        {{ ($request->tahun ?? '') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <input type="text" name="search" class="form-control form-control-sm"
                                placeholder="Cari data..." value="{{ $request->search ?? '' }}">
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                        @if ($request->filled('bulan') || $request->filled('tahun') || $request->filled('search'))
                            <a href="{{ route('penjualan.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                        @endif
                    </form>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                <div class="btn-group mb-2">
                    <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal"
                        data-bs-target="#tambahPenjualanModal">
                        <i class="fas fa-plus me-1"></i> Tambah Penjualan
                    </a>
                    <a href="{{ route('penjualan.generateLaporan', ['bulan' => $request->bulan ?? date('m'), 'tahun' => $request->tahun ?? date('Y')]) }}"
                        class="btn btn-danger btn-sm" target="_blank">
                        <i class="fas fa-file-pdf me-1"></i> Laporan PDF
                    </a>
                </div>

                @if ($request->bulan || $request->tahun || $request->search)
                    <div class="filter-info mb-2">
                        <span class="badge bg-info">
                            @if ($request->bulan && $request->tahun)
                                Menampilkan data: {{ date('F', mktime(0, 0, 0, $request->bulan, 1)) }}
                                {{ $request->tahun }}
                            @elseif($request->bulan)
                                Menampilkan data: {{ date('F', mktime(0, 0, 0, $request->bulan, 1)) }}
                            @elseif($request->tahun)
                                Menampilkan data: Tahun {{ $request->tahun }}
                            @endif
                        </span>
                    </div>
                @endif
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nozzle</th>
                            <th>Pulau</th>
                            <th>Produk</th>
                            <th>Teler Awal</th>
                            <th>Teler Akhir</th>
                            <th>Jumlah</th>
                            <th>Jumlah Rupiah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penjualans as $penjualan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $penjualan->created_at->format('d/m/Y') }}</td>
                                <td>{{ $penjualan->nozle->NamaNozle ?? '-' }}</td>
                                <td>{{ $penjualan->pulau->NamaPulau ?? '-' }}</td>
                                <td>{{ $penjualan->produk->NamaProduk ?? '-' }}</td>
                                <td class="text-end">{{ number_format($penjualan->TelerAwal, 2) }}</td>
                                <td class="text-end">{{ number_format($penjualan->TelerAkhir, 2) }}</td>
                                <td class="text-end">{{ number_format($penjualan->Jumlah, 2) }}</td>
                                <td class="text-end">Rp {{ number_format($penjualan->JumlahRupiah, 2) }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="#" class="btn btn-sm edit-btn" data-bs-toggle="modal"
                                            data-bs-target="#editPenjualanModal" data-id="{{ $penjualan->id }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="#" class="btn btn-delete" data-bs-toggle="tooltip" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fs-1 d-block mb-2"></i>
                                    @if ($request->bulan || $request->tahun || $request->search)
                                        Tidak ada data penjualan untuk filter yang dipilih
                                    @else
                                        Belum ada data penjualan
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($penjualans->count() > 0)
                        <tfoot class="table-group-divider">
                            <tr class="fw-bold">
                                <td colspan="7" class="text-end">Total:</td>
                                <td class="text-end">{{ number_format($penjualans->sum('Jumlah'), 2) }}</td>
                                <td class="text-end">Rp {{ number_format($penjualans->sum('JumlahRupiah'), 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>

            {{-- Pagination --}}
            @if ($penjualans->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Menampilkan {{ $penjualans->firstItem() }} - {{ $penjualans->lastItem() }} dari
                        {{ $penjualans->total() }} data
                    </div>
                    <div>
                        {{ $penjualans->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Modal Tambah --}}
    @include('modals.tambahpenjualan')

    {{-- Modal Edit --}}
    @include('modals.edit_penjualan')

    <style>
        body {
            background-color: #F0F2F5;
        }

        .section-card {
            background: #FFFFFF;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 24px;
            margin-bottom: 24px;
        }

        .section-title h4 {
            color: #424242;
        }

        .table {
            border: none;
        }

        .table thead {
            background-color: #E9ECEF;
        }

        .table th {
            color: #424242;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .table td {
            vertical-align: middle;
            font-size: 0.875rem;
            border-bottom: 1px solid #DEE2E6;
        }

        .table-group-divider tr {
            border-top: 2px solid #DEE2E6;
        }

        .btn-success {
            background-color: #66BB6A;
            border-color: #66BB6A;
        }

        .btn-danger {
            background-color: #D32F2F;
            border-color: #D32F2F;
        }

        .btn-primary {
            background-color: #4DB6AC;
            border-color: #4DB6AC;
        }

        .btn-secondary {
            background-color: #A9A9A9;
            border-color: #A9A9A9;
            color: #fff;
        }

        .btn-edit,
        .btn-delete {
            background-color: transparent;
            color: #6C757D;
            border: 1px solid #DEE2E6;
            padding: 0.375rem 0.5rem;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btn-edit:hover {
            background-color: #E0E0E0;
            color: #424242;
        }

        .btn-delete:hover {
            background-color: #E0E0E0;
            color: #D32F2F;
        }

        .filter-info .badge {
            background-color: #E0E0E0 !important;
            color: #424242;
            font-weight: normal;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Bootstrap tooltip
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function(el) {
                return new bootstrap.Tooltip(el)
            });
        });
    </script>
@endsection
