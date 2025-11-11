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
        if (startDate) {
            const date = new Date(startDate);
            date.setDate(date.getDate() + newDayNumber - 1);
            dayDate = date.toLocaleDateString('es-ES', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }

        dayCard.innerHTML = `
            <div class="day-header">
                <h3>Día ${newDayNumber}</h3>
                <p class="day-date">${dayDate}</p>
            </div>
            <div class="day-content" ondrop="drop(event)" ondragover="allowDrop(event)">
                <div class="add-element-btn" data-action="add-element" data-day="${newDayNumber}">
                    <i class="fas fa-plus"></i>
                </div>
                <p class="drag-instruction">Arrastra elementos aquí para personalizar este día</p>
            </div>
        `;

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

    deleteDay(dayNumber) {
        const dayCard = document.querySelector(`[data-day="${dayNumber}"]`);
        if (!dayCard) return;

        // Don't allow deleting the last remaining day
        const totalDays = document.querySelectorAll('.day-card').length;
        if (totalDays <= 1) {
            this.showNotification('Error', 'No se puede eliminar el último día del viaje.');
            return;
        }

        // Confirm deletion - same message as element deletion
        if (!confirm('¿Estás seguro de que quieres eliminar este elemento?')) {
            return;
        }

        // Remove the day
        dayCard.remove();

        // Renumber remaining days
        this.renumberDays();

        // Update dates
        this.updateItineraryDates();

        // Update summaries
        this.summaryManager.updateAllSummaries();

        this.showNotification('Elemento Eliminado', 'El elemento ha sido eliminado del itinerario.');
    }

    renumberDays() {
        const dayCards = document.querySelectorAll('.day-card');
        dayCards.forEach((card, index) => {
            const newDayNumber = index + 1;
            card.setAttribute('data-day', newDayNumber);

            // Update day header
            const dayHeader = card.querySelector('.day-header h3');
            if (dayHeader) {
                dayHeader.textContent = `Día ${newDayNumber}`;
            }

            // Update add element buttons
            const addBtn = card.querySelector('.add-element-btn');
            if (addBtn) {
                addBtn.setAttribute('data-day', newDayNumber);
            }

            // Update delete day buttons
            const deleteBtn = card.querySelector('.delete-day-btn');
            if (deleteBtn) {
                deleteBtn.setAttribute('data-day', newDayNumber);
            }

            // Update timeline items
            const timelineItems = card.querySelectorAll('.timeline-item');
            timelineItems.forEach(item => {
                // Update data-day attribute if it exists
                if (item.hasAttribute('data-day')) {
                    item.setAttribute('data-day', newDayNumber);
                }
            });
        });
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
