{{-- Componente: Sidebar Transport Item --}}
{{-- Ubicación: resources/views/components/sidebar/items/transport-item.blade.php --}}
{{-- Propósito: Elemento arrastrable para transporte --}}
{{-- Props: disabled (opcional) --}}

@props(['disabled' => false])

<x-sidebar.items.base
    type="transport"
    icon="{{ asset('images/icons/transport.svg') }}"
    title="Traslado"
    description="Tren, autobus, barco u otro"
    :disabled="$disabled"
/>
