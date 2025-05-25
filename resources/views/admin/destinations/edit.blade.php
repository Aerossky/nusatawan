@extends('layouts.admin')

@push('styles')
    <!-- Tailwind Typography -->
    <link href="https://cdn.jsdelivr.net/npm/@tailwindcss/typography@0.5.9/dist/typography.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />

    {{-- Custom CSS --}}
    @vite(['resources/css/custom/preview.css', 'resources/css/custom/map.css'])
@endpush

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

        @if ($errors->any())
            <div class="p-4 mb-6 text-sm text-red-800 rounded-lg bg-red-100" role="alert">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                
            </div>
        @endif

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

        {{-- Form untuk menangani hapus review (di luar form utama) --}}
        @foreach ($reviews as $review)
            <form id="delete-review-form-{{ $review->id }}"
                action="{{ route('admin.destinations.reviews.destroy', [$destination, $review]) }}" method="POST"
                style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @endforeach

        <form action="{{ route('admin.destinations.update', $destination) }}" method="POST" enctype="multipart/form-data"
            class="p-6 space-y-6" x-data="imageUploader" id="edit-form">
            {{-- CSRF Token --}}
            @csrf
            @method('PATCH')

            {{-- Input tersembunyi untuk gambar utama --}}
            <input type="hidden" name="primary_image_id" id="primary-image-input"
                value="{{ $destination->images->where('is_primary', true)->first()->id ?? '' }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                <!-- Best time to visit -->
                <div>
                    <label for="best_visit_time" class="block text-sm font-medium text-gray-700">Jadwal Terbaik</label>
                    <input type="text" name="best_visit_time" id="best_visit_time"
                        value="{{ old('best_time', $destination->best_visit_time) }}"
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
                            <input type="text" name="latitude" id="latitude"
                                value="{{ old('latitude', $destination->latitude) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                readonly required>
                        </div>
                        <div>
                            <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                            <input type="text" name="longitude" id="longitude"
                                value="{{ old('longitude', $destination->longitude) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                readonly required>
                        </div>
                        <div>
                            <label for="administrative_area"
                                class="block text-sm font-medium text-gray-700 mb-1">Kota/Kabupaten</label>
                            <input type="text" name="administrative_area" id="administrative_area"
                                value="{{ old('administrative_area', $destination->administrative_area) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        {{-- provinsi --}}
                        <div class="">
                            <label for="province" class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                            <input type="text" name="province" id="province"
                                value="{{ old('province', $destination->province) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
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

        {{-- Bagian Review  --}}
        <div class="p-6 border-t">
            <h3 class="text-md font-medium text-gray-700 mb-2">Review</h3>
            @if ($reviews->isEmpty())
                <p class="text-gray-500">Tidak ada review untuk destinasi ini.</p>
            @endif
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach ($reviews as $review)
                    <div class="bg-white p-4 rounded shadow-sm">
                        <div class="flex items-center mb-2">
                            <img src="{{ $review->user->profile_photo_url }}" alt="User"
                                class="w-10 h-10 rounded-full mr-2">
                            <div>
                                <p class="text-sm font-semibold">{{ $review->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-700 mb-2">
                            <strong>{{ $review->rating }} / 5</strong>
                            <p class="text-sm text-gray-600">{{ $review->comment }}</p>
                        </div>
                        <div class="flex justify-between">
                            <button type="button" onclick="deleteReview({{ $review->id }})"
                                class="text-red-500 hover:underline">
                                Hapus
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>

    {{-- Custom JS --}}
    @vite(['resources/js/pages/create-destination/editor.js', 'resources/js/pages/destination-submission/map.js'])
    <script src="https://unpkg.com/alpinejs" defer></script>

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

        // Fungsi untuk menangani penghapusan review
        function deleteReview(reviewId) {
            if (confirm('Apakah Anda yakin ingin menghapus review ini?')) {
                document.getElementById('delete-review-form-' + reviewId).submit();
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
