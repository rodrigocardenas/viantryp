{{-- Componente: Unsaved Changes Modal --}}
{{-- Ubicación: resources/views/components/unsaved-changes-modal.blade.php --}}
{{-- Propósito: Modal de advertencia para cambios sin guardar --}}
{{-- Props: ninguno --}}
{{-- CSS: resources/css/components/modals.css --}}

<!-- Unsaved Changes Modal -->
<div id="unsaved-changes-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-exclamation-triangle" style="color: #ffc107;"></i> Cambios sin Guardar</h3>
            <button class="modal-close" data-action="close-unsaved-modal">&times;</button>
        </div>
        <div class="modal-body">
            <p style="color: var(--text-gray); font-size: 0.9rem;">Tienes cambios sin guardar. Al salir volverás al index principal.</p>
            <div class="changes-summary" style="background: #f8f9fa; padding: 1rem; border-radius: 8px; margin-top: 1rem;">
                <h5 style="margin: 0 0 0.5rem 0; color: var(--primary-dark);">
                    <i class="fas fa-list"></i> Resumen de cambios:
                </h5>
                <div id="changesSummary" style="font-size: 0.9rem; color: var(--text-gray);">
                    <!-- Se llenará dinámicamente -->
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" data-action="close-unsaved-modal">
                <i class="fas fa-times"></i>
                Cancelar
            </button>
            <button class="btn btn-danger" data-action="exit-without-saving">
                <i class="fas fa-sign-out-alt"></i>
                Salir sin guardar
            </button>
            <button class="btn btn-primary" data-action="save-and-exit">
                <i class="fas fa-save"></i>
                Guardar y salir
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Unsaved Changes Modal Functions
    function showUnsavedChangesModal() {
        const modal = document.getElementById('unsaved-changes-modal');
        const changesSummary = document.getElementById('changesSummary');

        // Generate changes summary
        const changes = generateChangesSummary();
        changesSummary.innerHTML = changes;

        modal.style.display = 'block';
    }

    function closeUnsavedModal() {
        const modal = document.getElementById('unsaved-changes-modal');
        modal.style.display = 'none';
    }

    function exitWithoutSaving() {
        window.location.href = '{{ route("trips.index") }}';
    }

    function saveAndExit() {
        saveTrip();
    }

    function generateChangesSummary() {
        const currentData = collectAllTripItems();
        const originalData = []; // This would need to be stored when the page loads

        let changes = '';

        // Check for new items
        const currentItemCount = Object.keys(currentData).length;
        if (currentItemCount > 0) {
            changes += `• Se agregaron ${currentItemCount} elementos al itinerario<br>`;
        }

        // Check for title changes
        const currentTitle = document.getElementById('trip-title').value;
        const originalTitle = document.getElementById('trip-title').defaultValue || '';
        if (currentTitle !== originalTitle) {
            changes += `• Título del viaje modificado<br>`;
        }

        // Check for date changes
        const currentStartDate = document.getElementById('start-date').value;
        const originalStartDate = document.getElementById('start-date').defaultValue || '';

        if (currentStartDate !== originalStartDate) {
            changes += `• Fechas del viaje modificadas<br>`;
        }

        if (!changes) {
            changes = '• Cambios menores en el contenido<br>';
        }

        return changes;
    }
</script>
@endpush
