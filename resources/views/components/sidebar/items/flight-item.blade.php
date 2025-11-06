{{-- Componente: Sidebar Flight Item --}}
{{-- Ubicación: resources/views/components/sidebar/items/flight-item.blade.php --}}
{{-- Propósito: Elemento arrastrable para vuelos --}}
{{-- Props: disabled (opcional) --}}

@props(['disabled' => false])

<x-sidebar.items.base
    type="flight"
    icon="fas fa-plane"
    title="Vuelo"
    description="Aerolinea y horarios"
    :disabled="$disabled"
/>
