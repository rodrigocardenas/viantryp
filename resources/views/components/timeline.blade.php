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
                <div class="day-header">
                    <h3>Día {{ $day->day }}</h3>
                    <p class="day-date">{{ $day->getFormattedDate() }}</p>
                    @if(count($trip->days) > 1)
                        <button class="delete-day-btn" data-action="delete-day" data-day="{{ $day->day }}" title="Eliminar día">
                            <i class="fas fa-trash"></i>
                        </button>
                    @endif
                </div>
                <div class="day-content" ondrop="drop(event)" ondragover="allowDrop(event)">

                    <p class="drag-instruction">Arrastra elementos aquí para personalizar este día</p>

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
            <div class="day-header">
                <h3>Día 1</h3>
                <p class="day-date" id="day-1-date">
                    @if(isset($trip) && $trip->start_date)
                        {{ $trip->start_date->format('l, d \d\e F \d\e Y') }}
                    @else
                        martes, 16 de septiembre de 2025
                    @endif
                </p>
                <button class="delete-day-btn" data-action="delete-day" data-day="1" title="Eliminar día">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
                            <div class="day-content" ondrop="drop(event)" ondragover="allowDrop(event)">
                    {{-- <div class="add-element-btn" data-action="add-element" data-day="1">
                        <i class="fas fa-plus"></i>
                    </div> --}}
                    <p class="drag-instruction">Arrastra elementos aquí para personalizar este día</p>
<br>
                </div>
</search>
</search_and_replace>
    @endif

    <!-- Add Day Section -->
    <x-editor-add-day-section />
</div></search>
</search_and_replace>

@push('scripts')
<script>
    // Timeline-specific JavaScript functions

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

        setupFileUploadListeners(elementType);

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
                    <button class="action-btn" data-action="edit-element" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn btn-danger" data-action="delete-element" title="Eliminar">
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
        setupFileUploadListeners(elementType);

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
                <div class="add-element-btn" data-action="add-element" data-day="${newDayNumber}">
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
                    airline: itemElement.getAttribute('data-airline') || '',
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
</script>
@endpush
