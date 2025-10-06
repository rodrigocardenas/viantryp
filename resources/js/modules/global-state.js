// Global State Module for Viantryp Editor
// Defines all shared state variables used across modules

// Initialize day counter based on existing days in HTML
function initializeDayCounter() {
    // For new trips, start with dayCounter = 1 since day 1 is already in HTML
    // For existing trips, this will be overridden by persistence loading
    window.dayCounter = 1;
}

// Global state variables
window.currentElementType = null;
window.currentElementData = {};
window.currentDay = null;
window.draggedElement = null;
window.itemCounter = 0; // Start from 0 for new trips
initializeDayCounter(); // Initialize based on existing HTML
window.currentEditingItem = null;
window.startDate = null;
window.itemsData = {}; // Empty object for new trips

// Export the state module
window.GlobalStateModule = {
    // Utility functions for state management
    resetState: function() {
        window.currentElementType = null;
        window.currentElementData = {};
        window.currentDay = null;
        window.draggedElement = null;
        window.itemCounter = 0;
        initializeDayCounter(); // Re-initialize based on current HTML
        window.currentEditingItem = null;
        window.startDate = null;
        window.itemsData = {};
    },

    getState: function() {
        return {
            currentElementType: window.currentElementType,
            currentElementData: window.currentElementData,
            currentDay: window.currentDay,
            draggedElement: window.draggedElement,
            itemCounter: window.itemCounter,
            dayCounter: window.dayCounter,
            currentEditingItem: window.currentEditingItem,
            startDate: window.startDate,
            itemsData: window.itemsData
        };
    }
};
