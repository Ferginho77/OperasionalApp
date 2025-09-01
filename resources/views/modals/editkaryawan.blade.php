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
                       <form id="editKaryawanForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="text" name="id" id="IdKaryawan" hidden>
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
                             <div class="mb-2">
                                <label for="cv" class="form-label">Upload CV (PDF)</label>
                                <input type="file" name="Cv" id="Cv" class="form-control" accept="application/pdf">
                                <div id="cvPreview"></div>
                            </div>

                                <div class="mb-2">
                                    <label for="FilePribadi" class="form-label">File Pribadi (PDF)</label>
                                    <input type="file" name="FilePribadi" id="FilePribadi" class="form-control" accept="application/pdf">
                                       <div id="filePreview"></div>
                                </div>
                                <div class="mb-3">
                                <label for="Role">Status</label>
                                <select name="Status" id="Status" class="form-select">Status
                                     <option value="Aktif">Aktif</option>
                                     <option value="Resign">Resign</option>
                                     <option value="PHK">PHK</option>
                                     <option value="SP">SP</option>
                                     <option value="NonAktif">NonAktif</option>
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
        const cv = button.getAttribute('data-cv');
        const filePribadi = button.getAttribute('data-filepribadi');
        const IdKaryawan = button.getAttribute('data-id');
        const status = button.getAttribute('data-status');

        EditModal.querySelector('#IdKaryawan').value = IdKaryawan;
        EditModal.querySelector('#nama').value = nama;
        EditModal.querySelector('#Nip').value = Nip;
        EditModal.querySelector('#Role').value = role;
        EditModal.querySelector('#Status').value = status;

       const cvPreview = EditModal.querySelector('#cvPreview');
        cvPreview.innerHTML = cv ? `<a href="/storage/${cv}" target="_blank">Lihat CV Lama</a>` : 'Belum ada CV';

        const filePreview = EditModal.querySelector('#filePreview');
        filePreview.innerHTML = filePribadi ? `<a href="/storage/${filePribadi}" target="_blank">Lihat File Lama</a>` : 'Belum ada File Pribadi';

        // Set action route dengan id
        const form = document.getElementById('editKaryawanForm');
        form.action = `/karyawan/update`;


    });
});

</script>