{{-- Componente: Sidebar Hotel Item --}}
{{-- Ubicación: resources/views/components/sidebar/items/hotel-item.blade.php --}}
{{-- Propósito: Elemento arrastrable para hoteles --}}
{{-- Props: disabled (opcional) --}}

@props(['disabled' => false])

<x-sidebar.items.base
    type="hotel"
    icon="fas fa-bed"
    title="Alojamiento"
    description="Hotel o hospedaje"
    :disabled="$disabled"
/>
