// Date Utilities Module for Viantryp Editor
// Handles all date formatting and manipulation functionality

// Update itinerary dates based on start date
function updateItineraryDates() {
    const startDateInput = document.getElementById('startDate');
    if (!startDateInput || !startDateInput.value) {
        console.log('No start date set');
        return;
    }

    startDate = new Date(startDateInput.value + 'T00:00:00');
    console.log('Updating dates from:', startDate);

    // Update all day dates
    const dayContainers = document.querySelectorAll('.day-container');
    dayContainers.forEach((container, index) => {
        const dayNumber = index + 1;
        const dayDateElement = container.querySelector('.day-date');

        if (dayDateElement) {
            const currentDate = new Date(startDate);
            currentDate.setDate(startDate.getDate() + (dayNumber - 1));
            dayDateElement.textContent = formatDate(currentDate);
            dayDateElement.setAttribute('data-date', currentDate.toISOString().split('T')[0]);
        }
    });

    showNotification('Fechas Actualizadas', 'Las fechas del itinerario han sido actualizadas.');
}

// Update itinerary dates silently (without notification)
function updateItineraryDatesSilently() {
    const startDateInput = document.getElementById('startDate');
    if (!startDateInput || !startDateInput.value) {
        return;
    }

    startDate = new Date(startDateInput.value + 'T00:00:00');

    // Update all day dates
    const dayContainers = document.querySelectorAll('.day-container');
    dayContainers.forEach((container, index) => {
        const dayNumber = index + 1;
        const dayDateElement = container.querySelector('.day-date');

        if (dayDateElement) {
            const currentDate = new Date(startDate);
            currentDate.setDate(startDate.getDate() + (dayNumber - 1));
            dayDateElement.textContent = formatDate(currentDate);
            dayDateElement.setAttribute('data-date', currentDate.toISOString().split('T')[0]);
        }
    });
}

// Format date for display
function formatDate(date) {
    if (!date || !(date instanceof Date) || isNaN(date)) {
        return 'Fecha inválida';
    }

    const options = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    };

    return date.toLocaleDateString('es-ES', options);
}

// Set default start date
function setDefaultStartDate() {
    const startDateInput = document.getElementById('startDate');
    if (!startDateInput) {
        console.log('Start date input not found');
        return;
    }

    // Set default to today if not already set
    if (!startDateInput.value) {
        const today = new Date();
        const formattedDate = today.toISOString().split('T')[0];
        startDateInput.value = formattedDate;
        startDate = new Date(formattedDate + 'T00:00:00');
        console.log('Set default start date:', formattedDate);
    } else {
        startDate = new Date(startDateInput.value + 'T00:00:00');
        console.log('Using existing start date:', startDateInput.value);
    }
}

// Export functions for use in other modules
window.DateUtilsModule = {
    updateItineraryDates,
    updateItineraryDatesSilently,
    formatDate,
    setDefaultStartDate
};