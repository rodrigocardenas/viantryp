{{-- Componente: Sidebar --}}
{{-- Ubicación: resources/views/components/sidebar.blade.php --}}
{{-- Propósito: Barra lateral con elementos arrastrables del viaje --}}
{{-- Props:
    - showFlight: boolean (default: true)
    - showHotel: boolean (default: true)
    - showActivity: boolean (default: true)
    - showTransport: boolean (default: true)
    - showNote: boolean (default: true)
    - showSummary: boolean (default: true)
    - showTotal: boolean (default: true)
--}}
@props([
    'showFlight' => true,
    'showHotel' => true,
    'showActivity' => true,
    'showTransport' => true,
    'showNote' => true,
    'showSummary' => true,
    'showTotal' => true
])

<!-- Left Sidebar -->
<div class="editor-sidebar">
    <div class="sidebar-content">
        <div class="sidebar-section">
            <h4>Elementos del Viaje</h4>
            <div class="element-categories">
                @if($showFlight)
                    <x-sidebar.items.flight-item />
                @endif

                @if($showHotel)
                    <x-sidebar.items.hotel-item />
                @endif

                @if($showActivity)
                    <x-sidebar.items.activity-item />
                @endif

                @if($showTransport)
                    <x-sidebar.items.transport-item />
                @endif

                @if($showNote)
                    <x-sidebar.items.note-item />
                @endif

                @if($showSummary)
                    <x-sidebar.items.summary-item />
                @endif

                @if($showTotal)
                    <x-sidebar.items.total-item />
                @endif
            </div>
        </div>
    </div>
</div>
