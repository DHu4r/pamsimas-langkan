<div>
    @if ($showModal)
        <div class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-xl shadow-xl lg:max-w-4xl md:max-w-lg sm:max-w-md w-full">
                <h2 class="text-xl font-bold mb-4">Proses Pembayaran Cash</h2>
                <h2 class="text-lg font-semibold mb-2">Detail Pembayaran Pelanggan :</h2>
                <div class="flex text-slate-700">
                    <div class="min-w-6/12"> 
                        <h3 class="font-semibold border-b border-slate-500 py-1 ps-2">Nama Pelanggan </h3>
                        <h3 class="font-semibold border-b border-slate-500 py-1 ps-2">Komplek </h3>
                        <h3 class="font-semibold border-b border-slate-500 py-1 ps-2">Nomor Hp </h3>
                        <h3 class="font-semibold border-b border-slate-500 py-1 ps-2">Meteran awal periode </h3>
                        <h3 class="font-semibold border-b border-slate-500 py-1 ps-2">Meteran Akhir periode </h3>
                        <h3 class="font-semibold border-b border-slate-500 py-1 ps-2">Konsumsi Air </h3>
                        <h3 class="font-semibold border-b border-slate-500 py-1 ps-2">Harga Air </h3>
                        <h3 class="font-semibold border-b border-slate-500 py-1 ps-2">Total bayar </h3>
                    </div>
                    <div class="min-w-6/12">
                        <h3 class="border-b border-slate-500 py-1">: {{ $penggunaan_air->penggunas->nama }}</h3>
                        <h3 class="border-b border-slate-500 py-1">: {{ $penggunaan_air->penggunas->komplek }}</h3>
                        <h3 class="border-b border-slate-500 py-1">: {{ $penggunaan_air->penggunas->no_hp }}</h3>
                        <h3 class="border-b border-slate-500 py-1">: {{ $penggunaan_air->meter_baca_awal }} m<sup>3</sup></h3>
                        <h3 class="border-b border-slate-500 py-1">: {{ $penggunaan_air->meter_baca_akhir }} m<sup>3</sup></h3>
                        <h3 class="border-b border-slate-500 py-1">: {{ $penggunaan_air->konsumsi }} m<sup>3</sup></h3>
                        @php
                            $tarif = config('tarif.harga_per_m3');
                            $bayar = $penggunaan_air->konsumsi * $tarif;
                            $finalbayar = $bayar;
                        @endphp
                        <h3 class="border-b border-slate-500 py-1">
                            : {{ $penggunaan_air->konsumsi }} m³ x Rp. {{ number_format($tarif, 0, ',', '.') }} :
                            <strong>Rp. {{ number_format($bayar, 0, ',', '.') }}</strong>
                        </h3>
                        <h3 class="border-b border-slate-500 py-1">: <strong>Rp. {{ number_format($finalbayar, 0, ',', '.') }}</strong></h3>
                    </div>
                </div>

                {{-- Form / tombol bayar dsb di sini --}}

                <div class="mt-4 text-right">
                    @if (!$penggunaan_air->sudah_bayar)
                        <button 
                            x-data
                            @click="Swal.fire({
                                title: 'Memproses...',
                                text: 'Silakan tunggu sebentar.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                } 
                            });"
                            wire:click="prosesPembayaran"
                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            Bayar
                        </button>
                        <button wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            Tutup
                        </button>
                    @else
                        <button 
                        x-data
                        @click="window.open('{{ route('cetak.rekening', ['id' => $penggunaan_air->id]) }}', '_blank')"
                        class="px-4 py-2 bg-sky-600 text-white rounded hover:bg-sky-700">
                            Cetak Rekening
                        </button>
                        <button wire:click="$set('showModal', false)"
                        onclick="document.dispatchEvent(new Event('modal-closed'))"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            Tutup
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <script>

        window.addEventListener('pembayaran-berhasil', event => {
            Swal.close(); // Tutup loading
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: event.detail.message || 'Pembayaran berhasil!',
            }).then(() => {
                //tanda pembayaran sukses
                window.pembayaranSukses = true;
            });
        });

        window.addEventListener('modal-loaded', () => {
            Swal.close(); // Tutup loading SweetAlert
        });

        // Kalau modal ditutup, baru refresh (jika pembayaran sukses)
        document.addEventListener('modal-closed', () => {
            if (window.pembayaranSukses) {
                window.location.reload();
            }
        });
    </script>
</div>
