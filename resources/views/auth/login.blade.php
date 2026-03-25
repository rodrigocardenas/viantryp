<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Viantryp | Iniciar Sesión</title>
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
  <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800;900&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --dark:   #0d2b3e;
      --teal:   #1a7a8a;
      --teal2:  #0e5a6a;
      --light:  #f5f7f9;
      --gray:   #6b7a8d;
      --border: #e2e8ef;
      --white:  #ffffff;
    }

    body {
      font-family: 'Barlow', sans-serif;
      min-height: 100vh;
      background: var(--light);
      display: flex;
      flex-direction: column;
      color: var(--dark);
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
    }

    /* Logo */
    .nav-logo {
      text-decoration: none;
      display: flex;
      align-items: center;
    }
    .nav-logo-text {
      font-family: 'Barlow Condensed', sans-serif;
      font-weight: 900;
      font-size: 26px;
      color: var(--dark);
      letter-spacing: -0.5px;
      line-height: 1;
    }
    .nav-logo-text .dot { color: var(--teal); }

    /* Center links */
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
    .nav-link:hover {
      background: var(--light);
      color: var(--teal);
    }

    /* Right side */
    .nav-right {
      display: flex;
      align-items: center;
      gap: 8px;
    }
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
    .nav-back:hover {
      border-color: var(--teal);
      color: var(--teal);
      background: #f0faf9;
    }
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

    /* ─── MAIN ─── */
    main {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 30px 16px 48px 16px;
    }

    .login-wrapper {
      width: 100%;
      max-width: 460px;
      animation: fadeUp 0.45s ease both;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(18px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* Header */
    .login-header {
      text-align: center;
      margin-bottom: 32px;
    }
    .login-header h1 {
      font-family: 'inter', sans-serif;
      font-weight: 900;
      font-size: 39px;
      color: var(--dark);
      line-height: 1.1;
      letter-spacing: -0.5px;
    }
    .login-header h1 span { color: var(--teal); }
    .login-header p {
      margin-top: 10px;
      color: var(--gray);
      font-size: 16px;
      font-weight: 400;
    }

    /* Card */
    .login-card {
      background: var(--white);
      border-radius: 16px;
      border: 1px solid var(--border);
      padding: 36px 36px 32px;
      box-shadow: 0 4px 24px rgba(13,43,62,0.07);
    }

    /* Fields */
    .field { margin-bottom: 20px; }
    .field label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: var(--dark);
      margin-bottom: 7px;
      letter-spacing: 0.2px;
    }
    .input-wrap { position: relative; }
    .input-wrap input {
      width: 100%;
      height: 48px;
      border: 1.5px solid var(--border);
      border-radius: 10px;
      padding: 0 44px 0 14px;
      font-size: 15px;
      font-family: 'Barlow', sans-serif;
      color: var(--dark);
      background: var(--white);
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    .input-wrap input::placeholder { color: #b0bec5; }
    .input-wrap input:focus {
      border-color: var(--teal);
      box-shadow: 0 0 0 3px rgba(26,158,143,0.13);
    }
    .input-icon {
      position: absolute;
      right: 13px;
      top: 50%;
      transform: translateY(-50%);
      color: #b0bec5;
      display: flex;
    }
    .toggle-pw {
      background: none; border: none; cursor: pointer;
      padding: 0; display: flex; color: #b0bec5;
      transition: color 0.2s;
    }
    .toggle-pw:hover { color: var(--teal); }

    /* error styling */
    .input-wrap.has-error input { border-color: #ef4444; }
    .input-wrap.has-error input:focus { box-shadow: 0 0 0 3px rgba(239,68,68,0.13); }
    .error-msg { color: #ef4444; font-size: 12px; font-weight: 600; margin-top: 6px; display: block; }
    
    .general-error {
      background: #fef2f2; border: 1px solid #fca5a5; padding: 12px 16px; border-radius: 10px;
      margin-bottom: 20px; display: flex; align-items: center; gap: 10px; color: #b91c1c; font-size: 13px; font-weight: 600;
    }

    /* Options row */
    .row-options {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin: -4px 0 24px;
    }
    .remember {
      display: flex; align-items: center; gap: 7px;
      cursor: pointer; color: var(--gray); font-size: 13px; font-weight: 500;
    }
    .remember input[type=checkbox] {
      width: 15px; height: 15px;
      accent-color: var(--teal);
      cursor: pointer;
    }
    .forgot-link {
      font-size: 13px; font-weight: 600;
      color: var(--teal); text-decoration: none;
      transition: color 0.2s;
    }
    .forgot-link:hover { color: var(--teal2); }

    /* Buttons */
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
      box-shadow: 0 8px 24px rgba(26,158,143,0.38);
    }
    .btn-primary:active { transform: translateY(0); }

    .divider {
      display: flex; align-items: center; gap: 12px;
      margin: 22px 0; color: #b0bec5; font-size: 13px; font-weight: 500;
    }
    .divider::before, .divider::after {
      content: ''; flex: 1; height: 1px; background: var(--border);
    }

    .btn-google {
      width: 100%; height: 50px; background: var(--white);
      text-decoration: none;
      border: 1.5px solid var(--border); border-radius: 50px;
      font-size: 15px; font-weight: 600;
      font-family: 'Barlow', sans-serif;
      color: var(--dark); cursor: pointer;
      display: flex; align-items: center; justify-content: center; gap: 10px;
      transition: border-color 0.18s, box-shadow 0.18s, transform 0.14s;
    }
    .btn-google:hover {
      border-color: var(--teal);
      box-shadow: 0 4px 14px rgba(26,158,143,0.12);
      transform: translateY(-1px);
    }

    /* Signup */
    .signup-row {
      text-align: center;
      margin-top: 22px;
      font-size: 14px;
      color: var(--gray);
    }
    .signup-row a {
      color: var(--teal); font-weight: 700;
      text-decoration: none; transition: color 0.2s;
    }
    .signup-row a:hover { color: var(--teal2); }
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

  <!-- ── LOGIN ── -->
  <main>
    <div class="login-wrapper">

      <div class="login-header">
        <h1>Bienvenido a <span>Viantryp</span></h1>
        <p>Inicia sesión para gestionar tus viajes</p>
      </div>

      <div class="login-card">

        <form method="POST" action="{{ route('login') }}">
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

          <div class="field">
            <label for="email">Correo electrónico</label>
            <div class="input-wrap @error('email') has-error @enderror">
              <input id="email" name="email" type="email" placeholder="tu@email.com" autocomplete="email" value="{{ old('email') }}" required />
              <span class="input-icon">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M4 4h16c1.1 0 2 .9 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2z"/>
                  <polyline points="22,6 12,13 2,6"/>
                </svg>
              </span>
            </div>
            @error('email')
              <span class="error-msg">{{ $message }}</span>
            @enderror
          </div>

          <div class="field">
            <label for="password">Contraseña</label>
            <div class="input-wrap @error('password') has-error @enderror">
              <input id="password" name="password" type="password" placeholder="••••••••" autocomplete="current-password" required />
              <button class="toggle-pw input-icon" onclick="togglePw()" type="button">
                <svg id="eye-icon" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                  <circle cx="12" cy="12" r="3"/>
                </svg>
              </button>
            </div>
            @error('password')
              <span class="error-msg">{{ $message }}</span>
            @enderror
          </div>

          <div class="row-options">
            <label class="remember">
              <input type="checkbox" name="remember" id="remember" /> Recordarme
            </label>
            <a href="{{ route('password.request') }}" class="forgot-link">¿Olvidaste tu contraseña?</a>
          </div>

          <button class="btn-primary" type="submit">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/>
            </svg>
            Iniciar Sesión
          </button>
        </form>

        <div class="divider">O continúa con</div>

        <a href="{{ route('auth.google') }}" class="btn-google">
          <svg width="19" height="19" viewBox="0 0 24 24">
            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
          </svg>
          Continuar con Google
        </a>

      </div>

      <div class="signup-row">
        ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate gratis →</a>
      </div>

    </div>
  </main>

  <script>
    function togglePw() {
      const input = document.getElementById('password');
      const icon  = document.getElementById('eye-icon');
      if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>';
      } else {
        input.type = 'password';
        icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
      }
    }
  </script>
</body>
</html>
