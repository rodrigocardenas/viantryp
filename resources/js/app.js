import './bootstrap';

// Import all editor modules
import './editor.js';
import './main.js';

// Import modules for autocomplete functionality
import './modules/autocomplete.js';
import './modules/drag-drop.js';
import './modules/date-utils.js';
import './modules/ui-utils.js';
import './modules/persistence.js';
import './modules/day-manager.js';
import './modules/trip-manager.js';
import './modules/element-manager.js';

// Initialize modules when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Viantryp Editor modules loaded successfully');

    // Make modules available globally for backward compatibility
    if (window.AutocompleteModule) {
        console.log('Autocomplete module initialized');
    }
});
