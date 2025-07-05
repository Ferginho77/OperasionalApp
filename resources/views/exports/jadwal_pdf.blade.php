<!-- filepath: resources/views/exports/jadwal_pdf.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Operator</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            color: #222;
        }
        h2 {
            text-align: center;
            margin-bottom: 18px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        th, td {
            border: 1px solid #888;
            padding: 7px 10px;
            text-align: left;
        }
        th {
            background: #e3f2fd;
        }
        tr:nth-child(even) {
            background: #f7fafd;
        }
    </style>
</head>
<body>
     <div style="position: relative; height: 100px;">
        <img src="{{ public_path('img/LogoPertamina.png') }}" alt="Logo Pertamina" width="165" height="100" class="logo-kanan">
    </div>
    <h2>Jadwal Operator</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Operator</th>
                <th>Tanggal</th>
                <th>Shift</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jadwals as $i => $jadwal)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $jadwal->karyawan->Nama ?? '-' }}</td>
                    <td>{{ $jadwal->Tanggal }}</td>
                    <td>{{ ucfirst($jadwal->Shift) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>