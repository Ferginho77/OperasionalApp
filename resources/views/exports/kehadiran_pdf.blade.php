<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kehadiran Karyawan</title>
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
    <h2 style="margin-top: 20px;">Data Kehadiran Karyawan</h2>

       <table class="table table-bordered mt-2">
        <thead>
            <tr>
                <th>No</th>
                <th>Karyawan</th>
                <th>Waktu Masuk</th>
                <th>Waktu Pulang</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kehadirans as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->Karyawan->Nama }}</td>
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
</body>
</html>