<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $trip->title }} - Itinerario</title>
    <style>
        @page {
            margin: 1cm;
            size: A4;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #1f2937;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #0ea5e9;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #0ea5e9;
            margin-bottom: 10px;
        }

        .trip-title {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .trip-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
        }

        .info-item {
            flex: 1;
            text-align: center;
        }

        .info-label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
        }

        .summary {
            background: #f0f9ff;
            border-left: 4px solid #0ea5e9;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 0 8px 8px 0;
        }

        .summary-title {
            font-size: 14px;
            font-weight: bold;
            color: #0ea5e9;
            margin-bottom: 8px;
        }

        .summary-content {
            font-size: 12px;
            line-height: 1.5;
            color: #374151;
        }

        .day-section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .day-header {
            background: linear-gradient(135deg, #0ea5e9, #38bdf8);
            color: white;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .day-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .day-date {
            font-size: 12px;
            opacity: 0.9;
        }

        .timeline-item {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 12px;
            padding: 15px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .item-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .item-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .item-info {
            flex: 1;
        }

        .item-type {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .item-title {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 2px;
        }

        .item-subtitle {
            font-size: 12px;
            color: #6b7280;
        }

        .item-details {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #e5e7eb;
        }

        .detail-row {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            padding: 6px 0;
        }

        .detail-icon-small {
            width: 20px;
            height: 20px;
            background: #e0f2fe;
            border-radius: 3px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0ea5e9;
            font-size: 10px;
            margin-right: 8px;
            flex-shrink: 0;
        }

        .detail-text-small {
            flex: 1;
        }

        .detail-label-small {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 2px;
        }

        .detail-value-small {
            font-weight: 600;
            color: #1f2937;
            font-size: 11px;
        }

        .flight-route {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
        }

        .flight-segment {
            flex: 1;
            text-align: center;
        }

        .flight-time {
            font-size: 14px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 5px;
        }

        .flight-airport {
            font-size: 11px;
            color: #6b7280;
        }

        .flight-path {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 80px;
        }

        .flight-line {
            position: absolute;
            width: 100%;
            height: 2px;
            background: #e5e7eb;
            border-radius: 1px;
        }

        .flight-plane {
            position: absolute;
            font-size: 12px;
            background: white;
            padding: 2px;
            border-radius: 50%;
        }

        .reservation-details {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .reservation-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .reservation-label {
            font-weight: 600;
            color: #1f2937;
            min-width: 100px;
            font-size: 11px;
        }

        .reservation-value {
            color: #6b7280;
            font-size: 11px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
            padding: 10px;
            border-top: 1px solid #e5e7eb;
        }

        .icon-flight { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .icon-hotel { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .icon-activity { background: linear-gradient(135deg, #10b981, #059669); }
        .icon-transport { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
        .icon-note { background: linear-gradient(135deg, #6b7280, #4b5563); }

        @media print {
            .footer {
                position: fixed;
                bottom: 0;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Viantryp</div>
        <h1 class="trip-title">{{ $trip->title }}</h1>
    </div>

    <div class="trip-info">
        <div class="info-item">
            <div class="info-label">Fechas</div>
            <div class="info-value">{{ $trip->getFormattedDates() }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Duración</div>
            <div class="info-value">{{ $trip->getDuration() }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Viajeros</div>
            <div class="info-value">{{ $trip->travelers }}</div>
        </div>
        @if(isset($trip->user) && !$isPublicPreview)
        <div class="info-item">
            <div class="info-label">Creado por</div>
            <div class="info-value">{{ $trip->user->name }}</div>
        </div>
        @endif
    </div>

    @if($trip->summary)
    <div class="summary">
        <div class="summary-title">Resumen del Viaje</div>
        <div class="summary-content">{{ $trip->summary }}</div>
    </div>
    @endif

    @if(isset($trip) && $trip->days && count($trip->days) > 0)
        @foreach($trip->days as $day)
        <div class="day-section">
            <div class="day-header">
                <div class="day-title">Día {{ $day->day }}</div>
                <div class="day-date">{{ $day->getFormattedDate() }} - {{ $day->getFullDate() }}</div>
            </div>

            <div class="timeline-items">
                @if($day->items && count($day->items) > 0)
                    @foreach($day->items as $item)
                    <div class="timeline-item">
                        <div class="item-header">
                            <div class="item-icon {{ $item->getIconClass() }}">
                                <i class="{{ $item->getIcon() }}"></i>
                            </div>
                            <div class="item-info">
                                <div class="item-type">{{ $item->getTypeLabel() }}</div>
                                <div class="item-title">{{ $item->getTitle() }}</div>
                                <div class="item-subtitle">{{ $item->getSubtitle() }}</div>
                            </div>
                        </div>

                        @if($item->getDetailsHtml())
                        <div class="item-details">
                            {!! $item->getDetailsHtml() !!}
                        </div>
                        @endif
                    </div>
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
    <div class="timeline-item">
        <div class="item-header">
            <div class="item-icon icon-note">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="item-info">
                <div class="item-type">Información</div>
                <div class="item-title">Sin días programados</div>
                <div class="item-subtitle">No hay días en el itinerario</div>
            </div>
        </div>
    </div>
    @endif

    <div class="footer">
        <div>Generado por Viantryp - {{ now()->format('d/m/Y H:i') }}</div>
    </div>
</body>
</html>
