{{-- Componente: Trip Header --}}
{{-- Ubicación: resources/views/components/editor/trip-header.blade.php --}}
{{-- Propósito: Header del viaje con título e información básica --}}
{{-- Props: trip (opcional) --}}

@props(['trip' => null])

<!-- Trip Title -->
<div class="trip-title-section">
    <div class="trip-icon">
        <i class="fas fa-plane"></i>
    </div>
    <input type="text" id="trip-title" class="trip-title-input" placeholder="Nombre del viaje" value="{{ $trip->title ?? '' }}">
</div>

<!-- Trip Information Card -->
<div class="info-card">
    <div class="card-header">
        <i class="fas fa-calendar-alt"></i>
        <h3>Información de Itinerario</h3>
    </div>
    <div class="card-content">
        <div class="form-row">
            <div class="form-group">
                <label>Fecha de inicio:</label>
                <input type="date" id="start-date" class="form-input" value="{{ $trip ? ($trip->start_date ? $trip->start_date->format('Y-m-d') : '') : '' }}">
            </div>
            <button class="btn-update-dates" onclick="updateItineraryDates()">
                <i class="fas fa-sync-alt"></i>
                Actualizar fechas
            </button>
        </div>
    </div>
</div>