// UI Utilities Module for Viantryp Editor
// Handles UI-related functionality like notifications, icons, and utilities

// Show notification to user
function showNotification(title, message, duration = 3000) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.innerHTML = `
        <div class="notification-header">${title}</div>
        <div class="notification-body">${message}</div>
    `;

    // Add to page
    document.body.appendChild(notification);

    // Show notification
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);

    // Hide after duration
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, duration);
}

// Get icon for element type
function getIconForType(elementType) {
    const icons = {
        'flight': 'plane',
        'hotel': 'bed',
        'activity': 'map-marker-alt',
        'transport': 'car',
        'note': 'sticky-note',
        'summary': 'list-check',
        'total': 'dollar-sign'
    };
    return icons[elementType] || 'sticky-note';
}

// Validate required fields
function validateRequiredFields(formData) {
    const errors = [];
    // Add validation logic here based on element type
    return errors;
}

// Clear field error styling
function clearFieldError() {
    // Clear field error styling
    this.classList.remove('error');
}

// Update trip title in real time
function updateTripTitleInRealTime() {
    // Update trip title in real time
    const tripTitle = document.getElementById('tripTitle')?.value?.trim();
    // Update any display elements if needed
}

// Make existing items clickable
function makeExistingItemsClickable() {
    const existingItems = document.querySelectorAll('.timeline-item');
    existingItems.forEach(item => {
        item.addEventListener('click', function() {
            const itemId = this.dataset.id;
            if (itemId && itemsData[itemId]) {
                editItem(itemsData[itemId]);
            }
        });
    });
}

// Export functions for use in other modules
window.UIUtilsModule = {
    showNotification,
    getIconForType,
    validateRequiredFields,
    clearFieldError,
    updateTripTitleInRealTime,
    makeExistingItemsClickable
};