@extends('layouts.app')

@section('title', 'Viantryp - Editor de Itinerarios')

@section('content')

    <x-header :showActions="true" :backUrl="'#'" :backOnclick="'showUnsavedChangesModal()'" :actions="[
        ['url' => '#', 'text' => 'Guardar', 'class' => 'btn-save', 'icon' => 'fas fa-save', 'onclick' => 'saveTrip()'],
        ['url' => '#', 'text' => 'Vista Previa', 'class' => 'btn-preview', 'icon' => 'fas fa-eye', 'onclick' => 'previewTrip()'],
        ['url' => '#', 'text' => 'Descarga PDF', 'class' => 'btn-pdf', 'icon' => 'fas fa-file-pdf', 'onclick' => 'downloadPDF()']
    ]" />

    <!-- New Trip Modal Component -->
    <x-new-trip-modal />

    <div class="editor-container" id="editor-container">
        <!-- Sidebar Component -->
        <x-sidebar />

        <!-- Main Content Area -->
        <div class="editor-main">
            <div class="main-content">
                <!-- Trip Header Component -->
                <x-trip-header :trip="$trip ?? null" />

                <!-- Timeline Component -->
                <x-timeline :trip="$trip ?? null" />

                <!-- Add Day Button -->
                <div class="add-day-section">
                    <button class="btn-add-day" onclick="addNewDay()">
                        <i class="fas fa-plus"></i>
                        Agregar Día
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Element Modal Component -->
    <x-element-modal />

    <!-- Unsaved Changes Modal Component -->
    <x-unsaved-changes-modal />
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/editor.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush

@push('scripts')
<script>
    // Pass trip data to JavaScript
    @if(isset($trip))
        window.existingTripData = @json($trip->toArray());
    @else
        window.existingTripData = null;
    @endif
</script>
@endpush

@push('scripts')
<script>
    let currentElementType = null;
    let currentElementData = {};
    let currentDay = null;
    let selectedHotelData = null; // Store complete hotel data

    // Initialize the page
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded - initializing page');

        // Check if we're on the create route
        const isNewTrip = window.location.pathname.includes('/create');
        console.log('DOM loaded, current path:', window.location.pathname);
        console.log('Is new trip:', isNewTrip);

        const modal = document.getElementById('new-trip-modal');
        const editor = document.getElementById('editor-container');

        console.log('Modal element:', modal);
        console.log('Editor element:', editor);

        if (isNewTrip) {
            // Show modal for new trip
            console.log('Showing new trip modal');
            if (modal) {
                modal.classList.add('show');
                console.log('Modal classes after adding show:', modal.className);
            }
            if (editor) {
                editor.style.display = 'none';
            }
        } else {
            // Hide modal for existing trip
            console.log('Hiding modal, showing editor');
            if (modal) {
                modal.classList.remove('show');
            }
            if (editor) {
                editor.style.display = 'flex';
            }

            // Load existing trip data if available
            if (window.existingTripData) {
                console.log('Loading existing trip data:', window.existingTripData);
                loadExistingTripData(window.existingTripData);
            }
        }

        // Add event listeners to draggable elements
        const draggableElements = document.querySelectorAll('.element-category');
        draggableElements.forEach(element => {
            element.addEventListener('dragstart', drag);

            // Add click handlers for summary and total elements
            if (element.dataset.type === 'summary') {
                element.addEventListener('click', function(e) {
                    e.preventDefault();
                    handleSummaryClick(element);
                });
            }
            if (element.dataset.type === 'total') {
                element.addEventListener('click', function(e) {
                    e.preventDefault();
                    handleTotalClick(element);
                });
            }
        });

        // Track changes for unsaved changes warning
        let hasUnsavedChanges = false;
        const originalData = collectAllTripItems();

        // Function to check if there are unsaved changes
        function checkForChanges() {
            const currentData = collectAllTripItems();
            const currentTitle = document.getElementById('trip-title').value;
            const currentStartDate = document.getElementById('start-date').value;

            // Compare with original data
            const hasDataChanges = JSON.stringify(currentData) !== JSON.stringify(originalData);
            const hasTitleChanges = currentTitle !== (document.getElementById('trip-title').defaultValue || '');
            const hasDateChanges = currentStartDate !== (document.getElementById('start-date').defaultValue || '');

            hasUnsavedChanges = hasDataChanges || hasTitleChanges || hasDateChanges;
        }

        // Add change listeners to track modifications
        document.getElementById('trip-title').addEventListener('input', function() {
            checkForChanges();
            // Update all summaries and totals when title changes
            updateAllSummaries();
        });
        document.getElementById('start-date').addEventListener('change', checkForChanges);

        // Warn about unsaved changes when leaving the page
        window.addEventListener('beforeunload', function(e) {
            checkForChanges();
            if (hasUnsavedChanges) {
                e.preventDefault();
                e.returnValue = 'Tienes cambios sin guardar. ¿Estás seguro de que quieres salir?';
                return e.returnValue;
            }
        });

        // Handle back button and other navigation
        window.addEventListener('popstate', function(e) {
            checkForChanges();
            if (hasUnsavedChanges) {
                const shouldLeave = confirm('Tienes cambios sin guardar. ¿Estás seguro de que quieres salir?');
                if (!shouldLeave) {
                    history.pushState(null, null, window.location.pathname);
                }
            }
        });
    });

    function loadExistingTripData(tripData) {
        console.log('Loading trip data:', tripData);

        // Set trip title
        if (tripData.title) {
            document.getElementById('trip-title').value = tripData.title;
        }

        // Set start date
        if (tripData.start_date) {
            document.getElementById('start-date').value = tripData.start_date;
        }

        // Load trip items
        if (tripData.items_data) {
            // Group items by day
            const itemsByDay = {};
            tripData.items_data.forEach(item => {
                const day = item.day || 1;
                if (!itemsByDay[day]) {
                    itemsByDay[day] = [];
                }
                itemsByDay[day].push(item);
            });

            // Create days and add items
            Object.keys(itemsByDay).forEach(dayNum => {
                const dayNumber = parseInt(dayNum);

                // Ensure the day exists
                while (document.querySelectorAll('.day-card').length < dayNumber) {
                    addDay();
                }

                // Add items to the day
                const dayCard = document.querySelectorAll('.day-card')[dayNumber - 1];
                if (dayCard) {
                    itemsByDay[dayNum].forEach(item => {
                        // Create element data for the item
                        const elementData = {
                            type: item.type,
                            day: dayNumber,
                            ...item
                        };

                        // Add the element to the day
                        const elementDiv = createElementDiv(elementData);
                        const timelineItems = dayCard.querySelector('.timeline-items');
                        if (timelineItems) {
                            timelineItems.appendChild(elementDiv);
                        }
                    });
                }
            });
        }

        // Update summaries and totals
        updateAllSummaries();
        hasUnsavedChanges = false;
    }

    // Drag and Drop functionality
    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drag(ev) {
        ev.dataTransfer.setData("text", ev.target.dataset.type);
    }

    function drop(ev) {
        ev.preventDefault();
        const elementType = ev.dataTransfer.getData("text");
        const dayElement = ev.currentTarget.closest('.day-card');
        const dayNumber = parseInt(dayElement.dataset.day);

        currentDay = dayNumber;
        currentElementType = elementType;
        currentElementData = { type: elementType, day: dayNumber };

        // Special handling for summary - create directly without modal
        if (elementType === 'summary') {
            handleSummaryClick();
            return;
        }

        const modalTitle = document.getElementById('modal-title');
        const modalBody = document.getElementById('modal-body');

        modalTitle.textContent = `Agregar ${getTypeLabel(elementType)}`;
        modalBody.innerHTML = getElementForm(elementType);

        // Clear previously uploaded documents for this type
        uploadedDocuments[elementType] = [];

        setupFileUploadListeners();

        // Initialize Select2 for the modal form
        initializeSelect2();

        // Add event listener for flight lookup button
        setTimeout(() => {
            const lookupBtn = document.getElementById('lookup-flight');
            if (lookupBtn) {
                lookupBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    lookupFlightInfo();
                });
            }

            // Add event listener for hotel search button
            const hotelSearchBtn = document.getElementById('search-hotels');
            if (hotelSearchBtn) {
                hotelSearchBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    searchHotels();
                });
            }
        }, 100);

        document.getElementById('element-modal').style.display = 'block';
    }


    function addElementToDay(day) {
        showElementTypeSelection(day);
    }

    function showElementTypeSelection(day) {
        currentDay = day;
        const modal = document.getElementById('element-modal');
        const modalTitle = document.getElementById('modal-title');
        const modalBody = document.getElementById('modal-body');

        modalTitle.textContent = 'Seleccionar Tipo de Elemento';
        modalBody.innerHTML = `
            <div class="element-type-selection">
                <div class="element-type-grid">
                    <button class="element-type-btn" onclick="selectElementType('flight')">
                        <div class="element-type-icon flight-icon">
                            <i class="fas fa-plane"></i>
                        </div>
                        <span>Vuelo</span>
                    </button>
                    <button class="element-type-btn" onclick="selectElementType('hotel')">
                        <div class="element-type-icon hotel-icon">
                            <i class="fas fa-bed"></i>
                        </div>
                        <span>Hotel</span>
                    </button>
                    <button class="element-type-btn" onclick="selectElementType('activity')">
                        <div class="element-type-icon activity-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <span>Actividad</span>
                    </button>
                    <button class="element-type-btn" onclick="selectElementType('transport')">
                        <div class="element-type-icon transport-icon">
                            <i class="fas fa-car"></i>
                        </div>
                        <span>Traslado</span>
                    </button>
                    <button class="element-type-btn" onclick="selectElementType('note')">
                        <div class="element-type-icon note-icon">
                            <i class="fas fa-sticky-note"></i>
                        </div>
                        <span>Nota</span>
                    </button>
                    <button class="element-type-btn" onclick="selectElementType('summary')">
                        <div class="element-type-icon summary-icon">
                            <i class="fas fa-list-check"></i>
                        </div>
                        <span>Resumen</span>
                    </button>
                    <button class="element-type-btn" onclick="selectElementType('total')">
                        <div class="element-type-icon total-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <span>Valor Total</span>
                    </button>
                </div>
            </div>
        `;

        modal.style.display = 'block';
    }

    function selectElementType(type) {
        currentElementType = type;
        currentElementData = { type: type, day: currentDay };

        // Special handling for summary - create directly without modal
        if (type === 'summary') {
            handleSummaryClick();
            closeModal();
            return;
        }

        const modalTitle = document.getElementById('modal-title');
        const modalBody = document.getElementById('modal-body');

        modalTitle.textContent = `Agregar ${getTypeLabel(type)}`;
        modalBody.innerHTML = getElementForm(type);

        // Clear previously uploaded documents for this type
        uploadedDocuments[type] = [];

        setupFileUploadListeners();

        // Initialize Select2 for the modal form
        initializeSelect2();

        // Add event listener for flight lookup button
        setTimeout(() => {
            const lookupBtn = document.getElementById('lookup-flight');
            if (lookupBtn) {
                lookupBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    lookupFlightInfo();
                });
            }

            // Add event listener for hotel search button
            const hotelSearchBtn = document.getElementById('search-hotels');
            if (hotelSearchBtn) {
                hotelSearchBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    searchHotels();
                });
            }
        }, 100);

        // Add event listener for flight lookup
        const lookupBtn = document.getElementById('lookup-flight');
        console.log('Setting up flight lookup button listener, button found:', !!lookupBtn);
        if (lookupBtn) {
            lookupBtn.addEventListener('click', function(e) {
                console.log('Flight lookup button clicked!');
                e.preventDefault();
                lookupFlightInfo();
            });
            console.log('Flight lookup button listener added');
        } else {
            console.error('Flight lookup button not found in modal');
        }
    }

    function getTypeLabel(type) {
        const labels = {
            'flight': 'Vuelo',
            'hotel': 'Hotel',
            'activity': 'Actividad',
            'transport': 'Traslado',
            'note': 'Nota',
            'summary': 'Resumen de Itinerario',
            'total': 'Valor Total'
        };
        return labels[type] || 'Elemento';
    }

    function getElementForm(type) {
        const forms = {
            'flight': `
                <div class="form-group">
                    <label for="airline">Aerolínea</label>
                    <select id="airline" class="form-input airline-select" placeholder="Ej: Iberia">
                        <option value="">Seleccionar aerolínea</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="flight-number">Número de Vuelo</label>
                    <input type="text" id="flight-number" class="form-input" placeholder="Ej: IB1234">
                    <button type="button" id="lookup-flight" class="btn-lookup-flight" title="Buscar información del vuelo">
                        <i class="fas fa-search"></i> Buscar vuelo
                    </button>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="departure-time">Hora de Salida</label>
                        <input type="time" id="departure-time" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="arrival-time">Hora de Llegada</label>
                        <input type="time" id="arrival-time" class="form-input">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="departure-airport">Aeropuerto de Salida</label>
                        <select id="departure-airport" class="form-input airport-select" placeholder="Ej: Madrid Barajas">
                            <option value="">Seleccionar aeropuerto</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="arrival-airport">Aeropuerto de Llegada</label>
                        <select id="arrival-airport" class="form-input airport-select" placeholder="Ej: París Charles de Gaulle">
                            <option value="">Seleccionar aeropuerto</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirmation-number">Número de Confirmación</label>
                    <input type="text" id="confirmation-number" class="form-input" placeholder="Ej: ABC123">
                </div>
                <div class="form-group">
                    <label for="flight-documents">Documentos</label>
                    <input type="file" id="flight-documents" class="form-input" multiple accept=".pdf,.doc,.docx,.txt">
                    <small class="form-text">Sube archivos PDF, DOC, DOCX o TXT relacionados con el vuelo</small>
                </div>
            `,
            'hotel': `
                <div class="form-group">
                    <label for="hotel-name">Nombre del Hotel</label>
                    <select id="hotel-name" class="form-input hotel-select" placeholder="Buscar hotel...">
                        <option value="">Seleccionar hotel</option>
                    </select>
                    <button type="button" id="search-hotels" class="btn-search-hotels" title="Buscar hoteles en la ubicación">
                        <i class="fas fa-search"></i> Buscar Hoteles
                    </button>
                </div>
                <div id="hotel-results" class="hotel-results" style="display: none;">
                    <div id="hotel-list" class="hotel-list"></div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="check-in">Check-in</label>
                        <input type="time" id="check-in" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="check-out">Check-out</label>
                        <input type="time" id="check-out" class="form-input">
                    </div>
                </div>
                <div class="form-group">
                    <label for="room-type">Tipo de Habitación</label>
                    <input type="text" id="room-type" class="form-input" placeholder="Ej: Habitación doble">
                </div>
                <div class="form-group">
                    <label for="nights">Noches</label>
                    <input type="number" id="nights" class="form-input" min="1" placeholder="2">
                </div>
                <div class="form-group">
                    <label for="hotel-documents">Documentos</label>
                    <input type="file" id="hotel-documents" class="form-input" multiple accept=".pdf,.doc,.docx,.txt">
                    <small class="form-text">Sube archivos PDF, DOC, DOCX o TXT relacionados con el hotel</small>
                </div>
            `,
            'activity': `
                <div class="form-group">
                    <label for="activity-title">Título de la Actividad</label>
                    <input type="text" id="activity-title" class="form-input" placeholder="Ej: Visita al Louvre">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="start-time">Hora de Inicio</label>
                        <input type="time" id="start-time" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="end-time">Hora de Fin</label>
                        <input type="time" id="end-time" class="form-input">
                    </div>
                </div>
                <div class="form-group">
                    <label for="location">Ubicación</label>
                    <input type="text" id="location" class="form-input" placeholder="Ej: Museo del Louvre, París">
                </div>
                <div class="form-group">
                    <label for="description">Descripción</label>
                    <textarea id="description" class="form-input" rows="3" placeholder="Detalles de la actividad..."></textarea>
                </div>
            `,
            'transport': `
                <div class="form-group">
                    <label for="transport-type">Tipo de Transporte</label>
                    <input type="text" id="transport-type" class="form-input" placeholder="Ej: Taxi, Metro, Bus">
                </div>
                <div class="form-group">
                    <label for="pickup-time">Hora de Recogida</label>
                    <input type="time" id="pickup-time" class="form-input">
                </div>
                <div class="form-group">
                    <label for="pickup-location">Punto de Recogida</label>
                    <input type="text" id="pickup-location" class="form-input" placeholder="Ej: Hotel Plaza">
                </div>
                <div class="form-group">
                    <label for="destination">Destino</label>
                    <input type="text" id="destination" class="form-input" placeholder="Ej: Aeropuerto">
                </div>
                <div class="form-group">
                    <label for="transport-documents">Documentos</label>
                    <input type="file" id="transport-documents" class="form-input" multiple accept=".pdf,.doc,.docx,.txt">
                    <small class="form-text">Sube archivos PDF, DOC, DOCX o TXT relacionados con el traslado</small>
                </div>
            `,
            'note': `
                <div class="form-group">
                    <label for="note-title">Título de la Nota</label>
                    <input type="text" id="note-title" class="form-input" placeholder="Ej: Recordatorios importantes">
                </div>
                <div class="form-group">
                    <label for="note-content">Contenido</label>
                    <textarea id="note-content" class="form-input" rows="4" placeholder="Escribe tu nota aquí..."></textarea>
                </div>
            `,
            'summary': ``,
            'total': `
                <div class="form-group">
                    <label for="total-amount">Precio total del viaje *</label>
                    <input type="number" id="total-amount" class="form-input" placeholder="0" min="0" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="currency">Moneda *</label>
                    <select id="currency" class="form-input" required>
                        <option value="">Seleccionar moneda</option>
                        <option value="USD">USD - Dólar Estadounidense</option>
                        <option value="EUR">EUR - Euro</option>
                        <option value="CLP">CLP - Peso Chileno</option>
                        <option value="ARS">ARS - Peso Argentino</option>
                        <option value="PEN">PEN - Sol Peruano</option>
                        <option value="COP">COP - Peso Colombiano</option>
                        <option value="MXN">MXN - Peso Mexicano</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" id="place-at-end">
                        <span class="checkmark"></span>
                        Colocar al final del itinerario
                    </label>
                    <small class="form-text">Si no se marca, se colocará al inicio (después del resumen)</small>
                </div>
                <div class="form-group">
                    <label for="price-breakdown">Desglose del precio (opcional)</label>
                    <textarea id="price-breakdown" class="form-input" rows="4" placeholder="Ej: Vuelos: $500, Hoteles: $800, Actividades: $300, Transporte: $200"></textarea>
                </div>
            `
        };

        return forms[type] || '<p>Formulario no disponible</p>';
    }

    // Track uploaded documents for each element type
    let uploadedDocuments = {
        flight: [],
        hotel: [],
        transport: []
    };

    async function uploadDocument(file, type) {
        const tripId = getCurrentTripId();
        if (!tripId) {
            showNotification('Error', 'No se pudo determinar el ID del viaje.');
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
                uploadedDocuments[type].push(result.document);
                showNotification('Documento Subido', 'El documento se ha subido exitosamente.');
                return true;
            } else {
                showNotification('Error', result.message || 'Error al subir el documento.');
                return false;
            }
        } catch (error) {
            console.error('Error uploading document:', error);
            showNotification('Error', 'Error al subir el documento.');
            return false;
        }
    }

    function getCurrentTripId() {
        const currentPath = window.location.pathname;
        const urlParts = currentPath.split('/').filter(part => part !== '');
        if (urlParts.length >= 3 && urlParts[1] === 'trips' && !isNaN(urlParts[2])) {
            return urlParts[2];
        }
        return null;
    }

    function setupFileUploadListeners() {
        // Setup listeners for file inputs
        const fileInputs = document.querySelectorAll('#modal-body input[type="file"]');
        fileInputs.forEach(input => {
            input.addEventListener('change', async function(e) {
                const files = e.target.files;
                if (files.length > 0) {
                    const type = currentElementType; // flight, hotel, transport
                    for (let file of files) {
                        await uploadDocument(file, type);
                    }
                }
            });
        });
    }

    function saveElement() {
        const formData = collectFormData();

        // Validate required fields
        if (!validateForm(formData)) {
            return;
        }

        // If editing existing element
        if (currentElementData && currentElementData.title && currentElementData.title !== '') {
            // Update existing element
            updateExistingElement(formData);
        } else {
            // Create new element
            addElementToDay(formData);
        }

        closeModal();
        showNotification('Elemento Guardado', `${getTypeLabel(currentElementType)} guardado correctamente.`);
    }

    function validateForm(data) {
        // Validate required fields based on element type
        if (data.type === 'total') {
            if (!data.total_amount || data.total_amount === '0') {
                showNotification('Error', 'El precio total es obligatorio.', 'error');
                return false;
            }
            if (!data.currency) {
                showNotification('Error', 'La moneda es obligatoria.', 'error');
                return false;
            }
        }
        return true;
    }

    function updateExistingElement(newData) {
        // Find the existing element to update
        const allItems = document.querySelectorAll('.timeline-item');
        let elementToUpdate = null;

        allItems.forEach(item => {
            const itemData = extractItemDataForDisplay(item);
            if (itemData && itemData.title === currentElementData.title && itemData.type === currentElementData.type) {
                elementToUpdate = item;
            }
        });

        if (elementToUpdate) {
            // Update the element's content
            const titleElement = elementToUpdate.querySelector('.item-title');
            const subtitleElement = elementToUpdate.querySelector('.item-subtitle');

            if (titleElement) {
                titleElement.textContent = getElementTitle(newData);
            }
            if (subtitleElement) {
                subtitleElement.textContent = getElementSubtitle(newData);
            }

            // Update summaries
            updateAllSummaries();
        }
    }

    function fillFormWithData(data) {
        const form = document.getElementById('modal-body');
        const inputs = form.querySelectorAll('input, textarea, select');

        inputs.forEach(input => {
            const fieldName = input.id.replace('-', '_');
            if (data[fieldName] !== undefined) {
                if (input.type === 'checkbox') {
                    input.checked = data[fieldName];
                } else {
                    input.value = data[fieldName];
                }
            }
        });

        // Special handling for hotel select when editing
        if (data.type === 'hotel' && data.hotel_name && data.hotel_id) {
            const hotelSelect = document.getElementById('hotel-name');
            if (hotelSelect) {
                // Create option with the saved hotel data
                const option = document.createElement('option');
                option.value = data.hotel_id;
                option.text = data.hotel_name;
                option.selected = true;

                // Clear existing options and add the selected one
                hotelSelect.innerHTML = '';
                hotelSelect.appendChild(option);

                // Update Select2
                $(hotelSelect).trigger('change');

                // Restore hotel data for later use
                selectedHotelData = data.hotel_data || null;
            }
        }
    }

    function collectFormData() {
        const data = { type: currentElementType, day: currentDay };
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
        if (uploadedDocuments[currentElementType] && uploadedDocuments[currentElementType].length > 0) {
            data.documents = uploadedDocuments[currentElementType].map(doc => doc.id);
        }

        // Include selected hotel data if this is a hotel element
        if (currentElementType === 'hotel' && selectedHotelData) {
            data.hotel_id = selectedHotelData.id;
            data.hotel_name = selectedHotelData.name || selectedHotelData.hotel_name;
            data.hotel_data = selectedHotelData;
        }

        return data;
    }

    function addElementToDay(data) {
        // Special handling for total element positioning
        if (data.type === 'total') {
            const daysContainer = document.getElementById('days-container');
            if (!daysContainer) return;

            // Create element
            const elementDiv = createElementDiv(data);

            if (data.place_at_end) {
                // Place at the end of all days
                daysContainer.appendChild(elementDiv);
            } else {
                // Place at the beginning (after summary if exists)
                const firstDay = daysContainer.querySelector('.day-card');
                if (firstDay) {
                    daysContainer.insertBefore(elementDiv, firstDay);
                } else {
                    daysContainer.appendChild(elementDiv);
                }
            }

            // Update summaries after adding element
            updateAllSummaries();
            return;
        }

        const dayCard = document.querySelector(`[data-day="${data.day}"]`);
        if (!dayCard) return;

        const dayContent = dayCard.querySelector('.day-content');

        // Hide the add button and instruction
        const addBtn = dayContent.querySelector('.add-element-btn');
        const instruction = dayContent.querySelector('.drag-instruction');
        if (addBtn) addBtn.style.display = 'none';
        if (instruction) instruction.style.display = 'none';

        // Create element
        const elementDiv = createElementDiv(data);
        dayContent.appendChild(elementDiv);

        // Update summaries after adding element
        updateAllSummaries();
    }

    function createElementDiv(data) {
        const elementDiv = document.createElement('div');
        elementDiv.className = `timeline-item ${data.type}`;

        // Add flight data as attributes if this is a flight element
        if (data.type === 'flight') {
            elementDiv.setAttribute('data-airline', data.airline || '');
            elementDiv.setAttribute('data-flight-number', data.flight_number || '');
            elementDiv.setAttribute('data-departure-airport', data.departure_airport || '');
            elementDiv.setAttribute('data-arrival-airport', data.arrival_airport || '');
            elementDiv.setAttribute('data-departure-time', data.departure_time || '');
            elementDiv.setAttribute('data-arrival-time', data.arrival_time || '');
            elementDiv.setAttribute('data-confirmation-number', data.confirmation_number || '');
        }

        // Add hotel data as attributes if this is a hotel element
        if (data.type === 'hotel' && data.hotel_id) {
            elementDiv.setAttribute('data-hotel-id', data.hotel_id);
            elementDiv.setAttribute('data-hotel-data', JSON.stringify(data.hotel_data));
            elementDiv.setAttribute('data-check-in', data.check_in || '');
            elementDiv.setAttribute('data-check-out', data.check_out || '');
            elementDiv.setAttribute('data-room-type', data.room_type || '');
            elementDiv.setAttribute('data-nights', data.nights || 1);
        }

        elementDiv.innerHTML = `
            <div class="item-header">
                <div class="item-icon icon-${data.type}">
                    <i class="${getIcon(data.type)}"></i>
                </div>
                <div class="item-info">
                    <div class="item-type">${getTypeLabel(data.type)}</div>
                    <div class="item-title">${getElementTitle(data)}</div>
                    <div class="item-subtitle">${getElementSubtitle(data)}</div>
                </div>
                <div class="item-actions">
                    <button class="action-btn" onclick="editElement(this)" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn btn-danger" onclick="deleteElement(this)" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        // Clear selected hotel data after use
        if (data.type === 'hotel') {
            selectedHotelData = null;
        }

        return elementDiv;
    }

    function getElementTitle(data) {
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

    function getElementSubtitle(data) {
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

    function getIconClass(type) {
        const classes = {
            'flight': 'icon-flight',
            'hotel': 'icon-hotel',
            'activity': 'icon-activity',
            'transport': 'icon-transport',
            'note': 'icon-note',
            'summary': 'icon-summary',
            'total': 'icon-total'
        };
        return classes[type] || 'icon-note';
    }

    function getIcon(type) {
        const icons = {
            'flight': 'fas fa-plane',
            'hotel': 'fas fa-bed',
            'activity': 'fas fa-map-marker-alt',
            'transport': 'fas fa-car',
            'note': 'fas fa-sticky-note',
            'summary': 'fas fa-list-check',
            'total': 'fas fa-dollar-sign'
        };
        return icons[type] || 'fas fa-sticky-note';
    }

    function closeModal() {
        document.getElementById('element-modal').style.display = 'none';
        currentElementType = null;
        currentElementData = {};
        currentDay = null;
    }

    function editElement(button) {
        const itemElement = button.closest('.timeline-item');
        if (!itemElement) return;

        const itemData = extractItemDataForDisplay(itemElement);
        if (!itemData) return;

        // Set current element data for editing
        currentElementType = itemData.type;
        currentElementData = itemData;
        currentDay = itemData.day || 1;

        // Show modal with form
        const modalTitle = document.getElementById('modal-title');
        const modalBody = document.getElementById('modal-body');

        modalTitle.textContent = `Editar ${getTypeLabel(itemData.type)}`;
        modalBody.innerHTML = getElementForm(itemData.type);
        setupFileUploadListeners();

        // Fill form with existing data
        fillFormWithData(itemData);

        // Initialize Select2 for the modal form
        initializeSelect2();

        // Add event listener for flight lookup button
        setTimeout(() => {
            const lookupBtn = document.getElementById('lookup-flight');
            if (lookupBtn) {
                lookupBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    lookupFlightInfo();
                });
            }
        }, 100);

        document.getElementById('element-modal').style.display = 'block';
    }

    function deleteElement(button) {
        if (confirm('¿Estás seguro de que quieres eliminar este elemento?')) {
            button.closest('.timeline-item').remove();
            showNotification('Elemento Eliminado', 'El elemento ha sido eliminado del itinerario.');
            // Update summaries after deletion
            updateAllSummaries();
        }
    }

    function addNewDay() {
        const daysContainer = document.getElementById('days-container');
        const existingDays = daysContainer.querySelectorAll('.day-card');
        const newDayNumber = existingDays.length + 1;

        const dayCard = document.createElement('div');
        dayCard.className = 'day-card';
        dayCard.setAttribute('data-day', newDayNumber);

        const startDate = document.getElementById('start-date').value;
        let dayDate = 'Sin fecha';
        if (startDate) {
            const date = new Date(startDate);
            date.setDate(date.getDate() + newDayNumber - 1);
            dayDate = date.toLocaleDateString('es-ES', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }

        dayCard.innerHTML = `
            <div class="day-header">
                <h3>Día ${newDayNumber}</h3>
                <p class="day-date">${dayDate}</p>
            </div>
            <div class="day-content" ondrop="drop(event)" ondragover="allowDrop(event)">
                <div class="add-element-btn" onclick="addElementToDay(${newDayNumber})">
                    <i class="fas fa-plus"></i>
                </div>
                <p class="drag-instruction">Arrastra elementos aquí para personalizar este día</p>
            </div>
        `;

        daysContainer.appendChild(dayCard);

        // Update summaries after adding new day
        updateAllSummaries();

        showNotification('Día Agregado', `Día ${newDayNumber} agregado al itinerario.`);
    }

    function updateItineraryDates() {
        const startDateInput = document.getElementById('start-date').value;
        if (!startDateInput) {
            showNotification('Error', 'Por favor selecciona una fecha de inicio.');
            return;
        }

        const startDate = new Date(startDateInput + 'T00:00:00');
        const dayCards = document.querySelectorAll('.day-card');

        dayCards.forEach((card, index) => {
            const currentDate = new Date(startDate);
            currentDate.setDate(startDate.getDate() + index);

            const dateElement = card.querySelector('.day-date');
            const formattedDate = currentDate.toLocaleDateString('es-ES', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            dateElement.textContent = formattedDate;
            dateElement.setAttribute('data-date', currentDate.toISOString().split('T')[0]);
        });

        showNotification('Fechas Actualizadas', 'Las fechas de los días han sido actualizadas.');
        // Update summaries after date changes
        updateAllSummaries();
    }

    function previewTrip() {
        const tripId = {{ $trip->id ?? 'null' }};

        if (!tripId) {
            showNotification('Error', 'Primero guarda el viaje para ver la vista previa.', 'error');
            return;
        }

        const previewUrl = `/trips/${tripId}/preview`;
        window.open(previewUrl, '_blank');
    }

    function downloadPDF() {
        const tripId = {{ $trip->id ?? 'null' }};

        if (!tripId) {
            showNotification('Error', 'Primero guarda el viaje para descargar el PDF.', 'error');
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

            showNotification('PDF generado', 'El PDF del itinerario se está descargando.', 'success');

            // Reset button
            pdfBtn.innerHTML = originalText;
            pdfBtn.disabled = false;
        } catch (error) {
            console.error('PDF download error:', error);
            showNotification('Error', 'No se pudo generar el PDF.', 'error');

            // Reset button
            pdfBtn.innerHTML = originalText;
            pdfBtn.disabled = false;
        }
    }

    function saveTrip() {
        // Collect all trip elements from the days
        const itemsData = collectAllTripItems();

        // Calculate end date based on number of days
        const startDate = document.getElementById('start-date').value;
        let endDate = null;
        if (startDate) {
            const dayCards = document.querySelectorAll('.day-card');
            const numDays = Math.max(dayCards.length, 1); // Ensure at least 1 day
            const startDateObj = new Date(startDate);
            const endDateObj = new Date(startDate);
            endDateObj.setDate(startDateObj.getDate() + numDays - 1);
            endDate = endDateObj.toISOString().split('T')[0];
        }

        const tripData = {
            title: document.getElementById('trip-title').value?.trim(),
            start_date: startDate,
            end_date: endDate,
            travelers: 1, // Default value
            destination: '', // Optional field
            summary: '', // Optional field
            items_data: itemsData
        };

        // Determine if this is a new trip or updating existing
        const currentPath = window.location.pathname;
        const urlParts = currentPath.split('/').filter(part => part !== '');
        const isEditing = urlParts.length >= 3 && urlParts[1] === 'trips' && !isNaN(urlParts[2]) && urlParts[3] === 'edit';
        let url, method, tripId = null;

        console.log('Current path:', currentPath);
        console.log('URL parts:', urlParts);
        console.log('Is editing:', isEditing);

        if (isEditing) {
            // For editing, extract the trip ID from the URL
            tripId = urlParts[2]; // The ID should be at index 2
            console.log('Extracted trip ID:', tripId);

            if (!tripId || isNaN(tripId)) {
                console.error('Invalid trip ID extracted from URL');
                showNotification('Error', 'No se pudo determinar el ID del viaje para editar.');
                return;
            }

            url = '{{ url("trips") }}/' + tripId;
            method = 'POST'; // Use POST with _method override
            tripData._method = 'PATCH'; // Add method override
        } else {
            // For creating, make POST request to /trips
            url = '{{ url("trips") }}';
            method = 'POST';
        }

        // Fallback: if URL generation fails, use relative URLs
        if (!url || url === '{{ url("trips") }}') {
            console.log('URL generation failed, using fallback');
            if (isEditing && tripId) {
                url = '/trips/' + tripId;
            } else {
                url = '/trips';
            }
        }

        console.log('Final URL:', url);
        console.log('Method:', method);
        console.log('Trip data to send:', JSON.stringify(tripData, null, 2));

        // Validate required fields
        if (!tripData.title || tripData.title.trim() === '') {
            showNotification('Error', 'El título del viaje es obligatorio.');
            return;
        }

        if (!tripData.start_date) {
            showNotification('Error', 'La fecha de inicio es obligatoria.');
            return;
        }

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        console.log('CSRF token found:', !!csrfToken);

        if (!csrfToken) {
            console.error('CSRF token not found!');
            showNotification('Error', 'Token de seguridad no encontrado. Recarga la página.');
            return;
        }

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(tripData)
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response status text:', response.statusText);
            console.log('Response headers:', Object.fromEntries(response.headers.entries()));

            // Log the raw response text for debugging
            return response.text().then(text => {
                console.log('Raw response text:', text);
                console.log('Response text length:', text.length);

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

                try {
                    const jsonData = JSON.parse(text);
                    console.log('Successfully parsed JSON:', jsonData);
                    return jsonData;
                } catch (e) {
                    console.error('Failed to parse JSON response:', text);
                    console.error('JSON parse error:', e.message);
                    throw new Error(`Invalid JSON response from server: ${e.message}. Response: ${text.substring(0, 200)}...`);
                }
            });
        })
        .then(data => {
            console.log('Response data:', data);

            if (data.success) {
                showNotification('Viaje Guardado', 'El viaje ha sido guardado exitosamente.');
                window.location.href = '{{ route("trips.index") }}';
            } else {
                showNotification('Error', data.message || 'No se pudo guardar el viaje.');
            }
        })
        .catch(error => {
            console.error('Error saving trip:', error);
            showNotification('Error', 'No se pudo guardar el viaje. Revisa la consola para más detalles.');
        });
    }

    function collectAllTripItems() {
        const items = [];
        const dayCards = document.querySelectorAll('.day-card');

        dayCards.forEach((dayCard, index) => {
            const dayNumber = parseInt(dayCard.dataset.day) || (index + 1);
            const timelineItems = dayCard.querySelectorAll('.timeline-item');

            timelineItems.forEach(item => {
                const itemData = extractItemData(item, dayNumber);
                if (itemData) {
                    items.push(itemData);
                }
            });
        });

        return items;
    }

    function extractItemData(itemElement, dayNumber) {
        const itemType = itemElement.querySelector('.item-type')?.textContent?.toLowerCase();

        if (!itemType) return null;

        const baseData = {
            type: itemType.replace('elemento', '').trim().toLowerCase(),
            day: dayNumber
        };

        // Extract data based on item type
        switch (baseData.type) {
            case 'vuelo':
                return {
                    ...baseData,
                    type: 'flight',
                    airline: itemElement.querySelector('.item-title')?.textContent?.split(' ')[0] || '',
                    flight_number: itemElement.querySelector('.item-title')?.textContent?.split(' ')[1] || '',
                    departure_airport: itemElement.querySelector('.item-subtitle')?.textContent?.split(' → ')[0] || '',
                    arrival_airport: itemElement.querySelector('.item-subtitle')?.textContent?.split(' → ')[1] || ''
                };

            case 'hotel':
                return {
                    ...baseData,
                    type: 'hotel',
                    hotel_name: itemElement.querySelector('.item-title')?.textContent || '',
                    hotel_id: itemElement.dataset.hotelId || '',
                    hotel_data: itemElement.dataset.hotelData ? JSON.parse(itemElement.dataset.hotelData) : null,
                    check_in: itemElement.dataset.checkIn || '',
                    check_out: itemElement.dataset.checkOut || '',
                    room_type: itemElement.dataset.roomType || '',
                    nights: parseInt(itemElement.dataset.nights) || 1
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

    function createNewTrip() {
        const tripName = document.getElementById('new-trip-name').value.trim();

        if (!tripName) {
            showNotification('Error', 'Por favor ingresa un nombre para el viaje.');
            return;
        }

        // Set the trip title
        document.getElementById('trip-title').value = tripName;

        // Hide modal and show editor
        document.getElementById('new-trip-modal').classList.remove('show');
        document.getElementById('editor-container').style.display = 'flex';

        showNotification('Viaje Creado', `Viaje "${tripName}" creado exitosamente.`);
    }

    function cancelNewTrip() {
        window.location.href = '{{ route("trips.index") }}';
    }

    // Unsaved Changes Modal Functions
    function showUnsavedChangesModal() {
        const modal = document.getElementById('unsaved-changes-modal');
        const changesSummary = document.getElementById('changesSummary');

        // Generate changes summary
        const changes = generateChangesSummary();
        changesSummary.innerHTML = changes;

        modal.style.display = 'block';
    }

    function closeUnsavedModal() {
        const modal = document.getElementById('unsaved-changes-modal');
        modal.style.display = 'none';
    }

    function exitWithoutSaving() {
        window.location.href = '{{ route("trips.index") }}';
    }

    function saveAndExit() {
        saveTrip();
    }

    function generateChangesSummary() {
        const currentData = collectAllTripItems();
        const originalData = []; // This would need to be stored when the page loads

        let changes = '';

        // Check for new items
        const currentItemCount = Object.keys(currentData).length;
        if (currentItemCount > 0) {
            changes += `• Se agregaron ${currentItemCount} elementos al itinerario<br>`;
        }

        // Check for title changes
        const currentTitle = document.getElementById('trip-title').value;
        const originalTitle = document.getElementById('trip-title').defaultValue || '';
        if (currentTitle !== originalTitle) {
            changes += `• Título del viaje modificado<br>`;
        }

        // Check for date changes
        const currentStartDate = document.getElementById('start-date').value;
        const originalStartDate = document.getElementById('start-date').defaultValue || '';

        if (currentStartDate !== originalStartDate) {
            changes += `• Fechas del viaje modificadas<br>`;
        }

        if (!changes) {
            changes = '• Cambios menores en el contenido<br>';
        }

        return changes;
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const elementModal = document.getElementById('element-modal');
        const newTripModal = document.getElementById('new-trip-modal');
        const unsavedModal = document.getElementById('unsaved-changes-modal');

        if (event.target === elementModal) {
            closeModal();
        }

        if (event.target === newTripModal) {
            // Don't close new trip modal by clicking outside
        }

        if (event.target === unsavedModal) {
            closeUnsavedModal();
        }
    }

    // Automatic Itinerary Summary Generation
    function generateItinerarySummary() {
        const tripTitle = document.getElementById('trip-title').value.trim() || 'Mi Viaje';
        const startDate = document.getElementById('start-date').value;
        const dayContainers = document.querySelectorAll('.day-card');

        let summary = `<strong>${tripTitle}</strong><br>`;

        if (startDate) {
            const startDateObj = new Date(startDate);
            const endDateObj = new Date(startDate);
            endDateObj.setDate(startDateObj.getDate() + dayContainers.length - 1);

            const formatDate = (date) => {
                return date.toLocaleDateString('es-ES', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            };

            summary += `<strong>Duración:</strong> ${dayContainers.length} días (${formatDate(startDateObj)} - ${formatDate(endDateObj)})<br><br>`;
        }

        // Group items by day
        const itemsByDay = {};

        // Initialize days
        for (let i = 1; i <= dayContainers.length; i++) {
            itemsByDay[i] = [];
        }

        // Collect all timeline items and group by day
        dayContainers.forEach((dayCard, index) => {
            const dayNumber = index + 1;
            const timelineItems = dayCard.querySelectorAll('.timeline-item');

            timelineItems.forEach(item => {
                if (!item.classList.contains('summary')) {
                    const itemData = extractItemDataForDisplay(item);
                    if (itemData) {
                        itemsByDay[dayNumber].push(itemData);
                    }
                }
            });
        });

        // Generate day-by-day summary
        Object.keys(itemsByDay).forEach(dayNumber => {
            const dayItems = itemsByDay[dayNumber];
            if (dayItems.length > 0) {
                const dayDate = new Date(startDate);
                dayDate.setDate(dayDate.getDate() + parseInt(dayNumber) - 1);

                const formatDayDate = (date) => {
                    return dayDate.toLocaleDateString('es-ES', {
                        weekday: 'long',
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                };

                summary += `<strong>Día ${dayNumber} - ${formatDayDate(dayDate)}</strong><br>`;

                dayItems.forEach(item => {
                    let itemTitle = item.title || 'Sin título';

                    // Special formatting for different item types
                    if (item.type === 'flight') {
                        itemTitle = itemTitle;
                    } else if (item.type === 'hotel') {
                        itemTitle = itemTitle.replace(/\s*\(\d+\s*noche?s?\)/i, '').trim();
                    }

                    summary += `• ${itemTitle}<br>`;
                });

                summary += '<br>';
            }
        });

        // If no items found
        if (Object.values(itemsByDay).every(day => day.length === 0)) {
            summary += '<em>Sin elementos agregados aún</em>';
        }

        // Add total price if exists
        const totalElements = document.querySelectorAll('.timeline-item.total');
        if (totalElements.length > 0) {
            const totalElement = totalElements[0];
            const totalData = extractItemDataForDisplay(totalElement);
            if (totalData && totalData.total_amount && totalData.currency) {
                const price = parseFloat(totalData.total_amount);
                if (!isNaN(price)) {
                    const currencySymbols = {
                        'USD': '$',
                        'EUR': '€',
                        'COP': '$',
                        'MXN': '$'
                    };
                    const symbol = currencySymbols[totalData.currency] || totalData.currency;
                    const formattedPrice = `${symbol}${price.toLocaleString('es-ES', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                        useGrouping: true
                    })}`;
                    summary += `<br><br><strong>💰 Valor Total del Viaje:</strong> ${formattedPrice} ${totalData.currency}`;
                }
            }
        }

        return summary;
    }

    function extractItemDataForDisplay(itemElement) {
        if (!itemElement) return null;

        const baseData = {
            type: itemElement.classList.contains('flight') ? 'flight' :
                  itemElement.classList.contains('hotel') ? 'hotel' :
                  itemElement.classList.contains('activity') ? 'activity' :
                  itemElement.classList.contains('transport') ? 'transport' :
                  itemElement.classList.contains('note') ? 'note' :
                  itemElement.classList.contains('total') ? 'total' : 'unknown'
        };

        switch (baseData.type) {
            case 'flight':
                return {
                    ...baseData,
                    title: itemElement.querySelector('.item-title')?.textContent || '',
                    airline: itemElement.dataset.airline || itemElement.querySelector('.item-title')?.textContent?.split(' ')[1] || '',
                    flight_number: itemElement.dataset.flightNumber || itemElement.querySelector('.item-title')?.textContent?.split(' ')[2] || '',
                    departure_airport: itemElement.dataset.departureAirport || itemElement.querySelector('.item-subtitle')?.textContent?.split(' → ')[0] || '',
                    arrival_airport: itemElement.dataset.arrivalAirport || itemElement.querySelector('.item-subtitle')?.textContent?.split(' → ')[1] || '',
                    departure_time: itemElement.dataset.departureTime || '',
                    arrival_time: itemElement.dataset.arrivalTime || '',
                    confirmation_number: itemElement.dataset.confirmationNumber || ''
                };

            case 'hotel':
                const hotelData = {
                    ...baseData,
                    title: itemElement.querySelector('.item-title')?.textContent || '',
                    hotel_name: itemElement.querySelector('.item-title')?.textContent || '',
                    hotel_id: itemElement.dataset.hotelId || '',
                    hotel_data: itemElement.dataset.hotelData ? JSON.parse(itemElement.dataset.hotelData) : null,
                    check_in: itemElement.dataset.checkIn || '',
                    check_out: itemElement.dataset.checkOut || '',
                    room_type: itemElement.dataset.roomType || '',
                    nights: itemElement.dataset.nights || 1
                };

                // If we have hotel_data, extract additional info
                if (hotelData.hotel_data) {
                    hotelData.hotel_name = hotelData.hotel_data.name || hotelData.hotel_name;
                }

                return hotelData;

            case 'activity':
                return {
                    ...baseData,
                    title: itemElement.querySelector('.item-title')?.textContent || '',
                    activity_title: itemElement.querySelector('.item-title')?.textContent || '',
                    location: itemElement.querySelector('.item-subtitle')?.textContent || ''
                };

            case 'transport':
                return {
                    ...baseData,
                    title: itemElement.querySelector('.item-title')?.textContent || '',
                    transport_type: itemElement.querySelector('.item-title')?.textContent || '',
                    pickup_location: itemElement.querySelector('.item-subtitle')?.textContent?.split(' → ')[0] || '',
                    destination: itemElement.querySelector('.item-subtitle')?.textContent?.split(' → ')[1] || ''
                };

            case 'note':
                return {
                    ...baseData,
                    title: itemElement.querySelector('.item-title')?.textContent || '',
                    note_title: itemElement.querySelector('.item-title')?.textContent || '',
                    note_content: itemElement.querySelector('.item-subtitle')?.textContent || ''
                };

            case 'total':
                const titleText = itemElement.querySelector('.item-title')?.textContent || '';
                const subtitleText = itemElement.querySelector('.item-subtitle')?.textContent || '';

                // Extract amount and currency from title (handles different currency symbols)
                let totalAmount = '0.00';
                let currency = 'USD';

                // Try to extract amount and currency from title
                const amountMatch = titleText.match(/([€$S/])?(\d+(?:\.\d{2})?)\s*([A-Z]{3})?/);
                if (amountMatch) {
                    totalAmount = amountMatch[2];
                    if (amountMatch[3]) {
                        currency = amountMatch[3];
                    }
                }

                return {
                    ...baseData,
                    title: titleText,
                    total_amount: totalAmount,
                    currency: currency,
                    price_breakdown: subtitleText !== 'Precio total del viaje' ? subtitleText : ''
                };

            default:
                return {
                    ...baseData,
                    title: itemElement.querySelector('.item-title')?.textContent || 'Elemento'
                };
        }
    }

    function updateAllSummaries() {
        // Find all summary elements and update them
        const summaryElements = document.querySelectorAll('.timeline-item.summary');
        summaryElements.forEach(summaryElement => {
            updateSummaryElement(summaryElement);
        });

        // Find all total elements and update them
        const totalElements = document.querySelectorAll('.timeline-item.total');
        totalElements.forEach(totalElement => {
            updateTotalElement(totalElement);
        });
    }

    function updateSummaryElement(summaryElement) {
        if (summaryElement && summaryElement.classList.contains('summary')) {
            // Update title
            const tripTitle = document.getElementById('trip-title').value.trim() || 'Mi Viaje';
            const titleElement = summaryElement.querySelector('.item-title');
            if (titleElement) {
                titleElement.textContent = tripTitle;
            }

            // Update content
            const summaryContent = generateItinerarySummary();
            const descriptionElement = summaryElement.querySelector('.item-subtitle');

            if (descriptionElement) {
                descriptionElement.innerHTML = summaryContent;
            }
        }
    }

    function updateTotalElement(totalElement) {
        if (totalElement && totalElement.classList.contains('total')) {
            const itemData = extractItemDataForDisplay(totalElement);

            // If total has manual data, use it
            if (itemData && itemData.total_amount && itemData.currency) {
                const currencySymbols = {
                    'USD': '$',
                    'EUR': '€',
                    'CLP': '$',
                    'ARS': '$',
                    'PEN': 'S/',
                    'COP': '$',
                    'MXN': '$'
                };
                const symbol = currencySymbols[itemData.currency] || itemData.currency || '$';
                const amount = parseFloat(itemData.total_amount);

                // Update the total display
                const titleElement = totalElement.querySelector('.item-title');
                if (titleElement) {
                    titleElement.textContent = `${symbol}${amount.toFixed(2)} ${itemData.currency}`;
                }

                // Update subtitle with price breakdown if available
                const subtitleElement = totalElement.querySelector('.item-subtitle');
                if (subtitleElement && itemData.price_breakdown) {
                    subtitleElement.textContent = itemData.price_breakdown;
                }
                return;
            }

            // Otherwise, calculate total from all items automatically
            const allItems = document.querySelectorAll('.timeline-item:not(.summary):not(.total)');
            let totalAmount = 0;
            let currency = 'USD';

            allItems.forEach(item => {
                const priceText = item.querySelector('.item-title')?.textContent || '';
                const priceMatch = priceText.match(/\$?(\d+(?:\.\d{2})?)\s*([A-Z]{3})?/);
                if (priceMatch) {
                    totalAmount += parseFloat(priceMatch[1] || 0);
                    if (priceMatch[2]) {
                        currency = priceMatch[2];
                    }
                }
            });

            // Update the total display
            const titleElement = totalElement.querySelector('.item-title');
            if (titleElement) {
                titleElement.textContent = `$${totalAmount.toFixed(2)} ${currency}`;
            }
        }
    }

    // Click handlers for summary and total elements
    function handleSummaryClick() {
        // Check if there's already a summary
        const existingSummary = document.querySelector('.timeline-item.summary');

        if (existingSummary) {
            // If summary already exists, remove it
            existingSummary.remove();
            return;
        }

        // Create new summary element at the top
        const summaryElement = createSummaryElement();
        const daysContainer = document.getElementById('days-container');

        if (daysContainer) {
            daysContainer.insertBefore(summaryElement, daysContainer.firstChild);
        }

        // Update the summary content
        updateAllSummaries();
    }

    function handleTotalClick() {
        // Check if there's already a total element
        const existingTotal = document.querySelector('.timeline-item.total');

        if (existingTotal) {
            // If total already exists, remove it
            existingTotal.remove();
            return;
        }

        // Create new total element
        const totalElement = createTotalElement();
        const daysContainer = document.getElementById('days-container');

        if (daysContainer) {
            daysContainer.appendChild(totalElement);
        }
    }

    function createSummaryElement() {
        const tripTitle = document.getElementById('trip-title').value.trim() || 'Mi Viaje';
        const elementDiv = document.createElement('div');
        elementDiv.className = 'timeline-item summary';
        elementDiv.innerHTML = `
            <div class="item-header">
                <div class="item-icon summary-icon">
                    <i class="fas fa-list-check"></i>
                </div>
                <div class="item-info">
                    <div class="item-type">Resumen de Itinerario</div>
                    <div class="item-title">${tripTitle}</div>
                    <div class="item-subtitle">Resumen automático del viaje</div>
                </div>
                <div class="item-actions">
                    <button class="action-btn summary-update-btn" onclick="updateAllSummaries()" title="Actualizar resumen">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <button class="action-btn btn-danger" onclick="deleteElement(this)" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        return elementDiv;
    }

    function createTotalElement() {
        const elementDiv = document.createElement('div');
        elementDiv.className = 'timeline-item total';
        elementDiv.innerHTML = `
            <div class="item-header">
                <div class="item-icon total-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="item-info">
                    <div class="item-type">Valor Total</div>
                    <div class="item-title">$0.00 USD</div>
                    <div class="item-subtitle">Precio total del viaje</div>
                </div>
                <div class="item-actions">
                    <button class="action-btn summary-update-btn" onclick="updateAllSummaries()" title="Actualizar total">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <button class="action-btn btn-danger" onclick="deleteElement(this)" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        return elementDiv;
    }

    // Flight lookup functionality using AviationStack API
    async function lookupFlightInfo() {
        console.log('lookupFlightInfo called');

        const flightNumber = document.getElementById('flight-number').value.trim();
        const airline = document.getElementById('airline').value;

        console.log('Flight number:', flightNumber);
        console.log('Airline:', airline);

        if (!flightNumber) {
            showNotification('Error', 'Por favor ingresa un número de vuelo.', 'error');
            return;
        }

        // Show loading state
        const lookupBtn = document.getElementById('lookup-flight');
        console.log('Lookup button found:', !!lookupBtn);

        if (!lookupBtn) {
            console.error('Lookup button not found');
            return;
        }

        const originalText = lookupBtn.innerHTML;
        lookupBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        lookupBtn.disabled = true;

        try {
            // Using AviationStack API (free tier available)
            const apiKey = 'e6d8484a12f10b6190fda08b826479c9'; // You'll need to get a free API key from aviationstack.com
            const url = `https://api.aviationstack.com/v1/flights?access_key=${apiKey}&flight_iata=${flightNumber}`;
            console.log('API URL:', url);

            const response = await fetch(url);
            console.log('Response status:', response.status);

            if (!response.ok) {
                throw new Error(`API request failed with status ${response.status}`);
            }

            const data = await response.json();
            console.log('API response data:', data);

            if (data.data && data.data.length > 0) {
                const flight = data.data[0];
                console.log('Flight data:', flight);

                // Auto-fill form fields
                if (flight.departure && flight.departure.scheduled) {
                    const departureTime = formatTime(flight.departure.scheduled);
                    console.log('Setting departure time:', departureTime);
                    document.getElementById('departure-time').value = departureTime;
                }
                if (flight.arrival && flight.arrival.scheduled) {
                    const arrivalTime = formatTime(flight.arrival.scheduled);
                    console.log('Setting arrival time:', arrivalTime);
                    document.getElementById('arrival-time').value = arrivalTime;
                }
                if (flight.departure && flight.departure.iata) {
                    // Try to match with our airport list
                    const departureAirport = findAirportByIATA(flight.departure.iata);
                    console.log('Departure airport found:', departureAirport);
                    if (departureAirport) {
                        $('#departure-airport').val(departureAirport.id).trigger('change');
                    }
                }
                if (flight.arrival && flight.arrival.iata) {
                    // Try to match with our airport list
                    const arrivalAirport = findAirportByIATA(flight.arrival.iata);
                    console.log('Arrival airport found:', arrivalAirport);
                    if (arrivalAirport) {
                        $('#arrival-airport').val(arrivalAirport.id).trigger('change');
                    }
                }

                showNotification('Información encontrada', 'Los datos del vuelo han sido completados automáticamente.');
            } else {
                console.log('No flight data found');
                showNotification('Vuelo no encontrado', 'No se encontró información para este número de vuelo.', 'warning');
            }
        } catch (error) {
            console.error('Flight lookup error:', error);
            showNotification('Error', `No se pudo obtener la información del vuelo: ${error.message}`, 'error');
        } finally {
            // Reset button
            lookupBtn.innerHTML = originalText;
            lookupBtn.disabled = false;
        }
    }

    function formatTime(dateString) {
        try {
            const date = new Date(dateString);
            return date.toTimeString().slice(0, 5); // HH:MM format
        } catch (e) {
            return '';
        }
    }

    function findAirportByIATA(iataCode) {
        // Extended IATA codes for major airports worldwide
        const iataMap = {
            // Europe
            'MAD': 'Madrid Barajas (MAD)',
            'BCN': 'Barcelona El Prat (BCN)',
            'CDG': 'París Charles de Gaulle (CDG)',
            'ORY': 'París Orly (ORY)',
            'FRA': 'Fráncfort (FRA)',
            'MUC': 'Múnich (MUC)',
            'LHR': 'Londres Heathrow (LHR)',
            'LGW': 'Londres Gatwick (LGW)',
            'LTN': 'Londres Luton (LTN)',
            'STN': 'Londres Stansted (STN)',
            'LCY': 'Londres City (LCY)',
            'AMS': 'Ámsterdam Schiphol (AMS)',
            'FCO': 'Roma Fiumicino (FCO)',
            'MXP': 'Milán Malpensa (MXP)',
            'BER': 'Berlín Brandenburg (BER)',
            'VIE': 'Viena (VIE)',
            'ZRH': 'Zúrich (ZRH)',
            'GVA': 'Ginebra (GVA)',
            'CPH': 'Copenhague (CPH)',
            'ARN': 'Estocolmo Arlanda (ARN)',
            'OSL': 'Oslo Gardermoen (OSL)',
            'HEL': 'Helsinki (HEL)',
            'PRG': 'Praga (PRG)',
            'BUD': 'Budapest (BUD)',
            'WAW': 'Varsovia Chopin (WAW)',
            'LIS': 'Lisboa Humberto Delgado (LIS)',
            'OPO': 'Oporto (OPO)',
            'ATH': 'Atenas (ATH)',
            'TLV': 'Tel Aviv Ben Gurión (TLV)',
            'CAI': 'El Cairo (CAI)',
            'IST': 'Estambul (IST)',
            'DME': 'Moscú Domodédovo (DME)',
            'SVO': 'Moscú Sheremétievo (SVO)',
            'LED': 'San Petersburgo Púlkovo (LED)',

            // North America
            'JFK': 'Nueva York JFK (JFK)',
            'EWR': 'Nueva York Newark (EWR)',
            'LAX': 'Los Ángeles (LAX)',
            'ORD': 'Chicago O\'Hare (ORD)',
            'MIA': 'Miami (MIA)',
            'YYZ': 'Toronto Pearson (YYZ)',
            'YVR': 'Vancouver (YVR)',
            'YUL': 'Montreal Trudeau (YUL)',
            'MEX': 'México City (MEX)',
            'CUN': 'Cancún (CUN)',
            'GDL': 'Guadalajara (GDL)',
            'MTY': 'Monterrey (MTY)',
            'TIJ': 'Tijuana (TIJ)',
            'SJD': 'Los Cabos (SJD)',
            'HAV': 'La Habana (HAV)',
            'NAS': 'Nassau (NAS)',

            // South America
            'SCL': 'Santiago de Chile (SCL)',
            'BOG': 'Bogotá (BOG)',
            'LIM': 'Lima (LIM)',
            'EZE': 'Buenos Aires Ezeiza (EZE)',
            'GRU': 'São Paulo Guarulhos (GRU)',
            'BSB': 'Brasilia (BSB)',
            'GIG': 'Río de Janeiro Galeão (GIG)',
            'BOG': 'Bogotá (BOG)',
            'UIO': 'Quito (UIO)',
            'GYE': 'Guayaquil (GYE)',
            'ASU': 'Asunción (ASU)',
            'MVD': 'Montevideo (MVD)',
            'LPB': 'La Paz (LPB)',
            'VVI': 'Santa Cruz (VVI)',

            // Asia
            'DXB': 'Dubái (DXB)',
            'DOH': 'Doha (DOH)',
            'HKG': 'Hong Kong (HKG)',
            'NRT': 'Tokio Narita (NRT)',
            'HND': 'Tokio Haneda (HND)',
            'ICN': 'Seúl Incheon (ICN)',
            'PEK': 'Pekín Capital (PEK)',
            'PVG': 'Shanghái Pudong (PVG)',
            'CAN': 'Cantón (CAN)',
            'CTU': 'Chengdú (CTU)',
            'CGK': 'Yakarta Soekarno-Hatta (CGK)',
            'BKK': 'Bangkok Suvarnabhumi (BKK)',
            'SIN': 'Singapur Changi (SIN)',
            'KUL': 'Kuala Lumpur (KUL)',
            'DEL': 'Delhi (DEL)',
            'BOM': 'Bombay (BOM)',
            'BLR': 'Bangalore (BLR)',

            // Oceania
            'SYD': 'Sídney (SYD)',
            'MEL': 'Melbourne (MEL)',
            'AKL': 'Auckland (AKL)',
            'PER': 'Perth (PER)',
            'BNE': 'Brisbane (BNE)',

            // Africa
            'JNB': 'Johannesburgo (JNB)',
            'CPT': 'Ciudad del Cabo (CPT)',
            'ADD': 'Adís Abeba (ADD)',
            'NBO': 'Nairobi (NBO)',
            'LOS': 'Lagos (LOS)',
            'CMN': 'Casablanca (CMN)',
            'TUN': 'Túnez (TUN)',

            // Middle East
            'AUH': 'Abu Dhabi (AUH)',
            'RUH': 'Riad (RUH)',
            'AMM': 'Amán (AMM)',
            'BEY': 'Beirut (BEY)',
            'KWI': 'Kuwait (KWI)'
        };

        const airportName = iataMap[iataCode];
        if (airportName) {
            return { id: airportName, text: airportName };
        }

        // If not found in our list, return a generic entry with the IATA code
        return {
            id: `${iataCode} - ${iataCode}`,
            text: `${iataCode} - ${iataCode}`
        };
    }

    // Hotel search functionality using Booking.com API
    async function searchHotels() {
        const hotelSelect = document.getElementById('hotel-name');
        const selectedOption = hotelSelect.options[hotelSelect.selectedIndex];

        if (!selectedOption || !selectedOption.value) {
            showNotification('Error', 'Por favor selecciona una ubicación primero.', 'error');
            return;
        }

        const destId = selectedOption.value;
        const hotelResults = document.getElementById('hotel-results');
        const hotelList = document.getElementById('hotel-list');
        const searchBtn = document.getElementById('search-hotels');

        // Show loading state
        searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        searchBtn.disabled = true;
        hotelList.innerHTML = '<div class="loading">Buscando hoteles...</div>';
        hotelResults.style.display = 'block';

        try {
            // Get current date for search (next week)
            const today = new Date();
            const checkinDate = new Date(today);
            checkinDate.setDate(today.getDate() + 7);
            const checkoutDate = new Date(checkinDate);
            checkoutDate.setDate(checkinDate.getDate() + 1);

            const checkinStr = checkinDate.toISOString().split('T')[0];
            const checkoutStr = checkoutDate.toISOString().split('T')[0];

            const response = await fetch(`https://booking-com15.p.rapidapi.com/api/v1/hotels/searchHotels?dest_id=${destId}&search_type=city&arrival_date=${checkinStr}&departure_date=${checkoutStr}&adults=1&children_age=0%2C17&room_qty=1&page_number=1&units=metric&temperature_unit=c&languagecode=en-us&currency_code=USD`, {
                method: 'GET',
                headers: {
                    'x-rapidapi-host': 'booking-com15.p.rapidapi.com',
                    'x-rapidapi-key': '2ea32fefbamsh0dade5dedb8c255p1f80f9jsn59b5e00f47a5'
                }
            });

            const data = await response.json();

            if (data.status && data.data && data.data.hotels) {
                displayHotelResults(data.data.hotels.slice(0, 10)); // Show first 10 results
            } else {
                hotelList.innerHTML = '<div class="no-results">No se encontraron hoteles para esta ubicación.</div>';
            }
        } catch (error) {
            console.error('Hotel search error:', error);
            hotelList.innerHTML = '<div class="error">Error al buscar hoteles. Inténtalo de nuevo.</div>';
        } finally {
            // Reset button
            searchBtn.innerHTML = '<i class="fas fa-search"></i> Buscar Hoteles';
            searchBtn.disabled = false;
        }
    }

    function displayHotelResults(hotels) {
        const hotelList = document.getElementById('hotel-list');
        hotelList.innerHTML = '';

        hotels.forEach(hotel => {
            const hotelItem = document.createElement('div');
            hotelItem.className = 'hotel-item';
            hotelItem.onclick = () => selectHotel(hotel);

            const imageUrl = hotel.property.photoUrls && hotel.property.photoUrls[0] ?
                hotel.property.photoUrls[0] : 'https://via.placeholder.com/100x75?text=No+Image';

            const stars = '★'.repeat(hotel.property.propertyClass || 0);
            const rating = hotel.property.reviewScore ? `${hotel.property.reviewScore} ${hotel.property.reviewScoreWord}` : 'Sin calificación';

            const price = hotel.property.priceBreakdown && hotel.property.priceBreakdown.grossPrice ?
                `${hotel.property.priceBreakdown.grossPrice.currency} ${hotel.property.priceBreakdown.grossPrice.value.toFixed(2)}` : 'Precio no disponible';

            hotelItem.innerHTML = `
                <div class="hotel-image">
                    <img src="${imageUrl}" alt="${hotel.property.name}" onerror="this.src='https://via.placeholder.com/100x75?text=No+Image'">
                </div>
                <div class="hotel-info">
                    <div class="hotel-name">${hotel.property.name}</div>
                    <div class="hotel-address">${hotel.property.wishlistName || 'Dirección no disponible'}</div>
                    <div class="hotel-rating">
                        <span class="hotel-stars">${stars}</span>
                        <span class="hotel-score">${rating}</span>
                    </div>
                    <div class="hotel-price">${price} por noche</div>
                </div>
            `;

            hotelList.appendChild(hotelItem);
        });
    }

    function selectHotel(hotel) {
        // Store complete hotel data for later use
        selectedHotelData = {
            id: hotel.hotel_id,
            name: hotel.property.name,
            images: hotel.property.photoUrls || [],
            rating: hotel.property.reviewScore,
            reviewCount: hotel.property.reviewCount,
            reviewScoreWord: hotel.property.reviewScoreWord,
            stars: hotel.property.propertyClass,
            address: hotel.property.wishlistName,
            latitude: hotel.property.latitude,
            longitude: hotel.property.longitude,
            currency: hotel.property.currency,
            priceBreakdown: hotel.property.priceBreakdown,
            checkin: hotel.property.checkin,
            checkout: hotel.property.checkout,
            isPreferred: hotel.property.isPreferred,
            isPreferredPlus: hotel.property.isPreferredPlus
        };

        // Update the hotel select with the selected hotel
        const hotelSelect = document.getElementById('hotel-name');

        // Create a new option with hotel ID as value
        const option = document.createElement('option');
        option.value = hotel.hotel_id; // Use hotel ID instead of name
        option.text = hotel.property.name;
        option.selected = true;

        // Clear existing options and add the selected one
        hotelSelect.innerHTML = '';
        hotelSelect.appendChild(option);

        // Trigger change event to update Select2
        $(hotelSelect).trigger('change');

        // Hide results
        document.getElementById('hotel-results').style.display = 'none';

        showNotification('Hotel Seleccionado', `Has seleccionado: ${hotel.property.name}`);
    }

    // Initialize Select2 for autocomplete selects
    function initializeSelect2() {
        // Airlines data
        const airlines = [
            { id: 'Iberia', text: 'Iberia' },
            { id: 'Air France', text: 'Air France' },
            { id: 'Lufthansa', text: 'Lufthansa' },
            { id: 'British Airways', text: 'British Airways' },
            { id: 'KLM', text: 'KLM' },
            { id: 'Delta', text: 'Delta' },
            { id: 'American Airlines', text: 'American Airlines' },
            { id: 'United Airlines', text: 'United Airlines' },
            { id: 'Emirates', text: 'Emirates' },
            { id: 'Qatar Airways', text: 'Qatar Airways' },
            { id: 'Turkish Airlines', text: 'Turkish Airlines' },
            { id: 'LATAM', text: 'LATAM' },
            { id: 'Avianca', text: 'Avianca' },
            { id: 'Aeroméxico', text: 'Aeroméxico' },
            { id: 'Aerolineas Argentinas', text: 'Aerolineas Argentinas' },
            { id: 'Sky Airline', text: 'Sky Airline' },
            { id: 'JetSmart', text: 'JetSmart' },
            { id: 'Ryanair', text: 'Ryanair' },
            { id: 'Vueling', text: 'Vueling' },
            { id: 'EasyJet', text: 'EasyJet' },
            { id: 'Norwegian', text: 'Norwegian' },
            { id: 'Volotea', text: 'Volotea' },
            { id: 'Eurowings', text: 'Eurowings' },
            { id: 'Transavia', text: 'Transavia' },
            { id: 'Pegasus', text: 'Pegasus' },
            { id: 'Wizz Air', text: 'Wizz Air' },
            { id: 'Level', text: 'Level' }
        ];

        // Airports data - expanded list
        const airports = [
            // Europe
            { id: 'Madrid Barajas (MAD)', text: 'Madrid Barajas (MAD)' },
            { id: 'Barcelona El Prat (BCN)', text: 'Barcelona El Prat (BCN)' },
            { id: 'París Charles de Gaulle (CDG)', text: 'París Charles de Gaulle (CDG)' },
            { id: 'París Orly (ORY)', text: 'París Orly (ORY)' },
            { id: 'Fráncfort (FRA)', text: 'Fráncfort (FRA)' },
            { id: 'Múnich (MUC)', text: 'Múnich (MUC)' },
            { id: 'Londres Heathrow (LHR)', text: 'Londres Heathrow (LHR)' },
            { id: 'Londres Gatwick (LGW)', text: 'Londres Gatwick (LGW)' },
            { id: 'Londres Luton (LTN)', text: 'Londres Luton (LTN)' },
            { id: 'Londres Stansted (STN)', text: 'Londres Stansted (STN)' },
            { id: 'Londres City (LCY)', text: 'Londres City (LCY)' },
            { id: 'Ámsterdam Schiphol (AMS)', text: 'Ámsterdam Schiphol (AMS)' },
            { id: 'Roma Fiumicino (FCO)', text: 'Roma Fiumicino (FCO)' },
            { id: 'Milán Malpensa (MXP)', text: 'Milán Malpensa (MXP)' },
            { id: 'Berlín Brandenburg (BER)', text: 'Berlín Brandenburg (BER)' },
            { id: 'Viena (VIE)', text: 'Viena (VIE)' },
            { id: 'Zúrich (ZRH)', text: 'Zúrich (ZRH)' },
            { id: 'Ginebra (GVA)', text: 'Ginebra (GVA)' },
            { id: 'Copenhague (CPH)', text: 'Copenhague (CPH)' },
            { id: 'Estocolmo Arlanda (ARN)', text: 'Estocolmo Arlanda (ARN)' },
            { id: 'Oslo Gardermoen (OSL)', text: 'Oslo Gardermoen (OSL)' },
            { id: 'Helsinki (HEL)', text: 'Helsinki (HEL)' },
            { id: 'Praga (PRG)', text: 'Praga (PRG)' },
            { id: 'Budapest (BUD)', text: 'Budapest (BUD)' },
            { id: 'Varsovia Chopin (WAW)', text: 'Varsovia Chopin (WAW)' },
            { id: 'Lisboa Humberto Delgado (LIS)', text: 'Lisboa Humberto Delgado (LIS)' },
            { id: 'Oporto (OPO)', text: 'Oporto (OPO)' },
            { id: 'Atenas (ATH)', text: 'Atenas (ATH)' },
            { id: 'Tel Aviv Ben Gurión (TLV)', text: 'Tel Aviv Ben Gurión (TLV)' },
            { id: 'El Cairo (CAI)', text: 'El Cairo (CAI)' },
            { id: 'Estambul (IST)', text: 'Estambul (IST)' },
            { id: 'Moscú Domodédovo (DME)', text: 'Moscú Domodédovo (DME)' },
            { id: 'Moscú Sheremétievo (SVO)', text: 'Moscú Sheremétievo (SVO)' },
            { id: 'San Petersburgo Púlkovo (LED)', text: 'San Petersburgo Púlkovo (LED)' },

            // North America
            { id: 'Nueva York JFK (JFK)', text: 'Nueva York JFK (JFK)' },
            { id: 'Nueva York Newark (EWR)', text: 'Nueva York Newark (EWR)' },
            { id: 'Los Ángeles (LAX)', text: 'Los Ángeles (LAX)' },
            { id: 'Chicago O\'Hare (ORD)', text: 'Chicago O\'Hare (ORD)' },
            { id: 'Miami (MIA)', text: 'Miami (MIA)' },
            { id: 'Toronto Pearson (YYZ)', text: 'Toronto Pearson (YYZ)' },
            { id: 'Vancouver (YVR)', text: 'Vancouver (YVR)' },
            { id: 'Montreal Trudeau (YUL)', text: 'Montreal Trudeau (YUL)' },
            { id: 'México City (MEX)', text: 'México City (MEX)' },
            { id: 'Cancún (CUN)', text: 'Cancún (CUN)' },
            { id: 'Guadalajara (GDL)', text: 'Guadalajara (GDL)' },
            { id: 'Monterrey (MTY)', text: 'Monterrey (MTY)' },
            { id: 'Tijuana (TIJ)', text: 'Tijuana (TIJ)' },
            { id: 'Los Cabos (SJD)', text: 'Los Cabos (SJD)' },
            { id: 'La Habana (HAV)', text: 'La Habana (HAV)' },
            { id: 'Nassau (NAS)', text: 'Nassau (NAS)' },

            // South America
            { id: 'Santiago de Chile (SCL)', text: 'Santiago de Chile (SCL)' },
            { id: 'Bogotá (BOG)', text: 'Bogotá (BOG)' },
            { id: 'Lima (LIM)', text: 'Lima (LIM)' },
            { id: 'Buenos Aires Ezeiza (EZE)', text: 'Buenos Aires Ezeiza (EZE)' },
            { id: 'São Paulo Guarulhos (GRU)', text: 'São Paulo Guarulhos (GRU)' },
            { id: 'Brasilia (BSB)', text: 'Brasilia (BSB)' },
            { id: 'Río de Janeiro Galeão (GIG)', text: 'Río de Janeiro Galeão (GIG)' },
            { id: 'Quito (UIO)', text: 'Quito (UIO)' },
            { id: 'Guayaquil (GYE)', text: 'Guayaquil (GYE)' },
            { id: 'Asunción (ASU)', text: 'Asunción (ASU)' },
            { id: 'Montevideo (MVD)', text: 'Montevideo (MVD)' },
            { id: 'La Paz (LPB)', text: 'La Paz (LPB)' },
            { id: 'Santa Cruz (VVI)', text: 'Santa Cruz (VVI)' },

            // Asia
            { id: 'Dubái (DXB)', text: 'Dubái (DXB)' },
            { id: 'Doha (DOH)', text: 'Doha (DOH)' },
            { id: 'Hong Kong (HKG)', text: 'Hong Kong (HKG)' },
            { id: 'Tokio Narita (NRT)', text: 'Tokio Narita (NRT)' },
            { id: 'Tokio Haneda (HND)', text: 'Tokio Haneda (HND)' },
            { id: 'Seúl Incheon (ICN)', text: 'Seúl Incheon (ICN)' },
            { id: 'Pekín Capital (PEK)', text: 'Pekín Capital (PEK)' },
            { id: 'Shanghái Pudong (PVG)', text: 'Shanghái Pudong (PVG)' },
            { id: 'Cantón (CAN)', text: 'Cantón (CAN)' },
            { id: 'Chengdú (CTU)', text: 'Chengdú (CTU)' },
            { id: 'Yakarta Soekarno-Hatta (CGK)', text: 'Yakarta Soekarno-Hatta (CGK)' },
            { id: 'Bangkok Suvarnabhumi (BKK)', text: 'Bangkok Suvarnabhumi (BKK)' },
            { id: 'Singapur Changi (SIN)', text: 'Singapur Changi (SIN)' },
            { id: 'Kuala Lumpur (KUL)', text: 'Kuala Lumpur (KUL)' },
            { id: 'Delhi (DEL)', text: 'Delhi (DEL)' },
            { id: 'Bombay (BOM)', text: 'Bombay (BOM)' },
            { id: 'Bangalore (BLR)', text: 'Bangalore (BLR)' },

            // Oceania
            { id: 'Sídney (SYD)', text: 'Sídney (SYD)' },
            { id: 'Melbourne (MEL)', text: 'Melbourne (MEL)' },
            { id: 'Auckland (AKL)', text: 'Auckland (AKL)' },
            { id: 'Perth (PER)', text: 'Perth (PER)' },
            { id: 'Brisbane (BNE)', text: 'Brisbane (BNE)' },

            // Africa
            { id: 'Johannesburgo (JNB)', text: 'Johannesburgo (JNB)' },
            { id: 'Ciudad del Cabo (CPT)', text: 'Ciudad del Cabo (CPT)' },
            { id: 'Adís Abeba (ADD)', text: 'Adís Abeba (ADD)' },
            { id: 'Nairobi (NBO)', text: 'Nairobi (NBO)' },
            { id: 'Lagos (LOS)', text: 'Lagos (LOS)' },
            { id: 'Casablanca (CMN)', text: 'Casablanca (CMN)' },
            { id: 'Túnez (TUN)', text: 'Túnez (TUN)' },

            // Middle East
            { id: 'Abu Dhabi (AUH)', text: 'Abu Dhabi (AUH)' },
            { id: 'Riad (RUH)', text: 'Riad (RUH)' },
            { id: 'Amán (AMM)', text: 'Amán (AMM)' },
            { id: 'Beirut (BEY)', text: 'Beirut (BEY)' },
            { id: 'Kuwait (KWI)', text: 'Kuwait (KWI)' }
        ];

        // Initialize airline selects
        $('.airline-select').select2({
            data: airlines,
            placeholder: 'Seleccionar aerolínea',
            allowClear: true,
            width: '100%'
        });

        // Initialize airport selects
        $('.airport-select').select2({
            data: airports,
            placeholder: 'Seleccionar aeropuerto',
            allowClear: true,
            width: '100%'
        });

        // Initialize hotel selects with AJAX
        $('.hotel-select').select2({
            placeholder: 'Buscar hotel...',
            allowClear: true,
            width: '100%',
            minimumInputLength: 2,
            ajax: {
                url: function(params) {
                    // First get destination ID from location search
                    return 'https://booking-com15.p.rapidapi.com/api/v1/hotels/searchDestination';
                },
                dataType: 'json',
                delay: 300,
                headers: {
                    'x-rapidapi-host': 'booking-com15.p.rapidapi.com',
                    'x-rapidapi-key': '2ea32fefbamsh0dade5dedb8c255p1f80f9jsn59b5e00f47a5'
                },
                data: function(params) {
                    return {
                        query: params.term
                    };
                },
                processResults: function(data) {
                    if (data.status && data.data) {
                        return {
                            results: data.data.map(function(item) {
                                return {
                                    id: item.dest_id,
                                    text: item.label,
                                    type: item.dest_type,
                                    hotels: item.hotels || item.nr_hotels
                                };
                            })
                        };
                    }
                    return { results: [] };
                },
                cache: true
            },
            escapeMarkup: function(markup) {
                return markup;
            },
            templateResult: function(item) {
                if (item.loading) return item.text;
                return '<div>' + item.text + ' <small>(' + (item.hotels || 0) + ' hoteles)</small></div>';
            },
            templateSelection: function(item) {
                return item.text || item.id;
            }
        });
    }
</script>
@endpush
