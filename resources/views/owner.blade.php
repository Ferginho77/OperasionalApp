@extends('layouts.app')

@section('title', 'Dashboard Page')

@section('content')
    <div class="container-fluid p-4">
        <!-- Selamat Datang dengan Gambar -->
        <div class="row g-4 align-items-center mb-5 bg-white rounded-3">
            <div class="col-md-6">
                <img src="{{ asset('img/grafik.png') }}" class="img-fluid rounded" alt="Grafik"
                    style="width: 60%; max-width: 300px; max-height: 250px; object-fit: cover; object-position: top; display: block; margin: 0 auto;">
            </div>
            <div class="col-md-6">
                <h2 class="card-title fw-bold text-primary mb-2">Selamat Datang</h2>
                <p class="card-text fs-4 text-muted">
                    di <span class="fw-semibold">Dashboard Owner</span>
                </p>
                <p class="text-muted small">Tanggal: {{ now()->format('d F Y') }} | Waktu:
                    {{ now()->format('H:i') }} WIB</p>
            </div>
        </div>
        <!-- Cards Utama -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 g-3">
            <div class="col">
                <div class="card h-100 bg-purple-soft border-0 shadow-sm text-dark text-center">
                    <div class="card-body p-3">
                        <i class="fa-solid fa-gas-pump fa-2x mb-2" style="color: #6f42c1;"></i>
                        <h5 class="card-title fw-bold">SPBU Aktif</h5>
                        <p class="card-text fs-5">{{ $spbu ?? 0 }} SPBU</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 bg-blue-soft border-0 shadow-sm text-dark text-center">
                    <div class="card-body p-3">
                        <i class="fa-solid fa-user-check fa-2x mb-2" style="color: #007bff;"></i>
                        <h5 class="card-title fw-bold">Total Operator</h5>
                        <p class="card-text fs-5">{{ $karyawan ?? 0 }} Operator</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 bg-pink-soft border-0 shadow-sm text-dark text-center">
                    <div class="card-body p-3">
                        <i class="fa-solid fa-user-shield fa-2x mb-2" style="color: #e83e8c;"></i>
                        <h5 class="card-title fw-bold">Admin Aktif</h5>
                        <p class="card-text fs-5">{{ $adminCount ?? 0 }} Admin</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 bg-green-soft border-0 shadow-sm text-dark text-center">
                    <div class="card-body p-3">
                        <i class="fa-solid fa-calculator fa-2x mb-2" style="color: #28a745;"></i>
                        <h5 class="card-title fw-bold">Accounting Aktif</h5>
                        <p class="card-text fs-5">{{ $accountingCount ?? 0 }} Accounting</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 bg-yellow-soft border-0 shadow-sm text-dark text-center">
                    <div class="card-body p-3">
                        <i class="fa-solid fa-user-tie fa-2x mb-2" style="color: #ffc107;"></i>
                        <h5 class="card-title fw-bold">Pengawas Aktif</h5>
                        <p class="card-text fs-5">{{ $pengawasCount ?? 0 }} Pengawas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Manajemen SPBU per Wilayah -->
        <div class="row mt-5">
            <h3 class="mb-4 text-dark fw-bold">Manajemen SPBU per Wilayah</h3>
            <div class="row">
                @forelse ($spbus as $spbu)
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-0 spbu-card" style="cursor: pointer;"
                            onclick="window.location.href='/manajemen/spbu/{{ $spbu['id'] ?? '' }}'">
                            <div class="card-body">
                                <h5 class="card-title fw-bold mb-2 text-soft-primary">
                                    <i class="fa-solid fa-gas-pump me-2 text-soft-primary"></i>
                                    {{ $spbu['NamaSPBU'] ?? 'N/A' }}
                                </h5>
                                <p class="card-text text-muted mb-0">
                                    Nomor SPBU: {{ $spbu['NomorSPBU'] ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="card-footer bg-transparent border-top-0 text-end">
                                <a href="{{ route('owner.spbu.show', $spbu->id ?? '') }}"
                                    class="text-soft-primary fw-semibold">Kelola SPBU <i
                                        class="fa-solid fa-angle-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-muted">Tidak ada data SPBU saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
        .spbu-card {
            border-radius: 12px;
            background-color: #f4f5f7;
            /* soft grey */
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .spbu-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .text-soft-primary {
            color: #6c63ff;
            /* soft purple/blue */
        }

        .bg-purple-soft {
            background-color: #e9d8ff;
        }

        .bg-blue-soft {
            background-color: #cce5ff;
        }

        .bg-pink-soft {
            background-color: #ffd1dc;
        }

        .bg-green-soft {
            background-color: #c3e6cb;
        }

        .bg-yellow-soft {
            background-color: #fff3cd;
        }

        .bg-gray-700 {
            background-color: #495057;
        }

        .bg-light {
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 12px;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-footer {
            border-top: none;
        }

        .badge {
            font-size: 0.875rem;
            border-radius: 8px;
        }

        .fw-semibold {
            font-weight: 600;
        }

        .fs-4 {
            font-size: 1.5rem;
        }

        .fs-5 {
            font-size: 1.25rem;
        }

        .fa-2x {
            font-size: 2rem !important;
        }

        .shadow-sm {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .shadow-lg {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .img-fluid {
            max-width: 100%;
            height: auto;
        }

        .rounded-3 {
            border-radius: 0.75rem;
        }
    </style>
@endsection
