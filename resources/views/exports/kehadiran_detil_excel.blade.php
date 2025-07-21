<table>
    <thead>
        <tr>
            <th>No.</th>
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
