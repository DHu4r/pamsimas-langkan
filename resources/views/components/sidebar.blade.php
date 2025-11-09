    <!-- Sidebar untuk desktop -->
    <aside class="hidden lg:flex-col lg:flex fixed top-16 left-0 w-64 h-[calc(100vh-4rem)] bg-gray-400 shadow-2xl z-20" x-data="{ open: false }">
        <nav class="space-y-4 mt-2 flex-1 overflow-y-auto">
            {{-- Menu untuk semua role --}}
            <x-sidebar-link href="/dashboard">Dashboard</x-sidebar-link>

            {{-- Menu untuk Pengurus --}}
            @if (auth()->user()->role === "Pengurus")
                <x-sidebar-link href="/penggunaan_air">Penggunaan Air</x-sidebar-link>
                <x-sidebar-link href="/tagihan_air">Tagihan Air</x-sidebar-link>
                <x-sidebar-link href="/pelanggan">Daftar Pelanggan</x-sidebar-link>
                <x-sidebar-link href="/pengguna">Kelola Pengguna</x-sidebar-link>

                <!-- Menu Keuangan -->
                @php
                    $isKeuanganGroupActive = request()->is('keuangan') || request()->is('keuangan/*');
                @endphp
                <div x-data="{ open: {{ $isKeuanganGroupActive ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex justify-between items-center text-slate-900 font-semibold hover:bg-gray-600 hover:text-white px-4 py-3 transition">
                        Keuangan
                        <svg class="w-4 h-4 transition-transform duration-300" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="pl-4 space-y-1 mt-2">
                        <x-sidebar-link href="/keuangan/jurnal">Jurnal</x-sidebar-link>
                        <x-sidebar-link href="/keuangan/akun">Akun</x-sidebar-link>
                        <x-sidebar-link href="/keuangan/laporan">Laporan</x-sidebar-link>
                    </div>
                </div>
            @elseif (auth()->user()->role === "Pelanggan")
                <x-sidebar-link href="/penggunaan_air">Penggunaan Air</x-sidebar-link>
                <x-sidebar-link href="/tagihan_air">Tagihan Air</x-sidebar-link> 
            
            @elseif (auth()->user()->role === "Petugas Lapangan")
                <x-sidebar-link href="/penggunaan_air">Penggunaan Air</x-sidebar-link>
                <x-sidebar-link href="/pelanggan">Daftar Pelanggan</x-sidebar-link>
            @elseif (auth()->user()->role === "Mitra Pembayaran")
                <x-sidebar-link href="/tagihan_air">Tagihan Air</x-sidebar-link> 
                <x-sidebar-link href="/pelanggan">Daftar Pelanggan</x-sidebar-link>
                <x-sidebar-link href="/pembayaran">History Pembayaran</x-sidebar-link>
            @endif
        </nav>
        <div class="bg-gray-600 py-2 text-center text-white flex items-center px-4 font-semibold transition h-14">
            <div class="w-10/12 text-base font-normal"><p>{{ auth()->user()->nama }}</p></div>
            <div class="w-2/12">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="group min-h-10 min-w-3  flex items-center justify-center mr-1.5 md:mr-20 rounded-md border-1 border-slate-200 px-2 hover:bg-yellow-300 hover:border-yellow-300 text-white hover:text-slate-950">
                        <h3 class="mr-1 font-bold"></h3>
                        <svg class="group-hover:text-slate-950 w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H4m12 0-4 4m4-4-4-4m3-4h2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3h-2"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Sidebar untuk mobile -->
    <aside id="mobile-sidebar" x-data="{ open: false }"
    class="lg:hidden fixed top-16 left-0 w-64 h-[calc(100vh-4rem)] bg-gray-400 shadow z-40 transform -translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
        <nav class="space-y-4 mt-2 flex-1 overflow-y-auto">
            {{-- Menu yang semua user bisa akses --}}
            <x-sidebar-link href="/dashboard">Dashboard </x-sidebar-link>

            {{-- Menu untuk Pengurus --}}
            @if (auth()->user()->role === 'Pengurus')
                <x-sidebar-link href="/penggunaan_air">Penggunaan Air</x-sidebar-link>
                <x-sidebar-link href="/tagihan_air">Tagihan Air</x-sidebar-link>
                <x-sidebar-link href="/pelanggan">Daftar Pelanggan</x-sidebar-link>
                <x-sidebar-link href="/pengguna">Kelola Pengguna</x-sidebar-link>
                <!-- Menu Keuangan -->
                @php
                    $isKeuanganGroupActive = request()->is('keuangan') || request()->is('keuangan/*');
                @endphp
                <div x-data="{ open: {{ $isKeuanganGroupActive ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex justify-between items-center text-slate-900 font-semibold hover:bg-gray-600 hover:text-white px-4 py-3 transition">
                        Keuangan
                        <svg class="w-4 h-4 transition-transform duration-300" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="pl-4 space-y-1 mt-2">
                        <x-sidebar-link href="/keuangan/jurnal">Pencatatan</x-sidebar-link>
                        <x-sidebar-link href="/keuangan/akun">Akun</x-sidebar-link>
                        <x-sidebar-link href="/keuangan/laporan">Laporan</x-sidebar-link>
                    </div>
                </div>
            @elseif (auth()->user()->role === 'Pelanggan')
                <x-sidebar-link href="/penggunaan_air">Penggunaan Air</x-sidebar-link>
                <x-sidebar-link href="/tagihan_air">Tagihan Air</x-sidebar-link>
            @elseif (auth()->user()->role === "Petugas Lapangan")
                <x-sidebar-link href="/penggunaan_air">Penggunaan Air</x-sidebar-link>
                <x-sidebar-link href="/pelanggan">Daftar Pelanggan</x-sidebar-link>
            @elseif (auth()->user()->role === "Mitra Pembayaran")
                <x-sidebar-link href="/tagihan_air">Tagihan Air</x-sidebar-link> 
                <x-sidebar-link href="/pelanggan">Daftar Pelanggan</x-sidebar-link>
                <x-sidebar-link href="/pembayaran">History Pembayaran</x-sidebar-link>
            @endif
        </nav>
        <div class="bg-gray-600 py-2 text-center text-white flex items-center px-4 font-semibold transition h-14">
            <div class="w-10/12 text-base font-normal"><p>{{ auth()->user()->nama }}</p></div>
            <div class="w-2/12">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="group min-h-10 min-w-3  flex items-center justify-center mr-1.5 md:mr-20 rounded-md border-1 border-slate-200 px-2 hover:bg-yellow-300 hover:border-yellow-300 text-white hover:text-slate-950">
                        <h3 class="mr-1 font-bold"></h3>
                        <svg class="group-hover:text-slate-950 w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H4m12 0-4 4m4-4-4-4m3-4h2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3h-2"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>