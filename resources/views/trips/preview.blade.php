@extends('layouts.app')

@section('title', 'Viantryp - Vista Previa del Itinerario')

@section('content')
    <x-preview.sticky-header :trip="$trip" />

    <!-- Main Container -->
    <div class="container">
        <x-preview.trip-info :trip="$trip" />

        @if(isset($trip) && $trip->items_data && count($trip->items_data) > 0)
            @php
                // Check for summary items
                $summaryItems = array_filter($trip->items_data, function($item) {
                    return isset($item['type']) && $item['type'] === 'summary';
                });
            @endphp

            <x-preview.summary-section :summaryItems="$summaryItems" />

            @php
                // Group items by day (excluding summary items)
                $itemsByDay = [];
                foreach($trip->items_data as $item) {
                    if (isset($item['type']) && $item['type'] === 'summary') {
                        continue; // Skip summary items as they're already displayed
                    }
                    $day = $item['day'] ?? 1;
                    if (!isset($itemsByDay[$day])) {
                        $itemsByDay[$day] = [];
                    }
                    $itemsByDay[$day][] = $item;
                }
            @endphp

            @foreach($itemsByDay as $dayNumber => $dayItems)
                <x-preview.day-section :dayNumber="$dayNumber" :dayItems="$dayItems" :trip="$trip" />
            @endforeach
        @else
            <div class="day-section">
                <x-preview.activity-card :title="'No hay días en el itinerario'" :subtitle="'Agrega días y elementos a tu viaje en el editor.'" :showBadges="false" />
            </div>
        @endif
    </div>

    <!-- Contact Button -->
    <x-preview.contact-button />

@endsection

@vite(['resources/js/preview.js'])

<link rel="stylesheet" href="{{ asset('css/preview.css') }}">

<script>
    // Make trip data available globally for JavaScript
    window.tripId = {{ $trip->id ?? 'null' }};
    window.shareToken = '{{ request()->route("token") ?? "" }}';
</script>
