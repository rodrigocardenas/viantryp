{{-- Componente: Sidebar Transport Item --}}
{{-- Ubicación: resources/views/components/sidebar/items/transport-item.blade.php --}}
{{-- Propósito: Elemento arrastrable para transporte --}}
{{-- Props: disabled (opcional) --}}

@props(['disabled' => false])

<x-sidebar.items.base
    type="transport"
    icon="fas fa-car"
    title="Traslado"
    description="Tren, autobús, barco, taxi, van"
    :disabled="$disabled"
/>
