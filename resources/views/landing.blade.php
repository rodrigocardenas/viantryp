<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Viantryp | Home</title>
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800;900&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
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
    font-family: 'Barlow', sans-serif;
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
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 1.8rem; font-weight: 900;
    letter-spacing: -0.04em; color: var(--navy);
    text-decoration: none;
  }
  .nav-logo span { color: var(--teal); }
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
    background: #fff;
    overflow: hidden;
  }
  .hero-bg-video {
    position: absolute;
    top: 50%;
    left: 50%;
    min-width: 100%;
    min-height: 100%;
    width: auto;
    height: auto;
    transform: translate(-50%, -50%);
    object-fit: cover;
    opacity: 0.04;
    pointer-events: none;
    z-index: -1;
  }
  .hero-blob {
    position: absolute;
    filter: blur(80px);
    border-radius: 50%;
    z-index: 0;
    opacity: 0.15;
    animation: blobFloat 20s infinite alternate;
  }
  .blob-1 { width: 400px; height: 400px; background: var(--teal); top: -100px; left: -100px; animation-duration: 25s; }
  .blob-2 { width: 350px; height: 350px; background: var(--lime); bottom: -100px; right: -100px; animation-duration: 18s; animation-delay: -5s; }
  .blob-3 { width: 300px; height: 300px; background: var(--teal); top: 50%; left: 60%; animation-duration: 22s; animation-delay: -10s; }

  @keyframes blobFloat {
    0% { transform: translate(0, 0) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0, 0) scale(1); }
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
    font-family: inter, sans-serif;
    font-size: clamp(2.5rem, 8vw, 70px);
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
    padding: 14px 18px; border-radius: 100px;
    font-weight: 700; font-size: 1rem; text-decoration: none;
    transition: all 0.2s; box-shadow: 0 4px 24px rgba(26,122,138,0.2);
  }
  .btn-primary:hover { background: var(--teal-dark); transform: translateY(-2px); box-shadow: 0 8px 32px rgba(26,122,138,0.28); }
  .btn-secondary {
    background: var(--white); border: 1.5px solid var(--mid-gray);
    color: var(--navy); padding: 14px 21px; border-radius: 100px;
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
  /* ── CREATIVE STEPS ── */
  .creative-steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 3rem;
    margin-top: 3rem;
  }
  .creative-step {
    position: relative;
    padding-top: 2rem;
  }
  .step-giant-num {
    position: absolute;
    top: -20px;
    left: -10px;
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 8rem;
    font-weight: 900;
    color: var(--teal);
    opacity: 0.18;
    line-height: 1;
    z-index: 0;
    pointer-events: none;
  }
  .step-card {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.4);
    border-radius: 24px;
    padding: 2.5rem;
    position: relative;
    z-index: 1;
    box-shadow: 0 20px 40px rgba(0,0,0,0.03);
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    text-align: left;
  }
  .creative-step:hover .step-card {
    transform: translateY(12px);
    background: white;
    box-shadow: 0 30px 60px rgba(26,122,138,0.12);
    border-color: var(--teal);
  }
  .step-card h3 {
    font-family: 'Syne', sans-serif;
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: var(--navy);
  }
  .step-card p {
    font-size: 0.95rem;
    color: var(--text-soft);
    line-height: 1.6;
  }
  
  /* ── UNIQUE TRIPS DELETED ── */

  /* ── FOR WHO ── */
  .forwho { background: var(--off-white); }
  /* ── FOR WHO TOTAL OVERHAUL ── */
  /* ── FOR WHO TOTAL OVERHAUL ── */
  .forwho { 
    background: white; 
    padding: 6rem 0; 
    overflow: hidden;
    position: relative;
  }
  .audience-dual {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-top: 3rem;
  }
  .audience-block {
    position: relative;
    border-radius: 40px;
    padding: 1.5rem 2.5rem;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    overflow: hidden;
    transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    cursor: pointer;
    border: 1.2px solid var(--mid-gray);
    background: #fcfcfc;
  }
  .audience-block::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, transparent 0%, rgba(255,255,255,0.05) 100%);
    z-index: 2;
  }
  .audience-block:hover {
    transform: scale(1.02) translateY(-10px);
    background: white;
  }
  .audience-block.agencies:hover {
    box-shadow: 0 40px 100px rgba(26,122,138,0.12);
    border-color: var(--teal);
  }
  .audience-block.consultants:hover {
    box-shadow: 0 40px 100px rgba(138,184,32,0.12);
    border-color: var(--lime);
  }
  
  .block-content {
    position: relative;
    z-index: 3;
    transition: transform 0.6s ease;
  }
  .audience-block:hover .block-content {
    transform: translateY(-8px);
  }
  .block-tag {
    display: inline-block;
    padding: 0.4rem 1rem;
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 1.2rem;
  }
  .agencies .block-tag { background: var(--teal-light); color: var(--teal); }
  .consultants .block-tag { background: var(--lime-bg); color: var(--lime); }
  
  .block-title {
    font-family: 'Syne', sans-serif;
    font-size: 30px;
    font-weight: 700;
    color: var(--navy);
    line-height: 1.1;
    margin-bottom: 1.5rem;
  }
  .block-desc {
    color: var(--text-soft);
    font-size: 1.1rem;
    line-height: 1.6;
    max-width: 400px;
  }
  
  @media (max-width: 992px) {
    .audience-dual { grid-template-columns: 1fr; }
    .audience-block { height: auto; padding: 2rem; }
    .block-title { font-size: 1.5rem; }
  }

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
  .plan { 
    background: var(--white); 
    border: 1.5px solid var(--mid-gray); 
    border-radius: 20px; 
    padding: 2.5rem;
    transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
  }
  .plan:hover {
    transform: scale(1.02) translateY(-10px);
    border-color: var(--teal);
    box-shadow: 0 40px 80px rgba(26,122,138,0.12);
  }
  .plan.featured:hover {
    transform: scale(1.05) translateY(-12px);
    border-color: #5dcfe0;
    box-shadow: 0 40px 100px rgba(93,207,224,0.35);
    background: #0d1526;
  }
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
    .hero { padding-top: 6rem; padding-bottom: 3rem; }
    .section-title { font-size: 1.8rem !important; }
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
            
            <div class="user-profile-dropdown" style="position: relative;">
                <div id="profileTrigger" style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <span style="font-size: 12px; font-weight: 600; color: var(--navy);">
                        {{ auth()->user()->name }}
                    </span>
                    <div style="width: 36px; height: 36px; border-radius: 50%; background-color: var(--teal); color: var(--white); display: flex; align-items: center; justify-content: center; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1rem; text-decoration: none; border: 2px solid var(--teal-light); transition: transform 0.2s;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>

                <div id="profileMenu" class="dropdown-menu-content" style="display: none; position: absolute; top: calc(100% + 10px); right: 0; background: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 180px; overflow: hidden; z-index: 1000; border: 1px solid var(--mid-gray); text-align: left;">
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
                        <button type="submit" class="dropdown-item" style="width: 100%; border: none; background: transparent; display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: #c0392b; cursor: pointer; text-align: left; font-size: 13px; font-weight: 500; transition: background 0.2s; font-family: 'DM Sans', sans-serif;">
                            <i class="fas fa-sign-out-alt" style="font-size: 15px;"></i>
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <script>
            (function() {
                const initMenu = () => {
                    const trigger = document.getElementById('profileTrigger');
                    const menu = document.getElementById('profileMenu');
                    if (trigger && menu) {
                        trigger.addEventListener('click', function(e) {
                            e.stopPropagation();
                            const isVisible = menu.style.display === 'block';
                            menu.style.display = isVisible ? 'none' : 'block';
                        });
                        document.addEventListener('click', function(e) {
                            if (!trigger.contains(e.target) && !menu.contains(e.target)) {
                                menu.style.display = 'none';
                            }
                        });
                        const items = menu.querySelectorAll('.dropdown-item');
                        items.forEach(item => {
                            item.addEventListener('mouseover', () => item.style.background = '#f8fafc');
                            item.addEventListener('mouseout', () => item.style.background = 'transparent');
                        });
                    }
                };
                if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initMenu);
                else initMenu();
            })();
        </script>
    @else
        <a href="{{ route('login') }}" class="nav-login">Iniciar sesión</a>
        <a href="{{ route('register') }}" class="nav-cta">Comenzar gratis</a>
    @endauth
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-bg">
    <video class="hero-bg-video" autoplay muted loop playsinline>
        <source src="{{ asset('videos/hero-bg.mp4') }}" type="video/mp4">
    </video>
    <div class="hero-blob blob-1"></div>
    <div class="hero-blob blob-2"></div>
    <div class="hero-blob blob-3"></div>
  </div>
  <div class="hero-badge">✦Lleva tus viajes a otro nivel</div>
  <h1>Diseña tus viajes<br><em>en cuestión</em> de minutos</h1>
  <p>Organiza rutas, vuelos y estancias en una plataforma elegante e intuitiva. Ya sea para tu próximo gran viaje personal o para escalar tu negocio, Viantryp es el lienzo donde tus itinerarios cobran vida.</p>
  <div class="hero-actions">
    @auth
        <a href="{{ route('trips.index') }}" class="btn-primary">Ir a mis viajes →</a>
    @else
        <a href="#cta" class="btn-primary">Empezar ahora →</a>
        <a href="#demo" class="btn-secondary">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; display: inline-block; vertical-align: middle; margin-top: -2px;">
            <circle cx="12" cy="12" r="10"></circle>
            <polygon points="10 8 16 12 10 16 10 8"></polygon>
          </svg>
          Ver Demo
        </a>
    @endauth
  </div>
</section>

<!-- DEMO SECTION -->
<section class="demo-section" id="demo" style="background: #0f2a3a; background-image: radial-gradient(circle at 50% 0%, rgba(26, 122, 138, 0.15) 0%, transparent 70%);">
  <div class="container" style="max-width: 1000px;">
    <div class="reveal" style="text-align: center; margin-bottom: 1.0rem;">
      <div class="section-label" style="color: #5dcfe0;">Demo Interactiva</div>
      <p style="color: rgba(255,255,255,0.7); font-size: 15px; line-height: 1.5;">Prueba nuestra interfaz: arrastra servicios, organizalos y descubre la facilidad de crear itinerarios en segundos.</p>
    </div>

    <!-- PREMIUM HERO MOCKUP -->
    <div class="hero-mockup reveal d2" style="max-width: 1040px; margin: 0 auto; border: 1.5px solid rgba(255,255,255,0.1); border-radius: 20px; box-shadow: 0 30px 60px rgba(0,0,0,0.4);">
      <div class="mockup-frame" style="border: none;">
        <div class="mockup-bar">
          <div class="mockup-dot"></div>
          <div class="mockup-dot"></div>
          <div class="mockup-dot"></div>
        </div>
        <div class="mockup-inner">

      <div class="vt-root">
        <div class="vt-topbar">
          <img src="{{ asset('images/logo-viantryp.png') }}" alt="Viantryp" style="height: 24px; width: auto; display: block;">
        </div>

        <div class="vt-body">
          <!-- SIDEBAR -->
          <div class="vt-sidebar">
            <div class="vt-section-label">Servicios</div>
            <div class="vt-grid">
              <div class="vt-card" draggable="true" data-type="Vuelo"><div class="vt-card-drag"><span><i></i><i></i><i></i><i></i></span><span><i></i><i></i><i></i><i></i></span></div><div class="vt-card-icon icon-flight">✈️</div><div class="vt-card-name">Vuelo</div><div class="vt-card-sub">Agregar vuelo</div></div>
              <div class="vt-card" draggable="true" data-type="Alojamiento"><div class="vt-card-drag"><span><i></i><i></i><i></i><i></i></span><span><i></i><i></i><i></i><i></i></span></div><div class="vt-card-icon icon-hotel">🏨</div><div class="vt-card-name">Alojamiento</div><div class="vt-card-sub">Hotel u hospedaje</div></div>
              <div class="vt-card" draggable="true" data-type="Traslado"><div class="vt-card-drag"><span><i></i><i></i><i></i><i></i></span><span><i></i><i></i><i></i><i></i></span></div><div class="vt-card-icon icon-car">🚗</div><div class="vt-card-name">Traslado</div><div class="vt-card-sub">Bus, tren u otro</div></div>
              <div class="vt-card" draggable="true" data-type="Actividad"><div class="vt-card-drag"><span><i></i><i></i><i></i><i></i></span><span><i></i><i></i><i></i><i></i></span></div><div class="vt-card-icon icon-act">🎯</div><div class="vt-card-name">Actividad</div><div class="vt-card-sub">Tour o experiencia</div></div>
              <div class="vt-card" draggable="true" data-type="Comida"><div class="vt-card-drag"><span><i></i><i></i><i></i><i></i></span><span><i></i><i></i><i></i><i></i></span></div><div class="vt-card-icon icon-food">🍽️</div><div class="vt-card-name">Comida</div><div class="vt-card-sub">Restaurante y más</div></div>
              <div class="vt-card" draggable="true" data-type="Tour"><div class="vt-card-drag"><span><i></i><i></i><i></i><i></i></span><span><i></i><i></i><i></i><i></i></span></div><div class="vt-card-icon icon-tour">🗺️</div><div class="vt-card-name">Tour</div><div class="vt-card-sub">Guías y grupos</div></div>
            </div>
            <div class="vt-section-label">Diseño</div>
            <div class="vt-grid">
              <div class="vt-card" draggable="true" data-type="Texto"><div class="vt-card-drag"><span><i></i><i></i><i></i><i></i></span><span><i></i><i></i><i></i><i></i></span></div><div class="vt-card-icon icon-txt">Aa</div><div class="vt-card-name">Texto</div><div class="vt-card-sub">Caja de texto</div></div>
              <div class="vt-card" draggable="true" data-type="Título"><div class="vt-card-drag"><span><i></i><i></i><i></i><i></i></span><span><i></i><i></i><i></i><i></i></span></div><div class="vt-card-icon icon-title">T</div><div class="vt-card-name">Título</div><div class="vt-card-sub">Encabezado</div></div>
              <div class="vt-card" draggable="true" data-type="Separador"><div class="vt-card-drag"><span><i></i><i></i><i></i><i></i></span><span><i></i><i></i><i></i><i></i></span></div><div class="vt-card-icon icon-sep" style="font-size:7px">—✦—</div><div class="vt-card-name">Separador</div><div class="vt-card-sub">División</div></div>
              <div class="vt-card" draggable="true" data-type="Caja"><div class="vt-card-drag"><span><i></i><i></i><i></i><i></i></span><span><i></i><i></i><i></i><i></i></span></div><div class="vt-card-icon icon-box">🎨</div><div class="vt-card-name">Caja</div><div class="vt-card-sub">Notas fondo</div></div>
              <div class="vt-card" draggable="true" data-type="Imagen"><div class="vt-card-drag"><span><i></i><i></i><i></i><i></i></span><span><i></i><i></i><i></i><i></i></span></div><div class="vt-card-icon icon-img">🖼️</div><div class="vt-card-name">Imagen</div><div class="vt-card-sub">Subir foto</div></div>
              <div class="vt-card" draggable="true" data-type="Gif"><div class="vt-card-drag"><span><i></i><i></i><i></i><i></i></span><span><i></i><i></i><i></i><i></i></span></div><div class="vt-card-icon icon-gif">🎬</div><div class="vt-card-name">Gif</div><div class="vt-card-sub">Animación</div></div>
            </div>
          </div>

          <!-- MAIN CANVAS AREA -->
          <div class="vt-main-wrap">
            <div class="vt-main">
              <div class="vt-toolbar">
                <div class="vt-tabs-row" id="vtTabsContainer">
                    <button class="vt-tab active" id="vtTab0" onclick="vtSwitch(0)">Día 1 <span class="vt-tab-x">✕</span></button>
                    <button class="vt-tab"        id="vtTab1" onclick="vtSwitch(1)">Día 2 <span class="vt-tab-x">✕</span></button>
                    <button class="vt-tab"        id="vtTab2" onclick="vtSwitch(2)">Día 3 <span class="vt-tab-x">✕</span></button>
                </div>
                <div class="vt-toolbar-spacer"></div>
                <span class="vt-item-count" id="vtCount">0 elementos</span>
              </div>
              <div class="vt-canvas" id="vtCanvas"
                   ondragover="vtHandleDragOver(event)"
                   ondragleave="vtHandleDragLeave(event)"
                   ondrop="vtDrop(event)">
                <div class="vt-canvas-inner">
                  <div class="vt-empty" id="vtEmpty" style="display:none">
                    <div class="vt-empty-icon">🗺️</div>
                    <div class="vt-empty-title">Tu itinerario está vacío</div>
                    <div class="vt-empty-sub">Arrastra elementos desde el panel<br>izquierdo para comenzar</div>
                  </div>
                  <div class="vt-items" id="vtItems"></div>
                  <div class="vt-drop-hint" id="vtDropHint">+ Arrastra más elementos aquí</div>
                </div>
              </div>
            </div>
            <div class="vt-toast" id="vtToastEl"></div>
          </div>
            </div> <!-- .vt-body -->
          </div> <!-- .vt-root -->
        </div> <!-- .mockup-inner -->
      </div> <!-- .mockup-frame -->
    </div> <!-- .hero-mockup -->
  </div> <!-- .container -->
</section>

<!-- HOW IT WORKS -->
<section class="how" id="como-funciona" style="background: #f8fafc; padding: 8rem 2.5rem; position: relative;">
  <div class="container">
    <div class="reveal" style="text-align: left; margin-bottom: 5rem;">
      <div class="section-label">Proceso</div>
      <h2 class="section-title">Plasma tu gran viaje<br>en solo 3 pasos</h2>
    </div>
    
    <div class="creative-steps">
      <div class="creative-step reveal d1">
        <div class="step-giant-num">01</div>
        <div class="step-card">
          <h3>Construye el itinerario</h3>
          <p>Usa nuestro editor visual para estructurar tu viaje día a día. Arrastra destinos, rutas y fotos con total libertad</p>
        </div>
      </div>
      <div class="creative-step reveal d2">
        <div class="step-giant-num">02</div>
        <div class="step-card">
          <h3>Dale tu toque</h3>
          <p>Personaliza colores, añade documentos importantes o tu marca personal. Haz que cada itinerario cuente una historia única.</p>
        </div>
      </div>
      <div class="creative-step reveal d3">
        <div class="step-giant-num">03</div>
        <div class="step-card">
          <h3>Llévalo contigo</h3>
          <p>Comparte un solo enlace inteligente. Sin archivos pesados ni apps extra; toda la información accesible desde cualquier lugar.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- TRAVELER EXPERIENCE -->
<section class="travel-exp">
  <div class="container">
    <div class="travel-grid">
      <div class="travel-text reveal">
        <div class="section-label">Visualiza tu viaje de forma moderna</div>
        <h2 class="section-title">Todo lo que necesitas, en un solo lugar</h2>
        <p class="section-desc">Una experiencia digital moderna y dinámica que transforma tus viajes en piezas únicas, accesibles desde cualquier dispositivo.</p>
      </div>
      
      <div class="reveal d2">
        <div class="mockups-container">
          <!-- Laptop -->
          <div class="laptop-wrap">
            <div class="l-screen">
              <div class="l-bar"><div class="l-dot"></div><div class="l-dot"></div><div class="l-dot"></div></div>
              <img src="{{ asset('images/mockup-trip-desktop.png') }}" alt="Itinerario Desktop" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
          </div>
          
          <!-- Phone -->
          <div class="phone-wrap">
            <div class="p-frame">
              <div class="p-screen">
                <img src="{{ asset('images/mockup-trip-mobile.png') }}" alt="Itinerario Mobile" style="width: 100%; height: 95%; object-fit: cover;">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<style>
  /* ── DUBAI DEMO STYLES ── */
  .vt-root {
    font-family: 'Barlow', sans-serif;
    font-size: 13px; color: #1a2e2c; background: #f5f7fa;
    border-radius: 0; overflow: hidden; border: none;
    display: flex; flex-direction: column; height: 460px;
    text-align: left;
  }
  .vt-topbar { background: #0f2a3a; display: flex; align-items: center; height: 44px; padding: 0 18px; flex-shrink: 0; }
  
  .vt-body { display: flex; flex: 1; overflow: hidden; }
  .vt-sidebar { width: 184px; background: white; border-right: 1px solid #e2e8ef; overflow-y: auto; flex-shrink: 0; padding-bottom: 12px; }
  .vt-sidebar::-webkit-scrollbar { width: 3px; }
  .vt-sidebar::-webkit-scrollbar-thumb { background: #dde; border-radius: 4px; }
  
  .vt-section-label { font-family: 'Barlow Condensed', sans-serif; font-size: 9px; font-weight: 800; letter-spacing: 1.3px; text-transform: uppercase; color: #94a3b8; padding: 13px 12px 5px; }
  .vt-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 5px; padding: 0 7px; }
  
  .vt-card { border: 1px solid #e8eef2; border-radius: 9px; padding: 9px 8px 8px; background: #fafcfc; cursor: grab; transition: all 0.15s; position: relative; }
  .vt-card:hover { border-color: #3db898; background: #f0faf6; transform: translateY(-1px); box-shadow: 0 3px 8px rgba(61,184,152,0.15); }
  .vt-card:active { cursor: grabbing; transform: scale(0.97); }
  
  .vt-card-drag { position: absolute; top: 5px; right: 4px; display: flex; gap: 2px; opacity: 0; transition: opacity 0.15s; }
  .vt-card:hover .vt-card-drag { opacity: 1; }
  .vt-card-drag span { display: flex; flex-direction: column; gap: 2px; }
  .vt-card-drag i { display: block; width: 2px; height: 2px; background: #b0bec5; border-radius: 50%; }
  
  .vt-card-icon { width: 28px; height: 28px; border-radius: 7px; display: flex; align-items: center; justify-content: center; font-size: 13px; margin-bottom: 5px; }
  .vt-card-name { font-size: 11px; font-weight: 700; color: #1a2e2c; line-height: 1.2; }
  .vt-card-sub { font-size: 9px; color: #94a3b8; margin-top: 1px; line-height: 1.3; }
  
  .icon-flight { background: #e8f0fe; color: #4285f4; }
  .icon-hotel { background: #fff3e0; color: #f57c00; }
  .icon-car { background: #f3e5f5; color: #9c27b0; }
  .icon-act { background: #e8f5e9; color: #4caf50; }
  .icon-food { background: #fce4ec; color: #e91e63; }
  .icon-tour { background: #e3f2fd; color: #2196f3; }
  .icon-txt { background: #f5f5f5; color: #607d8b; font-family: 'Barlow Condensed'; font-weight: 800; font-size: 12px; }
  .icon-title { background: #f5f5f5; color: #37474f; font-family: 'Barlow Condensed'; font-weight: 900; font-size: 15px; }
  .icon-sep { background: #f5f5f5; color: #90a4ae; font-size: 8px; }
  .icon-box { background: #fce4ec; color: #e91e63; }
  .icon-img { background: #fff8e1; color: #ff8f00; }
  .icon-gif { background: #f3e5f5; color: #7b1fa2; }
  
  .vt-main-wrap { position: relative; flex: 1; display: flex; flex-direction: column; overflow: hidden; }
  .vt-main { flex: 1; display: flex; flex-direction: column; overflow: hidden; background: #f0f4f8; }
  .vt-toolbar { background: white; border-bottom: 1px solid #e2e8ef; display: flex; align-items: center; gap: 4px; padding: 0 12px; height: 40px; flex-shrink: 0; }
  .vt-tabs-row { display: flex; gap: 4px; }
  .vt-tab { padding: 4px 11px; border-radius: 7px; font-size: 11px; font-weight: 600; cursor: pointer; color: #1a2e2c; border: 1px solid transparent; transition: all 0.15s; white-space: nowrap; background: none; font-family: 'Barlow', sans-serif; display: flex; align-items: center; gap: 5px; }
  .vt-tab:hover { background: #f0f9f6; color: #3db898; }
  .vt-tab.active { background: linear-gradient(135deg, #3db898, #62d4b5); color: white; border-color: transparent; }
  .vt-tab-x { font-size: 9px; color: #b0bec5; cursor: pointer; line-height: 1; }
  .vt-tab.active .vt-tab-x { color: rgba(255,255,255,0.6); }
  .vt-toolbar-spacer { flex: 1; }
  .vt-item-count { font-size: 11px; color: #94a3b8; }
  
  .vt-canvas { flex: 1; overflow-y: auto; padding: 16px; display: flex; flex-direction: column; align-items: center; transition: background 0.2s; }
  .vt-canvas.vt-over { background: #e8f5f1; }
  .vt-canvas::-webkit-scrollbar { width: 4px; }
  .vt-canvas::-webkit-scrollbar-thumb { background: #c5d5d0; border-radius: 4px; }
  .vt-canvas-inner { width: 100%; max-width: 600px; }
  
  .vt-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 60px 20px; gap: 10px; }
  .vt-empty-icon { width: 52px; height: 52px; border-radius: 50%; background: #e8f5f1; display: flex; align-items: center; justify-content: center; font-size: 22px; margin-bottom: 4px; }
  .vt-empty-title { font-family: 'Barlow Condensed', sans-serif; font-size: 16px; font-weight: 700; color: #94a3b8; }
  .vt-empty-sub { font-size: 12px; color: #b0bec5; text-align: center; line-height: 1.5; }
  
  .vt-drop-hint { border: 2px dashed #b0ccc6; border-radius: 10px; padding: 12px 16px; text-align: center; color: #94a3b8; font-size: 11.5px; margin-top: 10px; display: none; }
  
  .vt-items { display: flex; flex-direction: column; border-radius: 10px; overflow: hidden; }
  .vt-item { background: white; border-bottom: 1px solid #f0f4f5; padding: 11px 14px; display: flex; align-items: flex-start; gap: 10px; position: relative; transition: background 0.12s; animation: vtSlide 0.22s ease; }
  .vt-item:first-child { border-radius: 10px 10px 0 0; }
  .vt-item:last-child { border-radius: 0 0 10px 10px; border-bottom: none; }
  .vt-item:only-child { border-radius: 10px; }
  .vt-item:hover { background: #fafcfc; }
  @keyframes vtSlide { from{opacity:0;transform:translateY(-6px)} to{opacity:1;transform:translateY(0)} }
  
  .vt-item-icon { width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
  .vt-item-body { flex: 1; min-width: 0; text-align: left; }
  .vt-item-type { font-size: 9px; font-weight: 700; letter-spacing: 0.6px; text-transform: uppercase; color: #94a3b8; margin-bottom: 1px; }
  .vt-item-name { font-size: 13px; font-weight: 700; color: #1a2e2c; }
  .vt-item-detail { font-size: 11px; color: #7fa098; margin-top: 1px; }
  
  .vt-item-actions { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); display: flex; gap: 4px; opacity: 0.18; transition: opacity 0.2s; }
  .vt-item:hover .vt-item-actions { opacity: 0.7; }
  .vt-item-btn { width: 20px; height: 20px; border-radius: 5px; background: #eef1f3; border: none; cursor: pointer; font-size: 10px; color: #64748b; display: flex; align-items: center; justify-content: center; transition: background 0.15s, color 0.15s; }
  .vt-item-btn.del:hover { background: #fde8ec; color: #e53e3e; }
  .vt-item-btn.edt:hover { background: #e8f5f1; color: #3db898; }
  
  .vt-toast { position: absolute; bottom: 14px; right: 14px; background: #0f2a3a; color: white; padding: 7px 14px; border-radius: 8px; font-size: 12px; font-weight: 500; opacity: 0; pointer-events: none; transition: all 0.25s; transform: translateY(6px); z-index: 10; }
  .vt-toast.show { opacity: 1; transform: translateY(0); }

  .vt-item.vt-dragging { opacity: 0.4; background: #f0f9f6; border: 1px dashed #3db898; }
  .vt-item.vt-drag-over { border-top: 2px solid #3db898; }

  /* ── PREMIUM HERO MOCKUP STYLES ── */
  .hero-mockup { max-width: 900px; margin: 0 auto; position: relative; }
  .mockup-frame {
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 40px 100px rgba(0,0,0,0.12), 0 10px 30px rgba(0,0,0,0.06);
    border: 1px solid rgba(0,0,0,0.05);
  }
  .mockup-bar {
    background: #0f172a;
    height: 38px;
    display: flex;
    align-items: center;
    padding: 0 16px;
    gap: 8px;
    flex-shrink: 0;
  }
  .mockup-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    opacity: 0.8;
  }
  .mockup-dot:nth-child(1) { background: #ff5f57; box-shadow: 0 0 6px rgba(255,95,87,0.4); }
  .mockup-dot:nth-child(2) { background: #febc2e; box-shadow: 0 0 6px rgba(254,188,46,0.3); }
  .mockup-dot:nth-child(3) { background: #28c840; box-shadow: 0 0 6px rgba(40,200,64,0.3); }
  
  .mockup-inner { position: relative; background: #f5f7fa; }

  @media (max-width: 768px) {
    .vt-body { flex-direction: column; overflow: visible; }
    .vt-sidebar { width: 100%; border-right: none; border-bottom: 1px solid #e2e8ef; height: auto; padding-bottom: 15px; }
    .vt-root { height: auto; min-height: 600px; }
    .vt-main-wrap { height: 450px; }
  }

  /* ── TRAVELER EXPERIENCE SECTION ── */
  .travel-exp { background: #0a1921; padding: 6rem 2.5rem; overflow: hidden; position: relative; }
  .travel-exp .section-label { color: #5dcfe0; }
  .travel-exp .section-title { color: #fff; }
  .travel-exp .section-desc { color: rgba(255,255,255,0.6); }

  .travel-grid { display: grid; grid-template-columns: 1fr 1.1fr; gap: 4rem; align-items: center; }
  
  .mockups-container { position: relative; height: 420px; width: 100%; display: flex; align-items: center; justify-content: center; }
  
  /* Laptop Mockup */
  .laptop-wrap { position: relative; width: 90%; max-width: 580px; z-index: 1; transform: translateX(-40px); }
  .l-screen { 
    background: #02070e; border-radius: 12px; padding: 4px; 
    box-shadow: 0 40px 100px rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.1); 
    aspect-ratio: 16/10; overflow: hidden;
  }
  .l-bar { height: 18px; display: flex; gap: 4px; padding: 0 4px 6px; }
  .l-dot { width: 6px; height: 6px; border-radius: 50%; background: rgba(255,255,255,0.2); }
  .l-content { background: white; height: 100%; border-radius: 4px; padding: 12px; display: flex; flex-direction: column; gap: 8px; }
  .l-header { height: 28px; background: #f1f5f9; border-radius: 4px; display: flex; align-items: center; padding: 0 8px; }
  .l-hero { height: 80px; background: linear-gradient(135deg, #1a7a8a, #8ab820); border-radius: 6px; }
  .l-rows { display: flex; gap: 8px; flex: 1; }
  .l-col { flex: 1; background: #f8fafc; border-radius: 4px; }

  /* Mobile Mockup Overlay */
  .phone-wrap { 
    position: absolute; right: 20px; bottom: -20px; width: 220px; z-index: 10;
    animation: floatPhone 4s ease-in-out infinite;
  }
  .p-frame { 
    background: #111; border-radius: 36px; padding: 3px; 
    border: 3px solid #333; box-shadow: 0 30px 60px rgba(0,0,0,0.6);
    aspect-ratio: 9/18.5; overflow: hidden; position: relative;
  }
  .p-screen { background: #f5f7fa; height: 100%; border-radius: 28px; overflow: hidden; display: flex; flex-direction: column; }
  .p-header { background: #1a7a8a; height: 110px; padding: 25px 15px 10px; color: white; display: flex; flex-direction: column; justify-content: flex-end; }
  .p-pill { background: rgba(255,255,255,0.2); padding: 4px 10px; border-radius: 100px; font-size: 8px; align-self: flex-end; }
  .p-title { font-size: 13px; font-weight: 700; margin-top: 8px; }
  .p-sub { font-size: 9px; opacity: 0.8; }
  .p-body { padding: 12px; flex: 1; display: flex; flex-direction: column; gap: 10px; }
  .p-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 6px; }
  .p-card { background: #1a2e2c; border-radius: 10px; height: 65px; color: white; display: flex; flex-direction: column; align-items: center; justify-content: center; font-size: 8px; gap: 2px; }
  .p-dot-full { width: 10px; height: 10px; border-radius: 50%; background: #e8f5f1; border: 2px solid #3db898; margin-bottom: 4px; }
  .p-activity { background: #1a2e2c; border-radius: 10px; padding: 12px; color: white; flex: 1; }
  .p-line { height: 3px; background: #3db898; width: 60%; border-radius: 2px; margin: 8px 0; }
  .p-txt { font-size: 10px; font-weight: 600; }

  @keyframes floatPhone {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-15px); }
  }

  @media (max-width: 900px) {
    .travel-grid { grid-template-columns: 1fr; text-align: center; gap: 0; }
    .travel-exp .section-desc { margin: 0 auto; }
    .mockups-container { height: 250px; margin-top: 1rem; }
    .phone-wrap { width: 112px; right: 0px; bottom: 0; }
    .laptop-wrap { width: 100%; transform: none; margin: 0 auto; }
  }
</style>

<script>
  (function() {
    const VT_DEFAULTS = [
      [
        { type:'Título',      icon:'T',  bg:'#f5f5f5', name:'🌆 Bienvenidos a Dubai',       detail:'Viaje Barcelona · Dubai · 7 días de experiencia única' },
        { type:'Vuelo',       icon:'✈️', bg:'#e8f0fe', name:'Vuelo BCN → DXB',              detail:'Emirates EK-0383 · 7h 20m · Salida 14:30' },
      ],
      [
        { type:'Alojamiento', icon:'🏨', bg:'#fff3e0', name:'Atlantis The Palm',             detail:'Palm Jumeirah · Check-in · Suite con vista al mar' },
        { type:'Comida',      icon:'🍽️', bg:'#fce4ec', name:'Almuerzo en Dubai Mall',       detail:'Food Court · Vista directa a las fuentes' },
      ],
      [
        { type:'Tour',        icon:'🗺️', bg:'#e3f2fd', name:'Safari en el Desierto',       detail:'Dune bashing + campamento beduino · Día completo' },
        { type:'Traslado',    icon:'🚗', bg:'#f3e5f5', name:'Traslado Hotel → DXB',         detail:'Limousine privada · Salida 06:00' },
        { type:'Vuelo',       icon:'✈️', bg:'#e8f0fe', name:'Vuelo DXB → BCN',              detail:'Emirates EK-0382 · 7h 15m · Salida 09:30' },
      ],
    ];

    const VT_EXTRA = {
      Vuelo:       [{ type:'Vuelo',       icon:'✈️', bg:'#e8f0fe', name:'Vuelo BCN → DXB',           detail:'Emirates EK-0383 · 7h 20m' }],
      Alojamiento: [{ type:'Alojamiento', icon:'🏨', bg:'#fff3e0', name:'Atlantis The Palm',           detail:'Palm Jumeirah · Desayuno incluido' }],
      Traslado:    [{ type:'Traslado',    icon:'🚗', bg:'#f3e5f5', name:'Traslado privado Dubai',      detail:'Limousine · 30 min' }],
      Actividad:   [{ type:'Actividad',   icon:'🎯', bg:'#e8f5e9', name:'Ski Dubai',                   detail:'Pista de esquí interior · 2h' },
                    { type:'Actividad',   icon:'🎯', bg:'#e8f5e9', name:'Dubai Frame',                 detail:'Vista 360° del viejo y nuevo Dubai' }],
      Comida:      [{ type:'Comida',      icon:'🍽️', bg:'#fce4ec', name:'Breakfast Burj Al Arab',     detail:'Brunch con vista al mar · 09:00' }],
      Tour:        [{ type:'Tour',        icon:'🗺️', bg:'#e3f2fd', name:'Tour Abu Dhabi',              detail:'Mezquita Sheikh Zayed · Día completo' }],
      Texto:       [{ type:'Texto',       icon:'Aa', bg:'#f5f5f5', name:'Nota del agente',             detail:'Recuerda llevar protector solar +50 y ropa ligera' }],
      Título:      [{ type:'Título',      icon:'T',  bg:'#f5f5f5', name:'🌆 Bienvenidos a Dubai',      detail:'Encabezado de sección' }],
      Separador:   [{ type:'Separador',   icon:'—✦—',bg:'#f5f5f5', name:'— ✦ —',                      detail:'División de sección' }],
      Caja:        [{ type:'Caja',        icon:'🎨', bg:'#fce4ec', name:'Información importante',      detail:'Código de vestimenta conservador en zocos y mezquitas' }],
      Imagen:      [{ type:'Imagen',      icon:'🖼️', bg:'#fff8e1', name:'Atardecer en el desierto',   detail:'https://picsum.photos/600/300?desert' }],
      Gif:         [{ type:'Gif',         icon:'🎬', bg:'#f3e5f5', name:'Luces de Dubai',            detail:'https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExNHJocXQ3Z3R4Z3R4Z3R4Z3R4Z3R4Z3R4Z3R4Z3R4Z3R4JmVwPXYxX2ludGVybmFsX2dpZl9ieV9pZCZjdD1n/3o7TKMGpxx6xYf/giphy.gif' }],
    };

    let uid = 1000;
    const vtDays = VT_DEFAULTS.map(d => d.map(x => ({...x, id: uid++})));
    let vtCurrent = 0, vtDragType = null, vtReorderId = null, vtTargetId = null, vtExtraIdx = {}, vtTimer = null;

    window.vtSwitch = (day) => {
      vtCurrent = day;
      document.querySelectorAll('.vt-tab').forEach((t, i) => t.classList.toggle('active', i === day));
      vtRender();
    };

    window.vtHandleDragOver = (e) => {
        e.preventDefault();
        document.getElementById('vtCanvas').classList.add('vt-over');
    };

    window.vtHandleDragLeave = (e) => {
        document.getElementById('vtCanvas').classList.remove('vt-over');
    };

    window.vtDrop = (e) => {
      e.preventDefault();
      document.getElementById('vtCanvas').classList.remove('vt-over');
      
      if (vtReorderId !== null) {
        const fromIdx = vtDays[vtCurrent].findIndex(x => x.id === vtReorderId);
        const toIdx = vtDays[vtCurrent].findIndex(x => x.id === vtTargetId);
        if (fromIdx !== -1 && toIdx !== -1 && fromIdx !== toIdx) {
          const row = vtDays[vtCurrent].splice(fromIdx, 1)[0];
          vtDays[vtCurrent].splice(toIdx, 0, row);
          vtRender();
        }
        vtReorderId = null; vtTargetId = null;
        return;
      }

      if (!vtDragType) return;
      const pool = VT_EXTRA[vtDragType] || [];
      const idx  = vtExtraIdx[vtDragType] || 0;
      const tpl  = pool[idx % pool.length] || { type:vtDragType, icon:'📌', bg:'#f5f5f5', name:vtDragType, detail:'' };
      vtExtraIdx[vtDragType] = idx + 1;
      vtDays[vtCurrent].push({...tpl, id: uid++});
      vtRender();
      vtToast('✓ ' + tpl.name + ' añadido');
      vtDragType = null;
    };

    window.vtRemove = (id) => {
      vtDays[vtCurrent] = vtDays[vtCurrent].filter(x => x.id !== id);
      vtRender();
    };

    function vtRender() {
      const list  = document.getElementById('vtItems');
      const empty = document.getElementById('vtEmpty');
      const hint  = document.getElementById('vtDropHint');
      const count = document.getElementById('vtCount');
      if (!list) return;
      const items = vtDays[vtCurrent];
      
      list.innerHTML = '';
      
      if (!items.length) {
        empty.style.display = 'flex'; hint.style.display = 'none';
      } else {
        empty.style.display = 'none'; hint.style.display = 'block';
        items.forEach(item => {
          const el = document.createElement('div');
          el.className = 'vt-item';
          el.draggable = true;

          el.addEventListener('dragstart', (e) => {
            vtReorderId = item.id;
            e.dataTransfer.effectAllowed = 'move';
            setTimeout(() => el.classList.add('vt-dragging'), 0);
          });

          el.addEventListener('dragover', (e) => {
            if (vtReorderId === null) return;
            e.preventDefault();
            vtTargetId = item.id;
            el.classList.add('vt-drag-over');
          });

          el.addEventListener('dragleave', () => {
            el.classList.remove('vt-drag-over');
          });

          el.addEventListener('dragend', () => {
            el.classList.remove('vt-dragging');
            document.querySelectorAll('.vt-item').forEach(i => i.classList.remove('vt-drag-over'));
            vtReorderId = null;
          });

          const isVisual = item.type === 'Imagen' || item.type === 'Gif';
          el.innerHTML = `
            <div class="vt-item-icon" style="background:${item.bg}">${item.icon}</div>
            <div class="vt-item-body">
              <div class="vt-item-type">${item.type}</div>
              <div class="vt-item-name">${item.name}</div>
              ${isVisual ? `<img src="${item.detail}" style="width:100%; border-radius:6px; margin-top:5px; height:100px; object-fit:cover; display:block;">` : `<div class="vt-item-detail">${item.detail}</div>`}
            </div>
            <div class="vt-item-actions">
              <button class="vt-item-btn edt" title="Editar">✏</button>
              <button class="vt-item-btn del" title="Eliminar" onclick="vtRemove(${item.id})">✕</button>
            </div>`;
          list.appendChild(el);
        });
      }
      const n = items.length;
      count.textContent = n + ' elemento' + (n !== 1 ? 's' : '');
    }

    function vtToast(msg) {
      const el = document.getElementById('vtToastEl');
      if (!el) return;
      el.textContent = msg; el.classList.add('show');
      clearTimeout(vtTimer);
      vtTimer = setTimeout(() => el.classList.remove('show'), 2100);
    }

    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.vt-card').forEach(c => {
        c.addEventListener('dragstart', () => { vtDragType = c.dataset.type; c.style.opacity = '0.5'; });
        c.addEventListener('dragend',   () => { c.style.opacity = '1'; });
      });
      vtRender();
    });
    
    // Fallback if DOMContentLoaded already fired
    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        vtRender();
        document.querySelectorAll('.vt-card').forEach(c => {
          c.addEventListener('dragstart', () => { vtDragType = c.dataset.type; c.style.opacity = '0.5'; });
          c.addEventListener('dragend', () => { c.style.opacity = '1'; });
        });
    }
  })();
</script>



<!-- FOR WHO -->
<section class="forwho" id="para-quien">
  <div class="container">
    <div class="reveal" style="text-align: center;">
      <div class="section-label" style="color: var(--teal);">Segmentos</div>
      <h2 class="section-title" style="color: var(--navy);">Diseñado para líderes del turismo</h2>
    </div>
    
    <div class="audience-dual reveal">
      <!-- Agencies -->
      <div class="audience-block agencies">
        <div class="block-content">
          <div class="block-tag">Equipos</div>
          <h3 class="block-title">Agencias<br>de Viajes</h3>
          <p class="block-desc">Escala tu producción de itinerarios sin aumentar la carga de trabajo de tu equipo.</p>
        </div>
      </div>
      
      <!-- Consultants -->
      <div class="audience-block consultants">
        <div class="block-content">
          <div class="block-tag">Personas</div>
          <h3 class="block-title">Diseñadores<br>Freelance</h3>
          <p class="block-desc">Eleva tu marca personal con propuestas visuales que compiten con las grandes operadoras.</p>
        </div>
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
      <h2>Tu próxima aventura,<br>lista en minutos</h2>
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
    <p class="section-desc" style="margin:0 auto 2.5rem;">Más que una plataforma, somos el aliado en tus viajes. <br>Escríbenos.</p>
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
