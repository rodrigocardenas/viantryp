{{-- Componente: Sidebar Item Base --}}
{{-- Ubicación: resources/views/components/sidebar/items/base.blade.php --}}
{{-- Propósito: Componente base reutilizable para elementos arrastrables del sidebar --}}
{{-- Props:
    - type: string (flight, hotel, activity, transport, note, summary, total)
    - icon: string (clase de FontAwesome)
    - title: string (título del elemento)
    - description: string (descripción del elemento)
    - disabled: boolean (opcional, para deshabilitar el elemento)
--}}

@props([
    'type' => 'unknown',
    'icon' => 'fas fa-circle',
    'title' => 'Elemento',
    'description' => 'Descripción',
    'disabled' => false
])

<div class="element-category {{ $disabled ? 'disabled' : '' }}"
     draggable="{{ $disabled ? 'false' : 'true' }}"
     data-type="{{ $type }}"
     @if(!$disabled) ondragstart="drag(event)" @endif
     @if($disabled) style="opacity: 0.5; cursor: not-allowed;" @endif>

    <div class="category-icon {{ $type }}-icon">
        <i class="{{ $icon }}"></i>
    </div>

    <div class="category-info">
        <h5>{{ $title }}</h5>
        <p>{{ $description }}</p>
    </div>

    @if($disabled)
        <div class="category-badge">
            <span class="badge badge-secondary">Próximamente</span>
        </div>
    @endif
</div>
