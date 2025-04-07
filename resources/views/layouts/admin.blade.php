<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Wisata</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background text-dark ">

    {{-- Navbar --}}
    <x-admin.navbar name="joko" email="joko@gmail.com" image="https://cdn-icons-png.flaticon.com/512/149/149071.png" />
    {{-- Aside --}}
    <x-admin.aside />

    {{-- Konten Halaman --}}
    <main class="p-4 sm:ml-64">
        {{-- Content --}}
        <div class="p-4 border-2 border-gray-200 rounded-lg mt-14">
            @yield('content')
        </div>
    </main>

</body>

</html>
