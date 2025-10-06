// Persistence Module for Viantryp Editor
// Handles saving and loading trip data

// Auto-save functionality
function autoSave() {
    console.log('Auto-saving trip...');

    const tripTitle = document.getElementById('tripTitle')?.value?.trim() || 'Viaje sin título';
    const startDateValue = document.getElementById('startDate')?.value;

    // Prepare trip data
    const tripData = {
        title: tripTitle,
        startDate: startDateValue,
        itemsData: itemsData,
        updatedAt: new Date().toISOString()
    };

    try {
        // Get existing trips
        const existingTrips = JSON.parse(localStorage.getItem('viantryp_trips') || '[]');

        // Find current trip by URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const tripId = urlParams.get('trip');

        if (tripId) {
            // Update existing trip
            const tripIndex = existingTrips.findIndex(t => t.id == tripId);
            if (tripIndex !== -1) {
                existingTrips[tripIndex] = { ...existingTrips[tripIndex], ...tripData };
            } else {
                // Create new trip entry
                tripData.id = parseInt(tripId);
                tripData.createdAt = new Date().toISOString();
                existingTrips.push(tripData);
            }
        } else {
            // Create new trip
            tripData.id = Date.now();
            tripData.createdAt = new Date().toISOString();
            existingTrips.push(tripData);

            // Update URL
            const newUrl = `${window.location.pathname}?trip=${tripData.id}`;
            window.history.replaceState({}, '', newUrl);
        }

        // Save to localStorage
        localStorage.setItem('viantryp_trips', JSON.stringify(existingTrips));

        console.log('Trip auto-saved successfully');
        updateSaveStatus();

    } catch (error) {
        console.error('Error auto-saving trip:', error);
        showNotification('Error', 'Hubo un problema al guardar el viaje automáticamente.');
    }
}

// Manual save functionality
function manualSave() {
    console.log('Manual saving trip...');

    const tripTitle = document.getElementById('tripTitle')?.value?.trim();
    if (!tripTitle) {
        showNotification('Error', 'Por favor ingresa un nombre para el viaje.');
        return;
    }

    const startDateValue = document.getElementById('startDate')?.value;

    // Prepare trip data
    const tripData = {
        title: tripTitle,
        startDate: startDateValue,
        itemsData: itemsData,
        updatedAt: new Date().toISOString()
    };

    try {
        // Get existing trips
        const existingTrips = JSON.parse(localStorage.getItem('viantryp_trips') || '[]');

        // Find current trip by URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const tripId = urlParams.get('trip');

        if (tripId) {
            // Update existing trip
            const tripIndex = existingTrips.findIndex(t => t.id == tripId);
            if (tripIndex !== -1) {
                existingTrips[tripIndex] = { ...existingTrips[tripIndex], ...tripData };
            } else {
                // Create new trip entry
                tripData.id = parseInt(tripId);
                tripData.createdAt = new Date().toISOString();
                existingTrips.push(tripData);
            }
        } else {
            // Create new trip
            tripData.id = Date.now();
            tripData.createdAt = new Date().toISOString();
            existingTrips.push(tripData);

            // Update URL
            const newUrl = `${window.location.pathname}?trip=${tripData.id}`;
            window.history.replaceState({}, '', newUrl);
        }

        // Save to localStorage
        localStorage.setItem('viantryp_trips', JSON.stringify(existingTrips));

        console.log('Trip saved successfully');
        showNotification('Viaje Guardado', 'Tu viaje ha sido guardado exitosamente.');
        updateSaveStatus();

    } catch (error) {
        console.error('Error saving trip:', error);
        showNotification('Error', 'Hubo un problema al guardar el viaje.');
    }
}

// Check if there are unsaved changes
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

// Load saved trip
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

// Rebuild UI from data
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
            const dayContainer = document.querySelector(`[data-day="${day}"] .day-content`);
            if (dayContainer) {
                dayContainer.appendChild(elementDiv);
            }
        });
    }
}

// Clean up corrupted data
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

// Export functions for use in other modules
window.PersistenceModule = {
    autoSave,
    manualSave,
    hasUnsavedChanges,
    updateSaveStatus,
    loadSavedTrip,
    rebuildUIFromData,
    cleanupCorruptedData
};
