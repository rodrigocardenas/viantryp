// Lógica específica para el modo de edición del editor de viajes

// Configurar estado inicial del editor
document.addEventListener('DOMContentLoaded', function () {
    if (window.editorMode === 'edit') {
        // Cargar elementos existentes en el timeline
        loadExistingElements();

        // Configurar drag and drop
        initializeDragAndDrop();

        // Configurar auto-guardado
        initializeAutoSave();
    }
});

function loadExistingElements() {
    // Cargar elementos del viaje existente
    const elements = window.existingTripData.elements || [];
    elements.forEach(element => {
        renderElement(element);
    });
}

function renderElement(element) {
    // Lógica para renderizar elementos existentes
    const timeline = document.getElementById('timeline');
    if (timeline) {
        // Crear y añadir elemento al timeline
        const elementDiv = createElementDiv(element);
        timeline.appendChild(elementDiv);
    }
}

function createElementDiv(element) {
    // Crear div para elemento del timeline
    const div = document.createElement('div');
    div.className = `timeline-element ${element.type}`;
    div.setAttribute('data-element-id', element.id);
    div.innerHTML = `
        <div class="element-header">
            <i class="fas fa-${getElementIcon(element.type)}"></i>
            <span class="element-title">${element.title || 'Sin título'}</span>
            <div class="element-actions">
                <button class="btn-edit" onclick="editElement(${element.id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn-delete" onclick="deleteElement(${element.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        <div class="element-content">
            ${renderElementContent(element)}
        </div>
    `;
    return div;
}

function getElementIcon(type) {
    const icons = {
        flight: 'plane',
        hotel: 'hotel',
        activity: 'map-marker-alt',
        transport: 'bus',
        note: 'sticky-note',
        summary: 'list',
        total: 'calculator'
    };
    return icons[type] || 'circle';
}

function renderElementContent(element) {
    // Renderizar contenido específico del elemento
    switch (element.type) {
        case 'flight':
            return `
                <div class="flight-info">
                    <div class="flight-route">
                        <span class="origin">${element.origin || ''}</span>
                        <i class="fas fa-plane"></i>
                        <span class="destination">${element.destination || ''}</span>
                    </div>
                    <div class="flight-details">
                        <span class="date">${element.date || ''}</span>
                        <span class="time">${element.time || ''}</span>
                    </div>
                </div>
            `;
        case 'hotel':
            return `
                <div class="hotel-info">
                    <div class="hotel-name">${element.name || ''}</div>
                    <div class="hotel-details">
                        <span class="checkin">${element.checkin || ''}</span> -
                        <span class="checkout">${element.checkout || ''}</span>
                    </div>
                </div>
            `;
        default:
            return `<div class="generic-content">${element.description || ''}</div>`;
    }
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

// Funciones para editar/eliminar elementos
function editElement(id) {
    // Abrir modal de edición
    console.log('Editing element:', id);
}

function deleteElement(id) {
    // Eliminar elemento
    if (confirm('¿Estás seguro de que quieres eliminar este elemento?')) {
        console.log('Deleting element:', id);
    }
}

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
