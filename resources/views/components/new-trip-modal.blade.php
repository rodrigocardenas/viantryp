{{-- Componente: Modal para Crear Nuevo Viaje --}}
{{-- Ubicación: resources/views/components/new-trip-modal.blade.php --}}
{{-- Propósito: Modal que permite crear un nuevo viaje con formulario básico --}}
{{-- Props: Ninguno --}}

<div id="new-trip-modal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Crear Nuevo Viaje</h2>
            <button class="modal-close" onclick="closeNewTripModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="new-trip-form" class="modal-body" onsubmit="event.preventDefault(); createTrip(); return false;">
            <div class="form-group">
                <label for="new-trip-title">Título del Viaje *</label>
                <input type="text" id="new-trip-title" name="title" required
                       placeholder="Ej: Viaje a Barcelona 2024">
            </div>

            <div class="form-group">
                <label for="start-date">Fecha de Inicio del Viaje</label>
                <input type="date" id="modal-start-date" name="start_date">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="client-name">Nombre del Cliente</label>
                    <input type="text" id="client-name" name="client_name" placeholder="Nombre del cliente">
                </div>
                <div class="form-group">
                    <label for="client-email">Correo del Cliente</label>
                    <input type="email" id="client-email" name="client_email" placeholder="correo@ejemplo.com">
                </div>
            </div>

            <div class="form-group">
                <label for="agent-id">Responsable (Agente)</label>
                <select id="agent-id" name="agent_id">
                    <option value="">Seleccionar agente...</option>
                    <!-- Opciones se cargarán dinámicamente -->
                </select>
            </div>

        </form>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeNewTripModal()">
                Cancelar
            </button>
            <button type="button" class="btn btn-primary" onclick="createTrip()">
                Crear Viaje
            </button>
        </div>
    </div>
</div>

@push('styles')
<style>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.modal-overlay.show {
    opacity: 1;
    visibility: visible;
}

.modal-content {
    background: white;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    transform: scale(0.9);
    transition: transform 0.3s ease;
}

.modal-overlay.show .modal-content {
    transform: scale(1);
}

.modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid #e1e5e9;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    margin: 0;
    font-size: 1.5rem;
    color: #1a202c;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #718096;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: all 0.2s;
}

.modal-close:hover {
    background: #f7fafc;
    color: #2d3748;
}

.modal-body {
    padding: 24px;
}

.form-group {
    margin-bottom: 20px;
}

.form-row {
    display: flex;
    gap: 16px;
}

.form-row .form-group {
    flex: 1;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #1a202c;
    font-size: 20px;
    background-color: white;
    padding: 8px 12px;
    border-radius: 6px;
    border: 2px solid #e2e8f0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 16px;
    background-color: white;
    transition: border-color 0.2s;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: #4299e1;
    box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
}

.modal-footer {
    padding: 20px 24px;
    border-top: 1px solid #e1e5e9;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

.btn {
    padding: 10px 16px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-secondary {
    background: #e2e8f0;
    color: #4a5568;
}

.btn-secondary:hover {
    background: #cbd5e0;
}

.btn-primary {
    background: #4299e1;
    color: white;
}

.btn-primary:hover {
    background: #3182ce;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>
@endpush

@push('scripts')
<script>
function closeNewTripModal() {
    const modal = document.getElementById('new-trip-modal');
    modal.classList.remove('show');
}

function createTrip() {
    // Prevent multiple rapid submissions
    if (window.isCreatingTrip) {
        console.log('Trip creation already in progress');
        return;
    }

    const form = document.getElementById('new-trip-form');
    const formData = new FormData(form);

    // Validar campos requeridos
    const title = formData.get('title');

    if (!title) {
        alert('Por favor completa todos los campos requeridos.');
        return;
    }

    // Set creation flag
    window.isCreatingTrip = true;

    // Deshabilitar botón
    const submitBtn = document.querySelector('.btn-primary');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Creando...';

    // Enviar datos
    fetch('{{ route("trips.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Cerrar modal
            closeNewTripModal();

            // Mostrar editor
            document.getElementById('editor-container').style.display = 'flex';

            // Actualizar título después de que el DOM se actualice
            setTimeout(() => {
                const tripTitleInput = document.getElementById('trip-title');
                if (tripTitleInput) {
                    tripTitleInput.value = title;
                }
            }, 100);

            // Configurar datos del viaje
            window.currentTripId = data.trip.id;
            window.existingTripData = data.trip;

            // Dynamically update the header with editor actions
            const headerActions = document.querySelector('.header .nav-actions');
            if(headerActions) {
                headerActions.innerHTML = `
                    <button class="btn btn-back" data-action="back" onclick="showUnsavedChangesModal()">
                        <i class="fas fa-arrow-left"></i> Volver
                    </button>
                    <button type="button" class="btn btn-save" data-action="save-trip">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                    <a href="/trips/${data.trip.id}/preview" class="btn btn-preview" target="_blank">
                        <i class="fas fa-eye"></i> Vista Previa
                    </a>
                    <a href="/trips/${data.trip.id}/pdf" class="btn btn-pdf" data-action="download-pdf">
                        <i class="fas fa-file-pdf"></i> Descarga PDF
                    </a>
                `;
            }

            // Update start date in the header if provided
            const startDateInput = document.getElementById('start-date');
            if (startDateInput && data.trip.start_date) {
                startDateInput.value = new Date(data.trip.start_date).toISOString().split('T')[0];
            }

            // Update client and agent info in the header
            const clientAgentInfo = document.querySelector('.client-agent-info');
            if (clientAgentInfo && data.trip.persons) {
                const client = data.trip.persons.find(p => p.type === 'client');
                const agent = data.trip.persons.find(p => p.type === 'agent');

                let html = '';
                if (client) {
                    html += `<div class="info-item"><label>Cliente:</label><span>${client.name} (${client.email})</span></div>`;
                }
                if (agent) {
                    html += `<div class="info-item"><label>Responsable:</label><span>${agent.name}</span></div>`;
                }

                if (html) {
                    clientAgentInfo.innerHTML = html;
                    clientAgentInfo.style.display = 'flex';
                } else {
                    clientAgentInfo.style.display = 'none';
                }
            }

            // Also update the new compact header (title, subtitle, banner and banner chip)
            const headerTitleInput = document.querySelector('.trip-header-block .h2-style') || document.getElementById('trip-title');
            if (headerTitleInput) {
                headerTitleInput.value = data.trip.title || title;
            }

            // Subtitle: client | duration | start date
            const subtitleEl = document.querySelector('.trip-subtitle');
            if (subtitleEl) {
                // If subtitle already contains an editable start-date input, preserve and update it
                const existingInput = subtitleEl.querySelector('input#start-date, input.subtitle-date-input');

                // Build parts for client and duration (do NOT include the date here if we have an input)
                const parts = [];
                if (data.trip.persons) {
                    const client = data.trip.persons.find(p => p.type === 'client');
                    if (client) parts.push(`<span class="subtitle-client">${escapeHtml(client.name)}</span>`);
                }

                if (data.trip.start_date && data.trip.end_date) {
                    try {
                        const s = new Date(data.trip.start_date);
                        const e = new Date(data.trip.end_date);
                        const diffDays = Math.floor((e - s) / (24 * 3600 * 1000)) + 1;
                        const durationText = diffDays === 1 ? '1 día' : diffDays + ' días';
                        parts.push(`<span class="subtitle-duration">${durationText}</span>`);
                    } catch (err) {
                        // ignore date parse errors
                    }
                }

                const sep = '<span class="subtitle-sep">&nbsp;|&nbsp;</span>';

                if (existingInput) {
                    // Update the input's value to the trip start date (ISO for input)
                    if (data.trip.start_date) {
                        const d = new Date(data.trip.start_date);
                        const iso = d.toISOString().split('T')[0];
                        existingInput.value = iso;
                        existingInput.setAttribute('value', iso);
                    }

                    // Build new innerHTML with client/duration parts, then append the input HTML
                    const inputHTML = existingInput.outerHTML;
                    subtitleEl.innerHTML = parts.filter(Boolean).join(sep);
                    if (subtitleEl.innerHTML && inputHTML) subtitleEl.innerHTML += sep + inputHTML;
                    else if (inputHTML) subtitleEl.innerHTML = inputHTML;
                } else {
                    // No editable input present: render plain spans including formatted date
                    if (data.trip.start_date) {
                        const d = new Date(data.trip.start_date);
                        const dd = String(d.getDate()).padStart(2, '0');
                        const mm = String(d.getMonth() + 1).padStart(2, '0');
                        const yyyy = d.getFullYear();
                        parts.push(`<span class="subtitle-date">${dd}/${mm}/${yyyy}</span>`);
                    }

                    subtitleEl.innerHTML = parts.map(p => p).join(sep);
                }
            }

            // Banner: update cover image (no text overlays on banner)
            const banner = document.getElementById('trip-banner');
            if (banner && data.trip.cover_image_url) {
                banner.style.backgroundImage = `url('${data.trip.cover_image_url}')`;
            }

            // small helper to avoid XSS when injecting names/emails
            function escapeHtml(str) {
                if (!str) return '';
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            // Show appropriate message based on action
            if (data.action === 'updated') {
                showNotification('Viaje actualizado', 'Un viaje existente ha sido actualizado exitosamente.', 'success');
            } else {
                showWelcomeToast();
            }
        } else {
            alert(data.message || 'Error al crear el viaje');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al crear el viaje. Inténtalo de nuevo.');
        window.isCreatingTrip = false; // Reset flag on error
    })
    .finally(() => {
        window.isCreatingTrip = false; // Clear the creation flag
        submitBtn.disabled = false;
        submitBtn.textContent = 'Crear Viaje';
    });
}

// Función para cargar agentes
function loadAgents() {
    fetch('{{ route("persons.agents") }}', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        const agentSelect = document.getElementById('agent-id');
        agentSelect.innerHTML = '<option value="">Seleccionar agente...</option>';
        data.forEach(agent => {
            const option = document.createElement('option');
            option.value = agent.id;
            option.textContent = agent.name;
            agentSelect.appendChild(option);
        });
        console.log('Agents loaded successfully');
    })
    .catch(error => {
        console.error('Error loading agents:', error);
    });
}

// Validación en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    loadAgents();
    const startDateInput = document.getElementById('start-date');
    const endDateInput = document.getElementById('end-date');

    if (startDateInput && endDateInput) {
        startDateInput.addEventListener('change', function() {
            if (endDateInput.value && this.value > endDateInput.value) {
                endDateInput.value = this.value;
            }
        });

        endDateInput.addEventListener('change', function() {
            if (startDateInput.value && this.value < startDateInput.value) {
                this.value = startDateInput.value;
            }
        });
    }

    // Función para mostrar toast de bienvenida
    window.showWelcomeToast = function() {
        // Remover toast existente si hay
        const existingToast = document.getElementById('welcome-toast');
        if (existingToast) {
            existingToast.remove();
        }

        // Crear el toast
        const toast = document.createElement('div');
        toast.id = 'welcome-toast';
        toast.innerHTML = `
            <div class="welcome-toast-content">
                <div class="welcome-toast-icon">
                    <i class="fas fa-magic"></i>
                </div>
                <div class="welcome-toast-text">
                    <h4>¡Tu viaje ha sido creado!</h4>
                    <p>Arrastra elementos desde la barra lateral para empezar a construir tu itinerario.</p>
                </div>
                <button class="welcome-toast-close" onclick="closeWelcomeToast()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        // Agregar estilos
        toast.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 0;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            z-index: 10000;
            max-width: 400px;
            opacity: 0;
            transform: translateY(100px);
            transition: all 0.5s ease;
        `;

        // Agregar al body
        document.body.appendChild(toast);

        // Mostrar con animación
        setTimeout(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';
        }, 100);

        // Auto-ocultar después de 8 segundos
        setTimeout(() => {
            closeWelcomeToast();
        }, 8000);
    }

    // Función para cerrar el toast
    window.closeWelcomeToast = function() {
        const toast = document.getElementById('welcome-toast');
        if (toast) {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(100px)';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 500);
        }
    }

    const modalStartDateInput = document.getElementById('modal-start-date');
    if (modalStartDateInput) {
        const today = new Date().toISOString().split('T')[0];
        modalStartDateInput.setAttribute('min', today);
    }
});
</script>

<style>
.welcome-toast-content {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 16px;
}

.welcome-toast-icon {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.welcome-toast-text h4 {
    margin: 0 0 4px 0;
    font-size: 16px;
    font-weight: 600;
}

.welcome-toast-text p {
    margin: 0;
    font-size: 14px;
    opacity: 0.9;
    line-height: 1.4;
}

.welcome-toast-close {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    opacity: 0.7;
    transition: opacity 0.2s ease;
    flex-shrink: 0;
    margin-top: -4px;
}

.welcome-toast-close:hover {
    opacity: 1;
}
</style>
@endpush
