@php
    $themeColors = [
        'default' => '#1a7f77',
        'ocean' => '#1a5f8f',
        'gold' => '#b08000',
        'sunset' => '#c0552a',
        'blush' => 'linear-gradient(135deg,#e07b9a,#f4a5bd)',
        'silver' => 'linear-gradient(135deg,#6e7f80,#9aa8a9)',
        'mint' => 'linear-gradient(135deg,#3db898,#62d4b5)',
        'lavender' => 'linear-gradient(135deg,#9b72cf,#b39ddb)'
    ];

    $avatarGradients = [
        'default' => 'linear-gradient(135deg, #1a7f77, #10a6b1)',
        'ocean' => 'linear-gradient(135deg, #1a5f8f, #2a7fb9)',
        'gold' => 'linear-gradient(135deg, #b08000, #d4a017)',
        'sunset' => 'linear-gradient(135deg, #c0552a, #d35400)',
    ];

    $themeAccents = [
        'default'  => ['light' => '#f0faf9', 'border' => 'rgba(26,154,138,0.2)'],
        'ocean'    => ['light' => '#f0f7fa', 'border' => 'rgba(26,95,143,0.2)'],
        'gold'     => ['light' => '#faf9f0', 'border' => 'rgba(176,128,0,0.2)'],
        'sunset'   => ['light' => '#faf4f0', 'border' => 'rgba(192,85,42,0.2)'],
        'blush'    => ['light' => '#faf0f4', 'border' => 'rgba(224,123,154,0.2)'],
        'silver'   => ['light' => '#f4f6f6', 'border' => 'rgba(110,127,128,0.2)'],
        'mint'     => ['light' => '#f0faf6', 'border' => 'rgba(61,184,152,0.2)'],
        'lavender' => ['light' => '#f6f0fa', 'border' => 'rgba(155,114,207,0.2)'],
    ];

    $userTheme = auth()->user()->theme_color ?? 'default';
    $currentTheme = $themeColors[$userTheme] ?? $themeColors['default'];
    $currentAccent = $themeAccents[$userTheme] ?? $themeAccents['default'];
    $isGradient = str_contains($currentTheme, 'gradient');
    $avatarBg = $isGradient ? $currentTheme : ($avatarGradients[$userTheme] ?? $currentTheme);
@endphp

<style>
    :root {
        --primary-blue: {{ $isGradient ? 'transparent' : $currentTheme }};
        --accent: {{ $currentTheme }};
        --accent-light: {{ $currentAccent['light'] }};
        --accent-border: {{ $currentAccent['border'] }};
        --avatar-gradient: {{ $avatarBg }};
        @if(!$isGradient)
        --blue-700: {{ $currentTheme }};
        --teal: {{ $currentTheme }};
        --teal2: {{ $currentTheme }};
        @else
        --blue-700: #1a7f77; {{-- Fallback --}}
        --teal: #1a7f77;
        @endif
    }

    @if($isGradient)
    .btn-primary, .btn-success, .btn-create, .topbar, .pvday-pill, .btn-viantryp, [data-theme] .theme-swatch.selected {
        background: {{ $currentTheme }} !important;
        border: none !important;
    }
    .avatar, .avatar-big {
        background: var(--avatar-gradient) !important;
        border: none !important;
    }
    .topbar-bg-decorators::before, .topbar-bg-decorators::after {
        background: {{ $currentTheme }} !important;
        opacity: 0.1 !important;
    }
    @else
    .topbar, .pvday-pill, .topbar-bg-decorators::before, .btn-primary, .btn-viantryp {
        background-color: {{ $currentTheme }} !important;
    }
    .avatar, .avatar-big {
        background: var(--avatar-gradient) !important;
    }
    @endif
</style>
