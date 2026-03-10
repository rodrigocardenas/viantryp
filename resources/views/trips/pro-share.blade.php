@extends('layouts.app')

@section('title', $trip->title . ' - Viantryp')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body {
        margin: 0;
        padding: 0;
        background: #f8fafc;
        font-family: 'Inter', sans-serif;
    }
    .public-preview-container {
        padding-top: 10px;
    }
    #preview-root {
        min-height: 100vh;
    }
</style>

<div class="public-preview-header">
    <div class="public-header-content">
        <div class="public-logo">
            <div class="header-logos-container">
                <img src="{{ asset('images/logo-viantryp.png') }}" alt="Viantryp Logo" class="viantryp-logo">
                <img src="{{ asset('images/LOGO GPS.png') }}" alt="GPS Logo" class="gps-logo">
            </div>
        </div>
    </div>
</div>

<div class="public-preview-container">
    <div id="preview-root">
        {{-- Content will be rendered here by JS --}}
    </div>
</div>

<script>
    window.proState = @json($trip->pro_state);
    window.viantrypUserName = "{{ $trip->user->name ?? 'Viantryp' }}";
</script>

<script src="{{ asset('js/trips/pro-viewer.js') }}"></script>
@endsection

@push('styles')
<style>
    /* Add any specific overrides for the shared view */
    .public-preview-header {
        position: sticky;
        top: 0;
        z-index: 1000;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        padding: 12px 0;
    }
    .public-header-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .header-logos-container {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .viantryp-logo { height: 28px; }
    .gps-logo { height: 35px; }
</style>
@endpush
