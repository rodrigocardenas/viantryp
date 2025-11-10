// Google Places Autocomplete Module
export class GooglePlacesAutocomplete {
    constructor() {
        this.apiKey = null;
        this.autocomplete = null;
        this.currentInput = null;
        this.options = {
            types: ['establishment'],
            fields: ['place_id', 'name', 'formatted_address', 'geometry', 'rating', 'types', 'photos', 'website', 'international_phone_number', 'price_level']
        };
        this.isLoaded = false;
        this.callbacks = {
            onPlaceSelect: null,
            onPlaceDetails: null,
            onError: null
        };
    }

    /**
     * Initialize autocomplete on an input element
     * @param {HTMLElement} inputElement - The input element to attach autocomplete to
     * @param {Object} options - Configuration options
     */
    async init(inputElement, options = {}) {
        this.currentInput = inputElement;
        this.options = { ...this.options, ...options };

        // Get API key from Laravel config (injected via global or meta tag)
        this.apiKey = this.getApiKey();

        if (!this.apiKey) {
            console.error('Google Places API key not found');
            this.showError('API key not configured');
            return false;
        }

        try {
            await this.loadGoogleMapsAPI();
            this.setupAutocomplete();
            this.setupEventListeners();
            return true;
        } catch (error) {
            console.error('Failed to initialize Google Places autocomplete:', error);
            this.showError('Failed to load Google Maps API');
            return false;
        }
    }

    /**
     * Get API key from Laravel configuration
     */
    getApiKey() {
        // Try to get from meta tag first
        const metaTag = document.querySelector('meta[name="google-places-api-key"]');
        if (metaTag) {
            const key = metaTag.getAttribute('content');
            if (key && key.trim()) {
                return key.trim();
            }
        }

        // Fallback to global variable if set by Laravel
        if (window.googlePlacesApiKey) {
            return window.googlePlacesApiKey;
        }

        // Fallback to Laravel config if available
        if (window.Laravel && window.Laravel.services && window.Laravel.services.google) {
            return window.Laravel.services.google.places_api_key;
        }

        console.error('Google Places API key not found. Make sure GOOGLE_PLACES_API_KEY is set in your .env file');
        return null;
    }

    /**
     * Load Google Maps JavaScript API
     */
    async loadGoogleMapsAPI() {
        return new Promise((resolve, reject) => {
            // Check if already loaded
            if (window.google && window.google.maps && window.google.maps.places) {
                this.isLoaded = true;
                resolve();
                return;
            }

            // Wait for the API to load (script is loaded in layout)
            const checkLoaded = setInterval(() => {
                if (window.google && window.google.maps && window.google.maps.places) {
                    clearInterval(checkLoaded);
                    this.isLoaded = true;
                    resolve();
                }
            }, 100);

            // Timeout after 10 seconds
            setTimeout(() => {
                clearInterval(checkLoaded);
                reject(new Error('Google Maps API loading timeout - check your API key and network connection'));
            }, 10000);
        });
    }

    /**
     * Setup Google Places Autocomplete
     */
    setupAutocomplete() {
        if (!this.isLoaded || !this.currentInput) return;

        try {
            // For now, use only the legacy Autocomplete as the new API might not be available
            // The new PlaceAutocompleteElement requires specific conditions to be available
            this.setupLegacyAutocomplete();
        } catch (error) {
            console.error('Error setting up autocomplete:', error);
            this.showError('Failed to setup autocomplete');
        }
    }

    /**
     * Setup new PlaceAutocompleteElement (recommended) - Currently disabled
     * This method is kept for future use when the new API becomes more widely available
     */
    setupNewAutocomplete() {
        // Temporarily disabled - the new API is not consistently available
        // Will fall back to legacy autocomplete
        this.setupLegacyAutocomplete();
    }

    /**
     * Setup legacy Autocomplete (current working solution)
     */
    setupLegacyAutocomplete() {
        try {
            // Validate that Google Maps API is properly loaded
            if (!window.google || !window.google.maps || !window.google.maps.places) {
                throw new Error('Google Maps Places API not available');
            }

            if (!window.google.maps.places.Autocomplete) {
                throw new Error('Google Maps Places Autocomplete not available - check API key permissions');
            }

            // Set types to lodging for hotels
            const autocompleteOptions = {
                ...this.options,
                types: ['lodging'] // This works with the legacy API
            };

            this.autocomplete = new google.maps.places.Autocomplete(
                this.currentInput,
                autocompleteOptions
            );

            // Add place_changed listener
            this.autocomplete.addListener('place_changed', () => {
                this.handlePlaceSelect();
            });

        } catch (error) {
            console.error('Error setting up legacy autocomplete:', error);
            this.showError('Failed to setup autocomplete');
        }
    }

    /**
     * Setup additional event listeners
     */
    setupEventListeners() {
        if (!this.currentInput) return;

        // Add loading indicator on input focus
        this.currentInput.addEventListener('focus', () => {
            this.showLoading();
        });

        // Clear loading on blur if no selection made
        this.currentInput.addEventListener('blur', () => {
            setTimeout(() => {
                this.hideLoading();
            }, 200); // Delay to allow place selection
        });
    }

    /**
     * Handle place selection from new PlaceAutocompleteElement
     */
    handleNewPlaceSelect(event) {
        const place = event.place;

        if (!place || !place.id) {
            console.warn('Selected place has no id');
            return;
        }

        const placeData = this.extractNewPlaceData(place);

        if (this.callbacks.onPlaceSelect) {
            this.callbacks.onPlaceSelect(placeData);
        }

        this.hideLoading();
    }

    /**
     * Handle place selection from legacy Autocomplete
     */
    handlePlaceSelect() {
        if (!this.autocomplete) return;

        const place = this.autocomplete.getPlace();

        if (!place.place_id) {
            console.warn('Selected place has no place_id');
            return;
        }

        const placeData = this.extractPlaceData(place);

        // Call onPlaceSelect callback with basic data
        if (this.callbacks.onPlaceSelect) {
            this.callbacks.onPlaceSelect(placeData);
        }

        // Fetch additional details from backend
        this.fetchPlaceDetails(placeData.place_id);

        this.hideLoading();
    }

    /**
     * Fetch additional place details from backend
     * @param {string} placeId - The Google Places place_id
     */
    async fetchPlaceDetails(placeId) {
        try {
            const response = await fetch('/api/places/details', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({ place_id: placeId })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const details = await response.json();

            // Call onPlaceDetails callback with full details
            if (this.callbacks.onPlaceDetails) {
                this.callbacks.onPlaceDetails(details);
            }
        } catch (error) {
            console.error('Error fetching place details:', error);
            if (this.callbacks.onError) {
                this.callbacks.onError('Failed to fetch place details');
            }
        }
    }

    /**
     * Extract relevant data from new PlaceAutocompleteElement result
     * @param {Object} place - New Places API result object
     */
    extractNewPlaceData(place) {
        return {
            place_id: place.id,
            name: place.displayName,
            formatted_address: place.formattedAddress,
            address_components: place.addressComponents,
            geometry: place.location ? {
                location: {
                    lat: place.location.lat(),
                    lng: place.location.lng()
                }
            } : null,
            rating: place.rating,
            types: place.types,
            photos: place.photos ? place.photos.map(photo => ({
                url: photo.getURI(),
                width: photo.widthPx,
                height: photo.heightPx
            })) : [],
            website: place.websiteURI,
            international_phone_number: place.internationalPhoneNumber,
            price_level: place.priceLevel,
            // Additional metadata
            utc_offset: place.utcOffsetMinutes,
            vicinity: place.shortFormattedAddress
        };
    }

    /**
     * Extract relevant data from legacy Google Places result
     * @param {Object} place - Google Places result object
     */
    extractPlaceData(place) {
        return {
            place_id: place.place_id,
            name: place.name,
            formatted_address: place.formatted_address,
            address_components: place.address_components,
            geometry: place.geometry ? {
                location: {
                    lat: place.geometry.location.lat(),
                    lng: place.geometry.location.lng()
                }
            } : null,
            rating: place.rating,
            types: place.types,
            photos: place.photos ? place.photos.map(photo => ({
                url: photo.getUrl({ maxWidth: 400, maxHeight: 400 }),
                width: photo.width,
                height: photo.height
            })) : [],
            website: place.website,
            international_phone_number: place.international_phone_number,
            price_level: place.price_level,
            // Additional metadata
            utc_offset: place.utc_offset,
            vicinity: place.vicinity
        };
    }

    /**
     * Set callback functions
     * @param {Object} callbacks - Object with callback functions
     */
    setCallbacks(callbacks) {
        this.callbacks = { ...this.callbacks, ...callbacks };
    }

    /**
     * Show loading indicator
     */
    showLoading() {
        if (!this.currentInput) return;

        this.currentInput.classList.add('autocomplete-loading');

        // Add loading spinner if not exists
        let spinner = this.currentInput.parentNode.querySelector('.autocomplete-spinner');
        if (!spinner) {
            spinner = document.createElement('div');
            spinner.className = 'autocomplete-spinner';
            spinner.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            this.currentInput.parentNode.appendChild(spinner);
        }
    }

    /**
     * Hide loading indicator
     */
    hideLoading() {
        if (!this.currentInput) return;

        this.currentInput.classList.remove('autocomplete-loading');

        const spinner = this.currentInput.parentNode.querySelector('.autocomplete-spinner');
        if (spinner) {
            spinner.remove();
        }
    }

    /**
     * Show error message
     * @param {string} message - Error message
     */
    showError(message) {
        console.error('Google Places Autocomplete Error:', message);

        if (this.callbacks.onError) {
            this.callbacks.onError(message);
        }

        // Show error in input
        if (this.currentInput) {
            this.currentInput.classList.add('autocomplete-error');
            this.currentInput.setAttribute('title', message);
        }
    }

    /**
     * Clear error state
     */
    clearError() {
        if (this.currentInput) {
            this.currentInput.classList.remove('autocomplete-error');
            this.currentInput.removeAttribute('title');
        }
    }

    /**
     * Restrict autocomplete to specific country
     * @param {string} countryCode - ISO 3166-1 Alpha-2 country code
     */
    setCountryRestriction(countryCode) {
        if (this.autocomplete) {
            this.autocomplete.setComponentRestrictions({ country: countryCode });
        }
        this.options.componentRestrictions = { country: countryCode };
    }

    /**
     * Set autocomplete types
     * @param {Array} types - Array of place types
     */
    setTypes(types) {
        this.options.types = types;
        if (this.autocomplete) {
            // Recreate autocomplete with new options
            this.setupAutocomplete();
        }
    }

    /**
     * Destroy autocomplete instance
     */
    destroy() {
        if (this.autocomplete) {
            // Legacy Autocomplete cleanup
            google.maps.event.clearInstanceListeners(this.autocomplete);
            this.autocomplete = null;
        }

        this.hideLoading();
        this.clearError();
        this.currentInput = null;
        this.isLoaded = false;
    }
}

// Default export
export default GooglePlacesAutocomplete;
