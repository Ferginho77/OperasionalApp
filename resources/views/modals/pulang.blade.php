<div class="modal fade" id="PulangModal" tabindex="-1" aria-labelledby="PulangModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('absensi.pulang') }}" method="POST" id="pulangForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Absen Pulang</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Pilih Absensi</label>
            <select name="id" id="absensiIdPulang" class="form-select" required>
              @foreach($absensi as $a)
                <option value="{{ $a->id }}">{{ $a->karyawan->Nama }} - Nozzle {{ $a->nozle->NamaNozle ?? '' }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Totalizer Akhir</label>
            <input type="number" step="0.01" name="TotalizerAkhir" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>
