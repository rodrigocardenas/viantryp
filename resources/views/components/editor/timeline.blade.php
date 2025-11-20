{{-- Componente: Timeline --}}
{{-- Ubicación: resources/views/components/editor/timeline.blade.php --}}
{{-- Propósito: Contenedor principal del timeline con días del viaje --}}
{{-- Props: trip (opcional) --}}

@props(['trip' => null])

<!-- Days Container -->
<div class="days-container" id="days-container">
    @if(isset($trip) && $trip->days && count($trip->days) > 0)
        @foreach($trip->days as $day)
            <div class="day-card" data-day="{{ $day->day }}">
                <div class="day-header">
                    <h3>Día {{ $day->day }}</h3>
                    <p class="day-date">{{ $day->getFormattedDate() }}</p>
                </div>
                <div class="day-content" ondrop="drop(event)" ondragover="allowDrop(event)">
                    <p class="drag-instruction">Arrastra elementos aquí para personalizar este día</p>

                    @if($day->items && count($day->items) > 0)
                        @foreach($day->items as $item)
                            <x-trip-item :item="$item" :day="$day->day" />
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    @else
        {{-- Always show at least one day for editing --}}
        <div class="day-card" data-day="1">
            <div class="day-header">
                <h3>Día 1</h3>
                <p class="day-date" id="day-1-date">
                    @if(isset($trip) && $trip->start_date)
                        {{ $trip->start_date->format('l, d \d\e F \d\e Y') }}
                    @else
                        martes, 16 de septiembre de 2025
                    @endif
                </p>
            </div>
            <div class="day-content" ondrop="drop(event)" ondragover="allowDrop(event)">
                <div class="add-element-btn btn-sm" data-action="add-element" data-day="1">
                    <i class="fas fa-plus"></i>
                </div>
            </div>
                </div>
    @endif

    <!-- Add Day Section -->

</div></search>
</search_and_replace>
<x-editor-add-day-section />
