const fs = require('fs');

const targetPath = 'c:/laragon/www/viantryp/resources/js/modules/day-manager.js';
let content = fs.readFileSync(targetPath, 'utf8');

const replacement = `        // Create day header
        const dayHeader = document.createElement('div');
        dayHeader.className = 'day-header';

        // --- TITLE SECTION ---
        const titleSection = document.createElement('div');
        titleSection.className = 'day-title-section';

        const dayBadge = document.createElement('div');
        dayBadge.className = 'day-badge';
        dayBadge.textContent = \`DÍA \${newDayNumber}\`;
        titleSection.appendChild(dayBadge);

        const nameInput = document.createElement('input');
        nameInput.type = 'text';
        nameInput.className = 'day-name-input';
        nameInput.placeholder = 'Nombre del día (opcional)';
        nameInput.setAttribute('data-day', newDayNumber);
        titleSection.appendChild(nameInput);

        dayHeader.appendChild(titleSection);

        // --- META SECTION ---
        const metaSection = document.createElement('div');
        metaSection.className = 'day-meta-section';

        // Date Group
        const dateGroup = document.createElement('div');
        dateGroup.className = 'day-date-group';

        const dateInput = document.createElement('input');
        dateInput.type = 'date';
        dateInput.id = \`day-\${newDayNumber}-date\`;
        dateInput.className = 'day-date-input-hidden';
        dateInput.setAttribute('data-day', newDayNumber);

        const dateDisplayBox = document.createElement('div');
        dateDisplayBox.className = 'day-date-display';
        dateDisplayBox.innerHTML = 'Seleccionar fecha <i class="far fa-calendar-alt"></i>';
        dateDisplayBox.onclick = function() { document.getElementById(\`day-\${newDayNumber}-date\`).showPicker(); };

        const dateTextSpan = document.createElement('span');
        dateTextSpan.className = 'day-date-text';
        dateTextSpan.id = \`day-\${newDayNumber}-date-display\`;

        dateInput.onchange = function () {
            if (this.value) {
                const date = new Date(this.value + 'T00:00:00');
                const formatted = date.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
                const fullText = date.toLocaleDateString('es-ES', { weekday: 'short', day: 'numeric', month: 'short' });
                dateDisplayBox.innerHTML = \`\${formatted} <i class="far fa-calendar-alt"></i>\`;
                dateTextSpan.textContent = fullText;

                if (newDayNumber === 1) {
                    const tripStartDateInput = document.getElementById('start-date');
                    if (tripStartDateInput) tripStartDateInput.value = this.value;
                }
            }
        };

        dateGroup.appendChild(dateInput);
        dateGroup.appendChild(dateDisplayBox);
        dateGroup.appendChild(dateTextSpan);
        metaSection.appendChild(dateGroup);

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
        dayCard.appendChild(dayHeader);`;

const startIndex = content.indexOf('        // Create day header');
const endIndex = content.indexOf('        // Create day content');

if (startIndex !== -1 && endIndex !== -1) {
    const updatedContent = content.substring(0, startIndex) + replacement + '\\n' + content.substring(endIndex);
    fs.writeFileSync(targetPath, updatedContent);
    console.log('Successfully updated day-manager.js');
} else {
    console.log('Could not find start/end bounds.');
}
