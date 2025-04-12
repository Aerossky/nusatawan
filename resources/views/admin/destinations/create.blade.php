@extends('layouts.admin')

@section('content')
    <div>
        <div class="flex justify-between items-center p-6 border-b">
            <h2 class="text-2xl font-semibold text-gray-800">Tambah Destinasi</h2>
            <x-button href="{{ route('admin.destinations.index') }}" variant="secondary">
                Kembali
            </x-button>
        </div>

        {{-- error --}}
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

        <form action="{{ route('admin.destinations.store') }}" method="POST" enctype="multipart/form-data"
            class="p-6 space-y-6" x-data="imageUploader">
            @csrf

            <!-- Nama Tempat -->
            <div>
                <label for="place_name" class="block text-sm font-medium text-gray-700">Nama Tempat</label>
                <input type="text" name="place_name" id="place_name" value="{{ old('place_name') }}"
                    class="mt-1 block w-full border-gray-300 rounded" required>
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

            {{-- time minutes --}}
            <div>
                <label for="time_minutes" class="block text-sm font-medium text-gray-700"> Perkiraan Lama Berwisata
                    (Menit)</label>
                <input type="number" name="time_minutes" id="time_minutes" value="{{ old('time_minutes') }}"
                    min="0" class="mt-1 block w-full border-gray-300 rounded">
                @error('time_minutes')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- kota -->
            <div>
                <label for="city" class="block text-sm font-medium text-gray-700">Kota</label>
                <input type="text" name="city" id="city" value="{{ old('city') }}"
                    class="mt-1 block w-full border-gray-300 rounded">
                @error('city')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Latitude -->
                <div>
                    <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                    <input type="number" name="latitude" id="latitude" value="{{ old('latitude') }}" step="0.000001"
                        min="-90" max="90" class="mt-1 block w-full border-gray-300 rounded">
                    @error('latitude')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Longitude -->
                <div>
                    <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                    <input type="number" name="longitude" id="longitude" value="{{ old('longitude') }}" step="0.000001"
                        min="-180" max="180" class="mt-1 block w-full border-gray-300 rounded">
                    @error('longitude')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>


            <!-- Deskripsi -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="description" id="description" rows="10"
                    class="mt-1 block w-full border-gray-300 rounded shadow-sm">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tempat Preview Konten Deskripsi -->
            <div class="p-4 border rounded bg-gray-50">
                <h3 class="font-bold mb-2">Preview Konten:</h3>
                <div id="content-preview" class="prose prose-sm lg:prose-lg max-w-none"></div>
            </div>

            {{-- GAMBAR START --}}
            <!-- Input untuk menyimpan index gambar primary -->
            <input type="hidden" name="primary_image_index" :value="primaryIndex">

            <!-- Area Upload -->
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition-colors mb-4"
                @dragover.prevent="dragOver = true" @dragleave.prevent="dragOver = false" @drop.prevent="handleDrop($event)"
                :class="{ 'border-blue-500 bg-blue-50': dragOver }">

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

            <!-- Pesan Error -->
            <div x-show="error" x-transition class="mb-4 p-3 bg-red-100 border border-red-200 text-red-700 rounded-md">
                <p x-text="error"></p>
            </div>

            <!-- Area Preview -->
            <div x-show="images.length > 0" class="mt-6">
                <h4 class="text-md font-medium text-gray-700 mb-3">Preview Gambar</h4>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    <template x-for="(image, index) in images" :key="index">
                        <div class="relative group rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                            <!-- Gambar -->
                            <div class="aspect-square overflow-hidden bg-gray-100">
                                <img :src="image.url" class="w-full h-full object-cover"
                                    :class="{ 'ring-4 ring-blue-500': primaryIndex === index }">
                            </div>

                            <!-- Overlay saat hover -->
                            <div
                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all flex items-center justify-center">
                                <div
                                    class="absolute bottom-0 left-0 right-0 p-2 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                                    <div class="flex justify-between items-center">
                                        <!-- Tombol Primary -->
                                        <button type="button" @click="setPrimary(index)"
                                            class="text-xs font-medium px-2 py-1 rounded"
                                            :class="primaryIndex === index ? 'bg-green-500 text-white' :
                                                'bg-white/80 text-gray-800 hover:bg-blue-500 hover:text-white'">
                                            <span x-text="primaryIndex === index ? 'Utama' : 'Set Utama'"></span>
                                        </button>

                                        <!-- Tombol Hapus -->
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

                            <!-- Nama file -->
                            <div class="px-2 py-1 text-xs truncate bg-white border-t border-gray-200"
                                x-text="image.file.name"></div>
                        </div>
                    </template>
                </div>
            </div>
            {{-- GAMBAR END --}}

            <!-- Submit -->
            <div>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <!-- Tailwind Typography -->
    <link href="https://cdn.jsdelivr.net/npm/@tailwindcss/typography@0.5.9/dist/typography.min.css" rel="stylesheet">

    <style>
        #content-preview h1 {
            font-size: 2em;
            font-weight: bold;
            margin-top: 0.5em;
            margin-bottom: 0.5em;
        }

        #content-preview h2 {
            font-size: 1.5em;
            font-weight: bold;
            margin-top: 0.5em;
            margin-bottom: 0.5em;
        }

        #content-preview h3 {
            font-size: 1.25em;
            font-weight: bold;
            margin-top: 0.5em;
            margin-bottom: 0.5em;
        }

        #content-preview ul {
            list-style-type: disc;
            margin-left: 1.5em;
            margin-top: 0.5em;
            margin-bottom: 0.5em;
        }

        #content-preview ol {
            list-style-type: decimal;
            margin-left: 1.5em;
            margin-top: 0.5em;
            margin-bottom: 0.5em;
        }

        #content-preview blockquote {
            border-left: 4px solid #e5e7eb;
            padding-left: 1em;
            font-style: italic;
        }

        #content-preview table {
            border-collapse: collapse;
            width: 100%;
        }

        #content-preview th,
        #content-preview td {
            border: 1px solid #e5e7eb;
            padding: 0.5em;
        }

        .primary-image {
            border: 3px solid #4f46e5 !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    @vite('resources/js/pages/create-destination/editor.js')
    @vite('resources/js/pages/create-destination/image-preview.js')
    <script src="https://unpkg.com/alpinejs" defer></script>
@endpush
