<x-layout :tittle="$tittle">
    <div>
        <h2 class="text-slate-700 text-xl font-bold mb-4">Daftar Catatan Jurnal</h2>
        {{-- Pembungkus tombol cari dan tambah --}}
        <div class="flex w-full justify-between mb-4">
            <form method="GET" action="{{ route('jurnal.index') }}" class="flex flex-col space-y-2 sm:flex-row sm:flex-wrap sm:space-y-2 sm:space-x-2 w-full">
                @if(request('search') || request('periode_bulan') || request('akun_d') || request('akun_k'))
                    <a href="{{ route('jurnal.index') }}"
                        class="w-full sm:w-auto px-4 py-2 bg-gray-300 text-slate-800 rounded hover:bg-gray-400 hover:text-black text-center">
                        Reset
                    </a>
                @endif
                <select name="periode_bulan" id="periode_bulan" class="w-full sm:w-44 text-gray-600 py-2 ps-2 border border-slate-400 rounded-xl focus:outline-sky-600 text-sm">
                    <option value="">Pilih Periode Bulan</option>
                    <option value=1 {{ request('periode_bulan') == 1 ? 'selected' : '' }}>Januari</option>
                    <option value=2 {{ request('periode_bulan') == 2 ? 'selected' : '' }}>Februari</option>
                    <option value=3 {{ request('periode_bulan') == 3 ? 'selected' : '' }}>Mare</option>
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
                
                <input type="number" name="periode_tahun" value="{{ request('periode_tahun') }}" placeholder="Periode Tahun" class="w-full sm:w-44 py-1.5 ps-2 border border-slate-400 rounded-xl focus:outline-sky-600 placeholder:text-sm"> 
                <select name="akun_d" id="akun_d" class="w-full sm:w-44 text-gray-600 py-2 ps-2 border border-slate-400 rounded-xl focus:outline-sky-600 text-sm"> 
                    <option value="">Pilih Akun Debit</option>
                    @foreach($akuns as $akun)
                        <option value="{{ $akun->id }}" {{ request('akun_d') == $akun->id ? 'selected' : '' }}>
                            {{ $akun->nama }}
                        </option>
                    @endforeach
                </select> 
                <select name="akun_k" id="akun_k" class="w-full sm:w-44 text-gray-600 py-2 ps-2 border border-slate-400 rounded-xl focus:outline-sky-600 text-sm">
                    <option value="">Pilih Akun Kredit</option>
                    @foreach($akuns as $akun)
                        <option value="{{ $akun->id }}" {{ request('akun_k') == $akun->id ? 'selected' : '' }}>
                            {{ $akun->nama }}
                        </option>
                    @endforeach
                </select>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari Data" class="w-full sm:w-80 py-1.5 ps-2 border border-slate-400 rounded-xl focus:outline-sky-600 placeholder:text-sm">
                <button type="submit" class="ml-4 px-4 py-2 bg-blue-500 text-white hover:bg-blue-300 rounded-xl hover:outline-blue-700 hover:outline-2">
                    Cari
                </button>
            </form>
        </div>
        <div>
        </div>
        {{-- Tampilan Halaman Dekstop --}}
        <div class="lg:block md:table w-full p-4 text-base text-slate-700 mt-5">
            <table class="border-collapse border border-gray-300 w-full p-4 text-sm">
                <thead class="border border-gray-300">
                    <tr class="border border-gray-300">
                        <th class="border border-gray-300 text-start px-4 py-2">No.</th>
                        <th class="border border-gray-300 text-start px-4 py-2">Tanggal</th>
                        <th class="border border-gray-300 text-start px-4 py-2">Akun</th>
                        <th class="border border-gray-300 text-start px-4 py-2">Keterangan</th>
                        <th class="border border-gray-300 text-start px-4 py-2">Debit</th>
                        <th class="border border-gray-300 text-start px-4 py-2">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jurnals as $jurnal)
                        <tr class="border border-gray-300">
                            <td class="border border-gray-300 text-start px-4 py-1" rowspan="2">{{ $loop->iteration }}</td>
                            <td class="border border-gray-300 text-start px-4 py-1 font-bold" rowspan="2">{{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('d F Y') }}</td>
                            <td class="border border-gray-300 text-start px-4 py-1">{{ $jurnal->debitAccount->nama }}</td>
                            <td class="border border-gray-300 text-start px-4 py-1" rowspan="2">{{ $jurnal->deskripsi }}</td>
                            <td class="border border-gray-300 text-start px-4 py-1">Rp. {{ number_format($jurnal->nominal, 0, ',', '.') }}</td>
                            <td class="border border-gray-300 text-start px-4 py-1">-</td>
                        </tr>
                        <tr class="border border-gray-300">
                            <td class="border border-gray-300 text-start px-4 py-1">{{ $jurnal->kreditAccount->nama }}</td>
                            <td class="border border-gray-300 text-start px-4 py-1">-</td>
                            <td class="border border-gray-300 text-start px-4 py-1">Rp. {{ number_format($jurnal->nominal, 0, ',', '.') }}</td>
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