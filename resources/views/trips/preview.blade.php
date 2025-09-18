@extends('layouts.app')

@section('title', 'Viantryp - Vista Previa del Itinerario')

@section('content')
    <x-header :showActions="true" :backUrl="route('trips.index')" :actions="[
        ['url' => '#', 'text' => 'Imprimir', 'class' => 'btn-secondary', 'icon' => 'fas fa-print', 'onclick' => 'window.print()'],
        ['url' => '#', 'text' => 'Descargar PDF', 'class' => 'btn-primary', 'icon' => 'fas fa-download', 'onclick' => 'downloadPDF()']
    ]" />

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
        color: var(--text-gray);
        font-size: 1.1rem;
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
        border: 1px solid var(--border-gray);
        border-radius: 12px;
        margin-bottom: 1.5rem;
        overflow: hidden;
        box-shadow: var(--shadow-soft);
        transition: all 0.3s ease;
    }

    .timeline-item:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        transform: translateY(-1px);
    }

    .item-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-gray);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .item-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .icon-flight {
        background: var(--primary-blue);
    }

    .icon-hotel {
        background: var(--coral);
    }

    .icon-activity {
        background: var(--mint);
    }

    .icon-transport {
        background: var(--purple);
    }

    .icon-note {
        background: var(--orange);
    }

    .item-info {
        flex: 1;
    }

    .item-type {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-gray);
        margin-bottom: 0.25rem;
    }

    .item-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 0.25rem;
    }

    .item-subtitle {
        color: var(--text-gray);
        font-size: 0.9rem;
    }

    .item-toggle {
        background: none;
        border: none;
        color: var(--text-gray);
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .item-toggle:hover {
        background: var(--light-gray);
        color: var(--primary-dark);
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
        background: var(--white);
        border: 1px solid var(--border-gray);
        border-radius: 50px;
        padding: 1rem 1.5rem;
        box-shadow: var(--shadow-soft);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 1000;
    }

    .contact-button:hover { transform: translateY(-2px); box-shadow: var(--shadow-hover); }

    .contact-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #0ea5e9, #38bdf8);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
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

    @media (max-width: 768px) {
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
</script>
@endpush
