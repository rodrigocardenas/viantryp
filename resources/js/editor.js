
// Editor JavaScript for Viantryp Trip Editor
import { TimelineManager } from './modules/timeline.js';
import { ModalManager } from './modules/modal.js';
import { SummaryManager } from './modules/summary.js';
import FileManager from './modules/file-manager.js';
import ElementManager from './modules/element-manager.js';
import DayManager from './modules/day-manager.js';
import ExportManager from './modules/export-manager.js';
import Utils from './modules/utils.js';

// Global managers
let timelineManager;
let modalManager;
let summaryManager;
let fileManager;
let elementManager;
let dayManager;
let exportManager;
let utils;

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
    fileManager = new FileManager();
    elementManager = new ElementManager(modalManager, timelineManager, summaryManager, fileManager);
    dayManager = new DayManager(summaryManager);
    exportManager = new ExportManager();
    utils = new Utils(modalManager, summaryManager);

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

    // Listen for addElementToDay events from ModalManager
    document.addEventListener('addElementToDay', (e) => {
        console.log('Editor received addElementToDay event:', e.detail);
        timelineManager.addElementToDay(e.detail.elementData);
    });

    // Listen for updateAllSummaries events
    document.addEventListener('updateAllSummaries', () => {
        summaryManager.updateAllSummaries();
    });

    // Add event delegation for data-action attributes
    document.addEventListener('click', function(e) {
        const action = e.target.closest('[data-action]');
        if (!action) return;

        const actionType = action.dataset.action;

        switch (actionType) {
            case 'add-element':
                e.preventDefault();
                const day = action.dataset.day;
                timelineManager.showAddElementModal(parseInt(day));
                break;
            case 'add-day':
                e.preventDefault();
                dayManager.addNewDay();
                break;
            case 'edit-element':
                e.preventDefault();
                timelineManager.editElement(action);
                break;
            case 'delete-element':
                e.preventDefault();
                utils.deleteElement(action);
                break;
            case 'update-summaries':
                e.preventDefault();
                summaryManager.updateAllSummaries();
                break;
            case 'save-trip':
                e.preventDefault();
                console.log('Save-trip action clicked — calling exportManager.saveTrip');
                if (exportManager && typeof exportManager.saveTrip === 'function') {
                    exportManager.saveTrip();
                } else {
                    console.error('exportManager not initialized or saveTrip not available');
                }
                break;
            case 'preview-trip':
                e.preventDefault();
                exportManager.previewTrip();
                break;
            case 'download-pdf':
                e.preventDefault();
                exportManager.downloadPDF();
                break;
            case 'back':
                e.preventDefault();
                showUnsavedChangesModal();
                break;
            case 'select-hotel':
                e.preventDefault();
                // Need to implement selectHotel function or move it here
                console.log('Select hotel clicked');
                break;
        }
    });
}

// Global functions that need to be accessible from HTML
window.addNewDay = function() {
    dayManager.addNewDay();
};

window.updateItineraryDates = function() {
    dayManager.updateItineraryDates();
};

window.allowDrop = function(ev) {
    timelineManager.allowDrop(ev);
};

window.drag = function(ev) {
    timelineManager.drag(ev);
};

window.drop = function(ev) {
    timelineManager.drop(ev);
};

window.saveTrip = function() {
    exportManager.saveTrip();
};

window.previewTrip = function() {
    exportManager.previewTrip();
};

window.downloadPDF = function() {
    exportManager.downloadPDF();
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
        const startDateInput = document.getElementById('start-date');
        if (startDateInput) {
            startDateInput.value = tripData.start_date;
            // Trigger date update to populate day dates
            if (typeof updateItineraryDates === 'function') {
                updateItineraryDates();
            }
        }
    }

    // Load trip items - only if NOT in edit mode (Blade components handle rendering in edit mode)
    if (tripData.items_data && window.editorMode !== 'edit') {
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
