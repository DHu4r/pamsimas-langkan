<x-layout :tittle="$tittle">
    <div x-data="formPengguna()" x-init="init()">
        <h2 class="text-xl font-bold mb-4">Daftar Pengguna</h2>
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
        <div>
            <div 
            x-data="{ openFilter: {{ request('search') || request('role') || request('komplek') ? 'true' : 'false' }} }"
            class="flex flex-col lg:flex-row w-full justify-between mb-4 space-x-1 space-y-1">
                <div>
                    <button  @click="tambahPengguna()"
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
                @if(request('search') || request('role') || request('komplek'))
                    <a href="{{ route('pengguna.index') }}">
                        <div class="w-30 text-center px-4 py-2 bg-gray-300 text-slate-800 rounded hover:bg-gray-400 hover:text-black">
                            Reset
                        </div>
                    </a>
                @endif 
                <form method="GET" action="{{ route('pengguna.index') }}" 
                class="flex-col space-y-2 md:space-y-0 md:flex-row md:flex lg:flex lg:flex-row lg:space-y-0 lg:space-x-2 w-full"
                :class="{ 'hidden': !openFilter }" 
                x-show="openFilter || window.innerWidth >= 1024" 
                x-transition>
                    <select name="role" id="role" class="text-gray-600 py-2 lg:w-44 w-full h-10 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600">
                        <option value="">Pilih Role</option>
                        <option value="Petugas Lapangan" {{ request('role') === 'Petugas Lapangan' ? 'selected' : '' }}>Petugas Lapangan</option>
                        <option value="Pengurus" {{ request('role') === 'Pengurus' ? 'selected' : '' }}>Pengurus</option>
                        <option value="Pelanggan" {{ request('role') === 'Pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                        <option value="Mitra Pembayaran" {{ request('role') === 'Mitra Pembayaran' ? 'selected' : '' }}>Mitra Pembayaran</option>
                    </select>
                    <select name="komplek" id="komplek" class="text-gray-600 py-2 lg:w-56 w-full h-10 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600">
                        <option value="">Pilih Komplek</option>
                        <option value="Desa dan Delod Desa" {{ request('komplek') === 'Desa dan Delod Desa' ? 'selected' : '' }}>Desa dan Delod Desa</option>
                        <option value="Desa Anyar" {{ request('komplek') === 'Desa Anyar' ? 'selected' : '' }}>Desa Anyar</option>
                        <option value="Banjar Kaja dan Bucu" {{ request('komplek') === 'Banjar Kaja dan Bucu' ? 'selected' : '' }}>Banjar Kaja dan Bucu</option>
                        <option value="Banjar Dalem dan Boni" {{ request('komplek') === 'Banjar Dalem dan Boni' ? 'selected' : '' }}>Banjar Dalem dan Boni</option>
                        <option value="Banjar Bunut" {{ request('komplek') === 'Banjar Bunut' ? 'selected' : '' }}>Banjar Bunut</option>
                    </select>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari Data" class="w-full md:w-80 py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600 placeholder:text-sm">
                    <button type="submit" class="ml-4 px-4 py-2 bg-blue-500 text-white hover:bg-blue-300 rounded-xl hover:outline-blue-700 hover:outline-2">
                        Cari
                    </button>
                </form>
            </div>
            
            {{-- Form Tambah dan edit Pengguna --}}
            <form 
            :action="formMode === 'edit' ? '{{ url('pengguna') }}/' + formData.id : '{{ route('pengguna.store') }}'" method="POST" class="mt-5" x-show="showForm" x-transition id="form-pengguna" @submit="isLoading = true">
                @csrf
                <template x-if="formMode === 'edit'">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                <div class="flex flex-wrap items-center gap-4">
                    <div>
                        <label for="nama" class="block ml-2 mb-2 font-semibold text-slate-700">Nama</label>
                        <input type="text" id="nama" x-ref="nama" name="nama" x-model="formData.nama" placeholder="Masukan nama pengguna" class="py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600 placeholder:text-sm"
                        required
                        >
                    </div>
                    <div>
                        <label for="role" class="block ml-2 mb-2 font-semibold text-slate-700">Role</label>
                        <select name="role" x-ref="role" name="role" id="role" x-model="formData.role" class="ml-2 ps-2 text-sm text-slate-700 py-2 px-4 border border-slate-400 rounded-xl focus:outline-sky-600" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="Pelanggan">Pelanggan</option>
                            <option value="Petugas Lapangan">Petugas Lapangan</option>
                            <option value="Mitra Pembayaran">Mitra Pembayaran</option>
                            <option value="Pengurus">Pengurus</option>
                        </select>
                    </div>
                    <div>
                        <label for="no_hp" class="block ml-2 mb-2 font-semibold text-slate-700">No. HP</label>
                        <input type="text" x-ref="no_hp" name="no_hp" placeholder="Masukan No. HP" id="no_hp" x-model="formData.no_hp" class="py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600 placeholder:text-sm" required>
                    </div>
                    <div>    
                        <label for="komplek" class="block ml-2 mb-2 font-semibold text-slate-700">Komplek</label>
                        <select name="komplek" x-ref="komplek" id="komplek" x-model="formData.komplek" class="ml-2 ps-2 text-sm text-slate-700 py-2 px-4 border border-slate-400 rounded-xl focus:outline-sky-600" required>
                            <option value="">-- Pilih Komplek --</option>
                            <option value="Banjar Kaja dan Bucu">Banjar Kaja dan Bucu</option>
                            <option value="Desa Anyar">Desa Anyar</option>
                            <option value="Banjar Dalem dan Boni">Banjar Dalem dan Boni</option>
                            <option value="Desa dan Delod Desa">Desa dan Delod Desa</option>
                            <option value="Banjar Bunut">Banjar Bunut</option>
                        </select>
                    </div>
                    <div>
                        <label for="password" class="block ml-2 mb-2 font-semibold text-slate-700">Password</label>
                        <input type="password" x-ref="password" name="password" placeholder="Masukan Password" id="password" class="py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600 placeholder:text-sm"
                        :required="formMode === 'tambah'"                        
                        >
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

        {{-- Tabel tampilan dekstop --}}
        <div class="hidden lg:block md:table w-full p-4 text-base text-slate-700 mt-5">
            <table class="w-full p-4">
                <thead class="border-b border-gray-300">
                    <tr class="">
                        <th class="text-start px-4 py-2 w-1/12">No.</th>
                        <th class="text-start px-4 py-2 w-3/12">Nama</th>
                        <th class="text-start px-4 py-2 w-2/12">Komplek</th>
                        <th class="text-start px-4 py-2 w-2/12">Nomor HP</th>
                        <th class="text-start px-4 py-2 w-2/12">Role</th>
                        <th class="text-center px-4 py-2 w-2/12"><span class="text-blue-600">Perbaharui</span> | <span class="text-red-600">Hapus</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penggunas as $pengguna)
                        <tr class="border-b border-gray-300">
                            <td class="text-start px-4 py-1">{{ $loop->iteration }}</td>
                            <td class="text-start px-4 py-1">{{ $pengguna->nama }}</td>
                            <td class="text-start px-4 py-1">{{ $pengguna->komplek }}</td>
                            <td class="text-start px-4 py-1">{{ $pengguna->no_hp }}</td>
                            <td class="text-start px-4 py-1">
                                @php
                                    $roleColorMap = [
                                        'Pelanggan' => 'bg-green-200 text-green-800',
                                        'Petugas Lapangan' => 'bg-yellow-200 text-yellow-800',
                                        'Mitra Pembayaran' => 'bg-blue-200 text-blue-800',
                                        'Pengurus' => 'bg-gray-200 text-gray-800',
                                    ];

                                    $label = $pengguna->role; // sekarang langsung label dari DB, contoh: 'Pelanggan'
                                    $warna = $roleColorMap[$label] ?? 'bg-slate-200 text-slate-800';
                                @endphp

                                <span class="px-2 py-1 rounded-full text-sm font-medium {{ $warna }}">
                                    {{ $label }}
                                </span>
                            </td>
                            <td class="flex justify-center text-start px-4 py-1">
                                @can('update', $pengguna)
                                    <button @click='editPengguna(@json($pengguna))' class="text-blue-600 mr-5">
                                        <svg class="hover:text-blue-900 w-8 h-8 text-blue-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M5 8a4 4 0 1 1 7.796 1.263l-2.533 2.534A4 4 0 0 1 5 8Zm4.06 5H7a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h2.172a2.999 2.999 0 0 1-.114-1.588l.674-3.372a3 3 0 0 1 .82-1.533L9.06 13Zm9.032-5a2.907 2.907 0 0 0-2.056.852L9.967 14.92a1 1 0 0 0-.273.51l-.675 3.373a1 1 0 0 0 1.177 1.177l3.372-.675a1 1 0 0 0 .511-.273l6.07-6.07a2.91 2.91 0 0 0-.944-4.742A2.907 2.907 0 0 0 18.092 8Z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                @endcan
                                @can('delete', $pengguna)
                                    <form id="form-hapus-{{ $pengguna->id }}" action="{{ route('pengguna.destroy', $pengguna->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmHapus('{{ $pengguna->id }}')" class="text-red-600">
                                            <svg class="w-8 h-8 hover:text-red-900 text-red-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endcan
                            </td>
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
            @foreach($penggunas as $pengguna)
                <div class="flex justify-between items-center bg-slate-100 shadow my-3 p-4">
                    <div>
                        <h1 class="text-xl font-bold text-slate-800">{{ $pengguna->nama }}</h1>
                        <h3 class="text-lg font-semibold text-slate-700">{{ $pengguna->komplek }}</h3>
                        <h3 class="text-base text-slate-700">{{ $pengguna->no_hp }}</h3>
                    </div>
                    <div class="flex group ">
                        <botton @click='editPengguna(@json($pengguna))'>
                            <svg class="hover:text-blue-900 w-8 h-8 text-blue-600 mr-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M5 8a4 4 0 1 1 7.796 1.263l-2.533 2.534A4 4 0 0 1 5 8Zm4.06 5H7a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h2.172a2.999 2.999 0 0 1-.114-1.588l.674-3.372a3 3 0 0 1 .82-1.533L9.06 13Zm9.032-5a2.907 2.907 0 0 0-2.056.852L9.967 14.92a1 1 0 0 0-.273.51l-.675 3.373a1 1 0 0 0 1.177 1.177l3.372-.675a1 1 0 0 0 .511-.273l6.07-6.07a2.91 2.91 0 0 0-.944-4.742A2.907 2.907 0 0 0 18.092 8Z" clip-rule="evenodd"/>
                            </svg>
                        </botton>
                        <form id="form-hapus-{{ $pengguna->id }}" action="{{ route('pengguna.destroy', $pengguna->id) }}" method="POST" class="inline">
                            <button type="button" onclick="confirmHapus('{{ $pengguna->id }}')">
                                <svg class="w-8 h-8 hover:text-red-900 text-red-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </form>
                        
                    </div>
                </div>
            @endforeach
        </div>
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