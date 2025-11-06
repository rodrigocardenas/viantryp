{{-- Componente: Sidebar Summary Item --}}
{{-- Ubicación: resources/views/components/sidebar/items/summary-item.blade.php --}}
{{-- Propósito: Elemento arrastrable para resumen --}}
{{-- Props: disabled (opcional) --}}

@props(['disabled' => false])

<x-sidebar.items.base
    type="summary"
    icon="fas fa-list-check"
    title="Resumen de Itinerario"
    description="Resumen automático del viaje"
    :disabled="$disabled"
/>
