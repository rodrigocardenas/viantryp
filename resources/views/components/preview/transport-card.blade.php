@props(['item', 'trip', 'documents' => null])

<div class="flight-card-unified">
    <!-- Header similar to flight-route-header -->
    <div class="flight-route-header">
        <span class="airport-route-text">Transporte en Tren</span>
    </div>

    <!-- Route section similar to flight-route-main -->
    <div class="flight-route-main">
        <!-- Departure section -->
        <div class="airport-section">
            <div class="airport-info">
                <div class="airport-time-date">
                    <span class="airport-time">{{ $item['departure_time'] ?? 'Hora no disponible' }}</span>
                </div>
                <div class="airport-name">{{ $item['pickup_location'] ?? 'Ubicación no especificada' }}</div>
                <div class="airport-location">Salida</div>
            </div>
        </div>

        <!-- Train connector -->
        <div class="flight-connector">
            <div class="plane-container">
                <i class="fas fa-train flight-plane" style="color: #000000;"></i>
            </div>
        </div>

        <!-- Return section -->
        <div class="airport-section">
            <div class="airport-info arrival-info">
                <div class="airport-time-date">
                    <span class="airport-time">{{ $item['return_time'] ?? 'Hora no disponible' }}</span>
                </div>
                <div class="airport-name">{{ $item['drop_off_location'] ?? 'Ubicación no especificada' }}</div>
                <div class="airport-location">Regreso</div>
            </div>
        </div>
    </div>
    @php
        $documents = $documents ?? collect();
    @endphp
    @if($documents->count() > 0)
        <div class="documents-section">
            <h5>Documentos adjuntos:</h5>
            @foreach($documents as $document)
                <a href="{{ $document->url }}" target="_blank" class="document-link">
                    <i class="fas fa-file"></i> {{ $document->original_name }}
                </a>
            @endforeach
        </div>
    @endif
</div>
