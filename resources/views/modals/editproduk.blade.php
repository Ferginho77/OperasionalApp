<div class="modal fade" id="EditProduk" tabindex="-1" aria-labelledby="EditProdukLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="EditProdukLabel">Form Edit Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <h3>Edit Karyawan</h3>
                        <form action="" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="TotalizerAkhir" class="form-label">Nama</label>
                                <input type="text" name="Nama" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="TotalizerAkhir" class="form-label">NIP</label>
                                <input type="text" name="NIP" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-warning">Perbarui</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
   </div>  