{{-- Componente: Sidebar Activity Item --}}
{{-- Ubicación: resources/views/components/sidebar/items/activity-item.blade.php --}}
{{-- Propósito: Elemento arrastrable para actividades --}}
{{-- Props: disabled (opcional) --}}

@props(['disabled' => false])

<x-sidebar.items.base
    type="activity"
    icon="fas fa-map-marker-alt"
    title="Agregar Actividad, tour o experiencia"
    description="Tour o experiencia"
    :disabled="$disabled"
/>
