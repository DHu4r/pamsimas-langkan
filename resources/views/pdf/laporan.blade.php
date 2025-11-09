<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        /* ==== Ukuran kertas dan margin cetak ==== */
        @page {
            size: A4;
            margin: 15mm;
        }

        /* ==== Layout umum ==== */
        body {
            font-family: "Calibri", sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            background: #fff;
        }

        .page {
            width: 100%;
            min-height: 100%;
            box-sizing: border-box;
        }

        /* ==== Tampilan tabel ==== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #444;
            padding: 5px 6px;
            text-align: center;
        }

        th {
            background: #FFFC00;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        .td_belum_cata {
            background-color: #F28772;
        }

        h2, h4 {
            text-align: center;
            margin: 2px 0;
        }

        /* ==== Warna total ==== */
        .total-row {
            background-color: #5AB2FA;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="page">
        <h2>PAMSIMAS DESA ADAT LANGKAN</h2>
        <h2>REKAPITULASI PEMAKAIAN AIR PELANGGAN</h2>
        <h4>Periode: {{ $laporan->nama_bulan }} {{ $laporan->periode_tahun }}</h4>

        @php
            $total_meter_awal = 0;
            $total_meter_akhir = 0;
            $total_konsumsi = 0;
            $total_bayar = 0;
        @endphp

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Komplek</th>
                    <th>Meter Awal</th>
                    <th>Meter Akhir</th>
                    <th>Pemakaian (m³)</th>
                    <th>Harga per Kubik</th>
                    <th>Jumlah Pembayaran (Rp)</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pelanggans as $pelanggan)
                    @php
                        $data = $penggunaan_airs->firstWhere('penggunas_id', $pelanggan->id);
                    @endphp
                    <tr>
                    @if ($data)
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $pelanggan->nama }}</td>
                        <td>{{ $pelanggan->komplek }}</td>
                        <td>{{ $data->meter_baca_awal }}</td>
                        <td>{{ $data->meter_baca_akhir }}</td>
                        <td>{{ $data->konsumsi }}</td>
                        <td>{{ number_format(config('tarif.harga_per_m3'), 0, ".", ",") }}</td>
                        <td>
                            {{ $data->pembayarans ? number_format($data->pembayarans->jumlah, 0, ".", ",") : '-' }}
                        </td>
                        <td>-</td>

                        @php
                            $total_meter_awal += $data->meter_baca_awal;
                            $total_meter_akhir += $data->meter_baca_akhir;
                            $total_konsumsi += $data->konsumsi;
                            $total_bayar += $data->pembayarans->jumlah ?? 0;
                        @endphp
                    @else
                        <td class="td_belum_cata">{{ $loop->iteration }}</td>
                        <td class="td_belum_cata">{{ $pelanggan->nama }}</td>
                        <td class="td_belum_cata">{{ $pelanggan->komplek }}</td>
                        <td class="td_belum_cata">-</td>
                        <td class="td_belum_cata">-</td>
                        <td class="td_belum_cata">-</td>
                        <td class="td_belum_cata">{{ number_format(config('tarif.harga_per_m3'), 0, ".", ",") }}</td>
                        <td class="td_belum_cata">-</td>
                        <td class="td_belum_cata">Belum Dicatat</td>
                    @endif
                    </tr>
                @endforeach

                <tr class="total-row">
                    <td></td>
                    <td colspan="2">TOTAL</td>
                    <td>{{ $total_meter_awal }}</td>
                    <td>{{ $total_meter_akhir }}</td>
                    <td>{{ $total_konsumsi }}</td>
                    <td></td>
                    <td>{{ number_format($total_bayar, 0, ".", ",") }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
