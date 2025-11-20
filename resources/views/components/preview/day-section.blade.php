@props(['dayNumber', 'dayItems', 'trip', 'dayDate', 'formattedDate'])

<div class="day-section">
    <div class="day-title">{{ $formattedDate ?: 'DIA ' . $dayNumber }}</div>

    @if(count($dayItems) > 0)
        @foreach($dayItems as $item)
            @if($item['type'] === 'flight')
                @php
                    $flightDocuments = $trip ? $trip->documents->where('type', 'flight') : collect();
                @endphp
                <x-preview.flight-card :item="$item" :trip="$trip" :documents="$flightDocuments" />
            @elseif($item['type'] === 'hotel')
                @php
                    $hotelDocuments = $trip ? $trip->documents->where('type', 'hotel') : collect();
                @endphp
                <x-preview.hotel-card :item="$item" :trip="$trip" :loop="$loop" :documents="$hotelDocuments" />
            @elseif($item['type'] === 'activity')
                @php
                    $activityDocuments = $trip ? $trip->documents->where('type', 'activity') : collect();
                @endphp
                <x-preview.activity-card :item="$item" :documents="$activityDocuments" />
            @elseif($item['type'] === 'transport')
                @php
                    $transportDocuments = $trip ? $trip->documents->where('type', 'transport') : collect();
                @endphp
                <x-preview.transport-card :item="$item" :trip="$trip" :documents="$transportDocuments" />
            @else
                <x-preview.activity-card :item="$item" :showBadges="false" />
            @endif
        @endforeach
    @else
        <x-preview.activity-card :title="'Día libre'" :subtitle="'No hay actividades programadas para este día'" :showBadges="false" />
    @endif
</div>
