@extends('layouts.admin')

@section('content')
    <div class="container mx-auto">
        <div class="">
            <div class="flex justify-between items-center p-6 border-b">
                <h2 class="text-2xl font-semibold text-gray-800">Pengajuan Destinasi</h2>
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
            <form action="{{ route('admin.destination-submission.index') }}" method="GET"
                class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
                <div class="w-full md:w-1/4">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" name="status"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="w-full md:w-1/4">
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select id="category_id" name="category_id"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full md:w-1/4">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Nama Tempat</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                        placeholder="Cari...">
                </div>

                <div class="w-full md:w-auto flex space-x-2">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Filter
                    </button>
                    <a href="{{ route('admin.destination-submission.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Reset
                    </a>
                </div>
            </form>

            {{-- Table --}}
            <div class="overflow-x-auto mt-6">
                <table class="w-full border-collapse text-center">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="p-3 ">No</th>
                            <th class="p-3 ">Gambar</th>
                            <th class="p-3 ">Nama Tempat</th>
                            <th class="p-3 ">Kategori</th>
                            <th class="p-3 ">Kota</th>
                            <th class="p-3 ">Status</th>
                            <th class="p-3 ">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($submissions as $submission)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $loop->iteration + ($submissions->currentPage() - 1) * $submissions->perPage() }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($submission->images)
                                        <img src="{{ asset('storage/' . $submission->images[0]['url']) }}"
                                            alt="{{ $submission->place_name }}" class="h-12 w-16 object-cover rounded">
                                    @else
                                        <div class="h-12 w-16 bg-gray-200 rounded flex items-center justify-center">
                                            <span class="text-gray-400 text-xs">No image</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ $submission->place_name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $submission->category->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $submission->administrative_area }}, {{ $submission->province }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $submission->status == 'approved'
                                        ? 'bg-green-100 text-green-800'
                                        : ($submission->status == 'rejected'
                                            ? 'bg-red-100 text-red-800'
                                            : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($submission->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <!-- View Button -->
                                        <a href="{{ route('admin.destination-submission.edit', $submission) }}"
                                            class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
                                            View
                                        </a>

                                        <!-- Delete Button -->
                                        <form action="" method="POST" class="inline-block"
                                            onsubmit="return confirm('Hapus pengajuan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center text-red-600 hover:text-red-800 transition-colors">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Tidak ada pengajuan ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($submissions->hasPages())
                <div class="flex justify-between items-center mt-6 gap-3">
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $submissions->firstItem() }} - {{ $submissions->lastItem() }}
                        dari total {{ $submissions->total() }} pengajuan
                    </div>
                    <div>
                        {{ $submissions->appends(request()->input())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
@endpush
