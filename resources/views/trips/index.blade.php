@extends('layouts.app')

@section('title', 'Viantryp - Gestión de Viajes')

@section('content')
    <x-header :showActions="true" :actions="[
        ['url' => route('trips.create'), 'text' => 'Nuevo Viaje', 'class' => 'btn-success', 'icon' => 'fas fa-plus']
    ]" />

    <x-navigation :activeTab="$activeTab ?? 'all'" />

    <!-- Main Content -->
    <main class="main-content">
        <!-- Search Section -->
        <div class="search-section">
            <div class="search-container">
                <div class="search-bar">
                    <span class="search-icon">🔍</span>
                    <input type="text" class="search-input" placeholder="Buscar viajes..." oninput="searchTrips(this.value)">
                </div>
                <a href="{{ route('trips.create') }}" class="new-trip-btn-search">
                    <span>+</span>
                    Nuevo Viaje
                </a>
            </div>
        </div>

        <!-- Bulk Actions Panel -->
        <div class="bulk-actions" id="bulk-actions">
            <div class="bulk-actions-info">
                <span class="bulk-actions-count">
                    <i class="fas fa-check-circle"></i>
                    <span id="selected-count">0</span> viaje(s) seleccionado(s)
                </span>
            </div>
            <div class="bulk-actions-buttons">
                <button class="bulk-action-btn bulk-duplicate-btn" onclick="duplicateSelectedTrips()">
                    <i class="fas fa-copy"></i>
                    Duplicar
                </button>
                <button class="bulk-action-btn bulk-delete-btn" onclick="deleteSelectedTrips()">
                    <i class="fas fa-trash"></i>
                    Eliminar
                </button>
                <button class="bulk-action-btn bulk-clear-btn" onclick="clearSelection()">
                    <i class="fas fa-times"></i>
                    Limpiar
                </button>
            </div>
        </div>

        <!-- Trips List -->
        <div class="trips-container">
            <div class="trips-header" id="trips-header-title">
                <span>{{ $headerTitle ?? 'Todos los Viajes' }}</span>
                <div class="select-all-container">
                    <input type="checkbox" id="select-all-checkbox" class="select-all-checkbox" onchange="toggleSelectAll()">
                    <label for="select-all-checkbox" class="select-all-label">Seleccionar todos</label>
                </div>
            </div>
            <div id="trips-list">
                @if(count($trips) > 0)
                    @foreach($trips as $trip)
                        <div class="trip-item" onclick="openTrip({{ $trip->id }})">
                            <input type="checkbox" class="trip-checkbox" data-trip-id="{{ $trip->id }}" onclick="event.stopPropagation();" onchange="updateSelectAllState()">
                            <div class="trip-emoji">{{ $trip->getStatusEmoji() }}</div>
                            <div class="trip-info">
                                <div class="trip-title">{{ $trip->title }}</div>
                                <div class="trip-dates">{{ $trip->getFormattedDates() }}</div>
                                <div class="trip-duration">{{ $trip->getDuration() }}</div>
                                <div>
                                    <select class="status-select" data-status="{{ $trip->status }}" onclick="event.stopPropagation();" onchange="changeTripStatus({{ $trip->id }}, this.value)">
                                        <option value="draft" {{ $trip->status === 'draft' ? 'selected' : '' }}>En Diseño</option>
                                        <option value="sent" {{ $trip->status === 'sent' ? 'selected' : '' }}>Enviada</option>
                                        <option value="approved" {{ $trip->status === 'approved' ? 'selected' : '' }}>Aprobado</option>
                                        <option value="completed" {{ $trip->status === 'completed' ? 'selected' : '' }}>Completado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="trip-actions">
                                <button class="action-btn btn-primary" onclick="event.stopPropagation(); previewTrip({{ $trip->id }})">
                                    Vista Previa
                                </button>
                                <button class="action-btn btn-secondary" onclick="event.stopPropagation(); editTrip({{ $trip->id }})">
                                    Editar
                                </button>
                                <button class="action-btn btn-danger" onclick="event.stopPropagation(); deleteTrip({{ $trip->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <div class="empty-icon">📋</div>
                        <p>No se encontraron viajes</p>
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection

@push('styles')
<style>
    :root {
        --ink: #1f2a44;
        --blue-700: #0ea5e9;
        --blue-600: #38bdf8;
        --blue-300: #93c5fd;
        --blue-100: #e0f2fe;
        --sky-50: #f0f9ff;
        --stone-100: #f5f7fa;
        --stone-300: #e2e8f0;
        --stone-400: #cbd5e1;
        --slate-600: #475569;
        --slate-500: #64748b;
        --success: #10b981;
        --danger: #ef4444;
        --shadow-soft: 0 10px 30px rgba(0,0,0,0.06);
        --shadow-hover: 0 14px 40px rgba(0,0,0,0.08);
        --radius: 16px;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(180deg, #e6f3fb 0%, #f7fbff 60%);
        color: var(--ink);
        letter-spacing: 0.1px;
    }

    .main-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    .search-section {
        background: white;
        border-radius: var(--radius);
        padding: 1.25rem 1.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-soft);
        border: 1px solid var(--stone-300);
    }

    .search-container {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .search-bar {
        flex: 1;
        display: flex;
        align-items: center;
        background: var(--sky-50);
        border: 1px solid var(--stone-300);
        border-radius: 999px;
        padding: 12px 16px;
        transition: border-color 0.3s ease;
    }

    .search-bar:focus-within {
        border-color: var(--blue-700);
        box-shadow: 0 0 0 4px rgba(14,165,233,0.08);
    }

    .search-input {
        flex: 1;
        border: none;
        background: transparent;
        outline: none;
        font-size: 1rem;
        margin-left: 8px;
    }

    .search-icon { color: var(--slate-500); }

    .new-trip-btn-search {
        background: var(--blue-700);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 999px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        box-shadow: var(--shadow-soft);
        text-decoration: none;
    }

    .new-trip-btn-search:hover {
        background: var(--blue-600);
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
        color: white;
    }

    .bulk-actions {
        display: none;
        gap: 0.5rem;
        margin-bottom: 1rem;
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid var(--stone-300);
        border-radius: var(--radius);
        box-shadow: var(--shadow-soft);
        animation: slideDown 0.3s ease-out;
        justify-content: space-between;
        align-items: center;
    }

    .bulk-actions.show {
        display: flex;
    }

    .bulk-actions-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
    }

    .bulk-actions-count {
        font-weight: 600;
        color: #475569;
        font-size: 1rem;
    }

    .bulk-actions-count i {
        color: #10b981;
        margin-right: 0.5rem;
    }

    .bulk-actions-buttons {
        display: flex;
        gap: 0.5rem;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .bulk-action-btn {
        padding: 10px 18px;
        border: none;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: var(--shadow-soft);
    }

    .bulk-action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .bulk-delete-btn {
        background: #ef4444;
        color: white;
    }

    .bulk-delete-btn:hover {
        background: #dc2626;
    }

    .bulk-duplicate-btn {
        background: var(--blue-700);
        color: white;
    }

    .bulk-duplicate-btn:hover {
        background: #2563eb;
    }

    .bulk-clear-btn {
        background: #94a3b8;
        color: white;
    }

    .bulk-clear-btn:hover {
        background: #4b5563;
    }

    .trips-container {
        background: white;
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow-soft);
        border: 1px solid var(--stone-300);
    }

    .trips-header {
        background: var(--sky-50);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--stone-300);
        font-weight: 600;
        color: var(--slate-600);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .select-all-container {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .select-all-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .select-all-label {
        font-size: 0.9rem;
        color: #64748b;
        cursor: pointer;
    }

    .trip-item {
        display: flex;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid var(--stone-300);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .trip-checkbox {
        width: 18px;
        height: 18px;
        margin-right: 1rem;
        cursor: pointer;
    }

    .trip-item:hover {
        background: var(--sky-50);
        transform: translateX(2px);
    }

    .trip-item:last-child {
        border-bottom: none;
    }

    .trip-emoji {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        background: var(--sky-50);
        border-radius: 8px;
        margin-right: 1rem;
    }

    .trip-info {
        flex: 1;
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        gap: 1rem;
        align-items: center;
    }

    .trip-title {
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--ink);
    }

    .trip-dates {
        color: var(--slate-500);
        font-size: 0.9rem;
    }

    .trip-duration {
        color: var(--slate-500);
        font-size: 0.9rem;
    }

    .trip-status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .status-published { background: #e8f8ff; color: var(--blue-700); }
    .status-draft { background: #fff7e6; color: #92400e; }
    .status-sent { background: #e6f3ff; color: #1d4ed8; }
    .status-approved { background: #e8fff5; color: #065f46; }
    .status-completed { background: #eef2f6; color: #374151; }

    .trip-actions {
        display: flex;
        gap: 8px;
    }

    .action-btn {
        padding: 8px 16px;
        border: none;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-soft);
    }

    .btn-primary { background: var(--blue-700); color: white; }
    .btn-primary:hover { background: var(--blue-600); transform: translateY(-1px); box-shadow: var(--shadow-hover); }
    .btn-secondary { background: #f1f5f9; color: var(--slate-600); border: 1px solid var(--stone-300); }
    .btn-secondary:hover { background: #e2e8f0; }
    .btn-danger {
        background: var(--danger);
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 4px;
        min-width: 40px;
        justify-content: center;
    }

    .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }

    .btn-danger:active {
        transform: translateY(0);
    }

    .btn-danger i {
        font-size: 0.9rem;
    }

    .status-select {
        padding: 4px 8px;
        border: 1px solid var(--stone-300);
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 400;
        background: white;
        cursor: pointer;
        transition: all 0.2s ease;
        min-width: 100px;
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 4px center;
        background-repeat: no-repeat;
        background-size: 12px;
        padding-right: 20px;
        color: var(--slate-500);
    }

    .status-select:focus {
        outline: none;
        border-color: var(--blue-700);
        box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.12);
    }

    .status-select:hover {
        border-color: #cbd5e1;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--slate-500);
    }

    .empty-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    @media (max-width: 768px) {
        .main-content {
            padding: 0 1rem;
        }

        .search-container {
            flex-direction: column;
            gap: 1rem;
        }

        .new-trip-btn-search {
            width: 100%;
            justify-content: center;
        }

        .trip-info {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }

        .trip-actions {
            margin-top: 1rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .action-btn {
            padding: 6px 12px;
            font-size: 0.8rem;
        }

        .status-select {
            width: 100%;
            margin-top: 0.5rem;
            min-width: 80px;
            font-size: 0.75rem;
        }

        .btn-danger {
            padding: 6px 10px;
            font-size: 0.8rem;
            min-width: 35px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    let currentFilter = '{{ $activeTab ?? "all" }}';
    let currentSearch = '';

    function filterTrips(filter) {
        currentFilter = filter;
        window.location.href = `{{ route('trips.index') }}?filter=${filter}`;
    }

    function searchTrips(query) {
        currentSearch = query;
        // Implement search functionality
        const tripItems = document.querySelectorAll('.trip-item');
        tripItems.forEach(item => {
            const title = item.querySelector('.trip-title').textContent.toLowerCase();
            if (title.includes(query.toLowerCase())) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function createNewTrip() {
        window.location.href = '{{ route("trips.create") }}';
    }

    function previewTrip(tripId) {
        window.open(`{{ url('trips') }}/${tripId}/preview`, '_blank');
    }

    function openTrip(tripId) {
        window.location.href = `{{ url('trips') }}/${tripId}/edit`;
    }

    function editTrip(tripId) {
        window.location.href = `{{ url('trips') }}/${tripId}/edit`;
    }

    function changeTripStatus(tripId, newStatus) {
        fetch(`{{ url('trips') }}/${tripId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Estado Actualizado', `El viaje ha cambiado a "${getStatusText(newStatus)}".`);
                // Update the select element's data-status attribute
                const selectElement = document.querySelector(`select[onchange*="${tripId}"]`);
                if (selectElement) {
                    selectElement.setAttribute('data-status', newStatus);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error', 'No se pudo actualizar el estado del viaje.');
        });
    }

    function deleteTrip(tripId) {
        if (confirm('¿Estás seguro de que quieres eliminar este viaje?')) {
            fetch(`{{ url('trips') }}/${tripId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Viaje Eliminado', 'El viaje ha sido eliminado exitosamente.');
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error', 'No se pudo eliminar el viaje.');
            });
        }
    }

    function getStatusText(status) {
        const statusMap = {
            'draft': 'En Diseño',
            'sent': 'Enviada',
            'approved': 'Aprobado',
            'completed': 'Completado'
        };
        return statusMap[status] || status;
    }

    // Selection functions
    function toggleSelectAll() {
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const tripCheckboxes = document.querySelectorAll('.trip-checkbox');

        tripCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });

        updateBulkActionsVisibility();
    }

    function updateSelectAllState() {
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const tripCheckboxes = document.querySelectorAll('.trip-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.trip-checkbox:checked');

        if (checkedCheckboxes.length === 0) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        } else if (checkedCheckboxes.length === tripCheckboxes.length) {
            selectAllCheckbox.checked = true;
            selectAllCheckbox.indeterminate = false;
        } else {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = true;
        }

        updateBulkActionsVisibility();
    }

    function updateBulkActionsVisibility() {
        const bulkActions = document.getElementById('bulk-actions');
        const selectedCountElement = document.getElementById('selected-count');
        const checkedCheckboxes = document.querySelectorAll('.trip-checkbox:checked');

        if (checkedCheckboxes.length > 0) {
            bulkActions.classList.add('show');
            selectedCountElement.textContent = checkedCheckboxes.length;
        } else {
            bulkActions.classList.remove('show');
            selectedCountElement.textContent = '0';
        }
    }

    function getSelectedTrips() {
        const selectedCheckboxes = document.querySelectorAll('.trip-checkbox:checked');
        return Array.from(selectedCheckboxes).map(checkbox => parseInt(checkbox.dataset.tripId));
    }

    function clearSelection() {
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const tripCheckboxes = document.querySelectorAll('.trip-checkbox');

        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;

        tripCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });

        updateBulkActionsVisibility();
    }

    function deleteSelectedTrips() {
        const selectedTripIds = getSelectedTrips();

        if (selectedTripIds.length === 0) {
            showNotification('Sin Selección', 'Por favor selecciona al menos un viaje para eliminar.');
            return;
        }

        const message = selectedTripIds.length === 1
            ? '¿Estás seguro de que quieres eliminar el viaje seleccionado?'
            : `¿Estás seguro de que quieres eliminar ${selectedTripIds.length} viajes seleccionados?`;

        if (confirm(message)) {
            // Implement bulk delete
            showNotification('Eliminando', 'Eliminando viajes seleccionados...');
            // Add actual implementation here
        }
    }

    function duplicateSelectedTrips() {
        const selectedTripIds = getSelectedTrips();

        if (selectedTripIds.length === 0) {
            showNotification('Sin Selección', 'Por favor selecciona al menos un viaje para duplicar.');
            return;
        }

        showNotification('Duplicando', 'Duplicando viajes seleccionados...');
        // Add actual implementation here
    }
</script>
@endpush
