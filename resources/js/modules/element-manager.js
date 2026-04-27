// Element Manager Module - Handles element creation, validation, and management
import elementLabels from '../data/element-labels.json';

class ElementManager {
    constructor(modalManager, timelineManager, summaryManager, fileManager) {
        this.modalManager = modalManager;
        this.timelineManager = timelineManager;
        this.summaryManager = summaryManager;
        this.fileManager = fileManager;
        this.currentElementData = {};
        this.selectedHotelData = null;
    }

    saveElement(currentElementType, currentDay) {
        const formData = this.collectFormData(currentElementType, currentDay);

        // Validate required fields
        if (!this.validateForm(formData)) {
            return;
        }

        // Check if we are editing (using the isEditing flag in ModalManager)
        if (this.modalManager.isEditing && this.modalManager.editingElement) {
            // Update existing element with new data
            this.updateExistingElement(formData, this.modalManager.editingElement);
        } else {
            // Create new element
            this.timelineManager.addElementToDay(formData);
        }

        this.modalManager.closeModal();
        this.showNotification('Elemento Guardado', `${this.getTypeLabel(currentElementType)} guardado correctamente.`);

        // Trigger auto-save of the entire trip
        document.dispatchEvent(new CustomEvent('saveTripRequested'));
    }

    validateForm(data) {
        // Validate required fields based on element type
        if (data.type === 'total') {
            if (!data.total_amount || data.total_amount === '0') {
                this.showNotification('Error', 'El precio total es obligatorio.', 'error');
                return false;
            }
            if (!data.currency) {
                this.showNotification('Error', 'La moneda es obligatoria.', 'error');
                return false;
            }
        }
        return true;
    }

    collectFormData(currentElementType, currentDay) {
        const data = { type: currentElementType, day: currentDay };
        const form = document.getElementById('modal-body');
        const inputs = form.querySelectorAll('input, textarea, select');

        inputs.forEach(input => {
            if (input.type === 'checkbox') {
                data[input.id.replace(/-/g, '_')] = input.checked;
            } else if (input.type === 'file') {
                // Skip file inputs, handled separately
            } else if (input.value.trim()) {
                data[input.id.replace(/-/g, '_')] = input.value.trim();
            }
        });

        // Include temp_id for new elements to link uploaded documents
        if (this.modalManager.currentElementData && this.modalManager.currentElementData.id && this.modalManager.currentElementData.id.startsWith('temp_')) {
            data.temp_id = this.modalManager.currentElementData.id;
        }

        // Include uploaded documents for this element type
        const uploadedDocs = this.fileManager.getUploadedDocuments(currentElementType);
        if (uploadedDocs.length > 0) {
            data.documents = uploadedDocs.map(doc => doc.id);
        }

        // Include selected hotel data if this is a hotel element
        if (currentElementType === 'hotel' && this.selectedHotelData) {
            data.hotel_id = this.selectedHotelData.place_id;
            data.hotel_name = this.selectedHotelData.name;
            data.hotel_data = this.selectedHotelData;
            // Include additional Google Places data
            data.place_id = this.selectedHotelData.place_id;
            data.formatted_address = this.selectedHotelData.formatted_address;
            data.rating = this.selectedHotelData.rating;
            data.website = this.selectedHotelData.website;
            data.phone_number = this.selectedHotelData.international_phone_number;
            if (this.selectedHotelData.geometry && this.selectedHotelData.geometry.location) {
                data.latitude = this.selectedHotelData.geometry.location.lat;
                data.longitude = this.selectedHotelData.geometry.location.lng;
            }
        }

        return data;
    }

    updateExistingElement(newData, elementDiv) {
        if (!elementDiv) {
            // Fallback: find by current title/type match
            const allItems = document.querySelectorAll('.timeline-item');
            allItems.forEach(item => {
                const titleEl = item.querySelector('.item-title');
                if (titleEl && item.getAttribute('data-type') === this.currentElementData.type) {
                    elementDiv = item;
                }
            });
        }

        if (!elementDiv) return;

        // Update ALL data-* attributes with the new form data
        Object.keys(newData).forEach(key => {
            if (key === 'type' || key === 'day') return;
            let value = newData[key];
            if (typeof value === 'object' && value !== null) {
                value = JSON.stringify(value);
            }
            if (value !== undefined && value !== null && value !== '') {
                elementDiv.setAttribute(`data-${key.replace(/_/g, '-')}`, value);
            }
        });

        // Update visible text (title and subtitle)
        const titleElement = elementDiv.querySelector('.item-title');
        const subtitleElement = elementDiv.querySelector('.item-subtitle');

        if (titleElement) {
            titleElement.textContent = this.getElementTitle(newData);
        }
        if (subtitleElement) {
            subtitleElement.textContent = this.getElementSubtitle(newData);
        }

        // Update summaries
        this.summaryManager.updateAllSummaries();
    }

    extractItemDataForDisplay(item) {
        // Extract data from timeline item for comparison
        const titleElement = item.querySelector('.item-title');
        const typeElement = item.querySelector('.item-type');

        if (titleElement && typeElement) {
            return {
                title: titleElement.textContent,
                type: typeElement.getAttribute('data-type')
            };
        }
        return null;
    }

    getElementTitle(data) {
        switch (data.type) {
            case 'flight': {
                const dep = data.departure_airport || '';
                const arr = data.arrival_airport || '';
                if (dep && arr) return `${dep} → ${arr}`;
                return dep || arr || data.airline || 'Vuelo';
            }
            case 'hotel':
                return data.hotel_name || 'Hotel';
            case 'activity':
                return data.activity_title || 'Actividad';
            case 'transport': {
                const from = data.pickup_location || '';
                const to = data.destination || '';
                if (from && to) return `${from} → ${to}`;
                return from || to || data.transport_type || 'Traslado';
            }
            case 'note':
                return data.note_title || 'Nota';
            case 'summary':
                return data.summary_title || 'Resumen de Itinerario';
            case 'total': {
                const currencySymbols = {
                    'USD': '$', 'EUR': '€', 'CLP': '$',
                    'ARS': '$', 'PEN': 'S/', 'COP': '$', 'MXN': '$'
                };
                const symbol = currencySymbols[data.currency] || data.currency || '$';
                const amount = data.total_amount || '0.00';
                return `${symbol}${parseFloat(amount).toFixed(2)} ${data.currency || 'USD'}`;
            }
            case 'documents':
                return data.documents_title || 'Documentos';
            default:
                return 'Elemento';
        }
    }

    formatDateEs(dateString, includeTime = true) {
        if (!dateString) return '';
        try {
            // Check if string contains only time (e.g., "14:30")
            if (/^\d{2}:\d{2}$/.test(dateString)) return dateString;

            const date = new Date(dateString);
            if (isNaN(date.getTime())) return dateString;

            const options = { day: 'numeric', month: 'short' };
            if (includeTime && dateString.includes('T')) {
                options.hour = '2-digit';
                options.minute = '2-digit';
            }
            return date.toLocaleDateString('es-ES', options).replace(',', '.');
        } catch (e) {
            return dateString;
        }
    }

    getElementSubtitle(data) {
        switch (data.type) {
            case 'flight': {
                const depDate = this.formatDateEs(data.departure_datetime, true);
                const arrDate = this.formatDateEs(data.arrival_datetime, true);
                if (depDate && arrDate) return `${depDate} → ${arrDate}`;
                return depDate || arrDate;
            }
            case 'hotel': {
                const checkin = this.formatDateEs(data.check_in, false);
                const checkout = this.formatDateEs(data.check_out, false);
                if (checkin && checkout) return `${checkin} - ${checkout}`;
                return checkin || checkout;
            }
            case 'activity': {
                const start = this.formatDateEs(data.start_datetime, true);
                const loc = data.location || '';
                if (start && loc) return `${start} | ${loc}`;
                return start || loc;
            }
            case 'transport': {
                const pickDate = this.formatDateEs(data.pickup_datetime, true);
                const arrTransDate = this.formatDateEs(data.arrival_datetime, true);
                if (pickDate && arrTransDate) return `${pickDate} → ${arrTransDate}`;
                return pickDate || arrTransDate;
            }
            case 'summary':
                return 'Resumen automático del viaje';
            case 'total':
                return data.price_breakdown || 'Precio total del viaje';
            case 'documents':
                return data.documents_description || (data.documents ? `${data.documents.length} archivos` : 'Sin archivos');
            default:
                return '';
        }
    }

    getTypeLabel(type) {
        return elementLabels.elementTypes[type] || 'Elemento';
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

    setCurrentElementData(data) {
        this.currentElementData = data;
    }

    setSelectedHotelData(data) {
        this.selectedHotelData = data;
    }
}

export default ElementManager;
