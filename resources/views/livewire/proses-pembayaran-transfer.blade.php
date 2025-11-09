<div>
    @if ($showModal)
        @if ($step === 1)
            <div class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center">
                <div class="bg-white p-6 rounded-xl shadow-xl lg:max-w-4xl md:max-w-lg sm:max-w-md w-full">
                    <h2 class="text-base font-bold mb-4">Proses Pembayaran Transfer</h2>
                    <h2 class="text-base font-semibold mb-2">Detail Pembayaran :</h2>
                    <table class="w-full text-slate-700 text-sm">
                        <tr class="border-b border-slate-500">
                            <td class="w-6/12 text-start">Nama Pelanggan</td>
                            <td class="w-1/12 text-center"> : </td>
                            <td class="w-5/12 text-start">{{ $penggunaan_air->penggunas->nama }}</td>
                        </tr>
                        <tr class="border-b border-slate-500">
                            <td class="w-6/12 text-start">Komplek</td>
                            <td class="w-1/12 text-center"> : </td>
                            <td class="w-5/12 text-start">{{ $penggunaan_air->penggunas->komplek }}</td>
                        </tr>
                        <tr class="border-b border-slate-500">
                            <td class="w-6/12 text-start">Nomor Hp</td>
                            <td class="w-1/12 text-center"> : </td>
                            <td class="w-5/12 text-start">{{ $penggunaan_air->penggunas->no_hp }}</td>
                        </tr>
                        <tr class="border-b border-slate-500">
                            <td class="w-6/12 text-start">Meteran Awal Periode</td>
                            <td class="w-1/12 text-center"> : </td>
                            <td class="w-5/12 text-start">{{ $penggunaan_air->meter_baca_awal }}</td>
                        </tr>
                        <tr class="border-b border-slate-500">
                            <td class="w-6/12 text-start">Meteran Akhir Periode</td>
                            <td class="w-1/12 text-center"> : </td>
                            <td class="w-5/12 text-start">{{ $penggunaan_air->meter_baca_akhir }}</td>
                        </tr>
                        @php
                            $tarif = config('tarif.harga_per_m3');
                            $bayar = $penggunaan_air->konsumsi * $tarif;
                            $finalbayar = $bayar;
                        @endphp
                        <tr class="border-b border-slate-500">
                            <td class="w-6/12 text-start">Konsumsi Air</td>
                            <td class="w-1/12 text-center"> : </td>
                            <td class="w-5/12 text-start">{{ $penggunaan_air->konsumsi }} m³ x Rp. {{ number_format($tarif, 0, ',', '.') }} :
                                <strong>Rp. {{ number_format($bayar, 0, ',', '.') }}</strong>
                            </td>
                        </tr>
                        <tr class="border-b border-slate-500">
                            <td class="w-6/12 text-start">Total Bayar</td>
                            <td class="w-1/12 text-center"> : </td>
                            <td class="w-5/12 text-start"><strong>Rp {{ number_format($total_bayar ?? $bayar, 0, ',', '.') }} </strong>
                            </td>
                        </tr>
                    </table>
                    <div class="w-full mt-8">
                        <h3 class="font-semibold text-sm text-slate-700">Silahkan melakukan transfer pembayaran sesuai detail di atas ke rekening dibawah ini.</h3>
                        <p class="text-sm text-slate-700 mt-2 ">Nama Bank : <span class="font-semibold text-sm text-slate-700 mt-1">Bank Rakyat Indonesia (BRI)</span></p>
                        <p class="text-sm text-slate-700 mt-1">Nomor : <span class="font-semibold text-sm text-slate-700 mt-1">8901 8543 6849 32</span></p>
                        <p class="text-sm text-slate-700 mt-1">Atas Nama : <span class="font-semibold text-sm text-slate-700 mt-1">I Wayan Sudarma Adnyana</span></p>
                        <h3 class="mt-2 font-semibold text-sm text-slate-700">Setelah melakukan transfer, silahkan simpan bukti transfernya. Klik tombol "lanjut" untuk melanjutkan ke tahap input bukti transfer.</h3>
                    </div>

                    {{-- Form / tombol bayar dsb di sini --}}

                    <div class="mt-4 text-right">
                        <button wire:click="nextStep" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            Lanjut
                        </button>
                        <button wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        @elseif ($step === 2)
            <div class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center" x-data="{ preview: null, buktiInput: null }">
                <div class="bg-white p-6 rounded-xl shadow-xl lg:max-w-4xl md:max-w-lg sm:max-w-md w-full">
                    <h2 class="text-base font-bold mb-4">Proses Pembayaran Transfer</h2>
                    <div class="flex text-slate-700 lg:text-sm text-xs">
                        <div class="min-w-6/12"> 
                            <h3 class="font-semibold border-b border-slate-500 py-1 ps-2">Nama Pelanggan </h3>
                            <h3 class="font-semibold border-b border-slate-500 py-1 ps-2">Nomor Hp </h3>
                            <h3 class="font-semibold border-b border-slate-500 py-1 ps-2">Konsumsi Air </h3>
                            <h3 class="font-semibold border-b border-slate-500 py-1 ps-2">Total bayar </h3>
                        </div>
                        <div class="min-w-6/12">
                            <h3 class="border-b border-slate-500 py-1">: {{ $penggunaan_air->penggunas->nama }}</h3>
                            <h3 class="border-b border-slate-500 py-1">: {{ $penggunaan_air->penggunas->no_hp }}</h3>
                            <h3 class="border-b border-slate-500 py-1">: {{ $penggunaan_air->konsumsi }} m<sup>3</sup></h3>
                            @php
                                $tarif = config('tarif.harga_per_m3');
                                $bayar = $penggunaan_air->konsumsi * $tarif;
                                $finalbayar = $bayar;
                            @endphp
                            <h3 class="border-b border-slate-500 py-1">: <strong>Rp. {{ number_format($finalbayar, 0, ',', '.') }}</strong></h3>
                        </div>
                    </div>

                    @if (!$penggunaan_air->sudah_bayar)
                    <form wire:submit.prevent="prosesPembayaranTransfer">
                        @csrf
                        <h2 class="mt-4 ps-4 text-base text-slate-800 font-bold mb-4">Silahkan lengkapi data berikut untuk melanjutkan pembayaran</h2>
                        <div class="flex sm:flex-row lg:flex-row flex-col text-slate-700 text-sm">
                            <div class="lg:w-4/12 md:w-4/12 w-full px-4">
                                <label for="no_rekening"><span class="font-bold">No. Rekening Transfer</span> (No. rekening yang digunakan untuk transfer pembayaran)</label>
                                <input type="number" name="no_rekening" id="no_rekening" required wire:model="no_rekening" class="w-full h-9 mt-1 ps-2 text-sm text-slate-700 border border-slate-400 rounded-xl focus:outline-sky-600">
                            </div>
                            <div class="lg:w-4/12 md:w-4/12 w-full px-4">
                                <label for="nama_rekening"><span class="font-bold">Nama Rekening Transfer</span> (Atas nama rekening yang digunakan untuk transfer pembayaran)</label>
                                <input required type="text" name="nama_rekening" id="nama_rekening" wire:model="nama_rekening" class="w-full mt-1 ps-2 text-sm text-slate-700 h-9 border border-slate-400 rounded-xl focus:outline-sky-600">
                            </div>
                            <div class="lg:w-4/12 md:w-4/12 w-full px-4">
                                <label for="nama_bank"><span class="font-bold">Nama Bank</span> (Bank Sumber Transfer)</label>
                                <select 
                                    required
                                    wire:model="nama_bank" 
                                    class="w-full h-9 lg:mt-11 ps-2 text-sm text-slate-700 border border-slate-400 rounded-xl focus:outline-sky-600">
                                    <option value="">-- Pilih Bank --</option>
                                    @foreach($daftarBank as $bank)
                                        <option value="{{ $bank }}">{{ $bank }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex text-slate-700 text-sm mt-5">
                            <div class="lg:w-6/12 md:w-6/12 w-full px-4 text-slate-700 text-sm mt-2">
                                <label for="no_rekening"><span class="font-bold">Bukti Transfer</span> (Silahkan upload bukti transfer)</label>
                                <input 
                                    required
                                    type="file"
                                    name="foto_bukti"
                                    id="foto_bukti"
                                    wire:model="file_bukti"
                                    accept="image/*"
                                    class="w-full h-9 mt-1 ps-2 text-center text-sm text-slate-700 rounded-xl focus:outline-sky-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-sky-600 file:text-white hover:file:bg-sky-700">
                            </div>
                            <div class="w-6/12 px-4 mt-2">
                                @if ($file_bukti)
                                    <div class="mt-2">
                                        <img src="{{ $file_bukti->temporaryUrl() }}" 
                                            alt="Preview Foto" 
                                            class="w-40 rounded-lg border">
                                        <button 
                                            type="button" 
                                            class="mt-2 px-3 py-1 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600"
                                            wire:click="$set('file_bukti', null)">
                                            Hapus Foto
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <h2 class="mt-4 ps-4 text-base text-slate-800 font-bold mb-4">Terimakasih telah melakukan pembayaran, pembayaran anda telah kami terima, selanjutnya anda dapat mencetat bukti pembayaran (Rekening Air)</h2>
                    @endif
                        <div class="mt-8 text-right">
                            @if (!$penggunaan_air->sudah_bayar)
                                <button type="button" wire:click="prevStep" class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-700">
                                    Kembali
                                </button>
                                <button type="button"
                                @click = "
                                    if ($el.form.checkValidity()) {
                                        Swal.fire({
                                            title: 'Proses Pembayaran?',
                                            text: 'Pastikan data sudah benar.',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonText: 'Ya, Simpan',
                                            cancelButtonText: 'Batal'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                $wire.prosesPembayaranTransfer();
                                            }
                                        });
                                    } else {
                                        $el.form.reportValidity();
                                    }
                                "
                                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                    Simpan
                                </button>
                                <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                    Tutup
                                </button>
                            @else
                                <button 
                                x-data
                                @click="window.open('{{ route('cetak.rekening', ['id' => $penggunaan_air->id]) }}', '_blank')"
                                class="px-4 py-2 bg-sky-600 text-white rounded hover:bg-sky-700">
                                    Cetak Rekening
                                </button>
                                <button 
                                wire:click="$set('showModal', false)"
                                onclick="document.dispatchEvent(new Event('modal-closed'))"
                                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                    Tutup
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        @endif
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