<x-layout :tittle="$tittle">
    @livewire('proses-pembayaran')
    @livewire('tagihan-air-detail')
    @livewire('proses-pembayaran-transfer')
    {{-- Pembungkus seluruh halaman --}}
    <h2 class="text-xl font-bold mb-4">Tagihan Air Pelanggan</h2>
    <div>
        {{-- Pembungkus tombol cari dan tambah --}}
        <div x-data="{ openFilter: {{ (request('periode_bulan') || request('periode_tahun') || request('search') || request()->has('status_bayar')) ? 'true' : 'false' }} }"
        class="flex flex-col lg:flex-row w-full justify-between mb-4">
            {{-- <div></div> --}}

            {{-- Tombol Filter untuk sm/md --}}
            <div class="flex lg:hidden mb-2 w-44 mt-2">
                <button @click="openFilter = !openFilter"
                        class="w-full px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    <span x-show="!openFilter">Filter Data</span>
                    <span x-show="openFilter">Tutup Filter</span>
                </button>
            </div>

            <form method="GET" action="{{ route('tagihan_air.index') }}" 
            class="flex-col space-y-2 md:space-y-0 md:flex-row md:flex md:items-center lg:flex lg:flex-row lg:space-y-0 lg:space-x-2 w-full"
            :class="{ 'hidden': !openFilter }" 
            x-show="openFilter || window.innerWidth >= 1024" 
            x-transition>
                @if(request('periode_bulan') || request('periode_tahun') || request('search') || request('status_bayar') !== null)
                    <a href="{{ route('tagihan_air.index') }}">
                        <div class="w-30 px-4 py-2 bg-gray-300 text-slate-800 rounded hover:bg-gray-400 hover:text-black text-center mb-2">
                            Reset
                        </div>
                    </a>
                @endif
                <select name="status_bayar" id="status_bayar" class="text-sm text-gray-600 py-2 lg:w-36 w-full h-10 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600">
                    <option value="" {{ request('status_bayar') === null ? 'selected' : '' }}>Status Bayar</option>
                    <option value="0" {{ request('status_bayar') === '0' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="1" {{ request('status_bayar') === '1' ? 'selected' : '' }}>Lunas</option>
                </select>
                <select name="periode_bulan" id="periode_bulan" class="text-sm text-gray-600 py-2 lg:w-44 w-full ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600">
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
                <input type="number" name="periode_tahun" value="{{ request('periode_tahun') }}" placeholder="Masukan Periode Tahun" class="lg:w-36 md:w-50 w-full py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600 placeholder:text-sm">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari Data" class="md:mt-0 w-full lg:w-44 md:w-80 py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600 placeholder:text-sm">
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
                        <th class="text-start px-4 py-2">Status Pembayaran</th>
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
                            <td class="text-start px-4 py-1">
                                @if ($penggunaan_air->sudah_bayar)
                                    <span class="px-2 py-1 rounded-full text-sm font-medium bg-green-200 outline-1 outline-green-700 text-green-900">Lunas</span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-sm font-medium bg-red-200 outline-1 outline-red-700 text-red-900">Belum Bayar</span>
                                @endif
                            </td>
                            <td class="text-start px-4 py-1">{{ $penggunaan_air->nama_bulan }} {{ $penggunaan_air->periode_tahun }}</td>
                            <td class="text-center px-4 py-1">
                                @if (!$penggunaan_air->sudah_bayar)
                                    <button 
                                        x-data 
                                        @click="
                                            Swal.fire({
                                                title: 'Memuat...',
                                                didOpen: () => { Swal.showLoading() }
                                            });
                                            $dispatch('show-tagihan-detail', ['{{ $penggunaan_air->id }}'])
                                        " 
                                        class="bg-yellow-400 my-1 px-3 py-1 rounded-lg hover:bg-yellow-600 hover:text-white cursor-pointer">
                                        Detail
                                    </button>
                                    <button 
                                        x-data 
                                        @click="
                                        Swal.fire({
                                            title: 'Memuat Pembayaran ... ',
                                            didOpen: () => { Swal.showLoading() }
                                        });
                                        @if (auth()->user()->role === "Pengurus")
                                           $dispatch('buka-modal-pembayaran', {             penggunaan_air_id: '{{ $penggunaan_air->id }}' })
                                            "
                                        @else
                                            $dispatch('buka-modal-pembayaran-transfer', {             penggunaan_air_id: '{{ $penggunaan_air->id }}' })
                                            "
                                        @endif
                                        class="cursor-pointer px-4 py-1 hover:bg-emerald-700 hover:text-white bg-emerald-400 rounded-lg shadow">Bayar
                                    </button>
                                @else
                                    <button 
                                    x-data
                                    @click="window.open('{{ route('cetak.rekening', ['id' => $penggunaan_air->id]) }}', '_blank')"
                                    class="bg-sky-400 my-1 px-3 py-1 rounded-lg hover:bg-sky-600 hover:text-white cursor-pointer">
                                        Cetak
                                    </button>
                                    <button 
                                        x-data 
                                        @click="
                                            Swal.fire({
                                                title: 'Memuat...',
                                                didOpen: () => { Swal.showLoading() }
                                            });
                                            $dispatch('show-tagihan-detail', ['{{ $penggunaan_air->id }}'])
                                        " 
                                        class="bg-yellow-400 my-1 px-3 py-1 rounded-lg hover:bg-yellow-600 hover:text-white cursor-pointer">
                                        Detail
                                    </button>
                                @endif
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
                        <h1 class="text-sm font-bold text-slate-800"><span class=" px-1 py-1 rounded-xl mr-1">Nama : </span>{{ $penggunaan_air->penggunas->nama }}</h1>
                        <h3 class="mt-2 text-base font-semibold text-slate-700"><span class=" px-1 py-1 rounded-xl mr-1">Konsumsi Air : </span>{{ $penggunaan_air->konsumsi }} m<sup>3</sup></h3>
                        <h3 class="mt-2 text-sm text-slate-700"><span class=" px-1 py-1 rounded-xl mr-1">Tgl. Catat : </span>{{ $penggunaan_air->tanggal_catat_indo }}</h3>
                        <h3 class="ml-1 mt-2 text-base">
                            @if ($penggunaan_air->sudah_bayar)
                                <span class="px-2 py-1 rounded-full text-sm font-medium bg-green-200 outline-1 outline-green-700 text-green-900">Lunas</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-sm font-medium bg-red-200 outline-1 outline-red-700 text-red-900">Belum Bayar</span>
                            @endif
                        </h3>
                    </div>
                    <div class="flex flex-col group ">
                        @if (!$penggunaan_air->sudah_bayar)
                            <button 
                                x-data 
                                @click="
                                    Swal.fire({
                                        title: 'Memuat...',
                                        didOpen: () => { Swal.showLoading() }
                                    });
                                    $dispatch('show-tagihan-detail', ['{{ $penggunaan_air->id }}'])
                                " 
                                class="bg-yellow-400 my-1 px-1 py-1 rounded-lg hover:bg-yellow-600 hover:text-white cursor-pointer">
                                Detail
                            </button>
                            <button 
                                x-data 
                                @click="
                                Swal.fire({
                                    title: 'Memuat Pembayaran ... ',
                                    didOpen: () => { Swal.showLoading() }
                                });
                                @if (auth()->user()->role === "Pengurus")
                                    $dispatch('buka-modal-pembayaran', {             penggunaan_air_id: '{{ $penggunaan_air->id }}' })
                                    "
                                @else
                                    $dispatch('buka-modal-pembayaran-transfer', {             penggunaan_air_id: '{{ $penggunaan_air->id }}' })
                                    "
                                @endif
                                class="cursor-pointer px-1 py-1 hover:bg-emerald-700 hover:text-white bg-emerald-400 rounded-lg shadow">Bayar
                            </button>
                        @else
                            <button
                            x-data
                            @click="window.open('{{ route('cetak.rekening', ['id' => $penggunaan_air->id]) }}', '_blank')"
                            class="bg-sky-400 my-1 px-1 py-1 rounded-lg hover:bg-sky-600 hover:text-white cursor-pointer">
                                Cetak
                            </button>
                            <button 
                                x-data 
                                @click="
                                    Swal.fire({
                                        title: 'Memuat...',
                                        didOpen: () => { Swal.showLoading() }
                                    });
                                    $dispatch('show-tagihan-detail', ['{{ $penggunaan_air->id }}'])
                                " 
                                class="bg-yellow-400 my-1 px-1 py-1 rounded-lg hover:bg-yellow-600 hover:text-white cursor-pointer">
                                Detail
                            </button>
                        @endif                    
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
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('refresh-halaman', () => {
                window.location.reload(); // refresh halaman penuh
            })
        })
    </script>
</x-layout>