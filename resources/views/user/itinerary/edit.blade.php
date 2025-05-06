@extends('layouts.user')
@section('title', 'Buat Rencana Perjalanan')
@section('content')
    <div class="mt-[70px]"></div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <a href="{{ route('user.itinerary.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
                Kembali ke Daftar Rencana
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Ubah Rencana Perjalanan </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Silakan isi informasi dasar rencana perjalanan Anda.</p>
            </div>

            <form action="{{ route('user.itinerary.update', $itinerary) }}" method="POST" class="p-6">
                @csrf
                @method('PATCH')

                <div class="space-y-6">
                    <!-- Judul Perjalanan -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Judul Perjalanan</label>
                        <input type="text" name="title" id="title"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Contoh: Liburan ke Bali" value="{{ old('title', $itinerary->title) }}" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="startDate" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                            <input type="date" name="startDate" id="startDate"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                value="{{ old('startDate', $itinerary->startDate) }}" required>
                            @error('startDate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="endDate" class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                            <input type="date" name="endDate" id="endDate"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                value="{{ old('endDate', $itinerary->endDate) }}" required>
                            @error('endDate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="draft" {{ old('status', $itinerary->status) == 'draft' ? 'selected' : '' }}>
                                Rencana</option>
                            <option value="ongoing" {{ old('status', $itinerary->status) == 'ongoing' ? 'selected' : '' }}>
                                Sedang Berlangsung
                            </option>
                            <option value="complete"
                                {{ old('status', $itinerary->status) == 'complete' ? 'selected' : '' }}>
                                Selesai
                            </option>
                        </select>

                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Simpan dan Lanjutkan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startInput = document.getElementById('startDate');
            const endInput = document.getElementById('endDate');

            // Fungsi untuk mengatur batasan tanggal akhir
            function updateEndDateConstraints() {
                const startDate = new Date(startInput.value);
                if (startDate.toString() !== 'Invalid Date') {
                    // Tanggal minimal adalah tanggal mulai
                    endInput.min = startInput.value;

                    // Tanggal maksimal adalah 1 bulan setelah tanggal mulai
                    const maxEndDate = new Date(startDate);
                    maxEndDate.setMonth(maxEndDate.getMonth() + 1);
                    endInput.max = maxEndDate.toISOString().split('T')[0];

                    // Jika tanggal akhir sudah diisi tapi melebihi batas maksimal, sesuaikan
                    const endDate = new Date(endInput.value);
                    if (endDate > maxEndDate) {
                        endInput.value = maxEndDate.toISOString().split('T')[0];
                    }
                }
            }

            // Jalankan validasi saat halaman dimuat
            if (startInput.value) {
                updateEndDateConstraints();
            }

            // Jalankan validasi saat tanggal mulai berubah
            startInput.addEventListener('change', updateEndDateConstraints);
        });
    </script>
@endpush
