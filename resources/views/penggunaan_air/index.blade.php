<x-layout :tittle="$tittle">
    @livewire('penggunaan-air-detail')
    <h2 class="text-xl font-bold mb-4">Penggunaan Air</h2>
    {{-- Pembungkus seluruh halaman --}}
    <div>
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
        {{-- Pembungkus tombol cari dan tambah --}}
        <div 
        x-data="{ openFilter: {{ (request('periode_bulan') || request('periode_tahun') || request('search') || request()->has('status_bayar')) ? 'true' : 'false' }} }"
        class="flex flex-col lg:flex-row w-full justify-between mb-4">
            @auth
                @if (in_array(Auth::user()->role, ['Petugas Lapangan', 'Pelanggan']))
                    <div class="w-48">
                        <a href="penggunaan_air/create" class="group flex px-4 py-2 rounded transition-all duration-300 bg-blue-500 text-white hover:bg-blue-300 hover:outline-blue-700 hover:outline-2">
                            <svg class="group-hover:text-gray-800 w-6 h-6 text-white mr-2" 
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" 
                                d="M9 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H7Zm8-1a1 1 0 0 1 1-1h1v-1a1 1 0 1 1 2 0v1h1a1 1 0 1 1 0 2h-1v1a1 1 0 1 1-2 0v-1h-1a1 1 0 0 1-1-1Z" clip-rule="evenodd"/>                   
                            <h3 class="group-hover:text-gray-800">Tambah Data</h3>
                        </a>
                    </div>
                @else
                    <div></div>
                @endif
            @endauth

            {{-- Tombol Filter untuk sm/md --}}
            <div class="flex lg:hidden mb-2 w-44 mt-2">
                <button @click="openFilter = !openFilter"
                        class="w-full px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    <span x-show="!openFilter">Filter Data</span>
                    <span x-show="openFilter">Tutup Filter</span>
                </button>
            </div>

            {{-- Filter Form --}}            
            <form method="GET" action="{{ route('penggunaan_air.index') }}" 
                class="flex-col space-y-2 md:space-y-0 md:flex-row md:flex md:items-center lg:flex lg:flex-row lg:space-y-0 lg:space-x-2 w-full"
                :class="{ 'hidden': !openFilter }" 
                x-show="openFilter || window.innerWidth >= 1024" 
                x-transition>
                @if(request('periode_bulan') || request('periode_tahun') || request('search') || request('status_bayar') !== null)
                    <a href="{{ route('penggunaan_air.index') }}"
                        class="px-4 py-2 bg-gray-300 text-slate-800 rounded hover:bg-gray-400 hover:text-black">
                        Reset
                    </a>
                @endif
                <select name="status_bayar" id="status_bayar" class="text-gray-600 w-full lg:w-36 py-2 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600 text-sm">
                    <option value="" {{ request('status_bayar') === null ? 'selected' : '' }}>Status Bayar</option>
                    <option value="0" {{ request('status_bayar') === '0' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="1" {{ request('status_bayar') === '1' ? 'selected' : '' }}>Lunas</option>
                </select>
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
                <input type="number" name="periode_tahun" value="{{ request('periode_tahun') }}" placeholder="Masukan Periode Tahun" class="w-full lg:w-36 md:w-50 py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600 placeholder:text-sm">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari Data" class="w-full lg:w-44 md:w-80 py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600 placeholder:text-sm">
                <button type="submit" class="ml-4 px-4 py-2 bg-blue-500 text-white hover:bg-blue-300 rounded-xl hover:outline-blue-700 hover:outline-2">
                    Cari
                </button>
            </form>
        </div>
        {{-- Tampilan Halaman Dekstop --}}
        <div class="hidden lg:block md:table w-full p-4 text-base text-slate-700 mt-5">
            <table class="w-full p-4">
                <thead class="border-b border-gray-300">
                    <tr class="">
                        <th class="text-start px-4 py-2">No.</th>
                        <th class="text-start px-4 py-2">Nama Pelanggan</th>
                        <th class="text-start px-4 py-2">Konsumsi</th>
                        <th class="text-start px-4 py-2">Tanggal Catat</th>
                        <th class="text-start px-4 py-2">Periode</th>
                        <th class="text-center px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($penggunaan_airs as $penggunaan_air)
                        <tr class="border-b border-gray-300">
                            <td class="text-start px-4 py-1">{{ $loop->iteration }}</td>
                            <td class="text-start px-4 py-1">{{ $penggunaan_air->penggunas->nama ?? '-' }}</td>
                            <td class="text-start px-4 py-1">{{ $penggunaan_air->konsumsi }} m<sup>3</sup></td>
                            <td class="text-start px-4 py-1">{{ $penggunaan_air->tanggal_catat_indo }}</td>
                            <td class="text-start px-4 py-1">{{ $penggunaan_air->nama_bulan }} {{ $penggunaan_air->periode_tahun }}</td>
                            <td class="text-center px-4 py-1">
                                <button 
                                    x-data 
                                    @click="
                                        Swal.fire({
                                            title: 'Memuat...',
                                            didOpen: () => { Swal.showLoading() }
                                        });
                                        $dispatch('show-detail', ['{{ $penggunaan_air->id }}'])
                                    " 
                                    class="bg-yellow-400 px-3 py-1 rounded-lg hover:bg-yellow-600 hover:text-white cursor-pointer">
                                    Detail
                                </button>
                                @can('update', $penggunaan_air)
                                    <a href="{{ route('penggunaan_air.edit', $penggunaan_air->id)}}">
                                        <button class="cursor-pointer px-1 py-1 hover:bg-sky-700 hover:text-white bg-sky-400 rounded-lg shadow">Edit</button>
                                    </a>
                                @endcan
                                @can('delete', $penggunaan_air)
                                    <form id="form-hapus-{{ $penggunaan_air->id }}" action="{{ route('penggunaan_air.destroy', $penggunaan_air) }}" method="POST" class="inline ">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmHapus('{{ $penggunaan_air->id }}')" class="cursor-pointer">
                                            <div class="px-1 py-1 bg-red-400 hover:bg-red-700 hover:text-white rounded-lg shadow">Hapus</div>
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                <h1 class="p-4 outline-1 outline-red-600 bg-red-300 ">Data tidak ditemukan</h1>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Tabel tampilan mobile --}}
        <div class="lg:hidden md:hidden mt-10">
            @forelse($penggunaan_airs as $penggunaan_air)
                <div class="flex justify-between items-center bg-slate-100 shadow my-3 p-2">
                    <div>
                        <h1 class="font-bold text-slate-800"><span class=" px-1 py-1 rounded-xl mr-1">Nama : </span>{{ $penggunaan_air->penggunas->nama }}</h1>
                        <h3 class="mt-2 font-semibold text-slate-700"><span class=" px-1 py-1 rounded-xl mr-1">Penggunaan Air : </span>{{ $penggunaan_air->konsumsi }} m<sup>3</sup></h3>
                        <h3 class="mt-2 text-sm text-slate-700"><span class=" px-1 py-1 rounded-xl mr-1">Tanggal Catat : </span>{{ $penggunaan_air->tanggal_catat_indo }}</h3>
                        <h3 class="mt-2 text-sm text-slate-700"><span class=" px-1 py-1 rounded-xl mr-1">Periode : </span>{{ $penggunaan_air->nama_bulan }} {{ $penggunaan_air->periode_tahun }}</h3>
                    </div>
                    <div class="flex group ">

                        <button 
                            x-data 
                            @click="
                                Swal.fire({
                                    title: 'Memuat...',
                                    didOpen: () => { Swal.showLoading() }
                                });
                                $dispatch('show-detail', ['{{ $penggunaan_air->id }}'])
                            " 
                            class="cursor-pointer">
                            <svg class="w-8 h-8 text-orange-400 hover:text-orange-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 14v4.833A1.166 1.166 0 0 1 16.833 20H5.167A1.167 1.167 0 0 1 4 18.833V7.167A1.166 1.166 0 0 1 5.167 6h4.618m4.447-2H20v5.768m-7.889 2.121 7.778-7.778"/>
                            </svg>                              
                        </button>
                        @can('update', $penggunaan_air)
                            <a href="{{ route('penggunaan_air.edit', $penggunaan_air->id)}}">
                                <svg class="hover:text-blue-900 w-8 h-8 text-blue-600 mr-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M5 8a4 4 0 1 1 7.796 1.263l-2.533 2.534A4 4 0 0 1 5 8Zm4.06 5H7a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h2.172a2.999 2.999 0 0 1-.114-1.588l.674-3.372a3 3 0 0 1 .82-1.533L9.06 13Zm9.032-5a2.907 2.907 0 0 0-2.056.852L9.967 14.92a1 1 0 0 0-.273.51l-.675 3.373a1 1 0 0 0 1.177 1.177l3.372-.675a1 1 0 0 0 .511-.273l6.07-6.07a2.91 2.91 0 0 0-.944-4.742A2.907 2.907 0 0 0 18.092 8Z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                        @endcan
                        @can('delete', $penggunaan_air)
                            <form id="form-hapus-{{ $penggunaan_air->id }}" action="{{ route('penggunaan_air.destroy', $penggunaan_air) }}" method="POST" class="inline ">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmHapus('{{ $penggunaan_air->id }}')" class="cursor-pointer">
                                    <svg class="w-8 h-8 hover:text-red-900 text-red-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </form>  
                        @endcan
                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="6" class="text-center">
                        <h1 class="p-4 outline-1 outline-red-600 bg-red-300 ">Data tidak ditemukan</h1>
                    </td>
                </tr>
            @endforelse
        </div>
    </div> 
    
    {{-- Sweetalert --}}
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
    </script>
</x-layout>