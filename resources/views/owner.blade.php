@extends('layouts.app')

@section('title', 'Dashboard Page')

@section('content')

<h1>Halaman Owner</h1>
<div class="row mt-5">

        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">Jadwal Operator/SPBU <i class="fa-solid fa-calendar-days"></i></div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="/kalender">View Details</a>
                    <div class="small text-white">
                        <svg class="svg-inline--fa fa-angle-right" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="angle-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512">
                            <path fill="currentColor" d="M246.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L178.7 256 41.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning mb-4">
                <div class="card-body">SPBU Aktif <i class="fa-solid fa-gas-pump"></i></div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="small text-white">{{ $spbu }} SPBU</span>
                    <div class="small text-white">
                        <svg class="svg-inline--fa fa-angle-right" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="angle-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512">
                            <path fill="currentColor" d="M246.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L178.7 256 41.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">Manajemen SPBU <i class="fa-solid fa-list-check"></i></div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="/manajemen">View Details</a>
                    <div class="small text-white">
                        <svg class="svg-inline--fa fa-angle-right" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="angle-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512">
                            <path fill="currentColor" d="M246.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L178.7 256 41.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">Operator Aktif <i class="fa-solid fa-user-check"></i></div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="small text-white">{{ $karyawan }} Operator</span>
                </div>
            </div>
        </div>

        {{-- Card Admin --}}
        <div class="col-xl-3 col-md-6">
            <div class="card bg-secondary text-white mb-4">
                <div class="card-body">Admin Aktif <i class="fa-solid fa-user-shield"></i></div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="small text-white">{{ $adminCount }} Admin</span>
                </div>
            </div>
        </div>

        {{-- Card Accounting --}}
        <div class="col-xl-3 col-md-6">
            <div class="card bg-dark text-white mb-4">
                <div class="card-body">Accounting Aktif <i class="fa-solid fa-calculator"></i></div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="small text-white">{{ $accountingCount }} Accounting</span>
                </div>
            </div>
        </div>

        {{-- Card Pengawas --}}
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">Pengawas Aktif <i class="fa-solid fa-user-tie"></i></div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="small text-white">{{ $pengawasCount }} Pengawas</span>
                </div>
            </div>
        </div>
    </div> 
    <div class="row mt-4">
    <h3>Manajemen SPBU per Wilayah</h3>
    @foreach($spbus as $spbu)
    <div class="col-xl-3 col-md-6">
        <div class="card mb-4 shadow-sm" style="cursor: pointer;" onclick="window.location.href='/manajemen/spbu/{{ $spbu['id'] }}'">
            <div class="card-body">
                <h5 class="card-title text-primary mb-2">
                    <i class="fa-solid fa-gas-pump"></i> {{ $spbu['NamaSPBU'] }}
                </h5>
                <p class="card-text text-muted mb-0">
                    {{ $spbu['NomorSPBU'] }}
                </p>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
               <a href="{{ route('owner.spbu.show', $spbu->id) }}">Kelola SPBU</a>
                <i class="fa-solid fa-angle-right text-primary"></i>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection