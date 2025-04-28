@extends('layouts.user')
@section('title', 'Destinasi Favorit')

@section('content')
    {{-- Header Section with Back Button --}}
    <div class="bg-white shadow-sm py-4">
        <div class="container mx-auto px-4 flex items-center">
            <a href="" class="flex items-center text-blue-600 hover:text-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
            <h1 class="text-2xl font-bold text-center flex-grow">Destinasi Favorit</h1>
            <div class="w-20"></div> {{-- Untuk menyeimbangkan layout --}}
        </div>
    </div>

    {{-- Card Section --}}
    <x-section>
        <div class="bg-gray-50 p-6 rounded-lg mb-20">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Destinasi Favorit Anda</h2>
                    <p class="text-gray-600 text-sm">Menampilkan
                        {{ $favorites->firstItem() ?? 0 }}-{{ $favorites->lastItem() ?? 0 }} dari total
                        {{ $favorites->total() ?? count($favorites) }} destinasi</p>
                </div>

                <div class="relative">
                    <form method="GET" id="sortForm" action="{{ route('user.destination-favorite.index') }}">
                        <select name="sort" onchange="document.getElementById('sortForm').submit()"
                            class="bg-white border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Urutkan: Terbaru
                            </option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Urutkan: Rating
                            </option>
                        </select>
                    </form>
                </div>
            </div>

            @if (count($favorites) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($favorites as $data)
                        <x-destination-card :data="$data->destination" />
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    @if (method_exists($favorites, 'links'))
                        {{ $favorites->links() }}
                    @else
                        <div class="flex justify-center">
                            <nav class="inline-flex rounded-md shadow">
                                <a href="#"
                                    class="px-3 py-2 bg-white border border-gray-300 text-gray-500 hover:bg-gray-50 rounded-l-md">
                                    Sebelumnya
                                </a>
                                <a href="#" class="px-3 py-2 bg-blue-600 text-white border border-blue-600">
                                    1
                                </a>
                                <a href="#"
                                    class="px-3 py-2 bg-white border border-gray-300 text-gray-500 hover:bg-gray-50">
                                    2
                                </a>
                                <a href="#"
                                    class="px-3 py-2 bg-white border border-gray-300 text-gray-500 hover:bg-gray-50 rounded-r-md">
                                    Selanjutnya
                                </a>
                            </nav>
                        </div>
                    @endif
                </div>
            @else
                <div class="bg-white rounded-lg shadow p-8 text-center">
                    <div class="flex justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Belum Ada Destinasi Favorit</h3>
                    <p class="text-gray-600 mb-6">Anda belum menyimpan destinasi favorit apapun</p>
                    <a href="{{ route('user.destinations.index') }}"
                        class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-md transition">
                        Jelajahi Destinasi
                    </a>
                </div>
            @endif
        </div>
    </x-section>
@endsection
