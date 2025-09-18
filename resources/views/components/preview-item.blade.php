<div class="timeline-item">
    <div class="item-header">
        <div class="item-icon {{ $item->getIconClass() }}">
            <i class="{{ $item->getIcon() }}"></i>
        </div>
        <div class="item-info">
            <div class="item-type">{{ $item->getTypeLabel() }}</div>
            <div class="item-title">{{ $item->getTitle() }}</div>
            <div class="item-subtitle">{{ $item->getSubtitle() }}</div>
        </div>
        <button class="item-toggle" onclick="toggleItemContent(this)">
            <i class="fas fa-chevron-down"></i>
        </button>
    </div>
    <div class="item-content" style="display: none;">
        {!! $item->getDetailsHtml() !!}
    </div>
</div>
