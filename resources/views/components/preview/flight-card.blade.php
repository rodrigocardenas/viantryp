@props(['item', 'trip', 'documents' => null])

@php
    // Calcular duración del vuelo considerando zonas horarias
    $departureTimezone = $item['departure_timezone'] ?? 'America/Bogota';
    $arrivalTimezone = $item['arrival_timezone'] ?? 'America/New_York';
    $departureDateTime = $item['departure_date'] ?? date('Y-m-d');
    $departureTime = $item['departure_time'] ?? '00:00';
    $arrivalDateTime = $item['arrival_date'] ?? date('Y-m-d');
    $arrivalTime = $item['arrival_time'] ?? '00:00';

    try {
        $departureDT = new DateTime($departureDateTime . ' ' . $departureTime, new DateTimeZone($departureTimezone));
        $arrivalDT = new DateTime($arrivalDateTime . ' ' . $arrivalTime, new DateTimeZone($arrivalTimezone));
        $interval = $departureDT->diff($arrivalDT);
        $hours = $interval->h + ($interval->days * 24);
        $minutes = $interval->i;
        $duration = $hours . 'h ' . $minutes . 'm';

        // Formatear fechas largas en español
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

        $diaIngles = $departureDT->format('l');
        $mesIngles = $departureDT->format('F');
        $diaNumero = $departureDT->format('j');
        $departureDateLong = $diasEspanol[$diaIngles] . ', ' . $diaNumero . ' de ' . $mesesEspanol[$mesIngles];

        $diaIngles = $arrivalDT->format('l');
        $mesIngles = $arrivalDT->format('F');
        $diaNumero = $arrivalDT->format('j');
        $arrivalDateLong = $diasEspanol[$diaIngles] . ', ' . $diaNumero . ' de ' . $mesesEspanol[$mesIngles];

        // Abbreviated dates for mobile
        $diasAbrev = [
            'Mon' => 'lun.',
            'Tue' => 'mar.',
            'Wed' => 'mié.',
            'Thu' => 'jue.',
            'Fri' => 'vie.',
            'Sat' => 'sáb.',
            'Sun' => 'dom.'
        ];
        $mesesAbrev = [
            'Jan' => 'ene.',
            'Feb' => 'feb.',
            'Mar' => 'mar.',
            'Apr' => 'abr.',
            'May' => 'may.',
            'Jun' => 'jun.',
            'Jul' => 'jul.',
            'Aug' => 'ago.',
            'Sep' => 'sept.',
            'Oct' => 'oct.',
            'Nov' => 'nov.',
            'Dec' => 'dic.'
        ];

        $diaInglesAbrev = $departureDT->format('D');
        $mesInglesAbrev = $departureDT->format('M');
        $departureDateAbrev = $diasAbrev[$diaInglesAbrev] . ' ' . $diaNumero . ' de ' . $mesesAbrev[$mesInglesAbrev];

        $diaInglesAbrev = $arrivalDT->format('D');
        $mesInglesAbrev = $arrivalDT->format('M');
        $diaNumeroArrival = $arrivalDT->format('j');
        $arrivalDateAbrev = $diasAbrev[$diaInglesAbrev] . ' ' . $diaNumeroArrival . ' de ' . $mesesAbrev[$mesInglesAbrev];

        // Return flight if available
        if (isset($item['return_date']) && $item['return_date']) {
            try {
                $returnDT = new DateTime($item['return_date'] . ' ' . ($item['return_time'] ?? '00:00'), new DateTimeZone($arrivalTimezone));
                $diaInglesAbrev = $returnDT->format('D');
                $mesInglesAbrev = $returnDT->format('M');
                $diaNumeroReturn = $returnDT->format('j');
                $returnDateAbrev = $diasAbrev[$diaInglesAbrev] . ' ' . $diaNumeroReturn . ' de ' . $mesesAbrev[$mesInglesAbrev];
                $returnTime = date('H:i', strtotime($item['return_time'] ?? '00:00'));
            } catch (Exception $e) {
                $returnDateAbrev = 'Fecha no disponible';
                $returnTime = 'Hora no disponible';
            }
        }
    } catch (Exception $e) {
        $duration = $item['duration'] ?? 'N/A';
        $departureDateLong = 'Fecha no disponible';
        $arrivalDateLong = 'Fecha no disponible';
        $departureDateAbrev = 'Fecha no disponible';
        $arrivalDateAbrev = 'Fecha no disponible';
    }
@endphp

<div class="flight-card-unified">
    <!-- Header con códigos de aeropuertos -->
    <div class="flight-route-header">
        <span class="airport-route-text">Vuelo {{ ucwords(preg_replace('/\([^)]*\)/', '', strtolower($item['departure_city'] ?? ''))) }} → {{ ucwords(preg_replace('/\([^)]*\)/', '', strtolower($item['arrival_city'] ?? ''))) }}</span>
    </div>

    <!-- Trayecto principal -->
    <div class="flight-route-main">
        <!-- Bloque origen -->
        <div class="airport-section">
            <div class="airport-info">
                <div class="airport-date mobile-only">{{ $departureDateAbrev }}</div>
                <div class="airport-time mobile-only">{{ date('H:i', strtotime($item['departure_time'] ?? '00:00')) }}</div>
                <div class="airport-location mobile-only">{{ $item['departure_city'] ?? '' }}, {{ getCountryFromCity($item['departure_city'] ?? '') }}</div>
                <div class="airport-time-date desktop-only">
                    <span class="airport-time">{{ date('H:i', strtotime($item['departure_time'] ?? '00:00')) }}</span>
                    <span class="airport-date-separator"> - </span>
                    <span class="airport-date">{{ $departureDateLong }}</span>
                </div>
                <div class="airport-code">{{ preg_match('/\(([^)]+)\)/', $item['departure_airport_name'] ?? '', $matches) ? $matches[1] : $item['departure_airport'] ?? 'DEP' }}</div>
                <div class="airport-name">{{ ucfirst(strtolower($item['departure_airport_name'] ?? '')) }}</div>
                <div class="airport-location desktop-only">{{ $item['departure_city'] ?? '' }}, {{ getCountryFromCity($item['departure_city'] ?? '') }}</div>
            </div>
        </div>

        <!-- Conector de vuelo -->
        <div class="flight-connector">
            <div class="plane-container">
                <i class="fas fa-plane flight-plane" style="color: #000000;"></i>

            </div>
        </div>

        <!-- Bloque destino -->
        <div class="airport-section">
            <div class="airport-info arrival-info">
                <div class="airport-location mobile-only">{{ $item['arrival_city'] ?? '' }}, {{ getCountryFromCity($item['arrival_city'] ?? '') }}</div>
                <div class="airport-time-date desktop-only">
                    <span class="airport-time">{{ date('H:i', strtotime($item['arrival_time'] ?? '00:00')) }}</span>
                    <span class="airport-date-separator"> - </span>
                    <span class="airport-date">{{ $arrivalDateLong }}</span>
                </div>
                <div class="airport-code">{{ preg_match('/\(([^)]+)\)/', $item['arrival_airport_name'] ?? '', $matches) ? $matches[1] : $item['arrival_airport'] ?? 'ARR' }}</div>
                <div class="airport-name">{{ ucfirst(strtolower($item['arrival_airport_name'] ?? '')) }}</div>
                <div class="airport-location desktop-only">{{ $item['arrival_city'] ?? '' }}, {{ getCountryFromCity($item['arrival_city'] ?? '') }}</div>
            </div>
        </div>

        @if(isset($returnDateAbrev))
        <div class="return-section mobile-only">
            <div class="airport-date">{{ $returnDateAbrev }}</div>
            <div class="airport-time">{{ $returnTime }}</div>
            <div class="airport-location">{{ $item['arrival_city'] ?? '' }}, {{ getCountryFromCity($item['arrival_city'] ?? '') }}</div>
        </div>
        @endif
    </div>

    <!-- Información adicional -->
    <div class="flight-details-section">
        <div class="flight-meta">
            <span class="airline-info">{{ $item['airline'] ?? 'Aerolínea' }}</span>
            @if(isset($item['flight_number']) && $item['flight_number'])
                <span class="flight-number">{{ $item['flight_number'] }}</span>
            @endif
            @if(isset($item['layover_duration']) && $item['layover_duration'])
                <span class="layover-duration">Escala: {{ $item['layover_duration'] }}</span>
            @endif
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
