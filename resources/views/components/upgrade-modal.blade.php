@php
    $user = auth()->user();
    if (!$user)
        return;

    $currentPlan = strtolower($user->plan ?? 'básico');
    $tripCount = \App\Models\Trip::where('user_id', $user->id)->count();



    $editorCount = \DB::table('trip_collaborators')
        ->join('trips', 'trip_collaborators.trip_id', '=', 'trips.id')
        ->where('trips.user_id', $user->id)
        ->where('trip_collaborators.role', 'editor')
        ->distinct('trip_collaborators.email')
        ->count();

    $limits = $user->getPlanLimits();

    // Theme color mapping for the targeted card
    $themeColorKey = $user->theme_color ?? 'default';
    $themes = [
        'default' => '#1c7182',
        'ocean' => '#1a5f8f',
        'gold' => '#b08000',
        'sunset' => '#c0552a',
        'blush' => 'linear-gradient(135deg,#e07b9a,#f4a5bd)',
        'silver' => 'linear-gradient(135deg,#6e7f80,#9aa8a9)',
        'mint' => 'linear-gradient(135deg,#3db898,#62d4b5)',
        'lavender' => 'linear-gradient(135deg,#9b72cf,#b39ddb)'
    ];
    $userTheme = $themes[$themeColorKey] ?? $themes['default'];

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
            'benefits' => ['3 itinerarios', 'Google Places Incluido'],
            'accent' => '#1a7a8a'
        ],
        'avanzado' => [
            'name' => 'Avanzado',
            'price_monthly' => 12,
            'price_annual' => 9,
            'is_custom' => false,
            'limit_trips' => 10,
            'limit_editors' => 2,
            'benefits' => ['10 itinerarios', '2 colaboradores de edición', 'Branding'],
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
            'price_monthly' => 'Hablar con Ventas',
            'price_annual' => 'Hablar con Ventas',
            'is_custom' => true,
            'limit_trips' => 1000000,
            'limit_editors' => 1000000,
            'benefits' => ['Dominio Propio', 'SLA / Soporte', 'API Avanzada'],
            'accent' => '#0f2a3a'
        ]
    ];

    foreach($planData as $key => &$data) {
        if (!$data['is_custom'] && $data['price_monthly'] > 0) {
            $data['savings'] = ($data['price_monthly'] - $data['price_annual']) * 12;
        }
    }
    unset($data);

    $tripProgress = min(100, ($tripCount / max(1, $limits['max_trips'])) * 100);

    $editorProgress = min(100, ($editorCount / max(1, $limits['max_editors'] ?? 1)) * 100);

    // Warning logic
    $warningMap = [
        'básico' => 'Has alcanzado el límite. Sube tu plan a Esencial para mejorar tus herramientas.',
        'esencial' => 'Has alcanzado el límite. Sube tu plan a Avanzado para mejorar tus herramientas.',
        'avanzado' => 'Has alcanzado el límite. Sube tu plan a Colaborativo para mejorar tus herramientas.',
        'colaborativo' => 'Has alcanzado el límite. Para gran escala, contacta con nuestro equipo corporativo.'
    ];
    $warningText = $warningMap[$currentPlan] ?? 'Gestiona tu suscripción y amplía tus límites.';

    // Prepare clean limits for JS
    $jsPlanLimits = [];
    foreach ($planData as $key => $data) {
        $jsPlanLimits[$key] = [
            'trips' => $data['limit_trips'],

            'editors' => $data['limit_editors'],
            'name' => $data['name']
        ];
    }

    // Progression for targeted next-step card
    $progression = ['básico' => 'esencial', 'esencial' => 'avanzado', 'avanzado' => 'colaborativo', 'colaborativo' => 'corporativo', 'corporativo' => null];
    $nextKey = $progression[$currentPlan] ?? null;
    $nextData = $nextKey ? $planData[$nextKey] : null;
@endphp

<div id="upgradePlanModal" class="modal upgrade-premium-modal" style="display: none;">
    <div class="modal-content {{ request()->routeIs('profile.index') ? 'modal-wide' : 'modal-standard' }}">
        <button class="close-btn" onclick="closeUpgradeModal()">&times;</button>

        <div class="modal-body">
            <!-- HEADER: USER INFO -->
            <div class="modal-user-header">
                <div class="user-info">
                    <div class="avatar user-avatar">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="">
                        @else
                            {{ $user->display_initials }}
                        @endif
                    </div>
                    <div class="user-details">
                        <div class="user-name">{{ $user->display_name }}</div>
                        <div class="user-email">{{ $user->email }}</div>
                    </div>
                </div>
                <div class="plan-badge-pill">
                    Plan {{ ucfirst($currentPlan) }}
                </div>
            </div>

            <!-- WARNING ALERT -->
            @if(!request()->routeIs('profile.index'))
                <div class="upgrade-alert">
                    <div class="alert-dot"></div>
                    <p>{{ $warningText }}</p>
                </div>
            @endif

            <!-- USAGE SECTION -->
            <div class="usage-section">
                <div class="section-title">TU ESTADO DE USO</div>
                <div class="usage-grid {{ request()->routeIs('profile.index') ? '' : 'usage-grid-compact' }}">
                    <div class="usage-card">
                        <div class="usage-label">Itinerarios</div>
                        <div class="usage-stats">
                            <span class="current" id="modal-trip-count">{{ $tripCount }}</span><span
                                class="limit" id="modal-trip-limit">/{{ $limits['max_trips'] >= 1000000 ? '∞' : $limits['max_trips'] }}</span>
                        </div>
                        <div class="progress-container">
                            <div class="progress-bar {{ $tripProgress >= 100 ? 'limit-reached' : '' }}" id="modal-trip-bar"
                                style="width: {{ $tripProgress }}%"></div>
                        </div>
                    </div>

                    <div class="usage-card">
                        <div class="usage-label">Editores</div>
                        <div class="usage-stats">
                            <span class="current" id="modal-editor-count">{{ $editorCount }}</span><span
                                class="limit" id="modal-editor-limit">/{{ ($limits['max_editors'] ?? 0) >= 1000000 ? '∞' : ($limits['max_editors'] ?? 0) }}</span>
                        </div>
                        <div class="progress-container">
                            <div class="progress-bar {{ $editorProgress >= 100 ? 'limit-reached' : '' }}" id="modal-editor-bar" style="width: {{ $editorProgress }}%; background: #6366f1;"></div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- PRICING TOGGLE -->
            <div class="pricing-toggle-wrap">
                <span class="toggle-label active" id="modalLabelMonthly">Mensual</span>
                <div class="toggle-switch" id="modalPriceToggle"></div>
                <span class="toggle-label" id="modalLabelAnnual">Anual <span class="annual-discount-pill">-25%</span></span>
            </div>

            @if(request()->routeIs('profile.index'))
                <!-- PLANS GRID (Management) -->
                <div class="plans-management-grid">
                    <div class="section-title">CAMBIAR O MEJORAR PLAN</div>
                    <div class="p-grid-container">
                        @foreach($planData as $key => $data)
                            @php 
                                $isBlocked = ($tripCount > $data['limit_trips']) || 
                                             ($editorCount > $data['limit_editors']); 
                            @endphp
                            <div class="p-card {{ $currentPlan === $key ? 'active' : '' }} {{ $isBlocked ? 'blocked' : '' }}">
                                @if(isset($data['popular']))
                                <div class="p-popular">MÁS POPULAR</div> @endif
                                @if($isBlocked)
                                <div class="p-blocked-badge">LÍMITE EXCEDIDO</div> @endif
                                <div class="p-name">{{ $data['name'] }}</div>
                                <div class="p-price" style="{{ !is_numeric($data['price_monthly']) ? 'font-size: 16px;' : '' }}">
                                    @if(is_numeric($data['price_monthly']))<span class="currency">$</span>@endif<span class="p-price-val" data-monthly="{{ is_numeric($data['price_monthly']) ? number_format($data['price_monthly'], 2, '.', '') : $data['price_monthly'] }}" data-annual="{{ is_numeric($data['price_annual']) ? number_format($data['price_annual'], 2, '.', '') : $data['price_annual'] }}">{{ is_numeric($data['price_monthly']) ? number_format($data['price_monthly'], 2, '.', '') : $data['price_monthly'] }}</span>@if(!$data['is_custom'])<small>/mes</small>@endif
                                </div>
                                @if(isset($data['savings']) && $data['savings'] > 0)
                                    <div class="plan-savings-hint">Ahorras ${{ $data['savings'] }} al año</div>
                                @endif
                                <div class="p-benefits">
                                    @foreach($data['benefits'] as $b)
                                        <div class="p-benefit"><i class="fas fa-check"></i> {{ $b }}</div>
                                    @endforeach
                                </div>
                                <button
                                    onclick="{{ $data['name'] === 'Corp.' ? 'window.location.href=\'' . route('contact') . '\'' : 'updateUserPlan(\'' . $key . '\')' }}"
                                    class="p-btn {{ $currentPlan === $key ? 'current' : '' }} {{ $isBlocked ? 'blocked' : '' }}"
                                    {{ $currentPlan === $key ? 'disabled' : '' }}>
                                    {{ $currentPlan === $key ? 'Actual' : ($isBlocked ? 'Bloqueado' : ($data['name'] === 'Corp.' ? 'Contactar' : 'Elegir')) }}
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <!-- NEXT STEP CARD (Targeted) -->
                @if($nextData)
                    <div class="next-step-card" style="background: {{ $userTheme }};">
                        <div class="ns-header">
                            <div class="ns-titles">
                                <div class="ns-label">SIGUIENTE PASO</div>
                                <div class="ns-plan-name">{{ $nextData['name'] }}</div>
                            </div>
                            @if(isset($nextData['popular']))
                            <div class="popular-badge">MÁS POPULAR</div> @endif
                        </div>
                        <div class="ns-price" style="{{ !is_numeric($nextData['price_monthly']) ? 'font-size: 18px;' : '' }}">
                            @if(is_numeric($nextData['price_monthly']))<span class="currency">$</span>@endif<span class="amount" data-monthly="{{ is_numeric($nextData['price_monthly']) ? number_format($nextData['price_monthly'], 2, '.', '') : $nextData['price_monthly'] }}" data-annual="{{ is_numeric($nextData['price_annual']) ? number_format($nextData['price_annual'], 2, '.', '') : $nextData['price_annual'] }}">{{ is_numeric($nextData['price_monthly']) ? number_format($nextData['price_monthly'], 2, '.', '') : $nextData['price_monthly'] }}</span>
                            @if(!$nextData['is_custom']) <span class="period">/mes</span> @endif
                            @if(isset($nextData['savings']) && $nextData['savings'] > 0)
                                <div class="plan-savings-hint" style="color: white; opacity: 0.9;">Ahorras ${{ $nextData['savings'] }} al año</div>
                            @endif
                        </div>
                        <div class="benefits-list">
                            @foreach($nextData['benefits'] as $benefit)
                                <div class="benefit-item">
                                    <div class="benefit-check"><i class="fas fa-check"></i></div>
                                    <span>{{ $benefit }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="ns-cta-container">
                            <button
                                onclick="{{ $nextData['name'] === 'Corp.' ? 'window.location.href=\'' . route('contact') . '\'' : 'updateUserPlan(\'' . $nextKey . '\')' }}"
                                class="btn-upgrade-main">
                                {{ $nextData['name'] === 'Corp.' ? 'Hablar con ventas — Contactar' : 'Mejorar a ' . $nextData['name'] . ' →' }}
                            </button>
                        </div>
                    </div>
                @else
                    <div
                        style="padding: 30px; background: #f8fafc; border-radius: 20px; text-align: center; border: 1px dashed #e2e8f0;">
                        <div style="font-weight: 800; color: #0f172a; margin-bottom: 5px;">¡Plan Máximo Activado!</div>
                        <div style="font-size: 13px; color: #64748b;">Disfrutas de todas las funciones corporativas.</div>
                    </div>
                @endif
            @endif

            <div class="modal-footer-links">
                Tiempos de facturación en <a href="{{ route('home') }}#precios" target="_blank">Nuestros Planes
                    &rarr;</a>
            </div>
        </div>

        <!-- LOADING OVERLAY -->
        <div id="modalLoadingOverlay"
            style="display:none; position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.8); z-index:100; align-items:center; justify-content:center; border-radius: 24px;">
            <div style="padding: 20px; text-align:center;">
                <i class="fas fa-circle-notch fa-spin"
                    style="font-size:32px; color:var(--teal); margin-bottom:10px; display:block;"></i>
                <span style="font-size:14px; font-weight:700; color:#0f172a;">Actualizando...</span>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- PLAN GATE MODAL -->
<!-- ============================================================ -->
<div id="planGateModal" style="display:none; position:fixed; inset:0; width:100%; height:100%; background:rgba(15,23,42,0.7); backdrop-filter:blur(8px); -webkit-backdrop-filter:blur(8px); z-index:10001; align-items:center; justify-content:center; font-family:'Barlow',sans-serif;">
    <div style="background:#fff; border-radius:24px; width:100%; max-width:460px; margin:auto; box-shadow:0 40px 100px -20px rgba(0,0,0,0.4); animation:modalPop 0.35s cubic-bezier(0.175,0.885,0.32,1.1); position:relative; overflow:hidden;">

        <!-- Close btn -->
        <button onclick="closePlanGateModal()" style="position:absolute;top:12px;right:12px;background:#f1f5f9;border:none;width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:18px;color:#64748b;cursor:pointer;z-index:10;">×</button>

        <!-- STEP 1: Code Entry -->
        <div id="planGateStep1" style="padding:32px;">
            <div style="width:52px;height:52px;background:#f0f9f8;border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:20px;">
                <i class="fas fa-key" style="font-size:22px;color:#1a7a8a;"></i>
            </div>
            <div style="font-size:11px;font-weight:800;letter-spacing:2px;color:#94a3b8;text-transform:uppercase;margin-bottom:6px;">Cambio de Plan</div>
            <h2 style="margin:0 0 8px;font-size:22px;font-weight:900;color:#0f172a;">Ingresa tu código de acceso</h2>
            <p style="margin:0 0 24px;font-size:14px;color:#64748b;line-height:1.6;">Para cambiar al plan <strong id="gateTargetPlanName" style="color:#1a7a8a;"></strong>, ingresa el código que te proporcionó nuestro equipo.</p>

            <div style="margin-bottom:12px;">
                <input
                    type="text"
                    id="planAccessCodeInput"
                    placeholder="Ej: VIA-2024-XYZ"
                    autocomplete="off"
                    style="width:100%;box-sizing:border-box;background:#f8fafc;border:2px solid #e2e8f0;border-radius:12px;padding:14px 16px;font-size:16px;font-weight:700;letter-spacing:3px;text-transform:uppercase;font-family:'Barlow',sans-serif;color:#0f172a;outline:none;transition:border-color 0.2s;"
                    oninput="this.value=this.value.toUpperCase()"
                />
                <div id="planCodeError" style="display:none;margin-top:8px;font-size:13px;color:#ef4444;font-weight:600;"></div>
            </div>

            <button onclick="submitPlanCode()" id="planCodeSubmitBtn" style="width:100%;background:#1a7a8a;color:white;border:none;border-radius:12px;padding:15px;font-size:15px;font-weight:700;cursor:pointer;transition:0.2s;margin-bottom:16px;">
                <span id="planCodeSubmitLabel">Verificar código →</span>
            </button>

            <div style="text-align:center;">
                <button onclick="switchToPlanRequest()" style="background:none;border:none;color:#1a7a8a;font-size:13px;font-weight:700;cursor:pointer;text-decoration:underline;font-family:'Barlow',sans-serif;">¿No tienes código? Solicitar acceso →</button>
            </div>
        </div>

        <!-- STEP 2: Request Form -->
        <div id="planGateStep2" style="display:none;padding:32px;">
            <div style="width:52px;height:52px;background:#fffbeb;border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:20px;">
                <i class="fas fa-envelope" style="font-size:22px;color:#f59e0b;"></i>
            </div>
            <div style="font-size:11px;font-weight:800;letter-spacing:2px;color:#94a3b8;text-transform:uppercase;margin-bottom:6px;">Solicitud de Plan</div>
            <h2 style="margin:0 0 8px;font-size:22px;font-weight:900;color:#0f172a;">Solicitar plan <span id="gateTargetPlanName2" style="color:#1a7a8a;"></span></h2>
            <p style="margin:0 0 24px;font-size:14px;color:#64748b;line-height:1.6;">Déjanos tus datos y nuestro equipo se pondrá en contacto contigo para activar tu plan.</p>

            <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:20px;">
                <div>
                    <label style="display:block;font-size:11px;font-weight:700;letter-spacing:1px;color:#94a3b8;text-transform:uppercase;margin-bottom:6px;">Nombre completo *</label>
                    <input type="text" id="reqName" placeholder="Tu nombre" style="width:100%;box-sizing:border-box;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:12px 14px;font-size:14px;font-family:'Barlow',sans-serif;color:#0f172a;outline:none;transition:border-color 0.2s;">
                </div>
                <div>
                    <label style="display:block;font-size:11px;font-weight:700;letter-spacing:1px;color:#94a3b8;text-transform:uppercase;margin-bottom:6px;">Teléfono / WhatsApp</label>
                    <input type="tel" id="reqPhone" placeholder="+57 300 000 0000" style="width:100%;box-sizing:border-box;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:12px 14px;font-size:14px;font-family:'Barlow',sans-serif;color:#0f172a;outline:none;transition:border-color 0.2s;">
                </div>
                <div>
                    <label style="display:block;font-size:11px;font-weight:700;letter-spacing:1px;color:#94a3b8;text-transform:uppercase;margin-bottom:6px;">Correo de contacto *</label>
                    <input type="email" id="reqEmail" placeholder="tu@correo.com" style="width:100%;box-sizing:border-box;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:12px 14px;font-size:14px;font-family:'Barlow',sans-serif;color:#0f172a;outline:none;transition:border-color 0.2s;">
                </div>
            </div>

            <div id="planReqError" style="display:none;margin-bottom:12px;font-size:13px;color:#ef4444;font-weight:600;"></div>

            <button onclick="submitPlanRequest()" id="planReqSubmitBtn" style="width:100%;background:#f59e0b;color:white;border:none;border-radius:12px;padding:15px;font-size:15px;font-weight:700;cursor:pointer;transition:0.2s;margin-bottom:16px;">
                <span id="planReqSubmitLabel">Enviar solicitud →</span>
            </button>

            <div style="text-align:center;">
                <button onclick="switchToCodeStep()" style="background:none;border:none;color:#64748b;font-size:13px;font-weight:600;cursor:pointer;font-family:'Barlow',sans-serif;">← Tengo un código</button>
            </div>
        </div>

        <!-- STEP 3: Success -->
        <div id="planGateStep3" style="display:none;padding:40px 32px;text-align:center;">
            <div style="width:64px;height:64px;background:#ecfdf5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                <i class="fas fa-check" style="font-size:26px;color:#10b981;"></i>
            </div>
            <h2 style="margin:0 0 10px;font-size:22px;font-weight:900;color:#0f172a;">¡Solicitud enviada!</h2>
            <p style="margin:0 0 28px;font-size:14px;color:#64748b;line-height:1.6;">Nuestro equipo ha recibido tu solicitud y se pondrá en contacto contigo a la brevedad para activar tu plan.</p>
            <button onclick="closePlanGateModal()" style="background:#1a7a8a;color:white;border:none;border-radius:12px;padding:14px 32px;font-size:14px;font-weight:700;cursor:pointer;">Entendido</button>
        </div>

    </div>
</div>

<style>
    .upgrade-premium-modal {
        position: fixed;
        inset: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 10000;
        font-family: 'Barlow', sans-serif;
        border: none;
        outline: none;
    }

    .upgrade-premium-modal .modal-content {
        background: #ffffff;
        border-radius: 28px;
        position: relative;
        box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.4);
        animation: modalPop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
        overflow: hidden;
        margin: auto;
    }

    .modal-standard {
        width: 100%;
        max-width: 520px;
    }

    .modal-wide {
        width: 100%;
        max-width: 920px;
    }

    @keyframes modalPop {
        from {
            transform: scale(0.97) translateY(20px);
            opacity: 0;
        }

        to {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
    }

    .upgrade-premium-modal .close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #f1f5f9;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #64748b;
        cursor: pointer;
        transition: 0.2s;
        z-index: 10;
    }

    .upgrade-premium-modal .modal-body {
        padding: 28px;
    }

    .modal-user-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-top: 5px;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {
        width: 44px;
        height: 44px;
        background: #1a9a8a;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 800;
        font-size: 15px;
        overflow: hidden;
    }

    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-name {
        font-weight: 700;
        color: #0f172a;
        font-size: 16px;
        margin-bottom: 2px;
    }

    .user-email {
        font-size: 13px;
        color: #64748b;
    }

    .plan-badge-pill {
        background: #f1f5f9;
        color: #475569;
        padding: 6px 14px;
        border-radius: 100px;
        font-size: 12px;
        font-weight: 700;
        border: 1px solid #e2e8f0;
    }

    .upgrade-alert {
        background: #fffbeb;
        border: 1px solid #fef3c7;
        border-radius: 12px;
        padding: 14px 18px;
        display: flex;
        gap: 10px;
        margin-bottom: 24px;
    }

    .alert-dot {
        width: 8px;
        height: 8px;
        background: #f59e0b;
        border-radius: 50%;
        margin-top: 5px;
        flex-shrink: 0;
    }

    .upgrade-alert p {
        margin: 0;
        font-size: 14px;
        color: #92400e;
        font-weight: 500;
    }

    .section-title {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.08em;
        color: #94a3b8;
        margin-bottom: 14px;
        text-transform: uppercase;
    }

    .usage-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .usage-grid-compact {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
    .usage-grid-compact .usage-card {
        padding: 12px;
    }
    .usage-grid-compact .usage-stats .current {
        font-size: 16px;
    }

    .usage-card {
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        padding: 16px;
    }

    .usage-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .usage-stats .current {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
    }

    .usage-stats .limit {
        font-size: 14px;
        color: #94a3b8;
    }

    .progress-container {
        height: 5px;
        background: #e2e8f0;
        border-radius: 10px;
        margin-top: 8px;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        background: #1a9a8a;
    }

    .progress-bar.limit-reached {
        background: #ef4444;
    }

    .progress-bar.ghost {
        background: #cbd5e1;
        width: 40%;
        opacity: 0.4;
    }

    /* TARGETED CARD STYLES */
    .next-step-card {
        border-radius: 20px;
        padding: 24px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .ns-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 8px;
    }

    .ns-titles .ns-label {
        font-size: 8px;
        font-weight: 800;
        opacity: 0.8;
        letter-spacing: 0.1em;
    }

    .ns-plan-name {
        font-family: 'Inter', sans-serif;
        font-size: 20px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .popular-badge {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(5px);
        color: white;
        font-size: 9px;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 100px;
    }

    .ns-price {
        margin-bottom: 16px;
        font-family: 'Barlow', sans-serif;
    }

    .ns-price .amount {
        font-size: 26px;
        font-weight: 800;
    }

    .benefits-list {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px 15px;
        margin-bottom: 20px;
    }

    .benefit-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 600;
    }

    .benefit-check {
        width: 16px;
        height: 16px;
        background: rgba(255, 255, 255, 0.25);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 8px;
    }

    .btn-upgrade-main {
        width: 100%;
        display: block;
        background: #ffffff;
        color: #000000;
        text-align: center;
        padding: 16px;
        border: none;
        border-radius: 100px;
        font-weight: 700;
        font-size: 14px;
        transition: 0.3s;
        cursor: pointer;
    }

    .btn-upgrade-main:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    /* PRICING TOGGLE STYLES */
    .pricing-toggle-wrap {
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
    }

    .toggle-label {
        font-size: 11px;
        font-weight: 700;
        color: #94a3b8;
        transition: 0.3s;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .toggle-label.active {
        color: #1a7a8a;
    }

    .toggle-switch {
        position: relative;
        width: 44px;
        height: 24px;
        background: #e2e8f0;
        border-radius: 100px;
        cursor: pointer;
        transition: 0.3s;
    }

    .toggle-switch::after {
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

    .toggle-switch.annual {
        background: #1a7a8a;
    }

    .toggle-switch.annual::after {
        transform: translateX(20px);
    }

    .annual-discount-pill {
        background: #ecfdf5;
        color: #10b981;
        font-size: 9px;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 100px;
        margin-left: 4px;
        border: 1px solid #d1fae5;
    }

    .plan-savings-hint {
        font-size: 10px;
        color: #10b981;
        font-weight: 700;
        margin-top: 4px;
        opacity: 0;
        transform: translateY(5px);
        transition: 0.3s;
        height: 0;
        overflow: hidden;
    }

    .toggle-switch.annual ~ .plan-savings-hint,
    #upgradePlanModal.annual-view .plan-savings-hint {
        opacity: 1;
        transform: translateY(0);
        height: auto;
        margin-top: 4px;
    }

    /* GRID STYLES */
    .p-grid-container {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 12px;
    }

    .p-card {
        background: #ffffff;
        border: 1px solid #eef2f6;
        border-radius: 20px;
        padding: 20px 16px;
        display: flex;
        flex-direction: column;
        transition: 0.3s ease;
        position: relative;
    }

    .p-card.active {
        border: 2px solid #1a7a8a;
        background: #f0f9f8;
    }

    .p-card.blocked {
        opacity: 0.7;
        background: #f1f5f9;
        border-style: dashed;
    }

    .p-blocked-badge {
        position: absolute;
        top: -10px;
        left: 50%;
        transform: translateX(-50%);
        background: #ef4444;
        color: white;
        font-size: 7px;
        font-weight: 900;
        padding: 3px 8px;
        border-radius: 100px;
        z-index: 2;
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
        margin-bottom: 15px;
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
    }

    .p-btn.current {
        background: #1a7a8a;
        color: white;
        border-color: #1a7a8a;
    }

    .p-btn.blocked {
        color: #94a3b8;
        background: #f1f5f9;
    }

    .modal-footer-links {
        text-align: center;
        margin-top: 28px;
        font-size: 13px;
        color: #94a3b8;
        font-weight: 600;
    }

    .modal-footer-links a {
        color: #1a7f77;
        text-decoration: none;
    }

    @media (max-width: 880px) {
        .p-grid-container {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .upgrade-premium-modal .modal-content {
            width: 95%;
            margin: 10px auto;
            max-height: 95vh;
            overflow-y: auto;
        }

        .modal-wide {
            max-width: 100%;
        }

        .p-grid-container {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            gap: 16px;
            padding: 10px 10px 30px;
            margin: 0 -5px;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none; /* Firefox */
        }

        .p-grid-container::-webkit-scrollbar {
            display: none; /* Chrome/Safari */
        }

        .p-card {
            flex-shrink: 0;
            width: 270px;
            scroll-snap-align: center;
        }

        .usage-grid {
            grid-template-columns: 1fr;
        }
        
        .modal-user-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
        
        .benefits-list {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .p-card {
            width: 82%;
        }
        
        .modal-body {
            padding: 20px 15px;
        }
    }
</style>

<script>
    const planLimits = @json($jsPlanLimits);
    const currentUsage = { 
        trips: {{ $tripCount }}, 

        editors: {{ $editorCount }}
    };

    async function openUpgradeModal() {
        const modal = document.getElementById('upgradePlanModal');
        if (!modal) return;
        
        // Show modal immediately
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        try {
            // Fetch fresh usage data
            const response = await fetch('{{ route("subscription.usage") }}');
            const data = await response.json();
            
            if (data.success) {
                // Update elements if they exist
                const updateEl = (id, val) => { const el = document.getElementById(id); if(el) el.textContent = val; };
                const updateBar = (id, perc, reached) => { 
                    const el = document.getElementById(id); 
                    if(el) { 
                        el.style.width = perc + '%'; 
                        if(reached) el.classList.add('limit-reached'); else el.classList.remove('limit-reached');
                    } 
                };

                const usage = data.usage;
                const limits = data.limits;

                updateEl('modal-trip-count', usage.trips);
                updateEl('modal-trip-limit', '/' + (limits.max_trips >= 1000000 ? '∞' : limits.max_trips));
                updateBar('modal-trip-bar', Math.min(100, (usage.trips / Math.max(1, limits.max_trips)) * 100), usage.trips >= limits.max_trips);



                updateEl('modal-editor-count', usage.editors);
                updateEl('modal-editor-limit', '/' + (limits.max_editors >= 1000000 ? '∞' : limits.max_editors));
                updateBar('modal-editor-bar', Math.min(100, (usage.editors / Math.max(1, limits.max_editors)) * 100), usage.editors >= limits.max_editors);
                
                // Update sync object for validation
                currentUsage.trips = usage.trips;

                currentUsage.editors = usage.editors;
            }
        } catch (err) {
            console.error('Error fetching usage:', err);
        }
    }
    function closeUpgradeModal() {
        const modal = document.getElementById('upgradePlanModal');
        if (modal) { modal.style.display = 'none'; document.body.style.overflow = 'auto'; }
    }

    // Pricing Toggle Logic for Modal
    (function() {
        document.addEventListener('DOMContentLoaded', () => {
            const toggle = document.getElementById('modalPriceToggle');
            const modal = document.getElementById('upgradePlanModal');
            const labelMonthly = document.getElementById('modalLabelMonthly');
            const labelAnnual = document.getElementById('modalLabelAnnual');
            const priceVals = document.querySelectorAll('.p-price-val, .ns-price .amount');
            
            if (toggle) {
                toggle.addEventListener('click', () => {
                    const isAnnual = toggle.classList.toggle('annual');
                    modal.classList.toggle('annual-view', isAnnual);
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
                });
                
                // Transition support
                priceVals.forEach(v => v.style.transition = 'opacity 0.2s');
            }
        });
    })();

    // ─── Plan Gate Logic ───────────────────────────────────────────────────────
    let _pendingPlanKey = null;

    function updateUserPlan(planKey) {
        const target = planLimits[planKey];
        if (!target) return;

        // Corporativo → redireccionar a Contáctanos directamente
        if (planKey === 'corporativo') {
            window.location.href = '{{ route("contact") }}';
            return;
        }

        // Usage limit checks (downgrade guard)
        if (currentUsage.trips > target.trips) {
            alert(`No puedes bajar al plan ${target.name} porque tienes ${currentUsage.trips} itinerarios y el plan solo permite ${target.trips}.`);
            return;
        }
        if (currentUsage.editors > target.editors) {
            alert(`No puedes bajar al plan ${target.name} porque has invitado a ${currentUsage.editors} editores y el plan solo permite ${target.editors}. Por favor, elimina colaboradores antes de bajar de plan.`);
            return;
        }

        // Open the gate modal instead of directly changing the plan
        _pendingPlanKey = planKey;
        document.getElementById('gateTargetPlanName').textContent = target.name;
        document.getElementById('gateTargetPlanName2').textContent = target.name;

        // Reset state
        document.getElementById('planAccessCodeInput').value = '';
        document.getElementById('planCodeError').style.display = 'none';
        document.getElementById('planReqError').style.display = 'none';
        document.getElementById('reqName').value = '';
        document.getElementById('reqPhone').value = '';
        document.getElementById('reqEmail').value = '';

        // Pre-fill contact email from known user email
        const userEmailEl = document.querySelector('.user-email');
        if (userEmailEl) document.getElementById('reqEmail').value = userEmailEl.textContent.trim();

        showPlanGateStep(1);
        document.getElementById('planGateModal').style.display = 'flex';
    }

    function closePlanGateModal() {
        document.getElementById('planGateModal').style.display = 'none';
        _pendingPlanKey = null;
    }

    function showPlanGateStep(n) {
        [1,2,3].forEach(i => {
            const el = document.getElementById('planGateStep' + i);
            if (el) el.style.display = i === n ? 'block' : 'none';
        });
    }

    function switchToPlanRequest() { showPlanGateStep(2); }
    function switchToCodeStep()    { showPlanGateStep(1); }

    async function submitPlanCode() {
        const code = document.getElementById('planAccessCodeInput').value.trim();
        if (!code) {
            showGateCodeError('Por favor ingresa el código de acceso.');
            return;
        }
        const btn = document.getElementById('planCodeSubmitBtn');
        const label = document.getElementById('planCodeSubmitLabel');
        btn.disabled = true;
        label.textContent = 'Verificando...';

        try {
            const res = await fetch('{{ route("profile.plan.verify-code") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ plan: _pendingPlanKey, code })
            });
            const data = await res.json();
            if (data.success) {
                window.location.reload();
            } else {
                showGateCodeError(data.message || 'Código inválido.');
            }
        } catch(e) {
            showGateCodeError('Error de conexión. Intenta nuevamente.');
        } finally {
            btn.disabled = false;
            label.textContent = 'Verificar código →';
        }
    }

    function showGateCodeError(msg) {
        const el = document.getElementById('planCodeError');
        el.textContent = msg;
        el.style.display = 'block';
    }

    async function submitPlanRequest() {
        const name  = document.getElementById('reqName').value.trim();
        const email = document.getElementById('reqEmail').value.trim();
        const phone = document.getElementById('reqPhone').value.trim();

        if (!name || !email) {
            const errEl = document.getElementById('planReqError');
            errEl.textContent = 'Nombre y correo son obligatorios.';
            errEl.style.display = 'block';
            return;
        }

        const btn = document.getElementById('planReqSubmitBtn');
        const label = document.getElementById('planReqSubmitLabel');
        btn.disabled = true;
        label.textContent = 'Enviando...';
        document.getElementById('planReqError').style.display = 'none';

        try {
            const res = await fetch('{{ route("profile.plan.request") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ plan: _pendingPlanKey, contact_name: name, contact_email: email, contact_phone: phone })
            });
            const data = await res.json();
            if (data.success) {
                showPlanGateStep(3);
            } else {
                const errEl = document.getElementById('planReqError');
                errEl.textContent = data.message || 'Error al enviar. Intenta de nuevo.';
                errEl.style.display = 'block';
            }
        } catch(e) {
            const errEl = document.getElementById('planReqError');
            errEl.textContent = 'Error de conexión. Intenta nuevamente.';
            errEl.style.display = 'block';
        } finally {
            btn.disabled = false;
            label.textContent = 'Enviar solicitud →';
        }
    }

    // Close gate modal on backdrop click
    document.addEventListener('DOMContentLoaded', function() {
        const gateModal = document.getElementById('planGateModal');
        if (gateModal) {
            gateModal.addEventListener('click', function(e) {
                if (e.target === gateModal) closePlanGateModal();
            });
        }
        // Enter key in code input
        const codeInput = document.getElementById('planAccessCodeInput');
        if (codeInput) {
            codeInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') submitPlanCode();
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('upgradePlanModal');
        if (modal) { modal.addEventListener('click', function (e) { if (e.target === modal) closeUpgradeModal(); }); }
    });
</script>