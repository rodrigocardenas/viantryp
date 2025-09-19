{{-- Componente: Unsaved Changes Modal --}}
{{-- Ubicación: resources/views/components/editor/unsaved-changes-modal.blade.php --}}
{{-- Propósito: Modal de advertencia para cambios sin guardar --}}
{{-- Props: ninguno --}}

<!-- Unsaved Changes Modal -->
<div id="unsaved-changes-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-exclamation-triangle" style="color: #ffc107;"></i> Cambios sin Guardar</h3>
            <button class="modal-close" onclick="closeUnsavedModal()">&times;</button>
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
            <button class="btn btn-secondary" onclick="closeUnsavedModal()">
                <i class="fas fa-times"></i>
                Cancelar
            </button>
            <button class="btn btn-danger" onclick="exitWithoutSaving()">
                <i class="fas fa-sign-out-alt"></i>
                Salir sin guardar
            </button>
            <button class="btn btn-primary" onclick="saveAndExit()">
                <i class="fas fa-save"></i>
                Guardar y salir
            </button>
        </div>
    </div>
</div>
