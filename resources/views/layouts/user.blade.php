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
    <x-navbar isHome="true" />

    {{-- Konten Halaman --}}
    <div class="container mx-auto p-6">
        @yield('content')
    </div>

    {{-- Footer User --}}
    {{-- <x-user.footer /> --}}

</body>

</html>
