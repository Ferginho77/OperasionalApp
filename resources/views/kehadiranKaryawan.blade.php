@extends('layouts.app')

@section('content')
<div class="container mt-4">
     
    <h3>Kehadiran Tiap Wilayah</h3>
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
                    <a href="{{ route('owner.kehadiran.detil', $spbu->id) }}">
                        Lihat Detail Kehadiran
                    </a>

                        <i class="fa-solid fa-angle-right text-primary"></i>
                </div>
            </div>
        </div>
    @endforeach
@endsection    