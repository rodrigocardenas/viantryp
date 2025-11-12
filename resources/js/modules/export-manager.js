// Export Manager Module - Handles trip export, preview, and saving

// Import FileManager if available
try {
    if (typeof FileManager === 'undefined' && typeof window !== 'undefined') {
        // Create a minimal FileManager fallback
        window.FileManager = window.FileManager || class FileManager {
            setupFileUploadListeners() {}
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
        this.fileManager = window.fileManager || new (window.FileManager || function(){})();
        this.isSaving = false; // Flag to prevent multiple simultaneous saves
        this.savedTripIds = new Set(); // Track saved trip IDs to prevent duplicates

        // Ensure FileManager methods are available
        if (this.fileManager && typeof this.fileManager.setupFileUploadListeners !== 'function') {
            // Fallback for old browsers or missing FileManager
            this.fileManager = {
                setupFileUploadListeners: function() {},
                uploadDocument: function() { return Promise.resolve(false); },
                processTempFiles: function() { return Promise.resolve(); }
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

            // Calculate end date based on number of days
            const startDateEl = document.getElementById('start-date');
            const startDate = startDateEl ? startDateEl.value : null;
            let endDate = null;
            if (startDate) {
                const dayCards = document.querySelectorAll('.day-card');
                const numDays = Math.max(dayCards.length, 1); // Ensure at least 1 day
                const startDateObj = new Date(startDate);
                const endDateObj = new Date(startDate);
                endDateObj.setDate(startDateObj.getDate() + numDays - 1);
                endDate = endDateObj.toISOString().split('T')[0];
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

            const tripData = {
                title: tripTitle,
                start_date: startDate,
                end_date: endDate,
                travelers: 1, // Default value
                destination: '', // Optional field
                summary: '', // Optional field
                items_data: itemsData
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

        return items;
    }

    extractItemData(itemElement, dayNumber) {
        const itemType = itemElement.querySelector('.item-type')?.textContent?.toLowerCase();

        if (!itemType) return null;

        const baseData = {
            type: itemType.replace('elemento', '').trim().toLowerCase(),
            day: dayNumber
        };

        // Extract data based on item type
        switch (baseData.type) {
            case 'vuelo':
                let departureAirport = itemElement.getAttribute('data-departure-airport') || itemElement.querySelector('.item-subtitle')?.textContent?.split(' → ')[0] || '';
                let arrivalAirport = itemElement.getAttribute('data-arrival-airport') || itemElement.querySelector('.item-subtitle')?.textContent?.split(' → ')[1] || '';

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
                    airline: itemElement.getAttribute('data-airline') || itemElement.querySelector('.item-title')?.textContent?.split(' ')[0] || '',
                    flight_number: itemElement.getAttribute('data-flight-number') || itemElement.querySelector('.item-title')?.textContent?.split(' ')[1] || '',
                    departure_airport: departureAirport,
                    arrival_airport: arrivalAirport,
                    departure_time: itemElement.getAttribute('data-departure-time') || '',
                    arrival_time: itemElement.getAttribute('data-arrival-time') || '',
                    confirmation_number: itemElement.getAttribute('data-confirmation-number') || ''
                };

            case 'hotel':
                return {
                    ...baseData,
                    type: 'hotel',
                    hotel_name: itemElement.querySelector('.item-title')?.textContent || '',
                    hotel_id: itemElement.getAttribute('data-hotel-id') || '',
                    hotel_data: itemElement.getAttribute('data-hotel-data') ? JSON.parse(itemElement.getAttribute('data-hotel-data')) : null,
                    check_in: itemElement.getAttribute('data-check-in') || '',
                    check_out: itemElement.getAttribute('data-check-out') || '',
                    room_type: itemElement.getAttribute('data-room-type') || '',
                    nights: parseInt(itemElement.getAttribute('data-nights')) || 1
                };

            case 'actividad':
                return {
                    ...baseData,
                    type: 'activity',
                    activity_title: itemElement.querySelector('.item-title')?.textContent || '',
                    location: itemElement.querySelector('.item-subtitle')?.textContent || '',
                    start_time: '',
                    end_time: '',
                    description: ''
                };

            case 'traslado':
            case 'transporte':
                return {
                    ...baseData,
                    type: 'transport',
                    transport_type: itemElement.querySelector('.item-title')?.textContent || '',
                    pickup_location: itemElement.querySelector('.item-subtitle')?.textContent?.split(' → ')[0] || '',
                    destination: itemElement.querySelector('.item-subtitle')?.textContent?.split(' → ')[1] || '',
                    pickup_time: ''
                };

            case 'nota':
                return {
                    ...baseData,
                    type: 'note',
                    note_title: itemElement.querySelector('.item-title')?.textContent || '',
                    note_content: itemElement.querySelector('.item-subtitle')?.textContent || ''
                };

            default:
                return {
                    ...baseData,
                    type: 'note',
                    note_title: itemElement.querySelector('.item-title')?.textContent || 'Elemento',
                    note_content: itemElement.querySelector('.item-subtitle')?.textContent || ''
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
