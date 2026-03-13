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
    $userTheme = auth()->user()->theme_color ?? 'default';
    $currentTheme = $themeColors[$userTheme] ?? $themeColors['default'];
    $isGradient = str_contains($currentTheme, 'gradient');
@endphp

<style>
    :root {
        --primary-blue: {{ $isGradient ? 'transparent' : $currentTheme }};
        --accent: {{ $currentTheme }};
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
    .btn-primary, .btn-success, .btn-create, .avatar, .avatar-big, .topbar, .pvday-pill, .btn-viantryp, [data-theme] .theme-swatch.selected {
        background: {{ $currentTheme }} !important;
        border: none !important;
    }
    .topbar-bg-decorators::before, .topbar-bg-decorators::after {
        background: {{ $currentTheme }} !important;
        opacity: 0.1 !important;
    }
    @else
    .topbar, .pvday-pill, .topbar-bg-decorators::before, .btn-primary, .btn-viantryp, .avatar, .avatar-big {
        background-color: {{ $currentTheme }} !important;
    }
    @endif
</style>
