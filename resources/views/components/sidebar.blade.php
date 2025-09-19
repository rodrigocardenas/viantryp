{{-- Componente: Sidebar --}}
{{-- Ubicación: resources/views/components/sidebar.blade.php --}}
{{-- Propósito: Barra lateral con elementos arrastrables del viaje --}}
{{-- Props: ninguno --}}
{{-- CSS: resources/css/components/sidebar.css --}}

<!-- Left Sidebar -->
<div class="editor-sidebar">
    <div class="sidebar-content">
        <div class="sidebar-section">
            <h4>Elementos del Viaje</h4>
            <div class="element-categories">
                <div class="element-category" draggable="true" data-type="flight" ondragstart="drag(event)">
                    <div class="category-icon flight-icon">
                        <i class="fas fa-plane"></i>
                    </div>
                    <div class="category-info">
                        <h5>Vuelo</h5>
                        <p>Aerolínea y horarios</p>
                    </div>
                </div>

                <div class="element-category" draggable="true" data-type="hotel" ondragstart="drag(event)">
                    <div class="category-icon hotel-icon">
                        <i class="fas fa-bed"></i>
                    </div>
                    <div class="category-info">
                        <h5>Alojamiento</h5>
                        <p>Hotel o hospedaje</p>
                    </div>
                </div>

                <div class="element-category" draggable="true" data-type="activity" ondragstart="drag(event)">
                    <div class="category-icon activity-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="category-info">
                        <h5>Actividad</h5>
                        <p>Tour o experiencia</p>
                    </div>
                </div>

                <div class="element-category" draggable="true" data-type="transport" ondragstart="drag(event)">
                    <div class="category-icon transport-icon">
                        <i class="fas fa-car"></i>
                    </div>
                    <div class="category-info">
                        <h5>Traslado</h5>
                        <p>Tren, autobús, barco, taxi, van</p>
                    </div>
                </div>

                <div class="element-category" draggable="true" data-type="note" ondragstart="drag(event)">
                    <div class="category-icon note-icon">
                        <i class="fas fa-sticky-note"></i>
                    </div>
                    <div class="category-info">
                        <h5>Nota</h5>
                        <p>Información adicional</p>
                    </div>
                </div>

                <div class="element-category" draggable="true" data-type="summary" ondragstart="drag(event)">
                    <div class="category-icon summary-icon">
                        <i class="fas fa-list-check"></i>
                    </div>
                    <div class="category-info">
                        <h5>Resumen de Itinerario</h5>
                        <p>Resumen automático del viaje</p>
                    </div>
                </div>

                <div class="element-category" draggable="true" data-type="total" ondragstart="drag(event)">
                    <div class="category-icon total-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="category-info">
                        <h5>Valor Total</h5>
                        <p>Precio total del viaje</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
