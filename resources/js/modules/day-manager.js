// Day Manager Module - Handles day creation and date management
class DayManager {
    constructor(summaryManager) {
        this.summaryManager = summaryManager;
    }

    addNewDay() {
        const dayInput = document.getElementById('new-day-number');
        let newDayNumber;

        if (dayInput && dayInput.value) {
            newDayNumber = parseInt(dayInput.value);
            if (isNaN(newDayNumber) || newDayNumber < 1) {
                alert('Número de día inválido');
                return;
            }
        } else {
            // Comportamiento automático: calcular el siguiente día basado en los existentes
            const daysContainer = document.getElementById('days-container');
            const existingDays = daysContainer.querySelectorAll('.day-card');

            if (existingDays.length > 0) {
                const existingDayNumbers = Array.from(existingDays).map(card => parseInt(card.dataset.day));
                newDayNumber = Math.max(...existingDayNumbers) + 1;
            } else {
                newDayNumber = 1;
            }
        }

        const daysContainer = document.getElementById('days-container');
        const existingDays = daysContainer.querySelectorAll('.day-card');
        const existingDayNumbers = Array.from(existingDays).map(card => parseInt(card.dataset.day));
        if (existingDayNumbers.includes(newDayNumber)) {
            alert('Ese número de día ya existe');
            return;
        }

        const dayCard = document.createElement('div');
        dayCard.className = 'day-card';
        dayCard.setAttribute('data-day', newDayNumber);

        let dayDate = 'Sin fecha';
        let defaultDate = '';
        // Importante: NO auto-calcular fechas por "fecha inicio + día".
        // La fecha debe quedar tal como la ingrese el usuario (o en blanco).

        // Create day header
        const dayHeader = document.createElement('div');
        dayHeader.className = 'day-header';

        // --- TITLE SECTION ---
        const titleSection = document.createElement('div');
        titleSection.className = 'day-title-section';

        const titleRow = document.createElement('div');
        titleRow.className = 'day-title-row';

        const title = document.createElement('h3');
        title.textContent = `DÍA ${newDayNumber}`;
        titleRow.appendChild(title);

        const separator = document.createElement('span');
        separator.className = 'day-separator';
        separator.textContent = '|';
        titleRow.appendChild(separator);

        const input = document.createElement('input');
        input.type = 'date';
        input.id = `day-${newDayNumber}-date`;
        input.className = 'day-date-input-large';
        input.value = defaultDate;
        input.setAttribute('data-day', newDayNumber);

        input.onchange = function () {
            const displayId = `day-${newDayNumber}-date-display`;
            const displayEl = document.getElementById(displayId);
            if (displayEl) {
                if (this.value) {
                    const date = new Date(this.value + 'T00:00:00');
                    displayEl.textContent = date.toLocaleDateString('es-ES', {
                        weekday: 'long',
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });
                    if (newDayNumber === 1) {
                        const tripStartDateInput = document.getElementById('start-date');
                        if (tripStartDateInput) {
                            tripStartDateInput.value = this.value;
                        }
                    }
                } else {
                    displayEl.textContent = 'Sin fecha';
                }
            }
        };

        titleRow.appendChild(input);
        titleSection.appendChild(titleRow);

        const display = document.createElement('p');
        display.className = 'day-date-display';
        display.id = `day-${newDayNumber}-date-display`;
        display.textContent = dayDate;
        titleSection.appendChild(display);

        dayHeader.appendChild(titleSection);

        // --- META SECTION ---
        const metaSection = document.createElement('div');
        metaSection.className = 'day-meta-section';


        // Actions Group
        const actionsGroup = document.createElement('div');
        actionsGroup.className = 'day-actions';

        const copyBtn = document.createElement('button');
        copyBtn.className = 'action-btn-outline';
        copyBtn.setAttribute('data-action', 'copy-day');
        copyBtn.setAttribute('data-day', newDayNumber);
        copyBtn.title = 'Copiar día';
        copyBtn.innerHTML = '<i class="far fa-copy"></i>';

        const deleteBtn = document.createElement('button');
        deleteBtn.className = 'action-btn-outline text-danger';
        deleteBtn.setAttribute('data-action', 'delete-day');
        deleteBtn.setAttribute('data-day', newDayNumber);
        deleteBtn.title = 'Eliminar día';
        deleteBtn.innerHTML = '<i class="far fa-trash-alt"></i>';

        actionsGroup.appendChild(copyBtn);
        actionsGroup.appendChild(deleteBtn);
        metaSection.appendChild(actionsGroup);

        dayHeader.appendChild(metaSection);
        dayCard.appendChild(dayHeader);
        // Create day content
        const dayContent = document.createElement('div');
        dayContent.className = 'day-content';
        dayContent.setAttribute('ondrop', 'drop(event)');
        dayContent.setAttribute('ondragover', 'allowDrop(event)');

        const instruction = document.createElement('p');
        instruction.className = 'drag-instruction';
        instruction.textContent = 'Arrastra aquí los elementos que quieres agregar a este día';
        dayContent.appendChild(instruction);

        dayCard.appendChild(dayContent);

        daysContainer.appendChild(dayCard);

        // Update summaries after adding new day
        this.summaryManager.updateAllSummaries();

        this.showNotification('Día Agregado', `Día ${newDayNumber} agregado al itinerario.`);
    }

    updateItineraryDates() {
        // Se mantiene por compatibilidad, pero ya no realiza ninguna automatización de fechas.
        // Las fechas de los días deben permanecer tal como están registradas.
        this.summaryManager?.updateAllSummaries?.();
    }

    showNotification(title, message, type = 'success') {
        // This should be imported from a notification module
        console.log(`${type.toUpperCase()}: ${title} - ${message}`);
        // For now, use a simple alert or create a proper notification system
        if (typeof showNotification === 'function') {
            showNotification(title, message, type);
        } else {
            alert(`${title}: ${message}`);
        }
    }
}

export default DayManager;
