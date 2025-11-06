{{-- Componente: Element Modal --}}
{{-- Ubicación: resources/views/components/element-modal.blade.php --}}
{{-- Propósito: Modal para agregar/editar elementos del viaje --}}
{{-- Props: ninguno --}}
{{-- CSS: resources/css/components/modals.css --}}

<!-- Element Modal -->
<div id="element-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modal-title">Agregar Elemento</h3>
            <button class="modal-close" data-action="close-modal">&times;</button>
        </div>
        <div class="modal-body" id="modal-body">
            <!-- Dynamic content will be inserted here -->
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" data-action="close-modal">Cancelar</button>
            <button class="btn btn-primary" data-action="save-element">Guardar</button>
        </div>
    </div>
</div>
