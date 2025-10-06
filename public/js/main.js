// Main Module for Viantryp Editor
// Coordinates all modules and handles initialization

// Global state variables
let currentElementType = null;
let currentElementData = {};
let currentDay = null;
let draggedElement = null;
let itemCounter = 0; // Start from 0 for new trips
let dayCounter = 1; // Start from 1 for new trips
let currentEditingItem = null;
let startDate = null;
let itemsData = {}; // Empty object for new trips

// Load all modules
document.addEventListener('DOMContentLoaded', function() {
    console.log('Loading Viantryp Editor modules...');

    // Load module scripts dynamically
    const modules = [
        'modules/drag-drop.js',
        'modules/date-utils.js',
        'modules/ui-utils.js',
        'modules/persistence.js',
        'modules/day-manager.js',
        'modules/trip-manager.js',
        'modules/element-manager.js',
        'modules/autocomplete.js'
    ];

    let loadedModules = 0;

    modules.forEach(module => {
        const script = document.createElement('script');
        script.src = module;
        script.onload = function() {
            loadedModules++;
            console.log(`Loaded module: ${module}`);

            if (loadedModules === modules.length) {
                // All modules loaded, initialize the editor
                initializeEditor();
            }
        };
        script.onerror = function() {
            console.error(`Failed to load module: ${module}`);
        };
        document.head.appendChild(script);
    });
});

// Initialize the editor after all modules are loaded
function initializeEditor() {
    console.log('Initializing Viantryp Editor...');

    // Clean up corrupted data first
    if (window.PersistenceModule) {
        window.PersistenceModule.cleanupCorruptedData();
    }

    // Initialize drag and drop
    if (window.DragDropModule) {
        window.DragDropModule.initializeDragAndDrop();
    }

    // Set default start date
    if (window.DateUtilsModule) {
        window.DateUtilsModule.setDefaultStartDate();
    }

    // Make existing items clickable
    if (window.UIUtilsModule) {
        window.UIUtilsModule.makeExistingItemsClickable();
    }

    // Initialize autocomplete functionality
    if (window.AutocompleteModule) {
        window.AutocompleteModule.initializeAutocomplete();
    }

    // Add event listener for date input to update automatically
    const startDateInput = document.getElementById('startDate');
    if (startDateInput) {
        startDateInput.addEventListener('change', function() {
            if (window.DateUtilsModule) {
                window.DateUtilsModule.updateItineraryDatesSilently();
            }
            if (window.PersistenceModule) {
                window.PersistenceModule.autoSave();
            }
        });
    }

    // Check for trip ID in URL and load if exists
    const urlParams = new URLSearchParams(window.location.search);
    const tripId = urlParams.get('trip');

    if (tripId) {
        console.log('Loading trip from URL:', tripId);
        if (window.PersistenceModule) {
            window.PersistenceModule.loadSavedTrip(tripId);
        }
    } else {
        console.log('No trip ID in URL, creating new trip');
        if (window.TripManagerModule) {
            window.TripManagerModule.createNewTrip();
        }
    }

    console.log('Viantryp Editor initialized successfully');
}

// Global functions that need to be accessible from HTML
window.addNewDay = function() {
    if (window.DayManagerModule) {
        window.DayManagerModule.addNewDay();
    }
};

window.deleteDay = function(button) {
    if (window.DayManagerModule) {
        window.DayManagerModule.deleteDay(button);
    }
};

window.manualSave = function() {
    if (window.PersistenceModule) {
        window.PersistenceModule.manualSave();
    }
};

window.createTripWithName = function() {
    if (window.TripManagerModule) {
        window.TripManagerModule.createTripWithName();
    }
};

window.saveElement = function() {
    if (window.ElementManagerModule) {
        window.ElementManagerModule.saveElement();
    }
};

window.closeModal = function() {
    if (window.ElementManagerModule) {
        window.ElementManagerModule.closeModal();
    }
};

window.toggleItem = function(button) {
    if (window.ElementManagerModule) {
        window.ElementManagerModule.toggleItem(button);
    }
};

window.editItem = function(item) {
    if (window.ElementManagerModule) {
        window.ElementManagerModule.editItem(item);
    }
};

// Make sure these functions are available globally
window.handleDragStart = function(e) {
    if (window.DragDropModule) {
        window.DragDropModule.handleDragStart(e);
    }
};

window.handleDragEnd = function(e) {
    if (window.DragDropModule) {
        window.DragDropModule.handleDragEnd(e);
    }
};

window.handleDragOver = function(e) {
    if (window.DragDropModule) {
        window.DragDropModule.handleDragOver(e);
    }
};

window.handleDragEnter = function(e) {
    if (window.DragDropModule) {
        window.DragDropModule.handleDragEnter(e);
    }
};

window.handleDragLeave = function(e) {
    if (window.DragDropModule) {
        window.DragDropModule.handleDragLeave(e);
    }
};

window.handleDrop = function(e) {
    if (window.DragDropModule) {
        window.DragDropModule.handleDrop(e);
    }
};

window.addElementToDay = function(dayNumber, elementType) {
    if (window.ElementManagerModule) {
        window.ElementManagerModule.addElementToDay(dayNumber, elementType);
    }
};

window.showElementModal = function() {
    if (window.ElementManagerModule) {
        window.ElementManagerModule.showElementModal();
    }
};

window.getTypeLabel = function(type) {
    if (window.ElementManagerModule) {
        return window.ElementManagerModule.getTypeLabel(type);
    }
    return 'Elemento';
};

window.getElementForm = function(type) {
    if (window.ElementManagerModule) {
        return window.ElementManagerModule.getElementForm(type);
    }
    return '<p>Formulario no disponible</p>';
};

window.collectFormData = function() {
    if (window.ElementManagerModule) {
        return window.ElementManagerModule.collectFormData();
    }
    return {};
};

window.showNotification = function(title, message, duration) {
    if (window.UIUtilsModule) {
        window.UIUtilsModule.showNotification(title, message, duration);
    }
};

window.getIconForType = function(elementType) {
    if (window.UIUtilsModule) {
        return window.UIUtilsModule.getIconForType(elementType);
    }
    return 'sticky-note';
};

window.validateRequiredFields = function(formData) {
    if (window.UIUtilsModule) {
        return window.UIUtilsModule.validateRequiredFields(formData);
    }
    return [];
};

window.clearFieldError = function() {
    if (window.UIUtilsModule) {
        window.UIUtilsModule.clearFieldError.call(this);
    }
};

window.updateTripTitleInRealTime = function() {
    if (window.UIUtilsModule) {
        window.UIUtilsModule.updateTripTitleInRealTime();
    }
};

window.autoSave = function() {
    if (window.PersistenceModule) {
        window.PersistenceModule.autoSave();
    }
};

window.hasUnsavedChanges = function() {
    if (window.PersistenceModule) {
        return window.PersistenceModule.hasUnsavedChanges();
    }
    return false;
};

window.updateSaveStatus = function() {
    if (window.PersistenceModule) {
        window.PersistenceModule.updateSaveStatus();
    }
};

window.loadSavedTrip = function(tripId) {
    if (window.PersistenceModule) {
        window.PersistenceModule.loadSavedTrip(tripId);
    }
};

window.rebuildUIFromData = function() {
    if (window.PersistenceModule) {
        window.PersistenceModule.rebuildUIFromData();
    }
};

window.formatDate = function(date) {
    if (window.DateUtilsModule) {
        return window.DateUtilsModule.formatDate(date);
    }
    return 'Fecha inválida';
};

window.updateItineraryDates = function() {
    if (window.DateUtilsModule) {
        window.DateUtilsModule.updateItineraryDates();
    }
};

window.updateItineraryDatesSilently = function() {
    if (window.DateUtilsModule) {
        window.DateUtilsModule.updateItineraryDatesSilently();
    }
};

window.setDefaultStartDate = function() {
    if (window.DateUtilsModule) {
        window.DateUtilsModule.setDefaultStartDate();
    }
};

window.makeExistingItemsClickable = function() {
    if (window.UIUtilsModule) {
        window.UIUtilsModule.makeExistingItemsClickable();
    }
};

window.showTripNameModal = function() {
    if (window.TripManagerModule) {
        window.TripManagerModule.showTripNameModal();
    }
};

window.createNewTrip = function() {
    if (window.TripManagerModule) {
        window.TripManagerModule.createNewTrip();
    }
};
