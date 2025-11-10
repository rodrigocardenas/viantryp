{{-- Componente: Vertical Menu --}}
{{-- Ubicación: resources/views/components/vertical-menu.blade.php --}}
{{-- Propósito: Menú vertical interactivo con elementos del viaje --}}
{{-- Props:
    - activeItem: string (elemento activo: flight, transport, hotel, activity, summary, total)
--}}

@props(['activeItem' => 'activity'])

<div class="vertical-menu">
    <div class="vertical-menu-header">
        <h3>Planificar Viaje</h3>
    </div>

    <div class="vertical-menu-items">
        <x-vertical-menu-item
            icon="fas fa-plane"
            title="Vuelo"
            description="Agregar vuelo"
            :active="$activeItem === 'flight'"
            href="#flight-section"
        />

        <x-vertical-menu-item
            icon="fas fa-route"
            title="Traslado"
            description="Tren, autobús, barco, taxi, van"
            :active="$activeItem === 'transport'"
            href="#transport-section"
        />

        <x-vertical-menu-item
            icon="fas fa-hotel"
            title="Alojamiento"
            description="Hotel o hospedaje"
            :active="$activeItem === 'hotel'"
            href="#hotel-section"
        />

        <x-vertical-menu-item
            icon="fas fa-map-marked-alt"
            title="Actividad"
            description="Tour o experiencia"
            :active="$activeItem === 'activity'"
            href="#activity-section"
        />

        <x-vertical-menu-item
            icon="fas fa-list-check"
            title="Resumen de Itinerario"
            description="Resumen automático del viaje"
            :active="$activeItem === 'summary'"
            href="#summary-section"
        />

        <x-vertical-menu-item
            icon="fas fa-dollar-sign"
            title="Valor Total"
            description="Precio total del viaje"
            :active="$activeItem === 'total'"
            href="#total-section"
        />
    </div>
</div>