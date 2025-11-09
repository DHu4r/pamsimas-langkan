@props(['href'])

@php
     $hrefPath = trim(parse_url($href, PHP_URL_PATH), '/'); // contoh: penggunaan_air
     $isActive = request()->is($hrefPath) || request()->is($hrefPath . '/*');
@endphp

<a href="{{ $href }}">
    <div {{ $attributes->merge([
        'class' => ($isActive ? 'bg-gray-600 text-white' : 'text-slate-900 hover:bg-gray-600 hover:text-white') . ' block px-4 py-3 font-semibold transition'
    ]) }}>
    {{ $slot }}
    </div>
</a>