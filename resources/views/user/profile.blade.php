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
                            <img id="profileImage" src="{{ asset('storage/' . $profile->image) }}" alt="Pengguna"
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
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-white p-4 rounded-lg shadow-md text-center transition-transform hover:transform hover:scale-105">
                <div class="text-3xl font-bold text-blue-600">{{ $destinationUserTotal }}</div>
                <div class="text-sm text-gray-600 font-medium">Destinasi</div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md text-center transition-transform hover:transform hover:scale-105">
                <div class="text-3xl font-bold text-blue-600">{{ $likedDestinationUserTotal }}</div>
                <div class="text-sm text-gray-600 font-medium">Menyukai Destinasi</div>
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
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold mb-6">Profil Ku</h3>

                        <form action="{{ route('user.profile.update', $profile) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <!-- Foto Profil Section -->
                            <div class="mb-8">
                                <div class="flex flex-col items-center md:items-start">
                                    <div class="mb-6">
                                        <label class="block text-gray-700 font-medium mb-2" for="image">Foto
                                            Profil</label>
                                        <div class="flex items-center gap-6">
                                            <div
                                                class="bg-gray-100 rounded-full w-24 h-24 overflow-hidden border-2 border-white shadow">
                                                <img id="current-profile" src="{{ asset('storage/' . $profile->image) }}"
                                                    alt="pengguna" class="w-full h-full object-cover">
                                            </div>
                                            <div class="flex-1">
                                                <input type="file" id="image" name="image" accept="image/*"
                                                    class="w-full px-4 py-2 border {{ $errors->has('image') ? 'border-red-500' : 'border-gray-300' }} rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                <p class="text-sm text-gray-500 mt-1">Silakan pilih gambar untuk mengganti
                                                    foto profil</p>
                                                @error('image')
                                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <!-- Informasi Dasar -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-2" for="name">Nama
                                            Lengkap</label>
                                        <input type="text" id="name" name="name"
                                            value="{{ old('name', $profile->name ?? 'Budi Santoso') }}"
                                            class="w-full px-4 py-2 border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <p class="text-sm text-gray-500 mt-1">Silakan ubah jika ada perubahan nama.</p>
                                        @error('name')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-gray-700 font-medium mb-2" for="email">Email</label>
                                        <input type="email" id="email" name="email"
                                            value="{{ old('email', $profile->email ?? 'budi@example.com') }}"
                                            class="w-full px-4 py-2 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <p class="text-sm text-gray-500 mt-1">Gunakan email aktif yang bisa dihubungi.</p>
                                        @error('email')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Password Section -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-2" for="password">Kata Sandi
                                            Baru</label>
                                        <input type="password" id="password" name="password"
                                            class="w-full px-4 py-2 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }} rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @if ($errors->has('password'))
                                            <p class="text-red-500 text-sm mt-1">{{ $errors->first('password') }}</p>
                                        @else
                                            <p class="text-sm text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah kata
                                                sandi.</p>
                                        @endif
                                    </div>

                                    <div>
                                        <label class="block text-gray-700 font-medium mb-2"
                                            for="password_confirmation">Konfirmasi
                                            Kata Sandi</label>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="w-full px-4 py-2 border {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-gray-300' }} rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @if ($errors->has('password_confirmation'))
                                            <p class="text-red-500 text-sm mt-1">
                                                {{ $errors->first('password_confirmation') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8">
                                <button type="submit"
                                    class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 font-medium transition-colors">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Destinations Tab Content -->
                <div x-show="activeTab === 'destinations'" x-transition.opacity.duration.500ms class="animate-fade-in">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6">Destinasi Yang Saya Bagikan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Card 1 -->
                        <div
                            class="border border-gray-200 rounded-2xl overflow-hidden shadow hover:shadow-lg transition-shadow duration-300">
                            <div class="relative h-48 bg-gray-100">
                                <img src="" alt="Bali" class="w-full h-full object-cover">
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
                                <img src="" alt="Yogyakarta" class="w-full h-full object-cover">
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
        // JavaScript untuk preview gambar
        document.getElementById('image').addEventListener('change', function(e) {
            const currentProfile = document.getElementById('current-profile');
            const file = e.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    currentProfile.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
