<div class="modal fade" id="KembaliModal" tabindex="-1" aria-labelledby="KembaliModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('absensi.kembali') }}" method="POST" id="kembaliForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Kembali ke Nozzle Awal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Pilih Absensi</label>
            <select name="id" id="absensiIdKembali" class="form-select" required>
              @foreach($absensi as $a)
                <option value="{{ $a->id }}">{{ $a->karyawan->Nama }} - Nozzle {{ $a->nozle->NamaNozle ?? '' }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Totalizer Awal Setelah Kembali</label>
            <input type="number" step="0.01" name="TotalizerAwal" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>

