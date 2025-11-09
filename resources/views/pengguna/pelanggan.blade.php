<x-layout :tittle="$tittle">
    <div>
        <h2 class="text-xl font-bold mb-4">Daftar Pelanggan</h2>
        <div> 
            <div 
            x-data="{ openFilter: {{ (request('komplek') || request('status_catat') || request('search')) ? 'true' : 'false' }} }"
            class="flex flex-col lg:flex-row w-full justify-between mb-4">
                {{-- Tombol Filter untuk sm/md --}}
                <div class="flex lg:hidden mb-2 w-44 mt-2">
                    <button @click="openFilter = !openFilter"
                            class="w-full px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        <span x-show="!openFilter">Filter Data</span>
                        <span x-show="openFilter">Tutup Filter</span>
                    </button>
                </div>

                {{-- Filter Form --}}
                <form method="GET" 
                action="{{ route('list_pelanggan') }}" 
                class="flex-col space-y-2 md:space-y-0 md:flex-row md:flex md:items-center lg:flex lg:flex-row lg:space-y-0 lg:space-x-2 w-full"
                :class="{ 'hidden': !openFilter }" 
                x-show="openFilter || window.innerWidth >= 1024" 
                x-transition
                >
                    @if(request('search') || request('komplek') || request('status_catat'))
                        <a href="{{ route('list_pelanggan') }}"
                            class="block w-24 px-4 py-2 bg-gray-300 text-slate-800 rounded hover:bg-gray-400 hover:text-black text-center">
                            Reset
                        </a>
                    @endif
                    <select name="komplek" id="komplek" class="text-gray-600 py-2 w-full lg:w-56 h-10 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600">
                        <option value="">Pilih Komplek</option>
                        <option value="Desa dan Delod Desa" {{ request('komplek') === 'Desa dan Delod Desa' ? 'selected' : '' }}>Desa dan Delod Desa</option>
                        <option value="Desa Anyar" {{ request('komplek') === 'Desa Anyar' ? 'selected' : '' }}>Desa Anyar</option>
                        <option value="Banjar Kaja dan Bucu" {{ request('komplek') === 'Banjar Kaja dan Bucu' ? 'selected' : '' }}>Banjar Kaja dan Bucu</option>
                        <option value="Banjar Dalem dan Boni" {{ request('komplek') === 'Banjar Dalem dan Boni' ? 'selected' : '' }}>Banjar Dalem dan Boni</option>
                        <option value="Banjar Bunut" {{ request('komplek') === 'Banjar Bunut' ? 'selected' : '' }}>Banjar Bunut</option>
                    </select>
                    @if (auth()->user()->role === 'Petugas Lapangan')
                        <select name="status_catat" id="status_catat" class="text-gray-600 py-2 lg:w-56 w-full h-10 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600">
                            <option value="">Pilih Status Catat</option>
                            <option value="Sudah Catat" {{ request('status_catat') === 'Sudah Catat' ? 'selected' : '' }}>Sudah Catat</option>
                            <option value="Belum Catat" {{ request('status_catat') === 'Belum Catat' ? 'selected' : '' }}>Belum Catat</option>
                        </select>
                    @endif
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari Data" class="w-full lg:w-80 py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600 placeholder:text-sm">
                    <button type="submit" class="ml-4 px-4 py-2 bg-blue-500 text-white hover:bg-blue-300 rounded-xl hover:outline-blue-700 hover:outline-2">
                        Cari
                    </button>
                </form>
            </div>
        </div>

        {{-- Tabel tampilan dekstop --}}
        <div class="hidden lg:block md:table w-full p-4 text-base text-slate-700 mt-5">
            <table class="w-full p-4">
                <thead class="border-b border-gray-300">
                    <tr class="">
                        <th class="text-start px-4 py-2 w-1/12">No.</th>
                        <th class="text-start px-4 py-2 w-3/12">Nama</th>
                        <th class="text-start px-4 py-2 w-2/12">Komplek</th>
                        <th class="text-start px-4 py-2 w-2/12">Nomor HP</th>
                        @if (auth()->user()->role === "Petugas Lapangan")
                            <th class="text-start px-4 py-2 w-2/12">Periode Saat ini</th>
                            <th class="text-center px-4 py-2 w-2/12">Status Catat Meteran</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($pelanggans as $pelanggan)
                        <tr class="border-b border-gray-300">
                            <td class="text-start px-4 py-1">{{ $loop->iteration }}</td>
                            <td class="text-start px-4 py-1">{{ $pelanggan->nama }}</td>
                            <td class="text-start px-4 py-1">{{ $pelanggan->komplek }}</td>
                            <td class="text-start px-4 py-1">{{ $pelanggan->no_hp }}</td>
                            @if (auth()->user()->role === "Petugas Lapangan")
                                <td class="text-start px-4 py-1">{{ $nama_bulan}} {{ $tahun }}</td>
                                <td class="text-center px-4 py-1">
                                    @if($pelanggan->penggunaan_airs->isNotEmpty())
                                        <div class="px-2 py-0.5 bg-green-300 outline outline-green-500 text-green-700 rounded-xl inline-block w-auto">Sudah Dicatat</div>
                                    @else
                                        <div class="px-2 py-0.5 bg-red-300 outline outline-red-500 text-red-700 rounded-xl inline-block w-auto">Belum Dicatat</div>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                <h1 class="p-4 outline-1 outline-red-600 bg-red-300 ">Data yang anda cari tidak ditemukan</h1>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Tabel tampilan mobile --}}
        <div class="lg:hidden md:hidden mt-10">
            @if (auth()->user()->role === "Petugas Lapangan")
                @forelse ($pelanggans as $pelanggan)
                    <div class="flex justify-between items-center bg-slate-100 shadow my-3 p-2">
                        <div>
                            <h1 class="font-bold text-sm text-slate-800"><span class=" px-1 py-1 rounded-xl mr-1">Nama : </span>{{ $pelanggan->nama }}</h1>
                            <h3 class="mt-2 text-sm font-semibold text-slate-700"><span class=" px-1 py-1 rounded-xl mr-1">Komplek : </span>{{ $pelanggan->komplek }}</h3>
                            <h3 class="mt-2 text-sm text-slate-700"><span class=" px-1 py-1 rounded-xl mr-1">Periode : </span>{{ $nama_bulan }} {{ $tahun }}</h3>
                            @if($pelanggan->penggunaan_airs->isNotEmpty())
                                <div class="px-2 my-1 py-0.5 bg-green-300 outline outline-green-500 text-green-700 rounded-xl inline-block w-auto">Sudah Dicatat</div>
                            @else
                                <div class="px-2 my-1 py-0.5 bg-red-300 outline outline-red-500 text-red-700 rounded-xl inline-block w-auto">Belum Dicatat</div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="flex justify-center items-center bg-slate-100 shadow my-3 p-2">
                        <div class="px-3 py-2 bg-red-300 outline outline-red-600 text-red-900">
                            Data Tidak ada
                        </div>
                    </div>
                @endforelse
            @else
                @forelse ($pelanggans as $pelanggan)
                    <div class="flex justify-between items-center bg-slate-100 shadow my-3 p-2">
                        <div>
                            <h1 class="font-bold text-sm text-slate-800"><span class=" px-1 py-1 rounded-xl mr-1">Nama : </span>{{ $pelanggan->nama }}</h1>
                            <h3 class="mt-2 text-sm font-semibold text-slate-700"><span class=" px-1 py-1 rounded-xl mr-1">Komplek : </span>{{ $pelanggan->komplek }}</h3>
                            <h3 class="mt-2 text-sm text-slate-700"><span class=" px-1 py-1 rounded-xl mr-1">No Hp : </span>{{ $pelanggan->no_hp }}</h3>
                        </div>
                    </div>
                @empty
                    <div class="flex justify-center items-center bg-slate-100 shadow my-3 p-2">
                        <div class="px-3 py-2 bg-red-300 outline outline-red-600 text-red-900">
                            Data Tidak ada
                        </div>
                    </div>
                @endforelse
            @endif        
        </div>

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

        function formPengguna() {
            return {
                showForm: false,
                formMode: 'tambah',
                isLoading: false,
                formData: {
                    id: null,
                    nama: '',
                    komplek: '',
                    role: '',
                    no_hp: '',
                    password: ''
                },
                init() {
                    this.isLoading = false;
                },
                tambahPengguna() {
                    this.showForm = !this.showForm;
                    if(!this.showForm){
                        this.formMode = 'tambah';
                        this.formData = {
                            id: null,
                            nama: '',
                            komplek: '',
                            role: '',
                            no_hp: '',
                            password: ''
                        }
                    };
                },
                editPengguna(pengguna) {
                    this.showForm = true;
                    this.formMode = 'edit';
                    this.formData = {
                        id: pengguna.id,
                        nama: pengguna.nama,
                        komplek: pengguna.komplek,
                        role: pengguna.role,
                        no_hp: pengguna.no_hp,
                        password: ''
                    };
                }
            }
        }

        function clearForm(){
            const form = document.getElementById('form-pengguna');
            form.reset();
        }

    
    </script>
</x-layout>