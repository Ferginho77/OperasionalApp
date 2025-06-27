<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="/img/LogoPertamina.png"> 
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Laman Kehadiran</title>
</head>
<body>
    <div class="container mt-5">
    <div class="card m-3">
        <h3>Form Absensi Karyawan</h3>

        <form action="">
             <div class="form-group">
                <label for="karyawan_id">Nama Karyawan</label>
                <select id="karyawan_id" class="form-select" onchange="updateRole()">
                    <option value="">-- Pilih --</option>
                    @foreach($karyawan as $k)
                        <option value="{{ $k->id }}" data-role="{{ $k->Role }}" data-nip="{{ $k->Nip }}">{{ $k->Nama }}</option>
                    @endforeach
                </select>
        </div>

        <div class="form-group mt-3">
            <label for="role">Role</label>
            <input type="text" id="role" class="form-control" readonly>
        </div>

        <button class="btn btn-primary mt-3" onclick="mulaiFaceID()">Mulai Face ID</button>
    </div>
        </form>
       

    <script src="https://cdn.faceio.net/fio.js"></script>
    <script>
        const faceio = new faceIO("YOUR_PUBLIC_APPLICATION_ID"); // ganti dengan ID asli FaceIO

        function updateRole() {
            const select = document.getElementById('karyawan_id');
            const selected = select.options[select.selectedIndex];
            const role = selected.getAttribute('data-role');
            document.getElementById('role').value = role || '';
        }

        function mulaiFaceID() {
            const karyawanId = document.getElementById('karyawan_id').value;

            if (!karyawanId) {
                alert("Silakan pilih nama karyawan terlebih dahulu.");
                return;
            }

            faceio.authenticate({
                locale: "auto"
            }).then(userInfo => {
                fetch('/absensi/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        karyawan_id: karyawanId,
                        facial_id: userInfo.facialId
                    })
                })
                .then(res => res.json())
                .then(data => alert(data.message));
            }).catch(err => {
                console.error(err);
                alert("Gagal autentikasi wajah.");
            });
        }
    </script>
</div>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>



