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
                        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-gray-200">
                            <img id="profileImage" src="{{ asset('storage/' . $profile->image) }}" alt="Profile Picture"
                                class="w-full h-full object-cover">
                        </div>
                    </label>
                </div>

                <!-- Profile Info -->
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-2xl font-bold text-gray-800">{{ $profile->name }}</h1>
                    <p class="text-gray-600 mb-2">{{ $profile->email }}</p>

                    {{-- kondisi kalau user aktif --}}
                    @if ($profile->status)
                        <span
                            class="inline-block bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full font-medium">Aktif</span>
                    @else
                        <span class="inline-block bg-red-100 text-red-800 text-xs px-3 py-1 rounded-full font-medium">Tidak
                            Aktif</span>
                    @endif

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
                </nav>
            </div>

            <!-- Tab Contents -->
            <div>
                <!-- Profile Tab Content -->
                <div x-show="activeTab === 'profile'" class="animate-fade-in">
                    <h3 class="text-lg font-semibold mb-4">Profil Ku</h3>
                    <form>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2" for="name">Nama Lengkap</label>
                                <input type="text" id="name" value="{{ $profile->name }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="text-sm text-gray-500 mt-1">Silakan ubah jika ada perubahan nama.</p>
                            </div>

                            <div>
                                <label class="block text-gray-700 font-medium mb-2" for="email">Email</label>
                                <input type="email" id="email" value="{{ $profile->email }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="text-sm text-gray-500 mt-1">Gunakan email aktif yang bisa dihubungi.</p>
                            </div>

                            <div>
                                <label class="block text-gray-700 font-medium mb-2" for="image">Foto Profil</label>
                                <input type="file" id="image" accept="image/*"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="text-sm text-gray-500 mt-1">Silakan pilih gambar untuk mengganti foto profil</p>
                                <div id="preview-container" class="mt-4">
                                    <img id="preview" src="" alt="Preview"
                                        class="w-32 h-32 object-cover rounded-full hidden border border-gray-300" />
                                </div>
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
                <div x-show="activeTab === 'destinations'" x-transition.opacity.duration.500ms class="animate-fade-in">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6">Destinasi Yang Saya Bagikan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Card 1 -->
                        <div
                            class="border border-gray-200 rounded-2xl overflow-hidden shadow hover:shadow-lg transition-shadow duration-300">
                            <div class="relative h-48 bg-gray-100">
                                <img src="https://via.placeholder.com/800x400?text=Bali" alt="Bali"
                                    class="w-full h-full object-cover">
                                <div
                                    class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                                    <h4 class="text-white text-lg font-semibold">Bali</h4>
                                </div>
                            </div>
                            <div class="p-4">
                                <p class="text-sm text-gray-600 mb-3">Diajukan Pada Tanggal: 12 Maret 2025</p>
                                <div class="flex flex-wrap gap-2">
                                    <span class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full">Pantai</span>
                                    <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">Budaya</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div
                            class="border border-gray-200 rounded-2xl overflow-hidden shadow hover:shadow-lg transition-shadow duration-300">
                            <div class="relative h-48 bg-gray-100">
                                <img src="https://via.placeholder.com/800x400?text=Yogyakarta" alt="Yogyakarta"
                                    class="w-full h-full object-cover">
                                <div
                                    class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                                    <h4 class="text-white text-lg font-semibold">Yogyakarta</h4>
                                </div>
                            </div>
                            <div class="p-4">
                                <p class="text-sm text-gray-600 mb-3">Diajukan Pada Tanggal: 5 Januari 2025</p>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="bg-purple-100 text-purple-800 text-xs px-3 py-1 rounded-full">Sejarah</span>
                                    <span
                                        class="bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded-full">Kuliner</span>
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
        const input = document.getElementById('image');
        const preview = document.getElementById('preview');

        input.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };

                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
