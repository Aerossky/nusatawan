<!-- resources/views/components/destination-card.blade.php -->
@props(['data'])

<div class="bg-white rounded-lg overflow-hidden transition-all duration-300 hover:shadow-md">
    <!-- Card Image -->
    <div class="relative h-52 overflow-hidden">
        @if ($data->images->count() > 0)
            <img src="{{ asset($data->primaryImage ? 'storage/' . $data->primaryImage->url : 'images/auth.png') }}"
                alt="{{ $data->place_name }}" class="w-full h-full object-cover">
        @else
            {{-- <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div> --}}
            <img src="{{ asset('images/categories/' . $data->category->name . '.jpg') }}"
                alt="{{ $data->category->name }}" class="w-full h-full object-cover">
        @endif

        <!-- Category Badge -->
        <div class="absolute top-3 left-3">
            <span class="bg-white bg-opacity-90 text-blue-600 text-xs font-medium px-2.5 py-1 rounded-full">
                {{ $data->category->name }}
            </span>
        </div>

        <!-- Rating Badge -->
        <div class="absolute bottom-3 right-3">
            <div
                class="bg-white bg-opacity-90 text-amber-500 text-xs font-medium px-2.5 py-1 rounded-full flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1 text-amber-500 fill-amber-500"
                    viewBox="0 0 20 20">
                    <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span>{{ number_format($data->rating ?? 0, 1) }}</span>
            </div>
        </div>
    </div>

    <!-- Card Content -->
    <div class="p-4">
        <h3 class="font-semibold text-lg text-gray-800 mb-1 truncate">{{ $data->place_name }}</h3>
        <p class="text-sm text-gray-500 mb-3 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            {{ $data->administrative_area . ', ' . $data->province }}
        </p>

        <!-- Rating Stars -->
        <div class="flex items-center mb-3">
            @for ($i = 1; $i <= 5; $i++)
                @if ($i <= ($data->rating_avg ?? 0))
                    <svg class="w-4 h-4 text-amber-500 fill-amber-500" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                @elseif ($i <= ($data->rating_avg ?? 0) + 0.5)
                    <svg class="w-4 h-4 text-amber-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <defs>
                            <linearGradient id="half-fill-{{ $data->id }}-{{ $i }}" x1="0%"
                                y1="0%" x2="100%" y2="0%">
                                <stop offset="50%" stop-color="#F59E0B" />
                                <stop offset="50%" stop-color="#D1D5DB" />
                            </linearGradient>
                        </defs>
                        <path fill="url(#half-fill-{{ $data->id }}-{{ $i }})"
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                @else
                    <svg class="w-4 h-4 text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                @endif
            @endfor

            @if (isset($data->reviews_count) && $data->reviews_count > 0)
                <span class="ml-1 text-xs text-gray-500">({{ $data->reviews_count }})</span>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between mt-3">
            <a href="{{ route('user.destinations.show', $data->slug) }}"
                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 rounded-md transition-colors text-sm font-medium mr-2">
                Lihat Detail
            </a>

            {{-- Like Button --}}
            @auth
                <div class="flex items-center">
                    <button
                        class="like-button flex items-center justify-center h-9 px-3 rounded-md border border-gray-200 hover:bg-gray-50 transition-colors"
                        data-destination-id="{{ $data->id }}"
                        data-is-liked="{{ $data->is_liked_by_user ? 'true' : 'false' }}"
                        data-likes-count="{{ $data->likes_count }}"
                        data-like-url="{{ route('user.destinations.like', $data) }}">
                        <svg class="{{ $data->is_liked_by_user ? 'text-red-500 fill-red-500' : 'text-gray-400' }} h-5 w-5 transition-colors like-icon"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                            fill="{{ $data->is_liked_by_user ? 'currentColor' : 'none' }}">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>

                        @if ($data->likes_count > 0)
                            <span
                                class="text-xs font-medium ml-1 text-gray-500 likes-count">{{ $data->likes_count }}</span>
                        @endif
                    </button>
                </div>
            @else
                {{-- Tombol Like untuk pengguna yang belum login (disabled) --}}
                <div class="flex items-center">
                    <button
                        class="flex items-center justify-center h-9 px-3 rounded-md border border-gray-200 bg-gray-100 cursor-not-allowed"
                        onclick="showLoginAlert()" title="Silakan login untuk menyukai">
                        <svg class="text-gray-400 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.5" fill="none">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>

                        @if ($data->likes_count > 0)
                            <span class="text-xs font-medium ml-1 text-gray-500">{{ $data->likes_count }}</span>
                        @endif
                    </button>
                </div>
            @endauth
            <!-- Share Button-->
            <button
                class="share-button ml-2 flex items-center justify-center h-9 w-9 rounded-md border border-gray-200 hover:bg-gray-50 transition-colors"
                data-title="{{ $data->place_name }}" data-url="{{ route('user.destinations.show', $data) }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                </svg>
            </button>
        </div>
    </div>
</div>

@once
    @push('scripts')
        @vite(['resources/js/share.js', 'resources/js/like.js'])
    @endpush
@endonce
