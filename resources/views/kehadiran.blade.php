@extends('layouts.header')
@section('content')
<div class="container mt-4">
    <h2>Data Absensi</h2>

    <!-- Filter Bulan & Tahun -->
    <div class="mb-3">
        <form method="GET">
            <div class="row">
                <div class="col-md-3">
                    <select name="bulan" class="form-select">
                        <option value="">-- Pilih Bulan --</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="tahun" class="form-select">
                        <option value="">-- Pilih Tahun --</option>
                        @php
                            $startYear = date('Y') - 5;
                            $endYear = date('Y') + 5;
                        @endphp
                        @for ($y = $startYear; $y <= $endYear; $y++)
                            <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-body">

            @php
                $filteredKehadiran = $kehadirans;

                // Filter Bulan
                if(request('bulan')) {
                    $filteredKehadiran = $filteredKehadiran->filter(function($item){
                        return \Carbon\Carbon::parse($item->WaktuMasuk)->month == request('bulan');
                    });
                }

                // Filter Tahun
                if(request('tahun')) {
                    $filteredKehadiran = $filteredKehadiran->filter(function($item){
                        return \Carbon\Carbon::parse($item->WaktuMasuk)->year == request('tahun');
                    });
                }

                // Pagination manual
                $perPage = 10;
                $currentPage = request()->get('page', 1);
                $total = $filteredKehadiran->count();
                $pagedData = $filteredKehadiran->slice(($currentPage - 1) * $perPage, $perPage);
            @endphp

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Karyawan</th>
                        <th>Jabatan</th>
                        <th>Waktu Masuk</th>
                        <th>Waktu Pulang</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pagedData as $index => $item)
                    <tr>
                        <td>{{ ($currentPage - 1) * $perPage + $index + 1 }}</td>
                        <td>{{ $item->Karyawan->Nama }}</td>
                        <td>{{ $item->Karyawan->Role }}</td>
                        <td>{{ $item->WaktuMasuk }}</td>
                        <td>{{ $item->WaktuPulang ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination Links -->
            @php
                $totalPages = ceil($total / $perPage);
            @endphp
            @if($totalPages > 1)
                <nav>
                    <ul class="pagination">
                        @for($i = 1; $i <= $totalPages; $i++)
                            <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">{{ $i }}</a>
                            </li>
                        @endfor
                    </ul>
                </nav>
            @endif

            <div class="mb-3">
                <a href="{{ url('/download-kehadiran') }}" class="btn btn-success">
                    Download Dokumen <i class="fa-solid fa-download"></i>
                </a>
                <a href="{{ url('/download-kehadiran-pdf') }}" class="btn btn-primary">
                    Download PDF <i class="fa-solid fa-download"></i>
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
