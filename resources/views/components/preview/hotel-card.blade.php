@props(['item', 'trip', 'loop'])

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

    <!-- Hotel Header similar to flight-route-header -->
    <div class="hotel-route-header">
        <span class="hotel-route-text">Alojamiento en {{ $hotelName }}</span>
    </div>

    <!-- Hotel details section similar to flight-details-section -->
    <div class="hotel-details-section">
        <div class="hotel-meta">
            @if(isset($item['room_type']) && $item['room_type'])
                <span class="hotel-room-type">{{ $item['room_type'] }}</span>
            @endif
        </div>
    </div>

    <div class="hotel-content">
        <!-- Left column: Photos -->
        <div class="hotel-photos-column">
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
        </div>

        <!-- Right column: Hotel information -->
        <div class="hotel-info-column">
            <!-- Hotel name and rating -->
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

            <!-- Hotel address -->
            @if($hotelAddress)
                <div class="hotel-address">
                    <i class="fas fa-map-marker-alt"></i> {{ $hotelAddress }}
                </div>
            @endif

            <!-- Check-in and check-out dates -->
            <div class="hotel-check-dates">
                <span><i class="fas fa-calendar-alt"></i> Check-in: {{ $item['check_in'] ?? 'Fecha no especificada' }}</span>
                <span><i class="fas fa-calendar-check"></i> Check-out: {{ $item['check_out'] ?? 'Fecha no especificada' }}</span>
            </div>

            <!-- Amenities -->
            <div class="hotel-amenities">
                @if($item['nights'])
                    <span class="amenity"><i class="fas fa-moon"></i> {{ $item['nights'] }} noches</span>
                @endif
            </div>

            <!-- Divider line -->
            <div class="hotel-divider"></div>

            <!-- Hotel nights at bottom -->
            @if($item['nights'])
                <div class="hotel-nights-bottom">
                    <span class="hotel-nights-badge">{{ $item['nights'] }} noches</span>
                </div>
            @endif

            @if($hotelWebsite || $hotelPhone)
                <div class="hotel-contact-section">
                    @if($hotelWebsite)
                        <a href="{{ $hotelWebsite }}" target="_blank" class="hotel-contact-link">
                            <i class="fas fa-globe"></i> Sitio web
                        </a>
                    @endif
                    @if($hotelPhone)
                        <a href="tel:{{ $hotelPhone }}" class="hotel-contact-link">
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
