<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Viantryp | Restablecer Contraseña</title>
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

    @media (max-width: 768px) {
      nav { padding: 1rem; justify-content: space-between; height: auto; }
      .nav-links { display: none; }
      .nav-logo img { height: 26px !important; }
      .nav-back {
        font-size: 12px !important;
        padding: 0.4rem 0.6rem !important;
        white-space: nowrap;
      }
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
      max-width: 500px;
      animation: fadeUp 0.45s ease both;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(18px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .login-header {
      text-align: center;
      margin-bottom: 32px;
    }
    .login-header h1 {
      font-family: 'inter', sans-serif;
      font-weight: 900;
      font-size: 32px;
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
      line-height: 1.5;
    }

    .login-card {
      background: var(--white);
      border-radius: 20px;
      border: 1px solid var(--border);
      padding: 40px;
      box-shadow: 0 4px 24px rgba(13,43,62,0.07);
    }

    .field { margin-bottom: 24px; }
    .field label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: var(--dark);
      margin-bottom: 8px;
    }
    .input-wrap { position: relative; }
    .input-wrap input {
      width: 100%;
      height: 50px;
      border: 1.5px solid var(--border);
      border-radius: 12px;
      padding: 0 44px 0 14px;
      font-size: 15px;
      font-family: 'Barlow', sans-serif;
      color: var(--dark);
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    .input-wrap input:focus {
      border-color: var(--teal);
      box-shadow: 0 0 0 3px rgba(26,158,143,0.13);
    }
    .input-wrap input:disabled { background: #f8f9fa; cursor: not-allowed; }

    .input-icon {
      position: absolute;
      right: 14px;
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

    .error-msg { color: #ef4444; font-size: 12px; font-weight: 600; margin-top: 6px; display: block; }
    
    .password-rules {
      margin-top: 12px;
      padding: 12px;
      background: #f8fafc;
      border-radius: 10px;
      font-size: 12px;
      color: var(--gray);
    }
    .password-rules strong { color: var(--dark); display: block; margin-bottom: 6px; }
    .password-rules ul { list-style: disc; padding-left: 18px; }

    .btn-primary {
      width: 100%; height: 52px;
      background: var(--teal);
      color: white; border: none; border-radius: 50px;
      font-size: 16px; font-weight: 700;
      font-family: 'Barlow', sans-serif;
      cursor: pointer;
      display: flex; align-items: center; justify-content: center; gap: 10px;
      transition: background 0.18s, transform 0.14s, box-shadow 0.18s;
      box-shadow: 0 4px 16px rgba(26,158,143,0.3);
      margin-bottom: 20px;
      margin-top: 10px;
    }
    .btn-primary:hover {
      background: var(--teal2);
      transform: translateY(-1px);
      box-shadow: 0 8px 24px rgba(26,158,143,0.38);
    }

    .back-action {
      text-align: center;
      font-size: 14px;
    }
    .back-action a {
      color: var(--teal); font-weight: 700;
      text-decoration: none; transition: color 0.2s;
      display: inline-flex; align-items: center; gap: 6px;
    }
    .back-action a:hover { color: var(--teal2); text-decoration: underline; }
  </style>
</head>
<body>

  <nav>
    <a href="{{ route('home') }}" class="nav-logo">
      <img src="/images/logo-viantryp.png" alt="Viantryp" style="height: 32px; width: auto; filter: invert(1) hue-rotate(180deg) contrast(1.5);">
    </a>

    <div class="nav-links">
      <a href="{{ route('home') }}#como-funciona" class="nav-link">Cómo funciona</a>
      <a href="{{ route('home') }}#precios"       class="nav-link">Precios</a>
      <a href="{{ route('home') }}#contacto"      class="nav-link">Contacto</a>
    </div>

    <div class="nav-right">
      <a href="{{ route('login') }}" class="nav-back">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M19 12H5M5 12l7 7M5 12l7-7"/>
        </svg>
        Regresar al login
      </a>
    </div>
  </nav>

  <main>
    <div class="login-wrapper">

      <div class="login-header">
        <h1>Nueva <span>contraseña</span></h1>
        <p>Crea una contraseña segura para proteger tu cuenta.</p>
      </div>

      <div class="login-card">

        <form method="POST" action="{{ route('password.update') }}">
          @csrf
          <input type="hidden" name="token" value="{{ $request->route('token') }}">

          <div class="field">
            <label for="email">Correo electrónico</label>
            <div class="input-wrap">
              <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" readonly required />
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
            <label for="password">Nueva Contraseña</label>
            <div class="input-wrap @error('password') has-error @enderror">
              <input id="password" name="password" type="password" placeholder="••••••••" required />
              <button class="toggle-pw input-icon" onclick="togglePw('password', 'eye-1')" type="button">
                <svg id="eye-1" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                  <circle cx="12" cy="12" r="3"/>
                </svg>
              </button>
            </div>
            @error('password')
              <span class="error-msg">{{ $message }}</span>
            @enderror
            
            <div class="password-rules">
              <strong>Requisitos:</strong>
              <ul>
                <li>Mínimo 8 caracteres</li>
                <li>Mayúsculas, minúsculas y números</li>
                <li>Al menos un carácter especial (@$!%*?&)</li>
              </ul>
            </div>
          </div>

          <div class="field">
            <label for="password_confirmation">Confirmar Contraseña</label>
            <div class="input-wrap">
              <input id="password_confirmation" name="password_confirmation" type="password" placeholder="••••••••" required />
              <button class="toggle-pw input-icon" onclick="togglePw('password_confirmation', 'eye-2')" type="button">
                <svg id="eye-2" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                  <circle cx="12" cy="12" r="3"/>
                </svg>
              </button>
            </div>
          </div>

          <button class="btn-primary" type="submit">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
              <polyline points="17 21 17 13 7 13 7 21"></polyline>
              <polyline points="7 3 7 8 15 8"></polyline>
            </svg>
            Restablecer Contraseña
          </button>
        </form>

        <div class="back-action">
          <a href="{{ route('login') }}">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M19 12H5M5 12l7 7M5 12l7-7"/>
            </svg>
            Volver al inicio de sesión
          </a>
        </div>

      </div>

    </div>
  </main>

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
  </script>
</body>
</html>
.add('fa-eye');
    }
}
</script>
@endsection
