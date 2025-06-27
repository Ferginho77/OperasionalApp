<div>
    <div class="modal fade" id="TambahNozle" tabindex="-1" aria-labelledby="TambahNozleLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="TambahNozleLabel">Form Tambah Nozle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <h3>Tambah Nozle</h3>
                        <form action="{{ route('nozle.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nama Nozle</label>
                                <input type="text" name="NamaNozle" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Pulau</label>
                                <select name="PulauId" class="form-select" required>
                                    <option value="">-- Pilih Pulau --</option>
                                    @forelse($pulau as $p)
                                        <option value="{{ $p->id }}">{{ $p->NamaPulau }}</option>
                                    @empty
                                        <option value="">Pulau belum tersedia</option>
                                    @endforelse
                                </select>
                            </div>
                            <input type="hidden" name="SpbuId" value="{{ $SpbuId }}">
                            <button type="submit" class="btn btn-outline-primary">Tambah</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>
