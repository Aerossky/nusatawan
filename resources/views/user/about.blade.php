@extends('layouts.user')
@section('title', 'Tentang Kami')
@section('content')
    <!-- Header Banner -->
    <div class="relative">
        <div class="w-full h-64 md:h-96 overflow-hidden">
            <img src="{{ asset('images/indonesia-panorama.jpg') }}" alt="Panorama Indonesia"
                class="w-full h-full object-cover">
            <!-- Dot Pattern Overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-40">
                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
                    <pattern id="dots" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                        <circle cx="3" cy="3" r="1.5" fill="white" opacity="0.2" />
                    </pattern>
                    <rect x="0" y="0" width="100%" height="100%" fill="url(#dots)" />
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <h1 class="text-4xl md:text-5xl font-bold text-white text-center">Temukan Indonesia Bersama Kami</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-screen-xl mx-auto py-12 px-4">
        <!-- Sejarah Platform -->
        <div class="mb-16 text-center">
            <h2 class="text-3xl font-bold mb-6">Perjalanan <span class="text-primary">Kami</span></h2>
            <p class="text-gray-700 max-w-3xl mx-auto">
                Platform ini lahir dari kesadaran akan pentingnya informasi akurat tentang destinasi wisata Indonesia.
                Dikembangkan pertama kali pada 2023 sebagai bagian program Studi Independen Bersertifikat Dicoding Batch 6
                bersama Aufaa Husniati dan Adriansyah.
            </p>
            <p class="text-gray-700 max-w-3xl mx-auto">
                Awalnya, platform ini hanya menyajikan informasi destinasi wisata secara umum. Kini, sebagai proyek skripsi,
                saya mengembangkannya dengan menambahkan fitur prakiraan cuaca untuk setiap destinasi, membantu wisatawan
                merencanakan perjalanan lebih aman dan nyaman dengan informasi cuaca real-time.
            </p>
        </div>

        <!-- Visi & Misi -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-16">
            <div class="order-2 md:order-1">
                <h2 class="text-3xl font-bold text-gray-900">Misi <span class="text-primary">Kami</span></h2>
                <p class="mt-4 text-gray-700">
                    Kami percaya bahwa perencanaan perjalanan yang baik dapat meningkatkan pengalaman wisata Anda.
                    Oleh karena itu, kami menghadirkan informasi wisata lengkap dengan prakiraan cuaca agar Anda dapat
                    memilih waktu terbaik untuk menjelajahi destinasi impian Anda.
                </p>
                <h3 class="text-xl font-bold text-gray-900 mt-6">Visi Kami</h3>
                <p class="mt-2 text-gray-700">
                    Menjadi platform terdepan yang menghubungkan wisatawan dengan beragam keindahan Indonesia
                    melalui informasi yang akurat, terpercaya, dan mudah diakses.
                </p>
                <h3 class="text-xl font-bold text-gray-900 mt-6">Nilai-Nilai Kami</h3>
                <ul class="mt-2 text-gray-700 space-y-2">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-primary mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span>Akurasi informasi dalam setiap konten yang kami sajikan</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-primary mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span>Keberlanjutan pariwisata yang bertanggung jawab</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-primary mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span>Pemberdayaan komunitas lokal di setiap destinasi</span>
                    </li>
                </ul>
            </div>
            <div class="order-1 md:order-2">
                <img src="{{ asset('images/destinations/IMG_20250402_182511.jpg') }}" alt="Nusa Dua Bali"
                    class="rounded-lg shadow-lg w-full h-96 object-cover">
            </div>
        </div>

        <!-- Fitur Unggulan -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-center mb-10">Fitur <span class="text-primary">Unggulan</span></h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md text-center transition-transform hover:scale-105">
                    <div
                        class="bg-primary bg-opacity-10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Destinasi Terpopuler</h3>
                    <p class="text-gray-600">Informasi lengkap tentang destinasi wisata populer di seluruh Indonesia</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md text-center transition-transform hover:scale-105">
                    <div
                        class="bg-primary bg-opacity-10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5.5 16a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 16h-8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Prakiraan Cuaca</h3>
                    <p class="text-gray-600">Prakiraan cuaca akurat untuk membantu Anda merencanakan perjalanan dengan lebih
                        baik</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md text-center transition-transform hover:scale-105">
                    <div
                        class="bg-primary bg-opacity-10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Panduan Wisata</h3>
                    <p class="text-gray-600">Tips dan panduan lengkap untuk berbagai jenis perjalanan di Indonesia</p>
                </div>
            </div>
        </div>

        <!-- Statistik Platform -->
        <div class="bg-gray-50 rounded-xl p-8 mb-16">
            <h2 class="text-3xl font-bold text-center mb-10">Platform <span class="text-primary">Dalam Angka</span></h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div>
                    <div class="text-4xl font-bold text-primary mb-2">500+</div>
                    <p class="text-gray-600">Destinasi Wisata</p>
                </div>
                <div>
                    <div class="text-4xl font-bold text-primary mb-2">10k+</div>
                    <p class="text-gray-600">Pengguna Aktif</p>
                </div>
                <div>
                    <div class="text-4xl font-bold text-primary mb-2">34</div>
                    <p class="text-gray-600">Provinsi di Indonesia</p>
                </div>
                <div>
                    <div class="text-4xl font-bold text-primary mb-2">95%</div>
                    <p class="text-gray-600">Tingkat Kepuasan</p>
                </div>
            </div>
        </div>

        <!-- Tentang Pengembang -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-center mb-10">Tentang <span class="text-primary">Pengembang</span></h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                <div class="col-span-1">
                    <img src="{{ asset('images/team/developer.png') }}" alt="Pengembang"
                        class="rounded-lg shadow-md w-80 h-80 object-cover mx-auto object-top">
                </div>
                <div class="col-span-2">
                    <h3 class="text-2xl font-bold text-gray-900">Risky</h3>
                    <p class="text-primary font-medium">Web Developer & Travel Enthusiast</p>
                    <div class="w-16 h-1 bg-primary my-4"></div>
                    <p class="text-gray-700 mb-4">
                        Halo! Saya adalah pengembang di balik platform ini. Dengan latar belakang di bidang pengembangan web
                        dan semangat untuk menjelajahi keindahan Indonesia, saya berkomitmen membangun solusi digital yang
                        membantu wisatawan merencanakan perjalanan dengan lebih mudah dan informatif.
                    </p>
                    <p class="text-gray-700 mb-4">
                        Saya sangat tertarik dengan dunia teknologi yang terus berkembang, dan saat ini fokus mengasah
                        kemampuan di bidang pengembangan web dan aplikasi, khususnya menggunakan PHP dan JavaScript. Saya
                        juga aktif mengikuti tren serta inovasi terbaru dalam industri teknologi, dan selalu bersemangat
                        untuk terus belajar dan memperluas keahlian.
                    </p>
                    <p class="text-gray-700 mb-6">
                        Dengan pengalaman lebih dari 2 tahun di bidang web development, saya memadukan keahlian teknis
                        dengan kecintaan terhadap dunia pariwisata untuk menciptakan platform yang bermanfaat ini. Saya
                        berharap platform ini dapat menjadi sumber informasi yang akurat dan terus berkembang melalui
                        kontribusi dari para pengguna.
                    </p>
                    <div class="flex space-x-4">
                        <!-- GitHub -->
                        <a href="https://github.com/Aerossky" target="_blank"
                            class="bg-gray-800 text-white p-2 rounded-full hover:bg-gray-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5">
                                <path fill="currentColor"
                                    d="M12 0C5.37 0 0 5.37 0 12c0 5.3 3.44 9.8 8.2 11.4.6.1.82-.26.82-.58v-2.3c-3.34.72-4.04-1.6-4.04-1.6-.55-1.4-1.35-1.8-1.35-1.8-1.1-.75.08-.74.08-.74 1.2.08 1.84 1.2 1.84 1.2 1.1 1.9 2.92 1.34 3.64 1.02.1-.8.43-1.35.78-1.66-2.67-.3-5.47-1.34-5.47-5.96 0-1.32.47-2.4 1.22-3.24-.12-.3-.53-1.52.12-3.16 0 0 1-.32 3.3 1.24a11.6 11.6 0 0 1 3-.4c1 .01 2 .14 3 .4 2.3-1.56 3.3-1.24 3.3-1.24.65 1.64.24 2.86.12 3.16.76.84 1.22 1.92 1.22 3.24 0 4.64-2.8 5.66-5.48 5.96.44.38.84 1.14.84 2.3v3.4c0 .32.22.7.82.58C20.56 21.8 24 17.3 24 12c0-6.63-5.37-12-12-12Z">
                                </path>
                            </svg>
                        </a>

                        <!-- LinkedIn -->
                        <a href="https://www.linkedin.com/in/risky-aerossky/" target="_blank"
                            class="bg-blue-700 text-white p-2 rounded-full hover:bg-blue-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5">
                                <path fill="currentColor"
                                    d="M22.23 0H1.77C.79 0 0 .78 0 1.75V22.2c0 .97.79 1.75 1.77 1.75h20.46c.98 0 1.77-.78 1.77-1.75V1.75C24 .78 23.21 0 22.23 0ZM7.12 20.45H3.56V8.91h3.56v11.54ZM5.34 7.27c-1.14 0-2.07-.92-2.07-2.07s.92-2.07 2.07-2.07 2.07.92 2.07 2.07-.92 2.07-2.07 2.07ZM20.45 20.45h-3.56v-5.6c0-1.34-.02-3.07-1.87-3.07-1.87 0-2.16 1.46-2.16 2.96v5.71h-3.56V8.91h3.41v1.58h.05c.47-.89 1.62-1.83 3.34-1.83 3.57 0 4.23 2.35 4.23 5.41v6.37Z">
                                </path>
                            </svg>
                        </a>

                        <!-- Instagram -->
                        <a href="https://instagram.com/risky_goh" target="_blank"
                            class="bg-gradient-to-r from-purple-500 via-pink-500 to-orange-500 text-white p-2 rounded-full hover:opacity-90 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-5 h-5">
                                <path fill="currentColor"
                                    d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Testimoni -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-center mb-10">Apa Kata <span class="text-primary">Mereka</span></h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400">
                            <svg class="w-5 h-5 inline-block" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <svg class="w-5 h-5 inline-block" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <svg class="w-5 h-5 inline-block" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <svg class="w-5 h-5 inline-block" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <svg class="w-5 h-5 inline-block" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-700 italic mb-4">"Website ini sangat membantu saya merencanakan perjalanan ke Danau
                        Toba. Informasi prakiraan cuaca yang akurat membuat perjalanan saya lancar tanpa kendala hujan!"</p>
                    <div class="flex items-center">
                        <img src="{{ asset('images/testimonial1.jpg') }}" alt="Testimoni 1"
                            class="w-10 h-10 rounded-full mr-3">
                        <div>
                            <h4 class="font-semibold">Budi Santoso</h4>
                            <p class="text-sm text-gray-500">Traveler dari Jakarta</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400">
                            <svg class="w-5 h-5 inline-block" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <svg class="w-5 h-5 inline-block" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <svg class="w-5 h-5 inline-block" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.
                                                            <svg class="w-5 h-5 inline-block" fill="currentColor" viewBox="0 0 20 20">
                                                                                            <path d=" M9.049 2.927c.3-.921
                                    1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8
                                    2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0
                                    00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0
                                    00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <svg class="w-5 h-5 inline-block" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <svg class="w-5 h-5 inline-block" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-700 italic mb-4">"Informasi di situs ini sangat detail dan membantu saya memutuskan
                        kapan waktu terbaik mengunjungi Raja Ampat. Sangat direkomendasikan!"</p>
                    <div class="flex items-center">
                        <img src="{{ asset('images/testimonial2.jpg') }}" alt="Testimoni 2"
                            class="w-10 h-10 rounded-full mr-3">
                        <div>
                            <h4 class="font-semibold">Siti Rahma</h4>
                            <p class="text-sm text-gray-500">Travel Blogger</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400">
                            <svg class="w-5 h-5 inline-block" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <svg class="w-5 h-5 inline-block" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <svg class="w-5 h-5 inline-block" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <svg class="w-5 h-5 inline-block" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <svg class="w-5 h-5 inline-block" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-700 italic mb-4">"Sebagai tour guide, saya selalu merekomendasikan website ini
                        kepada klien saya untuk mendapatkan informasi terbaik sebelum berkunjung ke berbagai destinasi di
                        Indonesia."</p>
                    <div class="flex items-center">
                        <img src="{{ asset('images/testimonial3.jpg') }}" alt="Testimoni 3"
                            class="w-10 h-10 rounded-full mr-3">
                        <div>
                            <h4 class="font-semibold">Andi Wijaya</h4>
                            <p class="text-sm text-gray-500">Tour Guide</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="bg-gradient-to-r from-primary to-blue-600 rounded-xl p-8 text-white text-center">
            <h2 class="text-3xl font-bold mb-4">Mulai Petualangan Anda Sekarang</h2>
            <p class="mb-6 max-w-2xl mx-auto">Temukan destinasi impian Anda berikutnya dengan informasi lengkap dan
                prakiraan cuaca yang akurat untuk pengalaman perjalanan terbaik.</p>
            <a href="{{ route('destinations.index') }}"
                class="inline-block bg-white text-primary font-bold px-6 py-3 rounded-lg hover:bg-gray-100 transition">Jelajahi
                Destinasi</a>
        </div>
    </div>
@endsection
