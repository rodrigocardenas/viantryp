@props(['trip'])

@if(isset($trip) && $trip->items_data)
    @php
        $globalNotes = array_filter($trip->items_data, function($item) {
            return isset($item['type']) && $item['type'] === 'note' && (!isset($item['day']) || $item['day'] === null);
        });
    @endphp

    @if(count($globalNotes) > 0)
        <div class="global-notes-section">
           

            <div class="notes-list">
                @foreach($globalNotes as $index => $note)
                    @php
                        $noteContent = $note['note_content'] ?? '';
                        $noteLength = strlen(strip_tags($noteContent));
                        $isLongNote = $noteLength > 100;
                        $noteId = 'note-' . $index;
                    @endphp

                    <div class="note-item {{ $isLongNote ? 'note-expandable' : '' }}" data-note-id="{{ $noteId }}">
                        <div class="note-content {{ $isLongNote ? 'note-collapsed' : '' }}" id="{{ $noteId }}">
                            {!! $noteContent !!}
                        </div>

                        @if($isLongNote)
                            <button
                                class="note-toggle-btn"
                                onclick="toggleNote('{{ $noteId }}')"
                                aria-label="Expandir nota"
                            >
                                <span class="toggle-text-expand">Ver más</span>
                                <span class="toggle-text-collapse" style="display: none;">Ver menos</span>
                                <i class="fas fa-chevron-down toggle-icon"></i>
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <script>
            function toggleNote(noteId) {
                const noteContent = document.getElementById(noteId);
                const noteItem = noteContent.closest('.note-item');
                const toggleBtn = noteItem.querySelector('.note-toggle-btn');
                const expandText = toggleBtn.querySelector('.toggle-text-expand');
                const collapseText = toggleBtn.querySelector('.toggle-text-collapse');
                const icon = toggleBtn.querySelector('.toggle-icon');

                if (noteContent.classList.contains('note-collapsed')) {
                    noteContent.classList.remove('note-collapsed');
                    noteContent.classList.add('note-expanded');
                    expandText.style.display = 'none';
                    collapseText.style.display = 'inline';
                    icon.style.transform = 'rotate(180deg)';
                } else {
                    noteContent.classList.remove('note-expanded');
                    noteContent.classList.add('note-collapsed');
                    expandText.style.display = 'inline';
                    collapseText.style.display = 'none';
                    icon.style.transform = 'rotate(0deg)';
                }
            }
        </script>

        <style>
            .global-notes-section {
                background: #ffffff;
                border-radius: 12px;
                padding: 1.5rem;
                margin-bottom: 2rem;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                border: 1px solid #e5e7eb;
            }

            .notes-header {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                margin-bottom: 1.25rem;
                padding-bottom: 0.75rem;
                border-bottom: 2px solid #f3f4f6;
            }

            .notes-icon {
                color: #f59e0b;
                font-size: 1.25rem;
            }

            .notes-header h3 {
                margin: 0;
                color: #1f2937;
                font-size: 1.25rem;
                font-weight: 600;
                font-family: 'Poppins', sans-serif;
            }

            .notes-list {
                display: flex;
                flex-direction: column;
                gap: 1rem;
            }

            .note-item {
                background: #f9fafb;
                border-radius: 8px;
                padding: 1rem;
                border-left: 4px solid #fbbf24;
                position: relative;
            }

            .note-content {
                color: #374151;
                line-height: 1.6;
                font-size: 0.95rem;
                overflow: hidden;
                transition: max-height 0.3s ease-in-out;
            }

            .note-content.note-collapsed {
                max-height: 120px;
                position: relative;
            }

            .note-content.note-collapsed::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                width: 100%;
                height: 40px;
                background: linear-gradient(to bottom, transparent, #f9fafb);
            }

            .note-content.note-expanded {
                max-height: none;
            }

            .note-content p {
                margin: 0 0 0.5rem 0;
            }

            .note-content p:last-child {
                margin-bottom: 0;
            }

            .note-content ul, .note-content ol {
                margin: 0.5rem 0;
                padding-left: 1.5rem;
            }

            .note-content li {
                margin-bottom: 0.25rem;
            }

            .note-content strong {
                font-weight: 600;
                color: #1f2937;
            }

            .note-content em {
                font-style: italic;
            }

            .note-content a {
                color: #3b82f6;
                text-decoration: underline;
            }

            .note-toggle-btn {
                background: none;
                border: none;
                color: #6b7280;
                font-size: 0.875rem;
                cursor: pointer;
                padding: 0.5rem 0 0 0;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                transition: color 0.2s ease;
                font-family: 'Poppins', sans-serif;
                margin-top: 0.5rem;
            }

            .note-toggle-btn:hover {
                color: #374151;
            }

            .toggle-icon {
                font-size: 0.75rem;
                transition: transform 0.3s ease;
            }

            @media (max-width: 768px) {
                .global-notes-section {
                    padding: 1rem;
                    margin-bottom: 1.5rem;
                }

                .notes-header h3 {
                    font-size: 1.1rem;
                }

                .note-item {
                    padding: 0.875rem;
                }

                .note-content {
                    font-size: 0.9rem;
                }
            }
        </style>
    @endif
@endif
