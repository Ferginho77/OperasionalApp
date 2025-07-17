<!DOCTYPE html>
<html>
<head>
    <title>Data Absensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>Data Absensi</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Karyawan ID</th>
                <th>Waktu Masuk</th>
                <th>Waktu Pulang</th>
                <th>SPBU ID</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kehadiran as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->KaryawanId }}</td>
                <td>{{ $item->WaktuMasuk }}</td>
                <td>{{ $item->WaktuPulang ?? '-' }}</td>
                <td>{{ $item->SpbuId }}</td>
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
