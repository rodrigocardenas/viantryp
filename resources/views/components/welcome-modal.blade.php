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
            'benefits' => ['1 itinerario', 'Link público', 'Banco de Imágenes gratuito'],
            'accent' => '#64748b'
        ],
        'esencial' => [
            'name' => 'Esencial',
            'price_monthly' => 5,
            'price_annual' => 4,
            'is_custom' => false,
            'limit_trips' => 3,
            'limit_editors' => 0,
            'benefits' => ['3 itinerarios', 'Google Places Incluido', 'Sin anuncios'],
            'accent' => '#1a7a8a'
        ],
        'avanzado' => [
            'name' => 'Avanzado',
            'price_monthly' => 12,
            'price_annual' => 9,
            'is_custom' => false,
            'limit_trips' => 10,
            'limit_editors' => 2,
            'benefits' => ['10 itinerarios', '2 colaboradores de edición', 'Branding PRO'],
            'accent' => '#1c7182',
            'popular' => true
        ],
        'colaborativo' => [
            'name' => 'Colaborativo',
            'price_monthly' => 29,
            'price_annual' => 22,
            'is_custom' => false,
            'limit_trips' => 1000000,
            'limit_editors' => 1000000,
            'benefits' => ['Itinerarios ilimitados', 'Colaboradores ilimitados', 'Roles/API'],
            'accent' => '#0e5a6a'
        ],
        'corporativo' => [
            'name' => 'Corporativo',
            'price_monthly' => 'Ventas',
            'price_annual' => 'Ventas',
            'is_custom' => true,
            'limit_trips' => 1000000,
            'limit_editors' => 1000000,
            'benefits' => ['Dominio Propio', 'SLA / Soporte', 'API Avanzada'],
            'accent' => '#0f2a3a'
        ]
    ];

    foreach ($planData as $key => &$data) {
        if (!$data['is_custom'] && $data['price_monthly'] > 0) {
            $data['savings'] = ($data['price_monthly'] - $data['price_annual']) * 12;
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
                <div class="modal-user-header" style="margin-bottom: 20px;">
                    <div class="user-info">
                        <div class="avatar user-avatar" style="background: var(--accent);">
                            @if($user->avatar)
                                <img src="{{ str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar) }}"
                                    alt="">
                            @else
                                {{ $user->display_initials }}
                            @endif
                        </div>
                        <div class="user-details" style="text-align: left;">
                            <div class="user-name">¡Hola, {{ $user->name }}!</div>
                            <div class="user-email">¡Tu registro en Viantryp ha sido exitoso!</div>
                        </div>
                    </div>
                </div>

                <!-- WELCOME TEXT -->
                <div style="text-align: center; margin-bottom: 25px;">
                    <h2
                        style="font-family: 'Barlow Condensed', sans-serif; font-size: 30px; font-weight: 900; text-transform: uppercase; margin: 0 0 5px; color: #0f172a; letter-spacing: -0.5px;">
                        Elige tu plan de inicio</h2>
                    <p style="font-size: 14px; color: #64748b; max-width: 800px; margin: 0 auto; line-height: 1.5;">
                        Selecciona el plan que mejor se adapte a tus necesidades. El plan Avanzado incluye 7 días de prueba
                        gratuita
                    </p>
                </div>

                <!-- PRICING TOGGLE -->
                <div class="pricing-toggle-wrap" style="margin-bottom: 30px;">
                    <span class="toggle-label active" id="welcomeLabelMonthly">Mensual</span>
                    <div class="toggle-switch" id="welcomePriceToggle"></div>
                    <span class="toggle-label" id="welcomeLabelAnnual">Anual <span
                            class="annual-discount-pill">-25%</span></span>
                </div>

                <!-- PLANS GRID -->
                <div class="p-grid-container">
                    @foreach($planData as $key => $data)
                        <div class="p-card {{ isset($data['popular']) ? 'active' : '' }}">
                            @if(isset($data['popular']))
                                <div class="p-popular">PROBAR GRATIS</div>
                            @endif

                            <div class="p-name">{{ $data['name'] }}</div>
                            <div class="p-price" style="{{ !is_numeric($data['price_monthly']) ? 'font-size: 16px;' : '' }}">
                                @if(is_numeric($data['price_monthly']))<span class="currency">$</span>@endif<span
                                    class="p-price-val"
                                    data-monthly="{{ is_numeric($data['price_monthly']) ? $data['price_monthly'] : $data['price_monthly'] }}"
                                    data-annual="{{ is_numeric($data['price_annual']) ? $data['price_annual'] : $data['price_annual'] }}">{{ is_numeric($data['price_monthly']) ? $data['price_monthly'] : $data['price_monthly'] }}</span>@if(!$data['is_custom'])<small>/mes</small>@endif
                            </div>

                            @if(isset($data['savings']) && $data['savings'] > 0)
                                <div class="plan-savings-hint">
                                    Ahorras ${{ $data['savings'] }} al año</div>
                            @endif

                            <div class="p-benefits">
                                @foreach($data['benefits'] as $b)
                                    <div class="p-benefit">
                                        <i class="fas fa-check"></i> {{ $b }}
                                    </div>
                                @endforeach
                                @if($key === 'avanzado')
                                    <div class="p-benefit" style="color: var(--accent); font-weight: 700; margin-top:5px;"><i
                                            class="fas fa-star"></i> 7 días de prueba</div>
                                @endif
                            </div>

                            @if($key === 'corporativo')
                                <button onclick="window.location.href='{{ route('contact') }}'" class="p-btn">Contactar</button>
                            @elseif($key === 'básico' || $key === 'avanzado')
                                <button onclick="confirmInitialPlan('{{ $key }}')"
                                    class="p-btn {{ $key === 'avanzado' ? 'current' : '' }}"
                                    style="{{ $key === 'avanzado' ? 'background: var(--accent); color: white;' : '' }}">
                                    {{ $key === 'avanzado' ? 'Probar 7 Días Gratis' : 'Elegir Plan' }}
                                </button>
                            @else
                                <button onclick="checkInitialPlanGate('{{ $key }}')" class="p-btn">Elegir
                                    Plan</button>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="pricing-disclaimer" style="text-align: center; margin-top: 20px; margin-bottom: 10px; font-size: 11px; color: #94a3b8; font-weight: 500;">
                    * Todos los precios están expresados en USD (Dólares Estadounidenses)
                </div>

                <div style="text-align: center;">
                    <p style="font-size: 11px; color: #94a3b8; font-weight: 500;">
                        ¿Tienes dudas? <a href="{{ route('contact') }}" target="_blank"
                            style="color: var(--accent); font-weight: 700;">Contáctanos</a> para ayudarte con tu elección.
                    </p>
                </div>
            </div>

            <!-- STEP 2: ACCOUNT USE SELECTOR -->
            <div id="welcomeStepUseType" style="display: none; padding: 10px 20px 20px;">
                <!-- HEADER -->
                <div style="text-align: center; margin-bottom: 30px;">
                    <h2 style="font-family: 'Barlow Condensed', sans-serif; font-size: 32px; font-weight: 900; text-transform: uppercase; margin: 0 0 10px; color: #0f172a; letter-spacing: -0.5px;">
                        ¿Cómo vas a usar Viantryp?
                    </h2>
                    <p style="font-size: 14.5px; color: #64748b; max-width: 500px; margin: 0 auto; line-height: 1.5; font-weight: 500;">
                        Personalizaremos tu perfil y tus herramientas según tu respuesta. Podrás cambiar esto en cualquier momento desde tu panel.
                    </p>
                </div>

                <!-- USE CASE GRID -->
                <div class="use-case-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; max-width: 680px; margin: 0 auto 35px;">
                    <!-- Option 1: Personal -->
                    <div class="use-case-card" onclick="selectUseCase('personal')" id="card-use-personal" style="background: white; border: 2px solid #e2e8f0; border-radius: 20px; padding: 32px 24px; cursor: pointer; transition: all 0.25s ease; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                        <div class="use-case-icon-wrapper" style="width: 40px; height: 40px; border-radius: 50%; background: #f0fdfa; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; transition: all 0.25s ease;">
                            <i class="fas fa-plane-departure" style="font-size: 20px; color: var(--accent);"></i>
                        </div>
                        <h3 style="font-size: 18px; font-weight: 900; color: #0f172a; margin: 0 0 10px; font-family: 'Barlow Condensed', sans-serif; text-transform: uppercase; letter-spacing: 0.5px;">Viajes Personales</h3>
                        <p style="font-size: 12.5px; color: #64748b; margin: 0; line-height: 1.5; font-weight: 500;">
                            Para planificar mis propias vacaciones, escapadas de fin de semana y aventuras con amigos o familia de forma visual e inteligente.
                        </p>
                    </div>

                    <!-- Option 2: Agency -->
                    <div class="use-case-card" onclick="selectUseCase('agency')" id="card-use-agency" style="background: white; border: 2px solid #e2e8f0; border-radius: 20px; padding: 32px 24px; cursor: pointer; transition: all 0.25s ease; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                        <div class="use-case-icon-wrapper" style="width: 40px; height: 40px; border-radius: 50%; background: #f0fdfa; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; transition: all 0.25s ease;">
                            <i class="fas fa-briefcase" style="font-size: 20px; color: var(--accent);"></i>
                        </div>
                        <h3 style="font-size: 18px; font-weight: 900; color: #0f172a; margin: 0 0 10px; font-family: 'Barlow Condensed', sans-serif; text-transform: uppercase; letter-spacing: 0.5px;">Negocio o Agencia</h3>
                        <p style="font-size: 12.5px; color: #64748b; margin: 0; line-height: 1.5; font-weight: 500;">
                            Para crear propuestas de viaje de alta conversión, diseñar itinerarios de marca y colaborar profesionalmente con mis viajeros o agentes.
                        </p>
                    </div>
                </div>

                <!-- ACTION BUTTONS -->
                <div style="display: flex; gap: 12px; justify-content: center; max-width: 420px; margin: 0 auto;">
                    <button onclick="goBackToPlans()" class="p-btn" style="flex: 1; border-color: #cbd5e1; color: #475569; margin: 0;">
                        Atrás
                    </button>
                    <button onclick="submitInitialConfig()" id="btnSubmitConfig" class="p-btn current" style="flex: 2; background: var(--accent); color: white; border-color: var(--accent); opacity: 0.65; cursor: not-allowed; margin: 0;" disabled>
                        Comenzar ahora
                    </button>
                </div>
            </div>

        </div>

        <!-- LOADING OVERLAY -->
        <div id="welcomeLoadingOverlay"
            style="display:none; position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.8); z-index:10003; align-items:center; justify-content:center; border-radius: 24px;">
            <div style="padding: 20px; text-align:center;">
                <i class="fas fa-circle-notch fa-spin"
                    style="font-size:32px; color:var(--accent); margin-bottom:10px; display:block;"></i>
                <span style="font-size:14px; font-weight:700; color:#0f172a;">Configurando tu cuenta...</span>
            </div>
        </div>
    </div>
</div>

<style>
    .p-grid-container {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }

    .p-card {
        background: #ffffff;
        border: 1px solid #eef2f6;
        border-radius: 20px;
        padding: 20px 16px;
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: 0.3s ease;
        position: relative;
    }

    .p-card.active {
        border: 2px solid var(--accent, #1a7a8a);
        background: #f0f9f8;
    }

    .p-popular {
        position: absolute;
        top: -10px;
        left: 50%;
        transform: translateX(-50%);
        background: #0f2a3a;
        color: white;
        font-size: 8px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 100px;
        z-index: 2;
    }

    .p-name {
        font-size: 13px;
        font-weight: 800;
        color: #64748b;
        margin-bottom: 8px;
        text-transform: uppercase;
    }

    .p-price {
        font-size: 20px;
        font-weight: 900;
        color: #0f172a;
        margin-bottom: 12px;
    }

    .p-price small {
        font-size: 10px;
        color: #94a3b8;
    }

    .p-benefits {
        flex-grow: 1;
        margin: 15px 0;
    }

    .p-benefit {
        font-size: 10px;
        color: #475569;
        font-weight: 600;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .p-btn {
        width: 100%;
        padding: 8px;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        background: white;
        color: #0f172a;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        margin-top: auto;
        transition: 0.2s;
    }

    .p-btn.current {
        background: var(--accent, #1a7a8a);
        color: white;
        border-color: var(--accent, #1a7a8a);
    }

    .plan-savings-hint {
        font-size: 10px;
        color: #10b981;
        font-weight: 700;
        margin-top: 4px;
        opacity: 0;
        height: 0;
        overflow: hidden;
        transition: 0.3s;
    }

    .use-case-card:hover {
        transform: translateY(-4px);
        border-color: var(--accent) !important;
        box-shadow: 0 12px 24px rgba(26, 122, 138, 0.08);
    }
    .use-case-card.active {
        border-color: var(--accent) !important;
        background: #f0f9f8 !important;
    }
    .use-case-card.active .use-case-icon-wrapper {
        background: var(--accent) !important;
    }
    .use-case-card.active .use-case-icon-wrapper i {
        color: white !important;
    }

    @media (max-width: 992px) {
        .p-grid-container {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .p-grid-container {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            gap: 16px;
            padding: 10px 10px 30px;
            margin: 0 -15px;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }

        .p-grid-container::-webkit-scrollbar {
            display: none;
        }

        .p-card {
            flex-shrink: 0;
            width: 270px;
            scroll-snap-align: center;
        }
    }

    @media (max-width: 480px) {
        .p-card {
            width: 82%;
        }
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
        const priceVals = modal.querySelectorAll('.p-price-val');
        const savingsHints = modal.querySelectorAll('.plan-savings-hint');

        if (toggle) {
            toggle.addEventListener('click', () => {
                const isAnnual = toggle.classList.toggle('annual');
                labelAnnual.classList.toggle('active', isAnnual);
                labelMonthly.classList.toggle('active', !isAnnual);

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
                        hint.style.height = 'auto';
                        hint.style.marginTop = '4px';
                    } else {
                        hint.style.opacity = '0';
                        hint.style.height = '0';
                        hint.style.marginTop = '0';
                    }
                });
            });

            priceVals.forEach(v => v.style.transition = 'opacity 0.2s');
        }
    });

    function confirmInitialPlan(plan) {
        let msg = `Has seleccionado el plan ${plan.toUpperCase()}. ¿Deseas continuar?`;
        if (plan === 'avanzado') msg = "Has seleccionado el plan AVANZADO. Se activarán tus 7 días de prueba gratuita. ¿Deseas continuar?";

        if (!confirm(msg)) return;

        window.selectedPlan = plan;
        showStep2();
    }

    function showStep2() {
        document.getElementById('welcomeStepPlans').style.display = 'none';
        document.getElementById('welcomeStepUseType').style.display = 'block';
        
        const modalContent = document.querySelector('.modal-content');
        if (modalContent) {
            modalContent.style.maxWidth = '750px';
        }
    }

    function goBackToPlans() {
        document.getElementById('welcomeStepUseType').style.display = 'none';
        document.getElementById('welcomeStepPlans').style.display = 'block';
        
        const modalContent = document.querySelector('.modal-content');
        if (modalContent) {
            modalContent.style.maxWidth = '1100px';
        }
    }

    function selectUseCase(type) {
        window.selectedUseCase = type;
        
        document.getElementById('card-use-personal').classList.toggle('active', type === 'personal');
        document.getElementById('card-use-agency').classList.toggle('active', type === 'agency');
        
        const btn = document.getElementById('btnSubmitConfig');
        btn.disabled = false;
        btn.style.opacity = '1';
        btn.style.cursor = 'pointer';
    }

    async function submitInitialConfig() {
        if (!window.selectedPlan || !window.selectedUseCase) return;
        
        const overlay = document.getElementById('welcomeLoadingOverlay');
        overlay.style.display = 'flex';

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
                overlay.style.display = 'none';
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Ocurrió un error inesperado al guardar tu configuración.');
            overlay.style.display = 'none';
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