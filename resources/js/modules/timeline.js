// Timeline Module - Handles timeline operations, drag-drop, days management
export class TimelineManager {
    constructor() {
        this.daysContainer = null;
        this.draggedElement = null;
    }

    init() {
        this.daysContainer = document.getElementById('days-container');
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Timeline-specific event listeners will be added here
        console.log('TimelineManager initialized');
    }

    // Drag and drop functions
    allowDrop(ev) {
        ev.preventDefault();
    }

    drag(ev) {
        ev.dataTransfer.setData("text", ev.target.dataset.type);
    }

    drop(ev) {
        ev.preventDefault();
        const elementType = ev.dataTransfer.getData("text");
        const dayElement = ev.currentTarget.closest('.day-card');
        const dayNumber = parseInt(dayElement.dataset.day);

        // Emit event for modal manager
        const event = new CustomEvent('elementDropped', {
            detail: { elementType, dayNumber }
        });
        document.dispatchEvent(event);
    }

    addElementToDay(data) {
        console.log('TimelineManager.addElementToDay called with:', data);

        // If data is a number, it means we want to show the modal for that day
        if (typeof data === 'number') {
            const dayNumber = data;
            // Emit event to show element modal for this day
            const event = new CustomEvent('showElementModal', {
                detail: { dayNumber, elementType: null }
            });
            document.dispatchEvent(event);
            return;
        }

        // Determine the trip ID from the page URL (/trips/{id}/edit)
        const tripIdMatch = window.location.pathname.match(/\/trips\/(\d+)/);
        const tripId = tripIdMatch ? tripIdMatch[1] : null;

        if (tripId) {
            // Async path: ask the server to render the element HTML
            this._renderItemFromServer(tripId, data).then(html => {
                if (html) {
                    const wrapper = document.createElement('div');
                    wrapper.innerHTML = html.trim();
                    const elementDiv = wrapper.firstElementChild;

                    if (elementDiv) {
                        // If note_content exists, set it via dataset (not attribute) to preserve HTML
                        if (data.note_content) {
                            elementDiv.dataset.noteContent = data.note_content;
                        }
                        this._insertElementIntoDOM(elementDiv, data);
                        return;
                    }
                }
                // Fallback if server response is empty/malformed
                this._insertElementIntoDOM(this.createElementDiv(data), data);
            }).catch(() => {
                // Network/server error fallback
                this._insertElementIntoDOM(this.createElementDiv(data), data);
            });
        } else {
            // No trip ID available (e.g. creating mode), use JS rendering
            this._insertElementIntoDOM(this.createElementDiv(data), data);
        }
    }

    async _renderItemFromServer(tripId, data) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) return null;

        try {
            const response = await fetch(`/trips/${tripId}/render-item`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'text/html',
                },
                body: JSON.stringify(data),
            });

            if (!response.ok) return null;
            return await response.text();
        } catch (e) {
            console.warn('render-item endpoint failed, using JS fallback:', e);
            return null;
        }
    }

    _insertElementIntoDOM(elementDiv, data) {
        // Global note
        if (data.type === 'note' && (typeof data.day === 'undefined' || data.day === null)) {
            const globalNotesList = document.getElementById('global-notes-list');
            if (globalNotesList) {
                globalNotesList.appendChild(elementDiv);
                document.dispatchEvent(new CustomEvent('elementAdded', { detail: { elementData: data } }));
                return;
            }
        }

        const dayCard = document.querySelector(`[data-day="${data.day}"]`);
        if (dayCard) {
            const dayContent = dayCard.querySelector('.day-content');
            if (dayContent) {
                const dragInstruction = dayContent.querySelector('.drag-instruction');
                if (dragInstruction) dragInstruction.textContent = 'arrastra para agregar más elementos';
                dayContent.appendChild(elementDiv);
            }
        }

        document.dispatchEvent(new CustomEvent('elementAdded', { detail: { elementData: data } }));
        console.log('Element added to timeline successfully:', data);
    }


    createElementDiv(data) {
        const elementDiv = document.createElement('div');
        elementDiv.className = `timeline-item ${data.type}`;
        elementDiv.setAttribute('data-type', data.type);

        // Set data attributes for the element
        Object.keys(data).forEach(key => {
            if (key !== 'type' && key !== 'day') {
                let value = data[key];
                // Convert objects to JSON strings for data attributes
                if (typeof value === 'object' && value !== null) {
                    value = JSON.stringify(value);
                }
                elementDiv.setAttribute(`data-${key.replace(/_/g, '-')}`, value);
            }
        });

        // Create the HTML structure
        const iconClass = this.getIconClass(data.type);
        const icon = this.getIcon(data.type);
        const title = this.getElementTitle(data);
        const subtitle = this.getElementSubtitle(data);

        elementDiv.innerHTML = `
            <div class="item-header">
                <div class="item-icon ${iconClass}">
                    <i class="${icon}"></i>
                </div>
                <div class="item-info">
                    <div class="item-type">${this.getTypeLabel(data.type)}</div>
                    <div class="item-title">${title}</div>
                    <div class="item-subtitle">${subtitle}</div>
                </div>
                <div class="item-actions">
                    <button class="btn-edit" data-action="edit-element" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-delete" data-action="delete-element" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        return elementDiv;
    }

    getIconForType(type) {
        const icons = {
            'flight': 'plane',
            'hotel': 'hotel',
            'activity': 'map-marker-alt',
            'transport': 'car',
            'note': 'sticky-note',
            'summary': 'list-check',
            'total': 'dollar-sign'
        };
        return icons[type] || 'circle';
    }

    getIconClass(type) {
        const iconMap = {
            'flight': 'icon-flight',
            'hotel': 'icon-hotel',
            'activity': 'icon-activity',
            'transport': 'icon-transport',
            'note': 'icon-note',
            'summary': 'icon-summary',
            'total': 'icon-total'
        };
        return iconMap[type] || 'icon-note';
    }

    getIcon(type) {
        const iconMap = {
            'flight': 'fas fa-plane',
            'hotel': 'fas fa-bed',
            'activity': 'fas fa-map-marker-alt',
            'transport': 'fas fa-car',
            'note': 'fas fa-sticky-note',
            'summary': 'fas fa-list-check',
            'total': 'fas fa-dollar-sign'
        };
        return iconMap[type] || 'fas fa-sticky-note';
    }

    getTypeLabel(type) {
        const labels = {
            'flight': 'Vuelo',
            'hotel': 'Hotel',
            'activity': 'Actividad',
            'transport': 'Transporte',
            'note': 'Nota',
            'summary': 'Resumen',
            'total': 'Total'
        };
        return labels[type] || type;
    }

    getElementTitle(data) {
        switch (data.type) {
            case 'flight':
                return `${data.airline_id || 'Vuelo'} ${data.flight_number || ''}`.trim();
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
                const departureInfo = `${data.departure_airport || ''} ${data.departure_time || ''}`.trim();
                const arrivalInfo = `${data.arrival_airport || ''} ${data.arrival_time || ''}`.trim();
                if (departureInfo && arrivalInfo) {
                    return `${departureInfo} → ${arrivalInfo}`;
                }
                return departureInfo || arrivalInfo || '';
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
            case 'note':
                return data.note_content || '';
            default:
                return '';
        }
    }

    addNewDay() {
        const existingDays = this.daysContainer.querySelectorAll('.day-card');
        const newDayNumber = existingDays.length + 1;

        const dayCard = document.createElement('div');
        dayCard.className = 'day-card';
        dayCard.setAttribute('data-day', newDayNumber);

        let dayDate = 'Sin fecha';
        // Importante: NO auto-calcular fechas por "fecha inicio + día".
        // La fecha del día debe ser la ingresada por el usuario (o sin fecha).

        dayCard.innerHTML = `
            <div class="day-header">
                <h3>Día ${newDayNumber}</h3>
                <p class="day-date">${dayDate}</p>
            </div>
            <div class="day-content" ondrop="drop(event)" ondragover="allowDrop(event)">
                <div class="add-element-btn" data-action="add-element" data-day="${newDayNumber}">
                    <i class="fas fa-plus"></i>
                </div>
                <p class="drag-instruction">Arrastra elementos aquí para personalizar este día</p>
            </div>
        `;

        this.daysContainer.appendChild(dayCard);

        // Attach event listeners to the newly created day content so drag/drop works
        const dayContent = dayCard.querySelector('.day-content');
        if (dayContent) {
            // Use bound methods so `this` inside handlers refers to TimelineManager
            dayContent.addEventListener('dragover', (ev) => this.allowDrop(ev));
            dayContent.addEventListener('drop', (ev) => this.drop(ev));
        }

        // Attach click listener for add-element button to open modal for the correct day
        const addBtn = dayCard.querySelector('.add-element-btn');
        if (addBtn) {
            addBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const day = parseInt(addBtn.dataset.day) || newDayNumber;
                this.showAddElementModal(day);
            });
        }

        // Emit event to update summaries
        const event = new CustomEvent('dayAdded', {
            detail: { dayNumber: newDayNumber }
        });
        document.dispatchEvent(event);

        // Show notification
        this.showNotification('Día Agregado', `Día ${newDayNumber} agregado al itinerario.`);
    }

    showNotification(title, message, type = 'success') {
        // Simple notification implementation
        console.log(`${type.toUpperCase()}: ${title} - ${message}`);
        // You can implement a more sophisticated notification system here
    }

    // New methods for data-action support
    showAddElementModal(dayNumber) {
        // Emit event to show element modal for this day
        const event = new CustomEvent('elementDropped', {
            detail: { elementType: null, dayNumber }
        });
        document.dispatchEvent(event);
    }

    editElement(button) {
        const element = button.closest('.timeline-item');
        if (element) {
            // Extract element data and show edit modal
            const elementData = this.extractElementData(element);
            // Emit event to show edit modal
            const event = new CustomEvent('editElement', {
                detail: { element, elementData }
            });
            document.dispatchEvent(event);
        }
    }

    deleteElement(button) {
        const element = button.closest('.timeline-item');
        if (element && confirm('¿Estás seguro de que quieres eliminar este elemento?')) {
            element.remove();
            // Emit event to update summaries
            const event = new CustomEvent('elementDeleted', {
                detail: { elementType: element.dataset.type }
            });
            document.dispatchEvent(event);
            this.showNotification('Elemento Eliminado', 'El elemento ha sido eliminado del itinerario.');
        }
    }

    extractElementData(element) {
        const dayCard = element.closest('.day-card');
        const data = {
            type: element.dataset.type,
            day: dayCard ? parseInt(dayCard.dataset.day) : null
        };

        // Extract all data attributes
        Object.keys(element.dataset).forEach(key => {
            if (key === 'type') return;

            // element.dataset returns camelCase keys for data-attributes (e.g. departureAirport)
            // convert camelCase to snake_case so it matches the server-side data keys (departure_airport)
            const snake = key.replace(/([A-Z])/g, '_$1').toLowerCase();
            let value = element.dataset[key];

            // Try to parse JSON if it looks like an object or array
            if (typeof value === 'string' && (value.startsWith('{') || value.startsWith('['))) {
                try {
                    value = JSON.parse(value);
                } catch (e) {
                    // Not valid JSON, keep as string
                }
            }

            data[snake] = value;
        });

        return data;
    }
}
