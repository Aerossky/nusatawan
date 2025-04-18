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
                                        <a href=""
                                            class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                <path fill-rule="evenodd"
                                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            View
                                        </a>

                                        @if ($submission->status === 'pending')
                                            <!-- Approve Button -->
                                            <form action="{{ route('admin.destination-submission.approve', $submission) }}"
                                                method="POST" class="inline-block"
                                                onsubmit="return confirm('Setujui pengajuan ini?')">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center text-green-600 hover:text-green-800 transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Approve
                                                </button>
                                            </form>

                                            <!-- Reject Button -->
                                            <form action="{{ route('admin.destination-submission.reject', $submission) }}"
                                                method="POST" class="inline-block"
                                                onsubmit="return confirm('Tolak pengajuan ini?')">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center text-yellow-600 hover:text-yellow-800 transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Reject
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Delete Button -->
                                        <form action="" method="POST" class="inline-block"
                                            onsubmit="return confirm('Hapus pengajuan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center text-red-600 hover:text-red-800 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
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
