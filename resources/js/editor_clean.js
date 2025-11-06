
// Editor JavaScript for Viantryp Trip Editor
import { TimelineManager } from './modules/timeline.js';
import { ModalManager } from './modules/modal.js';
import { SummaryManager } from './modules/summary.js';

// Global managers
let timelineManager;
let modalManager;
let summaryManager;

// State management
let currentElementType = null;
let currentElementData = {};
let currentDay = null;

// Initialize the editor when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Editor JavaScript initializing...');

    // Initialize managers
    timelineManager = new TimelineManager();
    modalManager = new ModalManager();
    summaryManager = new SummaryManager();

    timelineManager.init();
    modalManager.init();
    summaryManager.init();

    // Setup global event listeners
    setupGlobalEventListeners();

    // Load existing trip data if available
    if (window.existingTripData) {
        console.log('Loading existing trip data:', window.existingTripData);
        loadExistingTripData(window.existingTripData);
    }

    console.log('Editor JavaScript initialized successfully');
});

function setupGlobalEventListeners() {
    // Add event listeners for draggable elements
    const draggableElements = document.querySelectorAll('.element-category');
    draggableElements.forEach(element => {
        // Add click handlers for summary and total elements
        if (element.dataset.type === 'summary') {
            element.addEventListener('click', function(e) {
                e.preventDefault();
                summaryManager.handleSummaryClick();
            });
        }
        if (element.dataset.type === 'total') {
            element.addEventListener('click', function(e) {
                e.preventDefault();
                summaryManager.handleTotalClick();
            });
        }
    });
}

// Global functions that need to be accessible from HTML
window.addNewDay = function() {
    timelineManager.addNewDay();
};

window.saveTrip = function() {
    // Implementation will be moved here
    console.log('Saving trip');
};

window.previewTrip = function() {
    // Implementation will be moved here
    console.log('Previewing trip');
};

window.downloadPDF = function() {
    // Implementation will be moved here
    console.log('Downloading PDF');
};

window.showUnsavedChangesModal = function() {
    // Implementation will be moved here
    console.log('Showing unsaved changes modal');
};

// Legacy functions for backward compatibility
function loadExistingTripData(tripData) {
    console.log('Loading trip data:', tripData);

    // Set trip title
    if (tripData.title) {
        document.getElementById('trip-title').value = tripData.title;
    }

    // Set start date
    if (tripData.start_date) {
        document.getElementById('start-date').value = tripData.start_date;
    }

    // Load trip items
    if (tripData.items_data) {
        // Group items by day
        const itemsByDay = {};
        tripData.items_data.forEach(item => {
            const day = item.day || 1;
            if (!itemsByDay[day]) {
                itemsByDay[day] = [];
            }
            itemsByDay[day].push(item);
        });

        // Create days and add items
        Object.keys(itemsByDay).forEach(dayNum => {
            const dayNumber = parseInt(dayNum);

            // Ensure the day exists
            while (document.querySelectorAll('.day-card').length < dayNumber) {
                timelineManager.addNewDay();
            }

            // Add items to the day
            const dayCard = document.querySelectorAll('.day-card')[dayNumber - 1];
            if (dayCard) {
                itemsByDay[dayNum].forEach(item => {
                    // Create element data for the item
                    const elementData = {
                        type: item.type,
                        day: dayNumber,
                        ...item
                    };

                    // Add the element to the day
                    timelineManager.addElementToDay(elementData);
                });
            }
        });
    }

    // Update summaries and totals
    summaryManager.updateAllSummaries();
}
