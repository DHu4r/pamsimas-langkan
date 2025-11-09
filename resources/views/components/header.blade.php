<header class="bg-sky-900 shadow fixed top-0 left-0 w-full h-16 flex items-center z-30">
    <!-- Hamburger (kiri) untuk mobile -->
    <button class="lg:hidden mr-4 focus:outline-none cursor-pointer ml-1.5" onclick="toggleSidebar()">
        <div class="w-10 h-1 bg-white mb-2"></div>
        <div class="w-10 h-1 bg-white mb-2"></div>
        <div class="w-10 h-1 bg-white"></div>
    </button>

    <!-- Logo atau Judul -->
    <div class="md:min-w-64 md:h-16 flex justify-center bg-sky-900">
        <img src="{{ asset('img/logo.png') }}" alt="Pamsimas" class="w-44 p-1">
    </div>
    <div class="w-full flex justify-between items-center">
        <div class="lg:text-xl md:text-xl text-sm font-bold text-slate-200 ml-5">{{ $tittle }}</div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="group min-h-10 min-w-3  flex items-center justify-center mr-1.5 md:mr-20 rounded-md border-1 border-slate-200 px-2 hover:bg-yellow-300 hover:border-yellow-300 text-white hover:text-slate-950">
                <h3 class="hidden lg:block mr-1 font-bold">Keluar</h3>
                <svg class="group-hover:text-slate-950 w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H4m12 0-4 4m4-4-4-4m3-4h2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3h-2"/>
                </svg>
            </button>
        </form>
    </div>
</header>