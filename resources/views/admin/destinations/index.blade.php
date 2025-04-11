@extends('layouts.admin')

@section('content')
    <div class="container mx-auto">
        <div class="">
            <div class="flex justify-between items-center p-6 border-b">
                <h2 class="text-2xl font-semibold text-gray-800">Manajemen Destinasi</h2>
                <x-button href="{{ route('admin.destinations.create') }}" variant="primary">
                    Tambah Destinasi
                </x-button>
            </div>

            {{-- Alert --}}
            @if (session('success'))
                <x-ui.alert type="success" :message="session('success')" />
            @elseif (session('error'))
                <x-ui.alert type="error" :message="session('error')" />
            @endif
        </div>

        <div class="p-6">
            {{-- Filter dan Pencarian --}}
            <form method="GET" action="{{ route('admin.destinations.index') }}" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    {{-- Pencarian --}}
                    <div class="relative">
                        <input type="text" name="search" placeholder="Cari nama tempat atau kota"
                            value="{{ request('search') }}"
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>

                    {{-- Filter Kategori --}}
                    <select name="category_id"
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>


                    {{-- Arah Urutan  --}}
                    <select name="sort_by"
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        onchange="this.form.submit()">
                        <option value="newest" {{ request('sort_by', 'newest') == 'newest' ? 'selected' : '' }}>Terbaru
                        </option>
                        <option value="rating_desc" {{ request('sort_by') == 'rating_desc' ? 'selected' : '' }}>Rating
                            Tertinggi</option>
                        <option value="rating_asc" {{ request('sort_by') == 'rating_asc' ? 'selected' : '' }}>Rating
                            Terendah</option>
                    </select>

                    {{-- Jumlah Per Halaman --}}
                    <select name="per_page"
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        onchange="this.form.submit()">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 Destinasi</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Destinasi</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Destinasi</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Destinasi</option>
                    </select>

                    {{-- Reset Filter --}}
                    @if (request('search') || request('category_id') || request('sort_by'))
                        <div class="md:col-span-5">
                            <a href="{{ route('admin.destinations.index') }}"
                                class="inline-flex items-center gap-2 rounded-lg bg-gray-500 px-4 py-2 text-white text-sm hover:bg-gray-600 transition mx-auto">
                                Reset Filter
                            </a>
                        </div>
                    @endif
                </div>
            </form>

            {{-- table --}}
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="p-3 text-left">No</th>
                            <th class="p-3 text-left">Gambar</th>
                            <th class="p-3 text-left">Nama Tempat</th>
                            <th class="p-3 text-left">Kategori</th>
                            <th class="p-3 text-left">Kota</th>
                            <th class="p-3 text-left">Rating</th>
                            <th class="p-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($destinations as $destination)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $loop->iteration + ($destinations->currentPage() - 1) * $destinations->perPage() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($destination->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $destination->primaryImage['url']) }}"
                                            alt="{{ $destination->place_name }}" class="h-12 w-16 object-cover rounded">
                                    @else
                                        <div class="h-12 w-16 bg-gray-200 rounded flex items-center justify-center">
                                            <span class="text-gray-400 text-xs">No image</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $destination->place_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $destination->category->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $destination->city }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if ($destination->rating_count > 0)
                                        <div class="flex items-center">
                                            <span>{{ number_format($destination->rating, 1) }}</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-400 ml-1"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            <span
                                                class="text-xs text-gray-400 ml-1">({{ $destination->rating_count }})</span>
                                        </div>
                                    @else
                                        <span>No ratings</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.destinations.show', $destination) }}"
                                            class="text-blue-600 hover:text-blue-800">
                                            View
                                        </a>
                                        <a href="{{ route('admin.destinations.edit', $destination) }}"
                                            class="text-indigo-600 hover:text-indigo-800">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.destinations.destroy', $destination) }}"
                                            method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800"
                                                onclick="return confirm('Are you sure you want to delete this destination?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        @if ($destinations->isEmpty())
                            <tr>
                                <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                    No destinations found.
                                </td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="flex justify-between items-center mt-6 gap-3">
                <div class="text-sm text-gray-600">
                    Menampilkan {{ $destinations->firstItem() }} - {{ $destinations->lastItem() }}
                    dari total {{ $destinations->total() }} destinasi
                </div>
                <div>
                    {{ $destinations->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
@endpush
