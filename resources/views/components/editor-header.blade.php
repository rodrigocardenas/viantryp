{{-- Componente: Header del Editor --}}
{{-- Ubicación: resources/views/components/editor-header.blade.php --}}
{{-- Propósito: Header con acciones para el editor de viajes --}}
{{-- Props: showActions, backUrl, backText, saveUrl, saveText, deleteUrl, deleteText, shareUrl, shareText --}}

@props([
    'showActions' => true,
    'backUrl' => '#',
    'backText' => 'Volver',
    'saveUrl' => null,
    'saveText' => 'Guardar',
    'deleteUrl' => null,
    'deleteText' => 'Eliminar',
    'shareUrl' => null,
    'shareText' => 'Compartir',
    'trip' => null
])

<header class="editor-header">
    <div class="header-left">
        <a href="{{ $backUrl }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            {{ $backText }}
        </a>
    </div>

    @if($showActions)
    <div class="header-center">
        <div class="trip-title-and-date">
            <h1 class="trip-title" id="trip-title">{{ $trip->title ?? 'Sin título' }}</h1>

            {{-- Editable start date next to title so saving finds it easily --}}
            @php $today = date('Y-m-d'); @endphp
            <input type="date" id="start-date" class="form-input header-start-date"
                   value="{{ $trip && $trip->start_date ? $trip->start_date->format('Y-m-d') : $today }}"
                   min="{{ $today }}">
        </div>
    </div>

    <div class="header-right">
        @if($shareUrl)
        <a href="{{ $shareUrl }}" class="btn-share" target="_blank">
            <i class="fas fa-share-alt"></i>
            {{ $shareText }}
        </a>
        @endif

        @if($saveUrl)
        <button class="btn-save" onclick="saveTrip()">
            <i class="fas fa-save"></i>
            {{ $saveText }}
        </button>
        @endif

        @if($deleteUrl)
        <button class="btn-delete" onclick="confirmDelete()">
            <i class="fas fa-trash"></i>
            {{ $deleteText }}
        </button>
        @endif
    </div>
    @endif
</header>

@push('scripts')
<script>
function saveTrip() {
    // Implementar guardado del viaje
    console.log('Saving trip...');
    showNotification('Viaje guardado exitosamente', 'success');
}

function confirmDelete() {
    if (confirm('¿Estás seguro de que quieres eliminar este viaje? Esta acción no se puede deshacer.')) {
        // Implementar eliminación
        console.log('Deleting trip...');
    }
}

function showNotification(message, type = 'info') {
    // Implementar notificación
    alert(message);
}
</script>
@endpush

@push('scripts')
<script>
// Sync header start-date with global existingTripData so export-manager picks it up
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start-date');
    if (!startDateInput) return;

    // Ensure window.existingTripData exists
    if (!window.existingTripData) window.existingTripData = {};

    // Initialize from existingTripData if present
    if (window.existingTripData.start_date && !startDateInput.value) {
        startDateInput.value = window.existingTripData.start_date;
    }

    startDateInput.addEventListener('change', function() {
        window.existingTripData = window.existingTripData || {};
        window.existingTripData.start_date = startDateInput.value || null;

        if (typeof updateItineraryDates === 'function') {
            try { updateItineraryDates(); } catch (e) { console.warn('updateItineraryDates failed', e); }
        }
    });
});
</script>
@endpush
