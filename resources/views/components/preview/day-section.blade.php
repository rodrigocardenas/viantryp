@props(['dayNumber', 'dayItems', 'trip', 'dayDate', 'formattedDate'])

<div class="day-section">
    <div class="day-title">{{ $formattedDate ?: 'DIA ' . $dayNumber }}</div>

    @if(count($dayItems) > 0)
        @foreach($dayItems as $item)
            @if($item['type'] === 'flight')
                @php
                    $itemDocuments = isset($item['id']) ? $trip->getDocumentsByItemId($item['id']) : collect();
                @endphp
                <x-preview.flight-card :item="$item" :trip="$trip" :documents="$itemDocuments" />
            @elseif($item['type'] === 'hotel')
                @php
                    $itemDocuments = isset($item['id']) ? $trip->getDocumentsByItemId($item['id']) : collect();
                @endphp
                <x-preview.hotel-card :item="$item" :trip="$trip" :loop="$loop" :documents="$itemDocuments" />
            @elseif($item['type'] === 'activity')
                @php
                    $itemDocuments = isset($item['id']) ? $trip->getDocumentsByItemId($item['id']) : collect();
                @endphp
                <x-preview.activity-card :item="$item" :documents="$itemDocuments" />
            @elseif($item['type'] === 'transport')
                @php
                    $itemDocuments = isset($item['id']) ? $trip->getDocumentsByItemId($item['id']) : collect();
                @endphp
                <x-preview.transport-card :item="$item" :trip="$trip" :documents="$itemDocuments" />
            @else
                {{-- <x-preview.activity-card :item="$item" :showBadges="false" /> --}}
            @endif
        @endforeach
    @else
        <x-preview.activity-card :title="'Día libre'" :subtitle="'No hay actividades programadas para este día'" :showBadges="false" />
    @endif
</div>
