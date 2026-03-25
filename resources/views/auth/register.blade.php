<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Viantryp | Registro</title>
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
  <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800;900&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --dark:   #0d2b3e;
      --teal:   #1a7a8a;
      --teal2:  #0e5a6a;
      --teal3:  #0a4552;
      --light:  #f5f7f9;
      --gray:   #6b7a8d;
      --border: #e2e8ef;
      --white:  #ffffff;
    }

    html, body {
      height: 100%;
      font-family: 'Barlow', sans-serif;
      color: var(--dark);
    }

    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
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
      color: var(--dark);
      font-size: 15px;
      font-weight: 500;
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
      min-height: 0;
    }

    /* ── LEFT PANEL ── */
    .left-panel {
      background: var(--dark);
      position: relative;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      justify-content: center;
      gap: 0;
      padding: 56px 52px;
      min-height: 600px;
    }

    /* Decorative circles */
    .left-panel::before {
      content: '';
      position: absolute;
      width: 480px; height: 480px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(26,158,143,0.28) 0%, transparent 70%);
      top: -120px; right: -120px;
      pointer-events: none;
    }
    .left-panel::after {
      content: '';
      position: absolute;
      width: 320px; height: 320px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(26,158,143,0.18) 0%, transparent 70%);
      bottom: -80px; left: -80px;
      pointer-events: none;
    }

    /* Teal accent bar */
    .accent-bar {
      width: 48px; height: 5px;
      background: var(--teal);
      border-radius: 3px;
      margin-bottom: 28px;
    }

    .left-headline {
      font-family: 'Inter', sans-serif;
      font-weight: 800;
      font-size: 42px;
      line-height: 0.95;
      color: var(--white);
      letter-spacing: -1px;
      margin-bottom: 28px;
      position: relative;
      z-index: 1;
    }
    .left-headline .hl { color: var(--teal); }

    .left-sub {
      font-size: 16px;
      color: rgba(255,255,255,0.6);
      line-height: 1.6;
      position: relative;
      z-index: 1;
      margin-bottom: 44px;
    }

    /* Perks list */
    .perks {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 24px;
      position: relative;
      z-index: 1;
    }
    .perk {
      display: flex;
      align-items: flex-start;
      gap: 14px;
    }
    .perk-icon {
      width: 42px; height: 42px;
      border-radius: 12px;
      background: rgba(26,158,143,0.2);
      border: 1px solid rgba(26,158,143,0.35);
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .perk-icon svg { width: 20px; height: 20px; stroke: var(--teal); }
    .perk-text strong {
      display: block;
      color: var(--white);
      font-size: 15px;
      font-weight: 700;
      margin-bottom: 4px;
    }
    .perk-text span {
      color: rgba(255,255,255,0.5);
      font-size: 14px;
      line-height: 1.5;
    }

    /* ── RIGHT PANEL ── */
    .right-panel {
      display: flex;
      align-items: flex-start;
      justify-content: center;
      padding: 56px 52px;
      overflow-y: auto;
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
      margin-bottom: 28px;
    }
    .form-header h2 {
      font-family: 'Inter', sans-serif;
      font-weight: 900;
      font-size: 28px;
      color: var(--dark);
      letter-spacing: -0.5px;
      line-height: 1.1;
    }
    .form-header h2 span { color: var(--teal); }
    .form-header p {
      margin-top: 8px;
      color: var(--gray);
      font-size: 15px;
    }

    /* Google first */
    .btn-google {
      width: 100%; height: 50px; background: var(--white);
      text-decoration: none;
      border: 1.5px solid var(--border); border-radius: 50px;
      font-size: 15px; font-weight: 600;
      font-family: 'Barlow', sans-serif;
      color: var(--dark); cursor: pointer;
      display: flex; align-items: center; justify-content: center; gap: 10px;
      transition: border-color 0.18s, box-shadow 0.18s, transform 0.14s;
      box-shadow: 0 2px 8px rgba(13,43,62,0.06);
    }
    .btn-google:hover {
      border-color: var(--teal);
      box-shadow: 0 4px 14px rgba(26,158,143,0.15);
      transform: translateY(-1px);
    }

    .divider {
      display: flex; align-items: center; gap: 12px;
      margin: 20px 0; color: #b0bec5; font-size: 13px; font-weight: 500;
    }
    .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }

    /* Fields */
    .field { margin-bottom: 16px; }
    .field label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: var(--dark);
      margin-bottom: 6px;
    }
    .input-wrap { position: relative; }
    .input-wrap input {
      width: 100%;
      height: 46px;
      border: 1.5px solid var(--border);
      border-radius: 10px;
      padding: 0 42px 0 14px;
      font-size: 14px;
      font-family: 'Barlow', sans-serif;
      color: var(--dark);
      background: var(--white);
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    .input-wrap input::placeholder { color: #b0bec5; }
    .input-wrap input:focus {
      border-color: var(--teal);
      box-shadow: 0 0 0 3px rgba(26,158,143,0.12);
    }
    .input-wrap.has-error input { border-color: #e74c3c; }
    .input-wrap.has-error input:focus { box-shadow: 0 0 0 3px rgba(231,76,60,0.13); }
    .error-msg { color: #e74c3c; font-size: 12px; font-weight: 600; margin-top: 6px; display: block; }
    
    .general-error {
      background: #fdf2f2; border: 1px solid #fabebb; padding: 12px 16px; border-radius: 10px;
      margin-bottom: 20px; display: flex; align-items: center; gap: 10px; color: #c0392b; font-size: 13px; font-weight: 600;
    }
    
    .input-wrap input.success { border-color: #27ae60; }
    .input-icon {
      position: absolute;
      right: 12px; top: 50%;
      transform: translateY(-50%);
      color: #b0bec5; display: flex;
    }
    .toggle-pw {
      background: none; border: none; cursor: pointer;
      padding: 0; display: flex; color: #b0bec5;
      transition: color 0.2s;
    }
    .toggle-pw:hover { color: var(--teal); }

    /* Password strength */
    .pw-strength {
      margin-top: 8px;
      display: none;
    }
    .pw-strength.show { display: block; }
    .strength-bars {
      display: flex; gap: 4px; margin-bottom: 5px;
    }
    .strength-bar {
      height: 3px; flex: 1; border-radius: 2px;
      background: var(--border);
      transition: background 0.3s;
    }
    .strength-bar.weak   { background: #e74c3c; }
    .strength-bar.medium { background: #f39c12; }
    .strength-bar.strong { background: #27ae60; }
    .strength-label { font-size: 11px; color: var(--gray); font-weight: 500; }

    /* Reqs */
    .pw-reqs {
      margin-top: 8px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 4px 12px;
    }
    .req {
      font-size: 11px;
      color: #b0bec5;
      display: flex;
      align-items: center;
      gap: 5px;
      transition: color 0.2s;
      font-weight: 500;
    }
    .req.met { color: #27ae60; }
    .req-dot {
      width: 5px; height: 5px; border-radius: 50%;
      background: currentColor; flex-shrink: 0;
    }

    /* Terms */
    .terms-row {
      display: flex;
      align-items: flex-start;
      gap: 9px;
      margin: 18px 0 20px;
    }
    .terms-row input[type=checkbox] {
      width: 15px; height: 15px;
      accent-color: var(--teal);
      cursor: pointer;
      margin-top: 2px;
      flex-shrink: 0;
    }
    .terms-row label {
      font-size: 13px;
      color: var(--gray);
      line-height: 1.5;
      cursor: pointer;
    }
    .terms-row a { color: var(--teal); font-weight: 600; text-decoration: none; }
    .terms-row a:hover { color: var(--teal2); }

    /* Submit */
    .btn-primary {
      width: 100%; height: 50px;
      background: var(--teal);
      color: white; border: none; border-radius: 50px;
      font-size: 16px; font-weight: 700;
      font-family: 'Barlow', sans-serif;
      cursor: pointer;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      transition: background 0.18s, transform 0.14s, box-shadow 0.18s;
      box-shadow: 0 4px 16px rgba(26,158,143,0.3);
    }
    .btn-primary:hover {
      background: var(--teal2);
      transform: translateY(-1px);
      box-shadow: 0 8px 24px rgba(26,158,143,0.4);
    }
    .btn-primary:active { transform: translateY(0); }

    .login-row {
      text-align: center;
      margin-top: 20px;
      font-size: 14px;
      color: var(--gray);
    }
    .login-row a {
      color: var(--teal); font-weight: 700;
      text-decoration: none; transition: color 0.2s;
    }
    .login-row a:hover { color: var(--teal2); }

    /* ─── RESPONSIVE ─── */
    @media (max-width: 800px) {
      .page-body { grid-template-columns: 1fr; }
      .left-panel { display: none; }
      .right-panel { padding: 36px 24px; }
    }
  </style>
</head>
<body>

  <!-- ── NAVBAR ── -->
  <nav>
    <a href="{{ route('home') }}" class="nav-logo">
      <img src="/images/logo-viantryp.png" alt="Viantryp" style="height: 32px; width: auto; filter: invert(1) hue-rotate(180deg) contrast(1.5);">
    </a>
    <div class="nav-links">
      <a href="{{ route('home') }}#demo" class="nav-link">Cómo funciona</a>
      <a href="{{ route('home') }}#precios"       class="nav-link">Precios</a>
      <a href="{{ route('home') }}#contacto"      class="nav-link">Contacto</a>
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
          <h2>Crea tu cuenta<br/><span>gratis</span></h2>
          <p>Sin tarjeta de crédito. Sin compromisos.</p>
        </div>

        <!-- Google first (conversion best practice) -->
        <a href="{{ route('auth.google') }}" class="btn-google">
          <svg width="19" height="19" viewBox="0 0 24 24">
            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
          </svg>
          Registrarse con Google
        </a>

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
              <input id="password" name="password" type="password" placeholder="Mínimo 8 caracteres" autocomplete="new-password" oninput="checkPw(this.value)" required />
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
                <div class="strength-bar" id="bar4"></div>
              </div>
              <div class="strength-label" id="strength-label">Escribe tu contraseña</div>
              <div class="pw-reqs">
                <div class="req" id="req-len"><span class="req-dot"></span>8 caracteres</div>
                <div class="req" id="req-upper"><span class="req-dot"></span>Mayúscula</div>
                <div class="req" id="req-lower"><span class="req-dot"></span>Minúscula</div>
                <div class="req" id="req-num"><span class="req-dot"></span>Número</div>
                <div class="req" id="req-special"><span class="req-dot"></span>Carácter especial</div>
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
              Acepto los <a href="#terminos">Términos y Condiciones</a> y la <a href="#privacidad">Política de Privacidad</a> de Viantryp
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
        lower:   /[a-z]/.test(val),
        num:     /[0-9]/.test(val),
        special: /[@$!%*?&#^()_\-+=]/.test(val)
      };

      Object.entries(reqs).forEach(([key, met]) => {
        document.getElementById('req-' + key).classList.toggle('met', met);
      });

      const score = Object.values(reqs).filter(Boolean).length;
      const bars = [document.getElementById('bar1'), document.getElementById('bar2'), document.getElementById('bar3'), document.getElementById('bar4')];
      const label = document.getElementById('strength-label');

      bars.forEach(b => b.className = 'strength-bar');

      if (score <= 1) {
        bars[0].classList.add('weak');
        label.textContent = 'Muy débil';
        label.style.color = '#e74c3c';
      } else if (score === 2) {
        bars[0].classList.add('weak'); bars[1].classList.add('weak');
        label.textContent = 'Débil';
        label.style.color = '#e74c3c';
      } else if (score === 3) {
        bars[0].classList.add('medium'); bars[1].classList.add('medium'); bars[2].classList.add('medium');
        label.textContent = 'Regular';
        label.style.color = '#f39c12';
      } else if (score === 4) {
        bars.slice(0,4).forEach(b => b.classList.add('medium'));
        label.textContent = 'Buena';
        label.style.color = '#f39c12';
      } else {
        bars.forEach(b => b.classList.add('strong'));
        label.textContent = '¡Contraseña segura!';
        label.style.color = '#27ae60';
      }
    }
  </script>
</body>
</html>
