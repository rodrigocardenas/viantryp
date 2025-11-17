@props(['item' => null, 'title' => null, 'subtitle' => null, 'image' => null, 'price' => null, 'reviews' => null, 'showBadges' => true])

<div class="activity-card">
    @if($image)
        <img src="{{ $image }}" alt="{{ $title ?? 'Actividad' }}" class="activity-image">
    @endif

    <div class="activity-content">
        @if($item && isset($item['type']) && $item['type'] === 'activity')
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div class="activity-title">{{ $item['title'] ?? $title ?? 'Actividad' }}</div>
                @if($showBadges)
                    <span class="badge oficial">OFICIAL</span>
                @endif
            </div>
            <div class="activity-subtitle">{{ $item['subtitle'] ?? $subtitle ?? 'Descripción' }}</div>
            @if($showBadges)
                <div>
                    <span class="badge"><i class="fas fa-globe"></i> Web</span>
                    <span class="badge"><i class="fas fa-euro-sign"></i> {{ $item['price'] ?? $price ?? '669.95' }}</span>
                    <span class="badge"><i class="fas fa-map-marker-alt"></i> Ver dirección</span>
                </div>
                <div class="rating">
                    @for($i = 0; $i < 5; $i++)
                        <i class="fas fa-star" style="color: #ffc107;"></i>
                    @endfor
                    <span>{{ $item['reviews'] ?? $reviews ?? '12' }} reseñas de Google</span>
                </div>
            @endif
        @else
            <div class="activity-title">{{ $title ?? ($item['title'] ?? 'Elemento') }}</div>
            <div class="activity-subtitle">{{ $subtitle ?? ($item['subtitle'] ?? '') }}</div>
        @endif
    </div>
</div>