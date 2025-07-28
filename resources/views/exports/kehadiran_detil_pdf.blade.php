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
            font-size: 11px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }
        .footer {
            width: 100%;
            margin-top: 30px;
            font-size: 12px;
        }
        .footer td {
            text-align: center;
            border: none;
        }
        .keterangan {
            margin-top: 15px;
            font-size: 11px;
        }
    </style>
</head>
<body>
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
    <!-- Tanda Tangan -->
    <table class="footer">
        <tr>
            <td></td>
            <td>Mengetahui,</td>
        </tr>
        <tr>
            <td></td>
            <td>Bandung, {{ now()->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>PENGAWAS 1</td>
            <td>PENGAWAS 2</td>
        </tr>
        <tr><td height="50"></td><td></td></tr>
    </table>
</body>
</html>
