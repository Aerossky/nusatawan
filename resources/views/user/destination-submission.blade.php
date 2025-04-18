@extends('layouts.user')
@section('title', 'Pengajuan Destinasi')

@push('styles')
    <!-- CSS Leaflet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
    <style>
        #map {
            height: 400px;
            width: 100%;
        }

        .image-preview-item {
            position: relative;
            height: 150px;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .image-preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 0.375rem;
        }

        .delete-image {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(255, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        /* CSS untuk dropdown autocomplete */
        #map-search {
            position: relative;
            z-index: 2 !important;
        }

        #map {
            height: 400px;
            width: 100%;
            position: relative;
            z-index: 0;
            /* Pastikan peta berada di bawah pencarian */
        }

        /* Jika dropdown autocomplete perlu z-index lebih tinggi */
        .absolute.z-10.w-full.bg-white.border {
            z-index: 2;
            /* Pastikan dropdown muncul di atas peta */
        }

        /* Custom scrollbar untuk dropdown autocomplete */
        .absolute.z-10.w-full.bg-white.border::-webkit-scrollbar {
            width: 8px;
        }

        .absolute.z-10.w-full.bg-white.border::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .absolute.z-10.w-full.bg-white.border::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .absolute.z-10.w-full.bg-white.border::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }
    </style>
@endpush

@section('content')
    <div class="bg-white mt-14">
        <x-section>
            <div class="">
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-800">Pengajuan Destinasi</h1>
                    <p class="text-gray-600 mt-2">Ajukan destinasi wisata baru untuk dikunjungi oleh wisatawan lainnya</p>
                </div>

                <!-- Error messages -->
                @if (session('success'))
                    <x-ui.alert type="success" :message="session('success')" />
                @elseif (session('error'))
                    <x-ui.alert type="error" :message="session('error')" />
                @endif

                <div class="">
                    <form action="{{ route('destination-submission.store') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6" id="destinationForm">
                        @csrf
                        @method('POST')

                        @if ($errors->any())
                            <div class="bg-red-50 text-red-500 p-4 rounded-lg mb-6">
                                <ul class="list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Kolom Kiri -->
                            <div class="space-y-6">
                                <div>
                                    <label for="place_name" class="block text-sm font-medium text-gray-700 mb-1">Nama
                                        Destinasi</label>
                                    <input type="text" name="place_name" id="place_name" value="{{ old('place_name') }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Contoh: Pantai Kuta Bali">
                                    @error('place_name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="category_id"
                                        class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                    <select name="category_id" id="category_id"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="time_minutes" class="block text-sm font-medium text-gray-700 mb-1">
                                        Durasi Kunjungan (dalam Menit)
                                    </label>
                                    <input type="number" name="time_minutes" id="time_minutes"
                                        value="{{ old('time_minutes') }}" placeholder="Contoh: 60"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                        min="0" required>
                                    @error('time_minutes')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="best_visit_time" class="block text-sm font-medium text-gray-700 mb-1">
                                        Waktu Paling Disarankan untuk Berkunjung
                                    </label>
                                    <input type="text" name="best_visit_time" id="best_visit_time"
                                        value="{{ old('best_visit_time') }}" placeholder="Contoh: Pagi 06:00 - 09:00"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                    @error('best_visit_time')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="description"
                                        class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                    <textarea name="description" id="description" rows="4"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1" for="images">Foto
                                        Destinasi (Maksimal 5 foto)</label>
                                    <div
                                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                                fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path
                                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600 justify-center">
                                                <label for="images"
                                                    class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                                    <span>Upload file</span>
                                                    <input id="images" name="images[]" type="file" multiple
                                                        accept="image/*" class="sr-only"
                                                        {{ old('images') ? '' : 'required' }}>
                                                </label>
                                                <p class="pl-1">atau drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 5MB (Maksimal 5 foto)</p>
                                        </div>
                                    </div>
                                    <div id="error-images" class="error-message hidden">Tidak boleh lebih dari 5 foto</div>
                                    <div id="images-counter" class="text-sm text-gray-600 mt-2">0/5 foto dipilih</div>
                                    <div id="image-preview" class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-2"></div>

                                    {{-- Hidden inputs untuk old images --}}
                                    @if (old('temp_images'))
                                        @foreach (old('temp_images') as $index => $image)
                                            <input type="hidden" name="old_images[]" value="{{ $image }}"
                                                data-id="{{ $index }}">
                                        @endforeach
                                    @endif
                                </div>
                                @error('images')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                @error('images.*')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kolom Kanan -->
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1" for="map">Lokasi di
                                        Peta</label>
                                    <p class="text-sm text-gray-500 mb-2">Klik pada peta untuk menentukan lokasi</p>

                                    <div class="relative mb-4">
                                        <input type="text" id="map-search"
                                            placeholder="Cari lokasi (cth: Jembatan Barelang Batam, Pantai Kuta Bali)..."
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                            value="{{ old('map-search') }}">
                                    </div>

                                    <div id="map" class="rounded-lg border border-gray-300"></div>

                                    <div class="grid grid-cols-2 gap-4 mt-4">
                                        <div>
                                            <label for="latitude"
                                                class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                                            <input type="text" name="latitude" id="latitude"
                                                value="{{ old('latitude') }}"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                readonly required>
                                            @error('latitude')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="longitude"
                                                class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                                            <input type="text" name="longitude" id="longitude"
                                                value="{{ old('longitude') }}"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                readonly required>
                                            @error('longitude')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Kota Kabupaten --}}
                                        <div>
                                            <label for="administrative_area"
                                                class="block text-sm font-medium text-gray-700 mb-1">Kota/Kabupaten</label>
                                            <input type="text" name="administrative_area" id="administrative_area"
                                                value="{{ old('administrative_area') }}"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                            @error('administrative_area')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        {{-- provinsi --}}
                                        <div class="">
                                            <label for="province"
                                                class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                                            <input type="text" name="province" id="province"
                                                value="{{ old('province') }}"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                            @error('province')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>

                <div class="pt-5">
                    <div class="flex justify-end">
                        <button type="button" onclick="window.history.back()"
                            class="bg-white py-2 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Batal
                        </button>
                        <button type="submit"
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Ajukan Destinasi
                        </button>
                    </div>
                </div>
                </form>
            </div>
    </div>
    </x-section>
    </div>
@endsection

@push('scripts')
    <!-- Script Leaflet -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
    @vite(['resources/js/pages/destination-submission/map.js', 'resources/js/pages/destination-submission/image-upload.js'])
@endpush
