// Element Manager Module for Viantryp Editor
// Handles element creation, editing, and management

// Add element to day
function addElementToDay(dayNumber, elementType) {
    console.log('Adding element to day:', dayNumber, elementType);

    if (typeof elementType === 'string') {
        // This is a new element being dragged
        currentElementType = elementType;
        currentDay = dayNumber;
        showElementModal();
    } else {
        // This is form data being saved
        const formData = elementType;
        const itemId = Date.now().toString();
        formData.id = itemId;

        // Add to itemsData
        itemsData[itemId] = formData;

        // Create and add element to UI
        const elementDiv = createElementDiv(formData);
        const dayContainer = document.querySelector(`[data-day="${dayNumber}"] .day-items`);
        if (dayContainer) {
            // Remove placeholder if it exists
            const placeholder = dayContainer.querySelector('div[style*="text-align: center"]');
            if (placeholder) {
                placeholder.remove();
            }
            dayContainer.appendChild(elementDiv);
        }

        console.log('Element added:', formData);
    }
}

// Show element modal
function showElementModal() {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.id = 'element-modal';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h3>Agregar ${getTypeLabel(currentElementType)}</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body" id="modal-body">
                ${getElementForm(currentElementType)}
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeModal()">Cancelar</button>
                <button class="btn-primary" onclick="saveElement()">Guardar</button>
            </div>
        </div>
    `;

    document.body.appendChild(modal);
    modal.style.display = 'block';

    // Focus on first input
    setTimeout(() => {
        const firstInput = modal.querySelector('input');
        if (firstInput) firstInput.focus();

        // Initialize autocomplete for modal fields
        if (window.AutocompleteModule) {
            window.AutocompleteModule.initializeModalAutocomplete();
        }
    }, 100);
}

// Close modal
function closeModal() {
    const modal = document.getElementById('element-modal');
    if (modal) {
        modal.remove();
    }
    currentElementType = null;
    currentElementData = {};
    currentDay = null;
}

// Get type label
function getTypeLabel(type) {
    const labels = {
        'flight': 'Vuelo',
        'hotel': 'Hotel',
        'activity': 'Actividad',
        'transport': 'Transporte',
        'note': 'Nota',
        'summary': 'Resumen',
        'total': 'Total'
    };
    return labels[type] || 'Elemento';
}

// Get element form
function getElementForm(type) {
    const forms = {
        'flight': `
            <div class="form-group">
                <label for="airline">Aerolínea</label>
                <input type="text" id="airline" class="form-input" placeholder="Ej: Iberia" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="flight-number">Número de Vuelo</label>
                <input type="text" id="flight-number" class="form-input" placeholder="Ej: IB1234" autocomplete="off">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="departure-time">Hora de Salida</label>
                    <input type="time" id="departure-time" class="form-input" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="arrival-time">Hora de Llegada</label>
                    <input type="time" id="arrival-time" class="form-input" autocomplete="off">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="departure-airport">Aeropuerto de Salida</label>
                    <input type="text" id="departure-airport" class="form-input" placeholder="Ej: Madrid Barajas" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="arrival-airport">Aeropuerto de Llegada</label>
                    <input type="text" id="arrival-airport" class="form-input" placeholder="Ej: París Charles de Gaulle" autocomplete="off">
                </div>
            </div>
            <div class="form-group">
                <label for="confirmation-number">Número de Confirmación</label>
                <input type="text" id="confirmation-number" class="form-input" placeholder="Ej: ABC123" autocomplete="off">
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

// Save element
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

// Collect form data
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

// Create element div (missing function that was referenced)
function createElementDiv(item) {
    const elementDiv = document.createElement('div');
    elementDiv.className = 'timeline-item';
    elementDiv.setAttribute('data-id', item.id);
    elementDiv.setAttribute('data-type', item.type);

    const iconClass = getIconForType(item.type);
    const typeLabel = getTypeLabel(item.type);

    let content = '';
    switch (item.type) {
        case 'flight':
            content = `
                <div class="item-header">
                    <div class="item-icon icon-flight">
                        <i class="fas fa-${iconClass}"></i>
                    </div>
                    <div class="item-info">
                        <div class="item-type">${typeLabel}</div>
                        <div class="item-title">${item.airline || 'Aerolínea'} ${item.flightNumber || ''}</div>
                        <div class="item-subtitle">${item.originAirport || 'Origen'} → ${item.destinationAirport || 'Destino'}</div>
                    </div>
                    <button class="item-toggle" onclick="toggleItem(this)">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="item-content">
                    <div class="flight-details">
                        <div class="flight-route">
                            <div class="flight-segment">
                                <div class="flight-time">${item.departureTime || '--:--'}</div>
                                <div class="flight-airport">${item.originAirport || 'Origen'}</div>
                            </div>
                            <div class="flight-path">
                                <div class="flight-line"></div>
                                <div class="flight-plane"><i class="fas fa-plane"></i></div>
                            </div>
                            <div class="flight-segment">
                                <div class="flight-time">${item.arrivalTime || '--:--'}</div>
                                <div class="flight-airport">${item.destinationAirport || 'Destino'}</div>
                            </div>
                        </div>
                        <div class="flight-sections">
                            <div class="flight-section">
                                <div class="section-title">Detalles del Vuelo</div>
                                <div class="reservation-details">
                                    <div class="reservation-item">
                                        <span class="reservation-label">Aerolínea:</span>
                                        <span class="reservation-value">${item.airline || 'No especificado'}</span>
                                    </div>
                                    <div class="reservation-item">
                                        <span class="reservation-label">Número:</span>
                                        <span class="reservation-value">${item.flightNumber || 'No especificado'}</span>
                                    </div>
                                    <div class="reservation-item">
                                        <span class="reservation-label">Confirmación:</span>
                                        <span class="reservation-value">${item.confirmationNumber || 'No especificado'}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            break;
        case 'hotel':
            content = `
                <div class="item-header">
                    <div class="item-icon icon-hotel">
                        <i class="fas fa-${iconClass}"></i>
                    </div>
                    <div class="item-info">
                        <div class="item-type">${typeLabel}</div>
                        <div class="item-title">${item.hotelName || 'Hotel'}</div>
                        <div class="item-subtitle">${item.nights || '0'} noches • ${item.roomType || 'Habitación'}</div>
                    </div>
                    <button class="item-toggle" onclick="toggleItem(this)">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="item-content">
                    <div class="item-details">
                        <div class="detail-row">
                            <div class="detail-icon-small">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="detail-text-small">
                                <div class="detail-label-small">Check-in / Check-out</div>
                                <div class="detail-value-small">${item.checkinDate || '--:--'} / ${item.checkoutDate || '--:--'}</div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-icon-small">
                                <i class="fas fa-bed"></i>
                            </div>
                            <div class="detail-text-small">
                                <div class="detail-label-small">Tipo de habitación</div>
                                <div class="detail-value-small">${item.roomType || 'No especificado'}</div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-icon-small">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="detail-text-small">
                                <div class="detail-label-small">Noches</div>
                                <div class="detail-value-small">${item.nights || '0'}</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            break;
        case 'activity':
            content = `
                <div class="item-header">
                    <div class="item-icon icon-activity">
                        <i class="fas fa-${iconClass}"></i>
                    </div>
                    <div class="item-info">
                        <div class="item-type">${typeLabel}</div>
                        <div class="item-title">${item.activityTitle || 'Actividad'}</div>
                        <div class="item-subtitle">${item.startTime || '--:--'} - ${item.endTime || '--:--'} • ${item.location || 'Ubicación'}</div>
                    </div>
                    <button class="item-toggle" onclick="toggleItem(this)">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="item-content">
                    <div class="item-details">
                        <div class="detail-row">
                            <div class="detail-icon-small">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="detail-text-small">
                                <div class="detail-label-small">Horario</div>
                                <div class="detail-value-small">${item.startTime || '--:--'} - ${item.endTime || '--:--'}</div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-icon-small">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="detail-text-small">
                                <div class="detail-label-small">Ubicación</div>
                                <div class="detail-value-small">${item.location || 'No especificada'}</div>
                            </div>
                        </div>
                        ${item.activityDescription ? `
                        <div class="detail-row">
                            <div class="detail-icon-small">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="detail-text-small">
                                <div class="detail-label-small">Descripción</div>
                                <div class="detail-value-small">${item.activityDescription}</div>
                            </div>
                        </div>
                        ` : ''}
                    </div>
                </div>
            `;
            break;
        case 'transport':
            content = `
                <div class="item-header">
                    <div class="item-icon icon-transport">
                        <i class="fas fa-${iconClass}"></i>
                    </div>
                    <div class="item-info">
                        <div class="item-type">${typeLabel}</div>
                        <div class="item-title">${item.transportType || 'Transporte'}</div>
                        <div class="item-subtitle">${item.originLocation || 'Origen'} → ${item.destinationLocation || 'Destino'}</div>
                    </div>
                    <button class="item-toggle" onclick="toggleItem(this)">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="item-content">
                    <div class="item-details">
                        <div class="detail-row">
                            <div class="detail-icon-small">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="detail-text-small">
                                <div class="detail-label-small">Hora de recogida</div>
                                <div class="detail-value-small">${item.pickupTime || '--:--'}</div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-icon-small">
                                <i class="fas fa-route"></i>
                            </div>
                            <div class="detail-text-small">
                                <div class="detail-label-small">Ruta</div>
                                <div class="detail-value-small">${item.originLocation || 'Origen'} → ${item.destinationLocation || 'Destino'}</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            break;
        case 'note':
            content = `
                <div class="item-header">
                    <div class="item-icon icon-note">
                        <i class="fas fa-${iconClass}"></i>
                    </div>
                    <div class="item-info">
                        <div class="item-type">${typeLabel}</div>
                        <div class="item-title">${item.noteTitle || 'Nota'}</div>
                        <div class="item-subtitle">${item.noteContent ? item.noteContent.substring(0, 50) + '...' : 'Sin contenido'}</div>
                    </div>
                    <button class="item-toggle" onclick="toggleItem(this)">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="item-content">
                    <div class="item-details">
                        <div class="detail-row">
                            <div class="detail-icon-small">
                                <i class="fas fa-sticky-note"></i>
                            </div>
                            <div class="detail-text-small">
                                <div class="detail-label-small">Contenido</div>
                                <div class="detail-value-small">${item.noteContent || 'Sin contenido'}</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            break;
        case 'summary':
            content = `
                <div class="item-header">
                    <div class="item-icon icon-summary">
                        <i class="fas fa-${iconClass}"></i>
                    </div>
                    <div class="item-info">
                        <div class="item-type">${typeLabel}</div>
                        <div class="item-title">${item.summaryTitle || 'Resumen'}</div>
                        <div class="item-subtitle">${item.summaryContent ? item.summaryContent.substring(0, 50) + '...' : 'Sin contenido'}</div>
                    </div>
                    <button class="item-toggle" onclick="toggleItem(this)">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="item-content">
                    <div class="item-details">
                        <div class="detail-row">
                            <div class="detail-icon-small">
                                <i class="fas fa-list-check"></i>
                            </div>
                            <div class="detail-text-small">
                                <div class="detail-label-small">Resumen</div>
                                <div class="detail-value-small">${item.summaryContent || 'Sin contenido'}</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            break;
        case 'total':
            content = `
                <div class="item-header">
                    <div class="item-icon icon-total">
                        <i class="fas fa-${iconClass}"></i>
                    </div>
                    <div class="item-info">
                        <div class="item-type">${typeLabel}</div>
                        <div class="item-title">${item.currency || 'USD'} ${item.totalPrice || '0.00'}</div>
                        <div class="item-subtitle">${item.totalDescription ? item.totalDescription.substring(0, 50) + '...' : 'Sin descripción'}</div>
                    </div>
                    <button class="item-toggle" onclick="toggleItem(this)">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="item-content">
                    <div class="item-details">
                        <div class="detail-row">
                            <div class="detail-icon-small">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="detail-text-small">
                                <div class="detail-label-small">Total</div>
                                <div class="detail-value-small">${item.currency || 'USD'} ${item.totalPrice || '0.00'}</div>
                            </div>
                        </div>
                        ${item.totalDescription ? `
                        <div class="detail-row">
                            <div class="detail-icon-small">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="detail-text-small">
                                <div class="detail-label-small">Descripción</div>
                                <div class="detail-value-small">${item.totalDescription}</div>
                            </div>
                        </div>
                        ` : ''}
                    </div>
                </div>
            `;
            break;
        default:
            content = `
                <div class="item-header">
                    <div class="item-icon">
                        <i class="fas fa-${iconClass}"></i>
                    </div>
                    <div class="item-info">
                        <div class="item-type">${typeLabel}</div>
                        <div class="item-title">Elemento</div>
                        <div class="item-subtitle">Tipo desconocido</div>
                    </div>
                    <button class="item-toggle" onclick="toggleItem(this)">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="item-content">
                    <p>Contenido no disponible</p>
                </div>
            `;
    }

    elementDiv.innerHTML = content;
    return elementDiv;
}

// Toggle item visibility
function toggleItem(button) {
    const itemContent = button.closest('.timeline-item').querySelector('.item-content');
    const icon = button.querySelector('i');

    if (itemContent.style.display === 'none' || !itemContent.style.display) {
        itemContent.style.display = 'block';
        icon.className = 'fas fa-chevron-up';
    } else {
        itemContent.style.display = 'none';
        icon.className = 'fas fa-chevron-down';
    }
}

// Edit item
function editItem(item) {
    // Edit existing item
    showNotification('Editar', 'Funcionalidad de edición en desarrollo.');
}

// Export functions for use in other modules
window.ElementManagerModule = {
    addElementToDay,
    showElementModal,
    closeModal,
    getTypeLabel,
    getElementForm,
    saveElement,
    collectFormData,
    createElementDiv,
    toggleItem,
    editItem
};
