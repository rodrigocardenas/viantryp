@props(['item'])

@php
    function renderTextElement($item) {
        $type = $item['type'] ?? '';
        $data = $item['data'] ?? [];

        if ($type === 'title') {
            $content = $data['content'] ?? $data['title_content'] ?? '';
            if (empty(trim($content))) return '';
            return '<h2 class="text-card-title">' . nl2br(htmlspecialchars($content)) . '</h2>';
        }

        if ($type === 'paragraph') {
            $content = $data['content'] ?? $data['paragraph_content'] ?? '';
            if (empty(trim($content))) return '';
            return '<p class="text-card-paragraph">' . nl2br(htmlspecialchars($content)) . '</p>';
        }

        if ($type === 'extra') {
            $title = $data['extra_title'] ?? 'Información Extra';
            $content = $data['extra_content'] ?? '';
            if (empty(trim($content)) && empty(trim($title))) return '';
            
            $html = '<div class="text-card-extra">';
            if (!empty(trim($title))) {
                $html .= '<h3 class="extra-title">' . htmlspecialchars($title) . '</h3>';
            }
            if (!empty(trim($content))) {
                $html .= '<p class="extra-content">' . nl2br(htmlspecialchars($content)) . '</p>';
            }
            $html .= '</div>';
            return $html;
        }

        return '';
    }

    $htmlContent = renderTextElement($item);
@endphp

@if(!empty($htmlContent))
    <div class="text-card-container">
        {!! $htmlContent !!}
    </div>

    @pushOnce('styles')
    <style>
        .text-card-container {
            background: #ffffff;
            border-radius: 1rem;
            padding: 1.5rem 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .text-card-container:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .text-card-title {
            color: #0d3b4c;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.3;
        }

        .text-card-paragraph {
            color: #4b5563;
            font-size: 1rem;
            line-height: 1.7;
            margin: 0;
        }

        .text-card-extra .extra-title {
            color: #0d3b4c;
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0 0 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .text-card-extra .extra-title::before {
            content: "\f05a"; /* Info circle icon */
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            color: #61c5ed;
            font-size: 1.1rem;
        }

        .text-card-extra .extra-content {
            color: #4b5563;
            font-size: 0.95rem;
            line-height: 1.6;
            margin: 0;
            background: #f8fafc;
            padding: 1rem;
            border-radius: 0.5rem;
            border-left: 3px solid #61c5ed;
        }

        @media (max-width: 768px) {
            .text-card-container {
                padding: 1.25rem;
                border-radius: 0.75rem;
            }
            .text-card-title {
                font-size: 1.25rem;
            }
            .text-card-paragraph {
                font-size: 0.95rem;
            }
        }
    </style>
    @endPushOnce
@endif
