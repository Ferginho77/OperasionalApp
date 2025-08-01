<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kehadiran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .title {
            text-align: center;
            margin-bottom: 10px;
        }
        .title h3 {
            margin: 0;
            font-size: 18px;
        }
        .subtitle {
            text-align: center;
            margin-bottom: 20px;
        }
        .info {
            margin-bottom: 10px;
            font-size: 12px;
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
    <!-- Judul -->
    <div class="title">
        <h3>DAFTAR HADIR</h3>
    </div>
    <div class="subtitle">
        SPBU: {{ $spbu->Nama ?? $spbu->NomorSPBU }} <br>
    </div>

    <!-- Tabel Kehadiran -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Karyawan</th>
                <th>Jabatan</th>
                <th>Waktu Masuk</th>
                <th>Waktu Keluar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kehadirans as $index => $k)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $k->karyawan->Nama ?? '-' }}</td>
                    <td>{{ $k->karyawan->Role ?? '-' }}</td>
                    <td>{{ $k->WaktuMasuk }}</td>
                    <td>{{ $k->WaktuPulang }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
