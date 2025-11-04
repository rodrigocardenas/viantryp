@extends('layouts.app')

@section('title', 'Viantryp - Gestión de Aerolíneas')

@section('content')
    <x-header :showActions="true" :actions="[
        ['url' => route('airlines.create'), 'text' => 'Nueva Aerolínea', 'class' => 'btn-success', 'icon' => 'fas fa-plus']
    ]" />

    <!-- Main Content -->
    <main class="main-content">
        <!-- Search Section -->
        <div class="search-section">
            <div class="search-container">
                <div class="search-bar">
                    <span class="search-icon">🔍</span>
                    <input type="text" class="search-input" placeholder="Buscar aerolíneas..." oninput="searchAirlines(this.value)">
                </div>
                <a href="{{ route('airlines.create') }}" class="new-airline-btn-search">
                    <span>+</span>
                    Nueva Aerolínea
                </a>
            </div>
        </div>

        <!-- Airlines List -->
        <div class="airlines-container">
            <div class="airlines-header">
                <span>Aerolíneas</span>
            </div>
            <div id="airlines-list">
                @if(count($airlines) > 0)
                    @foreach($airlines as $airline)
                        <div class="airline-item">
                            <div class="airline-logo">
                                @if($airline->logo_path)
                                    <img src="{{ asset('storage/' . $airline->logo_path) }}" alt="{{ $airline->name }} logo" class="logo-image">
                                @else
                                    <div class="no-logo">✈️</div>
                                @endif
                            </div>
                            <div class="airline-info">
                                <div class="airline-name">{{ $airline->name }}</div>
                                <div class="airline-code">{{ $airline->carrier_code }}</div>
                            </div>
                            <div class="airline-actions">
                                <button class="action-btn btn-primary" onclick="viewAirline({{ $airline->id }})">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn btn-secondary" onclick="editAirline({{ $airline->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-btn btn-danger" onclick="deleteAirline({{ $airline->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <div class="empty-icon">✈️</div>
                        <p>No se encontraron aerolíneas</p>
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

    .new-airline-btn-search {
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

    .new-airline-btn-search:hover {
        background: var(--blue-600);
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
        color: white;
    }

    .airlines-container {
        background: white;
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow-soft);
        border: 1px solid var(--stone-300);
    }

    .airlines-header {
        background: var(--sky-50);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--stone-300);
        font-weight: 600;
        color: var(--slate-600);
    }

    .airline-item {
        display: flex;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid var(--stone-300);
        transition: all 0.3s ease;
    }

    .airline-item:hover {
        background: var(--sky-50);
    }

    .airline-item:last-child {
        border-bottom: none;
    }

    .airline-logo {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        border-radius: 8px;
        overflow: hidden;
        background: var(--sky-50);
    }

    .logo-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .no-logo {
        font-size: 1.5rem;
        color: var(--slate-500);
    }

    .airline-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .airline-name {
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--ink);
    }

    .airline-code {
        color: var(--slate-500);
        font-size: 0.9rem;
        font-weight: 500;
    }

    .airline-actions {
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

        .new-airline-btn-search {
            width: 100%;
            justify-content: center;
        }

        .airline-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .airline-logo {
            align-self: center;
        }

        .airline-actions {
            align-self: center;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function searchAirlines(query) {
        const airlineItems = document.querySelectorAll('.airline-item');
        airlineItems.forEach(item => {
            const name = item.querySelector('.airline-name').textContent.toLowerCase();
            const code = item.querySelector('.airline-code').textContent.toLowerCase();
            if (name.includes(query.toLowerCase()) || code.includes(query.toLowerCase())) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function viewAirline(airlineId) {
        window.location.href = `{{ url('airlines') }}/${airlineId}`;
    }

    function editAirline(airlineId) {
        window.location.href = `{{ url('airlines') }}/${airlineId}/edit`;
    }

    function deleteAirline(airlineId) {
        if (confirm('¿Estás seguro de que quieres eliminar esta aerolínea?')) {
            fetch(`{{ url('airlines') }}/${airlineId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Aerolínea Eliminada', 'La aerolínea ha sido eliminada exitosamente.');
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error', 'No se pudo eliminar la aerolínea.');
            });
        }
    }
</script>
@endpush
