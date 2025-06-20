
<div class="modal fade" id="PindahModal" tabindex="-1" aria-labelledby="PindahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="PindahModalLabel">Form Pindah Nozle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                            <h3>Pindah Nozzle</h3>

                        <form action="{{ route('absensi.pindah', $absenTerakhir->id ?? 0) }}" method="POST">

                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="NozleId" class="form-label">Nozzle Tujuan</label>
                                    <select name="NozleId" class="form-select">
                                        @foreach ($nozle as $n)
                                            <option value="{{ $n->id }}">{{ $n->NamaNozle }} (Pulau {{ $n->Pulau }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="TotalizerAwal" class="form-label">Totalizer Awal</label>
                                    <input type="number" step="0.01" name="TotalizerAwal" class="form-control" required>
                                </div>

                                <button type="submit" class="btn btn-info">Pindah Nozzle</button>
                            </form>
                        </div>
                </div>
            </div>
        </div>
   </div>