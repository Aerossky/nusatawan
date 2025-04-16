@extends('layouts.admin')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/@tailwindcss/typography@0.5.9/dist/typography.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />

    {{-- Custom CSS --}}
    @vite(['resources/css/custom/preview.css', 'resources/css/custom/map.css'])
@endpush

@section('content')
    <div>
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <h2 class="text-2xl font-semibold text-gray-800">Tambah Destinasi</h2>
            <x-button href="{{ route('admin.destinations.index') }}" variant="secondary">
                Kembali
            </x-button>
        </div>

        <!-- Error messages -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4" role="alert">
                <strong class="font-bold">Ada yang salah!</strong>
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main form -->
        <form action="{{ route('admin.destinations.store') }}" method="POST" enctype="multipart/form-data"
            class="p-6 space-y-6" x-data="imageUploader">
            @csrf

            <!-- Basic information section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Tempat -->
                <div>
                    <label for="place_name" class="block text-sm font-medium text-gray-700">Nama Tempat</label>
                    <input type="text" name="place_name" id="place_name" value="{{ old('place_name') }}"
                        class="mt-1 block w-full border-gray-300 rounded" placeholder="Contoh: Jempatan Balerang" required>
                    @error('place_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select name="category_id" id="category_id" class="mt-1 block w-full border-gray-300 rounded">
                        <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Pilih Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Time estimate -->
                <div>
                    <label for="time_minutes" class="block text-sm font-medium text-gray-700">
                        Perkiraan Lama Berwisata (Menit)
                    </label>
                    <input type="number" name="time_minutes" id="time_minutes" value="{{ old('time_minutes') }}"
                        min="0" class="mt-1 block w-full border-gray-300 rounded"
                        placeholder="Contoh: 45 (dalam menit)" required>
                    @error('time_minutes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Best time to visit -->
                <div>
                    <label for="best_visit_time" class="block text-sm font-medium text-gray-700">Jadwal Terbaik</label>
                    <input type="text" name="best_visit_time" id="best_visit_time" value="{{ old('best_visit_time') }}"
                        class="mt-1 block w-full border-gray-300 rounded" placeholder="Misal: Pagi 06:00 - 09:00" required>
                    @error('best_visit_time')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Location section -->
            <div class="space-y-4 border-t pt-6">
                <h3 class="text-lg font-medium text-gray-800">Informasi Lokasi</h3>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi di Peta</label>
                    <p class="text-sm text-gray-500 mb-2">Klik pada peta untuk menentukan lokasi</p>

                    <!-- Map search -->
                    <div class="relative mb-4">
                        <input type="text" id="map-search"
                            placeholder="Cari lokasi (cth: Jembatan Barelang Batam, Pantai Kuta Bali)..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Map container -->
                    <div id="map" class="rounded-lg border border-gray-300"></div>

                    <!-- Coordinates and city -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div>
                            <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                            <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                readonly required>
                        </div>
                        <div>
                            <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                            <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                readonly required>
                        </div>
                        <div>
                            <label for="administrative_area"
                                class="block text-sm font-medium text-gray-700 mb-1">Kota/Kabupaten</label>
                            <input type="text" name="administrative_area" id="administrative_area"
                                value="{{ old('administrative_area') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        {{-- provinsi --}}
                        <div class="">
                            <label for="province" class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                            <input type="text" name="province" id="province" value="{{ old('province') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description section -->
            <div class="border-t pt-6 space-y-4">
                <h3 class="text-lg font-medium text-gray-800">Deskripsi Destinasi</h3>

                <!-- Editor -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Konten Deskripsi</label>
                    <textarea name="description" id="description" rows="10"
                        class="mt-1 block w-full border-gray-300 rounded shadow-sm">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preview -->
                <div class="p-4 border rounded bg-gray-50">
                    <h4 class="font-bold mb-2">Preview Konten:</h4>
                    <div id="content-preview" class="prose prose-sm lg:prose-lg max-w-none"></div>
                </div>
            </div>

            <!-- Images section -->
            <div class="border-t pt-6 space-y-4">
                <h3 class="text-lg font-medium text-gray-800">Gambar Destinasi</h3>

                <!-- Hidden input for primary image -->
                <input type="hidden" name="primary_image_index" :value="primaryIndex">

                <!-- Upload area -->
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition-colors"
                    @dragover.prevent="dragOver = true" @dragleave.prevent="dragOver = false"
                    @drop.prevent="handleDrop($event)" :class="{ 'border-blue-500 bg-blue-50': dragOver }">

                    <div class="flex flex-col items-center justify-center space-y-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>

                        <h3 class="text-lg font-medium text-gray-700">Unggah Foto</h3>
                        <p class="text-sm text-gray-500">Tarik dan lepas gambar atau</p>

                        <label
                            class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                            Pilih File
                            <input type="file" name="image[]" multiple accept="image/*" @change="handleFiles($event)"
                                class="hidden">
                        </label>

                        <p class="text-xs text-gray-500">Format: JPG, PNG, GIF (Maks. 2MB)</p>
                    </div>
                </div>

                <!-- Error message -->
                <div x-show="error" x-transition
                    class="mt-2 p-3 bg-red-100 border border-red-200 text-red-700 rounded-md">
                    <p x-text="error"></p>
                </div>

                <!-- Preview area -->
                <div x-show="images.length > 0" class="mt-4">
                    <h4 class="text-md font-medium text-gray-700 mb-3">Preview Gambar</h4>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        <template x-for="(image, index) in images" :key="index">
                            <div class="relative group rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                <!-- Image -->
                                <div class="aspect-square overflow-hidden bg-gray-100">
                                    <img :src="image.url" class="w-full h-full object-cover"
                                        :class="{ 'primary-image': primaryIndex === index }">
                                </div>

                                <!-- Hover overlay -->
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all flex items-center justify-center">
                                    <div
                                        class="absolute bottom-0 left-0 right-0 p-2 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                                        <div class="flex justify-between items-center">
                                            <!-- Primary button -->
                                            <button type="button" @click="setPrimary(index)"
                                                class="text-xs font-medium px-2 py-1 rounded"
                                                :class="primaryIndex === index ? 'bg-green-500 text-white' :
                                                    'bg-white/80 text-gray-800 hover:bg-blue-500 hover:text-white'">
                                                <span x-text="primaryIndex === index ? 'Utama' : 'Set Utama'"></span>
                                            </button>

                                            <!-- Delete button -->
                                            <button type="button" @click="removeImage(index)"
                                                class="bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Filename -->
                                <div class="px-2 py-1 text-xs truncate bg-white border-t border-gray-200"
                                    x-text="image.file.name"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="border-t pt-6">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
    @vite(['resources/js/pages/create-destination/editor.js', 'resources/js/pages/create-destination/image-preview.js', 'resources/js/pages/destination-submission/map.js'])
    <script src="https://unpkg.com/alpinejs" defer></script>
@endpush
