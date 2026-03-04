@php
    $itemId = 'item-' . uniqid();
@endphp

<div class="timeline-item type-{{ $item->type }}" id="{{ $itemId }}" data-type="{{ $item->type }}" @foreach($item->data as $key => $value) @if($key !== 'note_content' && $key !== 'content' && $key !== 'extra_content') data-{{ str_replace('_', '-', $key) }}="{{ is_array($value) ? json_encode($value) : $value }}" @endif @endforeach>
    
    @if($item->type === 'title')
        <div class="item-header simple-text title-element">
            <h2 class="element-content-display">{!! nl2br(htmlspecialchars($item->data['content'] ?? $item->data['title_content'] ?? 'Título nuevo')) !!}</h2>
            <div class="item-actions hover-only">
                <button class="action-btn-outline" data-action="edit-element" title="Editar">
                    <i class="far fa-edit"></i>
                </button>
                <button class="action-btn-outline text-danger" data-action="delete-element" title="Eliminar">
                    <i class="far fa-trash-alt"></i>
                </button>
            </div>
        </div>
    @elseif($item->type === 'paragraph')
        <div class="item-header simple-text paragraph-element">
            <p class="element-content-display">{!! nl2br(htmlspecialchars($item->data['content'] ?? 'Párrafo nuevo')) !!}</p>
            <div class="item-actions hover-only">
                <button class="action-btn-outline" data-action="edit-element" title="Editar">
                    <i class="far fa-edit"></i>
                </button>
                <button class="action-btn-outline text-danger" data-action="delete-element" title="Eliminar">
                    <i class="far fa-trash-alt"></i>
                </button>
            </div>
        </div>
    @elseif($item->type === 'extra')
        <div class="item-header simple-text extra-element">
            <div class="element-content-display">
                <h3 style="margin: 0 0 5px 0; color: #0d3b4c; font-size: 16px;">{!! htmlspecialchars($item->data['extra_title'] ?? 'Información Extra') !!}</h3>
                <p style="margin: 0; color: #4B5563; font-size: 14px;">{!! nl2br(htmlspecialchars($item->data['extra_content'] ?? '...')) !!}</p>
            </div>
            <div class="item-actions hover-only">
                <button class="action-btn-outline" data-action="edit-element" title="Editar">
                    <i class="far fa-edit"></i>
                </button>
                <button class="action-btn-outline text-danger" data-action="delete-element" title="Eliminar">
                    <i class="far fa-trash-alt"></i>
                </button>
            </div>
        </div>
    @else
        <div class="item-header">
            <div class="drag-handle" title="Arrastrar para reordenar">
                <i class="fas fa-grip-vertical"></i>
            </div>
            <div class="item-icon {{ $item->getIconClass() }}">
                {!! $item->getIcon() !!}
            </div>
            <div class="item-info">
                <div class="item-type">{{ $item->getTypeLabel() }}</div>
                <div class="item-title">{!! $item->getTitle() !!}</div>
                <div class="item-subtitle">{!! $item->getSubtitle() !!}</div>
            </div>
            <div class="item-actions">
                <button class="action-btn-outline" data-action="edit-element" title="Editar">
                    <i class="far fa-edit"></i>
                </button>
                <button class="action-btn-outline text-danger" data-action="delete-element" title="Eliminar">
                    <i class="far fa-trash-alt"></i>
                </button>
            </div>
        </div>
    @endif
</div>

@if(isset($item->data['note_content']))
<script>
    // Set note_content via JavaScript to preserve HTML
    (function() {
        const element = document.getElementById('{{ $itemId }}');
        if (element) {
            element.dataset.noteContent = {!! json_encode($item->data['note_content']) !!};
        }
    })();
</script>
@endif

@if(isset($item->data['content']) && in_array($item->type, ['title', 'paragraph']))
<script>
    // Set content data attribute via JS to avoid attribute breaking
    (function() {
        const element = document.getElementById('{{ $itemId }}');
        if (element) {
            element.dataset.content = {!! json_encode($item->data['content']) !!};
        }
    })();
</script>
@endif

<style>
    .timeline-item {
        transition: all 0.3s ease;
    }

    .timeline-item.type-title, .timeline-item.type-paragraph, .timeline-item.type-extra {
        border: none;
        background: transparent;
        box-shadow: none;
        margin-bottom: 0.5rem;
    }
    
    .timeline-item.type-title:hover, .timeline-item.type-paragraph:hover, .timeline-item.type-extra:hover {
        background: rgba(0, 0, 0, 0.02);
        box-shadow: none;
    }

    .item-header {
        position: relative;
    }

    .item-header.simple-text {
        padding: 0.5rem 1rem;
        border-bottom: none;
        border-radius: 8px;
    }

    .item-header.simple-text .item-actions.hover-only {
        opacity: 0;
        transition: opacity 0.2s;
        position: absolute;
        right: 1rem;
    }

    .item-header.simple-text:hover .item-actions.hover-only {
        opacity: 1;
    }

    .element-content-display {
        margin: 0;
    }
</style>
