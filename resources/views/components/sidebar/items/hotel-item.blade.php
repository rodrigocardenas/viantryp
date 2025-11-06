{{-- Componente: Sidebar Hotel Item --}}
{{-- Ubicación: resources/views/components/sidebar/items/hotel-item.blade.php --}}
{{-- Propósito: Elemento arrastrable para hoteles --}}
{{-- Props: disabled (opcional) --}}

@props(['disabled' => false])

<x-sidebar.items.base
    type="hotel"
    icon="{{ asset('images/icons/hotel.svg') }}"
    title="Alojamiento"
    description="Agregar hospedaje"
    :disabled="$disabled"
/>
