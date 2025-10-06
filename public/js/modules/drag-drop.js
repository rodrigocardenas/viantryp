// Drag and Drop Module for Viantryp Editor
// Handles all drag and drop functionality for the trip editor

// Global drag and drop functions (must be in global scope for inline event handlers)
function handleDragStart(e) {
    draggedElement = e.target;
    e.target.style.opacity = '0.5';
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', e.target.outerHTML);
    e.dataTransfer.setData('text/plain', e.target.dataset.type);
    console.log('Started dragging element:', e.target.dataset.type);
}

function handleDragEnd(e) {
    e.target.style.opacity = '';
    draggedElement = null;
    console.log('Drag ended');
}

function handleDragOver(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
    return false;
}

function handleDragEnter(e) {
    e.preventDefault();
    if (!e.currentTarget.contains(e.relatedTarget)) {
        // Find the day container and add the drag-over class to it
        const dayContainer = e.currentTarget.closest('.day-container');
        if (dayContainer) {
            dayContainer.classList.add('drag-over');
        }
    }
}

function handleDragLeave(e) {
    if (!e.currentTarget.contains(e.relatedTarget)) {
        // Find the day container and remove the drag-over class from it
        const dayContainer = e.currentTarget.closest('.day-container');
        if (dayContainer) {
            dayContainer.classList.remove('drag-over');
        }
    }
}

function handleDrop(e) {
    e.preventDefault();
    e.currentTarget.classList.remove('drag-over');

    const elementType = e.dataTransfer.getData('text/plain');
    console.log('Drop event - elementType:', elementType);
    if (!elementType) {
        console.log('No elementType found in dataTransfer');
        return;
    }

    const dayContainer = e.currentTarget.closest('.day-container');
    console.log('Drop event - dayContainer:', dayContainer);
    if (!dayContainer) {
        console.log('No dayContainer found');
        return;
    }

    const dayNumber = parseInt(dayContainer.dataset.day);
    console.log('Dropped', elementType, 'on day', dayNumber);

    addElementToDay(dayNumber, elementType);

    return false;
}

// Initialize drag and drop functionality
function initializeDragAndDrop() {
    console.log('Initializing drag and drop...');

    // Make draggable elements
    const draggableElements = document.querySelectorAll('.draggable');
    draggableElements.forEach(element => {
        element.addEventListener('dragstart', handleDragStart);
        element.addEventListener('dragend', handleDragEnd);
    });

    // Make day containers droppable
    const dayContainers = document.querySelectorAll('.day-container');
    dayContainers.forEach(container => {
        container.addEventListener('dragover', handleDragOver);
        container.addEventListener('drop', handleDrop);
        container.addEventListener('dragenter', handleDragEnter);
        container.addEventListener('dragleave', handleDragLeave);
    });

    console.log('Drag and drop initialized');
}

// Export functions for use in other modules
window.DragDropModule = {
    handleDragStart,
    handleDragEnd,
    handleDragOver,
    handleDragEnter,
    handleDragLeave,
    handleDrop,
    initializeDragAndDrop
};