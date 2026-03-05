<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Viantryp — Creador de Itinerarios de Viaje</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
<style>
  :root {
    --teal: #1a7a8a;
    --teal-dark: #0e5a6a;
    --teal-light: #e8f7f9;
    --lime: #8ab820;
    --lime-bright: #9fd020;
    --lime-bg: #f2f8d8;
    --navy: #0f2a3a;
    --white: #ffffff;
    --off-white: #f7f9f7;
    --light-gray: #f0f3f0;
    --mid-gray: #e2e8e2;
    --text: #1a2e1a;
    --text-soft: #5a7060;
    --text-muted: #8a9e8a;
  }

  * { margin: 0; padding: 0; box-sizing: border-box; }
  html { scroll-behavior: smooth; }

  body {
    font-family: 'DM Sans', sans-serif;
    background: var(--white);
    color: var(--text);
    overflow-x: hidden;
  }

  /* ── NAV ── */
  nav {
    position: fixed; top: 0; left: 0; right: 0; z-index: 100;
    display: flex; align-items: center; justify-content: space-between;
    padding: 1.1rem 4rem;
    background: rgba(255,255,255,0.92);
    backdrop-filter: blur(16px);
    border-bottom: 1px solid var(--mid-gray);
  }
  .nav-logo {
    font-family: 'Syne', sans-serif;
    font-size: 1.5rem; font-weight: 800;
    letter-spacing: -0.04em; color: var(--navy);
    text-decoration: none;
  }
  .nav-logo span { color: var(--lime); }
  .nav-links { display: flex; gap: 2rem; list-style: none; margin-left: auto; margin-right: 1.5rem; }
  .nav-links a {
    font-size: 0.9rem; font-weight: 500; color: var(--text-soft);
    text-decoration: none; transition: color 0.2s;
  }
  .nav-links a:hover { color: var(--navy); }
  .nav-right { display: flex; align-items: center; gap: 0.75rem; }
  .nav-login {
    font-size: 0.88rem; font-weight: 500;
    color: var(--navy); text-decoration: none;
    padding: 0.55rem 1.2rem; border-radius: 100px;
    border: 1px solid var(--mid-gray);
    transition: background 0.2s, border-color 0.2s;
  }
  .nav-login:hover { background: var(--light-gray); border-color: #ccc; }
  .nav-cta {
    font-size: 0.88rem; font-weight: 700;
    color: var(--white); text-decoration: none;
    padding: 0.6rem 1.4rem; border-radius: 100px;
    background: var(--teal);
    transition: background 0.2s, transform 0.15s; 
  }
  .nav-cta:hover { background: var(--teal-dark); transform: scale(1.03); }

  /* ── HERO ── */
  .hero {
    min-height: 100vh;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    text-align: center;
    padding: 9rem 2rem 6rem;
    position: relative; overflow: hidden;
    background: var(--white);
  }
  .hero-bg {
    position: absolute; inset: 0; z-index: 0;
    background:
      radial-gradient(ellipse 70% 50% at 50% -10%, rgba(26,122,138,0.08) 0%, transparent 70%),
      radial-gradient(ellipse 40% 30% at 90% 90%, rgba(138,184,32,0.07) 0%, transparent 60%),
      var(--white);
  }
  .hero-badge {
    position: relative; z-index: 1;
    display: inline-flex; align-items: center; gap: 0.5rem;
    background: var(--lime-bg);
    border: 1px solid rgba(138,184,32,0.3);
    color: var(--lime);
    padding: 0.4rem 1rem; border-radius: 100px;
    font-size: 0.77rem; font-weight: 700; letter-spacing: 0.08em;
    text-transform: uppercase; margin-bottom: 2rem;
    animation: fadeDown 0.7s ease both;
  }
  .hero-badge::before {
    content: ''; width: 6px; height: 6px;
    background: var(--lime); border-radius: 50%;
    animation: pulse 2s infinite;
  }
  .hero h1 {
    position: relative; z-index: 1;
    font-family: inter sans-serif;
    font-size: clamp(3rem, 7vw, 6rem);
    font-weight: 800; line-height: 1.0;
    letter-spacing: -0.04em; color: var(--navy);
    max-width: 1060px; margin-bottom: 1.5rem;
    animation: fadeDown 0.7s 0.12s ease both;
  }
  .hero h1 em { font-style: normal; color: var(--teal); }
  .hero > p {
    position: relative; z-index: 1;
    font-weight: 300; color: var(--text-soft);
    max-width: 800px; line-height: 1.75; margin-bottom: 2.8rem;
    animation: fadeDown 0.7s 0.24s ease both;
  }
  .hero-actions {
    position: relative; z-index: 1;
    display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center;
    animation: fadeDown 0.7s 0.36s ease both;
  }
  .btn-primary {
    background: var(--teal); color: var(--white);
    padding: 0.9rem 2.2rem; border-radius: 100px;
    font-weight: 700; font-size: 1rem; text-decoration: none;
    transition: all 0.2s; box-shadow: 0 4px 24px rgba(26,122,138,0.2);
  }
  .btn-primary:hover { background: var(--teal-dark); transform: translateY(-2px); box-shadow: 0 8px 32px rgba(26,122,138,0.28); }
  .btn-secondary {
    background: var(--white); border: 1.5px solid var(--mid-gray);
    color: var(--navy); padding: 0.9rem 2.2rem; border-radius: 100px;
    font-weight: 500; font-size: 1rem; text-decoration: none; transition: all 0.2s;
  }
  .btn-secondary:hover { background: var(--light-gray); border-color: #bbb; }
  .hero-stats {
    position: relative; z-index: 1;
    display: flex; gap: 3.5rem; margin-top: 4.5rem;
    padding-top: 4rem;
    border-top: 1px solid var(--mid-gray);
    animation: fadeDown 0.7s 0.48s ease both;
  }
  .stat { text-align: center; }
  .stat-num { font-family: 'Syne', sans-serif; font-size: 2rem; font-weight: 800; color: var(--teal); }
  .stat-label { font-size: 0.78rem; color: var(--text-muted); margin-top: 0.25rem; }

  @keyframes fadeDown {
    from { opacity: 0; transform: translateY(-18px); }
    to { opacity: 1; transform: translateY(0); }
  }
  @keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.5;transform:scale(1.4)} }

  /* ── SHARED ── */
  section { padding: 3rem 2rem; }
  .container { max-width: 1100px; margin: 0 auto; }
  .section-label {
    display: inline-block; font-size: 0.73rem; font-weight: 700;
    letter-spacing: 0.13em; text-transform: uppercase;
    color: var(--teal); margin-bottom: 1rem;
  }
  .section-title {
    font-family: inter , sans-serif;
    font-size: clamp(2rem, 4vw, 3rem); font-weight: 800;
    line-height: 1.1; letter-spacing: -0.03em; margin-bottom: 1.2rem;
    color: var(--navy);
  }
  .section-desc {
    font-size: 1.05rem; color: var(--text-soft); line-height: 1.72; max-width: 540px;
  }

  /* ── HOW ── */
  .how { background: var(--off-white); }
  .steps {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1.5rem; margin-top: 4rem;
  }
  .step {
    background: var(--white); border: 1.5px solid var(--mid-gray);
    border-radius: 16px; padding: 2rem;
    transition: border-color 0.3s, transform 0.3s, box-shadow 0.3s;
  }
  .step:hover { border-color: var(--teal); transform: translateY(-4px); box-shadow: 0 8px 32px rgba(26,122,138,0.1); }
  .step-num {
    font-family: 'Syne', sans-serif; font-size: 3.5rem; font-weight: 800;
    color: #1a7a8a; line-height: 1; margin-bottom: 1rem;
  }
  .step-icon {
    width: 44px; height: 44px; border-radius: 12px;
    background: var(--teal-light);
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 1rem; font-size: 1.3rem;
  }
  .step h3 { font-family: 'Syne', sans-serif; font-size: 1.05rem; font-weight: 700; margin-bottom: 0.6rem; color: var(--navy); }
  .step p { font-size: 0.88rem; color: var(--text-soft); line-height: 1.6; }

  /* ── FEATURES ── */
  .features { background: var(--white); }
  .features-grid {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5px; background: var(--mid-gray);
    border-radius: 20px; overflow: hidden; margin-top: 4rem;
    border: 1.5px solid var(--mid-gray);
  }
  .feature-item { background: var(--white); padding: 2.5rem; transition: background 0.3s; }
  .feature-item:hover { background: var(--off-white); }
  .feature-emoji { font-size: 2rem; margin-bottom: 1rem; }
  .feature-item h3 { font-family: 'Syne', sans-serif; font-size: 1.1rem; font-weight: 700; margin-bottom: 0.6rem; color: var(--navy); }
  .feature-item p { font-size: 0.88rem; color: var(--text-soft); line-height: 1.6; }

  /* ── FOR WHO ── */
  .forwho { background: var(--off-white); }
  .audience-cards {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 1.5rem; margin-top: 3.5rem;
  }
  .audience-card { border-radius: 16px; padding: 2.5rem; border: 1.5px solid var(--mid-gray); background: var(--white); transition: box-shadow 0.3s, border-color 0.3s; }
  .audience-card:hover { box-shadow: 0 8px 32px rgba(0,0,0,0.07); border-color: var(--teal); }
  .audience-card.primary { background: linear-gradient(135deg, #e6f6f8 0%, #f0fafb 100%); border-color: rgba(26,122,138,0.25); }
  .audience-card.secondary { background: linear-gradient(135deg, var(--lime-bg) 0%, #f8fce8 100%); border-color: rgba(138,184,32,0.25); }
  .audience-icon { font-size: 2.5rem; margin-bottom: 1.5rem; }
  .audience-card h3 { font-family: 'Syne', sans-serif; font-size: 1.3rem; font-weight: 700; margin-bottom: 0.8rem; color: var(--navy); }
  .audience-card p { font-size: 0.9rem; color: var(--text-soft); line-height: 1.6; }
  .audience-tag {
    display: inline-block; margin-top: 1.5rem;
    background: rgba(26,122,138,0.1); color: var(--teal);
    font-size: 0.73rem; font-weight: 700;
    padding: 0.3rem 0.8rem; border-radius: 100px;
    letter-spacing: 0.06em; text-transform: uppercase;
  }
  .audience-card.secondary .audience-tag { background: var(--lime-bg); color: var(--lime); }

  /* ── QUOTE ── */
  .quote-section { background: var(--navy); text-align: center; }
  .big-quote {
    font-family: 'Syne', sans-serif;
    font-size: clamp(1.5rem, 3.5vw, 2.4rem); font-weight: 700; line-height: 1.35;
    max-width: 720px; margin: 0 auto 1.5rem; color: var(--white);
  }
  .big-quote em { color: #5dcfe0; font-style: normal; }
  .quote-sub { font-size: 0.88rem; color: rgba(255,255,255,0.4); }

  /* ── PRICING ── */
  .pricing { background: var(--off-white); }
  .pricing-grid {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 1.5rem; max-width: 860px; margin: 3.5rem auto 0;
  }
  .plan { background: var(--white); border: 1.5px solid var(--mid-gray); border-radius: 20px; padding: 2.5rem; }
  .plan.featured {
    background: var(--navy);
    border-color: var(--navy); position: relative;
  }
  .plan-badge {
    position: absolute; top: -14px; left: 50%; transform: translateX(-50%);
    background: var(--lime); color: var(--white);
    font-size: 0.68rem; font-weight: 800;
    padding: 0.28rem 1rem; border-radius: 100px;
    letter-spacing: 0.08em; white-space: nowrap; text-transform: uppercase;
  }
  .plan-name { font-size: 0.73rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--text-muted); margin-bottom: 1rem; }
  .plan.featured .plan-name { color: rgba(255,255,255,0.5); }
  .plan-price { font-family: 'Syne', sans-serif; font-size: 2.8rem; font-weight: 800; margin-bottom: 0.25rem; color: var(--navy); }
  .plan.featured .plan-price { color: var(--white); }
  .plan-price span { font-size: 1rem; font-weight: 400; color: var(--text-muted); }
  .plan.featured .plan-price span { color: rgba(255,255,255,0.4); }
  .plan-sub { font-size: 0.83rem; color: var(--text-muted); margin-bottom: 2rem; }
  .plan.featured .plan-sub { color: rgba(255,255,255,0.45); }
  .plan-features { list-style: none; display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 2rem; }
  .plan-features li { font-size: 0.87rem; color: var(--text-soft); display: flex; gap: 0.6rem; }
  .plan.featured .plan-features li { color: rgba(255,255,255,0.75); }
  .plan-features li::before { content: '✓'; color: var(--teal); flex-shrink: 0; }
  .plan.featured .plan-features li::before { color: #5dcfe0; }
  .plan-btn {
    display: block; text-align: center;
    border: 1.5px solid var(--mid-gray); border-radius: 100px; padding: 0.8rem;
    font-size: 0.9rem; color: var(--navy); font-weight: 500;
    text-decoration: none; transition: background 0.2s;
  }
  .plan-btn:hover { background: var(--light-gray); }
  .plan-btn.primary {
    background: var(--teal); color: var(--white);
    border-color: var(--teal); font-weight: 700;
  }
  .plan-btn.primary:hover { background: var(--teal-dark); }

  /* ── CTA FINAL ── */
  .cta-final { background: var(--white); }
  .cta-box {
    background: linear-gradient(135deg, #e6f6f8 0%, var(--lime-bg) 100%);
    border: 1.5px solid var(--mid-gray);
    border-radius: 24px; padding: 3rem 3rem; text-align: center;
  }
  .cta-box h2 {
    font-family: inter , sans-serif;
    font-size: clamp(2rem, 4vw, 3rem); font-weight: 800;
    letter-spacing: -0.03em; margin-bottom: 2rem; color: var(--navy);
  }
  .cta-box > p { font-size: 1.05rem; color: var(--text-soft); margin-bottom: 2.5rem; }
  .cta-actions { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
  .cta-note { font-size: 0.87rem; color: var(--text-muted); margin-top: 1.5rem; }

  /* ── CONTACT ── */
  .contact { padding: 5rem 2rem; text-align: center; background: var(--off-white); }
  .contact-email {
    font-family: 'Syne', sans-serif; font-size: 1.8rem; font-weight: 700;
    color: var(--teal); text-decoration: none;
    border-bottom: 2px solid rgba(26,122,138,0.25); padding-bottom: 0.2rem;
    transition: border-color 0.2s;
  }
  .contact-email:hover { border-color: var(--teal); }

  /* ── FOOTER ── */
  footer { background: var(--navy); padding: 1.5rem 1.5rem; }
  .footer-inner {
    max-width: 1100px; margin: 0 auto;
    display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;
  }
  .footer-logo { font-family: 'Syne', sans-serif; font-size: 1.25rem; font-weight: 800; color: var(--white); }
  .footer-logo span { color: var(--lime); }
  .footer-links { display: flex; gap: 2rem; }
  .footer-links a { font-size: 0.84rem; color: rgba(255,255,255,0.4); text-decoration: none; transition: color 0.2s; }
  .footer-links a:hover { color: var(--white); }
  .footer-copy { font-size: 0.76rem; color: rgba(255,255,255,0.25); }

  /* ── REVEAL ── */
  .reveal { opacity: 0; transform: translateY(28px); transition: opacity 0.7s ease, transform 0.7s ease; }
  .reveal.visible { opacity: 1; transform: translateY(0); }
  .d1 { transition-delay: 0.12s; }
  .d2 { transition-delay: 0.26s; }
  .d3 { transition-delay: 0.4s; }

  @media (max-width: 768px) {
    nav { padding: 1rem; justify-content: space-between; }
    .nav-links { display: none; }
    .nav-logo img { height: 26px !important; }
    .nav-right { gap: 0.4rem; margin-right: 0.5rem; }
    .nav-login, .nav-cta {
      font-size: 12px !important;
      padding: 0.4rem 0.6rem !important;
      white-space: nowrap;
    }
    .hero-stats { gap: 1.8rem; flex-wrap: wrap; justify-content: center; }
    .hero { padding-bottom: 3rem; }
    .cta-note { font-size: 0.87rem !important; }
    .section-desc { font-size: 0.8rem !important; }
  }
</style>
</head>
<body>

<!-- NAV -->
<nav>
  <a href="#" class="nav-logo" style="display:flex; align-items:center;">
    <img src="/images/logo-viantryp.png" alt="Viantryp" style="height: 32px; width: auto; filter: invert(1) hue-rotate(180deg) contrast(1.5);">
  </a>
  <ul class="nav-links">
    <li><a href="#como-funciona">Cómo funciona</a></li>
    <li><a href="#precios">Precios</a></li>
    <li><a href="#contacto">Contacto</a></li>
  </ul>
  <div class="nav-right">
    @auth
        <div style="display: flex; align-items: center; gap: 1.25rem;">
            <a href="{{ route('trips.index') }}" class="nav-login">Ir a Mis Viajes</a>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="font-size: 0.95rem; font-weight: 600; color: var(--navy);">
                    {{ auth()->user()->name }}
                </span>
                <a href="{{ route('trips.index') }}" style="width: 36px; height: 36px; border-radius: 50%; background-color: var(--teal); color: var(--white); display: flex; align-items: center; justify-content: center; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1rem; text-decoration: none; border: 2px solid var(--teal-light); transition: transform 0.2s;" title="Ir a Mis Viajes" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </a>
            </div>
        </div>
    @else
        <a href="{{ route('login') }}" class="nav-login">Iniciar sesión</a>
        <a href="{{ route('register') }}" class="nav-cta">Comenzar gratis</a>
    @endauth
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-bg"></div>
  <div class="hero-badge">✦ Plataforma para agencias de viajes</div>
  <h1>Diseña itinerarios<br><em>impactantes</em> en minutos</h1>
  <p>Viantryp es el creador de propuestas de viaje para agencias pequeñas y consultores independientes del mundo hispanohablante. Sin complicaciones, sin horas perdidas.</p>
  <div class="hero-actions">
    @auth
        <a href="{{ route('trips.index') }}" class="btn-primary">Ir a mis viajes →</a>
    @else
        <a href="#cta" class="btn-primary">Empezar ahora →</a>
        <a href="#como-funciona" class="btn-secondary">Ver cómo funciona</a>
    @endauth
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="how" id="como-funciona">
  <div class="container">
    <div class="reveal">
      <div class="section-label">Proceso</div>
      <h2 class="section-title">De la idea al cliente<br>en 3 pasos</h2>
      <p class="section-desc">Olvídate del Excel y el Canva. Viantryp tiene todo lo que necesitas para crear propuestas profesionales en tiempo récord.</p>
    </div>
    <div class="steps">
      <div class="step reveal d1">
        <div class="step-num">01</div>
        <div class="step-icon">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
            <line x1="16" y1="13" x2="8" y2="13"></line>
            <line x1="16" y1="17" x2="8" y2="17"></line>
            <polyline points="10 9 9 9 8 9"></polyline>
          </svg>
        </div>
        <h3>Recoge los datos</h3>
        <p>Completa el formulario con fechas, presupuesto, número de viajeros, servicios requeridos y preferencias del cliente.</p>
      </div>
      <div class="step reveal d2">
        <div class="step-num">02</div>
        <div class="step-icon">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
          </svg>
        </div>
        <h3>Diseña con IA o drag & drop</h3>
        <p>Usa plantillas especializadas en turismo, arrástralas y edítalas a tu gusto, o deja que la inteligencia artificial genere la propuesta por ti.</p>
      </div>
      <div class="step reveal d3">
        <div class="step-num">03</div>
        <div class="step-icon">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
          </svg>
        </div>
        <h3>Comparte y cierra</h3>
        <p>Envía un enlace personalizado al cliente. Cuando elija su opción, recibes una notificación instantánea para cerrar la venta.</p>
      </div>
    </div>
  </div>
</section>

<!-- FEATURES -->
<section class="features" id="funcionalidades">
  <div class="container">
    <div class="reveal">
      <div class="section-label">Funcionalidades</div>
      <h2 class="section-title">Todo lo que necesitas,<br>nada más</h2>
    </div>
    <div class="features-grid reveal">
      <div class="feature-item">
        <div class="feature-emoji">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 3l7.07 16.97 2.51-7.39 7.39-2.51L3 3z"></path>
            <path d="M13 13l6 6"></path>
          </svg>
        </div>
        <h3>Interfaz drag & drop</h3>
        <p>Construye propuestas arrastrando bloques de vuelos, hoteles, actividades y transporte. Diseñada para no técnicos.</p>
      </div>
      <div class="feature-item">
        <div class="feature-emoji">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon>
            <line x1="8" y1="2" x2="8" y2="18"></line>
            <line x1="16" y1="6" x2="16" y2="22"></line>
          </svg>
        </div>
        <h3>Plantillas especializadas</h3>
        <p>Plantillas preconstruidas para turismo, editables al 100%. Nada de plantillas genéricas que no encajan.</p>
      </div>
      <div class="feature-item">
        <div class="feature-emoji">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="10" rx="2"></rect>
            <circle cx="12" cy="5" r="2"></circle>
            <path d="M12 7v4"></path>
            <line x1="8" y1="16" x2="8" y2="16"></line>
            <line x1="16" y1="16" x2="16" y2="16"></line>
          </svg>
        </div>
        <h3>Generación con IA</h3>
        <p>Ingresa los datos del cliente y la IA genera una propuesta completa y profesional en segundos.</p>
      </div>
      <div class="feature-item">
        <div class="feature-emoji">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
            <line x1="12" y1="18" x2="12.01" y2="18"></line>
          </svg>
        </div>
        <h3>Optimizado para móvil</h3>
        <p>Tu cliente puede revisar y aprobar el itinerario desde cualquier dispositivo, en cualquier momento.</p>
      </div>
      <div class="feature-item">
        <div class="feature-emoji">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
          </svg>
        </div>
        <h3>Notificaciones en tiempo real</h3>
        <p>Recibe una alerta al instante cuando tu cliente revisa o selecciona una opción de tu propuesta.</p>
      </div>
      <div class="feature-item">
        <div class="feature-emoji">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path>
            <line x1="12" y1="18" x2="12" y2="22"></line>
            <line x1="12" y1="2" x2="12" y2="6"></line>
          </svg>
        </div>
        <h3>Precio accesible</h3>
        <p>Tarifas pensadas para agencias pequeñas y consultores independientes, sin costos ocultos.</p>
      </div>
    </div>
  </div>
</section>

<!-- FOR WHO -->
<section class="forwho">
  <div class="container">
    <div class="reveal">
      <div class="section-label">¿Para quién?</div>
      <h2 class="section-title">Hecho para quienes<br>viven del turismo</h2>
    </div>
    <div class="audience-cards">
      <div class="audience-card primary reveal d1">
        <div class="audience-icon">
          <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect>
            <path d="M9 22v-4h6v4"></path>
            <path d="M8 6h.01"></path>
            <path d="M16 6h.01"></path>
            <path d="M12 6h.01"></path>
            <path d="M12 10h.01"></path>
            <path d="M12 14h.01"></path>
            <path d="M16 10h.01"></path>
            <path d="M16 14h.01"></path>
            <path d="M8 10h.01"></path>
            <path d="M8 14h.01"></path>
          </svg>
        </div>
        <h3>Pequeñas agencias de viajes</h3>
        <p>Equipos con recursos limitados que necesitan presentar propuestas profesionales sin invertir horas de trabajo manual en cada cotización.</p>
        <span class="audience-tag">Agencias</span>
      </div>
      <div class="audience-card secondary reveal d2">
        <div class="audience-icon">
          <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--lime)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
          </svg>
        </div>
        <h3>Consultores independientes</h3>
        <p>Travel advisors que trabajan solos y necesitan impresionar a sus clientes con propuestas visuales de alta calidad, sin depender de un equipo de diseño.</p>
        <span class="audience-tag">Freelancers</span>
      </div>
    </div>
  </div>
</section>

<!-- QUOTE -->
<section class="quote-section">
  <div class="container">
    <div class="reveal">
      <p class="big-quote">"Transformamos un proceso que <em>normalmente toma horas</em> en una tarea que se completa en <em>minutos</em>."</p>
      <p class="quote-sub">— El equipo de Viantryp</p>
    </div>
  </div>
</section>

<!-- PRICING -->
<section class="pricing" id="precios">
  <div class="container">
    <div class="reveal" style="text-align:center;">
      <div class="section-label">Precios</div>
      <h2 class="section-title">Planes simples y transparentes</h2>
      <p class="section-desc" style="margin:0 auto;">Sin sorpresas. Cancela cuando quieras.</p>
    </div>
    <div class="pricing-grid">
      <div class="plan reveal d1">
        <div class="plan-name">Starter</div>
        <div class="plan-price">$0</div>
        <div class="plan-sub">Para comenzar sin riesgo</div>
        <ul class="plan-features">
          <li>3 itinerarios por mes</li>
          <li>Plantillas básicas</li>
          <li>Enlace compartible</li>
        </ul>
        <a href="{{ route('register') }}" class="plan-btn">Comenzar gratis</a>
      </div>
      <div class="plan featured reveal d2">
        <div class="plan-badge">Más popular</div>
        <div class="plan-name">Pro</div>
        <div class="plan-price">$29<span>/mes</span></div>
        <div class="plan-sub">Para profesionales del turismo</div>
        <ul class="plan-features">
          <li>Itinerarios ilimitados</li>
          <li>IA integrada</li>
          <li>Todas las plantillas</li>
          <li>Notificaciones en tiempo real</li>
          <li>Marca personalizada</li>
        </ul>
        <a href="{{ route('register') }}" class="plan-btn primary">Probar 14 días gratis</a>
      </div>
    </div>
  </div>
</section>

<!-- CTA FINAL -->
<section class="cta-final" id="cta">
  <div class="container">
    <div class="cta-box reveal">
      <div class="section-label">¿Listo para empezar?</div>
      <h2>Tu próxima propuesta,<br>lista en minutos</h2>
      <div class="cta-actions">
        <a href="{{ route('register') }}" class="btn-primary">Crear cuenta gratis →</a>
        <a href="#contacto" class="btn-secondary">Hablar con el equipo</a>
      </div>
      <p class="cta-note">Sin tarjeta de crédito · Cancela cuando quieras · Soporte en español</p>
    </div>
  </div>
</section>

<!-- CONTACT -->
<section class="contact" id="contacto">
  <div class="container reveal">
    <div class="section-label">Contacto</div>
    <h2 class="section-title">¿Tienes preguntas?</h2>
    <p class="section-desc" style="margin:0 auto 2.5rem;">Más que una plataforma, somos tu aliado en tus viajes. <br>Escríbenos.</p>
    <a href="mailto:hola@viantryp.com" class="contact-email">hola@viantryp.com</a>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="footer-inner">
    <div class="footer-logo" style="display:flex; align-items:center;">
      <img src="/images/logo-viantryp.png" alt="Viantryp" style="height: 32px; width: auto; filter: brightness(0) invert(1);">
    </div>
    <div class="footer-links">
      <a href="#como-funciona">Cómo funciona</a>
      <a href="#precios">Precios</a>
      <a href="#contacto">Contacto</a>
    </div>
    <div class="footer-copy">© 2026 Viantryp. Hecho con ♥.</div>
  </div>
</footer>

<script>
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
  }, { threshold: 0.1 });
  document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
      const href = a.getAttribute('href');
      if (href === '#') return;
      const target = document.querySelector(href);
      if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth' }); }
    });
  });
</script>
</body>
</html>
