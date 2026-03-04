{{-- Componente: Sidebar Item Base --}}
{{-- Ubicación: resources/views/components/sidebar/items/base.blade.php --}}
{{-- Propósito: Componente base reutilizable para elementos arrastrables del sidebar --}}

@props([
    'type' => 'unknown',
    'title' => 'Elemento',
    'description' => 'Descripción',
    'disabled' => false
])

<div class="drag-card c-{{ $type }} {{ $disabled ? 'disabled' : '' }}"
     draggable="{{ $disabled ? 'false' : 'true' }}"
     title="{{ $disabled ? 'Próximamente' : 'Arrastra al itinerario' }}"
     data-type="{{ $type }}"
     @if(!$disabled) ondragstart="drag(event)" @endif>

    <div class="card-icon">
        {{ $icon }}
    </div>

    <div>
        <div class="card-name">{{ $title }}</div>
        <div class="card-desc">{{ $description }}</div>
    </div>
</div>
