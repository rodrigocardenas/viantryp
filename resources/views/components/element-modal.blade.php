{{-- Componente: Element Modal --}}
{{-- Ubicación: resources/views/components/element-modal.blade.php --}}
{{-- Propósito: Modal para agregar/editar elementos del viaje --}}
{{-- Props: ninguno --}}
{{-- CSS: resources/css/components/modals.css --}}

<!-- Element Modal -->
<div id="element-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modal-title">Agregar Elemento</h3>
            <button class="modal-close" data-action="close-modal">&times;</button>
        </div>
        <div class="modal-body" id="modal-body">
            <!-- Dynamic content will be inserted here -->
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" data-action="close-modal">Cancelar</button>
            <button class="btn btn-primary" data-action="save-element">Guardar</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Element Modal specific JavaScript functions

    // Global variables for modal
    let currentElementType = null;
    let currentElementData = {};
    let currentDay = null;
    let selectedHotelData = null; // Store complete hotel data

    // Track uploaded documents for each element type
    let uploadedDocuments = {
        flight: [],
        hotel: [],
        transport: []
    };

    function showElementTypeSelection(day) {
        currentDay = day;
        const modal = document.getElementById('element-modal');
        const modalTitle = document.getElementById('modal-title');
        const modalBody = document.getElementById('modal-body');

        modalTitle.textContent = 'Seleccionar Tipo de Elemento';
        modalBody.innerHTML = `
            <div class="element-type-selection">
                <div class="element-type-grid">
                    <button class="element-type-btn" data-action="select-element-type" data-element-type="flight">
                        <div class="element-type-icon flight-icon">
                            <i class="fas fa-plane"></i>
                        </div>
                        <span>Vuelo</span>
                    </button>
                    <button class="element-type-btn" data-action="select-element-type" data-element-type="hotel">
                        <div class="element-type-icon hotel-icon">
                            <i class="fas fa-bed"></i>
                        </div>
                        <span>Hotel</span>
                    </button>
                    <button class="element-type-btn" data-action="select-element-type" data-element-type="activity">
                        <div class="element-type-icon activity-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <span>Actividad</span>
                    </button>
                    <button class="element-type-btn" data-action="select-element-type" data-element-type="transport">
                        <div class="element-type-icon transport-icon">
                            <i class="fas fa-car"></i>
                        </div>
                        <span>Traslado</span>
                    </button>
                    <button class="element-type-btn" data-action="select-element-type" data-element-type="note">
                        <div class="element-type-icon note-icon">
                            <i class="fas fa-sticky-note"></i>
                        </div>
                        <span>Nota</span>
                    </button>
                    <button class="element-type-btn" data-action="select-element-type" data-element-type="summary">
                        <div class="element-type-icon summary-icon">
                            <i class="fas fa-list-check"></i>
                        </div>
                        <span>Resumen</span>
                    </button>
                    <button class="element-type-btn" data-action="select-element-type" data-element-type="total">
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

    function closeModal() {
        document.getElementById('element-modal').style.display = 'none';
        currentElementType = null;
        currentElementData = {};
        currentDay = null;
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
            searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Buscar Hoteles';
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
            { id: 'San Petersburgo Púlkovo (LED)', text: 'San Petersburgo Púlkovo (LED)',

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
