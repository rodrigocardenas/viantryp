@props(['trip'])

<!-- Sticky Header that hides on scroll -->
<div class="preview-sticky-header" id="previewStickyHeader">
    @auth
        <x-preview.auth-header :trip="$trip" />
    @else
        <x-preview.public-header />
    @endauth
</div>