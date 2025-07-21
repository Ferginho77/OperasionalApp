<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kehadiran</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h3>Data Kehadiran - SPBU: {{ $spbu->Nama ?? $spbu->NomorSPBU }}</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Karyawan</th>
                <th>Role</th>
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
                    <td>{{ $k->WaktuKeluar }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
