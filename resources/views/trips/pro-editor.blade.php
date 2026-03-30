<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Viantryp | Editor de Itinerario</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800;900&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.places_api_key', env('GOOGLE_PLACES_API_KEY')) }}&libraries=places"></script>
    <link href="{{ asset('css/trips/pro-editor.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.css"/>
    <style>
        /* Driver.js Custom Styles (Consistent with Dashboard) */
        .driver-popover {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border: 1px solid #e2e8ef;
            font-family: 'Barlow', sans-serif;
        }
        .driver-popover-title {
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 800;
            font-size: 20px;
            color: #1a2e2c;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .driver-popover-description {
            font-size: 14px;
            color: #64748b;
            line-height: 1.5;
            margin-top: 8px;
        }
        .driver-popover-btn {
            background: #1ebdb1;
            color: white;
            text-shadow: none;
            border: none;
            padding: 6px 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 12px;
            transition: all 0.2s;
        }
        .driver-popover-btn:hover { background: #16a69b; }
        .driver-popover-close-btn { color: #8f9db0; }
        .driver-popover-arrow { border-color: #ffffff; }

        .btn-help {
            width: 32px; height: 32px; border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.1);
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,0.8); cursor: pointer; transition: all 0.2s;
            text-decoration: none; font-size: 14px; margin-right: 8px;
        }
        .btn-help:hover {
            background: rgba(255,255,255,0.2); color: white; border-color: white;
            transform: translateY(-1px);
        }

        /* Multi-element highlight helper - Refined: removed green border */
        .tutorial-extra-highlight {
            z-index: 1000004 !important; /* Above Driver.js overlay */
            position: relative !important;
            transition: all 0.2s ease !important;
        }
        .secondary-nav-link {
            cursor: pointer;
            background: rgba(255, 255, 255, 0.1) !important;
            color: white !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            padding: 6px 18px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.15s;
            font-family: 'Barlow', sans-serif;
            height: 32px;
            z-index: 1;
        }
        .secondary-nav-link:hover {
            background: rgba(255, 255, 255, 0.2) !important;
            border-color: white !important;
            color: white !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .secondary-nav-link i {
            margin-right: 6px;
        }

        @media (max-width: 768px) {
            .btn-help { display: none !important; }
        }
    </style>
    @auth
        @include('layouts.theme-styles')
    @endauth
    <script>
        window.viantrypUserName = "{{ auth()->user()->display_name ?? 'Invitado' }}";
        window.viantrypThemeColor = "{{ auth()->user()->theme_color ?? 'default' }}";
        window.viantrypDisplayNameType = "{{ auth()->user()->display_name_type ?? 'personal' }}";
        window.viantrypAgencyLogo = "{{ auth()->user()->agency_logo ? asset('storage/' . auth()->user()->agency_logo) : '' }}";
        window.viantrypAgencyName = @json(auth()->user()->agency_name ?? '');
        window.viantrypUserFullName = @json(auth()->user()->name . ' ' . auth()->user()->last_name);
        window.ViantrypTutorials = @json(auth()->user()->tutorials_seen ?? []);
        window.isFirstTrip = @json($isFirstTrip ?? false);
        window.tripId = {{ $trip->id ?? 'null' }};
        window.proStatus = "{{ $trip->status ?? 'draft' }}";
        window.proState = @json($trip->pro_state ?? null);
    </script>
</head>
<body>
<div class="topbar">
  <div class="topbar-bg-decorators"></div>
  <button class="sidebar-toggle" id="sidebarToggle" onclick="toggleSidebar()" title="Elementos"><i class="fa-solid fa-bars"></i></button>
  
  <a href="{{ route('home') }}" class="logo-link">
      <img src="/images/logo-viantryp.png" alt="Viantryp" class="topbar-logo-img">
  </a>

  <div class="topbar-div"></div>
  
  <span class="header-subtitle" style="color: rgba(255, 255, 255, 0.9); font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 0.75rem; margin-left: 12px; z-index: 1;">
      Editor de itinerario
  </span>
  
  <div class="topbar-spacer"></div>
  
  <div class="topbar-right">
      <div class="topbar-left">
          <button class="nav-link" style="border:none; cursor:pointer;" data-action="back" onclick="confirmExit()">
              <i class="fas fa-arrow-left" style="margin-right:4px;"></i> <span>Volver</span>
          </button>
      </div>
      <div class="topbar-actions">
          <button class="secondary-nav-link" onclick="manualSaveProTrip()">
              <i class="fa-solid fa-floppy-disk"></i> <span>Guardar cambios</span>
          </button>
          <button id="btnPreviewTrip" class="secondary-nav-link" onclick="openPreview()">
              <i class="fa-solid fa-eye"></i> <span>Vista previa</span>
          </button>
      </div>

      @auth
      <a href="javascript:void(0)" onclick="initEditorTutorial(true)" class="btn-help" title="Ayuda / Tutorial">
          <i class="fas fa-question-circle"></i>
      </a>
      <div class="user-profile-dropdown" style="position: relative;">
          <div class="ubadge" id="profileTrigger" style="cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 8px; padding: 4px 14px 4px 4px;">
            <div class="avatar" id="navAvatar" style="overflow: hidden;">
              @if(auth()->user()->avatar)
                  <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
              @else
                  {{ auth()->user()->display_initials }}
              @endif
            </div>
            <span class="uname" style="display: none; font-size: 12px; font-weight: 600; color: rgba(255,255,255,0.85);">{{ auth()->user()->display_name }}</span>
            <i class="fas fa-chevron-down" style="font-size: 10px; color: #fbfbfb;"></i>
          </div>
          
          <div id="profileMenu" class="dropdown-menu-content" style="display: none; position: absolute; top: calc(100% + 10px); right: 0; background: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 180px; overflow: hidden; z-index: 1000; border: 1px solid #e2e8ef; text-align: left;">
              <a href="{{ route('trips.index') }}" class="dropdown-item" style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: #1a2e2c; text-decoration: none; font-size: 13px; font-weight: 500; transition: background 0.2s;">
                  <i class="fas fa-suitcase-rolling" style="color: #64748b; font-size: 15px;"></i>
                  Mis viajes
              </a>
              <div style="height: 1px; background: #e2e8ef;"></div>
              <a href="{{ route('profile.index') }}" class="dropdown-item" style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: #1a2e2c; text-decoration: none; font-size: 13px; font-weight: 500; transition: background 0.2s;">
                  <i class="fas fa-user-circle" style="color: #64748b; font-size: 15px;"></i>
                  Mi perfil
              </a>
              <div style="height: 1px; background: #e2e8ef;"></div>
              <form method="POST" action="{{ route('logout') }}" id="logout-form" style="margin: 0;">
                  @csrf
                  <button type="submit" class="dropdown-item" style="width: 100%; border: none; background: transparent; display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: #c0392b; cursor: pointer; text-align: left; font-size: 13px; font-weight: 500; transition: background 0.2s; font-family: 'DM Sans', sans-serif;">
                      <i class="fas fa-sign-out-alt" style="font-size: 15px;"></i>
                      Cerrar sesión
                  </button>
              </form>
          </div>
      </div>
      
      <script>
          (function() {
              const initMenu = () => {
                  const trigger = document.getElementById('profileTrigger');
                  const menu = document.getElementById('profileMenu');
                  if (trigger && menu) {
                      trigger.addEventListener('click', function(e) {
                          e.stopPropagation();
                          const isVisible = menu.style.display === 'block';
                          menu.style.display = isVisible ? 'none' : 'block';
                          trigger.style.background = isVisible ? 'transparent' : 'rgba(255,255,255,0.08)';
                      });
                      document.addEventListener('click', function(e) {
                          if (!trigger.contains(e.target) && !menu.contains(e.target)) {
                              menu.style.display = 'none';
                              trigger.style.background = 'transparent';
                          }
                      });
                      const items = menu.querySelectorAll('.dropdown-item');
                      items.forEach(item => {
                          item.addEventListener('mouseover', () => item.style.background = '#f8fafc');
                          item.addEventListener('mouseout', () => item.style.background = 'transparent');
                      });
                  }
              };
              if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initMenu);
              else initMenu();
          })();
      </script>
      @endauth
  </div>
</div>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
<div class="main">
  <div class="sidebar">
    <div class="sidebar-scroll">
      <div class="sidebar-section">
        <div class="section-label">Servicios</div>
        <div class="element-grid">
          <div class="element-card type-flight" draggable="true" data-type="flight" data-label="Vuelo">
            <div class="el-drag-handle"><i class="fa-solid fa-ellipsis-vertical"></i><i class="fa-solid fa-ellipsis-vertical"></i></div>
            <div class="el-icon"><i class="fa-solid fa-plane"></i></div>
            <div class="el-info"><div class="el-name">Vuelo</div><div class="el-sub">Agregar vuelo</div></div>
          </div>
          <div class="element-card type-alojamiento" draggable="true" data-type="alojamiento" data-label="Alojamiento">
            <div class="el-drag-handle"><i class="fa-solid fa-ellipsis-vertical"></i><i class="fa-solid fa-ellipsis-vertical"></i></div>
            <div class="el-icon"><i class="fa-solid fa-hotel"></i></div>
            <div class="el-info"><div class="el-name">Alojamiento</div><div class="el-sub">Hotel u hospedaje</div></div>
          </div>
          <div class="element-card type-transporte" draggable="true" data-type="transporte" data-label="Transporte">
            <div class="el-drag-handle"><i class="fa-solid fa-ellipsis-vertical"></i><i class="fa-solid fa-ellipsis-vertical"></i></div>
            <div class="el-icon"><i class="fa-solid fa-car"></i></div>
            <div class="el-info"><div class="el-name">Traslado</div><div class="el-sub">Bus, tren u otro</div></div>
          </div>
          <div class="element-card type-actividad" draggable="true" data-type="actividad" data-label="Actividad">
            <div class="el-drag-handle"><i class="fa-solid fa-ellipsis-vertical"></i><i class="fa-solid fa-ellipsis-vertical"></i></div>
            <div class="el-icon"><i class="fa-solid fa-bullseye"></i></div>
            <div class="el-info"><div class="el-name">Actividad</div><div class="el-sub">Tour o experiencia</div></div>
          </div>
          <div class="element-card type-comida" draggable="true" data-type="comida" data-label="Comida">
            <div class="el-drag-handle"><i class="fa-solid fa-ellipsis-vertical"></i><i class="fa-solid fa-ellipsis-vertical"></i></div>
            <div class="el-icon"><i class="fa-solid fa-utensils"></i></div>
            <div class="el-info"><div class="el-name">Comida</div><div class="el-sub">Restaurante y más</div></div>
          </div>
          <div class="element-card type-tour" draggable="true" data-type="tour" data-label="Tour">
            <div class="el-drag-handle"><i class="fa-solid fa-ellipsis-vertical"></i><i class="fa-solid fa-ellipsis-vertical"></i></div>
            <div class="el-icon"><i class="fa-solid fa-map-location-dot"></i></div>
            <div class="el-info"><div class="el-name">Tour</div><div class="el-sub">Guías y grupos</div></div>
          </div>
        </div>
      </div>
      <div class="sidebar-section">
        <div class="section-label">Diseño</div>
        <div class="element-grid">
          <div class="element-card type-texto" draggable="true" data-type="texto" data-label="Caja de texto">
            <div class="el-drag-handle"><i class="fa-solid fa-ellipsis-vertical"></i><i class="fa-solid fa-ellipsis-vertical"></i></div>
            <div class="el-icon" style="font-size:14px;font-family:'Poppins';">Aa</div>
            <div class="el-info"><div class="el-name">Texto</div><div class="el-sub">Caja de texto</div></div>
          </div>
          <div class="element-card type-titulo" draggable="true" data-type="titulo" data-label="Título">
            <div class="el-drag-handle"><i class="fa-solid fa-ellipsis-vertical"></i><i class="fa-solid fa-ellipsis-vertical"></i></div>
            <div class="el-icon" style="font-size:19px;font-family:'Poppins';font-weight:800">T</div>
            <div class="el-info"><div class="el-name">Título</div><div class="el-sub">Encabezado</div></div>
          </div>
          <div class="element-card type-separador" draggable="true" data-type="separador" data-label="Separador">
            <div class="el-drag-handle"><i class="fa-solid fa-ellipsis-vertical"></i><i class="fa-solid fa-ellipsis-vertical"></i></div>
            <div class="el-icon" style="font-size:11px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:center;padding-top:8px;">— ✦ —</div>
            <div class="el-info"><div class="el-name">Separador</div><div class="el-sub">División</div></div>
          </div>
          <div class="element-card type-caja" draggable="true" data-type="caja" data-label="Caja con fondo">
            <div class="el-drag-handle"><i class="fa-solid fa-ellipsis-vertical"></i><i class="fa-solid fa-ellipsis-vertical"></i></div>
            <div class="el-icon"><i class="fa-solid fa-palette"></i></div>
            <div class="el-info"><div class="el-name">Caja</div><div class="el-sub">Notas fondo</div></div>
          </div>
        </div>
      </div>
      <div class="sidebar-section">
        <div class="section-label">Detalles</div>
        <div class="element-grid">
          <div class="element-card type-imagen" draggable="true" data-type="imagen" data-label="Imagen">
            <div class="el-drag-handle"><i class="fa-solid fa-ellipsis-vertical"></i><i class="fa-solid fa-ellipsis-vertical"></i></div>
            <div class="el-icon"><i class="fa-regular fa-image"></i></div>
            <div class="el-info"><div class="el-name">Imagen</div><div class="el-sub">Foto o Unsplash</div></div>
          </div>
          <div class="element-card type-gif" draggable="true" data-type="gif" data-label="GIF">
            <div class="el-drag-handle"><i class="fa-solid fa-ellipsis-vertical"></i><i class="fa-solid fa-ellipsis-vertical"></i></div>
            <div class="el-icon" style="color:#ce3df3;background:#f9f0ff"><i class="fa-solid fa-bolt"></i></div>
            <div class="el-info"><div class="el-name">GIF</div><div class="el-sub">Buscar en Giphy</div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="canvas-wrap">
    <div class="canvas-toolbar">
      <div class="day-tabs" id="dayTabs">
        <button class="day-tab portada-tab active" data-day="portada"><span class="day-tab-label"><i class="fa-solid fa-image"></i> Portada</span><span class="day-tab-delete portada-cierre-delete" onclick="confirmDeleteSection('portada',event)" title="Eliminar portada"><i class="fa-solid fa-trash-can"></i></span></button>
        <button class="day-tab" data-day="0" draggable="true" style="cursor:grab"><span class="day-tab-label"><span style="display:inline-flex; gap:1px; margin-right:7px; opacity:0.4; font-size:10px;"><i class="fa-solid fa-ellipsis-vertical"></i><i class="fa-solid fa-ellipsis-vertical"></i></span>Día 1</span><span class="day-tab-delete" onclick="confirmDeleteDay(0,event)" title="Eliminar día"><i class="fa-solid fa-trash-can"></i></span></button>
        <button class="day-tab" data-day="1" draggable="true" style="cursor:grab"><span class="day-tab-label"><span style="display:inline-flex; gap:1px; margin-right:7px; opacity:0.4; font-size:10px;"><i class="fa-solid fa-ellipsis-vertical"></i><i class="fa-solid fa-ellipsis-vertical"></i></span>Día 2</span><span class="day-tab-delete" onclick="confirmDeleteDay(1,event)" title="Eliminar día"><i class="fa-solid fa-trash-can"></i></span></button>
        <button class="day-tab" data-day="2" draggable="true" style="cursor:grab"><span class="day-tab-label"><span style="display:inline-flex; gap:1px; margin-right:7px; opacity:0.4; font-size:10px;"><i class="fa-solid fa-ellipsis-vertical"></i><i class="fa-solid fa-ellipsis-vertical"></i></span>Día 3</span><span class="day-tab-delete" onclick="confirmDeleteDay(2,event)" title="Eliminar día"><i class="fa-solid fa-trash-can"></i></span></button>
        <button class="day-tab cierre-tab" data-day="cierre"><span class="day-tab-label"><i class="fa-solid fa-flag-checkered"></i> Cierre</span><span class="day-tab-delete portada-cierre-delete" onclick="confirmDeleteSection('cierre',event)" title="Eliminar cierre"><i class="fa-solid fa-trash-can"></i></span></button>
      </div>
      <div style="display:flex;gap:6px;position:relative">
        <button class="add-day-btn" id="addDayBtn">+ Día</button>
        <button class="add-section-btn" id="addSectionBtn"><i class="fa-solid fa-plus"></i> Sección ▾</button>
        <div class="section-dropdown" id="sectionDropdown">
          <div class="section-option" onclick="addSection('portada')"><span><i class="fa-solid fa-image"></i></span> Portada</div>
          <div class="section-option" onclick="addSection('cierre')"><span><i class="fa-solid fa-flag-checkered"></i></span> Cierre</div>
        </div>
      </div>
      <div style="flex:1"></div>
    </div>
    <!-- DAY DATE SUBBAR -->
    <div class="day-subbar hidden" id="daySubbar">
      <span class="day-subbar-label" id="daySubbarLabel">DÍA 1</span>
      <input type="date" class="day-date-input" id="dayDateInput" oninput="saveDayDate()" onchange="saveDayDate()">
      <span class="day-date-nodate" id="dayNoDate">Sin fecha asignada</span>
    </div>
    <div class="canvas" id="canvas">
      <div class="canvas-inner" id="canvasInner">
        <div class="portada-canvas" id="portadaCanvas">
          <div class="portada-card">
            <!-- Per-element clear buttons injected via CSS hover on each section -->
            <div class="portada-hero" id="portadaHero">
              <img id="portadaHeroImg" src="" alt="">
              <div class="portada-hero-placeholder" id="portadaHeroPlaceholder">
                <div class="ph-main-icon" onclick="openUnsplash('portada')" style="cursor:pointer" title="Buscar en Unsplash"><i class="fa-solid fa-image"></i></div>
                <button class="ph-unsplash-btn" type="button" onclick="openUnsplash('portada')">
                  <span class="ph-unsplash-text" style="pointer-events:none">Agregar foto desde Unsplash</span>
                  <span class="ph-unsplash-sub" style="pointer-events:none">Miles de fotos profesionales sin costo · sin atribución</span>
                </button>
                <div class="ph-separator"><span>o también puedes</span></div>
                <label class="ph-local-btn">
                  <span class="ph-local-text">Sube tu propia imagen</span>
                  <span class="ph-local-sub">JPG, PNG o WEBP · máx. 5 MB</span>
                  <input type="file" accept="image/*" style="display:none" onchange="handlePortadaUpload(event)">
                </label>
              </div>
              <button class="portada-hero-btn has-photo-btn" id="portadaChangeBtn"><i class="fa-solid fa-rotate"></i> Cambiar foto · <span onclick="clearPortadaPhoto(event)" style="color:#f0567a"><i class="fa-solid fa-xmark"></i> Quitar</span></button>
            </div>
            <div class="portada-body">
              <div style="display:flex;align-items:center;gap:8px;position:relative">
                <input class="portada-title-input" id="portadaTitle" placeholder="Nombre del viaje..." value="{{ $trip->title ?? '' }}" style="flex:1">
                <button class="pfield-clear" onclick="document.getElementById('portadaTitle').value=''" title="Borrar título">🗑</button>
              </div>
              <div class="portada-divider"></div>
              <div class="portada-meta">
                <div class="portada-meta-item">
                  <div class="portada-meta-label" style="display:flex;justify-content:space-between;align-items:center"><span><i class="fa-solid fa-calendar-day"></i> Fecha inicio</span> <button class="pfield-clear-sm" onclick="document.getElementById('portadaFechaInicio').value=''" title="Borrar">🗑</button></div>
                  <input type="date" class="portada-meta-input" id="portadaFechaInicio">
                </div>
                <div class="portada-meta-item">
                  <div class="portada-meta-label" style="display:flex;justify-content:space-between;align-items:center"><span><i class="fa-solid fa-calendar-check"></i> Fecha fin</span> <button class="pfield-clear-sm" onclick="document.getElementById('portadaFechaFin').value=''" title="Borrar">🗑</button></div>
                  <input type="date" class="portada-meta-input" id="portadaFechaFin">
                </div>
                <div class="portada-meta-item">
                  <div class="portada-meta-label" style="display:flex;justify-content:space-between;align-items:center"><span><i class="fa-solid fa-user-group"></i> Viajeros</span> <button class="pfield-clear-sm" onclick="resetViajeros()" title="Reiniciar">🗑</button></div>
                  <div class="portada-travelers-row">
                    <div style="display:flex;flex-direction:column;gap:2px;align-items:center">
                      <div class="mini-counter"><button class="mini-counter-btn" onclick="changePortadaCount('adultos',-1)">−</button><div class="mini-counter-val" id="portadaAdultos">2</div><button class="mini-counter-btn" onclick="changePortadaCount('adultos',1)">+</button></div>
                      <div class="mini-counter-label" style="font-size:9px">Adultos</div>
                    </div>
                    <div style="font-size:14px;color:var(--text-dim);padding:0 2px">+</div>
                    <div style="display:flex;flex-direction:column;gap:2px;align-items:center">
                      <div class="mini-counter"><button class="mini-counter-btn" onclick="changePortadaCount('ninos',-1)">−</button><div class="mini-counter-val" id="portadaNinos">0</div><button class="mini-counter-btn" onclick="changePortadaCount('ninos',1)">+</button></div>
                      <div class="mini-counter-label" style="font-size:9px">Niños</div>
                    </div>
                    <div style="font-size:14px;color:var(--text-dim);padding:0 2px">=</div>
                    <div style="display:flex;flex-direction:column;gap:2px;align-items:center">
                      <div style="min-width:40px;height:30px;border:1.5px solid var(--border);border-radius:8px;background:var(--surface2);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:var(--accent)" id="portadaTotal">2</div>
                      <div class="mini-counter-label" style="font-size:9px">Total</div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="portada-divider"></div>
              <div class="portada-price-section" style="max-width:200px;margin:0 auto;text-align:center">
                <div class="portada-meta-label" style="display:flex;justify-content:center;align-items:center;gap:8px;margin-bottom:4px"><span><i class="fa-solid fa-money-bill-wave"></i> Valor total</span> <button class="pfield-clear-sm" onclick="document.getElementById('portadaPrecio').value=''" title="Borrar">🗑</button></div>
                <div style="display:flex;gap:5px">
                  <input type="text" class="portada-meta-input" id="portadaPrecio" placeholder="0" style="flex:1;min-width:0;text-align:center">
                  <select class="portada-meta-input" id="portadaMoneda" style="width:72px;padding:7px 5px">
                    <option>COP</option><option>USD</option><option>EUR</option><option>MXN</option><option>ARS</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div id="portadaDropHint" style="margin-top:10px;border:2px dashed var(--border);border-radius:var(--radius);padding:14px 20px;text-align:center;color:var(--text-dim);font-size:12.5px;transition:all .2s">
            <i class="fa-solid fa-plus"></i> Arrastra elementos adicionales a la portada
          </div>
          <div class="canvas-items" id="portadaItems" style="margin-top:8px"></div>
        </div>
        <div class="cierre-canvas" id="cierreCanvas" style="display:none">
          <div class="cierre-card" id="cierreCardMain">
            <button class="cierre-remove-btn" onclick="hideCierreCard()" title="Ocultar tarjeta de cierre"><i class="fa-solid fa-trash"></i></button>
            <div class="cierre-icon"><i class="fa-solid fa-plane-up"></i></div>
            <div class="cierre-badge">¡ITINERARIO COMPLETO!</div>
            <div class="cierre-title" id="cierreTitleDisplay">Tour por Europa 2025</div>
            <div class="cierre-sub">Este itinerario fue creado por <strong id="cierreAutor">{{ auth()->user()->display_name }}</strong>. ¡Que tengas un viaje increíble!</div>
          </div>
          <div id="cierreCardPlaceholder" class="cierre-hidden-placeholder" style="display:none">
            <div style="color:var(--text-dim);font-size:12.5px;margin-bottom:8px">El cierre predeterminado ha sido ocultado</div>
            <button class="btn btn-ghost" style="font-size:11px;padding:5px 12px;border:1px solid var(--border)" onclick="showCierreCard()">Restaurar diseño original</button>
          </div>
          <div class="canvas-items" id="cierreItems" style="margin-top:8px"></div>
          <div id="cierreDropHint" style="margin-top:10px;border:2px dashed var(--border);border-radius:var(--radius);padding:14px 20px;text-align:center;color:var(--text-dim);font-size:12.5px;transition:all .2s">
            <i class="fa-solid fa-plus"></i> Arrastra elementos adicionales al cierre
          </div>
        </div>
        <div id="regularCanvas" style="display:none">
          <div class="canvas-empty" id="emptyState"><div class="empty-icon"><i class="fa-solid fa-map-location-dot"></i></div><div class="empty-title">Tu itinerario está vacío</div><div class="empty-sub">Arrastra elementos desde el panel izquierdo para comenzar</div></div>
          <div class="canvas-items" id="canvasItems"></div>
          <div id="dropHint" style="display:none;margin-top:10px;border:2px dashed var(--border);border-radius:var(--radius);padding:14px 20px;text-align:center;color:var(--text-dim);font-size:12.5px;transition:all .2s">
            <i class="fa-solid fa-plus"></i> Arrastra más elementos aquí para seguir construyendo
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- GIPHY MODAL -->
<div class="unsplash-overlay" id="giphyOverlay">
  <div class="unsplash-modal" style="border-top:4px solid #ce3df3">
    <div class="unsplash-header">
      <div class="unsplash-title"><i class="fa-solid fa-bolt" style="color:#ce3df3"></i> Giphy</div>
      <input class="unsplash-search-inp" id="giphySearch" placeholder="Escribe para buscar GIFs..." value="">
      <button class="btn btn-ghost" style="padding:6px 12px;font-size:12px" onclick="searchGiphy()">Buscar</button>
      <button class="modal-close" onclick="closeGiphy()">×</button>
    </div>
    <div class="unsplash-grid" id="giphyGrid"><div class="unsplash-loading"><div class="spinner"></div> Cargando...</div></div>
    <div class="unsplash-footer">
      <div style="flex:1;text-align:left"><img src="https://giphy.com/static/img/powered_by_giphy_light.png" height="20"></div>
      <button class="btn btn-ghost" onclick="closeGiphy()">Cancelar</button>
      <button class="btn btn-primary" id="giphySelectBtn" onclick="confirmGiphy()" disabled style="background:#ce3df3;border-color:#ce3df3">Usar este GIF</button>
    </div>
  </div>
</div>
<!-- UNSPLASH MODAL -->
<div class="unsplash-overlay" id="unsplashOverlay">
  <div class="unsplash-modal">
    <div class="unsplash-header">
      <div class="unsplash-title"><i class="fa-solid fa-image" style="color:#14b8a6"></i> Unsplash</div>
      <input class="unsplash-search-inp" id="unsplashSearch" placeholder="Escribe para buscar fotos..." value="">
      <button class="btn btn-ghost" style="padding:6px 12px;font-size:12px" onclick="searchUnsplash()">Buscar</button>
      <button class="modal-close" onclick="closeUnsplash()">×</button>
    </div>
    <div class="unsplash-grid" id="unsplashGrid"><div class="unsplash-loading"><div class="spinner"></div> Cargando...</div></div>
    <div class="unsplash-footer">
      <button class="btn btn-ghost" onclick="closeUnsplash()">Cancelar</button>
      <button class="btn btn-primary" id="unsplashSelectBtn" onclick="confirmUnsplash()" disabled>Usar esta foto</button>
    </div>
  </div>
</div>
<!-- ELEMENT MODAL -->
<div class="modal-overlay" id="modalOverlay">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-icon" id="modalIcon">✈️</div>
      <div class="modal-title-wrap"><div class="modal-title" id="modalTitle">Agregar</div><div class="modal-subtitle" id="modalSubtitle">Completa los datos</div></div>
      <button class="modal-close" id="modalClose">×</button>
    </div>
    <div class="modal-body" id="modalBody"></div>
    <div class="modal-footer"><button class="btn btn-ghost" id="modalCancel">Cancelar</button><button class="btn btn-primary" id="modalSave">✓ Guardar</button></div>
  </div>
</div>
<!-- CONFIRM -->
<div class="confirm-overlay" id="confirmOverlay">
  <div class="confirm-box">
    <div class="confirm-title" id="confirmTitle">¿Eliminar?</div>
    <div class="confirm-msg" id="confirmMsg">Esta acción no se puede deshacer.</div>
    <div class="confirm-btns"><button class="btn btn-ghost" onclick="closeConfirm()">Cancelar</button><button class="btn btn-danger" id="confirmOkBtn">🗑 Eliminar</button></div>
  </div>
</div>
<div class="drag-ghost" id="dragGhost"><span id="ghostIcon"></span><span id="ghostLabel"></span></div>
<div class="toast" id="toast"><span id="toastIcon"></span><span id="toastMsg"></span></div>



    <script src="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.js.iife.js"></script>
    <script>
        function initEditorTutorial(force = false) {
            const driver = window.driver.js.driver;
            const tutorialsSeen = window.ViantrypTutorials || [];
            const hasSeenTutorial = tutorialsSeen.includes('editor');

            // Auto-open only if NOT seen yet AND it's the first trip
            if (!force && (hasSeenTutorial || !window.isFirstTrip)) return;

            const driverObj = driver({
                showProgress: true,
                animate: true,
                allowClose: true,
                nextBtnText: 'Siguiente',
                prevBtnText: 'Anterior',
                doneBtnText: 'Finalizar',
                steps: [
                    { 
                        element: '.header-subtitle', 
                        popover: { 
                            title: '¡Bienvenido!', 
                            description: 'Este es el Editor de Itinerarios. Aquí podrás diseñar cada detalle de la experiencia para tu cliente.' 
                        } 
                    },
                    { 
                        element: '.sidebar', 
                        popover: { 
                            title: 'Elementos y Diseño', 
                            description: 'Arrastra servicios o elementos de diseño directamente al lienzo central para ir construyendo el viaje.' 
                        } 
                    },
                    { 
                        element: '.canvas', 
                        popover: { 
                            title: 'Tu Lienzo', 
                            description: 'Este es tu espacio de trabajo. Aquí es donde se divide y organiza todo el contenido de tu itinerario de forma visual.' 
                        },
                        onHighlightStarted: (element) => {
                            // Ocultar temporalmente el banner para que solo se ilumine el lienzo (padding)
                            const card = document.querySelector('.portada-card');
                            if (card) card.style.opacity = '0.1';
                            const tab = document.querySelector('.day-tab.portada-tab');
                            if (tab) tab.click();
                        },
                        onDeselected: (element) => {
                            const card = document.querySelector('.portada-card');
                            if (card) card.style.opacity = '1';
                        }
                    },
                    { 
                        element: '.canvas-toolbar', 
                        popover: { 
                            title: 'Barra de Herramientas', 
                            description: 'Desde aquí gestionas los días del viaje, añades nuevas secciones y navegas rápidamente por todo el itinerario.' 
                        } 
                    },
                    { 
                        element: '.portada-card', 
                        popover: { 
                            title: 'Banner Principal', 
                            description: 'En esta sección defines la información básica (foto y fechas) que se mostrará en el gran banner de inicio de tu viaje.',
                            position: 'bottom'
                        },
                        onHighlightStarted: (element) => {
                            const tab = document.querySelector('.day-tab.portada-tab');
                            if (tab) {
                                tab.click();
                                tab.classList.add('tutorial-extra-highlight');
                            }
                        },
                        onDeselected: (element) => {
                            const tab = document.querySelector('.day-tab.portada-tab');
                            if (tab) tab.classList.remove('tutorial-extra-highlight');
                        }
                    },
                    { 
                        element: '#daySubbar', 
                        popover: { 
                            title: 'Configuración del Día', 
                            description: 'Asigna la fecha correspondiente a cada día y comienza a añadir contenido para construir el itinerario perfecto.',
                            position: 'bottom'
                        },
                        onHighlightStarted: (element) => {
                            const tab = document.querySelector('.day-tab[data-day="0"]');
                            if (tab) {
                                tab.click();
                                tab.classList.add('tutorial-extra-highlight');
                            }
                        },
                        onDeselected: (element) => {
                            const tab = document.querySelector('.day-tab[data-day="0"]');
                            if (tab) tab.classList.remove('tutorial-extra-highlight');
                        }
                    },
                    { 
                        element: '#cierreCardMain', 
                        popover: { 
                            title: 'Portada de Cierre', 
                            description: 'Esta es tu despedida predeterminada. Mostrará automáticamente tu nombre de autor personalizado en "Mi Perfil".',
                            position: 'top'
                        },
                        onHighlightStarted: (element) => {
                            const tab = document.querySelector('.day-tab.cierre-tab');
                            if (tab) {
                                tab.click();
                                tab.classList.add('tutorial-extra-highlight');
                            }
                        },
                        onDeselected: (element) => {
                            const tab = document.querySelector('.day-tab.cierre-tab');
                            if (tab) tab.classList.remove('tutorial-extra-highlight');
                        }
                    },
                    { 
                        element: '.cierre-remove-btn', 
                        popover: { 
                            title: 'Personaliza tu cierre', 
                            description: 'Si lo prefieres, puedes usar este botón para ocultar el banner predeterminado y diseñar un cierre con tu estilo.',
                            position: 'left'
                        },
                        onHighlightStarted: (element) => {
                            const tab = document.querySelector('.day-tab.cierre-tab');
                            if (tab) tab.click();
                        }
                    },
                    { 
                        element: '#btnPreviewTrip', 
                        popover: { 
                            title: 'Guardar y Vista Previa', 
                            description: 'En todo momento podrás ir viendo los cambios en tiempo real de tu itinerario. Usa la vista previa para generar una idea clara de cómo va quedando el diseño del viaje.' 
                        } 
                    }
                ],
                onDestroyed: () => {
                    if (!hasSeenTutorial) {
                        fetch('{{ route("profile.complete.tutorial") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ tutorial: 'editor' })
                        });
                        if (!window.ViantrypTutorials.includes('editor')) {
                            window.ViantrypTutorials.push('editor');
                        }
                    }
                    // Limpieza de clases extra
                    document.querySelectorAll('.tutorial-extra-highlight').forEach(el => el.classList.remove('tutorial-extra-highlight'));
                    const tab = document.querySelector('.day-tab.portada-tab');
                    if (tab) tab.click();
                }
            });

            driverObj.drive();
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Un pequeño delay para que el editor cargue su estado inicial
            setTimeout(initEditorTutorial, 1500);
        });
    </script>
    <script src="{{ asset('js/trips/pro-viewer.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/trips/pro-editor.js') }}?v={{ time() }}"></script>
</body>
</html>
