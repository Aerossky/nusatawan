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
            <a href="{{ route('user.destination-favorite.index') }}"
                class="bg-white p-4 rounded-lg shadow-md text-center transition-transform hover:transform hover:scale-105">
                <div class="text-3xl font-bold text-blue-600">{{ $likedDestinationUserTotal }}</div>
                <div class="text-sm text-gray-600 font-medium">Menyukai Destinasi</div>
            </a>
        </div>

        <!-- Tabs Section -->
        <div class="bg-white rounded-lg shadow-md p-6" x-data="{
            activeTab: window.location.hash === '#destinasi' ? 'destinations' : 'profile'
        }">
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
                <div x-show="activeTab === 'destinations'" class="animate-fade-in">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6">Destinasi Yang Saya Bagikan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if ($destinationSubmissions->isEmpty())
                            <div class="col-span-2 text-center">
                                <p class="text-gray-500">Tidak ada destinasi yang dibagikan.</p>
                            </div>
                        @endif
                        <!-- Card 1 -->

                        @foreach ($destinationSubmissions as $destinationSubmission)
                            <div
                                class="border-0 rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 bg-white flex flex-col">
                                <!-- Header/Image Section dengan efek zoom hover -->
                                <div class="relative h-52 overflow-hidden">
                                    {{-- @dd($destinationSubmission->images) --}}
                                    <img src="{{ asset($destinationSubmission->images ? 'storage/' . $destinationSubmission->images[0]['url'] : 'images/auth.png') }}"
                                        alt="{{ $destinationSubmission->place_name }}"
                                        class="w-full h-full object-cover transform transition-transform duration-500 hover:scale-105">

                                    <!-- Overlay gradient yang lebih halus -->
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent">
                                    </div>

                                    <!-- Category badge dipindahkan ke atas -->
                                    <div class="absolute top-3 right-3">
                                        <span
                                            class="bg-blue-600/90 text-white font-medium text-xs px-3 py-1 rounded-full shadow-sm backdrop-blur-sm">
                                            {{ $destinationSubmission->category->name }}
                                        </span>
                                    </div>

                                    <!-- Judul dengan ukuran yang lebih besar -->
                                    <div class="absolute bottom-0 left-0 right-0 p-4">
                                        <h3 class="text-white text-xl font-bold tracking-tight">
                                            {{ $destinationSubmission->place_name }}
                                        </h3>
                                    </div>
                                </div>

                                <!-- Content Section dengan spacing yang lebih baik -->
                                <div class="p-5 flex flex-col flex-grow">
                                    <!-- Status dengan desain yang lebih menonjol -->
                                    <div class="mb-3 flex items-center">
                                        @if ($destinationSubmission->status == 'approved')
                                            <span
                                                class="bg-emerald-100 text-emerald-700 font-medium text-xs px-3 py-1.5 rounded-full inline-flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Diterima
                                            </span>
                                        @elseif ($destinationSubmission->status == 'rejected')
                                            <span
                                                class="bg-red-100 text-red-700 font-medium text-xs px-3 py-1.5 rounded-full inline-flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Ditolak
                                            </span>
                                        @else
                                            <span
                                                class="bg-amber-100 text-amber-700 font-medium text-xs px-3 py-1.5 rounded-full inline-flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Menunggu Verifikasi
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Tanggal pengajuan dengan formatting yang lebih bagus -->
                                    <p class="text-sm text-gray-500 mb-3 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Diajukan: {{ $destinationSubmission->created_at->format('d F Y') }}
                                    </p>

                                    <!-- Notes berdasarkan status -->
                                    @if ($destinationSubmission->status == 'approved' && $destinationSubmission->approval_note)
                                        <div
                                            class="mt-1 mb-3 bg-emerald-50 p-4 rounded-xl text-sm text-emerald-800 border border-emerald-100">
                                            <p class="font-medium flex items-center mb-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Catatan Persetujuan:
                                            </p>
                                            <p>{{ $destinationSubmission->approval_note }}</p>
                                        </div>
                                    @elseif ($destinationSubmission->status == 'rejected' && $destinationSubmission->rejection_note)
                                        <div
                                            class="mt-1 mb-3 bg-red-50 p-4 rounded-xl text-sm text-red-800 border border-red-100">
                                            <p class="font-medium flex items-center mb-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                                Alasan Penolakan:
                                            </p>
                                            <p>{{ $destinationSubmission->rejection_note }}</p>
                                        </div>
                                    @endif

                                    <!-- Catatan umum jika ada -->
                                    @if ($destinationSubmission->admin_note)
                                        <div
                                            class="mt-1 bg-gray-50 p-4 rounded-xl text-sm text-gray-700 border border-gray-100">
                                            <p class="font-medium text-gray-800 flex items-center mb-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Catatan Umum:
                                            </p>
                                            <p>{{ $destinationSubmission->admin_note }}</p>
                                        </div>
                                    @endif

                                    <!-- Spacer untuk menjaga layout konsisten -->
                                    <div class="flex-grow"></div>

                                    <!-- Footer section -->
                                    <div class="mt-4 pt-3 border-t border-gray-100 flex justify-end">
                                        @php
                                            if (!function_exists('makeSlug')) {
                                                function makeSlug($text)
                                                {
                                                    // Function implementation
                                                    $text = strtolower($text);
                                                    $text = preg_replace('/\s+/', '-', $text);
                                                    $text = preg_replace('/[^a-z0-9\-]/', '', $text);
                                                    $text = preg_replace('/-+/', '-', $text);
                                                    $text = trim($text, '-');
                                                    return $text;
                                                }
                                            }

                                            $generatedSlug = makeSlug($destinationSubmission->place_name);
                                        @endphp

                                        @if ($destinationSubmission->status == 'approved')
                                            {{-- Jika sudah diapprove, arahkan ke halaman destinasi --}}
                                            <a href="{{ route('user.destinations.show', $destinationSubmission->destination_id ?? $generatedSlug) }}"
                                                class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors duration-200 flex items-center">
                                                Lihat Destinasi
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        @elseif($destinationSubmission->status == 'rejected')
                                            <a href="{{ route('user.destination-submission.create') }}"
                                                class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors duration-200 flex items-center">
                                                Ajukan Ulang Destinasi
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </x-section>
@endsection

@push('scripts')
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

        // Tempatkan ini di akhir halaman profile.blade.php
        document.addEventListener('DOMContentLoaded', function() {
            // Fungsi untuk mengatur activeTab pada semua komponen Alpine
            function setActiveTabOnAllComponents(tabName) {
                document.querySelectorAll('[x-data]').forEach(component => {
                    if (component.__x && component.__x.$data.activeTab !== undefined) {
                        component.__x.$data.activeTab = tabName;
                    }
                });
            }

            // Periksa hash URL saat halaman dimuat
            if (window.location.hash === '#destinasi') {
                // Tunggu beberapa saat untuk memastikan Alpine sudah diinisialisasi
                setTimeout(() => {
                    setActiveTabOnAllComponents('destinations');
                }, 300); // Meningkatkan timeout untuk memastikan Alpine sudah siap
            }
        });

        // Tangani perubahan hash URL - pastikan hanya ada satu listener
        window.addEventListener('hashchange', function() {
            if (window.location.hash === '#destinasi') {
                setTimeout(() => {
                    document.querySelectorAll('[x-data]').forEach(component => {
                        if (component.__x && component.__x.$data.activeTab !== undefined) {
                            component.__x.$data.activeTab = 'destinations';
                        }
                    });
                }, 100);
            }
        });
    </script>
@endpush
