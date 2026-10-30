<!DOCTYPE html>
<html lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <title>Tu prueba gratuita del Plan Viajero Pro ha finalizado</title>
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
    .divider { border: 0; border-top: 1px solid #f1f5f9; margin: 24px 0; }
    .reassurance-box {
      background: #f0fdf4;
      border: 1px solid #bbf7d0;
      border-radius: 14px;
      padding: 20px;
      font-size: 14.5px;
      color: #166534;
      line-height: 1.6;
      margin-bottom: 28px;
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
      <span class="badge">Actualización de tu Cuenta</span>
      <h1 class="greeting">Hola, {{ $name }}: tu periodo de prueba gratuita ha finalizado</h1>
      <p class="intro">
        Tu mes de prueba del <strong>Plan Viajero Pro</strong> ha concluido y tu cuenta ahora se encuentra en el <strong>Plan Básico gratuito ($0/mes)</strong>.
      </p>
    </div>

    <div class="reassurance-box">
      <strong>Tus itinerarios siguen disponibles:</strong> Todos los viajes y enlaces compartidos que creaste durante tu prueba se mantienen intactos en tu cuenta. Cuando quieras ampliar nuevamente tus límites con el <strong>Plan Viajero Pro ($6.99/mes)</strong> o el <strong>Plan Negocios ($24.99/mes)</strong>, puedes activarlo en cualquier momento.
    </div>

    <div class="cta-area">
      <a href="{{ route('trips.index') }}" class="cta-btn">Ir a mis viajes →</a>
    </div>

    <div class="footer">
      <p class="footer-copy">© {{ date('Y') }} Viantryp · Diseña experiencias, colecciona momentos.<br>Este es un aviso automático enviado a {{ $user_email }}.</p>
      <div class="footer-contact">
        ¿Necesitas ayuda? Escríbenos a <a href="mailto:hola@viantryp.com">hola@viantryp.com</a>
      </div>
    </div>
  </div>
</div>
</body>
</html>

