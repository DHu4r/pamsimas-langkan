<x-layout :tittle="$tittle">
    <div x-data="dropdownSearch()" x-init="init()">
        <div class="flex">
            <a href="/penggunaan_air">
                <div class="group flex items-center justify-center rounded-2xl w-12 h-10 bg-green-300 hover:bg-green-600 hover:outline-1 hover:outline-green-900">
                    <svg class="w-8 h-8 text-gray-800 group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m17 16-4-4 4-4m-6 8-4-4 4-4"/>
                    </svg>          
                </div>
            </a>
        </div>
        <form action="{{ route('penggunaan_air.store') }}" method="POST" enctype="multipart/form-data" class="mt-5" id="form-penggunaan-air">
            @csrf
            <div class="flex flex-wrap gap-4">
                @if (auth()->user()->role === 'Pelanggan')
                    <div class="w-full lg:w-5/12 mt-4">
                        <label for="pelanggan" class="block ml-2 mb-2 font-semibold text-slate-700">Nama Pelanggan</label>
                        {{-- Untuk role Pelanggan: langsung isi ID dari user login --}}
                        <input type="hidden" name="penggunas_id" value="{{ auth()->user()->id }}">
                        <input 
                            type="text" 
                            value="{{ auth()->user()->nama }}" 
                            disabled
                            class="w-full py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl bg-gray-100 text-slate-900"
                        />
                    </div>
                @else
                    <div class="w-full lg:w-5/12 mt-4">
                        <label for="pelanggan" class="block ml-2 mb-2 font-semibold text-slate-700">Pilih Pelanggan</label>
                            
                            {{-- Petugas Lapangan: pakai autocomplete --}}
                            <input 
                                required
                                type="text" 
                                placeholder="Cari pelanggan..." 
                                x-model="search" 
                                @focus="open = true" 
                                @click.away="open = false"
                                class="peer w-full py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600 placeholder:text-sm invalid:border-red-500"
                            />

                            <div x-show="open" class="absolute z-10 mt-1 lg:w-3/12 md:w-10/12 w-10/12 bg-white border border-gray-300 rounded-xl max-h-60 overflow-auto ml-2">
                                <template x-for="item in filteredData()" :key="item.id">
                                    <div 
                                        @click="selectItem(item)"
                                        class="px-4 py-2 cursor-pointer hover:bg-sky-100 text-slate-700"
                                    >
                                        <span x-text="item.nama"></span>
                                    </div>
                                </template>
                            </div>
                            <div x-show="filteredData().length === 0" class=" ml-6 px-4 py-2 text-red-400">
                                Tidak ada hasil
                            </div>
                            <input type="hidden" name="penggunas_id" :value="selectedId">
                    </div>
                    <div x-data="barcodeScanner()" class="w-full lg:w-5/12 mt-4">
                        <h3 class="font-semibold">Scan Barcode</h3>

                        <!-- Tombol Mulai Scan -->
                        <button 
                            type="button" 
                            @click="startScanner" 
                            class="px-4 py-2 bg-amber-400 font-semibold rounded-xl mt-2">
                            Mulai Scan
                        </button>

                        <!-- Area kamera -->
                        <div id="qr-reader" class="mt-3 w-full" style="max-width:300px;"></div>

                        <!-- Hidden input untuk user id -->
                        {{-- <input type="hidden" name="penggunas_id" id="penggunas_id"> --}}
                    </div>
                @endif 
                <div class="w-full lg:w-5/12 mt-4">
                    <label for="meter_baca_awal" class="block ml-2 mb-2 font-semibold text-slate-700">Nilai Meteran Awal</label>
                    <input type="number" name="meter_baca_awal" placeholder="Nilai Meteran Awal"
                    x-model="meterAwal"
                    class="w-full py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl bg-slate-200 focus:outline-0" readonly>
                </div>
                <div class="w-full lg:w-5/12 mt-4">    
                    <label for="meter_baca_akhir" class="block ml-2 mb-2 font-semibold text-slate-700">Nilai Meteran Akhir</label>
                    <input required type="number" x-model="meterAkhir" @input="hitungKonsumsi" name="meter_baca_akhir" id="meter_baca_akhir" class="w-full ml-2 ps-2 text-sm text-slate-700 py-2 px-4 border border-slate-400 rounded-xl focus:outline-sky-600" placeholder="Masukan Nilai Meteran Akhir">
                </div> 
                <div class="w-full lg:w-5/12 mt-4">
                    <label for="konsumsi" class="block ml-2 mb-2 font-semibold text-slate-700">Penggunaan Air (Meter Kubik)</label>
                    <input type="number" name="konsumsi" placeholder="Penggunaan Air"
                    x-model="konsumsi"
                    class="w-full py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl bg-slate-200 focus:outline-0" readonly>
                </div>
                <div class="w-full lg:w-5/12 mt-4">
                    <label for="estimasi_bayar" class="block ml-2 mb-2 font-semibold text-slate-700">Estimasi Pembayaran (Diluar Biaya Admin)</label>
                    <input type="text" :value="formatRupiah(estimasiBayar)" name="estimasi_bayar" placeholder="Estimasi Pembayaran"
                    class="w-full py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl bg-slate-200 focus:outline-0" readonly>
                </div>
                <div class="w-full lg:w-5/12 mt-4">
                    <label for="tanggal_catat" class="block ml-2 mb-2 font-semibold text-slate-700">Tanggal Catat</label>
                    <input required 
                    type="date" 
                    name="tanggal_catat" 
                    id="tanggal_catat" 
                    value="{{ old('tanggal_catat', now()->toDateString()) }}"
                    class="w-full ml-2 ps-2 text-sm text-slate-700 py-2 px-4 border border-slate-400 rounded-xl focus:outline-sky-600" placeholder="Masukan Tanggal">
                </div>
                <div class="w-full lg:w-5/12 mt-4">
                    <label required for="periode_bulan" class="block ml-2 mb-2 font-semibold text-slate-700">Periode Bulan</label>
                    <select 
                        name="periode_bulan"
                        id="periode_bulan"
                        class="w-full ml-2 ps-2 text-sm text-slate-700 py-2 px-4 border border-slate-400 rounded-xl focus:outline-sky-600">

                        <option value="">Pilih Periode Bulan</option>
                        <option value="1"  {{ now()->format('n') == 1 ? 'selected' : '' }}>Januari</option>
                        <option value="2"  {{ now()->format('n') == 2 ? 'selected' : '' }}>Februari</option>
                        <option value="3"  {{ now()->format('n') == 3 ? 'selected' : '' }}>Maret</option>
                        <option value="4"  {{ now()->format('n') == 4 ? 'selected' : '' }}>April</option>
                        <option value="5"  {{ now()->format('n') == 5 ? 'selected' : '' }}>Mei</option>
                        <option value="6"  {{ now()->format('n') == 6 ? 'selected' : '' }}>Juni</option>
                        <option value="7"  {{ now()->format('n') == 7 ? 'selected' : '' }}>Juli</option>
                        <option value="8"  {{ now()->format('n') == 8 ? 'selected' : '' }}>Agustus</option>
                        <option value="9"  {{ now()->format('n') == 9 ? 'selected' : '' }}>September</option>
                        <option value="10" {{ now()->format('n') == 10 ? 'selected' : '' }}>Oktober</option>
                        <option value="11" {{ now()->format('n') == 11 ? 'selected' : '' }}>November</option>
                        <option value="12" {{ now()->format('n') == 12 ? 'selected' : '' }}>Desember</option>
                    </select>
                </div>
                <div class="w-full lg:w-5/12 mt-4">
                    <label for="periode_tahun" class="block ml-2 mb-2 font-semibold text-slate-700">Periode Tahun</label>
                    <input required type="number" name="periode_tahun" id="periode_tahun" value="{{ now()->format("Y") }}" class="w-full ml-2 ps-2 text-sm text-slate-700 py-2 px-4 border border-slate-400 rounded-xl focus:outline-sky-600" placeholder="Masukan Tahun">
                </div>
                <div class="w-full lg:w-5/12 mt-4"
                    x-data="{
                        preview: null,
                        fileInput: null,

                        async handleFile(e) {
                            const file = e.target.files[0];
                            if (!file) {
                                this.preview = null;
                                this.fileInput = null;
                                return;
                            }

                            // Preview tetap dari file asli dulu
                            this.preview = URL.createObjectURL(file);

                            // Kalau sudah < 1.8MB, kirim apa adanya
                            if (file.size <= 1800 * 1024) {
                                this.fileInput = file;
                                return;
                            }

                            // Compress image
                            const compressed = await this.compressImage(file);

                            this.fileInput = compressed;

                            // Replace file di input supaya Laravel terima file hasil compress
                            const dt = new DataTransfer();
                            dt.items.add(compressed);
                            this.$refs.fileInput.files = dt.files;

                            // Update preview ke hasil compress
                            this.preview = URL.createObjectURL(compressed);
                        },

                        compressImage(file) {
                            return new Promise((resolve) => {
                                const img = new Image();
                                const reader = new FileReader();

                                reader.onload = (e) => {
                                    img.src = e.target.result;
                                };

                                img.onload = () => {
                                    const canvas = document.createElement('canvas');
                                    const ctx = canvas.getContext('2d');

                                    const maxWidth = 1600;
                                    const scale = Math.min(1, maxWidth / img.width);

                                    canvas.width = img.width * scale;
                                    canvas.height = img.height * scale;

                                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                                    canvas.toBlob(
                                        (blob) => {
                                            const newFile = new File([blob], file.name, {
                                                type: 'image/jpeg',
                                                lastModified: Date.now()
                                            });
                                            resolve(newFile);
                                        },
                                        'image/jpeg',
                                        0.75 // quality: 0.6–0.8
                                    );
                                };

                                reader.readAsDataURL(file);
                            });
                        }
                    }">

                    <label for="foto_meter" class="block ml-2 mb-2 font-semibold text-slate-700">
                        Foto Meteran Air
                    </label>

                    <input 
                        required
                        type="file" 
                        name="foto_meter" 
                        id="foto_meter"
                        accept="image/*" 
                        capture="environment"
                        x-ref="fileInput"
                        @change="handleFile($event)"
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
                    <button 
                    type="submit"
                    class="group flex items-center border border-sky-600 px-4 py-2 rounded-2xl bg-sky-300 hover:bg-sky-700 hover:text-white cursor-pointer font-semibold">
                        <svg class="w-6 h-6 text-gray-800 group-hover:text-white mr-1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 7V2.221a2 2 0 0 0-.5.365L4.586 6.5a2 2 0 0 0-.365.5H9Z"/>
                            <path fill-rule="evenodd" d="M11 7V2h7a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9h5a2 2 0 0 0 2-2Zm4.707 5.707a1 1 0 0 0-1.414-1.414L11 14.586l-1.293-1.293a1 1 0 0 0-1.414 1.414l2 2a1 1 0 0 0 1.414 0l4-4Z" clip-rule="evenodd"/>
                        </svg>
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>

    
    {{-- load dari public/js --}}
    <script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script src="https://unpkg.com/html5-qrcode@2.3.4/minified/html5-qrcode.min.js"></script>   --}}
    @if ($errors->any())
        <script>
            Swal.close();
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
    <script>
        document.getElementById('form-penggunaan-air').addEventListener('submit', function(e){
            if(this.checkValidity()) { //cek validasi html lulus
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Silakan tunggu sebentar.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }
        });
    </script>

    {{-- Script --}}
    <script>
        function dropdownSearch() {
            return {
                open: false,
                search: '',
                selectedId: '',
                selectedName: '',
                meterAwal: 0,
                meterAkhir:'',
                konsumsi:0,
                estimasiBayar:0,
                items: @json($penggunas), // ← kirim array PHP ke JavaScript

                 init() {
                    // kalau role Pelanggan, langsung fetch meter terakhir pakai id login
                    @if(auth()->user()->role === 'Pelanggan')
                        this.selectedId = "{{ auth()->id() }}";
                        this.getMeterAwal(this.selectedId);
                    @endif
                },

                filteredData() {
                    if (this.search === '') return this.items;
                    return this.items.filter(i => i.nama.toLowerCase().includes(this.search.toLowerCase()));
                },
                selectItem(item) {
                    this.search = item.nama;
                    this.selectedId = item.id;
                    this.open = false;
                    this.getMeterAwal(item.id);
                },
                async getMeterAwal(penggunaId){
                    try {
                        const res = await fetch(`/api/meter-terakhir/${penggunaId}`);
                        const data = await res.json();
                        this.meterAwal = data.meter_baca_akhir ?? 0;
                    } catch (err){
                        console.error("Gagal mengambil data meter akhir:", err);
                        this.meterAwal = 0;
                    }
                },
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
    <script>
        function barcodeScanner() {
            return {
                scanner: null,
                async startScanner() {
                    if (typeof Html5Qrcode === 'undefined') {
                        alert("Library html5-qrcode belum termuat!");
                        return;
                    }

                    if (!this.scanner) {
                        this.scanner = new Html5Qrcode("qr-reader");
                    }

                    try {
                        await this.scanner.start(
                            { facingMode: "environment" },
                            { fps: 10, qrbox: 250 },
                            (decodedText) => {
                                console.log("QR Code:", decodedText);

                                // Cari komponen dropdownSearch
                                let dropdown = document.querySelector('[x-data="dropdownSearch()"]')?._x_dataStack[0];
                                if (dropdown) {
                                    dropdown.selectedId = decodedText;

                                    // kalau ada nama di items, isi juga search box
                                    let item = dropdown.items.find(i => i.id === decodedText);
                                    if (item) {
                                        dropdown.search = item.nama;
                                        dropdown.getMeterAwal(item.id);
                                        // alert(`Pelanggan terdeteksi: ${item.nama}`);
                                        // pakai SweetAlert2
                                        Swal.fire({
                                            icon: 'success', // harus lowercase
                                            title: 'Pelanggan Terdeteksi',
                                            html: `
                                                <ul style="text-align: center;">
                                                    <li>Pelanggan atas nama <b>${item.nama}</b></li>
                                                </ul>
                                            `
                                        });
                                    } else {
                                        alert("UUID tidak ditemukan di daftar pelanggan!");
                                    }
                                }

                                this.scanner.stop();
                            },
                            (errorMessage) => {
                                console.log(errorMessage);
                            }
                        );
                    } catch (err) {
                        console.error("Gagal start scanner:", err);
                    }
                }
            }
        }
    </script>  
</x-layout>