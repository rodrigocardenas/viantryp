{{-- Vista: Editar Viaje Existente --}}
{{-- Ubicación: resources/views/trips/edit.blade.php --}}
{{-- Propósito: Vista dedicada para la edición de viajes existentes --}}
{{-- Contexto: Viaje existente con datos que cargar --}}

@extends('layouts.app')

@section('title', 'Viantryp - Editar Viaje')

@section('content')
    <x-header :showActions="true" :backUrl="route('trips.index')" :backOnclick="'showUnsavedChangesModal()'" :actions="[
        ['url' => '#', 'text' => 'Guardar', 'class' => 'btn-save', 'icon' => 'fas fa-save', 'data-action' => 'save-trip'],
        ['url' => route('trips.preview', $trip->id), 'text' => 'Vista Previa', 'class' => 'btn-preview', 'icon' => 'fas fa-eye', 'target' => '_blank'],
        ['url' => route('trips.pdf', $trip->id), 'text' => 'Descarga PDF', 'class' => 'btn-pdf', 'icon' => 'fas fa-file-pdf', 'data-action' => 'download-pdf']
    ]" />

    <!-- Contenedor del editor -->
    <div class="editor-container" id="editor-container">
        <!-- Sidebar con elementos disponibles -->
        <x-sidebar />

        <!-- Área principal -->
        <div class="editor-main">
            <div class="main-content">
                <!-- Header del viaje con datos -->
                <x-trip-header :trip="$trip" />

                <!-- Timeline con elementos existentes -->
                <x-timeline :trip="$trip" />

                <!-- Área de drop para nuevos elementos -->
                <div class="drop-zone" id="drop-zone">
                    <div class="drop-zone-content">
                        <i class="fas fa-plus-circle"></i>
                        <p>Suelta elementos aquí para añadirlos al itinerario</p>
                    </div>
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

@vite(['resources/js/editor.js', 'resources/js/modules/editor-edit-mode.js'])

@push('scripts')
<script>
    // Configuración específica para modo edición
    window.editorMode = 'edit';
    window.existingTripData = @json($trip->load(['persons', 'documents']));
</script>
@endpush
