@extends('layouts.admin')

@section('content')
    <div class="container mx-auto">
        <div class="">
            <div class="flex justify-between items-center p-6 border-b">
                <h2 class="text-2xl font-semibold text-gray-800">Manajemen Pengguna</h2>
                <x-button href="{{ route('admin.users.create') }}" variant="primary">
                    Tambah Pengguna
                </x-button>
            </div>

            <div class="p-6">
                {{-- Filter dan Pencarian --}}
                <form method="GET" action="{{ route('admin.users.index') }}" class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        {{-- Pencarian --}}
                        <div class="relative">
                            <input type="text" name="search" placeholder="Cari nama atau email"
                                value="{{ request('search') }}"
                                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>

                        {{-- Filter Status --}}
                        <select name="status"
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Non-Aktif
                            </option>
                            <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Banned</option>
                        </select>

                        {{-- Jumlah Per Halaman --}}
                        <select name="per_page"
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            onchange="this.form.submit()">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 Pengguna
                            </option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Pengguna</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Pengguna</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Pengguna</option>
                        </select>

                        {{-- Reset Filter --}}
                        @if (request('search') || request('status'))
                            <a href="{{ route('admin.users.index') }}"
                                class="inline-flex items-center gap-2 rounded-lg bg-gray-500 px-4 py-2 text-white text-sm hover:bg-gray-600 transition mx-auto">
                                Reset Filter
                            </a>
                        @endif

                    </div>
                </form>

                {{-- Tabel Pengguna --}}
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100 border-b">
                                <th class="p-3 text-left">No</th>
                                <th class="p-3 text-left">Nama</th>
                                <th class="p-3 text-left">Email</th>
                                <th class="p-3 text-left">Tanggal Bergabung</th>
                                <th class="p-3 text-left">Status</th>
                                <th class="p-3 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $index => $user)
                                <tr class="border-b hover:bg-gray-50 transition-colors">
                                    <td class="p-3">{{ $users->firstItem() + $index }}</td>
                                    <td class="p-3">{{ $user->name }}</td>
                                    <td class="p-3">{{ $user->email }}</td>
                                    <td class="p-3">{{ $user->created_at->format('d M Y') }}</td>
                                    <td class="p-3">
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-semibold
                                    @if ($user->status == 'active') bg-green-100 text-green-800
                                    @elseif($user->status == 'inactive') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </td>
                                    <td class="p-3">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.users.show', $user) }}"
                                                class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="" class="text-yellow-600 hover:text-yellow-800">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="text-red-600 hover:text-red-800 btn-delete"
                                                data-id="{{ $user->id }}" data-name="{{ $user->name }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Informasi Pagination --}}
                <div class="flex justify-between items-center mt-6 gap-3">
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }}
                        dari total {{ $users->total() }} pengguna
                    </div>

                    {{-- Pagination Links --}}
                    <div>
                        {{ $users->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Konfirmasi Hapus --}}
        <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
            <div class="bg-white rounded-lg shadow-xl p-6 w-96">
                <div class="flex justify-between items-center mb-4">
                    <h5 class="text-lg font-semibold">Konfirmasi Hapus Pengguna</h5>
                    <button id="closeDeleteModal" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="mb-4">
                    Apakah Anda yakin ingin menghapus pengguna
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.btn-delete');
            const deleteModal = document.getElementById('deleteModal');
            const deleteForm = document.getElementById('deleteForm');
            const deleteUserNameSpan = document.getElementById('deleteUserName');
            const closeModalButtons = [
                document.getElementById('closeDeleteModal'),
                document.getElementById('cancelDelete')
            ];

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-id');
                    const userName = this.getAttribute('data-name');

                    deleteUserNameSpan.textContent = userName;
                    deleteForm.action = `{{ route('admin.users.index') }}/${userId}`;

                    deleteModal.classList.remove('hidden');
                    deleteModal.classList.add('flex');
                });
            });

            // Tutup modal
            closeModalButtons.forEach(button => {
                button.addEventListener('click', function() {
                    deleteModal.classList.remove('flex');
                    deleteModal.classList.add('hidden');
                });
            });
        });
    </script>
@endpush
