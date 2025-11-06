{{-- Componente: Sidebar Activity Item --}}
{{-- Ubicación: resources/views/components/sidebar/items/activity-item.blade.php --}}
{{-- Propósito: Elemento arrastrable para actividades --}}
{{-- Props: disabled (opcional) --}}

@props(['disabled' => false])

<x-sidebar.items.base
    type="activity"
    icon="{{ asset('images/icons/activity.svg') }}"
    title="Actividad"
    description="Agregar tour o experiencia"
    :disabled="$disabled"
/>
