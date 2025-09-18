
// Editor JavaScript for Viantryp Trip Editor
let currentElementType = null;
let currentElementData = {};
let currentDay = null;

// State management
let draggedElement = null;
let itemCounter = 0; // Start from 0 for new trips
let dayCounter = 1; // Start from 1 for new trips
let currentEditingItem = null;
let startDate = null;

let itemsData = {}; // Empty object for new trips

// Global drag and drop functions (must be in global scope for inline event handlers)
function handleDragStart(e) {
    draggedElement = e.target;
    e.target.style.opacity = '0.5';
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', e.target.outerHTML);
    e.dataTransfer.setData('text/plain', e.target.dataset.type);
    console.log('Started dragging element:', e.target.dataset.type);
}

function handleDragEnd(e) {
    e.target.style.opacity = '';
    draggedElement = null;
    console.log('Drag ended');
}

function handleDragOver(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
    return false;
}

function handleDragEnter(e) {
    e.preventDefault();
    if (!e.currentTarget.contains(e.relatedTarget)) {
        // Find the day container and add the drag-over class to it
        const dayContainer = e.currentTarget.closest('.day-container');
        if (dayContainer) {
            dayContainer.classList.add('drag-over');
        }
    }
}

function handleDragLeave(e) {
    if (!e.currentTarget.contains(e.relatedTarget)) {
        // Find the day container and remove the drag-over class from it
        const dayContainer = e.currentTarget.closest('.day-container');
        if (dayContainer) {
            dayContainer.classList.remove('drag-over');
        }
    }
}

function handleDrop(e) {
    e.preventDefault();
    e.currentTarget.classList.remove('drag-over');

    const elementType = e.dataTransfer.getData('text/plain');
    console.log('Drop event - elementType:', elementType);
    if (!elementType) {
        console.log('No elementType found in dataTransfer');
        return;
    }

    const dayContainer = e.currentTarget.closest('.day-container');
    console.log('Drop event - dayContainer:', dayContainer);
    if (!dayContainer) {
        console.log('No dayContainer found');
        return;
    }

    const dayNumber = parseInt(dayContainer.dataset.day);
    console.log('Dropped', elementType, 'on day', dayNumber);

    addElementToDay(dayNumber, elementType);

    return false;
}

// Initialize drag and drop functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Initializing editor...');

    // Clean up corrupted data first
    cleanupCorruptedData();

    initializeDragAndDrop();
    setDefaultStartDate();
    makeExistingItemsClickable();

    // Add event listener for date input to update automatically
    const startDateInput = document.getElementById('startDate');
    startDateInput.addEventListener('change', function() {
        updateItineraryDatesSilently();
        autoSave();
    });

    // Add event listener for trip title to update automatically
    const tripTitleInput = document.getElementById('tripTitle');
    if (tripTitleInput) {
        tripTitleInput.addEventListener('input', function() {
            updateTripTitleInRealTime();
            // Debounce auto-save to avoid too frequent saves
            clearTimeout(window.tripTitleSaveTimeout);
            window.tripTitleSaveTimeout = setTimeout(() => {
                autoSave();
                // Show subtle notification for auto-save
                showNotification('Guardado Automático', 'Cambios guardados automáticamente', 2000);
            }, 1000);
        });

        // Also save on blur (when user finishes editing)
        tripTitleInput.addEventListener('blur', function() {
            autoSave();
        });
    }

    // Check if we're editing a saved trip
    const urlParams = new URLSearchParams(window.location.search);
    const tripId = urlParams.get('trip');
    const mode = urlParams.get('mode');

    console.log('Editor loaded with tripId:', tripId, 'mode:', mode);

    if (tripId && (mode === 'edit' || mode === 'new')) {
        if (mode === 'edit') {
            loadSavedTrip(tripId);
        } else {
            // For new trips, set up the trip ID and show name modal
            console.log('Setting up new trip with ID:', tripId);
            // Update URL to include mode=edit for consistency
            const newUrl = `${window.location.pathname}?trip=${tripId}&mode=edit`;
            window.history.replaceState({}, '', newUrl);
            showTripNameModal();
        }
    } else {
        // For legacy URLs or no parameters, create a new trip
        createNewTrip();
    }

    // Update save status after initialization
    setTimeout(() => {
        updateSaveStatus();

        // Check if we have a trip ID and verify it's saved
        const urlParams = new URLSearchParams(window.location.search);
        const tripId = urlParams.get('trip');
        if (tripId) {
            const savedTrips = JSON.parse(localStorage.getItem('viantryp_trips') || '[]');
            const trip = savedTrips.find(t => t.id == tripId);
            if (trip) {
                console.log('Trip found in storage:', trip.title);
                console.log('Trip data verification:', {
                    title: trip.title,
                    itemsCount: Object.keys(trip.itemsData || {}).length,
                    startDate: trip.startDate,
                    hasItemsData: !!trip.itemsData
                });
            } else {
                console.log('Trip not found in storage, will be saved on first change');
            }
        }

        // Verify current state
        console.log('Current editor state:', {
            tripTitle: document.getElementById('tripTitle')?.value,
            startDate: startDate,
            itemsDataCount: Object.keys(itemsData).length,
            dayCounter: dayCounter,
            itemCounter: itemCounter
        });

        // Create initial day if none exists
        const existingDays = document.querySelectorAll('.day-container');
        if (existingDays.length === 0) {
            console.log('No days found, creating initial day');
            addNewDay();
        }

        // Double-check data loading after a longer delay
        setTimeout(() => {
            const urlParams = new URLSearchParams(window.location.search);
            const tripId = urlParams.get('trip');
            if (tripId) {
                const savedTrips = JSON.parse(localStorage.getItem('viantryp_trips') || '[]');
                const trip = savedTrips.find(t => t.id == tripId);
                if (trip && (!document.getElementById('tripTitle')?.value || Object.keys(itemsData).length === 0)) {
                    console.log('Data still missing after delay, forcing reload...');
                    loadSavedTrip(tripId);
                }
            }
        }, 3000);
    }, 1000);

    // Set up auto-save interval
    setInterval(autoSave, 30000); // Auto-save every 30 seconds

    // Auto-save when page is about to unload
    window.beforeUnloadHandler = function(e) {
        // Show confirmation dialog if there are unsaved changes
        if (hasUnsavedChanges()) {
            e.preventDefault();
            e.returnValue = 'Tienes cambios sin guardar. ¿Estás seguro de que quieres salir?';
            return e.returnValue;
        }
    };

    window.addEventListener('beforeunload', window.beforeUnloadHandler);


    // Auto-save when page loses focus (user switches tabs or windows)
    window.addEventListener('blur', function() {
        autoSave();
    });

    // Auto-save when user navigates away
    window.addEventListener('pagehide', function() {
        autoSave();
    });

    // Clean up editor state backup when leaving the page (but not when going to preview)
    window.addEventListener('beforeunload', function(e) {
        // Only clean up if we're not going to preview
        const isGoingToPreview = e.target.location && e.target.location.href.includes('viantryp_preview.html');
        if (!isGoingToPreview) {
            sessionStorage.removeItem('editor_state_backup');
        }
    });
});

// Initialize drag and drop functionality
function initializeDragAndDrop() {
    console.log('Initializing drag and drop...');

    // Make all draggable elements draggable
    const draggableElements = document.querySelectorAll('.draggable-element');
    console.log('Found draggable elements:', draggableElements.length);
    draggableElements.forEach(element => {
        element.setAttribute('draggable', 'true');
        element.addEventListener('dragstart', handleDragStart);
        element.addEventListener('dragend', handleDragEnd);
        console.log('Made element draggable:', element.dataset.type);
    });

    // Make all day containers droppable (attach to .day-items elements)
    const dayItems = document.querySelectorAll('.day-items');
    console.log('Found day items:', dayItems.length);
    dayItems.forEach(item => {
        item.addEventListener('dragover', handleDragOver);
        item.addEventListener('drop', handleDrop);
        item.addEventListener('dragenter', handleDragEnter);
        item.addEventListener('dragleave', handleDragLeave);
        console.log('Made day item droppable');
    });

    console.log('Drag and drop initialized for', draggableElements.length, 'elements and', dayItems.length, 'day items');
}

// Add element to day
function addElementToDay(dayNumber, elementType) {
    console.log('Adding element', elementType, 'to day', dayNumber);

    currentDay = dayNumber;
    currentElementType = elementType;
    currentElementData = { type: elementType, day: dayNumber };

    const modalTitle = document.getElementById('modal-title');
    const modalBody = document.getElementById('modal-body');
    const modal = document.getElementById('element-modal');

    if (!modalTitle || !modalBody || !modal) {
        console.error('Modal elements not found');
        return;
    }

    modalTitle.innerHTML = `<i class="fas fa-${getIconForType(elementType)}"></i> Agregar ${getTypeLabel(elementType)}`;
    modalBody.innerHTML = getElementForm(elementType);

    modal.style.display = 'block';

    // Add event listeners for validation
    setTimeout(() => {
        const inputs = document.querySelectorAll('#modal-body input, #modal-body textarea, #modal-body select');
        inputs.forEach(input => {
            input.addEventListener('input', clearFieldError);
            input.addEventListener('change', clearFieldError);
        });
    }, 100);
}

// Update itinerary dates
function updateItineraryDates() {
    const startDateInput = document.getElementById('startDate');
    if (!startDateInput || !startDateInput.value) {
        showNotification('Error', 'Por favor selecciona una fecha de inicio.');
        return;
    }

    startDate = new Date(startDateInput.value + 'T00:00:00');
    console.log('Updating dates with start date:', startDate);

    const dayContainers = document.querySelectorAll('.day-container');
    dayContainers.forEach((container, index) => {
        const dayNumber = parseInt(container.dataset.day);
        const currentDate = new Date(startDate);
        currentDate.setDate(startDate.getDate() + (dayNumber - 1));

        const formattedDate = formatDate(currentDate);
        const dateElement = container.querySelector('.day-date');
        if (dateElement) {
            dateElement.textContent = formattedDate;
            dateElement.setAttribute('data-date', formattedDate);
        }
    });

    showNotification('Fechas Actualizadas', 'Las fechas de los días han sido actualizadas.');
    autoSave();
}

// Silent version for auto-updates
function updateItineraryDatesSilently() {
    const startDateInput = document.getElementById('startDate');
    if (!startDateInput || !startDateInput.value) {
        return;
    }

    startDate = new Date(startDateInput.value + 'T00:00:00');
    console.log('Silently updating dates with start date:', startDate);

    const dayContainers = document.querySelectorAll('.day-container');
    dayContainers.forEach((container, index) => {
        const dayNumber = parseInt(container.dataset.day);
        const currentDate = new Date(startDate);
        currentDate.setDate(startDate.getDate() + (dayNumber - 1));

        const formattedDate = formatDate(currentDate);
        const dateElement = container.querySelector('.day-date');
        if (dateElement) {
            dateElement.textContent = formattedDate;
            dateElement.setAttribute('data-date', formattedDate);
        }
    });
}

// Format date helper
function formatDate(date) {
    const months = [
        'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
        'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
    ];

    const day = date.getDate();
    const month = months[date.getMonth()];
    const year = date.getFullYear();

    return `${day} de ${month} de ${year}`;
}

// Set default start date
function setDefaultStartDate() {
    const startDateInput = document.getElementById('startDate');
    if (startDateInput && !startDateInput.value) {
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(today.getDate() + 1);
        startDateInput.value = tomorrow.toISOString().split('T')[0];
        startDate = new Date(tomorrow);
        console.log('Set default start date to tomorrow:', startDate);
    }
}

// Make existing items clickable
function makeExistingItemsClickable() {
    const timelineItems = document.querySelectorAll('.timeline-item');
    timelineItems.forEach(item => {
        item.addEventListener('click', function(e) {
            // Don't trigger if clicking on action buttons
            if (!e.target.closest('.item-actions')) {
                editItem(this);
            }
        });
    });
}

// Missing functions that are called but not defined

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
                <input type="text" id="airline" class="form-input" placeholder="Ej: Iberia">
            </div>
            <div class="form-group">
                <label for="flight-number">Número de Vuelo</label>
                <input type="text" id="flight-number" class="form-input" placeholder="Ej: IB1234">
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
                    <input type="text" id="departure-airport" class="form-input" placeholder="Ej: Madrid Barajas">
                </div>
                <div class="form-group">
                    <label for="arrival-airport">Aeropuerto de Llegada</label>
                    <input type="text" id="arrival-airport" class="form-input" placeholder="Ej: París Charles de Gaulle">
                </div>
            </div>
            <div class="form-group">
                <label for="confirmation-number">Número de Confirmación</label>
                <input type="text" id="confirmation-number" class="form-input" placeholder="Ej: ABC123">
            </div>
        `,
        'hotel': `
            <div class="form-group">
                <label for="hotel-name">Nombre del Hotel</label>
                <input type="text" id="hotel-name" class="form-input" placeholder="Ej: Hotel Plaza">
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
        'summary': `
            <div class="form-group">
                <label for="summary-title">Título del Resumen</label>
                <input type="text" id="summary-title" class="form-input" placeholder="Ej: Resumen del viaje">
            </div>
            <div class="form-group">
                <label for="summary-content">Contenido del Resumen</label>
                <textarea id="summary-content" class="form-input" rows="4" placeholder="Resumen automático del viaje..."></textarea>
            </div>
        `,
        'total': `
            <div class="form-group">
                <label for="total-amount">Monto Total</label>
                <input type="number" id="total-amount" class="form-input" placeholder="0.00" step="0.01">
            </div>
            <div class="form-group">
                <label for="currency">Moneda</label>
                <select id="currency" class="form-input">
                    <option value="USD">USD - Dólar Americano</option>
                    <option value="EUR">EUR - Euro</option>
                    <option value="COP">COP - Peso Colombiano</option>
                    <option value="MXN">MXN - Peso Mexicano</option>
                </select>
            </div>
            <div class="form-group">
                <label for="total-description">Descripción</label>
                <textarea id="total-description" class="form-input" rows="3" placeholder="Desglose de costos..."></textarea>
            </div>
        `
    };

    return forms[type] || '<p>Formulario no disponible</p>';
}

function saveElement() {
    const formData = collectFormData();

    // Validate required fields
    const validationErrors = validateRequiredFields(formData);
    if (validationErrors.length > 0) {
        const errorMessage = `Por favor completa los siguientes campos obligatorios:\n\n${validationErrors.join('\n')}`;
        alert(errorMessage);
        return; // Don't close the modal
    }

    addElementToDay(formData);
    closeModal();
    showNotification('Elemento Agregado', `${getTypeLabel(currentElementType)} agregado al día ${currentDay}.`);
}

function collectFormData() {
    const data = { type: currentElementType, day: currentDay };
    const form = document.getElementById('modal-body');
    const inputs = form.querySelectorAll('input, textarea, select');

    inputs.forEach(input => {
        if (input.value.trim()) {
            // Map form field IDs to expected data structure
            const fieldName = input.id.replace('-', '_');
            switch (fieldName) {
                case 'airline':
                    data.airline = input.value.trim();
                    break;
                case 'flight_number':
                    data.flightNumber = input.value.trim();
                    break;
                case 'departure_airport':
                    data.originAirport = input.value.trim();
                    break;
                case 'arrival_airport':
                    data.destinationAirport = input.value.trim();
                    break;
                case 'departure_time':
                    data.departureTime = input.value.trim();
                    break;
                case 'arrival_time':
                    data.arrivalTime = input.value.trim();
                    break;
                case 'confirmation_number':
                    data.confirmationNumber = input.value.trim();
                    break;
                case 'hotel_name':
                    data.hotelName = input.value.trim();
                    break;
                case 'check_in':
                    data.checkinDate = input.value.trim();
                    break;
                case 'check_out':
                    data.checkoutDate = input.value.trim();
                    break;
                case 'room_type':
                    data.roomType = input.value.trim();
                    break;
                case 'nights':
                    data.nights = input.value.trim();
                    break;
                case 'activity_title':
                    data.activityTitle = input.value.trim();
                    break;
                case 'start_time':
                    data.startTime = input.value.trim();
                    break;
                case 'end_time':
                    data.endTime = input.value.trim();
                    break;
                case 'location':
                    data.location = input.value.trim();
                    break;
                case 'description':
                    data.activityDescription = input.value.trim();
                    break;
                case 'transport_type':
                    data.transportType = input.value.trim();
                    break;
                case 'pickup_time':
                    data.pickupTime = input.value.trim();
                    break;
                case 'pickup_location':
                    data.originLocation = input.value.trim();
                    break;
                case 'destination':
                    data.destinationLocation = input.value.trim();
                    break;
                case 'note_title':
                    data.noteTitle = input.value.trim();
                    break;
                case 'note_content':
                    data.noteContent = input.value.trim();
                    break;
                case 'total_amount':
                    data.totalPrice = input.value.trim();
                    break;
                case 'currency':
                    data.currency = input.value.trim();
                    break;
                case 'summary_title':
                    data.summaryTitle = input.value.trim();
                    break;
                case 'summary_content':
                    data.summaryContent = input.value.trim();
                    break;
                default:
                    data[fieldName] = input.value.trim();
            }
        }
    });

    return data;
}



// Auto-save functionality
function autoSave() {
    const tripTitle = document.getElementById('tripTitle')?.value?.trim();
    const startDateInput = document.getElementById('startDate')?.value;

    // Only auto-save if we have meaningful data
    if (!tripTitle && Object.keys(itemsData).length === 0 && !startDateInput) {
        return; // Don't save empty trips
    }

    const urlParams = new URLSearchParams(window.location.search);
    const tripId = urlParams.get('trip');

    if (!tripId) {
        console.log('No trip ID found, skipping auto-save');
        return;
    }

    try {
        // Get existing trips
        let existingTrips = JSON.parse(localStorage.getItem('viantryp_trips') || '[]');

        // Find existing trip or create new one
        let tripIndex = existingTrips.findIndex(t => t.id == tripId);

        const tripData = {
            title: tripTitle || 'Viaje sin título',
            dates: startDateInput ? formatDate(new Date(startDateInput + 'T00:00:00')) : 'Sin fecha',
            duration: `${dayCounter} días`,
            status: 'draft',
            startDate: startDateInput,
            endDate: null,
            itemsData: JSON.parse(JSON.stringify(itemsData)), // Deep copy
            updatedAt: new Date().toISOString()
        };

        if (tripIndex === -1) {
            // Create new trip
            tripData.id = parseInt(tripId);
            tripData.createdAt = new Date().toISOString();
            existingTrips.push(tripData);
            console.log('Auto-saved new trip:', tripId);
        } else {
            // Update existing trip
            existingTrips[tripIndex] = {
                ...existingTrips[tripIndex],
                ...tripData
            };
            console.log('Auto-saved existing trip:', tripId);
        }

        // Save to localStorage
        localStorage.setItem('viantryp_trips', JSON.stringify(existingTrips));

        // Update save status
        updateSaveStatus();

    } catch (error) {
        console.error('Error in auto-save:', error);
    }
}

// Manual save functionality
function manualSave() {
    const tripTitle = document.getElementById('tripTitle')?.value?.trim();
    const startDateInput = document.getElementById('startDate')?.value;

    if (!tripTitle) {
        showNotification('Error', 'Por favor ingresa un nombre para el viaje.');
        return;
    }

    const urlParams = new URLSearchParams(window.location.search);
    const tripId = urlParams.get('trip');

    if (!tripId) {
        showNotification('Error', 'No se pudo identificar el viaje para guardar.');
        return;
    }

    try {
        // Get existing trips
        let existingTrips = JSON.parse(localStorage.getItem('viantryp_trips') || '[]');

        // Find existing trip or create new one
        let tripIndex = existingTrips.findIndex(t => t.id == tripId);

        const tripData = {
            title: tripTitle,
            dates: startDateInput ? formatDate(new Date(startDateInput + 'T00:00:00')) : 'Sin fecha',
            duration: `${dayCounter} días`,
            status: 'draft',
            startDate: startDateInput,
            endDate: null,
            itemsData: JSON.parse(JSON.stringify(itemsData)), // Deep copy
            updatedAt: new Date().toISOString()
        };

        if (tripIndex === -1) {
            // Create new trip
            tripData.id = parseInt(tripId);
            tripData.createdAt = new Date().toISOString();
            existingTrips.push(tripData);
            console.log('Manual save - created new trip:', tripId);
        } else {
            // Update existing trip
            existingTrips[tripIndex] = {
                ...existingTrips[tripIndex],
                ...tripData
            };
            console.log('Manual save - updated existing trip:', tripId);
        }

        // Save to localStorage
        localStorage.setItem('viantryp_trips', JSON.stringify(existingTrips));

        // Update save status
        updateSaveStatus();

        // Show success notification
        showNotification('Guardado Exitoso', 'Tu itinerario se ha guardado correctamente.');

    } catch (error) {
        console.error('Error in manual save:', error);
        showNotification('Error al Guardar', 'Hubo un problema al guardar tu itinerario. Inténtalo de nuevo.');
    }
}

// Check for unsaved changes
function hasUnsavedChanges() {
    const tripTitle = document.getElementById('tripTitle')?.value?.trim();
    const startDateInput = document.getElementById('startDate')?.value;

    // If we have any data, consider it as having changes
    return !!(tripTitle || Object.keys(itemsData).length > 0 || startDateInput);
}

// Update save status
function updateSaveStatus() {
    const tripTitle = document.getElementById('tripTitle')?.value?.trim();
    const hasItems = Object.keys(itemsData).length > 0;
    const hasCustomTitle = tripTitle && tripTitle !== 'Viaje sin título';

    // Update save button state
    const saveButton = document.querySelector('button[onclick="manualSave()"]');
    if (saveButton) {
        if (hasItems || hasCustomTitle) {
            saveButton.innerHTML = '<i class="fas fa-save"></i> Guardar';
            saveButton.style.background = 'var(--primary-blue)';
        } else {
            saveButton.innerHTML = '<i class="fas fa-save"></i> Guardar (vacío)';
            saveButton.style.background = '#6c757d';
        }
    }
}

// Add new day functionality
function addNewDay() {
    console.log('Adding new day...');

    dayCounter++;
    let container = document.getElementById('timeline');
    if (!container) {
        console.error('Timeline element not found, trying alternative containers');
        // Try alternative containers
        const alternatives = ['#days-container', '#timeline-container', '.timeline', '.days-container'];
        for (const alt of alternatives) {
            container = document.querySelector(alt);
            if (container) {
                console.log('Found alternative container:', alt);
                break;
            }
        }
        if (!container) {
            console.error('No suitable container found for days');
            return;
        }
    }

    const dayContainer = document.createElement('div');
    dayContainer.className = 'day-container';
    dayContainer.setAttribute('data-day', dayCounter);

    // Calculate the date for this day
    let dayDate = 'Selecciona fecha de inicio';
    if (startDate) {
        const currentDate = new Date(startDate);
        currentDate.setDate(startDate.getDate() + (dayCounter - 1));
        dayDate = formatDate(currentDate);
    }

    dayContainer.innerHTML = `
        <div class="day-actions">
            <button class="day-btn" onclick="deleteDay(this)">
                <i class="fas fa-trash"></i> Eliminar
            </button>
        </div>
        <div class="day-header">
            <div class="day-title">Día ${dayCounter}</div>
            <div class="day-date" data-date="">${dayDate}</div>
        </div>
        <div class="day-items" ondrop="handleDrop(event)" ondragover="handleDragOver(event)" ondragenter="handleDragEnter(event)" ondragleave="handleDragLeave(event)">
            <div style="text-align: center; padding: 2rem; color: #666; font-style: italic;">
                <i class="fas fa-plus-circle" style="font-size: 2rem; margin-bottom: 1rem; display: block; color: var(--primary-blue);"></i>
                Arrastra elementos aquí para personalizar este día
            </div>
        </div>
    `;

    container.appendChild(dayContainer);

    // Make the new day container droppable
    dayContainer.addEventListener('dragover', handleDragOver);
    dayContainer.addEventListener('drop', handleDrop);
    dayContainer.addEventListener('dragenter', handleDragEnter);
    dayContainer.addEventListener('dragleave', handleDragLeave);

    showNotification('Día Agregado', `Día ${dayCounter} agregado al itinerario.`);

    // Auto-save after adding day
    setTimeout(() => {
        autoSave();
    }, 500);
}

// Delete day functionality
function deleteDay(button) {
    const dayContainer = button.closest('.day-container');
    const dayNumber = parseInt(dayContainer.dataset.day);

    if (confirm(`¿Estás seguro de que quieres eliminar el Día ${dayNumber}? Se perderán todos los elementos de este día.`)) {
        // Remove all items from this day from itemsData
        Object.keys(itemsData).forEach(key => {
            if (itemsData[key].day === dayNumber) {
                delete itemsData[key];
            }
        });

        dayContainer.remove();

        // Renumber remaining days
        const remainingDays = document.querySelectorAll('.day-container');
        remainingDays.forEach((container, index) => {
            const newDayNumber = index + 1;
            container.setAttribute('data-day', newDayNumber);
            container.querySelector('.day-title').textContent = `Día ${newDayNumber}`;

            // Update day numbers in itemsData
            Object.keys(itemsData).forEach(key => {
                if (itemsData[key].day === parseInt(container.dataset.day)) {
                    itemsData[key].day = newDayNumber;
                }
            });
        });

        dayCounter = remainingDays.length;

        showNotification('Día Eliminado', `Día ${dayNumber} eliminado del itinerario.`);

        // Auto-save after deleting day
        setTimeout(() => {
            autoSave();
        }, 500);
    }
}

// Missing functions

function getIconForType(elementType) {
    const icons = {
        'flight': 'plane',
        'hotel': 'bed',
        'activity': 'map-marker-alt',
        'transport': 'car',
        'note': 'sticky-note',
        'summary': 'list-check',
        'total': 'dollar-sign'
    };
    return icons[elementType] || 'sticky-note';
}

function validateRequiredFields(formData) {
    const errors = [];
    // Add validation logic here based on element type
    return errors;
}

function clearFieldError() {
    // Clear field error styling
    this.classList.remove('error');
}

function showNotification(title, message, duration = 3000) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.innerHTML = `
        <div class="notification-header">${title}</div>
        <div class="notification-body">${message}</div>
    `;

    // Add to page
    document.body.appendChild(notification);

    // Show notification
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);

    // Hide after duration
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, duration);
}

function updateTripTitleInRealTime() {
    // Update trip title in real time
    const tripTitle = document.getElementById('tripTitle')?.value?.trim();
    // Update any display elements if needed
}

function cleanupCorruptedData() {
    // Clean up any corrupted data in localStorage
    try {
        const trips = JSON.parse(localStorage.getItem('viantryp_trips') || '[]');
        const validTrips = trips.filter(trip => trip && trip.id && trip.title);
        localStorage.setItem('viantryp_trips', JSON.stringify(validTrips));
    } catch (error) {
        console.error('Error cleaning up corrupted data:', error);
        localStorage.removeItem('viantryp_trips');
    }
}

function showTripNameModal() {
    // Show modal for entering trip name
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h3>Nombre del Viaje</h3>
            </div>
            <div class="modal-body">
                <input type="text" id="newTripTitle" placeholder="Ingresa el nombre de tu viaje" class="form-input">
            </div>
            <div class="modal-footer">
                <button onclick="createTripWithName()">Crear Viaje</button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    modal.style.display = 'block';
}

function createNewTrip() {
    // Create a new trip
    const tripId = Date.now().toString();
    const newUrl = `${window.location.pathname}?trip=${tripId}&mode=new`;
    window.history.replaceState({}, '', newUrl);
    showTripNameModal();
}

function createTripWithName() {
    const title = document.getElementById('newTripTitle')?.value?.trim();
    if (!title) {
        showNotification('Error', 'Por favor ingresa un nombre para el viaje.');
        return;
    }

    const urlParams = new URLSearchParams(window.location.search);
    const tripId = urlParams.get('trip');

    // Create new trip
    const tripData = {
        id: parseInt(tripId),
        title: title,
        createdAt: new Date().toISOString(),
        updatedAt: new Date().toISOString(),
        itemsData: {},
        startDate: null,
        status: 'draft'
    };

    const existingTrips = JSON.parse(localStorage.getItem('viantryp_trips') || '[]');
    existingTrips.push(tripData);
    localStorage.setItem('viantryp_trips', JSON.stringify(existingTrips));

    // Update UI
    document.getElementById('tripTitle').value = title;

    // Close modal
    const modal = document.querySelector('.modal');
    if (modal) {
        modal.remove();
    }

    showNotification('Viaje Creado', 'Tu viaje ha sido creado exitosamente.');
}

function editItem(item) {
    // Edit existing item
    showNotification('Editar', 'Funcionalidad de edición en desarrollo.');
}

// Complete the loadSavedTrip function
function loadSavedTrip(tripId) {
    try {
        console.log('Loading saved trip with ID:', tripId);

        const savedTrips = JSON.parse(localStorage.getItem('viantryp_trips') || '[]');
        let trip = savedTrips.find(t => t.id == tripId);

        console.log('Found trip in storage:', trip);

        if (trip) {
            console.log('Loading trip data:', trip);

            // Load trip data
            if (trip.title) {
                const tripTitleInput = document.getElementById('tripTitle');
                if (tripTitleInput) {
                    tripTitleInput.value = trip.title;
                    console.log('Set trip title:', trip.title);
                }
            }

            if (trip.startDate) {
                const startDateInput = document.getElementById('startDate');
                if (startDateInput) {
                    startDateInput.value = trip.startDate;
                    startDate = new Date(trip.startDate + 'T00:00:00');
                    console.log('Set start date:', trip.startDate);
                }
            }

            if (trip.itemsData) {
                itemsData = trip.itemsData;
                console.log('Loaded items data:', itemsData);

                // Rebuild UI from itemsData
                rebuildUIFromData();
            }

            // Update day counter
            if (trip.itemsData) {
                const maxDay = Math.max(...Object.values(trip.itemsData).map(item => item.day || 1), 1);
                dayCounter = maxDay;
            }

            showNotification('Viaje Cargado', 'Tu viaje ha sido cargado exitosamente.');
        } else {
            console.log('Trip not found, creating new trip');
            createNewTrip();
        }
    } catch (error) {
        console.error('Error loading saved trip:', error);
        showNotification('Error', 'Hubo un problema al cargar el viaje.');
    }
}

function rebuildUIFromData() {
    // Clear existing days
    const timeline = document.getElementById('timeline');
    if (timeline) {
        timeline.innerHTML = '';
    }

    // Group items by day
    const itemsByDay = {};
    Object.values(itemsData).forEach(item => {
        const day = item.day || 1;
        if (!itemsByDay[day]) {
            itemsByDay[day] = [];
        }
        itemsByDay[day].push(item);
    });

    // Create days
    const maxDay = Math.max(...Object.keys(itemsByDay).map(d => parseInt(d)), 1);
    for (let day = 1; day <= maxDay; day++) {
        addNewDay();
        const dayItems = itemsByDay[day] || [];
        dayItems.forEach(item => {
            const elementDiv = createElementDiv(item);
            const dayContainer = document.querySelector(`[data-day="${day}"] .day-items`);
            if (dayContainer) {
                dayContainer.appendChild(elementDiv);
            }
        });
    }
}
