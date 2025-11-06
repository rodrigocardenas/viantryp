{{-- Componente: Sidebar Total Item --}}
{{-- Ubicación: resources/views/components/sidebar/items/total-item.blade.php --}}
{{-- Propósito: Elemento arrastrable para total --}}
{{-- Props: disabled (opcional) --}}

@props(['disabled' => false])

<x-sidebar.items.base
    type="total"
    icon="fas fa-dollar-sign"
    title="Valor Total"
    description="Precio total del viaje"
    :disabled="$disabled"
/>
