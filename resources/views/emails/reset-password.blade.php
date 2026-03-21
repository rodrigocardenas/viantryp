<!DOCTYPE html>
<html lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Recupera tu contraseña – Viantryp</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      background: #eef1f5;
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
      -webkit-font-smoothing: antialiased;
      padding: 40px 16px 64px;
    }
    .wrap { max-width: 480px; margin: 0 auto; }

    /* TOPBAR */
    .topbar {
      background: white;
      border-radius: 16px 16px 0 0;
      padding: 20px 36px;
      display: flex; align-items: center; justify-content: space-between;
      border-bottom: 1px solid #f0f2f4;
    }
    .logo { font-weight: 900; font-size: 20px; color: #0d1f2d; letter-spacing: -0.5px; }
    .logo-dot { color: #1a9a8a; }
    .topbar-tag { font-size: 11px; font-weight: 700; color: #c0cad4; letter-spacing: 0.3px; }

    /* BODY */
    .body {
      background: white;
      padding: 48px 36px 44px;
      text-align: center;
      position: relative; overflow: hidden;
    }
    /* soft blob top-left */
    .body::before {
      content: '';
      position: absolute; top: -60px; left: -60px;
      width: 280px; height: 280px;
      background: radial-gradient(circle, rgba(160,215,200,0.22) 0%, transparent 70%);
      pointer-events: none;
    }

    /* lock icon circle */
    .icon-wrap {
      width: 64px; height: 64px; border-radius: 18px;
      background: #e8f8f5;
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 28px;
      position: relative; z-index: 1;
    }
    .icon-wrap svg { width: 28px; height: 28px; color: #1a9a8a; }

    .title {
      font-weight: 900; font-size: 28px; line-height: 1.1;
      letter-spacing: -1px; color: #0d1f2d;
      margin-bottom: 12px;
      position: relative; z-index: 1;
    }
    .title .accent { color: #1a9a8a; }

    .subtitle {
      font-size: 14px; line-height: 1.7; color: #7a8898;
      max-width: 340px; margin: 0 auto 36px;
      position: relative; z-index: 1;
    }

    /* CTA button */
    .cta-btn {
      display: inline-block;
      background: #147a6e;
      color: white; text-decoration: none;
      font-size: 15px; font-weight: 700;
      padding: 15px 40px; border-radius: 999px;
      letter-spacing: 0.1px;
      position: relative; z-index: 1;
      margin-bottom: 28px;
    }

    /* expiry note */
    .expiry {
      display: inline-flex; align-items: center; gap: 6px;
      background: #fff8ec;
      border: 1.5px solid #f5dfa0;
      border-radius: 999px;
      padding: 5px 14px;
      font-size: 11px; font-weight: 700;
      color: #a07820;
      position: relative; z-index: 1;
      margin-bottom: 36px;
    }
    .expiry svg { width: 12px; height: 12px; flex-shrink: 0; }

    /* divider */
    .divider { height: 1px; background: #f2f4f6; margin-bottom: 28px; }

    /* fallback link block */
    .link-block {
      background: #f6fbf9;
      border: 1.5px solid #ddeee9;
      border-radius: 12px;
      padding: 16px 20px;
      text-align: left;
      margin-bottom: 28px;
    }
    .link-label {
      font-size: 10px; font-weight: 800;
      letter-spacing: 1px; text-transform: uppercase;
      color: #c8d2da; margin-bottom: 8px;
    }
    .link-url {
      font-size: 12px; font-weight: 500;
      color: #1a9a8a; word-break: break-all;
      line-height: 1.5;
      font-family: monospace;
    }

    /* safety note */
    .safety {
      display: flex; align-items: flex-start; gap: 10px;
      background: #f8f8f8;
      border-radius: 12px;
      padding: 14px 16px;
      text-align: left;
    }
    .safety svg { width: 16px; height: 16px; color: #b0bcc8; flex-shrink: 0; margin-top: 1px; }
    .safety-text { font-size: 12.5px; line-height: 1.6; color: #9aabb8; }
    .safety-text b { color: #64748b; font-weight: 700; }

    /* FOOTER */
    .footer {
      background: #f4f6f8;
      border-top: 1px solid #eaeef2;
      border-radius: 0 0 16px 16px;
      padding: 24px 36px;
      text-align: center;
    }
    .footer-logo { font-weight: 900; font-size: 16px; color: #0d1f2d; letter-spacing: -0.4px; margin-bottom: 10px; display: block; }
    .footer-logo .dot { color: #1a9a8a; }
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
    <span class="topbar-tag">Seguridad de cuenta</span>
  </div>

  <!-- BODY -->
  <div class="body">

    <!-- icon -->
    <div class="icon-wrap">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
      </svg>
    </div>

    <h1 class="title">Recupera tu <span class="accent">contraseña</span></h1>
    <p class="subtitle">
      Recibimos una solicitud para restablecer la contraseña de tu cuenta. Haz clic en el botón para crear una nueva.
    </p>

    <a href="{{ $url }}" class="cta-btn">Restablecer contraseña →</a>
    <br/>

    <!-- expiry pill -->
    <span class="expiry">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      Este enlace expira en {{ $count }} minutos
    </span>

    <div class="divider"></div>

    <!-- fallback link -->
    <div class="link-block">
      <div class="link-label">¿El botón no funciona? Copia este enlace</div>
      <div class="link-url">{{ $url }}</div>
    </div>

    <!-- safety note -->
    <div class="safety">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      <span class="safety-text">
        <b>¿No solicitaste este cambio?</b> Ignora este correo — tu contraseña actual seguirá siendo la misma y tu cuenta está segura.
      </span>
    </div>

  </div>

  <!-- FOOTER -->
  <div class="footer">
    <img src="{{ config('app.url') }}/images/logo-viantryp.png" alt="Viantryp" style="height: 18px; width: auto; display: inline-block; opacity: 0.8; margin-bottom: 10px;">
    <p class="footer-copy">
      Este correo fue enviado a {{ $user_email }} · © {{ date('Y') }} Viantryp<br>
      Si tienes problemas, escríbenos a <a href="mailto:soporte@viantryp.com" style="color:#1a9a8a;text-decoration:none;">soporte@viantryp.com</a>
    </p>
  </div>

</div>
</body>
</html>
