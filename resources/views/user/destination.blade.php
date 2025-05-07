@extends('layouts.user')
@section('title', 'Destinasi')

@section('content')
    {{-- Search Box Section --}}
    <div class="relative">
        {{-- Background Gambar --}}
        <div class="w-full h-48 sm:h-56 md:h-64 bg-cover bg-center"
            style="background-image: url('{{ asset('images/hero.png') }}');"></div>

        {{-- Filter - OPTIMIZED FOR TABLET --}}
        <div
            class="absolute left-1/2 transform -translate-x-1/2 -bottom-64 sm:-bottom-52 md:-bottom-24 lg:-bottom-16 bg-white shadow-lg rounded-lg p-4 sm:p-6 w-[90%] sm:w-11/12 md:w-3/4 lg:w-2/3">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2 sm:mb-4">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-2 sm:mb-0">Cari Destinasi</h2>
                <a href="{{ route('user.destinations.index') }}"
                    class="text-blue-600 hover:text-blue-800 flex items-center text-xs sm:text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4 mr-1" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                    Lihat Semua Destinasi
                </a>
            </div>

            <form id="destination-search-form" method="POST" action="{{ route('user.destinations.index') }}"
                class="flex flex-col gap-2 mt-2 sm:mt-0">
                @csrf {{-- CSRF token untuk keamanan --}}

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 sm:gap-3">
                    {{-- Input Pencarian dengan Autocomplete --}}
                    <div class="relative">
                        <label for="search-input"
                            class="text-xs sm:text-sm font-medium text-gray-700 mb-1 block">Destinasi</label>
                        <div class="relative">
                            <input type="text" id="search-input" name="q"
                                placeholder="Cari destinasi, kota, atau provinsi..."
                                class="w-full p-2 text-sm border border-gray-300 rounded-md focus:ring focus:ring-blue-300 focus:border-blue-300"
                                value="{{ request('q') }}" autocomplete="off">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <div id="search-results"
                                class="absolute left-0 right-0 mt-1 bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-y-auto z-50 hidden">
                            </div>
                        </div>
                    </div>

                    {{-- Dropdown Kategori --}}
                    <div>
                        <label for="category-select"
                            class="text-xs sm:text-sm font-medium text-gray-700 mb-1 block">Kategori</label>
                        <select id="category-select" name="category"
                            class="w-full p-2 text-sm border border-gray-300 rounded-md focus:ring focus:ring-blue-300 focus:border-blue-300 bg-white">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Dropdown Rating --}}
                    <div>
                        <label for="sort_by" class="text-xs sm:text-sm font-medium text-gray-700 mb-1 block">Urutkan
                            Berdasarkan</label>
                        <select id="sort_by" name="sort_by"
                            class="w-full p-2 text-sm border border-gray-300 rounded-md focus:ring focus:ring-blue-300 focus:border-blue-300 bg-white">
                            <option value="likes_desc" {{ request('sort_by') == 'likes_desc' ? 'selected' : '' }}>
                                Paling Banyak Disukai
                            </option>
                            <option value="newest" {{ request('sort_by', 'newest') == 'newest' ? 'selected' : '' }}>
                                Terbaru Ditambahkan
                            </option>
                            <option value="rating_desc" {{ request('sort_by') == 'rating_desc' ? 'selected' : '' }}>
                                Rating Tertinggi
                            </option>
                            <option value="rating_asc" {{ request('sort_by') == 'rating_asc' ? 'selected' : '' }}>
                                Rating Terendah
                            </option>
                        </select>
                    </div>
                </div>

                {{-- Hidden fields untuk koordinat dan nama lokasi --}}
                <input type="hidden" id="lat-input" name="lat" value="{{ request('lat') }}">
                <input type="hidden" id="lng-input" name="lng" value="{{ request('lng') }}">
                <input type="hidden" id="location-name" name="location_name" value="{{ request('location_name') }}">
                <input type="hidden" id="search-id" name="search_id"> {{-- ID pencarian yang unik --}}

                {{-- Tombol Cari --}}
                <div class="flex justify-end mt-2 sm:mt-3">
                    <button type="button" id="search-button"
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-all duration-300 font-medium text-sm">
                        Cari Destinasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Fixed spacing for different screen sizes --}}
    <div class="mt-[270px] sm:mt-[220px] md:mt-[140px] lg:mt-[100px]"></div>

    {{-- Card Section --}}
    <x-section>
        <div class="bg-gray-100 p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-4 sm:mb-6">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold">Jelajahi Destinasi Menarik</h2>
                    <p class="text-gray-600 text-sm">Menampilkan
                        {{ $destinations->firstItem() ?? '0' }}-{{ $destinations->lastItem() ?? '0' }} dari total
                        {{ $destinations->total() ?? '0' }} destinasi</p>
                </div>

                @if (request()->has('q') || request()->has('category') || request()->has('rating'))
                    <a href="{{ route('user.destinations.index') }}"
                        class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 py-2 px-4 rounded-md flex items-center text-sm transition-all duration-200 mt-3 sm:mt-0 w-max">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Hapus Filter
                    </a>
                @endif
            </div>

            <div class="mt-6 sm:mt-8"></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                {{-- Kondisi ketika tidak ada destinasi --}}
                @if ($destinations->isEmpty())
                    <div class="col-span-1 sm:col-span-2 lg:col-span-3">
                        <div class="bg-white rounded-lg shadow-sm p-6 sm:p-8 text-center">
                            <div class="flex justify-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 sm:h-16 w-12 sm:w-16 text-gray-300"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                </svg>
                            </div>
                            <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2">Tidak ada destinasi ditemukan</h3>
                            <p class="text-gray-600 mb-6">Tidak ada destinasi yang sesuai dengan filter pencarian Anda.</p>
                            <div class="flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                                <button id="reset-filter-btn"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md transition-all duration-300">
                                    Lihat Semua Destinasi
                                </button>
                                <button id="back-btn"
                                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2 rounded-md transition-all duration-300">
                                    Kembali
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Card Destinasi --}}
                    @foreach ($destinations as $data)
                        <x-destination-card :data="$data" />
                    @endforeach
                @endif
            </div>

            {{-- Pagination --}}
            <div class="flex justify-center mt-8">
                @if ($destinations->hasPages())
                    <div class="mt-6 sm:mt-8">
                        {{ $destinations->links() }}
                    </div>
                @endif
            </div>
        </div>
    </x-section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const searchInput = document.getElementById('search-input');
            const resultsContainer = document.getElementById('search-results');
            const categorySelect = document.getElementById('category-select');
            const sortBy = document.getElementById('sort_by');
            const searchButton = document.getElementById('search-button');
            const latInput = document.getElementById('lat-input');
            const lngInput = document.getElementById('lng-input');
            const locationNameInput = document.getElementById('location-name');
            const searchIdInput = document.getElementById('search-id');
            const searchForm = document.getElementById('destination-search-form');
            const resetFilterBtn = document.getElementById('reset-filter-btn');
            const backBtn = document.getElementById('back-btn');

            // Generate ID pencarian unik
            searchIdInput.value = generateSearchId();

            // Handle reset filter button if it exists
            if (resetFilterBtn) {
                resetFilterBtn.addEventListener('click', function() {
                    window.location.href = "{{ route('user.destinations.index') }}";
                });
            }

            // Handle back button if it exists
            if (backBtn) {
                backBtn.addEventListener('click', function() {
                    window.history.back();
                });
            }

            // Current search data
            let selectedLocation = null;

            // If we have saved location data, restore it
            const savedLocationName = "{{ request('location_name') }}";
            if (savedLocationName && latInput.value && lngInput.value) {
                searchInput.value = savedLocationName;
                selectedLocation = {
                    formatted_name: savedLocationName,
                    lat: latInput.value,
                    lon: lngInput.value
                };
            }

            // Setup autocomplete
            if (searchInput && resultsContainer) {
                setupAutocomplete(searchInput, resultsContainer);
            }

            // Setup search button
            if (searchButton) {
                searchButton.addEventListener('click', function() {
                    performSearch();
                });
            }

            // Search when pressing enter in search input
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        performSearch();
                    }
                });
            }

            /**
             * Membuat ID pencarian unik
             */
            function generateSearchId() {
                return 's' + Math.random().toString(36).substring(2, 10);
            }

            /**
             * Autocomplete Menggunakan Nominatim
             */
            function setupAutocomplete(searchInput, resultsContainer) {
                let timeout = null;

                searchInput.addEventListener('input', function() {
                    clearTimeout(timeout);

                    // Reset selected location when user types
                    if (selectedLocation && this.value !== selectedLocation.formatted_name) {
                        resetLocationData();
                    }

                    const query = this.value.trim();
                    if (query.length < 2) {
                        resultsContainer.classList.add('hidden');
                        return;
                    }

                    timeout = setTimeout(() => {
                        // Use Nominatim for Indonesia locations
                        fetch(
                                `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=id&limit=5&accept-language=id`
                            )
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`HTTP error! Status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                displayResults(data, resultsContainer);
                            })
                            .catch(error => {
                                console.error("Error pencarian:", error);
                            });
                    }, 300);
                });

                // Hide results when clicking outside
                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                        resultsContainer.classList.add('hidden');
                    }
                });
            }

            /**
             * Display hasil pencarian
             */
            function displayResults(results, container) {
                container.innerHTML = '';

                if (results.length === 0) {
                    const noResult = document.createElement('div');
                    noResult.className = 'p-3 text-gray-500 text-sm';
                    noResult.textContent = 'Tidak ada hasil yang ditemukan';
                    container.appendChild(noResult);
                    container.classList.remove('hidden');
                    return;
                }

                // Display results
                results.forEach(result => {
                    const item = document.createElement('div');
                    item.className =
                        'p-3 hover:bg-gray-100 cursor-pointer text-sm border-b border-gray-100 last:border-0';

                    // Format display name
                    const displayName = formatLocationName(result.display_name);
                    item.textContent = displayName;

                    item.addEventListener('click', function() {
                        // Store the selected location data
                        selectedLocation = {
                            ...result,
                            formatted_name: displayName
                        };

                        // Update UI and store data in hidden fields
                        searchInput.value = displayName;
                        container.classList.add('hidden');

                        // Store values in hidden inputs
                        latInput.value = parseFloat(result.lat);
                        lngInput.value = parseFloat(result.lon);
                        locationNameInput.value = displayName;
                    });

                    container.appendChild(item);
                });

                container.classList.remove('hidden');
            }

            /**
             * Reset location data when user changes the input
             */
            function resetLocationData() {
                selectedLocation = null;
                latInput.value = '';
                lngInput.value = '';
                locationNameInput.value = '';
            }

            /**
             * Format location name to be more readable
             */
            function formatLocationName(name) {
                // Simplified version - clean up and translate common terms
                const parts = name.split(', ');
                // Take only first 3 parts for simplicity
                const simplifiedName = parts.slice(0, 3).join(', ');

                // Translate common terms
                return simplifiedName
                    .replace('City', 'Kota')
                    .replace('Province', 'Provinsi')
                    .replace('Regency', 'Kabupaten')
                    .replace('District', 'Kecamatan')
                    .replace('Village', 'Desa');
            }

            /**
             * Perform search with all filters
             */
            function performSearch() {
                const query = searchInput.value.trim();

                // If query is empty but we have coordinates, we can still search
                if (query.length === 0 && !latInput.value && !lngInput.value &&
                    categorySelect.value === '' && sortBy.value === '') {
                    // Show error with light toast
                    showToast('Masukkan kata kunci pencarian atau pilih filter');
                    return;
                }

                // If we have a selected location, just submit the form
                if (selectedLocation) {
                    searchForm.submit();
                    return;
                }

                // If query but no coordinates, try to get coordinates first
                if (query.length > 0 && !latInput.value && !lngInput.value) {
                    // Show loading indicator
                    showToast('Mencari lokasi...', 'info');

                    // Try getting location data from Nominatim first
                    fetch(
                            `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=id&limit=1&accept-language=id`
                        )
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.length > 0) {
                                // Store location data
                                const location = data[0];
                                const displayName = formatLocationName(location.display_name);

                                latInput.value = parseFloat(location.lat);
                                lngInput.value = parseFloat(location.lon);
                                locationNameInput.value = displayName;
                            }
                            // Submit the form
                            searchForm.submit();
                        })
                        .catch(error => {
                            console.error("Error:", error);
                            // Submit anyway
                            searchForm.submit();
                        });
                } else {
                    // If we already have coordinates or no query, submit the form directly
                    searchForm.submit();
                }
            }

            /**
             * Show a toast message
             */
            function showToast(message, type = 'error') {
                // Remove existing toasts
                const existingToasts = document.querySelectorAll('.toast-message');
                existingToasts.forEach(toast => document.body.removeChild(toast));

                // Create toast element
                const toast = document.createElement('div');
                toast.className =
                    'fixed bottom-4 left-1/2 transform -translate-x-1/2 px-4 py-2 rounded-md shadow-lg z-50 transition-opacity duration-300 toast-message text-sm';

                // Set style based on type
                if (type === 'error') {
                    toast.classList.add('bg-red-600', 'text-white');
                } else if (type === 'info') {
                    toast.classList.add('bg-blue-600', 'text-white');
                } else if (type === 'success') {
                    toast.classList.add('bg-green-600', 'text-white');
                }

                toast.textContent = message;

                // Add to DOM
                document.body.appendChild(toast);

                // Remove after 3 seconds
                setTimeout(() => {
                    toast.classList.add('opacity-0');
                    setTimeout(() => {
                        if (document.body.contains(toast)) {
                            document.body.removeChild(toast);
                        }
                    }, 300);
                }, 3000);
            }
        });
    </script>
@endpush
