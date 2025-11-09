<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekening Air</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; border: 1px solid #000; }
        p {margin:3px;}
        .tittle {
            text-align: center;
            margin: 0;
        }
    </style>
</head>
    <body>
        {{-- Rangkap 1 --}}
        <h3 class="tittle">PAMSIMAS II 2019</h3>
        <h3 class="tittle">"TIRTA HARUM"</h3>
        <h3 class="tittle">DESA ADAT LANGKAN</h3>
        <p><strong>Nama :</strong> {{ $penggunaanAir->penggunas->nama }}</p>
        <p><strong>Komplek :</strong> {{ $penggunaanAir->penggunas->komplek }}</p>
        <p><strong>No. Hp :</strong> {{ $penggunaanAir->penggunas->no_hp }}</p>
        <p><strong>Periode :</strong> {{ $penggunaanAir->nama_bulan }} {{ $penggunaanAir->periode_tahun }}</p>

        <table>
            <thead>
                <tr>
                    <th>Meteran Akhir Lalu</th>
                    <th>Meteran Akhir Sekarang</th>
                    <th>Jumlah Pemakaian (m³)</th>
                    <th>Harga / m³</th>
                    @if ($rolePembayar === 'Mitra Pembayaran')
                        <th>Biaya admin transfer</th>                      
                    @endif
                    <th>Total</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $penggunaanAir->meter_baca_awal }}</td>
                    <td>{{ $penggunaanAir->meter_baca_akhir }}</td>
                    <td>{{ $penggunaanAir->konsumsi }}</td>
                    <td>{{ number_format(config('tarif.harga_per_m3'), 0, ',', '.') }}</td>
                    @if ($rolePembayar === 'Mitra Pembayaran')
                        <td>Rp. 5,000</td>
                        <td>
                            {{ number_format(($penggunaanAir->konsumsi * config('tarif.harga_per_m3')) + 5000) }}
                        </td>                      
                    @endif
                    <td>
                        {{ number_format($penggunaanAir->konsumsi * config('tarif.harga_per_m3')) }}</td>
                    <td>
                    </td>
                </tr>
            </tbody>
        </table>
        <p><strong>Status Pembayaran : </strong>
            @if ($penggunaanAir->sudah_bayar)
                Lunas
            @else
                <span>Belum Lunas</span>
            @endif
        </p>
        @if ($penggunaanAir->pembayarans->metode === 'transfer')
            <p><strong>Metode Pembayaran : </strong> {{ $penggunaanAir->pembayarans->metode }} dari Rekening {{ $penggunaanAir->pembayarans->nama_bank }} a/n {{ $penggunaanAir->pembayarans->nama_rekening }} </p>
        @else
            <p><strong>Metode Pembayaran : </strong> {{ $penggunaanAir->pembayarans->metode }} di Pengurus a/n {{ $penggunaanAir->pembayarans->dibayarOleh->nama }} </p>
        @endif
        <p><strong>Tanggal Pembayaran : </strong> {{ $penggunaanAir->pembayarans->created_at_indo }}</p>
        <hr style="margin-top:20px;">
        <p style="font-size: 12px; text-align:right;">
            Dokumen ini dicetak oleh {{ $authUser->nama }} pada {{ $tanggalCetak }}
        </p>
    </body>
</html>
