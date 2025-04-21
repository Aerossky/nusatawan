@extends('layouts.user')
@section('title', 'Profil')
@section('content')
    <x-section>
        <div class="mt-14"></div>

        <!-- Profile Header Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row items-center ">
                <!-- Profile Picture -->
                <div class="mb-4 md:mb-0 md:mr-8">
                    <label for="photoInput" class="relative cursor-pointer block">
                        <div
                            class="w-32 h-32 rounded-full overflow-hidden border-4 border-gray-200 hover:border-blue-500 transition-all duration-300">
                            <img id="profileImage" src="https://via.placeholder.com/150?text=Risky" alt="Profile Picture"
                                class="w-full h-full object-cover">
                        </div>
                        <div
                            class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40 opacity-0 hover:opacity-100 rounded-full transition-opacity duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <input type="file" id="photoInput" name="photo" class="hidden" accept="image/*"
                            onchange="previewImage(event)">
                    </label>
                </div>

                <!-- Profile Info -->
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-2xl font-bold text-gray-800">Risky</h1>
                    <p class="text-gray-600 mb-2">riskywig@gmail.com</p>
                    <span
                        class="inline-block bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full font-medium">Aktif</span>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded-lg shadow-md text-center transition-transform hover:transform hover:scale-105">
                <div class="text-3xl font-bold text-blue-600">1</div>
                <div class="text-sm text-gray-600 font-medium">Destinasi</div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md text-center transition-transform hover:transform hover:scale-105">
                <div class="text-3xl font-bold text-blue-600">10</div>
                <div class="text-sm text-gray-600 font-medium">Favorit</div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md text-center transition-transform hover:transform hover:scale-105">
                <div class="text-3xl font-bold text-blue-600">1</div>
                <div class="text-sm text-gray-600 font-medium">Itinerari</div>
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="bg-white rounded-lg shadow-md p-6" x-data="{ activeTab: 'profile' }">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Informasi Pengguna</h2>

            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex space-x-8">
                    <a @click.prevent="activeTab = 'profile'"
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'profile', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'profile' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium cursor-pointer transition-colors duration-200">
                        Profil
                    </a>
                    <a @click.prevent="activeTab = 'destinations'"
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'destinations', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'destinations' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium cursor-pointer transition-colors duration-200">
                        Destinasi Saya
                    </a>
                    <a @click.prevent="activeTab = 'favorites'"
                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'favorites', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'favorites' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium cursor-pointer transition-colors duration-200">
                        Favorit
                    </a>
                </nav>
            </div>

            <!-- Tab Contents -->
            <div>
                <!-- Profile Tab Content -->
                <div x-show="activeTab === 'profile'" class="animate-fade-in">
                    <h3 class="text-lg font-semibold mb-4">Data Pribadi</h3>
                    <form>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2" for="name">Nama Lengkap</label>
                                <input type="text" id="name" value="Risky"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2" for="email">Email</label>
                                <input type="email" id="email" value="riskywig@gmail.com"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2" for="phone">Nomor Telepon</label>
                                <input type="tel" id="phone" placeholder="Masukkan nomor telepon"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2" for="location">Lokasi</label>
                                <input type="text" id="location" placeholder="Masukkan kota/provinsi"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Destinations Tab Content -->
                <div x-show="activeTab === 'destinations'" class="animate-fade-in">
                    <h3 class="text-lg font-semibold mb-4">Destinasi yang Pernah Dikunjungi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div
                            class="border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                            <div class="h-48 bg-gray-200 relative">
                                <img src="https://via.placeholder.com/800x400?text=Bali" alt="Bali"
                                    class="w-full h-full object-cover">
                                <div
                                    class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                                    <h4 class="text-white font-bold text-xl">Bali</h4>
                                </div>
                            </div>
                            <div class="p-4">
                                <p class="text-gray-600">Kunjungan terakhir: 12 Maret 2025</p>
                                <div class="mt-3 flex space-x-2">
                                    <span
                                        class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">Pantai</span>
                                    <span
                                        class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Budaya</span>
                                </div>
                            </div>
                        </div>

                        <div
                            class="border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                            <div class="h-48 bg-gray-200 relative">
                                <img src="https://via.placeholder.com/800x400?text=Yogyakarta" alt="Yogyakarta"
                                    class="w-full h-full object-cover">
                                <div
                                    class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                                    <h4 class="text-white font-bold text-xl">Yogyakarta</h4>
                                </div>
                            </div>
                            <div class="p-4">
                                <p class="text-gray-600">Kunjungan terakhir: 5 Januari 2025</p>
                                <div class="mt-3 flex space-x-2">
                                    <span
                                        class="inline-block bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">Sejarah</span>
                                    <span
                                        class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Kuliner</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Favorites Tab Content -->
                <div x-show="activeTab === 'favorites'" class="animate-fade-in">
                    <h3 class="text-lg font-semibold mb-4">Destinasi Favorit</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div
                            class="border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                            <div class="h-40 bg-gray-200">
                                <img src="https://via.placeholder.com/600x400?text=Gunung+Bromo" alt="Gunung Bromo"
                                    class="w-full h-full object-cover">
                            </div>
                            <div class="p-3">
                                <h4 class="font-medium text-gray-800">Gunung Bromo</h4>
                                <p class="text-sm text-gray-600">Jawa Timur</p>
                                <div class="flex items-center mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-500"
                                        fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                    </svg>
                                    <span class="text-sm ml-1">4.8</span>
                                </div>
                            </div>
                        </div>

                        <div
                            class="border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                            <div class="h-40 bg-gray-200">
                                <img src="https://via.placeholder.com/600x400?text=Raja+Ampat" alt="Raja Ampat"
                                    class="w-full h-full object-cover">
                            </div>
                            <div class="p-3">
                                <h4 class="font-medium text-gray-800">Raja Ampat</h4>
                                <p class="text-sm text-gray-600">Papua Barat</p>
                                <div class="flex items-center mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-500"
                                        fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                    </svg>
                                    <span class="text-sm ml-1">4.9</span>
                                </div>
                            </div>
                        </div>

                        <div
                            class="border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                            <div class="h-40 bg-gray-200">
                                <img src="https://via.placeholder.com/600x400?text=Borobudur" alt="Borobudur"
                                    class="w-full h-full object-cover">
                            </div>
                            <div class="p-3">
                                <h4 class="font-medium text-gray-800">Borobudur</h4>
                                <p class="text-sm text-gray-600">Jawa Tengah</p>
                                <div class="flex items-center mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-500"
                                        fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                    </svg>
                                    <span class="text-sm ml-1">4.7</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-section>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
