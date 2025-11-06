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

        <form id="new-trip-form" class="modal-body">
            <div class="form-group">
                <label for="trip-title">Título del Viaje *</label>
                <input type="text" id="trip-title" name="title" required
                       placeholder="Ej: Viaje a Barcelona 2024">
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
    margin-bottom: 6px;
    font-weight: 500;
    color: #2d3748;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.2s;
}

.form-group input:focus,
.form-group textarea:focus {
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

            // Actualizar título
            document.getElementById('trip-title').textContent = title;

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

            // Show appropriate message based on action
            if (data.action === 'updated') {
                showNotification('Viaje actualizado', 'Un viaje existente ha sido actualizado exitosamente.', 'success');
            } else {
                showNotification('Viaje creado exitosamente', 'success');
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

// Validación en tiempo real
document.addEventListener('DOMContentLoaded', function() {
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
});
</script>
@endpush
