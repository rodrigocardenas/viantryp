<!DOCTYPE html>
<html lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <title>Bienvenido a Viantryp</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      background: #f4f7f9;
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      -webkit-font-smoothing: antialiased;
      padding: 40px 16px 64px;
      color: #334155;
    }

    .wrap { max-width: 580px; margin: 0 auto; background: white; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.04); }

    /* ── TOPBAR ── */
    .topbar {
      background: white;
      padding: 28px 40px;
      display: flex; align-items: center; justify-content: space-between;
      border-bottom: 1px solid #f1f5f9;
    }
    .nav-tag { font-size: 11px; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px; text-transform: uppercase; }

    /* ── HERO ── */
    .hero {
      background: white;
      padding: 56px 40px 48px;
      text-align: center;
      position: relative;
    }
    
    .badge {
      display: inline-flex; align-items: center; gap: 7px;
      background: #eef8f7;
      border: 1px solid #ccede8;
      border-radius: 999px;
      padding: 6px 16px;
      font-size: 11px; font-weight: 800;
      letter-spacing: 1px; text-transform: uppercase;
      color: #1a9a8a;
      margin-bottom: 28px;
    }
    .badge-dot { width: 6px; height: 6px; border-radius: 50%; background: #1a9a8a; }

    .hero-title {
      font-weight: 900; font-size: 42px; line-height: 1.1;
      letter-spacing: -1.5px; color: #0f172a;
      margin-bottom: 20px;
    }
    .hero-title .accent { color: #1a9a8a; }

    .hero-sub {
      font-size: 16px; line-height: 1.6; color: #64748b;
      max-width: 420px;
      margin: 0 auto 36px;
    }

    .cta-btn {
      display: inline-block;
      background: linear-gradient(135deg, #1a9a8a 0%, #147a6e 100%);
      color: #ffffff !important;
      text-decoration: none;
      font-size: 16px; font-weight: 700;
      padding: 16px 42px; border-radius: 100px;
      box-shadow: 0 4px 14px rgba(26, 154, 138, 0.3);
    }

    /* ── BODY ── */
    .body {
      padding: 40px;
      background: white;
    }

    .greeting { font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 12px; letter-spacing: -0.3px; }
    .intro { font-size: 15px; line-height: 1.65; color: #64748b; margin-bottom: 40px; }

    /* features Section */
    .feat-label {
      font-size: 12px; font-weight: 800; letter-spacing: 1.5px;
      text-transform: uppercase; color: #94a3b8; margin-bottom: 24px;
      text-align: center;
    }
    .feats { display: flex; flex-direction: column; gap: 20px; margin-bottom: 40px; }
    
    .feat-card {
      background: #f8fafc;
      border: 1px solid #f1f5f9;
      border-radius: 16px;
      padding: 24px;
      display: flex;
      align-items: flex-start;
      gap: 18px;
    }
    .feat-ico {
      width: 44px; height: 44px; border-radius: 12px;
      flex-shrink: 0; display: flex; align-items: center; justify-content: center;
    }
    .feat-ico svg { width: 22px; height: 22px; }
    .feat-ico.green { background: #e0f2f1; color: #1a9a8a; }
    .feat-ico.navy  { background: #e2e8f0; color: #0f172a; }
    .feat-ico.lime  { background: #f0f9f1; color: #25904a; }
    
    .feat-body { flex: 1; }
    .feat-name { font-size: 15px; font-weight: 800; color: #0f172a; margin-bottom: 4px; }
    .feat-desc { font-size: 14px; line-height: 1.6; color: #64748b; }

    /* ── FOOTER ── */
    .footer {
      background: #f8fafc;
      border-top: 1px solid #f1f5f9;
      padding: 48px 40px;
      text-align: center;
    }
    .footer-links {
      display: table;
      width: 100%;
      margin-bottom: 32px;
      border-collapse: separate;
      border-spacing: 10px 0;
    }
    .footer-link-cell {
      display: table-cell;
      width: 33.33%;
      padding: 10px;
      background: white;
      border: 1px solid #f1f5f9;
      border-radius: 10px;
    }
    .footer-link {
      font-size: 13px;
      font-weight: 700;
      color: #1a9a8a;
      text-decoration: none;
      display: block;
    }
    
    .footer-copy {
      font-size: 12px;
      color: #94a3b8;
      line-height: 1.8;
    }
    .footer-contact {
      margin-top: 20px;
      font-size: 13px;
      color: #64748b;
    }
    .footer-contact a { color: #1a9a8a; text-decoration: none; font-weight: 600; }
  </style>
</head>
<body>
<div class="wrap">

  <!-- TOPBAR -->
  <div class="topbar">
    <a href="{{ config('app.url') }}" style="display:block; text-decoration:none;">
      <img src="{{ config('app.url') }}/images/logo-viantryp-premium.png" alt="Viantryp" style="height: 28px; width: auto; display: block; border:0;">
    </a>
    <span class="nav-tag">Bienvenida</span>
  </div>

  <!-- HERO -->
  <div class="hero">
    <div class="badge"><span class="badge-dot"></span>✦ Tu aventura comienza aquí</div>
    <h1 class="hero-title">Diseña viajes<br><span class="accent">inolvidables</span></h1>
    <p class="hero-sub">Viantryp es tu lienzoparapara crear itinerarios interactivos que tus clientes o amigos amarán.</p>
    <a href="{{ route('trips.index') }}" class="cta-btn">Ir a mis viajes →</a>
  </div>

  <!-- BODY -->
  <div class="body">

    <p class="greeting">Hola, {{ $name }} 👋</p>
    <p class="intro">Tu cuenta ha sido creada exitosamente. Estamos emocionados de ayudarte a transformar la forma en que planificas y compartes tus aventuras.</p>

    <div class="feat-label">Lo que puedes hacer ahora</div>
    <div class="feats">

      <!-- Card 1 -->
      <div class="feat-card">
        <div class="feat-ico green">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
        </div>
        <div class="feat-body">
          <div class="feat-name">Editor Visual Inteligente</div>
          <div class="feat-desc">Crea rutas profesionales en minutos. Arrastra y suelta destinos, hoteles y actividades con facilidad.</div>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="feat-card">
        <div class="feat-ico navy">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
        </div>
        <div class="feat-body">
          <div class="feat-name">Enlaces Interactivos</div>
          <div class="feat-desc">Comparte tus viajes mediante un link elegante. Tus clientes podrán verlo desde cualquier dispositivo móvil.</div>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="feat-card">
        <div class="feat-ico lime">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        </div>
        <div class="feat-body">
          <div class="feat-name">Documentación en un solo lugar</div>
          <div class="feat-desc">Adjunta reservas, tickets y vouchers directamente en el itinerario. Todo organizado, nada perdido.</div>
        </div>
      </div>

    </div>

  </div>

  <!-- FOOTER -->
  <div class="footer">
    
    <div class="footer-links">
      <div class="footer-link-cell">
        <a href="{{ url('/') }}#demo" class="footer-link">Cómo funciona</a>
      </div>
      <div class="footer-link-cell">
        <a href="{{ url('/') }}#precios" class="footer-link">Precios</a>
      </div>
      <div class="footer-link-cell">
        <a href="mailto:hola@viantryp.com" class="footer-link">Contacto</a>
      </div>
    </div>

    <p class="footer-copy">© {{ date('Y') }} Viantryp · Diseña experiencias, colecciona momentos.<br>Este es un correo automático enviado a {{ $user_email }}.</p>
    
    <div class="footer-contact">
      ¿Necesitas ayuda? Escríbenos a <a href="mailto:hola@viantryp.com">hola@viantryp.com</a>
    </div>
  </div>

</div>
</body>
</html>
