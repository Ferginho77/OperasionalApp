<div class="modal fade" id="SelesaiBackupModal" tabindex="-1" aria-labelledby="SelesaiBackupModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('absensi.selesaiBackup') }}" method="POST" id="selesaiBackupForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Selesai Backup Nozzle</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="backupSessionId" class="form-label">Pilih Backup Session</label>
          <select id="backupSessionId" name="id" class="form-select" required>
              @foreach($backupSessions as $b)
                  @if($b->backupOperator)
                      <option value="{{ $b->id }}">
                          {{ $b->backupOperator->Nama }}
                          @if($b->absensi && $b->absensi->nozle)
                              di Nozzle {{ $b->absensi->nozle->NamaNozle }}
                          @endif
                      </option>
                  @endif
              @endforeach
          </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Totalizer Akhir</label>
            <input type="number" step="0.01" name="TotalizerAkhir" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-secondary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>

