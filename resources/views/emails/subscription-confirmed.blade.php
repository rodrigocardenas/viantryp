<!DOCTYPE html>
<html lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <title>¡Tu Plan {{ $plan_name }} está activo!</title>
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
      background: #eef8f7;
      color: #1a9a8a;
      font-size: 11px;
      font-weight: 800;
      letter-spacing: 1px;
      text-transform: uppercase;
      padding: 6px 14px;
      border-radius: 999px;
      margin-bottom: 16px;
    }
    .greeting { font-size: 30px; font-weight: 800; color: #000000; margin-bottom: 16px; letter-spacing: -0.8px; line-height: 1.25; }
    .intro { font-size: 16px; line-height: 1.65; color: #475569; margin-bottom: 20px; }
    .summary-card {
      background: #fbfcfd;
      border: 1px solid #e2e8f0;
      border-radius: 16px;
      padding: 24px;
      margin-bottom: 32px;
    }
    .summary-title { font-size: 14px; font-weight: 800; color: #1a9a8a; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; }
    .summary-list { list-style: none; padding: 0; margin: 0; }
    .summary-list li { font-size: 14.5px; color: #334155; padding: 6px 0; line-height: 1.5; }
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
      <span class="badge">Suscripción Activada</span>
      <h1 class="greeting">¡Gracias, {{ $name }}! Tu Plan {{ $plan_name }} ya está activo</h1>
      <p class="intro">
        Hemos activado todos los beneficios de tu <strong>Plan {{ $plan_name }}</strong> en tu cuenta de Viantryp.
      </p>
    </div>

    <div class="summary-card">
      <div class="summary-title">Lo que tienes disponible en tu Plan {{ $plan_name }}</div>
      <ul class="summary-list">
        @if(strtolower($plan_name) === 'negocios')
          <li>✓ <strong>Itinerarios activos ilimitados</strong></li>
          <li>✓ <strong>Colaboradores y editores ilimitados</strong></li>
          <li>✓ <strong>Consultas en Google Places y archivos adjuntos ilimitados</strong></li>
          <li>✓ <strong>Marca Blanca con logo propio de tu agencia en web y PDF</strong></li>
          <li>✓ <strong>Tryp IA ilimitado y soporte prioritario dedicado</strong></li>
        @else
          <li>✓ <strong>Hasta 20 itinerarios activos</strong></li>
          <li>✓ <strong>Hasta 2 colaboradores de edición de viaje</strong></li>
          <li>✓ <strong>Hasta 50 consultas en Google Places por itinerario</strong></li>
          <li>✓ <strong>Hasta 20 archivos adjuntos por itinerario</strong></li>
          <li>✓ <strong>Tryp IA ilimitado y exportación en PDF</strong></li>
        @endif
      </ul>
    </div>

    <div class="cta-area">
      <a href="{{ route('trips.index') }}" class="cta-btn">Diseñar mis viajes →</a>
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

