<div>
    <div class="modal fade" id="TambahKaryawan" tabindex="-1" aria-labelledby="TambahKaryawanLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="TambahKaryawanLabel">Form Tambah Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <h3>Tambah Karyawan</h3>
                        <form action="{{ route('karyawan.store') }}" method="POST"  enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" name="Nama" class="form-control" required>
                            </div>                         
                                <input type="text" name="Role" class="form-control" value="Operator" hidden>                          
                            <div class="mb-3">
                                <label class="form-label">NIK</label>
                                <input type="text" name="Nip" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jabatan</label>
                                <select name="Role" id="" class="form-select" required> 
                                    <option value="Operator">Operator</option>
                                    <option value="Pengawas">Pengawas</option>
                                    <option value="OB">OB</option>
                                    <option value="Security">Security</option>
                                </select>
                             <div class="mb-2">
                                    <label for="cv" class="form-label">Upload CV (PDF)</label>
                                    <input type="file" name="Cv" id="Cv" class="form-control" accept="application/pdf" required>
                                </div>
                                <div class="mb-2">
                                    <label for="FilePribadi" class="form-label">File Pribadi (PDF)</label>
                                    <input type="file" name="FilePribadi" id="FilePribadi" class="form-control" accept="application/pdf" required>
                                </div>
                            <button type="submit" class="btn btn-outline-primary">Tambah</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
   </div>  
</div>
