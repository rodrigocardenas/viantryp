<nav class="nav-container">
    <div class="nav-content">
        <div class="nav-tabs">
            <button class="nav-tab {{ $activeTab === 'all' ? 'active' : '' }}" onclick="filterTrips('all')">
                Todos los Viajes
            </button>
            <button class="nav-tab {{ $activeTab === 'draft' ? 'active' : '' }}" onclick="filterTrips('draft')">
                Viajes en Diseño
            </button>
            <button class="nav-tab {{ $activeTab === 'sent' ? 'active' : '' }}" onclick="filterTrips('sent')">
                Propuestas Enviadas
            </button>
            <button class="nav-tab {{ $activeTab === 'approved' ? 'active' : '' }}" onclick="filterTrips('approved')">
                Viajes Aprobados
            </button>
            <button class="nav-tab {{ $activeTab === 'completed' ? 'active' : '' }}" onclick="filterTrips('completed')">
                Viajes Pasados
            </button>
        </div>
    </div>
</nav>

<style>
    .nav-container {
        background: transparent;
        padding: 0 2rem;
    }

    .nav-content {
        max-width: 1200px;
        margin: 0 auto;
    }

    .nav-tabs {
        display: flex;
        gap: 0.5rem;
        padding: 0.75rem 0;
    }

    .nav-tab {
        padding: 10px 14px;
        background: white;
        border: 1px solid var(--stone-300);
        border-radius: 999px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.25s ease;
        position: relative;
        color: var(--slate-600);
        box-shadow: var(--shadow-soft);
    }

    .nav-tab:hover {
        background: var(--sky-50);
        color: var(--blue-700);
    }

    .nav-tab.active {
        color: var(--blue-700);
        background: var(--blue-100);
        border-color: transparent;
    }

    @media (max-width: 768px) {
        .nav-content {
            padding: 0 1rem;
        }

        .nav-tabs {
            overflow-x: auto;
            white-space: nowrap;
        }
    }
</style>
