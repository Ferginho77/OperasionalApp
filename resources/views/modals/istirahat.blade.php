<div class="modal fade" id="IstirahatModal" tabindex="-1" aria-labelledby="IstirahatModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" id="istirahatForm" action="{{ route('absensi.istirahat') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Absen Istirahat</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="absensiIdIstirahat" class="form-label">Pilih Absensi</label>
            <select id="absensiIdIstirahat" name="id" class="form-select" required>
              @foreach($absensi as $a)
                <option value="{{ $a->id }}">{{ $a->karyawan->Nama }} - Nozzle {{ $a->nozle->NamaNozle ?? '' }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="TotalizerAkhirIstirahat" class="form-label">Totalizer Akhir</label>
            <input type="number" step="0.01" name="TotalizerAkhir" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-warning">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>
