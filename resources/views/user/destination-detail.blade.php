@extends('layouts.user')
@section('title', 'Detail Destinasi')

@section('content')
    <div class="container mx-auto max-w-full">
        <!-- Gambar Header dengan Overlay - Full Width -->
        <div class="relative mb-4 w-full">
            <img src="{{ asset('images/auth.png') }}" alt="Pantai Kuta"
                class="w-full h-[300px] md:h-[500px] lg:h-[600px] max-h-96 object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
            <div class="absolute bottom-0 left-0 w-full p-6 text-white">
                <div class="container mx-auto px-0 md:px-6 md:max-w-4xl">
                    <div class="flex items-center text-sm mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Bali, Indonesia - 8 Januari 2023
                    </div>
                    <h1 class="text-3xl font-bold">Pantai Kuta</h1>
                    <div class="flex items-center mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="ml-1 text-white">4.5</span>
                        <span class="mx-2 text-white">•</span>
                        <button class="text-blue-300">16 Review</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4">
            <!-- Informasi Penulis (Baru) -->
            <div class="bg-white rounded-lg shadow-md p-5 mb-6">
                <div class="flex flex-col sm:flex-row items-start">
                    <div class="flex-shrink-0 mr-4 mb-3 sm:mb-0">
                        <img src="{{ asset('images/auth.png') }}" alt="Author"
                            class="w-12 h-12 rounded-full object-cover border-2 border-blue-500">
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center">
                            <h3 class="font-semibold text-gray-800">Ditulis oleh: Anisa Sari</h3>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Dipublikasikan: 8 Januari 2023 • Diperbarui: 15 Januari 2023
                        </p>
                        <div class="flex items-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span class="text-sm text-gray-500">238 dilihat</span>
                            <span class="mx-2 text-gray-400">•</span>
                            <button class="text-sm text-blue-500 hover:underline">Lihat profil penulis</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Destinasi -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <p class="text-gray-700 mb-4">
                    Kei-nya bertemakan Pantai Kuta, salah satu destinasi pantai terbaik di Bali dengan pasir putih terhalus
                    dan
                    ombak yang sempurna untuk berselancar. Nikmati pemandangan matahari terbenam yang spektakuler, suasana
                    pantai yang ramai, hingga berbagai aktivitas menarik seperti bermain parasailing atau banana boat.
                    Jelajahi
                    juga berbagai kafe dan restoran di sekitarnya.
                </p>
                <p class="text-gray-700 mb-4">
                    Lokasi ini menawarkan berbagai aktivitas. Telusuri pesisir pantai sembari menikmati sunset yang memukau,
                    gunakan papan selancar untuk berselancar di ombak, duduk santai sambil menyesap minuman kelapa muda,
                    bahkan
                    temukan berbagai kuliner yang beragam di tepi pantai. 😍
                </p>
                <div class="text-sm text-gray-600 mb-2">
                    <span class="font-medium">Jam Buka:</span> 06.00 - 19.00 (setiap hari, termasuk weekend)
                </div>
                <div class="text-sm text-gray-600">
                    <span class="font-medium">Tiket Masuk:</span> Rp 20.000/orang, Parkir motor Rp 5.000 dan mobil Rp 10.000
                    (weekdays dan weekend)
                </div>
            </div>

            <!-- Cuaca dengan Tab Pilihan (Hari Ini/Prakiraan) -->
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
                    <h2 class="text-xl font-semibold mb-2 sm:mb-0">Informasi Cuaca</h2>
                    <div class="flex bg-gray-100 rounded-lg overflow-hidden">
                        <button id="todayBtn" class="px-4 py-2 font-medium bg-blue-500 text-white">Hari Ini</button>
                        <button id="forecastBtn"
                            class="px-4 py-2 font-medium text-gray-700 hover:bg-gray-200">Prakiraan</button>
                    </div>
                </div>

                <!-- Cuaca Hari Ini (Default) - Simetris dengan grid-cols-3 pada semua ukuran layar -->
                <div id="todayWeather" class="grid grid-cols-1 sm:grid-cols-3 gap-4 ">
                    <div class="bg-white rounded-lg shadow-md p-4 flex flex-col items-center justify-center h-40">
                        <h3 class="text-gray-600 text-sm mb-1">Pagi</h3>
                        <div class="text-2xl font-bold mb-1">31°C</div>
                        <div class="flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-4 flex flex-col items-center justify-center h-40">
                        <h3 class="text-gray-600 text-sm mb-1">Siang</h3>
                        <div class="text-2xl font-bold mb-1">33°C</div>
                        <div class="flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-4 flex flex-col items-center justify-center h-40">
                        <h3 class="text-gray-600 text-sm mb-1">Malam</h3>
                        <div class="text-2xl font-bold mb-1">29°C</div>
                        <div class="flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Prakiraan Cuaca (Hidden by Default) -->
                <div id="forecastWeather" class="hidden">
                    <div class="overflow-x-auto">
                        <div class="flex space-x-4 py-2 justify-between">
                            <!-- Besok -->
                            <div
                                class="bg-white rounded-lg shadow-md p-4 flex flex-col items-center justify-center flex-1">
                                <h3 class="text-gray-800 font-medium mb-2">Besok</h3>
                                <div class="text-xl font-bold mb-1">32°C</div>
                                <div class="flex items-center justify-center mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-600">Cerah</span>
                            </div>

                            <!-- 2 Hari Lagi -->
                            <div
                                class="bg-white rounded-lg shadow-md p-4 flex flex-col items-center justify-center flex-1">
                                <h3 class="text-gray-800 font-medium mb-2">2 Hari</h3>
                                <div class="text-xl font-bold mb-1">30°C</div>
                                <div class="flex items-center justify-center mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-600">Berawan</span>
                            </div>

                            <!-- 3 Hari Lagi -->
                            <div
                                class="bg-white rounded-lg shadow-md p-4 flex flex-col items-center justify-center flex-1">
                                <h3 class="text-gray-800 font-medium mb-2">3 Hari</h3>
                                <div class="text-xl font-bold mb-1">28°C</div>
                                <div class="flex items-center justify-center mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-600">Hujan</span>
                            </div>

                            <!-- 4 Hari Lagi -->
                            <div
                                class="bg-white rounded-lg shadow-md p-4 flex flex-col items-center justify-center flex-1">
                                <h3 class="text-gray-800 font-medium mb-2">4 Hari</h3>
                                <div class="text-xl font-bold mb-1">29°C</div>
                                <div class="flex items-center justify-center mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-300" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-600">Berawan Sebagian</span>
                            </div>

                            <!-- 5 Hari Lagi -->
                            <div
                                class="bg-white rounded-lg shadow-md p-4 flex flex-col items-center justify-center flex-1">
                                <h3 class="text-gray-800 font-medium mb-2">5 Hari</h3>
                                <div class="text-xl font-bold mb-1">31°C</div>
                                <div class="flex items-center justify-center mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-600">Cerah</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Wisata Terdekat dengan Foto Full Width -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                            clip-rule="evenodd" />
                    </svg>
                    Wisata Terdekat
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-gray-100 rounded-lg overflow-hidden shadow-md h-20 md:h-80">
                        <img src="https://via.placeholder.com/300" alt="Wisata Terdekat"
                            class="w-full h-full object-cover">
                    </div>
                    <div class="bg-gray-100 rounded-lg overflow-hidden shadow-md h-20 md:h-80">
                        <img src="https://via.placeholder.com/300" alt="Wisata Terdekat"
                            class="w-full h-full object-cover">
                    </div>
                    <div class="bg-gray-100 rounded-lg overflow-hidden shadow-md h-20 md:h-80">
                        <img src="https://via.placeholder.com/300" alt="Wisata Terdekat"
                            class="w-full h-full object-cover">
                    </div>
                    <div class="bg-gray-100 rounded-lg overflow-hidden shadow-md h-20 md:h-80">
                        <img src="https://via.placeholder.com/300" alt="Wisata Terdekat"
                            class="w-full h-full object-cover">
                    </div>
                </div>
            </div>

            <!-- Form Komentar -->
            <div class="mb-6 pb-6">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z"
                            clip-rule="evenodd" />
                    </svg>
                    Tinggalkan Komentar
                </h2>
                <form>
                    <textarea rows="4"
                        class="w-full border border-gray-300 rounded-lg p-3 mb-4 focus:outline-none focus:border-blue-500"
                        placeholder="Tulis komentar Anda..."></textarea>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg">Kirim
                        Komentar</button>
                </form>
            </div>

            <!-- Komentar yang ada -->
            <div>
                <div class="bg-white rounded-lg shadow-md p-4 mb-4">
                    <div class="flex items-start mb-3">
                        <div class="flex-shrink-0 mr-3">
                            <div class="w-10 h-10 bg-gray-300 rounded-full"></div>
                        </div>
                        <div>
                            <h4 class="font-medium">Auliya Rachman</h4>
                            <p class="text-sm text-gray-500">21 Januari 2023</p>
                        </div>
                    </div>
                    <p class="text-gray-700">Pemandangan sunsetnya sangat indah! Tetapi agak ramai di akhir pekan.</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4 mb-4">
                    <div class="flex items-start mb-3">
                        <div class="flex-shrink-0 mr-3">
                            <div class="w-10 h-10 bg-gray-300 rounded-full"></div>
                        </div>
                        <div>
                            <h4 class="font-medium">Budi Santoso</h4>
                            <p class="text-sm text-gray-500">15 Januari 2023</p>
                        </div>
                    </div>
                    <p class="text-gray-700">Pantainya bersih dan nyaman. Banyak pilihan makanan di sekitar.</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="flex items-start mb-3">
                        <div class="flex-shrink-0 mr-3">
                            <div class="w-10 h-10 bg-gray-300 rounded-full"></div>
                        </div>
                        <div>
                            <h4 class="font-medium">Ratna Purnama</h4>
                            <p class="text-sm text-gray-500">10 Januari 2023</p>
                        </div>
                    </div>
                    <p class="text-gray-700">Kunjungan kedua saya ke sini dan masih suka dengan suasananya!</p>
                </div>
            </div>
        </div>
    </div>

    <div class="my-5"></div>

    <!-- Script untuk Toggle Cuaca -->
    <script>
        const todayBtn = document.getElementById('todayBtn');
        const forecastBtn = document.getElementById('forecastBtn');
        const todayWeather = document.getElementById('todayWeather');
        const forecastWeather = document.getElementById('forecastWeather');

        todayBtn.addEventListener('click', function() {
            todayWeather.classList.remove('hidden');
            forecastWeather.classList.add('hidden');
            todayBtn.classList.add('bg-blue-500', 'text-white');
            todayBtn.classList.remove('text-gray-700', 'hover:bg-gray-200');
            forecastBtn.classList.remove('bg-blue-500', 'text-white');
            forecastBtn.classList.add('text-gray-700', 'hover:bg-gray-200');
        });

        forecastBtn.addEventListener('click', function() {
            todayWeather.classList.add('hidden');
            forecastWeather.classList.remove('hidden');
            forecastBtn.classList.add('bg-blue-500', 'text-white');
            forecastBtn.classList.remove('text-gray-700', 'hover:bg-gray-200');
            todayBtn.classList.remove('bg-blue-500', 'text-white');
            todayBtn.classList.add('text-gray-700', 'hover:bg-gray-200');
        });
    </script>
@endsection
