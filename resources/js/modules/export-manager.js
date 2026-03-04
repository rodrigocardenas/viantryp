// Export Manager Module - Handles trip export, preview, and saving

// Import FileManager if available
try {
    if (typeof FileManager === 'undefined' && typeof window !== 'undefined') {
        // Create a minimal FileManager fallback
        window.FileManager = window.FileManager || class FileManager {
            setupFileUploadListeners() { }
            uploadDocument() { return Promise.resolve(false); }
            processTempFiles() { return Promise.resolve(); }
        };
    }
} catch (e) {
    console.warn('FileManager import failed, using fallback:', e);
}

class ExportManager {
    constructor() {
        // Use the global FileManager instance, or create one if not available
        this.fileManager = window.fileManager || new (window.FileManager || function () { })();
        this.isSaving = false; // Flag to prevent multiple simultaneous saves
        this.savedTripIds = new Set(); // Track saved trip IDs to prevent duplicates

        // Ensure FileManager methods are available
        if (this.fileManager && typeof this.fileManager.setupFileUploadListeners !== 'function') {
            // Fallback for old browsers or missing FileManager
            this.fileManager = {
                setupFileUploadListeners: function () { },
                uploadDocument: function () { return Promise.resolve(false); },
                processTempFiles: function () { return Promise.resolve(); }
            };
        }
    }

    previewTrip() {
        const tripId = this.getCurrentTripId();
        if (!tripId) {
            this.showNotification('Error', 'Primero guarda el viaje para ver la vista previa.', 'error');
            return;
        }

        const previewUrl = `/trips/${tripId}/preview`;
        window.open(previewUrl, '_blank');
    }

    downloadPDF() {
        const tripId = this.getCurrentTripId();
        if (!tripId) {
            this.showNotification('Error', 'Primero guarda el viaje para descargar el PDF.', 'error');
            return;
        }

        // Show loading state
        const pdfBtn = document.querySelector('.btn-pdf');
        const originalText = pdfBtn.innerHTML;
        pdfBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando PDF...';
        pdfBtn.disabled = true;

        try {
            // Create a temporary link to trigger download
            const link = document.createElement('a');
            link.href = `/trips/${tripId}/pdf`;
            link.download = 'itinerario.pdf';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            this.showNotification('PDF generado', 'El PDF del itinerario se está descargando.', 'success');

            // Reset button
            pdfBtn.innerHTML = originalText;
            pdfBtn.disabled = false;
        } catch (error) {
            console.error('PDF download error:', error);
            this.showNotification('Error', 'No se pudo generar el PDF.', 'error');

            // Reset button
            pdfBtn.innerHTML = originalText;
            pdfBtn.disabled = false;
        }
    }

    async saveTrip() {
        // Prevent multiple simultaneous saves
        if (this.isSaving) {
            console.log('Save operation already in progress, ignoring duplicate request');
            this.showNotification('Guardando...', 'Por favor espera a que termine la operación anterior.', 'warning');
            return;
        }

        // Smart save logic: allow updates to existing trips
        const currentTripId = this.getCurrentTripId();
        if (currentTripId && this.savedTripIds.has(currentTripId)) {
            console.log('Trip already saved successfully, will update instead of prevent save');
        }

        this.isSaving = true;

        try {
            // Collect all trip elements from the days
            const itemsData = this.collectAllTripItems();

            // Collect days dates
            const daysDates = this.collectDaysDates();

            // Determinar end_date SIN automatización (sin start_date + días).
            const startDateEl = document.getElementById('start-date');
            const startDate = startDateEl ? startDateEl.value : null;
            let endDate = null;
            if (startDate) {
                const manualDates = Object.values(daysDates || {}).filter(Boolean);
                if (manualDates.length > 0) {
                    // YYYY-MM-DD se puede ordenar como string
                    endDate = [...manualDates].sort().pop();
                } else {
                    // Si no hay fechas por día, no inventar una fecha final
                    endDate = startDate;
                }
            }

            // Read title safely: the title may be an input or an element (h1) depending on page
            const tripTitleEl = document.getElementById('trip-title');
            let tripTitle = '';
            if (tripTitleEl) {
                // Prefer .value for inputs, fall back to textContent for headings
                if (typeof tripTitleEl.value !== 'undefined') {
                    tripTitle = (tripTitleEl.value || '').toString().trim();
                } else {
                    tripTitle = (tripTitleEl.textContent || '').toString().trim();
                }
            }

            // Read travelers, price and currency from header inputs
            const travelersEl = document.getElementById('trip-travelers');
            const priceEl = document.getElementById('trip-price');
            const currencyEl = document.getElementById('trip-currency');

            // Read cover image from global state or HTML element if present
            let coverImageUrl = null;
            if (window.existingTripData && window.existingTripData.cover_image_url !== undefined) {
                coverImageUrl = window.existingTripData.cover_image_url;
            } else {
                const previewImg = document.getElementById('cover-image-preview');
                if (previewImg && previewImg.src && !previewImg.src.includes('data:')) {
                    // Only use it if it's a URL, not a base64 (which should go via FormData upload)
                    coverImageUrl = previewImg.src;
                }
            }

            const tripData = {
                title: tripTitle,
                start_date: startDate,
                end_date: endDate,
                travelers: travelersEl ? (parseInt(travelersEl.value) || 1) : 1,
                price: priceEl ? (parseFloat(priceEl.value) || 0) : 0,
                currency: currencyEl ? currencyEl.value : 'USD',
                cover_image_url: coverImageUrl,
                destination: '', // Optional field
                summary: '', // Optional field
                items_data: itemsData,
                days_dates: daysDates
            };

            // Determine if this is a new trip or updating existing
            let url, method, tripId = null;

            // First, check if we have a current trip ID (created from modal)
            if (window.currentTripId) {
                // This is an update operation
                tripId = window.currentTripId;
                url = '/trips/' + tripId;
                method = 'PATCH'; // Use real PATCH method for updates
                console.log('Updating existing trip with ID:', tripId);
            } else {
                // Check URL for edit mode
                const currentPath = window.location.pathname;
                const urlParts = currentPath.split('/').filter(part => part !== '');

                // Normalize: expected edit URL is /trips/{id}/edit -> ['trips','{id}','edit']
                const isEditing = urlParts.length >= 3 && urlParts[0] === 'trips' && !isNaN(urlParts[1]) && urlParts[2] === 'edit';

                console.log('Current path:', currentPath);
                console.log('URL parts:', urlParts);
                console.log('Is editing:', isEditing);

                if (isEditing) {
                    // For editing, extract the trip ID from the URL
                    tripId = urlParts[1]; // The ID is at index 1
                    console.log('Extracted trip ID:', tripId);

                    if (!tripId || isNaN(tripId)) {
                        console.error('Invalid trip ID extracted from URL');
                        this.showNotification('Error', 'No se pudo determinar el ID del viaje para editar.');
                        return;
                    }

                    url = '/trips/' + tripId;
                    method = 'PATCH'; // Use real PATCH method for updates
                } else {
                    // For creating, make POST request to /trips
                    url = '/trips';
                    method = 'POST';
                }
            }

            console.log('Final URL:', url);
            console.log('Method:', method);
            console.log('Trip data to send:', JSON.stringify(tripData, null, 2));

            // Validate required fields
            if (!tripData.title || tripData.title.trim() === '') {
                this.showNotification('Error', 'El título del viaje es obligatorio.');
                return;
            }

            if (!tripData.start_date) {
                this.showNotification('Error', 'La fecha de inicio es obligatoria.');
                return;
            }

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            console.log('CSRF token found:', !!csrfToken);

            if (!csrfToken) {
                console.error('CSRF token not found!');
                this.showNotification('Error', 'Token de seguridad no encontrado. Recarga la página.');
                return;
            }

            // Show saving notification
            this.showNotification('Guardando...', 'El viaje se está guardando.', 'info');

            const response = await fetch(url, {
                method: method,
                credentials: 'same-origin', // ensure cookies (session) are sent
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(tripData)
            });

            console.log('Response status:', response.status);
            console.log('Response status text:', response.statusText);

            const text = await response.text();
            console.log('Raw response text:', text);

            if (!response.ok) {
                console.error('HTTP error response:', {
                    status: response.status,
                    statusText: response.statusText,
                    body: text
                });
                throw new Error(`HTTP error! status: ${response.status}, message: ${text}`);
            }

            // Check if response is empty
            if (!text || text.trim() === '') {
                console.error('Empty response from server');
                throw new Error('Empty response from server');
            }

            const data = JSON.parse(text);
            console.log('Successfully parsed JSON:', data);

            if (data.success) {
                // Track successful saves to prevent duplicates
                if (data.trip && data.trip.id) {
                    this.savedTripIds.add(data.trip.id.toString());
                    window.currentTripId = data.trip.id; // Update global state
                }

                // Process any temporary uploaded files
                if (this.fileManager && typeof this.fileManager.processTempFiles === 'function') {
                    console.log('Processing temporary files for trip ID:', data.trip.id);
                    await this.fileManager.processTempFiles(data.trip.id);
                }

                // Show appropriate message based on action
                if (data.action === 'updated') {
                    this.showNotification('Viaje Actualizado', 'El viaje existente ha sido actualizado exitosamente.', 'success');
                } else {
                    this.showNotification('Viaje Creado', 'El nuevo viaje ha sido creado exitosamente.', 'success');
                }

                // Only redirect for new trips, not updates
                if (data.action === 'created' && !window.currentTripId) {
                    window.location.href = '/trips';
                }
            } else {
                this.showNotification('Error', data.message || 'No se pudo guardar el viaje.');
            }
        } catch (error) {
            console.error('Error saving trip:', error);
            this.showNotification('Error', 'No se pudo guardar el viaje. Revisa la consola para más detalles.');
        } finally {
            this.isSaving = false;
        }
    }

    collectAllTripItems() {
        const items = [];
        const dayCards = document.querySelectorAll('.day-card');

        dayCards.forEach((dayCard, index) => {
            const dayNumber = parseInt(dayCard.dataset.day) || (index + 1);
            const timelineItems = dayCard.querySelectorAll('.timeline-item');

            timelineItems.forEach(item => {
                const itemData = this.extractItemData(item, dayNumber);
                if (itemData) {
                    items.push(itemData);
                }
            });
        });

        // Also collect items that are in the global notes list (outside of days)
        try {
            const globalNotes = document.querySelectorAll('#global-notes-list .timeline-item');
            globalNotes.forEach(item => {
                const itemData = this.extractItemData(item, null);
                if (itemData) {
                    items.push(itemData);
                }
            });
        } catch (err) {
            console.warn('No global notes found or error collecting them', err);
        }

        console.log('=== ALL ITEMS COLLECTED ===');
        items.forEach((item, idx) => {
            console.log(`Item ${idx}:`, {
                type: item.type,
                day: item.day,
                temp_id: item.temp_id || '(no temp_id)',
                id: item.id || '(no id)'
            });
        });
        console.log('===========================');

        return items;
    }

    collectDaysDates() {
        const daysDates = {};
        const dayCards = document.querySelectorAll('.day-card');

        dayCards.forEach(dayCard => {
            const dayNumber = parseInt(dayCard.dataset.day);
            const dateInput = dayCard.querySelector('.day-date-input, .day-date-input-large');
            if (dateInput && dateInput.value) {
                daysDates[dayNumber] = dateInput.value;
            }
        });

        return daysDates;
    }

    extractItemData(itemElement, dayNumber) {
        // Use data-type attribute (canonical: 'flight', 'hotel', etc.) as primary source
        const dataType = itemElement.getAttribute('data-type')?.toLowerCase() || '';
        const labelType = itemElement.querySelector('.item-type')?.textContent?.toLowerCase() || '';
        const resolvedType = dataType || labelType.replace('elemento', '').trim();

        if (!resolvedType) return null;

        const baseData = {
            type: resolvedType,
            day: dayNumber
        };

        console.log('Extracting item data for type:', resolvedType, 'from element:', itemElement);

        // Extract data based on item type
        switch (resolvedType) {
            case 'flight':
            case 'vuelo':
                let departureAirport = itemElement.getAttribute('data-departure-airport') || '';
                let arrivalAirport = itemElement.getAttribute('data-arrival-airport') || '';

                // Handle case where airports might be concatenated
                if (departureAirport && arrivalAirport === '' && departureAirport.includes('→')) {
                    const airports = departureAirport.split('→');
                    if (airports.length === 2) {
                        departureAirport = airports[0].trim();
                        arrivalAirport = airports[1].trim();
                    }
                }

                return {
                    ...baseData,
                    type: 'flight',
                    airline_name: itemElement.getAttribute('data-airline-name') || '',
                    flight_number: itemElement.getAttribute('data-flight-number') || '',
                    departure_airport: departureAirport,
                    arrival_airport: arrivalAirport,
                    departure_datetime: itemElement.getAttribute('data-departure-datetime') || '',
                    arrival_datetime: itemElement.getAttribute('data-arrival-datetime') || '',
                    // Split datetime into separate date/time fields for the preview blade
                    departure_date: (itemElement.getAttribute('data-departure-datetime') || '').split(/[T ]/)[0] || '',
                    departure_time: (itemElement.getAttribute('data-departure-datetime') || '').split(/[T ]/)[1] || '',
                    arrival_date: (itemElement.getAttribute('data-arrival-datetime') || '').split(/[T ]/)[0] || '',
                    arrival_time: (itemElement.getAttribute('data-arrival-datetime') || '').split(/[T ]/)[1] || '',
                    departure_city: itemElement.getAttribute('data-departure-city') || '',
                    arrival_city: itemElement.getAttribute('data-arrival-city') || '',
                    departure_airport_name: itemElement.getAttribute('data-departure-airport-name') || '',
                    arrival_airport_name: itemElement.getAttribute('data-arrival-airport-name') || '',
                    confirmation_number: itemElement.getAttribute('data-confirmation-number') || '',
                    baggage_types: itemElement.getAttribute('data-baggage-types') ? JSON.parse(itemElement.getAttribute('data-baggage-types')) : [],
                    temp_id: itemElement.getAttribute('data-temp-id') || ''
                };

            case 'hotel':
            case 'alojamiento':
                return {
                    ...baseData,
                    type: 'hotel',
                    hotel_name: itemElement.querySelector('.item-title')?.textContent || '',
                    hotel_id: itemElement.getAttribute('data-hotel-id') || '',
                    hotel_data: itemElement.getAttribute('data-hotel-data') ? JSON.parse(itemElement.getAttribute('data-hotel-data')) : null,
                    check_in: itemElement.getAttribute('data-check-in') || '',
                    check_out: itemElement.getAttribute('data-check-out') || '',
                    room_type: itemElement.getAttribute('data-room-type') || '',
                    meal_plan: itemElement.getAttribute('data-meal-plan') || '',
                    nights: parseInt(itemElement.getAttribute('data-nights')) || 1,
                    temp_id: itemElement.getAttribute('data-temp-id') || ''
                };

            case 'activity':
            case 'actividad':
                return {
                    ...baseData,
                    type: 'activity',
                    activity_title: itemElement.getAttribute('data-activity-title') || itemElement.querySelector('.item-title')?.textContent || '',
                    location: itemElement.getAttribute('data-location') || '',
                    start_datetime: itemElement.getAttribute('data-start-datetime') || '',
                    end_datetime: itemElement.getAttribute('data-end-datetime') || '',
                    description: itemElement.getAttribute('data-description') || '',
                    place_id: itemElement.getAttribute('data-place-id') || '',
                    location_data: itemElement.getAttribute('data-location-data') ? JSON.parse(itemElement.getAttribute('data-location-data')) : null,
                    formatted_address: itemElement.getAttribute('data-formatted-address') || '',
                    rating: itemElement.getAttribute('data-rating') || null,
                    website: itemElement.getAttribute('data-website') || '',
                    phone_number: itemElement.getAttribute('data-phone-number') || '',
                    latitude: itemElement.getAttribute('data-latitude') || '',
                    longitude: itemElement.getAttribute('data-longitude') || '',
                    temp_id: itemElement.getAttribute('data-temp-id') || ''
                };

            case 'transport':
            case 'traslado':
            case 'transporte':
                return {
                    ...baseData,
                    type: 'transport',
                    transport_type: itemElement.getAttribute('data-transport-type') || '',
                    pickup_location: itemElement.getAttribute('data-pickup-location') || '',
                    destination: itemElement.getAttribute('data-destination') || '',
                    pickup_datetime: itemElement.getAttribute('data-pickup-datetime') || '',
                    arrival_datetime: itemElement.getAttribute('data-arrival-datetime') || '',
                    temp_id: itemElement.getAttribute('data-temp-id') || ''
                };

            case 'note':
            case 'nota':
                return {
                    ...baseData,
                    type: 'note',
                    note_title: itemElement.querySelector('.item-title')?.textContent || '',
                    note_content: itemElement.dataset.noteContent || '',
                    temp_id: itemElement.getAttribute('data-temp-id') || ''
                };

            case 'title':
            case 'título':
                return {
                    ...baseData,
                    type: 'title',
                    content: itemElement.dataset.content || itemElement.querySelector('.element-content-display')?.textContent || '',
                    temp_id: itemElement.getAttribute('data-temp-id') || ''
                };

            case 'paragraph':
            case 'párrafo':
                return {
                    ...baseData,
                    type: 'paragraph',
                    content: itemElement.dataset.content || itemElement.querySelector('.element-content-display')?.textContent || '',
                    temp_id: itemElement.getAttribute('data-temp-id') || ''
                };

            default:
                return {
                    ...baseData,
                    type: 'note',
                    note_title: itemElement.querySelector('.item-title')?.textContent || 'Elemento',
                    note_content: itemElement.dataset.noteContent || '',
                    temp_id: itemElement.getAttribute('data-temp-id') || ''
                };
        }
    }

    getCurrentTripId() {
        // First try to get from existing trip data
        if (window.existingTripData && window.existingTripData.id) {
            return window.existingTripData.id;
        }

        // Fallback to URL parsing
        const currentPath = window.location.pathname;
        const urlParts = currentPath.split('/').filter(part => part !== '');
        // Expect paths like: /trips/{id}/edit  -> ['trips','{id}','edit']
        if (urlParts.length >= 2 && urlParts[0] === 'trips' && !isNaN(urlParts[1])) {
            return urlParts[1];
        }
        return null;
    }

    showNotification(title, message, type = 'success') {
        // This should be imported from a notification module
        console.log(`${type.toUpperCase()}: ${title} - ${message}`);
        // For now, use a simple alert or create a proper notification system
        if (typeof showNotification === 'function') {
            showNotification(title, message, type);
        } else {
            alert(`${title}: ${message}`);
        }
    }
}

export default ExportManager;
