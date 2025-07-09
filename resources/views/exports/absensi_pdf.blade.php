<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Absensi Karyawan</title>
    <style>
        body {
            font-family: sans-serif;
            position: relative;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
        .logo-kanan {
            position: absolute;
            top: 0;
            right: 0;
        }
    </style>
</head>
<body>
    <div style="position: relative; height: 100px;">
        <img src="{{ public_path('img/LogoPertamina.png') }}" alt="Logo Pertamina" width="165" height="100" class="logo-kanan">
    </div>

    <h2 style="margin-top: 20px;">Data Absensi Karyawan</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Karyawan</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Istirahat</th>
                <th>Jam Kembali</th>
                <th>Jam Pulang</th>
                <th>Nozzle</th>
                <th>Produk</th>
                <th>Totalizer Utama</th>
                <th>Totalizer Backup</th>
                <th>Total Liter</th>
                <th>Insentif</th>

            </tr>
        </thead>
        <tbody>
            @foreach($absensi as $index => $x)
                @php
                    $totalizer_utama = ($x->TotalizerAkhir && $x->TotalizerAwal)
                        ? $x->TotalizerAkhir - $x->TotalizerAwal
                        : 0;

                    $totalizer_backup = \App\Models\BackupSession::where('AbsensiId', $x->id)
                        ->whereNotNull('TotalizerAkhir')
                        ->sum(\DB::raw('TotalizerAkhir - TotalizerAwal'));

                    $total_liter = $totalizer_utama + $totalizer_backup;
                    $insentif = $total_liter * 100;
                @endphp

                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $x->karyawan ? $x->karyawan->Nama : 'Karyawan Tidak Ditemukan' }}</td>
                    <td>{{ $x->Tanggal }}</td>
                    <td>{{ $x->JamMasuk ? date('H:i', strtotime($x->JamMasuk)) : '-' }}</td>
                    <td>{{ $x->JamIstirahatMulai ? date('H:i', strtotime($x->JamIstirahatMulai)) : '-' }}</td>
                    <td>{{ $x->JamIstirahatSelesai ? date('H:i', strtotime($x->JamIstirahatSelesai)) : '-' }}</td>
                    <td>{{ $x->JamPulang ? date('H:i', strtotime($x->JamPulang)) : '-' }}</td>
                    <td>{{ $x->nozle->NamaNozle ?? '-' }}</td>
                    <td>{{ $x->produk->NamaProduk ?? '-' }}</td>
                    <td>{{ $totalizer_utama }}L</td>
                    <td>{{ $totalizer_backup }}L</td>
                    <td>{{ $total_liter }}L</td>
                    <td>Rp {{ number_format($insentif, 0, ',', '.') }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
