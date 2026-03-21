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
      background: #eef1f5;
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      -webkit-font-smoothing: antialiased;
      padding: 40px 16px 64px;
    }

    .wrap { max-width: 560px; margin: 0 auto; }

    /* ── TOPBAR ── */
    .topbar {
      background: white;
      border-radius: 16px 16px 0 0;
      padding: 20px 40px;
      display: flex; align-items: center; justify-content: space-between;
      border-bottom: 1px solid #f0f2f4;
    }
    .logo { font-weight: 900; font-size: 20px; color: #0d1f2d; letter-spacing: -0.5px; }
    .logo-dot { color: #1a9a8a; }
    .nav-faint { font-size: 12px; font-weight: 500; color: #c0cad4; }

    /* ── HERO ── */
    .hero {
      background: white;
      padding: 48px 40px 44px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }
    .hero::before {
      content: '';
      position: absolute; top: -80px; left: -100px;
      width: 380px; height: 380px;
      background: radial-gradient(circle, rgba(160,215,200,0.3) 0%, transparent 68%);
      pointer-events: none;
    }
    .hero::after {
      content: '';
      position: absolute; bottom: -50px; right: -80px;
      width: 300px; height: 300px;
      background: radial-gradient(circle, rgba(160,215,200,0.18) 0%, transparent 68%);
      pointer-events: none;
    }

    /* pill badge — green-lime like screenshot */
    .badge {
      display: inline-flex; align-items: center; gap: 7px;
      background: rgba(175, 235, 185, 0.28);
      border: 1.5px solid rgba(90, 195, 110, 0.4);
      border-radius: 999px;
      padding: 5px 16px;
      font-size: 10px; font-weight: 800;
      letter-spacing: 1.1px; text-transform: uppercase;
      color: #25904a;
      margin-bottom: 28px;
      position: relative; z-index: 1;
    }
    .badge-dot { width: 6px; height: 6px; border-radius: 50%; background: #25904a; flex-shrink: 0; }

    .hero-title {
      font-weight: 900; font-size: 44px; line-height: 1.07;
      letter-spacing: -1.8px; color: #0d1f2d;
      margin-bottom: 18px;
      position: relative; z-index: 1;
    }
    .hero-title .accent { color: #1a9a8a; }

    .hero-sub {
      font-size: 15px; line-height: 1.7; color: #7a8898;
      font-weight: 400; max-width: 390px;
      margin: 0 auto 36px;
      position: relative; z-index: 1;
    }

    .cta-btn {
      display: inline-block;
      background: #147a6e;
      color: white; text-decoration: none;
      font-size: 15px; font-weight: 700;
      padding: 15px 38px; border-radius: 999px;
      letter-spacing: 0.1px;
      position: relative; z-index: 1;
    }

    /* ── BODY ── */
    .body {
      background: white;
      border-top: 1px solid #f2f4f6;
      padding: 36px 40px 40px;
    }

    .greeting { font-size: 17px; font-weight: 800; color: #0d1f2d; margin-bottom: 10px; letter-spacing: -0.3px; }
    .intro { font-size: 14px; line-height: 1.72; color: #7a8898; margin-bottom: 32px; }

    /* features */
    .feat-label {
      font-size: 10px; font-weight: 800; letter-spacing: 1.2px;
      text-transform: uppercase; color: #c8d2da; margin-bottom: 18px;
    }
    .feats { display: flex; flex-direction: column; gap: 18px; margin-bottom: 36px; }
    .feat { display: flex; align-items: flex-start; gap: 14px; }
    .feat-ico {
      width: 40px; height: 40px; border-radius: 11px;
      flex-shrink: 0; display: flex; align-items: center; justify-content: center;
    }
    .feat-ico svg { width: 18px; height: 18px; }
    .feat-ico.green { background: #e8f8f5; color: #1a9a8a; }
    .feat-ico.navy  { background: #edf1f7; color: #0d1f2d; }
    .feat-ico.lime  { background: #eef8ee; color: #25904a; }
    .feat-body { flex: 1; padding-top: 2px; }
    .feat-name { font-size: 14px; font-weight: 800; color: #0d1f2d; margin-bottom: 3px; letter-spacing: -0.2px; }
    .feat-desc { font-size: 13px; line-height: 1.62; color: #9aabb8; }

    /* plan card */
    .plan-card {
      background: #f6fbf9; border: 1.5px solid #ddeee9;
      border-radius: 14px; padding: 18px 20px;
      display: flex; align-items: center; gap: 14px;
      margin-bottom: 36px;
    }
    .plan-ico {
      width: 40px; height: 40px; border-radius: 10px;
      background: #e8f8f5;
      display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .plan-ico svg { width: 18px; height: 18px; color: #1a9a8a; }
    .plan-info { flex: 1; }
    .plan-tag { font-size: 10px; font-weight: 800; letter-spacing: 0.8px; text-transform: uppercase; color: #1a9a8a; margin-bottom: 3px; }
    .plan-name { font-size: 14px; font-weight: 800; color: #0d1f2d; letter-spacing: -0.2px; }
    .plan-sub { font-size: 12px; color: #9aabb8; margin-top: 2px; }
    .plan-cta {
      font-size: 12px; font-weight: 700; color: #1a9a8a;
      text-decoration: none; white-space: nowrap;
      background: #e8f8f5; padding: 8px 14px; border-radius: 8px;
    }

    /* quote */
    .quote {
      background: #f6fbf9; border-radius: 14px;
      border-left: 3px solid #1a9a8a;
      padding: 18px 22px;
    }
    .quote-text {
      font-size: 14px; font-weight: 600; font-style: italic;
      color: #0d1f2d; line-height: 1.65; margin-bottom: 8px; letter-spacing: -0.1px;
    }
    .quote-author { font-size: 11.5px; font-weight: 700; color: #b0bcc8; letter-spacing: 0.3px; }

    /* ── FOOTER ── */
    .footer {
      background: #f4f6f8; border-top: 1px solid #eaeef2;
      border-radius: 0 0 16px 16px;
      padding: 28px 40px; text-align: center;
    }
    .footer-logo { font-weight: 900; font-size: 17px; color: #0d1f2d; letter-spacing: -0.4px; margin-bottom: 14px; display: block; }
    .footer-logo .dot { color: #1a9a8a; }
    .footer-links { display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; margin-bottom: 14px; }
    .footer-link { font-size: 12px; font-weight: 500; color: #9aabb8; text-decoration: none; }
    .footer-copy { font-size: 11px; color: #c0cad4; line-height: 1.65; }
  </style>
</head>
<body>
<div class="wrap">

  <!-- TOPBAR -->
  <div class="topbar">
    <a href="{{ config('app.url') }}" style="display:block; text-decoration:none;">
      <img src="{{ config('app.url') }}/images/logo-viantryp.png" alt="Viantryp" style="height: 24px; width: auto; display: block; border:0;">
    </a>
    <span class="nav-faint">Correo de bienvenida</span>
  </div>

  <!-- HERO -->
  <div class="hero">
    <div class="badge"><span class="badge-dot"></span>✦ Bienvenido a bordo</div>
    <h1 class="hero-title">Tu lienzo para<br>viajes <span class="accent">inolvidables</span></h1>
    <p class="hero-sub">Ya eres parte de Viantryp. En segundos podrás empezar a crear itinerarios que realmente impresionan.</p>
    <a href="{{ route('trips.index') }}" class="cta-btn">Ir a mis viajes →</a>
  </div>

  <!-- BODY -->
  <div class="body">

    <p class="greeting">Hola, {{ $name }} 👋</p>
    <p class="intro">Tu cuenta está activa y lista. Organiza rutas, vuelos y estancias en una plataforma elegante e intuitiva — ya sea para tu próximo viaje personal o para escalar tu negocio.</p>

    <div class="feat-label">Lo que tienes disponible ahora</div>
    <div class="feats">

      <div class="feat">
        <div class="feat-ico green">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
        </div>
        <div class="feat-body">
          <div class="feat-name">Editor visual Drag &amp; Drop</div>
          <div class="feat-desc">Arrastra destinos, vuelos y actividades. Tan fácil como jugar, con resultados profesionales.</div>
        </div>
      </div>

      <div class="feat">
        <div class="feat-ico navy">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
        </div>
        <div class="feat-body">
          <div class="feat-name">Enlace interactivo inteligente</div>
          <div class="feat-desc">Un solo link para tu cliente. Sin apps ni PDFs pesados. Si cambias algo, el enlace se actualiza solo.</div>
        </div>
      </div>

      <div class="feat">
        <div class="feat-ico lime">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
        </div>
        <div class="feat-body">
          <div class="feat-name">Toda tu documentación ordenada</div>
          <div class="feat-desc">Reservas, mapas y archivos en el día que corresponden. Adiós al caos de correos y capturas.</div>
        </div>
      </div>

    </div>

    <!-- Plan -->
    <div class="plan-card">
      <div class="plan-ico">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
      </div>
      <div class="plan-info">
        <div class="plan-tag">Plan activo</div>
        <div class="plan-name">Free Forever</div>
        <div class="plan-sub">Hasta 3 itinerarios · Editor visual incluido</div>
      </div>
      <a href="{{ url('/') }}#precios" class="plan-cta">Mejorar →</a>
    </div>

    <!-- Quote -->
    <div class="quote">
      <div class="quote-text">"Transformamos un proceso que normalmente toma horas en una tarea que se completa en minutos."</div>
      <div class="quote-author">— Equipo Viantryp</div>
    </div>

  </div>

  <!-- FOOTER -->
  <div class="footer">
    <img src="{{ config('app.url') }}/images/logo-viantryp.png" alt="Viantryp" style="height: 18px; width: auto; display: inline-block; opacity: 0.8; margin-bottom: 10px;">
    <div class="footer-links">
      <a href="{{ url('/') }}#como-funciona" class="footer-link">Cómo funciona</a>
      <a href="{{ url('/') }}#precios" class="footer-link">Precios</a>
      <a href="mailto:hola@viantryp.com" class="footer-link">Contacto</a>
    </div>
    <p class="footer-copy">Recibiste este correo porque creaste una cuenta en Viantryp.<br>© {{ date('Y') }} Viantryp · Todos los derechos reservados.</p>
  </div>

</div>
</body>
</html>
