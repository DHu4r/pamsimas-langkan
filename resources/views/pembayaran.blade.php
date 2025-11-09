<x-layout :tittle="$tittle">
    @livewire('tagihan-air-detail')
    <div>
        <h1 class="text-base font-semibold mb-5">Pembayaran saya</h1>
        <form method="GET" action="{{ route('index.pembayaran') }}" 
        class="flex-col space-y-2 md:space-y-0 md:flex-row md:flex md:items-center lg:flex lg:flex-row lg:space-y-0 lg:space-x-2 w-full">
            @if(request('periode_bulan') || request('periode_tahun') || request('search') !== null)
                <a href="{{ route('index.pembayaran') }}">
                    <div class="w-30 px-4 py-2 bg-gray-300 text-slate-800 rounded hover:bg-gray-400 hover:text-black text-center mb-2">
                        Reset
                    </div>
                </a>
            @endif
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
            <input type="number" name="periode_tahun" value="{{ request('periode_tahun') }}" placeholder="Masukan Periode Tahun" class="lg:w-50 md:w-50 w-full py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600 placeholder:text-sm">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari Data" class="md:mt-0 w-full lg:w-44 md:w-80 py-1.5 ps-2 ml-2 border border-slate-400 rounded-xl focus:outline-sky-600 placeholder:text-sm">
            <button type="submit" class="ml-4 px-4 py-2 bg-blue-500 text-white hover:bg-blue-300 rounded-xl hover:outline-blue-700 hover:outline-2">
                Cari
            </button>
        </form>
        {{-- Versi tampilan desktop --}}
        <div class="hidden lg:block md:table w-full p-4 text-base text-slate-700 mt-5">
            <table class="w-full p-4">
                <thead class="border-b border-gray-300">
                    <tr>
                        <th class="text-start px-4 py-2">No.</th>
                        <th class="text-start px-4 py-2">Tanggal Pembayaran</th>
                        <th class="text-start px-4 py-2">Jumlah</th>
                        <th class="text-start px-4 py-2">Periode</th>
                        <th class="text-start px-4 py-2">Nama Pelanggan</th>
                        <th class="text-start px-4 py-2">Biaya Admin</th>
                        <th class="text-center px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pembayarans as $pembayaran)
                        <tr class="border-b border-gray-300">
                            <td class="text-start px-4 py-1">{{ $loop->iteration }}</td>
                            <td class="text-start px-4 py-1">{{ \Carbon\Carbon::parse($pembayaran->created_at)->translatedFormat('d F Y, \J\a\m H:i') }} </td>
                            <td class="text-start px-4 py-1">Rp. {{ number_format($pembayaran->jumlah,0,",", ".") }}</td>
                            <td class="text-start px-4 py-1">{{ $pembayaran->penggunaanAir->nama_bulan }} {{ $pembayaran->penggunaanAir->periode_tahun }}</td>
                            <td class="text-start px-4 py-1">{{ $pembayaran->penggunaanAir->penggunas->nama }}</td>
                            <td class="text-start px-4 py-1">Rp. 5.000</td>
                            <td class="text-start px-4 py-1">
                                <button 
                                    x-data 
                                    @click="
                                        Swal.fire({
                                            title: 'Memuat...',
                                            didOpen: () => { Swal.showLoading() }
                                        });
                                        $dispatch('show-tagihan-detail', ['{{ $pembayaran->penggunaanAir->id }}'])
                                    " 
                                    class="bg-yellow-400 my-1 px-3 py-1 rounded-lg hover:bg-yellow-600 hover:text-white cursor-pointer">
                                    Detail
                                </button>
                                <button 
                                x-data
                                @click="window.open('{{ route('cetak.rekening', ['id' => $pembayaran->penggunaanAir->id]) }}', '_blank')"
                                class="bg-sky-400 my-1 px-3 py-1 rounded-lg hover:bg-sky-600 hover:text-white cursor-pointer">
                                    Cetak
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <h1 class="p-4 outline-1 outline-red-600 bg-red-300 ">Data  tidak ditemukan</h1>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Versi tampilan mobile --}}
        <div class="lg:hidden md:hidden mt-10">
            @forelse ($pembayarans as $pembayaran)
                <div class="flex justify-between items-center bg-slate-100 shadow my-3 p-2">
                    <div>
                        <h1 class="font-bold text-sm text-slate-800"><span class=" px-1 py-1 rounded-xl mr-1">Nama Pelanggan : </span>{{ $pembayaran->penggunaanAir->penggunas->nama }}</h1>
                        <h3 class="mt-2 text-sm font-semibold text-slate-700"><span class=" px-1 py-1 rounded-xl mr-1">Periode : </span>{{ $pembayaran->penggunaanAir->nama_bulan }} {{ $pembayaran->penggunaanAir->periode_tahun }}</h3>
                        <h3 class="mt-2 text-sm text-slate-700"><span class=" px-1 py-1 rounded-xl mr-1">Jumlah Bayar : </span>Rp. {{ number_format($pembayaran->jumlah,0,",", ".") }}</h3>
                        <h3 class="mt-2 text-sm text-slate-700"><span class=" px-1 py-1 rounded-xl mr-1">Metode : </span>{{ $pembayaran->metode }} dari {{ $pembayaran->nama_bank }}</h3>
                        <h3 class="mt-2 text-sm text-slate-700"><span class=" px-1 py-1 rounded-xl mr-1">Dibayar Oleh : </span>{{ $pembayaran->dibayaroleh->nama }}</h3>
                    </div>
                </div>
            @empty
                <div class="flex justify-center items-center bg-slate-100 shadow my-3 p-2">
                    <div class="px-3 py-2 bg-red-300 outline outline-red-600 text-red-900">
                        Data Tidak ada
                    </div>
                </div>
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