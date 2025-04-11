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
            class="p-6 space-y-6">
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
                    class="mt-1 block w-full border-gray-300 rounded">
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
                    <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}"
                        class="mt-1 block w-full border-gray-300 rounded">
                    @error('latitude')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Longitude -->
                <div>
                    <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                    <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}"
                        class="mt-1 block w-full border-gray-300 rounded">
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

            <!-- Gambar -->
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Gambar Destinasi</label>
                <input type="file" name="image[]" id="image" multiple class="mt-1 block w-full" accept="image/*">
                @foreach ($errors->get('image.*') as $messages)
                    @foreach ($messages as $message)
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @endforeach
                @endforeach


                <!-- Hidden Primary Image Index -->
                <input type="hidden" name="primary_image_index" id="primaryImage">
            </div>

            <!-- Preview Gambar -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold mb-4">Preview Gambar</h3>
                <div id="image-preview" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"></div>
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
    @vite('resources/js/pages/create-destination/image-preview.js')
@endpush
