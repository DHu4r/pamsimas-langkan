<x-layout :tittle="$tittle">
    <div x-data="Interaksi({{ $penggunaan_air->meter_baca_awal }}, {{ $penggunaan_air->meter_baca_akhir }})">
        <div class="flex">
            <a href="/penggunaan_air">
                <div class="group flex items-center justify-center rounded-2xl w-12 h-10 bg-green-300 hover:bg-green-600 hover:outline-1 hover:outline-green-900">
                    <svg class="w-8 h-8 text-gray-800 group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m17 16-4-4 4-4m-6 8-4-4 4-4"/>
                    </svg>          
                </div>
            </a>
        </div>
        <form action="{{ route('penggunaan_air.update', $penggunaan_air->id) }}" method="POST" class="mt-5" id="form-penggunaan-air" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="flex flex-wrap items-center gap-4">
                <div class="w-full lg:w-5/12 mt-4">
                    <label for="pelanggan" class="block ml-2 mb-2 font-semibold text-slate-700">Nama Pelanggan</label>
                    <select name="pelanggan" id="pelanggan" class="w-full py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl bg-slate-200 focus:outline-0">
                        <option value="{{ $pengguna->id }}" selected>{{ $pengguna->nama }}</option>
                    </select>
                </div>

                <div class="w-full lg:w-5/12 mt-4">
                    <label for="meter_baca_awal" class="block ml-2 mb-2 font-semibold text-slate-700">Nilai Meteran Awal</label>
                    <input type="number" name="meter_baca_awal" value="{{ $penggunaan_air->meter_baca_awal }}" class="w-full py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl bg-slate-200 focus:outline-0" readonly>
                </div>
                <div class="w-full lg:w-5/12 mt-4">    
                    <label for="meter_baca_akhir" class="block ml-2 mb-2 font-semibold text-slate-700">Nilai Meteran Akhir</label>
                    <input required type="number" x-model="meterAkhir" @input="hitungKonsumsi" name="meter_baca_akhir" id="meter_baca_akhir" class="w-full ml-2 ps-2 text-sm text-slate-700 py-2 px-4 border border-slate-400 rounded-xl focus:outline-sky-600" value="{{ $penggunaan_air->meter_baca_akhir }}">
                </div>
                <div class="w-full lg:w-5/12 mt-4">
                    <label for="konsumsi" class="block ml-2 mb-2 font-semibold text-slate-700">Penggunaan Air (Meter Kubik)</label>
                    <input type="number" name="konsumsi" value="{{ $penggunaan_air->konsumsi }}" x-model="konsumsi"
                    class="w-full py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl bg-slate-200 focus:outline-0" readonly>
                </div>
                <div class="w-full lg:w-5/12 mt-4">
                    <label for="estimasi_bayar" class="block ml-2 mb-2 font-semibold text-slate-700">Estimasi Pembayaran (Diluar Biaya Admin)</label>
                    <input type="text" :value="formatRupiah(estimasiBayar)" name="estimasi_bayar" placeholder="Estimasi Pembayaran"
                    class="w-full py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl bg-slate-200 focus:outline-0" readonly>
                </div>
                <div class="w-full lg:w-5/12 mt-4">
                    <label for="tanggal_catat" class="block ml-2 mb-2 font-semibold text-slate-700">Tanggal Catat</label>
                    <input required type="date" name="tanggal_catat" id="tanggal_catat" class="w-full ml-2 ps-2 text-sm text-slate-700 py-2 px-4 border border-slate-400 rounded-xl focus:outline-sky-600" value="{{ $penggunaan_air->tanggal_catat }}">
                </div>
                <div class="w-full lg:w-5/12">
                    <label required for="periode_bulan" class="block ml-2 mb-2 font-semibold text-slate-700">Bulan</label>
                    <select name="periode_bulan" id="periode_bulan" class="text-slate-700 py-2 w-full ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600" required>
                        <option value="">Pilih Bulan</option>
                        <option value="1" {{ $penggunaan_air->periode_bulan == 1 ? 'selected' : '' }}>Januari</option>
                        <option value="2" {{ $penggunaan_air->periode_bulan == 2 ? 'selected' : '' }}>Februari</option>
                        <option value="3" {{ $penggunaan_air->periode_bulan == 3 ? 'selected' : '' }}>Maret</option>
                        <option value="4" {{ $penggunaan_air->periode_bulan == 4 ? 'selected' : '' }}>April</option>
                        <option value="5" {{ $penggunaan_air->periode_bulan == 5 ? 'selected' : '' }}>Mei</option>
                        <option value="6" {{ $penggunaan_air->periode_bulan == 6 ? 'selected' : '' }}>Juni</option>
                        <option value="7" {{ $penggunaan_air->periode_bulan == 7 ? 'selected' : '' }}>Juli</option>
                        <option value="8" {{ $penggunaan_air->periode_bulan == 8 ? 'selected' : '' }}>Agustus</option>
                        <option value="9" {{ $penggunaan_air->periode_bulan == 9 ? 'selected' : '' }}>September</option>
                        <option value="10" {{ $penggunaan_air->periode_bulan == 10 ? 'selected' : '' }}>Oktober</option>
                        <option value="11" {{ $penggunaan_air->periode_bulan == 11 ? 'selected' : '' }}>November</option>
                        <option value="12" {{ $penggunaan_air->periode_bulan == 12 ? 'selected' : '' }}>Desember</option>
                    </select>
                </div>
                <div class="w-full lg:w-5/12">
                    <label for="periode_tahun" class="block ml-2 mb-2 font-semibold text-slate-700">Periode Tahun</label>
                    <input required type="number" name="periode_tahun" id="periode_tahun" class="w-full ml-2 ps-2 text-sm text-slate-700 py-2 px-4 border border-slate-400 rounded-xl focus:outline-sky-600" value="{{ $penggunaan_air->periode_tahun }}">
                </div>
                <div class="w-full lg:w-5/12 mt-4" x-data="{ preview: null, fileInput: null }">
                    <label for="foto_meter" class="block ml-2 mb-2 font-semibold text-slate-700">
                        Foto Meteran Air
                    </label>
                    <input 
                        type="file" 
                        name="foto_meter" 
                        id="foto_meter"
                        accept="image/*" 
                        capture="environment"
                        x-ref="fileInput"
                        @change="
                            if ($event.target.files.length) {
                                preview = URL.createObjectURL($event.target.files[0]);
                                fileInput = $event.target.files[0];
                            } else {
                                preview = null;
                                fileInput = null;
                            }
                        "
                        class="w-full ml-2 ps-2 text-sm text-slate-700 py-2 px-4 border border-slate-400 rounded-xl focus:outline-sky-600"
                    >

                    <template x-if="preview">
                        <div class="mt-2">
                            <img :src="preview" alt="Preview Foto" class="w-40 rounded-lg border">
                            <button 
                                type="button" 
                                class="mt-2 px-3 py-1 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600"
                                @click="
                                    preview = null;
                                    fileInput = null;
                                    $refs.fileInput.value = null;
                                "
                            >
                                Hapus Foto
                            </button>
                        </div>
                    </template>
                </div> 
                <div class="flex w-full justify-center">
                    <button type="submit" class="group flex items-center border border-sky-600 px-4 py-2 rounded-2xl bg-sky-300 hover:bg-sky-700 hover:text-white cursor-pointer font-semibold">
                        <svg class="w-6 h-6 text-gray-800 group-hover:text-white mr-1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 7V2.221a2 2 0 0 0-.5.365L4.586 6.5a2 2 0 0 0-.365.5H9Z"/>
                            <path fill-rule="evenodd" d="M11 7V2h7a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9h5a2 2 0 0 0 2-2Zm4.707 5.707a1 1 0 0 0-1.414-1.414L11 14.586l-1.293-1.293a1 1 0 0 0-1.414 1.414l2 2a1 1 0 0 0 1.414 0l4-4Z" clip-rule="evenodd"/>
                        </svg>
                        Perbaharui
                    </button>
                </div>
            </div>
        </form>
    </div>

    @if ($errors->any())
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal',
            html: `
                <ul style="text-align: left;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `
            });
        </script>
    @endif

    {{-- Script --}}
    <script>
        function Interaksi(awal, akhir) {
            return {
                meterAwal: awal,
                meterAkhir: akhir,
                konsumsi: akhir > awal ? akhir - awal : 0,
                estimasiBayar: (akhir > awal ? akhir - awal : 0) * 20000,

                hitungKonsumsi(){
                    const awal = parseInt(this.meterAwal) || 0;
                    const akhir = parseInt(this.meterAkhir) || 0;
                    this.konsumsi = akhir > awal ? akhir - awal : 0;
                    this.estimasiBayar = this.konsumsi * 20000;
                },

                formatRupiah(angka) {
                    return 'Rp. ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                }
            }
        }
    </script>        
</x-layout>