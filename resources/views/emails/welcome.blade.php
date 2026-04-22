<!DOCTYPE html>
<html lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <title>Bienvenido a Viantryp</title>
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

    /* ── HEADER ── */
    .header {
      padding-bottom: 24px;
      text-align: left;
    }
    .logo-img { height: 32px; width: auto; display: block; border: 0; }

    /* ── GREETING & INTRO ── */
    .hero { margin-bottom: 32px; }
    .greeting { font-size: 32px; font-weight: 800; color: #000000; margin-bottom: 16px; letter-spacing: -1px; }
    .intro { font-size: 16px; line-height: 1.6; color: #64748b; margin-bottom: 24px; font-weight: 400; }
    
    .divider { border: 0; border-top: 1px solid #f1f5f9; margin: 24px 0; }

    /* ── FEATURES SECTION ── */
    .feat-label {
      font-size: 12px; font-weight: 800; letter-spacing: 1.5px;
      text-transform: uppercase; color: #1a9a8a; margin-bottom: 24px;
      display: block;
    }
    .feats { display: flex; flex-direction: column; gap: 16px; margin-bottom: 40px; }
    
    .feat-card {
      background: #fbfcfd;
      border: 1px solid #f1f5f9;
      border-radius: 16px;
      padding: 24px;
      display: flex;
      align-items: flex-start;
      gap: 20px;
    }
    .feat-ico {
      width: 48px; height: 48px; border-radius: 10px;
      background: #eef8f7; color: #1a9a8a;
      flex-shrink: 0; display: flex; align-items: center; justify-content: center;
    }
    .feat-ico svg { width: 22px; height: 22px; }
    
    .feat-body { flex: 1; }
    .feat-name { font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 4px; }
    .feat-name .accent { color: #1a9a8a; }
    .feat-desc { font-size: 14px; line-height: 1.55; color: #64748b; }

    /* ── CTA ── */
    .cta-area { text-align: center; margin-bottom: 48px; }
    .cta-btn {
      display: inline-block;
      background: #11998e; /* Teal from image */
      color: #ffffff !important;
      text-decoration: none;
      font-size: 16px; font-weight: 700;
      padding: 16px 40px; border-radius: 10px;
    }

    /* ── FOOTER ── */
    .footer {
      padding-top: 24px;
      text-align: center;
    }
    .footer-links {
      display: flex;
      justify-content: center;
      gap: 32px;
      margin-bottom: 24px;
    }
    .footer-link {
      font-size: 14px;
      font-weight: 600;
      color: #1a9a8a;
      text-decoration: none;
    }
  </style>
</head>
<body>
<div class="container">

  <!-- HEADER -->
  <div class="header">
    <a href="{{ config('app.url') }}" style="text-decoration:none;">
      <img src="{{ config('app.url') }}/images/logo-viantryp-clean.png" alt="Viantryp" class="logo-img">
    </a>
  </div>

  <!-- HERO -->
  <div class="hero">
    <h1 class="greeting">Hola, {{ $name }}</h1>
    <p class="intro">Tu cuenta ha sido creada exitosamente. Estamos emocionados de ayudarte a transformar la forma en que planificas y compartes tus <strong>viajes</strong>.</p>
  </div>

  <hr class="divider">

  <!-- FEATURES -->
  <div class="feat-section">
    <span class="feat-label">QUÉ PUEDES HACER AHORA</span>
    
    <div class="feats">
      <!-- Feature 1 -->
      <div class="feat-card">
        <div class="feat-ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
        </div>
        <div class="feat-body">
          <div class="feat-name">Editor visual <span class="accent">Drag &amp; Drop</span></div>
          <div class="feat-desc">Crea rutas profesionales en minutos. Arrastra y suelta destinos, hoteles y actividades con facilidad.</div>
        </div>
      </div>

      <!-- Feature 2 -->
      <div class="feat-card">
        <div class="feat-ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
        </div>
        <div class="feat-body">
          <div class="feat-name">Enlace interactivo <span class="accent">personal</span></div>
          <div class="feat-desc">Comparte tus viajes mediante un link elegante. Tus clientes podrán verlo desde cualquier dispositivo móvil.</div>
        </div>
      </div>

      <!-- Feature 3 -->
      <div class="feat-card">
        <div class="feat-ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        </div>
        <div class="feat-body">
          <div class="feat-name">Documentación en <span class="accent">un solo lugar</span></div>
          <div class="feat-desc">Adjunta reservas, tickets y vouchers directamente en el itinerario. Todo organizado, nada perdido.</div>
        </div>
      </div>
    </div>
  </div>

  <!-- CTA -->
  <div class="cta-area">
    <a href="{{ route('trips.index') }}" class="cta-btn">Ir a mis viajes →</a>
  </div>

  <hr class="divider">

  <!-- FOOTER -->
  <div class="footer">
    <div class="footer-links">
      <a href="{{ url('/') }}#demo" class="footer-link">Cómo funciona</a>
      <a href="{{ url('/') }}#precios" class="footer-link">Precios</a>
      <a href="mailto:hola@viantryp.com" class="footer-link">Contacto</a>
    </div>
  </div>

</div>
</body>
</html>
