@extends('layouts.user')
@section('title', 'Beranda')

@section('content')
    {{-- Hero Section --}}
    <section class="relative min-h-screen bg-cover bg-center bg-no-repeat"
        style="background-image: url('{{ asset('images/hero.png') }}');">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div> <!-- Overlay untuk teks lebih terbaca -->

        <div
            class="relative mx-auto w-screen max-w-screen-xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8 lg:py-32 flex flex-col items-center justify-center text-center min-h-screen">
            <div class="max-w-prose">

                <h1 class="text-4xl font-bold text-white sm:text-5xl">
                    Siap Bertualang?
                    <strong class="text-primary">Cari</strong>
                    Destinasi Impianmu!
                </h1>

                <p class="mt-4 text-base text-pretty text-gray-300 sm:text-lg/relaxed">
                    Temukan pengalaman wisata terbaik dengan informasi cuaca terkini untuk perjalanan yang lebih nyaman.
                </p>

                <div class="mt-4 flex flex-wrap justify-center gap-4 sm:mt-6">
                    <a class="inline-block rounded border border-indigo-400 bg-primary px-5 py-3 font-medium text-white shadow-sm transition-colors hover:bg-indigo-700"
                        href="{{ route('user.destinations.index') }}">
                        Jelajahi Sekarang
                    </a>

                    <a class="inline-block rounded border border-gray-300 px-5 py-3 font-medium text-white shadow-sm transition-colors hover:bg-gray-50 hover:text-gray-900"
                        href="{{ route('user.about') }}">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Destinasi Section --}}
    <x-section>
        <div class="max-w-screen-xl mx-auto px-4">
            <h3 class="text-primary font-semibold text-lg">Destinasi Favorit</h3>

            <!-- Wrapper untuk membuat tombol "Lihat Semua" turun ke bawah di mobile -->
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
                <p class="text-2xl font-bold">Destinasi Yang Wajib Kamu Kunjungi.</p>
                <a href="{{ route('user.destinations.index') }}"
                    class="text-primary text-sm font-medium hover:underline mt-2 sm:mt-0">Lihat Semua &gt;</a>
            </div>

            <!-- SwiperJS Container yang diperbaiki -->
            <div class="swiper mySwiper w-full">
                <div class="swiper-wrapper">
                    @foreach ($favoriteDestinations as $destination)
                        <div class="swiper-slide">
                            <div class="bg-white rounded-lg overflow-hidden transition-all duration-300 hover:shadow-md">
                                <!-- Card Image -->
                                <div class="relative h-52 overflow-hidden">
                                    @if ($destination->images->count() > 0)
                                        <img src="{{ asset($destination->primaryImage ? 'storage/' . $destination->primaryImage->url : 'images/auth.png') }}"
                                            alt="{{ $destination->place_name }}" class="w-full h-full object-cover">
                                    @else
                                        {{-- <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div> --}}

                                        <img src="{{ asset('images/categories/' . $destination->category->name . '.jpg') }}"
                                            alt="{{ $destination->category->name }}" class="w-full h-full object-cover">
                                    @endif

                                    <!-- Category Badge -->
                                    <div class="absolute top-3 left-3">
                                        <span
                                            class="bg-white bg-opacity-90 text-blue-600 text-xs font-medium px-2.5 py-1 rounded-full">
                                            {{ $destination->category->name }}
                                        </span>
                                    </div>

                                    <!-- Rating Badge -->
                                    <div class="absolute bottom-3 right-3">
                                        <div
                                            class="bg-white bg-opacity-90 text-amber-500 text-xs font-medium px-2.5 py-1 rounded-full flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-3.5 w-3.5 mr-1 text-amber-500 fill-amber-500" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            <span>{{ number_format($destination->rating ?? 0, 1) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Content -->
                                <div class="p-4">
                                    <h3 class="font-semibold text-lg text-gray-800 mb-1 truncate">
                                        {{ $destination->place_name }}</h3>
                                    <p class="text-sm text-gray-500 mb-3 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ $destination->administrative_area . ', ' . $destination->province }}
                                    </p>
                                    <!-- Detail Button Only -->
                                    <div class="mt-3">
                                        <a href="{{ route('user.destinations.show', $destination->slug) }}"
                                            class="block bg-blue-500 hover:bg-blue-600 text-white text-center py-2 rounded-md transition-colors text-sm font-medium">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Pagination terintegrasi dengan swiper -->
        <div class="flex justify-center w-full mt-6">
            <div class="swiper-pagination !static !transform-none"></div>
        </div>
    </x-section>

    {{-- Nusatawan Section --}}
    <div class="bg-primary py-24">
        <x-section>
            <div class="text-center ">
                <h1 class="text-2xl md:text-4xl font-semibold mb-8 text-white">
                    Apa yang
                    <span class=" text-3xl md:text-5xl font-bold">
                        Nusatawan
                    </span>
                    bawa untuk Anda?
                </h1>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <p class="text-lg">
                            <span class="text-5xl">🌤️</span> <br>
                            Informasi Cuaca
                        </p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <p class="text-4xl font-bold mb-2">
                            {{ $totalDestinationStats }}
                        </p>
                        <p class="text-lg">
                            Destinasi Wisata
                        </p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <p class="text-4xl font-bold mb-2">
                            {{ $totalUsersStats }}
                        </p>
                        <p class="text-lg">
                            Kontribusi Pengguna
                        </p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <p class="text-lg">
                            <span class="text-5xl">🛣️</span> <br>
                            Rencana Perjalanan
                        </p>
                    </div>
                </div>
            </div>
        </x-section>
    </div>

    {{-- Tombol CTA untuk smartphone --}}
    <div class="md:hidden fixed bottom-6 right-6 z-50 group">
        <!-- Main Button -->
        <a href="{{ route('user.destination-submission.create') }}"
            class="flex items-center justify-center bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-full w-16 h-16 shadow-lg hover:from-blue-700 hover:to-blue-600 transition-all duration-300 transform hover:scale-105">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
        </a>

        <!-- Tooltip that appears on tap/hover -->
        <span
            class="absolute -top-12 right-0 bg-gray-900 text-white text-sm font-medium px-4 py-2 rounded-lg shadow-md
        opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap pointer-events-none">
            Ajukan Destinasi Baru
        </span>

        <!-- Pulse Animation Effect -->
        <span
            class="absolute inset-0 rounded-full bg-blue-400 animate-ping opacity-25 duration-1000 pointer-events-none"></span>
    </div>

    {{-- Bagikan Section --}}
    <x-section>
        <section>
            <div class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:items-center md:gap-8">
                    <div>
                        <div class="max-w-lg md:max-w-none">
                            <h2 class="text-2xl font-bold text-black sm:text-3xl">
                                Bagikan Destinasi <br><span class="text-primary">Favoritmu!</span>
                            </h2>

                            <p class="mt-4 text-gray-700">
                                Punya tempat wisata tersembunyi yang wajib dikunjungi? Tambahkan destinasi impianmu dan
                                bantu wisatawan lain menemukan keindahan Indonesia yang belum banyak dikenal.
                            </p>

                            <div class="mt-6 space-y-4">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <p class="text-gray-700">Mudah dan cepat. Hanya perlu beberapa menit.</p>
                                </div>
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <p class="text-gray-700">Bantu wisatawan lain menemukan tempat indah.</p>
                                </div>
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary mr-2"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <p class="text-gray-700">Dapatkan informasi cuaca terkini untuk destinasimu.</p>
                                </div>
                            </div>

                            <a class="mt-6 inline-block rounded border border-blue-400 bg-blue-500 px-6 py-3 font-medium text-white shadow-lg transition-colors hover:bg-blue-600"
                                href="{{ route('user.destination-submission.create') }}">
                                <div class="flex items-center">
                                    <span>Bagikan Sekarang</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div>
                        <img src="https://images.unsplash.com/photo-1731690415686-e68f78e2b5bd?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                            class="rounded-lg shadow-lg" alt="Destinasi Wisata Indonesia" />
                    </div>
                </div>
            </div>
        </section>
    </x-section>

    {{-- Section FAQ --}}
    <div class="bg-gradient-to-b from-blue-50 to-white py-12 rounded-lg">
        {{-- Faq Section --}}
        <x-section class="bg-gradient-to-b from-blue-50 to-white py-12 rounded-lg">
            <div class="container mx-auto px-4">
                <div class="text-center mb-10">
                    <h1 class="text-3xl md:text-4xl font-bold mb-3 text-black">FAQ - Sistem Informasi Cuaca</h1>
                    <p class="text-gray-600 max-w-2xl mx-auto">Temukan jawaban untuk pertanyaan yang sering diajukan
                        tentang
                        layanan informasi cuaca kami yang menggunakan OpenWeatherMap</p>
                </div>

                <div class="max-w-3xl mx-auto space-y-6">
                    <details
                        class="group border border-blue-200 rounded-lg overflow-hidden transition-all duration-300 hover:shadow-md">
                        <summary
                            class="flex justify-between items-center cursor-pointer p-5 bg-white text-lg font-semibold text-primary">
                            <span>Apa itu sistem informasi cuaca ini?</span>
                            <svg class="w-5 h-5 text-blue-500 transition-transform group-open:rotate-180" fill="none"
                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="p-5 bg-white border-t border-blue-100">
                            <p class="text-gray-700">Sistem ini memberikan informasi cuaca real-time berdasarkan data dari
                                OpenWeatherMap API. Anda dapat melihat kondisi cuaca saat ini, prakiraan untuk 5 hari ke
                                depan,
                                serta berbagai parameter cuaca seperti suhu, kelembaban, dan kecepatan angin untuk lokasi
                                yang Anda pilih.</p>
                        </div>
                    </details>

                    <details
                        class="group border border-blue-200 rounded-lg overflow-hidden transition-all duration-300 hover:shadow-md">
                        <summary
                            class="flex justify-between items-center cursor-pointer p-5 bg-white text-lg font-semibold text-primary">
                            <span>Seberapa akurat data cuaca yang ditampilkan?</span>
                            <svg class="w-5 h-5 text-blue-500 transition-transform group-open:rotate-180" fill="none"
                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="p-5 bg-white border-t border-blue-100">
                            <p class="text-gray-700">Data cuaca diperbarui setiap jam dan bersumber dari OpenWeatherMap,
                                salah satu penyedia data cuaca terkemuka dunia. OpenWeatherMap mengumpulkan data dari
                                stasiun meteorologi global, radar cuaca, dan model perkiraan untuk memberikan akurasi yang
                                tinggi. Tingkat akurasi berkisar 90-95% untuk prakiraan 24 jam dan 70-85% untuk prakiraan
                                3-5 hari ke depan.</p>
                        </div>
                    </details>

                    <details
                        class="group border border-blue-200 rounded-lg overflow-hidden transition-all duration-300 hover:shadow-md">
                        <summary
                            class="flex justify-between items-center cursor-pointer p-5 bg-white text-lg font-semibold text-primary">
                            <span>Bagaimana cara membaca prakiraan cuaca di website ini?</span>
                            <svg class="w-5 h-5 text-blue-500 transition-transform group-open:rotate-180" fill="none"
                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="p-5 bg-white border-t border-blue-100">
                            <p class="text-gray-700">Anda bisa melihat beberapa parameter utama cuaca dari OpenWeatherMap:
                            </p>
                            <ul class="list-disc ml-6 mt-2 space-y-1 text-gray-700">
                                <li>Suhu saat ini dan terasa seperti (feels like) dalam °C</li>
                                <li>Kelembaban udara (dalam %)</li>
                                <li>Kecepatan dan arah angin (dalam m/s atau km/jam)</li>
                                <li>Tekanan udara (dalam hPa)</li>
                                <li>Visibilitas (dalam km)</li>
                                <li>Kondisi cuaca umum (cerah, berawan, hujan, dll)</li>
                            </ul>
                            <p class="mt-3 text-gray-700">Ikon cuaca menunjukkan kondisi secara visual berdasarkan kode
                                cuaca OpenWeatherMap. Prakiraan tersedia untuk interval 3 jam dalam 5 hari ke depan.</p>
                        </div>
                    </details>

                    <details
                        class="group border border-blue-200 rounded-lg overflow-hidden transition-all duration-300 hover:shadow-md">
                        <summary
                            class="flex justify-between items-center cursor-pointer p-5 bg-white text-lg font-semibold text-primary">
                            <span>Kenapa datanya tidak diperbarui?</span>
                            <svg class="w-5 h-5 text-blue-500 transition-transform group-open:rotate-180" fill="none"
                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="p-5 bg-white border-t border-blue-100">
                            <p class="text-gray-700">Beberapa kemungkinan penyebab:</p>
                            <ul class="list-disc ml-6 mt-2 space-y-1 text-gray-700">
                                <li>Masalah koneksi internet pada perangkat Anda</li>
                                <li>Cache browser yang perlu dibersihkan</li>
                                <li>Batas kuota API OpenWeatherMap yang terlampaui (pada paket free)</li>
                                <li>Pemeliharaan atau gangguan pada server OpenWeatherMap</li>
                                <li>Lokasi yang dipilih tidak memiliki data terbaru</li>
                            </ul>
                            <p class="mt-3 text-gray-700">Coba refresh halaman atau bersihkan cache browser. Jika masalah
                                berlanjut, hubungi kami melalui halaman <span
                                    class="text-blue-600 font-medium">Kontak</span>.</p>
                        </div>
                    </details>

                    <details
                        class="group border border-blue-200 rounded-lg overflow-hidden transition-all duration-300 hover:shadow-md">
                        <summary
                            class="flex justify-between items-center cursor-pointer p-5 bg-white text-lg font-semibold text-primary">
                            <span>Apa perbedaan antara prakiraan cuaca harian dan 5 hari?</span>
                            <svg class="w-5 h-5 text-blue-500 transition-transform group-open:rotate-180" fill="none"
                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="p-5 bg-white border-t border-blue-100">
                            <p class="text-gray-700">OpenWeatherMap menyediakan dua jenis prakiraan yang kami tampilkan:
                            </p>
                            <ul class="list-disc ml-6 mt-2 space-y-2 text-gray-700">
                                <li><span class="font-medium">Prakiraan saat ini</span>: Menampilkan kondisi cuaca
                                    real-time dan beberapa jam ke depan dengan tingkat akurasi tertinggi.</li>
                                <li><span class="font-medium">Prakiraan 5 hari</span>: Menampilkan kondisi cuaca untuk 5
                                    hari ke depan dengan interval 3 jam. Akurasi menurun seiring dengan jarak waktu
                                    prakiraan.</li>
                            </ul>
                            <p class="mt-3 text-gray-700">Untuk pengambilan keputusan jangka pendek, lebih baik menggunakan
                                prakiraan saat ini. Untuk perencanaan beberapa hari ke depan, prakiraan 5 hari memberikan
                                gambaran umum yang cukup akurat.</p>
                        </div>
                    </details>

                </div>

                <div class="text-center mt-10">
                    <p class="text-gray-600">Masih memiliki pertanyaan? <a href="#kontak"
                            class="text-primary font-medium hover:text-blue-800">Hubungi kami</a></p>
                    <p class="text-sm text-gray-500 mt-2">Data cuaca disediakan oleh <a href="https://openweathermap.org"
                            target="_blank" rel="noopener noreferrer"
                            class="text-primary hover:underline">OpenWeatherMap</a></p>
                </div>
            </div>
        </x-section>
    </div>

    {{-- Pop Up CTA --}}
    <div id="ctaPopup" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Bagikan Destinasi Favorit Anda!</h3>
                <button id="closePopup" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <p class="text-gray-600 mb-4">Temukan tempat wisata tersembunyi yang belum banyak diketahui? Bantu wisatawan
                lain dengan membagikan destinasi favoritmu!</p>
            <div class="flex justify-center">
                <a href="{{ route('user.destination-submission.create') }}"
                    class="inline-block rounded-lg bg-blue-500 px-6 py-3 text-white font-medium hover:bg-blue-600 transition-colors">
                    Ajukan Destinasi Sekarang
                </a>
            </div>
            <div class="mt-4 text-center">
                <label class="inline-flex items-center">
                    <input type="checkbox" id="dontShowAgain" class="form-checkbox h-4 w-4 text-blue-500">
                    <span class="ml-2 text-sm text-gray-600">Jangan tampilkan lagi</span>
                </label>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    {{-- Script untuk popup dan floating button --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const popup = document.getElementById('ctaPopup');
            const closeBtn = document.getElementById('closePopup');
            const dontShowCheckbox = document.getElementById('dontShowAgain');

            // Cek apakah user sudah memilih untuk tidak menampilkan popup
            if (!localStorage.getItem('hideDestinationCta')) {
                // Tampilkan popup setelah 5 detik
                setTimeout(() => {
                    popup.classList.remove('hidden');
                }, 5000);
            }

            // Tutup popup
            closeBtn.addEventListener('click', function() {
                popup.classList.add('hidden');

                // Jika checkbox dicentang, simpan ke localStorage
                if (dontShowCheckbox.checked) {
                    localStorage.setItem('hideDestinationCta', 'true');
                }
            });
        });
    </script>
@endpush
