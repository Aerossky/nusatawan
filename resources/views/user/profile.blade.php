@extends('layouts.user')
@section('title', 'Profil')
@section('content')
    <x-section>
        <div class="mt-14"></div>

        <!-- Profil Pengguna -->
        <div class="flex flex-col items-center text-center">
            <!-- Foto Profil (Klik untuk Ubah) -->
            <label for="photoInput" class="relative cursor-pointer group">
                <div
                    class="w-24 h-24 rounded-full overflow-hidden border-4 border-gray-300 transition-all duration-300 group-hover:shadow-lg">
                    <img id="profileImage" src="{{ asset('images/auth.png') }}" alt="Profile Picture"
                        class="w-full h-full object-cover">
                </div>
                <div
                    class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 rounded-full transition-opacity duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z">
                        </path>
                    </svg>
                </div>
                <input type="file" id="photoInput" name="photo" class="hidden" accept="image/*"
                    onchange="previewImage(event)">
            </label>

            <h2 class="text-xl font-semibold mt-4">Profil Pengguna</h2>
        </div>

        <!-- Form Edit Profil -->
        <div class="mt-6 md:max-w-xl mx-auto">
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div>
                    <label class="block text-gray-700">Nama</label>
                    <div class="relative">
                        <input type="text" name="name" value="Risky"
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300">
                        <span class="absolute right-3 top-2 text-gray-400">
                            ✏️
                        </span>
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700">Email</label>
                    <div class="relative">
                        <input type="email" name="email" value="riskywaj@gmail.com"
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300">
                        <span class="absolute right-3 top-2 text-gray-400">
                            ✏️
                        </span>
                    </div>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600 transition">
                    Simpan
                </button>
            </form>
        </div>

        <!-- DestinasiKu -->
        <div class="mt-10">
            <h3 class="text-xl font-semibold">DestinasiKu</h3>
            <div class="grid md:grid-cols-3 gap-6 mt-4">
                @foreach ([['Raja Ampat', 'Papua Barat', 'raja-ampat.jpg'], ['Danau Toba', 'Sumatera Utara', 'danau-toba.jpg'], ['Tana Toraja', 'Sulawesi Selatan', 'tana-toraja.jpg']] as $destinasi)
                    <div class="bg-white rounded-md overflow-hidden shadow-md transition hover:shadow-lg">
                        <div class="relative">
                            <img src="{{ asset('images/' . $destinasi[2]) }}" alt="{{ $destinasi[0] }}"
                                class="w-full h-32 object-cover">
                        </div>
                        <div class="p-4">
                            <div class="flex justify-between items-center mb-1">
                                <h3 class="font-semibold">{{ $destinasi[0] }}</h3>
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-700 mr-2">75</span>
                                    <button class="text-gray-400 hover:text-red-500 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm mb-2">{{ $destinasi[1] }}</p>
                            <div class="mb-3">
                                <span class="bg-amber-100 text-amber-700 text-xs px-2 py-1 rounded-full">Wisata</span>
                            </div>
                            <button
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded text-sm transition">Lihat
                                Detail</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-section>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const profileImage = document.getElementById('profileImage');
                profileImage.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

@endsection
