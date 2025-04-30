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
                @if (count($itinerary->itineraryDestinations) != 0)
                    <button data-modal-target="destinasi-modal" data-modal-toggle="destinasi-modal"
                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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

            @if (count($itinerary->itineraryDestinations) > 0)
                @php
                    // Urutkan destinasi berdasarkan tanggal dan waktu kunjungan
                    $sortedDestinations = $itinerary->itineraryDestinations->sortBy(function ($destination) {
                        // Jika visitDateTime tersedia, gunakan untuk pengurutan
                        if ($destination->visitDateTime) {
                            return $destination->visitDateTime;
                        }
                        // Jika tidak ada visitDateTime, letakkan di akhir
                        return '9999-12-31 23:59:59';
                    });

                    // Kelompokkan destinasi berdasarkan tanggal
                    $destinationsByDate = [];
                    foreach ($sortedDestinations as $destination) {
                        $dateKey = $destination->visitDateTime
                            ? \Carbon\Carbon::parse($destination->visitDateTime)->format('Y-m-d')
                            : 'no-date';

                        if (!isset($destinationsByDate[$dateKey])) {
                            $destinationsByDate[$dateKey] = [];
                        }

                        $destinationsByDate[$dateKey][] = $destination;
                    }

                    // Urutkan berdasarkan kunci tanggal
                    ksort($destinationsByDate);
                    $globalIndex = 1;
                @endphp
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <ul class="divide-y divide-gray-200">
                        @foreach ($destinationsByDate as $dateKey => $destinations)
                            @if ($dateKey !== 'no-date')
                                <li class="p-3 bg-blue-50">
                                    <div class="text-sm font-medium text-blue-800">
                                        {{ \Carbon\Carbon::parse($dateKey)->format('d M Y') }}
                                    </div>
                                </li>
                            @endif

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
                                                        {{ $itineraryDestination->destination->place_name }}</div>
                                                    <div class="mt-1 text-xs text-gray-500">
                                                        <span>{{ $itineraryDestination->destination->administrative_area }},
                                                            {{ $itineraryDestination->destination->province }}</span>
                                                        @if ($itineraryDestination->visitDateTime)
                                                            <span class="ml-2 px-2 py-0.5 rounded bg-blue-50 text-blue-700">
                                                                {{ \Carbon\Carbon::parse($itineraryDestination->visitDateTime)->format('H:i') }}
                                                            </span>
                                                        @endif
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
                                            <button type="button"
                                                onclick="removeDestination({{ $itineraryDestination->id }})"
                                                class="inline-flex items-center p-1.5 border border-gray-300 shadow-sm text-xs rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
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
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="p-6 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum Ada Destinasi</h3>
                        <p class="mt-1 text-sm text-gray-500">Mulai tambahkan tempat yang ingin Anda kunjungi.</p>
                        <div class="mt-6">
                            <button data-modal-target="destinasi-modal" data-modal-toggle="destinasi-modal"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Tambah Destinasi Sekarang
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>

    {{-- Modal Destinasi --}}
    <div id="destinasi-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <!-- Modal header -->
                <div
                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Terms of Service
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="destinasi-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                        With less than a month to go before the European Union enacts new consumer privacy laws for its
                        citizens, companies around the world are updating their terms of service agreements to comply.
                    </p>
                    <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                        The European Union’s General Data Protection Regulation (G.D.P.R.) goes into effect on May 25 and is
                        meant to ensure a common set of data rights in the European Union. It requires organizations to
                        notify
                        users as soon as possible of high-risk data breaches that could personally affect them.
                    </p>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button data-modal-hide="destinasi-modal" type="button"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">I
                        accept</button>
                    <button data-modal-hide="destinasi-modal" type="button"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Decline</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Edit Destination -->
    <div id="editDestinationModal"
        class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all max-w-lg w-full">
            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Edit Destinasi</h3>
            </div>
            <form id="editDestinationForm" method="POST">
                @csrf
                @method('PUT')
                <div class="px-4 py-5">
                    <div class="space-y-4">
                        <div>
                            <label for="visitDateTime" class="block text-sm font-medium text-gray-700">Waktu
                                Kunjungan</label>
                            <input type="datetime-local" name="visitDateTime" id="editVisitDateTime"
                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label for="note" class="block text-sm font-medium text-gray-700">Catatan</label>
                            <textarea name="note" id="editNote" rows="3"
                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan
                    </button>
                    <button type="button" onclick="closeEditModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal"
        class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all max-w-lg w-full">
            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Konfirmasi</h3>
            </div>
            <div class="px-4 py-5">
                <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus destinasi ini?</p>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form id="deleteDestinationForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Hapus
                    </button>
                </form>
                <button type="button" onclick="closeConfirmModal()"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>

    <!-- Share Modal -->
    <div id="shareModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all max-w-lg w-full">
            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Bagikan Rencana</h3>
            </div>
            <div class="px-4 py-5">
                <div class="space-y-4">
                    <p class="text-sm text-gray-500">Salin tautan di bawah ini untuk membagikan rencana perjalanan
                        Anda:</p>
                    <div class="flex">
                        <input type="text" id="shareLink" value=""
                            class="flex-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-l-md"
                            readonly>
                        <button type="button" onclick="copyShareLink()"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-r-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
                                <path
                                    d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex space-x-2 mt-4">
                        <a href="" target="_blank"
                            class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="h-5 w-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                            WhatsApp
                        </a>
                        <a href="mailto:?subject=Rencana Perjalanan: {{ $itinerary->title }}&body=Lihat rencana perjalanan saya: "
                            class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-600"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                            Email
                        </a>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeShareModal()"
                    class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentDestinationId = null;

        // Edit Destination Functions
        function editDestination(id) {
            currentDestinationId = id;
            // Fetch destination details via AJAX and populate the form
            fetch(`/itineraries/{{ $itinerary->id }}/destinations/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editVisitDateTime').value = data.visitDateTime;
                    document.getElementById('editNote').value = data.note;
                    document.getElementById('editDestinationForm').action =
                        `/itineraries/{{ $itinerary->id }}/destinations/${id}`;
                    document.getElementById('editDestinationModal').classList.remove('hidden');
                });
        }

        function closeEditModal() {
            document.getElementById('editDestinationModal').classList.add('hidden');
        }

        // Remove Destination Functions
        function removeDestination(id) {
            currentDestinationId = id;
            document.getElementById('deleteDestinationForm').action =
                `/itineraries/{{ $itinerary->id }}/destinations/${id}`;
            document.getElementById('confirmationModal').classList.remove('hidden');
        }

        function closeConfirmModal() {
            document.getElementById('confirmationModal').classList.add('hidden');
        }

        // Share Modal Functions
        document.getElementById('shareItinerary').addEventListener('click', function() {
            document.getElementById('shareModal').classList.remove('hidden');
        });

        function closeShareModal() {
            document.getElementById('shareModal').classList.add('hidden');
        }

        function copyShareLink() {
            const shareLink = document.getElementById('shareLink');
            shareLink.select();
            document.execCommand('copy');
            alert('Tautan berhasil disalin!');
        }

        // Print Function
        document.getElementById('printItinerary').addEventListener('click', function() {
            window.print();
        });

        // Close modals when clicking outside
        window.onclick = function(event) {
            const modals = [
                document.getElementById('editDestinationModal'),
                document.getElementById('confirmationModal'),
                document.getElementById('shareModal')
            ];

            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        }
    </script>
@endsection
