<x-layout :tittle="$tittle">
    <div x-data="formAkun()" x-init="init()">
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
            <div class="flex w-full justify-between mb-4">
                <div>
                    <button  @click="tambahAkun()"
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
                {{-- Pembungkus tombol cari dan tambah --}}
                <form method="GET" action="{{ route('akun.index') }}" class="flex mb-4">
                    @if(request('search'))
                        <a href="{{ route('akun.index') }}"
                            class="px-4 py-2 bg-gray-300 text-slate-800 rounded hover:bg-gray-400 hover:text-black">
                            Reset
                        </a>
                    @endif
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari Data" class="w-36 md:w-80 py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600 placeholder:text-sm">
                    <button type="submit" class="ml-4 px-4 py-2 bg-blue-500 text-white hover:bg-blue-300 rounded-xl hover:outline-blue-700 hover:outline-2">
                        Cari
                    </button>
                </form>
            </div>

            {{-- Form tambah dan edit akun --}}
            <form 
            :action="formMode === 'edit' ? '{{ url('keuangan/akun') }}/' + formData.id : '{{ route('akun.store') }}'" method="POST" class="mt-5" x-show="showForm" x-transition id="form-akun" @submit="isLoading = true">
                @csrf
                <template x-if="formMode === 'edit'">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                <div class="flex flex-wrap items-center gap-4">
                    <div>
                        <label for="kode" class="block ml-2 mb-2 font-semibold text-slate-700">Kode</label>
                        <input type="text" id="kode" x-ref="kode" name="kode" x-model="formData.kode" placeholder="Masukan kode akun" 
                        :class="formMode === 'edit' ? 'py-1.5 ps-2 ml-2 bg-slate-400 rounded-xl' : 'py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600 placeholder:text-sm'" :readonly="formMode === 'edit'" required>
                    </div>
                    <div>
                        <label for="nama" class="block ml-2 mb-2 font-semibold text-slate-700">Nama Akun</label>
                        <input type="text" id="nama" x-ref="nama" name="nama" x-model="formData.nama" placeholder="Masukan nama akun" class="py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600 placeholder:text-sm" required>
                    </div>
                    <div>
                        <label for="tipe" class="block ml-2 mb-2 font-semibold text-slate-700">Tipe</label>
                        <select name="tipe" x-ref="tipe" name="tipe" id="tipe" x-model="formData.tipe" class="ml-2 ps-2 text-sm text-slate-700 py-2 px-4 border border-slate-400 rounded-xl focus:outline-sky-600" required>
                            <option value="">-- Pilih Tipe Akun --</option>
                            <option value="aset">Aset</option>
                            <option value="pendapatan">Pendapatan</option>
                        </select>
                    </div>
                    <div>
                        <label for="keterangan" class="block ml-2 mb-2 font-semibold text-slate-700">Keterangan</label>
                        <input type="text" id="keterangan" x-ref="keterangan" name="keterangan" x-model="formData.keterangan" placeholder="Masukan keterangan" class="py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600 placeholder:text-sm" required>
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
        {{-- Tampilan Halaman Dekstop --}}
        <div class="hidden lg:block md:table w-full p-4 text-base text-slate-700 mt-5">
            <table class="w-full p-4">
                <thead class="border-b border-gray-300">
                    <tr class="">
                        <th class="text-start px-4 py-2">No.</th>
                        <th class="text-start px-4 py-2">Kode</th>
                        <th class="text-start px-4 py-2">Nama</th>
                        <th class="text-start px-4 py-2">Tipe</th>
                        <th class="text-start px-4 py-2">Keterangan</th>
                        <th class="text-center px-4 py-2">Saldo</th>
                        <th class="text-center px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($akuns as $akun)
                        <tr class="border-b border-gray-300">
                            <td class="text-start px-4 py-1">{{ $loop->iteration }}</td>
                            <td class="text-start px-4 py-1 font-bold">{{ $akun->kode }}</td>
                            <td class="text-start px-4 py-1">{{ $akun->nama }}</td>
                            <td class="text-start px-4 py-1">
                                @if ($akun->tipe == 'aset')
                                    <span class="px-2 py-1 rounded-full text-sm font-medium bg-green-200 outline-1 outline-green-700 text-green-900">Aset</span>
                                @elseif ($akun->tipe == 'pendapatan')
                                    <span class="px-2 py-1 rounded-full text-sm font-medium bg-orange-200 outline-1 outline-orange-600 text-orange-800">Pendapatan</span>
                                @endif
                            </td>
                            <td class="text-start px-4 py-1">{{ $akun->keterangan }}</td>
                            <td class="text-start px-4 py-1">Rp. {{ number_format($akun->saldo, 0, ',', '.') }}</td>
                            <td class="text-center px-4 py-1">
                                <button @click='editAkun(@json($akun))'
                                class="cursor-pointer px-1 py-1 hover:bg-sky-700 hover:text-white bg-sky-400 rounded-lg shadow">Edit</button>

                                <form id="form-hapus-{{ $akun->id }}" action="{{ route('akun.destroy', $akun) }}" method="POST" class="inline ">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmHapus('{{ $akun->id }}')" class="cursor-pointer">
                                        <div class="px-1 py-1 bg-red-400 hover:bg-red-700 hover:text-white rounded-lg shadow">Hapus</div>
                                    </button>
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

        {{-- Tabel tampilan mobile --}}
        {{-- <div class="lg:hidden md:hidden mt-10">
            @foreach($penggunaan_airs as $penggunaan_air)
                <div class="flex justify-between items-center bg-slate-100 shadow my-3 p-4">
                    <div>
                        <h1 class="text-xl font-bold text-slate-800"><span class=" px-1 py-1 rounded-xl mr-1">Nama : </span>{{ $penggunaan_air->penggunas->nama }}</h1>
                        <h3 class="mt-2 text-lg font-semibold text-slate-700"><span class=" px-1 py-1 rounded-xl mr-1">Penggunaan Air : </span>{{ $penggunaan_air->konsumsi }} m<sup>3</sup></h3>
                        <h3 class="mt-2 text-base text-slate-700"><span class=" px-1 py-1 rounded-xl mr-1">Tanggal Catat : </span>{{ $penggunaan_air->tanggal_catat }} - {{ $penggunaan_air->bulan }} - {{ $penggunaan_air->tahun }}</h3>
                    </div>
                    <div class="flex group ">
                        <a href="{{ route('penggunaan_air.edit', $penggunaan_air->id)}}">
                            <svg class="hover:text-blue-900 w-8 h-8 text-blue-600 mr-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M5 8a4 4 0 1 1 7.796 1.263l-2.533 2.534A4 4 0 0 1 5 8Zm4.06 5H7a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h2.172a2.999 2.999 0 0 1-.114-1.588l.674-3.372a3 3 0 0 1 .82-1.533L9.06 13Zm9.032-5a2.907 2.907 0 0 0-2.056.852L9.967 14.92a1 1 0 0 0-.273.51l-.675 3.373a1 1 0 0 0 1.177 1.177l3.372-.675a1 1 0 0 0 .511-.273l6.07-6.07a2.91 2.91 0 0 0-.944-4.742A2.907 2.907 0 0 0 18.092 8Z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                        <form id="form-hapus-{{ $penggunaan_air->id }}" action="{{ route('penggunaan_air.destroy', $penggunaan_air) }}" method="POST" class="inline ">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmHapus('{{ $penggunaan_air->id }}')" class="cursor-pointer">
                                <svg class="w-8 h-8 hover:text-red-900 text-red-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </form>
                        
                    </div>
                </div>
            @endforeach
        </div> --}}
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

        function formAkun() {
            return {
                showForm: false,
                formMode: 'tambah',
                isLoading: false,
                formData: {
                    id: null,
                    kode: '',
                    nama: '',
                    tipe: '',
                    keterangan: ''
                },
                init() {
                    this.isLoading = false;
                },
                tambahAkun() {
                    this.showForm = !this.showForm;
                    if(!this.showForm){
                        this.formMode = 'tambah';
                        this.formData = {
                            id: null,
                            kode: '',
                            nama: '',
                            tipe: '',
                            keterangan: ''
                        }
                    };
                },
                editAkun(akun) {
                    this.showForm = true;
                    this.formMode = 'edit';
                    this.formData = {
                        id: akun.id,
                        kode: akun.kode,
                        nama: akun.nama,
                        tipe: akun.tipe,
                        keterangan: akun.keterangan
                    };
                }
            }
        }

        function clearForm(){
            const form = document.getElementById('form-akun');
            form.reset();
        }

    
    </script>
</x-layout>