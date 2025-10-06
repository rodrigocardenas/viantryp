// Trip Manager Module for Viantryp Editor
// Handles trip creation and management

// Show modal for entering trip name
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

// Create a new trip
function createNewTrip() {
    // Create a new trip
    const tripId = Date.now().toString();
    const newUrl = `${window.location.pathname}?trip=${tripId}&mode=new`;
    window.history.replaceState({}, '', newUrl);
    showTripNameModal();
}

// Create trip with name
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

// Export functions for use in other modules
window.TripManagerModule = {
    showTripNameModal,
    createNewTrip,
    createTripWithName
};