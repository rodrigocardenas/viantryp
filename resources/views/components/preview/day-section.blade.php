@props(['dayNumber', 'dayItems', 'trip'])

<div class="day-section">
    <div class="day-title">DIA {{ $dayNumber }}</div>

    @if(count($dayItems) > 0)
        @foreach($dayItems as $item)
            @if($item['type'] === 'flight')
                <x-preview.flight-card :item="$item" :trip="$trip" />
            @elseif($item['type'] === 'hotel')
                <x-preview.hotel-card :item="$item" :trip="$trip" :loop="$loop" />
            @elseif($item['type'] === 'activity')
                <x-preview.activity-card :item="$item" />
            @elseif($item['type'] === 'transport')
                <x-preview.transport-card :item="$item" :trip="$trip" />
            @else
                <x-preview.activity-card :item="$item" :showBadges="false" />
            @endif
        @endforeach
    @else
        <x-preview.activity-card :title="'Día libre'" :subtitle="'No hay actividades programadas para este día'" :showBadges="false" />
    @endif
</div>
