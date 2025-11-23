<x-layout :tittle="$tittle">
    <div x-data="formLaporan()" x-init="init()"> 
        @if (session('success'))
            <div x-data="{ show: true }" 
            x-init="setTimeout(() => show = false, 3000)" 
            x-show="show"
            x-transition 
            class="max-w-sm bg-green-200 border border-green-600 text-green-900 px-4 py-2 rounded-lg z-50 mb-4"
            >
                <div class="flex items-center justify-between">
                    <span class="mr-2 font-semibold">{{ session('success') }}</span>
                    <button @click="show = false" class="ml-2 text-lg font-bold leading-none focus:outline-none">×</button>
                </div>
            </div>
        @endif
        @if ($errors->any())
        <div 
            x-data="{ show: true }" 
            x-init="setTimeout(() => show = false, 5000)" 
            x-show="show" 
            x-transition
            class="mb-4 bg-red-200 border border-red-400 text-red-800 px-4 py-3 rounded-lg z-50 max-w-sm"
        >
            <div class="flex items-start justify-between space-x-2 ">
                <div>
                    <strong class="font-semibold">Terjadi kesalahan: @foreach ($errors->all() as $error)
                        {{ $error }},
                    @endforeach</strong>
                </div>
                <button @click="show = false" class="text-xl leading-none font-bold focus:outline-none">×</button>
            </div>
        </div>
        @endif

        <h1 class="font-semibold mb-2">Daftar Laporan</h1>
        <div>
            <div 
            x-data="{ openFilter: {{ request('search') || request('periode_bulan') || request('periode_tahun') ? 'true' : 'false' }} }"
            class="flex flex-col lg:flex-row w-full justify-between mb-4 space-x-1 space-y-1">
                <div>
                    <button  @click="tambahLaporan()"
                        class="group flex px-4 py-2 rounded transition-all duration-300"
                        :class="showForm 
                            ? 'bg-red-500 text-white hover:bg-red-400 hover:outline-red-700 hover:outline-2' 
                            : 'bg-blue-500 text-white hover:bg-blue-300 hover:outline-blue-700 hover:outline-2'">
                            <svg x-show="!showForm" class="group-hover:text-gray-800 w-6 h-6 text-white mr-2" 
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" 
                                    d="M9 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H7Zm8-1a1 1 0 0 1 1-1h1v-1a1 1 0 1 1 2 0v1h1a1 1 0 1 1 0 2h-1v1a1 1 0 1 1-2 0v-1h-1a1 1 0 0 1-1-1Z" 
                                    clip-rule="evenodd"/>
                            </svg>
                            <svg x-show="showForm" class="group-hover:text-gray-800 w-6 h-6 text-white mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>                      
                        <h3 class="font-semibold group-hover:text-slate-800" x-text="showForm ? 'Batal' : 'Tambah'"></h3>
                    </button>
                </div>

                {{-- Tombol Filter untuk sm/md --}}
                <div class="flex lg:hidden mb-1 w-44 mt-1">
                    <button @click="openFilter = !openFilter"
                            class="w-full px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        <span x-show="!openFilter">Filter Data</span>
                        <span x-show="openFilter">Tutup Filter</span>
                    </button>
                </div>
                @if(request('search') || request('periode_bulan') || request('periode_tahun'))
                    <a href="{{ route('laporan.index') }}"
                        class="px-4 py-2 bg-gray-300 text-slate-800 rounded hover:bg-gray-400 hover:text-black">
                        Reset
                    </a>
                @endif

                {{-- Pembungkus tombol cari dan tambah --}}
                <form method="GET" action="{{ route('laporan.index') }}" 
                class="flex-col space-y-2 md:space-y-0 md:flex-row md:flex lg:flex lg:flex-row lg:space-y-0 lg:space-x-2 w-full"
                :class="{ 'hidden': !openFilter }" 
                x-show="openFilter || window.innerWidth >= 1024" 
                x-transition>
                    <select name="periode_bulan" id="periode_bulan" class="text-gray-600 py-2 w-full lg:w-44 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600 text-sm">
                        <option value="">Pilih Periode Bulan</option>
                        <option value=1 {{ request('periode_bulan') == 1 ? 'selected' : '' }}>Januari</option>
                        <option value=2 {{ request('periode_bulan') == 2 ? 'selected' : '' }}>Februari</option>
                        <option value=3 {{ request('periode_bulan') == 3 ? 'selected' : '' }}>Maret</option>
                        <option value=4 {{ request('periode_bulan') == 4 ? 'selected' : '' }}>April</option>
                        <option value=5 {{ request('periode_bulan') == 5 ? 'selected' : '' }}>Mei</option>
                        <option value=6 {{ request('periode_bulan') == 6 ? 'selected' : '' }}>Juni</option>
                        <option value=7 {{ request('periode_bulan') == 7 ? 'selected' : '' }}>Juli</option>
                        <option value=8 {{ request('periode_bulan') == 8 ? 'selected' : '' }}>Agustus</option>
                        <option value=9 {{ request('periode_bulan') == 9 ? 'selected' : '' }}>September</option>
                        <option value=10 {{ request('periode_bulan') == 10 ? 'selected' : '' }}>Oktober</option>
                        <option value=11 {{ request('periode_bulan') == 11 ? 'selected' : '' }}>November</option>
                        <option value=12 {{ request('periode_bulan') == 12 ? 'selected' : '' }}>Desember</option>
                    </select> 
                    <input type="number" name="periode_tahun" value="{{ request('periode_tahun') }}" placeholder="Masukan Periode Tahun" class="w-full lg:w-48 md:w-50 py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600 placeholder:text-sm">
                    <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari Data" class="w-full lg:w-44 md:w-80 py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600 placeholder:text-sm">
                    <button type="submit" class="ml-4 px-4 py-2 bg-blue-500 text-white hover:bg-blue-300 rounded-xl hover:outline-blue-700 hover:outline-2">
                        Cari
                    </button>
                </form>
            </div>

            {{-- Form Tambah Laporan --}}
            <form action={{ route('laporan.store') }} method="POST" x-show="showForm" x-transition id="form-laporan" @submit="isLoading = true">
                @csrf
                <div class="flex flex-wrap items-center gap-4">
                    <div>
                        <select name="bulan_laporan" id="bulan_laporan" class="text-gray-600 py-2 w-full lg:w-44 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600 text-sm" x-model="formData.bulan_laporan" required>
                            <option value="">Pilih Bulan Laporan</option>
                            <option value=1 {{ request('bulan_laporan') == 1 ? 'selected' : '' }}>Januari</option>
                            <option value=2 {{ request('bulan_laporan') == 2 ? 'selected' : '' }}>Februari</option>
                            <option value=3 {{ request('bulan_laporan') == 3 ? 'selected' : '' }}>Maret</option>
                            <option value=4 {{ request('bulan_laporan') == 4 ? 'selected' : '' }}>April</option>
                            <option value=5 {{ request('bulan_laporan') == 5 ? 'selected' : '' }}>Mei</option>
                            <option value=6 {{ request('bulan_laporan') == 6 ? 'selected' : '' }}>Juni</option>
                            <option value=7 {{ request('bulan_laporan') == 7 ? 'selected' : '' }}>Juli</option>
                            <option value=8 {{ request('bulan_laporan') == 8 ? 'selected' : '' }}>Agustus</option>
                            <option value=9 {{ request('bulan_laporan') == 9 ? 'selected' : '' }}>September</option>
                            <option value=10 {{ request('bulan_laporan') == 10 ? 'selected' : '' }}>Oktober</option>
                            <option value=11 {{ request('bulan_laporan') == 11 ? 'selected' : '' }}>November</option>
                            <option value=12 {{ request('bulan_laporan') == 12 ? 'selected' : '' }}>Desember</option>
                        </select> 
                    </div>
                    <div>
                        <input type="number" name="tahun_laporan" value="{{ request('tahun_laporan') }}" placeholder="Masukan Tahun Laporan" class="w-full lg:w-48 md:w-50 py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600 placeholder:text-sm" x-model="formData.tahun_laporan" required>
                    </div>
                    <div>
                        <input type="text" name="catatan" value="{{ request('catatan') }}" placeholder="Masukan Catatan" class="w-full lg:w-48 md:w-50 py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600 placeholder:text-sm" x-model="formData.catatan" required>
                    </div>
                    <div>
                        <button type="submit" 
                            class="group flex items-center border border-sky-600 px-4 py-2 rounded-2xl bg-sky-300 hover:bg-sky-700 hover:text-white cursor-pointer ml-6"
                            :disabled="isLoading"
                        >
                            <template x-if="isLoading">
                                <svg class="animate-spin w-5 h-5 text-white mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                            </template>
                            <template x-if="!isLoading">
                                <svg class="w-6 h-6 text-gray-800 group-hover:text-white mr-1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 7V2.221a2 2 0 0 0-.5.365L4.586 6.5a2 2 0 0 0-.365.5H9Z"/>
                                    <path fill-rule="evenodd" d="M11 7V2h7a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9h5a2 2 0 0 0 2-2Zm4.707 5.707a1 1 0 0 0-1.414-1.414L11 14.586l-1.293-1.293a1 1 0 0 0-1.414 1.414l2 2a1 1 0 0 0 1.414 0l4-4Z" clip-rule="evenodd"/>
                                </svg>
                            </template>
                            <span x-text="isLoading ? 'Menyimpan...' : 'Simpan'"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="lg:block md:table w-full p-4 text-base text-slate-700 mt-5">
            <table class="w-full p-4">
                <thead class="border-b border-gray-300">
                    <tr>
                        <th class="text-start px-4 py-2">No.</th>
                        <th class="text-start px-4 py-2">Periode</th>
                        <th class="text-start px-4 py-2">Jumlah Pelanggan</th>
                        <th class="text-start px-4 py-2">Pemasukan</th>
                        <th class="text-start px-4 py-2">Piutang Pelanggan</th>
                        <th class="text-center px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody class="border-b border-gray-300">
                    @forelse ($laporans as $laporan)
                        <tr>
                            <td class="px-4 py-2 text-start">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 text-start">{{ $laporan->nama_bulan }} {{ $laporan->periode_tahun }}</td>
                            <td class="px-4 py-2 text-start">{{ $laporan->jumlah_pelanggan }} Pelanggan</td>
                            <td class="px-4 py-2 text-start">Rp. {{ number_format($laporan->total_pemasukan, 0, ".", ",") }}</td>
                            <td class="px-4 py-2 text-start">Rp. {{ number_format($laporan->total_piutang, 0, ".", ",") }}</td> 
                            <td class="px-4 py-2 text-center">
                                <a href="{{ route('laporan.show', $laporan->id) }}" target="_blank">
                                    <button class="bg-yellow-400 px-3 py-1 rounded-lg hover:bg-yellow-600 hover:text-white cursor-pointer">Detail</button>
                                </a>
                                <form id="form-hapus-{{ $laporan->id }}" action="{{ route('laporan.destroy', $laporan->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmHapus('{{ $laporan->id }}')" class="bg-red-400 px-3 py-1 rounded-lg hover:bg-red-600 hover:text-white cursor-pointer">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <h1 class="p-4 outline-1 outline-red-600 bg-red-300 ">Data yang anda cari tidak ditemukan</h1>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layout>
 
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmHapus(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus data ?',
                text: "Data tidak dapat dikembalikan !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#395ff7',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-hapus-' + id).submit();
                }
            })
        }

    function formLaporan() {
        return {
            showForm: false,
            formMode: 'tambah',
            isLoading: false,
            formData: {
                id: null,
                bulan_laporan: '',
                tahun_laporan: '',
                catatan:'',
            },
            init() {
                this.isLoading = false;
            },
            tambahLaporan() {
                this.showForm = !this.showForm;
                if(!this.showForm){
                    this.formMode = 'tambah';
                    this.formData = {
                        id: null,
                        bulan_laporan: '',
                        tahun_laporan: '',
                        catatan:'',
                    }
                };
            },
        }
    }

    function clearForm(){
        const form = document.getElementById('form-laporan');
        form.reset();
    }
</script>
