@extends('layouts.admin')

@push('styles')
    <!-- Tailwind Typography -->
    <link href="https://cdn.jsdelivr.net/npm/@tailwindcss/typography@0.5.9/dist/typography.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />

    {{-- Custom CSS --}}
    @vite(['resources/css/custom/preview.css', 'resources/css/custom/map.css'])

    [x-cloak] { display: none !important; }
@endpush

@section('content')
    <div>
        {{-- Header Section --}}
        <div class="flex justify-between items-center p-6 border-b">
            <h2 class="text-2xl font-semibold text-gray-800">Review Pengajuan Destinasi</h2>
            <div>
                <x-button href="{{ route('admin.destination-submission.index') }}" variant="secondary">
                    Kembali
                </x-button>
            </div>
        </div>

        {{-- Alert Messages --}}
        @if (session('success'))
            <x-ui.alert type="success" :message="session('success')" />
        @elseif (session('error'))
            <x-ui.alert type="error" :message="session('error')" />
        @endif

        <div class="p-6">
            {{-- Status Bar --}}
            <div
                class="mb-6 p-4 rounded-lg {{ $submission->status === 'pending' ? 'bg-yellow-50 border border-yellow-200' : ($submission->status === 'approved' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200') }}">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        @if ($submission->status === 'pending')
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                    clip-rule="evenodd" />
                            </svg>
                        @elseif($submission->status === 'approved')
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        @elseif($submission->status === 'rejected')
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        @endif
                    </div>
                    <div class="ml-3">
                        <h3
                            class="text-sm font-medium {{ $submission->status === 'pending' ? 'text-yellow-800' : ($submission->status === 'approved' ? 'text-green-800' : 'text-red-800') }}">
                            {{ $submission->status === 'pending' ? 'Menunggu Persetujuan' : ($submission->status === 'approved' ? 'Disetujui' : 'Ditolak') }}
                        </h3>
                        @if ($submission->admin_note)
                            <div
                                class="mt-2 text-sm {{ $submission->status === 'pending' ? 'text-yellow-700' : ($submission->status === 'approved' ? 'text-green-700' : 'text-red-700') }}">
                                <p><strong>Catatan:</strong> {{ $submission->admin_note }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Basic Details Section --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Nama Tempat</h3>
                    <p class="mt-1 text-lg font-medium text-gray-900">{{ $submission->place_name }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500">Kategori</h3>
                    <p class="mt-1 text-lg font-medium text-gray-900">
                        {{ $submission->category->name ?? 'Tidak ada kategori' }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500">Perkiraan Lama Berwisata</h3>
                    <p class="mt-1 text-lg font-medium text-gray-900">{{ $submission->time_minutes }} menit</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500">Jadwal Terbaik</h3>
                    <p class="mt-1 text-lg font-medium text-gray-900">{{ $submission->best_visit_time }}</p>
                </div>
            </div>

            {{-- Location Section --}}
            <div class="space-y-4 border-t pt-6 mb-6">
                <h3 class="text-lg font-medium text-gray-800">Informasi Lokasi</h3>

                <div>
                    <div id="map" class="rounded-lg border border-gray-300 h-80 mb-4"></div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Latitude</h3>
                            <p class="mt-1 text-lg font-medium text-gray-900">{{ $submission->latitude }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Longitude</h3>
                            <p class="mt-1 text-lg font-medium text-gray-900">{{ $submission->longitude }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Kota/Kabupaten</h3>
                            <p class="mt-1 text-lg font-medium text-gray-900">{{ $submission->administrative_area }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Provinsi</h3>
                            <p class="mt-1 text-lg font-medium text-gray-900">{{ $submission->province }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Images Section --}}
            <div class="mb-6 border-t pt-6">
                <h3 class="text-lg font-medium text-gray-800 mb-3">Foto-foto</h3>
                @if ($submission->images && count($submission->images) > 0)
                    @if ($submission->status === 'pending')
                        <form id="image-selection-form" class="mb-4">
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @foreach ($submission->images as $index => $image)
                                    <div
                                        class="relative group rounded-lg overflow-hidden border border-gray-200 shadow-sm {{ $index === 0 || $image->is_primary ? 'ring-2 ring-blue-500' : '' }}">
                                        <div class="absolute top-2 right-2 z-10">
                                            <input type="checkbox" name="selected_images[]" value="{{ $image->id }}"
                                                class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                checked>
                                        </div>

                                        <div class="absolute bottom-12 right-2 z-10 bg-white bg-opacity-80 p-1 rounded">
                                            <input type="radio" name="primary_image" value="{{ $image->id }}"
                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                                {{ ($index === 0 && !$submission->images->contains('is_primary', true)) || $image->is_primary ? 'checked' : '' }}>
                                            <label class="text-xs font-medium text-gray-700">Utama</label>
                                        </div>

                                        <div class="aspect-square overflow-hidden bg-gray-100">
                                            <img src="{{ asset('storage/' . $image->url) }}"
                                                class="w-full h-full object-cover" alt="{{ $image->name ?? 'image' }}">
                                            @if (($index === 0 && !$submission->images->contains('is_primary', true)) || $image->is_primary)
                                                <span
                                                    class="absolute top-2 left-2 bg-blue-600 text-white text-xs px-2 py-0.5 rounded shadow">
                                                    Utama
                                                </span>
                                            @endif
                                        </div>

                                        <div class="px-2 py-1 text-xs truncate bg-white border-t border-gray-200">
                                            {{ $image->name ?? basename($image->url) }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 bg-blue-50 p-3 rounded-lg">
                                <p class="text-sm text-blue-700">
                                    <span class="font-bold">Petunjuk:</span> Centang kotak pada gambar untuk memilih foto
                                    yang akan disimpan. Pilih satu foto sebagai foto utama dengan mengklik radio button
                                    "Utama".
                                </p>
                            </div>
                        </form>
                    @else
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                            @foreach ($submission->images as $image)
                                <div
                                    class="relative group rounded-lg overflow-hidden border border-gray-200 shadow-sm {{ $image->is_primary ? 'ring-2 ring-blue-500' : '' }}">
                                    <div class="aspect-square overflow-hidden bg-gray-100">
                                        <img src="{{ asset('storage/' . $image->url) }}" class="w-full h-full object-cover"
                                            alt="{{ $image->name ?? 'image' }}">
                                        @if ($image->is_primary)
                                            <span
                                                class="absolute top-2 left-2 bg-blue-600 text-white text-xs px-2 py-0.5 rounded shadow">
                                                Utama
                                            </span>
                                        @endif
                                    </div>

                                    <div class="px-2 py-1 text-xs truncate bg-white border-t border-gray-200">
                                        {{ $image->name ?? basename($image->url) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <p class="text-gray-500 italic">Tidak ada foto.</p>
                @endif
            </div>

            {{-- Approval Forms Section --}}
            @if ($submission->status === 'pending')
                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Deskripsi</h3>

                    <div class="mb-4 border-b" x-data="{ activeTab: 'original' }">
                        <div class="flex -mb-px">
                            <button @click="activeTab = 'original'"
                                :class="{ 'border-b-2 border-blue-500 text-blue-600': activeTab === 'original' }"
                                class="px-4 py-2 font-medium text-sm hover:text-blue-600">Deskripsi Asli</button>
                            <button @click="activeTab = 'edit'"
                                :class="{ 'border-b-2 border-blue-500 text-blue-600': activeTab === 'edit' }"
                                class="px-4 py-2 font-medium text-sm hover:text-blue-600">Edit Deskripsi</button>
                        </div>
                    </div>

                    <div x-show="activeTab === 'original'"
                        class="prose prose-sm lg:prose-lg max-w-none bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
                        {!! $submission->description !!}
                    </div>

                    <div>
                        <!-- Form approval -->
                        <form action="{{ route('admin.destination-submission.approve', $submission) }}" method="POST"
                            class="space-y-4" id="approval-form">
                            @csrf

                            <div id="selected-images-container"></div>
                            <input type="hidden" name="primary_image_id" id="primary-image-input">

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Edit
                                    Deskripsi</label>
                                <textarea id="description" name="description" rows="10"
                                    class="mt-1 block w-full border-gray-300 rounded shadow-sm">{{ $submission->description }}</textarea>
                            </div>

                            <div class="p-4 border rounded bg-gray-50">
                                <h3 class="font-bold mb-2">Preview Konten:</h3>
                                <div id="content-preview" class="prose prose-sm lg:prose-lg max-w-none"></div>
                            </div>

                            <div>
                                <label for="admin_note" class="block text-sm font-medium text-gray-700 mb-1">Catatan
                                    Persetujuan (Opsional)</label>
                                <textarea id="admin_note" name="admin_note" rows="3" class="w-full border-gray-300 rounded-md shadow-sm"></textarea>
                            </div>

                            <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition">
                                Setujui Dengan Perubahan
                            </button>
                        </form>
                    </div>

                    <div class="border-t pt-6 mt-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Tolak Pengajuan</h3>
                        <form action="{{ route('admin.destination-submission.reject', $submission) }}" method="POST"
                            id="reject-form">
                            @csrf

                            <div id="reject-selected-images-container"></div>
                            <input type="hidden" name="primary_image_id" id="reject-primary-image-input">

                            <div class="mb-4">
                                <label for="admin_note" class="block text-sm font-medium text-gray-700 mb-1">Alasan
                                    Penolakan <span class="text-red-500">*</span></label>
                                <textarea id="admin_note" name="admin_note" rows="3" class="w-full border-gray-300 rounded-md shadow-sm"
                                    required></textarea>
                            </div>
                            <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md transition">
                                Tolak Pengajuan
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="mb-6 border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-3">Deskripsi</h3>
                    <div class="prose prose-sm lg:prose-lg max-w-none bg-gray-50 p-4 rounded-lg border border-gray-200">
                        {!! $submission->description !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize map
            const map = L.map('map').setView([{{ $submission->latitude }}, {{ $submission->longitude }}], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            const marker = L.marker([{{ $submission->latitude }}, {{ $submission->longitude }}]).addTo(map);
            marker.bindPopup("<b>{{ $submission->place_name }}</b>").openPopup();

            @if ($submission->status === 'pending')
                // Pastikan ada gambar utama yang dipilih saat halaman dimuat
                document.addEventListener('DOMContentLoaded', function() {
                    // Cek apakah ada primary image yang sudah terpilih
                    const existingPrimary = document.querySelector('input[name="primary_image"]:checked');

                    // Jika tidak ada, pilih yang pertama
                    if (!existingPrimary) {
                        const firstImage = document.querySelector('input[name="primary_image"]');
                        if (firstImage) {
                            firstImage.checked = true;

                            // Tambahkan label "Utama" pada gambar pertama
                            const imageContainer = firstImage.closest('.relative.group');
                            const imgContainer = imageContainer.querySelector('.aspect-square');

                            // Periksa apakah label sudah ada
                            if (!imgContainer.querySelector('.bg-blue-600.absolute.top-2.left-2')) {
                                const utamaSpan = document.createElement('span');
                                utamaSpan.className =
                                    'absolute top-2 left-2 bg-blue-600 text-white text-xs px-2 py-0.5 rounded shadow';
                                utamaSpan.textContent = 'Utama';
                                imgContainer.appendChild(utamaSpan);
                            }

                            // Tambahkan ring pada container
                            imageContainer.classList.add('ring-2', 'ring-blue-500');
                        }
                    }

                    // Pastikan nilai primary_image_id sudah diisi
                    const primaryRadio = document.querySelector('input[name="primary_image"]:checked');
                    if (primaryRadio) {
                        document.getElementById('primary-image-input').value = primaryRadio.value;
                        document.getElementById('reject-primary-image-input').value = primaryRadio.value;
                    }
                });
            @endif

            // Wait for Alpine.js to load
            setTimeout(() => {
                // Set up tabs functionality
                const tabButtons = document.querySelectorAll(
                    '[x-data="{ activeTab: \'original\' }"] button');
                const originalContent = document.querySelector('[x-show="activeTab === \'original\'"]');
                const editContent = document.querySelector('[x-show="activeTab === \'edit\'"]');

                if (tabButtons.length === 2) {
                    tabButtons[0].addEventListener('click', function() {
                        originalContent.style.display = 'block';
                        editContent.style.display = 'none';
                    });

                    tabButtons[1].addEventListener('click', function() {
                        originalContent.style.display = 'none';
                        editContent.style.display = 'block';
                    });
                }

                // Initialize editor for pending submissions
                @if ($submission->status === 'pending')
                    let editor;
                    ClassicEditor
                        .create(document.querySelector('#description'))
                        .then(newEditor => {
                            editor = newEditor;

                            // Update preview on content change
                            editor.model.document.on('change:data', () => {
                                document.getElementById('content-preview').innerHTML = editor
                                    .getData();
                            });

                            // Initial preview
                            document.getElementById('content-preview').innerHTML = editor.getData();
                        })
                        .catch(error => {
                            console.error(error);
                        });

                    // Handle image selection functionality
                    const approvalForm = document.getElementById('approval-form');
                    const rejectForm = document.getElementById('reject-form');
                    const selectedImagesContainer = document.getElementById('selected-images-container');
                    const rejectSelectedImagesContainer = document.getElementById(
                        'reject-selected-images-container');
                    const primaryImageInput = document.getElementById('primary-image-input');
                    const rejectPrimaryImageInput = document.getElementById('reject-primary-image-input');

                    // Update hidden inputs before submission
                    function updateImageSelection() {
                        // Clear existing hidden inputs
                        selectedImagesContainer.innerHTML = '';
                        rejectSelectedImagesContainer.innerHTML = '';

                        // Get selected images
                        const checkboxes = document.querySelectorAll(
                            'input[name="selected_images[]"]:checked');
                        checkboxes.forEach(checkbox => {
                            const hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = 'selected_images[]';
                            hiddenInput.value = checkbox.value;

                            selectedImagesContainer.appendChild(hiddenInput);

                            // Clone for reject form
                            const rejectHiddenInput = hiddenInput.cloneNode(true);
                            rejectSelectedImagesContainer.appendChild(rejectHiddenInput);
                        });

                        // Set primary image
                        const primaryRadio = document.querySelector('input[name="primary_image"]:checked');
                        if (primaryRadio) {
                            primaryImageInput.value = primaryRadio.value;
                            rejectPrimaryImageInput.value = primaryRadio.value;
                        }
                    }

                    // Add form submission handlers
                    if (approvalForm) {
                        approvalForm.addEventListener('submit', function(e) {
                            e.preventDefault(); // Mencegah submit langsung
                            updateImageSelection();
                            this.submit(); // Submit form setelah update selesai
                        });
                    }
                    if (rejectForm) {
                        rejectForm.addEventListener('submit', updateImageSelection);
                    }

                    // Handle checkbox change events
                    document.querySelectorAll('input[name="selected_images[]"]').forEach(checkbox => {
                        checkbox.addEventListener('change', function() {
                            // Prevent unchecking primary image
                            const imageId = this.value;
                            const primaryRadio = document.querySelector(
                                `input[name="primary_image"][value="${imageId}"]`);
                            if (primaryRadio && primaryRadio.checked && !this.checked) {
                                alert(
                                    'Anda tidak dapat menghapus foto utama. Pilih foto utama yang lain terlebih dahulu.'
                                );
                                this.checked = true;
                            }
                        });
                    });

                    // Handle primary image selection
                    document.querySelectorAll('input[name="primary_image"]').forEach(radio => {
                        radio.addEventListener('change', function() {
                            // Ensure primary image checkbox is checked
                            const imageId = this.value;
                            const checkbox = document.querySelector(
                                `input[name="selected_images[]"][value="${imageId}"]`);
                            if (checkbox && !checkbox.checked) {
                                checkbox.checked = true;
                            }

                            // Update UI to show primary image
                            document.querySelectorAll('.bg-blue-600.absolute.top-2.left-2')
                                .forEach(span => {
                                    span.remove();
                                });

                            const imageContainer = this.closest('.relative.group');
                            const imgContainer = imageContainer.querySelector(
                                '.aspect-square');

                            const utamaSpan = document.createElement('span');
                            utamaSpan.className =
                                'absolute top-2 left-2 bg-blue-600 text-white text-xs px-2 py-0.5 rounded shadow';
                            utamaSpan.textContent = 'Utama';
                            imgContainer.appendChild(utamaSpan);
                        });
                    });
                @endif
            }, 100);
        });
    </script>
@endpush
