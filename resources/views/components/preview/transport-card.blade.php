@props(['item', 'trip', 'documents' => null])

@php
    // Formatear fechas largas en español como en flight-card
    $diasEspanol = [
        'Monday' => 'Lunes',
        'Tuesday' => 'Martes',
        'Wednesday' => 'Miércoles',
        'Thursday' => 'Jueves',
        'Friday' => 'Viernes',
        'Saturday' => 'Sábado',
        'Sunday' => 'Domingo'
    ];
    $mesesEspanol = [
        'January' => 'Enero',
        'February' => 'Febrero',
        'March' => 'Marzo',
        'April' => 'Abril',
        'May' => 'Mayo',
        'June' => 'Junio',
        'July' => 'Julio',
        'August' => 'Agosto',
        'September' => 'Septiembre',
        'October' => 'Octubre',
        'November' => 'Noviembre',
        'December' => 'Diciembre'
    ];

    // Formatear fecha de recogida
    $pickupDateLong = 'Fecha no disponible';
    if ($item['pickup_datetime']) {
        try {
            $pickupDT = \Carbon\Carbon::parse($item['pickup_datetime']);
            $diaIngles = $pickupDT->format('l');
            $mesIngles = $pickupDT->format('F');
            $diaNumero = $pickupDT->format('j');
            $pickupDateLong = $diasEspanol[$diaIngles] . ', ' . $diaNumero . ' de ' . $mesesEspanol[$mesIngles];
        } catch (Exception $e) {
            $pickupDateLong = 'Fecha no disponible';
        }
    }

    // Formatear fecha de llegada
    $arrivalDateLong = 'Fecha no disponible';
    if ($item['arrival_datetime']) {
        try {
            $arrivalDT = \Carbon\Carbon::parse($item['arrival_datetime']);
            $diaIngles = $arrivalDT->format('l');
            $mesIngles = $arrivalDT->format('F');
            $diaNumero = $arrivalDT->format('j');
            $arrivalDateLong = $diasEspanol[$diaIngles] . ', ' . $diaNumero . ' de ' . $mesesEspanol[$mesIngles];
        } catch (Exception $e) {
            $arrivalDateLong = 'Fecha no disponible';
        }
    }
@endphp

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
                </div>
                <div class="airport-name">{{ $item['pickup_location'] ?? 'Ubicación no especificada' }}</div>
                <div class="airport-location">Salida</div>
                <span class="airport-date">{{ $pickupDateLong }}</span>

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
                </div>
                <div class="airport-name">{{ $item['destination'] ?? 'Ubicación no especificada' }}</div>
                <div class="airport-location">Llegada</div>
                <span class="airport-date">{{ $arrivalDateLong }}</span>

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
