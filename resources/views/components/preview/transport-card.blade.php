@props(['item', 'trip', 'documents' => null])

<div class="flight-card-unified">
    <!-- Header similar to flight-route-header -->
    <div class="flight-route-header">
        <span class="airport-route-text">Transporte en {{ $item['transport_type'] ?? 'Traslado' }}</span>
    </div>

    <!-- Route section similar to flight-route-main -->
    <div class="flight-route-main">
        <!-- Departure section -->
        <div class="airport-section">
            <div class="airport-info">
                <div class="airport-time-date">
                    <span class="airport-time">{{ $item['pickup_datetime'] ? \Carbon\Carbon::parse($item['pickup_datetime'])->format('H:i') : 'Hora no disponible' }}</span>
                    <span class="airport-date">{{ $item['pickup_datetime'] ? \Carbon\Carbon::parse($item['pickup_datetime'])->format('d/m/Y') : '' }}</span>
                </div>
                <div class="airport-name">{{ $item['pickup_location'] ?? 'Ubicación no especificada' }}</div>
                <div class="airport-location">Salida</div>
            </div>
        </div>

        <!-- Transport connector -->
        <div class="flight-connector">
            <div class="plane-container">
                @php
                    $icon = 'fas fa-car';
                    if ($item['transport_type'] === 'Tren') {
                        $icon = 'fas fa-train';
                    } elseif ($item['transport_type'] === 'Bus') {
                        $icon = 'fas fa-bus';
                    } elseif ($item['transport_type'] === 'Barco/Ferry') {
                        $icon = 'fas fa-ship';
                    }
                @endphp
                <i class="{{ $icon }} flight-plane" style="color: #000000;"></i>
            </div>
        </div>

        <!-- Arrival section -->
        <div class="airport-section">
            <div class="airport-info arrival-info">
                <div class="airport-time-date">
                    <span class="airport-time">{{ $item['arrival_datetime'] ? \Carbon\Carbon::parse($item['arrival_datetime'])->format('H:i') : 'Hora no disponible' }}</span>
                    <span class="airport-date">{{ $item['arrival_datetime'] ? \Carbon\Carbon::parse($item['arrival_datetime'])->format('d/m/Y') : '' }}</span>
                </div>
                <div class="airport-name">{{ $item['destination'] ?? 'Ubicación no especificada' }}</div>
                <div class="airport-location">Llegada</div>
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
