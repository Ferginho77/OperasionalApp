<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Shift</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header p {
            margin: 3px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .mb-3 {
            margin-bottom: 15px;
        }

        .pulau-header {
            background-color: #d0d0d0;
            padding: 8px;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 14px;
        }

        .product-header {
            background-color: #e0e0e0;
            padding: 5px;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .total-row {
            font-weight: bold;
            background-color: #f8f8f8;
        }

        .footer {
            margin-top: 20px;
            border-top: 1px solid #000;
            padding-top: 10px;
            text-align: center;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>LAPORAN SHIFT</h2>
        <p>{{ $spbu->NamaSPBU }}</p>
        <p>PERIODE: {{ strtoupper($bulan) }} {{ $tahun }}</p>
    </div>

    <div style="margin-bottom: 15px;">
        <div style="display: inline-block; width: 45%; vertical-align: top;">
            <p style="margin: 0; padding: 2px 0;"><strong>HARI/TGL:</strong> {{ now()->translatedFormat('l, d F Y') }}
            </p>
        </div>
        <div style="display: inline-block; width: 45%; vertical-align: top; text-align: right;">
            <p style="margin: 0; padding: 2px 0;"><strong>SHIFT:</strong> {{ $shift }}</p>
        </div>
    </div>

    @foreach ($dataPerPulau as $pulauData)
        <div class="mb-3">
            @foreach ($pulauData['produk'] as $produkData)
                <div class="mb-3">
                    <div class="product-header">
                        {{ strtoupper($produkData['produk']->NamaProduk) }}: Rp.
                        {{ number_format($produkData['produk']->HargaPerLiter, 0, ',', '.') }} / LITER
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th width="15%">NOZZLE</th>
                                <th width="25%">TELLER AWAL</th>
                                <th width="25%">TELLER AKHIR</th>
                                <th width="15%">JUMLAH (LITER)</th>
                                <th width="20%">RP.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produkData['penjualans'] as $penjualan)
                                <tr>
                                    <td class="text-center">{{ $penjualan->nozle->NamaNozle ?? '-' }}</td>
                                    <td class="text-right">{{ number_format($penjualan->TelerAwal, 2, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($penjualan->TelerAkhir, 2, ',', '.') }}
                                    </td>
                                    <td class="text-right">{{ number_format($penjualan->Jumlah, 2, ',', '.') }}</td>
                                    <td class="text-right">Rp
                                        {{ number_format($penjualan->JumlahRupiah, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr class="total-row">
                                <td colspan="3" class="text-right"><strong>TOTAL
                                        {{ strtoupper($produkData['produk']->NamaProduk) }}</strong></td>
                                <td class="text-right">
                                    <strong>{{ number_format($produkData['total_liter'], 2, ',', '.') }}</strong></td>
                                <td class="text-right"><strong>Rp
                                        {{ number_format($produkData['total_rupiah'], 0, ',', '.') }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endforeach

            <table>
                <tr class="total-row">
                    <td colspan="3" class="text-right"><strong>TOTAL PULAU
                            {{ $pulauData['pulau']->NamaPulau }}</strong></td>
                    <td class="text-right">
                        <strong>{{ number_format($pulauData['total_pulau_liter'], 2, ',', '.') }}</strong></td>
                    <td class="text-right"><strong>Rp
                            {{ number_format($pulauData['total_pulau_rupiah'], 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>
    @endforeach

    <div class="mb-3">
        <table>
            <tr class="total-row">
                <td width="80%"><strong>TOTAL PENDAPATAN</strong></td>
                <td width="20%" class="text-right"><strong>Rp
                        {{ number_format($totalPendapatan, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>{{ $spbu->NamaSPBU }} [{{ $spbu->NomorSPBU }}]</p>
        <p>{{ $tanggal }}</p>
    </div>
</body>

</html>
