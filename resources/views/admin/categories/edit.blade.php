@extends('layouts.admin')

@section('content')
    <div>
        <div class="flex justify-between items-center p-6 border-b">
            <h2 class="text-2xl font-semibold text-gray-800">Ubah Kategori</h2>
            <x-button href="{{ route('admin.categories.index') }}" variant="secondary">
                Kembali
            </x-button>
        </div>

        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data"
            class="p-6 space-y-6" x-data="imageUploader">
            @csrf
            @method('PATCH')

            <!-- Nama Tempat -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}"
                    class="mt-1 block w-full border-gray-300 rounded" required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
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
