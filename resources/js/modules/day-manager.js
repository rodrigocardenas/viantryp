// Day Manager Module - Handles day creation and date management
class DayManager {
    constructor(summaryManager) {
        this.summaryManager = summaryManager;
    }

    addNewDay() {
        const dayInput = document.getElementById('new-day-number');
        if (!dayInput || !dayInput.value) {
            alert('Por favor ingresa un número de día');
            return;
        }
        const newDayNumber = parseInt(dayInput.value);
        if (isNaN(newDayNumber) || newDayNumber < 1) {
            alert('Número de día inválido');
            return;
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

        // Create title section
        const titleSection = document.createElement('div');
        titleSection.className = 'day-title-section';

        const title = document.createElement('h3');
        title.textContent = `Día ${newDayNumber}`;
        titleSection.appendChild(title);

        const deleteBtn = document.createElement('button');
        deleteBtn.className = 'btn-delete-day';
        deleteBtn.setAttribute('data-action', 'delete-day');
        deleteBtn.setAttribute('data-day', newDayNumber);
        deleteBtn.title = 'Eliminar día';
        deleteBtn.innerHTML = '<i class="fas fa-trash"></i>';
        titleSection.appendChild(deleteBtn);

        dayHeader.appendChild(titleSection);

        // Create date section
        const dateSection = document.createElement('div');
        dateSection.className = 'day-date-section';

        const label = document.createElement('label');
        label.setAttribute('for', `day-${newDayNumber}-date`);
        label.textContent = 'Fecha:';
        dateSection.appendChild(label);

        const input = document.createElement('input');
        input.type = 'date';
        input.id = `day-${newDayNumber}-date`;
        input.className = 'day-date-input';
        input.value = defaultDate;
        input.setAttribute('data-day', newDayNumber);
        dateSection.appendChild(input);

        const display = document.createElement('p');
        display.className = 'day-date-display';
        display.id = `day-${newDayNumber}-date-display`;
        display.textContent = dayDate;
        dateSection.appendChild(display);

        dayHeader.appendChild(dateSection);

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
