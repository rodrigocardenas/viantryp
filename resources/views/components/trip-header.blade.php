{{-- Componente: Trip Header --}}
{{-- Ubicación: resources/views/components/trip-header.blade.php --}}
{{-- Propósito: Header del viaje con título e información básica --}}
{{-- Props: trip (opcional) --}}
{{-- CSS: resources/css/components/trip-header.css --}}

@props(['trip' => null])

@php
    $client = $trip ? $trip->persons->where('type', 'client')->first() : null;
    $agent = $trip ? $trip->persons->where('type', 'agent')->first() : null;
@endphp

<div class="trip-header-card">
    <!-- Main Editing Form -->
    <div class="trip-header-form">
        @php $today = date('Y-m-d'); @endphp
        <input type="hidden" id="start-date" value="{{ $trip && $trip->start_date ? $trip->start_date->format('Y-m-d') : $today }}">

        <div class="form-group-top">
            <label class="field-label">TÍTULO DEL PLAN</label>
            <input type="text" id="trip-title" class="trip-title-input-premium" placeholder="Nombre del viaje" value="{{ $trip->title ?? '' }}">
        </div>

        <div class="form-group-top">
            <label class="field-label">NOMBRE DEL CLIENTE</label>
            <input type="text" id="trip-client-name" class="trip-client-input-premium" placeholder="Nombre del cliente" value="{{ $client->name ?? '' }}" readonly>
        </div>

        <hr class="header-divider">

        <div class="form-row-flexible">
            <div class="form-group-half">
                <label class="field-label">NÚMERO DE VIAJEROS</label>
                <div class="stepper-input">
                    <button type="button" class="stepper-btn minus" onclick="decrementTravelers()">−</button>
                    <input type="number" id="trip-travelers" class="stepper-value" value="{{ $trip->travelers ?? 1 }}" min="1">
                    <button type="button" class="stepper-btn plus" onclick="incrementTravelers()">+</button>
                </div>
                <span class="field-help">Adultos + niños</span>
            </div>

            <div class="form-group-half">
                <label class="field-label">VALOR DEL VIAJE</label>
                <div class="price-group-premium">
                    <input type="number" id="trip-price" class="price-input-premium" placeholder="0,00" min="0" step="0.01" value="{{ $trip->price ?? 0 }}">
                    <div class="currency-selector-wrapper">
                        <select id="trip-currency" class="currency-select-premium">
                            <option value="USD" {{ ($trip->currency ?? 'USD') == 'USD' ? 'selected' : '' }}>USD</option>
                            <option value="EUR" {{ ($trip->currency ?? '') == 'EUR' ? 'selected' : '' }}>EUR</option>
                            <option value="COP" {{ ($trip->currency ?? '') == 'COP' ? 'selected' : '' }}>COP</option>
                            <option value="MXN" {{ ($trip->currency ?? '') == 'MXN' ? 'selected' : '' }}>MXN</option>
                        </select>
                        <i class="fas fa-chevron-down select-icon"></i>
                    </div>
                </div>
                <span class="field-help">Valor total del viaje</span>
            </div>
        </div>
    </div>

    <!-- Footer within the card -->
    <div class="trip-header-footer">
        <div class="footer-actions">
            <button type="button" class="btn-cancel-premium" onclick="window.history.back()">Cancelar</button>
            <button type="button" class="btn-save-premium" data-action="save-trip">Guardar cambios</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Stepper logic
    window.incrementTravelers = function() {
        const input = document.getElementById('trip-travelers');
        if (input) {
            input.value = parseInt(input.value || 0) + 1;
            input.dispatchEvent(new Event('change'));
        }
    };

    window.decrementTravelers = function() {
        const input = document.getElementById('trip-travelers');
        if (input && input.value > 1) {
            input.value = parseInt(input.value) - 1;
            input.dispatchEvent(new Event('change'));
        }
    };

        // Make the start-date editable and keep it in sync with global state
        const startDateInput = document.getElementById('start-date');
        if (startDateInput) {
            // ensure min is set to today
            const today = new Date().toISOString().split('T')[0];
            if (!startDateInput.hasAttribute('min')) startDateInput.setAttribute('min', today);

            startDateInput.addEventListener('change', function() {
                // update window state so saveFlow picks it up
                if (!window.existingTripData) window.existingTripData = {};
                window.existingTripData.start_date = startDateInput.value || null;

                // DISABLE AUTOMATIC DATE UPDATES
                // The user requested to stop automatic consecutive date filling.
                // Keeping the code commented out for reference or future optional enabling.
                /*
                if (typeof updateItineraryDates === 'function') {
                    try { updateItineraryDates(); } catch (e) { console.warn('updateItineraryDates failed', e); }
                }
                */
            });
        }
});
</script>
