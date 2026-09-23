@php
    $user = auth()->user();
    if (!$user || $user->initial_plan_chosen_at)
        return;

    $planData = [
        'básico' => [
            'name' => 'Básico',
            'price_monthly' => 0,
            'price_annual' => 0,
            'is_custom' => false,
            'limit_trips' => 1,
            'limit_editors' => 0,
            'benefits' => [
                '1 itinerario activo',
                'Fotos Unsplash & GIFs ilimitados',
                '5 consultas en Google Places activas',
                '5 consultas Tryp IA por viaje',
                '5 archivos adjuntos por viaje',
                'Personalización de colores y temas'
            ],
            'accent' => '#64748b'
        ],
        'avanzado' => [
            'name' => 'Viajero Pro',
            'price_monthly' => 6.99,
            'price_annual' => 5.59,
            'is_custom' => false,
            'limit_trips' => 20,
            'limit_editors' => 2,
            'benefits' => [
                '20 itinerarios activos',
                '2 colaboradores de edición de viaje',
                '50 consultas en Google Places activas',
                '20 archivos adjuntos por itinerario',
                'Tryp IA (Asistente de Viantryp) Ilimitado',
                'Exportación de PDF'
            ],
            'accent' => '#1EAACE',
            'popular' => true
        ],
        'colaborativo' => [
            'name' => 'Negocios',
            'price_monthly' => 24.99,
            'price_annual' => 19.99,
            'is_custom' => false,
            'limit_trips' => 1000000,
            'limit_editors' => 1000000,
            'benefits' => [
                'Itinerarios activos ilimitados',
                'Colaboradores y editores ilimitados',
                'Consultas en Google Places ilimitadas',
                'Archivos adjuntos ilimitados',
                'Marca Blanca con Logo de Agencia',
                'Soporte prioritario dedicado'
            ],
            'accent' => '#0e5a6a'
        ]
    ];

    foreach ($planData as $key => &$data) {
        if (!$data['is_custom'] && $data['price_monthly'] > 0) {
            $data['savings'] = round(($data['price_monthly'] - $data['price_annual']) * 12, 2);
        }
    }
    unset($data);
@endphp

<div id="welcomePlanModal" class="modal upgrade-premium-modal" style="display: flex; z-index: 10002;">
    <div class="modal-content modal-wide" style="transition: max-width 0.3s ease;">
        <div class="modal-body">

            <!-- STEP 1: PLANS SELECTION -->
            <div id="welcomeStepPlans">
                <!-- HEADER: USER INFO -->
                <div class="modal-user-header">
                    <div class="user-info">
                        <div class="avatar user-avatar">
                            @if($user->avatar)
                                <img src="{{ str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar) }}"
                                    alt="">
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

                <!-- WELCOME TEXT -->
                <div class="welcome-heading">
                    <h2 class="welcome-main-title">Elige tu plan de inicio</h2>
                    <p class="welcome-main-desc">
                        Selecciona el plan que mejor se adapte a tus necesidades.
                    </p>
                </div>

                <!-- PRICING TOGGLE -->
                <div class="pricing-toggle-wrap">
                    <span class="toggle-label active" id="welcomeLabelMonthly">Mensual</span>
                    <div class="toggle-switch" id="welcomePriceToggle"></div>
                    <span class="toggle-label" id="welcomeLabelAnnual">Anual <span
                            class="annual-discount-pill">-20%</span></span>
                </div>

                <!-- PLANS GRID -->
                <div class="p-grid-container">
                    @foreach($planData as $key => $data)
                        <div class="p-card {{ isset($data['popular']) ? 'active' : '' }}">
                            @if(isset($data['popular']))
                                <div class="p-popular">RECOMENDADO</div>
                            @endif

                            <div>
                                <div class="p-name">{{ $data['name'] }}</div>
                                <div class="p-price"
                                    style="{{ !is_numeric($data['price_monthly']) ? 'font-size: 18px;' : '' }}">
                                    @if(is_numeric($data['price_monthly']))<span class="currency">$</span>@endif<span
                                        class="p-price-val"
                                        data-monthly="{{ is_numeric($data['price_monthly']) ? number_format($data['price_monthly'], 2, '.', '') : $data['price_monthly'] }}"
                                        data-annual="{{ is_numeric($data['price_annual']) ? number_format($data['price_annual'], 2, '.', '') : $data['price_annual'] }}">{{ is_numeric($data['price_monthly']) ? number_format($data['price_monthly'], 2, '.', '') : $data['price_monthly'] }}</span>@if(!$data['is_custom'])<small>/mes</small>@endif
                                </div>

                                @if(isset($data['savings']) && $data['savings'] > 0)
                                    <div class="plan-savings-hint">Ahorras ${{ $data['savings'] }} al año</div>
                                @endif

                                <div class="p-benefits">
                                    @foreach($data['benefits'] as $b)
                                        <div class="p-benefit">
                                            <i class="fas fa-check"></i> {{ $b }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @if($key === 'básico')
                                <button onclick="confirmInitialPlan('{{ $key }}')" class="p-btn">
                                    Elegir Plan
                                </button>
                            @elseif($key === 'avanzado')
                                <button onclick="confirmInitialPlan('{{ $key }}')" class="p-btn current">
                                    Probar 7 Días Gratis →
                                </button>
                            @elseif($key === 'colaborativo')
                                <button onclick="confirmInitialPlan('{{ $key }}')" class="p-btn">
                                    Probar 7 Días Gratis →
                                </button>
                            @else
                                <button onclick="checkInitialPlanGate('{{ $key }}')" class="p-btn">
                                    Elegir Plan
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="pricing-disclaimer">
                    * Todos los precios están expresados en USD (Dólares Estadounidenses)
                </div>

                <div class="modal-footer-links">
                    <span>¿Tienes dudas? <a href="{{ route('contact') }}" target="_blank">Contáctanos &rarr;</a></span>
                </div>
            </div>

            <!-- STEP 2: ACCOUNT USE SELECTOR -->
            <div id="welcomeStepUseType" style="display: none;">
                <!-- HEADER -->
                <div class="welcome-heading">
                    <h2 class="welcome-main-title">
                        ¿Cómo vas a usar Viantryp?
                    </h2>
                    <p class="welcome-main-desc">
                        Personalizaremos tu panel y tus herramientas según tu respuesta. Podrás cambiar esto en
                        cualquier momento desde tu perfil.
                    </p>
                </div>

                <!-- USE CASE GRID -->
                <div class="use-case-grid">
                    <!-- Option 1: Personal -->
                    <div class="use-case-card" onclick="selectUseCase('personal')" id="card-use-personal">
                        <div class="use-case-icon-wrapper">
                            <i class="fas fa-plane-departure"></i>
                        </div>
                        <h3 class="use-case-title">Viajes Personales</h3>
                        <p class="use-case-desc">
                            Para planificar mis propias vacaciones, escapadas de fin de semana y aventuras con amigos o
                            familia de forma visual e inteligente.
                        </p>
                    </div>

                    <!-- Option 2: Agency -->
                    <div class="use-case-card" onclick="selectUseCase('agency')" id="card-use-agency">
                        <div class="use-case-icon-wrapper">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <h3 class="use-case-title">Negocio o Agencia</h3>
                        <p class="use-case-desc">
                            Para crear itinerarios profesionales de gran impacto, gestionados en equipo y con un flujo
                            de trabajo más ágil.
                        </p>
                    </div>
                </div>

                <!-- ACTION BUTTONS -->
                <div class="use-case-actions">
                    <button onclick="goBackToPlans()" class="p-btn btn-secondary-back">
                        ← Volver a planes
                    </button>
                    <button onclick="submitInitialConfig()" id="btnSubmitConfig" class="p-btn current btn-submit-step"
                        disabled>
                        Comenzar ahora →
                    </button>
                </div>
            </div>

        </div>

        <!-- LOADING OVERLAY -->
        <div id="welcomeLoadingOverlay" class="welcome-loading-overlay">
            <div style="padding: 20px; text-align:center;">
                <i class="fas fa-circle-notch fa-spin"
                    style="font-size:32px; color:#1EAACE; margin-bottom:10px; display:block;"></i>
                <span style="font-size:14px; font-weight:700; color:#0f172a;">Configurando tu cuenta...</span>
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
        background: rgba(15, 23, 42, 0.75) !important;
        backdrop-filter: blur(8px) !important;
        -webkit-backdrop-filter: blur(8px) !important;
        z-index: 10002 !important;
        font-family: 'Manrope', sans-serif !important;
        border: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        transform: none !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 20px 12px !important;
        box-sizing: border-box !important;
        overflow-y: auto !important;
        -webkit-overflow-scrolling: touch;
    }

    #welcomePlanModal .modal-content {
        background: #ffffff !important;
        border-radius: 28px !important;
        position: relative !important;
        box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.4) !important;
        animation: welcomeModalPop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1) !important;
        overflow: hidden !important;
        margin: auto !important;
        max-height: calc(100vh - 40px) !important;
        max-height: calc(100dvh - 40px) !important;
        display: flex !important;
        flex-direction: column !important;
        width: 100% !important;
        border: none !important;
    }

    #welcomePlanModal .modal-wide {
        width: 100% !important;
        max-width: 820px !important;
    }

    @keyframes welcomeModalPop {
        from {
            transform: scale(0.97) translateY(20px);
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
        max-height: calc(100vh - 80px) !important;
        max-height: calc(100dvh - 80px) !important;
        -webkit-overflow-scrolling: touch;
        font-family: 'Manrope', sans-serif !important;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
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
        padding-top: 2px;
    }

    #welcomePlanModal .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    #welcomePlanModal .user-avatar {
        width: 44px;
        height: 44px;
        background: #1EAACE;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 800;
        font-size: 15px;
        overflow: hidden;
        flex-shrink: 0;
    }

    #welcomePlanModal .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    #welcomePlanModal .user-name {
        font-weight: 700;
        color: #0f172a;
        font-size: 16px;
        margin-bottom: 2px;
        line-height: 1.2;
    }

    #welcomePlanModal .user-email {
        font-size: 13px;
        color: #64748b;
        line-height: 1.3;
    }

    #welcomePlanModal .welcome-heading {
        text-align: center;
        margin-bottom: 22px;
    }

    #welcomePlanModal .welcome-main-title {
        font-size: 24px;
        font-weight: 900;
        color: #0f172a;
        margin: 0 0 6px;
        letter-spacing: -0.02em;
        line-height: 1.25;
    }

    #welcomePlanModal .welcome-main-desc {
        font-size: 13.5px;
        color: #64748b;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.5;
        font-weight: 500;
    }

    /* PRICING TOGGLE STYLES */
    #welcomePlanModal .pricing-toggle-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin: 0 auto 24px;
        background: #f8fafc;
        padding: 8px 16px;
        border-radius: 100px;
        width: fit-content;
        border: 1px solid #e2e8f0;
        user-select: none;
    }

    #welcomePlanModal .toggle-label {
        font-size: 11px;
        font-weight: 700;
        color: #94a3b8;
        transition: 0.3s;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    #welcomePlanModal .toggle-label.active {
        color: #1EAACE;
    }

    #welcomePlanModal .toggle-switch {
        position: relative;
        width: 44px;
        height: 24px;
        background: #e2e8f0;
        border-radius: 100px;
        cursor: pointer;
        transition: 0.3s;
    }

    #welcomePlanModal .toggle-switch::after {
        content: '';
        position: absolute;
        top: 3px;
        left: 3px;
        width: 18px;
        height: 18px;
        background: white;
        border-radius: 50%;
        transition: 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    #welcomePlanModal .toggle-switch.annual {
        background: #1EAACE;
    }

    #welcomePlanModal .toggle-switch.annual::after {
        transform: translateX(20px);
    }

    #welcomePlanModal .annual-discount-pill {
        background: #ecfdf5;
        color: #1eaace;
        font-size: 9px;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 100px;
        margin-left: 4px;
        border: 1px solid #d1fae5;
    }

    #welcomePlanModal .plan-savings-hint {
        font-size: 10px;
        color: #1eaace;
        font-weight: 700;
        margin-top: 4px;
        opacity: 0;
        transform: translateY(5px);
        transition: 0.3s;
        height: 0;
        overflow: hidden;
    }

    #welcomePlanModal.annual-view .plan-savings-hint,
    #welcomePlanModal .toggle-switch.annual~.plan-savings-hint {
        opacity: 1;
        transform: translateY(0);
        height: auto;
        margin-top: 4px;
    }

    /* GRID & CARDS */
    #welcomePlanModal .p-grid-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        align-items: stretch;
        margin-bottom: 20px;
    }

    #welcomePlanModal .p-card {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 20px;
        padding: 22px 18px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.25s ease;
        position: relative;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
    }

    #welcomePlanModal .p-card.active {
        border: 2.5px solid #1EAACE;
        background: #f0fdff;
        box-shadow: 0 10px 30px -5px rgba(30, 170, 206, 0.2);
    }

    #welcomePlanModal .p-popular {
        position: absolute;
        top: -12px;
        left: 50%;
        transform: translateX(-50%);
        background: #1EAACE;
        color: white;
        font-size: 10px;
        font-weight: 800;
        padding: 3px 14px;
        border-radius: 100px;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 10px rgba(30, 170, 206, 0.3);
        z-index: 2;
        white-space: nowrap;
    }

    #welcomePlanModal .p-name {
        font-size: 14px;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    #welcomePlanModal .p-price {
        font-size: 26px;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 14px;
        display: flex;
        align-items: baseline;
        gap: 2px;
    }

    #welcomePlanModal .p-price small {
        font-size: 11px;
        color: #64748b;
        font-weight: 600;
    }

    #welcomePlanModal .p-benefits {
        flex-grow: 1;
        margin-bottom: 18px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    #welcomePlanModal .p-benefit {
        font-size: 11.5px;
        color: #475569;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 7px;
        line-height: 1.3;
    }

    #welcomePlanModal .p-benefit i {
        color: #1eaace;
        font-size: 11px;
        flex-shrink: 0;
    }

    #welcomePlanModal .p-btn {
        width: 100%;
        min-height: 44px;
        padding: 10px 14px;
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        background: #ffffff;
        color: #0f172a;
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        font-family: 'Manrope', sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        box-sizing: border-box;
    }

    #welcomePlanModal .p-btn:hover {
        border-color: #cbd5e1;
        transform: translateY(-1px);
    }

    #welcomePlanModal .p-btn.current {
        background: #1EAACE;
        color: white;
        border-color: #1EAACE;
        box-shadow: 0 4px 12px rgba(30, 170, 206, 0.25);
    }

    #welcomePlanModal .pricing-disclaimer {
        text-align: center;
        margin-top: 18px;
        margin-bottom: 6px;
        font-size: 11px;
        color: #94a3b8;
        font-weight: 500;
    }

    #welcomePlanModal .modal-footer-links {
        text-align: center;
        margin-top: 10px;
        font-size: 12px;
        color: #94a3b8;
        font-weight: 600;
    }

    #welcomePlanModal .modal-footer-links a {
        color: #1EAACE;
        text-decoration: none;
        font-weight: 600;
    }

    /* STEP 2 USE CASES */
    #welcomePlanModal .use-case-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        max-width: 640px;
        margin: 0 auto 30px;
    }

    #welcomePlanModal .use-case-card {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 20px;
        padding: 28px 20px;
        cursor: pointer;
        transition: all 0.25s ease;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
    }

    #welcomePlanModal .use-case-card:hover {
        transform: translateY(-3px);
        border-color: #1EAACE;
        box-shadow: 0 12px 28px rgba(30, 170, 206, 0.12);
    }

    #welcomePlanModal .use-case-card.active {
        border: 2.5px solid #1EAACE !important;
        background: #f0fdff !important;
        box-shadow: 0 10px 30px -5px rgba(30, 170, 206, 0.2) !important;
    }

    #welcomePlanModal .use-case-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #f0fdff;
        border: 1px solid #cffafe;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        transition: all 0.25s ease;
    }

    #welcomePlanModal .use-case-icon-wrapper i {
        font-size: 20px;
        color: #1EAACE;
        transition: all 0.25s ease;
    }

    #welcomePlanModal .use-case-card.active .use-case-icon-wrapper {
        background: #1EAACE !important;
        border-color: #1EAACE !important;
    }

    #welcomePlanModal .use-case-card.active .use-case-icon-wrapper i {
        color: #ffffff !important;
    }

    #welcomePlanModal .use-case-title {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 8px;
        letter-spacing: -0.01em;
    }

    #welcomePlanModal .use-case-desc {
        font-size: 12.5px;
        color: #64748b;
        margin: 0;
        line-height: 1.5;
        font-weight: 500;
    }

    #welcomePlanModal .use-case-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
        max-width: 440px;
        margin: 0 auto;
    }

    #welcomePlanModal .btn-secondary-back {
        flex: 1;
        border-color: #e2e8f0;
        color: #475569;
        margin: 0;
    }

    #welcomePlanModal .btn-submit-step {
        flex: 2;
        margin: 0;
        opacity: 0.6;
        cursor: not-allowed;
    }

    #welcomePlanModal .welcome-loading-overlay {
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.85);
        z-index: 10003;
        align-items: center;
        justify-content: center;
        border-radius: 28px;
    }

    /* RESPONSIVE & MOBILE / APP OPTIMIZATION */
    @media (max-width: 768px) {
        #welcomePlanModal {
            padding: max(12px, env(safe-area-inset-top, 12px)) max(10px, env(safe-area-inset-right, 10px)) max(14px, env(safe-area-inset-bottom, 14px)) max(10px, env(safe-area-inset-left, 10px)) !important;
            align-items: center !important;
        }

        #welcomePlanModal .modal-content {
            width: 100% !important;
            max-width: 100% !important;
            margin: auto !important;
            max-height: calc(100dvh - 24px) !important;
            max-height: calc(100vh - 24px) !important;
            border-radius: 22px !important;
        }

        #welcomePlanModal .modal-body {
            padding: 20px 16px !important;
            max-height: calc(100dvh - 36px) !important;
            max-height: calc(100vh - 36px) !important;
        }

        #welcomePlanModal .modal-wide {
            max-width: 100% !important;
            width: 100% !important;
        }

        #welcomePlanModal .modal-user-header {
            margin-bottom: 16px;
        }

        #welcomePlanModal .user-avatar {
            width: 40px;
            height: 40px;
            font-size: 14px;
        }

        #welcomePlanModal .user-name {
            font-size: 15px;
        }

        #welcomePlanModal .user-email {
            font-size: 12px;
        }

        #welcomePlanModal .welcome-heading {
            margin-bottom: 16px;
        }

        #welcomePlanModal .welcome-main-title {
            font-size: 20px;
        }

        #welcomePlanModal .welcome-main-desc {
            font-size: 12.5px;
            line-height: 1.45;
        }

        #welcomePlanModal .pricing-toggle-wrap {
            padding: 6px 14px;
            gap: 10px;
            margin-bottom: 18px;
            width: auto;
            max-width: 100%;
        }

        #welcomePlanModal .p-grid-container {
            grid-template-columns: 1fr;
            gap: 14px;
            margin-bottom: 16px;
        }

        #welcomePlanModal .p-card {
            padding: 18px 16px;
            border-radius: 18px;
        }

        #welcomePlanModal .p-popular {
            top: -10px;
            font-size: 9px;
            padding: 2px 10px;
        }

        #welcomePlanModal .p-price {
            font-size: 24px;
            margin-bottom: 10px;
        }

        #welcomePlanModal .p-benefits {
            gap: 7px;
            margin-bottom: 14px;
        }

        #welcomePlanModal .use-case-grid {
            grid-template-columns: 1fr;
            gap: 12px;
            margin: 0 auto 20px;
        }

        #welcomePlanModal .use-case-card {
            padding: 20px 16px;
            border-radius: 18px;
        }

        #welcomePlanModal .use-case-icon-wrapper {
            width: 44px;
            height: 44px;
            margin-bottom: 12px;
        }

        #welcomePlanModal .use-case-icon-wrapper i {
            font-size: 18px;
        }

        #welcomePlanModal .use-case-title {
            font-size: 15px;
            margin-bottom: 4px;
        }

        #welcomePlanModal .use-case-desc {
            font-size: 12px;
            line-height: 1.4;
        }
    }

    @media (max-width: 480px) {
        #welcomePlanModal {
            padding: max(8px, env(safe-area-inset-top, 8px)) max(8px, env(safe-area-inset-right, 8px)) max(12px, env(safe-area-inset-bottom, 12px)) max(8px, env(safe-area-inset-left, 8px)) !important;
        }

        #welcomePlanModal .modal-content {
            border-radius: 18px !important;
            max-height: calc(100dvh - 16px) !important;
            max-height: calc(100vh - 16px) !important;
        }

        #welcomePlanModal .modal-body {
            padding: 16px 12px !important;
            max-height: calc(100dvh - 24px) !important;
            max-height: calc(100vh - 24px) !important;
        }

        #welcomePlanModal .welcome-main-title {
            font-size: 18px;
        }

        #welcomePlanModal .welcome-main-desc {
            font-size: 12px;
        }

        #welcomePlanModal .use-case-actions {
            flex-direction: column-reverse;
            gap: 8px;
            width: 100%;
        }

        #welcomePlanModal .btn-secondary-back,
        #welcomePlanModal .btn-submit-step {
            width: 100%;
            flex: none;
        }
    }

    /* PWA & NATIVE APP MODE ADJUSTMENTS */
    .is-viantryp-app #welcomePlanModal,
    body.is-viantryp-app #welcomePlanModal {
        padding-top: max(16px, env(safe-area-inset-top, 16px)) !important;
        padding-bottom: max(24px, env(safe-area-inset-bottom, 24px)) !important;
        padding-left: max(10px, env(safe-area-inset-left, 10px)) !important;
        padding-right: max(10px, env(safe-area-inset-right, 10px)) !important;
        height: 100dvh !important;
        max-height: 100dvh !important;
    }

    .is-viantryp-app #welcomePlanModal .modal-content,
    body.is-viantryp-app #welcomePlanModal .modal-content {
        max-height: calc(100dvh - env(safe-area-inset-top, 0px) - env(safe-area-inset-bottom, 0px) - 20px) !important;
    }

    .is-viantryp-app #welcomePlanModal .modal-body,
    body.is-viantryp-app #welcomePlanModal .modal-body {
        max-height: calc(100dvh - env(safe-area-inset-top, 0px) - env(safe-area-inset-bottom, 0px) - 30px) !important;
    }
</style>

<script>
    window.selectedPlan = null;
    window.selectedUseCase = null;

    // Toggle logic for Welcome Modal
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('welcomePriceToggle');
        const modal = document.getElementById('welcomePlanModal');
        const labelMonthly = document.getElementById('welcomeLabelMonthly');
        const labelAnnual = document.getElementById('welcomeLabelAnnual');
        if (!toggle || !modal) return;

        const priceVals = modal.querySelectorAll('.p-price-val');
        const savingsHints = modal.querySelectorAll('.plan-savings-hint');

        toggle.addEventListener('click', () => {
            const isAnnual = toggle.classList.toggle('annual');
            modal.classList.toggle('annual-view', isAnnual);
            if (labelAnnual) labelAnnual.classList.toggle('active', isAnnual);
            if (labelMonthly) labelMonthly.classList.toggle('active', !isAnnual);

            priceVals.forEach(v => {
                const target = isAnnual ? v.dataset.annual : v.dataset.monthly;
                if (target && target !== 'Ventas') {
                    v.style.opacity = '0';
                    setTimeout(() => {
                        v.textContent = target;
                        v.style.opacity = '1';
                    }, 150);
                }
            });

            savingsHints.forEach(hint => {
                if (isAnnual) {
                    hint.style.opacity = '1';
                    hint.style.transform = 'translateY(0)';
                    hint.style.height = 'auto';
                    hint.style.marginTop = '4px';
                } else {
                    hint.style.opacity = '0';
                    hint.style.transform = 'translateY(5px)';
                    hint.style.height = '0';
                    hint.style.marginTop = '0';
                }
            });
        });

        priceVals.forEach(v => v.style.transition = 'opacity 0.2s');
    });

    function confirmInitialPlan(plan) {
        let msg = `Has seleccionado el plan ${plan.toUpperCase()}. ¿Deseas continuar?`;
        if (plan === 'avanzado') msg = "Has seleccionado el plan VIAJERO PRO. Se activarán tus 7 días de prueba gratuita. ¿Deseas continuar?";
        if (plan === 'colaborativo') msg = "Has seleccionado el plan NEGOCIOS. Se activarán tus 7 días de prueba gratuita. ¿Deseas continuar?";

        if (!confirm(msg)) return;

        window.selectedPlan = plan;
        showStep2();
    }

    function showStep2() {
        const step1 = document.getElementById('welcomeStepPlans');
        const step2 = document.getElementById('welcomeStepUseType');
        if (step1) step1.style.display = 'none';
        if (step2) step2.style.display = 'block';

        const modalContent = document.querySelector('#welcomePlanModal .modal-content');
        if (modalContent) {
            modalContent.style.maxWidth = '720px';
        }
    }

    function goBackToPlans() {
        const step1 = document.getElementById('welcomeStepPlans');
        const step2 = document.getElementById('welcomeStepUseType');
        if (step2) step2.style.display = 'none';
        if (step1) step1.style.display = 'block';

        const modalContent = document.querySelector('#welcomePlanModal .modal-content');
        if (modalContent) {
            modalContent.style.maxWidth = '820px';
        }
    }

    function selectUseCase(type) {
        window.selectedUseCase = type;

        const pCard = document.getElementById('card-use-personal');
        const aCard = document.getElementById('card-use-agency');
        if (pCard) pCard.classList.toggle('active', type === 'personal');
        if (aCard) aCard.classList.toggle('active', type === 'agency');

        const btn = document.getElementById('btnSubmitConfig');
        if (btn) {
            btn.disabled = false;
            btn.style.opacity = '1';
            btn.style.cursor = 'pointer';
        }
    }

    async function submitInitialConfig() {
        if (!window.selectedPlan || !window.selectedUseCase) return;

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
                    plan: window.selectedPlan,
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

    function checkInitialPlanGate(plan) {
        if (plan === 'básico') {
            confirmInitialPlan('básico');
            return;
        }

        if (typeof updateUserPlan === 'function') {
            const welcomeModal = document.getElementById('welcomePlanModal');
            if (welcomeModal) welcomeModal.style.display = 'none';

            window.planGateSuccessCallback = (verifiedPlan) => {
                window.selectedPlan = verifiedPlan || plan;
                if (welcomeModal) {
                    welcomeModal.style.display = 'flex';
                    showStep2();
                }
            };

            const originalClosePlanGate = window.closePlanGateModal;
            window.closePlanGateModal = function () {
                if (typeof originalClosePlanGate === 'function') originalClosePlanGate();
                if (welcomeModal) welcomeModal.style.display = 'flex';
                window.closePlanGateModal = originalClosePlanGate;
            };

            updateUserPlan(plan);
        } else {
            alert('El sistema de validación no está listo. Por favor intenta de nuevo.');
        }
    }
</script>