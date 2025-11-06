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

        // If editing existing element
        if (this.currentElementData && this.currentElementData.title && this.currentElementData.title !== '') {
            // Update existing element
            this.updateExistingElement(formData);
        } else {
            // Create new element
            this.timelineManager.addElementToDay(formData);
        }

        this.modalManager.closeModal();
        this.showNotification('Elemento Guardado', `${this.getTypeLabel(currentElementType)} guardado correctamente.`);
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
                data[input.id.replace('-', '_')] = input.checked;
            } else if (input.type === 'file') {
                // Skip file inputs, handled separately
            } else if (input.value.trim()) {
                data[input.id.replace('-', '_')] = input.value.trim();
            }
        });

        // Include uploaded documents for this element type
        const uploadedDocs = this.fileManager.getUploadedDocuments(currentElementType);
        if (uploadedDocs.length > 0) {
            data.documents = uploadedDocs.map(doc => doc.id);
        }

        // Include selected hotel data if this is a hotel element
        if (currentElementType === 'hotel' && this.selectedHotelData) {
            data.hotel_id = this.selectedHotelData.id;
            data.hotel_name = this.selectedHotelData.name || this.selectedHotelData.hotel_name;
            data.hotel_data = this.selectedHotelData;
        }

        return data;
    }

    updateExistingElement(newData) {
        // Find the existing element to update
        const allItems = document.querySelectorAll('.timeline-item');
        let elementToUpdate = null;

        allItems.forEach(item => {
            const itemData = this.extractItemDataForDisplay(item);
            if (itemData && itemData.title === this.currentElementData.title && itemData.type === this.currentElementData.type) {
                elementToUpdate = item;
            }
        });

        if (elementToUpdate) {
            // Update the element's content
            const titleElement = elementToUpdate.querySelector('.item-title');
            const subtitleElement = elementToUpdate.querySelector('.item-subtitle');

            if (titleElement) {
                titleElement.textContent = this.getElementTitle(newData);
            }
            if (subtitleElement) {
                subtitleElement.textContent = this.getElementSubtitle(newData);
            }

            // Update summaries
            this.summaryManager.updateAllSummaries();
        }
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
            case 'flight':
                return `${data.airline || 'Vuelo'} ${data.flight_number || ''}`.trim();
            case 'hotel':
                return data.hotel_name || 'Hotel';
            case 'activity':
                return data.activity_title || 'Actividad';
            case 'transport':
                return data.transport_type || 'Traslado';
            case 'note':
                return data.note_title || 'Nota';
            case 'summary':
                return data.summary_title || 'Resumen de Itinerario';
            case 'total':
                const currencySymbols = {
                    'USD': '$',
                    'EUR': '€',
                    'CLP': '$',
                    'ARS': '$',
                    'PEN': 'S/',
                    'COP': '$',
                    'MXN': '$'
                };
                const symbol = currencySymbols[data.currency] || data.currency || '$';
                const amount = data.total_amount || '0.00';
                return `${symbol}${parseFloat(amount).toFixed(2)} ${data.currency || 'USD'}`;
            default:
                return 'Elemento';
        }
    }

    getElementSubtitle(data) {
        switch (data.type) {
            case 'flight':
                const departureInfo = `${data.departure_airport || ''} ${data.departure_time || ''}`.trim();
                const arrivalInfo = `${data.arrival_airport || ''} ${data.arrival_time || ''}`.trim();
                if (departureInfo && arrivalInfo) {
                    return `${departureInfo} → ${arrivalInfo}`;
                }
                return departureInfo || arrivalInfo || '';
            case 'hotel':
                return `${data.check_in || ''} - ${data.check_out || ''}`.replace(' - ', '');
            case 'activity':
                return data.location || '';
            case 'transport':
                return `${data.pickup_location || ''} → ${data.destination || ''}`.replace(' → ', '');
            case 'summary':
                return 'Resumen automático del viaje';
            case 'total':
                return data.price_breakdown || 'Precio total del viaje';
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
