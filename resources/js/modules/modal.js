// Modal Module - Handles element creation/editing modals
import elementLabels from '../data/element-labels.json';
import elementForms from '../data/element-forms.json';
import selectorsData from '../data/selectors.json';

export class ModalManager {
    constructor() {
        this.currentElementType = null;
        this.currentElementData = {};
        this.currentDay = null;
        this.selectedHotelData = null;
        this.uploadedDocuments = {
            flight: [],
            hotel: [],
            transport: []
        };
    }

    init() {
        this.setupEventListeners();
        console.log('ModalManager initialized');
    }

    setupEventListeners() {
        // Listen for element dropped events
        document.addEventListener('elementDropped', (e) => {
            console.log('Element dropped event received:', e.detail);
            this.showElementModal(e.detail.elementType, e.detail.dayNumber);
        });

        console.log('ModalManager event listeners setup');
    }

    showElementModal(elementType, dayNumber) {
        console.log('Showing element modal for:', elementType, 'day:', dayNumber);
        this.currentElementType = elementType;
        this.currentDay = dayNumber;
        this.currentElementData = { type: elementType, day: dayNumber };

        const modal = document.getElementById('element-modal');
        const modalTitle = document.getElementById('modal-title');
        const modalBody = document.getElementById('modal-body');

        modalTitle.textContent = `Agregar ${this.getTypeLabel(elementType)}`;
        modalBody.innerHTML = this.getElementForm(elementType);

        // Clear previously uploaded documents
        this.uploadedDocuments[elementType] = [];

        this.setupFileUploadListeners();
        this.initializeSelect2();
        this.setupModalButtons();

        modal.style.display = 'block';
        console.log('Modal displayed');
    }

    getTypeLabel(type) {
        return elementLabels.elementTypes[type] || 'Elemento';
    }

    getElementForm(type) {
        return elementForms.elementForms[type] || '<p>Formulario no disponible</p>';
    }

    setupFileUploadListeners() {
        // Setup listeners for file inputs
        const fileInputs = document.querySelectorAll('#modal-body input[type="file"]');
        fileInputs.forEach(input => {
            input.addEventListener('change', async (e) => {
                const files = e.target.files;
                if (files.length > 0) {
                    const type = this.currentElementType; // flight, hotel, transport
                    for (let file of files) {
                        await this.uploadDocument(file, type);
                    }
                }
            });
        });
    }

    async uploadDocument(file, type) {
        const tripId = this.getCurrentTripId();
        if (!tripId) {
            this.showNotification('Error', 'No se pudo determinar el ID del viaje.');
            return false;
        }

        const formData = new FormData();
        formData.append('file', file);
        formData.append('type', type);
        formData.append('item_id', 'temp_' + Date.now()); // Temporary ID until element is saved

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        try {
            const response = await fetch(`/trips/${tripId}/documents/upload`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                this.uploadedDocuments[type].push(result.document);
                this.showNotification('Documento Subido', 'El documento se ha subido exitosamente.');
                return true;
            } else {
                this.showNotification('Error', result.message || 'Error al subir el documento.');
                return false;
            }
        } catch (error) {
            console.error('Error uploading document:', error);
            this.showNotification('Error', 'Error al subir el documento.');
            return false;
        }
    }

    getCurrentTripId() {
        const currentPath = window.location.pathname;
        const urlParts = currentPath.split('/').filter(part => part !== '');
        if (urlParts.length >= 3 && urlParts[1] === 'trips' && !isNaN(urlParts[2])) {
            return urlParts[2];
        }
        return null;
    }

    initializeSelect2() {
        // Initialize Select2 for dropdowns with data from JSON
        console.log('Initializing Select2 with data:', selectorsData);

        // Initialize airline select
        const airlineSelects = document.querySelectorAll('.airline-select');
        airlineSelects.forEach(select => {
            if ($(select).hasClass('select2-hidden-accessible')) {
                $(select).select2('destroy');
            }
            $(select).select2({
                data: selectorsData.airlines,
                placeholder: 'Seleccionar aerolínea',
                allowClear: true,
                width: '100%'
            });
        });

        // Initialize airport selects
        const airportSelects = document.querySelectorAll('.airport-select');
        airportSelects.forEach(select => {
            if ($(select).hasClass('select2-hidden-accessible')) {
                $(select).select2('destroy');
            }
            $(select).select2({
                data: selectorsData.airports,
                placeholder: 'Seleccionar aeropuerto',
                allowClear: true,
                width: '100%'
            });
        });

        // Initialize hotel selects (if any)
        const hotelSelects = document.querySelectorAll('.hotel-select');
        hotelSelects.forEach(select => {
            if ($(select).hasClass('select2-hidden-accessible')) {
                $(select).select2('destroy');
            }
            $(select).select2({
                data: selectorsData.hotels || [],
                placeholder: 'Seleccionar hotel',
                allowClear: true,
                width: '100%'
            });
        });

        console.log('Select2 initialized successfully');
    }

    setupModalButtons() {
        // Setup save button
        const saveBtn = document.querySelector('[data-action="save-element"]');
        if (saveBtn) {
            saveBtn.addEventListener('click', () => this.saveElement());
        }

        // Setup cancel button
        const cancelBtn = document.querySelector('[data-action="close-modal"]');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', () => this.closeModal());
        }

        // Setup close button (X)
        const closeBtn = document.querySelector('.modal-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => this.closeModal());
        }
    }

    saveElement() {
        const formData = this.collectFormData();

        // Validate required fields
        if (!this.validateForm(formData)) {
            return;
        }

        // If editing existing element
        if (this.currentElementData && this.currentElementData.title && this.currentElementData.title !== '') {
            // Update existing element
            this.updateExistingElement(formData);
        } else {
            // Create new element
            this.addElementToDay(formData);
        }

        this.closeModal();
        this.showNotification('Elemento Guardado', `${this.getTypeLabel(this.currentElementType)} guardado correctamente.`);
    }

    validateForm(data) {
        // Validate required fields based on element type
        if (data.type === 'total') {
            if (!data.total_amount || data.total_amount === '0') {
                this.showNotification('Error', 'El precio total es obligatorio.', 'error');
                return false;
            }
            if (!data.currency) {
                this.showNotification('Error', 'La moneda es obligatoria.', 'error');
                return false;
            }
        }
        return true;
    }

    collectFormData() {
        const data = { type: this.currentElementType, day: this.currentDay };
        const form = document.getElementById('modal-body');
        const inputs = form.querySelectorAll('input, textarea, select');

        inputs.forEach(input => {
            if (input.type === 'checkbox') {
                data[input.id.replace('-', '_')] = input.checked;
            } else if (input.type === 'file') {
                // Skip file inputs, handled separately
            } else if (input.value.trim()) {
                data[input.id.replace('-', '_')] = input.value.trim();
            }
        });

        // Include uploaded documents for this element type
        if (this.uploadedDocuments[this.currentElementType] && this.uploadedDocuments[this.currentElementType].length > 0) {
            data.documents = this.uploadedDocuments[this.currentElementType].map(doc => doc.id);
        }

        // Include selected hotel data if this is a hotel element
        if (this.currentElementType === 'hotel' && this.selectedHotelData) {
            data.hotel_id = this.selectedHotelData.id;
            data.hotel_name = this.selectedHotelData.name || this.selectedHotelData.hotel_name;
            data.hotel_data = this.selectedHotelData;
        }

        return data;
    }

    updateExistingElement(newData) {
        // Find the existing element to update
        const allItems = document.querySelectorAll('.timeline-item');
        let elementToUpdate = null;

        allItems.forEach(item => {
            const itemData = this.extractItemDataForDisplay(item);
            if (itemData && itemData.title === this.currentElementData.title && itemData.type === this.currentElementData.type) {
                elementToUpdate = item;
            }
        });

        if (elementToUpdate) {
            // Update the element's content
            const titleElement = elementToUpdate.querySelector('.item-title');
            const subtitleElement = elementToUpdate.querySelector('.item-subtitle');

            if (titleElement) {
                titleElement.textContent = this.getElementTitle(newData);
            }
            if (subtitleElement) {
                subtitleElement.textContent = this.getElementSubtitle(newData);
            }

            // Update summaries
            this.updateAllSummaries();
        }
    }

    addElementToDay(data) {
        // Dispatch event to TimelineManager to add the element
        const event = new CustomEvent('addElementToDay', {
            detail: { elementData: data }
        });
        document.dispatchEvent(event);
    }

    getElementTitle(data) {
        switch (data.type) {
            case 'flight':
                return `${data.airline || 'Vuelo'} ${data.flight_number || ''}`.trim();
            case 'hotel':
                return data.hotel_name || 'Hotel';
            case 'activity':
                return data.activity_title || 'Actividad';
            case 'transport':
                return data.transport_type || 'Traslado';
            case 'note':
                return data.note_title || 'Nota';
            case 'summary':
                return data.summary_title || 'Resumen de Itinerario';
            case 'total':
                const currencySymbols = {
                    'USD': '$',
                    'EUR': '€',
                    'CLP': '$',
                    'ARS': '$',
                    'PEN': 'S/',
                    'COP': '$',
                    'MXN': '$'
                };
                const symbol = currencySymbols[data.currency] || data.currency || '$';
                const amount = data.total_amount || '0.00';
                return `${symbol}${parseFloat(amount).toFixed(2)} ${data.currency || 'USD'}`;
            default:
                return 'Elemento';
        }
    }

    getElementSubtitle(data) {
        switch (data.type) {
            case 'flight':
                return `${data.departure_airport || ''} → ${data.arrival_airport || ''}`.replace(' → ', '');
            case 'hotel':
                return `${data.check_in || ''} - ${data.check_out || ''}`.replace(' - ', '');
            case 'activity':
                return data.location || '';
            case 'transport':
                return `${data.pickup_location || ''} → ${data.destination || ''}`.replace(' → ', '');
            case 'summary':
                return 'Resumen automático del viaje';
            case 'total':
                return data.price_breakdown || 'Precio total del viaje';
            default:
                return '';
        }
    }

    extractItemDataForDisplay(item) {
        // Helper method to extract data from DOM element
        const titleElement = item.querySelector('.item-title');
        const subtitleElement = item.querySelector('.item-subtitle');

        return {
            title: titleElement ? titleElement.textContent : '',
            subtitle: subtitleElement ? subtitleElement.textContent : '',
            type: item.dataset.type
        };
    }

    updateAllSummaries() {
        // Dispatch event to SummaryManager
        const event = new CustomEvent('updateAllSummaries');
        document.dispatchEvent(event);
    }

    showNotification(title, message, type = 'success') {
        // Simple notification implementation
        console.log(`${type.toUpperCase()}: ${title} - ${message}`);
        // You can implement a more sophisticated notification system here
    }

    closeModal() {
        const modal = document.getElementById('element-modal');
        modal.style.display = 'none';
        this.currentElementType = null;
        this.currentElementData = {};
        this.currentDay = null;
    }
}
