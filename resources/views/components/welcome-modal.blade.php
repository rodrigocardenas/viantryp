@php
    $user = auth()->user();
    if (!$user || $user->initial_plan_chosen_at) return;
@endphp

<div id="welcomePlanModal" class="modal upgrade-premium-modal" style="display: flex; z-index: 10002;">
    <div class="modal-content modal-wide">
        <div class="modal-body">
            <!-- HEADER: USER INFO -->
            <div class="modal-user-header" style="margin-bottom: 24px;">
                <div class="user-info">
                    <div class="avatar user-avatar" style="background: var(--accent);">
                        @if($user->avatar)
                            <img src="{{ str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar) }}" alt="">
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
            <div style="text-align: center; margin-bottom: 32px;">
                <h2 style="font-family: 'Barlow Condensed', sans-serif; font-size: 32px; font-weight: 900; text-transform: uppercase; margin: 0 0 10px; color: #0f172a; letter-spacing: -0.5px;">Elige tu plan de inicio</h2>
                <p style="font-size: 15px; color: #64748b; max-width: 600px; margin: 0 auto; line-height: 1.6;">
                    Para empezar a crear itinerarios profesionales, selecciona el plan que mejor se adapte a tus necesidades. Solo el plan **Avanzado** incluye los primeros 7 días de prueba gratuita.
                </p>
            </div>

            <!-- PLANS GRID -->
            <div class="p-grid-container" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 12px; margin-bottom: 20px;">
                
                <!-- PLAN BÁSICO -->
                <div class="p-card" style="display: flex; flex-direction: column; height: 100%;">
                    <div class="p-name">Básico</div>
                    <div class="p-price"><span class="currency">$</span><span class="p-price-val">0</span><small>/mes</small></div>
                    <div class="p-benefits" style="flex: 1; margin-bottom: 15px;">
                        <div class="p-benefit"><i class="fas fa-check"></i> 1 Itinerario</div>
                        <div class="p-benefit"><i class="fas fa-check"></i> Link público</div>
                        <div class="p-benefit"><i class="fas fa-check"></i> Fotos gratis</div>
                    </div>
                    <button onclick="confirmInitialPlan('básico')" class="p-btn" style="margin-top: auto; border-color: #cbd5e1;">Elegir Básico Gratis</button>
                </div>

                <!-- PLAN ESENCIAL -->
                <div class="p-card" style="display: flex; flex-direction: column; height: 100%;">
                    <div class="p-name">Esencial</div>
                    <div class="p-price"><span class="currency">$</span><span class="p-price-val">5</span><small>/mes</small></div>
                    <div class="p-benefits" style="flex: 1; margin-bottom: 15px;">
                        <div class="p-benefit"><i class="fas fa-check"></i> 3 Itinerarios</div>
                        <div class="p-benefit"><i class="fas fa-check"></i> Google Places</div>
                        <div class="p-benefit"><i class="fas fa-check"></i> Sin anuncios</div>
                    </div>
                    <button onclick="checkInitialPlanGate('esencial')" class="p-btn" style="margin-top: auto;">Activar con Código</button>
                </div>

                <!-- PLAN AVANZADO -->
                <div class="p-card active" style="display: flex; flex-direction: column; height: 100%; border: 2px solid var(--accent); background: #f0f9f8;">
                    <div class="p-popular" style="background:#0f2a3a; color:white; font-size:9px; top:-12px; font-weight:800; padding:4px 10px;">PROBAR GRATIS</div>
                    <div class="p-name">Avanzado</div>
                    <div class="p-price"><span class="currency">$</span><span class="p-price-val">12</span><small>/mes</small></div>
                    <div class="p-benefits" style="flex: 1; margin-bottom: 15px;">
                        <div class="p-benefit"><i class="fas fa-check"></i> 10 Itinerarios</div>
                        <div class="p-benefit"><i class="fas fa-check"></i> 2 Colaboradores</div>
                        <div class="p-benefit"><i class="fas fa-check"></i> Branding PRO</div>
                        <div class="p-benefit" style="color: var(--accent); font-weight: 700; margin-top:5px;"><i class="fas fa-star"></i> 7 días de prueba</div>
                    </div>
                    <button onclick="confirmInitialPlan('avanzado')" class="p-btn current" style="margin-top: auto; background: var(--accent);">Probar 7 Días Gratis</button>
                </div>

                <!-- PLAN COLABORATIVO -->
                <div class="p-card" style="display: flex; flex-direction: column; height: 100%;">
                    <div class="p-name">Colaborativo</div>
                    <div class="p-price"><span class="currency">$</span><span class="p-price-val">29</span><small>/mes</small></div>
                    <div class="p-benefits" style="flex: 1; margin-bottom: 15px;">
                        <div class="p-benefit"><i class="fas fa-check"></i> Ilimitados</div>
                        <div class="p-benefit"><i class="fas fa-check"></i> Roles de equipo</div>
                        <div class="p-benefit"><i class="fas fa-check"></i> Soporte VIP</div>
                    </div>
                    <button onclick="checkInitialPlanGate('colaborativo')" class="p-btn" style="margin-top: auto;">Activar con Código</button>
                </div>

                <!-- PLAN CORPORATIVO -->
                <div class="p-card" style="display: flex; flex-direction: column; height: 100%;">
                    <div class="p-name">Corporativo</div>
                    <div class="p-price" style="font-size: 14px; margin-top: 5px;">A medida</div>
                    <div class="p-benefits" style="flex: 1; margin-bottom: 15px;">
                        <div class="p-benefit"><i class="fas fa-check"></i> Dominio propio</div>
                        <div class="p-benefit"><i class="fas fa-check"></i> API Avanzada</div>
                        <div class="p-benefit"><i class="fas fa-check"></i> Account Manager</div>
                    </div>
                    <button onclick="window.location.href='{{ route('contact') }}'" class="p-btn" style="margin-top: auto;">Contactar</button>
                </div>

            </div>

            <div style="text-align: center;">
                <p style="font-size: 11px; color: #94a3b8; font-weight: 500;">
                    ¿Tienes dudas? <a href="{{ route('contact') }}" target="_blank" style="color: var(--accent); font-weight: 700;">Contáctanos</a> para ayudarte con tu elección.
                </p>
            </div>
        </div>

        <!-- LOADING OVERLAY -->
        <div id="welcomeLoadingOverlay" style="display:none; position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.8); z-index:10003; align-items:center; justify-content:center; border-radius: 24px;">
            <div style="padding: 20px; text-align:center;">
                <i class="fas fa-circle-notch fa-spin" style="font-size:32px; color:var(--accent); margin-bottom:10px; display:block;"></i>
                <span style="font-size:14px; font-weight:700; color:#0f172a;">Configurando tu cuenta...</span>
            </div>
        </div>
    </div>
</div>

<script>
    async function confirmInitialPlan(plan) {
        if (!confirm(`Has seleccionado el plan ${plan.toUpperCase()}. ¿Deseas confirmar esta elección?`)) return;
        
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
        // Safety guard: The Basic plan should NEVER trigger the code gate
        if (plan === 'básico') {
            confirmInitialPlan('básico');
            return;
        }

        if (typeof updateUserPlan === 'function') {
            // Hide welcome modal so plan gate is visible
            const welcomeModal = document.getElementById('welcomePlanModal');
            if (welcomeModal) welcomeModal.style.display = 'none';

            window.planGateSuccessCallback = (verifiedPlan) => {
                finalizeInitialPlan(verifiedPlan || plan);
            };
            
            // Set a callback for when the gate is closed without success
            // to show the welcome modal again
            const originalClosePlanGate = window.closePlanGateModal;
            window.closePlanGateModal = function() {
                if (typeof originalClosePlanGate === 'function') originalClosePlanGate();
                if (welcomeModal) welcomeModal.style.display = 'flex';
                // Restore original close function
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
