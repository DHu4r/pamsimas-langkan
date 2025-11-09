<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    <title>{{ $tittle }}</title>
    @livewireStyles
    <script defer>
        function toggleSidebar() {
            // document.getElementById('mobile-sidebar').classList.toggle('hidden');
            const sidebar = document.getElementById('mobile-sidebar');
            sidebar.classList.toggle('-translate-x-full');
        }
    </script>
    {{-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
</head>
<body class="bg-gray-100">

    <!-- Header -->
    <x-header :tittle="$tittle"/>

    {{-- Sidebar --}}
    <x-sidebar />

    <!-- Konten utama -->
    <main class="pt-16 lg:pl-64">
        <div class="bg-white p-6 shadow mt-2 mx-3">
            {{ $slot }}
        </div>
    </main>

    @livewireScripts
    {{-- <script src="https://unpkg.com/alpinejs" defer></script> --}}
</body>
</html>