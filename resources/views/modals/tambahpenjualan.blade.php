{{-- CSS untuk styling validasi --}}
<style>
    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 0.25rem;
    }

    .format-info {
        background: #e3f2fd;
        border: 1px solid #90caf9;
        border-radius: 0.375rem;
        padding: 0.5rem;
        margin-bottom: 1rem;
        font-size: 0.875em;
        color: #1565c0;
    }

    .is-invalid {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
    }

    .is-valid {
        border-color: #198754 !important;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25) !important;
    }

    .format-example {
        font-family: 'Courier New', monospace;
        font-weight: bold;
    }

    .alert-format {
        animation: shake 0.5s;
    }

    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        25% {
            transform: translateX(-5px);
        }

        75% {
            transform: translateX(5px);
        }
    }

    .calculation-info {
        background: #e8f5e9;
        border: 1px solid #81c784;
        border-radius: 0.375rem;
        padding: 0.5rem;
        margin-bottom: 1rem;
        font-size: 0.875em;
        color: #2e7d32;
    }

    .calculation-result {
        background: #fff3cd;
        border: 1px solid #ffdf7e;
        border-radius: 0.375rem;
        padding: 0.5rem;
        margin-top: 1rem;
        font-size: 0.875em;
        color: #856404;
    }
</style>

<div class="modal fade" id="tambahPenjualanModal" tabindex="-1" aria-labelledby="tambahPenjualanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('penjualan.store') }}" method="POST" id="formPenjualan">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Penjualan</h5>
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
                            <select name="NozzelId" id="NozzelId" class="form-select" required>
                                <option value="">-- Pilih Nozzel --</option>
                                @foreach ($nozzles as $nozzle)
                                    <option value="{{ $nozzle->id }}" data-pulau-nama="{{ $nozzle->pulau->NamaPulau }}" data-pulau="{{ $nozzle->PulauId }}">
                                        {{ $nozzle->NamaNozle }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Pulau (otomatis, readonly) --}}
                        <div class="col-md-6">
                            <label class="form-label">Pulau</label>
                            <input type="text" id="PulauNama" class="form-control" readonly
                                style="background:#e9ecef;">
                            <input type="hidden" name="PulauId" id="PulauId">
                        </div>

                        {{-- Produk --}}
                        <div class="col-md-6">
                            <label class="form-label">Produk</label>
                            <select name="ProdukId" id="ProdukId" class="form-select" required>
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
                            <input type="text" id="HargaPerLiter" class="form-control" readonly
                                style="background:#e9ecef;">
                        </div>

                        {{-- Teler Awal --}}
                        <div class="col-md-6">
                            <label class="form-label">Teler Awal</label>
                            <input type="text" name="TelerAwal" id="TelerAwal" class="form-control number-format"
                                required>
                            <div class="invalid-feedback" id="TelerAwal-error"></div>
                        </div>

                        {{-- Teler Akhir --}}
                        <div class="col-md-6">
                            <label class="form-label">Teler Akhir</label>
                            <input type="text" name="TelerAkhir" id="TelerAkhir" class="form-control number-format"
                                required>
                            <div class="invalid-feedback" id="TelerAkhir-error"></div>
                        </div>

                        {{-- Jumlah --}}
                        <div class="col-md-6">
                            <label class="form-label">Jumlah (Liter)</label>
                            <input type="text" name="Jumlah" id="Jumlah" class="form-control number-format"
                                readonly style="background:#e9ecef;">
                            <div class="invalid-feedback" id="Jumlah-error"></div>
                        </div>

                        {{-- Jumlah Rupiah --}}
                        <div class="col-md-6">
                            <label class="form-label">Jumlah Rupiah</label>
                            <input type="text" name="JumlahRupiah" id="JumlahRupiah"
                                class="form-control number-format" readonly style="background:#e9ecef;">
                        </div>
                    </div>

                    {{-- Hasil Perhitungan --}}
                    <div class="calculation-result mt-3 d-none" id="calculationResult">
                        <i class="fas fa-check-circle me-1"></i>
                        <span id="resultText"></span>
                    </div>

                    {{-- Alert untuk format error umum --}}
                    <div class="alert alert-danger mt-3 d-none" id="formatAlert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Format Salah!</strong> Gunakan format Indonesia: <span
                            class="format-example">10.100.200,20</span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const nozzelSelect = document.getElementById("NozzelId");
        const pulauInput = document.getElementById("PulauId");
        const pulauNama = document.getElementById("PulauNama");
        const produkSelect = document.getElementById("ProdukId");
        const hargaPerLiterInput = document.getElementById("HargaPerLiter");
        const formatAlert = document.getElementById("formatAlert");
        const calculationResult = document.getElementById("calculationResult");
        const resultText = document.getElementById("resultText");

        const tAwal = document.getElementById("TelerAwal");
        const tAkhir = document.getElementById("TelerAkhir");
        const jumlah = document.getElementById("Jumlah");
        const jumlahRupiah = document.getElementById("JumlahRupiah");
        let hargaPerLiter = 0;

        // === Pulau otomatis ===
        nozzelSelect.addEventListener("change", function() {
            const selected = this.options[this.selectedIndex];
            pulauInput.value = selected.getAttribute("data-pulau") ?? "";
            pulauNama.value = selected.getAttribute("data-pulau-nama") ?? "";
        });

        // === Harga produk otomatis dari data-harga ===
        produkSelect.addEventListener("change", function() {
            const selected = this.options[this.selectedIndex];
            hargaPerLiter = parseFloat(selected.getAttribute("data-harga")) || 0;
            hargaPerLiterInput.value = formatNumber(hargaPerLiter);
            hitungSemua();
        });

        // === Validasi Format Indonesia ===
        function isValidIndonesianFormat(value) {
            if (!value || value.trim() === "") return true;
            const pattern = /^-?\d{1,3}(\.\d{3})*(,\d{1,2})?$/;
            return pattern.test(value);
        }

        function showFormatError(inputElement, message = "Format harus seperti: 10.100.200,20") {
            const errorDiv = document.getElementById(inputElement.id + "-error");
            inputElement.classList.add("is-invalid");
            inputElement.classList.remove("is-valid");
            if (errorDiv) errorDiv.textContent = message;
            formatAlert.classList.remove("d-none");
            setTimeout(() => {
                formatAlert.classList.add("d-none");
            }, 5000);
        }

        function hideFormatError(inputElement) {
            const errorDiv = document.getElementById(inputElement.id + "-error");
            inputElement.classList.remove("is-invalid");
            inputElement.classList.add("is-valid");
            if (errorDiv) errorDiv.textContent = "";
            formatAlert.classList.add("d-none");
        }

        function validateFormat(inputElement) {
            const value = inputElement.value.trim();
            if (!value) {
                hideFormatError(inputElement);
                return true;
            }
            if (!isValidIndonesianFormat(value)) {
                showFormatError(inputElement);
                return false;
            }
            hideFormatError(inputElement);
            return true;
        }

        // === Helper angka ===
        function parseNumber(value) {
            if (!value) return 0;
            return parseFloat(value.replace(/\./g, "").replace(",", "."));
        }

        function formatNumber(value) {
            if (isNaN(value)) return "";
            return value.toLocaleString("id-ID", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // === Hitung semua nilai ===
        function hitungSemua() {
            // Validasi input
            if (!validateFormat(tAwal) || !validateFormat(tAkhir)) return;

            const awal = parseNumber(tAwal.value);
            const akhir = parseNumber(tAkhir.value);

            if (!tAwal.value || !tAkhir.value) {
                jumlah.value = "";
                jumlahRupiah.value = "";
                calculationResult.classList.add("d-none");
                return;
            }

            // Hitung jumlah liter
            const jmlLiter = akhir - awal;
            jumlah.value = formatNumber(jmlLiter);

            // Hitung jumlah rupiah
            if (hargaPerLiter > 0) {
                const jmlRupiah = jmlLiter * hargaPerLiter;
                jumlahRupiah.value = formatNumber(jmlRupiah);

                // Tampilkan hasil perhitungan
                calculationResult.classList.remove("d-none");
                resultText.innerHTML = `
                    <strong>Hasil Perhitungan:</strong><br>
                    Teler Akhir (${formatNumber(akhir)}) - Teler Awal (${formatNumber(awal)}) = 
                    Jumlah Liter (${formatNumber(jmlLiter)})<br>
                    Jumlah Liter (${formatNumber(jmlLiter)}) × Harga (${formatNumber(hargaPerLiter)}) = 
                    Jumlah Rupiah (${formatNumber(jmlRupiah)})
                `;
            } else {
                jumlahRupiah.value = "";
                calculationResult.classList.add("d-none");
            }
        }

        // === Event Listeners ===
        tAwal.addEventListener("input", hitungSemua);
        tAkhir.addEventListener("input", hitungSemua);

        [tAwal, tAkhir].forEach(input => {
            input.addEventListener("blur", () => validateFormat(input));
        });

        // Validasi sebelum submit
        document.getElementById("formPenjualan").addEventListener("submit", function(e) {
            let hasError = false;
            [tAwal, tAkhir].forEach(input => {
                if (input.value && !validateFormat(input)) hasError = true;
            });

            if (hasError) {
                e.preventDefault();
                showFormatError(tAwal, "Periksa kembali format semua angka!");
                document.querySelector(".is-invalid")?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                return false;
            }

            // Pastikan nilai akhir lebih besar dari nilai awal
            const awal = parseNumber(tAwal.value);
            const akhir = parseNumber(tAkhir.value);

            if (akhir < awal) {
                e.preventDefault();
                showFormatError(tAkhir, "Teler akhir harus lebih besar dari teler awal!");
                tAkhir.focus();
                return false;
            }

            return true;
        });
    });
</script>  