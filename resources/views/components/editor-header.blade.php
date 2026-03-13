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

        @auth
        <div class="user-profile-dropdown" style="position: relative; margin-left: 10px; border-left: 1px solid #e2e8ef; padding-left: 16px;">
            <div class="ubadge-editor" id="profileTriggerEditor" style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
              <div class="avatar" style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #1a9a8a, #0c4a5b); display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; color: white;">
                {{ collect(explode(' ', auth()->user()->name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->join('') }}
              </div>
              <i class="fas fa-chevron-down" style="font-size: 10px; color: #64748b;"></i>
            </div>
            
            <div id="profileMenuEditor" class="dropdown-menu-content" style="display: none; position: absolute; top: calc(100% + 10px); right: 0; background: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 180px; overflow: hidden; z-index: 1000; border: 1px solid #e2e8ef;">
                <a href="{{ route('profile.index') }}" class="dropdown-item" style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: #1a2e2c; text-decoration: none; font-size: 13px; font-weight: 500; transition: background 0.2s;">
                    <i class="fas fa-user-circle" style="color: #1a9a8a; font-size: 15px;"></i>
                    Mi perfil
                </a>
                <div style="height: 1px; background: #e2e8ef;"></div>
                <form method="POST" action="{{ route('logout') }}" id="logout-form" style="margin: 0;">
                    @csrf
                    <button type="submit" class="dropdown-item" style="width: 100%; border: none; background: transparent; display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: #c0392b; cursor: pointer; text-align: left; font-size: 13px; font-weight: 500; transition: background 0.2s; font-family: 'DM Sans', sans-serif;">
                        <i class="fas fa-sign-out-alt" style="font-size: 15px;"></i>
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>

        <script>
            (function() {
                const initMenu = () => {
                    const trigger = document.getElementById('profileTriggerEditor');
                    const menu = document.getElementById('profileMenuEditor');
                    if (trigger && menu) {
                        trigger.addEventListener('click', function(e) {
                            e.stopPropagation();
                            const isVisible = menu.style.display === 'block';
                            menu.style.display = isVisible ? 'none' : 'block';
                        });
                        document.addEventListener('click', function(e) {
                            if (!trigger.contains(e.target) && !menu.contains(e.target)) {
                                menu.style.display = 'none';
                            }
                        });
                        const items = menu.querySelectorAll('.dropdown-item');
                        items.forEach(item => {
                            item.addEventListener('mouseover', () => item.style.background = '#f8fafc');
                            item.addEventListener('mouseout', () => item.style.background = 'transparent');
                        });
                    }
                };
                if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initMenu);
                else initMenu();
            })();
        </script>
        @endauth
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
