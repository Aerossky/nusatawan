@extends('layouts.user')
@section('title', $itinerary->title)
@section('content')
    <div class="mt-[70px]"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Back button -->
        <div class="mb-6">
            <a href="" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
                Kembali ke Daftar Rencana
            </a>
        </div>

        <!-- Header -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $itinerary->title }}</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($itinerary->startDate)->format('d M Y') }} -
                        {{ \Carbon\Carbon::parse($itinerary->endDate)->format('d M Y') }}
                    </p>
                </div>
                <span
                    class="px-2 py-1 text-xs font-semibold rounded-full {{ $itinerary->status == 'ongoing' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                    {{ $itinerary->status == 'ongoing' ? 'Sedang Berlangsung' : 'Selesai' }}
                </span>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <button id="printItinerary"
                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Cetak
                        </button>
                        <button id="shareItinerary"
                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z" />
                            </svg>
                            Bagikan
                        </button>
                    </div>
                    <div>
                        <a href=""
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Edit Rencana
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Destinasi -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Destinasi</h2>
            </div>

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <ul id="itinerary-list" class="divide-y divide-gray-200">
                    @php
                        // Urutkan destinasi berdasarkan tanggal dan waktu kunjungan
                        $sortedDestinations = $itinerary->itineraryDestinations->sortBy(function ($destination) {
                            // Jika visitDateTime tersedia, gunakan untuk pengurutan
                            if ($destination->visit_date_time) {
                                return $destination->visit_date_time;
                            }
                            // Jika tidak ada visitDateTime, letakkan di akhir
                            return '9999-12-31 23:59:59';
                        });

                        // Kelompokkan destinasi berdasarkan tanggal
                        $destinationsByDate = [];
                        foreach ($sortedDestinations as $destination) {
                            $dateKey = $destination->visit_date_time
                                ? \Carbon\Carbon::parse($destination->visit_date_time)->format('Y-m-d')
                                : 'no-date';

                            if (!isset($destinationsByDate[$dateKey])) {
                                $destinationsByDate[$dateKey] = [];
                            }

                            $destinationsByDate[$dateKey][] = $destination;
                        }

                        // Generate array of all dates between start and end date
                        $allDates = [];
                        $currentDate = \Carbon\Carbon::parse($itinerary->startDate);
                        $endDate = \Carbon\Carbon::parse($itinerary->endDate);

                        while ($currentDate <= $endDate) {
                            $dateKey = $currentDate->format('Y-m-d');
                            $allDates[$dateKey] = isset($destinationsByDate[$dateKey])
                                ? $destinationsByDate[$dateKey]
                                : [];
                            $currentDate->addDay();
                        }

                        $globalIndex = 1;
                    @endphp

                    @foreach ($allDates as $dateKey => $destinations)
                        <li class="p-3 bg-blue-50">
                            <div class="flex justify-between items-center">
                                {{-- Tanggal --}}
                                <div class="text-sm font-medium text-blue-800">
                                    {{ \Carbon\Carbon::parse($dateKey)->format('d M Y') }}
                                </div>
                                {{-- tombol tambah destinasi --}}
                                @if (count($destinations) > 0)
                                    <button data-modal-target="destinasi-modal" data-modal-toggle="destinasi-modal"
                                        data-date="{{ $dateKey }}"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 add-destination-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Tambah Destinasi
                                    </button>
                                @endif
                            </div>
                        </li>
                        @if (count($destinations) > 0)
                            @foreach ($destinations as $itineraryDestination)
                                <li class="p-4 hover:bg-gray-50">
                                    <div class="flex justify-between items-start">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center">
                                                <div
                                                    class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-800 font-semibold">
                                                    {{ $globalIndex++ }}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{-- Nama destinasi --}}
                                                        @isset($itineraryDestination->destination->place_name)
                                                            {{ $itineraryDestination->destination->place_name }}
                                                        @endisset
                                                    </div>
                                                    <div class="mt-1 text-xs text-gray-500">
                                                        {{-- lokasi Destinasi --}}
                                                        @isset($itineraryDestination->destination->administrative_area)
                                                            <span>{{ $itineraryDestination->destination->administrative_area }},
                                                                {{ $itineraryDestination->destination->province }}</span>

                                                            <span class="ml-2 px-2 py-0.5 rounded bg-blue-50 text-blue-700">
                                                                {{ \Carbon\Carbon::parse($itineraryDestination->visit_date_time)->format('H:i') }}
                                                            </span>
                                                        @endisset
                                                    </div>
                                                </div>
                                            </div>

                                            @if ($itineraryDestination->note)
                                                <div class="mt-2 text-sm text-gray-500 bg-gray-50 p-3 rounded">
                                                    <p>{{ $itineraryDestination->note }}</p>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="ml-4 flex items-center space-x-2">
                                            <button type="button"
                                                onclick="editDestination({{ $itineraryDestination->id }})"
                                                class="inline-flex items-center p-1.5 border border-gray-300 shadow-sm text-xs rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path
                                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                </svg>
                                            </button>
                                            <button type="button" data-destination-id="{{ $itineraryDestination->id }}"
                                                class="deleteDestination inline-flex items-center p-1.5 border border-gray-300 shadow-sm text-xs rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <li class="p-4 hover:bg-gray-50">
                                <div class="flex justify-center">
                                    <button data-modal-target="destinasi-modal" data-modal-toggle="destinasi-modal"
                                        data-date="{{ $dateKey }}"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 add-destination-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Tambah Destinasi
                                    </button>
                                </div>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>


    <!-- Modal untuk menambah destinasi (Revisi) -->
    <div id="destinasi-modal" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t sticky top-0 bg-white z-10">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Tambah Destinasi
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-hide="destinasi-modal">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="p-6 space-y-6 flex flex-col">
                    <form id="destinationForm">
                        <input type="hidden" id="id" name="id">
                        <input type="hidden" id="itinerary_id" name="itinerary_id" value="{{ $itinerary->id }}">
                        <input type="hidden" id="order_index" name="order_index">
                        <input type="hidden" id="created_at" name="created_at">
                        <input type="hidden" id="selected_date" name="selected_date">
                        <input type="hidden" id="destination_id" name="destination_id">
                        <input type="hidden" id="destination_name" name="destination_name">
                        <input type="hidden" id="destination_lat" name="destination_lat">
                        <input type="hidden" id="destination_lng" name="destination_lng">

                        <!-- Search and selection section -->
                        <div class="mb-4">
                            <label for="destination" class="block text-sm font-medium text-gray-700 mb-1">Pilih
                                Lokasi</label>
                            <div class="relative">
                                <input type="text" id="destination" name="destination"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    placeholder="Cari lokasi..." autocomplete="off">
                                <div id="search-indicator"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">
                                    <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </div>
                                <!-- Autocomplete results -->
                                <div id="destination-search-results"
                                    class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto hidden">
                                </div>
                            </div>
                        </div>

                        <!-- Destination results container with fixed height and scrolling -->
                        <div id="nearby-destinations"
                            class="mb-4 border rounded-md bg-gray-50 p-3 max-h-64 overflow-y-auto">
                            <div class="text-gray-500 text-sm italic">Pilih lokasi untuk melihat destinasi terdekat</div>
                        </div>

                        <!-- Selected destination info (will be shown after a destination is selected) -->
                        <div id="selected-destination-info"
                            class="mb-4 p-3 border border-blue-300 rounded-md bg-blue-50 hidden">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 id="selected-destination-name" class="font-medium text-blue-700"></h4>
                                    <p id="selected-destination-location" class="text-sm text-gray-600 mt-1"></p>
                                </div>
                                <button type="button" id="clear-destination" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Time and notes section -->
                        <div class="mb-4">
                            <label for="visit_time" class="block text-sm font-medium text-gray-700">Waktu
                                Kunjungan</label>
                            <input type="time" id="visit_time" name="visit_time"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>

                        <div class="mb-4">
                            <label for="note" class="block text-sm font-medium text-gray-700">Catatan</label>
                            <textarea id="note" name="note" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                placeholder="Tambahkan catatan..."></textarea>
                        </div>
                    </form>
                </div>

                <!-- Modal footer -->
                <div
                    class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b sticky bottom-0 bg-white z-10">
                    <button type="button" id="saveDestination"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan</button>
                    <button type="button"
                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10"
                        data-modal-hide="destinasi-modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/location-search.js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const destinationForm = document.getElementById('destinationForm');
            const saveButton = document.getElementById('saveDestination');
            const deleteButtons = document.querySelectorAll('.deleteDestination');
            const nearbyDestinationsContainer = document.getElementById('nearby-destinations');
            const selectedDestinationInfo = document.getElementById('selected-destination-info');
            const selectedDestinationName = document.getElementById('selected-destination-name');
            const selectedDestinationLocation = document.getElementById('selected-destination-location');
            const clearDestinationButton = document.getElementById('clear-destination');

            // Hidden fields
            const destinationIdInput = document.getElementById('destination_id');
            const destinationLatInput = document.getElementById('destination_lat');
            const destinationLngInput = document.getElementById('destination_lng');

            // Variables
            let selectedDestinationId = null;
            let selectedDestinationSource = null;

            // Initialize LocationSearch with database search capability
            const locationSearch = new LocationSearch({
                inputId: 'destination',
                resultsContainerId: 'destination-search-results',
                indicatorId: 'search-indicator',
                latInputId: 'destination_lat',
                lngInputId: 'destination_lng',
                countryCode: 'id',
                language: 'id',
                // Add URL for database search endpoint
                dbSearchUrl: '{{ route('user.itinerary.destination.search.name') }}',
                // Tambahkan parameter baru untuk menentukan urutan hasil
                resultsOrder: 'nominatim_first', // nominatim_first akan menampilkan hasil Nominatim di atas
                onLocationSelect: function(location) {
                    if (location) {
                        // Clear any existing nearby destinations display
                        nearbyDestinationsContainer.innerHTML =
                            '<div class="p-3 text-gray-500 text-sm">Mencari destinasi terdekat...</div>';

                        // Handle selection differently based on source
                        if (location.source === 'database') {
                            // If from database, it's already a destination, so set ID directly
                            destinationIdInput.value = location.id;
                            selectedDestinationId = location.id;
                            selectedDestinationSource = 'database';

                            console.log('Selected destination from database:', location);

                            // Display the selected destination
                            displaySelectedDatabaseDestination(location);

                            // No need to search for nearby destinations as the user already selected one
                            nearbyDestinationsContainer.innerHTML =
                                '<div class="p-3 text-gray-500 text-sm">Destinasi sudah dipilih dari database</div>';
                        } else {
                            // If from Nominatim, search for nearby destinations
                            destinationIdInput.value = '';
                            selectedDestinationId = null;
                            selectedDestinationSource = 'nominatim';

                            // Send coordinates to controller to get nearby destinations
                            sendCoordinatesToController(location.lat, location.lng, location.name);
                        }
                    }
                }
            });

            // Initialize event listeners
            setupEventListeners();

            /**
             * Setup all event listeners
             */
            function setupEventListeners() {
                // Clear selection
                if (clearDestinationButton) {
                    clearDestinationButton.addEventListener('click', clearSelectedDestination);
                }

                // Save destination
                if (saveButton) {
                    saveButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        saveDestination();
                    });
                }

                // Delete destination buttons
                if (deleteButtons.length > 0) {
                    deleteButtons.forEach(button => {
                        button.addEventListener('click', function(e) {
                            e.preventDefault();
                            // Get the destination ID directly from the button
                            const destinationId = this.getAttribute('data-destination-id');
                            // Get the itinerary ID from the hidden input field
                            const itineraryId = document.getElementById('itinerary_id').value;

                            // Call the removeDestination function with both IDs
                            if (destinationId) {
                                removeDestination(destinationId, itineraryId);
                            } else {
                                console.error('No destination ID found on delete button');
                            }
                        });
                    });
                }

                // Listen for modal toggle
                document.addEventListener('click', function(event) {
                    if (event.target && event.target.hasAttribute('data-modal-toggle') &&
                        event.target.getAttribute('data-modal-toggle') === 'destinasi-modal') {

                        // Get selected date if available
                        const selectedDate = event.target.getAttribute('data-date');
                        if (selectedDate) {
                            document.getElementById('selected_date').value = selectedDate;
                        }

                        // Reset form
                        resetForm();
                    }
                });
            }

            /**
             * Display selected database destination
             */
            function displaySelectedDatabaseDestination(destination) {
                // Show destination info
                selectedDestinationName.textContent = destination.name;
                selectedDestinationLocation.textContent =
                    `${destination.administrative_area || ''}, ${destination.province || ''}`;
                selectedDestinationInfo.classList.remove('hidden');
            }

            /**
             * Send coordinates to controller to get nearby destinations
             */
            function sendCoordinatesToController(lat, lng) {
                // Show loading state
                nearbyDestinationsContainer.innerHTML =
                    '<div class="p-3 text-gray-500 text-sm">Mencari destinasi terdekat...</div>';

                fetch("{{ route('user.itinerary.destination.search.coordinates') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                        },
                        body: JSON.stringify({
                            lat: lat,
                            lng: lng
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success' && data.nearbyDestinations) {
                            displayNearbyDestinations(data.nearbyDestinations);
                        } else {
                            nearbyDestinationsContainer.innerHTML =
                                '<div class="p-3 text-red-500 text-sm">Tidak ada destinasi ditemukan di sekitar lokasi ini</div>';
                        }
                    })
                    .catch(error => {
                        console.error('Error saat mengirim data:', error);
                        nearbyDestinationsContainer.innerHTML =
                            '<div class="p-3 text-red-500 text-sm">Terjadi kesalahan saat mencari destinasi</div>';
                    });
            }

            /**
             * Display nearby destinations
             */
            function displayNearbyDestinations(destinations) {
                nearbyDestinationsContainer.innerHTML = '';

                // Create title for destinations section
                const titleElement = document.createElement('h4');
                titleElement.className = 'text-base font-medium text-gray-700 mb-2';
                titleElement.textContent = 'Destinasi Terdekat';
                nearbyDestinationsContainer.appendChild(titleElement);

                if (!destinations.length) {
                    const noResult = document.createElement('div');
                    noResult.className = 'p-3 text-gray-500 text-sm';
                    noResult.textContent = 'Tidak ada destinasi wisata yang ditemukan di sekitar lokasi ini';
                    nearbyDestinationsContainer.appendChild(noResult);
                    return;
                }

                // Create a container for destination cards
                const cardsContainer = document.createElement('div');
                cardsContainer.className = 'grid grid-cols-1 md:grid-cols-2 gap-3';

                destinations.forEach(destination => {
                    const card = document.createElement('div');
                    card.className = 'border rounded-md p-3 hover:bg-gray-50 cursor-pointer';
                    card.dataset.destinationId = destination.id;

                    // Basic info
                    const nameElement = document.createElement('div');
                    nameElement.className = 'font-medium text-blue-600';
                    nameElement.textContent = destination.place_name || 'Unnamed Destination';

                    // Location
                    const locationElement = document.createElement('div');
                    locationElement.className = 'text-xs text-gray-500 mt-1';
                    locationElement.textContent =
                        `${destination.administrative_area || ''}, ${destination.province || ''}`;

                    // Append elements to card
                    card.appendChild(nameElement);
                    card.appendChild(locationElement);

                    // Check if distanceElement exists before appending
                    if (typeof distanceElement !== 'undefined' && distanceElement) {
                        card.appendChild(distanceElement);
                    }

                    // Add click event to select this destination
                    card.addEventListener('click', function() {
                        selectDestination(destination);
                    });

                    cardsContainer.appendChild(card);
                });

                nearbyDestinationsContainer.appendChild(cardsContainer);
            }

            /**
             * Select a destination from the list
             */
            function selectDestination(destination) {
                // Store destination ID and reset coordinates (since we're using a specific destination)
                selectedDestinationId = destination.id;
                selectedDestinationSource = 'database';

                // Update hidden input
                destinationIdInput.value = destination.id;

                // If the destination has coordinates, store them as well
                if (destination.latitude && destination.longitude) {
                    destinationLatInput.value = destination.latitude;
                    destinationLngInput.value = destination.longitude;
                }

                // Update the selected destination info
                selectedDestinationName.textContent = destination.place_name || 'Unnamed Destination';
                selectedDestinationLocation.textContent =
                    `${destination.administrative_area || ''}, ${destination.province || ''}`;
                selectedDestinationInfo.classList.remove('hidden');

                // Highlight the selected destination
                const allDestinationCards = nearbyDestinationsContainer.querySelectorAll('[data-destination-id]');
                allDestinationCards.forEach(card => {
                    card.classList.remove('border-blue-500', 'bg-blue-50');
                });

                // Find and highlight the selected card
                const selectedCard = nearbyDestinationsContainer.querySelector(
                    `[data-destination-id="${destination.id}"]`);
                if (selectedCard) {
                    selectedCard.classList.add('border-blue-500', 'bg-blue-50');
                }
            }

            /**
             * Clear selected destination
             */
            function clearSelectedDestination() {
                selectedDestinationId = null;
                selectedDestinationSource = null;

                // Clear hidden inputs
                destinationIdInput.value = '';

                // Reset location search
                locationSearch.clearSelectedLocation();

                // Hide selected destination info
                selectedDestinationInfo.classList.add('hidden');

                // Reset nearby destinations container
                nearbyDestinationsContainer.innerHTML =
                    '<div class="text-gray-500 text-sm italic">Pilih lokasi untuk melihat destinasi terdekat</div>';

                // Remove highlight from any selected card
                const allDestinationCards = document.querySelectorAll('[data-destination-id]');
                allDestinationCards.forEach(card => {
                    card.classList.remove('border-blue-500', 'bg-blue-50');
                });
            }

            /**
             * Reset form when modal is opened or closed
             */
            function resetForm() {
                destinationForm.reset();
                clearSelectedDestination();
                locationSearch.hideResults();
            }

            document.querySelectorAll('.add-destination-btn').forEach(button => {
                button.addEventListener('click', function() {
                    // Get selected date if available
                    const selectedDate = this.getAttribute('data-date');
                    if (selectedDate) {
                        document.getElementById('selected_date').value = selectedDate;
                        console.log('Selected date:', selectedDate); // For debugging
                    }

                    // Reset form
                    resetForm();
                });
            });

            /**
             * Save the selected destination to the itinerary
             */
            function saveDestination() {
                // Get form values
                const itineraryId = document.getElementById('itinerary_id').value;
                const visitTime = document.getElementById('visit_time').value;
                const note = document.getElementById('note').value;
                const selectedDate = document.getElementById('selected_date').value;

                // Get selected location
                const selectedLocation = locationSearch.getSelectedLocation();

                // Check if a destination or location was selected
                if (!selectedDestinationId && !selectedLocation) {
                    alert('Silakan pilih lokasi atau destinasi terlebih dahulu');
                    return;
                }

                // Create visit date time from selected date and time
                let visitDateTime = null;
                if (selectedDate && visitTime) {
                    visitDateTime = `${selectedDate}T${visitTime}`;
                }

                // Get the CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Prepare data
                const destinationData = {
                    itinerary_id: itineraryId,
                    visit_date_time: visitDateTime,
                    note: note
                };

                // If we have a destination from database, use it
                if (selectedDestinationId && selectedDestinationSource === 'database') {
                    destinationData.destination_id = selectedDestinationId;
                }
                // Otherwise use the selected location coordinates
                else if (selectedLocation) {
                    destinationData.destination_lat = destinationLatInput.value;
                    destinationData.destination_lng = destinationLngInput.value;
                    destinationData.destination_name = selectedLocation.formatted_name;

                    // If available, include additional location details
                    if (selectedLocation.administrative_area) {
                        destinationData.administrative_area = selectedLocation.administrative_area;
                    }
                    if (selectedLocation.province) {
                        destinationData.province = selectedLocation.province;
                    }
                }

                // Calculate order index if needed
                const orderIndex = document.getElementById('order_index').value || 0;
                if (orderIndex) {
                    destinationData.order_index = parseInt(orderIndex);
                }

                // Display loading state
                saveButton.disabled = true;
                saveButton.textContent = 'Menyimpan...';

                // Send data to the server using Fetch API
                fetch('{{ route('user.itinerary.destination.add') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify(destinationData)
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'success') {
                            // Show success message
                            console.log('Destination saved successfully:', data);

                            // Hide modal
                            const modalHideButtons = document.querySelectorAll(
                                '[data-modal-hide="destinasi-modal"]');
                            if (modalHideButtons.length > 0) {
                                modalHideButtons[0].click();
                            }

                            // Refresh the page to show new destination
                            window.location.reload();
                        } else {
                            // Show error message
                            alert(data.message || 'Terjadi kesalahan saat menyimpan destinasi');
                            saveButton.disabled = false;
                            saveButton.textContent = 'Simpan';
                        }
                    })
                    .catch(error => {
                        console.error('Error saat menyimpan destinasi:', error);
                        alert('Terjadi kesalahan saat menyimpan destinasi');
                        saveButton.disabled = false;
                        saveButton.textContent = 'Simpan';
                    });
            }
        });

        /**
         * Function to remove a destination from the itinerary
         * @param {number} destinationId - The ID of the destination to remove
         * @param {number} itineraryId - The ID of the itinerary
         */
        function removeDestination(destinationId, itineraryId) {
            // Confirm before deleting
            if (!confirm('Apakah Anda yakin ingin menghapus destinasi ini?')) {
                return;
            }

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Send delete request to the server
            fetch('{{ route('user.itinerary.destination.remove') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        itinerary_id: itineraryId,
                        destination_id: destinationId
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        // Show success message if needed
                        console.log('Destination removed successfully:', data);
                        // Refresh the page to update the destination list
                        window.location.reload();
                    } else {
                        // Show error message
                        alert(data.message || 'Terjadi kesalahan saat menghapus destinasi');
                    }
                })
                .catch(error => {
                    console.error('Error saat menghapus destinasi:', error);
                    alert('Terjadi kesalahan saat menghapus destinasi');
                });
        }
    </script>
@endpush
