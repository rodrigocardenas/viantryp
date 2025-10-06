// Day Manager Module for     const dayContainer = document.createElement('div');
    dayContainer.className = 'day-card';
    dayContainer.setAttribute('data-day', window.dayCounter);
    // Viantryp Editor
// Handles day creation, deletion, and management

// Add new day functionality
function addNewDay() {
    console.log('Adding new day...');

    dayCounter++;
    let container = document.getElementById('timeline');
    if (!container) {
        console.error('Timeline element not found, trying alternative containers');
        // Try alternative containers
        const alternatives = ['#days-container', '#timeline-container', '.timeline', '.days-container'];
        for (const alt of alternatives) {
            container = document.querySelector(alt);
            if (container) {
                console.log('Found alternative container:', alt);
                break;
            }
        }
        if (!container) {
            console.error('No suitable container found for days');
            return;
        }
    }

    const dayContainer = document.createElement('div');
    dayContainer.className = 'day-card';
    dayContainer.setAttribute('data-day', window.dayCounter);

    // Calculate the date for this day
    let dayDate = 'Selecciona fecha de inicio';
    if (startDate) {
        const currentDate = new Date(startDate);
        currentDate.setDate(startDate.getDate() + (dayCounter - 1));
        dayDate = formatDate(currentDate);
    }

    dayContainer.innerHTML = `
        <div class="day-actions">
            <button class="day-btn" onclick="deleteDay(this)">
                <i class="fas fa-trash"></i> Eliminar
            </button>
        </div>
        <div class="day-header">
            <div class="day-title">Día ${dayCounter}</div>
            <div class="day-date" data-date="">${dayDate}</div>
        </div>
        <div class="day-content" ondrop="handleDrop(event)" ondragover="handleDragOver(event)" ondragenter="handleDragEnter(event)" ondragleave="handleDragLeave(event)">
            <div style="text-align: center; padding: 2rem; color: #666; font-style: italic;">
                <i class="fas fa-plus-circle" style="font-size: 2rem; margin-bottom: 1rem; display: block; color: var(--primary-blue);"></i>
                Arrastra elementos aquí para personalizar este día
            </div>
        </div>
    `;

    container.appendChild(dayContainer);

    // Make the new day container droppable
    dayContainer.addEventListener('dragover', handleDragOver);
    dayContainer.addEventListener('drop', handleDrop);
    dayContainer.addEventListener('dragenter', handleDragEnter);
    dayContainer.addEventListener('dragleave', handleDragLeave);

    showNotification('Día Agregado', `Día ${dayCounter} agregado al itinerario.`);

    // Auto-save after adding day
    setTimeout(() => {
        autoSave();
    }, 500);
}

// Delete day functionality
function deleteDay(button) {
    const dayContainer = button.closest('.day-card');
    const dayNumber = parseInt(dayContainer.dataset.day);

    if (confirm(`¿Estás seguro de que quieres eliminar el Día ${dayNumber}? Se perderán todos los elementos de este día.`)) {
        // Remove all items from this day from itemsData
        Object.keys(itemsData).forEach(key => {
            if (itemsData[key].day === dayNumber) {
                delete itemsData[key];
            }
        });

        dayContainer.remove();

        // Renumber remaining days
        const remainingDays = document.querySelectorAll('.day-card');
        remainingDays.forEach((container, index) => {
            const newDayNumber = index + 1;
            container.setAttribute('data-day', newDayNumber);
            container.querySelector('.day-title').textContent = `Día ${newDayNumber}`;

            // Update day numbers in itemsData
            Object.keys(itemsData).forEach(key => {
                if (itemsData[key].day === parseInt(container.dataset.day)) {
                    itemsData[key].day = newDayNumber;
                }
            });
        });

        dayCounter = remainingDays.length;

        showNotification('Día Eliminado', `Día ${dayNumber} eliminado del itinerario.`);

        // Auto-save after deleting day
        setTimeout(() => {
            autoSave();
        }, 500);
    }
}

// Export functions for use in other modules
window.DayManagerModule = {
    addNewDay,
    deleteDay
};
