<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Wisata</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background text-dark ">

    {{-- Navbar User --}}
    <x-user.navbar isHome="true" />

    {{-- Konten Halaman --}}
    <div class="mx-auto">
        @yield('content')
    </div>

    {{-- Footer User --}}
    <x-user.footer />


    {{-- Script --}}
    @yield('script')
</body>

</html>
