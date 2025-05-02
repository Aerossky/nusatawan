class LocationSearch {
    /**
     * Initialize the location search component
     * @param {Object} options - Configuration options
     * @param {string} options.inputId - ID of the search input element
     * @param {string} options.resultsContainerId - ID of the container for displaying search results
     * @param {string} options.indicatorId - ID of the loading indicator element (optional)
     * @param {string} options.latInputId - ID of the hidden input for latitude (optional)
     * @param {string} options.lngInputId - ID of the hidden input for longitude (optional)
     * @param {string} options.countryCode - Country code to limit search results (default: 'id' for Indonesia)
     * @param {string} options.language - Language for search results (default: 'id' for Indonesian)
     * @param {Function} options.onLocationSelect - Callback when a location is selected (optional)
     * @param {string} options.dbSearchUrl - URL for database search API (optional)
     * @param {number} options.searchDebounceTime - Debounce time in ms for search input (default: 300)
     */
    constructor(options) {
        // Required options
        this.inputElement = document.getElementById(options.inputId);
        this.resultsContainer = document.getElementById(options.resultsContainerId);

        // Optional options with defaults
        this.searchIndicator = options.indicatorId ? document.getElementById(options.indicatorId) : null;
        this.latInput = options.latInputId ? document.getElementById(options.latInputId) : null;
        this.lngInput = options.lngInputId ? document.getElementById(options.lngInputId) : null;
        this.countryCode = options.countryCode || 'id';
        this.language = options.language || 'id';
        this.onLocationSelect = options.onLocationSelect || null;
        this.dbSearchUrl = options.dbSearchUrl || null;
        this.searchDebounceTime = options.searchDebounceTime || 300;

        // Internal variables
        this.searchTimeout = null;
        this.selectedLocation = null;
        this.dbSearchResults = [];
        this.nominatimResults = [];

        // Initialize if required elements exist
        if (this.inputElement && this.resultsContainer) {
            this.init();
        } else {
            console.error('LocationSearch: Required elements not found');
        }
    }

    /**
     * Initialize event listeners
     */
    init() {
        // Setup input event listener for autocomplete
        this.inputElement.addEventListener('input', this.handleSearchInput.bind(this));

        // Hide search results when clicking outside
        document.addEventListener('click', (e) => {
            if (!this.inputElement.contains(e.target) && !this.resultsContainer.contains(e.target)) {
                this.hideResults();
            }
        });
    }

    /**
     * Handle search input with debounce
     */
    handleSearchInput() {
        // Reset selected location if user changes the input
        if (this.selectedLocation && this.inputElement.value !== this.selectedLocation.formatted_name) {
            this.clearSelectedLocation();
        }

        // Clear previous timeout
        if (this.searchTimeout) {
            clearTimeout(this.searchTimeout);
        }

        const query = this.inputElement.value.trim();

        // Hide results if query is too short
        if (query.length < 2) {
            this.hideResults();
            return;
        }

        // Show loading indicator
        this.showLoadingIndicator();

        // Show search results container
        this.showResults();
        this.resultsContainer.innerHTML = '<div class="p-3 text-gray-500 text-sm">Mencari lokasi...</div>';

        // Set debounce timeout
        this.searchTimeout = setTimeout(() => {
            // Reset results
            this.dbSearchResults = [];
            this.nominatimResults = [];

            // Perform both searches
            const nominatimPromise = this.searchLocationsByNominatim(query);
            const dbPromise = this.dbSearchUrl ? this.searchLocationsInDatabase(query) : Promise.resolve([]);

            // Wait for both searches to complete
            Promise.all([nominatimPromise, dbPromise])
                .then(() => {
                    // Hide loading indicator
                    this.hideLoadingIndicator();

                    // Display combined results
                    this.displayCombinedSearchResults();
                })
                .catch(error => {
                    // Hide loading indicator
                    this.hideLoadingIndicator();

                    console.error("Error during search:", error);
                    this.resultsContainer.innerHTML = '<div class="p-3 text-red-500 text-sm">Terjadi kesalahan saat mencari lokasi</div>';
                });
        }, this.searchDebounceTime);
    }

    /**
     * Search locations using Nominatim API
     * @param {string} query - Search query
     * @returns {Promise} - Promise that resolves when search is complete
     */
    searchLocationsByNominatim(query) {
        return fetch(
            `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=${this.countryCode}&limit=5&accept-language=${this.language}`
        )
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Store results
            this.nominatimResults = data;
        })
        .catch(error => {
            console.error("Error in Nominatim search:", error);
            this.nominatimResults = [];
        });
    }

    /**
     * Search locations in database
     * @param {string} query - Search query
     * @returns {Promise} - Promise that resolves when search is complete
     */
    searchLocationsInDatabase(query) {
        if (!this.dbSearchUrl) {
            return Promise.resolve([]);
        }

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        return fetch(this.dbSearchUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                query: query
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Store results
            if (data.status === 'success' && Array.isArray(data.destinations)) {
                this.dbSearchResults = data.destinations;
            } else {
                this.dbSearchResults = [];
            }
        })
        .catch(error => {
            console.error("Error in database search:", error);
            this.dbSearchResults = [];
        });
    }

    /**
     * Display combined search results from both sources
     */
    displayCombinedSearchResults() {
        // Clear previous results
        this.resultsContainer.innerHTML = '';

        const hasNominatimResults = this.nominatimResults.length > 0;
        const hasDbResults = this.dbSearchResults.length > 0;

        if (!hasNominatimResults && !hasDbResults) {
            const noResult = document.createElement('div');
            noResult.className = 'p-3 text-gray-500 text-sm';
            noResult.textContent = 'Tidak ada hasil yang ditemukan';
            this.resultsContainer.appendChild(noResult);
            return;
        }

        // Display Nominatim results FIRST
        if (hasNominatimResults) {
            const nominatimHeader = document.createElement('div');
            nominatimHeader.className = 'p-2 bg-gray-50 text-gray-700 text-xs font-medium';
            nominatimHeader.textContent = 'Lokasi dari Peta';
            this.resultsContainer.appendChild(nominatimHeader);

            this.nominatimResults.forEach(result => {
                const item = document.createElement('div');
                item.className = 'p-3 hover:bg-gray-100 cursor-pointer text-sm border-b border-gray-100';

                // Format display name
                const displayName = this.formatLocationName(result.display_name);
                item.textContent = displayName;

                item.addEventListener('click', () => {
                    this.handleNominatimLocationSelect(result, displayName);
                });

                this.resultsContainer.appendChild(item);
            });
        }

        // Display database results SECOND (after Nominatim)
        if (hasDbResults) {
            const dbHeader = document.createElement('div');
            dbHeader.className = 'p-2 bg-blue-50 text-blue-700 text-xs font-medium';
            dbHeader.textContent = 'Destinasi Tersedia';
            this.resultsContainer.appendChild(dbHeader);

            this.dbSearchResults.forEach(result => {
                const item = document.createElement('div');
                item.className = 'p-3 hover:bg-gray-100 cursor-pointer text-sm border-b border-gray-100 last:border-0';

                // Format display name
                const displayName = result.place_name || 'Unnamed Destination';

                // Create main content
                const nameEl = document.createElement('div');
                nameEl.className = 'font-medium text-blue-700';
                nameEl.textContent = displayName;

                // Create location info
                const locationEl = document.createElement('div');
                locationEl.className = 'text-xs text-gray-500 mt-1';
                locationEl.textContent = `${result.administrative_area || ''}, ${result.province || ''}`;

                // Add badge for database result
                const badge = document.createElement('span');
                badge.className = 'inline-block bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full ml-2';
                badge.textContent = 'DB';
                nameEl.appendChild(badge);

                item.appendChild(nameEl);
                item.appendChild(locationEl);

                item.addEventListener('click', () => {
                    this.handleDatabaseLocationSelect(result);
                });

                this.resultsContainer.appendChild(item);
            });
        }
    }

    /**
     * Handle selection of a location from Nominatim
     * @param {Object} result - Location data from Nominatim
     * @param {string} displayName - Formatted display name
     */
    handleNominatimLocationSelect(result, displayName) {
        // Store the selected location data
        this.selectedLocation = {
            ...result,
            formatted_name: displayName,
            source: 'nominatim'
        };

        // Update UI
        this.inputElement.value = displayName;
        this.hideResults();

        // Store values in hidden inputs if provided
        if (this.latInput) this.latInput.value = parseFloat(result.lat);
        if (this.lngInput) this.lngInput.value = parseFloat(result.lon);

        // Call the callback function if provided
        if (typeof this.onLocationSelect === 'function') {
            this.onLocationSelect({
                name: displayName,
                lat: parseFloat(result.lat),
                lng: parseFloat(result.lon),
                source: 'nominatim',
                original: result
            });
        }
    }

    /**
     * Handle selection of a location from database
     * @param {Object} result - Location data from database
     */
    handleDatabaseLocationSelect(result) {
        // Store the selected location data
        this.selectedLocation = {
            ...result,
            formatted_name: result.place_name,
            source: 'database',
            id: result.id
        };

        // Update UI
        this.inputElement.value = result.place_name;
        this.hideResults();

        // Store values in hidden inputs if provided
        if (this.latInput) this.latInput.value = parseFloat(result.latitude);
        if (this.lngInput) this.lngInput.value = parseFloat(result.longitude);

        // Call the callback function if provided
        if (typeof this.onLocationSelect === 'function') {
            this.onLocationSelect({
                name: result.place_name,
                lat: parseFloat(result.latitude),
                lng: parseFloat(result.longitude),
                source: 'database',
                id: result.id,
                administrative_area: result.administrative_area,
                province: result.province,
                original: result
            });
        }
    }

    /**
     * Format location name to be more readable
     * @param {string} name - Raw location name from Nominatim
     * @returns {string} - Formatted location name
     */
    formatLocationName(name) {
        // Simplified version - clean up and translate common terms
        const parts = name.split(', ');
        // Take only first 3 parts for simplicity
        const simplifiedName = parts.slice(0, 3).join(', ');

        // Translate common terms
        return simplifiedName
            .replace('City', 'Kota')
            .replace('Province', 'Provinsi')
            .replace('Regency', 'Kabupaten')
            .replace('District', 'Kecamatan')
            .replace('Village', 'Desa');
    }

    /**
     * Clear selected location
     */
    clearSelectedLocation() {
        this.selectedLocation = null;

        // Clear input
        this.inputElement.value = '';

        // Clear hidden inputs if provided
        if (this.latInput) this.latInput.value = '';
        if (this.lngInput) this.lngInput.value = '';

        // Call the callback function if provided
        if (typeof this.onLocationSelect === 'function') {
            this.onLocationSelect(null);
        }
    }

    /**
     * Show loading indicator
     */
    showLoadingIndicator() {
        if (this.searchIndicator) {
            this.searchIndicator.classList.remove('hidden');
        }
    }

    /**
     * Hide loading indicator
     */
    hideLoadingIndicator() {
        if (this.searchIndicator) {
            this.searchIndicator.classList.add('hidden');
        }
    }

    /**
     * Show search results container
     */
    showResults() {
        this.resultsContainer.classList.remove('hidden');
    }

    /**
     * Hide search results container
     */
    hideResults() {
        this.resultsContainer.classList.add('hidden');
    }

    /**
     * Get selected location data
     * @returns {Object|null} - Selected location or null if nothing selected
     */
    getSelectedLocation() {
        return this.selectedLocation;
    }

    /**
     * Set location programmatically
     * @param {Object} location - Location object with name, lat, lng properties
     */
    setLocation(location) {
        if (!location) {
            this.clearSelectedLocation();
            return;
        }

        this.selectedLocation = location;
        this.inputElement.value = location.name || location.formatted_name;

        // Store values in hidden inputs if provided
        if (this.latInput && location.lat) this.latInput.value = location.lat;
        if (this.lngInput && location.lng) this.lngInput.value = location.lng;
    }
}

// Export the LocationSearch class
export default LocationSearch;
window.LocationSearch = LocationSearch;
