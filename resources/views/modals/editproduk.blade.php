<div class="modal fade" id="EditProduk" tabindex="-1" aria-labelledby="EditProdukLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="EditProdukLabel">Form Edit Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <h3>Edit Produk</h3>
                        <form action="{{ route('produk.edit') }}" method="POST" id="editprodukForm">
                            @csrf
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Produk</label>
                                <input type="text" id="NamaProduk" name="NamaProduk" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="harga" class="form-label">Harga Per Liter</label>
                                <input type="text" id="harga" name="HargaPerLiter" class="form-control" required>
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
    const EditProduk = document.getElementById('EditProduk');
    
    EditProduk.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const nama = button.getAttribute('data-nama');
        const harga = button.getAttribute('data-harga');

        EditProduk.querySelector('#NamaProduk').value = nama;
        EditProduk.querySelector('#harga').value = harga;

        // Set action route dengan id
        const form = document.getElementById('editprodukForm');
        form.action = `/edit/produk`;
    });
});

</script>