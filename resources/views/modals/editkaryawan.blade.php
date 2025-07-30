<div class="modal fade" id="EditModal" tabindex="-1" aria-labelledby="EditModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="EditModalLabel">Form Edit Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <h3>Edit Karyawan</h3>
                       <form id="editKaryawanForm" method="POST">
                            @csrf
                            <input type="text" id="IdKaryawan" name="id" hidden>
                            <div class="mb-3">
                                <label for="TotalizerAkhir" class="form-label">Nama</label>
                                <input type="text" id="nama" name="Nama" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="TotalizerAkhir" class="form-label">NIK</label>
                                <input type="text" id="Nip" name="Nip" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="Role">Jabatan</label>
                                <select name="Role" id="Role" class="form-select">Jabatan
                                     <option value="Operator">Operator</option>
                                    <option value="Pengawas">Pengawas</option>
                                    <option value="OB">OB</option>
                                    <option value="Security">Security</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-warning">Perbarui</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
   </div>  

<script>
   document.addEventListener('DOMContentLoaded', function () {
    const EditModal = document.getElementById('EditModal');
    
    EditModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const nama = button.getAttribute('data-nama');
        const Nip = button.getAttribute('data-nip');
        const role = button.getAttribute('data-role');
        const IdKaryawan = button.getAttribute('data-id');

        EditModal.querySelector('#nama').value = nama;
        EditModal.querySelector('#Nip').value = Nip;
        EditModal.querySelector('#Role').value = role;
        EditModal.querySelector('#IdKaryawan').value = IdKaryawan;

        // Set action route dengan id
        const form = document.getElementById('editKaryawanForm');
        form.action = `/karyawan/update`;
    });
});

</script>