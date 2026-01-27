<div>
    @if ($showModal && $penggunaanAir)
    <div class="fixed inset-0 bg-black/70 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold">Detail Penggunaan Air</h2>
                    <button wire:click="closeModal" class="text-gray-600 hover:text-red-600 text-2xl">&times;</button>
                </div>

                <div class="md:text-sm lg:text-sm  text-xs space-y-2">
                    <div class="flex items-start text-start">
                        <p class="w-5/12"><strong>Nama :</strong></p>
                        <span class="w-7/12">{{ $penggunaanAir->penggunas->nama ?? '-' }}</span>
                    </div>
                    <div class="flex items-start text-start">
                        <p class="w-5/12"><strong>Penggunaan Awal :</strong></p>
                        <span class="w-7/12">{{ $penggunaanAir->meter_baca_awal }} m³</span>
                    </div>
                    <div class="flex items-start text-start">
                        <p class="w-5/12"><strong>Penggunaan Akhir :</strong></p>
                        <span class="w-7/12">{{ $penggunaanAir->meter_baca_akhir }} m³</span>
                    </div>
                    <div class="flex items-start text-start">
                        <p class="w-5/12"><strong>Konsumsi :</strong></p> 
                        <span class="w-7/12">{{ $penggunaanAir->konsumsi }} m³</span>
                    </div>
                    <div class="flex items-start text-start">
                        <p class="w-5/12"><strong>Tanggal Catat :</strong></p>
                        <span class="w-7/12">{{ $penggunaanAir->tanggal_catat_indo }}</span>
                    </div>
                    <div class="flex items-start text-start">
                        <p class="w-5/12"><strong>Estimasi Bayar :</strong></p>
                        @php
                            $tarif = config('tarif.harga_per_m3');
                            $bayar = $penggunaanAir->konsumsi * $tarif;
                        @endphp
                        <span class="w-7/12">
                            {{ $penggunaanAir->konsumsi }} m³ x Rp. {{ number_format($tarif, 0, ',', '.') }} :
                            <strong>Rp. {{ number_format($bayar, 0, ',', '.') }}</strong>
                        </span>
                    </div>
                </div>
                <div class="md:text-sm lg:text-sm  text-xs flex items-start text-start py-2">
                    <p class="w-5/12"><strong>Status Pencatatan : </strong></p>
                    @if ($penggunaanAir->status === 'pending')
                        <span class="w-4/12 text-center px-2 py-1 rounded-full md:text-sm lg:text-sm  text-xs font-medium bg-red-200 outline-1 outline-red-700 text-red-900">belum verifikasi</span>
                    @elseif ($penggunaanAir->status === 'rejected')
                        <span class="w-3/12 text-center px-2 py-1 rounded-full md:text-sm lg:text-sm  text-xs font-medium bg-red-200 outline-1 outline-red-700 text-red-900">ditolak</span>
                    @else
                        <span class="w-3/12 text-center px-2 py-1 rounded-full md:text-sm lg:text-sm  text-xs font-medium bg-green-200 outline-1 outline-green-700 text-green-900">terverifikasi</span>
                    @endif
                </div>
                @if ($currentUser && in_array($currentUser->role, ['Pelanggan']))
                    @if ($penggunaanAir->status === 'pending')
                        <p class="text-xs">*Silahkan tunggu verifikasi penggunaan air dari petugas KSPAM</p>
                    @endif
                @endif
                @if ($penggunaanAir->status === 'rejected')
                    <p class="text-xs">*Ditolak karena {{ $penggunaanAir->catatan_verifikasi }}</p>
                @endif
                <div class="md:text-sm lg:text-sm  text-xs flex items-start text-start py-2">
                    <p class="w-5/12"><strong>Status Pembayaran : </strong></p>
                    @if ($penggunaanAir->sudah_bayar)
                        <span class="w-3/12 text-center px-2 py-1 rounded-full md:text-sm lg:text-sm  text-xs font-medium bg-green-200 outline-1 outline-green-700 text-green-900">Lunas</span>
                    @else
                        <span class="w-3/12 text-center px-2 py-1 rounded-full md:text-sm lg:text-sm  text-xs font-medium bg-red-200 outline-1 outline-red-700 text-red-900">Belum Bayar</span>
                    @endif
                </div>
                <div class="h-32 flex md:text-sm lg:text-sm  text-xs">
                    <p class="w-5/12"><strong>Gambar Meteran : </strong></p>
                    <img class="h-32 w-7/12 rounded shadow" src="{{ asset('storage/' . $penggunaanAir->foto_meter) }}" alt="Foto Meteran Air">
                </div>
                <div class="mt-4 text-right">
                    @if ($currentUser && in_array($currentUser->role, ['Pengurus', 'Petugas Lapangan']))
                        @if ($penggunaanAir->status === 'pending')

                            {{-- Input catatan (muncul saat mau menolak) --}}
                            <div class="mb-3 text-left">
                                <label class="block text-sm font-semibold text-slate-700">
                                    Catatan (wajib jika ditolak)
                                </label>
                                <textarea
                                    wire:model.defer="catatanVerifikasi"
                                    class="w-full border rounded p-2 text-sm"
                                    placeholder="Masukkan alasan penolakan (mis. foto tidak jelas)..."
                                ></textarea>

                                @error('catatanVerifikasi')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button
                                wire:click="reject"
                                class="bg-red-500 text-white px-4 py-2 rounded"
                            >
                                Tolak
                            </button>

                            <button
                                wire:click="approve"
                                class="bg-green-600 text-white px-4 py-2 rounded"
                            >
                                Verifikasi
                            </button>

                        @endif
                    @endif

                    <button
                        wire:click="closeModal"
                        class="bg-blue-600 text-white px-4 py-2 rounded"
                    >
                        Tutup
                    </button>
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
