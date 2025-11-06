{{-- Componente: Sidebar --}}
{{-- Ubicación: resources/views/components/editor/sidebar.blade.php --}}
{{-- Propósito: Barra lateral con elementos arrastrables del viaje --}}
{{-- Props: ninguno --}}

<!-- Left Sidebar -->
<div class="editor-sidebar">
    <div class="sidebar-content">
        <div class="sidebar-section">
            <h4>Elementos del Viaje</h4>
            <div class="element-categories">
                <x-sidebar.items.flight-item />
                <x-sidebar.items.hotel-item />
                <x-sidebar.items.activity-item />
                <x-sidebar.items.transport-item />
                <x-sidebar.items.note-item />
                <x-sidebar.items.summary-item />
                <x-sidebar.items.total-item />
            </div>
        </div>
    </div>
</div>
