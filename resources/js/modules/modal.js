// Modal Module - Handles element creation/editing modals
import selectorsData from '../data/selectors.json';
import GooglePlacesAutocomplete from './autocomplete.js';

// Temporary hardcoded element labels and forms until JSON import is fixed
const elementLabelsData = {
  "flight": "Vuelo",
  "hotel": "Hotel",
  "activity": "Actividad",
  "transport": "Transporte",
  "note": "Nota",
  "summary": "Resumen",
  "total": "Total"
};

const elementFormsData = {
  "flight": "<div class=\"form-group\"><label for=\"airline\">Aerolínea</label><select id=\"airline\" class=\"form-input airline-select\" placeholder=\"Ej: Iberia\"><option value=\"\">Seleccionar aerolínea</option></select></div><div class=\"form-group\"><label for=\"flight-number\">Número de Vuelo</label><input type=\"text\" id=\"flight-number\" class=\"form-input\" placeholder=\"Ej: IB1234\"><button type=\"button\" id=\"lookup-flight\" class=\"btn-lookup-flight\" title=\"Buscar información del vuelo\"><i class=\"fas fa-search\"></i> Buscar vuelo</button></div><div class=\"form-row\"><div class=\"form-group\"><label for=\"departure-time\">Hora de Salida</label><input type=\"time\" id=\"departure-time\" class=\"form-input\"></div><div class=\"form-group\"><label for=\"arrival-time\">Hora de Llegada</label><input type=\"time\" id=\"arrival-time\" class=\"form-input\"></div></div><div class=\"form-row\"><div class=\"form-group\"><label for=\"departure-airport\">Aeropuerto de Salida</label><select id=\"departure-airport\" class=\"form-input airport-select\" placeholder=\"Ej: Madrid Barajas\"><option value=\"\">Seleccionar aeropuerto</option></select></div><div class=\"form-group\"><label for=\"arrival-airport\">Aeropuerto de Llegada</label><select id=\"arrival-airport\" class=\"form-input airport-select\" placeholder=\"Ej: París Charles de Gaulle\"><option value=\"\">Seleccionar aeropuerto</option></select></div></div><div class=\"form-group\"><label for=\"confirmation-number\">Número de Confirmación</label><input type=\"text\" id=\"confirmation-number\" class=\"form-input\" placeholder=\"Ej: ABC123\"></div><div class=\"form-group\"><label for=\"flight-documents\">Documentos</label><input type=\"file\" id=\"flight-documents\" class=\"form-input\" multiple accept=\".pdf,.doc,.docx,.txt\"><small class=\"form-text\">Sube archivos PDF, DOC, DOCX o TXT relacionados con el vuelo</small></div>",
  "hotel": "<div class=\"form-group\"><label for=\"hotel-name\">Nombre del Hotel</label><div class=\"autocomplete-input\"><input type=\"text\" id=\"hotel-name\" class=\"form-input hotel-autocomplete\" placeholder=\"Ej: Hotel Central\" autocomplete=\"off\"></div><div id=\"selected-place-info\" class=\"selected-place-info\" style=\"display: none;\"></div><div id=\"selected-place-details\" class=\"selected-place-details\" style=\"display: none;\"></div></div><div class=\"form-row\"><div class=\"form-group\"><label for=\"check-in\">Check-in</label><input type=\"time\" id=\"check-in\" class=\"form-input\"></div><div class=\"form-group\"><label for=\"check-out\">Check-out</label><input type=\"time\" id=\"check-out\" class=\"form-input\"></div></div><div class=\"form-group\"><label for=\"room-type\">Tipo de Habitación</label><input type=\"text\" id=\"room-type\" class=\"form-input\" placeholder=\"Ej: Habitación doble\"></div><div class=\"form-group\"><label for=\"nights\">Noches</label><input type=\"number\" id=\"nights\" class=\"form-input\" min=\"1\" placeholder=\"2\"></div><div class=\"form-group\"><label for=\"hotel-documents\">Documentos</label><input type=\"file\" id=\"hotel-documents\" class=\"form-input\" multiple accept=\".pdf,.doc,.docx,.txt\"><small class=\"form-text\">Sube archivos PDF, DOC, DOCX o TXT relacionados con el hotel</small></div>",
  "activity": "<div class=\"form-group\"><label for=\"activity-title\">Título de la Actividad</label><input type=\"text\" id=\"activity-title\" class=\"form-input\" placeholder=\"Ej: Visita al Louvre\"></div><div class=\"form-row\"><div class=\"form-group\"><label for=\"start-time\">Hora de Inicio</label><input type=\"time\" id=\"start-time\" class=\"form-input\"></div><div class=\"form-group\"><label for=\"end-time\">Hora de Fin</label><input type=\"time\" id=\"end-time\" class=\"form-input\"></div></div><div class=\"form-group\"><label for=\"location\">Ubicación</label><input type=\"text\" id=\"location\" class=\"form-input\" placeholder=\"Ej: Museo del Louvre, París\"></div><div class=\"form-group\"><label for=\"description\">Descripción</label><textarea id=\"description\" class=\"form-input\" rows=\"3\" placeholder=\"Detalles de la actividad...\"></textarea></div>",
  "transport": "<div class=\"form-group\"><label for=\"transport-type\">Tipo de Transporte</label><input type=\"text\" id=\"transport-type\" class=\"form-input\" placeholder=\"Ej: Taxi, Metro, Bus\"></div><div class=\"form-group\"><label for=\"pickup-time\">Hora de Recogida</label><input type=\"time\" id=\"pickup-time\" class=\"form-input\"></div><div class=\"form-group\"><label for=\"pickup-location\">Punto de Recogida</label><input type=\"text\" id=\"pickup-location\" class=\"form-input\" placeholder=\"Ej: Hotel Plaza\"></div><div class=\"form-group\"><label for=\"destination\">Destino</label><input type=\"text\" id=\"destination\" class=\"form-input\" placeholder=\"Ej: Aeropuerto\"></div><div class=\"form-group\"><label for=\"transport-documents\">Documentos</label><input type=\"file\" id=\"transport-documents\" class=\"form-input\" multiple accept=\".pdf,.doc,.docx,.txt\"><small class=\"form-text\">Sube archivos PDF, DOC, DOCX o TXT relacionados con el traslado</small></div>",
  "note": "<div class=\"form-group\"><label for=\"note-title\">Título de la Nota</label><input type=\"text\" id=\"note-title\" class=\"form-input\" placeholder=\"Ej: Recordatorios importantes\"></div><div class=\"form-group\"><label for=\"note-content\">Contenido</label><textarea id=\"note-content\" class=\"form-input\" rows=\"4\" placeholder=\"Escribe tu nota aquí...\"></textarea></div>",
  "summary": "",
  "total": "<div class=\"form-group\"><label for=\"total-amount\">Precio total del viaje *</label><input type=\"number\" id=\"total-amount\" class=\"form-input\" placeholder=\"0\" min=\"0\" step=\"0.01\" required></div><div class=\"form-group\"><label for=\"currency\">Moneda *</label><select id=\"currency\" class=\"form-input\" required><option value=\"\">Seleccionar moneda</option><option value=\"USD\">USD - Dólar Estadounidense</option><option value=\"EUR\">EUR - Euro</option><option value=\"CLP\">CLP - Peso Chileno</option><option value=\"ARS\">ARS - Peso Argentino</option><option value=\"PEN\">PEN - Sol Peruano</option><option value=\"COP\">COP - Peso Colombiano</option><option value=\"MXN\">MXN - Peso Mexicano</option></select></div><div class=\"form-group\"><label class=\"checkbox-label\"><input type=\"checkbox\" id=\"place-at-end\"><span class=\"checkmark\"></span>Colocar al final del itinerario</label><small class=\"form-text\">Si no se marca, se colocará al inicio (después del resumen)</small></div><div class=\"form-group\"><label for=\"price-breakdown\">Desglose del precio (opcional)</label><textarea id=\"price-breakdown\" class=\"form-input\" rows=\"4\" placeholder=\"Ej: Vuelos: $500, Hoteles: $800, Actividades: $300, Transporte: $200\"></textarea></div>"
};

export class ModalManager {
    constructor() {
        this.currentElementType = null;
        this.currentElementData = {};
        this.currentDay = null;
        this.selectedHotelData = null;
        this.isEditing = false;
        this.existingDocuments = [];
        this.uploadedDocuments = {
            flight: [],
            hotel: [],
            transport: []
        };
        this.hotelAutocomplete = null;
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

        // Listen for edit element events
        document.addEventListener('editElement', (e) => {
            console.log('Edit element event received:', e.detail);
            this.showEditElementModal(e.detail.element, e.detail.elementData);
        });

        console.log('ModalManager event listeners setup');
    }

    showElementModal(elementType, dayNumber) {
        console.log('Showing element modal for:', elementType, 'day:', dayNumber);
        this.currentElementType = elementType;
        this.currentDay = dayNumber;
        this.currentElementData = { type: elementType, day: dayNumber };
        this.isEditing = false;
        this.existingDocuments = [];

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

        // Initialize hotel autocomplete if this is a hotel modal
        if (this.currentElementType === 'hotel') {
            // Delay initialization to ensure DOM is ready
            setTimeout(() => {
                this.initializeHotelAutocomplete();
            }, 100);
        }

        modal.style.display = 'block';
        console.log('Modal displayed');
    }

    showEditElementModal(element, elementData) {
        console.log('Showing edit element modal for:', elementData);
        this.currentElementType = elementData.type;
        this.currentDay = elementData.day;
        this.currentElementData = elementData;
        this.editingElement = element; // Store reference to element being edited
        this.isEditing = true;

        // Load existing documents for this element type
        this.loadExistingDocuments(elementData.type);

        const modal = document.getElementById('element-modal');
        const modalTitle = document.getElementById('modal-title');
        const modalBody = document.getElementById('modal-body');

        modalTitle.textContent = `Editar ${this.getTypeLabel(elementData.type)}`;
        modalBody.innerHTML = this.getElementForm(elementData.type);

        // Clear previously uploaded documents
        this.uploadedDocuments[elementData.type] = [];

        this.setupFileUploadListeners();
    // Initialize Select2 first so selects are ready when we populate values
    this.initializeSelect2();

    // Populate form with existing data (after select2 init so dropdowns receive values)
    this.populateFormWithData(elementData);

    this.setupModalButtons();

        modal.style.display = 'block';
        console.log('Edit modal displayed');
    }

    populateFormWithData(data) {
        // Define field mappings for different element types
        const fieldMappings = {
            flight: {
                'airline': 'airline',
                'flight_number': 'flight-number',
                'departure_time': 'departure-time',
                'arrival_time': 'arrival-time',
                'departure_airport': 'departure-airport',
                'arrival_airport': 'arrival-airport',
                'confirmation_number': 'confirmation-number'
            },
            hotel: {
                'hotel_name': 'hotel-name',
                'check_in': 'check-in',
                'check_out': 'check-out',
                'room_type': 'room-type',
                'nights': 'nights'
            },
            activity: {
                'activity_title': 'activity-title',
                'start_time': 'start-time',
                'end_time': 'end-time',
                'location': 'location',
                'description': 'description'
            },
            transport: {
                'transport_type': 'transport-type',
                'pickup_time': 'pickup-time',
                'pickup_location': 'pickup-location',
                'destination': 'destination'
            },
            note: {
                'note_title': 'note-title',
                'note_content': 'note-content'
            }
        };

        const mappings = fieldMappings[data.type] || {};

        // Populate form fields using the mappings
        Object.keys(mappings).forEach(dataKey => {
            const fieldId = mappings[dataKey];
            if (data[dataKey]) {
                const input = document.querySelector(`#modal-body #${fieldId}`);
                if (input) {
                    if (input.type === 'checkbox') {
                        input.checked = data[dataKey];
                    } else if (input.tagName === 'SELECT') {
                        input.value = data[dataKey];
                        // Trigger change for select2 if it's a select element
                        if ($(input).hasClass('select2-hidden-accessible')) {
                            $(input).trigger('change');
                        }
                    } else {
                        input.value = data[dataKey];
                    }
                }
            }
        });

        // Handle special cases for different element types
        if (data.type === 'flight') {
            // Handle flight specific fields
            if (data.airline) {
                const airlineSelect = document.querySelector('#modal-body #airline');
                if (airlineSelect) {
                    airlineSelect.value = data.airline;
                    $(airlineSelect).trigger('change'); // Trigger change for select2
                }
            }
            // Handle airport selects
            if (data.departure_airport) {
                const departureSelect = document.querySelector('#modal-body #departure-airport');
                if (departureSelect) {
                    departureSelect.value = data.departure_airport;
                    $(departureSelect).trigger('change');
                }
            }
            if (data.arrival_airport) {
                const arrivalSelect = document.querySelector('#modal-body #arrival-airport');
                if (arrivalSelect) {
                    arrivalSelect.value = data.arrival_airport;
                    $(arrivalSelect).trigger('change');
                }
            }
        } else if (data.type === 'hotel') {
            // Handle hotel specific fields
            if (data.hotel_name) {
                const hotelInput = document.querySelector('#modal-body #hotel-name');
                if (hotelInput) hotelInput.value = data.hotel_name;
            }

            // Load hotel data for autocomplete
            if (data.hotel_data) {
                this.selectedHotelData = data.hotel_data;
                this.displaySelectedPlaceInfo(data.hotel_data);
            }
        }
    }

    getTypeLabel(type) {
        return elementLabelsData[type] || 'Elemento';
    }

    getElementForm(type) {
        let formHtml = elementFormsData[type] || '<p>Formulario no disponible</p>';

        // Add existing documents section if editing and there are documents
        if (this.isEditing && this.existingDocuments.length > 0) {
            const documentsHtml = this.existingDocuments.map(doc => `
                <div class="existing-document" data-document-id="${doc.id}">
                    <div class="document-info">
                        <i class="fas fa-file"></i>
                        <span class="document-name">${doc.original_name}</span>
                        <span class="document-size">(${this.formatFileSize(doc.size)})</span>
                    </div>
                    <div class="document-actions">
                        <a href="/documents/${doc.id}/download" target="_blank" class="btn-document-view" title="Ver documento">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button type="button" class="btn-document-delete" data-document-id="${doc.id}" title="Eliminar documento">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `).join('');

            formHtml += `
                <div class="form-group">
                    <label>Documentos existentes</label>
                    <div class="existing-documents-list">
                        ${documentsHtml}
                    </div>
                </div>
            `;
        }

        return formHtml;
    }

    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
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

        // Setup listeners for delete document buttons
        const deleteButtons = document.querySelectorAll('#modal-body .btn-document-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', async (e) => {
                const documentId = e.currentTarget.dataset.documentId;
                if (confirm('¿Estás seguro de que quieres eliminar este documento?')) {
                    await this.deleteDocument(documentId);
                }
            });
        });
    }

    async deleteDocument(documentId) {
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            const response = await fetch(`/documents/${documentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                // Remove from existing documents
                this.existingDocuments = this.existingDocuments.filter(doc => doc.id != documentId);

                // Remove from DOM
                const documentElement = document.querySelector(`[data-document-id="${documentId}"]`);
                if (documentElement) {
                    documentElement.remove();
                }

                this.showNotification('Documento Eliminado', 'El documento ha sido eliminado exitosamente.');
            } else {
                const result = await response.json();
                this.showNotification('Error', result.message || 'Error al eliminar el documento.');
            }
        } catch (error) {
            console.error('Error deleting document:', error);
            this.showNotification('Error', 'Error al eliminar el documento.');
        }
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

    loadExistingDocuments(elementType) {
        // Load existing documents for the current trip and element type
        if (window.existingTripData && window.existingTripData.documents) {
            this.existingDocuments = window.existingTripData.documents.filter(doc => doc.type === elementType);
        } else {
            this.existingDocuments = [];
        }
        console.log('Loaded existing documents for', elementType, ':', this.existingDocuments);
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
        if (this.editingElement) {
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
                data[input.id.replace(/-/g, '_')] = input.checked;
            } else if (input.type === 'file') {
                // Skip file inputs, handled separately
            } else if (input.value.trim()) {
                data[input.id.replace(/-/g, '_')] = input.value.trim();
            }
        });

        // Include uploaded documents for this element type
        if (this.uploadedDocuments[this.currentElementType] && this.uploadedDocuments[this.currentElementType].length > 0) {
            data.documents = this.uploadedDocuments[this.currentElementType].map(doc => doc.id);
        }

        // Include selected hotel data if this is a hotel element
        if (this.currentElementType === 'hotel' && this.selectedHotelData) {
            data.hotel_id = this.selectedHotelData.place_id;
            data.hotel_name = this.selectedHotelData.name;
            data.hotel_data = this.selectedHotelData;
            // Include additional Google Places data
            data.place_id = this.selectedHotelData.place_id;
            data.formatted_address = this.selectedHotelData.formatted_address;
            data.rating = this.selectedHotelData.rating;
            data.website = this.selectedHotelData.website;
            data.phone_number = this.selectedHotelData.international_phone_number;
            if (this.selectedHotelData.geometry && this.selectedHotelData.geometry.location) {
                data.latitude = this.selectedHotelData.geometry.location.lat;
                data.longitude = this.selectedHotelData.geometry.location.lng;
            }
        }

        return data;
    }

    updateExistingElement(newData) {
        if (this.editingElement) {
            // Update the element's content directly
            const titleElement = this.editingElement.querySelector('.item-title');
            const subtitleElement = this.editingElement.querySelector('.item-subtitle');

            if (titleElement) {
                titleElement.textContent = this.getElementTitle(newData);
            }
            if (subtitleElement) {
                subtitleElement.textContent = this.getElementSubtitle(newData);
            }

            // Update data attributes
            Object.keys(newData).forEach(key => {
                if (key !== 'type' && key !== 'day') {
                    this.editingElement.setAttribute(`data-${key.replace(/_/g, '-')}`, newData[key]);
                }
            });

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

    /**
     * Initialize Google Places autocomplete for hotel input
     */
    async initializeHotelAutocomplete() {
        const hotelInput = document.querySelector('#modal-body #hotel-name');
        if (!hotelInput) {
            console.error('Hotel input not found');
            return;
        }

        console.log('Initializing hotel autocomplete for input:', hotelInput);

        // Destroy existing instance if any
        if (this.hotelAutocomplete) {
            console.log('Destroying existing autocomplete instance');
            this.hotelAutocomplete.destroy();
        }

        // Create new autocomplete instance
        this.hotelAutocomplete = new GooglePlacesAutocomplete();

        // Set callbacks
        this.hotelAutocomplete.setCallbacks({
            onPlaceSelect: (placeData) => {
                console.log('Hotel place selected:', placeData);
                this.handleHotelPlaceSelect(placeData);
            },
            onPlaceDetails: (details) => {
                console.log('Hotel details received:', details);
                this.handleHotelPlaceDetails(details);
            },
            onError: (error) => {
                console.error('Hotel autocomplete error:', error);
                this.showNotification('Error', 'Error al cargar sugerencias de hoteles', 'error');
            }
        });

        // Initialize with lodging types
        console.log('Initializing autocomplete with establishment types...');
        const success = await this.hotelAutocomplete.init(hotelInput, {
            types: [] // Try without types restriction first
        });

        if (success) {
            console.log('Hotel autocomplete initialized successfully');
        } else {
            console.error('Failed to initialize hotel autocomplete');
        }
    }

    /**
     * Handle hotel place selection from autocomplete
     */
    handleHotelPlaceSelect(placeData) {
        console.log('Hotel selected - placeData:', placeData);
        console.log('Hotel name:', placeData.name);

        // Store selected hotel data
        this.selectedHotelData = placeData;

        // Update input value
        const hotelInput = document.querySelector('#modal-body #hotel-name');
        console.log('Hotel input element:', hotelInput);
        if (hotelInput) {
            console.log('Setting input value to:', placeData.name);
            hotelInput.value = placeData.name;
            console.log('Input value after setting:', hotelInput.value);
        } else {
            console.error('Hotel input not found when trying to update value');
        }

        // Display selected place information
        this.displaySelectedPlaceInfo(placeData);
    }

    /**
     * Handle detailed hotel place information from backend
     */
    handleHotelPlaceDetails(details) {
        console.log('Hotel details received:', details);

        // Store detailed hotel data
        this.selectedHotelDetails = details;

        // Update display with additional information
        this.displaySelectedPlaceDetails(details);
    }

    /**
     * Display information about the selected place
     */
    displaySelectedPlaceInfo(placeData) {
        const infoContainer = document.getElementById('selected-place-info');
        if (!infoContainer) return;

        // Generate star rating HTML
        const ratingHtml = placeData.rating ? this.generateStarRating(placeData.rating) : '';

        // Generate price level HTML
        const priceHtml = placeData.price_level ? this.generatePriceLevel(placeData.price_level) : '';

        infoContainer.innerHTML = `
            <div class="place-name">${placeData.name}</div>
            <div class="place-address">${placeData.formatted_address || ''}</div>
            <div class="place-details">
                ${ratingHtml ? `<span class="place-rating">${ratingHtml} <span class="rating-text">(${placeData.rating})</span></span>` : ''}
                ${priceHtml ? `<span class="place-price">${priceHtml}</span>` : ''}
            </div>
        `;

        infoContainer.style.display = 'block';
    }

    /**
     * Display detailed information about the selected place
     */
    displaySelectedPlaceDetails(details) {
        const detailsContainer = document.getElementById('selected-place-details');
        if (!detailsContainer) return;

        // Generate reviews HTML
        const reviewsHtml = details.reviews ? this.generateReviewsHtml(details.reviews) : '';

        // Generate photos HTML
        const photosHtml = details.photos ? this.generatePhotosHtml(details.photos) : '';

        detailsContainer.innerHTML = `
            <div class="place-website">
                ${details.website ? `<a href="${details.website}" target="_blank" class="website-link"><i class="fas fa-globe"></i> Sitio web</a>` : ''}
            </div>
            <div class="place-phone">
                ${details.international_phone_number ? `<i class="fas fa-phone"></i> ${details.international_phone_number}` : ''}
            </div>
            ${reviewsHtml ? `<div class="place-reviews">${reviewsHtml}</div>` : ''}
            ${photosHtml ? `<div class="place-photos">${photosHtml}</div>` : ''}
        `;

        detailsContainer.style.display = 'block';
    }

    /**
     * Generate star rating HTML
     */
    generateStarRating(rating) {
        const fullStars = Math.floor(rating);
        const hasHalfStar = rating % 1 >= 0.5;
        const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);

        let stars = '';

        // Full stars
        for (let i = 0; i < fullStars; i++) {
            stars += '<i class="fas fa-star star"></i>';
        }

        // Half star
        if (hasHalfStar) {
            stars += '<i class="fas fa-star-half-alt star"></i>';
        }

        // Empty stars
        for (let i = 0; i < emptyStars; i++) {
            stars += '<i class="far fa-star star"></i>';
        }

        return stars;
    }

    /**
     * Generate price level HTML
     */
    generatePriceLevel(priceLevel) {
        let price = '';
        for (let i = 0; i < priceLevel; i++) {
            price += '$';
        }
        return price;
    }

    /**
     * Generate reviews HTML
     */
    generateReviewsHtml(reviews) {
        if (!reviews || reviews.length === 0) return '';

        const recentReviews = reviews.slice(0, 3); // Show only first 3 reviews
        let html = '<h4>Reseñas recientes:</h4>';

        recentReviews.forEach(review => {
            const rating = this.generateStarRating(review.rating);
            html += `
                <div class="review">
                    <div class="review-header">
                        <strong>${review.author_name}</strong>
                        <span class="review-rating">${rating}</span>
                    </div>
                    <p class="review-text">${review.text}</p>
                    <small class="review-date">${new Date(review.time * 1000).toLocaleDateString()}</small>
                </div>
            `;
        });

        return html;
    }

    /**
     * Generate photos HTML
     */
    generatePhotosHtml(photos) {
        if (!photos || photos.length === 0) return '';

        let html = '<h4>Fotos:</h4><div class="photos-grid">';

        photos.slice(0, 6).forEach(photo => { // Show only first 6 photos
            const photoUrl = photo.getUrl ? photo.getUrl({ maxWidth: 200, maxHeight: 200 }) : photo.photo_reference;
            html += `<img src="${photoUrl}" alt="Hotel photo" class="place-photo">`;
        });

        html += '</div>';
        return html;
    }

    closeModal() {
        // Clear form data and reset state without saving
        const modal = document.getElementById('element-modal');
        modal.style.display = 'none';

        // Clean up hotel autocomplete
        if (this.hotelAutocomplete) {
            this.hotelAutocomplete.destroy();
            this.hotelAutocomplete = null;
        }

        this.currentElementType = null;
        this.currentElementData = {};
        this.currentDay = null;
        this.editingElement = null; // Reset editing element reference
        this.selectedHotelData = null;
    }
}
