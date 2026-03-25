<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title') | Viantryp</title>
<meta name="description" content="@yield('meta_description', 'Viantryp — La plataforma que transforma itinerarios de viaje en experiencias digitales.')">
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800;900&family=Barlow:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
  :root {
    --teal: #1a7a8a; --teal-dark: #0e5a6a; --teal-light: #e8f7f9;
    --lime: #8ab820; --lime-bright: #9fd020; --lime-bg: #f2f8d8;
    --navy: #0f2a3a; --white: #ffffff; --off-white: #f7f9f7;
    --text: #1e293b; --text-soft: #64748b; --text-muted: #94a3b8;
    --mid-gray: #e2e8f0;
  }
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Inter', sans-serif; color: var(--text); background: var(--white); }
  a { text-decoration: none; }

  /* ── NAV ── */
  .page-nav {
    position: sticky; top: 0; z-index: 100;
    background: rgba(255,255,255,0.97); backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--mid-gray);
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.9rem 2rem;
  }
  .page-nav-logo img { height: 28px; width: auto; }
  .nav-right { display: flex; align-items: center; gap: 0.75rem; margin-left: auto; }
  .nav-login {
    font-family: 'Barlow', sans-serif;
    font-size: 0.88rem; font-weight: 500;
    color: var(--navy); text-decoration: none;
    padding: 0.55rem 1.2rem; border-radius: 100px;
    border: 1px solid var(--mid-gray);
    transition: background 0.2s, border-color 0.2s;
  }
  .nav-login:hover { background: var(--off-white); border-color: #ccc; }
  .nav-cta {
    font-family: 'Barlow', sans-serif;
    font-size: 0.88rem; font-weight: 700;
    color: var(--white); text-decoration: none;
    padding: 0.6rem 1.4rem; border-radius: 100px;
    background: var(--teal);
    transition: background 0.2s, transform 0.15s; 
  }
  .nav-cta:hover { background: var(--teal-dark); transform: translateY(-1px); }
  .page-nav-links {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    align-items: center;
    gap: 6px;
    list-style: none;
    margin: 0;
    padding: 0;
  }
  .page-nav-links a {
    text-decoration: none;
    font-family: 'Barlow', sans-serif;
    color: #0d2b3e;
    font-size: 15px;
    font-weight: 500;
    padding: 7px 14px;
    border-radius: 8px;
    transition: background 0.18s, color 0.18s;
  }
  .page-nav-links a:hover { background: var(--off-white); color: var(--teal); }

  /* ── MOBILE MENU ── */
  .mobile-menu-toggle {
    display: none; background: none; border: none; flex-direction: column; gap: 5px; cursor: pointer; padding: 5px; z-index: 1001; margin-left: 1rem;
  }
  .mobile-menu-toggle span { width: 22px; height: 2px; background: var(--navy); border-radius: 2px; transition: 0.3s; }
  
  .mobile-menu {
    position: fixed; top: 0; right: -100%; width: 80%; max-width: 200px; height: 100vh; 
    background: white; z-index: 2000; padding: 60px 20px 30px; transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: -10px 0 30px rgba(0,0,0,0.1); display: flex; flex-direction: column;
  }
  .mobile-menu.active { right: 0; }
  .mobile-menu-close { position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 24px; color: var(--navy); cursor: pointer; }
  .mobile-nav-links { list-style: none; display: flex; flex-direction: column; gap: 15px; flex-grow: 1; margin-top: 10px; }
  .mobile-nav-links a { text-decoration: none; font-size: 14px; font-weight: 600; color: #0d2b3e; font-family: 'Barlow', sans-serif; }

  @media (max-width: 850px) {
    .page-nav-links { display: none !important; }
    .nav-right .nav-cta, .nav-right .nav-login { display: none !important; }
    .nav-auth-container { display: none !important; }
    .mobile-menu-toggle { display: flex !important; margin-left: auto; }
  }
  @media (max-width: 768px) {
    .page-nav { padding: 1rem; justify-content: space-between; }
    .page-nav-logo img { height: 26px !important; }
    .nav-right { gap: 0.1rem; margin-right: 0.5rem; }
    .nav-cta, .nav-login {
      font-size: 12px !important;
      padding: 0.4rem 0.6rem !important;
      white-space: nowrap;
    }
  }

  /* ── PAGE HERO ── */
  .page-hero {
    background: var(--navy);
    background-image: radial-gradient(circle at 70% 40%, rgba(26,122,138,0.2) 0%, transparent 60%);
    padding: 5.5rem 2rem 4.5rem; text-align: center; overflow: hidden; position: relative;
  }
  .page-hero-label {
    font-size: 0.72rem; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase;
    color: #5dcfe0; margin-bottom: 1rem; display: block;
  }
  .page-hero-title {
    font-family: 'Inter', sans-serif;
    font-size: clamp(2rem, 5vw, 3.2rem); font-weight: 800;
    color: white; margin-bottom: 1rem; letter-spacing: -0.03em; line-height: 1.1;
  }
  .page-hero-title em { font-style: normal; color: #5dcfe0; }
  .page-hero-sub {
    font-size: 15px; color: rgba(255,255,255,0.58);
    max-width: 560px; margin: 0 auto; line-height: 1.7;
  }
  .page-hero-dots {
    position: absolute; right: 5%; top: 20%;
    width: 120px; height: 120px; opacity: 0.06;
    background-image: radial-gradient(circle, white 1px, transparent 1px);
    background-size: 16px 16px;
    border-radius: 50%;
  }

  /* ── LAYOUT CONTAINERS ── */
  .container { max-width: 1100px; margin: 0 auto; padding: 0 2rem; }
  .section { padding: 5rem 0; }
  .section-bg { background: var(--off-white); }
  .section-dark { background: var(--navy); }

  /* ── TYPOGRAPHY UTILS ── */
  .section-label {
    font-size: 0.72rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase;
    color: var(--teal); margin-bottom: 0.65rem; display: block;
  }
  .section-title {
    font-family: 'Inter', sans-serif; font-size: clamp(1.7rem, 3vw, 2.4rem);
    font-weight: 800; color: var(--navy); margin-bottom: 1rem; letter-spacing: -0.03em; line-height: 1.15;
  }
  .section-title span { color: var(--teal); }
  .section-text { font-size: 0.97rem; color: var(--text-soft); line-height: 1.8; margin-bottom: 1rem; }

  /* ── CARD GRID ── */
  .card-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
  .card-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
  .card {
    background: var(--white); border-radius: 20px; padding: 2rem;
    border: 1.5px solid var(--mid-gray); transition: all 0.3s;
  }
  .card:hover { transform: translateY(-5px); border-color: var(--teal); box-shadow: 0 20px 40px rgba(26,122,138,0.08); }
  .card-icon {
    width: 46px; height: 46px; border-radius: 12px; background: var(--teal-light);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; color: var(--teal); margin-bottom: 1.2rem;
  }
  .card-title { font-size: 1rem; font-weight: 700; color: var(--navy); margin-bottom: 0.5rem; }
  .card-text { font-size: 0.88rem; color: var(--text-soft); line-height: 1.65; }

  /* ── ICON LIST ── */
  .icon-list { list-style: none; display: flex; flex-direction: column; gap: 0.9rem; }
  .icon-list li { display: flex; gap: 0.8rem; align-items: flex-start; font-size: 0.93rem; color: var(--text-soft); line-height: 1.5; }
  .icon-list li i { color: var(--teal); font-size: 1rem; margin-top: 0.15rem; flex-shrink: 0; }

  /* ── BADGE ── */
  .badge {
    display: inline-block; padding: 0.3rem 0.8rem; border-radius: 100px;
    font-size: 0.72rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;
  }
  .badge-teal { background: var(--teal-light); color: var(--teal); }
  .badge-lime { background: var(--lime-bg); color: var(--lime); }

  /* ── SEPARATOR ── */
  .sep { height: 1px; background: var(--mid-gray); margin: 0; }

  /* ── BTN ── */
  .btn-primary {
    display: inline-flex; align-items: center; gap: 0.5rem;
    background: var(--teal); color: white; padding: 0.85rem 1.8rem;
    border-radius: 12px; font-size: 0.95rem; font-weight: 700; transition: all 0.3s;
    box-shadow: 0 8px 20px rgba(26,122,138,0.2);
  }
  .btn-primary:hover { background: var(--teal-dark); transform: translateY(-2px); }
  .btn-secondary {
    display: inline-flex; align-items: center; gap: 0.5rem;
    border: 1.5px solid var(--mid-gray); color: var(--navy); padding: 0.85rem 1.8rem;
    border-radius: 12px; font-size: 0.95rem; font-weight: 600; transition: all 0.3s;
  }
  .btn-secondary:hover { border-color: var(--teal); color: var(--teal); }
  .btn-actions { display: flex; gap: 1rem; flex-wrap: wrap; }

  /* ── LAYOUT UTILS ── */
  .about-split { display: grid; grid-template-columns: 1fr 1fr; gap: 5rem; align-items: center; }
  .about-split.start { align-items: flex-start; }
  .contact-split { display: grid; grid-template-columns: 1fr 1.4fr; gap: 4rem; align-items: start; }
  .partners-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 1.2rem; align-items: center; margin-bottom: 2.5rem; }
  .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
  
  /* ── LEGAL PAGES ── */
  .legal-layout { display: grid; grid-template-columns: 220px 1fr; gap: 4rem; align-items: start; }
  .legal-sidebar { position: sticky; top: 100px; }
  .legal-content { max-width: 720px; }

  /* ── FOOTER ── */
  footer { background: var(--navy); padding: 4rem 1.5rem 2rem; }
  .footer-inner { max-width: 1200px; margin: 0 auto; }
  .footer-top {
    display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 2.5rem;
    padding-bottom: 3rem; border-bottom: 1px solid rgba(255,255,255,0.08);
  }
  .footer-brand-desc { font-size: 0.875rem; color: rgba(255,255,255,0.5); line-height: 1.7; margin: 1rem 0 1.5rem; max-width: 230px; }
  .footer-subscribe {
    display: flex; border-radius: 10px; overflow: hidden;
    border: 1px solid rgba(255,255,255,0.12); max-width: 280px;
  }
  .footer-subscribe input {
    flex: 1; background: rgba(255,255,255,0.05); border: none; outline: none;
    padding: 5px; font-size: 0.8rem; color: white; font-family: 'Inter', sans-serif;
  }
  .footer-subscribe input::placeholder { color: rgba(255,255,255,0.3); }
  .footer-subscribe button {
    background: var(--teal); color: white; border: none;
    padding: 0.65rem 1rem; font-size: 0.78rem; font-weight: 700;
    cursor: pointer; white-space: nowrap; transition: background 0.2s; font-family: 'Inter', sans-serif;
  }
  .footer-subscribe button:hover { background: var(--teal-dark); }
  .footer-col { padding-left: 1.5rem; }
  .footer-col-title {
    font-size: 0.78rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase;
    color: rgba(255,255,255,0.9); margin-bottom: 1.2rem;
  }
  .footer-col-links { list-style: none; display: flex; flex-direction: column; gap: 0.75rem; }
  .footer-col-links a { font-size: 0.875rem; color: rgba(255,255,255,0.45); text-decoration: none; transition: color 0.2s; }
  .footer-col-links a:hover { color: rgba(255,255,255,0.9); }
  .footer-bottom { display: flex; justify-content: space-between; align-items: center; padding-top: 1.5rem; flex-wrap: wrap; gap: 1rem; }
  .footer-copy { font-size: 0.76rem; color: rgba(255,255,255,0.25); }
  .footer-social { display: flex; gap: 0.75rem; }
  .footer-social a {
    width: 32px; height: 32px; border-radius: 8px;
    background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);
    display: flex; align-items: center; justify-content: center;
    color: rgba(255,255,255,0.4); font-size: 0.8rem; text-decoration: none; transition: all 0.2s;
  }
  .footer-social a:hover { background: var(--teal); border-color: var(--teal); color: white; }

  @media (max-width: 900px) {
    .footer-top { grid-template-columns: 1fr 1fr; gap: 2rem; }
    .footer-brand { grid-column: 1 / -1; }
    .footer-subscribe { max-width: 100%; }
    .card-grid-2, .card-grid-3 { grid-template-columns: 1fr; }
    .about-split { grid-template-columns: 1fr; gap: 3rem; }
    .contact-split { grid-template-columns: 1fr; gap: 3rem; }
    .partners-grid { grid-template-columns: repeat(3, 1fr); }
    .legal-layout { grid-template-columns: 1fr; gap: 2rem; }
    .legal-sidebar { position: static; border-bottom: 1px solid var(--mid-gray); padding-bottom: 1.5rem; margin-bottom: 1rem; display: none; } /* Hidden on mobile to save space */
  }
  @media (max-width: 768px) {
    .page-hero { padding: 4rem 1.5rem 3rem !important; }
    .page-hero-title { font-size: 2.2rem !important; }
    .page-hero-sub { font-size: 14px !important; }
    .section { padding: 3.5rem 0 !important; }
    .container { padding: 0 1.25rem !important; }
    .section-title { font-size: 1.8rem !important; }
  }
  @media (max-width: 640px) {
    .footer-top { grid-template-columns: 1fr; }
    .footer-bottom { flex-direction: column; text-align: center; }
    .page-nav { padding: 0.9rem 1rem; }
    .partners-grid { grid-template-columns: repeat(2, 1fr); }
    .form-row { grid-template-columns: 1fr; }
    
    .footer-col-title { cursor: pointer; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 1rem; margin-bottom: 0; }
    .footer-col-title::after { content: '\f078'; font-family: 'Font Awesome 5 Free'; font-weight: 900; font-size: 0.8rem; transition: transform 0.3s; }
    .footer-col.active .footer-col-title::after { transform: rotate(180deg); }
    .footer-col-links { display: none; padding-top: 1rem; padding-bottom: 1rem; }
    .footer-col.active .footer-col-links { display: flex; }
    .footer-col { padding-left: 0; }
  }

  /* ── REVEAL ── */
  .reveal { opacity: 0; transform: translateY(24px); transition: opacity 0.7s ease, transform 0.7s ease; }
  .reveal.visible { opacity: 1; transform: none; }
  .d1 { transition-delay: 0.1s; } .d2 { transition-delay: 0.22s; } .d3 { transition-delay: 0.36s; }
</style>
</head>
<body>

<!-- NAV -->
<nav class="page-nav">
  <a href="{{ route('home') }}" class="page-nav-logo">
    <img src="{{ asset('images/logo-viantryp.png') }}" alt="Viantryp" style="filter: invert(1) hue-rotate(180deg) contrast(1.5);">
  </a>
  <div class="page-nav-links">
    <a href="{{ route('home') }}#como-funciona">Cómo funciona</a>
    <a href="{{ route('home') }}#demo">Ver Demo</a>
    <a href="{{ route('home') }}#precios">Precios</a>
    <a href="{{ route('contact') }}">Contacto</a>
  </div>
  <div class="nav-right">
    @auth
        <div class="nav-auth-container" style="display: flex; align-items: center; gap: 1.25rem;">
            <a href="{{ route('trips.index') }}" class="nav-login">Ir a Mis Viajes</a>
            
            <div class="user-profile-dropdown" style="position: relative;">
                <div id="profileTriggerStatic" style="display: flex; align-items: center; gap: 0.3rem; cursor: pointer;">
                    <span style="font-size: 12px; font-weight: 600; color: var(--navy);">
                        {{ auth()->user()->name }}
                    </span>
                    <div style="width: 36px; height: 36px; border-radius: 50%; background-color: var(--teal); color: var(--white); display: flex; align-items: center; justify-content: center; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1rem; text-decoration: none; border: 2px solid var(--teal-light); transition: transform 0.2s;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>

                <div id="profileMenuStatic" class="dropdown-menu-content" style="display: none; position: absolute; top: calc(100% + 10px); right: 0; background: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 180px; overflow: hidden; z-index: 1000; border: 1px solid var(--mid-gray); text-align: left;">
                    <a href="{{ route('trips.index') }}" class="dropdown-item" style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: var(--text); text-decoration: none; font-size: 13px; font-weight: 500; transition: background 0.2s;">
                        <i class="fas fa-suitcase-rolling" style="color: #64748b; font-size: 15px;"></i>
                        Mis viajes
                    </a>
                    <div style="height: 1px; background: var(--mid-gray);"></div>
                    <a href="{{ route('profile.index') }}" class="dropdown-item" style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: var(--text); text-decoration: none; font-size: 13px; font-weight: 500; transition: background 0.2s;">
                        <i class="fas fa-user-circle" style="color: #64748b; font-size: 15px;"></i>
                        Mi perfil
                    </a>
                    <div style="height: 1px; background: var(--mid-gray);"></div>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form" style="margin: 0;">
                        @csrf
                        <button type="submit" class="dropdown-item" style="width: 100%; border: none; background: transparent; display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: #c0392b; cursor: pointer; text-align: left; font-size: 13px; font-weight: 500; transition: background 0.2s; font-family: 'Inter', sans-serif;">
                            <i class="fas fa-sign-out-alt" style="font-size: 15px;"></i>
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <script>
            (function() {
                const initProfileMenu = () => {
                    const trigger = document.getElementById('profileTriggerStatic');
                    const menu = document.getElementById('profileMenuStatic');

                    if (trigger && menu) {
                        trigger.addEventListener('click', function(e) {
                            e.stopPropagation();
                            const isVisible = menu.style.display === 'block';
                            menu.style.display = isVisible ? 'none' : 'block';
                        });
                    }

                    document.addEventListener('click', function(e) {
                        if (trigger && menu && !trigger.contains(e.target) && !menu.contains(e.target)) {
                            menu.style.display = 'none';
                        }
                    });

                    const items = menu?.querySelectorAll('.dropdown-item');
                    items?.forEach(item => {
                        item.addEventListener('mouseover', () => item.style.background = '#f8fafc');
                        item.addEventListener('mouseout', () => item.style.background = 'transparent');
                    });
                };
                if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initProfileMenu);
                else initProfileMenu();
            })();
        </script>
    @else
        <a href="{{ route('login') }}" class="nav-login">Iniciar sesión</a>
        <a href="{{ route('register') }}" class="nav-cta">Comenzar gratis</a>
    @endauth
    <button class="mobile-menu-toggle" id="mobileMenuBtnStatic">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>

@yield('content')

<!-- FOOTER -->
<footer>
  <div class="footer-inner">
    <div class="footer-top">
      <div class="footer-brand">
        <div><img src="{{ asset('images/logo-viantryp.png') }}" alt="Viantryp" style="height: 28px; filter: brightness(0) invert(1);"></div>
        <p class="footer-brand-desc">La plataforma que transforma itinerarios de viaje en experiencias digitales modernas.</p>
        <div class="footer-parte-de" style="margin-top: 2rem;">
          <h4 style="font-size: 0.78rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: rgba(255,255,255,0.5); margin-bottom: 0.8rem;">PARTE DE</h4>
          <div>
            <img src="{{ asset('images/startub.png') }}" alt="StartUB! Universitat de Barcelona" style="height: 35px; filter: brightness(0) invert(1); opacity: 0.9;">
          </div>
        </div>
      </div>
      <div class="footer-col">
        <div class="footer-col-title">Producto</div>
        <ul class="footer-col-links">
          <li><a href="{{ route('home') }}#demo">Demo interactiva</a></li>
          <li><a href="{{ route('home') }}#como-funciona">Funcionalidades</a></li>
          <li><a href="{{ route('home') }}#precios">Precios</a></li>
          <li><a href="{{ route('home') }}#soluciones">Soluciones</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <div class="footer-col-title">Legal</div>
        <ul class="footer-col-links">
          <li><a href="{{ route('terms') }}">Términos de uso</a></li>
          <li><a href="{{ route('privacy') }}">Privacidad</a></li>
          <li><a href="{{ route('gdpr') }}">RGPD</a></li>
          <li><a href="{{ route('security') }}">Seguridad</a></li>
          <li class="contact-desktop-li" style="margin-top: 0.5rem; padding-top: 0.8rem; border-top: 1px solid rgba(255,255,255,0.1);"><a href="{{ route('contact') }}">Contacto</a></li>
        </ul>
      </div>
      
      <!-- CONTACTO (MOBILE ONLY) -->
      <style>
        .contact-mobile-col { display: none; }
        .contact-desktop-li { display: list-item; }
        @media (max-width: 640px) {
          .contact-mobile-col { display: block; margin-top: 0; padding-top: 0; }
          .contact-desktop-li { display: none !important; }
          .contact-mobile-col .footer-col-title::after { display: none !important; }
        }
      </style>
      <div class="footer-col contact-mobile-col">
        <a href="{{ route('contact') }}" class="footer-col-title" style="text-decoration:none; display:flex;">Contacto</a>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="footer-copy">© 2026 Viantryp. Todos los derechos reservados.</div>
    </div>
  </div>
</footer>

<script>
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
  }, { threshold: 0.1 });
  document.querySelectorAll('.reveal').forEach(el => obs.observe(el));

  document.querySelectorAll('.footer-col-title').forEach(title => {
    title.addEventListener('click', () => {
      if(window.innerWidth <= 640) {
        const col = title.parentElement;
        const wasActive = col.classList.contains('active');
        document.querySelectorAll('.footer-col').forEach(c => c.classList.remove('active'));
        if(!wasActive) col.classList.add('active');
      }
    });
  });
</script>
@stack('scripts')
<div class="mobile-menu" id="mobileMenuStatic">
  <button class="mobile-menu-close" id="mobileMenuCloseStatic">&times;</button>
  <ul class="mobile-nav-links">
    <li><a href="{{ route('home') }}#como-funciona">Cómo funciona</a></li>
    <li><a href="{{ route('home') }}#demo">Ver Demo</a></li>
    <li><a href="{{ route('home') }}#precios">Precios</a></li>
    <li><a href="{{ route('contact') }}">Contacto</a></li>
  </ul>

  <div class="mobile-auth" style="margin-top: auto; padding-top: 20px; border-top: 1px solid var(--mid-gray); display: flex; flex-direction: column; gap: 10px;">
    @auth
      <div style="font-size:12px; font-weight:600; color:var(--text-soft); text-align:center;">Hola, {{ auth()->user()->name }}</div>
      <a href="{{ route('trips.index') }}" class="nav-cta" style="width: 100%; justify-content: center; text-align: center; padding: 10px; display: block;">Mis viajes</a>
      <form method="POST" action="{{ route('logout') }}" style="margin: 0; width: 100%;">
          @csrf
          <button type="submit" style="width: 100%; border: 1px solid var(--mid-gray); border-radius: 100px; background: transparent; padding: 10px; color: #c0392b; cursor: pointer; text-align: center; font-size: 13px; font-weight: 500; font-family: 'Inter', sans-serif;">
              Cerrar sesión
          </button>
      </form>
    @else
      <a href="{{ route('register') }}" class="nav-cta" style="width: 100%; display: block; text-align: center; padding: 10px; margin-bottom: 5px;">Comenzar gratis</a>
      <a href="{{ route('login') }}" class="nav-login" style="width: 100%; text-align: center; padding: 10px; display: block;">Iniciar sesión</a>
    @endauth
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('mobileMenuBtnStatic');
    const close = document.getElementById('mobileMenuCloseStatic');
    const menu = document.getElementById('mobileMenuStatic');
    
    if (btn && close && menu) {
      btn.addEventListener('click', () => menu.classList.add('active'));
      close.addEventListener('click', () => menu.classList.remove('active'));
      
      menu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
          menu.classList.remove('active');
        });
      });
    }
  });
</script>

</body>
</html>
