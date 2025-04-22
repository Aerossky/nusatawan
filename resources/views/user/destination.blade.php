@extends('layouts.user')
@section('title', 'Destinasi')

@section('content')
    {{-- Search Box Section --}}
    <div class="relative">
        {{-- Background Gambar --}}
        <div class="w-full h-64 bg-cover bg-center" style="background-image: url('{{ asset('images/hero.png') }}');"></div>

        {{-- Box Filter - posisi di antara gambar dan konten --}}
        <div
            class="absolute left-1/2 transform -translate-x-1/2 -bottom-10 bg-white shadow-lg rounded-lg p-4 w-11/12 md:w-3/4 lg:w-2/3">
            <div class="flex flex-wrap gap-2">
                {{-- Input Pencarian --}}
                <input type="text" placeholder="Cari destinasi..."
                    class="flex-grow min-w-[200px] p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-300">

                {{-- Dropdown Kategori --}}
                <select class="p-2 border border-gray-300 rounded-md">
                    <option value="">Pilih Kategori</option>
                    <option value="pantai">Pantai</option>
                    <option value="gunung">Gunung</option>
                    <option value="candi">Candi</option>
                </select>

                {{-- Dropdown Lokasi --}}
                <select class="p-2 border border-gray-300 rounded-md">
                    <option value="">Pilih Lokasi</option>
                    <option value="bali">Bali</option>
                    <option value="jawa">Jawa</option>
                    <option value="sumatera">Sumatera</option>
                </select>

                {{-- Dropdown Cuaca --}}
                <select class="p-2 border border-gray-300 rounded-md">
                    <option value="">Filter Cuaca</option>
                    <option value="cerah">Cerah</option>
                    <option value="berawan">Berawan</option>
                    <option value="hujan">Hujan</option>
                </select>

                {{-- Tombol Cari --}}
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Cari</button>
            </div>
        </div>
    </div>

    <div class="mt-12"></div>

    {{-- Card Section --}}
    <x-section>
        <div class="bg-gray-100 p-6">
            <h2 class="text-2xl font-bold">Jelajahi Destinasi Menarik</h2>
            <p class="text-gray-600 text-sm mb-4">Menampilkan 1-9 dari total 10 destinasi</p>

            <div class="mt-12"></div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Card Destinasi --}}
                @foreach ($destinations as $data)
                    <div class="bg-white rounded-md overflow-hidden shadow-md">
                        <div class="relative">
                            <img src="{{ asset('storage/' . $data->primaryImage->url) }}" alt="Pantai Kuta"
                                class="w-full h-32 object-cover">
                        </div>
                        <div class="p-4">
                            <div class="flex justify-between items-center mb-1">
                                <h3 class="font-semibold">{{ $data->place_name }}</h3>
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-700 mr-2">123</span>
                                    <button class="text-gray-400 hover:text-red-500 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm mb-2">{{ $data->administrative_area . ', ' . $data->province }}
                            </p>
                            <div class="mb-3">
                                <span
                                    class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full">{{ $data->category->name }}</span>
                            </div>
                            <button class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded text-sm">Lihat
                                Detail</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-section>

@endsection
