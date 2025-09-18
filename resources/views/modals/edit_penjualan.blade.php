<div class="modal fade" id="editPenjualanModal" tabindex="-1" aria-labelledby="editPenjualanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formEditPenjualan" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editPenjualanLabel">Edit Penjualan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- Info Format --}}
                    <div class="format-info">
                        <i class="fas fa-info-circle me-1"></i>
                        <strong>Format Angka:</strong> Gunakan format Indonesia <span
                            class="format-example">10.100.200,20</span>
                        <br>
                        <small>• Ribuan dipisah dengan titik (.)</small>
                        <br>
                        <small>• Desimal dipisah dengan koma (,)</small>
                    </div>
                    <div class="row g-3">
                        {{-- Nozzel --}}
                        <div class="col-md-6">
                            <label class="form-label">Nozzel</label>
                            <select name="NozzelId" id="editNozzelId" class="form-select" required>
                                <option value="">-- Pilih Nozzel --</option>
                                @foreach ($nozzles as $nozzle)
                                    <option value="{{ $nozzle->id }}" data-pulau-id="{{ $nozzle->PulauId }}"
                                        data-pulau-nama="{{ $nozzle->pulau->NamaPulau }}">
                                        {{ $nozzle->NamaNozle }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Pulau (otomatis, readonly) --}}
                        <div class="col-md-6">
                            <label class="form-label">Pulau</label>
                            <input type="text" id="editPulauNama" class="form-control" readonly
                                style="background:#e9ecef;">
                            <input type="hidden" name="PulauId" id="editPulauId">
                        </div>
                        {{-- Produk --}}
                        <div class="col-md-6">
                            <label class="form-label">Produk</label>
                            <select name="ProdukId" id="editProdukId" class="form-select" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach ($produks as $produk)
                                    <option value="{{ $produk->id }}" data-harga="{{ $produk->HargaPerLiter }}">
                                        {{ $produk->NamaProduk }} - Rp
                                        {{ number_format($produk->HargaPerLiter, 2, ',', '.') }}/L
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Harga per Liter (otomatis, readonly) --}}
                        <div class="col-md-6">
                            <label class="form-label">Harga per Liter</label>
                            <input type="text" id="editHargaPerLiter" class="form-control" readonly
                                style="background:#e9ecef;">
                        </div>
                        {{-- Teler Awal --}}
                        <div class="col-md-6">
                            <label class="form-label">Teler Awal</label>
                            <input type="text" name="TelerAwal" id="editTelerAwal" class="form-control number-format"
                                required>
                            <div class="invalid-feedback" id="editTelerAwal-error"></div>
                        </div>
                        {{-- Teler Akhir --}}
                        <div class="col-md-6">
                            <label class="form-label">Teler Akhir</label>
                            <input type="text" name="TelerAkhir" id="editTelerAkhir"
                                class="form-control number-format" required>
                            <div class="invalid-feedback" id="editTelerAkhir-error"></div>
                        </div>
                        {{-- Jumlah --}}
                        <div class="col-md-6">
                            <label class="form-label">Jumlah (Liter)</label>
                            <input type="text" name="Jumlah" id="editJumlah" class="form-control number-format"
                                readonly style="background:#e9ecef;">
                            <div class="invalid-feedback" id="editJumlah-error"></div>
                        </div>
                        {{-- Jumlah Rupiah --}}
                        <div class="col-md-6">
                            <label class="form-label">Jumlah Rupiah</label>
                            <input type="text" name="JumlahRupiah" id="editJumlahRupiah"
                                class="form-control number-format" readonly style="background:#e9ecef;">
                        </div>
                    </div>
                    {{-- Hasil Perhitungan --}}
                    <div class="calculation-result mt-3 d-none" id="editCalculationResult">
                        <i class="fas fa-check-circle me-1"></i>
                        <span id="editResultText"></span>
                    </div>
                    {{-- Alert untuk format error umum --}}
                    <div class="alert alert-danger mt-3 d-none" id="editFormatAlert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Format Salah!</strong> Gunakan format Indonesia: <span
                            class="format-example">10.100.200,20</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        // Helper functions
        function parseNumber(value) {
            if (!value) return 0;
            return parseFloat(value.replace(/\./g, "").replace(",", "."));
        }

        function formatLiter(value) {
            const numericValue = parseFloat(value);
            if (isNaN(numericValue)) return "";
            return numericValue.toLocaleString("id-ID", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function formatRupiah(value) {
            const numericValue = parseFloat(value);
            if (isNaN(numericValue)) return "";
            return "Rp " + numericValue.toLocaleString("id-ID", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // Ambil elemen modal edit
        const editModal = document.getElementById("editPenjualanModal");
        const editForm = document.getElementById("formEditPenjualan");
        const editNozzelSelect = document.getElementById("editNozzelId");
        const editProdukSelect = document.getElementById("editProdukId");
        const editTAwal = document.getElementById("editTelerAwal");
        const editTAkhir = document.getElementById("editTelerAkhir");
        const editJumlah = document.getElementById("editJumlah");
        const editJumlahRupiah = document.getElementById("editJumlahRupiah");
        const editHargaPerLiterInput = document.getElementById("editHargaPerLiter");
        const editCalculationResult = document.getElementById("editCalculationResult");
        const editResultText = document.getElementById("editResultText");
        const editPulauInput = document.getElementById("editPulauId");
        const editPulauNama = document.getElementById("editPulauNama");

        // Event listener saat modal edit akan ditampilkan
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const penjualanId = button.getAttribute('data-id');

            editForm.reset();
            editCalculationResult.classList.add("d-none");

            fetch(`/penjualan/${penjualanId}/edit`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    editForm.action = `/penjualan/${penjualanId}`;

                    // Set nilai NozzelId
                    editNozzelSelect.value = data.NozzelId;

                    // Picu event 'change' pada select Nozzel.
                    // Ini akan menjalankan event listener yang sudah ada untuk mengisi Pulau.
                    editNozzelSelect.dispatchEvent(new Event('change'));

                    // Set nilai ProdukId
                    editProdukSelect.value = data.ProdukId;

                    // Picu event 'change' pada select Produk.
                    // Ini akan menjalankan event listener yang sudah ada untuk mengisi Harga.
                    editProdukSelect.dispatchEvent(new Event('change'));

                    // Mengisi nilai Teler dan Jumlah dengan format yang benar.
                    editTAwal.value = formatLiter(data.TelerAwal);
                    editTAkhir.value = formatLiter(data.TelerAkhir);
                    editJumlah.value = formatLiter(data.Jumlah);
                    editJumlahRupiah.value = formatRupiah(data.JumlahRupiah);
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    alert('Gagal mengambil data. Silakan coba lagi.');
                });
        });

        // Event listener saat Nozzel diubah
        editNozzelSelect.addEventListener("change", function() {
            const selected = this.options[this.selectedIndex];
            editPulauInput.value = selected.getAttribute("data-pulau-id") ?? "";
            editPulauNama.value = selected.getAttribute("data-pulau-nama") ?? "";
        });

        // Event listener saat Produk diubah
        editProdukSelect.addEventListener("change", function() {
            const selected = this.options[this.selectedIndex];
            const hargaPerLiter = parseFloat(selected.getAttribute("data-harga")) || 0;
            editHargaPerLiterInput.value = formatRupiah(hargaPerLiter);
            hitungEditSemua();
        });

        // Event listener saat Teler Awal atau Akhir diubah
        editTAwal.addEventListener("input", hitungEditSemua);
        editTAkhir.addEventListener("input", hitungEditSemua);

        // Fungsi Validasi dan Perhitungan untuk Modal Edit
        function hitungEditSemua() {
            const awal = parseNumber(editTAwal.value);
            const akhir = parseNumber(editTAkhir.value);
            const selectedProduk = editProdukSelect.options[editProdukSelect.selectedIndex];
            const hargaPerLiter = parseFloat(selectedProduk.getAttribute('data-harga')) || 0;

            if (!editTAwal.value || !editTAkhir.value) {
                editJumlah.value = "";
                editJumlahRupiah.value = "";
                editCalculationResult.classList.add("d-none");
                return;
            }

            const jmlLiter = akhir - awal;
            editJumlah.value = formatLiter(jmlLiter);

            if (hargaPerLiter > 0) {
                const jmlRupiah = jmlLiter * hargaPerLiter;
                editJumlahRupiah.value = formatRupiah(jmlRupiah);
                editCalculationResult.classList.remove("d-none");
                editResultText.innerHTML = `
                    <strong>Hasil Perhitungan:</strong><br>
                    Teler Akhir (${formatLiter(akhir)}) - Teler Awal (${formatLiter(awal)}) =
                    Jumlah Liter (${formatLiter(jmlLiter)})<br>
                    Jumlah Liter (${formatLiter(jmlLiter)}) × Harga (${formatRupiah(hargaPerLiter)}) =
                    Jumlah Rupiah (${formatRupiah(jmlRupiah)})
                `;
            } else {
                editJumlahRupiah.value = "";
                editCalculationResult.classList.add("d-none");
            }
        }
    });
</script>
