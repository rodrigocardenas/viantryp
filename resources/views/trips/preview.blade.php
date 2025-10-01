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
                            <button type="button" class="btn btn-save" onclick="saveTrip()">
                                <i class="fas fa-save"></i>
                                Guardar
                            </button>
                            <button type="button" class="btn btn-preview" onclick="previewTrip()">
                                <i class="fas fa-eye"></i>
                                Vista Previa
                            </button>
                            <button type="button" class="btn btn-pdf" onclick="downloadPDF()">
                                <i class="fas fa-file-pdf"></i>
                                Descarga versión PDF
                            </button>
                        </div>

                        <!-- Authentication Section -->
                        <div class="auth-section">
                            <div class="user-profile">
                                @if(Auth::user()->avatar)
                                    <img src="{{ Auth::user()->avatar }}" alt="Avatar" class="user-avatar">
                                @else
                                    <div class="user-avatar-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                                <span class="user-name">{{ Auth::user()->name }}</span>
                                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-logout">
                                        <i class="fas fa-sign-out-alt"></i>
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </div>
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
                        <button onclick="window.print()" class="public-btn-print">
                            <i class="fas fa-print"></i>
                            Imprimir
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
        background: linear-gradient(180deg, #e6f3fb 0%, #f7fbff 60%);
        min-height: 100vh;
        color: var(--primary-dark);
        line-height: 1.6;
        letter-spacing: 0.1px;
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

    .trip-info-card { background: var(--white); border-radius: var(--radius); padding: 2rem; margin-bottom: 2rem; box-shadow: var(--shadow-soft); border: 1px solid var(--border-gray); }

    .trip-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .trip-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #0ea5e9, #38bdf8);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }

    .trip-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 0.5rem;
    }

        .trip-subtitle {
        color: #666;
        font-size: 0.9rem;
        margin: 0.25rem 0 0 0;
    }

    .trip-author {
        color: #888;
        font-size: 0.8rem;
        margin: 0.25rem 0 0 0;
        font-style: italic;
    }

    .timeline-container { background: transparent; position: relative; }

    .timeline {
        position: relative;
        padding-left: 3rem;
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 1rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--border-gray);
        border-radius: 1px;
    }

    .day-section {
        position: relative;
        background: var(--white);
        border-radius: var(--radius);
        padding: 2rem;
        box-shadow: var(--shadow-soft);
        border: 1px solid var(--border-gray);
    }

    .day-marker {
        position: absolute;
        left: -2.5rem;
        top: 1rem;
        width: 2.5rem;
        height: 2.5rem;
        background: linear-gradient(135deg, #0ea5e9, #38bdf8);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1rem;
        z-index: 2;
        box-shadow: var(--shadow-soft);
    }

    .day-header {
        margin-bottom: 1.5rem;
        padding-left: 1rem;
    }

    .day-date { background: #e0f2fe; color: #0f172a; padding: 0.5rem 1rem; border-radius: 999px; font-size: 0.9rem; font-weight: 600; display: inline-block; margin-bottom: 0.5rem; }

    .day-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 0.5rem;
    }

    .day-subtitle {
        color: var(--text-gray);
        font-size: 1rem;
    }

    .timeline-items {
        padding-left: 1rem;
    }

    .timeline-item {
        background: var(--white);
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        margin-bottom: 1.5rem;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .timeline-item:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1), 0 4px 10px rgba(0, 0, 0, 0.06);
        transform: translateY(-2px);
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
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .icon-flight {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    }

    .icon-hotel {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .icon-activity {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .icon-transport {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    }

    .icon-note {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
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
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 12px;
        padding: 1.5rem;
        margin: 1rem 0;
        border-left: 4px solid var(--primary-blue);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .summary-content {
        font-size: 0.95rem;
        line-height: 1.6;
        color: #333;
    }

    .summary-content strong {
        color: var(--primary-blue);
        font-weight: 600;
    }

    .summary-content br {
        margin-bottom: 0.5rem;
    }

    .summary-content em {
        color: #666;
        font-style: italic;
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
        // Implement PDF download functionality
        showNotification('Descargando PDF', 'Generando PDF del itinerario...');
        // Add actual PDF generation logic here
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
</script>
@endpush
