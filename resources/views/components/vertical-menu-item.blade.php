{{-- Componente: Vertical Menu Item --}}
{{-- Ubicación: resources/views/components/vertical-menu-item.blade.php --}}
{{-- Propósito: Elemento reutilizable para menú vertical interactivo --}}
{{-- Props:
    - icon: string (emoji o clase de FontAwesome)
    - title: string (título del elemento)
    - description: string (descripción opcional)
    - active: boolean (estado activo)
    - href: string (enlace opcional)
--}}

@props([
    'icon' => '📍',
    'title' => 'Elemento',
    'description' => '',
    'active' => false,
    'href' => '#'
])

<div class="vertical-menu-item {{ $active ? 'active' : '' }}"
     @if($href !== '#') onclick="window.location.href='{{ $href }}'" @endif>
    <div class="menu-item-icon">
        @if(str_contains($icon, '.svg') || str_contains($icon, 'fas '))
            <img src="{{ $icon }}" alt="" class="menu-icon-svg" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';" />
            <i class="{{ str_contains($icon, 'fas ') ? $icon : 'fas fa-circle' }}" style="display: none;"></i>
        @else
            {{ $icon }}
        @endif
    </div>
    <div class="menu-item-content">
        <h4 class="menu-item-title">{{ $title }}</h4>
        @if($description)
            <p class="menu-item-description">{{ $description }}</p>
        @endif
    </div>
    <div class="menu-item-arrow">
        <i class="fas fa-chevron-right"></i>
    </div>
</div>