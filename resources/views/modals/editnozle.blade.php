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
                        <form id="EditNozleForm" method="POST">
                            @csrf
                            <input type="text" id="IdNozle" name="id" hidden>
                            <div class="mb-3">
                                <label for="TotalizerAkhir" class="form-label">NamaNozle</label>
                                <input type="text" id="nama" name="NamaNozle" class="form-control" required>
                            </div>
                                <input type="text" id="pulau" name="PulauId" class="form-control" hidden>
                            <button type="submit" class="btn btn-warning">Perbarui</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
   </div>  
   <script>
   document.addEventListener('DOMContentLoaded', function () {
    const EditNozle = document.getElementById('EditNozle');
    
    EditNozle.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const nama = button.getAttribute('data-nama');
        const pulau = button.getAttribute('data-pulau');
        const IdNozle = button.getAttribute('data-id');

        EditNozle.querySelector('#nama').value = nama;
        EditNozle.querySelector('#pulau').value = pulau;
        EditNozle.querySelector('#IdNozle').value = IdNozle;

        // Set action route dengan id
        const form = document.getElementById('EditNozleForm');
        form.action = `/nozle/update`;
    });
});

</script>