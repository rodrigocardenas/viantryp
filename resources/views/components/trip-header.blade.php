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
                const fd = new FormData();
                fd.append('cover', file);

                fetch(`/trips/${window.currentTripId}/cover`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: fd
                })
                .then(res => res.json())
                .then(data => {
                    if (data && data.cover_url) {
                        banner.style.backgroundImage = `url('${data.cover_url}')`;
                    }
                })
                .catch(err => console.warn('No se pudo subir la portada ahora:', err));
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
