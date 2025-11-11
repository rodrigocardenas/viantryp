@extends('layouts.app')

@section('title', 'Viantryp - Vista Previa del Itinerario')

@section('content')
    <!-- Sticky Header that hides on scroll -->
    <div class="preview-sticky-header" id="previewStickyHeader">
        @auth
            <!-- Header for authenticated users -->
            <div class="header">
                <div class="header-content">
                    <div class="logo-container">
                        <a href="{{ route('trips.index') }}" class="viantryp-logo">
                            <i class="fas fa-route"></i>
                            Viantryp
                        </a>
                    </div>
                    <div class="header-right">
                        <div class="nav-actions">
                            <a href="{{ route('trips.edit', $trip->id) }}" class="btn btn-back">
                                <i class="fas fa-arrow-left"></i>
                                Volver
                            </a>
                            <button type="button" class="btn btn-share" onclick="shareTrip()">
                                <i class="fas fa-share-alt"></i>
                                Compartir
                            </button>
                            <button type="button" class="btn btn-pdf" onclick="downloadPDF()">
                                <i class="fas fa-file-pdf"></i>
                                Descarga versión PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Simple header for public preview -->
            <div class="public-preview-header">
                <div class="public-header-content">
                    <div class="public-logo">
                        <h1 class="public-logo-text">Viantryp</h1>
                        <p class="public-subtitle">Vista Previa del Itinerario</p>
                    </div>
                    <div class="public-actions">
                        <button onclick="downloadPDF()" class="public-btn-print">
                            <i class="fas fa-file-pdf"></i>
                            Descargar PDF
                        </button>
                    </div>
                </div>
            </div>
        @endauth
    </div>

    <!-- Main Container -->
    <div class="container">
        <header>
            <h1>{{ $trip->title ?? 'Viaje sin título' }}</h1>
            <div class="trip-code">{{ $trip->code ?? 'YATPKYEQTQ' }}</div>
            <div class="price-section">
                <span class="price-label">Precio Total</span>
                <span class="price-amount">{{ $trip->price ?? '0.00' }} USD $</span>
            </div>
        </header>

        @if(isset($trip) && $trip->items_data && count($trip->items_data) > 0)
            @php
                // Check for summary items
                $summaryItems = array_filter($trip->items_data, function($item) {
                    return isset($item['type']) && $item['type'] === 'summary';
                });
            @endphp

            @if(count($summaryItems) > 0)
                @foreach($summaryItems as $summaryItem)
                    <div class="summary-section">
                        <div class="summary-content">
                            <h3>{{ $summaryItem['title'] ?? 'Resumen del Itinerario' }}</h3>
                            <div class="summary-text">
                                {!! nl2br(e($summaryItem['content'] ?? $summaryItem['subtitle'] ?? '')) !!}
                            </div>
                        </div>
                    </div>

                @endforeach
            @endif

            @php
                // Group items by day (excluding summary items)
                $itemsByDay = [];
                foreach($trip->items_data as $item) {
                    if (isset($item['type']) && $item['type'] === 'summary') {
                        continue; // Skip summary items as they're already displayed
                    }
                    $day = $item['day'] ?? 1;
                    if (!isset($itemsByDay[$day])) {
                        $itemsByDay[$day] = [];
                    }
                    $itemsByDay[$day][] = $item;
                }
            @endphp
            @php
                // Group items by day
                $itemsByDay = [];
                foreach($trip->items_data as $item) {
                    $day = $item['day'] ?? 1;
                    if (!isset($itemsByDay[$day])) {
                        $itemsByDay[$day] = [];
                    }
                    $itemsByDay[$day][] = $item;
                }
            @endphp

            @foreach($itemsByDay as $dayNumber => $dayItems)
                <div class="day-section">
                    <div class="day-title">DIA {{ $dayNumber }}</div>

                    @if(count($dayItems) > 0)
                        @foreach($dayItems as $item)
                            @if($item['type'] === 'flight')
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
                                    } catch (Exception $e) {
                                        $duration = $item['duration'] ?? 'N/A';
                                        $departureDateLong = 'Fecha no disponible';
                                        $arrivalDateLong = 'Fecha no disponible';
                                    }
                                @endphp

                                <div class="flight-card-unified">
                                    <!-- Trayecto principal -->
                                    <div class="flight-route-main">
                                        <!-- Bloque origen -->
                                        <div class="airport-section">
                                            <div class="airport-info">
                                                <div class="airport-date">{{ $departureDateLong }}</div>
                                                <div class="airport-time">{{ date('H:i', strtotime($item['departure_time'] ?? '00:00')) }}</div>
                                                <div class="airport-code">{{ strtoupper($item['departure_airport'] ?? 'DEP') }}</div>
                                                <div class="airport-name">{{ ucfirst(strtolower($item['departure_airport_name'] ?? '')) }}</div>
                                                <div class="airport-location">{{ $item['departure_city'] ?? '' }}, {{ $item['departure_country'] ?? '' }}</div>
                                            </div>
                                        </div>

                                        <!-- Conector de vuelo -->
                                        <div class="flight-connector">
                                            <div class="plane-container">
                                                <i class="fas fa-plane flight-plane" style="color: #1e3a8a;"></i>
                                                
                                            </div>
                                        </div>

                                        <!-- Bloque destino -->
                                        <div class="airport-section">
                                            <div class="airport-info">
                                                <div class="airport-date">{{ $arrivalDateLong }}</div>
                                                <div class="airport-time">{{ date('H:i', strtotime($item['arrival_time'] ?? '00:00')) }}</div>
                                                <div class="airport-code">{{ strtoupper($item['arrival_airport'] ?? 'ARR') }}</div>
                                                <div class="airport-name">{{ ucfirst(strtolower($item['arrival_airport_name'] ?? '')) }}</div>
                                                <div class="airport-location">{{ $item['arrival_city'] ?? '' }}, {{ $item['arrival_country'] ?? '' }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Información adicional -->
                                    <div class="flight-details-section">
                                        <div class="flight-meta">
                                            <span class="airline-info">{{ $item['airline'] ?? 'Aerolínea' }}</span>
                                            @if(isset($item['layover_duration']) && $item['layover_duration'])
                                                <span class="layover-duration">Escala: {{ $item['layover_duration'] }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    @php
                                        $documents = $trip->documents->where('type', 'flight');
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
                            @elseif($item['type'] === 'hotel')
                                <div class="hotel-card">
                                    @php
                                        $detailedInfo = $item['detailed_info'] ?? null;
                                        $hotelName = $detailedInfo['name'] ?? $item['hotel_name'] ?? $item['title'] ?? 'Hotel';
                                        $hotelAddress = $detailedInfo['formatted_address'] ?? $item['location'] ?? '';
                                        $hotelRating = $detailedInfo['rating'] ?? null;
                                        $hotelWebsite = $detailedInfo['website'] ?? null;
                                        $hotelPhone = $detailedInfo['international_phone_number'] ?? null;
                                        $hotelPhotos = $detailedInfo['photos'] ?? [];
                                        $hotelId = $item['hotel_id'] ?? 'hotel-' . $loop->index;
                                    @endphp

                                    @if(count($hotelPhotos) > 0)
                                        <!-- Hotel Photo Carousel -->
                                        <div class="hotel-gallery" data-hotel-id="{{ $hotelId }}">
                                            <div class="hotel-gallery-track">
                                                @foreach(array_slice($hotelPhotos, 0, 8) as $index => $photo)
                                                    <div class="hotel-gallery-slide {{ $index === 0 ? 'active' : '' }}" onclick="showHotelGallery({{ json_encode(array_column($hotelPhotos, 'url')) }}, {{ $index }}, '{{ addslashes($hotelName) }}')">
                                                        <img src="{{ $photo['url'] }}" alt="Hotel photo {{ $index + 1 }}" class="hotel-gallery-image" loading="lazy">
                                                    </div>
                                                @endforeach
                                        </div>

                                            @if(count($hotelPhotos) > 1)
                                                <button class="hotel-gallery-btn hotel-gallery-prev" type="button">
                                                    <i class="fas fa-chevron-left"></i>
                                                </button>
                                                <button class="hotel-gallery-btn hotel-gallery-next" type="button">
                                                    <i class="fas fa-chevron-right"></i>
                                                </button>
                                                <div class="hotel-gallery-indicators">
                                                    @for($i = 0; $i < min(count($hotelPhotos), 8); $i++)
                                                        <span class="hotel-gallery-indicator {{ $i === 0 ? 'active' : '' }}" data-slide="{{ $i }}"></span>
                                                    @endfor
                                        </div>
                                            @endif
                                    </div>
                                    @else
                                        <!-- Fallback image if no photos available -->
                                        <img src="{{ $item['image'] ?? 'https://images.unsplash.com/photo-1555400038-63f5ba517a47?w=400&h=300&fit=crop' }}" alt="{{ $hotelName }}" class="hotel-fallback-image">
                                    @endif

                                    <div class="hotel-content">
                                        <div class="hotel-header">
                                            <h3 class="hotel-title">{{ $hotelName }}</h3>
                                            @if($hotelRating)
                                                <div class="hotel-rating-section">
                                                    <div class="hotel-rating">
                                                        @for($i = 0; $i < 5; $i++)
                                                            @if($i < floor($hotelRating))
                                                                <i class="fas fa-star star-filled"></i>
                                                            @elseif($i < $hotelRating)
                                                                <i class="fas fa-star-half-alt star-filled"></i>
                                                            @else
                                                                <i class="far fa-star star-empty"></i>
                                                            @endif
                                                        @endfor
                                                        <span class="rating-text">({{ number_format($hotelRating, 1) }})</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        @if($hotelAddress)
                                            <div class="hotel-address">
                                                <i class="fas fa-map-marker-alt"></i> {{ $hotelAddress }}
                                            </div>
                                        @endif

                                        <div class="hotel-details">
                                            <div class="hotel-check-dates">
                                                <span><i class="fas fa-calendar-alt"></i> Check-in: {{ $item['check_in'] ?? 'Fecha no especificada' }}</span>
                                                <span><i class="fas fa-calendar-check"></i> Check-out: {{ $item['check_out'] ?? 'Fecha no especificada' }}</span>
                                            </div>

                                            <div class="hotel-amenities">
                                                @if($item['room_type'])
                                                    <span class="amenity"><i class="fas fa-bed"></i> {{ $item['room_type'] }}</span>
                                                @endif
                                                @if($item['nights'])
                                                    <span class="amenity"><i class="fas fa-moon"></i> {{ $item['nights'] }} noches</span>
                                                @endif
                                            </div>

                                            @if($hotelWebsite || $hotelPhone)
                                                <div class="hotel-contact">
                                                    @if($hotelWebsite)
                                                        <a href="{{ $hotelWebsite }}" target="_blank" class="contact-link">
                                                            <i class="fas fa-globe"></i> Sitio web
                                                        </a>
                                                    @endif
                                                    @if($hotelPhone)
                                                        <a href="tel:{{ $hotelPhone }}" class="contact-link">
                                                            <i class="fas fa-phone"></i> {{ $hotelPhone }}
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    @php
                                        $documents = $trip->documents->where('type', 'hotel');
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
                            @elseif($item['type'] === 'activity')
                                <div class="activity-card">
                                    <img src="{{ $item['image'] ?? 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=300&h=300&fit=crop' }}" alt="{{ $item['title'] ?? 'Actividad' }}" class="activity-image">
                                    <div class="activity-content">
                                        <div style="display: flex; justify-content: space-between; align-items: start;">
                                            <div class="activity-title">{{ $item['title'] ?? 'Actividad' }}</div>
                                            <span class="badge oficial">OFICIAL</span>
                                        </div>
                                        <div class="activity-subtitle">{{ $item['subtitle'] ?? 'Descripción' }}</div>
                                        <div>
                                            <span class="badge"><i class="fas fa-globe"></i> Web</span>
                                            <span class="badge"><i class="fas fa-euro-sign"></i> {{ $item['price'] ?? '669.95' }}</span>
                                            <span class="badge"><i class="fas fa-map-marker-alt"></i> Ver dirección</span>
                                        </div>
                                        <div class="rating">
                                            @for($i = 0; $i < 5; $i++)
                                                <i class="fas fa-star" style="color: #ffc107;"></i>
                                            @endfor
                                            <span>{{ $item['reviews'] ?? '12' }} reseñas de Google</span>
                                        </div>
                                    </div>
                                </div>
                            @elseif($item['type'] === 'transport')
                                <div class="train-card">
                                    <div style="font-weight: 600; margin-bottom: 15px;">{{ $item['transport_type'] ?? 'Transporte no especificado' }}</div>
                                    <div class="train-route">
                                        <div class="train-point">
                                            <div class="train-label">Origen</div>
                                            <div class="train-time">{{ $item['pickup_time'] ?? 'Hora no disponible' }}</div>
                                            <div class="train-location">{{ $item['pickup_location'] ?? 'Ubicación no especificada' }}</div>
                                        </div>
                                        <div class="train-icon">
                                            <i class="fas fa-train" style="font-size: 24px;"></i>
                                            <div class="train-duration">{{ $item['duration'] ?? 'Duración no disponible' }}</div>
                                        </div>
                                        <div class="train-point">
                                            <div class="train-label">Destino</div>
                                            <div class="train-time">{{ $item['arrival_time'] ?? 'Hora no disponible' }}</div>
                                            <div class="train-location">{{ $item['destination'] ?? 'Ubicación no especificada' }}</div>
                                        </div>
                                    </div>
                                    @php
                                        $documents = $trip->documents->where('type', 'transport');
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
                            @else
                                <div class="activity-card">
                                    <div class="activity-content">
                                        <div class="activity-title">{{ $item['title'] ?? 'Elemento' }}</div>
                                        <div class="activity-subtitle">{{ $item['subtitle'] ?? '' }}</div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="activity-card">
                            <div class="activity-content">
                                <div class="activity-title">Día libre</div>
                                <div class="activity-subtitle">No hay actividades programadas para este día</div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <div class="day-section">
                <div class="activity-card">
                    <div class="activity-content">
                        <div class="activity-title">No hay días en el itinerario</div>
                        <div class="activity-subtitle">Agrega días y elementos a tu viaje en el editor.</div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Contact Button -->
    <div class="contact-button minimized" id="contactButton">
        <div class="contact-icon">
            <i class="fas fa-headset"></i>
        </div>
        <div class="contact-text">
            <div class="contact-title">Habla con tu Agente</div>
            <div class="contact-details">
                <div class="contact-detail">
                    <i class="fas fa-user" style="color: #666;"></i>
                    <strong>Camilo Gutierrez</strong>
                </div>
                <div class="contact-detail">
                    <i class="fas fa-phone" style="color: #666;"></i>
                    +34 657 22 18 38
                </div>
                <div class="contact-detail">
                    <i class="fas fa-envelope" style="color: #666;"></i>
                    info@viajesgps.com
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        background-color: #f5f5f5;
        color: #333;
        line-height: 1.6;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    header {
        background: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    h1 {
        font-size: 28px;
        margin-bottom: 5px;
    }

    .trip-code {
        color: #666;
        font-size: 14px;
        margin-bottom: 15px;
    }

    .price-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-top: 1px solid #e0e0e0;
        border-bottom: 1px solid #e0e0e0;
    }

    .price-label {
        font-size: 14px;
        color: #666;
    }

    .price-amount {
        font-size: 20px;
        font-weight: bold;
    }

    .help-section {
        background: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .help-title {
        font-size: 14px;
        margin-bottom: 15px;
        color: #666;
    }

    .contact-info {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .contact-info strong {
        font-size: 16px;
    }

    .contact-detail {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
        color: #666;
        font-size: 14px;
    }

    .day-section {
        background: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .day-title {
        font-size: 13px;
        font-weight: normal;
        margin-bottom: 20px;
        color: #0066cc;
        background: none;
    }

    .day-date {
        font-size: 14px;
        font-weight: normal;
        color: #666;
        background: none;
        box-shadow: none;
    }

    .flight-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .flight-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .flight-route {
        font-size: 16px;
        font-weight: 600;
    }

    .airline-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .airline-logo {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #ff6b6b, #ee5a6f);
        border-radius: 4px;
    }

    .flight-details {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        gap: 30px;
        align-items: center;
        margin-top: 20px;
    }

    .flight-point {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
    }

    .flight-label {
        font-size: 12px;
        color: #666;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 8px;
        letter-spacing: 0.5px;
    }

    .airport-code {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 5px;
        color: #1a1a1a;
    }

    .city-name {
        font-size: 13px;
        color: #666;
        margin-bottom: 8px;
    }

    .flight-time {
        font-size: 14px;
        color: #1a1a1a;
        margin-top: 5px;
        font-weight: 500;
    }

    .flight-icon {
        text-align: center;
    }

    .flight-duration {
        font-size: 12px;
        color: #999;
        text-align: center;
    }

    .activity-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 15px;
        display: flex;
        gap: 15px;
    }

    .activity-image {
        width: 150px;
        height: 150px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .activity-content {
        padding: 15px;
        flex: 1;
    }

    .activity-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .activity-subtitle {
        font-size: 14px;
        color: #666;
        margin-bottom: 10px;
    }

    .activity-time {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
        font-size: 14px;
        color: #333;
    }

    .badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 12px;
        margin-right: 5px;
        margin-bottom: 5px;
        border: 1px solid #ddd;
        background: white;
    }

    .badge.oficial {
        background: #e3f2fd;
        color: #1976d2;
        border-color: #1976d2;
    }

    .amenities {
        display: flex;
        gap: 15px;
        margin-top: 10px;
        font-size: 13px;
        color: #666;
    }

    .stars {
        color: #ffc107;
        margin-right: 5px;
    }

    .rating {
        font-size: 13px;
        color: #666;
    }

    .train-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
    }

    .train-route {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        gap: 30px;
        align-items: center;
    }

    .train-point {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
    }

    .train-label {
        font-size: 12px;
        color: #666;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 8px;
        letter-spacing: 0.5px;
    }

    .train-time {
        font-size: 14px;
        color: #1a1a1a;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .train-location {
        font-size: 16px;
        font-weight: 600;
        color: #1a1a1a;
    }

    .train-icon {
        text-align: center;
        padding: 10px;
        color: #1a1a1a;
    }

    .train-duration {
        font-size: 12px;
        color: #999;
    }

    .contact-button {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: #0066cc;
        border: none;
        border-radius: 16px;
        padding: 1rem 1.5rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1), 0 4px 10px rgba(0, 0, 0, 0.06);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1000;
        backdrop-filter: blur(10px);
        max-width: 300px;
    }

    .contact-button.minimized {
        padding: 1rem;
        max-width: 60px;
        justify-content: center;
    }

    .contact-button.minimized .contact-text {
        display: none;
    }

    .contact-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15), 0 8px 16px rgba(0, 0, 0, 0.1);
        background: #0052a3;
    }

    .contact-icon {
        width: 44px;
        height: 44px;
        background: #0066cc;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        flex-shrink: 0;
    }

    .contact-text {
        text-align: left;
        color: white;
        min-width: 0;
        flex: 1;
    }

    .contact-title {
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .contact-details {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .contact-detail {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .contact-detail i {
        width: 16px;
        text-align: center;
        flex-shrink: 0;
    }

    /* Floating decorative shapes */
    .floating-shapes {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
        z-index: -1;
        overflow: hidden;
    }

    .shape {
        position: absolute;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.1), rgba(16, 185, 129, 0.1));
        animation: float 20s ease-in-out infinite;
    }

    .shape:nth-child(1) {
        width: 80px;
        height: 80px;
        top: 10%;
        left: 10%;
        animation-delay: 0s;
    }

    .shape:nth-child(2) {
        width: 60px;
        height: 60px;
        top: 60%;
        right: 15%;
        animation-delay: 5s;
    }

    .shape:nth-child(3) {
        width: 100px;
        height: 100px;
        bottom: 20%;
        left: 20%;
        animation-delay: 10s;
    }

    .shape:nth-child(4) {
        width: 40px;
        height: 40px;
        top: 30%;
        right: 30%;
        animation-delay: 15s;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        25% { transform: translateY(-20px) rotate(90deg); }
        50% { transform: translateY(-10px) rotate(180deg); }
        75% { transform: translateY(-30px) rotate(270deg); }
    }

    /* Header styles */
    .preview-sticky-header {
        position: sticky;
        top: 0;
        z-index: 1000;
        transition: transform 0.3s ease;
    }

    .preview-sticky-header.hidden {
        transform: translateY(-100%);
    }

    .header {
        background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 60%, #93c5fd 100%);
        color: white;
        padding: 1.25rem 2rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        height: 80px;
        display: flex;
        align-items: center;
    }

    .header-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .viantryp-logo {
        color: white;
        text-decoration: none;
        font-size: 1.5rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .viantryp-logo i {
        font-size: 1.8rem;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .nav-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .btn {
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid transparent;
        cursor: pointer;
        font-size: 0.875rem;
        font-family: 'Inter', sans-serif;
        letter-spacing: 0.025em;
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.4);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-share {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }

    .btn-share:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.4);
    }

    .btn-pdf {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }

    .btn-pdf:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(239, 68, 68, 0.4);
    }

    .public-preview-header {
        background: linear-gradient(135deg, #1f2a44 0%, #0ea5e9 100%);
        color: white;
        padding: 1.5rem 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .public-header-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .public-logo {
        display: flex;
        flex-direction: column;
    }

    .public-logo-text {
        font-size: 2rem;
        font-weight: bold;
        margin: 0;
        background: linear-gradient(45deg, #fff, #e0f2fe);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .public-subtitle {
        font-size: 0.9rem;
        margin: 0.25rem 0 0 0;
        opacity: 0.9;
        font-weight: 300;
    }

    .public-actions {
        display: flex;
        gap: 1rem;
    }

    .public-btn-print {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.25);
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        font-size: 0.875rem;
        font-family: 'Inter', sans-serif;
        backdrop-filter: blur(10px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .public-btn-print:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.4);
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }

    @media (max-width: 768px) {
        .container {
            padding: 10px;
        }

        h1 {
            font-size: 24px;
        }

        .flight-details {
            grid-template-columns: 1fr;
            gap: 15px;
        }



        .airport-code {
            font-size: 20px;
        }

        .activity-card {
            flex-direction: column;
        }

        .activity-image {
            width: 100%;
            height: 200px;
        }

        .train-route {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .train-icon {
            transform: rotate(90deg);
            padding: 15px 0;
        }

        .contact-detail {
            font-size: 0.75rem;
        }

        .contact-detail i {
            width: 14px;
        }

        .contact-title {
            font-size: 0.85rem;
        }

        .price-section {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .header-content {
            padding: 0 1rem;
            flex-direction: column;
            gap: 1rem;
        }

        .nav-actions {
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
            border-radius: 8px;
        }

        .public-header-content {
            padding: 0 1rem;
            flex-direction: column;
            gap: 1rem;
        }

        .public-logo-text {
            font-size: 1.5rem;
        }

        .public-subtitle {
            font-size: 0.8rem;
        }

        .public-actions {
            width: 100%;
            justify-content: center;
        }

        .contact-button {
            bottom: 1rem;
            right: 1rem;
            padding: 0.75rem 1rem;
        }

        .contact-text {
            display: none;
        }

        .floating-shapes {
            display: none;
        }
    }
</style>
<style>
    :root {
        --primary-dark: #1f2a44;
        --primary-blue: #0ea5e9;
        --light-blue: #e0f2fe;
        --coral: #FF6B6B;
        --mint: #22c55e;
        --gold: #fbbf24;
        --purple: #a78bfa;
        --orange: #fb923c;
        --light-gray: #F6F9FC;
        --white: #FFFFFF;
        --shadow: rgba(0, 0, 0, 0.08);
        --text-gray: #64748b;
        --border-gray: #e2e8f0;
        --shadow-soft: 0 10px 30px rgba(0,0,0,0.06);
        --shadow-hover: 0 14px 40px rgba(0,0,0,0.08);
        --radius: 16px;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background:
            radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.1) 0%, transparent 50%),
            linear-gradient(180deg, #e6f3fb 0%, #f7fbff 40%, #ffffff 100%);
        min-height: 100vh;
        color: var(--primary-dark);
        line-height: 1.6;
        letter-spacing: 0.1px;
        position: relative;
        overflow-x: hidden;
    }

    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image:
            radial-gradient(circle at 25% 25%, rgba(14, 165, 233, 0.03) 0%, transparent 50%),
            radial-gradient(circle at 75% 75%, rgba(16, 185, 129, 0.03) 0%, transparent 50%);
        pointer-events: none;
        z-index: -1;
    }

    /* Floating decorative elements */
    .floating-shapes {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
        z-index: -1;
        overflow: hidden;
    }

    .shape {
        position: absolute;
        border-radius: 50%;
        animation: float 20s ease-in-out infinite;
    }

    .shape:nth-child(1) {
        width: 80px;
        height: 80px;
        top: 10%;
        left: 10%;
        background: #0066cc;
        animation-delay: 0s;
    }

    .shape:nth-child(2) {
        width: 60px;
        height: 60px;
        top: 60%;
        right: 15%;
        background: #0066cc;
        animation-delay: 5s;
    }

    .shape:nth-child(3) {
        width: 100px;
        height: 100px;
        bottom: 20%;
        left: 20%;
        background: #0066cc;
        animation-delay: 10s;
    }

    .shape:nth-child(4) {
        width: 40px;
        height: 40px;
        top: 30%;
        right: 30%;
        background: #0066cc;
        animation-delay: 15s;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        25% { transform: translateY(-20px) rotate(90deg); }
        50% { transform: translateY(-10px) rotate(180deg); }
        75% { transform: translateY(-30px) rotate(270deg); }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .trip-info-card {
        animation: fadeInUp 0.8s ease-out;
    }

    .day-section {
        animation: fadeInUp 0.6s ease-out;
        animation-fill-mode: both;
    }

    .day-section:nth-child(1) { animation-delay: 0.2s; }
    .day-section:nth-child(2) { animation-delay: 0.4s; }
    .day-section:nth-child(3) { animation-delay: 0.6s; }
    .day-section:nth-child(4) { animation-delay: 0.8s; }
    .day-section:nth-child(5) { animation-delay: 1s; }

    .timeline-item {
        animation: fadeIn 0.5s ease-out;
        animation-fill-mode: both;
    }

    .contact-button {
        animation: fadeInUp 0.8s ease-out 1.2s;
        animation-fill-mode: both;
    }

    .header {
        background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 60%, #93c5fd 100%);
        box-shadow: var(--shadow-soft);
        padding: 1.25rem 2rem;
        color: white;
        height: 80px;
        display: flex;
        align-items: center;
    }

    .header-content {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .logo {
        font-size: 1.8rem;
        font-weight: 800;
        color: white;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 0.5rem;
        border-radius: 10px;
    }

    .logo:hover { background: rgba(255,255,255,0.15); transform: translateY(-1px); }

    .logo i { color: #ffffff; }

    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 999px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        font-size: 0.9rem;
        white-space: nowrap;
        box-shadow: var(--shadow-soft);
    }

    .btn:hover { transform: translateY(-1px); box-shadow: var(--shadow-hover); }

    .btn-back { background: var(--light-gray); color: var(--primary-dark); border: 1px solid var(--border-gray); }

    .btn-back:hover { background: var(--primary-blue); color: white; border-color: var(--primary-blue); }

    .btn-primary { background: var(--primary-blue); color: white; }

    .btn-secondary { background: var(--light-gray); color: var(--primary-dark); }

    .main-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    .trip-info-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(248,250,252,0.95) 100%);
        border-radius: var(--radius);
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-soft);
        border: 1px solid rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        position: relative;
        overflow: hidden;
    }

    .trip-info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-blue), var(--mint), var(--gold), var(--purple));
        border-radius: var(--radius) var(--radius) 0 0;
    }

    .trip-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2rem;
        position: relative;
    }

    .trip-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 50%, #93c5fd 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        box-shadow: 0 8px 25px rgba(14, 165, 233, 0.3);
        position: relative;
        z-index: 2;
        transition: all 0.3s ease;
    }

    .trip-icon:hover {
        transform: scale(1.05) rotate(5deg);
        box-shadow: 0 12px 35px rgba(14, 165, 233, 0.4);
    }

    .trip-icon::before {
        content: '';
        position: absolute;
        inset: -2px;
        background: linear-gradient(135deg, #0ea5e9, #38bdf8, #93c5fd);
        border-radius: 22px;
        z-index: -1;
        opacity: 0.3;
    }

    .trip-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--primary-dark);
        margin-bottom: 0.75rem;
        background: linear-gradient(135deg, var(--primary-dark) 0%, #374151 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1.2;
        letter-spacing: -0.02em;
    }

    .trip-subtitle {
        color: var(--text-gray);
        font-size: 1.1rem;
        margin: 0.5rem 0 0.75rem 0;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .trip-subtitle::before {
        content: '📅';
        font-size: 1.2rem;
    }

    .trip-author {
        color: var(--text-gray);
        font-size: 0.95rem;
        margin: 0.5rem 0 0 0;
        font-style: italic;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        opacity: 0.8;
    }

    .trip-author::before {
        content: '👤';
        font-size: 1rem;
    }

    .timeline-container {
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(248,250,252,0.1) 100%);
        position: relative;
        border-radius: var(--radius);
        padding: 1rem;
        backdrop-filter: blur(5px);
    }

    .timeline {
        position: relative;
        padding-left: 4rem;
        display: flex;
        flex-direction: column;
        gap: 2.5rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 2rem;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(to bottom, var(--primary-blue), var(--mint), var(--gold), var(--purple));
        border-radius: 2px;
        box-shadow: 0 0 20px rgba(14, 165, 233, 0.3);
    }

    .day-section {
        position: relative;
        background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(248,250,252,0.95) 100%);
        border-radius: var(--radius);
        padding: 2.5rem;
        box-shadow: var(--shadow-soft);
        border: 1px solid rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .day-section:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.1);
    }

    .day-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-blue), var(--mint), var(--gold));
        opacity: 0.7;
    }

    .day-marker {
        position: absolute;
        left: -3rem;
        top: 1.5rem;
        width: 3rem;
        height: 3rem;
        background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 50%, #93c5fd 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 800;
        font-size: 1.1rem;
        z-index: 2;
        box-shadow: 0 6px 20px rgba(14, 165, 233, 0.4);
        border: 3px solid rgba(255,255,255,0.3);
        transition: all 0.3s ease;
    }

    .day-marker:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 25px rgba(14, 165, 233, 0.5);
    }

    .day-header {
        margin-bottom: 2rem;
        padding-left: 1rem;
        position: relative;
    }

    .day-date {
        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
        color: var(--primary-dark);
        padding: 0.75rem 1.5rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-block;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(14, 165, 233, 0.2);
        border: 1px solid rgba(14, 165, 233, 0.1);
        transition: all 0.3s ease;
    }

    .day-date:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
    }

    .day-title {
        font-size: 1.5rem;
        font-weight: 500;
        color: var(--primary-dark);
        margin-bottom: 0.75rem;
        background: linear-gradient(135deg, var(--primary-dark) 0%, #374151 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1.3;
    }

    .day-subtitle {
        color: var(--text-gray);
        font-size: 1.1rem;
        font-weight: 500;
        opacity: 0.9;
    }

    .timeline-items {
        padding-left: 1rem;
    }

    .timeline-item {
        background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(248,250,252,0.95) 100%);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 20px;
        margin-bottom: 2rem;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
        position: relative;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--primary-blue), var(--mint), var(--gold));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .timeline-item:hover {
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12), 0 8px 20px rgba(0, 0, 0, 0.08);
        transform: translateY(-4px) scale(1.02);
    }

    .timeline-item:hover::before {
        opacity: 1;
    }

    .item-header {
        padding: 1.75rem;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
    }

    .item-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        position: relative;
        transition: all 0.3s ease;
        border: 2px solid rgba(255,255,255,0.2);
    }

    .item-icon::before {
        content: '';
        position: absolute;
        inset: -2px;
        background: inherit;
        border-radius: 18px;
        z-index: -1;
        opacity: 0.3;
    }

    .item-icon:hover {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.25);
    }

    .icon-flight {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 50%, #1e40af 100%);
    }

    .icon-hotel {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 50%, #b45309 100%);
    }

    .icon-activity {
        background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
    }

    .icon-transport {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 50%, #6d28d9 100%);
    }

    .icon-note {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 50%, #374151 100%);
    }

    .item-info {
        flex: 1;
    }

    .item-type {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #6b7280;
        margin-bottom: 0.5rem;
        font-family: 'Inter', sans-serif;
    }

    .item-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.25rem;
        line-height: 1.4;
    }

    .item-subtitle {
        color: #6b7280;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .item-toggle {
        background: rgba(107, 114, 128, 0.1);
        border: 1px solid rgba(107, 114, 128, 0.2);
        color: #6b7280;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 8px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-family: 'Inter', sans-serif;
    }

    .item-toggle:hover {
        background: rgba(107, 114, 128, 0.15);
        border-color: rgba(107, 114, 128, 0.3);
        color: #374151;
        transform: scale(1.05);
    }

    .item-content { padding: 1.5rem; background: var(--light-gray); }

    .flight-details {
        background: var(--white);
        border-radius: 12px;
        padding: 2rem;
        border: 1px solid var(--border-gray);
    }

    .flight-route {
        display: flex;
        align-items: center;
        gap: 2rem;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--border-gray);
    }

    .flight-segment {
        flex: 1;
        text-align: center;
    }

    .flight-time {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 0.5rem;
    }

    .flight-airport {
        font-size: 0.9rem;
        color: var(--text-gray);
        line-height: 1.4;
    }

    .flight-path {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 120px;
    }

    .flight-line {
        position: absolute;
        width: 100%;
        height: 2px;
        background: var(--border-gray);
        border-radius: 1px;
    }

    .flight-plane {
        position: absolute;
        left: 0;
        font-size: 1.2rem;
        background: var(--white);
        padding: 0.25rem;
        border-radius: 50%;
    }

    .flight-destination {
        position: absolute;
        right: 0;
        font-size: 1.2rem;
        background: var(--white);
        padding: 0.25rem;
        border-radius: 50%;
    }

    .flight-sections {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .flight-section {
        border-bottom: 1px solid var(--border-gray);
        padding-bottom: 1.5rem;
    }

    .flight-section:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 1rem;
    }

    .reservation-details {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .reservation-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .reservation-label {
        font-weight: 600;
        color: var(--primary-dark);
        min-width: 120px;
    }

    .reservation-value {
        color: var(--text-gray);
    }

    .baggage-link {
        color: var(--primary-blue);
        text-decoration: none;
        font-weight: 500;
    }

    .baggage-link:hover {
        text-decoration: underline;
    }

    .additional-details {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .document-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .document-link i {
        color: var(--text-gray);
        font-size: 0.9rem;
    }

    .document-link a {
        color: var(--primary-blue);
        text-decoration: none;
        font-weight: 500;
    }

    .document-link a:hover {
        text-decoration: underline;
    }

    .item-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .detail-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background: var(--white);
        border-radius: 8px;
        border: 1px solid var(--border-gray);
    }

    .detail-icon-small {
        width: 24px;
        height: 24px;
        background: var(--light-blue);
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-blue);
        font-size: 0.8rem;
    }

    .detail-text-small {
        flex: 1;
    }

    .detail-label-small {
        font-size: 0.8rem;
        color: var(--text-gray);
        margin-bottom: 0.1rem;
    }

    .detail-value-small {
        font-weight: 600;
        color: var(--primary-dark);
        font-size: 0.9rem;
    }

    /* Hotel Card Styles */
    .hotel-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(248,250,252,0.95) 100%);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 20px;
        margin-bottom: 2rem;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
        position: relative;
    }

    .hotel-card:hover {
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12), 0 8px 20px rgba(0, 0, 0, 0.08);
        transform: translateY(-4px) scale(1.02);
    }

    .hotel-fallback-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-radius: 20px 20px 0 0;
    }

    .hotel-content {
        padding: 1.75rem;
    }

    .hotel-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        gap: 1rem;
    }

    .hotel-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin: 0;
        flex: 1;
    }

    .hotel-rating-section {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .hotel-rating {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .hotel-rating .star-filled {
        color: #fbbf24;
        font-size: 1rem;
    }

    .hotel-rating .star-empty {
        color: #e5e7eb;
        font-size: 1rem;
    }

    .hotel-rating .rating-text {
        font-size: 0.9rem;
        color: var(--text-gray);
        margin-left: 0.25rem;
    }

    .hotel-address {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-gray);
        font-size: 0.95rem;
        margin-bottom: 1rem;
    }

    .hotel-details {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .hotel-check-dates {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: var(--primary-dark);
    }

    .hotel-check-dates span {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .hotel-amenities {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .hotel-amenities .amenity {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: var(--text-gray);
        background: rgba(59, 130, 246, 0.1);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        border: 1px solid rgba(59, 130, 246, 0.2);
    }

    .hotel-contact {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .hotel-contact .contact-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: var(--primary-blue);
        text-decoration: none;
        background: rgba(59, 130, 246, 0.1);
        padding: 0.5rem 1rem;
        border-radius: 25px;
        border: 1px solid rgba(59, 130, 246, 0.2);
        transition: all 0.3s ease;
    }

    .hotel-contact .contact-link:hover {
        background: var(--primary-blue);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    /* Hotel Gallery Carousel Styles */
    .hotel-gallery {
        position: relative;
        width: 100%;
        height: 300px;
        overflow: hidden;
        border-radius: 20px 20px 0 0;
        background: #f8fafc;
    }

    .hotel-gallery-track {
        display: flex;
        width: 100%;
        height: 100%;
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .hotel-gallery-slide {
        flex: 0 0 100%;
        height: 100%;
        position: relative;
        opacity: 0;
        transition: opacity 0.5s ease;
    }

    .hotel-gallery-slide.active {
        opacity: 1;
    }

    .hotel-gallery-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 20px 20px 0 0;
    }

    .hotel-gallery-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.9);
        border: none;
        border-radius: 50%;
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 10;
    }

    .hotel-gallery-btn:hover {
        background: white;
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    }

    .hotel-gallery-prev {
        left: 1rem;
    }

    .hotel-gallery-next {
        right: 1rem;
    }

    .hotel-gallery-indicators {
        position: absolute;
        bottom: 1rem;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 0.5rem;
        z-index: 10;
    }

    .hotel-gallery-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .hotel-gallery-indicator.active {
        background: white;
        transform: scale(1.2);
    }

    .hotel-gallery-indicator:hover {
        background: rgba(255, 255, 255, 0.8);
    }

    .contact-button {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(229, 231, 235, 0.8);
        border-radius: 16px;
        padding: 1rem 1.5rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1), 0 4px 10px rgba(0, 0, 0, 0.06);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1000;
        backdrop-filter: blur(10px);
    }

    .contact-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15), 0 8px 16px rgba(0, 0, 0, 0.1);
        background: rgba(255, 255, 255, 1);
    }

    .contact-icon {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .contact-text {
        text-align: left;
    }

    .contact-title {
        font-weight: 600;
        color: var(--primary-dark);
        font-size: 0.9rem;
    }

    .contact-subtitle {
        color: var(--text-gray);
        font-size: 0.8rem;
    }

    .loading {
        text-align: center;
        padding: 3rem;
        color: var(--text-gray);
    }

    .loading i {
        font-size: 2rem;
        margin-bottom: 1rem;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .error {
        text-align: center;
        padding: 3rem;
        color: var(--coral);
    }

    .error i {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    /* Trip Summary Styles */
    .trip-summary {
        background: linear-gradient(135deg, rgba(240,249,255,0.8) 0%, rgba(219,234,254,0.8) 100%);
        border-radius: 16px;
        padding: 2rem;
        margin: 1.5rem 0;
        border-left: 5px solid var(--primary-blue);
        box-shadow: 0 4px 16px rgba(14, 165, 233, 0.1);
        backdrop-filter: blur(5px);
        position: relative;
        overflow: hidden;
    }

    .trip-summary::before {
        content: '💡';
        position: absolute;
        top: 1rem;
        right: 1.5rem;
        font-size: 2rem;
        opacity: 0.3;
    }

    .summary-content {
        font-size: 1rem;
        line-height: 1.7;
        color: var(--primary-dark);
        position: relative;
        z-index: 1;
    }

    .summary-content strong {
        color: var(--primary-blue);
        font-weight: 700;
        background: linear-gradient(135deg, var(--primary-blue), var(--blue-600));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .summary-content br {
        margin-bottom: 0.75rem;
    }

    .summary-content em {
        color: var(--text-gray);
        font-style: italic;
        font-weight: 500;
    }

    /* Sticky Header Styles */
    .preview-sticky-header {
        position: sticky;
        top: 0;
        z-index: 1000;
        transition: transform 0.3s ease;
    }

    .preview-sticky-header.hidden {
        transform: translateY(-100%);
    }

    /* Header for authenticated users */
    .header {
        background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 60%, #93c5fd 100%);
        color: white;
        padding: 1.25rem 2rem;
        box-shadow: var(--shadow-soft);
        height: 80px;
        display: flex;
        align-items: center;
    }

    .header-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .viantryp-logo {
        color: white;
        text-decoration: none;
        font-size: 1.5rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .viantryp-logo i {
        font-size: 1.8rem;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .nav-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .btn {
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid transparent;
        cursor: pointer;
        font-size: 0.875rem;
        font-family: 'Inter', sans-serif;
        letter-spacing: 0.025em;
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.4);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-save {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }

    .btn-save:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.4);
    }

    .btn-preview {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
    }

    .btn-preview:hover {
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(245, 158, 11, 0.4);
    }

    .btn-pdf {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }

    .btn-pdf:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(239, 68, 68, 0.4);
    }

    .btn-share {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }

    .btn-share:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.4);
    }

    .auth-section {
        display: flex;
        align-items: center;
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: rgba(255, 255, 255, 0.1);
        padding: 0.5rem 1rem;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .user-avatar-placeholder {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.9rem;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .user-name {
        color: white;
        font-weight: 500;
        font-size: 0.9rem;
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .btn-logout {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        border: 1px solid rgba(239, 68, 68, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 0.375rem;
        font-family: 'Inter', sans-serif;
    }

    .btn-logout:hover {
        background: rgba(239, 68, 68, 0.15);
        border-color: rgba(239, 68, 68, 0.3);
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);
    }

    /* Public Preview Header Styles */
    .public-preview-header {
        background: linear-gradient(135deg, #1f2a44 0%, #0ea5e9 100%);
        color: white;
        padding: 1.5rem 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .public-header-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .public-logo {
        display: flex;
        flex-direction: column;
    }

    .public-logo-text {
        font-size: 2rem;
        font-weight: bold;
        margin: 0;
        background: linear-gradient(45deg, #fff, #e0f2fe);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .public-subtitle {
        font-size: 0.9rem;
        margin: 0.25rem 0 0 0;
        opacity: 0.9;
        font-weight: 300;
    }

    .public-actions {
        display: flex;
        gap: 1rem;
    }

    .public-btn-print {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.25);
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        font-size: 0.875rem;
        font-family: 'Inter', sans-serif;
        backdrop-filter: blur(10px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .public-btn-print:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.4);
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }

    @media (max-width: 768px) {
        .header-content {
            padding: 0 1rem;
            flex-direction: column;
            gap: 1rem;
        }

        .nav-actions {
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
            border-radius: 8px;
        }

        .user-profile {
            padding: 0.4rem 0.8rem;
        }

        .user-name {
            display: none;
        }

        .btn-logout {
            padding: 0.3rem 0.6rem;
            font-size: 0.75rem;
        }

        .main-container {
            padding: 1rem;
        }

        .trip-info-card {
            padding: 1.5rem;
        }

        .trip-icon {
            width: 60px;
            height: 60px;
            font-size: 1.8rem;
        }

        .trip-title {
            font-size: 2rem;
        }

        .timeline {
            padding-left: 3rem;
        }

        .day-marker {
            left: -2rem;
            width: 2rem;
            height: 2rem;
            font-size: 0.9rem;
        }

        .day-section {
            padding: 1.5rem;
        }

        .day-title {
            font-size: 1.4rem;
        }

        .item-icon {
            width: 48px;
            height: 48px;
            font-size: 1.2rem;
        }

        .item-details {
            grid-template-columns: 1fr;
        }

        .contact-button {
            bottom: 1rem;
            right: 1rem;
            padding: 0.75rem 1rem;
        }

        .contact-text {
            display: none;
        }

        .floating-shapes {
            display: none;
        }

        .public-header-content {
            padding: 0 1rem;
            flex-direction: column;
            gap: 1rem;
        }

        .public-logo-text {
            font-size: 1.5rem;
        }

        .public-subtitle {
            font-size: 0.8rem;
        }

        .public-actions {
            width: 100%;
            justify-content: center;
        }
    }

    /* Unified Flight Card Styles */
    .flight-card-unified {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        transition: all 0.2s ease;
    }

    .flight-card-unified:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        transform: translateY(-1px);
    }

    .flight-route-main {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 16px;
        gap: 12px;
    }

    .airport-section {
        flex: 1;
        text-align: center;
        position: relative;
    }

    .time-display {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        font-size: 0.875rem;
        font-weight: 700;
        color: #374151;
        background: #f3f4f6;
        padding: 6px 12px;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
        margin-bottom: 8px;
    }

    .time-icon {
        font-size: 0.75rem;
        color: #6b7280;
    }

    .airport-info {
        text-align: center;
    }

    .airport-date {
        font-size: 0.75rem;
        color: #6b7280;
        font-weight: 500;
        margin-bottom: 4px;
    }

    .airport-time {
        font-size: 0.875rem;
        font-weight: 700;
        color: #374151;
        margin-bottom: 8px;
    }

    .airport-code {
        font-size: 1.25rem;
        font-weight: 800;
        color: #111827;
        margin-bottom: 4px;
        letter-spacing: 1px;
    }

    .airport-name {
        font-size: 0.875rem;
        color: #374151;
        font-weight: 500;
        margin-bottom: 2px;
        line-height: 1.3;
    }

    .airport-location {
        font-size: 0.75rem;
        color: #6b7280;
        font-weight: 400;
    }

    .flight-connector {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 16px;
        position: relative;
    }

    .plane-container {
        text-align: center;
    }

    .flight-plane {
        font-size: 1.25rem;
        color: #dc2626;
        margin-bottom: 4px;
        display: block;
    }

    .flight-duration {
        font-size: 0.625rem;
        color: #6b7280;
        font-weight: 600;
        background: #f9fafb;
        padding: 3px 8px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .flight-number-below {
        font-size: 0.75rem;
        font-weight: 700;
        color: #dc2626;
        background: white;
        padding: 2px 6px;
        border-radius: 4px;
        border: 1px solid #dc2626;
        margin-top: 4px;
        text-align: center;
        white-space: nowrap;
    }

    .flight-details-section {
        border-top: 1px solid #f3f4f6;
        padding-top: 12px;
    }

    .flight-dates {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-bottom: 8px;
        font-size: 0.75rem;
        color: #6b7280;
    }

    .departure-date,
    .arrival-date {
        font-weight: 500;
    }

    .date-arrow {
        color: #dc2626;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .flight-meta {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .airline-info {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
    }

    .flight-number {
        font-size: 0.75rem;
        color: white;
        background: #dc2626;
        padding: 2px 8px;
        border-radius: 4px;
        font-weight: 600;
    }

    .layover-duration {
        font-size: 0.75rem;
        color: #dc2626;
        font-weight: 600;
    }

    /* Documents section styles */
    .documents-section {
        margin-top: 15px;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 6px;
        border: 1px solid #e9ecef;
    }

    .documents-section h5 {
        font-size: 14px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
        margin-top: 0;
    }

    .document-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        margin: 2px 4px 2px 0;
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        text-decoration: none;
        color: #007bff;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .document-link:hover {
        background-color: #f8f9fa;
        border-color: #007bff;
        color: #0056b3;
        text-decoration: none;
    }

    .document-link i {
        font-size: 12px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .flight-card-unified {
            padding: 12px;
        }

        .flight-route-main {
            flex-direction: column;
            gap: 16px;
        }

        .airport-section {
            display: flex;
            align-items: center;
            gap: 12px;
            text-align: left;
        }

        .airport-info {
            text-align: left;
            flex: 1;
        }

        .airport-date {
            font-size: 0.7rem;
            margin-bottom: 2px;
        }

        .airport-time {
            font-size: 0.8rem;
            margin-bottom: 6px;
        }

        .airport-code {
            font-size: 1.1rem;
            margin-bottom: 2px;
        }

        .airport-name {
            font-size: 0.8rem;
            margin-bottom: 1px;
        }

        .airport-location {
            font-size: 0.7rem;
        }

        .flight-connector {
            order: 2;
            padding: 8px 0;
        }

        .flight-plane {
            font-size: 1.1rem;
        }

        .flight-duration {
            font-size: 0.6rem;
            padding: 2px 6px;
        }

        .flight-number-below {
            font-size: 0.7rem;
            padding: 1px 4px;
        }

        .flight-details-section {
            padding-top: 10px;
        }

        .flight-dates {
            font-size: 0.7rem;
            gap: 6px;
            margin-bottom: 6px;
        }

        .flight-meta {
            gap: 12px;
        }

        .airline-info {
            font-size: 0.8rem;
        }

        .flight-number {
            font-size: 0.7rem;
            padding: 1px 6px;
        }

        .layover-duration {
            font-size: 0.7rem;
        }
    }

    /* Summary section styles */
    .summary-section {
        background: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border-left: 4px solid #0ea5e9;
    }

    .summary-content h3 {
        font-size: 24px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .summary-content h3::before {
        content: '📋';
        font-size: 28px;
    }

    .summary-text {
        font-size: 16px;
        line-height: 1.7;
        color: #374151;
        white-space: pre-line;
    }

    .summary-text p {
        margin-bottom: 15px;
    }

    .summary-text p:last-child {
        margin-bottom: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    function downloadPDF() {
        const tripId = {{ $trip->id ?? 'null' }};
        const token = '{{ request()->route("token") ?? "" }}';

        if (!tripId) {
            showNotification('Error', 'No se puede descargar el PDF de este viaje.', 'error');
            return;
        }

        // Show loading state - try both button selectors
        const pdfBtn = document.querySelector('.btn-pdf') || document.querySelector('.public-btn-print');
        const originalText = pdfBtn.innerHTML;
        pdfBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando PDF...';
        pdfBtn.disabled = true;

        try {
            // Create a temporary link to trigger download
            const link = document.createElement('a');
            const url = token ? `/trips/${tripId}/pdf?token=${token}` : `/trips/${tripId}/pdf`;
            link.href = url;
            link.download = 'itinerario.pdf';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            showNotification('PDF generado', 'El PDF del itinerario se está descargando.', 'success');

            // Reset button
            pdfBtn.innerHTML = originalText;
            pdfBtn.disabled = false;
        } catch (error) {
            console.error('PDF download error:', error);
            showNotification('Error', 'No se pudo generar el PDF.', 'error');

            // Reset button
            pdfBtn.innerHTML = originalText;
            pdfBtn.disabled = false;
        }
    }

    async function shareTrip() {
        const tripId = {{ $trip->id ?? 'null' }};

        if (!tripId) {
            showNotification('Error', 'No se puede compartir este viaje.', 'error');
            return;
        }

        try {
            // Show loading state
            const shareBtn = document.querySelector('.btn-share');
            const originalText = shareBtn.innerHTML;
            shareBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando enlace...';
            shareBtn.disabled = true;

            // Generate share token via AJAX
            const response = await fetch(`/trips/${tripId}/generate-share-token`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });

            const data = await response.json();

            if (data.success) {
                // Show share modal with the URL
                showShareModal(data.share_url);

                // Reset button
                shareBtn.innerHTML = originalText;
                shareBtn.disabled = false;
            } else {
                throw new Error(data.message || 'Error al generar el enlace');
            }
        } catch (error) {
            console.error('Share error:', error);
            showNotification('Error', error.message || 'No se pudo generar el enlace de compartición.', 'error');

            // Reset button
            const shareBtn = document.querySelector('.btn-share');
            shareBtn.innerHTML = '<i class="fas fa-share-alt"></i> Compartir';
            shareBtn.disabled = false;
        }
    }

    function showShareModal(shareUrl) {
        // Remove existing modal if present
        const existingModal = document.getElementById('shareModal');
        if (existingModal) {
            existingModal.remove();
        }

        // Create modal HTML
        const modalHtml = `
            <div id="shareModal" class="share-modal-overlay" style="
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10000;
                font-family: 'Poppins', sans-serif;
            ">
                <div class="share-modal" style="
                    background: white;
                    border-radius: 16px;
                    padding: 2rem;
                    max-width: 500px;
                    width: 90%;
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
                    position: relative;
                ">
                    <div class="share-modal-header" style="
                        text-align: center;
                        margin-bottom: 1.5rem;
                    ">
                        <h3 style="
                            font-size: 1.5rem;
                            font-weight: 700;
                            color: #1f2937;
                            margin: 0 0 0.5rem 0;
                        ">Compartir Itinerario</h3>
                        <p style="
                            color: #6b7280;
                            margin: 0;
                            font-size: 0.9rem;
                        ">Copia el enlace para compartir este viaje</p>
                    </div>

                    <div class="share-modal-body">
                        <div class="share-url-container" style="
                            margin-bottom: 1.5rem;
                        ">
                            <label style="
                                display: block;
                                font-size: 0.85rem;
                                font-weight: 600;
                                color: #374151;
                                margin-bottom: 0.5rem;
                            ">Enlace de compartición:</label>
                            <div class="share-url-input-group" style="
                                display: flex;
                                gap: 0.5rem;
                            ">
                                <input type="text" id="shareUrlInput" value="${shareUrl}" readonly style="
                                    flex: 1;
                                    padding: 0.75rem;
                                    border: 1px solid #d1d5db;
                                    border-radius: 8px;
                                    font-size: 0.9rem;
                                    background: #f9fafb;
                                    color: #374151;
                                    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
                                ">
                                <button id="copyShareUrlBtn" style="
                                    padding: 0.75rem 1rem;
                                    background: linear-gradient(135deg, #10b981, #059669);
                                    color: white;
                                    border: none;
                                    border-radius: 8px;
                                    cursor: pointer;
                                    font-weight: 600;
                                    display: flex;
                                    align-items: center;
                                    gap: 0.5rem;
                                    transition: all 0.3s ease;
                                ">
                                    <i class="fas fa-copy"></i>
                                    Copiar
                                </button>
                            </div>
                        </div>

                        <div class="share-modal-actions" style="
                            display: flex;
                            gap: 0.75rem;
                            justify-content: flex-end;
                        ">
                            <button id="closeShareModalBtn" style="
                                padding: 0.625rem 1.25rem;
                                background: #f3f4f6;
                                color: #374151;
                                border: 1px solid #d1d5db;
                                border-radius: 8px;
                                cursor: pointer;
                                font-weight: 500;
                                transition: all 0.3s ease;
                            ">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        // Get modal elements
        const modal = document.getElementById('shareModal');
        const urlInput = document.getElementById('shareUrlInput');
        const copyBtn = document.getElementById('copyShareUrlBtn');
        const closeBtn = document.getElementById('closeShareModalBtn');

        // Auto-select the URL
        setTimeout(() => {
            urlInput.select();
            urlInput.focus();
        }, 100);

        // Copy button functionality
        copyBtn.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(shareUrl);
                copyBtn.innerHTML = '<i class="fas fa-check"></i> ¡Copiado!';
                copyBtn.style.background = 'linear-gradient(135deg, #059669, #047857)';

                // Reset button after 2 seconds
                setTimeout(() => {
                    copyBtn.innerHTML = '<i class="fas fa-copy"></i> Copiar';
                    copyBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                }, 2000);
            } catch (error) {
                // Fallback for older browsers
                urlInput.select();
                document.execCommand('copy');
                copyBtn.innerHTML = '<i class="fas fa-check"></i> ¡Copiado!';
                copyBtn.style.background = 'linear-gradient(135deg, #059669, #047857)';

                setTimeout(() => {
                    copyBtn.innerHTML = '<i class="fas fa-copy"></i> Copiar';
                    copyBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                }, 2000);
            }
        });

        // Close modal functionality
        closeBtn.addEventListener('click', () => {
            modal.remove();
        });

        // Close on overlay click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function closeOnEscape(e) {
            if (e.key === 'Escape') {
                modal.remove();
                document.removeEventListener('keydown', closeOnEscape);
            }
        });
    }

    function toggleItemContent(button) {
        const item = button.closest('.timeline-item');
        const content = item.querySelector('.item-content');
        const icon = button.querySelector('i');

        if (content.style.display === 'none') {
            content.style.display = 'block';
            icon.className = 'fas fa-chevron-up';
        } else {
            content.style.display = 'none';
            icon.className = 'fas fa-chevron-down';
        }
    }

    // Header hide on scroll functionality
    let lastScrollTop = 0;
    const header = document.getElementById('previewStickyHeader');

    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > lastScrollTop && scrollTop > 100) {
            // Scrolling down and past 100px
            header.classList.add('hidden');
        } else {
            // Scrolling up or at top
            header.classList.remove('hidden');
        }

        lastScrollTop = scrollTop;
    });

    // Hotel Gallery Carousel Functionality
    function initializeHotelCarousels() {
        const carousels = document.querySelectorAll('.hotel-gallery');

        carousels.forEach(carousel => {
            const track = carousel.querySelector('.hotel-gallery-track');
            const slides = carousel.querySelectorAll('.hotel-gallery-slide');
            const prevBtn = carousel.querySelector('.hotel-gallery-prev');
            const nextBtn = carousel.querySelector('.hotel-gallery-next');
            const indicators = carousel.querySelectorAll('.hotel-gallery-indicator');

            if (!track || slides.length === 0) return;

            let currentIndex = 0;

            function updateCarousel() {
                // Update slide positions using transform
                track.style.transform = `translateX(-${currentIndex * 100}%)`;

                // Update active slide opacity
                slides.forEach((slide, index) => {
                    slide.classList.toggle('active', index === currentIndex);
                });

                // Update indicators
                indicators.forEach((indicator, index) => {
                    indicator.classList.toggle('active', index === currentIndex);
                });

                // Update button states
                if (prevBtn) prevBtn.style.opacity = currentIndex === 0 ? '0.5' : '1';
                if (nextBtn) nextBtn.style.opacity = currentIndex === slides.length - 1 ? '0.5' : '1';
            }

            function nextSlide() {
                if (currentIndex < slides.length - 1) {
                    currentIndex++;
                    updateCarousel();
                }
            }

            function prevSlide() {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateCarousel();
                }
            }

            function goToSlide(index) {
                if (index >= 0 && index < slides.length) {
                    currentIndex = index;
                    updateCarousel();
                }
            }

            // Event listeners
            if (prevBtn) prevBtn.addEventListener('click', prevSlide);
            if (nextBtn) nextBtn.addEventListener('click', nextSlide);

            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => goToSlide(index));
            });

            // Touch/swipe support
            let startX = 0;
            let isDragging = false;

            carousel.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
                isDragging = true;
            });

            carousel.addEventListener('touchmove', (e) => {
                if (!isDragging) return;
                const currentX = e.touches[0].clientX;
                const diff = startX - currentX;

                if (Math.abs(diff) > 50) {
                    if (diff > 0 && currentIndex < slides.length - 1) {
                        nextSlide();
                    } else if (diff < 0 && currentIndex > 0) {
                        prevSlide();
                    }
                    isDragging = false;
            }
            });
        });
    }

    function showHotelGallery(images, startIndex = 0, hotelName = 'Hotel') {
        currentHotelImages = images;
        currentImageIndex = startIndex;

        const modalHtml = `
            <div id="hotelGalleryModal" class="hotel-gallery-modal-overlay" style="
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.9);
                z-index: 10000;
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10000;
                font-family: 'Poppins', sans-serif;
            ">
                <div class="hotel-gallery-modal" style="
                    position: relative;
                    max-width: 90vw;
                    max-height: 90vh;
                    background: white;
                    border-radius: 12px;
                    overflow: hidden;
                    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
                ">
                    <div class="gallery-modal-header" style="
                        padding: 1rem 1.5rem;
                        background: var(--primary-dark, #1f2a44);
                        color: white;
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                    ">
                        <h3 style="margin: 0; font-size: 1.2rem;">${hotelName} - Galería de Fotos</h3>
                        <button onclick="closeHotelGallery()" style="
                            background: none;
                            border: none;
                            color: white;
                            font-size: 1.5rem;
                            cursor: pointer;
                            padding: 0.25rem;
                            border-radius: 4px;
                            transition: background 0.3s ease;
                        " onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='none'">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="gallery-modal-body" style="
                        position: relative;
                        background: black;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        min-height: 400px;
                    ">
                        <img id="galleryMainImage" src="${currentHotelImages[currentImageIndex]}" alt="Hotel image" style="
                            max-width: 100%;
                            max-height: 70vh;
                            object-fit: contain;
                        ">
                        ${currentHotelImages.length > 1 ? `
                            <button onclick="prevHotelImage()" class="gallery-nav-btn" style="
                                position: absolute;
                                left: 1rem;
                                top: 50%;
                                transform: translateY(-50%);
                                background: rgba(0, 0, 0, 0.7);
                                color: white;
                                border: none;
                                width: 50px;
                                height: 50px;
                                border-radius: 50%;
                                cursor: pointer;
                                font-size: 1.2rem;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                transition: background 0.3s ease;
                            " onmouseover="this.style.background='rgba(0,0,0,0.9)'" onmouseout="this.style.background='rgba(0,0,0,0.7)'">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button onclick="nextHotelImage()" class="gallery-nav-btn" style="
                                position: absolute;
                                right: 1rem;
                                top: 50%;
                                transform: translateY(-50%);
                                background: rgba(0, 0, 0, 0.7);
                                color: white;
                                border: none;
                                width: 50px;
                                height: 50px;
                                border-radius: 50%;
                                cursor: pointer;
                                font-size: 1.2rem;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                transition: background 0.3s ease;
                            " onmouseover="this.style.background='rgba(0,0,0,0.9)'" onmouseout="this.style.background='rgba(0,0,0,0.7)'">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        ` : ''}
                    </div>
                    <div class="gallery-modal-footer" style="
                        padding: 1rem 1.5rem;
                        background: #f8f9fa;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        gap: 0.5rem;
                    ">
                        <span id="galleryImageCounter" style="
                            font-size: 0.9rem;
                            color: #6c757d;
                        ">${currentImageIndex + 1} de ${currentHotelImages.length}</span>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHtml);

        // Add keyboard navigation
        document.addEventListener('keydown', handleGalleryKeydown);
    }

    function closeHotelGallery() {
        const modal = document.getElementById('hotelGalleryModal');
        if (modal) {
            modal.remove();
        }
        document.removeEventListener('keydown', handleGalleryKeydown);
    }

    function nextHotelImage() {
        currentImageIndex = (currentImageIndex + 1) % currentHotelImages.length;
        updateGalleryImage();
    }

    function prevHotelImage() {
        currentImageIndex = currentImageIndex === 0 ? currentHotelImages.length - 1 : currentImageIndex - 1;
        updateGalleryImage();
    }

    function updateGalleryImage() {
        const mainImage = document.getElementById('galleryMainImage');
        const counter = document.getElementById('galleryImageCounter');

        if (mainImage) {
            mainImage.src = currentHotelImages[currentImageIndex];
        }
        if (counter) {
            counter.textContent = `${currentImageIndex + 1} de ${currentHotelImages.length}`;
        }
    }

    function handleGalleryKeydown(e) {
        if (e.key === 'Escape') {
            closeHotelGallery();
        } else if (e.key === 'ArrowRight') {
            nextHotelImage();
        } else if (e.key === 'ArrowLeft') {
            prevHotelImage();
        }
    }


    // Initialize carousels on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeHotelCarousels();
    });

</script>
@endpush

