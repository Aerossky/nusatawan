@extends('layouts.user')
@section('title', 'Detail Destinasi')

@push('styles')
    @vite(['resources/css/custom/preview.css'])
    <style>
        .weather-card {
            transition: all 0.3s ease;
        }

        .weather-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .weather-icon {
            width: 64px;
            height: 64px;
        }
    </style>
@endpush

@section('content')
    {{-- Header Gambar dan Informasi --}}
    <div class="container mx-auto max-w-full">
        <!-- Gambar Header dengan Overlay - Full Width -->
        <div class="relative mb-4 w-full">
            <img src="{{ asset($destination->primaryImage ? 'storage/' . $destination->primaryImage->url : 'images/auth.png') }}"
                alt="{{ $destination->place_name }}" class="w-full h-[300px] md:h-[500px] lg:h-[600px] max-h-96 object-cover">

            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
            <div class="absolute bottom-0 left-0 w-full p-6 text-white">
                <div class="container mx-auto px-0 md:px-6 md:max-w-4xl">
                    <!-- Lokasi dan tanggal -->
                    <div class="flex items-center text-sm mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $destination->administrative_area . ', ' . $destination->province }} -
                        {{ $destination->created_at->format('d M Y') }}
                    </div>

                    <!-- Judul dan tombol like -->
                    <div class="flex items-center justify-between">
                        <h1 class="text-3xl font-bold">{{ $destination->place_name }}</h1>

                        <!-- Like Button yang diperbaiki posisinya -->
                        <button
                            class="like-button flex items-center justify-center px-3 py-1 rounded-full bg-black/20 hover:bg-black/40 transition-colors"
                            data-destination-id="{{ $destination->id }}"
                            data-is-liked="{{ $destination->is_liked_by_user ? 'true' : 'false' }}"
                            data-likes-count="{{ $destination->likes_count }}"
                            data-like-url="{{ route('user.destinations.like', $destination) }}">
                            <svg class="{{ $destination->is_liked_by_user ? 'text-red-500 fill-red-500' : 'text-white' }} h-5 w-5 transition-colors like-icon"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.5" fill="{{ $destination->is_liked_by_user ? 'currentColor' : 'none' }}">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                            <span
                                class="text-sm font-medium ml-1 text-white likes-count">{{ $destination->likes_count }}</span>
                        </button>
                    </div>

                    <!-- Rating dan review -->
                    <div class="flex items-center mt-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="ml-1 text-white">{{ number_format($destinationRating, 1) }}</span>
                        <span class="mx-2 text-white">•</span>
                        <a href="#comments" class="text-blue-300">{{ $totalReview }} Review</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="max-w-4xl mx-auto px-4">
            <!-- Informasi Penulis (Baru) -->
            <div class="bg-white rounded-lg shadow-md p-5 mb-6">
                <div class="flex flex-col sm:flex-row justify-between items-start w-full">
                    {{-- Kiri: foto + info --}}
                    <div class="flex items-start">
                        {{-- foto penulis --}}
                        <div class="flex-shrink-0 mr-4 mb-3 sm:mb-0">
                            <img src="{{ asset('images/auth.png') }}" alt="Author"
                                class="w-12 h-12 rounded-full object-cover border-2 border-blue-500">
                        </div>
                        {{-- informasi penulis --}}
                        <div>
                            <div class="flex flex-wrap items-center">
                                <h3 class="font-semibold text-gray-800">Ditulis oleh: {{ $destination->user['name'] }}</h3>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">
                                Dipublikasikan: {{ $destination->created_at->format('d M Y') }}
                                @if ($destination->created_at->format('d M Y') != $destination->updated_at->format('d M Y'))
                                    • Diubah: {{ $destination->updated_at->format('d M Y') }}
                                @endif
                            </p>
                            <div class="flex items-center mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <button class="text-sm text-blue-500 hover:underline">Lihat Foto Destinasi Lainnya</button>
                            </div>
                        </div>
                    </div>

                    {{-- Kanan: tombol share --}}
                    <div class="mt-4 sm:mt-0">
                        <button
                            class="share-button ml-2 flex items-center justify-center h-9 w-9 rounded-md border border-gray-200 hover:bg-gray-50 transition-colors"
                            data-title="{{ $destination->place_name }}"
                            data-url="{{ route('user.destinations.show', $destination) }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Informasi Destinasi -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="text-gray-700 mb-4" id="content-preview">
                    {!! $destination->description !!}
                </div>
                <div class="text-sm text-gray-600 mb-2">
                    <span class="font-medium">Waktu Terbaik Untuk Kunjungan:</span> {{ $destination->best_visit_time }}
                </div>
                <div class="text-sm text-gray-600">
                    <span class="font-medium">Durasi Kunjungan:</span> {{ $destination->time_minutes }} menit
                </div>
            </div>

            <!-- Cuaca dengan Tab Pilihan (Hari Ini/Prakiraan) -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path d="M5.5 16a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 16h-8z" />
                    </svg>
                    Informasi Cuaca
                </h2>

                <!-- Weather Tab System -->
                <div x-data="{ activeTab: 'today' }">
                    <!-- Tab Headers -->
                    <div class="flex border-b mb-4">
                        <button @click="activeTab = 'today'"
                            :class="{ 'border-b-2 border-blue-500 text-blue-600': activeTab === 'today' }"
                            class="py-2 px-4 font-medium text-sm focus:outline-none">
                            Cuaca Hari Ini
                        </button>
                        <button @click="activeTab = 'forecast'"
                            :class="{ 'border-b-2 border-blue-500 text-blue-600': activeTab === 'forecast' }"
                            class="py-2 px-4 font-medium text-sm focus:outline-none">
                            Prakiraan 5 Hari
                        </button>
                    </div>

                    <!-- Today's Weather Tab -->
                    <div x-show="activeTab === 'today'">
                        @if ($currentWeather)
                            <!-- Current Weather Summary -->
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 mb-4 text-white">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <div class="text-sm">{{ $destination->administrative_area }}</div>
                                        <div class="text-2xl font-bold">{{ round($currentWeather['temp']) }}°C
                                        </div>
                                        <div class="text-sm">Terasa seperti
                                            {{ round($currentWeather['feels_like']) }}°C</div>
                                        <div class="mt-1 text-sm">
                                            <span class="capitalize"></span>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <img src="{{ $currentWeather['icon_url'] }}" alt="Weather Icon"
                                            class="weather-icon inline-block">
                                    </div>
                                </div>
                                <div class="flex justify-between mt-3 text-sm">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                        </svg>
                                        {{ $currentWeather['humidity'] }}% kelembaban
                                    </div>
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                        {{ $currentWeather['wind_speed'] }} m/s
                                    </div>
                                </div>
                            </div>

                            <!-- Today's Time Slots -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                @if ($todayForecast)
                                    @foreach (['morning', 'afternoon', 'evening'] as $slot)
                                        @if (isset($todayForecast[$slot]))
                                            <div class="weather-card bg-gray-50 rounded-lg p-3 text-center">
                                                <div class="font-medium text-gray-700">
                                                    {{ $todayForecast[$slot]['time'] }}</div>
                                                <img src="{{ $todayForecast[$slot]['icon_url'] }}" alt="Weather Icon"
                                                    class="weather-icon mx-auto my-1">
                                                <div class="text-xl font-semibold text-gray-800">
                                                    {{ $todayForecast[$slot]['temp'] }}°C</div>
                                                <div class="text-sm text-gray-600 capitalize">
                                                    {{ $todayForecast[$slot]['description'] }}</div>
                                            </div>
                                        @else
                                            <div class="weather-card bg-gray-50 rounded-lg p-3 text-center">
                                                <div class="font-medium text-gray-700">
                                                    @if ($slot == 'morning')
                                                        Pagi
                                                    @elseif($slot == 'afternoon')
                                                        Siang
                                                    @else
                                                        Malam
                                                    @endif
                                                </div>
                                                <div class="h-16 flex items-center justify-center">
                                                    <span class="text-gray-400">Data tidak tersedia</span>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="col-span-3 text-center py-4 text-gray-500">
                                        Data prakiraan tidak tersedia saat ini
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-4 text-gray-500">
                                Data cuaca tidak tersedia saat ini
                            </div>
                        @endif
                    </div>

                    <!-- 5-Day Forecast Tab -->
                    <div x-show="activeTab === 'forecast'" class="py-4">
                        @if ($weekForecast)
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                                @foreach ($weekForecast as $index => $day)
                                    <div class="relative group">
                                        <!-- Main Weather Card -->
                                        <div
                                            class="bg-white rounded-xl p-4 text-center shadow-sm hover:shadow-md transition-all duration-300 cursor-pointer border border-gray-100 transform hover:-translate-y-1">
                                            <div class="font-medium text-gray-800 text-lg">{{ $day['day'] }}</div>
                                            <div class="text-sm text-gray-500 mb-2">{{ $day['date'] }}</div>
                                            <img src="{{ $day['icon_url'] }}" alt="Weather Icon"
                                                class="mx-auto h-16 w-16 my-2">
                                            <div class="text-2xl font-semibold text-gray-800 mt-1">
                                                {{ $day['avg_temp'] }}°C</div>
                                            <div class="text-sm text-gray-600 capitalize mt-1">{{ $day['main_weather'] }}
                                            </div>
                                        </div>

                                        <!-- Hover Detail Card -->
                                        <div
                                            class="absolute left-0 right-0 top-full mt-2 z-10 bg-white rounded-xl shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top scale-95 group-hover:scale-100 w-full">
                                            <div class="p-4">
                                                <h3 class="font-medium text-gray-800 text-center border-b pb-2 mb-3">
                                                    {{ $day['day'] }}, {{ $day['date'] }}</h3>

                                                @foreach (['morning', 'afternoon', 'evening'] as $timeSlot)
                                                    @if ($day['time_details'][$timeSlot])
                                                        <div
                                                            class="py-2 {{ !$loop->first ? 'border-t border-gray-100' : '' }}">
                                                            <div class="flex items-center justify-between">
                                                                <span
                                                                    class="font-medium text-gray-700">{{ $day['time_details'][$timeSlot]['time'] }}</span>
                                                                <span
                                                                    class="text-gray-900 font-semibold">{{ $day['time_details'][$timeSlot]['temp'] }}°C</span>
                                                            </div>

                                                            <div class="flex items-center mt-1">
                                                                <img src="{{ $day['time_details'][$timeSlot]['icon_url'] }}"
                                                                    alt="Weather Icon" class="h-8 w-8 mr-2">
                                                                <span
                                                                    class="text-sm text-gray-600 capitalize">{{ $day['time_details'][$timeSlot]['description'] }}</span>
                                                            </div>

                                                            <div class="grid grid-cols-2 gap-2 mt-2 text-xs text-gray-500">
                                                                <div class="flex items-center">
                                                                    <svg class="h-4 w-4 mr-1"
                                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M8 7l4-4m0 0l4 4m-4-4v18" />
                                                                    </svg>
                                                                    <span>Terasa:
                                                                        {{ $day['time_details'][$timeSlot]['feels_like'] }}°C</span>
                                                                </div>
                                                                <div class="flex items-center">
                                                                    <svg class="h-4 w-4 mr-1"
                                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                                                    </svg>
                                                                    <span>Kelembaban:
                                                                        {{ $day['time_details'][$timeSlot]['humidity'] }}%</span>
                                                                </div>
                                                                <div class="flex items-center col-span-2 mt-1">
                                                                    <svg class="h-4 w-4 mr-1"
                                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                    </svg>
                                                                    <span>Angin:
                                                                        {{ $day['time_details'][$timeSlot]['wind_speed'] }}
                                                                        m/s</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500 bg-gray-50 rounded-lg">
                                <svg class="h-12 w-12 mx-auto text-gray-400 mb-3" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                                </svg>
                                <p class="font-medium">Data prakiraan tidak tersedia saat ini</p>
                                <p class="text-sm mt-1">Silakan coba lagi nanti</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Weather Notice -->
                <div class="mt-4 text-xs text-gray-500 text-center">
                    Data cuaca diperbarui secara berkala. Terakhir diperbarui: {{ now()->format('d M Y H:i') }}
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
            <div id="review-form" class="mb-8 bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-5 flex items-center text-gray-800 border-b pb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ $userReview ? 'Edit Ulasan & Rating' : 'Beri Ulasan & Rating' }}
                </h2>

                {{-- Pesan --}}
                <div id="message-container" class="mb-4"></div>

                <form action="{{ route('user.reviews.store', $destination) }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <!-- Rating Section with Card Design -->
                        <div class="bg-gray-50 rounded-lg p-5 border border-gray-100">
                            <label class="block text-gray-700 mb-3 font-medium flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-500"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                Bagaimana Kualitas Konten? <span class="text-red-500 ml-1">*</span>
                            </label>

                            <div x-data="{
                                rating: {{ $userReview ? $userReview->rating : 0 }},
                                hoverRating: 0,
                                ratings: [
                                    { value: 1, label: 'Buruk', color: 'text-red-500' },
                                    { value: 2, label: 'Kurang', color: 'text-orange-500' },
                                    { value: 3, label: 'Cukup', color: 'text-yellow-500' },
                                    { value: 4, label: 'Bagus', color: 'text-lime-500' },
                                    { value: 5, label: 'Sangat Bagus', color: 'text-green-500' }
                                ],
                                rate(val) {
                                    this.rating = val;
                                },
                                currentLabel() {
                                    let label = '';
                                    let value = this.hoverRating || this.rating;
                            
                                    if (value > 0) {
                                        label = this.ratings.find(r => r.value === value)?.label || '';
                                    }
                            
                                    return value ? `${value}.0 - ${label}` : 'Pilih rating';
                                },
                                getColor() {
                                    let value = this.hoverRating || this.rating;
                                    if (value > 0) {
                                        return this.ratings.find(r => r.value === value)?.color || 'text-gray-500';
                                    }
                                    return 'text-gray-500';
                                }
                            }">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <!-- Stars Selection -->
                                    <div class="flex items-center">
                                        <template x-for="(star, index) in 5" :key="index">
                                            <button type="button" @click="rate(star)" @mouseover="hoverRating = star"
                                                @mouseleave="hoverRating = 0"
                                                class="focus:outline-none p-1 transition-transform duration-200"
                                                :class="{ 'scale-110': hoverRating === star || rating === star }">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-9 w-9 transition-colors duration-200"
                                                    :class="(hoverRating >= star) ? 'text-yellow-400' : (rating >= star ?
                                                        'text-yellow-400' : 'text-gray-300')"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            </button>
                                        </template>
                                    </div>

                                    <!-- Rating Label -->
                                    <div class="flex items-center justify-between sm:justify-end gap-3">
                                        <span
                                            class="font-medium text-sm px-3 py-1 rounded-full transition-colors duration-200"
                                            :class="getColor()">
                                            <span x-text="currentLabel()"></span>
                                        </span>
                                        <input type="hidden" name="rating" x-model="rating">
                                    </div>
                                </div>

                                <!-- Rating Selection Helper Text -->
                                <div class="mt-3 text-xs text-gray-500" x-show="rating === 0">
                                    <p>Klik bintang untuk memberikan rating</p>
                                </div>

                                <!-- Selected Rating Confirmation -->
                                <div class="mt-3 text-xs flex items-center" x-show="rating > 0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500 mr-1"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Rating Anda: <span class="font-semibold" x-text="rating + '.0'"></span> - Terima
                                        kasih atas penilaian Anda!</span>
                                </div>
                            </div>
                        </div>

                        <!-- Comment Section -->
                        <div>
                            <label for="comment" class="block text-gray-700 mb-2 font-medium flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                                Bagikan pengalaman Anda: <span class="text-red-500 ml-1">*</span>
                            </label>
                            <textarea id="comment" name="comment" rows="4"
                                class="w-full border border-gray-300 rounded-lg p-4 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition duration-200"
                                placeholder="Ceritakan pengalaman kunjungan Anda ke destinasi ini...">{{ $userReview ? $userReview->comment : '' }}</textarea>
                            <p class="mt-2 text-xs text-gray-500">Ulasan Anda akan membantu wisatawan lain merencanakan
                                perjalanan mereka.</p>
                        </div>

                        <!-- Submit Section -->
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
                            <button type="submit"
                                class="w-full sm:w-auto bg-blue-500 hover:bg-blue-600 text-white py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                {{ $userReview ? 'Ubah Ulasan & Rating' : 'Kirim Ulasan & Rating' }}
                            </button>

                            <div class="flex items-center text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $userReview ? 'Ulasan Anda terakhir diperbarui: ' . $userReview->updated_at->format('d M Y') : 'Ulasan akan ditampilkan setelah moderasi' }}
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Komentar yang ada -->
            <div id="comments">
                {{-- Ketika belum ada komentar --}}
                @if ($reviews->count() == 0)
                    <div class="text-center py-4 text-gray-500">
                        Belum ada komentar untuk destinasi ini.
                    </div>
                @endif

                @foreach ($reviews as $review)
                    <div class="bg-white rounded-lg shadow-md p-4 mb-4">
                        <div class="flex items-start mb-3">
                            <div class="flex-shrink-0 mr-3">
                                <div class="w-10 h-10 bg-gray-300 rounded-full">
                                    {{-- foto pengguna --}}
                                    <img src="{{ asset('storage/' . $review->user->image) }}" alt="User"
                                        class="w-full h-full rounded-full object-cover" />
                                </div>
                            </div>
                            <div>
                                <h4 class="font-medium">{{ $review->user->name }}</h4>
                                <p class="text-sm text-gray-500">{{ $review->created_at }}</p>
                            </div>
                        </div>
                        <p class="text-gray-700">{{ $review->comment }}</p>
                    </div>
                @endforeach

                {{-- Pagination --}}
                <div class="flex justify-center mt-4">
                    {{ $reviews->links() }}
                </div>

            </div>
        </div>
    </div>

    <div class="my-5"></div>

@endsection

@push('scripts')
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite(['resources/js/share.js', 'resources/js/like.js']);
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('weatherTooltips', () => ({
                adjustTooltipPosition() {
                    const tooltips = document.querySelectorAll('.group');
                    tooltips.forEach(tooltip => {
                        const card = tooltip.querySelector('.absolute');
                        const rect = tooltip.getBoundingClientRect();
                        const isNearLeftEdge = rect.left < 160;
                        const isNearRightEdge = window.innerWidth - rect.right <
                            160; // Fixed this line

                        if (isNearLeftEdge) {
                            card.classList.add('left-0', 'right-auto');
                            card.classList.remove('right-0', 'left-auto');
                        } else if (isNearRightEdge) {
                            card.classList.add('right-0', 'left-auto');
                            card.classList.remove('left-0', 'right-auto');
                        }
                    });
                },
                init() {
                    this.adjustTooltipPosition();
                    window.addEventListener('resize', this.adjustTooltipPosition);
                }
            }));
        });

        document.addEventListener('DOMContentLoaded', function() {
            $('#review-form form').on('submit', function(e) {
                e.preventDefault();

                // Get the rating and comment values
                const rating = $('input[name="rating"]').val();
                const comment = $('#comment').val().trim();

                // Validate form fields
                if (!rating || rating === '0') {
                    showFormError('Harap pilih rating terlebih dahulu',
                        'Rating dan komentar harus diisi untuk mengirim ulasan.');
                    return false;
                }

                if (!comment) {
                    showFormError('Harap isi komentar terlebih dahulu',
                        'Rating dan komentar harus diisi untuk mengirim ulasan.');
                    return false;
                }

                // If validation passes, proceed with AJAX submission
                $.ajax({
                    type: "POST",
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        // Display success message
                        showFormSuccess('Review berhasil dikirim');

                        // Refresh page after 2 seconds
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = "Terjadi kesalahan yang tidak diketahui";

                        if (xhr.responseText) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                errorMessage = response.message || xhr.responseText;
                            } catch (e) {
                                errorMessage = xhr.responseText;
                            }
                        }

                        showFormError('Terjadi kesalahan', errorMessage);
                    }
                });
            });

            // Helper functions for displaying messages
            function showFormError(title, message) {
                $('#message-container').html(
                    `<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p class="font-medium">${title}</p>
                <p>${message}</p>
            </div>`
                );
            }

            function showFormSuccess(message) {
                $('#message-container').html(
                    `<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                ${message}
            </div>`
                );
            }
        });
    </script>
@endpush
