<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Nusatawan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
    <div class="w-full max-w-4xl flex flex-col md:flex-row bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Bagian kiri: Gambar Gunung -->
        <div class="hidden md:block md:w-1/2 bg-cover bg-center h-auto"
            style="background-image: url('{{ asset('images/auth.png') }}');">
        </div>

        <!-- Bagian kanan: Form Registrasi -->
        <div class="w-full md:w-1/2 p-4 md:p-6 lg:p-8">
            <div class="w-full max-w-md mx-auto">
                <!-- Header -->
                <h2 class="text-2xl font-semibold text-gray-700">
                    Halo,
                    <span class="text-blue-500 font-bold">Nusatawan</span>
                </h2>
                <p class="text-gray-500 mb-6">
                    Masukkan detail akun Anda untuk mulai menjelajah atau membagikan destinasi terbaik.
                </p>

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border border-red-300 text-red-700 rounded-lg">
                        <div class="font-medium">Oops! Ada beberapa masalah dengan input Anda:</div>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('auth.register.post') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Nama -->
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 mb-1">Nama</label>
                        <input type="text" id="name" name="name" required
                            class="w-full px-4 py-2 border @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Masukkan nama lengkap" value="{{ old('name') }}">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email" required
                            class="w-full px-4 py-2 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="contoh@email.com" value="{{ old('email') }}">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kata Sandi -->
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 mb-1">Kata Sandi</label>
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-2 border @error('password') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Konfirmasi Kata Sandi -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-gray-700 mb-1">Konfirmasi Kata
                            Sandi</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="w-full px-4 py-2 border @error('password') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Masukkan lagi kata sandi Anda">
                    </div>

                    <!-- Foto -->
                    <div class="mb-6">
                        <label for="image" class="block text-gray-700 mb-1">Foto</label>
                        <input type="file" id="image" name="image" accept="image/*"
                            class="w-full px-4 py-2 border @error('image') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('image')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tombol Daftar -->
                    <button type="submit"
                        class="w-full bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-600 transition duration-200">
                        Daftar
                    </button>

                    <!-- Login Link -->
                    <p class="text-center text-gray-500 mt-4">
                        Sudah punya akun?
                        <a href="" class="text-blue-500 font-semibold hover:underline">Masuk</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
