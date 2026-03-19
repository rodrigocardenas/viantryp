<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Viantryp' }}</title>
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
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f1f5f9;
        }
        .logo {
            font-size: 26px;
            font-weight: 800;
            color: #1a9a8a; /* Viantryp Primary */
            text-decoration: none;
            letter-spacing: -0.5px;
        }
        .title {
            font-size: 22px;
            font-weight: 700;
            color: #0d2b3e;
            margin: 15px 0 0 0;
        }
        .content {
            margin-bottom: 30px;
            font-size: 15px;
            color: #475569;
        }
        .cta-container {
            text-align: center;
            margin: 30px 0;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #1a9a8a 0%, #16a69b 100%);
            color: white !important;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 15px;
            box-shadow: 0 4px 12px rgba(26, 154, 138, 0.25);
            transition: transform 0.2s ease;
        }
        .footer {
            margin-top: 35px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 13px;
            color: #94a3b8;
            text-align: center;
        }
        .footer p {
            margin: 5px 0;
        }
        .accent-box {
            background: #f0f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 4px solid #1a9a8a;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="{{ config('app.url') }}" class="logo">Viantryp</a>
            @if(isset($title))
                <h1 class="title">{{ $title }}</h1>
            @endif
        </div>

        <div class="content">
            {{ $slot }}
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} Viantryp. Todos los derechos reservados.</p>
            <p>Este correo automático fue enviado para {{ $user_email ?? 'nuestro usuario' }}.</p>
            <p>Viantryp - Diseña experiencias, colecciona momentos.</p>
        </div>
    </div>
</body>
</html>
