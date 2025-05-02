<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Wisata</title>
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
