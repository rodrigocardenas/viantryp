<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enlace para ver tu viaje</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8fafc;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #1f2a44;
            margin-bottom: 10px;
        }
        .title {
            font-size: 20px;
            color: #1f2a44;
            margin: 0;
        }
        .content {
            margin-bottom: 30px;
        }
        .trip-info {
            background: #f0f9ff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #0ea5e9;
        }
        .trip-title {
            font-size: 18px;
            font-weight: bold;
            color: #1f2a44;
            margin-bottom: 10px;
        }
        .trip-details {
            color: #64748b;
            margin: 5px 0;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%);
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
            transition: transform 0.2s ease;
        }
        .cta-button:hover {
            transform: translateY(-2px);
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 14px;
            color: #64748b;
            text-align: center;
        }
        .custom-message {
            background: #fef3c7;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #f59e0b;
        }
        .warning {
            background: #fef2f2;
            color: #dc2626;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #dc2626;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">Viantryp</div>
            <h1 class="title">¡Tienes un viaje para revisar!</h1>
        </div>

        <div class="content">
            <p>Hola,</p>

            @if($customMessage)
                <div class="custom-message">
                    <strong>Mensaje personalizado:</strong><br>
                    {{ $customMessage }}
                </div>
            @endif

            <p>Te comparto el enlace para que puedas ver los detalles de tu viaje:</p>

            <div class="trip-info">
                <div class="trip-title">{{ $trip->title }}</div>
                @if($trip->destination)
                    <div class="trip-details"><strong>Destino:</strong> {{ $trip->destination }}</div>
                @endif
                @if($trip->start_date && $trip->end_date)
                    <div class="trip-details"><strong>Fechas:</strong> {{ $trip->getFormattedDates() }}</div>
                @endif
                @if($trip->travelers)
                    <div class="trip-details"><strong>Viajeros:</strong> {{ $trip->travelers }}</div>
                @endif
            </div>

            <div style="text-align: center;">
                <a href="{{ $shareUrl }}" class="cta-button">
                    📋 Ver Viaje Completo
                </a>
            </div>

            <p>Si el botón no funciona, puedes copiar y pegar este enlace en tu navegador:</p>
            <p style="word-break: break-all; background: #f1f5f9; padding: 10px; border-radius: 4px; font-family: monospace; font-size: 14px;">
                {{ $shareUrl }}
            </p>

            <div class="warning">
                <strong>Nota:</strong> Este enlace es privado y solo funcionará para ti. Si tienes alguna pregunta sobre el viaje, no dudes en contactarme.
            </div>
        </div>

        <div class="footer">
            <p>Este correo fue enviado desde Viantryp - Tu gestor de viajes personal</p>
            <p>Si no esperabas este correo, puedes ignorarlo.</p>
        </div>
    </div>
</body>
</html>
