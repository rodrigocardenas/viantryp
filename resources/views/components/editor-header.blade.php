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
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;">
                <path d="M19 12H5M5 12l7 7M5 12l7-7"/>
            </svg>
            {{ $backText }}
        </a>
    </div>

    @if($showActions)
    <div class="header-center">
        <div class="trip-title-and-date">
            <h1 class="trip-title" id="trip-title">{{ $trip->title ?? 'Sin título' }}</h1>
            <div style="width: 1px; height: 20px; background: var(--border); margin: 0 16px;"></div>
            @php $today = date('Y-m-d'); @endphp
            <div class="header-date-wrap">
                <i class="far fa-calendar-alt" style="color: var(--gray); font-size: 13px;"></i>
                <input type="date" id="start-date" class="header-start-date"
                       value="{{ $trip && $trip->start_date ? $trip->start_date->format('Y-m-d') : $today }}"
                       min="{{ $today }}">
            </div>
        </div>
    </div>

    <div class="header-right">
        @if($shareUrl)
        <a href="{{ $shareUrl }}" class="btn-nav-editor btn-nav-share" target="_blank">
            <i class="fas fa-share-alt"></i>
            {{ $shareText }}
        </a>
        @endif

        @if($saveUrl)
        <button class="btn-nav-editor btn-nav-save" onclick="saveTrip()">
            <i class="fas fa-save"></i>
            {{ $saveText }}
        </button>
        @endif

        @if($deleteUrl)
        <button class="btn-nav-editor btn-nav-delete" onclick="confirmDelete()">
            <i class="fas fa-trash"></i>
            {{ $deleteText }}
        </button>
        @endif

        @auth
        <div class="user-profile-dropdown" style="position: relative; margin-left: 10px; border-left: 1px solid var(--border); padding-left: 16px;">
            <div class="ubadge-editor" id="profileTriggerEditor" style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
              <div class="avatar" style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #1a9a8a, #0c4a5b); display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; color: white;">
                {{ collect(explode(' ', auth()->user()->name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->join('') }}
              </div>
              <i class="fas fa-chevron-down" style="font-size: 10px; color: var(--gray);"></i>
            </div>
            
            <div id="profileMenuEditor" class="dropdown-menu-content" style="display: none; position: absolute; top: calc(100% + 10px); right: 0; background: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 180px; overflow: hidden; z-index: 1000; border: 1px solid #e2e8ef;">
                <a href="{{ route('trips.index') }}" class="dropdown-item" style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: var(--dark); text-decoration: none; font-size: 13px; font-weight: 500; transition: background 0.2s;">
                    <i class="fas fa-suitcase-rolling" style="color: #64748b; font-size: 15px;"></i>
                    Mis viajes
                </a>
                <div style="height: 1px; background: #e2e8ef;"></div>
                <a href="{{ route('profile.index') }}" class="dropdown-item" style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: var(--dark); text-decoration: none; font-size: 13px; font-weight: 500; transition: background 0.2s;">
                    <i class="fas fa-user-circle" style="color: #64748b; font-size: 15px;"></i>
                    Mi perfil
                </a>
                <div style="height: 1px; background: var(--border);"></div>
                <form method="POST" action="{{ route('logout') }}" id="logout-form" style="margin: 0;">
                    @csrf
                    <button type="submit" class="dropdown-item" style="width: 100%; border: none; background: transparent; display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: #c0392b; cursor: pointer; text-align: left; font-size: 13px; font-weight: 500; transition: background 0.2s; font-family: 'Barlow', sans-serif;">
                        <i class="fas fa-sign-out-alt" style="font-size: 15px;"></i>
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </div>
    @endif
</header>

<style>
    .editor-header {
        height: 64px;
        background: var(--white);
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 40px;
        position: sticky;
        top: 0;
        z-index: 1000;
        font-family: 'Barlow', sans-serif;
    }

    .btn-back {
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        color: var(--dark);
        font-weight: 600;
        font-size: 14px;
        padding: 8px 16px;
        border-radius: 50px;
        border: 1.5px solid var(--border);
        background: var(--white);
        transition: all 0.2s;
    }
    .btn-back:hover {
        border-color: var(--teal);
        color: var(--teal);
        background: #f0faf9;
    }

    .trip-title-and-date {
        display: flex;
        align-items: center;
    }
    .trip-title {
        font-weight: 700;
        font-size: 18px;
        color: var(--dark);
        margin: 0;
        font-family: 'Barlow', sans-serif;
    }
    .header-date-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--gray);
    }
    .header-start-date {
        border: none;
        background: transparent;
        font-family: 'Barlow', sans-serif;
        font-size: 14px;
        font-weight: 500;
        color: var(--dark);
        outline: none;
        cursor: pointer;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-nav-editor {
        height: 38px;
        padding: 0 18px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'Barlow', sans-serif;
        border: 1.5px solid var(--border);
        background: var(--white);
        color: var(--dark);
        text-decoration: none;
    }
    .btn-nav-editor:hover {
        border-color: var(--teal);
        color: var(--teal);
        background: #f0faf9;
    }
    .btn-nav-save {
        background: var(--teal);
        color: var(--white);
        border: none;
        box-shadow: 0 4px 12px rgba(26,122,138,0.2);
    }
    .btn-nav-save:hover {
        background: var(--teal2);
        color: var(--white);
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(26,122,138,0.3);
    }

    .dropdown-item:hover {
        background: #f8fafc;
    }
</style>

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
