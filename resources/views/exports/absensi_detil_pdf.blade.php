<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Absensi Detil</title>
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
   <h2 style="text-align:center; margin-bottom: 4px;">PT. Hendarsyah Surya Putra</h2>
    <div style="position: relative; height: 100px;">
        <img src="{{ public_path('img/LogoPertamina.png') }}" alt="Logo Pertamina" width="165" height="100" class="logo-kanan">
    </div>
    <h3>Rekap Absensi Detil SPBU: {{ $spbu->Nama ?? $spbu->NomorSPBU }}</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Karyawan</th>
                <th>Role</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Jam Masuk</th>
                <th>Jam Istirahat</th>
                <th>Jam Kembali</th>
                <th>Jam Pulang</th>
                <th>Nozzle</th>
                <th>Produk</th>
                <th>Totalizer Utama</th>
                <th>Totalizer Backup</th>
                <th>Insentif</th> 
            </tr>
        </thead>
        <tbody>
            @foreach($data as $i => $row)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $row['nama'] }}</td>
                <td>{{ $row['role'] }}</td>
                <td>{{ $row['tanggal'] }}</td>
                <td>{{ $row['status'] }}</td>
                <td>{{ $row['jam_masuk'] }}</td>
                <td>{{ $row['jam_istirahat'] }}</td>
                <td>{{ $row['jam_kembali'] }}</td>
                <td>{{ $row['jam_pulang'] }}</td>
                <td>{{ $row['nozle'] }}</td>
                <td>{{ $row['produk'] }}</td>
                <td>{{ number_format($row['totalizer_utama'], 0) }}</td>
                <td>{{ number_format($row['totalizer_backup'], 0) }}</td>
                <td>Rp {{ number_format($row['insentif'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>