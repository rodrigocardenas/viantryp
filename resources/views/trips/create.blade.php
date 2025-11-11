{{-- Vista: Crear Nuevo Viaje --}}
{{-- Ubicació@push('styles')
    <link rel="stylesheet" href="{{ asset('css/editor.css') }}?v={{ time() }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpushurces/views/trips/create.blade.php --}}
{{-- Propósito: Vista dedicada para la creación inicial de viajes --}}
{{-- Contexto: Viaje nuevo, sin datos existentes --}}

@extends('layouts.app')

@section('title', 'Viantryp - Crear Nuevo Viaje')

@section('content')
    <x-header :showActions="true" :backUrl="route('trips.index')" />

    <!-- Modal para crear nuevo viaje -->
    <x-new-trip-modal />



    <!-- Contenedor del editor (inicialmente oculto) -->
    <div class="editor-container" id="editor-container" style="display: none;">
        <!-- Sidebar con todos los elementos disponibles -->
        <x-sidebar />

        <!-- Área principal -->
        <div class="editor-main">
            <div class="main-content">
                <!-- Header del viaje (vacío inicialmente) -->
                <x-trip-header :trip="null" />

                <!-- Timeline vacío -->
                <x-timeline :trip="null" />

                <!-- Mensaje de bienvenida (oculto inicialmente) -->
                <div class="welcome-message" id="welcome-message" style="display: none;">
                    <div class="welcome-icon">
                        <i class="fas fa-magic"></i>
                    </div>
                    <h3>¡Tu viaje ha sido creado!</h3>
                    <p>Arrastra elementos desde la barra lateral para empezar a construir tu itinerario.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modales compartidos -->
    <x-element-modal />
    <x-unsaved-changes-modal />
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/editor.css') }}?v={{ time() }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush

@vite(['resources/js/editor.js'])

@push('scripts')
<script>
    // Configuración específica para modo creación
    window.editorMode = 'create';
    window.existingTripData = null;

    // Mostrar modal de nuevo viaje automáticamente
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            const modal = document.getElementById('new-trip-modal');
            if (modal) {
                modal.classList.add('show');
            }
        }, 500);
    });
</script>
@endpush
