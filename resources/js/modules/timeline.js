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

        // If data is an object, create the element directly
        console.log('Creating element div for data:', data);
        const elementDiv = this.createElementDiv(data);
        console.log('Element div created:', elementDiv);

        const dayCard = document.querySelector(`[data-day="${data.day}"]`);
        console.log('Day card found:', dayCard);

        if (dayCard) {
            const dayContent = dayCard.querySelector('.day-content');
            console.log('Day content found:', dayContent);

            if (dayContent) {
                // Remove the drag instruction if it exists
                const dragInstruction = dayContent.querySelector('.drag-instruction');
                if (dragInstruction) {
                    dragInstruction.remove();
                    console.log('Drag instruction removed');
                }

                // Add the element before the add-element-btn
                const addBtn = dayContent.querySelector('.add-element-btn');
                console.log('Add button found:', addBtn);

                if (addBtn) {
                    dayContent.insertBefore(elementDiv, addBtn);
                    console.log('Element inserted before add button');
                } else {
                    dayContent.appendChild(elementDiv);
                    console.log('Element appended to day content');
                }
            }
        }

        // Emit event to update summaries
        const event = new CustomEvent('elementAdded', {
            detail: { elementData: data }
        });
        document.dispatchEvent(event);

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
        const iconClass = this.getIconForType(data.type);
        const title = this.getElementTitle(data);
        const subtitle = this.getElementSubtitle(data);

        elementDiv.innerHTML = `
            <div class="item-header">
                <div class="item-icon ${data.type}-icon">
                    <i class="fas fa-${iconClass}"></i>
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

        const startDateInput = document.getElementById('start-date');
        let dayDate = 'Sin fecha';
        if (startDateInput && startDateInput.value) {
            const date = new Date(startDateInput.value);
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
        const data = {
            type: element.dataset.type,
            day: parseInt(element.closest('.day-card').dataset.day)
        };

        // Extract all data attributes
        Object.keys(element.dataset).forEach(key => {
            if (key === 'type') return;

            // element.dataset returns camelCase keys for data-attributes (e.g. departureAirport)
            // convert camelCase to snake_case so it matches the server-side data keys (departure_airport)
            const snake = key.replace(/([A-Z])/g, '_$1').toLowerCase();
            data[snake] = element.dataset[key];
        });

        return data;
    }
}
