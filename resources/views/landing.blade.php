<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Viantryp | Home</title>
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800;900&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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


  /* ── QUOTE ── */
  .quote-section { background: var(--navy); text-align: center; }
  .big-quote {
    font-family: 'Syne', sans-serif;
    font-size: clamp(1.5rem, 3.5vw, 2.4rem); font-weight: 700; line-height: 1.35;
    max-width: 720px; margin: 0 auto 1.5rem; color: var(--white);
  }
  .big-quote em { color: #5dcfe0; font-style: normal; }
  .quote-sub { font-size: 0.88rem; color: rgba(255,255,255,0.4); }

  /* ── PRICING SECTION ── */
  .pricing { 
    background-color: var(--white);
    background-image: radial-gradient(circle at 10% 20%, rgba(26, 122, 138, 0.03) 0%, transparent 40%), radial-gradient(circle at 90% 80%, rgba(138, 184, 32, 0.03) 0%, transparent 40%);
    padding: 5rem 2rem;
  }
  .pricing-grid {
    display: grid; 
    grid-template-columns: repeat(4, 1fr);
    grid-auto-rows: 1fr;
    gap: 1.5rem; 
    max-width: 1240px; 
    width: 100%;
    margin: 3.5rem auto 0;
    align-items: stretch;
  }
  .plan { 
    background: rgba(255, 255, 255, 0.8); 
    backdrop-filter: blur(10px);
    border: 1px solid rgba(226, 232, 226, 0.5); 
    border-radius: 24px; 
    padding: 3rem 2rem;
    display: flex;
    flex-direction: column;
    height: 100%;
    min-width: 0;
    transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1);
    box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    position: relative;
  }
  .plan:hover {
    transform: translateY(-12px);
    background: var(--white);
    box-shadow: 0 30px 60px rgba(0,0,0,0.08);
    border-color: rgba(11, 142, 163, 0.2);
  }
  .plan.featured {
    background: #0f172a;
    border-color: #1e293b;
    position: relative;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
  }
  .plan.featured:hover {
    transform: translateY(-15px);
    box-shadow: 0 40px 80px rgba(0,0,0,0.3);
    border-color: var(--teal);
  }
  .plan-badge {
    position: absolute; top: -14px; left: 50%; transform: translateX(-50%);
    background: linear-gradient(90deg, var(--lime), var(--lime-bright));
    color: var(--white);
    font-size: 0.7rem; font-weight: 800;
    padding: 0.4rem 1.2rem; border-radius: 100px;
    letter-spacing: 0.05em; white-space: nowrap; text-transform: uppercase;
    box-shadow: 0 4px 12px rgba(138,184,32,0.3);
  }
  .plan-name { font-size: 0.75rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: var(--text-muted); margin-bottom: 1.2rem; }
  .plan.featured .plan-name { color: #5dcfe0; }
  .plan-price { 
    font-family: 'Syne', sans-serif; 
    font-size: 26px; 
    font-weight: 800; 
    color: var(--navy); 
    margin-bottom: 0.5rem; 
    line-height: 1; 
    display: flex;
    align-items: baseline;
    gap: 1px;
  }
  .plan.featured .plan-price { color: var(--white); }
  .plan-price .period { font-size: 1rem; font-weight: 400; color: var(--text-muted); }
  .plan.featured .plan-price .period { color: rgba(255,255,255,0.4); }
  .price-note { font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.5rem; transition: opacity 0.2s; }
  .plan.featured .price-note { color: rgba(255,255,255,0.4); }
  .plan-savings { 
    font-size: 0.78rem; 
    font-weight: 700; 
    color: var(--lime); 
    background: var(--lime-bg); 
    padding: 0.2rem 0.6rem; 
    border-radius: 6px; 
    display: inline-block;
    margin-bottom: 2rem;
    transition: all 0.3s ease;
  }
  .plan.featured .plan-savings { background: rgba(138,184,32,0.15); }
  .plan-sub { font-size: 0.88rem; color: var(--text-soft); margin-bottom: 1.2rem; min-height: 2.5rem; }
  .plan.featured .plan-sub { color: rgba(255,255,255,0.6); }
  .plan-features { list-style: none; display: flex; flex-direction: column; gap: 0.9rem; margin-bottom: 2.5rem; flex: 1; }
  .plan-features li { font-size: 0.9rem; color: var(--text-soft); display: flex; gap: 0.75rem; line-height: 1.4; }
  .plan.featured .plan-features li { color: rgba(255,255,255,0.7); }
  .plan-features i { color: var(--teal); font-size: 1rem; margin-top: 0.15rem; }
  .plan.featured .plan-features i { color: var(--lime); }
  .plan-btn {
    display: block; width: 100%; text-align: center; padding: 1.1rem; border-radius: 12px;
    font-size: 0.95rem; font-weight: 700; transition: all 0.3s;
    border: 1px solid var(--mid-gray); color: var(--navy);
  }
  .plan-btn:hover { background: #f8fafc; border-color: var(--teal); color: var(--teal); transform: scale(1.02); }
  .plan-btn.primary { background: linear-gradient(135deg, var(--teal), #0a7a8a); color: var(--white); border: none; }
  .plan-btn.primary:hover { transform: scale(1.02); box-shadow: 0 10px 25px rgba(11, 142, 163, 0.3); }

  @media (max-width: 1100px) {
    .pricing-grid { grid-template-columns: repeat(2, 1fr); padding: 0 1rem; }
  }
  @media (max-width: 768px) {
    .pricing { padding: 6rem 1rem; }
    .pricing-grid { grid-template-columns: 1fr; }
  }
  /* ── SOLUTIONS SECTION ── */
  .solutions { padding: 5rem 0; background: var(--white); overflow: hidden; }
  .solutions-header { text-align: center; margin-bottom: 3rem; }
  .solutions-title { font-size: 2.8rem; font-weight: 800; color: var(--navy); margin-bottom: 1rem; letter-spacing: -0.02em; }
  .solutions-title span { color: var(--teal); }
  .solutions-desc { font-size: 1.15rem; color: var(--text-soft); max-width: 700px; margin: 0 auto; line-height: 1.6; }

  .solutions-tabs {
    display: flex;
    justify-content: center;
    gap: 0.8rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    padding: 0 1rem;
  }
  .sol-tab {
    padding: 0.6rem 1.4rem;
    border-radius: 100px;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-soft);
    background: transparent;
    border: 1px solid var(--mid-gray);
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    white-space: nowrap;
  }
  .sol-tab:hover { border-color: var(--teal); color: var(--teal); }
  .sol-tab.active {
    background: var(--teal);
    border-color: var(--teal);
    color: var(--white);
    box-shadow: 0 10px 20px rgba(11, 142, 163, 0.2);
  }

  .solutions-card {
    background: #f0f7f9;
    border: 1px solid rgba(11, 142, 163, 0.1);
    border-radius: 40px;
    padding: 4rem;
    display: grid;
    grid-template-columns: 1.2fr 0.8fr;
    gap: 3rem;
    max-width: 1200px;
    margin: 0 auto;
    position: relative;
    box-shadow: 0 30px 60px rgba(0,0,0,0.03);
  }
  .sol-content-left { display: flex; flex-direction: column; }
  .sol-tagline { font-size: 2.2rem; font-weight: 800; color: var(--navy); margin-bottom: 1.2rem; }
  .sol-tagline span { color: var(--text-muted); font-weight: 400; }
  .sol-text { font-size: 1.1rem; color: var(--text-soft); margin-bottom: 3rem; line-height: 1.6; }
  
  .sol-benefits { list-style: none; margin-bottom: 3rem; }
  .sol-benefits li { 
    display: flex; 
    align-items: flex-start; 
    gap: 0.8rem; 
    font-size: 1rem; 
    color: var(--navy); 
    font-weight: 500;
    margin-bottom: 1rem;
  }
  .sol-benefits li i { color: var(--teal); font-size: 1.2rem; margin-top: 0.1rem; }


  .sol-btn {
    align-self: flex-start;
    padding: 1rem 2rem;
    background: var(--navy);
    color: var(--white);
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.95rem;
    display: inline-flex;
    align-items: center;
    gap: 0.8rem;
    transition: all 0.3s;
    box-shadow: 0 10px 20px rgba(15, 23, 42, 0.1);
  }
  .sol-btn:hover { background: #1e293b; transform: translateY(-3px); box-shadow: 0 15px 30px rgba(15, 23, 42, 0.2); }

  .sol-features-right { display: flex; flex-direction: column; gap: 1.5rem; }
  .sol-feature-item {
    background: var(--white);
    padding: 1.2rem 1.5rem;
    border-radius: 20px;
    display: flex;
    align-items: center;
    gap: 1.2rem;
    box-shadow: 0 10px 25px rgba(0,0,0,0.02);
    transition: all 0.3s;
    border: 1px solid rgba(11, 142, 163, 0.05);
  }
  .sol-feature-item:hover { transform: scale(1.02); box-shadow: 0 15px 35px rgba(0,0,0,0.05); }
  .sol-feature-icon {
    width: 32px; height: 32px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem;
  }
  .sol-feature-info { flex: 1; }
  .sol-feature-name { font-size: 0.95rem; font-weight: 700; color: var(--navy); margin-bottom: 0.2rem; }
  .sol-feature-name span { color: var(--teal); }
  .sol-feature-desc { font-size: 0.85rem; color: var(--text-soft); line-height: 1.4; }

  @media (max-width: 1024px) {
    .solutions-card { grid-template-columns: 1fr; padding: 3rem 2rem; gap: 3rem; }
    .sol-tagline { font-size: 1.8rem; }
    .solutions-title { font-size: 2.2rem; }
  }
  @media (max-width: 768px) {
    .solutions-tabs { justify-content: flex-start; overflow-x: auto; padding-bottom: 1rem; }
    .sol-tab { flex-shrink: 0; }
  }

  /* ── TOGGLE ── */
  .pricing-toggle-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 1rem;
    position: relative;
    z-index: 10;
  }
  .toggle-label {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-soft);
    transition: color 0.3s;
  }
  .toggle-label.active {
    color: var(--navy);
  }
  .toggle-switch {
    position: relative;
    width: 60px;
    height: 32px;
    background: var(--mid-gray);
    border-radius: 100px;
    cursor: pointer;
    transition: background 0.3s;
  }
  .toggle-switch::after {
    content: '';
    position: absolute;
    top: 4px;
    left: 4px;
    width: 24px;
    height: 24px;
    background: white;
    border-radius: 50%;
    transition: transform 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  }
  .toggle-switch.annual {
    background: var(--teal);
  }
  .toggle-switch.annual::after {
    transform: translateX(28px);
  }
  .annual-discount {
    background: var(--lime-bg);
    color: var(--lime);
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.25rem 0.75rem;
    border-radius: 100px;
    margin-left: 0.5rem;
  }
  .plan:hover {
    transform: translateY(-12px);
    background: var(--white);
    border-color: var(--teal);
    box-shadow: 0 30px 60px rgba(26,122,138,0.12);
  }
  .plan.featured {
    background: #0f172a;
    border-color: #1e293b;
    position: relative;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
  }
  .plan.featured:hover {
    transform: translateY(-15px);
    background: #0d1526;
    border-color: #5dcfe0;
    box-shadow: 0 40px 80px rgba(93,207,224,0.25);
  }
  .plan-badge {
    position: absolute; top: -14px; left: 50%; transform: translateX(-50%);
    background: linear-gradient(90deg, var(--lime), var(--lime-bright));
    color: var(--white);
    font-size: 0.7rem; font-weight: 800;
    padding: 0.4rem 1.2rem; border-radius: 100px;
    letter-spacing: 0.05em; white-space: nowrap; text-transform: uppercase;
    box-shadow: 0 4px 12px rgba(138,184,32,0.3);
  }
  .plan-name { font-size: 0.75rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: var(--text-muted); margin-bottom: 1.2rem; }
  .plan.featured .plan-name { color: #5dcfe0; }
  .plan-price { 
    font-family: 'Syne', sans-serif; 
    font-size: 26px; 
    font-weight: 800; 
    color: var(--navy); 
    margin-bottom: 0.5rem; 
    line-height: 1; 
    display: flex;
    align-items: baseline;
    gap: 1px;
  }
  .plan.featured .plan-price { color: var(--white); }
  .plan-price .period { font-size: 1rem; font-weight: 400; color: var(--text-muted); }
  .plan.featured .plan-price .period { color: rgba(255,255,255,0.4); }
  .price-note { font-size: 10px; color: var(--text-muted); margin-bottom: 0.5rem; transition: opacity 0.2s; }
  .plan.featured .price-note { color: rgba(255,255,255,0.4); }
  .plan-desc-special { font-size: 13px; font-weight: 600; color: var(--text-soft); margin-bottom: 0.5rem; display: block; }
  .plan.featured .plan-desc-special { color: rgba(255,255,255,0.8); }
  .plan-savings { 
    font-size: 0.78rem; 
    font-weight: 700; 
    color: var(--lime); 
    background: var(--lime-bg); 
    padding: 0.2rem 0.6rem; 
    border-radius: 6px; 
    display: inline-block;
    margin-bottom: 2rem;
    transition: all 0.3s ease;
  }
  .plan.featured .plan-savings { background: rgba(138,184,32,0.15); }
  .plan-sub { font-size: 0.88rem; color: var(--text-soft); margin-bottom: 1.2rem; min-height: 2.5rem; }
  .plan.featured .plan-sub { color: rgba(255,255,255,0.6); }
  .plan-features { list-style: none; display: flex; flex-direction: column; gap: 0.9rem; margin-bottom: 2.5rem; flex: 1; }
  .plan-features li { font-size: 0.9rem; color: var(--text-soft); display: flex; gap: 0.75rem; line-height: 1.4; }
  .plan.featured .plan-features li { color: rgba(255,255,255,0.8); }
  .plan-features li::before { content: '✓'; color: var(--teal); font-weight: 900; flex-shrink: 0; }
  .plan.featured .plan-features li::before { color: var(--lime); }
  .plan-btn {
    display: block; text-align: center;
    border: 1.5px solid var(--mid-gray); border-radius: 100px; padding: 1rem;
    font-size: 0.95rem; color: var(--navy); font-weight: 600;
    text-decoration: none; transition: all 0.3s ease;
  }
  .plan-btn:hover { background: var(--navy); color: var(--white); border-color: var(--navy); }
  .plan-btn.primary {
    background: var(--teal); color: var(--white);
    border-color: var(--teal); box-shadow: 0 8px 20px rgba(26,122,138,0.2);
  }
  .plan-btn.primary:hover { background: var(--teal-dark); transform: scale(1.02); }

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

    @media (max-width: 1100px) {
      .pricing-grid { grid-template-columns: repeat(2, 1fr); padding: 0 1rem; }
    }
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
      .pricing-grid { grid-template-columns: 1fr; }
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
          <div class="vt-topbar-actions">
            <button class="vt-preview-btn" id="vtPreviewBtn">
              <i class="fas fa-eye"></i> <span>Vista previa</span>
            </button>
          </div>
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

          <!-- TRAVELER SIDEBAR (VISIBLE ONLY IN PREVIEW) -->
          <div class="vt-preview-sidebar">
            <div class="vt-preview-sidebar-label">ITINERARIO</div>
            <div id="vtPreviewDaysList" class="vt-preview-days-list"></div>
          </div>

          <!-- MAIN CANVAS AREA -->
          <div class="vt-main-wrap">
            <!-- PREVIEW HERO -->
            <div class="vt-preview-hero">
              <div class="vt-preview-hero-inner">
                <div class="vt-preview-hero-title">Escapada a Dubai <span class="vt-badge">RESERVADO</span></div>
                <div class="vt-preview-hero-stats">
                  <div class="stat"><span>FECHAS</span><strong>14 may — 21 may</strong></div>
                  <div class="stat"><span>VIAJEROS</span><strong>2 personas</strong></div>
                  <div class="stat"><span>TOTAL</span><strong>USD $2,450.00</strong></div>
                </div>
              </div>
            </div>

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
                  <h3 class="vt-preview-day-heading" id="vtPreviewDayHeader">Día 1: Llegada y Bienvenida</h3>
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
<section class="how" id="como-funciona" style="background: #f8fafc; padding: 6rem 2.5rem; position: relative;">
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

<!-- SOLUTIONS -->
<section class="solutions" id="soluciones">
  <div class="container">
    <div class="solutions-header reveal">
      <div class="section-label">Soluciones</div>
      <h2 class="solutions-title"><span>Viantryp:</span> El lienzo para tus viajes inolvidables</h2>
      <p class="solutions-desc">Tu centro de control para itinerarios perfectos, atractivos y digitales.</p>
    </div>

    <div class="solutions-tabs reveal">
      <button class="sol-tab active" data-target="viajeros">Viajeros</button>
      <button class="sol-tab" data-target="grupos">Grupos y familias</button>
      <button class="sol-tab" data-target="agencias">Agencias de viajes</button>
      <button class="sol-tab" data-target="operadores">Operadores turísticos</button>
      <button class="sol-tab" data-target="empresas">Empresas y eventos</button>
    </div>

    <div class="solutions-card reveal">
      <div class="sol-content-left" id="sol-left">
        <h3 class="sol-tagline">Organiza tu <span>Aventura.</span></h3>
        <p class="sol-text">Crea rutas perfectas en minutos y lleva todo tu viaje en la palma de tu mano, siempre actualizado.</p>
        <ul class="sol-benefits">
          <li><i class="fas fa-check"></i> Planifica sin estrés manteniendo todo bajo control en un solo lienzo digital.</li>
          <li><i class="fas fa-check"></i> Disfruta de un diseño que evoluciona junto a tus ideas.</li>
          <li><i class="fas fa-check"></i> Actualiza tu viaje sin rehacer documentos.</li>
        </ul>


        <a href="#precios" class="sol-btn">Explorar soluciones →</a>
      </div>

      <div class="sol-features-right" id="sol-right">
        <div class="sol-feature-item">
          <div class="sol-feature-icon" style="color: #0b8ea3;">
            <i class="fas fa-pencil-ruler"></i>
          </div>
          <div class="sol-feature-info">
            <div class="sol-feature-name">Editor visual <span>Drag & Drop</span></div>
            <div class="sol-feature-desc">Arrastra destinos y fotos para diseñar tu ruta ideal en segundos. Es tan fácil como jugar, pero con resultados profesionales.</div>
          </div>
        </div>
        <div class="sol-feature-item">
          <div class="sol-feature-icon" style="color: #22c55e;">
            <i class="fas fa-link"></i>
          </div>
          <div class="sol-feature-info">
            <div class="sol-feature-name">Enlace interactivo <span>personal</span></div>
            <div class="sol-feature-desc">Lleva todo tu plan en un solo link. Si cambias de opinión sobre un lugar, actualízalo y ten tu ruta siempre al día en tu móvil.</div>
          </div>
        </div>
        <div class="sol-feature-item">
          <div class="sol-feature-icon" style="color: #0f172a;">
            <i class="fas fa-file-invoice"></i>
          </div>
          <div class="sol-feature-info">
            <div class="sol-feature-name">Toda tu documentación <span>a mano</span></div>
            <div class="sol-feature-desc">Guarda tus reservas y mapas directamente en el día que corresponden. Olvida buscar entre cientos de correos y capturas de pantalla.</div>
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
  .vt-topbar { 
    background: #0f2a3a; 
    display: flex; 
    align-items: center; 
    justify-content: space-between;
    height: 48px; 
    padding: 0 18px; 
    flex-shrink: 0; 
  }
  .vt-topbar-actions { display: flex; align-items: center; gap: 10px; }
  .vt-preview-btn {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    color: white;
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
  }
  .vt-preview-btn:hover { background: rgba(255,255,255,0.2); border-color: rgba(255,255,255,0.4); }
  .vt-preview-btn i { font-size: 13px; }

  /* PREVIEW MODE STATES */
  .vt-root.is-preview .vt-sidebar { 
    width: 0; opacity: 0; padding: 0; border: none; overflow: hidden; pointer-events: none;
  }
  .vt-root.is-preview .vt-toolbar,
  .vt-root.is-preview .vt-drop-hint,
  .vt-root.is-preview .vt-card-drag,
  .vt-root.is-preview .vt-item-delete { 
    display: none !important; 
  }
  .vt-root.is-preview .vt-main-wrap { 
    padding: 0; max-width: 800px; margin: 0 auto; background: transparent; 
  }
  .vt-root.is-preview .vt-canvas { 
    background: transparent; box-shadow: none; border: none; padding: 20px 10px; 
  }
  .vt-root.is-preview .vt-item { cursor: default; transform: none !important; }
  .vt-root.is-preview .vt-items { padding-bottom: 100px; }
  
  .vt-body { display: flex; flex: 1; overflow: hidden; }
  .vt-sidebar { width: 184px; background: white; border-right: 1px solid #e2e8ef; overflow-y: auto; flex-shrink: 0; padding-bottom: 12px; }
  .vt-root.is-preview .vt-preview-sidebar { display: flex; flex-direction: column; width: 180px; background: #fff; border-right: 1px solid #eef2f6; flex-shrink: 0; padding: 20px 0; }
  .vt-preview-sidebar { display: none; }
  .vt-preview-sidebar-label { font-size: 10px; font-weight: 800; color: #94a3b8; letter-spacing: 1px; padding: 0 20px 15px; }
  .vt-preview-day-item { padding: 12px 20px; font-size: 13px; color: #64748b; cursor: pointer; transition: all 0.2s; border-left: 3px solid transparent; }
  .vt-preview-day-item:hover { background: #f8fafc; color: var(--navy); }
  .vt-preview-day-item.active { background: #f0f9ff; color: #0284c7; border-left-color: #0284c7; font-weight: 700; }
  .vt-preview-day-item span { font-size: 11px; display: block; opacity: 0.7; margin-top: 2px; }

  /* HERO AREA FOR PREVIEW */
  .vt-preview-hero { display: none; }
  .vt-root.is-preview .vt-preview-hero { 
    display: block; width: 100%; padding: 20px; box-sizing: border-box; 
  }
  .vt-preview-hero-inner {
    background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1512453979798-5ea266f8880c?q=80&w=2070&auto=format&fit=crop');
    background-size: cover; background-position: center; border-radius: 16px; padding: 15px; color: white;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
  }
  .vt-preview-hero-title { font-size: 24px; font-weight: 800; display: flex; align-items: center; gap: 15px; margin-bottom: 30px; text-shadow: 0 2px 4px rgba(0,0,0,0.3); }
  .vt-badge { background: #dcfce7; color: #166534; font-size: 9px; padding: 4px 12px; border-radius: 100px; font-weight: 700; }
  
  .vt-preview-hero-stats { 
    display: grid; grid-template-columns: 1fr 1fr 1fr; background: rgba(255,255,255,0.95); 
    border-radius: 12px; padding: 15px 0; color: #1e293b;
  }
  .vt-preview-hero-stats .stat { text-align: center; border-right: 1px solid #e2e8f0; }
  .vt-preview-hero-stats .stat:last-child { border-right: none; }
  .vt-preview-hero-stats .stat span { font-size: 8px; font-weight: 700; color: #94a3b8; display: block; margin-bottom: 4px; }
  .vt-preview-hero-stats .stat strong { font-size: 12px; color: var(--navy); }

  .vt-preview-day-heading { display: none; }
  .vt-root.is-preview .vt-preview-day-heading { 
    display: block; font-family: 'Barlow Condensed', sans-serif; font-size: 22px; font-weight: 800; color: var(--navy); 
    margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1.5px solid #edf2f7;
  }

  .vt-root.is-preview .vt-topbar { height: 54px; }
  .vt-root.is-preview .vt-main-wrap { background: #f8fafc; overflow-y: auto; flex: 1; }
  .vt-root.is-preview .vt-main { background: transparent; overflow-y: visible; flex: initial; }
  .vt-root.is-preview .vt-canvas { padding: 10px 0; }
  .vt-root.is-preview .vt-canvas-inner { max-width: 720px; margin: 0 auto; }
  .vt-items { display: flex; flex-direction: column; gap: 12px; }
  .vt-root.is-preview .vt-items { gap: 8px; padding-bottom: 80px; }

  /* RICH PREVIEW CARDS - MOCKUP STYLE */
  .vt-root.is-preview .vt-item {
    background: white; border: none; border-radius: 12px; padding: 0; overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.04); display: flex; flex-direction: column; 
    border-bottom: 2px solid #eef2f6; transition: transform 0.2s;
  }
  .vt-root.is-preview .vt-item:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.06); }

  /* FLIGHT CARD RICH */
  .vt-item-flight-rich { padding: 20px; border-left: 4px solid #0284c7; }
  .flight-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
  .flight-header span { font-size: 11px; font-weight: 700; color: #64748b; }
  .flight-badge { background: #0f172a; color: white; padding: 3px 8px; border-radius: 4px; font-size: 8px; }
  
  .flight-main { display: flex; align-items: center; justify-content: space-between; margin: 15px 0; }
  .flight-main .time { text-align: left; }
  .flight-main .time strong { font-size: 22px; color: var(--navy); display: block; }
  .flight-main .time span { font-size: 10px; color: #94a3b8; font-weight: 600; }
  .plane-icon { font-size: 18px; color: #cbd5e1; transform: rotate(90deg); }
  
  .flight-footer { border-top: 1.5px solid #f1f5f9; padding-top: 15px; font-size: 10px; color: #64748b; display: flex; gap: 8px; align-items: center; }

  /* HOTEL CARD RICH */
  .vt-item-hotel-rich { padding: 20px; border-left: 4px solid #f59e0b; }
  .hotel-title { font-size: 14px; font-weight: 800; color: var(--navy); margin-bottom: 5px; }
  .hotel-stars { color: #f59e0b; font-size: 8px; margin-bottom: 8px; letter-spacing: 2px; }
  .hotel-detail { font-size: 11px; color: #64748b; margin-bottom: 12px; line-height: 1.4; }
  .hotel-location { font-size: 9px; font-weight: 600; color: #94a3b8; display: flex; align-items: center; gap: 6px; }

  /* GENERIC RICH */
  .vt-item-generic-rich { padding: 20px; border-left: 4px solid #3db898; }
  .generic-title { font-size: 13px; font-weight: 700; color: var(--navy); margin-bottom: 8px; display: flex; align-items: center; gap: 10px; }
  .generic-detail { font-size: 11px; color: #64748b; line-height: 1.4; }

  /* Transitions */
  .vt-sidebar, .vt-preview-sidebar, .vt-main-wrap, .vt-canvas {
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
  }
  
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
        { type:'Vuelo',       icon:'✈️', bg:'#e8f0fe', name:'Vuelo BCN → DXB',              detail:'Emirates EK-0383 · 14:30h', airline:'Emirates Airlines', flight:'EK 0383', times:'14:30 - 21:55' },
        { type:'Alojamiento', icon:'🏨', bg:'#fff3e0', name:'Atlantis The Palm',             detail:'Check-in 15:00 · Suite Lujo', stars:5, location:'Palm Jumeirah' },
      ],
      [
        { type:'Actividad',   icon:'🎯', bg:'#e8f5e9', name:'Safari en el Desierto',       detail:'Dune bashing + Cena · 16:00h', duration:'6 horas' },
        { type:'Comida',      icon:'🍽️', bg:'#fce4ec', name:'Almuerzo Dubai Mall',       detail:'Reserva confirmada · 13:30h' },
      ],
      [
        { type:'Tour',        icon:'🗺️', bg:'#e3f2fd', name:'Burj Khalifa Top',             detail:'Piso 148 · Entrada 10:00h', ticket:'BK-99231' },
        { type:'Traslado',    icon:'🚗', bg:'#f3e5f5', name:'Traslado Hotel → DXB',         detail:'Privado · Salida 07:00h' },
      ],
    ];

    const VT_EXTRA = {
      Vuelo:       [{ type:'Vuelo',       icon:'✈️', bg:'#e8f0fe', name:'Vuelo BCN → DXB',           detail:'Emirates EK-0383', airline:'Emirates', flight:'EK 0383', times:'14:30 - 21:55' }],
      Alojamiento: [{ type:'Alojamiento', icon:'🏨', bg:'#fff3e0', name:'Atlantis The Palm',           detail:'Palm Jumeirah', location:'Dubai' }],
      Traslado:    [{ type:'Traslado',    icon:'🚗', bg:'#f3e5f5', name:'Traslado privado',           detail:'Limousine · 30 min' }],
      Actividad:   [{ type:'Actividad',   icon:'🎯', bg:'#e8f5e9', name:'Safari Desierto',           detail:'Dune bashing', duration:'6h' }],
      Comida:      [{ type:'Comida',      icon:'🍽️', bg:'#fce4ec', name:'Cena Romántica',            detail:'Burj Al Arab · 20:00' }],
      Tour:        [{ type:'Tour',        icon:'🗺️', bg:'#e3f2fd', name:'Tour Ciudad',                detail:'Guía privado · 4h' }],
      Texto:       [{ type:'Texto',       icon:'Aa', bg:'#f5f5f5', name:'Nota importante',           detail:'Llevar pasaporte original' }],
      Título:      [{ type:'Título',      icon:'T',  bg:'#f5f5f5', name:'Día de llegada',             detail:'Bienvenidos a los Emiratos' }],
      Separador:   [{ type:'Separador',   icon:'—✦—',bg:'#f5f5f5', name:'— ✦ —',                      detail:'' }],
      Caja:        [{ type:'Caja',        icon:'🎨', bg:'#fce4ec', name:'Info Clima',                 detail:'32°C - Soleado' }],
      Imagen:      [{ type:'Imagen',      icon:'🖼️', bg:'#fff8e1', name:'Foto Destino',               detail:'https://picsum.photos/600/300?dubai' }],
      Gif:         [{ type:'Gif',         icon:'🎬', bg:'#f3e5f5', name:'Vibe Local',                detail:'https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExNHJocXQ3Z3R4Z3R4Z3R4Z3R4Z3R4Z3R4Z3R4Z3R4Z3R4JmVwPXYxX2ludGVybmFsX2dpZl9ieV9pZCZjdD1n/3o7TKMGpxx6xYf/giphy.gif' }],
    };

    let currentDay = 0;
    let uid = 2000;
    let vtDragType = null;
    let vtReorderId = null;
    let vtTargetId = null;
    let vtTimer = null;
    let vtExtraIdx = {};

    const days = [
      { id: 0, title: 'Día 1: Llegada y Bienvenida', items: VT_DEFAULTS[0].map(x => ({...x, id: uid++})) },
      { id: 1, title: 'Día 2: Explorando Dubai', items: VT_DEFAULTS[1].map(x => ({...x, id: uid++})) },
      { id: 2, title: 'Día 3: El Desierto', items: VT_DEFAULTS[2].map(x => ({...x, id: uid++})) }
    ];

    window.vtSwitch = (n) => {
      currentDay = n;
      document.querySelectorAll('.vt-tab').forEach((t, idx) => t.classList.toggle('active', idx === n));
      vtUpdatePreviewSidebar();
      vtRender();
    };

    function vtUpdatePreviewSidebar() {
      const list = document.getElementById('vtPreviewDaysList');
      const header = document.getElementById('vtPreviewDayHeader');
      if (list) {
        list.innerHTML = days.map((day, idx) => `
          <div class="vt-preview-day-item ${idx === currentDay ? 'active' : ''}" onclick="vtSwitch(${idx})">
            Día ${idx + 1} </span>
          </div>
        `).join('');
      }
      if (header && days[currentDay]) {
        header.textContent = days[currentDay].title;
      }
    }

    window.vtHandleDragOver = (e) => {
      e.preventDefault();
      document.getElementById('vtCanvas').classList.add('vt-over');
    };
    window.vtHandleDragLeave = () => document.getElementById('vtCanvas').classList.remove('vt-over');

    window.vtDrop = (e) => {
      e.preventDefault();
      document.getElementById('vtCanvas').classList.remove('vt-over');
      
      if (vtReorderId !== null) {
        const fromIdx = days[currentDay].items.findIndex(x => x.id === vtReorderId);
        const toIdx = days[currentDay].items.findIndex(x => x.id === vtTargetId);
        if (fromIdx !== -1 && toIdx !== -1 && fromIdx !== toIdx) {
          const row = days[currentDay].items.splice(fromIdx, 1)[0];
          days[currentDay].items.splice(toIdx, 0, row);
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
      days[currentDay].items.push({...tpl, id: uid++});
      vtRender();
      vtToast('✓ ' + tpl.name + ' añadido');
      vtDragType = null;
    };

    window.vtRemove = (id) => {
      days[currentDay].items = days[currentDay].items.filter(x => x.id !== id);
      vtRender();
    };

    function vtRender() {
      const list  = document.getElementById('vtItems');
      const empty = document.getElementById('vtEmpty');
      const hint  = document.getElementById('vtDropHint');
      const count = document.getElementById('vtCount');
      if (!list) return;
      
      const items = days[currentDay].items;
      list.innerHTML = '';
      
      const isPreview = document.querySelector('.vt-root').classList.contains('is-preview');
      
      if (!items.length) {
        empty.style.display = 'flex'; hint.style.display = 'none';
      } else {
        empty.style.display = 'none'; hint.style.display = isPreview ? 'none' : 'block';
        items.forEach(item => {
          const el = document.createElement('div');
          el.className = 'vt-item';
          el.dataset.type = item.type;
          
          if (!isPreview) {
            el.draggable = true;
            el.addEventListener('dragstart', (e) => {
              vtReorderId = item.id;
              setTimeout(() => el.classList.add('vt-dragging'), 0);
            });
            el.addEventListener('dragover', (e) => {
              if (vtReorderId === null) return;
              e.preventDefault(); vtTargetId = item.id;
              el.classList.add('vt-drag-over');
            });
            el.addEventListener('dragleave', () => el.classList.remove('vt-drag-over'));
            el.addEventListener('dragend', () => {
              el.classList.remove('vt-dragging');
              document.querySelectorAll('.vt-item').forEach(i => i.classList.remove('vt-drag-over'));
              vtReorderId = null;
            });
          }

          if (isPreview) {
            renderRichCard(el, item);
          } else {
            renderEditorCard(el, item);
          }
          list.appendChild(el);
        });
      }
      count.textContent = items.length + ' elemento' + (items.length !== 1 ? 's' : '');
    }

    function renderEditorCard(el, item) {
      const isVisual = item.type === 'Imagen' || item.type === 'Gif';
      el.innerHTML = `
        <div class="vt-item-drag"><span><i></i><i></i></span></div>
        <div class="vt-item-icon" style="background:${item.bg}">${item.icon}</div>
        <div class="vt-item-body">
          <div class="vt-item-type">${item.type}</div>
          <div class="vt-item-name">${item.name}</div>
          ${isVisual ? `<img src="${item.detail}" style="width:100%; border-radius:6px; margin-top:5px; height:80px; object-fit:cover;">` : `<div class="vt-item-detail">${item.detail}</div>`}
        </div>
        <div class="vt-item-actions">
          <button class="vt-item-btn del" onclick="vtRemove(${item.id})">✕</button>
        </div>`;
    }

    function renderRichCard(el, item) {
      if (item.type === 'Vuelo') {
        el.innerHTML = `
          <div class="vt-item-flight-rich">
            <div class="flight-header">
              <span><i class="fas fa-plane"></i> Vuelo ${item.name}</span>
              <span class="flight-badge">${item.flight || 'EX-0383'}</span>
            </div>
            <div class="flight-main">
              <div class="time"><strong>14:30</strong><span>Barcelona (BCN)</span></div>
              <div class="plane-icon"><i class="fas fa-plane"></i></div>
              <div class="time"><strong>21:55</strong><span>Dubai (DXB)</span></div>
            </div>
            <div class="flight-footer">
              <i class="fas fa-info-circle"></i> Equipaje incluido: ✓ Personal · ✓ Mano · ✕ Bodega
            </div>
          </div>`;
      } else if (item.type === 'Alojamiento') {
        el.innerHTML = `
          <div class="vt-item-hotel-rich">
            <div class="hotel-title"><i class="fas fa-hotel"></i> ${item.name}</div>
            <div class="hotel-stars">⭐⭐⭐⭐⭐</div>
            <div class="hotel-detail">${item.detail}</div>
            <div class="hotel-location"><i class="fas fa-map-marker-alt"></i> ${item.location || 'Dubai Oceanfront'}</div>
          </div>`;
      } else {
        const isVisual = item.type === 'Imagen' || item.type === 'Gif';
        el.innerHTML = `
          <div class="vt-item-generic-rich">
            <div class="generic-title"><i class="${getIcon(item.type)}"></i> ${item.name}</div>
            <div class="generic-detail">${item.detail}</div>
            ${isVisual ? `<img src="${item.detail}" style="width:100%; border-radius:8px; margin-top:10px;">` : ''}
          </div>`;
      }
    }

    function getIcon(type) {
      const map = { Actividad:'fas fa-star', Comida:'fas fa-utensils', Tour:'fas fa-map-signs', Traslado:'fas fa-car', Texto:'fas fa-align-left' };
      return map[type] || 'fas fa-info-circle';
    }

    function vtToast(msg) {
      const el = document.getElementById('vtToastEl');
      if (!el) return;
      el.textContent = msg; el.classList.add('show');
      clearTimeout(vtTimer);
      vtTimer = setTimeout(() => el.classList.remove('show'), 2000);
    }

    // PREVIEW TOGGLE
    const vtPreviewBtn = document.getElementById('vtPreviewBtn');
    if (vtPreviewBtn) {
      vtPreviewBtn.addEventListener('click', () => {
        const root = document.querySelector('.vt-root');
        root.classList.toggle('is-preview');
        const isPreview = root.classList.contains('is-preview');
        vtPreviewBtn.innerHTML = isPreview 
          ? '<i class="fas fa-edit"></i> <span>Volver al editor</span>' 
          : '<i class="fas fa-eye"></i> <span>Vista previa</span>';
        vtUpdatePreviewSidebar();
        vtRender();
      });
    }

    window.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.vt-card').forEach(c => {
        c.addEventListener('dragstart', () => { vtDragType = c.dataset.type; c.style.opacity = '0.5'; });
        c.addEventListener('dragend', () => c.style.opacity = '1');
      });
      vtUpdatePreviewSidebar();
      vtRender();
    });
  })();
</script>




<!-- QUOTE -->
<section class="quote-section">
  <div class="container">
    <div class="reveal">
      <p class="big-quote">"Transformamos un proceso que <em>normalmente toma horas</em> en una tarea que se completa en <em>minutos</em>."</p>
      <p class="quote-sub">— Equipo Viantryp</p>
    </div>
  </div>
</section>


<!-- PRICING -->
<section class="pricing" id="precios">
  <div class="container">
    <div class="reveal" style="text-align:center;">
      <div class="section-label">Precios</div>
      <h2 class="section-title">Planes simples y transparentes</h2>
      <p class="section-desc" style="margin:0 auto 3rem;">Sin sorpresas. Cancela cuando quieras.</p>

      <div class="pricing-toggle-wrap reveal">
     <span class="toggle-label" id="labelMonthly">Mensual</span>
        <div class="toggle-switch annual" id="priceToggle"></div>
        <span class="toggle-label active" id="labelAnnual">Anual</span>
        <span class="annual-discount">Ahorra hasta 25%</span>
      </div>
    </div>
    <div class="pricing-grid">
      <!-- Free Forever -->
      <div class="plan reveal d1">
        <div class="plan-name">Free Forever</div>
        <div class="plan-price">
          <span class="currency">$</span>
          <span class="price-val">0</span>
        </div>
        <div class="plan-desc-special">Para exploradores y curiosos</div>
        <div class="plan-savings" style="opacity: 0; margin-bottom: 2rem;">&nbsp;</div>
        <ul class="plan-features">
          <li>Hasta 3 itinerarios activos</li>
          <li>Límite de 10 archivos adjuntos</li>
          <li>Editor Visual "Drag & Drop"</li>
          <li>Enlace con marca Viantryp</li>
        </ul>
        <a href="{{ route('register') }}" class="plan-btn">Comenzar gratis</a>
      </div>

      <!-- Unlimited -->
      <div class="plan reveal d2">
        <div class="plan-name">Unlimited</div>
        <div class="plan-price">
          <span class="currency">$</span>
          <span class="price-val" data-monthly="19.00" data-annual="15.00">15.00</span>
          <span class="period">/mes</span>
        </div>
        <div class="price-note" data-monthly="Facturado mensualmente" data-annual="Facturado anualmente">Facturado anualmente</div>
        <div class="plan-savings" style="opacity: 1;">Ahorras $48 al año</div>
        <div class="plan-sub">Todo lo del Free Forever, más:</div>
        <ul class="plan-features">
          <li>Itinerarios y archivos ilimitados</li>
          <li>Tu logo en el itinerario</li>
          <li>Link de viaje personalizado</li>
          <li>Soporte básico</li>
        </ul>
        <a href="{{ route('register') }}" class="plan-btn">Comenzar ahora</a>
      </div>

      <!-- Business & Teams -->
      <div class="plan featured reveal d3">
        <div class="plan-badge">Más popular</div>
        <div class="plan-name">Business & Teams</div>
        <div class="plan-price">
          <span class="currency">$</span>
          <span class="price-val" data-monthly="34.00" data-annual="27.00">27.00</span>
          <span class="period">/mes</span>
        </div>
        <div class="price-note" data-monthly="Facturado mensualmente" data-annual="Facturado anualmente">Facturado anualmente</div>
        <div class="plan-savings" style="opacity: 1;">Ahorras $84 al año</div>
        <div class="plan-sub">Todo lo del Unlimited, más:</div>
        <ul class="plan-features">
          <li>Viantryp AI para generar rutas</li>
          <li>Colaboración entre equipos</li>
          <li>Gestión de roles y permisos</li>
          <li>Integraciones vía API básicas</li>
        </ul>
        <a href="{{ route('register') }}" class="plan-btn primary">Comenzar ahora</a>
      </div>

      <!-- Enterprise -->
      <div class="plan reveal d4">
        <div class="plan-name">Enterprise</div>
        <div class="plan-price" style="font-size: 20px; line-height: 1.2; margin-bottom: 0.5rem;">Precios flexibles</div>
        <div class="plan-desc-special">Seguridad y escala profunda</div>
        <div class="plan-savings" style="opacity: 0; margin-bottom: 2rem;">&nbsp;</div>
        <div class="plan-sub">Todo lo de Business & Teams, más:</div>
        <ul class="plan-features">
          <li>Dominio personalizado</li>
          <li>Soporte dedicado y SLA</li>
          <li>Integraciones API avanzadas</li>
        </ul>
        <a href="{{ route('register') }}" class="plan-btn">Comenzar ahora</a>
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
        <a href="{{ route('register') }}" class="btn-secondary">Unirse ahora</a>
      </div>
      <p class="cta-note">Sin tarjeta de crédito · Cancela cuando quieras · Soporte en español</p>
    </div>
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
    </div>
    <div class="footer-copy">© 2026 Viantryp. Hecho con ♥.</div>
  </div>
</footer>

<script>
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
  }, { threshold: 0.1 });
  document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

  // Pricing Toggle Logic
  (function() {
    const toggle = document.getElementById('priceToggle');
    const labelMonthly = document.getElementById('labelMonthly');
    const labelAnnual = document.getElementById('labelAnnual');
    const priceVals = document.querySelectorAll('.price-val');
    const priceNotes = document.querySelectorAll('.price-note');
    const savingsLabels = document.querySelectorAll('.plan-savings');

    if (toggle) {
      toggle.addEventListener('click', () => {
        const isAnnual = toggle.classList.toggle('annual');
        labelAnnual.classList.toggle('active', isAnnual);
        labelMonthly.classList.toggle('active', !isAnnual);
        
        priceVals.forEach(v => {
          const target = isAnnual ? v.dataset.annual : v.dataset.monthly;
          if (target) {
            v.style.opacity = '0';
            setTimeout(() => { v.textContent = target; v.style.opacity = '1'; }, 150);
          }
        });

        priceNotes.forEach(n => {
          const target = isAnnual ? n.dataset.annual : n.dataset.monthly;
          if (target) {
            n.style.opacity = '0';
            setTimeout(() => { n.textContent = target; n.style.opacity = '1'; }, 150);
          }
        });

        savingsLabels.forEach(s => {
          s.style.opacity = isAnnual ? '1' : '0';
          s.style.transform = isAnnual ? 'translateY(0)' : 'translateY(5px)';
          s.style.pointerEvents = isAnnual ? 'auto' : 'none';
        });
      });
      // Ensure smooth transition
      priceVals.forEach(v => v.style.transition = 'opacity 0.2s');
      priceNotes.forEach(n => n.style.transition = 'opacity 0.2s');
    }
  })();

  // Solutions Tab Logic
  (function() {
    const tabs = document.querySelectorAll('.sol-tab');
    const tagline = document.querySelector('.sol-tagline');
    const text = document.querySelector('.sol-text');
    const benefitsList = document.querySelector('.sol-benefits');
    const contentBox = document.querySelector('.solutions-card');
    const featuresRight = document.getElementById('sol-right');

    const data = {
      viajeros: {
        tagline: 'Organiza tu <span>Aventura.</span>',
        text: 'Crea rutas perfectas en minutos y lleva todo tu viaje en la palma de tu mano, siempre actualizado.',
        benefits: [
          'Planifica sin estrés manteniendo todo bajo control en un solo lienzo digital.',
          'Disfruta de un diseño que evoluciona junto a tus ideas.',
          'Actualiza tu viaje sin rehacer documentos.'
        ],
        features: [
          { title: 'Editor visual', span: 'Drag & Drop', desc: 'Arrastra destinos y fotos para diseñar tu ruta ideal en segundos. Es tan fácil como jugar, pero con resultados profesionales.', icon: 'fas fa-pencil-ruler', color: '#0b8ea3' },
          { title: 'Enlace interactivo', span: 'personal', desc: 'Lleva todo tu plan en un solo link. Si cambias de opinión sobre un lugar, actualízalo y ten tu ruta siempre al día en tu móvil.', icon: 'fas fa-link', color: '#22c55e' },
          { title: 'Toda tu documentación', span: 'a mano', desc: 'Guarda tus reservas y mapas directamente en el día que corresponden. Olvida buscar entre cientos de correos y capturas de pantalla.', icon: 'fas fa-file-invoice', color: '#0f172a' }
        ]
      },
      agencias: {
        tagline: 'Escala tu <span>Agencia.</span>',
        text: 'Optimiza la operación de tu equipo y mejora la conversión de ventas.',
        benefits: [
          'Cierra propuestas más rápido con una visual que enamora a tus clientes.',
          'Reduce horas de diseño a simples minutos de edición.',
          'Fideliza a tus viajeros con una herramienta interactiva y fácil de usar.'
        ],
        features: [
          { title: 'Marca Blanca', span: 'Total', desc: 'Elimina el logo de Viantryp y usa tu propia identidad. Presenta tus viajes bajo tu dominio y proyecta una imagen de gran operadora.', icon: 'fas fa-id-card', color: '#0b8ea3' },
          { title: 'Propuestas interactivas', span: 'de lujo', desc: 'Envía enlaces elegantes que enamoran a tus clientes. Sustituye los PDFs pesados por una experiencia digital que cierra ventas.', icon: 'fas fa-desktop', color: '#22c55e' },
          { title: 'Gestión operativa', span: '360°', desc: 'Vincula vouchers y seguros de viaje a cada servicio. Tu cliente tendrá todo el soporte organizado y accesible en un solo clic.', icon: 'fas fa-cog', color: '#0f172a' }
        ]
      },
      grupos: {
        tagline: 'Viajes en <span>Grupo.</span>',
        text: 'Mantén a todos sincronizados y felices en cada etapa de la aventura.',
        benefits: [
          'Haz que todos vivan la experiencia del viaje antes de despegar.',
          'Invita a más viajeros para colaborar en un mismo tablero.',
          'Unifica la información del viaje del grupo en un solo lugar.'
        ],
        features: [
          { title: 'Planificación', span: 'colaborativa', desc: 'Invita a tus amigos o familia a editar juntos. Decidan las paradas en tiempo real y eviten los grupos de WhatsApp infinitos.', icon: 'fas fa-users', color: '#0f172a' },
          { title: 'Centro de control', span: 'grupal', desc: 'Un solo lugar para los tickets de todos. Adjunta los pases de abordar y reservas de grupo para que nadie se pierda nada.', icon: 'fas fa-th-large', color: '#22c55e' },
          { title: 'Diseño visual', span: 'compartido', desc: 'Crea un itinerario que todos amen. Arrastra fotos de los destinos para que el grupo empiece a vivir el viaje antes de despegar.', icon: 'fas fa-image', color: '#6366f1' }
        ]
      },
      operadores: {
        tagline: 'Operativa de <span>Alto Nivel.</span>',
        text: 'Control absoluto sobre tu inventario y logística en terreno para grupos masivos.',
        benefits: [
          'Garantiza calidad en cada uno de tus servicios locales.',
          'Gestiona imprevistos en segundos con tu equipo en campo.',
          'Presenta tus servicios con un impacto visual.'
        ],
        features: [
          { title: 'Propuesta visual', span: 'de servicios', desc: 'Presenta cada servicio con identidad visual. Fotos de alta calidad y descripciones cautivadoras que venden por ti.', icon: 'fas fa-images', color: '#22c55e' },
          { title: 'Logística de operación', span: 'en vivo', desc: 'Sincroniza el terreno al instante. Envía actualizaciones a guías y transportistas sobre el mismo itinerario, eliminando errores de comunicación.', icon: 'fas fa-map-marked-alt', color: '#0b8ea3' },
          { title: 'Consolidador de servicios', span: '360°', desc: 'Agrupa servicios locales, traslados y experiencias en una sola ruta maestra profesional.', icon: 'fas fa-layer-group', color: '#0f172a' }
        ]
      },
      empresas: {
        tagline: 'Eventos <span>Corporativos.</span>',
        text: 'Logística impecable para tus viajes de negocios y eventos de gran escala.',
        benefits: [
          'Controla quién edita y maneja la agenda de cada evento.',
          'Protege la información sensible con protocolos de visualización privada.',
          'Refuerza el prestigio de tu marca corporativa en cada detalle del proyecto.'
        ],
        features: [
          { title: 'Logística de eventos', span: 'en tiempo real', desc: 'Gestiona agendas complejas para grupos grandes y mantén actualizada la información al instante.', icon: 'fas fa-calendar-alt', color: '#0b8ea3' },
          { title: 'Colaboración', span: 'multiequipo', desc: 'Asigna roles y permisos. Deja que tus coordinadores de campo y oficina trabajen sobre el mismo lienzo con total seguridad.', icon: 'fas fa-user-friends', color: '#0f172a' },
          { title: 'Interfaz de marca', span: 'corporativa', desc: 'Profesionaliza la comunicación interna y externa de cada proyecto con los colores de la empresa o evento.', icon: 'fas fa-building', color: '#6366f1' }
        ]
      }
    };

    tabs.forEach(tab => {
      tab.addEventListener('click', () => {
        const target = tab.dataset.target;
        if (!target || !data[target]) return;

        // Update active state
        tabs.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');

        // Transition content
        contentBox.style.opacity = '0.7';
        contentBox.style.transform = 'translateY(10px)';
        
        setTimeout(() => {
          const item = data[target];
          tagline.innerHTML = item.tagline;
          text.textContent = item.text;
          
          benefitsList.innerHTML = item.benefits.map(b => 
            `<li><i class="fas fa-check"></i> ${b}</li>`
          ).join('');

          if (featuresRight && item.features) {
            featuresRight.innerHTML = item.features.map(f => `
              <div class="sol-feature-item">
                <div class="sol-feature-icon" style="color: ${f.color};">
                  <i class="${f.icon}"></i>
                </div>
                <div class="sol-feature-info">
                  <div class="sol-feature-name">${f.title} <span>${f.span}</span></div>
                  <div class="sol-feature-desc">${f.desc}</div>
                </div>
              </div>
            `).join('');
          }

          contentBox.style.opacity = '1';
          contentBox.style.transform = 'translateY(0)';
        }, 150);
      });
    });
    
    if (contentBox) {
      contentBox.style.transition = 'all 0.3s ease';
    }
  })();

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
