@props(['item' => null, 'title' => null, 'subtitle' => null, 'image' => null, 'price' => null, 'reviews' => null, 'showBadges' => true])

<div class="activity-card">
    @php
        $detailedInfo = $item['detailed_info'] ?? null;
        $activityTitle = $detailedInfo['name'] ?? $item['activity_title'] ?? $item['title'] ?? $title ?? 'Actividad';
        $activityAddress = $detailedInfo['formatted_address'] ?? $item['location'] ?? $item['formatted_address'] ?? '';
        $activityRating = $detailedInfo['rating'] ?? $item['rating'] ?? null;
        $activityWebsite = $detailedInfo['website'] ?? $item['website'] ?? null;
        $activityPhone = $detailedInfo['international_phone_number'] ?? $item['phone_number'] ?? null;
        $activityPhotos = $item['location_data']['photos'] ?? $detailedInfo['photos'] ?? [];
        $activityId = $item['place_id'] ?? 'activity-' . ($item['id'] ?? 'default');
    @endphp

    <div class="activity-content">
        <!-- Left column: Photos -->
        <div class="activity-photos-column">
            @if(count($activityPhotos) > 0)
                <!-- Activity Photo Carousel -->
                <div class="activity-gallery" data-activity-id="{{ $activityId }}">
                    <div class="activity-gallery-track">
                        @foreach(array_slice($activityPhotos, 0, 8) as $index => $photo)
                            <div class="activity-gallery-slide {{ $index === 0 ? 'active' : '' }}" onclick="showActivityGallery({{ json_encode(array_column($activityPhotos, 'url')) }}, {{ $index }}, '{{ addslashes($activityTitle) }}')">
                                <img src="{{ $photo['url'] }}" alt="Activity photo {{ $index + 1 }}" class="activity-gallery-image" loading="lazy">
                            </div>
                        @endforeach
                    </div>

                    @if(count($activityPhotos) > 1)
                        <button class="activity-gallery-btn activity-gallery-prev" type="button">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="activity-gallery-btn activity-gallery-next" type="button">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        <div class="activity-gallery-indicators">
                            @for($i = 0; $i < min(count($activityPhotos), 8); $i++)
                                <span class="activity-gallery-indicator {{ $i === 0 ? 'active' : '' }}" data-slide="{{ $i }}"></span>
                            @endfor
                        </div>
                    @endif
                </div>
            @else
                <!-- Fallback image if no photos available -->
                <img src="{{ $item['image'] ?? 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=400&h=300&fit=crop' }}" alt="{{ $activityTitle }}" class="activity-fallback-image">
            @endif
        </div>

        <!-- Right column: Activity information -->
        <div class="activity-info-column">
            <!-- Activity name and rating -->
            <div class="activity-header">
                <h3 class="activity-title">{{ $activityTitle }}</h3>
                @if($activityRating && is_numeric($activityRating))
                    <div class="activity-rating-section">
                        <div class="activity-rating">
                            @for($i = 0; $i < 5; $i++)
                                @if($i < floor($activityRating))
                                    <i class="fas fa-star star-filled"></i>
                                @elseif($i < $activityRating)
                                    <i class="fas fa-star-half-alt star-filled"></i>
                                @else
                                    <i class="far fa-star star-empty"></i>
                                @endif
                            @endfor
                            <span class="rating-text">({{ number_format($activityRating, 1) }})</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Activity address -->
            @if($activityAddress)
                <div class="activity-address">
                    <i class="fas fa-map-marker-alt"></i> {{ $activityAddress }}
                </div>
            @endif

            <!-- Activity times -->
            @php
                $startTime = $item['start_datetime'] ?? ($item['start_date'] && $item['start_time'] ? $item['start_date'] . ' ' . $item['start_time'] : '');
                $endTime = $item['end_datetime'] ?? ($item['end_date'] && $item['end_time'] ? $item['end_date'] . ' ' . $item['end_time'] : '');
            @endphp
            @if($startTime || $endTime)
                <div class="activity-times">
                    <i class="fas fa-clock"></i>
                    @if($startTime && $endTime)
                        {{ \Carbon\Carbon::parse($startTime)->format('H:i') }} - {{ \Carbon\Carbon::parse($endTime)->format('H:i') }}
                    @elseif($startTime)
                        Desde {{ \Carbon\Carbon::parse($startTime)->format('H:i') }}
                    @elseif($endTime)
                        Hasta {{ \Carbon\Carbon::parse($endTime)->format('H:i') }}
                    @endif
                </div>
            @endif

            <!-- Activity description -->
            @if(isset($item['description']) && $item['description'])
                <div class="activity-description">
                    {{ $item['description'] }}
                </div>
            @endif

            <!-- Divider line -->
            <div class="activity-divider"></div>

            @if($activityWebsite || $activityPhone)
                <div class="activity-contact-section">
                    @if($activityWebsite)
                        <a href="{{ $activityWebsite }}" target="_blank" class="activity-contact-link">
                            <i class="fas fa-globe"></i> Sitio web
                        </a>
                    @endif
                    @if($activityPhone)
                        <a href="tel:{{ $activityPhone }}" class="activity-contact-link">
                            <i class="fas fa-phone"></i> {{ $activityPhone }}
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @php
        $documents = isset($trip) ? $trip->documents->where('type', 'activity') : collect();
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
