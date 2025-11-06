@extends('layouts.app')

@section('title', 'Viantryp - Gestión de Viajes')

@section('content')
    <x-header />

    <x-navigation :activeTab="$activeTab ?? 'all'" />

    <!-- Main Content -->
    <main class="main-content">
        <!-- Search Section -->
        <div class="search-section">
            <div class="search-container">
                <div class="search-bar">
                    <span class="search-icon"><i class="fas fa-search"></i></span>
                    <input type="text" class="search-input" placeholder="Buscar viajes..." oninput="searchTrips(this.value)">
                </div>
                <a href="{{ route('trips.create') }}" class="new-trip-btn-search">
                    <i class="fas fa-plus"></i>
                    Crear Viaje
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
            <!-- Column Headers -->
            <div class="trips-column-headers">
                <div class="header-checkbox">
                    <input type="checkbox" id="select-all-checkbox" class="select-all-checkbox" onchange="toggleSelectAll()">
                </div>
                <div class="header-code">ID</div>
                <div class="header-info">
                    <div class="header-title">Nombre del Viaje</div>
                    <div class="header-dates">Fecha del Viaje</div>
                    <div class="header-status">Estado</div>
                </div>
                <div class="header-actions">Acciones</div>
            </div>

            <div id="trips-list">
                @if(count($trips) > 0)
                    @foreach($trips as $trip)
                        <div class="trip-item" onclick="openTrip({{ $trip->id }})">
                            <input type="checkbox" class="trip-checkbox" data-trip-id="{{ $trip->id }}" onclick="event.stopPropagation();" onchange="updateSelectAllState()">
                            <div class="trip-code">
                                <span class="code-display" onclick="event.stopPropagation(); editTripCode({{ $trip->id }}, '{{ $trip->code }}')">{{ $trip->code ?? 'N/A' }}</span>
                                <input type="text" class="code-input" id="code-input-{{ $trip->id }}" style="display: none;" onblur="saveTripCode({{ $trip->id }})" onkeypress="handleCodeKeyPress(event, {{ $trip->id }})" maxlength="20">
                            </div>
                            <div class="trip-info">
                                <div class="trip-title">{{ $trip->title }}</div>
                                <div class="trip-dates">{{ $trip->getFormattedDates() }}</div>
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
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn btn-secondary" onclick="event.stopPropagation(); editTrip({{ $trip->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-btn btn-info" onclick="event.stopPropagation(); openEmailModal({{ $trip->id }}, '{{ $trip->title }}')">
                                    <i class="fas fa-envelope"></i>
                                </button>
                                <button class="action-btn btn-danger" onclick="event.stopPropagation(); deleteTrip({{ $trip->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fas fa-clipboard-list"></i></div>
                        <p>No se encontraron viajes</p>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Email Modal -->
    <div id="email-modal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Enviar viaje por correo</h3>
                <button class="modal-close" onclick="closeEmailModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p id="email-modal-trip-title"></p>
                <form id="email-form">
                    <div class="form-group">
                        <label for="email-address">Dirección de correo electrónico:</label>
                        <input type="email" id="email-address" name="email" required placeholder="cliente@ejemplo.com">
                    </div>
                    <div class="form-group">
                        <label for="email-message">Mensaje opcional:</label>
                        <textarea id="email-message" name="message" rows="3" placeholder="Mensaje personalizado (opcional)"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeEmailModal()">Cancelar</button>
                <button class="btn-primary" onclick="sendEmail()" id="send-email-btn">
                    <i class="fas fa-paper-plane"></i>
                    Enviar
                </button>
            </div>
        </div>
    </div>
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
        background: white;
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
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 999px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        box-shadow: 0 4px 16px rgba(245, 158, 11, 0.3);
        text-decoration: none;
        position: relative;
        overflow: hidden;
    }

    .new-trip-btn-search::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .new-trip-btn-search:hover::before {
        left: 100%;
    }

    .new-trip-btn-search:hover {
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
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
        min-height: calc(100vh - 200px); /* Ensure it extends to near the bottom of the viewport */
    }

    .trips-column-headers {
        background: var(--sky-50);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--stone-300);
        display: flex;
        align-items: center;
        font-weight: 600;
        color: var(--slate-600);
        font-size: 0.9rem;
    }

    .header-checkbox {
        width: 18px;
        margin-right: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .header-checkbox .select-all-checkbox {
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    .header-code {
        width: 120px;
        margin-right: 1rem;
        font-weight: 600;
    }

    .header-info {
        flex: 1;
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1rem;
        align-items: center;
    }

    .header-title, .header-dates, .header-status {
        font-weight: 600;
        text-align: left;
    }

    .header-actions {
        display: flex;
        gap: 8px;
        width: 160px;
        justify-content: center;
        font-weight: 600;
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

    .trip-code {
        width: 120px;
        margin-right: 1rem;
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--slate-500);
        text-align: left;
        cursor: pointer;
    }

    .code-display {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        transition: background-color 0.2s ease;
    }

    .code-display:hover {
        background: white;
        border: 1px solid var(--stone-300);
        border-radius: 4px;
    }


    .code-input {
        width: 100px;
        padding: 0.25rem 0.5rem;
        border: 1px solid var(--stone-300);
        border-radius: 4px;
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .trip-item:hover {
        background: var(--sky-50);
        transform: translateX(2px);
    }

    .trip-item:last-child {
        border-bottom: none;
    }

    .trip-info {
        flex: 1;
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
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

    .btn-info {
        background: #06b6d4;
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

    .btn-info:hover {
        background: #0891b2;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(6, 182, 212, 0.3);
    }

    .btn-info:active {
        transform: translateY(0);
    }

    .btn-info i {
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

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .modal-content {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow-hover);
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--stone-300);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        margin: 0;
        color: var(--ink);
        font-size: 1.25rem;
        font-weight: 600;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--slate-500);
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s ease;
    }

    .modal-close:hover {
        background: var(--stone-100);
        color: var(--ink);
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-body p {
        margin: 0 0 1rem 0;
        color: var(--slate-600);
        font-weight: 500;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--slate-600);
        font-weight: 500;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--stone-300);
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.2s ease;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--blue-700);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }

    .modal-footer {
        padding: 1.5rem;
        border-top: 1px solid var(--stone-300);
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
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

        .trips-column-headers {
            display: none;
        }

        .trip-code {
            width: auto;
            margin-right: 0.5rem;
            font-size: 0.8rem;
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

        .btn-info {
            padding: 6px 10px;
            font-size: 0.8rem;
            min-width: 35px;
        }

        .modal-content {
            width: 95%;
            margin: 1rem;
        }

        .modal-header,
        .modal-body,
        .modal-footer {
            padding: 1rem;
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
            fetch(`{{ url('trips/bulk-delete') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ trip_ids: selectedTripIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Viajes Eliminados', `${selectedTripIds.length} viaje(s) eliminado(s) exitosamente.`);
                    location.reload();
                } else {
                    showNotification('Error', data.message || 'No se pudieron eliminar los viajes.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error', 'Ocurrió un error al eliminar los viajes.');
            });
        }
    }

    function duplicateSelectedTrips() {
        const selectedTripIds = getSelectedTrips();

        if (selectedTripIds.length === 0) {
            showNotification('Sin Selección', 'Por favor selecciona al menos un viaje para duplicar.');
            return;
        }

        fetch(`{{ url('trips/bulk-duplicate') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ trip_ids: selectedTripIds })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Viajes Duplicados', `${selectedTripIds.length} viaje(s) duplicado(s) exitosamente.`);
                location.reload();
            } else {
                showNotification('Error', data.message || 'No se pudieron duplicar los viajes.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error', 'Ocurrió un error al duplicar los viajes.');
        });
    }

    function editTripCode(tripId, currentCode) {
        const displaySpan = document.querySelector(`.code-display[onclick*="${tripId}"]`);
        const inputField = document.getElementById(`code-input-${tripId}`);

        if (displaySpan && inputField) {
            displaySpan.style.display = 'none';
            inputField.style.display = 'inline-block';
            inputField.value = currentCode;
            inputField.focus();
            inputField.select();
        }
    }

    function saveTripCode(tripId) {
        const inputField = document.getElementById(`code-input-${tripId}`);
        const displaySpan = document.querySelector(`.code-display[onclick*="${tripId}"]`);
        const newCode = inputField.value.trim().toUpperCase();
        const currentCode = displaySpan.textContent.trim();

        if (displaySpan && inputField) {
            // If code hasn't changed, just hide input and show display
            if (newCode === currentCode) {
                inputField.style.display = 'none';
                displaySpan.style.display = 'inline';
                return;
            }

            // If code changed, save it first, then update UI only on success
            fetch(`{{ url('trips') }}/${tripId}/code`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ code: newCode })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Only hide input and show display after successful API response
                    inputField.style.display = 'none';
                    displaySpan.style.display = 'inline';
                    displaySpan.textContent = newCode;
                    showNotification('Identificador Actualizado', 'El identificador del viaje ha sido actualizado.');
                } else {
                    // Keep input visible on error so user can retry
                    showNotification('Error', data.message || 'No se pudo actualizar el identificador.');
                    inputField.value = currentCode;
                    inputField.focus();
                    inputField.select();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Keep input visible on error so user can retry
                showNotification('Error', 'Ocurrió un error al actualizar el identificador.');
                inputField.value = currentCode;
                inputField.focus();
                inputField.select();
            });
        }
    }

    function handleCodeKeyPress(event, tripId) {
        if (event.key === 'Enter') {
            saveTripCode(tripId);
        } else if (event.key === 'Escape') {
            const inputField = document.getElementById(`code-input-${tripId}`);
            const displaySpan = document.querySelector(`.code-display[onclick*="${tripId}"]`);
            if (inputField && displaySpan) {
                inputField.style.display = 'none';
                displaySpan.style.display = 'inline';
                inputField.value = displaySpan.textContent.trim();
            }
        }
    }


    // Email modal functions
    let currentTripId = null;

    function openEmailModal(tripId, tripTitle) {
        currentTripId = tripId;
        document.getElementById('email-modal-trip-title').textContent = `Enviar "${tripTitle}" por correo`;
        document.getElementById('email-modal').style.display = 'flex';
        document.getElementById('email-address').focus();
        document.getElementById('email-form').reset();
    }

    function closeEmailModal() {
        document.getElementById('email-modal').style.display = 'none';
        currentTripId = null;
    }

    function sendEmail() {
        const email = document.getElementById('email-address').value.trim();
        const message = document.getElementById('email-message').value.trim();

        if (!email) {
            showNotification('Error', 'Por favor ingresa una dirección de correo válida.');
            return;
        }

        if (!currentTripId) {
            showNotification('Error', 'No se pudo identificar el viaje.');
            return;
        }

        const sendBtn = document.getElementById('send-email-btn');
        const originalText = sendBtn.innerHTML;
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
        sendBtn.disabled = true;

        fetch(`{{ url('trips') }}/${currentTripId}/send-email`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                email: email,
                message: message
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Correo Enviado', 'El enlace del viaje ha sido enviado exitosamente.');
                closeEmailModal();
            } else {
                showNotification('Error', data.message || 'No se pudo enviar el correo.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error', 'Ocurrió un error al enviar el correo.');
        })
        .finally(() => {
            sendBtn.innerHTML = originalText;
            sendBtn.disabled = false;
        });
    }

    // Close modal when clicking outside
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('email-modal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeEmailModal();
                }
            });
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal && modal.style.display === 'flex') {
                closeEmailModal();
            }
        });
    });
</script>
@endpush
