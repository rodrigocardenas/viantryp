{{-- Componente: Element Modal --}}
{{-- Ubicación: resources/views/components/editor/element-modal.blade.php --}}
{{-- Propósito: Modal para agregar/editar elementos del viaje --}}
{{-- Props: ninguno --}}

<!-- Element Modal -->
<div id="element-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modal-title">Agregar Elemento</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body" id="modal-body">
            <!-- Dynamic content will be inserted here -->
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal()">Cancelar</button>
            <button class="btn btn-primary" onclick="saveElement()">Guardar</button>
        </div>
    </div>
</div>
