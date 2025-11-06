{{-- Componente: Sidebar Note Item --}}
{{-- Ubicación: resources/views/components/sidebar/items/note-item.blade.php --}}
{{-- Propósito: Elemento arrastrable para notas --}}
{{-- Props: disabled (opcional) --}}

@props(['disabled' => false])

<x-sidebar.items.base
    type="note"
    icon="fas fa-sticky-note"
    title="Nota"
    description="Información adicional"
    :disabled="$disabled"
/>
