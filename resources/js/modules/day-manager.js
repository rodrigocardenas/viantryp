// Day Manager Module - Handles day creation and date management
class DayManager {
    constructor(summaryManager) {
        this.summaryManager = summaryManager;
    }

    addNewDay() {
        const daysContainer = document.getElementById('days-container');
        const existingDays = daysContainer.querySelectorAll('.day-card');
        const newDayNumber = existingDays.length + 1;

        const dayCard = document.createElement('div');
        dayCard.className = 'day-card';
        dayCard.setAttribute('data-day', newDayNumber);

        const startDate = document.getElementById('start-date').value;
        let dayDate = 'Sin fecha';
        let defaultDate = '';
        if (startDate) {
            const date = new Date(startDate);
            date.setDate(date.getDate() + newDayNumber - 1);
            dayDate = date.toLocaleDateString('es-ES', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            defaultDate = date.toISOString().split('T')[0];
        }

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

        const addBtn = document.createElement('div');
        addBtn.className = 'add-element-btn btn-sm';
        addBtn.setAttribute('data-action', 'add-element');
        addBtn.setAttribute('data-day', newDayNumber);
        addBtn.innerHTML = '<i class="fas fa-plus"></i>';
        dayContent.appendChild(addBtn);

        dayCard.appendChild(dayContent);

        daysContainer.appendChild(dayCard);

        // Update summaries after adding new day
        this.summaryManager.updateAllSummaries();

        this.showNotification('Día Agregado', `Día ${newDayNumber} agregado al itinerario.`);
    }

    updateItineraryDates() {
        const startDateInput = document.getElementById('start-date').value;
        if (!startDateInput) {
            this.showNotification('Error', 'Por favor selecciona una fecha de inicio.');
            return;
        }

        const startDate = new Date(startDateInput + 'T00:00:00');
        const dayCards = document.querySelectorAll('.day-card');

        dayCards.forEach((card, index) => {
            const currentDate = new Date(startDate);
            currentDate.setDate(startDate.getDate() + index);

            const dateElement = card.querySelector('.day-date');
            const formattedDate = currentDate.toLocaleDateString('es-ES', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            dateElement.textContent = formattedDate;
            dateElement.setAttribute('data-date', currentDate.toISOString().split('T')[0]);
        });

        this.showNotification('Fechas Actualizadas', 'Las fechas de los días han sido actualizadas.');
        // Update summaries after date changes
        this.summaryManager.updateAllSummaries();
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
