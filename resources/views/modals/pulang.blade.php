
<div class="modal fade" id="PulangModal" tabindex="-1" aria-labelledby="PulangModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="PulangModalLabel">Form Pulang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <h3>Absen Pulang</h3>
                        <form action="{{ route('absensi.pulang', $absenTerakhir->id ?? 0) }}" method="POST">

                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="TotalizerAkhir" class="form-label">Totalizer Akhir</label>
                                <input type="number" step="0.01" name="TotalizerAkhir" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-danger">Absen Pulang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
   </div>  