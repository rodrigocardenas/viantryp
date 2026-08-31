@php
    $currentUser = auth()->user();
    $currentRoute = request()->route() ? request()->route()->getName() : '';
    $currentFilter = request('filter', 'personal');
    
    // Active tabs logic
    $isTripsActive = ($currentRoute === 'trips.index' && $currentFilter !== 'shared') || $currentRoute === 'home';
    $isSharedActive = $currentRoute === 'trips.index' && $currentFilter === 'shared';
    $isProfileActive = $currentRoute === 'profile.index';
    
    // Plan and limits calculation for bottom sheet
    if ($currentUser) {
        $tripCount = \App\Models\Trip::where('user_id', $currentUser->id)->count();
        $editorCount = \DB::table('trip_collaborators')
            ->join('trips', 'trip_collaborators.trip_id', '=', 'trips.id')
            ->where('trips.user_id', $currentUser->id)
            ->where('trip_collaborators.role', 'editor')
            ->distinct('trip_collaborators.email')
            ->count();
        $limits = $currentUser->getPlanLimits();
        $maxTrips = $limits['max_trips'] ?? 5;
        $maxEditors = $limits['max_editors'] ?? 0;
        $tripPercent = min(100, ($tripCount / max(1, $maxTrips)) * 100);
        $editorPercent = $maxEditors > 0 ? min(100, ($editorCount / $maxEditors) * 100) : 0;
    }
@endphp

@if($currentUser)
<!-- Detection Script for App Mode (PWA Standalone or ?app=1 / ?mode=app) -->
<script>
(function() {
    const isStandalone = window.navigator.standalone === true || 
                         window.matchMedia('(display-mode: standalone)').matches ||
                         window.matchMedia('(display-mode: fullscreen)').matches;
    const urlParams = new URLSearchParams(window.location.search);
    const hasAppParam = urlParams.get('app') === '1' || urlParams.get('mode') === 'app';
    const hasWebParam = urlParams.get('web') === '1' || urlParams.get('mode') === 'web';
    
    if (hasWebParam) {
        localStorage.removeItem('viantryp_app_mode');
    } else if (hasAppParam) {
        localStorage.setItem('viantryp_app_mode', '1');
    }

    const isSavedAppMode = localStorage.getItem('viantryp_app_mode') === '1';

    if (isStandalone || hasAppParam || isSavedAppMode) {
        document.documentElement.classList.add('is-viantryp-app');
        if (document.body) document.body.classList.add('is-viantryp-app');
        else document.addEventListener('DOMContentLoaded', () => document.body.classList.add('is-viantryp-app'));
    }
})();
</script>

<!-- Viantryp Mobile Bottom Navigation Bar (Exclusive for App Version) -->
<div id="viantrypBottomNav" class="viantryp-bottom-nav">
    <div class="viantryp-bottom-nav-inner">
        <!-- 1. Mis viajes -->
        <a href="{{ route('trips.index', ['filter' => 'personal']) }}" 
           class="nav-tab-item {{ $isTripsActive ? 'active' : '' }}" 
           id="tabMisViajes">
            <div class="tab-icon-wrapper">
                <svg class="tab-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="20" height="14" x="2" y="7" rx="2" ry="2"/>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                </svg>
            </div>
            <span class="tab-label">Mis viajes</span>
        </a>

        <!-- 2. Compartidos -->
        <a href="{{ route('trips.index', ['filter' => 'shared']) }}" 
           class="nav-tab-item {{ $isSharedActive ? 'active' : '' }}" 
           id="tabCompartidos">
            <div class="tab-icon-wrapper">
                <svg class="tab-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <span class="tab-label">Compartidos</span>
        </a>

        <!-- 3. Botón Flotante Central (+ Crear) -->
        <div class="nav-tab-fab-container">
            <button type="button" class="fab-create-btn" id="fabCreateTripBtn" onclick="handleMobileCreateTrip()" title="Crear Viaje">
                <svg class="fab-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
            </button>
            <span class="tab-label fab-label">Crear</span>
        </div>

        <!-- 4. Plantillas -->
        <button type="button" class="nav-tab-item" id="tabPlantillas" onclick="showSoonToast('Plantillas de viaje')">
            <div class="tab-icon-wrapper relative">
                <svg class="tab-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/>
                    <path d="m22 12.5-8.58 3.91a2 2 0 0 1-1.66 0L2 12.5"/>
                    <path d="m22 17.5-8.58 3.91a2 2 0 0 1-1.66 0L2 17.5"/>
                </svg>
                <span class="badge-pronto">Pronto</span>
            </div>
            <span class="tab-label">Plantillas</span>
        </button>

        <!-- 5. Perfil (Abre Bottom Sheet) -->
        <button type="button" 
                class="nav-tab-item {{ $isProfileActive ? 'active' : '' }}" 
                id="tabPerfilMobile" 
                onclick="toggleMobileProfileSheet(true)">
            <div class="tab-icon-wrapper">
                <svg class="tab-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
            <span class="tab-label">Perfil</span>
        </button>
    </div>
</div>

<!-- Profile Bottom Sheet Modal -->
<div id="profileBottomSheetBackdrop" class="profile-sheet-backdrop" onclick="toggleMobileProfileSheet(false)"></div>

<div id="profileBottomSheet" class="profile-bottom-sheet" role="dialog" aria-modal="true" aria-labelledby="profileSheetTitle">
    <!-- Sheet Drag Handle -->
    <div class="sheet-drag-handle-zone" onclick="toggleMobileProfileSheet(false)">
        <div class="sheet-drag-pill"></div>
    </div>

    <!-- Sheet Header -->
    <div class="sheet-header">
        <div class="sheet-user-info">
            <div class="sheet-avatar">
                @if($currentUser->avatar)
                    <img src="{{ str_starts_with($currentUser->avatar, 'http') ? $currentUser->avatar : asset('storage/' . $currentUser->avatar) }}" alt="{{ $currentUser->name }}">
                @else
                    {{ collect(explode(' ', $currentUser->name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->join('') }}
                @endif
            </div>
            <div class="sheet-user-meta">
                <div class="sheet-name-row">
                    <h3 id="profileSheetTitle" class="sheet-user-name">{{ $currentUser->name }}</h3>
                    <span class="sheet-plan-badge">{{ ucfirst($currentUser->plan ?? 'Básico') }}</span>
                </div>
                <p class="sheet-user-email">{{ $currentUser->email }}</p>
            </div>
        </div>
        <button type="button" class="sheet-close-btn" onclick="toggleMobileProfileSheet(false)" aria-label="Cerrar">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>

    <!-- Sheet Body / Scrollable Content -->
    <div class="sheet-body">
        <!-- Plan Usage Card -->
        <div class="sheet-usage-card">
            <div class="sheet-usage-header">
                <span class="sheet-usage-title">USO DEL PLAN</span>
                <a href="{{ route('profile.index', ['upgrade' => 'true']) }}" onclick="handleSheetUpgrade(event)" class="sheet-upgrade-link">
                    Mejorar plan <i class="fas fa-arrow-up-right-from-square" style="font-size: 11px; margin-left: 2px;"></i>
                </a>
            </div>

            <!-- Itineraries Progress -->
            <div class="sheet-metric-row">
                <div class="sheet-metric-labels">
                    <span class="sheet-metric-name">
                        <i class="fas fa-route" style="color: #1D63B8; margin-right: 6px;"></i> Itinerarios
                    </span>
                    <span class="sheet-metric-val">{{ $tripCount }} / {{ $maxTrips >= 1000000 ? '∞' : $maxTrips }}</span>
                </div>
                <div class="sheet-progress-track">
                    <div class="sheet-progress-bar" style="width: {{ $tripPercent }}%;"></div>
                </div>
            </div>

            <!-- Collaborators Progress -->
            <div class="sheet-metric-row">
                <div class="sheet-metric-labels">
                    <span class="sheet-metric-name">
                        <i class="fas fa-users" style="color: #1D63B8; margin-right: 6px;"></i> Colaboradores
                    </span>
                    <span class="sheet-metric-val">{{ $editorCount }} / {{ $maxEditors >= 1000000 ? '∞' : $maxEditors }}</span>
                </div>
                <div class="sheet-progress-track">
                    <div class="sheet-progress-bar" style="width: {{ $editorPercent }}%;"></div>
                </div>
            </div>
        </div>

        <!-- Quick Links / Account Settings -->
        <div class="sheet-nav-group">
            <a href="{{ route('profile.index', ['section' => ($currentUser->account_type === 'agency' ? 'agencia' : 'info')]) }}" class="sheet-nav-item">
                <div class="sheet-nav-icon">
                    <i class="{{ $currentUser->account_type === 'agency' ? 'fas fa-briefcase' : 'fas fa-user-circle' }}"></i>
                </div>
                <div class="sheet-nav-text">
                    <span class="sheet-nav-title">Mi Cuenta y Ajustes</span>
                    <span class="sheet-nav-desc">Datos personales, agencia y preferencias</span>
                </div>
                <i class="fas fa-chevron-right sheet-chevron"></i>
            </a>

            <a href="{{ route('profile.index', ['section' => 'tema']) }}" class="sheet-nav-item">
                <div class="sheet-nav-icon">
                    <i class="fas fa-palette"></i>
                </div>
                <div class="sheet-nav-text">
                    <span class="sheet-nav-title">Personalización de Marca</span>
                    <span class="sheet-nav-desc">Paleta de colores y estilos visuales</span>
                </div>
                <i class="fas fa-chevron-right sheet-chevron"></i>
            </a>

            <a href="{{ route('profile.index', ['section' => 'subscription']) }}" class="sheet-nav-item">
                <div class="sheet-nav-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div class="sheet-nav-text">
                    <span class="sheet-nav-title">Planes y Suscripción</span>
                    <span class="sheet-nav-desc">Límites, planes activos y facturación</span>
                </div>
                <i class="fas fa-chevron-right sheet-chevron"></i>
            </a>

            <a href="{{ route('profile.index', ['section' => 'seguridad']) }}" class="sheet-nav-item">
                <div class="sheet-nav-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="sheet-nav-text">
                    <span class="sheet-nav-title">Seguridad de la Cuenta</span>
                    <span class="sheet-nav-desc">Contraseña y acceso a tu sesión</span>
                </div>
                <i class="fas fa-chevron-right sheet-chevron"></i>
            </a>

            <a href="mailto:hola@viantryp.com" class="sheet-nav-item">
                <div class="sheet-nav-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <div class="sheet-nav-text">
                    <span class="sheet-nav-title">Soporte y Ayuda</span>
                    <span class="sheet-nav-desc">Contáctanos por correo o tutoriales</span>
                </div>
                <i class="fas fa-chevron-right sheet-chevron"></i>
            </a>
        </div>

        <!-- Logout Action -->
        <form method="POST" action="{{ route('logout') }}" style="margin: 16px 0 8px;">
            @csrf
            <button type="submit" class="sheet-logout-btn">
                <i class="fas fa-arrow-right-from-bracket"></i>
                <span>Cerrar sesión</span>
            </button>
        </form>
    </div>
</div>

<!-- Floating Toast Notification for Mobile -->
<div id="viantrypMobileToast" class="viantryp-mobile-toast">
    <span id="viantrypMobileToastMsg"></span>
</div>

<style>
/* ══════════════════════════════════════════════════════════════
   VIANTRYP MOBILE BOTTOM NAVIGATION BAR & PROFILE BOTTOM SHEET
   Corporate Palette & Modern Design Tokens (App Exclusive)
   ══════════════════════════════════════════════════════════════ */

:root {
    --vt-navy: #0F3256;         /* Corporate Dark Blue */
    --vt-primary: #1D63B8;      /* Viantryp Primary Blue */
    --vt-primary-hover: #154c8f;
    --vt-slate-50: #f8fafc;
    --vt-slate-100: #f1f5f9;
    --vt-slate-200: #e2e8ef;
    --vt-slate-400: #94a3b8;
    --vt-slate-600: #475569;
    --vt-slate-700: #334155;
    --vt-amber-bg: #fef3c7;
    --vt-amber-text: #b45309;
}

/* Oculto por defecto en la versión Web estándar */
.viantryp-bottom-nav,
.app-topbar-logo {
    display: none;
}

/* Activo únicamente en modo App / PWA en móviles */
@media (max-width: 768px) {
    .is-viantryp-app body,
    body.is-viantryp-app {
        padding-bottom: 74px !important;
    }

    /* Ocultar el aviso de instalar app en modo App */
    .is-viantryp-app #pwa-install-banner,
    body.is-viantryp-app #pwa-install-banner {
        display: none !important;
    }

    /* Ocultar la barra lateral / sidebar en modo App */
    .is-viantryp-app .dashboard-sidebar,
    body.is-viantryp-app .dashboard-sidebar,
    .is-viantryp-app .sidebar-backdrop,
    body.is-viantryp-app .sidebar-backdrop {
        display: none !important;
    }

    .is-viantryp-app .dashboard-container,
    body.is-viantryp-app .dashboard-container {
        display: flex !important;
        flex-direction: column !important;
        min-height: 100vh !important;
    }

    .is-viantryp-app .dashboard-main,
    body.is-viantryp-app .dashboard-main {
        width: 100% !important;
        flex: 1 !important;
        margin: 0 !important;
    }

    .is-viantryp-app .page-wrapper,
    body.is-viantryp-app .page-wrapper {
        padding: 20px 16px 80px 16px !important;
    }

    /* Ocultar settings-sub-sidebar en modo App para mostrar directamente la tarjeta elegida */
    .is-viantryp-app .settings-sub-sidebar,
    body.is-viantryp-app .settings-sub-sidebar {
        display: none !important;
    }

    .is-viantryp-app .settings-grid,
    body.is-viantryp-app .settings-grid {
        display: block !important;
        grid-template-columns: 1fr !important;
        width: 100% !important;
        margin: 0 !important;
    }

    .is-viantryp-app .main-content,
    body.is-viantryp-app .main-content {
        width: 100% !important;
    }

    /* Topbar estilizado para la versión App en móviles con el mismo color del sidebar desktop (var(--sidebar-bg)) */
    .is-viantryp-app .dashboard-topbar,
    body.is-viantryp-app .dashboard-topbar {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 0 18px !important;
        height: 60px !important;
        background: var(--sidebar-bg, linear-gradient(135deg, #1a7f77 0%, #0d2b3e 100%)) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.12) !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15) !important;
        position: sticky !important;
        top: 0 !important;
        z-index: 200 !important;
    }

    .is-viantryp-app .app-topbar-logo,
    body.is-viantryp-app .app-topbar-logo {
        display: flex !important;
        align-items: center !important;
    }

    .is-viantryp-app .app-topbar-logo img,
    body.is-viantryp-app .app-topbar-logo img {
        height: 28px !important;
        width: auto !important;
        filter: brightness(0) invert(1) !important;
    }

    /* Ocultar el menú lateral web tradicional en la app */
    .is-viantryp-app .mobile-hamburger-btn,
    body.is-viantryp-app .mobile-hamburger-btn {
        display: none !important;
    }

    /* Ocultar la barra de búsqueda del topbar para dejar espacio limpio al logo y acciones */
    .is-viantryp-app .dashboard-topbar .topbar-search,
    body.is-viantryp-app .dashboard-topbar .topbar-search {
        display: none !important;
    }

    /* Ocultar botón crear del topbar (ya está en el FAB central del bottom bar) */
    .is-viantryp-app .btn-topbar-create,
    body.is-viantryp-app .btn-topbar-create {
        display: none !important;
    }

    /* Ocultar el avatar / dropdown de perfil del topbar en la app (ya está en el bottom nav) */
    .is-viantryp-app .profile-dropdown-wrapper,
    body.is-viantryp-app .profile-dropdown-wrapper,
    .is-viantryp-app .profile-trigger,
    body.is-viantryp-app .profile-trigger {
        display: none !important;
    }

    /* Mantener visible únicamente la campana de notificaciones a la derecha */
    .is-viantryp-app .topbar-actions,
    body.is-viantryp-app .topbar-actions {
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
    }

    .is-viantryp-app .btn-topbar-icon,
    body.is-viantryp-app .btn-topbar-icon {
        background: rgba(255, 255, 255, 0.12) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        color: #ffffff !important;
        transition: all 0.2s ease !important;
    }

    .is-viantryp-app .btn-topbar-icon:hover,
    body.is-viantryp-app .btn-topbar-icon:hover {
        background: rgba(255, 255, 255, 0.22) !important;
        color: #ffffff !important;
    }

    .is-viantryp-app .noti-wrapper,
    body.is-viantryp-app .noti-wrapper {
        display: block !important;
    }

    .is-viantryp-app .viantryp-bottom-nav,
    body.is-viantryp-app .viantryp-bottom-nav {
        display: block !important;
        position: fixed !important;
        bottom: 0 !important;
        left: 0 !important;
        right: 0 !important;
        width: 100vw !important;
        background: #ffffff !important;
        border-top: 1px solid var(--vt-slate-100) !important;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.05) !important;
        z-index: 2500 !important;
        padding-bottom: env(safe-area-inset-bottom, 0px);
        font-family: 'Barlow', sans-serif !important;
        user-select: none;
        -webkit-user-select: none;
    }

    .viantryp-bottom-nav-inner {
        display: flex;
        align-items: center;
        justify-content: space-around;
        height: 64px;
        max-width: 500px;
        margin: 0 auto;
        padding: 0 8px;
        position: relative;
    }

    .nav-tab-item {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 3px;
        height: 100%;
        background: transparent;
        border: none;
        outline: none;
        color: var(--vt-slate-400);
        text-decoration: none;
        cursor: pointer;
        transition: color 0.18s ease, transform 0.15s ease;
        padding: 6px 2px;
        position: relative;
    }

    .nav-tab-item:hover {
        color: var(--vt-slate-600);
    }

    .nav-tab-item:active {
        transform: scale(0.94);
    }

    .nav-tab-item.active {
        color: var(--vt-navy) !important;
    }

    .nav-tab-item.active .tab-label {
        color: var(--vt-navy) !important;
        font-weight: 600 !important;
    }

    .nav-tab-item.active .tab-icon {
        stroke: var(--vt-navy) !important;
        stroke-width: 2.3px !important;
    }

    .tab-icon-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 24px;
    }

    .tab-icon {
        width: 21px;
        height: 21px;
        stroke: currentColor;
        transition: stroke 0.18s ease;
    }

    .tab-label {
        font-size: 11px;
        font-weight: 500;
        letter-spacing: -0.1px;
        line-height: 1;
        color: inherit;
        transition: color 0.18s ease;
    }

    /* Central FAB Button (+ Crear) */
    .nav-tab-fab-container {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        position: relative;
    }

    .fab-create-btn {
        position: relative;
        top: -14px;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: var(--accent, var(--vt-primary, #1D63B8));
        color: #ffffff;
        border: 3.5px solid #ffffff;
        box-shadow: 0 10px 20px -3px rgba(15, 23, 42, 0.35), 0 4px 6px -2px rgba(15, 23, 42, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        margin-bottom: -10px;
    }

    .fab-create-btn:hover {
        background: var(--vt-primary-hover);
        transform: scale(1.06);
    }

    .fab-create-btn:active {
        transform: scale(0.92);
    }

    .fab-icon {
        width: 22px;
        height: 22px;
        stroke: #ffffff;
    }

    .fab-label {
        position: relative;
        top: -4px;
        color: var(--vt-slate-600);
        font-weight: 600;
    }

    /* Badge "Pronto" */
    .badge-pronto {
        position: absolute;
        top: -7px;
        right: -24px;
        background: var(--vt-amber-bg);
        color: var(--vt-amber-text);
        font-size: 9.5px;
        font-weight: 600;
        padding: 1px 5px;
        border-radius: 9999px;
        line-height: 1.2;
        letter-spacing: -0.2px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        pointer-events: none;
    }
}

/* ══════════════════════════════════════════════════════════════
   BOTTOM SHEET MODAL STYLES
   ══════════════════════════════════════════════════════════════ */
.profile-sheet-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.55);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    z-index: 2998;
    opacity: 0;
    transition: opacity 0.28s ease;
}

.profile-sheet-backdrop.show {
    display: block;
    opacity: 1;
}

.profile-bottom-sheet {
    display: flex;
    flex-direction: column;
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    max-width: 540px;
    margin: 0 auto;
    background: #ffffff;
    border-radius: 24px 24px 0 0;
    box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.2);
    z-index: 2999;
    transform: translateY(105%);
    transition: transform 0.32s cubic-bezier(0.16, 1, 0.3, 1);
    max-height: 88vh;
    font-family: 'Barlow', sans-serif;
    padding-bottom: env(safe-area-inset-bottom, 16px);
}

.profile-bottom-sheet.show {
    transform: translateY(0);
}

.sheet-drag-handle-zone {
    width: 100%;
    padding: 12px 0 6px;
    display: flex;
    justify-content: center;
    cursor: grab;
}

.sheet-drag-pill {
    width: 44px;
    height: 4.5px;
    background: #cbd5e1;
    border-radius: 999px;
}

.sheet-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 20px 16px;
    border-bottom: 1px solid var(--vt-slate-100);
}

.sheet-user-info {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 0;
}

.sheet-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: var(--avatar-gradient, linear-gradient(135deg, #1D63B8, #0F3256));
    color: #ffffff;
    font-weight: 700;
    font-size: 17px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(29, 99, 184, 0.25);
}

.sheet-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.sheet-user-meta {
    min-width: 0;
}

.sheet-name-row {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.sheet-user-name {
    margin: 0;
    font-size: 17px;
    font-weight: 700;
    color: var(--vt-navy);
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.sheet-plan-badge {
    background: #e0f2fe;
    color: #0369a1;
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    padding: 2px 8px;
    border-radius: 9999px;
}

.sheet-user-email {
    margin: 3px 0 0;
    font-size: 13px;
    color: var(--vt-slate-400);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.sheet-close-btn {
    background: var(--vt-slate-100);
    border: none;
    color: var(--vt-slate-600);
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.15s ease, transform 0.15s ease;
    flex-shrink: 0;
}

.sheet-close-btn:hover {
    background: #e2e8f0;
    color: var(--vt-navy);
}

.sheet-body {
    padding: 18px 20px 20px;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
}

/* Plan Usage Card */
.sheet-usage-card {
    background: var(--vt-slate-50);
    border: 1px solid var(--vt-slate-200);
    border-radius: 16px;
    padding: 16px;
    margin-bottom: 16px;
}

.sheet-usage-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.sheet-usage-title {
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 0.5px;
    color: var(--vt-slate-600);
    text-transform: uppercase;
}

.sheet-upgrade-link {
    font-size: 12.5px;
    font-weight: 600;
    color: var(--vt-primary);
    text-decoration: none;
    transition: color 0.15s ease;
    display: inline-flex;
    align-items: center;
}

.sheet-upgrade-link:hover {
    color: var(--vt-navy);
    text-decoration: underline;
}

.sheet-metric-row {
    margin-bottom: 12px;
}

.sheet-metric-row:last-child {
    margin-bottom: 0;
}

.sheet-metric-labels {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13px;
    margin-bottom: 6px;
}

.sheet-metric-name {
    font-weight: 500;
    color: var(--vt-slate-700);
    display: flex;
    align-items: center;
}

.sheet-metric-val {
    font-weight: 700;
    color: var(--vt-navy);
    font-size: 12.5px;
}

.sheet-progress-track {
    height: 7px;
    background: var(--vt-slate-200);
    border-radius: 999px;
    overflow: hidden;
}

.sheet-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #1D63B8 0%, #38bdf8 100%);
    border-radius: 999px;
    transition: width 0.4s ease;
}

/* Nav links list in Bottom Sheet */
.sheet-nav-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.sheet-nav-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 14px;
    border-radius: 12px;
    background: #ffffff;
    border: 1px solid var(--vt-slate-100);
    text-decoration: none;
    transition: all 0.18s ease;
}

.sheet-nav-item:hover {
    background: var(--vt-slate-50);
    border-color: var(--vt-slate-200);
    transform: translateX(2px);
}

.sheet-nav-icon {
    width: 24px;
    height: 24px;
    background: transparent !important;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: #64748b !important;
    flex-shrink: 0;
    transition: color 0.18s ease;
}

.sheet-nav-item:hover .sheet-nav-icon,
.sheet-nav-item:active .sheet-nav-icon {
    color: var(--accent, #1D63B8) !important;
}

.sheet-nav-text {
    flex: 1;
    min-width: 0;
}

.sheet-nav-title {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: var(--vt-navy);
    line-height: 1.2;
}

.sheet-nav-desc {
    display: block;
    font-size: 12px;
    color: var(--vt-slate-400);
    margin-top: 2px;
}

.sheet-chevron {
    font-size: 12px;
    color: var(--vt-slate-400);
}

/* Logout button */
.sheet-logout-btn {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #fee2e2;
    background: #fff5f5;
    color: #dc2626;
    border-radius: 12px;
    font-size: 13.5px;
    font-weight: 600;
    font-family: inherit;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.18s ease;
}

.sheet-logout-btn:hover {
    background: #fee2e2;
    color: #b91c1c;
}

/* Mobile Toast Notification */
.viantryp-mobile-toast {
    position: fixed;
    bottom: 84px;
    left: 50%;
    transform: translateX(-50%) translateY(20px);
    background: rgba(15, 50, 86, 0.95);
    color: #ffffff;
    padding: 10px 18px;
    border-radius: 9999px;
    font-size: 12.5px;
    font-weight: 500;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(8px);
    z-index: 9999;
    opacity: 0;
    pointer-events: none;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    white-space: nowrap;
    text-align: center;
}

.viantryp-mobile-toast.show {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
}
</style>

<script>
function toggleMobileProfileSheet(show) {
    const sheet = document.getElementById('profileBottomSheet');
    const backdrop = document.getElementById('profileBottomSheetBackdrop');
    const tabPerfil = document.getElementById('tabPerfilMobile');
    
    if (show) {
        backdrop.classList.add('show');
        sheet.classList.add('show');
        document.body.style.overflow = 'hidden';
        if (tabPerfil) tabPerfil.classList.add('active');
    } else {
        sheet.classList.remove('show');
        backdrop.classList.remove('show');
        document.body.style.overflow = '';
        @if(!$isProfileActive)
            if (tabPerfil) tabPerfil.classList.remove('active');
        @endif
    }
}

function handleMobileCreateTrip() {
    if (typeof showCreateTripModal === 'function') {
        showCreateTripModal();
    } else {
        window.location.href = "{{ route('trips.index') }}?create=1";
    }
}

function handleSheetUpgrade(e) {
    if (typeof openUpgradeModal === 'function') {
        e.preventDefault();
        toggleMobileProfileSheet(false);
        openUpgradeModal(true);
    }
}

function showSoonToast(featureName) {
    const toast = document.getElementById('viantrypMobileToast');
    const msg = document.getElementById('viantrypMobileToastMsg');
    if (!toast || !msg) return;
    
    msg.innerHTML = `✨ <strong>${featureName}:</strong> ¡Estará disponible muy pronto!`;
    toast.classList.add('show');
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 2800);
}

// Support for swipe down gesture to close sheet
(function() {
    let startY = 0;
    let currentY = 0;
    const sheet = document.getElementById('profileBottomSheet');
    if (!sheet) return;
    
    sheet.addEventListener('touchstart', (e) => {
        startY = e.touches[0].clientY;
    }, { passive: true });
    
    sheet.addEventListener('touchmove', (e) => {
        currentY = e.touches[0].clientY;
        const diff = currentY - startY;
        if (diff > 0 && sheet.scrollTop <= 0) {
            sheet.style.transform = `translateY(${diff}px)`;
        }
    }, { passive: true });
    
    sheet.addEventListener('touchend', (e) => {
        const diff = currentY - startY;
        sheet.style.transform = '';
        if (diff > 80) {
            toggleMobileProfileSheet(false);
        }
        startY = 0;
        currentY = 0;
    });
})();
</script>
@endif
