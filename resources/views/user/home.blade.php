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
                        href="#">
                        Jelajahi Sekarang
                    </a>

                    <a class="inline-block rounded border border-gray-300 px-5 py-3 font-medium text-white shadow-sm transition-colors hover:bg-gray-50 hover:text-gray-900"
                        href="#">
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
                <a href="#" class="text-primary text-sm font-medium hover:underline mt-2 sm:mt-0">Lihat Semua &gt;</a>
            </div>

            <!-- SwiperJS Container -->
            <div class="swiper mySwiper relative pb-32">
                <div class="swiper-wrapper">

                    {{-- @foreach ($destinations as $destination) --}}
                    <div class="swiper-slide w-auto max-w-xs">
                        <div class="bg-white shadow-md rounded-lg overflow-hidden">
                            <img src="{{ asset('images/auth.png') }}" alt="" class="w-full h-52 object-cover">
                            <div class="p-4">
                                <h3 class="text-lg font-semibold">Nama</h3>
                                <p class="text-gray-600 text-sm">Lokasi</p>
                            </div>
                        </div>
                    </div>
                    {{-- @endforeach --}}
                </div>

                <!-- Spacer agar pagination lebih turun -->
                <div class="h-10"></div>

                <!-- Pagination -->
                <div class="swiper-pagination absolute -bottom-16 left-1/2 transform -translate-x-1/2 z-50"></div>
            </div>
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
                            100
                        </p>
                        <p class="text-lg">
                            Destinasi Wisata
                        </p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <p class="text-4xl font-bold mb-2">
                            50
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

                            <a class="mt-6 inline-block rounded border border-indigo-400 bg-primary px-5 py-3 font-medium text-white shadow-sm transition-colors hover:bg-indigo-700"
                                href="#">
                                Bagikan Sekarang
                            </a>
                        </div>
                    </div>

                    <div>
                        <img src="https://images.unsplash.com/photo-1731690415686-e68f78e2b5bd?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                            class="rounded" alt="" />
                    </div>
                </div>
            </div>
        </section>
    </x-section>

    {{-- Faq Section --}}
    <div class="bg-gradient-to-b from-blue-50 to-white py-12 rounded-lg">
        {{-- Faq Section --}}
        <x-section class="bg-gradient-to-b from-blue-50 to-white py-12 rounded-lg">
            <div class="container mx-auto px-4">
                <div class="text-center mb-10">
                    <h1 class="text-3xl md:text-4xl font-bold mb-3 text-black">FAQ - Sistem Informasi Cuaca</h1>
                    <p class="text-gray-600 max-w-2xl mx-auto">Temukan jawaban untuk pertanyaan yang sering diajukan tentang
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

@endsection
