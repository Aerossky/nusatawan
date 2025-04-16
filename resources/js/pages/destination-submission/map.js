document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi peta
    initMap();
});

// Variabel untuk menyimpan data
let mapObj, markerObj;

// Fungsi untuk inisialisasi peta
function initMap() {
    // Koordinat default (Indonesia)
    const defaultLat = -6.200000;
    const defaultLng = 106.816666;

    // Batas wilayah Indonesia
    const indonesiaBounds = L.latLngBounds(
        L.latLng(-11.0, 95.0), // Southwest
        L.latLng(6.1, 141.0)   // Northeast
    );

    // Inisialisasi peta dengan batas
    mapObj = L.map('map', {
        maxBounds: indonesiaBounds,
        maxBoundsViscosity: 1.0
    }).setView([defaultLat, defaultLng], 5);

    // Tambahkan layer peta (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 18,
        minZoom: 4
    }).addTo(mapObj);

    // Tambahkan event click pada peta
    mapObj.on('click', function(e) {
        if (indonesiaBounds.contains(e.latlng)) {
            addMarker(e.latlng.lat, e.latlng.lng);
        } else {
            alert("Lokasi di luar wilayah Indonesia.");
        }
    });

    // Dapatkan lokasi pengguna
    getUserLocation(indonesiaBounds);

    // Setup pencarian dengan autocomplete
    setupSearchWithAutocomplete();
}

// Fungsi untuk mendapatkan lokasi pengguna
function getUserLocation(bounds) {
    if (!navigator.geolocation) {
        console.log("Geolocation tidak didukung browser ini.");
        return;
    }

    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            const latlng = L.latLng(lat, lng);

            if (bounds.contains(latlng)) {
                mapObj.setView(latlng, 10);
                addMarker(lat, lng);
            } else {
                console.log("Lokasi pengguna di luar Indonesia.");
            }
        },
        function(error) {
            console.log("Tidak bisa mendapatkan lokasi pengguna:", error.message);
        },
        {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0
        }
    );
}

// Fungsi untuk menambahkan marker
function addMarker(lat, lng) {
    // Hapus marker sebelumnya jika ada
    if (markerObj) {
        mapObj.removeLayer(markerObj);
    }

    // Tambahkan marker baru
    markerObj = L.marker([lat, lng]).addTo(mapObj);

    // Update nilai input latitude dan longitude
    document.getElementById('latitude').value = lat.toFixed(8);
    document.getElementById('longitude').value = lng.toFixed(8);

    // Tambahkan reverse geocoding untuk mendapatkan informasi kota
    reverseGeocode(lat, lng);
}

// Fungsi untuk membersihkan teks dari karakter non-latin
function cleanText(text) {
    if (!text) return '';

    // Hapus semua karakter non-latin dan non-spasi
    // Regex ini hanya menyimpan karakter latin (A-Z, a-z), angka, spasi dan beberapa tanda baca umum
    return text.replace(/[^\w\s.,;:!?"'()\-]/g, '').trim();
}

// Fungsi untuk mengubah format lokasi ke bahasa Indonesia
function translateLocationName(name) {
    if (!name) return '';

    // Bersihkan karakter non-latin terlebih dahulu
    name = cleanText(name);

    // Daftar kata bahasa Inggris yang umum dan terjemahannya
    const translations = {
        'east': 'Timur',
        'west': 'Barat',
        'north': 'Utara',
        'south': 'Selatan',
        'central': 'Tengah',
        'city': 'Kota',
        'regency': 'Kabupaten',
        'district': 'Kecamatan',
        'village': 'Desa',
        'province': 'Provinsi'
    };

    // Ganti kata-kata bahasa Inggris dengan bahasa Indonesia
    let result = name;
    Object.keys(translations).forEach(engWord => {
        // Case insensitive replacement
        const regex = new RegExp('\\b' + engWord + '\\b', 'gi');
        result = result.replace(regex, translations[engWord]);
    });

    return result;
}

// Cache untuk menyimpan hasil reverse geocoding
const geocodeCache = new Map();

// Fungsi untuk reverse geocoding dengan cache
function reverseGeocode(lat, lng) {
    // Round koordinat untuk efisiensi cache (6 desimal ≈ akurasi 10cm)
    const roundedLat = parseFloat(lat.toFixed(6));
    const roundedLng = parseFloat(lng.toFixed(6));
    const cacheKey = `${roundedLat},${roundedLng}`;

    // Periksa cache terlebih dahulu
    if (geocodeCache.has(cacheKey)) {
        const cachedData = geocodeCache.get(cacheKey);
        fillLocationFields(cachedData);
        return;
    }

    // Set parameter untuk prefer bahasa Indonesia
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=10&accept-language=id`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("Reverse geocode result:", data);

            // Simpan di cache
            geocodeCache.set(cacheKey, data);

            // Isi field lokasi
            fillLocationFields(data);
        })
        .catch(error => {
            console.error("Error saat reverse geocoding:", error);
        });
}

// Fungsi untuk mengisi field lokasi
function fillLocationFields(data) {
    if (!data || !data.address) return;

    // Coba ekstrak wilayah administratif dari hasil reverse geocoding
    const administrativeArea = cleanText(
        data.address.city ||
        data.address.town ||
        data.address.county ||
        data.address.municipality ||
        data.address.state ||
        ''
    );

    // Isi field kota/kabupaten jika ditemukan
    if (administrativeArea) {
        const translatedArea = translateLocationName(administrativeArea);
        document.getElementById('administrative_area').value = translatedArea;
    }

    // Isi field provinsi jika ditemukan
    const province = cleanText(data.address.state || data.address.province || '');

    if (province) {
        document.getElementById('province').value = translateLocationName(province);
    }
}

// Debounce function untuk mengurangi API calls
function debounce(func, wait) {
    let timeout;
    return function(...args) {
        const context = this;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), wait);
    };
}

// Fungsi untuk setup pencarian dengan autocomplete
function setupSearchWithAutocomplete() {
    const searchInput = document.getElementById('map-search');

    // Buat container untuk hasil autocomplete
    const autocompleteResults = document.createElement('div');
    autocompleteResults.id = 'autocomplete-results';
    autocompleteResults.style.position = 'absolute';
    autocompleteResults.style.zIndex = '1000';
    autocompleteResults.style.width = '100%';
    autocompleteResults.style.maxHeight = '240px';
    autocompleteResults.style.overflowY = 'auto';
    autocompleteResults.style.backgroundColor = 'white';
    autocompleteResults.style.border = '1px solid #ccc';
    autocompleteResults.style.borderRadius = '0.375rem';
    autocompleteResults.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1)';
    autocompleteResults.style.display = 'none'; // Sembunyikan di awal

    // Tambahkan elemen ke DOM
    searchInput.parentNode.appendChild(autocompleteResults);

    // Cache untuk hasil pencarian
    const searchCache = new Map();

    // Fungsi pencarian dengan debounce
    const performSearch = debounce(function(query) {
        console.log("Mencari:", query);

        // Periksa cache terlebih dahulu
        if (searchCache.has(query)) {
            displayResults(searchCache.get(query));
            return;
        }

        // Ambil hasil dari Nominatim dengan parameter bahasa Indonesia
        fetch(`https://nominatim.openstreetmap.org/search?format=json&countrycodes=id&q=${encodeURIComponent(query)}&limit=5&accept-language=id`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log("Hasil pencarian:", data);

                // Simpan di cache
                searchCache.set(query, data);

                // Tampilkan hasil
                displayResults(data);
            })
            .catch(error => {
                console.error("Error pencarian:", error);
                showError("Terjadi kesalahan saat mencari lokasi");
            });
    }, 300);

    // Fungsi untuk menampilkan hasil pencarian
    function displayResults(data) {
        // Bersihkan hasil sebelumnya
        autocompleteResults.innerHTML = '';

        if (data && data.length > 0) {
            // Tampilkan container hasil
            autocompleteResults.style.display = 'block';

            // Tambahkan setiap hasil ke dropdown
            data.forEach(result => {
                const item = document.createElement('div');
                item.style.padding = '8px';
                item.style.cursor = 'pointer';

                // Bersihkan nama lokasi dari karakter non-latin dan terjemahkan
                const cleanDisplayName = cleanText(result.display_name);
                item.textContent = translateLocationName(cleanDisplayName);

                // Efek hover
                item.onmouseenter = () => item.style.backgroundColor = '#f3f4f6';
                item.onmouseleave = () => item.style.backgroundColor = 'white';

                // Tangani klik pada hasil
                item.addEventListener('click', function() {
                    const lat = parseFloat(result.lat);
                    const lng = parseFloat(result.lon);

                    // Update input dengan lokasi terpilih yang sudah dibersihkan dan diterjemahkan
                    searchInput.value = translateLocationName(cleanText(result.display_name));

                    // Pindahkan peta ke lokasi dan tambahkan marker
                    mapObj.setView([lat, lng], 15);
                    addMarker(lat, lng);

                    // Sembunyikan hasil
                    autocompleteResults.style.display = 'none';
                });

                autocompleteResults.appendChild(item);
            });
        } else {
            // Tidak ada hasil ditemukan
            showNoResults();
        }
    }

    // Fungsi untuk menampilkan pesan tidak ada hasil
    function showNoResults() {
        autocompleteResults.innerHTML = '';
        const noResults = document.createElement('div');
        noResults.style.padding = '8px';
        noResults.style.color = '#6b7280';
        noResults.textContent = 'Tidak ada lokasi ditemukan';
        autocompleteResults.appendChild(noResults);
        autocompleteResults.style.display = 'block';
    }

    // Fungsi untuk menampilkan pesan error
    function showError(message) {
        autocompleteResults.innerHTML = '';
        const errorEl = document.createElement('div');
        errorEl.style.padding = '8px';
        errorEl.style.color = '#dc2626';
        errorEl.textContent = message;
        autocompleteResults.appendChild(errorEl);
        autocompleteResults.style.display = 'block';
    }

    // Handle input event with debounce
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();

        // Sembunyikan hasil jika input terlalu pendek
        if (query.length < 3) {
            autocompleteResults.style.display = 'none';
            return;
        }

        performSearch(query);
    });

    // Sembunyikan hasil ketika klik di luar
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !autocompleteResults.contains(e.target)) {
            autocompleteResults.style.display = 'none';
        }
    });

    // Handle tombol Enter
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const query = this.value.trim();

            if (query.length > 2) {
                fetch(`https://nominatim.openstreetmap.org/search?format=json&countrycodes=id&q=${encodeURIComponent(query)}&limit=1&accept-language=id`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.length > 0) {
                            const result = data[0];
                            const lat = parseFloat(result.lat);
                            const lng = parseFloat(result.lon);

                            // Update input dengan lokasi terpilih yang sudah dibersihkan
                            searchInput.value = translateLocationName(cleanText(result.display_name));

                            // Pindahkan peta dan tambahkan marker
                            mapObj.setView([lat, lng], 15);
                            addMarker(lat, lng);

                            // Sembunyikan hasil
                            autocompleteResults.style.display = 'none';
                        } else {
                            alert('Lokasi tidak ditemukan. Silakan coba kata kunci lain.');
                        }
                    })
                    .catch(error => {
                        console.error("Error pencarian:", error);
                        alert('Terjadi kesalahan saat mencari lokasi. Silakan coba lagi.');
                    });
            }
        }
    });
}
