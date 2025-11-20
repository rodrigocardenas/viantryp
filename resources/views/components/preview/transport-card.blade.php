@props(['item', 'trip', 'documents' => null])

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
