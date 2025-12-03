@props(['trip'])

<header>
    <h1>{{ $trip->title ?? 'Viaje sin título' }}</h1>
    <div class="trip-code">{{ $trip->code ?? 'YATPKYEQTQ' }}</div>
    @if($trip->cover_image_url)
        <div class="trip-cover-banner">
            <img src="{{ $trip->cover_image_url }}" alt="Imagen de portada del viaje" class="cover-banner-image">
        </div>
    @else
        <div class="trip-cover-banner">
            {{-- set default cover image --}}
            <img src="{{ asset('images/default-cover.jpeg') }}" alt="Imagen de portada del viaje" class="cover-banner-image">
        </div>
    @endif
    <div class="price-section">
        <span class="price-label">Precio Total</span>
        <span class="price-amount">${{ number_format($trip->price ?? 0, 2) }}</span>
    </div>
</header>

<x-preview.global-notes :trip="$trip" />
