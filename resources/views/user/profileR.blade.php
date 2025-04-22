{{-- <x-section>
    <div class="mt-14"></div>

    <!-- Profil Pengguna -->
    <div class="flex flex-col items-center text-center">
        <!-- Foto Profil (Klik untuk Ubah) -->
        <label for="photoInput" class="relative cursor-pointer group">
            <div
                class="w-24 h-24 rounded-full overflow-hidden border-4 border-gray-300 transition-all duration-300 group-hover:shadow-lg">
                <img id="profileImage" src="{{ $user->image ? asset($user->image) : asset('images/auth.png') }}"
                    alt="Profile Picture" class="w-full h-full object-cover">
            </div>
            <div
                class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 rounded-full transition-opacity duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z">
                    </path>
                </svg>
            </div>
            <input type="file" id="photoInput" name="photo" class="hidden" accept="image/*"
                onchange="previewImage(event)">
        </label>

        <h2 class="text-xl font-semibold mt-4">{{ $user->name }}</h2>
        <p class="text-gray-500">{{ $user->email }}</p>

        <!-- Status Badge -->
        <div class="mt-2">
            @if ($user->status == 'active')
                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Aktif</span>
            @elseif($user->status == 'inactive')
                <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Tidak Aktif</span>
            @elseif($user->status == 'banned')
                <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">Diblokir</span>
            @endif
        </div>
    </div>

    <!-- Statistik Singkat -->
    <div class="mt-6 grid grid-cols-3 gap-4 max-w-lg mx-auto">
        <div class="bg-white p-4 rounded-lg shadow text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $submissionCount }}</div>
            <div class="text-sm text-gray-500">Destinasi</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $likedCount }}</div>
            <div class="text-sm text-gray-500">Favorit</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $itineraryCount }}</div>
            <div class="text-sm text-gray-500">Itinerari</div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="mt-10 border-b border-gray-200" x-data="{ activeTab: 'profile' }">
        <nav class="-mb-px flex space-x-8">
            <button @click="activeTab = 'profile'"
                :class="{ 'border-blue-500 text-blue-600': activeTab === 'profile', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'profile' }"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Profil
            </button>
            <button @click="activeTab = 'submissions'"
                :class="{ 'border-blue-500 text-blue-600': activeTab === 'submissions', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'submissions' }"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Ajuan Destinasi
            </button>
            <button @click="activeTab = 'favorites'"
                :class="{ 'border-blue-500 text-blue-600': activeTab === 'favorites', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'favorites' }"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Favorit
            </button>
            <button @click="activeTab = 'itineraries'"
                :class="{ 'border-blue-500 text-blue-600': activeTab === 'itineraries', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'itineraries' }"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Itinerari
            </button>
        </nav>
    </div>

    <!-- Tab Contents -->
    <div class="mt-6">
        <!-- Tab: Profil -->
        <div x-show="activeTab === 'profile'">
            <!-- Form Edit Profil -->
            <div class="md:max-w-xl mx-auto">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Informasi Dasar -->
                    <div>
                        <label class="block text-gray-700">Nama</label>
                        <div class="relative">
                            <input type="text" name="name" value="{{ $user->name }}"
                                class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300">
                            <span class="absolute right-3 top-2 text-gray-400">
                                ✏️
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-700">Email</label>
                        <div class="relative">
                            <input type="email" name="email" value="{{ $user->email }}"
                                class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300">
                            <span class="absolute right-3 top-2 text-gray-400">
                                ✏️
                            </span>
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-gray-700">Password Baru (kosongkan jika tidak ingin mengubah)</label>
                        <div class="relative">
                            <input type="password" name="password"
                                class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300">
                            <span class="absolute right-3 top-2 text-gray-400">
                                🔒
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-700">Konfirmasi Password Baru</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation"
                                class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300">
                            <span class="absolute right-3 top-2 text-gray-400">
                                🔒
                            </span>
                        </div>
                    </div>

                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <button type="submit"
                        class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600 transition">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        <!-- Tab: Ajuan Destinasi -->
        <div x-show="activeTab === 'submissions'" class="space-y-6">
            <!-- Filter dan Sorting -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-4">
                <div class="mb-3 md:mb-0">
                    <h3 class="text-lg font-semibold">Destinasi Saya</h3>
                </div>
                <div class="flex space-x-2">
                    <select class="border rounded-md p-1 text-sm focus:outline-none focus:ring focus:ring-blue-300">
                        <option value="all">Semua Status</option>
                        <option value="pending">Menunggu</option>
                        <option value="approved">Disetujui</option>
                        <option value="rejected">Ditolak</option>
                    </select>
                    <select class="border rounded-md p-1 text-sm focus:outline-none focus:ring focus:ring-blue-300">
                        <option value="newest">Terbaru</option>
                        <option value="oldest">Terlama</option>
                    </select>
                </div>
            </div>

            <!-- Tombol Ajukan Destinasi Baru -->
            <div class="mb-6">
                <a href="{{ route('destinations.create') }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Ajukan Destinasi Baru
                </a>
            </div>

            <!-- Daftar Ajuan Destinasi -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($submissions as $submission)
                    <div class="bg-white rounded-md overflow-hidden shadow-md transition hover:shadow-lg">
                        <div class="relative">
                            @if ($submission->submissionImages->count() > 0)
                                <img src="{{ asset($submission->submissionImages[0]->url) }}"
                                    alt="{{ $submission->place_name }}" class="w-full h-40 object-cover">
                            @else
                                <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif

                            <!-- Status Badge -->
                            <div class="absolute top-2 right-2">
                                @if ($submission->status == 'pending')
                                    <span
                                        class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Menunggu</span>
                                @elseif($submission->status == 'approved')
                                    <span
                                        class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Disetujui</span>
                                @elseif($submission->status == 'rejected')
                                    <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">Ditolak</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold">{{ $submission->place_name }}</h3>
                            <p class="text-gray-600 text-sm mb-2">{{ $submission->province }}</p>
                            <div class="mb-3">
                                <span
                                    class="bg-amber-100 text-amber-700 text-xs px-2 py-1 rounded-full">{{ $submission->category->name }}</span>
                            </div>
                            <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ $submission->description }}</p>
                            <div class="flex space-x-2">
                                <a href="{{ route('submissions.show', $submission->id) }}"
                                    class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 rounded text-sm text-center transition">
                                    Detail
                                </a>
                                <a href="{{ route('submissions.edit', $submission->id) }}"
                                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-3 rounded text-sm transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-10 bg-gray-50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-3"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        <p class="text-gray-500">Anda belum mengajukan destinasi apapun</p>
                        <a href="{{ route('destinations.create') }}"
                            class="mt-3 inline-block bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
                            Ajukan Destinasi Sekarang
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($submissions->count() > 0)
                <div class="mt-6">
                    {{ $submissions->links() }}
                </div>
            @endif
        </div>

        <!-- Tab: Favorit -->
        <div x-show="activeTab === 'favorites'" class="space-y-6">
            <h3 class="text-lg font-semibold">Destinasi Favorit</h3>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($likedDestinations as $destination)
                    <div class="bg-white rounded-md overflow-hidden shadow-md transition hover:shadow-lg">
                        <div class="relative">
                            @if ($destination->destinationImages->count() > 0)
                                <img src="{{ asset($destination->destinationImages->where('is_primary', true)->first()->url ?? $destination->destinationImages[0]->url) }}"
                                    alt="{{ $destination->place_name }}" class="w-full h-40 object-cover">
                            @else
                                <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <div class="flex justify-between items-center mb-1">
                                <h3 class="font-semibold">{{ $destination->place_name }}</h3>
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-700 mr-2">{{ $destination->rating }}</span>
                                    <form action="{{ route('destinations.unlike', $destination->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-500 hover:text-gray-500 focus:outline-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm mb-2">{{ $destination->province }}</p>
                            <div class="mb-3">
                                <span
                                    class="bg-amber-100 text-amber-700 text-xs px-2 py-1 rounded-full">{{ $destination->category->name }}</span>
                            </div>
                            <div class="flex items-center mb-3">
                                <span class="text-yellow-500 mr-1">★</span>
                                <span class="text-sm text-gray-600">{{ $destination->rating }}
                                    ({{ $destination->rating_count }} ulasan)
                                </span>
                                <span class="mx-2 text-gray-300">•</span>
                                <span class="text-sm text-gray-600">{{ $destination->time_minutes }} menit</span>
                            </div>
                            <a href="{{ route('destinations.show', $destination->slug) }}"
                                class="block w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded text-sm text-center transition">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-10 bg-gray-50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-3"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <p class="text-gray-500">Anda belum memiliki destinasi favorit</p>
                        <a href="{{ route('destinations.index') }}"
                            class="mt-3 inline-block bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
                            Jelajahi Destinasi
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($likedDestinations->count() > 0)
                <div class="mt-6">
                    {{ $likedDestinations->links() }}
                </div>
            @endif
        </div>

        <!-- Tab: Itinerari -->
        <div x-show="activeTab === 'itineraries'" class="space-y-6">
            <!-- Header dengan Tombol Tambah -->
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold">Itinerari Perjalanan</h3>
                <a href="{{ route('itineraries.create') }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Buat Itinerari
                </a>
            </div>

            <!-- Daftar Itinerari -->
            <div class="space-y-4">
                @forelse($itineraries as $itinerary)
                    <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-medium text-lg">{{ $itinerary->title }}</h4>
                                <div class="flex items-center text-sm text-gray-500 mt-1">
                                    <span>{{ \Carbon\Carbon::parse($itinerary->startDate)->format('d M Y') }} -
                                        {{ \Carbon\Carbon::parse($itinerary->endDate)->format('d M Y') }}</span>
                                    <span class="mx-2">•</span>
                                    <span>
                                        @if ($itinerary->status == 'complete')
                                            <span class="text-green-500">Selesai</span>
                                        @else
                                            <span class="text-blue-500">Sedang Berlangsung</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div>
                                @if ($itinerary->status == 'ongoing')
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">Sedang
                                        Berlangsung</span>
                                @else
                                    <span
                                        class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Selesai</span>
                                @endif
                            </div>
                        </div>

                        <!-- Destinasi dalam Itinerari -->
                        <div class="mt-4">
                            <div class="text-sm font-medium text-gray-700 mb-2">Destinasi:</div>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($itinerary->destinations as $destination)
                                    <span
                                        class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full">{{ $destination->place_name }}</span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-4 flex space-x-2">
                            <a href="{{ route('itineraries.show', $itinerary->id) }}"
                                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 rounded text-sm text-center transition">
                                Lihat Detail
                            </a>
                            <a href="{{ route('itineraries.edit', $itinerary->id) }}"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-3 rounded text-sm transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </a>
                            <form action="{{ route('itineraries.destroy', $itinerary->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-100 hover:bg-red-200 text-red-600 py-2 px-3 rounded text-sm transition"
                                    onclick="return confirm('Yakin ingin menghapus itinerari ini?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 bg-gray-50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-3"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <p class="text-gray-500">Anda belum membuat itinerari perjalanan</p>
                        <a href="{{ route('itineraries.create') }}"
                            class="mt-3
                        <a href="{{ route('itineraries.create') }}"
                            class="mt-3 inline-block bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
                            Buat Itinerari Sekarang
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($itineraries->count() > 0)
                <div class="mt-6">
                    {{ $itineraries->links() }}
                </div>
            @endif
        </div>
    </div>
</x-section>


<!-- Script untuk Alpine.js dan preview gambar -->
<script>
    // Preview gambar profil saat diunggah
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const profileImage = document.getElementById('profileImage');
            profileImage.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script> --}}


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Tab dengan Alpine.js</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md" x-data="{ activeTab: 'profile' }">
        <h1 class="text-2xl font-bold mb-6">Informasi Pengguna</h1>

        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-6">
                <a @click.prevent="activeTab = 'profile'"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'profile', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'profile' }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm cursor-pointer">
                    Profil
                </a>
                <a @click.prevent="activeTab = 'destinations'"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'destinations', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'destinations' }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm cursor-pointer">
                    Destinasi Saya
                </a>
                <a @click.prevent="activeTab = 'favorites'"
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'favorites', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'favorites' }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm cursor-pointer">
                    Favorit
                </a>
            </nav>
        </div>

        <!-- Tab Content -->
        <!-- Profile Content -->
        <div x-show="activeTab === 'profile'" class="py-4">
            <h2 class="text-xl font-semibold mb-4">Profil Saya</h2>
            <form>
                <!-- Form fields -->
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2" for="name">Nama</label>
                    <input type="text" id="name"
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Masukkan nama Anda">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2" for="email">Email</label>
                    <input type="email" id="email"
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Masukkan email Anda">
                </div>
                <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Simpan
                    Perubahan</button>
            </form>
        </div>

        <!-- Destinations Content -->
        <div x-show="activeTab === 'destinations'" class="py-4">
            <h2 class="text-xl font-semibold mb-4">Destinasi Saya</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border rounded-md p-4">
                    <h3 class="font-medium">Bali</h3>
                    <p class="text-gray-600">Kunjungan terakhir: 12 Maret 2025</p>
                </div>
                <div class="border rounded-md p-4">
                    <h3 class="font-medium">Yogyakarta</h3>
                    <p class="text-gray-600">Kunjungan terakhir: 5 Januari 2025</p>
                </div>
            </div>
        </div>

        <!-- Favorites Content -->
        <div x-show="activeTab === 'favorites'" class="py-4">
            <h2 class="text-xl font-semibold mb-4">Destinasi Favorit</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="border rounded-md overflow-hidden">
                    <div class="h-40 bg-gray-300"></div>
                    <div class="p-3">
                        <h3 class="font-medium">Gunung Bromo</h3>
                        <p class="text-sm text-gray-600">Jawa Timur</p>
                    </div>
                </div>
                <div class="border rounded-md overflow-hidden">
                    <div class="h-40 bg-gray-300"></div>
                    <div class="p-3">
                        <h3 class="font-medium">Raja Ampat</h3>
                        <p class="text-sm text-gray-600">Papua Barat</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Script tambahan jika diperlukan
    </script>
</body>

</html>
