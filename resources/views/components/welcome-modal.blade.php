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
    <div class="modal-content modal-wide">
        <div class="modal-body">
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
            <div class="p-grid-container"
                style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 12px; margin-bottom: 20px;">
                @foreach($planData as $key => $data)
                    <div class="p-card {{ isset($data['popular']) ? 'active' : '' }}"
                        style="display: flex; flex-direction: column; height: 100%; {{ isset($data['popular']) ? 'border: 2px solid var(--accent); background: #f0f9f8;' : '' }}">
                        @if(isset($data['popular']))
                            <div class="p-popular"
                                style="background:#0f2a3a; color:white; font-size:9px; top:-10px; font-weight:800; padding:4px 10px;">
                                PROBAR GRATIS</div>
                        @endif

                        <div class="p-name">{{ $data['name'] }}</div>
                        <div class="p-price" style="{{ !is_numeric($data['price_monthly']) ? 'font-size: 14px;' : '' }}">
                            @if(is_numeric($data['price_monthly']))<span class="currency">$</span>@endif<span
                                class="p-price-val"
                                data-monthly="{{ is_numeric($data['price_monthly']) ? $data['price_monthly'] : $data['price_monthly'] }}"
                                data-annual="{{ is_numeric($data['price_annual']) ? $data['price_annual'] : $data['price_annual'] }}">{{ is_numeric($data['price_monthly']) ? $data['price_monthly'] : $data['price_monthly'] }}</span>@if(!$data['is_custom'])<small>/mes</small>@endif
                        </div>

                        @if(isset($data['savings']) && $data['savings'] > 0)
                            <div class="plan-savings-hint"
                                style="opacity: 0; height: 0; overflow: hidden; transition: 0.3s; font-size: 10px; color: #10b981; font-weight: 700;">
                                Ahorras ${{ $data['savings'] }} al año</div>
                        @endif

                        <div class="p-benefits" style="flex: 1; margin: 15px 0;">
                            @foreach($data['benefits'] as $b)
                                <div class="p-benefit"
                                    style="font-size: 10px; margin-bottom: 4px; display: flex; align-items: center; gap: 4px; color: #475569; font-weight: 600;">
                                    <i class="fas fa-check"></i> {{ $b }}
                                </div>
                            @endforeach
                            @if($key === 'avanzado')
                                <div class="p-benefit" style="color: var(--accent); font-weight: 700; margin-top:5px;"><i
                                        class="fas fa-star"></i> 7 días de prueba</div>
                            @endif
                        </div>

                        @if($key === 'corporativo')
                            <button onclick="window.location.href='{{ route('contact') }}'" class="p-btn"
                                style="margin-top: auto;">Contactar</button>
                        @elseif($key === 'básico' || $key === 'avanzado')
                            <button onclick="confirmInitialPlan('{{ $key }}')"
                                class="p-btn {{ $key === 'avanzado' ? 'current' : '' }}"
                                style="margin-top: auto; {{ $key === 'avanzado' ? 'background: var(--accent); color: white;' : 'border-color: #cbd5e1;' }}">
                                {{ $key === 'avanzado' ? 'Probar 7 Días Gratis' : 'Elegir Plan' }}
                            </button>
                        @else
                            <button onclick="checkInitialPlanGate('{{ $key }}')" class="p-btn" style="margin-top: auto;">Elegir
                                Plan</button>
                        @endif
                    </div>
                @endforeach
            </div>

            <div style="text-align: center;">
                <p style="font-size: 11px; color: #94a3b8; font-weight: 500;">
                    ¿Tienes dudas? <a href="{{ route('contact') }}" target="_blank"
                        style="color: var(--accent); font-weight: 700;">Contáctanos</a> para ayudarte con tu elección.
                </p>
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

<script>
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

    async function confirmInitialPlan(plan) {
        let msg = `Has seleccionado el plan ${plan.toUpperCase()}. ¿Deseas confirmar esta elección?`;
        if (plan === 'avanzado') msg = "Has seleccionado el plan AVANZADO. Se activarán tus 7 días de prueba gratuita. ¿Deseas continuar?";

        if (!confirm(msg)) return;

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
                body: JSON.stringify({ plan: plan })
            });

            const result = await response.json();
            if (result.success) {
                location.reload();
            } else {
                alert(result.message || 'Error al seleccionar el plan');
                overlay.style.display = 'none';
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Ocurrió un error inesperado.');
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
                finalizeInitialPlan(verifiedPlan || plan);
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

    async function finalizeInitialPlan(plan) {
        const overlay = document.getElementById('welcomeLoadingOverlay');
        if (overlay) overlay.style.display = 'flex';

        try {
            await fetch('{{ route("profile.choose-initial-plan") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ plan: plan })
            });
            location.reload();
        } catch (e) {
            console.error(e);
            location.reload();
        }
    }
</script>