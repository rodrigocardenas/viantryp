@if(isset($trip) && $trip->items_data)
    @php
        $globalNotes = array_filter($trip->items_data, function($item) {
            return isset($item['type']) && $item['type'] === 'note' && (!isset($item['day']) || $item['day'] === null);
        });
    @endphp

    @if(count($globalNotes) > 0)
        <div class="global-notes-unified-card">
            <div class="notes-content-wrapper">
                @foreach($globalNotes as $index => $note)
                    <div class="unified-note-item">
                        <div class="note-body">
                            {!! $note['note_content'] ?? '' !!}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <style>
            .global-notes-unified-card {
                background: #ffffff;
                border-radius: 1.5rem;
                overflow: hidden;
                margin-bottom: 2rem;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                border: 1px solid #e5e7eb;
            }

            .notes-content-wrapper {
                padding: 0;
            }

            .unified-note-item {
                padding: 1.5rem 2rem;
                color: #374151;
                line-height: 1.8;
                font-size: 12px;
            }

            .unified-note-item:not(:last-child) {
                border-bottom: 1px solid #f1f5f9;
            }

            .note-body p {
                margin-bottom: 1.25rem;
            }

            .note-body p:last-child {
                margin-bottom: 0;
            }

            .note-body strong {
                color: #0d3b4c;
                font-weight: 700;
            }

            @media (max-width: 768px) {
                .unified-note-item {
                    padding: 1rem;
                    font-size: 11px;
                }
            }
        </style>
    @endif
@endif
