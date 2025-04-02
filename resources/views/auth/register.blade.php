<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Nusatawan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-screen w-screen flex items-center justify-center bg-gray-100">

    <div
        class="w-full max-w-4xl h-screen md:h-auto flex flex-col md:flex-row bg-white shadow-lg rounded-lg overflow-hidden relative">

        <!-- Gambar sebagai background di mobile -->
        <div class="absolute inset-0 md:relative md:w-1/2">
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                style="background-image: url('{{ asset('images/auth.png') }}');">
            </div>
        </div>

        <!-- Form Register (di tengah layar saat mobile) -->
        <div
            class="absolute inset-0 flex items-center justify-center md:relative md:w-1/2 p-2 bg-white bg-opacity-80 md:bg-white z-10">
            <div class="w-full max-w-md bg-white  p-6 rounded-lg shadow-lg md:shadow-none">
                <h2 class="text-2xl font-semibold text-gray-700 text-left">Halo,
                    <span class="text-primary font-bold">Nusatawan</span>
                </h2>
                <p class="text-left text-gray-500 mb-6">Masukkan detail akun Anda untuk mulai menjelajah atau membagikan
                    destinasi
                    terbaik.</p>

                <form>
                    <div class="mb-4">
                        <label class="block text-gray-700">Nama</label>
                        <input type="text"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Email</label>
                        <input type="email"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Kata Sandi</label>
                        <input type="password"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Foto</label>
                        <input type="file"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button class="w-full bg-primary text-white py-2 rounded-lg hover:bg-blue-700 transition">
                        Daftar
                    </button>
                </form>

                <p class="text-center text-gray-500 mt-4">
                    Sudah punya akun? <a href="#" class="text-primary font-semibold hover:underline">Masuk</a>
                </p>
            </div>
        </div>
    </div>

</body>

</html>
