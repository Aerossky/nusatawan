@extends('layouts.admin')

@section('content')
    <div class="container mx-auto">
        <div class="">
            <div class="flex justify-between items-center p-6 border-b">
                <h2 class="text-2xl font-semibold text-gray-800">Manajemen Kategori</h2>
                <x-button href="{{ route('admin.categories.create') }}" variant="primary">
                    Tambah Kategori
                </x-button>
            </div>

            {{-- Alert --}}
            @if (session('success'))
                <x-ui.alert type="success" :message="session('success')" />
            @elseif (session('error'))
                <x-ui.alert type="error" :message="session('error')" />
            @endif

            <div class="p-6">
                {{-- Filter dan Pencarian --}}
                <form method="GET" action="{{ route('admin.categories.index') }}" class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        {{-- Pencarian --}}
                        <div class="relative">
                            <input type="text" name="search" placeholder="Cari nama kategori"
                                value="{{ request('search') }}"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>

                        {{-- Jumlah Per Halaman --}}
                        <select name="per_page"
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            onchange="this.form.submit()">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 Kategori
                            </option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Kategori</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Kategori</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Kategori</option>
                        </select>

                        {{-- Reset Filter --}}
                        @if (request('search'))
                            <a href="{{ route('admin.categories.index') }}"
                                class="inline-flex items-center gap-2 rounded-lg bg-gray-500 px-4 py-2 text-white text-sm hover:bg-gray-600 transition mx-auto">
                                Reset Filter
                            </a>
                        @endif
                    </div>
                </form>

                {{-- Tabel Kategori --}}
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100 border-b">
                                <th class="p-3 text-left">No</th>
                                <th class="p-3 text-left">Nama</th>
                                <th class="p-3 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $index => $category)
                                <tr class="border-b hover:bg-gray-50 transition-colors">
                                    <td class="p-3">{{ $categories->firstItem() + $index }}</td>
                                    <td class="p-3">{{ $category->name }}</td>
                                    <td class="p-3">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.categories.edit', $category) }}"
                                                class="text-yellow-600 hover:text-yellow-800">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="text-red-600 hover:text-red-800 btn-delete"
                                                data-id="{{ $category->id }}" data-name="{{ $category->name }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="flex justify-between items-center mt-6 gap-3">
                    {{-- <div class="text-sm text-gray-600">
                        Menampilkan {{ $categories->firstItem() }} - {{ $categories->lastItem() }}
                        dari total {{ $categories->total() }} kategori
                    </div> --}}
                    {{-- {{ $categories->appends(request()->input())->links() }} --}}
                </div>
            </div>
        </div>

        {{-- Modal Konfirmasi Hapus --}}
        <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
            <div class="bg-white rounded-lg shadow-xl p-6 w-96">
                <div class="flex justify-between items-center mb-4">
                    <h5 class="text-lg font-semibold">Konfirmasi Hapus Kategori</h5>
                    <button id="closeDeleteModal" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="mb-4">
                    Apakah Anda yakin ingin menghapus kategori
                    <strong id="deleteUserName" class="text-red-600"></strong>?
                </div>

                <div class="flex justify-end space-x-2">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" id="cancelDelete"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
