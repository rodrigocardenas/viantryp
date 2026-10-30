<!DOCTYPE html>
<html lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <title>Confirmación de cancelación — Viantryp</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      background: #ffffff;
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      -webkit-font-smoothing: antialiased;
      padding: 40px 20px;
      color: #0f172a;
    }
    .container { max-width: 580px; margin: 0 auto; }
    .hero { margin-bottom: 28px; }
    .badge {
      display: inline-block;
      background: #f1f5f9;
      color: #475569;
      font-size: 11px;
      font-weight: 800;
      letter-spacing: 1px;
      text-transform: uppercase;
      padding: 6px 14px;
      border-radius: 999px;
      margin-bottom: 16px;
    }
    .greeting { font-size: 28px; font-weight: 800; color: #000000; margin-bottom: 16px; letter-spacing: -0.8px; line-height: 1.25; }
    .intro { font-size: 16px; line-height: 1.65; color: #475569; margin-bottom: 20px; }
    .info-box {
      background: #f8fafc;
      border-left: 4px solid #1a9a8a;
      border-radius: 12px;
      padding: 20px;
      font-size: 14.5px;
      color: #334155;
      line-height: 1.6;
      margin-bottom: 32px;
    }
    .cta-area { text-align: center; margin-bottom: 36px; }
    .cta-btn {
      display: inline-block;
      background: #11998e;
      color: #ffffff !important;
      text-decoration: none;
      font-size: 16px; font-weight: 700;
      padding: 16px 38px; border-radius: 10px;
    }
    .footer {
      background: #f8fafc;
      border-top: 1px solid #f1f5f9;
      padding: 40px 32px;
      text-align: center;
      border-radius: 16px;
    }
    .footer-copy { font-size: 12px; color: #94a3b8; line-height: 1.8; }
    .footer-contact { margin-top: 16px; font-size: 13px; color: #64748b; }
    .footer-contact a { color: #1a9a8a; text-decoration: none; font-weight: 600; }
  </style>
</head>
<body>
<div class="container">
  <div style="padding: 20px 0;">
    <div class="hero">
      <span class="badge">Confirmación de Cancelación</span>
      <h1 class="greeting">Hola, {{ $name }}: hemos procesado la cancelación de tu Plan {{ $plan_name }}</h1>
      <p class="intro">
        Te confirmamos que tu suscripción al <strong>Plan {{ $plan_name }}</strong> ha sido cancelada y no se realizarán nuevos cargos automáticos.
      </p>
    </div>

    <div class="info-box">
      @if(!empty($ends_at))
        <strong>Periodo de acceso activo:</strong> Seguirás disfrutando de todas las herramientas de tu <strong>Plan {{ $plan_name }}</strong> hasta el <strong>{{ $ends_at }}</strong>. Después de esa fecha, tu cuenta pasará automáticamente al Plan Básico gratuito y conservarás tus viajes creados.
      @else
        <strong>Tus viajes siguen seguros:</strong> Tu cuenta ahora se encuentra en el Plan Básico gratuito ($0/mes) y todos tus itinerarios creados permanecen disponibles en tu panel.
      @endif
    </div>

    <div class="cta-area">
      <a href="{{ route('profile.index') }}?tab=subscription" class="cta-btn">Ver estado de mi cuenta →</a>
    </div>

    <div class="footer">
      <p class="footer-copy">© {{ date('Y') }} Viantryp · Diseña experiencias, colecciona momentos.<br>Confirmación enviada a {{ $user_email }}.</p>
      <div class="footer-contact">
        ¿Necesitas ayuda? Escríbenos a <a href="mailto:hola@viantryp.com">hola@viantryp.com</a>
      </div>
    </div>
  </div>
</div>
</body>
</html>

