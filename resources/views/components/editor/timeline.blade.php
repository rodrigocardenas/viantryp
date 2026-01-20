{{-- Componente: Timeline --}}
{{-- Ubicación: resources/views/components/editor/timeline.blade.php --}}
{{-- Propósito: Contenedor principal del timeline con días del viaje --}}
{{-- Props: trip (opcional) --}}

@props(['trip' => null])

<!-- Global Notes Section -->
<div class="info-card global-notes-card" id="global-notes-section">
    <div class="card-header">
        <i class="fas fa-sticky-note"></i>
        <h3>Notas Generales</h3>
    </div>
    <div class="card-content">
        <div class="global-notes-section-inner">
            <div class="notes-editor-row">
                <div id="global-note-editor" class="quill-container"></div>
                <div class="notes-actions">
                    <button class="btn btn-primary" id="btn-save-global-note" type="button">Agregar Nota</button>
                </div>
            </div>
            <div class="notes-container" id="global-notes-list" ondrop="drop(event)" ondragover="allowDrop(event)">
        @if(isset($trip) && $trip->notes && count($trip->notes) > 0)
            @foreach($trip->notes as $note)
                <x-trip-item :item="$note" :day="null" />
            @endforeach
        @endif
            </div>
        </div>
    </div>
</div>

<!-- Days Container -->
<div class="days-container" id="days-container">
    @if(isset($trip) && $trip->days && count($trip->days) > 0)
        @foreach($trip->days as $day)
            <div class="day-card" data-day="{{ $day->day }}">
                <div class="day-header">
                    <h3>Día {{ $day->day }}</h3>
                    <p class="day-date">{{ $day->getFormattedDate() }}</p>
                </div>
                <div class="day-content" ondrop="drop(event)" ondragover="allowDrop(event)">
                    <p class="drag-instruction">Arrastra elementos aquí para personalizar este día</p>

                    @if($day->items && count($day->items) > 0)
                        @foreach($day->items as $item)
                            <x-trip-item :item="$item" :day="$day->day" />
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    @else
        {{-- Always show at least one day for editing --}}
        <div class="day-card" data-day="1">
            <div class="day-header">
                <h3>Día 1</h3>
                <p class="day-date" id="day-1-date">
                    @if(isset($trip) && $trip->start_date)
                        {{ $trip->start_date->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                    @else
                        martes, 16 de septiembre de 2025
                    @endif
                </p>
            </div>
            <div class="day-content" ondrop="drop(event)" ondragover="allowDrop(event)">
                <div class="add-element-btn btn-sm" data-action="add-element" data-day="1">
                    <i class="fas fa-plus"></i>
                </div>
            </div>
                </div>
    @endif

    <!-- Add Day Section -->

</div></search>
</search_and_replace>
<x-editor-add-day-section />

@push('scripts')
<script>
    // Minimal helpers for editor timeline
    function getTypeLabel(type) {
        const labels = {
            'flight': 'Vuelo',
            'hotel': 'Hotel',
            'activity': 'Actividad',
            'transport': 'Transporte',
            'note': 'Nota',
            'summary': 'Resumen',
            'total': 'Total'
        };
        return labels[type] || type;
    }

    function initializeGlobalNotesEditor() {
        const editorEl = document.getElementById('global-note-editor');
        if (!editorEl) return;
        if (!window.Quill) {
            setTimeout(initializeGlobalNotesEditor, 100);
            return;
        }
        try {
            const q = new window.Quill('#global-note-editor', { theme: 'snow', modules: { toolbar: [['bold','italic','underline'], [{ 'list': 'ordered'}, { 'list': 'bullet' }], ['link'], ['clean']] } });
            // Keep reference globally for debugging
            window.__globalNotesQuill = q;
            const saveBtn = document.getElementById('btn-save-global-note');
            if (saveBtn) saveBtn.addEventListener('click', function() {
                const content = (window.__globalNotesQuill && window.__globalNotesQuill.root && window.__globalNotesQuill.root.innerHTML) || '';
                if (!content.replace(/<[^>]*>/g, '').trim()) { alert('Ingresa contenido para la nota'); return; }
                const data = { type: 'note', note_title: 'Nota', note_content: content, day: null };
                if (typeof timelineManager !== 'undefined' && typeof timelineManager.addElementToDay === 'function') {
                    timelineManager.addElementToDay(data);
                }
                // Clear editor
                window.__globalNotesQuill.setContents([]);
            });
            // Expose global save function
            window.saveGlobalNote = function() {
                const content = (window.__globalNotesQuill && window.__globalNotesQuill.root && window.__globalNotesQuill.root.innerHTML) || '';
                if (!content.replace(/<[^>]*>/g, '').trim()) { alert('Ingresa contenido para la nota'); return; }
                const data = { type: 'note', note_title: 'Nota', note_content: content, day: null };
                if (typeof timelineManager !== 'undefined' && typeof timelineManager.addElementToDay === 'function') {
                    timelineManager.addElementToDay(data);
                }
                window.__globalNotesQuill.setContents([]);
            };
        } catch (err) {
            console.error('Error initializing Quill on editor timeline:', err);
        }
    }

    document.addEventListener('DOMContentLoaded', function() { setTimeout(initializeGlobalNotesEditor, 200); });
</script>
@endpush
