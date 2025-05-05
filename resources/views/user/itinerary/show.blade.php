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


    <!-- Modal untuk menambah destinasi -->
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
    <!-- Modal untuk mengedit destinasi -->
    <div id="edit-destinasi-modal" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full flex items-center justify-center">
        <div class="relative w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t sticky top-0 bg-white z-10">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Edit Destinasi
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center close-modal-btn"
                        data-modal-hide="edit-destinasi-modal">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="p-6 space-y-6 flex flex-col">
                    <form id="editDestinationForm">
                        <input type="hidden" id="edit_itinerary_destination_id" name="itinerary_destination_id">
                        <input type="hidden" id="edit_itinerary_id" name="itinerary_id" value="{{ $itinerary->id }}">
                        <input type="hidden" id="edit_selected_date" name="selected_date">

                        <!-- Destination info (read-only) -->
                        <div class="mb-4 p-3 border border-blue-300 rounded-md bg-blue-50">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 id="edit_destination_name" class="font-medium text-blue-700"></h4>
                                    <p id="edit_destination_location" class="text-sm text-gray-600 mt-1"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Time -->
                        <div class="mb-4">
                            <label for="edit_visit_time" class="block text-sm font-medium text-gray-700">Waktu
                                Kunjungan</label>
                            <input type="time" id="edit_visit_time" name="visit_time"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="edit_note" class="block text-sm font-medium text-gray-700">Catatan</label>
                            <textarea id="edit_note" name="note" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                placeholder="Tambahkan catatan..."></textarea>
                        </div>
                    </form>
                </div>

                <!-- Modal footer -->
                <div
                    class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b sticky bottom-0 bg-white z-10">
                    <button type="button" id="updateDestination"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan
                        Perubahan</button>
                    <button type="button"
                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 cancel-btn"
                        data-modal-hide="edit-destinasi-modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/location-search.js')
    <script>
        /**
         * Itinerary Management Script
         * Handles all itinerary-related functionality including destination management
         */

        // Main class to handle itinerary functionality
        class ItineraryManager {
            constructor() {
                // Core elements
                this.destinationForm = document.getElementById('destinationForm');
                this.saveButton = document.getElementById('saveDestination');
                this.deleteButtons = document.querySelectorAll('.deleteDestination');
                this.nearbyDestinationsContainer = document.getElementById('nearby-destinations');
                this.selectedDestinationInfo = document.getElementById('selected-destination-info');
                this.selectedDestinationName = document.getElementById('selected-destination-name');
                this.selectedDestinationLocation = document.getElementById('selected-destination-location');
                this.clearDestinationButton = document.getElementById('clear-destination');
                this.updateButton = document.getElementById('updateDestination');
                this.addDestinationButtons = document.querySelectorAll('.add-destination-btn');

                // Hidden inputs
                this.destinationIdInput = document.getElementById('destination_id');
                this.destinationLatInput = document.getElementById('destination_lat');
                this.destinationLngInput = document.getElementById('destination_lng');

                // Variables
                this.selectedDestinationId = null;
                this.selectedDestinationSource = null;
                this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                // Initialize location search
                this.initLocationSearch();

                // Setup event listeners
                this.setupEventListeners();
            }

            /**
             * Initialize location search functionality
             */
            initLocationSearch() {
                this.locationSearch = new LocationSearch({
                    inputId: 'destination',
                    resultsContainerId: 'destination-search-results',
                    indicatorId: 'search-indicator',
                    latInputId: 'destination_lat',
                    lngInputId: 'destination_lng',
                    countryCode: 'id',
                    language: 'id',
                    dbSearchUrl: '{{ route('user.itinerary.destination.search.name') }}',
                    resultsOrder: 'nominatim_first',
                    onLocationSelect: (location) => this.handleLocationSelect(location)
                });
            }

            /**
             * Setup all event listeners
             */
            setupEventListeners() {
                // Clear selection button
                if (this.clearDestinationButton) {
                    this.clearDestinationButton.addEventListener('click', () => this.clearSelectedDestination());
                }

                // Save destination button
                if (this.saveButton) {
                    this.saveButton.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.saveDestination();
                    });
                }

                // Delete destination buttons
                if (this.deleteButtons.length > 0) {
                    this.deleteButtons.forEach(button => {
                        button.addEventListener('click', (e) => {
                            e.preventDefault();
                            const destinationId = button.getAttribute('data-destination-id');
                            const itineraryId = document.getElementById('itinerary_id').value;

                            if (destinationId) {
                                this.removeDestination(destinationId, itineraryId);
                            } else {
                                console.error('No destination ID found on delete button');
                            }
                        });
                    });
                }

                // Update destination button
                if (this.updateButton) {
                    this.updateButton.addEventListener('click', () => this.updateDestination());
                }

                // Add destination buttons
                if (this.addDestinationButtons.length > 0) {
                    this.addDestinationButtons.forEach(button => {
                        button.addEventListener('click', () => {
                            const selectedDate = button.getAttribute('data-date');
                            if (selectedDate) {
                                document.getElementById('selected_date').value = selectedDate;
                            }
                            this.resetForm();
                        });
                    });
                }

                // Modal toggle listener
                document.addEventListener('click', (event) => {
                    if (event.target && event.target.hasAttribute('data-modal-toggle') &&
                        event.target.getAttribute('data-modal-toggle') === 'destinasi-modal') {

                        const selectedDate = event.target.getAttribute('data-date');
                        if (selectedDate) {
                            document.getElementById('selected_date').value = selectedDate;
                        }
                        this.resetForm();
                    }
                });
            }

            /**
             * Handle location selection from search
             * @param {Object} location - The selected location
             */
            handleLocationSelect(location) {
                if (!location) return;

                // Clear any existing nearby destinations display
                this.nearbyDestinationsContainer.innerHTML =
                    '<div class="p-3 text-gray-500 text-sm">Mencari destinasi terdekat...</div>';

                if (location.source === 'database') {
                    // If from database, it's already a destination
                    this.destinationIdInput.value = location.id;
                    this.selectedDestinationId = location.id;
                    this.selectedDestinationSource = 'database';

                    // Display selected destination
                    this.displaySelectedDatabaseDestination(location);

                    // No need to search for nearby destinations
                    this.nearbyDestinationsContainer.innerHTML =
                        '<div class="p-3 text-gray-500 text-sm">Destinasi sudah dipilih dari database</div>';
                } else {
                    // If from Nominatim, search for nearby destinations
                    this.destinationIdInput.value = '';
                    this.selectedDestinationId = null;
                    this.selectedDestinationSource = 'nominatim';

                    // Search for nearby destinations
                    this.sendCoordinatesToController(location.lat, location.lng, location.name);
                }
            }

            /**
             * Display selected database destination
             * @param {Object} destination - The destination to display
             */
            displaySelectedDatabaseDestination(destination) {
                this.selectedDestinationName.textContent = destination.name;
                this.selectedDestinationLocation.textContent =
                    `${destination.administrative_area || ''}, ${destination.province || ''}`;
                this.selectedDestinationInfo.classList.remove('hidden');
            }

            /**
             * Send coordinates to controller to fetch nearby destinations
             * @param {number} lat - Latitude
             * @param {number} lng - Longitude
             */
            sendCoordinatesToController(lat, lng) {
                this.nearbyDestinationsContainer.innerHTML =
                    '<div class="p-3 text-gray-500 text-sm">Mencari destinasi terdekat...</div>';

                fetch("{{ route('user.itinerary.destination.search.coordinates') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                        },
                        body: JSON.stringify({
                            lat,
                            lng
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success' && data.nearbyDestinations) {
                            this.displayNearbyDestinations(data.nearbyDestinations);
                        } else {
                            this.nearbyDestinationsContainer.innerHTML =
                                '<div class="p-3 text-red-500 text-sm">Tidak ada destinasi ditemukan di sekitar lokasi ini</div>';
                        }
                    })
                    .catch(error => {
                        console.error('Error saat mengirim data:', error);
                        this.nearbyDestinationsContainer.innerHTML =
                            '<div class="p-3 text-red-500 text-sm">Terjadi kesalahan saat mencari destinasi</div>';
                    });
            }

            /**
             * Display nearby destinations
             * @param {Array} destinations - List of destinations to display
             */
            displayNearbyDestinations(destinations) {
                this.nearbyDestinationsContainer.innerHTML = '';

                // Create title
                const titleElement = document.createElement('h4');
                titleElement.className = 'text-base font-medium text-gray-700 mb-2';
                titleElement.textContent = 'Destinasi Terdekat';
                this.nearbyDestinationsContainer.appendChild(titleElement);

                if (!destinations.length) {
                    const noResult = document.createElement('div');
                    noResult.className = 'p-3 text-gray-500 text-sm';
                    noResult.textContent = 'Tidak ada destinasi wisata yang ditemukan di sekitar lokasi ini';
                    this.nearbyDestinationsContainer.appendChild(noResult);
                    return;
                }

                // Create container for destination cards
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

                    // Add click event to select this destination
                    card.addEventListener('click', () => this.selectDestination(destination));

                    cardsContainer.appendChild(card);
                });

                this.nearbyDestinationsContainer.appendChild(cardsContainer);
            }

            /**
             * Select a destination from the list
             * @param {Object} destination - The destination to select
             */
            selectDestination(destination) {
                // Store destination ID
                this.selectedDestinationId = destination.id;
                this.selectedDestinationSource = 'database';

                // Update hidden input
                this.destinationIdInput.value = destination.id;

                // Store coordinates if available
                if (destination.latitude && destination.longitude) {
                    this.destinationLatInput.value = destination.latitude;
                    this.destinationLngInput.value = destination.longitude;
                }

                // Update selected destination info
                this.selectedDestinationName.textContent = destination.place_name || 'Unnamed Destination';
                this.selectedDestinationLocation.textContent =
                    `${destination.administrative_area || ''}, ${destination.province || ''}`;
                this.selectedDestinationInfo.classList.remove('hidden');

                // Remove highlights from all cards
                const allDestinationCards = this.nearbyDestinationsContainer.querySelectorAll('[data-destination-id]');
                allDestinationCards.forEach(card => {
                    card.classList.remove('border-blue-500', 'bg-blue-50');
                });

                // Highlight selected card
                const selectedCard = this.nearbyDestinationsContainer.querySelector(
                    `[data-destination-id="${destination.id}"]`);
                if (selectedCard) {
                    selectedCard.classList.add('border-blue-500', 'bg-blue-50');
                }
            }

            /**
             * Clear selected destination
             */
            clearSelectedDestination() {
                this.selectedDestinationId = null;
                this.selectedDestinationSource = null;

                // Clear hidden inputs
                this.destinationIdInput.value = '';

                // Reset location search
                this.locationSearch.clearSelectedLocation();

                // Hide selected destination info
                this.selectedDestinationInfo.classList.add('hidden');

                // Reset nearby destinations container
                this.nearbyDestinationsContainer.innerHTML =
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
            resetForm() {
                this.destinationForm.reset();
                this.clearSelectedDestination();
                this.locationSearch.hideResults();
            }

            /**
             * Save the selected destination to the itinerary
             */
            saveDestination() {
                // Get form values
                const itineraryId = document.getElementById('itinerary_id').value;
                const visitTime = document.getElementById('visit_time').value;
                const note = document.getElementById('note').value;
                const selectedDate = document.getElementById('selected_date').value;

                // Get selected location
                const selectedLocation = this.locationSearch.getSelectedLocation();

                // Check if a destination or location was selected
                if (!this.selectedDestinationId && !selectedLocation) {
                    alert('Silakan pilih lokasi atau destinasi terlebih dahulu');
                    return;
                }

                // Create visit date time from selected date and time
                let visitDateTime = null;
                if (selectedDate && visitTime) {
                    visitDateTime = `${selectedDate}T${visitTime}`;
                }

                // Prepare data
                const destinationData = {
                    itinerary_id: itineraryId,
                    visit_date_time: visitDateTime,
                    note: note
                };

                // Add destination ID or coordinate data as needed
                if (this.selectedDestinationId && this.selectedDestinationSource === 'database') {
                    destinationData.destination_id = this.selectedDestinationId;
                } else if (selectedLocation) {
                    destinationData.destination_lat = this.destinationLatInput.value;
                    destinationData.destination_lng = this.destinationLngInput.value;
                    destinationData.destination_name = selectedLocation.formatted_name;

                    // Include additional location details if available
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
                this.saveButton.disabled = true;
                this.saveButton.textContent = 'Menyimpan...';

                // Send data to the server
                fetch('{{ route('user.itinerary.destination.add') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
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
                            // Hide modal
                            const modalHideButtons = document.querySelectorAll(
                                '[data-modal-hide="destinasi-modal"]');
                            if (modalHideButtons.length > 0) {
                                modalHideButtons[0].click();
                            }

                            // Refresh the page to show new destination
                            window.location.reload();
                        } else {
                            alert(data.message || 'Terjadi kesalahan saat menyimpan destinasi');
                            this.saveButton.disabled = false;
                            this.saveButton.textContent = 'Simpan';
                        }
                    })
                    .catch(error => {
                        console.error('Error saat menyimpan destinasi:', error);
                        alert('Terjadi kesalahan saat menyimpan destinasi');
                        this.saveButton.disabled = false;
                        this.saveButton.textContent = 'Simpan';
                    });
            }

            /**
             * Update an existing destination
             */
            updateDestination() {
                // Get form values
                const itineraryDestinationId = document.getElementById('edit_itinerary_destination_id').value;
                const itineraryId = document.getElementById('edit_itinerary_id').value;
                const visitTime = document.getElementById('edit_visit_time').value;
                const note = document.getElementById('edit_note').value;

                // Prepare data
                const updateData = {
                    itinerary_destination_id: itineraryDestinationId,
                    itinerary_id: itineraryId,
                    visit_time: visitTime,
                    note: note
                };

                // Display loading state
                this.updateButton.disabled = true;
                this.updateButton.textContent = 'Menyimpan...';

                // Send data to the server
                fetch('/rencana-perjalanan/destinasi/update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                        },
                        body: JSON.stringify(updateData)
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'success') {
                            // Hide modal
                            const modalHideButtons = document.querySelectorAll(
                                '[data-modal-hide="edit-destinasi-modal"]');
                            if (modalHideButtons.length > 0) {
                                modalHideButtons[0].click();
                            }

                            // Refresh the page to show updated destination
                            window.location.reload();
                        } else {
                            alert(data.message || 'Terjadi kesalahan saat memperbarui destinasi');
                            this.updateButton.disabled = false;
                            this.updateButton.textContent = 'Simpan Perubahan';
                        }
                    })
                    .catch(error => {
                        console.error('Error saat memperbarui destinasi:', error);
                        alert('Terjadi kesalahan saat memperbarui destinasi');
                        this.updateButton.disabled = false;
                        this.updateButton.textContent = 'Simpan Perubahan';
                    });
            }

            /**
             * Remove a destination from the itinerary
             * @param {number} destinationId - The ID of the destination to remove
             * @param {number} itineraryId - The ID of the itinerary
             */
            removeDestination(destinationId, itineraryId) {
                // Confirm before deleting
                if (!confirm('Apakah Anda yakin ingin menghapus destinasi ini?')) {
                    return;
                }

                // Send delete request to the server
                fetch('{{ route('user.itinerary.destination.remove') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
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
                            // Refresh the page to update the destination list
                            window.location.reload();
                        } else {
                            alert(data.message || 'Terjadi kesalahan saat menghapus destinasi');
                        }
                    })
                    .catch(error => {
                        console.error('Error saat menghapus destinasi:', error);
                        alert('Terjadi kesalahan saat menghapus destinasi');
                    });
            }
        }

        /**
         * Modal Manager
         * Handles modal opening and closing functionality
         */
        class ModalManager {
            /**
             * Show modal with overlay
             * @param {string} modalId - ID of the modal element
             */
            static showModalWithOverlay(modalId) {
                const modal = document.getElementById(modalId);
                if (!modal) return;

                // Add overlay
                let overlay = document.createElement('div');
                overlay.setAttribute('modal-backdrop', '');
                overlay.classList.add(
                    'fixed', 'inset-0', 'bg-gray-900', 'bg-opacity-50', 'z-40'
                );
                document.body.appendChild(overlay);

                // Show modal
                modal.classList.remove('hidden');
                modal.classList.add('flex', 'items-center', 'justify-center', 'z-50');
            }

            /**
             * Hide modal and remove overlay
             * @param {string} modalId - ID of the modal element
             */
            static hideModalWithOverlay(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.add('hidden');
                }

                const overlay = document.querySelector('[modal-backdrop]');
                if (overlay) {
                    overlay.remove();
                }
            }

            /**
             * Initialize modal management
             */
            static initModals() {
                // Event listener for close buttons
                const closeButtons = document.querySelectorAll('[data-modal-hide]');
                closeButtons.forEach(button => {
                    const modalId = button.getAttribute('data-modal-hide');
                    if (modalId) {
                        button.addEventListener('click', () => ModalManager.hideModalWithOverlay(modalId));
                    }
                });
            }
        }

        /**
         * Itinerary edit functionality
         * @param {number} itineraryDestinationId - The ID of the itinerary destination to edit
         */
        function editDestination(itineraryDestinationId) {
            const itineraryId = document.getElementById('itinerary_id').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Reset form
            document.getElementById('editDestinationForm').reset();

            // Set itinerary ID and destination ID
            document.getElementById('edit_itinerary_destination_id').value = itineraryDestinationId;
            document.getElementById('edit_itinerary_id').value = itineraryId;

            // Fetch destination details
            fetch(`/rencana-perjalanan/destinasi/${itineraryDestinationId}/detail`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        const destination = data.destination;

                        // Set destination info
                        if (destination && destination.place_name) {
                            document.getElementById('edit_destination_name').textContent =
                                destination.place_name || 'Unnamed Destination';

                            const locationText =
                                `${destination.administrative_area || ''}, ${destination.province || ''}`;
                            document.getElementById('edit_destination_location').textContent = locationText;
                        } else {
                            document.getElementById('edit_destination_name').textContent = 'Unnamed Destination';
                            document.getElementById('edit_destination_location').textContent = 'Lokasi tidak tersedia';
                        }

                        // Set time and note
                        if (destination.visit_date_time) {
                            const visitDateTime = new Date(destination.visit_date_time);
                            const hours = String(visitDateTime.getHours()).padStart(2, '0');
                            const minutes = String(visitDateTime.getMinutes()).padStart(2, '0');
                            const visitTime = `${hours}:${minutes}`;

                            document.getElementById('edit_visit_time').value = visitTime;
                        }

                        document.getElementById('edit_note').value = destination.note || '';

                        // Show the modal
                        ModalManager.showModalWithOverlay('edit-destinasi-modal');
                    } else {
                        alert(data.message || 'Terjadi kesalahan saat mengambil data destinasi');
                    }
                })
                .catch(error => {
                    console.error('Error saat mengambil data destinasi:', error);
                    alert('Terjadi kesalahan saat mengambil data destinasi');
                });
        }

        // Initialize everything when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize modal manager
            ModalManager.initModals();

            // Initialize itinerary manager
            const itineraryManager = new ItineraryManager();

            // Make edit function globally available
            window.editDestination = editDestination;
            window.showModalWithOverlay = ModalManager.showModalWithOverlay;
        });
    </script>
@endpush
