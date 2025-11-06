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
    'shareText' => 'Compartir'
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
        <h1 class="trip-title" id="trip-title">Sin título</h1>
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
