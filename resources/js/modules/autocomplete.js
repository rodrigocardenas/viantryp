// Autocomplete Module for Viantryp Editor
// Provides autocomplete functionality for airports and airlines

// Airport data - Major airports worldwide
const airportsList = [
    // Europe
    { code: 'MAD', name: 'Madrid Barajas', city: 'Madrid', country: 'Spain' },
    { code: 'BCN', name: 'Barcelona El Prat', city: 'Barcelona', country: 'Spain' },
    { code: 'PMI', name: 'Palma de Mallorca', city: 'Palma', country: 'Spain' },
    { code: 'AGP', name: 'Málaga Costa del Sol', city: 'Málaga', country: 'Spain' },
    { code: 'ALC', name: 'Alicante', city: 'Alicante', country: 'Spain' },
    { code: 'IBZ', name: 'Ibiza', city: 'Ibiza', country: 'Spain' },
    { code: 'TFS', name: 'Tenerife Sur', city: 'Tenerife', country: 'Spain' },
    { code: 'LPA', name: 'Gran Canaria', city: 'Las Palmas', country: 'Spain' },
    { code: 'FUE', name: 'Fuerteventura', city: 'Fuerteventura', country: 'Spain' },
    { code: 'ACE', name: 'Lanzarote', city: 'Lanzarote', country: 'Spain' },

    // France
    { code: 'CDG', name: 'Paris Charles de Gaulle', city: 'Paris', country: 'France' },
    { code: 'ORY', name: 'Paris Orly', city: 'Paris', country: 'France' },
    { code: 'NCE', name: 'Nice Côte d\'Azur', city: 'Nice', country: 'France' },
    { code: 'LYS', name: 'Lyon Saint-Exupéry', city: 'Lyon', country: 'France' },
    { code: 'MRS', name: 'Marseille Provence', city: 'Marseille', country: 'France' },

    // Germany
    { code: 'FRA', name: 'Frankfurt am Main', city: 'Frankfurt', country: 'Germany' },
    { code: 'MUC', name: 'Munich', city: 'Munich', country: 'Germany' },
    { code: 'DUS', name: 'Düsseldorf', city: 'Düsseldorf', country: 'Germany' },
    { code: 'HAM', name: 'Hamburg', city: 'Hamburg', country: 'Germany' },
    { code: 'BER', name: 'Berlin Brandenburg', city: 'Berlin', country: 'Germany' },

    // Italy
    { code: 'FCO', name: 'Rome Fiumicino', city: 'Rome', country: 'Italy' },
    { code: 'MXP', name: 'Milan Malpensa', city: 'Milan', country: 'Italy' },
    { code: 'VCE', name: 'Venice Marco Polo', city: 'Venice', country: 'Italy' },
    { code: 'FLR', name: 'Florence Peretola', city: 'Florence', country: 'Italy' },
    { code: 'NAP', name: 'Naples', city: 'Naples', country: 'Italy' },

    // UK
    { code: 'LHR', name: 'London Heathrow', city: 'London', country: 'United Kingdom' },
    { code: 'LGW', name: 'London Gatwick', city: 'London', country: 'United Kingdom' },
    { code: 'MAN', name: 'Manchester', city: 'Manchester', country: 'United Kingdom' },
    { code: 'BHX', name: 'Birmingham', city: 'Birmingham', country: 'United Kingdom' },
    { code: 'EDI', name: 'Edinburgh', city: 'Edinburgh', country: 'United Kingdom' },

    // Netherlands
    { code: 'AMS', name: 'Amsterdam Schiphol', city: 'Amsterdam', country: 'Netherlands' },
    { code: 'RTM', name: 'Rotterdam The Hague', city: 'Rotterdam', country: 'Netherlands' },

    // Belgium
    { code: 'BRU', name: 'Brussels', city: 'Brussels', country: 'Belgium' },

    // Switzerland
    { code: 'ZRH', name: 'Zurich', city: 'Zurich', country: 'Switzerland' },
    { code: 'GVA', name: 'Geneva', city: 'Geneva', country: 'Switzerland' },

    // Austria
    { code: 'VIE', name: 'Vienna', city: 'Vienna', country: 'Austria' },

    // Portugal
    { code: 'LIS', name: 'Lisbon Humberto Delgado', city: 'Lisbon', country: 'Portugal' },
    { code: 'OPO', name: 'Porto Francisco Sá Carneiro', city: 'Porto', country: 'Portugal' },
    { code: 'FAO', name: 'Faro', city: 'Faro', country: 'Portugal' },

    // Greece
    { code: 'ATH', name: 'Athens International', city: 'Athens', country: 'Greece' },
    { code: 'HER', name: 'Heraklion International', city: 'Heraklion', country: 'Greece' },
    { code: 'CFU', name: 'Corfu International', city: 'Corfu', country: 'Greece' },

    // Turkey
    { code: 'IST', name: 'Istanbul Airport', city: 'Istanbul', country: 'Turkey' },
    { code: 'SAW', name: 'Sabiha Gökçen', city: 'Istanbul', country: 'Turkey' },
    { code: 'ADB', name: 'İzmir Adnan Menderes', city: 'İzmir', country: 'Turkey' },

    // Americas
    { code: 'JFK', name: 'John F. Kennedy', city: 'New York', country: 'United States' },
    { code: 'LAX', name: 'Los Angeles', city: 'Los Angeles', country: 'United States' },
    { code: 'MIA', name: 'Miami International', city: 'Miami', country: 'United States' },
    { code: 'ORD', name: 'O\'Hare', city: 'Chicago', country: 'United States' },
    { code: 'DFW', name: 'Dallas/Fort Worth', city: 'Dallas', country: 'United States' },
    { code: 'ATL', name: 'Hartsfield-Jackson', city: 'Atlanta', country: 'United States' },
    { code: 'DEN', name: 'Denver International', city: 'Denver', country: 'United States' },
    { code: 'SEA', name: 'Seattle-Tacoma', city: 'Seattle', country: 'United States' },
    { code: 'SFO', name: 'San Francisco', city: 'San Francisco', country: 'United States' },
    { code: 'BOS', name: 'Logan', city: 'Boston', country: 'United States' },

    // Canada
    { code: 'YYZ', name: 'Toronto Pearson', city: 'Toronto', country: 'Canada' },
    { code: 'YUL', name: 'Montréal-Pierre Elliott Trudeau', city: 'Montreal', country: 'Canada' },
    { code: 'YVR', name: 'Vancouver', city: 'Vancouver', country: 'Canada' },

    // Mexico
    { code: 'MEX', name: 'Mexico City International', city: 'Mexico City', country: 'Mexico' },
    { code: 'CUN', name: 'Cancún International', city: 'Cancún', country: 'Mexico' },
    { code: 'GDL', name: 'Guadalajara International', city: 'Guadalajara', country: 'Mexico' },
    { code: 'MTY', name: 'Monterrey International', city: 'Monterrey', country: 'Mexico' },

    // Colombia
    { code: 'BOG', name: 'El Dorado', city: 'Bogotá', country: 'Colombia' },
    { code: 'MDE', name: 'José María Córdova', city: 'Medellín', country: 'Colombia' },
    { code: 'CTG', name: 'Rafael Núñez', city: 'Cartagena', country: 'Colombia' },
    { code: 'BAQ', name: 'Ernesto Cortissoz', city: 'Barranquilla', country: 'Colombia' },

    // Argentina
    { code: 'EZE', name: 'Ministro Pistarini', city: 'Buenos Aires', country: 'Argentina' },
    { code: 'AEP', name: 'Aeroparque Jorge Newbery', city: 'Buenos Aires', country: 'Argentina' },
    { code: 'COR', name: 'Ingeniero Ambrosio Taravella', city: 'Córdoba', country: 'Argentina' },

    // Chile
    { code: 'SCL', name: 'Arturo Merino Benítez', city: 'Santiago', country: 'Chile' },
    { code: 'PUQ', name: 'Presidente Carlos Ibáñez', city: 'Punta Arenas', country: 'Chile' },

    // Peru
    { code: 'LIM', name: 'Jorge Chávez International', city: 'Lima', country: 'Peru' },
    { code: 'CUZ', name: 'Alejandro Velasco Astete', city: 'Cusco', country: 'Peru' },

    // Brazil
    { code: 'GRU', name: 'São Paulo-Guarulhos', city: 'São Paulo', country: 'Brazil' },
    { code: 'GIG', name: 'Rio de Janeiro Galeão', city: 'Rio de Janeiro', country: 'Brazil' },
    { code: 'BSB', name: 'Brasília', city: 'Brasília', country: 'Brazil' },

    // Middle East
    { code: 'DXB', name: 'Dubai International', city: 'Dubai', country: 'United Arab Emirates' },
    { code: 'AUH', name: 'Abu Dhabi International', city: 'Abu Dhabi', country: 'United Arab Emirates' },
    { code: 'DOH', name: 'Hamad International', city: 'Doha', country: 'Qatar' },
    { code: 'TLV', name: 'Ben Gurion', city: 'Tel Aviv', country: 'Israel' },

    // Asia
    { code: 'HND', name: 'Tokyo Haneda', city: 'Tokyo', country: 'Japan' },
    { code: 'NRT', name: 'Tokyo Narita', city: 'Tokyo', country: 'Japan' },
    { code: 'ICN', name: 'Incheon International', city: 'Seoul', country: 'South Korea' },
    { code: 'BKK', name: 'Suvarnabhumi', city: 'Bangkok', country: 'Thailand' },
    { code: 'SIN', name: 'Singapore Changi', city: 'Singapore', country: 'Singapore' },
    { code: 'HKG', name: 'Hong Kong International', city: 'Hong Kong', country: 'Hong Kong' },
    { code: 'PVG', name: 'Shanghai Pudong', city: 'Shanghai', country: 'China' },
    { code: 'PEK', name: 'Beijing Capital', city: 'Beijing', country: 'China' },
    { code: 'CAN', name: 'Guangzhou Baiyun', city: 'Guangzhou', country: 'China' },
    { code: 'CTU', name: 'Chengdu Shuangliu', city: 'Chengdu', country: 'China' },
    { code: 'DEL', name: 'Indira Gandhi', city: 'Delhi', country: 'India' },
    { code: 'BOM', name: 'Chhatrapati Shivaji', city: 'Mumbai', country: 'India' }
];

// Airline data - Major airlines worldwide
const airlinesList = [
    // Europe
    'Iberia', 'Air France', 'Lufthansa', 'British Airways', 'KLM Royal Dutch Airlines',
    'Alitalia', 'Swiss International Air Lines', 'Austrian Airlines', 'SAS Scandinavian Airlines',
    'Norwegian Air Shuttle', 'Ryanair', 'EasyJet', 'Vueling', 'Volotea', 'Eurowings',
    'Air Europa', 'TAP Air Portugal', 'Azores Airlines', 'Aegean Airlines', 'Turkish Airlines',
    'Pegasus Airlines', 'SunExpress', 'Corendon Airlines', 'TUI fly', 'Condor Flugdienst',

    // Americas
    'American Airlines', 'Delta Air Lines', 'United Airlines', 'Southwest Airlines',
    'JetBlue Airways', 'Alaska Airlines', 'Spirit Airlines', 'Frontier Airlines',
    'Air Canada', 'WestJet', 'Aeroméxico', 'Interjet', 'VivaAerobus', 'Aeromar',
    'LATAM Airlines', 'Avianca', 'Aerolínea de Antioquia', 'Copa Airlines', 'AeroRepública',
    'Gol Transportes Aéreos', 'Azul Brazilian Airlines', 'Aerolineas Argentinas',
    'LAN Airlines', 'Sky Airline',

    // Middle East & Asia
    'Emirates', 'Etihad Airways', 'Qatar Airways', 'Turkish Airlines', 'Gulf Air',
    'Oman Air', 'Royal Jordanian', 'Middle East Airlines', 'El Al Israel Airlines',
    'Japan Airlines', 'All Nippon Airways', 'Cathay Pacific', 'Singapore Airlines',
    'Malaysia Airlines', 'Thai Airways', 'Korean Air', 'Asiana Airlines', 'China Southern Airlines',
    'China Eastern Airlines', 'Air China', 'Hainan Airlines', 'IndiGo', 'Air India',
    'SpiceJet', 'GoAir',

    // Oceania
    'Qantas', 'Virgin Australia', 'Air New Zealand', 'Jetstar Airways',

    // Africa
    'South African Airways', 'Kenya Airways', 'Ethiopian Airlines', 'EgyptAir',
    'Royal Air Maroc', 'Tunisair', 'Air Algérie'
];

// Autocomplete functionality
function setupAirportAutocomplete(inputId, containerId) {
    const airportInput = document.getElementById(inputId);
    if (!airportInput) return;

    // Create autocomplete container
    let autocompleteContainer = document.getElementById(containerId);
    if (!autocompleteContainer) {
        autocompleteContainer = document.createElement('div');
        autocompleteContainer.id = containerId;
        autocompleteContainer.style.cssText = `
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 0 0 8px 8px;
        `;
        airportInput.parentNode.style.position = 'relative';
        airportInput.parentNode.appendChild(autocompleteContainer);
    }

    airportInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        if (query.length < 2) {
            autocompleteContainer.style.display = 'none';
            return;
        }

        const matches = airportsList.filter(airport =>
            airport.code.toLowerCase().includes(query) ||
            airport.name.toLowerCase().includes(query) ||
            airport.city.toLowerCase().includes(query) ||
            airport.country.toLowerCase().includes(query)
        ).slice(0, 8); // Limit to 8 results

        if (matches.length > 0) {
            autocompleteContainer.innerHTML = matches.map(airport =>
                `<div class="autocomplete-item" onclick="selectAirport('${inputId}', '${airport.code}', '${airport.name}', '${airport.city}', '${airport.country}')">
                    <div style="font-weight: bold; color: #333;">${airport.code} - ${airport.name}</div>
                    <div style="font-size: 0.9em; color: #666;">${airport.city}, ${airport.country}</div>
                </div>`
            ).join('');
            autocompleteContainer.style.display = 'block';
        } else {
            autocompleteContainer.style.display = 'none';
        }
    });

    // Hide autocomplete when clicking outside
    document.addEventListener('click', function(e) {
        if (!airportInput.contains(e.target) && !autocompleteContainer.contains(e.target)) {
            autocompleteContainer.style.display = 'none';
        }
    });

    // Handle keyboard navigation
    airportInput.addEventListener('keydown', function(e) {
        const items = autocompleteContainer.querySelectorAll('.autocomplete-item');
        const currentIndex = Array.from(items).findIndex(item => item.classList.contains('selected'));

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (currentIndex < items.length - 1) {
                items.forEach(item => item.classList.remove('selected'));
                items[currentIndex + 1].classList.add('selected');
            } else if (items.length > 0) {
                items.forEach(item => item.classList.remove('selected'));
                items[0].classList.add('selected');
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (currentIndex > 0) {
                items.forEach(item => item.classList.remove('selected'));
                items[currentIndex - 1].classList.add('selected');
            } else if (items.length > 0) {
                items.forEach(item => item.classList.remove('selected'));
                items[items.length - 1].classList.add('selected');
            }
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (currentIndex >= 0) {
                const item = items[currentIndex];
                const onclick = item.getAttribute('onclick');
                if (onclick) {
                    eval(onclick);
                }
            }
        } else if (e.key === 'Escape') {
            autocompleteContainer.style.display = 'none';
        }
    });
}

function selectAirport(inputId, code, name, city, country) {
    const airportInput = document.getElementById(inputId);
    const autocompleteContainer = document.getElementById(inputId.replace('Input', 'Autocomplete'));

    if (airportInput) {
        airportInput.value = `${code} - ${name}`;
        airportInput.setAttribute('data-airport-code', code);
        airportInput.setAttribute('data-airport-city', city);
        airportInput.setAttribute('data-airport-country', country);
        airportInput.focus();

        // Trigger title update if both airports are selected
        setTimeout(() => {
            const originInput = document.getElementById('departure-airport');
            const destinationInput = document.getElementById('arrival-airport');

            if (originInput && destinationInput &&
                originInput.getAttribute('data-airport-city') &&
                destinationInput.getAttribute('data-airport-city')) {

                // Update the title preview in real-time
                const originCity = originInput.getAttribute('data-airport-city');
                const destinationCity = destinationInput.getAttribute('data-airport-city');
                const titlePreview = `Viaje desde ${originCity} hacia ${destinationCity}`;

                // You could add a preview element here if needed
                console.log('Título sugerido:', titlePreview);
            }
        }, 100);
    }

    if (autocompleteContainer) {
        autocompleteContainer.style.display = 'none';
    }
}

function setupAirlineAutocomplete() {
    const airlineInput = document.getElementById('airline');
    if (!airlineInput) return;

    // Create autocomplete container
    let autocompleteContainer = document.getElementById('airlineAutocomplete');
    if (!autocompleteContainer) {
        autocompleteContainer = document.createElement('div');
        autocompleteContainer.id = 'airlineAutocomplete';
        autocompleteContainer.style.cssText = `
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 0 0 8px 8px;
        `;
        airlineInput.parentNode.style.position = 'relative';
        airlineInput.parentNode.appendChild(autocompleteContainer);
    }

    airlineInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        if (query.length < 2) {
            autocompleteContainer.style.display = 'none';
            return;
        }

        const matches = airlinesList.filter(airline =>
            airline.toLowerCase().includes(query)
        ).slice(0, 10); // Limit to 10 results

        if (matches.length > 0) {
            autocompleteContainer.innerHTML = matches.map(airline =>
                `<div class="autocomplete-item" onclick="selectAirline('${airline}')">${airline}</div>`
            ).join('');
            autocompleteContainer.style.display = 'block';
        } else {
            autocompleteContainer.style.display = 'none';
        }
    });

    // Hide autocomplete when clicking outside
    document.addEventListener('click', function(e) {
        if (!airlineInput.contains(e.target) && !autocompleteContainer.contains(e.target)) {
            autocompleteContainer.style.display = 'none';
        }
    });

    // Handle keyboard navigation
    airlineInput.addEventListener('keydown', function(e) {
        const items = autocompleteContainer.querySelectorAll('.autocomplete-item');
        const currentIndex = Array.from(items).findIndex(item => item.classList.contains('selected'));

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (currentIndex < items.length - 1) {
                items.forEach(item => item.classList.remove('selected'));
                items[currentIndex + 1].classList.add('selected');
            } else if (items.length > 0) {
                items.forEach(item => item.classList.remove('selected'));
                items[0].classList.add('selected');
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (currentIndex > 0) {
                items.forEach(item => item.classList.remove('selected'));
                items[currentIndex - 1].classList.add('selected');
            } else if (items.length > 0) {
                items.forEach(item => item.classList.remove('selected'));
                items[items.length - 1].classList.add('selected');
            }
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (currentIndex >= 0) {
                selectAirline(items[currentIndex].textContent);
            }
        } else if (e.key === 'Escape') {
            autocompleteContainer.style.display = 'none';
        }
    });
}

function selectAirline(airline) {
    const airlineInput = document.getElementById('airline');
    const autocompleteContainer = document.getElementById('airlineAutocomplete');

    if (airlineInput) {
        airlineInput.value = airline;
        airlineInput.focus();
    }

    if (autocompleteContainer) {
        autocompleteContainer.style.display = 'none';
    }
}

// Initialize autocomplete for all airport fields
function initializeAutocomplete() {
    console.log('Initializing autocomplete functionality...');

    // Setup airport autocompletes
    setupAirportAutocomplete('departure-airport', 'departure-airport-autocomplete');
    setupAirportAutocomplete('arrival-airport', 'arrival-airport-autocomplete');

    // Setup airline autocomplete
    setupAirlineAutocomplete();

    console.log('Autocomplete functionality initialized');
}

// Initialize autocomplete for modal forms (called when modal opens)
function initializeModalAutocomplete() {
    console.log('Initializing modal autocomplete...');

    // Small delay to ensure DOM is ready
    setTimeout(() => {
        console.log('Setting up airport autocompletes...');
        setupAirportAutocomplete('departure-airport', 'departure-airport-autocomplete');
        setupAirportAutocomplete('arrival-airport', 'arrival-airport-autocomplete');

        console.log('Setting up airline autocomplete...');
        setupAirlineAutocomplete();

        console.log('Modal autocomplete initialization completed');
    }, 100);
}

// Export functions for use in other modules
window.AutocompleteModule = {
    setupAirportAutocomplete,
    setupAirlineAutocomplete,
    selectAirport,
    selectAirline,
    initializeAutocomplete,
    initializeModalAutocomplete,
    airportsList,
    airlinesList
};
