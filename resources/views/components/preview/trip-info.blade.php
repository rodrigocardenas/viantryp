@props(['trip'])

<div class="trip-preview-wrapper">
    <header class="preview-header-minimal">
        <div class="trip-main-banner">
            <div class="banner-icon">
                <i class="fas fa-plane"></i>
            </div>
            <div class="banner-text">
                <h1 class="preview-trip-title">
                    {{ $trip->title ?? 'Plan de Viaje' }}
                </h1>
                @if(isset($trip->customer_name))
                    <p class="customer-name-subtitle">{{ $trip->customer_name }}</p>
                @endif
                <div class="status-badge-container mobile-only">
                    @if($trip->status === 'approved' || $trip->status === 'completed')
                        <span class="status-badge confirmed">
                            <span class="status-dot"></span> CONFIRMADO
                        </span>
                    @else
                       <span class="status-badge draft">
                            <span class="status-dot"></span> BORRADOR
                        </span>
                    @endif
                </div>
            </div>
            <div class="status-badge-container desktop-only">
                @if($trip->status === 'approved' || $trip->status === 'completed')
                    <span class="status-badge confirmed">
                        <span class="status-dot"></span> CONFIRMADO
                    </span>
                @else
                   <span class="status-badge draft">
                        <span class="status-dot"></span> BORRADOR
                    </span>
                @endif
            </div>
        </div>

        @if($trip->cover_image_url)
            <div class="trip-cover-banner">
                <img src="{{ $trip->cover_image_url }}" alt="Imagen de portada" class="cover-banner-image">
            </div>
        @endif

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
                    {{ $trip->travelers ?? '1 adulto' }}
                </span>
            </div>
            <div class="summary-divider"></div>
            <div class="summary-item">
                <span class="summary-label">TOTAL</span>
                <span class="summary-value total-price">
                    ${{ number_format($trip->price, 2, ',', '.') }}
                </span>
            </div>
        </div>
    </header>
</div>

<x-preview.global-notes :trip="$trip" />
