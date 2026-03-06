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
    <!-- Main Editing Form -->
    <div class="trip-header-form">
        @php $today = date('Y-m-d'); @endphp
        <input type="hidden" id="start-date" value="{{ $trip && $trip->start_date ? $trip->start_date->format('Y-m-d') : $today }}">

        <div class="form-group-top">
            <label class="field-label">TÍTULO DEL PLAN</label>
            <input type="text" id="trip-title" class="trip-title-input-premium" placeholder="Nombre del viaje" value="{{ $trip->title ?? '' }}">
        </div>


        <hr class="header-divider">

    <!-- New Section: Cover Image Upload -->
    <div class="trip-cover-upload-section">
        <label class="field-label">FOTO DE PORTADA</label>
        <div class="cover-upload-container" id="cover-upload-container">
            <input type="file" id="trip-cover-input" accept="image/jpeg, image/png, image/webp" style="display: none;">
            
            <div class="cover-preview-wrapper" id="cover-preview-wrapper" style="{{ $trip && $trip->cover_image_url ? '' : 'display: none;' }}">
                <img src="{{ $trip && $trip->cover_image_url ? $trip->cover_image_url : '' }}" id="cover-image-preview" alt="Portada del Viaje" class="cover-image-preview">
                <button type="button" class="btn-remove-cover" id="btn-remove-cover" title="Eliminar imagen">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            
            <div class="cover-upload-prompt" id="cover-upload-prompt" style="{{ $trip && $trip->cover_image_url ? 'display: none;' : '' }}">
                <div class="upload-option-unsplash" onclick="event.stopPropagation(); openUnsplashModal()">
                    <div class="upload-icon"><i class="fas fa-thin fa-images"></i></div>
                    <div class="upload-text">Agregar foto desde Unsplash</div>
                    <div class="upload-hint">Miles de fotos profesionales sin costo · sin atribución</div>
                </div>
                
                <div class="unsplash-divider" style="margin: 1rem 0; width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <hr style="flex:1; border:0; border-top:1px solid #e2e8f0;">
                    <span style="font-size:0.75rem; color:#94a3b8; font-weight:600;">O</span>
                    <hr style="flex:1; border:0; border-top:1px solid #e2e8f0;">
                </div>

                <div class="upload-option-local" onclick="event.stopPropagation(); document.getElementById('trip-cover-input').click()">
                    <button type="button" class="btn-unsplash-search" id="btn-upload-local">
                        <div class="upload-text">Sube tu propia imagen</div>
                        <div class="upload-hint">JPG, PNG o WEBP · máx. 5 MB</div>
                    </button>
                </div>
            </div>
            
            <div class="upload-progress-overlay" id="upload-progress-overlay" style="display: none;">
                <div class="loader-spinner"><i class="fas fa-spinner fa-spin"></i></div>
                <span>Subiendo...</span>
            </div>
        </div>
    </div>

        <div class="form-row-flexible" style="margin-top: 1rem;">
            <div class="form-group-half">
                <label class="field-label">NÚMERO DE VIAJEROS</label>
                <div class="stepper-input">
                    <button type="button" class="stepper-btn minus" onclick="decrementTravelers()">−</button>
                    <input type="number" id="trip-travelers" class="stepper-value" value="{{ $trip->travelers ?? 1 }}" min="1">
                    <button type="button" class="stepper-btn plus" onclick="incrementTravelers()">+</button>
                </div>
                <span class="field-help">Adultos + niños</span>
            </div>

            <div class="form-group-half">
                <label class="field-label">VALOR DEL VIAJE</label>
                <div class="price-group-premium">
                    <input type="number" id="trip-price" class="price-input-premium" placeholder="0,00" min="0" step="0.01" value="{{ $trip->price ?? 0 }}">
                    <div class="currency-selector-wrapper">
                        <select id="trip-currency" class="currency-select-premium">
                            <option value="USD" {{ ($trip->currency ?? 'USD') == 'USD' ? 'selected' : '' }}>USD</option>
                            <option value="EUR" {{ ($trip->currency ?? '') == 'EUR' ? 'selected' : '' }}>EUR</option>
                            <option value="COP" {{ ($trip->currency ?? '') == 'COP' ? 'selected' : '' }}>COP</option>
                            <option value="MXN" {{ ($trip->currency ?? '') == 'MXN' ? 'selected' : '' }}>MXN</option>
                        </select>
                        <i class="fas fa-chevron-down select-icon"></i>
                    </div>
                </div>
                <span class="field-help">Valor total del viaje</span>
            </div>
        </div>
    </div>

    <!-- Footer within the card -->
    <div class="trip-header-footer">
        <div class="footer-actions">
            <button type="button" class="btn-cancel-premium" onclick="window.history.back()">Cancelar</button>
            <button type="button" class="btn-save-premium" data-action="save-trip">Guardar cambios</button>
        </div>
    </div>
</div>

<!-- Unsplash Search Modal -->
<div class="unsplash-modal-overlay" id="unsplash-modal" style="display: none;">
    <div class="unsplash-modal-content">
        <div class="unsplash-modal-header">
            <h3>Galería de Imágenes</h3>
            <button type="button" class="unsplash-btn-close" onclick="closeUnsplashModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="unsplash-modal-body">
            <div class="unsplash-search-bar">
                <input type="text" id="unsplash-search-input" placeholder="Ej. París, playa, montañas..." onkeypress="handleUnsplashEnter(event)">
                <button type="button" class="unsplash-btn-search" onclick="performUnsplashSearch()"><i class="fas fa-search"></i> Buscar</button>
            </div>
            
            <div class="unsplash-loading" id="unsplash-loading" style="display: none;">
                <i class="fas fa-spinner fa-spin"></i> Buscando imágenes increíbles...
            </div>
            
            <div class="unsplash-error" id="unsplash-error" style="display: none;"></div>
            
            <div class="unsplash-grid" id="unsplash-grid">
                <!-- Fallback content, will be overwritten by JS -->
                <div style="grid-column: 1/-1; text-align: center; color: #94a3b8; padding: 2rem;">
                    Busca un destino para ver fotos...
                </div>
            </div>
        </div>
        <div class="unsplash-modal-footer">
            <span class="unsplash-credit">Fotos proporcionadas por <a href="https://unsplash.com/?utm_source=viantryp&utm_medium=referral" target="_blank">Unsplash</a></span>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Stepper logic
    window.incrementTravelers = function() {
        const input = document.getElementById('trip-travelers');
        if (input) {
            input.value = parseInt(input.value || 0) + 1;
            input.dispatchEvent(new Event('change'));
        }
    };

    window.decrementTravelers = function() {
        const input = document.getElementById('trip-travelers');
        if (input && input.value > 1) {
            input.value = parseInt(input.value) - 1;
            input.dispatchEvent(new Event('change'));
        }
    };

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

                // DISABLE AUTOMATIC DATE UPDATES
                // The user requested to stop automatic consecutive date filling.
                // Keeping the code commented out for reference or future optional enabling.
                /*
                if (typeof updateItineraryDates === 'function') {
                    try { updateItineraryDates(); } catch (e) { console.warn('updateItineraryDates failed', e); }
                }
                */
            });
        }

    // Cover Image Upload Logic
    const uploadContainer = document.getElementById('cover-upload-container');
    const fileInput = document.getElementById('trip-cover-input');
    const previewWrapper = document.getElementById('cover-preview-wrapper');
    const previewImage = document.getElementById('cover-image-preview');
    const uploadPrompt = document.getElementById('cover-upload-prompt');
    const progressOverlay = document.getElementById('upload-progress-overlay');
    const btnRemoveCover = document.getElementById('btn-remove-cover');

    if (uploadContainer && fileInput) {
        // Handle File Selection
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                handleCoverFileUpload(this.files[0]);
            }
        });

        // Handle Drag & Drop
        uploadContainer.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadContainer.classList.add('dragover');
        });
        
        uploadContainer.addEventListener('dragleave', () => {
            uploadContainer.classList.remove('dragover');
        });
        
        uploadContainer.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadContainer.classList.remove('dragover');
            if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                const file = e.dataTransfer.files[0];
                if (file.type.match('image.*')) {
                    handleCoverFileUpload(file);
                } else {
                    alert('Por favor, sube un archivo de imagen (JPG, PNG, WEBP).');
                }
            }
        });

        // Handle Remove Button
        btnRemoveCover.addEventListener('click', () => {
            if (confirm('¿Seguro que deseas eliminar la foto de portada?')) {
                // Here we might just clear UI and let the saveFlow handle the empty string, or send a delete request
                // The easiest is just clearing the visual and state so "Guardar cambios" saves empty cover
                previewImage.src = '';
                previewWrapper.style.display = 'none';
                uploadPrompt.style.display = '';
                fileInput.value = '';
                
                // Keep this synced with form data
                if (!window.existingTripData) window.existingTripData = {};
                window.existingTripData.cover_image_url = null;
                
                // Optional: Alert the user to click Save
                if (typeof showNotification === 'function') {
                    showNotification('Info', 'Recuerda hacer clic en "Guardar cambios" para aplicar.');
                }
            }
        });
    }

    function handleCoverFileUpload(file) {
        // Display Progress
        progressOverlay.style.display = 'flex';
        
        // Prepare FormData
        const formData = new FormData();
        formData.append('cover', file);
        
        // CSRF Token
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const token = tokenMeta ? tokenMeta.getAttribute('content') : '';
        
        // Trip ID
        const tripId = "{{ $trip ? $trip->id : '' }}";
        if (!tripId) {
            alert("Sube la foto después de guardar el viaje inicial.");
            progressOverlay.style.display = 'none';
            return;
        }

        // Send AJAX Request
        fetch(`/trips/${tripId}/cover`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            progressOverlay.style.display = 'none';
            if (data.success && data.cover_url) {
                // Update Preview
                previewImage.src = data.cover_url;
                previewWrapper.style.display = 'block';
                uploadPrompt.style.display = 'none';
                
                // Keep global state synced
                if (!window.existingTripData) window.existingTripData = {};
                window.existingTripData.cover_image_url = data.cover_url;
                
                if (typeof showNotification === 'function') {
                    showNotification('Éxito', 'Foto de portada subida correctamente.');
                }
            } else {
                alert(data.message || 'Ocurrió un error al subir la imagen.');
            }
        })
        .catch(error => {
            console.error('Error uploading cover:', error);
            progressOverlay.style.display = 'none';
            alert('Error de conexión al subir la imagen.');
        });
    }

    // Unsplash Global Functions
    window.openUnsplashModal = function() {
        document.getElementById('unsplash-modal').style.display = 'flex';
        // Auto-search if empty
        const grid = document.getElementById('unsplash-grid');
        if (grid.children.length <= 1) { // 1 is the fallback div
            performUnsplashSearch('travel destination');
        }
    };
    
    window.closeUnsplashModal = function() {
        document.getElementById('unsplash-modal').style.display = 'none';
    };
    
    window.handleUnsplashEnter = function(e) {
        if (e.key === 'Enter') {
            performUnsplashSearch();
        }
    };

    window.performUnsplashSearch = function(defaultQuery = null) {
        let query = defaultQuery || document.getElementById('unsplash-search-input').value.trim();
        if (!query && !defaultQuery) {
            query = 'travel';
        }

        const grid = document.getElementById('unsplash-grid');
        const loading = document.getElementById('unsplash-loading');
        const errorDiv = document.getElementById('unsplash-error');

        grid.innerHTML = '';
        errorDiv.style.display = 'none';
        loading.style.display = 'flex';

        fetch(`/api/unsplash/search?query=${encodeURIComponent(query)}&per_page=12`, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            loading.style.display = 'none';
            if (data.success && data.images && data.images.length > 0) {
                renderUnsplashImages(data.images);
            } else {
                errorDiv.innerText = data.message || 'No se encontraron imágenes para esta búsqueda.';
                errorDiv.style.display = 'block';
            }
        })
        .catch(err => {
            console.error('Unsplash search error:', err);
            loading.style.display = 'none';
            errorDiv.innerText = 'Error de conexión al buscar en la galería.';
            errorDiv.style.display = 'block';
        });
    }

    function renderUnsplashImages(images) {
        const grid = document.getElementById('unsplash-grid');
        images.forEach(img => {
            const wrap = document.createElement('div');
            wrap.className = 'unsplash-img-container';
            wrap.innerHTML = `
                <img src="${img.url_thumb}" loading="lazy" alt="Unsplash Image">
                <div class="unsplash-img-overlay">
                    <span class="unsplash-img-author">Por ${img.author_name}</span>
                </div>
            `;
            wrap.onclick = () => selectUnsplashImage(img.url_full);
            grid.appendChild(wrap);
        });
    }

    function selectUnsplashImage(fullUrl) {
        // Set preview
        previewImage.src = fullUrl;
        previewWrapper.style.display = 'block';
        uploadPrompt.style.display = 'none';
        
        // Keep synced
        if (!window.existingTripData) window.existingTripData = {};
        window.existingTripData.cover_image_url = fullUrl;
        
        // Notify and close
        closeUnsplashModal();
        if (typeof showNotification === 'function') {
            showNotification('Éxito', 'Imagen seleccionada. Recuerda guardar el viaje.');
        }
    }
});
</script>
