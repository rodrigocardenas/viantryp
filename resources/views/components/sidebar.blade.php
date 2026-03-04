{{-- Componente: Sidebar --}}
{{-- Ubicación: resources/views/components/sidebar.blade.php --}}
{{-- Propósito: Barra lateral con elementos arrastrables del viaje --}}
@props([
    'showFlight' => true,
    'showHotel' => true,
    'showActivity' => true,
    'showTransport' => true,
    'showNote' => false,
    'showTitle' => true,
    'showParagraph' => true,
    'showExtra' => true
])

<!-- Left Sidebar -->
<div class="editor-sidebar">

  <!-- HEADER -->
  <div class="sidebar-header">
    <div class="sidebar-title sidebar-title-premium">
      ELEMENTOS
    </div>
    <span class="header-hint">Arrastra los elementos al itinerario</span>
  </div>

  <!-- BODY -->
  <div class="sidebar-body">

    <!-- ── SERVICIOS ── -->
    @if($showFlight || $showTransport || $showHotel || $showActivity)
    <div class="group">
      <div class="group-label">Servicios</div>
      <div class="group-grid">
        @if($showFlight)
            <x-sidebar.items.flight-item />
        @endif
        @if($showTransport)
            <x-sidebar.items.transport-item />
        @endif
        @if($showHotel)
            <x-sidebar.items.hotel-item />
        @endif
        @if($showActivity)
            <x-sidebar.items.activity-item />
        @endif
      </div>
    </div>
    <div class="group-divider"></div>
    @endif

    <!-- ── DISEÑO ── -->
    @if($showTitle || $showParagraph || $showNote)
    <div class="group">
      <div class="group-label">Diseño</div>
      <div class="group-grid">
        @if($showTitle)
            <x-sidebar.items.title-item />
        @endif
        @if($showParagraph)
            <x-sidebar.items.paragraph-item />
        @endif
        @if($showNote)
            <x-sidebar.items.note-item />
        @endif
      </div>
    </div>
    <div class="group-divider"></div>
    @endif

    <!-- ── INFO DEL VIAJE ── -->
    @if($showExtra)
    <div class="group">
      <div class="group-label">Info del viaje</div>
      <div class="group-grid">
        @if($showExtra)
            <x-sidebar.items.extra-item />
        @endif
      </div>
    </div>
    @endif

  </div><!-- /sidebar-body -->

</div>
