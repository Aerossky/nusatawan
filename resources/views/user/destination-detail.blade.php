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
                        {{ $destination->administrative_area . ', ' . $destination->province }} -
                        {{ $destination->created_at->format('d M Y') }}
                    </div>
                    <h1 class="text-3xl font-bold">{{ $destination->place_name }}</h1>
                    <div class="flex items-center mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="ml-1 text-white">4.5</span> // TODO: Replace with appropriate user ID
                        <span class="mx-2 text-white">•</span>
                        <button class="text-blue-300">16 Review</button> //TODO : Ambil review dari database

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
                            <h3 class="font-semibold text-gray-800">Ditulis oleh: {{ $destination->user['name'] }}</h3>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">
                            Dipublikasikan: {{ $destination->created_at->format('d M Y') }}
                            {{-- jika terdapat perubahan --}}
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

@endsection

@push('scripts')
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('weatherTooltips', () => ({
                adjustTooltipPosition() {
                    const tooltips = document.querySelectorAll('.group');
                    tooltips.forEach(tooltip => {
                        const card = tooltip.querySelector('.absolute');
                        const rect = tooltip.getBoundingClientRect();
                        const isNearLeftEdge = rect.left < 160;
                        const isNearRightEdge = window.innerWidth - rect.right < script 160;

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
    </script>
@endpush
