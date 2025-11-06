// Lógica específica para el modo de edición del editor de viajes

// Configurar estado inicial del editor
document.addEventListener('DOMContentLoaded', function () {
    if (window.editorMode === 'edit') {
        // Configurar event listeners para elementos existentes
        setupExistingElementListeners();

        // Configurar drag and drop
        initializeDragAndDrop();

        // Configurar auto-guardado
        initializeAutoSave();
    }
});

// Configurar event listeners para elementos ya renderizados por Blade
function setupExistingElementListeners() {
    // Los botones ahora usan data-action attributes, así que el sistema principal los maneja
    // No necesitamos configurar listeners adicionales aquí
    console.log('Existing element listeners configured (using data-action system)');
}

// Extraer datos del elemento para edición
function extractElementData(element) {
    // Implementar lógica para extraer datos del elemento DOM
    // Esto debería coincidir con la lógica en TimelineManager.extractElementData
    return {
        id: element.dataset.elementId,
        type: element.dataset.type,
        // ... otros campos según el tipo de elemento
    };
}

function initializeDragAndDrop() {
    // Configurar drag and drop para elementos nuevos
    const dropZone = document.getElementById('drop-zone');
    if (dropZone) {
        dropZone.addEventListener('dragover', handleDragOver);
        dropZone.addEventListener('drop', handleDrop);
    }
}

function initializeAutoSave() {
    // Configurar auto-guardado cada 30 segundos
    setInterval(() => {
        if (hasUnsavedChanges()) {
            autoSave();
        }
    }, 30000);
}

function hasUnsavedChanges() {
    // Verificar si hay cambios sin guardar
    return true; // Implementar lógica real
}

function autoSave() {
    // Implementar auto-guardado
    console.log('Auto-saving...');
}

// Funciones globales para ser llamadas desde HTML
window.editItem = function(button) {
    const element = button.closest('.timeline-item');
    if (element) {
        // Extraer datos del elemento
        const elementData = extractElementData(element);
        // Emitir evento de edición
        const event = new CustomEvent('editElement', {
            detail: { element, elementData }
        });
        document.dispatchEvent(event);
    }
};

window.deleteItem = function(button) {
    const element = button.closest('.timeline-item');
    if (element && confirm('¿Estás seguro de que quieres eliminar este elemento?')) {
        element.remove();
        // Emitir evento de eliminación
        const event = new CustomEvent('elementDeleted', {
            detail: { elementType: element.dataset.type }
        });
        document.dispatchEvent(event);
    }
};

// Funciones de drag and drop
function handleDragOver(e) {
    e.preventDefault();
    e.currentTarget.classList.add('drag-over');
}

function handleDrop(e) {
    e.preventDefault();
    e.currentTarget.classList.remove('drag-over');

    const elementType = e.dataTransfer.getData('text/plain');
    if (elementType) {
        createNewElement(elementType);
    }
}

function createNewElement(type) {
    // Crear nuevo elemento
    console.log('Creating new element of type:', type);
}
