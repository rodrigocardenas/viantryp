<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Viantryp | Registro</title>
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&family=Barlow+Condensed:wght@700;800;900&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --dark:   #0f2a3a;
      --teal:   #02b5cb;
      --teal2:  #136075;
      --teal3:  #0a4552;
      --light:  #f7f9f7;
      --gray:   #64748b;
      --border: #e2e8f0;
      --white:  #ffffff;
    }

    html, body {
      height: 100%;
      font-family: 'Manrope', 'Barlow', sans-serif;
      color: var(--dark);
    }

    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      height: 100vh;
      overflow: hidden;
      background: var(--light);
    }

    /* ─── NAVBAR ─── */
    nav {
      width: 100%;
      background: var(--white);
      border-bottom: 1px solid var(--border);
      height: 64px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 40px;
      position: sticky;
      top: 0;
      z-index: 100;
      flex-shrink: 0;
    }
    .nav-logo { text-decoration: none; display: flex; align-items: center; }
    .nav-logo-text {
      font-family: 'Barlow Condensed', sans-serif;
      font-weight: 900;
      font-size: 26px;
      color: var(--dark);
      letter-spacing: -0.5px;
      line-height: 1;
    }
    .nav-logo-text .dot { color: var(--teal); }
    .nav-links {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .nav-link {
      text-decoration: none;
      color: #1e293b;
      font-family: 'Manrope', sans-serif;
      font-size: 14px !important;
      font-weight: 600;
      padding: 7px 14px;
      border-radius: 8px;
      transition: background 0.18s, color 0.18s;
    }
    .nav-link:hover { background: var(--light); color: var(--teal); }
    .nav-right { display: flex; align-items: center; gap: 8px; }
    .nav-back {
      text-decoration: none;
      color: var(--dark);
      font-size: 15px;
      font-weight: 600;
      padding: 8px 18px;
      border-radius: 50px;
      border: 1.5px solid var(--border);
      background: var(--white);
      display: flex;
      align-items: center;
      gap: 6px;
      transition: border-color 0.18s, color 0.18s, background 0.18s;
    }
    .nav-back:hover { border-color: var(--teal); color: var(--teal); background: #f0faf9; }
    .nav-back svg { width: 15px; height: 15px; }

    /* Responsive Nav (similar to landing) */
    @media (max-width: 768px) {
      nav { padding: 1rem; justify-content: space-between; height: auto; }
      .nav-links { display: none; }
      .nav-logo img { height: 26px !important; }
      .nav-back {
        font-size: 12px !important;
        padding: 0.4rem 0.6rem !important;
        white-space: nowrap;
      }
      .nav-right { gap: 0.1rem; margin-right: 0.5rem; }
    }

    /* ─── SPLIT LAYOUT ─── */
    .page-body {
      flex: 1;
      display: grid;
      grid-template-columns: 1fr 1fr;
      height: calc(100vh - 64px);
      min-height: 0;
      overflow: hidden;
    }

    /* ── LEFT PANEL ── */
    .left-panel {
      background: var(--dark);
      position: relative;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      gap: 0;
      padding: 38px 52px 24px;
      height: 100%;
      min-height: 0;
    }

    /* Decorative circles */
    .left-panel::before {
      content: '';
      position: absolute;
      width: 480px; height: 480px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(2,181,203,0.22) 0%, transparent 70%);
      top: -120px; right: -120px;
      pointer-events: none;
    }
    .left-panel::after {
      content: '';
      position: absolute;
      width: 320px; height: 320px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(2,181,203,0.14) 0%, transparent 70%);
      bottom: -80px; left: -80px;
      pointer-events: none;
    }

    /* Teal accent bar */
    .accent-bar {
      width: 48px; height: 5px;
      background: var(--teal);
      border-radius: 3px;
      margin-top: 6px;
      margin-bottom: 22px;
    }

    h1, .left-headline {
      font-family: 'Manrope', sans-serif !important;
      font-weight: 900;
      font-size: 40px;
      line-height: 1.08;
      color: var(--white);
      letter-spacing: -1.5px;
      margin-bottom: 20px;
      position: relative;
      z-index: 1;
    }
    .left-headline .hl { color: var(--teal); }

    .left-sub {
      font-size: 15.5px;
      color: rgba(255,255,255,0.65);
      line-height: 1.55;
      position: relative;
      z-index: 1;
      margin-bottom: 34px;
    }

    /* Perks list */
    .perks {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 20px;
      position: relative;
      z-index: 1;
    }
    .perk {
      display: flex;
      align-items: flex-start;
      gap: 14px;
    }
    .perk-icon {
      width: 24px;
      height: 24px;
      background: transparent;
      border: none;
      border-radius: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      margin-top: 2px;
      color: #02b5cb;
    }
    .perk-icon svg {
      width: 22px;
      height: 22px;
      stroke: #02b5cb;
    }
    .perk-text strong {
      display: block;
      color: var(--white);
      font-size: 15px;
      font-weight: 700;
      margin-bottom: 3px;
    }
    .perk-text span {
      color: rgba(255,255,255,0.55);
      font-size: 13.5px;
      line-height: 1.45;
    }

    /* ── RIGHT PANEL ── */
    .right-panel {
      display: flex;
      align-items: flex-start;
      justify-content: center;
      padding: 38px 52px 24px;
      height: 100%;
      overflow: hidden;
    }

    .form-wrapper {
      width: 100%;
      max-width: 420px;
      animation: fadeUp 0.45s ease both;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(18px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .form-header {
      margin-bottom: 18px;
    }
    .form-header h2 {
      font-family: 'Manrope', sans-serif !important;
      font-weight: 900;
      font-size: 30px;
      color: var(--dark);
      letter-spacing: -1.2px;
      line-height: 1;
      white-space: nowrap;
    }
    .form-header h2 span { color: var(--teal); }

    /* Google first */
    .btn-google {
      width: 100%; height: 43px; background: var(--white);
      text-decoration: none;
      border: 1.5px solid var(--border); border-radius: 50px;
      font-size: 14.5px; font-weight: 600;
      font-family: 'Manrope', 'Barlow', sans-serif;
      color: var(--dark); cursor: pointer;
      display: flex; align-items: center; justify-content: center; gap: 10px;
      transition: border-color 0.18s, box-shadow 0.18s, transform 0.14s;
      box-shadow: 0 2px 8px rgba(13,43,62,0.06);
    }
    .btn-google:hover {
      border-color: var(--teal);
      box-shadow: 0 4px 14px rgba(2,181,203,0.15);
      transform: translateY(-1px);
    }

    .btn-apple {
      width: 100%; height: 43px; background: #000000;
      text-decoration: none;
      border: 1.5px solid #000000; border-radius: 50px;
      font-size: 14.5px; font-weight: 600;
      font-family: 'Manrope', 'Barlow', sans-serif;
      color: #ffffff; cursor: pointer;
      display: none; align-items: center; justify-content: center; gap: 10px;
      margin-bottom: 10px;
      transition: opacity 0.18s, box-shadow 0.18s, transform 0.14s;
      box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    }
    .btn-apple:hover {
      opacity: 0.9;
      box-shadow: 0 4px 14px rgba(0,0,0,0.18);
      transform: translateY(-1px);
    }

    html.is-viantryp-ios .google-btn-container {
      display: none !important;
    }

    /* Exclusivo iOS: Safe areas y prevención de auto-zoom en WKWebView (sin afectar Android ni Web) */
    @media (max-width: 768px) {
      html.is-viantryp-ios {
        -webkit-text-size-adjust: 100%;
        -webkit-tap-highlight-color: transparent;
      }
      html.is-viantryp-ios nav {
        padding-top: calc(1rem + env(safe-area-inset-top, 0px)) !important;
      }
      html.is-viantryp-ios .right-panel {
        padding-bottom: calc(28px + env(safe-area-inset-bottom, 0px)) !important;
      }
      html.is-viantryp-ios input:not([type="checkbox"]):not([type="radio"]),
      html.is-viantryp-ios select,
      html.is-viantryp-ios textarea {
        font-size: max(16px, 1em) !important;
      }
    }

    .divider {
      display: flex; align-items: center; gap: 12px;
      margin: 10px 0; color: #b0bec5; font-size: 12.5px; font-weight: 500;
    }
    .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }

    /* Fields */
    .field { margin-bottom: 10px; }
    .field label {
      display: block;
      font-size: 12.5px;
      font-weight: 600;
      color: var(--dark);
      margin-bottom: 4px;
      letter-spacing: 0.2px;
    }
    .input-wrap { position: relative; }
    .input-wrap input {
      width: 100%;
      height: 41px;
      border: 1.5px solid var(--border);
      border-radius: 10px;
      padding: 0 42px 0 14px;
      font-size: 14px;
      font-family: 'Manrope', 'Barlow', sans-serif;
      color: var(--dark);
      background: var(--white);
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    .input-wrap input::placeholder { color: #b0bec5; }
    .input-wrap input:focus {
      border-color: var(--teal);
      box-shadow: 0 0 0 3px rgba(2,181,203,0.12);
    }
    .input-wrap.has-error input { border-color: #e74c3c; }
    .input-wrap.has-error input:focus { box-shadow: 0 0 0 3px rgba(231,76,60,0.13); }
    .error-msg { color: #e74c3c; font-size: 11.5px; font-weight: 600; margin-top: 4px; display: block; }
    
    .general-error {
      background: #fdf2f2; border: 1px solid #fabebb; padding: 10px 14px; border-radius: 10px;
      margin-bottom: 12px; display: flex; align-items: center; gap: 10px; color: #c0392b; font-size: 12.5px; font-weight: 600;
    }
    
    .input-wrap input.success { border-color: #27ae60; }
    .input-icon {
      position: absolute;
      right: 13px; top: 50%;
      transform: translateY(-50%);
      color: #02b5cb;
      background: transparent;
      display: flex;
    }
    .toggle-pw {
      background: transparent; border: none; cursor: pointer;
      padding: 0; display: flex; color: #02b5cb;
      transition: color 0.2s;
    }
    .toggle-pw:hover { color: var(--teal2); }

    /* Password strength (fixed height so column never shifts up/down) */
    .pw-strength {
      margin-top: 5px;
      display: block;
    }
    .strength-bars {
      display: flex; gap: 4px; margin-bottom: 4px;
    }
    .strength-bar {
      height: 3px; flex: 1; border-radius: 2px;
      background: var(--border);
      transition: background 0.3s;
    }
    .strength-bar.weak   { background: #e74c3c; }
    .strength-bar.medium { background: #f39c12; }
    .strength-bar.strong { background: #27ae60; }
    .strength-label { display: none; }

    /* Reqs */
    .pw-reqs {
      margin-top: 3px;
      display: flex;
      align-items: center;
      gap: 12px;
      flex-wrap: nowrap;
    }
    .req {
      font-size: 11px;
      color: #94a3b8;
      display: flex;
      align-items: center;
      gap: 4px;
      transition: color 0.2s;
      font-weight: 500;
    }
    .req.met { color: #02b5cb; font-weight: 600; }
    .req-dot {
      width: 5px; height: 5px; border-radius: 50%;
      background: currentColor; flex-shrink: 0;
    }

    /* Terms */
    .terms-row {
      display: flex;
      align-items: flex-start;
      gap: 9px;
      margin: 10px 0 12px;
    }
    .terms-row input[type=checkbox] {
      width: 15px; height: 15px;
      accent-color: var(--teal);
      cursor: pointer;
      margin-top: 2px;
      flex-shrink: 0;
    }
    .terms-row label {
      font-size: 12.5px;
      color: var(--gray);
      line-height: 1.4;
      cursor: pointer;
    }
    .terms-row a { color: var(--teal); font-weight: 600; text-decoration: none; }
    .terms-row a:hover { color: var(--teal2); }

    /* Submit */
    .btn-primary {
      width: 100%; height: 44px;
      background: var(--teal);
      color: white; border: none; border-radius: 50px;
      font-size: 15px; font-weight: 700;
      font-family: 'Manrope', 'Barlow', sans-serif;
      cursor: pointer;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      transition: background 0.18s, transform 0.14s, box-shadow 0.18s;
      box-shadow: 0 4px 16px rgba(2,181,203,0.3);
    }
    .btn-primary:hover {
      background: var(--teal2);
      transform: translateY(-1px);
      box-shadow: 0 8px 24px rgba(2,181,203,0.4);
    }
    .btn-primary:active { transform: translateY(0); }

    .login-row {
      text-align: center;
      margin-top: 10px;
      font-size: 13.5px;
      color: var(--gray);
    }
    .login-row a {
      color: var(--teal); font-weight: 700;
      text-decoration: none; transition: color 0.2s;
    }
    .login-row a:hover { color: var(--teal2); }

    /* ─── RESPONSIVE ─── */
    @media (max-width: 800px) {
      body { height: auto; overflow-y: auto; }
      .page-body { height: auto; overflow: visible; grid-template-columns: 1fr; }
      .left-panel { display: none; }
      .right-panel { height: auto; overflow: visible; padding: 32px 24px; }
    }
  </style>
</head>
<body>

  <!-- ── NAVBAR ── -->
  <nav>
    <a href="{{ route('home') }}" class="nav-logo">
      <img src="{{ asset('images/logo-viantryp-cyan.png') }}" alt="Viantryp" style="height: 32px; width: auto;">
    </a>
    <div class="nav-links">
      <a href="{{ route('home') }}#como-funciona" class="nav-link">Cómo funciona</a>
      <a href="{{ route('home') }}#precios"       class="nav-link">Precios</a>
      <a href="{{ route('contact') }}"           class="nav-link">Contacto</a>
    </div>
    <div class="nav-right">
      <a href="{{ route('home') }}" class="nav-back">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M19 12H5M5 12l7 7M5 12l7-7"/>
        </svg>
        Volver al inicio
      </a>
    </div>
  </nav>

  <!-- ── SPLIT BODY ── -->
  <div class="page-body">

    <!-- LEFT: Brand Panel -->
    <div class="left-panel">

      <div>
        <div class="accent-bar"></div>
        <h1 class="left-headline">
          Diseña<br/>
          itinerarios<br/>
          <span class="hl">impactantes</span><br/>
          en minutos.
        </h1>
        <p class="left-sub">
          La plataforma que redefine el diseño de itinerario de viajes. Sin complicaciones técnicas. Sin horas perdidas en diseño manual.
        </p>
      </div>

      <ul class="perks">
        <li class="perk">
          <div class="perk-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
            </svg>
          </div>
          <div class="perk-text">
            <strong>Itinerarios en tan solo minutos</strong>
            <span> Organiza destinos, estancias y trayectos en un lienzo que entiende el ritmo de tus viajes.</span>
          </div>
        </li>
        <li class="perk">
          <div class="perk-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="18" height="18" rx="3"/>
              <path d="M3 9h18M9 21V9"/>
            </svg>
          </div>
          <div class="perk-text">
            <strong>Sincronización total en un solo lugar</strong>
            <span>Archivos, soportes y guías vinculados directamente a tu itinerario y siempre accesibles.</span>
          </div>
        </li>
        <li class="perk">
          <div class="perk-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
              <circle cx="9" cy="7" r="4"/>
              <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
          </div>
          <div class="perk-text">
            <strong>Compartir en tiempo real</strong>
            <span>Un enlace con el que puedas acceder a tu itinerario sin importar dónde estés.</span>
          </div>
        </li>
      </ul>

    </div>

    <!-- RIGHT: Form -->
    <div class="right-panel">
      <div class="form-wrapper">

        <div class="form-header">
          <h2>Crea tu cuenta <span>ahora</span></h2>
        </div>

        <!-- Apple / Google first (conversion best practice) -->
        <button type="button" id="btnAppleAuthRegister" class="btn-apple" onclick="doNativeCapacitorAppleAuth()">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
            <path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09l.01-.01zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/>
          </svg>
          <span>Registrarse con Apple</span>
        </button>

        <div class="google-btn-container" style="position: relative; width: 100%;">
          <a href="{{ route('auth.google', ['app' => request('app', '1')]) }}" class="btn-google" id="btnGoogleAuthRegister">
            <svg width="19" height="19" viewBox="0 0 24 24">
              <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
              <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
              <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
              <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            <span>Registrarse con Google</span>
          </a>
          <div id="g_id_signin_layer_register" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0.001; z-index: 10; overflow: hidden; pointer-events: auto;"></div>
        </div>

        <div class="divider">O con tu correo</div>

        <form method="POST" action="{{ route('register') }}">
          @csrf

          @if($errors->has('general'))
            <div class="general-error">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
              </svg>
              <span>{{ $errors->first('general') }}</span>
            </div>
          @endif

          <!-- Name -->
          <div class="field">
            <label for="name">Nombre completo</label>
            <div class="input-wrap @error('name') has-error @enderror">
              <input id="name" name="name" type="text" placeholder="Tu nombre completo" autocomplete="name" value="{{ old('name') }}" required />
              <span class="input-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                  <circle cx="12" cy="7" r="4"/>
                </svg>
              </span>
            </div>
            @error('name')
              <span class="error-msg">{{ $message }}</span>
            @enderror
          </div>

          <!-- Email -->
          <div class="field">
            <label for="email">Correo electrónico</label>
            <div class="input-wrap @error('email') has-error @enderror">
              <input id="email" name="email" type="email" placeholder="tu@email.com" autocomplete="email" value="{{ old('email') }}" required />
              <span class="input-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M4 4h16c1.1 0 2 .9 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2z"/>
                  <polyline points="22,6 12,13 2,6"/>
                </svg>
              </span>
            </div>
            @error('email')
              <span class="error-msg">{{ $message }}</span>
            @enderror
          </div>

          <!-- Password -->
          <div class="field">
            <label for="password">Contraseña</label>
            <div class="input-wrap @error('password') has-error @enderror">
              <input id="password" name="password" type="password" placeholder="Mínimo 8 caracteres, 1 mayúscula y 1 símbolo" autocomplete="new-password" oninput="checkPw(this.value)" required />
              <button class="toggle-pw input-icon" onclick="togglePw('password','eye1')" type="button">
                <svg id="eye1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                  <circle cx="12" cy="12" r="3"/>
                </svg>
              </button>
            </div>
            @error('password')
              <span class="error-msg">{{ $message }}</span>
            @enderror
            <!-- Strength meter -->
            <div class="pw-strength" id="pw-strength">
              <div class="strength-bars">
                <div class="strength-bar" id="bar1"></div>
                <div class="strength-bar" id="bar2"></div>
                <div class="strength-bar" id="bar3"></div>
              </div>
              <div class="strength-label" id="strength-label">Escribe tu contraseña</div>
              <div class="pw-reqs">
                <div class="req" id="req-len"><span class="req-dot"></span>8 caracteres</div>
                <div class="req" id="req-upper"><span class="req-dot"></span>1 Mayúscula</div>
                <div class="req" id="req-special"><span class="req-dot"></span>1 Símbolo</div>
              </div>
            </div>
          </div>

          <!-- Confirm password -->
          <div class="field">
            <label for="confirm">Confirmar contraseña</label>
            <div class="input-wrap @error('password_confirmation') has-error @enderror">
              <input id="confirm" name="password_confirmation" type="password" placeholder="Repite tu contraseña" autocomplete="new-password" required />
              <button class="toggle-pw input-icon" onclick="togglePw('confirm','eye2')" type="button">
                <svg id="eye2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                  <circle cx="12" cy="12" r="3"/>
                </svg>
              </button>
            </div>
            @error('password_confirmation')
              <span class="error-msg">{{ $message }}</span>
            @enderror
          </div>

          <!-- Terms -->
          <div class="terms-row">
            <input type="checkbox" id="terms" name="terms" required />
            <label for="terms">
              Acepto los <a href="{{ route('terms') }}" target="_blank">Términos y Condiciones</a> y la <a href="{{ route('privacy') }}" target="_blank">Política de Privacidad</a> de Viantryp
            </label>
          </div>
          @error('terms')
            <span class="error-msg" style="margin-top: -10px; margin-bottom: 20px; display: block;">{{ $message }}</span>
          @enderror

          <button class="btn-primary" type="submit">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
              <circle cx="8.5" cy="7" r="4"/>
              <line x1="20" y1="8" x2="20" y2="14"/>
              <line x1="23" y1="11" x2="17" y2="11"/>
            </svg>
            Crear cuenta gratis
          </button>
        </form>

        <div class="login-row">
          ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión →</a>
        </div>

      </div>
    </div>
  </div>

  {{-- Google Identity Services SDK --}}
  <script src="https://accounts.google.com/gsi/client" async defer></script>

  <script>
    function togglePw(inputId, iconId) {
      const input = document.getElementById(inputId);
      const icon  = document.getElementById(iconId);
      if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>';
      } else {
        input.type = 'password';
        icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
      }
    }

    function checkPw(val) {
      const strength = document.getElementById('pw-strength');
      strength.classList.toggle('show', val.length > 0);

      const reqs = {
        len:     val.length >= 8,
        upper:   /[A-Z]/.test(val),
        special: /[^A-Za-z0-9]/.test(val)
      };

      Object.entries(reqs).forEach(([key, met]) => {
        const el = document.getElementById('req-' + key);
        if (el) el.classList.toggle('met', met);
      });

      const score = Object.values(reqs).filter(Boolean).length;
      const bars = [document.getElementById('bar1'), document.getElementById('bar2'), document.getElementById('bar3')];
      const label = document.getElementById('strength-label');

      bars.forEach(b => b.className = 'strength-bar');

      if (score === 1) {
        bars[0].classList.add('weak');
        label.textContent = 'Débil';
        label.style.color = '#e74c3c';
      } else if (score === 2) {
        bars[0].classList.add('medium'); bars[1].classList.add('medium');
        label.textContent = 'Regular';
        label.style.color = '#f39c12';
      } else if (score === 3) {
        bars.forEach(b => b.classList.add('strong'));
        label.textContent = '¡Contraseña segura!';
        label.style.color = '#27ae60';
      } else {
        label.textContent = 'Escribe tu contraseña';
        label.style.color = 'var(--gray)';
      }
    }

    const clientId = '{{ config("services.google.client_id", "68250907387-5t11umbj4m0h0qr9p013l48uqof74orn.apps.googleusercontent.com") }}';

    // Manejador nativo de Google Auth para la App Capacitor
    async function doNativeCapacitorGoogleAuth() {
      const googleBtn = document.getElementById('btnGoogleAuthRegister') || document.querySelector('.btn-google');
      if (googleBtn) {
        googleBtn.style.opacity = '0.6';
        googleBtn.style.pointerEvents = 'none';
      }

      try {
        let GoogleAuth = window.Capacitor?.Plugins?.GoogleAuth;
        if (!GoogleAuth && window.Capacitor?.registerPlugin) {
          GoogleAuth = window.Capacitor.registerPlugin('GoogleAuth');
        }

        if (GoogleAuth) {
          try {
            await GoogleAuth.initialize({
              clientId: clientId,
              scopes: 'profile,email',
              grantOfflineAccess: false
            });
          } catch(initErr) {
            console.warn('GoogleAuth.initialize notice:', initErr);
          }

          try {
            await GoogleAuth.signOut();
          } catch (signOutErr) {
            // Ignorar si no había sesión previa de Google
          }

          const googleUser = await GoogleAuth.signIn();
          if (googleUser) {
            const idToken = (googleUser.authentication && googleUser.authentication.idToken) || googleUser.idToken;
            const email = googleUser.email || (googleUser.profile && googleUser.profile.email);
            const googleId = googleUser.id || googleUser.userId || (googleUser.profile && googleUser.profile.id);
            const givenName = googleUser.givenName || googleUser.name || (googleUser.profile && googleUser.profile.givenName) || '';
            const familyName = googleUser.familyName || (googleUser.profile && googleUser.profile.familyName) || '';
            const imageUrl = googleUser.imageUrl || (googleUser.profile && googleUser.profile.imageUrl) || '';

            if (idToken || email) {
              const res = await fetch('/auth/google/native', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                  'Content-Type': 'application/json',
                  'Accept': 'application/json',
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                  idToken: idToken,
                  credential: idToken,
                  email: email,
                  google_id: googleId,
                  givenName: givenName,
                  familyName: familyName,
                  imageUrl: imageUrl
                })
              });

              const resData = await res.json();
              if (resData.success && resData.redirect) {
                try { localStorage.setItem('viantryp_app_mode', '1'); } catch (e) { }
                window.location.href = resData.redirect;
                return;
              } else {
                alert(resData.message || 'Error al registrarse con Google.');
              }
            }
          }
        }
      } catch (err) {
        console.warn('Native Google Auth cancelled/failed:', err);
      } finally {
        if (googleBtn) {
          googleBtn.style.opacity = '1';
          googleBtn.style.pointerEvents = 'auto';
        }
      }
    }

    // Manejador nativo de Sign in with Apple para la App Capacitor en iOS
    async function doNativeCapacitorAppleAuth() {
      const appleBtn = document.getElementById('btnAppleAuthRegister');
      if (appleBtn) {
        appleBtn.style.opacity = '0.6';
        appleBtn.style.pointerEvents = 'none';
      }

      try {
        let SignInWithApple = window.Capacitor?.Plugins?.SignInWithApple;
        if (!SignInWithApple && window.Capacitor?.registerPlugin) {
          SignInWithApple = window.Capacitor.registerPlugin('SignInWithApple');
        }

        if (!SignInWithApple) {
          alert('El registro nativo con Apple solo está disponible desde la app de iOS.');
          return;
        }

        const result = await SignInWithApple.authorize({
          clientId: 'com.viantryp.app',
          redirectURI: 'https://viantryp.com/register',
          scopes: 'email name'
        });

        const response = result && result.response ? result.response : result;
        if (!response || !response.identityToken) {
          return;
        }

        const res = await fetch('/auth/apple/native', {
          method: 'POST',
          credentials: 'same-origin',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({
            identityToken: response.identityToken,
            user: response.user || null,
            email: response.email || null,
            givenName: response.givenName || null,
            familyName: response.familyName || null
          })
        });

        if (!res.ok) {
          alert('Error en el servidor al registrarse con Apple.');
          return;
        }

        const resData = await res.json();
        if (resData.success && resData.redirect) {
          try { localStorage.setItem('viantryp_app_mode', '1'); } catch (e) {}
          window.location.href = resData.redirect;
          return;
        } else {
          alert(resData.message || 'Error al registrarse con Apple.');
        }
      } catch (err) {
        const errMsg = (err && (err.message || err.errorMessage || String(err))) || '';
        const isCancel = errMsg.toLowerCase().includes('cancel') || errMsg.includes('1001');
        if (!isCancel) {
          console.error('Apple Auth error:', err);
          alert('Error al registrarse con Apple. Por favor, intenta de nuevo.');
        }
      } finally {
        if (appleBtn) {
          appleBtn.style.opacity = '1';
          appleBtn.style.pointerEvents = 'auto';
        }
      }
    }

    document.addEventListener('DOMContentLoaded', function() {
      const googleBtn = document.getElementById('btnGoogleAuthRegister') || document.querySelector('.btn-google');
      const appleBtn = document.getElementById('btnAppleAuthRegister');
      const isNativeApp = Boolean(window.Capacitor && (window.Capacitor.isNativePlatform || window.Capacitor.Plugins));
      const isIosPlatform = (window.Capacitor && typeof window.Capacitor.getPlatform === 'function' && window.Capacitor.getPlatform() === 'ios')
        || (isNativeApp && /iPad|iPhone|iPod/.test(navigator.userAgent))
        || new URLSearchParams(window.location.search).get('ios') === '1';

      if (isIosPlatform) {
        document.documentElement.classList.add('is-viantryp-ios');
        if (document.body) document.body.classList.add('is-viantryp-ios');
        const vp = document.querySelector('meta[name="viewport"]');
        if (vp && !vp.content.includes('viewport-fit')) {
          vp.setAttribute('content', vp.content + ', viewport-fit=cover');
        }
        if (appleBtn) appleBtn.style.display = 'flex';
      }

      if (isNativeApp) {
        // En la App Nativa: Ocultamos la capa GSI web para que el toque siempre vaya al plugin nativo de Android
        const layer = document.getElementById('g_id_signin_layer_register');
        if (layer) layer.style.display = 'none';

        if (googleBtn) {
          googleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            doNativeCapacitorGoogleAuth();
          });
        }
      } else {
        // En Navegador Web de escritorio / móvil: Usamos Google Identity Services (GSI)
        function initGsi() {
          if (window.google && window.google.accounts && window.google.accounts.id) {
            window.google.accounts.id.initialize({
              client_id: clientId,
              ux_mode: 'redirect',
              login_uri: '{{ url("/auth/google/callback") }}',
              auto_select: false,
              cancel_on_tap_outside: true,
              context: 'signup'
            });

            const layer = document.getElementById('g_id_signin_layer_register');
            if (layer) {
              window.google.accounts.id.renderButton(layer, {
                theme: 'outline',
                size: 'large',
                width: 380,
                type: 'standard',
                shape: 'pill'
              });
            }

            window.google.accounts.id.prompt();
          } else {
            setTimeout(initGsi, 200);
          }
        }
        initGsi();

        if (googleBtn) {
          googleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (window.google && window.google.accounts && window.google.accounts.id) {
              window.google.accounts.id.prompt();
            } else {
              window.location.href = "{{ route('auth.google') }}";
            }
          });
        }
      }
    });
  </script>
</body>
</html>
