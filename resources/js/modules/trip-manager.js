// Trip Manager Module for Viantryp Editor
// Handles trip creation and management

// Create a new trip
function createNewTrip() {
    // Show the HTML modal instead of creating a dynamic one
    const modal = document.getElementById('new-trip-modal');
    if (modal) {
        modal.classList.add('show');
        // Focus on the input field
        const input = document.getElementById('new-trip-name');
        if (input) {
            input.focus();
        }
    }
}

// Export functions for use in other modules
window.TripManagerModule = {
    createNewTrip
};
