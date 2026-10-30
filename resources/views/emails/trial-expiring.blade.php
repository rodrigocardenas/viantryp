<!DOCTYPE html>
<html lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <title>Tu prueba gratuita del Plan Viajero Pro está por finalizar</title>
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
    .hero { margin-bottom: 32px; }
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
    .divider { border: 0; border-top: 1px solid #f1f5f9; margin: 24px 0; }
    .feat-label {
      font-size: 12px; font-weight: 800; letter-spacing: 1.5px;
      text-transform: uppercase; color: #1a9a8a; margin-bottom: 20px;
      display: block;
    }
    .feats { display: flex; flex-direction: column; gap: 14px; margin-bottom: 32px; }
    .feat-card {
      background: #fbfcfd;
      border: 1px solid #f1f5f9;
      border-radius: 16px;
      padding: 20px 24px;
    }
    .feat-name { font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 6px; }
    .feat-name .accent { color: #1a9a8a; }
    .feat-desc { font-size: 14px; line-height: 1.55; color: #64748b; }
    .reassurance-box {
      background: #f8fafc;
      border-left: 4px solid #1a9a8a;
      border-radius: 10px;
      padding: 16px 20px;
      font-size: 14px;
      color: #334155;
      line-height: 1.55;
      margin-bottom: 32px;
    }
    .cta-area { text-align: center; margin-bottom: 40px; }
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
      <span class="badge">Aviso de Prueba Gratuita</span>
      <h1 class="greeting">Hola, {{ $name }}: te quedan {{ $days_remaining ?? 3 }} {{ ($days_remaining ?? 3) == 1 ? 'día' : 'días' }} de tu Plan Viajero Pro</h1>
      <p class="intro">
        Tu mes de prueba gratuita del <strong>Plan Viajero Pro</strong> finalizará pronto{{ !empty($trial_ends_at) ? ' (' . $trial_ends_at . ')' : '' }}. Queremos avisarte con anticipación para que elijas cómo deseas continuar planificando tus viajes.
      </p>
    </div>

    <div class="reassurance-box">
      <strong>Tus viajes están 100% seguros:</strong> Si decides no suscribirte, tu cuenta pasará automáticamente al <strong>Plan Básico gratuito ($0/mes)</strong> y conservarás los itinerarios que ya creaste.
    </div>

    <hr class="divider">

    <div class="feat-section">
      <span class="feat-label">PLANES DISPONIBLES PARA CONTINUAR SIN LÍMITES</span>
      <div class="feats">
        <div class="feat-card">
          <div class="feat-name">Plan <span class="accent">Viajero Pro</span> · $6.99 USD/mes ($5.59/mes anual)</div>
          <div class="feat-desc">Hasta 20 itinerarios activos, 2 colaboradores de edición, 50 consultas en Google Places, 20 archivos adjuntos por viaje, Tryp IA ilimitado y exportación PDF.</div>
        </div>
        <div class="feat-card">
          <div class="feat-name">Plan <span class="accent">Negocios</span> · $24.99 USD/mes ($19.99/mes anual)</div>
          <div class="feat-desc">Itinerarios, colaboradores, consultas en Google Places y adjuntos ilimitados, además de Marca Blanca con el logo propio de tu agencia.</div>
        </div>
      </div>
    </div>

    <div class="cta-area">
      <a href="{{ route('profile.index') }}?tab=subscription" class="cta-btn">Ver mi cuenta y planes →</a>
    </div>

    <div class="footer">
      <p class="footer-copy">© {{ date('Y') }} Viantryp · Diseña experiencias, colecciona momentos.<br>Este es un aviso informativo enviado a {{ $user_email }}.</p>
      <div class="footer-contact">
        ¿Tienes dudas? Escríbenos a <a href="mailto:hola@viantryp.com">hola@viantryp.com</a>
      </div>
    </div>
  </div>
</div>
</body>
</html>

