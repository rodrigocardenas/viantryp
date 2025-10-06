// Editor Main Module - Combines all editor functionality
// This file imports all modules and makes them available globally

// Import all modules
import './modules/autocomplete.js';
import './modules/drag-drop.js';
import './modules/date-utils.js';
import './modules/ui-utils.js';
import './modules/persistence.js';
import './modules/day-manager.js';
import './modules/trip-manager.js';
import './modules/element-manager.js';

// Make modules available globally for backward compatibility
document.addEventListener('DOMContentLoaded', function() {
    console.log('Viantryp Editor modules loaded successfully');

    // Initialize autocomplete if available
    if (window.AutocompleteModule) {
        console.log('Autocomplete module available');
    }

    // Initialize other modules if available
    if (window.ElementManagerModule) {
        console.log('Element Manager module available');
    }
});

// Export for potential use
export { };
