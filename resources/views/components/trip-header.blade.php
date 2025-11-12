{{-- Componente: Trip Header --}}
{{-- Ubicación: resources/views/components/trip-header.blade.php --}}
{{-- Propósito: Header del viaje con título e información básica --}}
{{-- Props: trip (opcional) --}}
{{-- CSS: resources/css/components/trip-header.css --}}

@props(['trip' => null])

@php
    $client = $trip ? $trip->persons->where('type', 'client')->first() : null;
    $agent = $trip ? $trip->persons->where('type', 'agent')->first() : null;
@endphp

<div class="trip-header-card">
    <!-- Top card: data (title and compact subtitle) -->
    <div class="trip-card-top">
        <div class="trip-header-top">
            <input type="text" id="trip-title" class="trip-title-input h2-style" placeholder="Nombre del viaje" value="{{ $trip->title ?? '' }}">

            <div class="trip-subtitle">
                @if($client)
                    <span class="subtitle-client">{{ $client->name }}</span>
                @endif

                @if($trip && method_exists($trip, 'getDuration') && $trip->getDuration())
                    <span class="subtitle-sep">|</span>
                    <span class="subtitle-duration">{{ $trip->getDuration() }}</span>
                @endif

                @php $today = date('Y-m-d'); @endphp
                <span class="subtitle-sep">|</span>
                {{-- Editable start date input (value in Y-m-d for input[type=date]) --}}
                <input type="date" id="start-date" class="subtitle-date-input form-input"
                       value="{{ $trip && $trip->start_date ? $trip->start_date->format('Y-m-d') : $today }}"
                       min="{{ $today }}">
            </div>
        </div>

        <!-- Optionally other meta/chips to the right -->
        <div class="trip-header-meta">
            @if($agent)
                <div class="meta-chip agent-chip">
                    <div class="meta-text">
                        <strong>{{ $agent->name }}</strong>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Banner below the top section -->
    <div id="trip-banner" class="trip-card-banner" style="background-image: url('{{ $trip->cover_image_url ?? '/images/default-cover.png' }}')">
        <div class="banner-overlay">
            <div class="banner-actions">
                <button type="button" id="change-cover-btn" class="change-cover-btn" title="Cambiar portada">
                    <i class="fas fa-camera"></i>
                </button>
                <input type="file" id="cover-file-input" accept="image/*" style="display:none">
            </div>
            <!-- No text overlays on the banner by design -->
        </div>
    </div>
</div>

<script>
// Keep existing JS that binds to #change-cover-btn and #trip-banner
document.addEventListener('DOMContentLoaded', function() {
    const changeBtn = document.getElementById('change-cover-btn');
    const fileInput = document.getElementById('cover-file-input');
    const banner = document.getElementById('trip-banner');

    if (changeBtn && fileInput && banner) {
        changeBtn.addEventListener('click', function() { fileInput.click(); });

        fileInput.addEventListener('change', function(evt) {
            const file = evt.target.files && evt.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                banner.style.backgroundImage = `url('${e.target.result}')`;
                let hidden = document.getElementById('cover-data-url');
                if (!hidden) {
                    hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.id = 'cover-data-url';
                    hidden.name = 'cover_data_url';
                    document.querySelector('#editor-container')?.appendChild(hidden);
                }
                hidden.value = e.target.result;
            };
            reader.readAsDataURL(file);

            if (window.currentTripId) {
                // Client-side file size check (server limit: 5120 KB = 5 MB)
                const MAX_BYTES = 5 * 1024 * 1024;
                if (file.size > MAX_BYTES) {
                    const msg = 'La imagen es demasiado grande. El tamaño máximo permitido es 5 MB.';
                    try { showNotification && showNotification('Error', msg, 'error'); } catch(e){}
                    alert(msg);
                    return;
                }

                const fd = new FormData();
                fd.append('cover', file);
                // If a base64 preview was created, include it as a fallback
                const hiddenData = document.getElementById('cover-data-url');
                if (hiddenData && hiddenData.value) {
                    fd.append('cover_data_url', hiddenData.value);
                }

                fetch(`/trips/${window.currentTripId}/cover`, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: fd
                })
                .then(async res => {
                    const contentType = res.headers.get('content-type') || '';
                    const text = await res.text();

                    if (!res.ok) {
                        console.warn('Cover upload HTTP error', res.status, text);

                        // If server returned JSON (validation errors), parse and surface them
                        if (contentType.includes('application/json')) {
                            try {
                                const json = JSON.parse(text);
                                // Validation errors (422)
                                if (res.status === 422 && json.errors) {
                                    const coverErrors = json.errors.cover || [];
                                    const errMsg = coverErrors.join('\n') || json.message || 'Error de validación';
                                    try { showNotification && showNotification('Error al subir portada', errMsg, 'error'); } catch(e){}
                                    alert(errMsg);
                                    throw new Error('Validation error: ' + errMsg);
                                }

                                // CSRF/session expired
                                if (res.status === 419) {
                                    try { showNotification && showNotification('Sesión expirada', 'Tu sesión ha expirado. Vuelve a iniciar sesión y vuelve a intentarlo.','error'); } catch(e){}
                                }

                                // Generic JSON error
                                const message = json.message || ('HTTP ' + res.status);
                                try { showNotification && showNotification('Error', message, 'error'); } catch(e){}
                                throw new Error(message);
                            } catch (e) {
                                // JSON parse failed
                                console.warn('Failed to parse JSON error response', e, text);
                                throw new Error(`HTTP ${res.status}`);
                            }
                        }

                        // Non-JSON response (HTML) — likely redirect/login page or server error
                        if (res.status === 419) {
                            try { showNotification && showNotification('Sesión expirada', 'Tu sesión ha expirado. Vuelve a iniciar sesión y vuelve a intentarlo.','error'); } catch(e){}
                        }

                        throw new Error(`HTTP ${res.status}`);
                    }

                    if (contentType.includes('application/json')) {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            console.warn('Cover upload returned invalid JSON:', e, text);
                            throw e;
                        }
                    }

                    // Unexpected non-JSON response (probably an HTML error page like 419)
                    console.warn('Cover upload returned non-JSON response:', contentType, text);
                    throw new Error('Unexpected response content-type: ' + contentType);
                })
                .then(data => {
                    if (data && data.cover_url) {
                        banner.style.backgroundImage = `url('${data.cover_url}')`;
                    }
                })
                .catch(err => {
                    console.warn('No se pudo subir la portada ahora:', err);
                    // Optionally surface the error to the user
                    try { showNotification && showNotification('Error', 'No se pudo subir la portada. Revisa la consola.','error'); } catch(e){}
                });
            }
        });
    }

        // Make the start-date editable and keep it in sync with global state
        const startDateInput = document.getElementById('start-date');
        if (startDateInput) {
            // ensure min is set to today
            const today = new Date().toISOString().split('T')[0];
            if (!startDateInput.hasAttribute('min')) startDateInput.setAttribute('min', today);

            startDateInput.addEventListener('change', function() {
                // update window state so saveFlow picks it up
                if (!window.existingTripData) window.existingTripData = {};
                window.existingTripData.start_date = startDateInput.value || null;

                // If the editor has an update function for itinerary dates, call it
                if (typeof updateItineraryDates === 'function') {
                    try { updateItineraryDates(); } catch (e) { console.warn('updateItineraryDates failed', e); }
                }
            });
        }
});
</script>
