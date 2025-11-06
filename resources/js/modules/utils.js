// Utils Module - General utility functions
class Utils {
    constructor(modalManager, summaryManager) {
        this.modalManager = modalManager;
        this.summaryManager = summaryManager;
    }

    closeModal() {
        document.getElementById('element-modal').style.display = 'none';
        // Reset modal state - this should be handled by modal manager
        if (this.modalManager) {
            this.modalManager.resetModalState();
        }
    }

    deleteElement(button) {
        if (confirm('¿Estás seguro de que quieres eliminar este elemento?')) {
            button.closest('.timeline-item').remove();
            this.showNotification('Elemento Eliminado', 'El elemento ha sido eliminado del itinerario.');
            // Update summaries after deletion
            if (this.summaryManager) {
                this.summaryManager.updateAllSummaries();
            }
        }
    }

    extractItemDataForDisplay(itemElement) {
        if (!itemElement) return null;

        const baseData = {
            type: itemElement.classList.contains('flight') ? 'flight' :
                  itemElement.classList.contains('hotel') ? 'hotel' :
                  itemElement.classList.contains('activity') ? 'activity' :
                  itemElement.classList.contains('transport') ? 'transport' :
                  itemElement.classList.contains('note') ? 'note' :
                  itemElement.classList.contains('total') ? 'total' : 'unknown'
        };

        switch (baseData.type) {
            case 'flight':
                return {
                    ...baseData,
                    title: itemElement.querySelector('.item-title')?.textContent || '',
                    airline: itemElement.dataset.airline || itemElement.querySelector('.item-title')?.textContent?.split(' ')[1] || '',
                    flight_number: itemElement.dataset.flightNumber || itemElement.querySelector('.item-title')?.textContent?.split(' ')[2] || '',
                    departure_airport: itemElement.dataset.departureAirport || itemElement.querySelector('.item-subtitle')?.textContent?.split(' → ')[0] || '',
                    arrival_airport: itemElement.dataset.arrivalAirport || itemElement.querySelector('.item-subtitle')?.textContent?.split(' → ')[1] || '',
                    departure_time: itemElement.dataset.departureTime || '',
                    arrival_time: itemElement.dataset.arrivalTime || '',
                    confirmation_number: itemElement.dataset.confirmationNumber || ''
                };

            case 'hotel':
                const hotelData = {
                    ...baseData,
                    title: itemElement.querySelector('.item-title')?.textContent || '',
                    hotel_name: itemElement.querySelector('.item-title')?.textContent || '',
                    hotel_id: itemElement.dataset.hotelId || '',
                    hotel_data: itemElement.dataset.hotelData ? JSON.parse(itemElement.dataset.hotelData) : null,
                    check_in: itemElement.dataset.checkIn || '',
                    check_out: itemElement.dataset.checkOut || '',
                    room_type: itemElement.dataset.roomType || '',
                    nights: itemElement.dataset.nights || 1
                };

                // If we have hotel_data, extract additional info
                if (hotelData.hotel_data) {
                    hotelData.hotel_name = hotelData.hotel_data.name || hotelData.hotel_name;
                }

                return hotelData;

            case 'activity':
                return {
                    ...baseData,
                    title: itemElement.querySelector('.item-title')?.textContent || '',
                    activity_title: itemElement.querySelector('.item-title')?.textContent || '',
                    location: itemElement.querySelector('.item-subtitle')?.textContent || '',
                    start_time: itemElement.dataset.startTime || '',
                    end_time: itemElement.dataset.endTime || '',
                    description: itemElement.dataset.description || ''
                };

            case 'transport':
                return {
                    ...baseData,
                    title: itemElement.querySelector('.item-title')?.textContent || '',
                    transport_type: itemElement.querySelector('.item-title')?.textContent || '',
                    pickup_location: itemElement.querySelector('.item-subtitle')?.textContent?.split(' → ')[0] || '',
                    destination: itemElement.querySelector('.item-subtitle')?.textContent?.split(' → ')[1] || '',
                    pickup_time: itemElement.dataset.pickupTime || ''
                };

            case 'note':
                return {
                    ...baseData,
                    title: itemElement.querySelector('.item-title')?.textContent || '',
                    note_title: itemElement.querySelector('.item-title')?.textContent || '',
                    note_content: itemElement.querySelector('.item-subtitle')?.textContent || ''
                };

            case 'total':
                return {
                    ...baseData,
                    title: itemElement.querySelector('.item-title')?.textContent || '',
                    total_amount: itemElement.dataset.totalAmount || '',
                    currency: itemElement.dataset.currency || '',
                    price_breakdown: itemElement.dataset.priceBreakdown || '',
                    place_at_end: itemElement.dataset.placeAtEnd === 'true'
                };

            default:
                return {
                    ...baseData,
                    title: itemElement.querySelector('.item-title')?.textContent || 'Elemento',
                    subtitle: itemElement.querySelector('.item-subtitle')?.textContent || ''
                };
        }
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

export default Utils;
