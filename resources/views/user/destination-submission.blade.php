@extends('layouts.user')
@section('title', 'Pengajuan Destinasi')

@push('styles')
    <!-- CSS Leaflet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    @vite(['resources/css/custom/map.css'])
    <style>
        /* Progress steps styling */
        .step-progress {
            display: flex;
            margin-bottom: 2rem;
            position: relative;
        }

        .step-progress::before {
            content: '';
            position: absolute;
            height: 2px;
            width: 100%;
            background: #e5e7eb;
            top: 15px;
            z-index: 1;
        }

        .step {
            flex: 1;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #e5e7eb;
            color: #6b7280;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .step.active .step-number {
            background-color: #3b82f6;
            color: white;
        }

        .step.completed .step-number {
            background-color: #10b981;
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Image upload styling */
        .upload-area {
            position: relative;
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            padding: 2rem;
            transition: all 0.3s ease;
            background-color: #f8fafc;
        }

        .upload-area.drag-over {
            background-color: #eff6ff;
            border-color: #3b82f6;
        }

        .image-preview-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
        }

        .image-preview-item .delete-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(239, 68, 68, 0.8);
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .image-preview-item:hover .delete-btn {
            opacity: 1;
        }

        /* Enhanced field focus */
        input:focus,
        select:focus,
        textarea:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }

        /* Tooltip styling */
        .tooltip {
            position: relative;
            display: inline-block;
        }

        .tooltip .tooltip-text {
            visibility: hidden;
            width: 200px;
            background-color: #374151;
            color: #fff;
            text-align: left;
            border-radius: 6px;
            padding: 8px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 0.75rem;
            line-height: 1.25rem;
        }

        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
    </style>
@endpush

@section('content')
    <div class="bg-white mt-14">
        <x-section>
            <!-- Header -->
            <div class="mb-6 border-l-4 border-blue-600 pl-3">
                <h1 class="text-2xl font-bold text-gray-800">Pengajuan Destinasi</h1>
                <p class="text-gray-600 text-sm mt-1">Ajukan destinasi wisata baru untuk dikunjungi oleh wisatawan lainnya
                </p>
            </div>

            <!-- Alert messages -->
            @if (session('success'))
                <div class="mb-4">
                    <x-ui.alert type="success" :message="session('success')" />
                </div>
            @elseif (session('error'))
                <div class="mb-4">
                    <x-ui.alert type="error" :message="session('error')" />
                </div>
            @endif

            <!-- Progress Steps -->
            <div class="step-progress mb-8">
                <div class="step active" data-step="1">
                    <div class="step-number">1</div>
                    <div class="step-label text-sm font-medium">Informasi Dasar</div>
                </div>
                <div class="step" data-step="2">
                    <div class="step-number">2</div>
                    <div class="step-label text-sm font-medium">Lokasi</div>
                </div>
                <div class="step" data-step="3">
                    <div class="step-number">3</div>
                    <div class="step-label text-sm font-medium">Foto</div>
                </div>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 text-red-500 p-3 rounded-lg mb-5 border-l-4 border-red-500 text-sm">
                    <p class="font-medium mb-2">Mohon perbaiki kesalahan berikut:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('destination-submission.store') }}" method="POST" enctype="multipart/form-data"
                id="destinationForm">
                @csrf
                @method('POST')

                <!-- Informasi Dasar Tab -->
                <div class="tab-content active" id="tab-1">
                    <div class="mb-6">
                        <div class="mb-4 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            <h2 class="text-lg font-semibold text-gray-800 border-b pb-2 flex-grow">Informasi Dasar</h2>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="place_name"
                                    class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                    Nama Destinasi
                                    <div class="tooltip ml-1">
                                        <i class="fas fa-question-circle text-gray-400 text-xs"></i>
                                        <span class="tooltip-text">Masukkan nama lengkap destinasi yang jelas dan mudah
                                            dikenali</span>
                                    </div>
                                </label>
                                <input type="text" name="place_name" id="place_name" value="{{ old('place_name') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition"
                                    placeholder="Contoh: Pantai Kuta Bali">
                                @error('place_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="category_id"
                                        class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                        Kategori
                                        <div class="tooltip ml-1">
                                            <i class="fas fa-question-circle text-gray-400 text-xs"></i>
                                            <span class="tooltip-text">Pilih kategori yang paling sesuai dengan destinasi
                                                Anda</span>
                                        </div>
                                    </label>
                                    <select name="category_id" id="category_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition"
                                        required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="time_minutes"
                                        class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                        Durasi Kunjungan (menit)
                                        <div class="tooltip ml-1">
                                            <i class="fas fa-question-circle text-gray-400 text-xs"></i>
                                            <span class="tooltip-text">Perkiraan waktu yang dibutuhkan untuk mengunjungi
                                                tempat ini</span>
                                        </div>
                                    </label>
                                    <input type="number" name="time_minutes" id="time_minutes"
                                        value="{{ old('time_minutes') }}" placeholder="Contoh: 60"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition"
                                        min="0" required>
                                    @error('time_minutes')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="best_visit_time"
                                    class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                    Waktu Terbaik untuk Berkunjung
                                    <div class="tooltip ml-1">
                                        <i class="fas fa-question-circle text-gray-400 text-xs"></i>
                                        <span class="tooltip-text">Jam atau periode terbaik untuk mengunjungi destinasi
                                            ini</span>
                                    </div>
                                </label>
                                <input type="text" name="best_visit_time" id="best_visit_time"
                                    value="{{ old('best_visit_time') }}" placeholder="Contoh: Pagi 06:00 - 09:00"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition"
                                    required>
                                @error('best_visit_time')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description"
                                    class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                    Deskripsi
                                    <div class="tooltip ml-1">
                                        <i class="fas fa-question-circle text-gray-400 text-xs"></i>
                                        <span class="tooltip-text">Ceritakan secara detail tentang destinasi ini, apa yang
                                            menarik, dan pengalaman yang bisa didapat</span>
                                    </div>
                                </label>
                                <textarea name="description" id="description" rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition"
                                    placeholder="Ceritakan tentang destinasi ini..." required>{{ old('description') }}</textarea>
                                <div class="flex justify-between mt-1">
                                    <p class="text-xs text-gray-500">Minimum 100 karakter</p>
                                    <p class="text-xs text-gray-500"><span id="char-count">0</span>/2000 karakter</p>
                                </div>
                                @error('description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Tips untuk tab Informasi Dasar -->
                    <div class="mb-6">
                        <div class="bg-blue-50 rounded-lg p-3">
                            <div class="flex items-center text-blue-700 font-medium">
                                <i class="fas fa-lightbulb mr-2"></i>
                                <h3>Tips Informasi Dasar</h3>
                            </div>
                            <div class="mt-2 pl-6 text-sm text-blue-700 space-y-2">
                                <p>• Berikan nama destinasi yang jelas dan mudah dikenali</p>
                                <p>• Pilih kategori yang tepat agar wisatawan mudah menemukan destinasi Anda</p>
                                <p>• Tulis deskripsi yang mendetail tentang keunikan destinasi</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="button"
                            class="next-tab px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                            data-next="2">
                            Selanjutnya <i class="fas fa-arrow-right ml-1"></i>
                        </button>
                    </div>
                </div>

                <!-- Lokasi Tab -->
                <div class="tab-content" id="tab-2">
                    <div class="mb-6">
                        <div class="mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
                            <h2 class="text-lg font-semibold text-gray-800 border-b pb-2 flex-grow">Lokasi Destinasi</h2>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1" for="map-search">
                                    Cari Lokasi
                                </label>
                                <div class="flex">
                                    <input type="text" id="map-search"
                                        placeholder="Cth: Jembatan Barelang Batam, Pantai Kuta Bali..."
                                        class="flex-grow px-3 py-2 border border-gray-300 rounded-l-lg focus:ring-blue-500 focus:border-blue-500 transition"
                                        value="{{ old('map-search') }}">
                                    <button type="button" id="use-my-location"
                                        class="bg-gray-100 text-gray-700 px-3 py-2 border border-gray-300 border-l-0 rounded-r-lg hover:bg-gray-200 transition">
                                        <i class="fas fa-crosshairs"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1" for="map">
                                    Klik pada peta untuk menentukan lokasi yang tepat
                                </label>
                                <div id="map" class="w-full h-80 rounded-lg border border-gray-300 shadow-sm"></div>

                                <!-- Map controls -->
                                <div class="flex items-center justify-end mt-1 space-x-2">
                                    <button type="button" id="zoom-in"
                                        class="p-1 bg-white border border-gray-300 rounded shadow-sm">
                                        <i class="fas fa-plus text-gray-700"></i>
                                    </button>
                                    <button type="button" id="zoom-out"
                                        class="p-1 bg-white border border-gray-300 rounded shadow-sm">
                                        <i class="fas fa-minus text-gray-700"></i>
                                    </button>
                                    <button type="button" id="reset-map"
                                        class="p-1 bg-white border border-gray-300 rounded shadow-sm">
                                        <i class="fas fa-redo-alt text-gray-700"></i>
                                    </button>
                                </div>

                                <!-- Selected location card -->
                                <div id="selected-location-card"
                                    class="mt-3 bg-blue-50 p-3 rounded-lg border border-blue-200 hidden">
                                    <div class="flex items-start">
                                        <i class="fas fa-map-pin text-blue-600 mt-1 mr-2"></i>
                                        <div>
                                            <h4 class="font-medium text-blue-800" id="selected-location-name">Lokasi
                                                Terpilih</h4>
                                            <p class="text-sm text-blue-600" id="selected-location-address">Alamat akan
                                                muncul di sini</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">
                                        Latitude
                                    </label>
                                    <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50 text-sm transition"
                                        readonly required>
                                    @error('latitude')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">
                                        Longitude
                                    </label>
                                    <input type="text" name="longitude" id="longitude"
                                        value="{{ old('longitude') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50 text-sm transition"
                                        readonly required>
                                    @error('longitude')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label for="administrative_area" class="block text-sm font-medium text-gray-700 mb-1">
                                        Kota/Kabupaten
                                    </label>
                                    <input type="text" name="administrative_area" id="administrative_area"
                                        value="{{ old('administrative_area') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition">
                                    @error('administrative_area')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="province" class="block text-sm font-medium text-gray-700 mb-1">
                                        Provinsi
                                    </label>
                                    <input type="text" name="province" id="province" value="{{ old('province') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition">
                                    @error('province')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tips untuk tab Lokasi -->
                    <div class="mb-6">
                        <div class="bg-blue-50 rounded-lg p-3">
                            <div class="flex items-center text-blue-700 font-medium">
                                <i class="fas fa-lightbulb mr-2"></i>
                                <h3>Tips Lokasi</h3>
                            </div>
                            <div class="mt-2 pl-6 text-sm text-blue-700 space-y-2">
                                <p>• Gunakan tombol "Gunakan Lokasi Saya" untuk mengisi lokasi saat ini</p>
                                <p>• Zoom in pada peta untuk menentukan lokasi dengan lebih tepat</p>
                                <p>• Pastikan penanda berada tepat di lokasi destinasi untuk memudahkan wisatawan</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <button type="button"
                            class="prev-tab px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition"
                            data-prev="1">
                            <i class="fas fa-arrow-left mr-1"></i> Sebelumnya
                        </button>
                        <button type="button"
                            class="next-tab px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                            data-next="3">
                            Selanjutnya <i class="fas fa-arrow-right ml-1"></i>
                        </button>
                    </div>
                </div>

                <!-- Foto Tab -->
                <div class="tab-content" id="tab-3">
                    <div class="mb-6">
                        <div class="mb-4 flex items-center">
                            <i class="fas fa-images text-blue-600 mr-2"></i>
                            <h2 class="text-lg font-semibold text-gray-800 border-b pb-2 flex-grow">Foto Destinasi</h2>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center"
                                    for="images">
                                    Unggah foto destinasi (maksimal 5 foto)
                                    <div class="tooltip ml-1">
                                        <i class="fas fa-question-circle text-gray-400 text-xs"></i>
                                        <span class="tooltip-text">Upload foto dengan kualitas baik, jelas, dan menampilkan
                                            destinasi secara representatif</span>
                                    </div>
                                </label>

                                <!-- Contoh foto ideal -->
                                <div class="mb-4 bg-gray-50 p-3 rounded-lg border border-gray-200">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-1"></i> Contoh Foto Ideal:
                                    </h4>
                                    <div class="grid grid-cols-3 gap-2">
                                        <div class="aspect-w-16 aspect-h-9 bg-gray-200 rounded overflow-hidden">
                                            <img src="/assets/images/example-destination-1.jpg"
                                                alt="Contoh foto destinasi yang baik" class="object-cover w-full h-full">
                                        </div>
                                        <div class="aspect-w-16 aspect-h-9 bg-gray-200 rounded overflow-hidden">
                                            <img src="/assets/images/example-destination-2.jpg"
                                                alt="Contoh foto destinasi yang baik" class="object-cover w-full h-full">
                                        </div>
                                        <div class="aspect-w-16 aspect-h-9 bg-gray-200 rounded overflow-hidden">
                                            <img src="/assets/images/example-destination-3.jpg"
                                                alt="Contoh foto destinasi yang baik" class="object-cover w-full h-full">
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">Foto yang jelas, terang, dan menampilkan objek
                                        utama destinasi akan meningkatkan peluang disetujui</p>
                                </div>

                                <!-- Upload area -->
                                <div id="upload-area" class="upload-area">
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                            viewBox="0 0 48 48" aria-hidden="true">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex flex-col items-center mt-4">
                                            <p class="text-gray-600 mb-2">Tarik dan lepas foto ke sini, atau</p>
                                            <label for="images"
                                                class="relative cursor-pointer bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition">
                                                <span>Pilih Foto</span>
                                                <input id="images" name="images[]" type="file" multiple
                                                    accept="image/*" class="sr-only"
                                                    {{ old('images') ? '' : 'required' }}>
                                            </label>
                                            <p class="text-xs text-gray-500 mt-2">PNG, JPG, GIF hingga 5MB per file</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <div id="error-images" class="text-red-500 text-xs hidden">Tidak boleh lebih dari 5
                                        foto</div>
                                    <div class="flex justify-between items-center">
                                        <div id="images-counter" class="text-sm text-gray-600">0/5 foto dipilih</div>
                                        <button type="button" id="clear-images"
                                            class="text-sm text-red-600 hover:text-red-800 hidden transition">
                                            <i class="fas fa-trash-alt mr-1"></i> Hapus Semua
                                        </button>
                                    </div>

                                    <!-- Image preview grid -->
                                    <div id="image-preview" class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-3"></div>
                                </div>

                                {{-- Hidden inputs untuk old images --}}
                                @if (old('temp_images'))
                                    @foreach (old('temp_images') as $index => $image)
                                        <input type="hidden" name="old_images[]" value="{{ $image }}"
                                            data-id="{{ $index }}">
                                    @endforeach
                                @endif

                                @error('images')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                @error('images.*')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Tips untuk tab Foto -->
                    <div class="mb-6">
                        <div class="bg-blue-50 rounded-lg p-3">
                            <div class="flex items-center text-blue-700 font-medium">
                                <i class="fas fa-lightbulb mr-2"></i>
                                <h3>Tips Foto Destinasi</h3>
                            </div>
                            <div class="mt-2 pl-6 text-sm text-blue-700 space-y-2">
                                <p>• Upload minimal 3 foto untuk menampilkan destinasi dari berbagai sudut</p>
                                <p>• Gunakan foto dengan pencahayaan yang baik dan resolusi tinggi</p>
                                <p>• Tampilkan keunikan atau daya tarik utama destinasi</p>
                                <p>• Hindari foto dengan watermark atau teks yang mengganggu</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <button type="button"
                            class="prev-tab px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition"
                            data-prev="2">
                            <i class="fas fa-arrow-left mr-1"></i> Sebelumnya
                        </button>
                        <button type="submit" id="submit-form"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            <i class="fas fa-check mr-1"></i> Ajukan Destinasi
                        </button>
                    </div>
                </div>
            </form>
        </x-section>
    </div>
@endsection

@push('scripts')
    <!-- Script Leaflet -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>

    @vite(['resources/js/pages/destination-submission/map.js', 'resources/js/pages/destination-submission/index.js'])
@endpush
