<div class="modal fade" id="EditNozle" tabindex="-1" aria-labelledby="EditNozleLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="EditNozleLabel">Form Edit Nozle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <h3>Edit Nozle</h3>
                        <form action="" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="TotalizerAkhir" class="form-label">NamaNozle</label>
                                <input type="text" name="NamaNozle" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="TotalizerAkhir" class="form-label">Pulau</label>
                                <input type="text" name="Pulau" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-warning">Perbarui</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
   </div>  