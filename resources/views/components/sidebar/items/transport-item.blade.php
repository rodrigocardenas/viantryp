@props(['disabled' => false])

<x-sidebar.items.base
    type="transport"
    title="Traslado"
    description="Bus, tren u otro"
    :disabled="$disabled"
>
    <x-slot name="icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="1" y="3" width="15" height="13" rx="2"/>
            <path d="M16 8h4l3 5v3h-7V8z"/>
            <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
        </svg>
    </x-slot>
</x-sidebar.items.base>
