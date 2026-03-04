{{-- Componente: Timeline --}}
{{-- Ubicación: resources/views/components/timeline.blade.php --}}
{{-- Propósito: Contenedor principal del timeline con días del viaje --}}
{{-- Props: trip (opcional) --}}
{{-- CSS: resources/css/components/timeline.css --}}

@props(['trip' => null])

<!-- Global Notes Section (Redesigned) -->
<div class="section-title-notes" id="global-notes-section">
    <div class="section-title-icon-notes">
        <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
    </div>
    <h3>Notas Generales</h3>
</div>

<div class="editor-card-notes">
    <!-- Toolbar -->
    <div class="toolbar-notes">
        <!-- Paragraph style -->
        <div class="toolbar-group-notes">
            <select class="tb-select-notes" id="note-style-select" title="Estilo de párrafo">
                <option value="p">Párrafo</option>
                <option value="h1">Título 1</option>
                <option value="h2">Título 2</option>
                <option value="h3">Título 3</option>
                <option value="blockquote">Cita</option>
            </select>
        </div>

        <!-- Bold / Italic / Underline -->
        <div class="toolbar-group-notes">
            <button class="tb-btn-notes" data-cmd="bold" title="Negrita"><b>B</b></button>
            <button class="tb-btn-notes" data-cmd="italic" title="Cursiva"><i>I</i></button>
            <button class="tb-btn-notes" data-cmd="underline" title="Subrayado"><u>U</u></button>
        </div>

        <!-- Lists -->
        <div class="toolbar-group-notes">
            <button class="tb-btn-notes" data-cmd="insertOrderedList" title="Lista numerada">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="10" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="10" y1="18" x2="21" y2="18"/><path d="M4 6h1v4"/><path d="M4 10h2"/><path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/></svg>
            </button>
            <button class="tb-btn-notes" data-cmd="insertUnorderedList" title="Lista con viñetas">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/><circle cx="4" cy="6" r="1.5" fill="currentColor" stroke="none"/><circle cx="4" cy="12" r="1.5" fill="currentColor" stroke="none"/><circle cx="4" cy="18" r="1.5" fill="currentColor" stroke="none"/></svg>
            </button>
        </div>

        <!-- Alignment -->
        <div class="toolbar-group-notes">
            <button class="tb-btn-notes" data-cmd="justifyLeft" title="Alinear izquierda">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="15" y2="12"/><line x1="3" y1="18" x2="18" y2="18"/></svg>
            </button>
            <button class="tb-btn-notes" data-cmd="justifyCenter" title="Centrar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="6" y1="12" x2="18" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg>
            </button>
            <button class="tb-btn-notes" data-cmd="justifyRight" title="Alinear derecha">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="9" y1="12" x2="21" y2="12"/><line x1="6" y1="18" x2="21" y2="18"/></svg>
            </button>
        </div>

        <!-- Link / Clear -->
        <div class="toolbar-group-notes">
            <button class="tb-btn-notes" id="btn-note-link" title="Insertar enlace">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
            </button>
            <button class="tb-btn-notes" data-cmd="removeFormat" title="Limpiar formato">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7V4h16v3"/><path d="M9 20h6"/><path d="M12 4v16"/><line x1="3" y1="21" x2="21" y2="3"/></svg>
            </button>
        </div>
    </div>

    <!-- Editable area -->
    <div
        class="editor-area-notes"
        id="global-note-editor"
        contenteditable="true"
        data-placeholder="Escribe una nota general..."
    ></div>

    <!-- Footer -->
    <div class="editor-footer-notes">
        <span class="char-count-notes" id="char-count-notes">0 caracteres</span>
        <div class="notes-actions">
            <button class="btn-notes btn-save-notes" id="btn-save-global-note" type="button">Agregar nota</button>
        </div>
    </div>
</div>

<!-- Link Modal for Notes -->
<div class="link-modal-notes" id="link-modal-notes">
    <div class="link-box-notes">
        <h4>Insertar enlace</h4>
        <input class="link-input-notes" id="link-url-notes" type="url" placeholder="https://...">
        <div class="editor-actions-notes">
            <button class="btn-notes btn-cancel-notes" id="btn-close-link-notes">Cancelar</button>
            <button class="btn-notes btn-save-notes" id="btn-insert-link-notes">Insertar</button>
        </div>
    </div>
</div>

<div class="notes-container" id="global-notes-list" ondrop="drop(event)" ondragover="allowDrop(event)">
@if(isset($trip) && $trip->notes && count($trip->notes) > 0)
    @foreach($trip->notes as $note)
        <x-trip-item :item="$note" :day="null" />
    @endforeach
@endif
</div>

<!-- Days Container -->
<div class="days-container" id="days-container">
    @if(isset($trip) && $trip->days && count($trip->days) > 0)
        @foreach($trip->days as $day)
            <div class="day-card" data-day="{{ $day->day }}">
                <div class="day-header">
                    <div class="day-title-section">
                        <div class="day-title-row">
                            <h3>DÍA {{ $day->day }}</h3>
                            <span class="day-separator">|</span>
                            <input type="date" id="day-{{ $day->day }}-date" class="day-date-input-large" value="{{ $day->getDateInputValue() }}" data-day="{{ $day->day }}">
                        </div>
                        <p class="day-date-display" id="day-{{ $day->day }}-date-display">{{ $day->getFormattedDate() ?: 'Sin fecha' }}</p>
                    </div>
                    <div class="day-actions">
                            <button class="action-btn-outline" data-action="copy-day" data-day="{{ $day->day }}" title="Copiar día">
                                <i class="far fa-copy"></i>
                            </button>
                            <button class="action-btn-outline text-danger" data-action="delete-day" data-day="{{ $day->day }}" title="Eliminar día">
                                <i class="far fa-trash-alt"></i>
                            </button>
                        </div>
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
                <div class="day-header">
                    <div class="day-title-section">
                        <div class="day-title-row">
                            <h3>DÍA 1</h3>
                            <span class="day-separator">|</span>
                            <input type="date" id="day-1-date" class="day-date-input-large" value="" data-day="1">
                        </div>
                        <p class="day-date-display" id="day-1-date-display">Sin fecha</p>
                    </div>
                    <div class="day-actions">
                            <button class="action-btn-outline" data-action="copy-day" data-day="1" title="Copiar día">
                                <i class="far fa-copy"></i>
                            </button>
                            <button class="action-btn-outline text-danger" data-action="delete-day" data-day="1" title="Eliminar día" style="display: none;">
                                <i class="far fa-trash-alt"></i>
                            </button>
                        </div>
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
                // For display, strip HTML tags to show plain text in subtitle
                const noteContent = data.note_content || '';
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = noteContent;
                const plainText = tempDiv.textContent || tempDiv.innerText || '';
                // Truncate if too long
                return plainText.length > 100 ? plainText.substring(0, 100) + '...' : plainText;
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

    // Add global notes collected from the global notes container
    collectGlobalNotes(items);
    return items;
    }

    // Also collect global notes that are outside of days
    function collectGlobalNotes(itemsArray) {
        const notesList = document.querySelectorAll('#global-notes-list .timeline-item');
        notesList.forEach(item => {
            const itemData = extractItemData(item, null); // day null for global
            if (itemData) itemsArray.push(itemData);
        });
    }

    // --- General Notes Custom Editor logic ---
    let savedRange = null;

    function initializeGeneralNotesEditor() {
        const editor = document.getElementById('global-note-editor');
        if (!editor) return;

        // Toolbar Button Commands
        document.querySelectorAll('.tb-btn-notes[data-cmd]').forEach(btn => {
            btn.addEventListener('click', function() {
                const cmd = this.dataset.cmd;
                editor.focus();
                document.execCommand(cmd, false, null);
                updateCount();
            });
        });

        // Style Selector (P, H1, H2, H3, Blockquote)
        const styleSelect = document.getElementById('note-style-select');
        if (styleSelect) {
            styleSelect.addEventListener('change', function() {
                editor.focus();
                document.execCommand('formatBlock', false, this.value);
                this.blur();
                updateCount();
            });
        }

        // Link Handling
        const linkBtn = document.getElementById('btn-note-link');
        const linkModal = document.getElementById('link-modal-notes');
        const linkInput = document.getElementById('link-url-notes');
        const insertLinkBtn = document.getElementById('btn-insert-link-notes');
        const closeLinkBtn = document.getElementById('btn-close-link-notes');

        if (linkBtn && linkModal) {
            linkBtn.addEventListener('click', function() {
                const selection = window.getSelection();
                if (selection.rangeCount > 0) {
                    savedRange = selection.getRangeAt(0);
                    linkInput.value = '';
                    linkModal.classList.add('open');
                    setTimeout(() => linkInput.focus(), 100);
                } else {
                    alert('Por favor selecciona texto para insertar un enlace.');
                }
            });

            closeLinkBtn?.addEventListener('click', () => linkModal.classList.remove('open'));
            
            insertLinkBtn?.addEventListener('click', function() {
                const url = linkInput.value.trim();
                if (!url) {
                    linkModal.classList.remove('open');
                    return;
                }
                editor.focus();
                const sel = window.getSelection();
                sel.removeAllRanges();
                sel.addRange(savedRange);
                document.execCommand('createLink', false, url);
                linkModal.classList.remove('open');
                updateCount();
            });

            // Close modal on backdrop click
            linkModal.addEventListener('click', (e) => {
                if (e.target === linkModal) linkModal.classList.remove('open');
            });
        }

        // Character Count & Input
        editor.addEventListener('input', updateCount);
        editor.addEventListener('keyup', updateCount);
        editor.addEventListener('mouseup', updateCount);

        function updateCount() {
            const text = editor.innerText || '';
            const countEl = document.getElementById('char-count-notes');
            if (countEl) countEl.textContent = text.length + ' caracteres';
        }

        // Save Button logic
        const saveBtn = document.getElementById('btn-save-global-note');
        if (saveBtn) {
            saveBtn.addEventListener('click', function() {
                const content = editor.innerHTML.trim();
                const plainText = editor.innerText.trim();
                
                if (!plainText) {
                    alert('Por favor ingresa contenido para la nota.');
                    return;
                }

                const noteData = { 
                    type: 'note', 
                    note_title: 'Nota', 
                    note_content: content, 
                    day: null 
                };

                if (typeof addElementToDay === 'function') {
                    addElementToDay(noteData);
                } else {
                    console.error('No method found to add note to timeline');
                    return;
                }

                // Clear editor
                editor.innerHTML = '';
                updateCount();
                
                if (typeof showNotification === 'function') {
                    showNotification('Nota agregada', 'La nota global fue agregada correctamente.');
                }
            });
        }
    }

    // Initialize after DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(initializeGeneralNotesEditor, 400);
    });

    function extractItemData(itemElement, dayNumber) {
        // Prefer the data-type attribute (canonical type like 'flight', 'hotel', etc.)
        // Fallback to .item-type text for backwards compatibility
        const dataTypeAttr = itemElement.getAttribute('data-type')?.toLowerCase() || '';
        const itemTypeText = itemElement.querySelector('.item-type')?.textContent?.toLowerCase() || '';
        const itemType = dataTypeAttr || itemTypeText;

        if (!itemType) return null;

        const baseData = {
            type: itemType,
            day: dayNumber
        };

        // Extract data based on item type
        switch (itemType) {
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
                    airline_id: itemElement.getAttribute('data-airline_id') || itemElement.getAttribute('data-airline-id') || '',
                    flight_number: itemElement.getAttribute('data-flight-number') || '',
                    departure_airport: departureAirport,
                    arrival_airport: arrivalAirport,
                    departure_datetime: itemElement.getAttribute('data-departure-datetime') || '',
                    arrival_datetime: itemElement.getAttribute('data-arrival-datetime') || '',
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

            case 'activity':
            case 'actividad':
                return {
                    ...baseData,
                    type: 'activity',
                    activity_title: itemElement.getAttribute('data-activity-title') || itemElement.querySelector('.item-title')?.textContent || '',
                    location: itemElement.getAttribute('data-location') || '',
                    start_datetime: itemElement.getAttribute('data-start-datetime') || '',
                    end_datetime: itemElement.getAttribute('data-end-datetime') || '',
                    description: itemElement.getAttribute('data-description') || ''
                };

            case 'transport':
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

        // Duración: basada en las fechas ingresadas por el usuario (sin auto-calcular por fecha inicio + día).
        const dayDateValues = Array.from(dayContainers)
            .map(card => {
                const dayNumber = parseInt(card.dataset.day) || null;
                if (!dayNumber) return null;
                const input = document.getElementById(`day-${dayNumber}-date`);
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
                const dayDateInput = document.getElementById(`day-${dayNumber}-date`);
                const dayDateValue = dayDateInput && dayDateInput.value ? dayDateInput.value : null;
                const dayDate = dayDateValue ? new Date(dayDateValue + 'T00:00:00') : null;

                const formatDayDate = (date) => {
                    return date.toLocaleDateString('es-ES', {
                        weekday: 'long',
                        day: '2-digit',
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

            // Renumber remaining days
            const remainingDays = document.querySelectorAll('.day-card');
            remainingDays.forEach((card, index) => {
                const newDayNumber = index + 1;
                card.dataset.day = newDayNumber;
                card.querySelector('h3').textContent = `Día ${newDayNumber}`;
                card.querySelector('.day-date-input').dataset.day = newDayNumber;
                card.querySelector('.day-date-input').id = `day-${newDayNumber}-date`;
                card.querySelector('.day-date-display').id = `day-${newDayNumber}-date-display`;
                card.querySelector('.btn-delete-day').dataset.day = newDayNumber;
                card.querySelector('.add-element-btn').dataset.day = newDayNumber;

                // Update elements in this day
                const elements = card.querySelectorAll('.timeline-item');
                elements.forEach(element => {
                    if (element.dataset.day) {
                        element.dataset.day = newDayNumber;
                    }
                });
            });

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
