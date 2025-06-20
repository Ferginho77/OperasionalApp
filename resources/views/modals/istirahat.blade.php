<div class="modal fade" id="IstirahatModal" tabindex="-1" aria-labelledby="IstirahatModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="IstirahatModalLabel">Form Absen Istirahat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <h3>Absen Istirahat</h3>

                        <form action="{{ route('absensi.istirahat', $absenTerakhir->id ?? 0) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="TotalizerAkhir" class="form-label">Totalizer Akhir</label>
                                <input type="number" step="0.01" name="TotalizerAkhir" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-warning">Absen Istirahat</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
   </div>  