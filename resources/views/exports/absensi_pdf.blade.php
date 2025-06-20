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
                <th>Jam Hadir</th>
                <th>Jam Istirahat</th>
                <th>Jam Kembali</th>
                <th>Jam Pulang</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absensi as $index => $x)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $x->karyawan ? $x->karyawan->Nama : 'Karyawan Tidak Ditemukan' }}</td>
                    <td>{{ $x->Tanggal }}</td>
                    <td>{{ $x->JamMasuk ?? 'Belum Absen' }}</td>
                    <td>{{ $x->JamIstirahat ?? 'Belum Absen' }}</td>
                    <td>{{ $x->JamKembali ?? 'Belum Absen' }}</td>
                    <td>{{ $x->JamKeluar ?? 'Belum Absen' }}</td>
                    <td>
                        @if ($x->JamMasuk && !$x->JamIstirahat)
                            Hadir
                        @elseif ($x->JamIstirahat && !$x->JamKembali)
                            Sedang Istirahat
                        @elseif ($x->JamKembali && !$x->JamKeluar)
                            Sudah Beres Istirahat
                        @elseif ($x->JamKeluar)
                            Sudah Pulang
                        @else
                            Belum Absen
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
