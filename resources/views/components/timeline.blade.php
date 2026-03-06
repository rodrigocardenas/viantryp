{{-- Componente: Timeline --}}
{{-- Ubicación: resources/views/components/timeline.blade.php --}}
{{-- Propósito: Contenedor principal del timeline con días del viaje --}}
{{-- Props: trip (opcional) --}}
{{-- CSS: resources/css/components/timeline.css --}}

@props(['trip' => null])



<!-- Days Container -->
<div class="days-container" id="days-container">
    @if(isset($trip) && $trip->days && count($trip->days) > 0)
        @foreach($trip->days as $day)
            <div class="day-card" data-day="{{ $day->day }}">
                <button class="btn-delete-day-absolute" data-action="delete-day" data-day="{{ $day->day }}" title="Eliminar día">
                    <i class="fas fa-trash"></i>
                </button>
                <div class="day-header">
                    <div class="day-title-section">
                            <div class="day-title-row">
                                <h3>DÍA {{ $day->day }}</h3>
                                <span class="day-separator">|</span>
                                <input type="date" id="day-{{ $day->day }}-date" class="day-date-input-large" 
                                       value="{{ $day->getDateInputValue() }}" 
                                       data-day="{{ $day->day }}"
                                       style="opacity: 1; width: auto; height: auto; position: static;"
                                       @if($day->day == 1)
                                       onchange="document.getElementById('start-date').value = this.value; document.getElementById('day-{{ $day->day }}-date-display').innerText = new Date(this.value + 'T00:00:00').toLocaleDateString('es-ES', {weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'});"
                                       @else
                                       onchange="document.getElementById('day-{{ $day->day }}-date-display').innerText = new Date(this.value + 'T00:00:00').toLocaleDateString('es-ES', {weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'});"
                                       @endif
                                >
                            </div>
                            <p class="day-date-display" id="day-{{ $day->day }}-date-display">{{ $day->getFormattedDate() }}</p>
                    </div>
                </div>
                <div class="day-content" ondrop="drop(event)" ondragover="allowDrop(event)">
                    <p class="drag-instruction">@if($day->items && count($day->items) > 0) arrastra para agregar más elementos @else Arrastra aquí los elementos que quieres agregar a este día @endif</p>

                    @if($day->items && count($day->items) > 0)
                        @foreach($day->items as $item)
                            <x-trip-item :item="$item" :day="$day->day" />
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    @else
        {{-- Always show at least one day for editing --}}
        <div class="day-card" data-day="1">
            <button class="btn-delete-day-absolute" data-action="delete-day" data-day="1" title="Eliminar día" style="display: none;">
                <i class="fas fa-trash"></i>
            </button>
            <div class="day-header">
                <div class="day-title-section">
                    <div class="day-title-row">
                        <h3>DÍA 1</h3>
                        <span class="day-separator">|</span>
                        <input type="date" id="day-1-date" class="day-date-input-large" 
                               value="{{ isset($trip) && $trip->start_date ? $trip->start_date->format('Y-m-d') : '' }}" 
                               data-day="1"
                               style="opacity: 1; width: auto; height: auto; position: static;"
                               onchange="document.getElementById('start-date').value = this.value; document.getElementById('day-1-date-display').innerText = new Date(this.value + 'T00:00:00').toLocaleDateString('es-ES', {weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'});">
                    </div>
                    <p class="day-date-display" id="day-1-date-display">Sin fecha</p>
                </div>
            </div>
            <div class="day-content" ondrop="drop(event)" ondragover="allowDrop(event)">
                <p class="drag-instruction">Arrastra aquí los elementos que quieres agregar a este día</p>
            </div>
        </div>
    @endif


</div>

<!-- Add Day Section -->
    <x-editor-add-day-section />

@push('scripts')
<script>
    // Timeline-specific JavaScript functions

    // Timeline-specific JavaScript functions

    // Expose helper functions to global scope for inline scripts and modules
    if (typeof window !== 'undefined') {
        window.getTypeLabel = getTypeLabel;
        window.getIcon = getIcon;
        window.getIconClass = getIconClass;
    }

    function getElementSubtitle(data) {
        switch (data.type) {
            case 'flight':
                const departure = data.departure_airport || '';
                const arrival = data.arrival_airport || '';
                if (departure && arrival) {
                    return `${departure} → ${arrival}`;
                } else if (departure) {
                    return departure;
                } else if (arrival) {
                    return arrival;
                }
                return '';
            case 'hotel':
                const checkIn = data.check_in || '';
                const checkOut = data.check_out || '';
                if (checkIn && checkOut) {
                    return `${checkIn} - ${checkOut}`;
                } else if (checkIn) {
                    return checkIn;
                } else if (checkOut) {
                    return checkOut;
                }
                return '';
            case 'activity':
                return data.location || '';
            case 'transport':
                const pickup = data.pickup_location || '';
                const destination = data.destination || '';
                if (pickup && destination) {
                    return `${pickup} → ${destination}`;
                } else if (pickup) {
                    return pickup;
                } else if (destination) {
                    return destination;
                }
                return '';
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

    // Escuchar el evento 'editElement' que es despachado por timeline.js
    document.addEventListener('editElement', function(e) {
        const itemElement = e.detail.element;
        const itemData = e.detail.elementData;

    });

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

        let dayDate = 'Sin fecha';
        let defaultDate = '';

        // Create day header
        const dayHeader = document.createElement('div');
        dayHeader.className = 'day-header';

        // Create title section
        const titleSection = document.createElement('div');
        titleSection.className = 'day-title-section';

        const title = document.createElement('h3');
        title.textContent = `Día ${newDayNumber}`;
        titleSection.appendChild(title);

        const deleteBtn = document.createElement('button');
        deleteBtn.className = 'btn-delete-day';
        deleteBtn.setAttribute('data-action', 'delete-day');
        deleteBtn.setAttribute('data-day', newDayNumber);
        deleteBtn.title = 'Eliminar día';
        deleteBtn.innerHTML = '<i class="fas fa-trash"></i>';
        titleSection.appendChild(deleteBtn);

        dayHeader.appendChild(titleSection);

        // Create date section
        const dateSection = document.createElement('div');
        dateSection.className = 'day-date-section';

        const label = document.createElement('label');
        label.setAttribute('for', `day-${newDayNumber}-date`);
        label.textContent = 'Fecha:';
        dateSection.appendChild(label);

        const input = document.createElement('input');
        input.type = 'date';
        input.id = `day-${newDayNumber}-date`;
        input.className = 'day-date-input';
        input.value = defaultDate;
        input.setAttribute('data-day', newDayNumber);
        dateSection.appendChild(input);

        const display = document.createElement('p');
        display.className = 'day-date-display';
        display.id = `day-${newDayNumber}-date-display`;
        display.textContent = dayDate;
        dateSection.appendChild(display);

        dayHeader.appendChild(dateSection);

        dayCard.appendChild(dayHeader);

        // Create day content
        const dayContent = document.createElement('div');
        dayContent.className = 'day-content';
        dayContent.setAttribute('ondrop', 'drop(event)');
        dayContent.setAttribute('ondragover', 'allowDrop(event)');

        const instruction = document.createElement('p');
        instruction.className = 'drag-instruction';
        instruction.textContent = 'Arrastra aquí los elementos que quieres agregar a este día';
        dayContent.appendChild(instruction);

        dayCard.appendChild(dayContent);

        daysContainer.appendChild(dayCard);

        // Update summaries after adding new day
        updateAllSummaries();

        showNotification('Día Agregado', `Día ${newDayNumber} agregado al itinerario.`);
    }

    function updateItineraryDates() {
        // Mantener por compatibilidad, pero NO auto-calcular fechas.
        // Las fechas de los días deben permanecer tal como se registran manualmente.
        updateAllSummaries();
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
                    airline_id: itemElement.getAttribute('data-airline_id') || '',
                    flight_number: itemElement.getAttribute('data-flight-number') || '',
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
                    meal_plan: itemElement.getAttribute('data-meal-plan') || '',
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
                    transport_type: itemElement.getAttribute('data-transport-type') || '',
                    pickup_location: itemElement.getAttribute('data-pickup-location') || '',
                    destination: itemElement.getAttribute('data-destination') || '',
                    pickup_datetime: itemElement.getAttribute('data-pickup-datetime') || '',
                    arrival_datetime: itemElement.getAttribute('data-arrival-datetime') || ''
                };

            case 'nota':
                return {
                    ...baseData,
                    type: 'note',
                    note_title: itemElement.querySelector('.item-title')?.textContent || '',
                    note_content: itemElement.dataset.noteContent || itemElement.querySelector('.item-subtitle')?.innerHTML || ''
                };

            default:
                return {
                    ...baseData,
                    type: 'note',
                    note_title: itemElement.querySelector('.item-title')?.textContent || 'Elemento',
                    note_content: itemElement.dataset.noteContent || itemElement.querySelector('.item-subtitle')?.innerHTML || ''
                };
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
                    <button class="action-btn summary-update-btn" data-action="update-summaries" title="Actualizar resumen">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <button class="action-btn btn-danger" data-action="delete-element" title="Eliminar">
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
                    <button class="action-btn summary-update-btn" data-action="update-summaries" title="Actualizar total">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <button class="action-btn btn-danger" data-action="delete-element" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        return elementDiv;
    }

    // Automatic Itinerary Summary Generation
    function generateItinerarySummary() {
        const tripTitle = document.getElementById('trip-title').value.trim() || 'Mi Viaje';
        const dayContainers = document.querySelectorAll('.day-card');

        let summary = `<strong>${tripTitle}</strong><br>`;

        // Duración: basada en fechas ingresadas por el usuario (sin auto-calcular por fecha inicio + día).
        const dayDateValues = Array.from(dayContainers)
            .map((card, idx) => {
                const dayNumber = parseInt(card.dataset.day) || (idx + 1);
                const input = document.getElementById(`day-${dayNumber}-date`) || card.querySelector('.day-date-input');
                return input && input.value ? input.value : null;
            })
            .filter(Boolean);

        if (dayContainers.length > 0) {
            if (dayDateValues.length > 0) {
                const sorted = [...dayDateValues].sort();
                const startDateObj = new Date(sorted[0] + 'T00:00:00');
                const endDateObj = new Date(sorted[sorted.length - 1] + 'T00:00:00');
                const formatDate = (date) =>
                    date.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
                summary += `<strong>Duración:</strong> ${dayContainers.length} días (${formatDate(startDateObj)} - ${formatDate(endDateObj)})<br><br>`;
            } else {
                summary += `<strong>Duración:</strong> ${dayContainers.length} días<br><br>`;
            }
        }

        // Group items by day
        const itemsByDay = {};

        // Initialize days
        for (let i = 1; i <= dayContainers.length; i++) {
            itemsByDay[i] = [];
        }

        // Collect all timeline items and group by day
        dayContainers.forEach((dayCard, index) => {
            const dayNumber = parseInt(dayCard.dataset.day) || (index + 1);
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
                const dayCard = document.querySelector(`.day-card[data-day="${dayNumber}"]`);
                const dateInput = document.getElementById(`day-${dayNumber}-date`) || dayCard?.querySelector('.day-date-input');
                const dayDateValue = dateInput && dateInput.value ? dateInput.value : null;
                const dayDate = dayDateValue ? new Date(dayDateValue + 'T00:00:00') : null;

                const formatDayDate = (date) => {
                    return date.toLocaleDateString('es-ES', {
                        weekday: 'long',
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                };

                summary += `<strong>Día ${dayNumber} - ${dayDate ? formatDayDate(dayDate) : 'Sin fecha'}</strong><br>`;

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

    // Handle day date changes
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('day-date-input')) {
            const dayNumber = e.target.dataset.day;
            const newDate = e.target.value;
            const displayElement = document.getElementById(`day-${dayNumber}-date-display`);

            if (newDate) {
                const date = new Date(newDate + 'T00:00:00');
                const formattedDate = date.toLocaleDateString('es-ES', {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
                displayElement.textContent = formattedDate;
            } else {
                displayElement.textContent = 'Sin fecha';
            }

            // Validate elements in this day
            validateDayElements(dayNumber, newDate);
        }
    });

    // Handle day deletion
    document.addEventListener('click', function(e) {
        const deleteButton = e.target.closest('[data-action="delete-day"]');
        if (deleteButton) {
            e.preventDefault(); // Stop form submissions or navigation if it's an a tag
            e.stopPropagation(); // Stop parent clicks from intercepting
            const dayNumber = deleteButton.dataset.day;

            if (confirm(`¿Estás seguro de que quieres eliminar el Día ${dayNumber}? Se eliminarán todos los elementos contenidos en este día.`)) {
                deleteDay(dayNumber);
            }
        }
    });

    function validateDayElements(dayNumber, dayDate) {
        if (!dayDate) return; // No validation if no date set

        const dayCard = document.querySelector(`[data-day="${dayNumber}"]`);
        const elements = dayCard.querySelectorAll('.timeline-item');

        elements.forEach(element => {
            const elementData = extractItemData(element, dayNumber);
            if (elementData) {
                const elementDates = getElementDates(elementData);
                const hasMismatch = elementDates.some(date => date && date !== dayDate);

                if (hasMismatch) {
                    showDateMismatchDialog(dayNumber, elementData.type);
                    element.classList.add('date-mismatch');
                } else {
                    element.classList.remove('date-mismatch');
                }
            }
        });
    }

    function getElementDates(elementData) {
        const dates = [];

        switch (elementData.type) {
            case 'flight':
                if (elementData.departure_time) {
                    dates.push(elementData.departure_time.split(' ')[0]);
                }
                break;
            case 'hotel':
                if (elementData.check_in) dates.push(elementData.check_in);
                if (elementData.check_out) dates.push(elementData.check_out);
                break;
            case 'activity':
                // Activities might not have dates, skip validation
                break;
            case 'transport':
                if (elementData.pickup_datetime) {
                    dates.push(elementData.pickup_datetime.split(' ')[0]);
                }
                if (elementData.arrival_datetime) {
                    dates.push(elementData.arrival_datetime.split(' ')[0]);
                }
                break;
        }

        return dates;
    }

    function showDateMismatchDialog(dayNumber, elementType) {
        const typeLabels = {
            'flight': 'vuelo',
            'hotel': 'hotel',
            'activity': 'actividad',
            'transport': 'transporte'
        };

        const typeLabel = typeLabels[elementType] || 'elemento';

        // Removed warning dialog as requested
        // alert(`Advertencia: El ${typeLabel} en el Día ${dayNumber} tiene fechas que no coinciden con la fecha asignada al día. Por favor, corrige las fechas del elemento.`);
    }

    function deleteDay(dayNumber) {
        const dayCard = document.querySelector(`[data-day="${dayNumber}"]`);
        if (dayCard) {
            dayCard.remove();

            updateAllSummaries();
            showNotification('Día Eliminado', `Día ${dayNumber} y todos sus elementos han sido eliminados.`);
        }
    }
</script>
@endpush

<style>
    .day-header {
        display: flex;
        justify-content: flex-start;
        align-items: flex-start;
        margin-bottom: 1rem;
        padding: 1rem;
        background: var(--stone-50);
        border-radius: 12px;
        border: 1px solid var(--stone-200);
    }

    .day-card {
        position: relative;
    }

    .day-title-section {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
        width: 100%;
    }

    .day-title-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .day-title-row h3 {
        margin: 0;
        color: var(--ink);
        font-size: 1.25rem;
        font-weight: 600;
    }

    .day-separator {
        font-weight: bold;
        color: var(--slate-400);
        font-size: 1.1rem;
    }

    .day-date-input-large {
        padding: 0.5rem;
        border: 1px solid var(--stone-300);
        border-radius: 6px;
        font-size: 1rem;
        width: 160px;
    }

    .day-date-display {
        font-size: 0.875rem;
        color: var(--slate-500);
        margin: 0;
        text-align: left;
    }

    .btn-delete-day-absolute {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: var(--red-500);
        color: white;
        border: none;
        border-radius: 6px;
        padding: 0.8rem 0.8rem;
        cursor: pointer;
        font-size: 0.875rem;
        transition: background-color 0.2s;
        z-index: 10;
    }

    .btn-delete-day-absolute:hover {
        background: var(--red-600);
    }

    .timeline-item.date-mismatch {
        border-color: var(--red-400);
        background: var(--red-50);
    }

    /* Removed warning logo as requested */
    /* .timeline-item.date-mismatch::before {
        content: '⚠️';
        position: absolute;
        top: -5px;
        right: -5px;
        background: var(--red-500);
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
    } */

    @media (max-width: 768px) {
        .day-header {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }

        .day-title-section {
            gap: 1rem;
        }

        .day-title-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .day-separator {
            display: none;
        }

        .day-date-input-large {
            width: 100%;
            margin-left: 0;
        }

        .day-date-display {
            text-align: left;
        }

        .btn-delete-day-absolute {
            top: 0.25rem;
            right: 0.25rem;
            padding: 0.2rem 0.4rem;
            font-size: 0.8rem;
        }
    }
</style>
