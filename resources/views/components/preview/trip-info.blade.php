@props(['trip'])

<div class="trip-preview-wrapper">
    <header class="preview-header-minimal">
        {{-- Banner with cover image or fallback gradient --}}
        <div class="trip-main-banner {{ $trip->cover_image_url ? 'has-cover' : '' }}">
            @if($trip->cover_image_url)
                <img src="{{ $trip->cover_image_url }}" alt="Imagen de portada" class="banner-cover-image">
                <div class="banner-cover-overlay"></div>
            @endif

            @if(!$trip->cover_image_url)
                <div class="banner-icon-centered">
                    <i class="fas fa-globe"></i>
                </div>
            @endif
        </div>

        {{-- Title and status below the banner --}}
        <div class="trip-title-section">
            <div class="trip-title-left">
                <h1 class="preview-trip-title">
                    {{ $trip->title ?? 'Plan de Viaje' }}
                </h1>
                @if(isset($trip->customer_name))
                    <p class="customer-name-subtitle">{{ $trip->customer_name }}</p>
                @endif
            </div>
            <div class="status-badge-container">
                @php
                    $statusClass = 'draft';
                    $statusText = 'PROPUESTA';
                    
                    switch($trip->status) {
                        case 'draft':
                        case 'sent':
                            $statusClass = 'draft';
                            $statusText = 'PROPUESTA';
                            break;
                        case 'reserved':
                            $statusClass = 'reserved';
                            $statusText = 'RESERVADO';
                            break;
                        case 'completed':
                            $statusClass = 'completed';
                            $statusText = 'COMPLETADO';
                            break;
                        case 'discarded':
                            $statusClass = 'discarded';
                            $statusText = 'DESCARTADO';
                            break;
                    }
                @endphp
                <span class="status-badge {{ $statusClass }}">
                    <span class="status-dot"></span> {{ $statusText }}
                </span>
            </div>
        </div>

        <div class="trip-summary-box">
            <div class="summary-item">
                <span class="summary-label">FECHAS</span>
                <span class="summary-value">
                    @if($trip->start_date && $trip->end_date)
                        {{ $trip->start_date->isoFormat('D MMM') }} — {{ $trip->end_date->isoFormat('D MMM') }}
                    @else
                        Pendiente
                    @endif
                </span>
            </div>
            <div class="summary-divider"></div>
            <div class="summary-item">
                <span class="summary-label">VIAJEROS</span>
                <span class="summary-value">
                    {{ $trip->travelers ?? '1' }} {{ ($trip->travelers ?? 1) > 1 ? 'personas' : 'adulto' }}
                </span>
            </div>
            <div class="summary-divider"></div>
            <div class="summary-item">
                <span class="summary-label">TOTAL</span>
                <span class="summary-value total-price">
                    {{ $trip->currency ?? 'USD' }} ${{ number_format($trip->price ?? 0, 2, ',', '.') }}
                </span>
            </div>
        </div>
    </header>
</div>

<x-preview.global-notes :trip="$trip" />
