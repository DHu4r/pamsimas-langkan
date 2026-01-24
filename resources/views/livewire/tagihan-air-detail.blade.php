<div>
    @if ($showModal && $penggunaan_air)
    <div class="fixed inset-0 bg-black/70 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold">Detail Tagihan Air</h2>
                    <button wire:click="closeModal" class="text-gray-600 hover:text-red-600 text-2xl">&times;</button>
                </div>

                <div class="text-sm space-y-2">
                    <div class="flex items-start text-start border-b-1 border-slate-300">
                        <p class="w-5/12"><strong>Nama</strong></p>
                        <p class="w-1/12"><strong>:</strong></p>
                        <span class="w-6/12">{{ $penggunaan_air->penggunas->nama ?? '-' }}</span>
                    </div>
                    <div class="flex items-start text-start border-b-1 border-slate-300">
                        <p class="w-5/12"><strong>Penggunaan Awal</strong></p>
                        <p class="w-1/12"><strong>:</strong></p>
                        <span class="w-6/12">{{ $penggunaan_air->meter_baca_awal }} m³</span>
                    </div>
                    <div class="flex items-start text-start border-b-1 border-slate-300">
                        <p class="w-5/12"><strong>Penggunaan Akhir</strong></p>
                        <p class="w-1/12"><strong>:</strong></p>
                        <span class="w-6/12">{{ $penggunaan_air->meter_baca_akhir }} m³</span>
                    </div>
                    <div class="flex items-start text-start border-b-1 border-slate-300">
                        <p class="w-5/12"><strong>Konsumsi</strong></p>
                        <p class="w-1/12"><strong>:</strong></p> 
                        <span class="w-6/12">{{ $penggunaan_air->konsumsi }} m³</span>
                    </div>
                    <div class="flex items-start text-start border-b-1 border-slate-300">
                        <p class="w-5/12"><strong>Tanggal Catat</strong></p>
                        <p class="w-1/12"><strong>:</strong></p> 
                        <span class="w-6/12">{{ $penggunaan_air->tanggal_catat_indo }}</span>
                    </div>
                    <div class="flex items-start text-start border-b-1 border-slate-300">
                        <p class="w-5/12"><strong>Estimasi Bayar</strong></p>
                        @php
                            $tarif = config('tarif.harga_per_m3');
                            $bayar = $penggunaan_air->konsumsi * $tarif;
                        @endphp
                        <p class="w-1/12"><strong>:</strong></p> 
                        <span class="w-6/12">
                            {{ $penggunaan_air->konsumsi }} m³ x Rp. {{ number_format($tarif, 0, ',', '.') }} :
                            <strong>Rp. {{ number_format($bayar, 0, ',', '.') }}</strong>
                        </span>
                    </div>
                    <div class="flex items-start text-start py-2 border-b-1 border-slate-300">
                        <p class="w-5/12"><strong>Status Pembayaran</strong></p>
                        <p class="w-1/12"><strong>:</strong></p> 
                        @if ($penggunaan_air->sudah_bayar)
                            <span class="w-3/12 text-center px-2 py-1 rounded-full text-sm font-medium bg-green-200 outline-1 outline-green-700 text-green-900">Lunas</span>
                        @else
                            <span class="w-3/12 text-center px-2 py-1 rounded-full text-sm font-medium bg-red-200 outline-1 outline-red-700 text-red-900">Belum Bayar</span>
                        @endif
                    </div>
                    @if ($penggunaan_air->sudah_bayar)
                        <div class="flex border-b-1 border-slate-300">
                            <p class="w-5/12"><strong>Dibayar Oleh</strong></p>
                            <p class="w-1/12"><strong>:</strong></p> 
                            <p class="w-6/12">{{ $pembayaran->dibayarOleh->nama }} Selaku {{ $pembayaran->dibayarOleh->role }}</p>
                        </div>
                        <div class="flex border-b-1 border-slate-300">
                            <p class="w-5/12"><strong>Metode Pembayaran</strong></p>
                            <p class="w-1/12"><strong>:</strong></p> 
                            @if ($pembayaran->metode === 'cash')
                                <p class="w-6/12">{{ $pembayaran->metode }} di <span class="font-semibold">Pengurus</span> a/n <span class="font-semibold">{{ $pembayaran->dibayarOleh->nama }} </span></p>
                            @elseif ($pembayaran->metode === 'transfer')
                                @if ($pembayaran->dibayarOleh->role === "Pelanggan")
                                    <p class="w-6/12">{{ $pembayaran->metode }} dari Rekening <span class="font-semibold">{{ $pembayaran->nama_bank }} ({{ $pembayaran->no_rekening }})</span> a/n <span class="font-semibold">{{ $pembayaran->nama_rekening }}</span></p>
                                @else
                                    <p class="w-6/12">{{ $pembayaran->metode }} dari Rekening <span class="font-semibold">{{ $pembayaran->nama_bank }}</span> a/n <span class="font-semibold">{{ $pembayaran->nama_rekening }}</span> melalui mitra a/n <span class="font-semibold">{{ $pembayaran->dibayarOleh->nama }}</span></p>
                                @endif
                            @endif
                        </div>
                        <div class="flex border-b-1 border-slate-300">
                            <p class="w-5/12"><strong>Tanggal Bayar</strong></p>
                            <p class="w-1/12"><strong>:</strong></p>
                            <p class="w-6/12">{{ $pembayaran->created_at_indo }}</p> 
                        </div>
                    @endif
                </div>

                <div class="mt-4 text-right">
                    <button wire:click="closeModal" class="bg-blue-600 text-white px-4 py-2 rounded">Tutup</button>
                </div>
            </div>
        </div>
    @endif
</div>
<script>
    window.addEventListener('modal-loaded', () => {
        Swal.close(); // Tutup loading SweetAlert
    });
</script>