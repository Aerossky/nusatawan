@extends('layouts.user')
@section('title', 'Destinasi')

@section('content')
    {{-- Search Box Section --}}
    <div class="relative">
        {{-- Background Gambar --}}
        <div class="w-full h-64 bg-cover bg-center" style="background-image: url('{{ asset('images/hero.png') }}');"></div>

        {{-- Box Filter - posisi di antara gambar dan konten --}}
        <div
            class="absolute left-1/2 transform -translate-x-1/2 -bottom-10 bg-white shadow-lg rounded-lg p-4 w-11/12 md:w-3/4 lg:w-2/3">
            <div class="flex flex-wrap gap-2">
                {{-- Input Pencarian --}}
                <input type="text" placeholder="Cari destinasi..."
                    class="flex-grow min-w-[200px] p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-300">

                {{-- Dropdown Kategori --}}
                <select class="p-2 border border-gray-300 rounded-md">
                    <option value="">Pilih Kategori</option>
                    <option value="pantai">Pantai</option>
                    <option value="gunung">Gunung</option>
                    <option value="candi">Candi</option>
                </select>

                {{-- Dropdown Lokasi --}}
                <select class="p-2 border border-gray-300 rounded-md">
                    <option value="">Pilih Lokasi</option>
                    <option value="bali">Bali</option>
                    <option value="jawa">Jawa</option>
                    <option value="sumatera">Sumatera</option>
                </select>

                {{-- Dropdown Cuaca --}}
                <select class="p-2 border border-gray-300 rounded-md">
                    <option value="">Filter Cuaca</option>
                    <option value="cerah">Cerah</option>
                    <option value="berawan">Berawan</option>
                    <option value="hujan">Hujan</option>
                </select>

                {{-- Tombol Cari --}}
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Cari</button>
            </div>
        </div>
    </div>

    <div class="mt-12"></div>

    {{-- Card Section --}}
    <x-section>
        <div class="bg-gray-100 p-6">
            <h2 class="text-2xl font-bold">Jelajahi Destinasi Menarik</h2>
            <p class="text-gray-600 text-sm mb-4">Menampilkan 1-9 dari total 10 destinasi</p>

            <div class="mt-12"></div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Card Destinasi --}}
                @foreach ($destinations as $data)
                    <div class="bg-white rounded-lg overflow-hidden transition-all duration-300 hover:shadow-md">
                        <!-- Card Image -->
                        <div class="relative h-52 overflow-hidden">
                            @if ($data->images->count() > 0)
                                <img src="{{ asset('storage/' . $data->images->where('is_primary', true)->first()->path ?? $data->images->first()->path) }}"
                                    alt="{{ $data->place_name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif

                            <!-- Category Badge -->
                            <div class="absolute top-3 left-3">
                                <span
                                    class="bg-white bg-opacity-90 text-blue-600 text-xs font-medium px-2.5 py-1 rounded-full">
                                    {{ $data->category->name }}
                                </span>
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="p-4">
                            <h3 class="font-semibold text-lg text-gray-800 mb-1 truncate">{{ $data->place_name }}</h3>
                            <p class="text-sm text-gray-500 mb-3 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $data->administrative_area . ', ' . $data->province }}
                            </p>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between mt-3">
                                <a href="{{ route('user.destinations.show', $data->slug) }}"
                                    class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 rounded-md transition-colors text-sm font-medium mr-2">
                                    Lihat Detail
                                </a>

                                <!-- Like Button dengan Form (tanpa JS) -->
                                <form method="POST" action="" class="inline">
                                    @csrf
                                    @if ($data->is_liked_by_user)
                                        @method('DELETE')
                                    @endif
                                    <button type="submit"
                                        class="flex items-center justify-center h-9 px-3 rounded-md border border-gray-200 hover:bg-gray-50 transition-colors">
                                        <svg class="{{ $data->is_liked_by_user ? 'text-red-500 fill-red-500' : 'text-gray-400' }} h-5 w-5 transition-colors"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.5"
                                            fill="{{ $data->is_liked_by_user ? 'currentColor' : 'none' }}">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                        </svg>
                                        <span class="text-xs font-medium ml-1 text-gray-500">{{ $data->likes_count }}</span>
                                    </button>
                                </form>

                                <!-- Share Button dengan JavaScript -->
                                <button
                                    onclick="shareDestination('{{ $data->place_name }}', '{{ route('user.destinations.show', $data) }}')"
                                    class="ml-2 flex items-center justify-center h-9 w-9 rounded-md border border-gray-200 hover:bg-gray-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </x-section>


@endsection

@push('scripts')
    <script>
        function shareDestination(title, url) {
            // Cek apakah Web Share API tersedia
            if (navigator.share) {
                navigator.share({
                        title: title,
                        url: url
                    })
                    .catch(error => console.log('Error sharing:', error));
            } else {
                // Fallback untuk browser yang tidak mendukung Web Share API
                // Buat temporary input untuk menyalin URL
                const tempInput = document.createElement('input');
                document.body.appendChild(tempInput);
                tempInput.value = url;
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);

                // Beri feedback kepada user
                alert('Link berhasil disalin!');
            }
        }
    </script>
@endpush
