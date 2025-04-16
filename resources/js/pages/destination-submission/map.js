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
        L.latLng(6.1, 141.0) // Northeast
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
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            const latlng = L.latLng(lat, lng);

            if (indonesiaBounds.contains(latlng)) {
                mapObj.setView(latlng, 10);
                addMarker(lat, lng);
            } else {
                console.log("Lokasi pengguna di luar Indonesia.");
            }
        }, function() {
            console.log("Tidak bisa mendapatkan lokasi pengguna.");
        });
    }

    // Setup pencarian dengan autocomplete
    setupSearchWithAutocomplete();
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

// Fungsi untuk mengubah format lokasi ke bahasa Indonesia
function translateLocationName(name) {
    if (!name) return '';

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

// Fungsi untuk reverse geocoding
function reverseGeocode(lat, lng) {
    // Set parameter untuk prefer bahasa Indonesia
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=10&accept-language=id`)
        .then(response => response.json())
        .then(data => {
            console.log("Reverse geocode result:", data);

            // Coba ekstrak wilayah administratif dari hasil reverse geocoding
            let administrativeArea = '';

            // Coba beberapa kemungkinan lokasi dalam data
            if (data.address) {
                // Prioritas pencarian: city, town, county, municipality, state
                administrativeArea = data.address.city ||
                       data.address.town ||
                       data.address.county ||
                       data.address.municipality ||
                       data.address.state;

                // Terjemahkan nama lokasi ke bahasa Indonesia jika masih ada istilah Inggris
                administrativeArea = translateLocationName(administrativeArea);
            }

            // Isi field kota/kabupaten jika ditemukan
            if (administrativeArea) {
                document.getElementById('administrative_area').value = administrativeArea;
            }

            //  isi field provinsi jika ditemukan
            const province = data.address.state || data.address.province;
            
            if (province) {
                document.getElementById('province').value = province;
            }
        })
        .catch(error => {
            console.error("Error saat reverse geocoding:", error);
        });
}

// Fungsi untuk setup pencarian dengan autocomplete
function setupSearchWithAutocomplete() {
    const searchInput = document.getElementById('map-search');

    // Buat container untuk hasil autocomplete dan pastikan ditambahkan ke DOM
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

    let debounceTimer;

    // Menangani perubahan input untuk memicu autocomplete
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();

        // Hapus timer sebelumnya
        clearTimeout(debounceTimer);

        // Sembunyikan hasil jika input terlalu pendek
        if (query.length < 3) {
            autocompleteResults.style.display = 'none';
            return;
        }

        // Tunda API call untuk menghindari terlalu banyak request (debounce)
        debounceTimer = setTimeout(() => {
            console.log("Mencari:", query); // Debug log

            // Ambil hasil dari Nominatim dengan parameter bahasa Indonesia
            fetch(
                    `https://nominatim.openstreetmap.org/search?format=json&countrycodes=id&q=${encodeURIComponent(query)}&limit=5&accept-language=id`
                )
                .then(response => response.json())
                .then(data => {
                    console.log("Hasil pencarian:", data); // Debug log

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
                            // Terjemahkan nama lokasi yang masih dalam bahasa Inggris
                            item.textContent = translateLocationName(result.display_name);

                            // Efek hover
                            item.onmouseenter = function() {
                                this.style.backgroundColor = '#f3f4f6';
                            };
                            item.onmouseleave = function() {
                                this.style.backgroundColor = 'white';
                            };

                            // Tangani klik pada hasil
                            item.addEventListener('click', function() {
                                const lat = parseFloat(result.lat);
                                const lng = parseFloat(result.lon);

                                // Update input dengan lokasi terpilih yang sudah diterjemahkan
                                searchInput.value = translateLocationName(result.display_name);

                                // Pindahkan peta ke lokasi dan tambahkan marker
                                mapObj.setView([lat, lng], 15);
                                addMarker(lat, lng);

                                // Ekstrak dan isi data wilayah administratif dari hasil
                                let administrativeArea = '';

                                if (result.address) {
                                    // Prioritas pencarian: city, town, county, municipality, state
                                    administrativeArea = result.address.city ||
                                                       result.address.town ||
                                                       result.address.county ||
                                                       result.address.municipality ||
                                                       result.address.state;

                                    // Terjemahkan nama lokasi ke bahasa Indonesia
                                    administrativeArea = translateLocationName(administrativeArea);

                                    if (administrativeArea) {
                                        document.getElementById('city').value = administrativeArea;
                                    }
                                }

                                // Sembunyikan hasil
                                autocompleteResults.style.display = 'none';
                            });

                            autocompleteResults.appendChild(item);
                        });
                    } else {
                        // Tidak ada hasil ditemukan
                        const noResults = document.createElement('div');
                        noResults.style.padding = '8px';
                        noResults.style.color = '#6b7280';
                        noResults.textContent = 'Tidak ada lokasi ditemukan';
                        autocompleteResults.appendChild(noResults);
                        autocompleteResults.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error("Error pencarian:", error);
                    // Tampilkan error di dalam dropdown
                    autocompleteResults.innerHTML = '';
                    const errorEl = document.createElement('div');
                    errorEl.style.padding = '8px';
                    errorEl.style.color = '#dc2626';
                    errorEl.textContent = 'Terjadi kesalahan saat mencari lokasi';
                    autocompleteResults.appendChild(errorEl);
                    autocompleteResults.style.display = 'block';
                });
        }, 300); // Delay 300ms untuk debounce input
    });

    // Sembunyikan hasil ketika klik di luar
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !autocompleteResults.contains(e.target)) {
            autocompleteResults.style.display = 'none';
        }
    });

    // Pertahankan fungsi tombol Enter yang sudah ada
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const query = this.value.trim();

            if (query.length > 2) {
                fetch(
                        `https://nominatim.openstreetmap.org/search?format=json&countrycodes=id&q=${encodeURIComponent(query)}&limit=1&accept-language=id`
                    )
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            const result = data[0];
                            const lat = parseFloat(result.lat);
                            const lng = parseFloat(result.lon);

                            mapObj.setView([lat, lng], 15);
                            addMarker(lat, lng);

                            // Ekstrak dan isi data wilayah administratif dari hasil
                            let administrativeArea = '';

                            if (result.address) {
                                // Prioritas pencarian: city, town, county, municipality, state
                                administrativeArea = result.address.city ||
                                                   result.address.town ||
                                                   result.address.county ||
                                                   result.address.municipality ||
                                                   result.address.state;

                                // Terjemahkan nama lokasi ke bahasa Indonesia
                                administrativeArea = translateLocationName(administrativeArea);

                                if (administrativeArea) {
                                    document.getElementById('city').value = administrativeArea;
                                }
                            }

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
