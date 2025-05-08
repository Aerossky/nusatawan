<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Halaman Admin Aplikasi Wisata">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - Wisata</title>

    <!-- Favicon and Icons -->
    <link rel="icon" type="image/png" sizes="32x32" href="/path/to/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/path/to/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/path/to/apple-touch-icon.png">
    <link rel="manifest" href="/path/to/site.webmanifest">
    <link rel="mask-icon" href="/path/to/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-background text-dark">
    {{-- Navbar --}}
    <x-admin.navbar name="joko" email="joko@gmail.com"
        image="https://cdn-icons-png.flaticon.com/512/149/149071.png" />

    {{-- Aside --}}
    <x-admin.aside />

    {{-- Konten Halaman --}}
    <main class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 rounded-lg mt-14 bg-white">
            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>

</html>
