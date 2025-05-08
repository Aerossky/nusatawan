<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Nusatawan</title>
    <!-- Primary Meta Tags -->
    <title>Nusatawan</title>
    
    <link rel="icon" href="{{ asset('images/logo/nusatawan-logo.png') }}" type="image/png">
    <meta name="title" content="Nusatawan" />
    <meta name="description"
        content="Nusatawan membantu merencanakan perjalanan wisata di Indonesia dengan informasi destinasi terintegrasi prakiraan cuaca. Temukan tempat wisata favorit dan ketahui kondisi cuaca sebelum berkunjung.">
    <meta name="keywords"
        content="wisata Indonesia, prakiraan cuaca wisata, destinasi wisata, perencanaan perjalanan, informasi wisata, cuaca destinasi wisata, pariwisata Indonesia">
    <meta name="author" content="Nusatawan">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Indonesia">


    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.nusatawan.com/">
    <meta property="og:title" content="Nusatawan - Informasi Wisata & Prakiraan Cuaca Indonesia">
    <meta property="og:description"
        content="Rencanakan perjalanan wisata Anda dengan informasi destinasi dan prakiraan cuaca terintegrasi di seluruh Indonesia.">
    <meta property="og:image" content="{{ asset('images/logo/nusatawan-logo.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://www.nusatawan.com/">
    <meta property="twitter:title" content="Nusatawan - Informasi Wisata & Prakiraan Cuaca Indonesia">
    <meta property="twitter:description"
        content="Rencanakan perjalanan wisata Anda dengan informasi destinasi dan prakiraan cuaca terintegrasi di seluruh Indonesia.">
    <meta property="twitter:image" content="{{ asset('images/logo/nusatawan-logo.png') }}">

    <!-- Tambahan Meta untuk Mobile -->
    <meta name="theme-color" content="#1e3a8a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Nusatawan">

    <!-- Metatags Tambahan untuk SEO -->
    <meta name="geo.region" content="ID">
    <meta name="geo.placename" content="Indonesia">
    <link rel="canonical" href="https://www.nusatawan.com/">


    <!-- SwiperJS CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')


</head>

<body class="bg-background text-dark ">

    {{-- Navbar User --}}
    <x-user.navbar
        currentPage="{{ request()->routeIs('user.home')
            ? 'user.home'
            : (request()->routeIs('user.destinations.*')
                ? 'user.destinations.index'
                : (request()->routeIs('user.about')
                    ? 'user.about'
                    : (request()->routeIs('user.itinerary.*')
                        ? 'user.itinerary.index'
                        : ''))) }}" />

    {{-- Toast Notification --}}
    @if (session('success'))
        <x-ui.toast type="success" message="{{ session('success') }}" />
    @endif

    {{-- Konten Halaman --}}
    <div class="mx-auto">
        @yield('content')
    </div>

    {{-- Footer User --}}
    <x-user.footer />

    <!-- SwiperJS JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    {{-- Script tambahan dari halaman --}}
    @stack('scripts')
</body>

</html>
