@extends('layouts.app')

@section('title', 'Viantryp - Vista Previa del Itinerario')

@section('content')
    <!-- Sticky Header that hides on scroll -->
    <div class="preview-sticky-header" id="previewStickyHeader">
        @auth
            <!-- Header for authenticated users -->
            <div class="header">
                <div class="header-content">
                    <div class="logo-container">
                        <a href="{{ route('trips.index') }}" class="viantryp-logo">
                            <i class="fas fa-route"></i>
                            Viantryp
                        </a>
                    </div>
                    <div class="header-right">
                        <div class="nav-actions">
                            <a href="#" class="btn btn-back" onclick="showUnsavedChangesModal()">
                                <i class="fas fa-arrow-left"></i>
                                Volver
                            </a>
                            <button type="button" class="btn btn-share" onclick="shareTrip()">
                                <i class="fas fa-share-alt"></i>
                                Compartir
                            </button>
                            <button type="button" class="btn btn-pdf" onclick="downloadPDF()">
                                <i class="fas fa-file-pdf"></i>
                                Descarga versión PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Simple header for public preview -->
            <div class="public-preview-header">
                <div class="public-header-content">
                    <div class="public-logo">
                        <h1 class="public-logo-text">Viantryp</h1>
                        <p class="public-subtitle">Vista Previa del Itinerario</p>
                    </div>
                    <div class="public-actions">
                        <button onclick="downloadPDF()" class="public-btn-print">
                            <i class="fas fa-file-pdf"></i>
                            Descargar PDF
                        </button>
                    </div>
                </div>
            </div>
        @endauth
    </div>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Trip Info Card -->
        <div class="trip-info-card" id="tripInfo">
            <div class="trip-header">
                <div class="trip-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <div>
                    <h1 class="trip-title">{{ $trip->title ?? 'Viaje sin título' }}</h1>
                    <p class="trip-subtitle">{{ $trip->getFormattedDates() ?? 'Fechas por definir' }}</p>
                    @if(isset($trip->user) && !$isPublicPreview ?? false)
                        <p class="trip-author">Creado por: {{ $trip->user->name }}</p>
                    @endif
                </div>
            </div>

            @if($trip->summary ?? false)
                <div class="trip-summary">
                    <div class="summary-content">
                        <strong>Resumen de tu Viaje</strong><br>
                        {{ $trip->summary }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Timeline Container -->
        <div class="timeline-container">
            <div class="timeline" id="timeline">
                @if(isset($trip) && $trip->days && count($trip->days) > 0)
                    @foreach($trip->days as $day)
                        <div class="day-section">
                            <div class="day-marker">{{ $day->day }}</div>
                            <div class="day-header">
                                <div class="day-date">{{ $day->getFormattedDate() }}</div>
                                <h2 class="day-title">Día {{ $day->day }}</h2>
                                <p class="day-subtitle">{{ $day->getFullDate() }}</p>
                            </div>
                            <div class="timeline-items">
                                @if($day->items && count($day->items) > 0)
                                    @foreach($day->items as $item)
                                        <x-preview-item :item="$item" />
                                    @endforeach
                                @else
                                    <div class="timeline-item">
                                        <div class="item-header">
                                            <div class="item-icon icon-note">
                                                <i class="fas fa-info-circle"></i>
                                            </div>
                                            <div class="item-info">
                                                <div class="item-type">Información</div>
                                                <div class="item-title">Día libre</div>
                                                <div class="item-subtitle">No hay actividades programadas para este día</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h3>No hay días en el itinerario</h3>
                        <p>Agrega días y elementos a tu viaje en el editor.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Contact Button -->
    <div class="contact-button">
        <div class="contact-icon">
            <i class="fas fa-headset"></i>
        </div>
        <div class="contact-text">
            <div class="contact-title">Contacto</div>
            <div class="contact-subtitle">Tu experto en viajes</div>
        </div>
    </div>

    <!-- Floating decorative shapes -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
@endsection

@push('styles')
<style>
    :root {
        --primary-dark: #1f2a44;
        --primary-blue: #0ea5e9;
        --light-blue: #e0f2fe;
        --coral: #FF6B6B;
        --mint: #22c55e;
        --gold: #fbbf24;
        --purple: #a78bfa;
        --orange: #fb923c;
        --light-gray: #F6F9FC;
        --white: #FFFFFF;
        --shadow: rgba(0, 0, 0, 0.08);
        --text-gray: #64748b;
        --border-gray: #e2e8f0;
        --shadow-soft: 0 10px 30px rgba(0,0,0,0.06);
        --shadow-hover: 0 14px 40px rgba(0,0,0,0.08);
        --radius: 16px;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background:
            radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.1) 0%, transparent 50%),
            linear-gradient(180deg, #e6f3fb 0%, #f7fbff 40%, #ffffff 100%);
        min-height: 100vh;
        color: var(--primary-dark);
        line-height: 1.6;
        letter-spacing: 0.1px;
        position: relative;
        overflow-x: hidden;
    }

    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image:
            radial-gradient(circle at 25% 25%, rgba(14, 165, 233, 0.03) 0%, transparent 50%),
            radial-gradient(circle at 75% 75%, rgba(16, 185, 129, 0.03) 0%, transparent 50%);
        pointer-events: none;
        z-index: -1;
    }

    /* Floating decorative elements */
    .floating-shapes {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
        z-index: -1;
        overflow: hidden;
    }

    .shape {
        position: absolute;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.1), rgba(16, 185, 129, 0.1));
        animation: float 20s ease-in-out infinite;
    }

    .shape:nth-child(1) {
        width: 80px;
        height: 80px;
        top: 10%;
        left: 10%;
        animation-delay: 0s;
    }

    .shape:nth-child(2) {
        width: 60px;
        height: 60px;
        top: 60%;
        right: 15%;
        animation-delay: 5s;
    }

    .shape:nth-child(3) {
        width: 100px;
        height: 100px;
        bottom: 20%;
        left: 20%;
        animation-delay: 10s;
    }

    .shape:nth-child(4) {
        width: 40px;
        height: 40px;
        top: 30%;
        right: 30%;
        animation-delay: 15s;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        25% { transform: translateY(-20px) rotate(90deg); }
        50% { transform: translateY(-10px) rotate(180deg); }
        75% { transform: translateY(-30px) rotate(270deg); }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .trip-info-card {
        animation: fadeInUp 0.8s ease-out;
    }

    .day-section {
        animation: fadeInUp 0.6s ease-out;
        animation-fill-mode: both;
    }

    .day-section:nth-child(1) { animation-delay: 0.2s; }
    .day-section:nth-child(2) { animation-delay: 0.4s; }
    .day-section:nth-child(3) { animation-delay: 0.6s; }
    .day-section:nth-child(4) { animation-delay: 0.8s; }
    .day-section:nth-child(5) { animation-delay: 1s; }

    .timeline-item {
        animation: fadeIn 0.5s ease-out;
        animation-fill-mode: both;
    }

    .contact-button {
        animation: fadeInUp 0.8s ease-out 1.2s;
        animation-fill-mode: both;
    }

    .header {
        background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 60%, #93c5fd 100%);
        box-shadow: var(--shadow-soft);
        padding: 1.25rem 2rem;
        color: white;
    }

    .header-content {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo {
        font-size: 1.8rem;
        font-weight: 800;
        color: white;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 0.5rem;
        border-radius: 10px;
    }

    .logo:hover { background: rgba(255,255,255,0.15); transform: translateY(-1px); }

    .logo i { color: #ffffff; }

    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 999px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        font-size: 0.9rem;
        white-space: nowrap;
        box-shadow: var(--shadow-soft);
    }

    .btn:hover { transform: translateY(-1px); box-shadow: var(--shadow-hover); }

    .btn-back { background: var(--light-gray); color: var(--primary-dark); border: 1px solid var(--border-gray); }

    .btn-back:hover { background: var(--primary-blue); color: white; border-color: var(--primary-blue); }

    .btn-primary { background: var(--primary-blue); color: white; }

    .btn-secondary { background: var(--light-gray); color: var(--primary-dark); }

    .main-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    .trip-info-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(248,250,252,0.95) 100%);
        border-radius: var(--radius);
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-soft);
        border: 1px solid rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        position: relative;
        overflow: hidden;
    }

    .trip-info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-blue), var(--mint), var(--gold), var(--purple));
        border-radius: var(--radius) var(--radius) 0 0;
    }

    .trip-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2rem;
        position: relative;
    }

    .trip-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 50%, #93c5fd 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        box-shadow: 0 8px 25px rgba(14, 165, 233, 0.3);
        position: relative;
        z-index: 2;
        transition: all 0.3s ease;
    }

    .trip-icon:hover {
        transform: scale(1.05) rotate(5deg);
        box-shadow: 0 12px 35px rgba(14, 165, 233, 0.4);
    }

    .trip-icon::before {
        content: '';
        position: absolute;
        inset: -2px;
        background: linear-gradient(135deg, #0ea5e9, #38bdf8, #93c5fd);
        border-radius: 22px;
        z-index: -1;
        opacity: 0.3;
    }

    .trip-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--primary-dark);
        margin-bottom: 0.75rem;
        background: linear-gradient(135deg, var(--primary-dark) 0%, #374151 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1.2;
        letter-spacing: -0.02em;
    }

    .trip-subtitle {
        color: var(--text-gray);
        font-size: 1.1rem;
        margin: 0.5rem 0 0.75rem 0;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .trip-subtitle::before {
        content: '📅';
        font-size: 1.2rem;
    }

    .trip-author {
        color: var(--text-gray);
        font-size: 0.95rem;
        margin: 0.5rem 0 0 0;
        font-style: italic;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        opacity: 0.8;
    }

    .trip-author::before {
        content: '👤';
        font-size: 1rem;
    }

    .timeline-container {
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(248,250,252,0.1) 100%);
        position: relative;
        border-radius: var(--radius);
        padding: 1rem;
        backdrop-filter: blur(5px);
    }

    .timeline {
        position: relative;
        padding-left: 4rem;
        display: flex;
        flex-direction: column;
        gap: 2.5rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 2rem;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(to bottom, var(--primary-blue), var(--mint), var(--gold), var(--purple));
        border-radius: 2px;
        box-shadow: 0 0 20px rgba(14, 165, 233, 0.3);
    }

    .day-section {
        position: relative;
        background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(248,250,252,0.95) 100%);
        border-radius: var(--radius);
        padding: 2.5rem;
        box-shadow: var(--shadow-soft);
        border: 1px solid rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .day-section:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.1);
    }

    .day-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-blue), var(--mint), var(--gold));
        opacity: 0.7;
    }

    .day-marker {
        position: absolute;
        left: -3rem;
        top: 1.5rem;
        width: 3rem;
        height: 3rem;
        background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 50%, #93c5fd 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 800;
        font-size: 1.1rem;
        z-index: 2;
        box-shadow: 0 6px 20px rgba(14, 165, 233, 0.4);
        border: 3px solid rgba(255,255,255,0.3);
        transition: all 0.3s ease;
    }

    .day-marker:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 25px rgba(14, 165, 233, 0.5);
    }

    .day-header {
        margin-bottom: 2rem;
        padding-left: 1rem;
        position: relative;
    }

    .day-date {
        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
        color: var(--primary-dark);
        padding: 0.75rem 1.5rem;
        border-radius: 999px;
        font-size: 0.95rem;
        font-weight: 700;
        display: inline-block;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(14, 165, 233, 0.2);
        border: 1px solid rgba(14, 165, 233, 0.1);
        transition: all 0.3s ease;
    }

    .day-date:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
    }

    .day-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--primary-dark);
        margin-bottom: 0.75rem;
        background: linear-gradient(135deg, var(--primary-dark) 0%, #374151 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1.3;
    }

    .day-subtitle {
        color: var(--text-gray);
        font-size: 1.1rem;
        font-weight: 500;
        opacity: 0.9;
    }

    .timeline-items {
        padding-left: 1rem;
    }

    .timeline-item {
        background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(248,250,252,0.95) 100%);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 20px;
        margin-bottom: 2rem;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
        position: relative;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--primary-blue), var(--mint), var(--gold));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .timeline-item:hover {
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12), 0 8px 20px rgba(0, 0, 0, 0.08);
        transform: translateY(-4px) scale(1.02);
    }

    .timeline-item:hover::before {
        opacity: 1;
    }

    .item-header {
        padding: 1.75rem;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
    }

    .item-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        position: relative;
        transition: all 0.3s ease;
        border: 2px solid rgba(255,255,255,0.2);
    }

    .item-icon::before {
        content: '';
        position: absolute;
        inset: -2px;
        background: inherit;
        border-radius: 18px;
        z-index: -1;
        opacity: 0.3;
    }

    .item-icon:hover {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.25);
    }

    .icon-flight {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 50%, #1e40af 100%);
    }

    .icon-hotel {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 50%, #b45309 100%);
    }

    .icon-activity {
        background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
    }

    .icon-transport {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 50%, #6d28d9 100%);
    }

    .icon-note {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 50%, #374151 100%);
    }

    .item-info {
        flex: 1;
    }

    .item-type {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #6b7280;
        margin-bottom: 0.5rem;
        font-family: 'Inter', sans-serif;
    }

    .item-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.25rem;
        line-height: 1.4;
    }

    .item-subtitle {
        color: #6b7280;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .item-toggle {
        background: rgba(107, 114, 128, 0.1);
        border: 1px solid rgba(107, 114, 128, 0.2);
        color: #6b7280;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 8px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-family: 'Inter', sans-serif;
    }

    .item-toggle:hover {
        background: rgba(107, 114, 128, 0.15);
        border-color: rgba(107, 114, 128, 0.3);
        color: #374151;
        transform: scale(1.05);
    }

    .item-content { padding: 1.5rem; background: var(--light-gray); }

    .flight-details {
        background: var(--white);
        border-radius: 12px;
        padding: 2rem;
        border: 1px solid var(--border-gray);
    }

    .flight-route {
        display: flex;
        align-items: center;
        gap: 2rem;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--border-gray);
    }

    .flight-segment {
        flex: 1;
        text-align: center;
    }

    .flight-time {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 0.5rem;
    }

    .flight-airport {
        font-size: 0.9rem;
        color: var(--text-gray);
        line-height: 1.4;
    }

    .flight-path {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 120px;
    }

    .flight-line {
        position: absolute;
        width: 100%;
        height: 2px;
        background: var(--border-gray);
        border-radius: 1px;
    }

    .flight-plane {
        position: absolute;
        left: 0;
        font-size: 1.2rem;
        background: var(--white);
        padding: 0.25rem;
        border-radius: 50%;
    }

    .flight-destination {
        position: absolute;
        right: 0;
        font-size: 1.2rem;
        background: var(--white);
        padding: 0.25rem;
        border-radius: 50%;
    }

    .flight-sections {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .flight-section {
        border-bottom: 1px solid var(--border-gray);
        padding-bottom: 1.5rem;
    }

    .flight-section:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 1rem;
    }

    .reservation-details {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .reservation-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .reservation-label {
        font-weight: 600;
        color: var(--primary-dark);
        min-width: 120px;
    }

    .reservation-value {
        color: var(--text-gray);
    }

    .baggage-link {
        color: var(--primary-blue);
        text-decoration: none;
        font-weight: 500;
    }

    .baggage-link:hover {
        text-decoration: underline;
    }

    .additional-details {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .document-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .document-link i {
        color: var(--text-gray);
        font-size: 0.9rem;
    }

    .document-link a {
        color: var(--primary-blue);
        text-decoration: none;
        font-weight: 500;
    }

    .document-link a:hover {
        text-decoration: underline;
    }

    .item-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .detail-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background: var(--white);
        border-radius: 8px;
        border: 1px solid var(--border-gray);
    }

    .detail-icon-small {
        width: 24px;
        height: 24px;
        background: var(--light-blue);
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-blue);
        font-size: 0.8rem;
    }

    .detail-text-small {
        flex: 1;
    }

    .detail-label-small {
        font-size: 0.8rem;
        color: var(--text-gray);
        margin-bottom: 0.1rem;
    }

    .detail-value-small {
        font-weight: 600;
        color: var(--primary-dark);
        font-size: 0.9rem;
    }

    /* Hotel Gallery Styles */
    .hotel-gallery {
        grid-column: 1 / -1;
        margin-bottom: 1rem;
    }

    .gallery-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .gallery-title::before {
        content: '📸';
        font-size: 1.2rem;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .gallery-item {
        position: relative;
        aspect-ratio: 4/3;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 2px solid transparent;
    }

    .gallery-item:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border-color: var(--primary-blue);
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .gallery-item:hover img {
        transform: scale(1.1);
    }

    .more-images {
        position: relative;
    }

    .more-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
    }

    .more-overlay span {
        color: white;
        font-size: 1.2rem;
        font-weight: 600;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    }

    .hotel-rating-section {
        grid-column: 1 / -1;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }

    .rating-score {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--success);
    }

    .rating-word {
        font-weight: 500;
        color: var(--primary-dark);
    }

    .review-count {
        font-size: 0.85rem;
        color: var(--text-gray);
    }

    .star-filled {
        color: #fbbf24;
        margin-right: 2px;
    }

    .contact-button {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(229, 231, 235, 0.8);
        border-radius: 16px;
        padding: 1rem 1.5rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1), 0 4px 10px rgba(0, 0, 0, 0.06);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1000;
        backdrop-filter: blur(10px);
    }

    .contact-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15), 0 8px 16px rgba(0, 0, 0, 0.1);
        background: rgba(255, 255, 255, 1);
    }

    .contact-icon {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .contact-text {
        text-align: left;
    }

    .contact-title {
        font-weight: 600;
        color: var(--primary-dark);
        font-size: 0.9rem;
    }

    .contact-subtitle {
        color: var(--text-gray);
        font-size: 0.8rem;
    }

    .loading {
        text-align: center;
        padding: 3rem;
        color: var(--text-gray);
    }

    .loading i {
        font-size: 2rem;
        margin-bottom: 1rem;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .error {
        text-align: center;
        padding: 3rem;
        color: var(--coral);
    }

    .error i {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    /* Trip Summary Styles */
    .trip-summary {
        background: linear-gradient(135deg, rgba(240,249,255,0.8) 0%, rgba(219,234,254,0.8) 100%);
        border-radius: 16px;
        padding: 2rem;
        margin: 1.5rem 0;
        border-left: 5px solid var(--primary-blue);
        box-shadow: 0 4px 16px rgba(14, 165, 233, 0.1);
        backdrop-filter: blur(5px);
        position: relative;
        overflow: hidden;
    }

    .trip-summary::before {
        content: '💡';
        position: absolute;
        top: 1rem;
        right: 1.5rem;
        font-size: 2rem;
        opacity: 0.3;
    }

    .summary-content {
        font-size: 1rem;
        line-height: 1.7;
        color: var(--primary-dark);
        position: relative;
        z-index: 1;
    }

    .summary-content strong {
        color: var(--primary-blue);
        font-weight: 700;
        background: linear-gradient(135deg, var(--primary-blue), var(--blue-600));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .summary-content br {
        margin-bottom: 0.75rem;
    }

    .summary-content em {
        color: var(--text-gray);
        font-style: italic;
        font-weight: 500;
    }

    /* Sticky Header Styles */
    .preview-sticky-header {
        position: sticky;
        top: 0;
        z-index: 1000;
        transition: transform 0.3s ease;
    }

    .preview-sticky-header.hidden {
        transform: translateY(-100%);
    }

    /* Header for authenticated users */
    .header {
        background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 60%, #93c5fd 100%);
        color: white;
        padding: 1.25rem 2rem;
        box-shadow: var(--shadow-soft);
    }

    .header-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .viantryp-logo {
        color: white;
        text-decoration: none;
        font-size: 1.5rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .viantryp-logo i {
        font-size: 1.8rem;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .nav-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .btn {
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid transparent;
        cursor: pointer;
        font-size: 0.875rem;
        font-family: 'Inter', sans-serif;
        letter-spacing: 0.025em;
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.4);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-save {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }

    .btn-save:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.4);
    }

    .btn-preview {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
    }

    .btn-preview:hover {
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(245, 158, 11, 0.4);
    }

    .btn-pdf {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }

    .btn-pdf:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(239, 68, 68, 0.4);
    }

    .btn-share {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }

    .btn-share:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.4);
    }

    .auth-section {
        display: flex;
        align-items: center;
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: rgba(255, 255, 255, 0.1);
        padding: 0.5rem 1rem;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .user-avatar-placeholder {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.9rem;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .user-name {
        color: white;
        font-weight: 500;
        font-size: 0.9rem;
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .btn-logout {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        border: 1px solid rgba(239, 68, 68, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 0.375rem;
        font-family: 'Inter', sans-serif;
    }

    .btn-logout:hover {
        background: rgba(239, 68, 68, 0.15);
        border-color: rgba(239, 68, 68, 0.3);
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);
    }

    /* Public Preview Header Styles */
    .public-preview-header {
        background: linear-gradient(135deg, #1f2a44 0%, #0ea5e9 100%);
        color: white;
        padding: 1.5rem 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .public-header-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .public-logo {
        display: flex;
        flex-direction: column;
    }

    .public-logo-text {
        font-size: 2rem;
        font-weight: bold;
        margin: 0;
        background: linear-gradient(45deg, #fff, #e0f2fe);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .public-subtitle {
        font-size: 0.9rem;
        margin: 0.25rem 0 0 0;
        opacity: 0.9;
        font-weight: 300;
    }

    .public-actions {
        display: flex;
        gap: 1rem;
    }

    .public-btn-print {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.25);
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        font-size: 0.875rem;
        font-family: 'Inter', sans-serif;
        backdrop-filter: blur(10px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .public-btn-print:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.4);
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }

    @media (max-width: 768px) {
        .header-content {
            padding: 0 1rem;
            flex-direction: column;
            gap: 1rem;
        }

        .nav-actions {
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
            border-radius: 8px;
        }

        .user-profile {
            padding: 0.4rem 0.8rem;
        }

        .user-name {
            display: none;
        }

        .btn-logout {
            padding: 0.3rem 0.6rem;
            font-size: 0.75rem;
        }

        .main-container {
            padding: 1rem;
        }

        .trip-info-card {
            padding: 1.5rem;
        }

        .trip-icon {
            width: 60px;
            height: 60px;
            font-size: 1.8rem;
        }

        .trip-title {
            font-size: 2rem;
        }

        .timeline {
            padding-left: 3rem;
        }

        .day-marker {
            left: -2rem;
            width: 2rem;
            height: 2rem;
            font-size: 0.9rem;
        }

        .day-section {
            padding: 1.5rem;
        }

        .day-title {
            font-size: 1.4rem;
        }

        .item-icon {
            width: 48px;
            height: 48px;
            font-size: 1.2rem;
        }

        .item-details {
            grid-template-columns: 1fr;
        }

        .contact-button {
            bottom: 1rem;
            right: 1rem;
            padding: 0.75rem 1rem;
        }

        .contact-text {
            display: none;
        }

        .floating-shapes {
            display: none;
        }

        .public-header-content {
            padding: 0 1rem;
            flex-direction: column;
            gap: 1rem;
        }

        .public-logo-text {
            font-size: 1.5rem;
        }

        .public-subtitle {
            font-size: 0.8rem;
        }

        .public-actions {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function downloadPDF() {
        const tripId = {{ $trip->id ?? 'null' }};
        const token = '{{ request()->route("token") ?? "" }}';

        if (!tripId) {
            showNotification('Error', 'No se puede descargar el PDF de este viaje.', 'error');
            return;
        }

        // Show loading state - try both button selectors
        const pdfBtn = document.querySelector('.btn-pdf') || document.querySelector('.public-btn-print');
        const originalText = pdfBtn.innerHTML;
        pdfBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando PDF...';
        pdfBtn.disabled = true;

        try {
            // Create a temporary link to trigger download
            const link = document.createElement('a');
            const url = token ? `/trips/${tripId}/pdf?token=${token}` : `/trips/${tripId}/pdf`;
            link.href = url;
            link.download = 'itinerario.pdf';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            showNotification('PDF generado', 'El PDF del itinerario se está descargando.', 'success');

            // Reset button
            pdfBtn.innerHTML = originalText;
            pdfBtn.disabled = false;
        } catch (error) {
            console.error('PDF download error:', error);
            showNotification('Error', 'No se pudo generar el PDF.', 'error');

            // Reset button
            pdfBtn.innerHTML = originalText;
            pdfBtn.disabled = false;
        }
    }

    async function shareTrip() {
        const tripId = {{ $trip->id ?? 'null' }};

        if (!tripId) {
            showNotification('Error', 'No se puede compartir este viaje.', 'error');
            return;
        }

        try {
            // Show loading state
            const shareBtn = document.querySelector('.btn-share');
            const originalText = shareBtn.innerHTML;
            shareBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando enlace...';
            shareBtn.disabled = true;

            // Generate share token via AJAX
            const response = await fetch(`/trips/${tripId}/generate-share-token`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });

            const data = await response.json();

            if (data.success) {
                // Show share modal with the URL
                showShareModal(data.share_url);

                // Reset button
                shareBtn.innerHTML = originalText;
                shareBtn.disabled = false;
            } else {
                throw new Error(data.message || 'Error al generar el enlace');
            }
        } catch (error) {
            console.error('Share error:', error);
            showNotification('Error', error.message || 'No se pudo generar el enlace de compartición.', 'error');

            // Reset button
            const shareBtn = document.querySelector('.btn-share');
            shareBtn.innerHTML = '<i class="fas fa-share-alt"></i> Compartir';
            shareBtn.disabled = false;
        }
    }

    function showShareModal(shareUrl) {
        // Remove existing modal if present
        const existingModal = document.getElementById('shareModal');
        if (existingModal) {
            existingModal.remove();
        }

        // Create modal HTML
        const modalHtml = `
            <div id="shareModal" class="share-modal-overlay" style="
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10000;
                font-family: 'Poppins', sans-serif;
            ">
                <div class="share-modal" style="
                    background: white;
                    border-radius: 16px;
                    padding: 2rem;
                    max-width: 500px;
                    width: 90%;
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
                    position: relative;
                ">
                    <div class="share-modal-header" style="
                        text-align: center;
                        margin-bottom: 1.5rem;
                    ">
                        <h3 style="
                            font-size: 1.5rem;
                            font-weight: 700;
                            color: #1f2937;
                            margin: 0 0 0.5rem 0;
                        ">Compartir Itinerario</h3>
                        <p style="
                            color: #6b7280;
                            margin: 0;
                            font-size: 0.9rem;
                        ">Copia el enlace para compartir este viaje</p>
                    </div>

                    <div class="share-modal-body">
                        <div class="share-url-container" style="
                            margin-bottom: 1.5rem;
                        ">
                            <label style="
                                display: block;
                                font-size: 0.85rem;
                                font-weight: 600;
                                color: #374151;
                                margin-bottom: 0.5rem;
                            ">Enlace de compartición:</label>
                            <div class="share-url-input-group" style="
                                display: flex;
                                gap: 0.5rem;
                            ">
                                <input type="text" id="shareUrlInput" value="${shareUrl}" readonly style="
                                    flex: 1;
                                    padding: 0.75rem;
                                    border: 1px solid #d1d5db;
                                    border-radius: 8px;
                                    font-size: 0.9rem;
                                    background: #f9fafb;
                                    color: #374151;
                                    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
                                ">
                                <button id="copyShareUrlBtn" style="
                                    padding: 0.75rem 1rem;
                                    background: linear-gradient(135deg, #10b981, #059669);
                                    color: white;
                                    border: none;
                                    border-radius: 8px;
                                    cursor: pointer;
                                    font-weight: 600;
                                    display: flex;
                                    align-items: center;
                                    gap: 0.5rem;
                                    transition: all 0.3s ease;
                                ">
                                    <i class="fas fa-copy"></i>
                                    Copiar
                                </button>
                            </div>
                        </div>

                        <div class="share-modal-actions" style="
                            display: flex;
                            gap: 0.75rem;
                            justify-content: flex-end;
                        ">
                            <button id="closeShareModalBtn" style="
                                padding: 0.625rem 1.25rem;
                                background: #f3f4f6;
                                color: #374151;
                                border: 1px solid #d1d5db;
                                border-radius: 8px;
                                cursor: pointer;
                                font-weight: 500;
                                transition: all 0.3s ease;
                            ">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        // Get modal elements
        const modal = document.getElementById('shareModal');
        const urlInput = document.getElementById('shareUrlInput');
        const copyBtn = document.getElementById('copyShareUrlBtn');
        const closeBtn = document.getElementById('closeShareModalBtn');

        // Auto-select the URL
        setTimeout(() => {
            urlInput.select();
            urlInput.focus();
        }, 100);

        // Copy button functionality
        copyBtn.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(shareUrl);
                copyBtn.innerHTML = '<i class="fas fa-check"></i> ¡Copiado!';
                copyBtn.style.background = 'linear-gradient(135deg, #059669, #047857)';

                // Reset button after 2 seconds
                setTimeout(() => {
                    copyBtn.innerHTML = '<i class="fas fa-copy"></i> Copiar';
                    copyBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                }, 2000);
            } catch (error) {
                // Fallback for older browsers
                urlInput.select();
                document.execCommand('copy');
                copyBtn.innerHTML = '<i class="fas fa-check"></i> ¡Copiado!';
                copyBtn.style.background = 'linear-gradient(135deg, #059669, #047857)';

                setTimeout(() => {
                    copyBtn.innerHTML = '<i class="fas fa-copy"></i> Copiar';
                    copyBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                }, 2000);
            }
        });

        // Close modal functionality
        closeBtn.addEventListener('click', () => {
            modal.remove();
        });

        // Close on overlay click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function closeOnEscape(e) {
            if (e.key === 'Escape') {
                modal.remove();
                document.removeEventListener('keydown', closeOnEscape);
            }
        });
    }

    function toggleItemContent(button) {
        const item = button.closest('.timeline-item');
        const content = item.querySelector('.item-content');
        const icon = button.querySelector('i');

        if (content.style.display === 'none') {
            content.style.display = 'block';
            icon.className = 'fas fa-chevron-up';
        } else {
            content.style.display = 'none';
            icon.className = 'fas fa-chevron-down';
        }
    }

    // Header hide on scroll functionality
    let lastScrollTop = 0;
    const header = document.getElementById('previewStickyHeader');

    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > lastScrollTop && scrollTop > 100) {
            // Scrolling down and past 100px
            header.classList.add('hidden');
        } else {
            // Scrolling up or at top
            header.classList.remove('hidden');
        }

        lastScrollTop = scrollTop;
    });

    // Hotel Gallery Modal Functionality
    let currentHotelImages = [];
    let currentImageIndex = 0;

    function openHotelGallery(hotelId, startIndex = 0) {
        // Find the hotel item with the matching ID
        const hotelItems = document.querySelectorAll('.timeline-item');
        let hotelData = null;

        hotelItems.forEach(item => {
            if (item.classList.contains('hotel')) {
                const itemData = JSON.parse(item.dataset.hotelData || '{}');
                if (itemData.id == hotelId) {
                    hotelData = itemData;
                }
            }
        });

        if (!hotelData || !hotelData.images || hotelData.images.length === 0) {
            return;
        }

        currentHotelImages = hotelData.images;
        currentImageIndex = startIndex;

        showHotelGalleryModal(hotelData.name || 'Hotel');
    }

    function showHotelGalleryModal(hotelName) {
        // Create modal HTML
        const modalHtml = `
            <div id="hotelGalleryModal" class="gallery-modal" style="
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.9);
                z-index: 10000;
                display: flex;
                align-items: center;
                justify-content: center;
                animation: fadeIn 0.3s ease;
            ">
                <div class="gallery-modal-content" style="
                    position: relative;
                    max-width: 90vw;
                    max-height: 90vh;
                    background: white;
                    border-radius: 12px;
                    overflow: hidden;
                    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
                ">
                    <div class="gallery-modal-header" style="
                        padding: 1rem 1.5rem;
                        background: var(--primary-dark, #1f2a44);
                        color: white;
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                    ">
                        <h3 style="margin: 0; font-size: 1.2rem;">${hotelName} - Galería de Fotos</h3>
                        <button onclick="closeHotelGallery()" style="
                            background: none;
                            border: none;
                            color: white;
                            font-size: 1.5rem;
                            cursor: pointer;
                            padding: 0.25rem;
                            border-radius: 4px;
                            transition: background 0.3s ease;
                        " onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='none'">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="gallery-modal-body" style="
                        position: relative;
                        background: black;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        min-height: 400px;
                    ">
                        <img id="galleryMainImage" src="${currentHotelImages[currentImageIndex]}" alt="Hotel image" style="
                            max-width: 100%;
                            max-height: 70vh;
                            object-fit: contain;
                        ">
                        ${currentHotelImages.length > 1 ? `
                            <button onclick="prevHotelImage()" class="gallery-nav-btn" style="
                                position: absolute;
                                left: 1rem;
                                top: 50%;
                                transform: translateY(-50%);
                                background: rgba(0, 0, 0, 0.7);
                                color: white;
                                border: none;
                                width: 50px;
                                height: 50px;
                                border-radius: 50%;
                                cursor: pointer;
                                font-size: 1.2rem;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                transition: background 0.3s ease;
                            " onmouseover="this.style.background='rgba(0,0,0,0.9)'" onmouseout="this.style.background='rgba(0,0,0,0.7)'">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button onclick="nextHotelImage()" class="gallery-nav-btn" style="
                                position: absolute;
                                right: 1rem;
                                top: 50%;
                                transform: translateY(-50%);
                                background: rgba(0, 0, 0, 0.7);
                                color: white;
                                border: none;
                                width: 50px;
                                height: 50px;
                                border-radius: 50%;
                                cursor: pointer;
                                font-size: 1.2rem;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                transition: background 0.3s ease;
                            " onmouseover="this.style.background='rgba(0,0,0,0.9)'" onmouseout="this.style.background='rgba(0,0,0,0.7)'">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        ` : ''}
                    </div>
                    <div class="gallery-modal-footer" style="
                        padding: 1rem 1.5rem;
                        background: #f8f9fa;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        gap: 0.5rem;
                    ">
                        <span id="galleryImageCounter" style="
                            font-size: 0.9rem;
                            color: #6c757d;
                        ">${currentImageIndex + 1} de ${currentHotelImages.length}</span>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHtml);

        // Add keyboard navigation
        document.addEventListener('keydown', handleGalleryKeydown);
    }

    function closeHotelGallery() {
        const modal = document.getElementById('hotelGalleryModal');
        if (modal) {
            modal.remove();
        }
        document.removeEventListener('keydown', handleGalleryKeydown);
    }

    function nextHotelImage() {
        currentImageIndex = (currentImageIndex + 1) % currentHotelImages.length;
        updateGalleryImage();
    }

    function prevHotelImage() {
        currentImageIndex = currentImageIndex === 0 ? currentHotelImages.length - 1 : currentImageIndex - 1;
        updateGalleryImage();
    }

    function updateGalleryImage() {
        const mainImage = document.getElementById('galleryMainImage');
        const counter = document.getElementById('galleryImageCounter');

        if (mainImage) {
            mainImage.src = currentHotelImages[currentImageIndex];
        }
        if (counter) {
            counter.textContent = `${currentImageIndex + 1} de ${currentHotelImages.length}`;
        }
    }

    function handleGalleryKeydown(e) {
        if (e.key === 'Escape') {
            closeHotelGallery();
        } else if (e.key === 'ArrowRight') {
            nextHotelImage();
        } else if (e.key === 'ArrowLeft') {
            prevHotelImage();
        }
    }
</script>
@endpush
