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
      background: #f8fafc;
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      -webkit-font-smoothing: antialiased;
      padding: 48px 16px;
      color: #334155;
    }

    .wrap { max-width: 580px; margin: 0 auto; background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }

    /* ── HEADER ── */
    .header {
      padding: 40px 40px 20px;
      text-align: center;
    }
    .logo-img { height: 36px; width: auto; display: inline-block; border: 0; }

    /* ── BODY ── */
    .body {
      padding: 0 40px 40px;
      background: white;
    }

    .greeting { font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 12px; letter-spacing: -0.4px; }
    .intro { font-size: 15px; line-height: 1.6; color: #64748b; margin-bottom: 40px; }

    /* FEATURES */
    .feat-section { margin-bottom: 40px; }
    .feat-label {
      font-size: 12px; font-weight: 800; letter-spacing: 1.2px;
      text-transform: uppercase; color: #94a3b8; margin-bottom: 24px;
    }
    .feats { display: flex; flex-direction: column; gap: 16px; }
    
    .feat-card {
      background: #fbfcfd;
      border: 1px solid #f1f5f9;
      border-radius: 12px;
      padding: 20px;
      display: flex;
      align-items: flex-start;
      gap: 16px;
    }
    .feat-ico {
      width: 40px; height: 40px; border-radius: 10px;
      flex-shrink: 0; display: flex; align-items: center; justify-content: center;
    }
    .feat-ico svg { width: 20px; height: 20px; }
    .feat-ico.green { background: #e0f2f1; color: #1a9a8a; }
    .feat-ico.navy  { background: #e2e8f0; color: #0f172a; }
    .feat-ico.lime  { background: #f0f9f1; color: #25904a; }
    
    .feat-body { flex: 1; }
    .feat-name { font-size: 15px; font-weight: 800; color: #0f172a; margin-bottom: 4px; }
    .feat-desc { font-size: 14px; line-height: 1.55; color: #64748b; }

    /* CTA */
    .cta-area { text-align: center; padding-top: 10px; }
    .cta-btn {
      display: inline-block;
      background: #1a9a8a;
      color: #ffffff !important;
      text-decoration: none;
      font-size: 16px; font-weight: 700;
      padding: 16px 48px; border-radius: 12px;
      box-shadow: 0 4px 12px rgba(26, 154, 138, 0.2);
    }

    /* ── FOOTER ── */
    .footer {
      background: #f8fafc;
      border-top: 1px solid #f1f5f9;
      padding: 40px;
      text-align: center;
    }
    .footer-links {
      display: table;
      width: 100%;
      margin-bottom: 32px;
      border-collapse: separate;
      border-spacing: 12px 0;
    }
    .footer-link-cell {
      display: table-cell;
      width: 33.33%;
      padding: 12px;
      background: white;
      border: 1px solid #f1f5f9;
      border-radius: 8px;
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
      margin-top: 24px;
      font-size: 13px;
      color: #64748b;
    }
    .footer-contact a { color: #1a9a8a; text-decoration: none; font-weight: 600; }
  </style>
</head>
<body>
<div class="wrap">

  <!-- HEADER -->
  <div class="header">
    <a href="{{ config('app.url') }}" style="text-decoration:none;">
      <img src="{{ config('app.url') }}/images/logo-viantryp-clean.png" alt="Viantryp" class="logo-img">
    </a>
  </div>

  <!-- BODY -->
  <div class="body">

    <p class="greeting">Hola, {{ $name }} 👋</p>
    <p class="intro">Tu cuenta ha sido creada exitosamente. Estamos emocionados de ayudarte a transformar la forma en que planificas y compartes tus <strong>viajes</strong>.</p>

    <div class="feat-section">
      <div class="feat-label">Qué puedes hacer ahora</div>
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

    <!-- CTA -->
    <div class="cta-area">
      <a href="{{ route('trips.index') }}" class="cta-btn">Ir a mis viajes →</a>
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
