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
                        <form action="{{ route('karyawan.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" name="Nama" class="form-control" required>
                            </div>                         
                                <input type="text" name="Role" class="form-control" value="Operator" hidden>                          
                            <div class="mb-3">
                                <label class="form-label">NIP</label>
                                <input type="text" name="Nip" class="form-control" required>
                            </div>
                            @foreach($SpbuId as $s)
                                <input type="text" name="NomorSPBU" class="form-control" value="{{ $s->SpbuId }}" hidden>
                            @endforeach
                                
                            <button type="submit" class="btn btn-outline-primary">Tambah</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
   </div>  
</div>
