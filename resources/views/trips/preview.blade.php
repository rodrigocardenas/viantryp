@extends('layouts.app')

@section('title', 'Viantryp - Vista Previa del Itinerario')

@section('content')
    <x-preview.sticky-header :trip="$trip" />

    <!-- Main Container -->
    <div class="container">
        <x-preview.trip-info :trip="$trip" />
        @if(isset($trip) && $trip->items_data && count($trip->items_data) > 0)
            @php
                // Helper function to get timestamp for sorting items chronologically
                $getItemTimestamp = function($item) {
                    switch ($item['type']) {
                        case 'flight':
                            if (isset($item['departure_time']) && !empty($item['departure_time'])) {
                                return strtotime($item['departure_time']);
                            }
                            break;
                        case 'hotel':
                            if (isset($item['check_in']) && !empty($item['check_in'])) {
                                return strtotime($item['check_in']);
                            }
                            break;
                        case 'transport':
                            if (isset($item['pickup_datetime']) && !empty($item['pickup_datetime'])) {
                                return strtotime($item['pickup_datetime']);
                            }
                            break;
                        case 'activity':
                            if (isset($item['start_time']) && !empty($item['start_time'])) {
                                return strtotime($item['start_time']);
                            }
                            break;
                    }
                    // Default: return a high timestamp to put items without time at the end
                    return PHP_INT_MAX;
                };

                // Check for summary items
                $summaryItems = array_filter($trip->items_data, function($item) {
                    return isset($item['type']) && $item['type'] === 'summary';
                });

                // Group items by day (excluding summary items) and sort chronologically
                $itemsByDay = [];
                $dayDates = [];

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

                // Get day dates from days_dates array
                foreach(array_keys($itemsByDay) as $dayNumber) {
                    $dayDates[$dayNumber] = null;
                    if ($trip->days_dates && isset($trip->days_dates[$dayNumber])) {
                        $dayDates[$dayNumber] = \Carbon\Carbon::parse($trip->days_dates[$dayNumber]);
                    } elseif ($trip->start_date) {
                        $dayDates[$dayNumber] = $trip->start_date->copy()->addDays($dayNumber - 1);
                    }
                }

                // Sort items within each day chronologically
                foreach($itemsByDay as $dayNumber => &$dayItems) {
                    usort($dayItems, function($a, $b) {
                        $timeA = $getItemTimestamp($a);
                        $timeB = $getItemTimestamp($b);
                        return $timeA <=> $timeB;
                    });
                }
                unset($dayItems); // Break reference
            @endphp

            <x-preview.summary-section :summaryItems="$summaryItems" />

            @foreach($itemsByDay as $dayNumber => $dayItems)
                @php
                    $dayDate = $dayDates[$dayNumber];
                    $formattedDate = $dayDate ? $dayDate->isoFormat('dddd, D [de] MMMM [de] YYYY') : 'Sin fecha';
                @endphp
                <x-preview.day-section :dayNumber="$dayNumber" :dayItems="$dayItems" :trip="$trip" :dayDate="$dayDate" :formattedDate="$formattedDate" />
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

    // Ensure gallery functions are available globally
    window.showHotelGallery = window.showHotelGallery || function(images, startIndex, hotelName) {
        // Fallback implementation if Vite build fails
        console.log('showHotelGallery called with', images.length, 'images');
    };

    window.showActivityGallery = window.showActivityGallery || function(images, startIndex, activityName) {
        // Fallback implementation if Vite build fails
        console.log('showActivityGallery called with', images.length, 'images');
    };
</script>
