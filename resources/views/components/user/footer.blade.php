<footer class="bg-blue-700 text-white py-8">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
            <!-- Kolom 1: Logo dan Deskripsi -->
            <div>
                <h2 class="text-2xl font-semibold">Nusatawan.</h2>
                <p class="mt-2 text-sm">
                    Platform informasi wisata berbasis web dengan integrasi cuaca dan kontribusi pengguna.
                </p>
            </div>

            <!-- Kolom 2: Navigasi -->
            <div>
                <h3 class="text-lg font-semibold">Navigasi</h3>
                <ul class="mt-2 space-y-2">
                    <li><a href="{{ route('user.home') }}" class="hover:underline block">Beranda</a></li>
                    <li><a href="{{ route('user.destinations.index') }}" class="hover:underline block">Destinasi
                            Wisata</a></li>
                    <li><a href="{{ route('user.itinerary.index') }}" class="hover:underline block">Rencana
                            Perjalanan</a></li>
                    <li><a href="{{ route('user.destination-submission.create') }}"
                            class="hover:underline block">Kontribusi Wisata</a></li>
                    <li><a href="{{ route('user.about') }}" class="hover:underline block">Tentang Kami</a></li>
                    <li><a href="#" class="hover:underline block">Hubungi Kami</a></li>
                </ul>
            </div>

            <!-- Kolom 3: Teknologi -->
            <div>
                <h3 class="text-lg font-semibold">Teknologi</h3>
                <div class="mt-2 flex flex-col space-y-2 md:space-y-3">
                    <!-- Baris teknologi pertama -->
                    <div class="flex flex-wrap items-center">
                        <span class="text-xl mr-2">🚀</span>
                        <span class="font-semibold">Laravel, MySQL</span>
                    </div>

                    <!-- Baris teknologi kedua -->
                    <div class="flex flex-wrap items-center">
                        <span class="text-xl mr-2">🗺️</span>
                        <span class="font-semibold">OpenWeatherMap, OpenStreetMap, Nominatim, Leaflet</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="mt-8 text-center text-sm border-t border-white/20 pt-4">
            <p>© 2025 Nusatawan. Dibuat sebagai Proyek Skripsi.
                <a href="#" class="underline">Syarat & Ketentuan</a> |
                <a href="#" class="underline">Kebijakan Privasi</a>
            </p>
        </div>
    </div>
</footer>
