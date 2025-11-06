{{-- Componente: New Trip Modal --}}
{{-- Ubicación: resources/views/components/editor/new-trip-modal.blade.php --}}
{{-- Propósito: Modal para crear un nuevo viaje --}}
{{-- Props: ninguno --}}

<!-- New Trip Modal -->
<div id="new-trip-modal" class="modal">
    <div class="modal-content new-trip-modal">
        <div class="modal-header">
            <div class="modal-title">
                <i class="fas fa-map-marker-alt"></i>
                <span>Nuevo Viaje</span>
            </div>
            <button class="modal-close" data-action="cancel-new-trip">&times;</button>
        </div>
        <div class="modal-body">
            <div class="welcome-section">
                <div class="airplane-icon">✈️</div>
                <h2>¡Bienvenido a tu nuevo viaje!</h2>
                <p>Dale un nombre especial a tu aventura</p>
            </div>
            <div class="input-section">
                <label for="new-trip-name">Nombre del viaje</label>
                <input type="text" id="new-trip-name" class="trip-name-input" placeholder="Ej: Aventura en París, Vacaciones en la playa..." autofocus>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" data-action="cancel-new-trip">Cancelar</button>
            <button class="btn-create" data-action="create-new-trip">
                <i class="fas fa-check"></i>
                Crear Viaje
            </button>
        </div>
    </div>
</div>
