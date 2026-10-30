@php
    $user = auth()->user();
    if (!$user || $user->initial_plan_chosen_at) {
        return;
    }
@endphp

<div id="welcomePlanModal" class="modal upgrade-premium-modal" style="display: flex; z-index: 10002;">
    <div class="modal-content modal-welcome-onboarding" style="position: relative;">
        <!-- Canvas Confetti Overlay -->
        <canvas id="welcomeConfettiCanvas" style="position: absolute; inset: 0; width: 100%; height: 100%; pointer-events: none; z-index: 50;"></canvas>

        <div class="modal-body">

            <!-- HEADER: USER INFO -->
            <div class="modal-user-header">
                <div class="user-info">
                    <div class="avatar user-avatar">
                        @if($user->avatar)
                            <img src="{{ str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar) }}" alt="">
                        @else
                            {{ $user->display_initials }}
                        @endif
                    </div>
                    <div class="user-details">
                        <div class="user-name">¡Hola, {{ $user->display_name ?? $user->name }}!</div>
                        <div class="user-email">¡Tu registro en Viantryp ha sido exitoso!</div>
                    </div>
                </div>
            </div>

            <!-- STEP 1: ACCOUNT USE SELECTOR -->
            <div id="welcomeStepUseType">
                <div class="welcome-heading">
                    <h2 class="welcome-main-title">¡Bienvenido a la comunidad Viantryp!</h2>
                    <p class="welcome-main-desc">
                        Personalizaremos tu panel y tus herramientas según el uso que quieras darle a la plataforma. Podrás cambiar esto en cualquier momento desde tu perfil.
                    </p>
                </div>

                <!-- USE CASE GRID -->
                <div class="use-case-grid">
                    <!-- Option 1: Personal -->
                    <div class="use-case-card" onclick="selectUseCase('personal')" id="card-use-personal">
                        <div class="use-case-icon-wrapper">
                            <i class="fas fa-plane-departure"></i>
                        </div>
                        <div class="use-case-text-group">
                            <h3 class="use-case-title">Viajes Personales</h3>
                            <p class="use-case-desc">
                                Para planificar mis propias vacaciones, escapadas de fin de semana y aventuras con amigos o familia de forma visual e inteligente.
                            </p>
                        </div>
                    </div>

                    <!-- Option 2: Agency -->
                    <div class="use-case-card" onclick="selectUseCase('agency')" id="card-use-agency">
                        <div class="use-case-icon-wrapper">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div class="use-case-text-group">
                            <h3 class="use-case-title">Negocio o Agencia</h3>
                            <p class="use-case-desc">
                                Para crear itinerarios profesionales de gran impacto, gestionados en equipo y con un flujo de trabajo más ágil.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- ACTION BUTTON -->
                <div class="use-case-actions">
                    <button type="button" onclick="goToProBanner()" id="btnGoToBanner" class="p-btn current btn-submit-step" disabled style="opacity: 0.5; cursor: not-allowed;">
                        Continuar &rarr;
                    </button>
                </div>
            </div>

            <!-- STEP 2: CELEBRATION & PRO 1-MONTH TRIAL BANNER -->
            <div id="welcomeStepProBanner" style="display: none;">
                <div class="pro-launch-container">
                    
                    <div class="pro-launch-message-box">
                        <p class="pro-launch-message-text">
                            Por motivo de nuestro lanzamiento oficial, hemos activado <strong>1 mes gratis del Plan Viajero Pro</strong> en tu cuenta. Explora Tryp IA, gestiona hasta 20 itinerarios activos y diseña tus viajes sin restricciones.
                        </p>
                    </div>

                    <div class="pro-benefits-section">
                        <h4 class="pro-benefits-heading">
                            Lo que podrás hacer con el Plan Viajero Pro durante este mes:
                        </h4>

                        <ul class="pro-launch-benefits-list">
                            <li class="benefit-item">
                                <span class="benefit-bullet">
                                    <svg viewBox="0 0 20 20" fill="currentColor" width="15" height="15">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                                <div class="benefit-text">
                                    <strong>Tryp IA (Beta) Ilimitado</strong>: Asistencia inteligente continua para estructurar y organizar vuelos, hoteles y actividades.
                                </div>
                            </li>
                            <li class="benefit-item">
                                <span class="benefit-bullet">
                                    <svg viewBox="0 0 20 20" fill="currentColor" width="15" height="15">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                                <div class="benefit-text">
                                    <strong>Hasta 20 Itinerarios Activos</strong>: Planificación completa de múltiples viajes en lienzos interactivos y mapas.
                                </div>
                            </li>
                            <li class="benefit-item">
                                <span class="benefit-bullet">
                                    <svg viewBox="0 0 20 20" fill="currentColor" width="15" height="15">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                                <div class="benefit-text">
                                    <strong>Colaboradores de Edición en Tiempo Real</strong>: Comparte tus itinerarios e invita a acompañantes para editar juntos.
                                </div>
                            </li>
                            <li class="benefit-item">
                                <span class="benefit-bullet">
                                    <svg viewBox="0 0 20 20" fill="currentColor" width="15" height="15">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                                <div class="benefit-text">
                                    <strong>Exportación a PDF y Archivos Adjuntos</strong>: Descarga tus itinerarios y adjunta hasta 20 comprobantes y billetes por viaje.
                                </div>
                            </li>
                        </ul>
                        <div class="pro-launch-more-hint">
                            ... y mucho más.
                        </div>
                    </div>

                    <div class="pro-launch-actions">
                        <button type="button" onclick="submitInitialConfig()" id="btnSubmitConfig" class="pro-launch-submit-btn">
                            <span>Comenzar a explorar Viantryp &rarr;</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <!-- LOADING OVERLAY -->
        <div id="welcomeLoadingOverlay" class="welcome-loading-overlay">
            <div style="padding: 20px; text-align:center;">
                <i class="fas fa-circle-notch fa-spin" style="font-size:32px; color:#1EAACE; margin-bottom:10px; display:block;"></i>
                <span style="font-size:14px; font-weight:700; color:#0f172a;">Activando tu mes gratis de Viajero Pro...</span>
            </div>
        </div>
    </div>
</div>

<style>
    #welcomePlanModal {
        position: fixed !important;
        inset: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        height: 100dvh !important;
        max-width: 100vw !important;
        max-height: 100vh !important;
        max-height: 100dvh !important;
        background: rgba(15, 23, 42, 0.78) !important;
        backdrop-filter: blur(8px) !important;
        -webkit-backdrop-filter: blur(8px) !important;
        z-index: 10002 !important;
        font-family: 'Manrope', system-ui, -apple-system, sans-serif !important;
        border: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        transform: none !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 20px 14px !important;
        box-sizing: border-box !important;
        overflow-y: auto !important;
        -webkit-overflow-scrolling: touch;
    }

    #welcomePlanModal .modal-content.modal-welcome-onboarding {
        background: #ffffff !important;
        border-radius: 28px !important;
        position: relative !important;
        box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.4) !important;
        animation: welcomeModalPop 0.32s cubic-bezier(0.16, 1, 0.3, 1) !important;
        overflow: hidden !important;
        margin: auto !important;
        max-height: calc(100vh - 36px) !important;
        max-height: calc(100dvh - 36px) !important;
        display: flex !important;
        flex-direction: column !important;
        width: 100% !important;
        max-width: 660px !important;
        border: none !important;
        transition: max-width 0.3s ease;
        box-sizing: border-box !important;
    }

    @keyframes welcomeModalPop {
        from {
            transform: scale(0.96) translateY(14px);
            opacity: 0;
        }
        to {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
    }

    #welcomePlanModal .modal-body {
        padding: 28px;
        overflow-y: auto !important;
        max-height: calc(100vh - 70px) !important;
        max-height: calc(100dvh - 70px) !important;
        -webkit-overflow-scrolling: touch;
        overscroll-behavior: contain;
        font-family: 'Manrope', system-ui, -apple-system, sans-serif !important;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
        box-sizing: border-box !important;
    }

    #welcomePlanModal .modal-body::-webkit-scrollbar {
        width: 5px;
    }

    #welcomePlanModal .modal-body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 6px;
    }

    #welcomePlanModal .modal-user-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f1f5f9;
    }

    #welcomePlanModal .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    #welcomePlanModal .user-avatar {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #1EAACE 0%, #0e7aad 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 800;
        font-size: 15px;
        overflow: hidden;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(30, 170, 206, 0.3);
    }

    #welcomePlanModal .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    #welcomePlanModal .user-name {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    #welcomePlanModal .user-email {
        font-size: 12px;
        color: #0284c7;
        font-weight: 600;
        margin-top: 2px;
    }

    #welcomePlanModal .welcome-heading {
        text-align: center;
        margin-bottom: 22px;
    }

    #welcomePlanModal .welcome-main-title {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
        letter-spacing: -0.02em;
        line-height: 1.25;
    }

    #welcomePlanModal .welcome-main-desc {
        font-size: 13px;
        color: #64748b;
        max-width: 520px;
        margin: 0 auto;
        line-height: 1.5;
    }

    /* USE CASES GRID */
    #welcomePlanModal .use-case-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        max-width: 600px;
        margin: 0 auto 20px;
    }

    #welcomePlanModal .use-case-card {
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 20px;
        padding: 24px 18px;
        cursor: pointer;
        transition: all 0.22s ease;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        -webkit-tap-highlight-color: transparent;
        user-select: none;
    }

    #welcomePlanModal .use-case-card:hover {
        transform: translateY(-3px);
        border-color: #1EAACE;
        box-shadow: 0 12px 28px rgba(30, 170, 206, 0.12);
    }

    #welcomePlanModal .use-case-card:active {
        transform: scale(0.98);
    }

    #welcomePlanModal .use-case-card.active {
        border-color: #1EAACE !important;
        background: #f0fdff !important;
        box-shadow: 0 10px 30px -5px rgba(30, 170, 206, 0.25) !important;
    }

    #welcomePlanModal .use-case-icon-wrapper {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: #f0fdff;
        border: 1px solid #cffafe;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
        transition: all 0.22s ease;
        flex-shrink: 0;
    }

    #welcomePlanModal .use-case-card.active .use-case-icon-wrapper {
        background: #1EAACE;
        border-color: #1EAACE;
    }

    #welcomePlanModal .use-case-icon-wrapper i {
        font-size: 22px;
        color: #1EAACE;
        transition: all 0.22s ease;
    }

    #welcomePlanModal .use-case-card.active .use-case-icon-wrapper i {
        color: #ffffff;
    }

    #welcomePlanModal .use-case-text-group {
        display: flex;
        flex-direction: column;
    }

    #welcomePlanModal .use-case-title {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 6px;
    }

    #welcomePlanModal .use-case-desc {
        font-size: 12px;
        color: #64748b;
        line-height: 1.45;
        margin: 0;
    }

    #welcomePlanModal .use-case-actions {
        display: flex;
        justify-content: flex-end;
        width: 100%;
        margin-top: 14px;
    }

    #welcomePlanModal .p-btn {
        padding: 13px 28px;
        border-radius: 14px;
        font-size: 14.5px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        -webkit-tap-highlight-color: transparent;
    }

    #welcomePlanModal .p-btn.current {
        background: linear-gradient(135deg, #1EAACE 0%, #0e7aad 100%) !important;
        color: white !important;
        box-shadow: 0 4px 14px rgba(30, 170, 206, 0.35);
    }

    #welcomePlanModal .p-btn.current:not(:disabled):hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(30, 170, 206, 0.5);
    }

    #welcomePlanModal .p-btn.current:not(:disabled):active {
        transform: scale(0.98);
    }

    /* STEP 2: CELEBRATION & PRO LAUNCH BANNER */
    .pro-launch-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        animation: fadeIn 0.32s cubic-bezier(0.16, 1, 0.3, 1);
        width: 100%;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .pro-launch-message-box {
        background: linear-gradient(135deg, rgba(30, 170, 206, 0.08) 0%, rgba(14, 122, 173, 0.05) 100%);
        border: 1.5px solid rgba(30, 170, 206, 0.3);
        border-radius: 18px;
        padding: 18px 22px;
        margin-bottom: 20px;
        width: 100%;
        box-sizing: border-box;
        box-shadow: 0 10px 25px -10px rgba(30, 170, 206, 0.15);
        text-align: center;
    }

    .pro-launch-message-text {
        font-size: 14px;
        line-height: 1.6;
        color: #1e293b;
        margin: 0;
    }

    .pro-launch-message-text strong {
        color: #0284c7;
        font-weight: 800;
    }

    .pro-benefits-section {
        width: 100%;
        box-sizing: border-box;
    }

    .pro-benefits-heading {
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 12px 0;
        text-align: left;
        letter-spacing: -0.01em;
    }

    .pro-launch-benefits-list {
        list-style: none;
        padding: 0;
        margin: 0 0 10px 0;
        display: flex;
        flex-direction: column;
        gap: 8px;
        width: 100%;
    }

    .benefit-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 6px 2px;
        background: transparent !important;
        border: none !important;
        font-size: 13.5px;
        line-height: 1.5;
        color: #334155;
    }

    .benefit-bullet {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: rgba(30, 170, 206, 0.14);
        color: #0284c7;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .benefit-text {
        flex: 1;
    }

    .benefit-text strong {
        color: #0f172a;
        font-weight: 700;
    }

    .pro-launch-more-hint {
        font-size: 13.5px;
        font-weight: 700;
        color: #0284c7;
        padding-left: 34px;
        margin-top: 6px;
        letter-spacing: 0.2px;
        font-style: italic;
    }

    .pro-launch-actions {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        margin-top: 20px;
        padding-top: 16px;
        border-top: 1px solid #f1f5f9;
        box-sizing: border-box;
    }

    .pro-launch-submit-btn {
        width: 100% !important;
        background: linear-gradient(135deg, #1EAACE 0%, #0e7aad 100%) !important;
        color: #ffffff !important;
        font-size: 15px !important;
        font-weight: 800 !important;
        border: none !important;
        border-radius: 14px !important;
        padding: 14px 24px !important;
        cursor: pointer !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        box-shadow: 0 8px 22px -4px rgba(30, 170, 206, 0.45) !important;
        transition: all 0.22s ease !important;
        -webkit-tap-highlight-color: transparent;
    }

    .pro-launch-submit-btn:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 12px 26px -4px rgba(30, 170, 206, 0.6) !important;
    }

    .pro-launch-submit-btn:active {
        transform: scale(0.98) !important;
    }

    /* LOADING OVERLAY */
    .welcome-loading-overlay {
        display: none;
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(4px);
        align-items: center;
        justify-content: center;
        z-index: 100;
    }

    /* ========================================================
       RESPONSIVE & MOBILE DESIGN (<= 640px)
       ======================================================== */
    @media (max-width: 640px) {
        #welcomePlanModal {
            padding: max(10px, env(safe-area-inset-top, 10px)) max(10px, env(safe-area-inset-right, 10px)) max(14px, env(safe-area-inset-bottom, 14px)) max(10px, env(safe-area-inset-left, 10px)) !important;
            align-items: center !important;
        }

        #welcomePlanModal .modal-content.modal-welcome-onboarding {
            border-radius: 22px !important;
            max-height: calc(100dvh - env(safe-area-inset-top, 0px) - env(safe-area-inset-bottom, 0px) - 20px) !important;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.35) !important;
        }

        #welcomePlanModal .modal-body {
            padding: 16px 14px 18px !important;
            max-height: calc(100dvh - env(safe-area-inset-top, 0px) - env(safe-area-inset-bottom, 0px) - 36px) !important;
        }

        #welcomePlanModal .modal-user-header {
            margin-bottom: 14px;
            padding-bottom: 10px;
        }

        #welcomePlanModal .user-avatar {
            width: 38px;
            height: 38px;
            font-size: 13px;
        }

        #welcomePlanModal .user-name {
            font-size: 14.5px;
        }

        #welcomePlanModal .user-email {
            font-size: 11.5px;
        }

        #welcomePlanModal .welcome-heading {
            margin-bottom: 14px;
        }

        #welcomePlanModal .welcome-main-title {
            font-size: 19px;
            margin-bottom: 6px;
            line-height: 1.25;
        }

        #welcomePlanModal .welcome-main-desc {
            font-size: 11.5px;
            line-height: 1.4;
        }

        /* Horizontal Card Layout on Mobile for Better Ergonomics */
        #welcomePlanModal .use-case-grid {
            grid-template-columns: 1fr;
            gap: 10px;
            margin-bottom: 14px;
        }

        #welcomePlanModal .use-case-card {
            flex-direction: row;
            text-align: left;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 16px;
        }

        #welcomePlanModal .use-case-icon-wrapper {
            width: 42px;
            height: 42px;
            margin-bottom: 0;
            flex-shrink: 0;
        }

        #welcomePlanModal .use-case-icon-wrapper i {
            font-size: 18px;
        }

        #welcomePlanModal .use-case-title {
            font-size: 14px;
            margin-bottom: 2px;
        }

        #welcomePlanModal .use-case-desc {
            font-size: 11px;
            line-height: 1.35;
        }

        #welcomePlanModal .use-case-actions {
            margin-top: 10px;
        }

        #welcomePlanModal .p-btn.btn-submit-step {
            width: 100% !important;
            padding: 13px 20px !important;
            font-size: 14.5px !important;
            border-radius: 12px !important;
            min-height: 46px !important;
        }

        /* Step 2 Mobile */
        .pro-launch-message-box {
            padding: 12px 14px;
            border-radius: 14px;
            margin-bottom: 14px;
        }

        .pro-launch-message-text {
            font-size: 12.5px;
            line-height: 1.5;
        }

        .pro-benefits-heading {
            font-size: 13px;
            margin-bottom: 8px;
        }

        .pro-launch-more-hint {
            font-size: 12px;
            padding-left: 29px;
            margin-top: 4px;
        }

        .pro-launch-benefits-list {
            gap: 6px;
            margin-bottom: 10px;
        }

        .benefit-item {
            font-size: 12px;
            line-height: 1.4;
            padding: 4px 0;
            gap: 10px;
        }

        .benefit-bullet {
            width: 19px;
            height: 19px;
            margin-top: 1px;
        }

        .benefit-bullet svg {
            width: 13px;
            height: 13px;
        }

        .pro-launch-actions {
            margin-top: 14px;
            padding-top: 12px;
        }

        .pro-launch-submit-btn {
            width: 100% !important;
            padding: 13px 20px !important;
            font-size: 14.5px !important;
            border-radius: 12px !important;
            min-height: 46px !important;
        }
    }

    /* ========================================================
       NATIVE APP & PWA MODE (is-viantryp-app)
       ======================================================== */
    .is-viantryp-app #welcomePlanModal,
    body.is-viantryp-app #welcomePlanModal {
        padding-top: max(14px, env(safe-area-inset-top, 14px)) !important;
        padding-bottom: max(18px, env(safe-area-inset-bottom, 18px)) !important;
        padding-left: max(10px, env(safe-area-inset-left, 10px)) !important;
        padding-right: max(10px, env(safe-area-inset-right, 10px)) !important;
        height: 100dvh !important;
        max-height: 100dvh !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .is-viantryp-app #welcomePlanModal .modal-content.modal-welcome-onboarding,
    body.is-viantryp-app #welcomePlanModal .modal-content.modal-welcome-onboarding {
        max-height: calc(100dvh - env(safe-area-inset-top, 0px) - env(safe-area-inset-bottom, 0px) - 20px) !important;
        border-radius: 22px !important;
    }

    .is-viantryp-app #welcomePlanModal .modal-body,
    body.is-viantryp-app #welcomePlanModal .modal-body {
        max-height: calc(100dvh - env(safe-area-inset-top, 0px) - env(safe-area-inset-bottom, 0px) - 36px) !important;
        padding: 16px 14px 18px !important;
    }
</style>

<script>
    window.selectedUseCase = null;

    function selectUseCase(type) {
        window.selectedUseCase = type;

        const pCard = document.getElementById('card-use-personal');
        const aCard = document.getElementById('card-use-agency');
        if (pCard) pCard.classList.toggle('active', type === 'personal');
        if (aCard) aCard.classList.toggle('active', type === 'agency');

        const btn = document.getElementById('btnGoToBanner');
        if (btn) {
            btn.disabled = false;
            btn.style.opacity = '1';
            btn.style.cursor = 'pointer';
        }
    }

    function goToProBanner() {
        if (!window.selectedUseCase) return;

        const step1 = document.getElementById('welcomeStepUseType');
        const step2 = document.getElementById('welcomeStepProBanner');
        if (step1) step1.style.display = 'none';
        if (step2) step2.style.display = 'block';

        // Disparar animación de confetti festiva en alta definición
        fireWelcomeConfetti();
    }

    // Animación de Confetti de Alto Rendimiento en Canvas (Ultra Smooth & Retina Ready)
    function fireWelcomeConfetti() {
        const canvas = document.getElementById('welcomeConfettiCanvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        const modalContent = document.querySelector('#welcomePlanModal .modal-content');

        const rect = modalContent ? modalContent.getBoundingClientRect() : { width: window.innerWidth, height: window.innerHeight };
        const dpr = Math.min(window.devicePixelRatio || 1, 2);
        
        canvas.width = rect.width * dpr;
        canvas.height = rect.height * dpr;
        canvas.style.width = rect.width + 'px';
        canvas.style.height = rect.height + 'px';
        ctx.scale(dpr, dpr);

        const colors = ['#1EAACE', '#38bdf8', '#0284c7', '#f59e0b', '#fbbf24', '#10b981', '#6366f1', '#ec4899'];
        const particles = [];
        const particleCount = rect.width < 500 ? 55 : 80;

        for (let i = 0; i < particleCount; i++) {
            particles.push({
                x: rect.width / 2 + (Math.random() - 0.5) * (rect.width * 0.4),
                y: rect.height * 0.32,
                w: Math.random() * 7 + 5,
                h: Math.random() * 5 + 3,
                color: colors[Math.floor(Math.random() * colors.length)],
                vx: (Math.random() - 0.5) * 10,
                vy: (Math.random() * -8) - 3,
                rotation: Math.random() * 360,
                rotationSpeed: (Math.random() - 0.5) * 14,
                opacity: 1,
                gravity: 0.30 + Math.random() * 0.12
            });
        }

        const startTime = Date.now();

        function render() {
            const elapsed = Date.now() - startTime;
            ctx.clearRect(0, 0, rect.width, rect.height);

            let activeCount = 0;
            particles.forEach(p => {
                p.x += p.vx;
                p.y += p.vy;
                p.vy += p.gravity;
                p.rotation += p.rotationSpeed;
                p.vx *= 0.985;

                if (elapsed > 1300) {
                    p.opacity = Math.max(0, 1 - (elapsed - 1300) / 900);
                }

                if (p.opacity > 0 && p.y < rect.height + 30) {
                    activeCount++;
                    ctx.save();
                    ctx.translate(p.x, p.y);
                    ctx.rotate((p.rotation * Math.PI) / 180);
                    ctx.globalAlpha = p.opacity;
                    ctx.fillStyle = p.color;
                    ctx.fillRect(-p.w / 2, -p.h / 2, p.w, p.h);
                    ctx.restore();
                }
            });

            if (activeCount > 0 && elapsed < 2300) {
                requestAnimationFrame(render);
            } else {
                ctx.clearRect(0, 0, rect.width, rect.height);
            }
        }

        render();
    }

    async function submitInitialConfig() {
        if (!window.selectedUseCase) return;

        const overlay = document.getElementById('welcomeLoadingOverlay');
        if (overlay) overlay.style.display = 'flex';

        try {
            const response = await fetch('{{ route("profile.choose-initial-plan") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    plan: 'avanzado',
                    account_type: window.selectedUseCase
                })
            });

            const result = await response.json();
            if (result.success) {
                location.reload();
            } else {
                alert(result.message || 'Error al guardar la configuración inicial.');
                if (overlay) overlay.style.display = 'none';
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Ocurrió un error inesperado al guardar tu configuración.');
            if (overlay) overlay.style.display = 'none';
        }
    }
</script>