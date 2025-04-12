@extends('layouts.admin')

@section('content')
    <div>
        <div class="flex justify-between items-center p-6 border-b">
            <h2 class="text-2xl font-semibold text-gray-800">Ubah Destinasi</h2>
            <div class="">
                <x-button href="{{ route('admin.destinations.index') }}" variant="secondary">
                    Kembali
                </x-button>
            </div>
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <x-ui.alert type="success" :message="session('success')" />
        @elseif (session('error'))
            <x-ui.alert type="error" :message="session('error')" />
        @endif

        {{-- Form untuk menangani hapus gambar (di luar form utama) --}}
        <form id="delete-image-form" action="" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>

        <form action="{{ route('admin.destinations.update', $destination) }}" method="POST" enctype="multipart/form-data"
            class="p-6 space-y-6" x-data="imageUploader" id="edit-form">
            {{-- CSRF Token --}}
            @csrf
            @method('PATCH')

            {{-- Input tersembunyi untuk gambar utama --}}
            <input type="hidden" name="primary_image_id" id="primary-image-input"
                value="{{ $destination->images->where('is_primary', true)->first()->id ?? '' }}">

            <!-- Nama Tempat -->
            <div>
                <label for="place_name" class="block text-sm font-medium text-gray-700">Nama Tempat</label>
                <input type="text" name="place_name" id="place_name"
                    value="{{ old('place_name', $destination->place_name) }}"
                    class="mt-1 block w-full border-gray-300 rounded" required>
                @error('place_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kategori -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                <select name="category_id" id="category_id" class="mt-1 block w-full border-gray-300 rounded">
                    <option value="" disabled>Pilih Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $destination->category_id) == $category->id ? 'selected' : '' }}>
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
                <label for="time_minutes" class="block text-sm font-medium text-gray-700">Perkiraan Lama Berwisata
                    (Menit)</label>
                <input type="number" name="time_minutes" id="time_minutes"
                    value="{{ old('time_minutes', $destination->time_minutes) }}" min="0"
                    class="mt-1 block w-full border-gray-300 rounded">
                @error('time_minutes')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- kota -->
            <div>
                <label for="city" class="block text-sm font-medium text-gray-700">Kota</label>
                <input type="text" name="city" id="city" value="{{ old('city', $destination->city) }}"
                    class="mt-1 block w-full border-gray-300 rounded">
                @error('city')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Latitude -->
                <div>
                    <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                    <input type="number" name="latitude" id="latitude"
                        value="{{ old('latitude', $destination->latitude) }}" step="0.000001" min="-90" max="90"
                        class="mt-1 block w-full border-gray-300 rounded">
                    @error('latitude')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Longitude -->
                <div>
                    <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                    <input type="number" name="longitude" id="longitude"
                        value="{{ old('longitude', $destination->longitude) }}" step="0.000001" min="-180"
                        max="180" class="mt-1 block w-full border-gray-300 rounded">
                    @error('longitude')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>


            <!-- Deskripsi -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="description" id="description" rows="10"
                    class="mt-1 block w-full border-gray-300 rounded shadow-sm">{{ old('description', $destination->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tempat Preview Konten Deskripsi -->
            <div class="p-4 border rounded bg-gray-50">
                <h3 class="font-bold mb-2">Preview Konten:</h3>
                <div id="content-preview" class="prose prose-sm lg:prose-lg max-w-none"></div>
            </div>

            {{-- Gambar --}}
            {{-- Foto Existing (Sudah Ada) --}}
            @if ($destination->images && count($destination->images) > 0)
                <div>
                    <h3 class="text-md font-medium text-gray-700 mb-2">Foto Saat Ini</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach ($destination->images as $index => $image)
                            <div
                                class="relative group rounded-lg overflow-hidden border border-gray-200 shadow-sm {{ $image->is_primary ? 'ring-4 ring-blue-500' : '' }}">

                                <!-- Gambar -->
                                <div class="aspect-square overflow-hidden bg-gray-100">
                                    <img src="{{ asset('storage/' . $image->url) }}"
                                        class="w-full h-full object-cover old-image" alt="{{ $image->name ?? 'image' }}">

                                    @if ($image->is_primary)
                                        <span
                                            class="absolute top-2 left-2 bg-blue-600 text-white text-xs px-2 py-0.5 rounded shadow">
                                            Utama
                                        </span>
                                    @endif
                                </div>

                                <!-- Overlay saat hover -->
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all flex items-center justify-center">
                                    <div
                                        class="absolute bottom-0 left-0 right-0 p-2 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                                        <div class="flex justify-between items-center">
                                            <!-- Set Utama -->
                                            <label
                                                class="text-xs font-medium px-2 py-1 rounded
                                            {{ $image->is_primary ? 'bg-green-500 text-white' : 'bg-white/80 text-gray-800 hover:bg-blue-500 hover:text-white' }}">
                                                <input type="radio" name="primary_image_index"
                                                    value="{{ $image->id }}"
                                                    @if ($image->is_primary) checked @endif>
                                                Set Utama
                                            </label>

                                            <!-- Tombol Hapus -->
                                            <button type="button"
                                                onclick="deleteImage('{{ route('admin.destinations.image.destroy', [$destination, $image]) }}')"
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
                                <div class="px-2 py-1 text-xs truncate bg-white border-t border-gray-200">
                                    {{ $image->name ?? basename($image->url) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif


            {{-- Upload Foto Baru --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Tambah Gambar Baru</label>
                <input type="file" id="image" name="image[]" multiple
                    class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

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

    <script>
        // Handler untuk radio button gambar utama
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.primary-image-radio').forEach(radio => {
                radio.addEventListener('change', function() {
                    // Update hidden input untuk form utama
                    document.getElementById('primary-image-input').value = this.value;

                    // Kirim form set-primary jika ingin langsung mengubah status
                    // tanpa menunggu form utama disimpan
                    setPrimaryImage(this.value);
                });
            });
        });

        // Fungsi untuk menangani penghapusan gambar
        function deleteImage(url) {
            if (confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
                const form = document.getElementById('delete-image-form');
                form.action = url;
                form.submit();
            }
        }

        //fungsi cek total gambar
        document.getElementById('edit-form').addEventListener('submit', function(e) {
            const maxImages = 5;
            const oldImages = document.querySelectorAll('.old-image').length;
            const newImages = document.getElementById('image').files.length;
            const total = oldImages + newImages;

            if (total > maxImages) {
                e.preventDefault(); // stop form
                alert(`Total gambar tidak boleh lebih dari ${maxImages}. Sekarang: ${total}`);
            }
        });
    </script>
@endpush
