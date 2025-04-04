@extends('layouts.user')
@section('title', 'Tentang Kami')
@section('content')
    <div class="max-w-screen-lg mx-auto px-6 py-12 mt-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Misi <span class="text-primary">Kami</span></h2>
                <p class="mt-4 text-gray-700">
                    Kami percaya bahwa perencanaan perjalanan yang baik dapat meningkatkan pengalaman wisata Anda.
                    Oleh karena itu, kami menghadirkan informasi wisata lengkap dengan prakiraan cuaca agar Anda dapat
                    memilih waktu terbaik untuk menjelajahi destinasi impian Anda.
                </p>
            </div>
            <div>
                <img src="{{ asset('images/auth.png') }}" alt="Destinasi Wisata"
                    class="rounded-lg shadow-md grayscale w-full h-64 md:h-80 object-cover">
            </div>
        </div>

        <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Tentang <span class="text-primary">Pengembang</span></h2>
                <div class="my-5"></div>
                <img src="{{ asset('images/auth.png') }}" alt="Pengembang"
                    class="rounded-lg shadow-md grayscale w-full h-64 md:h-80 object-cover">
            </div>
            <div>
                <h3 class="mt-4 text-xl font-bold text-gray-900">Risky</h3>
                <p class="mt-2 text-gray-700">
                    Halo! Saya, pengembang di balik platform ini. Dengan latar belakang dalam pengembangan web,
                    saya berkomitmen untuk membangun solusi digital yang membantu wisatawan dalam merencanakan
                    perjalanan mereka dengan lebih baik. Saya berharap platform ini dapat menjadi sumber informasi
                    yang bermanfaat dan terus berkembang dengan kontribusi dari para pengguna.
                </p>
                <div class="mt-4 flex space-x-4">
                    <!-- GitHub -->
                    <a href="https://github.com/username" target="_blank" class="text-gray-600 hover:text-gray-900">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6">
                            <path fill="currentColor"
                                d="M12 0C5.37 0 0 5.37 0 12c0 5.3 3.44 9.8 8.2 11.4.6.1.82-.26.82-.58v-2.3c-3.34.72-4.04-1.6-4.04-1.6-.55-1.4-1.35-1.8-1.35-1.8-1.1-.75.08-.74.08-.74 1.2.08 1.84 1.2 1.84 1.2 1.1 1.9 2.92 1.34 3.64 1.02.1-.8.43-1.35.78-1.66-2.67-.3-5.47-1.34-5.47-5.96 0-1.32.47-2.4 1.22-3.24-.12-.3-.53-1.52.12-3.16 0 0 1-.32 3.3 1.24a11.6 11.6 0 0 1 3-.4c1 .01 2 .14 3 .4 2.3-1.56 3.3-1.24 3.3-1.24.65 1.64.24 2.86.12 3.16.76.84 1.22 1.92 1.22 3.24 0 4.64-2.8 5.66-5.48 5.96.44.38.84 1.14.84 2.3v3.4c0 .32.22.7.82.58C20.56 21.8 24 17.3 24 12c0-6.63-5.37-12-12-12Z">
                            </path>
                        </svg>
                    </a>

                    <!-- LinkedIn -->
                    <a href="https://linkedin.com/in/username" target="_blank" class="text-gray-600 hover:text-gray-900">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6">
                            <path fill="currentColor"
                                d="M22.23 0H1.77C.79 0 0 .78 0 1.75V22.2c0 .97.79 1.75 1.77 1.75h20.46c.98 0 1.77-.78 1.77-1.75V1.75C24 .78 23.21 0 22.23 0ZM7.12 20.45H3.56V8.91h3.56v11.54ZM5.34 7.27c-1.14 0-2.07-.92-2.07-2.07s.92-2.07 2.07-2.07 2.07.92 2.07 2.07-.92 2.07-2.07 2.07ZM20.45 20.45h-3.56v-5.6c0-1.34-.02-3.07-1.87-3.07-1.87 0-2.16 1.46-2.16 2.96v5.71h-3.56V8.91h3.41v1.58h.05c.47-.89 1.62-1.83 3.34-1.83 3.57 0 4.23 2.35 4.23 5.41v6.37Z">
                            </path>
                        </svg>
                    </a>

                    <!-- Instagram -->
                    <a href="https://instagram.com/username" target="_blank" class="text-gray-600 hover:text-gray-900">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-6 h-6">
                            <path fill="currentColor"
                                d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
