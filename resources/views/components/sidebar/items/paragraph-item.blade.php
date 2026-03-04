@props(['disabled' => false])

<x-sidebar.items.base
    type="text"
    title="Texto"
    description="Párrafo libre"
    :disabled="$disabled"
>
    <x-slot name="icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="17" y1="10" x2="3" y2="10"/>
            <line x1="21" y1="6" x2="3" y2="6"/>
            <line x1="21" y1="14" x2="3" y2="14"/>
            <line x1="17" y1="18" x2="3" y2="18"/>
        </svg>
    </x-slot>
</x-sidebar.items.base>
