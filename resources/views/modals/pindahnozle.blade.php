<div class="modal fade" id="PindahModal" tabindex="-1" aria-labelledby="PindahModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('absensi.mulaiBackup') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Mulai Backup Nozzle</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="absensiIdBackup" class="form-label">Absensi yang Di-backup</label>
            <select name="AbsensiId" class="form-select" required>
              @foreach($absensi as $a)
                <option value="{{ $a->id }}">{{ $a->karyawan->Nama }} - Nozzle {{ $a->nozle->NamaNozle ?? '' }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="BackupOperatorId" class="form-label">Operator Backup</label>
            <select name="BackupOperatorId" class="form-select" required>
              @foreach($karyawan as $k)
                <option value="{{ $k->id }}">{{ $k->Nama }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Totalizer Awal</label>
            <input type="number" step="0.01" name="TotalizerAwal" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-info">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>
