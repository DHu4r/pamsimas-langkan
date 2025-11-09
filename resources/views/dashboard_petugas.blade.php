<x-layout :tittle="$tittle">
    @if (auth()->check())
        <div class="bg-gray-100 shadow w-full py-2 px-2 rounded-2xl">
            <h1 class="ps-4 font-semibold text-slate-900">Selamat Datang {{ auth()->user()->nama }}</h1>
        </div>
    @endif
    <div class="w-full py-2 px-2 mt-5">
        <div class="flex flex-wrap mt-3 gap-y-4">
            <div class="w-full md:w-6/12 lg:w-3/12 justify-center">
                <div class="w-11/12 h-40 bg-[#18A3B8] rounded-xl shadow mx-auto">
                    <div class="relative h-9/12 flex flex-col justify-between">
                        <img src="{{ asset('img/catat.png') }}" class="absolute inset-0 w-20 opacity-30 z-0 ms-auto mt-2 mr-2">
                        <div class="p-2 relative z-10">
                            <h1 class="ps-2 mt-2 w-8/12 text-white font-extrabold text-4xl">{{ $air_tercatat_periode_ini }} </sup></h1>
                        </div>
                        <div class="text-white p-2 font-semibold">Penggunaan Air Tercatat Bulan Ini</div>
                    </div>
                    <a href="/penggunaan_air">
                        <div class="rounded-b-xl h-3/12 bg-[#1692A7] hover:bg-sky-800 flex items-center justify-center">
                            <h1 class="text-white font-semibold">Lihat</h1>
                            <div>
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="w-full md:w-6/12 lg:w-3/12">
                <div class="w-11/12 h-40 bg-[#27A746] rounded-xl shadow mx-auto">
                    <div class="relative h-9/12 flex flex-col justify-between">
                    <img src="{{ asset('img/office.png') }}" class="absolute inset-0 w-20 opacity-30 z-0 ms-auto mt-2 mr-2">
                        <div class="p-2 relative z-10">
                            <h1 class="ps-2 w-8/12 text-white font-extrabold text-4xl mt-2">{{ $total_pelanggan }}</h1>
                        </div>
                        <div class="text-white p-2 font-semibold">Total Pelanggan</div>
                    </div>
                    <a href="/pelanggan">
                        <div class="rounded-b-xl h-3/12 bg-[#25983F] hover:bg-[#105D22] flex items-center justify-center">
                            <h1 class="text-white font-semibold">Lihat</h1>
                            <div>
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="w-full md:w-6/12 lg:w-3/12">
                <div class="w-11/12 h-40 bg-[#FEC009] rounded-xl shadow mx-auto">
                    <div class="relative h-9/12 flex flex-col justify-between">
                        <img src="{{ asset('img/meteran.png') }}" class="absolute inset-0 w-20 opacity-30 z-0 ms-auto mt-2 mr-2">
                        <div class="p-2 relative z-10">
                            <h1 class="ps-2 w-8/12 text-white font-extrabold text-4xl mt-2">{{ $jumlah_inputan_saya }}</h1>
                        </div>
                        <div class="text-white p-2 font-semibold">History Pencatatan Saya</div>
                    </div>
                    <a href="/penggunaan_air">
                        <div class="rounded-b-xl h-3/12 bg-[#E5AC06] hover:bg-[#CE9B06] flex items-center justify-center">
                            <h1 class="text-white font-semibold">Lihat</h1>
                            <div>
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="w-full md:w-6/12 lg:w-3/12">
                <div class="w-11/12 h-40 bg-[#C7313E] rounded-xl shadow mx-auto">
                    <div class="relative h-9/12 flex flex-col justify-between">
                        <img src="{{ asset('img/catat.png') }}" class="absolute inset-0 w-20 opacity-30 z-0 ms-auto mt-2 mr-2">
                        <div class="p-2 relative z-10">
                            <h1 class="ps-2 w-8/12 text-white font-extrabold text-4xl mt-2">{{ $air_belum_tercatat_periode_ini }} <h1>
                        </div>
                        <div class="text-white p-2 font-semibold">Penggunaan Air Belum Tercatat Bulan Ini</div>
                    </div>
                    <a href="/penggunaan_air">
                        <div class="rounded-b-xl h-3/12 bg-[#C12834] hover:bg-[#AA1D28] flex items-center justify-center">
                            <h1 class="text-white font-semibold">Lihat</h1>
                            <div>
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layout>