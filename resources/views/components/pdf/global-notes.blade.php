@props(['trip'])

@if(isset($trip) && $trip->notes && count($trip->notes) > 0)
    <div class="global-notes-section-pdf">
        <h3 class="notes-section-title">Notas Generales</h3>
        @foreach($trip->notes as $note)
            <div class="note-item-pdf">
                <div class="note-icon-wrapper">
                    <i class="note-icon">📝</i>
                </div>
                <div class="note-content-pdf">
                    {!! $note->note_content ?? $note->getSubtitle() !!}
                </div>
            </div>
        @endforeach
    </div>

    <style>
        .global-notes-section-pdf {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .notes-section-title {
            font-size: 16px;
            font-weight: bold;
            color: #f59e0b;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #fbbf24;
        }

        .note-item-pdf {
            background: #fffbeb;
            border-left: 4px solid #fbbf24;
            padding: 12px 15px;
            margin-bottom: 12px;
            border-radius: 0 6px 6px 0;
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .note-icon-wrapper {
            flex-shrink: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .note-icon {
            font-size: 18px;
            font-style: normal;
        }

        .note-content-pdf {
            flex: 1;
            font-size: 11px;
            line-height: 1.5;
            color: #374151;
        }

        .note-content-pdf p {
            margin: 0 0 8px 0;
        }

        .note-content-pdf p:last-child {
            margin-bottom: 0;
        }

        .note-content-pdf ul,
        .note-content-pdf ol {
            margin: 8px 0;
            padding-left: 20px;
        }

        .note-content-pdf li {
            margin-bottom: 4px;
        }

        .note-content-pdf strong {
            font-weight: 600;
            color: #1f2937;
        }

        .note-content-pdf em {
            font-style: italic;
        }

        .note-content-pdf a {
            color: #3b82f6;
            text-decoration: underline;
        }
    </style>
@endif
