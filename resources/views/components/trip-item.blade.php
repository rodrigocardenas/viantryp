@php
    $itemId = 'item-' . uniqid();
@endphp

<div class="timeline-item type-{{ $item->type }}" id="{{ $itemId }}" data-type="{{ $item->type }}" @foreach($item->data as $key => $value) @if($key !== 'note_content' && $key !== 'content' && $key !== 'extra_content') data-{{ str_replace('_', '-', $key) }}="{{ is_array($value) ? json_encode($value) : $value }}" @endif @endforeach>
    
    @if($item->type === 'title')
        <div class="item-header simple-text title-element">
            <h2 class="element-content-display">{!! nl2br(htmlspecialchars($item->data['content'] ?? $item->data['title_content'] ?? 'Título nuevo')) !!}</h2>
            <div class="item-actions hover-only">
                <button class="action-btn" data-action="edit-element" title="Editar">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="action-btn btn-danger" data-action="delete-element" title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    @elseif($item->type === 'paragraph')
        <div class="item-header simple-text paragraph-element">
            <p class="element-content-display">{!! nl2br(htmlspecialchars($item->data['content'] ?? 'Párrafo nuevo')) !!}</p>
            <div class="item-actions hover-only">
                <button class="action-btn" data-action="edit-element" title="Editar">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="action-btn btn-danger" data-action="delete-element" title="Eliminar">
                    <i class="fas fa-trash"></i>
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
                <button class="action-btn" data-action="edit-element" title="Editar">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="action-btn btn-danger" data-action="delete-element" title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    @else
        <div class="item-header">
            <div class="item-icon {{ $item->getIconClass() }}">
                <i class="{{ $item->getIcon() }}"></i>
            </div>
            <div class="item-info">
                <div class="item-type">{{ $item->getTypeLabel() }}</div>
                <div class="item-title">{!! $item->getTitle() !!}</div>
                <div class="item-subtitle">{!! $item->getSubtitle() !!}</div>
            </div>
            <div class="item-actions">
                <button class="action-btn" data-action="edit-element" title="Editar">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="action-btn btn-danger" data-action="delete-element" title="Eliminar">
                    <i class="fas fa-trash"></i>
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
        background: white;
        border: 1px solid var(--stone-300);
        border-radius: 12px;
        margin-bottom: 1.5rem;
        overflow: hidden;
        box-shadow: var(--shadow-soft);
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

    .timeline-item:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        transform: translateY(-1px);
    }

    .item-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--stone-300);
        display: flex;
        align-items: center;
        gap: 1rem;
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

    .title-element h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--ink);
    }

    .paragraph-element p {
        font-size: 1rem;
        color: var(--slate-600);
        line-height: 1.6;
    }

    .item-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .icon-flight {
        background: var(--blue-700);
    }

    .icon-hotel {
        background: #FF6B6B;
    }

    .icon-activity {
        background: #22c55e;
    }

    .icon-transport {
        background: #a78bfa;
    }

    .icon-note {
        background: #fb923c;
    }

    .item-info {
        flex: 1;
    }

    .item-type {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--slate-500);
        margin-bottom: 0.25rem;
    }

    .item-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--ink);
        margin-bottom: 0.25rem;
    }

    .item-subtitle {
        color: var(--slate-500);
        font-size: 0.9rem;
    }

    .item-actions {
        display: flex;
        gap: 0.5rem;
    }

    .action-btn {
        padding: 0.5rem;
        border: 1px solid var(--stone-300);
        border-radius: 6px;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
        color: var(--slate-500);
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
    }

    .action-btn:hover {
        background: var(--sky-50);
        border-color: var(--blue-700);
        color: var(--blue-700);
    }

    .action-btn.btn-danger:hover {
        background: #fef2f2;
        border-color: var(--danger);
        color: var(--danger);
    }
</style>
