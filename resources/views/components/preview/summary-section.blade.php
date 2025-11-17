@props(['summaryItems' => []])

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
