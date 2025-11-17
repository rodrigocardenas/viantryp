@props(['trip'])

<header>
    <h1>{{ $trip->title ?? 'Viaje sin título' }}</h1>
    <div class="trip-code">{{ $trip->code ?? 'YATPKYEQTQ' }}</div>
    <div class="price-section">
        <span class="price-label">Precio Total</span>
        <span class="price-amount">${{ number_format($trip->price ?? 0, 2) }}</span>
    </div>
</header>
