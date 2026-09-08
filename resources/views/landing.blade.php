<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Viantryp | Home</title>
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
  {{-- PWA --}}
  <link rel="manifest" href="{{ asset('manifest.json') }}">
  <meta name="theme-color" content="#0d2b3e">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="apple-mobile-web-app-title" content="Viantryp">
  <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">
  <link
    href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&family=Barlow+Condensed:wght@700;800;900&family=Barlow:wght@400;500;600;700&family=Inter:wght@400;600;700;800&display=swap"
    rel="stylesheet">
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

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html {
      scroll-behavior: smooth;
    }

    @media (min-width: 992px) {
      html {
        zoom: 0.9;
      }
    }

    body {
      font-family: 'Manrope', 'Barlow', sans-serif;
      background: var(--white);
      color: var(--text);
      overflow-x: hidden;
    }

    /* ── NAV INITIAL & SCROLLED STATES ── */
    nav {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 100;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 1.1rem 4rem;
      background: transparent;
      backdrop-filter: none;
      -webkit-backdrop-filter: none;
      border-bottom: 1px solid transparent;
      box-shadow: none;
      transition: background 0.35s ease, border-color 0.35s ease, box-shadow 0.35s ease, padding 0.35s ease;
    }

    nav.scrolled {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border-bottom: 1px solid rgba(226, 232, 240, 0.8);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
      padding: 0.85rem 4rem;
    }

    .nav-logo {
      display: flex;
      align-items: center;
      text-decoration: none;
    }

    .nav-logo-wrap {
      position: relative;
      display: flex;
      align-items: center;
      height: 32px;
    }

    .nav-logo-img {
      height: 32px;
      width: auto;
      transition: opacity 0.35s ease;
      display: block;
    }

    .nav-logo-img.logo-cyan {
      position: relative;
      opacity: 1;
    }

    .nav-logo-img.logo-black {
      position: absolute;
      top: 0;
      left: 0;
      opacity: 0;
    }

    nav.scrolled .nav-logo-img.logo-cyan {
      opacity: 0;
    }

    nav.scrolled .nav-logo-img.logo-black {
      opacity: 1;
    }

    .nav-links {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      align-items: center;
      gap: 12px;
      list-style: none;
      margin: 0;
      padding: 0;
    }

    .nav-links a {
      text-decoration: none;
      color: #1e293b;
      font-size: 14px !important;
      font-weight: 600;
      padding: 6px 14px;
      border-radius: 8px;
      transition: all 0.2s ease;
    }

    .nav-links a:hover {
      background: rgba(2, 181, 203, 0.08);
      color: #02b5cb;
    }

    .nav-right {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .nav-login {
      font-family: 'Manrope', sans-serif;
      font-size: 0.9rem;
      font-weight: 600;
      color: #0f2a3a;
      text-decoration: none;
      padding: 0.55rem 1.2rem;
      border-radius: 100px;
      border: 1px solid rgba(15, 42, 58, 0.15);
      transition: all 0.2s;
    }

    .nav-login:hover {
      background: rgba(15, 42, 58, 0.05);
      border-color: rgba(15, 42, 58, 0.3);
    }

    .nav-cta {
      font-family: 'Manrope', sans-serif;
      font-size: 0.9rem;
      font-weight: 700;
      color: var(--white);
      text-decoration: none;
      padding: 0.6rem 1.4rem;
      border-radius: 100px;
      background: linear-gradient(135deg, #136075 0%, #2bb2c7 100%);
      box-shadow: 0 4px 14px rgba(43, 178, 199, 0.25);
      transition: all 0.2s;
    }

    .nav-cta:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(43, 178, 199, 0.35);
    }

    h1 {
      font-family: 'Manrope', sans-serif !important;
    }

    /* ── HERO ── */
    .hero {
      min-height: 100vh;
      display: flex;
      align-items: flex-end;
      justify-content: center;
      padding: 8.5rem 0 0 3.5rem;
      position: relative;
      overflow: hidden;
      background: linear-gradient(135deg, #f4fbfb 0%, #f8fafc 40%, #edf7f9 70%, #f0f9ff 100%);
      box-sizing: border-box;
    }

    .hero-container {
      position: relative;
      z-index: 2;
      width: 100%;
      max-width: 100%;
      margin: 0 0 0 auto;
      display: grid;
      grid-template-columns: 1fr 1.55fr;
      gap: 0;
      align-items: flex-end;
      padding-right: 0;
    }

    .hero .feature-banner-visual {
      display: flex;
      justify-content: flex-end;
      align-items: flex-end;
      align-self: flex-end;
      width: 100%;
      margin-right: 0;
      padding-right: 0;
    }

    .hero-hand-wrapper {
      position: relative;
      width: 100%;
      max-width: 960px;
      margin: 0 0 0 auto;
      display: flex;
      justify-content: flex-end;
      align-items: flex-end;
      align-self: flex-end;
      margin-bottom: 0 !important;
      padding-right: 0 !important;
      background: transparent !important;
      box-shadow: none !important;
    }

    .hero-hand-img {
      width: 100%;
      max-width: 960px;
      height: auto;
      display: block;
      object-fit: contain;
      vertical-align: bottom;
      margin-bottom: 0 !important;
      margin-right: 0 !important;
      filter: none !important;
      animation: none !important;
      transition: none !important;
      transform: none !important;
      box-shadow: none !important;
    }

    .hero-hand-wrapper:hover .hero-hand-img {
      transform: none !important;
    }

    .hero-text-side {
      text-align: left;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      align-self: center;
      justify-self: flex-end;
      margin-left: auto;
      padding-top: 2rem;
      padding-bottom: 5rem;
      padding-left: 3.5rem;
      max-width: 680px;
    }

    .hero h1 {
      position: relative;
      z-index: 1;
      font-family: 'Manrope', sans-serif;
      font-size: clamp(3.2rem, 5.6vw, 76px);
      font-weight: 700;
      line-height: 1.05;
      letter-spacing: -4px;
      color: #0b2230;
      max-width: 650px;
      margin-bottom: 1.6rem;
    }

    .hero h1,
    .section-title,
    .how-left-content h2,
    .feature-banner-text h2,
    .cta-title,
    .pricing-header h2 {
      font-weight: 700 !important;
      letter-spacing: -5px !important;
    }

    .solutions-title {
      font-weight: 700 !important;
      letter-spacing: -3px !important;
    }

    /* ── HERO FLOATING BADGES (WOW EFFECT) ── */
    .hero-float-badge {
      position: absolute;
      z-index: 10;
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border: 1.5px solid rgba(2, 181, 203, 0.35);
      padding: 0.6rem 1.1rem;
      border-radius: 100px;
      font-family: 'Manrope', sans-serif;
      font-size: 0.82rem;
      font-weight: 700;
      color: #0c2332;
      box-shadow: 0 14px 35px rgba(2, 181, 203, 0.2), 0 4px 12px rgba(0, 0, 0, 0.05);
      display: flex;
      align-items: center;
      gap: 0.5rem;
      pointer-events: none;
      transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .badge-top-right {
      top: 2%;
      right: -3%;
      animation: floatBadge1 5.5s ease-in-out infinite;
    }

    .badge-bottom-left {
      bottom: 6%;
      left: -5%;
      animation: floatBadge2 6s ease-in-out infinite 0.8s;
    }

    @keyframes floatBadge1 {

      0%,
      100% {
        transform: translateY(0px) rotate(1.5deg);
      }

      50% {
        transform: translateY(-10px) rotate(-1deg);
      }
    }

    @keyframes floatBadge2 {

      0%,
      100% {
        transform: translateY(0px) rotate(-1.5deg);
      }

      50% {
        transform: translateY(-12px) rotate(1deg);
      }
    }

    .hero h1 .cyan-highlight {
      color: #02b5cb;
      display: inline-block;
    }

    .hero-text-side p {
      position: relative;
      z-index: 1;
      font-size: 1.12rem;
      font-weight: 400;
      color: #475569;
      max-width: 520px;
      line-height: 1.68;
      margin-bottom: 2.5rem;
    }

    .hero-actions {
      position: relative;
      z-index: 1;
      display: flex;
      gap: 1.2rem;
      align-items: center;
      flex-wrap: wrap;
    }

    .btn-pill-primary {
      font-family: 'Manrope', sans-serif;
      background: linear-gradient(135deg, #136075 0%, #177890 100%);
      color: #ffffff !important;
      padding: 0.85rem 2.4rem;
      border-radius: 9999px;
      font-weight: 700;
      font-size: 1.05rem;
      text-decoration: none;
      box-shadow: 0 8px 22px rgba(19, 96, 117, 0.28);
      transition: all 0.22s ease;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .btn-pill-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 28px rgba(19, 96, 117, 0.38);
    }

    .btn-pill-secondary {
      font-family: 'Manrope', sans-serif;
      background: linear-gradient(135deg, #3adbdf 0%, #20c8de 100%);
      color: #ffffff !important;
      padding: 0.85rem 2.4rem;
      border-radius: 9999px;
      font-weight: 700;
      font-size: 1.05rem;
      text-decoration: none;
      box-shadow: 0 8px 22px rgba(32, 200, 222, 0.32);
      transition: all 0.22s ease;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .btn-pill-secondary:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 28px rgba(32, 200, 222, 0.45);
    }

    /* ── FINAL CTA BANNER SECTION (MATCHING USER IMAGE) ── */
    .feature-banner {
      padding: 5.5rem 2rem;
      position: relative;
      overflow: hidden;
      background: radial-gradient(circle at 50% 30%, #e2f4f7 0%, #f4fafb 55%, #eaf5f8 100%);
      border-top: 1px solid rgba(2, 181, 203, 0.12);
      border-bottom: 1px solid rgba(2, 181, 203, 0.08);
    }

    .feature-banner-container {
      position: relative;
      z-index: 2;
      width: 100%;
      max-width: 820px;
      margin: 0 auto;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

    .feature-banner-text {
      text-align: center;
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 100%;
    }

    .feature-banner-text h2 {
      font-family: 'Manrope', sans-serif;
      font-size: clamp(2.4rem, 4.5vw, 54px);
      font-weight: 800;
      line-height: 1.1;
      letter-spacing: -3px !important;
      color: #0b2230;
      margin-bottom: 1rem;
    }

    .feature-banner-text h2 span {
      color: #2ed2ea;
      display: block;
      letter-spacing: -3px !important;
    }

    .feature-banner-text p {
      font-family: 'Manrope', sans-serif;
      font-size: 1.05rem;
      font-weight: 400;
      color: #52657a;
      line-height: 1.55;
      max-width: 680px;
      margin: 0 auto 2.2rem;
    }

    .feature-banner-actions {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 1.25rem;
      flex-wrap: wrap;
    }

    .btn-cta-dark {
      background: linear-gradient(135deg, #1b6072 0%, #3e9ab0 100%);
      color: #ffffff !important;
      padding: 0.95rem 2.8rem;
      border-radius: 100px;
      font-weight: 700;
      font-size: 1rem;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 8px 24px rgba(27, 96, 114, 0.25);
      transition: all 0.25s ease;
    }

    .btn-cta-dark:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 30px rgba(27, 96, 114, 0.35);
    }

    .btn-cta-cyan {
      background: linear-gradient(135deg, #4ce0f5 0%, #36cbe4 100%);
      color: #ffffff !important;
      padding: 0.95rem 2.8rem;
      border-radius: 100px;
      font-weight: 700;
      font-size: 1rem;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 8px 24px rgba(54, 203, 228, 0.35);
      transition: all 0.25s ease;
    }

    .btn-cta-cyan:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 30px rgba(54, 203, 228, 0.45);
    }

    /* ── DEMO SECTION TEXTURE & BACKGROUND PATTERN ── */
    .demo-section {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem 1.5rem 1.5rem;
      overflow: hidden;
      position: relative;
      background-color: #10596b;
      background-image:
        radial-gradient(circle at 15% 15%, rgba(77, 226, 244, 0.2) 0%, transparent 45%),
        radial-gradient(circle at 85% 85%, rgba(2, 181, 203, 0.16) 0%, transparent 45%),
        radial-gradient(rgba(255, 255, 255, 0.08) 1.2px, transparent 1.2px),
        linear-gradient(135deg, #10596b 0%, #156677 50%, #0d4a57 100%) !important;
      background-size: 100% 100%, 100% 100%, 28px 28px, 100% 100% !important;
    }

    .demo-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: radial-gradient(ellipse at 50% 0%, rgba(77, 226, 244, 0.12) 0%, transparent 55%);
      pointer-events: none;
      z-index: 1;
    }



    @media (max-width: 992px) {
      .hero {
        padding: 5.5rem 1.5rem 0 !important;
      }

      .hero-container {
        grid-template-columns: 1fr;
        text-align: center;
        padding-right: 0 !important;
      }

      .hero .feature-banner-visual,
      .hero-hand-wrapper {
        justify-content: center !important;
        margin: 0 auto !important;
        align-self: center !important;
        max-width: 100% !important;
      }

      .hero-hand-img {
        max-width: 520px !important;
        margin: 0 auto !important;
      }

      .hero-text-side {
        text-align: center;
        align-items: center;
        display: flex;
        flex-direction: column;
        padding-left: 0 !important;
        padding-bottom: 2rem !important;
      }

      .hero-actions {
        justify-content: center;
      }

      .hero-devices-wrapper {
        transform: none;
        max-width: 100%;
      }
    }

    .hero-stats {
      position: relative;
      z-index: 1;
      display: flex;
      gap: 3.5rem;
      margin-top: 4.5rem;
      padding-top: 4rem;
      border-top: 1px solid var(--mid-gray);
      animation: fadeDown 0.7s 0.48s ease both;
    }

    .stat {
      text-align: center;
    }

    .stat-num {
      font-family: 'Syne', sans-serif;
      font-size: 2rem;
      font-weight: 800;
      color: var(--teal);
    }

    .stat-label {
      font-size: 0.78rem;
      color: var(--text-muted);
      margin-top: 0.25rem;
    }

    @keyframes fadeDown {
      from {
        opacity: 0;
        transform: translateY(-18px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes pulse {

      0%,
      100% {
        opacity: 1;
        transform: scale(1)
      }

      50% {
        opacity: 0.5;
        transform: scale(1.4)
      }
    }

    /* ── SHARED ── */
    section {
      padding: 3rem 2rem;
    }

    .container {
      max-width: 1100px;
      margin: 0 auto;
    }

    .section-label {
      display: inline-block;
      font-size: 0.73rem;
      font-weight: 700;
      letter-spacing: 0.13em;
      text-transform: uppercase;
      color: var(--teal);
      margin-bottom: 1rem;
    }

    .section-title {
      font-family: 'Manrope', sans-serif;
      font-size: clamp(2rem, 4vw, 3rem);
      font-weight: 800;
      line-height: 1.1;
      letter-spacing: -0.092em;
      margin-bottom: 1.2rem;
      color: var(--navy);
    }

    .section-desc {
      font-size: 1.05rem;
      color: var(--text-soft);
      line-height: 1.72;
      max-width: 540px;
    }

    /* ── HOW ── */
    .how {
      padding: 7rem 4rem;
      position: relative;
      overflow: hidden;
      background: linear-gradient(135deg, #f4fbfb 0%, #f8fafc 40%, #edf7f9 70%, #f0f9ff 100%);
    }

    .how-container {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 1280px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1fr 1.15fr;
      gap: 4rem;
      align-items: center;
    }

    .how-left-content {
      text-align: left;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }

    .how-overline {
      font-family: 'Manrope', sans-serif;
      font-size: 1.18rem;
      font-weight: 600;
      color: #02b5cb;
      margin-bottom: 0.5rem;
    }

    .how-left-content h2 {
      font-family: 'Manrope', sans-serif;
      font-size: clamp(2.4rem, 4vw, 54px);
      font-weight: 800;
      line-height: 1.08;
      letter-spacing: -0.092em;
      color: #0f2a3a;
      margin-bottom: 2rem;
    }

    .cyan-highlight-big {
      color: #02b5cb;
      font-size: clamp(2.8rem, 4.8vw, 64px);
      display: inline-block;
    }

    /* Dual Phones Graphic - Realistic Static Mockups */
    .how-phones-wrapper {
      position: relative;
      width: 100%;
      max-width: 360px;
      height: 380px;
      margin-top: 1rem;
    }

    .how-phone-device {
      position: absolute;
      width: 188px;
      filter: drop-shadow(0 20px 30px rgba(0, 0, 0, 0.28));
      animation: none !important;
      transition: none !important;
    }

    .how-phone-device.phone-left {
      top: 28px;
      left: 0;
      z-index: 2;
      transform: rotate(-9deg);
      animation: none !important;
    }

    .how-phone-device.phone-right {
      top: 17px;
      left: 176px;
      z-index: 1;
      transform: rotate(10deg);
      animation: none !important;
    }

    .p-screen-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: top center;
      display: block;
      border-radius: 30px;
    }

    .p-screen-content {
      position: relative;
      z-index: 10;
      height: 100%;
      display: flex;
      flex-direction: column;
      padding: 6px 10px 10px;
      background: #f8fafc;
      font-family: 'Manrope', sans-serif;
    }

    .p-status-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 8px;
      font-weight: 700;
      color: #1e293b;
      padding: 2px 4px 6px;
    }

    .p-status-icons {
      display: flex;
      align-items: center;
      gap: 3px;
      font-size: 7px;
    }

    .p-app-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 8px;
      padding-bottom: 4px;
      border-bottom: 1px solid #e2e8f0;
    }

    .p-app-logo {
      font-family: 'Barlow Condensed', sans-serif;
      font-size: 1.05rem;
      font-weight: 900;
      color: #0f2a3a;
    }

    .p-app-logo span {
      color: #02b5cb;
    }

    .p-back-btn {
      font-size: 7px;
      font-weight: 600;
      color: #475569;
      background: #ffffff;
      border: 1px solid #cbd5e1;
      border-radius: 100px;
      padding: 2px 7px;
      cursor: pointer;
    }

    .p-menu-btn {
      font-size: 10px;
      color: #0f2a3a;
    }

    .p-welcome-body {
      display: flex;
      flex-direction: column;
      text-align: left;
    }

    .p-welcome-title {
      font-size: 0.98rem;
      font-weight: 800;
      color: #0f2a3a;
      text-align: center;
      line-height: 1.15;
      margin-bottom: 2px;
    }

    .p-welcome-sub {
      font-size: 0.58rem;
      color: #64748b;
      text-align: center;
      margin-bottom: 8px;
    }

    .p-field-group {
      margin-bottom: 5px;
    }

    .p-field-group label {
      font-size: 0.54rem;
      font-weight: 600;
      color: #475569;
      display: block;
      margin-bottom: 1px;
    }

    .p-input-box {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 6px;
      padding: 4px 7px;
      font-size: 0.58rem;
      color: #94a3b8;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    }

    .p-remember-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 0.48rem;
      color: #64748b;
      margin: 3px 0 6px;
    }

    .p-remember-row a {
      color: #02b5cb;
      text-decoration: none;
    }

    .p-btn-teal {
      background: linear-gradient(135deg, #10596b 0%, #156677 100%);
      color: #ffffff;
      border-radius: 100px;
      padding: 5px;
      font-size: 0.62rem;
      font-weight: 700;
      text-align: center;
      box-shadow: 0 3px 10px rgba(16, 89, 107, 0.25);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 4px;
    }

    .p-divider-line {
      font-size: 0.46rem;
      color: #94a3b8;
      text-align: center;
      margin: 5px 0;
      position: relative;
    }

    .p-btn-google {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      color: #1e293b;
      border-radius: 100px;
      padding: 4px;
      font-size: 0.58rem;
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 4px;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }

    .p-steps-body {
      display: flex;
      flex-direction: column;
      text-align: left;
    }

    .p-steps-tag {
      font-size: 0.52rem;
      font-weight: 800;
      letter-spacing: 0.08em;
      color: #02b5cb;
      margin-bottom: 1px;
    }

    .p-steps-heading {
      font-size: 0.88rem;
      font-weight: 800;
      color: #0f2a3a;
      line-height: 1.15;
      margin-bottom: 8px;
    }

    .p-step-card-box {
      background: #ffffff;
      border-radius: 8px;
      padding: 6px 8px;
      margin-bottom: 5px;
      border: 1px solid #f1f5f9;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
      display: flex;
      flex-direction: column;
      gap: 1px;
    }

    .p-step-badge {
      font-size: 0.95rem;
      font-weight: 900;
      color: #cbd5e1;
      line-height: 1;
    }

    .p-step-text h4 {
      font-size: 0.62rem;
      font-weight: 700;
      color: #0f2a3a;
      margin-bottom: 1px;
    }

    .p-step-text p {
      font-size: 0.52rem;
      color: #64748b;
      line-height: 1.25;
      margin: 0;
    }

    /* Step Cards Right Column */
    .how-step-cards {
      display: flex;
      flex-direction: column;
      gap: 1.35rem;
    }

    .how-card {
      background: rgba(214, 235, 242, 0.65);
      border: 1px solid rgba(2, 181, 203, 0.15);
      border-radius: 24px;
      padding: 1.8rem 2.2rem;
      display: grid;
      grid-template-columns: auto 1fr;
      gap: 1.8rem;
      align-items: center;
      transition: all 0.28s ease;
    }

    .how-card:hover {
      background: rgba(214, 235, 242, 0.9);
      transform: translateX(6px);
      box-shadow: 0 12px 30px rgba(15, 42, 58, 0.06);
    }

    .how-card-num-side {
      display: flex;
      align-items: center;
      gap: 0.6rem;
    }

    .how-card-arrow {
      color: #0f2a3a;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0.75;
    }

    .how-card-num {
      font-family: 'Manrope', sans-serif;
      font-size: 7.5rem;
      font-weight: 800;
      color: #02b5cb;
      line-height: 1;
      letter-spacing: -0.04em;
    }

    .how-card-text-side {
      text-align: left;
    }

    .how-card-text-side h3 {
      font-family: 'Manrope', sans-serif;
      font-size: 1.55rem;
      font-weight: 800;
      color: #0f2a3a;
      letter-spacing: -0.092em;
      margin-bottom: 0.5rem;
    }

    .how-card-text-side p {
      font-size: 1rem;
      font-weight: 400;
      color: #475569;
      line-height: 1.55;
    }

    @media (max-width: 992px) {
      .how {
        padding: 4rem 2rem;
      }

      .how-container {
        grid-template-columns: 1fr;
        gap: 3rem;
      }

      .how-left-content {
        text-align: center;
        align-items: center;
      }

      .how-phones-wrapper {
        margin: 0 auto;
      }
    }

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


    /* ── QUOTE SECTION ── */
    .quote-section {
      position: relative;
      background: linear-gradient(135deg, #2ea1b5 0%, #1e8396 100%);
      padding: 6.5rem 2rem;
      text-align: center;
      overflow: hidden;
      color: #ffffff;
    }

    .quote-pattern-left {
      position: absolute;
      top: 50%;
      left: -80px;
      transform: translateY(-50%);
      width: 420px;
      height: 420px;
      opacity: 0.38;
      pointer-events: none;
    }

    .quote-pattern-right {
      position: absolute;
      bottom: -80px;
      right: -80px;
      width: 400px;
      height: 400px;
      opacity: 0.38;
      pointer-events: none;
    }

    .quote-container {
      position: relative;
      z-index: 2;
      max-width: 1060px;
      margin: 0 auto;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .big-quote {
      font-family: 'Manrope', sans-serif;
      font-size: clamp(2.1rem, 3.8vw, 48px);
      font-weight: 800;
      line-height: 1.22;
      letter-spacing: -0.092em;
      color: #ffffff;
      max-width: 980px;
      margin: 0 auto;
    }

    .quote-author {
      font-family: 'Manrope', sans-serif;
      font-size: 1.3rem;
      font-weight: 700;
      color: #ffffff;
      margin-top: 2rem;
      opacity: 0.95;
    }

    /* ── PRICING SECTION ── */
    .pricing {
      background-color: var(--white);
      background-image: radial-gradient(circle at 10% 20%, rgba(26, 122, 138, 0.03) 0%, transparent 40%), radial-gradient(circle at 90% 80%, rgba(138, 184, 32, 0.03) 0%, transparent 40%);
      padding: 3.5rem 2rem 4rem;
    }

    .pricing-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      grid-auto-rows: 1fr;
      gap: 1.75rem;
      max-width: 1140px;
      width: 100%;
      align-items: stretch;
      padding-top: 1.5rem;
      margin: 0 auto;
    }

    .pricing .section-label {
      font-family: 'Manrope', sans-serif;
      font-size: 1.18rem;
      font-weight: 600;
      color: #02b5cb;
      margin-bottom: 0.5rem;
      text-transform: none;
      letter-spacing: normal;
    }

    .annual-discount-pill {
      background: rgba(2, 181, 203, 0.1);
      color: #02b5cb;
      font-size: 11px;
      font-weight: 800;
      padding: 3px 10px;
      border-radius: 100px;
      margin-left: 6px;
      border: 1px solid rgba(2, 181, 203, 0.25);
    }

    .plan {
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(226, 232, 226, 0.6);
      border-radius: 24px;
      padding: 2.25rem 1.75rem;
      display: flex;
      flex-direction: column;
      height: 100%;
      min-width: 0;
      transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
      position: relative;
    }

    .plan:hover {
      transform: translateY(-8px);
      background: var(--white);
      box-shadow: 0 30px 60px rgba(0, 0, 0, 0.08);
      border-color: rgba(11, 142, 163, 0.2);
    }

    .plan.featured {
      background: #0A2540;
      border-color: rgba(30, 170, 206, 0.3);
      position: relative;
      box-shadow: 0 20px 50px rgba(10, 37, 64, 0.35);
      color: #ffffff;
      transform: translateY(14px);
    }

    .plan.featured:hover {
      transform: translateY(4px);
      box-shadow: 0 40px 80px rgba(10, 37, 64, 0.45);
      border-color: #1EAACE;
    }

    .plan-badge {
      position: absolute;
      top: -14px;
      left: 50%;
      transform: translateX(-50%);
      background: linear-gradient(90deg, var(--lime), var(--lime-bright));
      color: var(--white);
      font-size: 0.7rem;
      font-weight: 800;
      padding: 0.4rem 1.2rem;
      border-radius: 100px;
      letter-spacing: 0.05em;
      white-space: nowrap;
      text-transform: uppercase;
      box-shadow: 0 4px 12px rgba(138, 184, 32, 0.3);
    }

    .plan-name {
      font-size: 13px;
      font-weight: 700;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: var(--text-muted);
      margin-bottom: 0.2rem;
    }

    .plan.featured .plan-name {
      color: #5dcfe0;
    }

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

    .plan.featured .plan-price {
      color: var(--white);
    }

    .plan-price .period {
      font-size: 1rem;
      font-weight: 400;
      color: var(--text-muted);
    }

    .plan.featured .plan-price .period {
      color: rgba(255, 255, 255, 0.4);
    }

    .price-note {
      font-size: 0.75rem;
      color: var(--text-muted);
      margin-bottom: 0.5rem;
      transition: opacity 0.2s;
    }

    .plan.featured .price-note {
      color: rgba(255, 255, 255, 0.4);
    }

    .plan-savings {
      font-size: 0.78rem;
      font-weight: 700;
      color: #02b5cb !important;
      background: #d8f8f6 !important;
      padding: 0.2rem 0.6rem;
      border-radius: 6px;
      display: inline-block;
      margin-bottom: 2rem;
      transition: all 0.3s ease;
    }

    .plan.featured .plan-savings {
      color: #02b5cb !important;
      background: #d8f8f6 !important;
    }

    .plan-sub {
      font-size: 0.88rem;
      color: var(--text-soft);
      margin-bottom: 1.2rem;
      min-height: 2.5rem;
    }

    .plan.featured .plan-sub {
      color: rgba(255, 255, 255, 0.6);
    }

    .plan-features {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 11px;
      margin-bottom: 2.5rem;
      flex: 1;
    }

    .plan-features li {
      font-size: 12.5px;
      color: var(--text-soft);
      display: flex;
      gap: 0.75rem;
      line-height: 1.4;
    }

    .plan.featured .plan-features li {
      color: rgba(255, 255, 255, 0.7);
    }

    .plan-features i {
      color: var(--teal);
      font-size: 1rem;
      margin-top: 0.15rem;
    }

    .plan.featured .plan-features i {
      color: var(--lime);
    }

    .plan-btn {
      display: block;
      width: 100%;
      text-align: center;
      padding: 1.1rem;
      border-radius: 12px;
      font-size: 0.95rem;
      font-weight: 700;
      transition: all 0.3s;
      border: 1px solid var(--mid-gray);
      color: var(--navy);
    }

    .plan-btn:hover {
      background: #f8fafc;
      border-color: var(--teal);
      color: var(--teal);
      transform: scale(1.02);
    }

    .plan-btn.btn-pro {
      background: #197388;
      color: #ffffff;
      border: none;
      box-shadow: 0 4px 15px rgba(30, 170, 206, 0.4);
    }

    .plan-btn.btn-pro:hover {
      background: #1997b8;
      color: #ffffff;
      transform: scale(1.02);
      box-shadow: 0 8px 22px rgba(30, 170, 206, 0.5);
    }

    .plan-btn.btn-outline {
      background: transparent;
      color: var(--navy);
      border: 2px solid var(--navy);
    }

    .plan-btn.btn-outline:hover {
      background: var(--navy);
      color: #ffffff;
      transform: scale(1.02);
    }

    @media (max-width: 1100px) {
      .pricing-grid {
        grid-template-columns: repeat(2, 1fr);
        padding: 0 1rem;
      }
    }

    @media (max-width: 768px) {
      .pricing {
        padding: 6rem 1rem;
      }

      .pricing-grid {
        display: flex;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        gap: 1.25rem;
        padding: 2.5rem 1.5rem 3rem;
        margin: 0 -1rem;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        grid-template-columns: none;
        /* Disable grid */
      }

      .pricing-grid::-webkit-scrollbar {
        display: none;
      }

      .plan {
        flex-shrink: 0;
        width: 82%;
        scroll-snap-align: center;
      }
    }

    /* ── SOLUTIONS SECTION ── */
    .solutions {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 3rem 1.5rem;
      background: linear-gradient(180deg, #ffffff 0%, #f4fbfb 100%);
      overflow: hidden;
      box-sizing: border-box;
      position: relative;
    }

    .solutions-header {
      text-align: center;
      margin-bottom: 1.5rem;
    }

    .solutions-title {
      font-family: 'Manrope', sans-serif;
      font-size: clamp(2.2rem, 3.8vw, 42px);
      font-weight: 800;
      color: #0c2332;
      margin-bottom: 0.5rem;
      letter-spacing: -0.04em;
    }

    .solutions-title span {
      color: #02b5cb;
    }

    .solutions-desc {
      font-family: 'Manrope', sans-serif;
      font-size: 1.1rem;
      color: #475569;
      max-width: 680px;
      margin: 0 auto;
      line-height: 1.5;
    }

    .solutions-tabs {
      display: flex;
      justify-content: center;
      gap: 0.7rem;
      margin-bottom: 1.5rem;
      flex-wrap: wrap;
      padding: 0 1rem;
    }

    .sol-tab {
      font-family: 'Manrope', sans-serif;
      padding: 0.55rem 1.3rem;
      border-radius: 100px;
      font-size: 0.88rem;
      font-weight: 700;
      color: #64748b;
      background: #ffffff;
      border: 1.5px solid #e2e8f0;
      cursor: pointer;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      white-space: nowrap;
    }

    .sol-tab:hover {
      border-color: #02b5cb;
      color: #02b5cb;
    }

    .sol-tab.active {
      background: linear-gradient(135deg, #136075 0%, #177890 100%);
      border-color: #136075;
      color: #ffffff;
      box-shadow: 0 8px 20px rgba(19, 96, 117, 0.25);
    }

    .solutions-card {
      background: #ffffff;
      border: 1.5px solid rgba(2, 181, 203, 0.18);
      border-radius: 28px;
      padding: 2.5rem 3rem;
      display: grid;
      grid-template-columns: 1.15fr 0.85fr;
      gap: 2.2rem;
      max-width: 1140px;
      margin: 0 auto;
      position: relative;
      box-shadow: 0 20px 50px rgba(11, 142, 163, 0.07);
    }

    .sol-content-left {
      display: flex;
      flex-direction: column;
    }

    .sol-tagline {
      font-family: 'Manrope', sans-serif;
      font-size: 1.85rem;
      font-weight: 800;
      color: #0c2332;
      margin-bottom: 0.6rem;
    }

    .sol-tagline span {
      color: #02b5cb;
      font-weight: 700;
    }

    .sol-text {
      font-family: 'Manrope', sans-serif;
      font-size: 1.02rem;
      color: #475569;
      margin-bottom: 1.4rem;
      line-height: 1.55;
    }

    .sol-benefits {
      list-style: none;
      margin-bottom: 1.6rem;
    }

    .sol-benefits li {
      font-family: 'Barlow', sans-serif;
      display: flex;
      align-items: flex-start;
      gap: 0.75rem;
      font-size: 0.95rem;
      color: #1e293b;
      font-weight: 500;
      margin-bottom: 0.65rem;
    }

    .sol-benefits li i {
      color: #02b5cb;
      font-size: 1.1rem;
      margin-top: 0.12rem;
    }

    .sol-btn {
      font-family: 'Manrope', sans-serif;
      grid-column: 1 / -1;
      justify-self: center;
      margin-top: 0.5rem;
      padding: 0.85rem 2rem;
      background: linear-gradient(135deg, #136075 0%, #177890 100%);
      color: #ffffff !important;
      border-radius: 100px;
      font-weight: 700;
      font-size: 0.9rem;
      display: inline-flex;
      align-items: center;
      gap: 0.8rem;
      transition: all 0.3s;
      box-shadow: 0 8px 20px rgba(19, 96, 117, 0.25);
    }

    .sol-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 28px rgba(19, 96, 117, 0.35);
    }

    .sol-features-right {
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
    }

    .sol-feature-item {
      background: #ecf7f8;
      padding: 1.2rem 1.5rem;
      border-radius: 20px;
      display: flex;
      align-items: center;
      gap: 1.2rem;
      box-shadow: 0 10px 25px rgba(2, 181, 203, 0.05);
      transition: all 0.3s;
      border: 1px solid rgba(2, 181, 203, 0.15);
    }

    .sol-feature-item:hover {
      transform: translateY(-2px) scale(1.01);
      box-shadow: 0 15px 35px rgba(2, 181, 203, 0.12);
      border-color: rgba(2, 181, 203, 0.3);
    }

    .sol-feature-icon {
      width: 32px;
      height: 32px;
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
    }

    .sol-feature-info {
      flex: 1;
    }

    .sol-feature-name {
      font-size: 0.95rem;
      font-weight: 700;
      color: var(--navy);
      margin-bottom: 0.2rem;
    }

    .sol-feature-name span {
      color: var(--teal);
    }

    .sol-feature-desc {
      font-size: 0.85rem;
      color: var(--text-soft);
      line-height: 1.4;
    }

    @media (max-width: 1024px) {
      .solutions-card {
        grid-template-columns: 1fr;
        padding: 3rem 2rem;
        gap: 3rem;
      }

      .sol-tagline {
        font-size: 1.8rem;
      }

      .solutions-title {
        font-size: 2.2rem;
      }
    }

    @media (max-width: 768px) {
      .solutions-tabs {
        justify-content: flex-start;
        overflow-x: auto;
        padding-bottom: 1rem;
      }

      .sol-tab {
        flex-shrink: 0;
      }
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
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
      box-shadow: 0 30px 60px rgba(26, 122, 138, 0.12);
    }

    .plan.featured {
      background: #0f172a;
      border-color: #1e293b;
      position: relative;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }

    .plan.featured:hover {
      transform: translateY(-15px);
      background: #0d1526;
      border-color: #5dcfe0;
      box-shadow: 0 40px 80px rgba(93, 207, 224, 0.25);
    }

    .plan-badge {
      position: absolute;
      top: -14px;
      left: 50%;
      transform: translateX(-50%);
      background: linear-gradient(90deg, var(--lime), var(--lime-bright));
      color: var(--white);
      font-size: 0.7rem;
      font-weight: 800;
      padding: 0.4rem 1.2rem;
      border-radius: 100px;
      letter-spacing: 0.05em;
      white-space: nowrap;
      text-transform: uppercase;
      box-shadow: 0 4px 12px rgba(138, 184, 32, 0.3);
    }

    .plan-name {
      font-size: 13px;
      font-weight: 700;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: var(--text-muted);
      margin-bottom: 0.2rem;
    }

    .plan.featured .plan-name {
      color: #5dcfe0;
    }

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

    .plan.featured .plan-price {
      color: var(--white);
    }

    .plan-price .period {
      font-size: 1rem;
      font-weight: 400;
      color: var(--text-muted);
    }

    .plan.featured .plan-price .period {
      color: rgba(255, 255, 255, 0.4);
    }

    .price-note {
      font-size: 10px;
      color: var(--text-muted);
      margin-bottom: 0.5rem;
      transition: opacity 0.2s;
    }

    .plan.featured .price-note {
      color: rgba(255, 255, 255, 0.4);
    }

    .plan-desc-special {
      font-size: 11px;
      font-weight: 600;
      color: var(--text-soft);
      margin-bottom: 1.5rem;
      display: block;
    }

    .plan.featured .plan-desc-special {
      color: rgba(255, 255, 255, 0.8);
    }

    .plan-trial-note {
      font-size: 11px;
      color: var(--text-muted);
      margin-top: 0.8rem;
      text-align: center;
      display: block;
    }

    .plan.featured .plan-trial-note {
      color: rgba(255, 255, 255, 0.4);
    }

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

    .plan.featured .plan-savings {
      background: rgba(138, 184, 32, 0.15);
    }

    .plan-sub {
      font-size: 0.88rem;
      color: var(--text-soft);
      margin-bottom: 1.2rem;
      min-height: 2.5rem;
    }

    .plan.featured .plan-sub {
      color: rgba(255, 255, 255, 0.6);
    }

    .plan-features {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 11px;
      margin-bottom: 2.5rem;
      flex: 1;
    }

    .plan-features li {
      font-size: 12.5px;
      color: var(--text-soft);
      display: flex;
      gap: 0.75rem;
      line-height: 1.4;
    }

    .plan.featured .plan-features li {
      color: rgba(255, 255, 255, 0.8);
    }

    .plan-features li::before {
      display: none !important;
      content: none !important;
    }

    .plan-btn {
      display: block;
      text-align: center;
      border: 1.5px solid var(--mid-gray);
      border-radius: 100px;
      padding: 1rem;
      font-size: 0.95rem;
      color: var(--navy);
      font-weight: 600;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .plan-btn:hover {
      background: var(--navy);
      color: var(--white);
      border-color: var(--navy);
    }

    .plan-btn.primary {
      background: var(--teal);
      color: var(--white);
      border-color: var(--teal);
      box-shadow: 0 8px 20px rgba(26, 122, 138, 0.2);
    }

    .plan-btn.primary:hover {
      background: var(--teal-dark);
      transform: scale(1.02);
    }

    /* ── CTA FINAL ── */
    .cta-final {
      background: var(--white);
    }

    .cta-box {
      background: linear-gradient(135deg, #e6f6f8 0%, var(--lime-bg) 100%);
      border: 1.5px solid var(--mid-gray);
      border-radius: 24px;
      padding: 3rem 3rem;
      text-align: center;
    }

    .cta-box h2 {
      font-family: inter, sans-serif;
      font-size: clamp(2rem, 4vw, 3rem);
      font-weight: 800;
      letter-spacing: -0.03em;
      margin-bottom: 2rem;
      color: var(--navy);
    }

    .cta-box>p {
      font-size: 1.05rem;
      color: var(--text-soft);
      margin-bottom: 2.5rem;
    }

    .cta-actions {
      display: flex;
      gap: 1rem;
      justify-content: center;
      flex-wrap: wrap;
    }

    .cta-note {
      font-size: 0.87rem;
      color: var(--text-muted);
      margin-top: 1.5rem;
    }


    /* ── FOOTER ── */
    footer {
      background: var(--navy);
      padding: 4rem 1.5rem 2rem;
    }

    .footer-inner {
      max-width: 1200px;
      margin: 0 auto;
    }

    .footer-top {
      display: grid;
      grid-template-columns: 2fr 1fr 1fr;
      gap: 2.5rem;
      padding-bottom: 3rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .footer-brand {}

    .footer-brand-desc {
      font-size: 0.875rem;
      color: rgba(255, 255, 255, 0.5);
      line-height: 1.7;
      margin: 1rem 0 1.5rem;
      max-width: 230px;
    }

    .footer-subscribe {
      display: flex;
      gap: 0;
      border-radius: 10px;
      overflow: hidden;
      border: 1px solid rgba(255, 255, 255, 0.12);
      max-width: 280px;
    }

    .footer-subscribe input {
      flex: 1;
      background: rgba(255, 255, 255, 0.05);
      border: none;
      outline: none;
      padding: 5px;
      font-size: 0.8rem;
      color: white;
      font-family: 'Inter', sans-serif;
    }

    .footer-subscribe input::placeholder {
      color: rgba(255, 255, 255, 0.3);
    }

    .footer-subscribe button {
      background: var(--teal);
      color: white;
      border: none;
      padding: 0.65rem 1rem;
      font-size: 0.78rem;
      font-weight: 700;
      cursor: pointer;
      white-space: nowrap;
      transition: background 0.2s;
      font-family: 'Inter', sans-serif;
    }

    .footer-subscribe button:hover {
      background: var(--teal-dark);
    }

    .footer-col {
      padding-left: 1.5rem;
    }

    .footer-col-title {
      font-size: 0.78rem;
      font-weight: 700;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: rgba(255, 255, 255, 0.9);
      margin-bottom: 1.2rem;
    }

    .footer-col-links {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
    }

    .footer-col-links a {
      font-size: 0.875rem;
      color: rgba(255, 255, 255, 0.45);
      text-decoration: none;
      transition: color 0.2s;
      display: inline-block;
    }

    .footer-col-links a:hover {
      color: rgba(255, 255, 255, 0.9);
    }

    .footer-bottom {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-top: 1.5rem;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .footer-copy {
      font-size: 0.76rem;
      color: rgba(255, 255, 255, 0.25);
    }

    .footer-social {
      display: flex;
      gap: 0.75rem;
    }

    .footer-social a {
      width: 32px;
      height: 32px;
      border-radius: 8px;
      background: rgba(255, 255, 255, 0.06);
      border: 1px solid rgba(255, 255, 255, 0.1);
      display: flex;
      align-items: center;
      justify-content: center;
      color: rgba(255, 255, 255, 0.4);
      font-size: 0.8rem;
      text-decoration: none;
      transition: all 0.2s;
    }

    .footer-social a:hover {
      background: var(--teal);
      border-color: var(--teal);
      color: white;
    }

    @media (max-width: 900px) {
      .footer-top {
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
      }

      .footer-brand {
        grid-column: 1 / -1;
      }

      .footer-brand-desc {
        max-width: 100%;
      }

      .footer-subscribe {
        max-width: 100%;
      }
    }

    @media (max-width: 640px) {
      .footer-top {
        grid-template-columns: 1fr;
      }

      .footer-bottom {
        flex-direction: column;
        text-align: center;
      }

      .footer-col-title {
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        padding-bottom: 1rem;
        margin-bottom: 0;
      }

      .footer-col-title::after {
        content: '\f078';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        font-size: 0.8rem;
        transition: transform 0.3s;
      }

      .footer-col.active .footer-col-title::after {
        transform: rotate(180deg);
      }

      .footer-col-links {
        display: none;
        padding-top: 1rem;
        padding-bottom: 1rem;
      }

      .footer-col.active .footer-col-links {
        display: flex;
      }

      .footer-col {
        padding-left: 0;
      }
    }

    /* ── REVEAL & STAGGER PHYSICS (SLOWER, ULTRA-SMOOTH) ── */
    .reveal {
      opacity: 0;
      transform: translateY(42px);
      transition: opacity 1.5s cubic-bezier(0.16, 1, 0.3, 1), transform 1.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .reveal.visible {
      opacity: 1;
      transform: translateY(0);
    }

    /* ── REVEAL LEFT (HERO SLIDE IN FROM LEFT - SLOWER) ── */
    .reveal-left {
      opacity: 0;
      transform: translateX(-56px);
      transition: opacity 1.6s cubic-bezier(0.16, 1, 0.3, 1), transform 1.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .reveal-left.visible {
      opacity: 1;
      transform: translateX(0);
    }

    /* ── REVEAL RIGHT (HOW SECTION PAN FROM RIGHT - SLOWER) ── */
    .reveal-right {
      opacity: 0;
      transform: translateX(60px);
      transition: opacity 1.6s cubic-bezier(0.16, 1, 0.3, 1), transform 1.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .reveal-right.visible {
      opacity: 1;
      transform: translateX(0);
    }

    /* ── REVEAL HERO HAND ENTRANCE ── */
    .reveal-hand-entrance {
      opacity: 0;
      transform: translate3d(50px, 30px, 0) scale(0.96);
      transition: opacity 1.5s cubic-bezier(0.16, 1, 0.3, 1), transform 1.5s cubic-bezier(0.16, 1, 0.3, 1);
      transition-delay: 0.25s;
      will-change: opacity, transform;
    }

    .reveal-hand-entrance.visible {
      opacity: 1;
      transform: translate3d(0, 0, 0) scale(1);
    }

    .d1 {
      transition-delay: 0.2s;
    }

    .d2 {
      transition-delay: 0.45s;
    }

    .d3 {
      transition-delay: 0.7s;
    }

    .d4 {
      transition-delay: 0.95s;
    }

    /* ── PINTEREST-INSPIRED FLOATING & SHINE MICRO-INTERACTIONS ── */
    @keyframes floatSoft {

      0%,
      100% {
        transform: translateY(0px) rotate(0deg);
      }

      50% {
        transform: translateY(-9px) rotate(0.4deg);
      }
    }

    @keyframes floatPhoneSoft {

      0%,
      100% {
        transform: translateY(0px) rotate(-1.5deg);
      }

      50% {
        transform: translateY(-12px) rotate(-0.5deg);
      }
    }

    @keyframes tabSlideInSlow {
      from {
        opacity: 0;
        transform: translateX(25px) translateY(10px);
      }

      to {
        opacity: 1;
        transform: translateX(0) translateY(0);
      }
    }

    /* Titilar / Pulse hint on inactive solution tabs */
    @keyframes tabPulseNotice {

      0%,
      100% {
        box-shadow: 0 0 0 0 rgba(2, 181, 203, 0.35);
        transform: translateY(0);
      }

      50% {
        box-shadow: 0 0 0 8px rgba(2, 181, 203, 0);
        transform: translateY(-2px);
      }
    }

    .sol-tab:not(.active) {
      animation: tabPulseNotice 3.2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    .hero .laptop-wrap {
      animation: floatSoft 6.5s ease-in-out infinite;
    }

    .hero .phone-wrap {
      animation: floatPhoneSoft 5.8s ease-in-out infinite 0.6s;
    }

    .btn-pill-primary {
      position: relative;
      overflow: hidden;
    }

    .btn-pill-primary::after {
      content: '';
      position: absolute;
      top: -50%;
      left: -70%;
      width: 50%;
      height: 200%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transform: rotate(25deg);
      transition: all 0.65s ease;
    }

    .btn-pill-primary:hover::after {
      left: 130%;
    }

    .how-card {
      transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s ease, border-color 0.35s ease;
    }

    .how-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 16px 35px rgba(2, 181, 203, 0.12);
    }

    @media (max-width: 1100px) {
      .pricing-grid {
        grid-template-columns: repeat(2, 1fr);
        padding: 0 1rem;
      }
    }

    /* ── ENHANCED RESPONSIVE MOBILE STYLES (EXCLUDING DEMO SECTION) ── */
    @media (max-width: 768px) {

      html,
      body {
        overflow-x: hidden;
      }

      nav {
        padding: 0.85rem 1.25rem !important;
        justify-content: space-between !important;
      }

      nav.scrolled {
        padding: 0.75rem 1.25rem !important;
      }

      .nav-links {
        display: none !important;
      }

      .nav-logo-wrap,
      .nav-logo-img {
        height: 28px !important;
      }

      .nav-right {
        gap: 0.5rem !important;
        margin-right: 0 !important;
      }

      .nav-right .nav-login,
      .nav-right .nav-cta {
        display: none !important;
      }

      #profileTrigger span {
        display: none !important;
      }

      .nav-auth-container {
        display: none !important;
      }

      .mobile-menu-toggle {
        display: flex !important;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 5px;
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 6px;
        z-index: 1001;
      }

      .mobile-menu-toggle:active {
        opacity: 0.7;
      }

      .mobile-menu-toggle span {
        display: block;
        width: 24px;
        height: 2px;
        background: var(--navy);
        border-radius: 2px;
        transition: 0.3s ease;
      }

      /* Hero Mobile Polish */
      .hero {
        padding: 5.5rem 0 0 0 !important;
        min-height: 100vh !important;
        min-height: 100dvh !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: space-between !important;
        align-items: center !important;
        text-align: center !important;
        box-sizing: border-box !important;
      }

      .hero-container {
        display: flex !important;
        flex-direction: column !important;
        justify-content: space-between !important;
        flex: 1 !important;
        grid-template-columns: none !important;
        width: 100% !important;
        max-width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
        gap: 1.25rem !important;
        align-items: center !important;
      }

      .hero-text-side {
        padding: 0 1.25rem !important;
        max-width: 100% !important;
        text-align: center !important;
        align-items: center !important;
        justify-content: center !important;
        margin: 0 auto !important;
      }

      .hero h1 {
        font-size: 53px !important;
        line-height: 1.05 !important;
        letter-spacing: -3.5px !important;
        margin-bottom: 1rem !important;
        text-align: center !important;
        max-width: 100% !important;
      }

      .hero p {
        font-size: 1.05rem !important;
        line-height: 1.5 !important;
        text-align: center !important;
        margin: 0 0 1.5rem 0 !important;
        color: var(--text-soft) !important;
        max-width: 100% !important;
      }

      .hero-subtext-desktop {
        display: none !important;
      }

      .hero-actions {
        display: flex !important;
        flex-direction: column !important;
        width: 100% !important;
        max-width: 320px !important;
        gap: 0.75rem !important;
        margin: 0 auto !important;
      }

      .hero-actions .btn-pill-primary,
      .hero-actions .btn-pill-secondary {
        width: 100% !important;
        justify-content: center !important;
        text-align: center !important;
        padding: 0.9rem 1.5rem !important;
        font-size: 1rem !important;
      }

      .hero-stats {
        gap: 1.5rem !important;
        flex-wrap: wrap !important;
        justify-content: center !important;
        margin-top: 1.5rem !important;
      }

      .feature-banner-visual {
        width: 100% !important;
        padding: 0 !important;
        margin: auto 0 0 0 !important;
      }

      .hero-hand-wrapper {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        justify-content: center !important;
        align-items: flex-end !important;
      }

      .hero-hand-img {
        width: 100% !important;
        max-width: 100% !important;
        height: auto !important;
        margin: 0 !important;
        padding: 0 !important;
        display: block !important;
        border-radius: 0 !important;
        object-fit: cover !important;
      }

      /* General Mobile Section Polish */
      .section-title {
        font-size: 1.9rem !important;
        line-height: 1.25 !important;
        letter-spacing: -0.5px !important;
      }

      .section-desc {
        font-size: 0.95rem !important;
        line-height: 1.6 !important;
      }

      /* How Section Mobile Polish */
      .how {
        padding: 3.5rem 1.25rem !important;
      }

      .how-container {
        grid-template-columns: 1fr !important;
        gap: 2.25rem !important;
        width: 100% !important;
      }

      .how-left-content {
        text-align: center !important;
        align-items: center !important;
      }

      .how-left-content h2 {
        font-size: 32px !important;
        line-height: 1.15 !important;
        letter-spacing: -2px !important;
        margin-bottom: 1.5rem !important;
      }

      .cyan-highlight-big {
        font-size: 36px !important;
      }

      .how-phones-wrapper {
        width: 100% !important;
        max-width: 290px !important;
        height: 290px !important;
        margin: 0.5rem auto 0 auto !important;
        position: relative !important;
      }

      .how-phone-device {
        width: 150px !important;
      }

      .how-phone-device.phone-left {
        top: 20px !important;
        left: 0px !important;
      }

      .how-phone-device.phone-right {
        top: 10px !important;
        left: 135px !important;
      }

      .how-step-cards {
        gap: 1rem !important;
        width: 100% !important;
      }

      .how-card {
        padding: 1.25rem 1.25rem !important;
        border-radius: 20px !important;
        gap: 1.25rem !important;
        background: rgba(255, 255, 255, 0.95) !important;
        border: 1px solid rgba(2, 181, 203, 0.2) !important;
        box-shadow: 0 8px 20px rgba(15, 42, 58, 0.05) !important;
      }

      .how-card-num {
        font-size: 3.6rem !important;
        line-height: 1 !important;
      }

      .how-card-arrow svg {
        width: 18px !important;
        height: 18px !important;
      }

      .how-card-text-side h3 {
        font-size: 1.25rem !important;
        line-height: 1.25 !important;
        letter-spacing: -0.5px !important;
        margin-bottom: 0.35rem !important;
      }

      .how-card-text-side p {
        font-size: 0.9rem !important;
        line-height: 1.5 !important;
        color: #475569 !important;
      }

      /* Solutions Section Mobile Polish */
      .solutions {
        padding: 3.5rem 1rem !important;
      }

      .solutions-title {
        font-size: 28px !important;
        line-height: 1.2 !important;
        letter-spacing: -1.5px !important;
        text-align: center !important;
        margin-bottom: 1.25rem !important;
      }

      .solutions-tabs {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        flex-wrap: wrap !important;
        gap: 0.5rem !important;
        padding: 0.25rem 0.5rem 1rem !important;
        margin-bottom: 1.25rem !important;
        width: 100% !important;
        max-width: 100% !important;
        text-align: center !important;
      }

      .sol-tab {
        flex-shrink: 0 !important;
        font-size: 0.85rem !important;
        padding: 0.6rem 1.15rem !important;
        border-radius: 100px !important;
      }

      .solutions-card {
        grid-template-columns: 1fr !important;
        padding: 1.5rem 1.25rem !important;
        border-radius: 22px !important;
        gap: 1.25rem !important;
        width: 100% !important;
        box-shadow: 0 12px 30px rgba(11, 142, 163, 0.08) !important;
      }

      .sol-tagline {
        font-size: 1.55rem !important;
        line-height: 1.2 !important;
        letter-spacing: -1px !important;
        margin-bottom: 0.4rem !important;
        text-align: left !important;
      }

      .sol-text {
        font-size: 0.92rem !important;
        line-height: 1.5 !important;
        color: #475569 !important;
        margin-bottom: 1rem !important;
        text-align: left !important;
      }

      .sol-benefits {
        display: none !important;
      }

      .sol-features-right {
        display: flex !important;
        flex-direction: column !important;
        gap: 0.85rem !important;
        width: 100% !important;
      }

      .sol-feature-item {
        display: flex !important;
        align-items: flex-start !important;
        gap: 0.85rem !important;
        padding: 0.9rem !important;
        background: rgba(248, 250, 252, 0.85) !important;
        border: 1px solid rgba(226, 232, 240, 0.9) !important;
        border-radius: 16px !important;
      }

      .sol-feature-icon {
        width: 36px !important;
        height: 36px !important;
        min-width: 36px !important;
        border-radius: 10px !important;
        background: rgba(2, 181, 203, 0.1) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 1rem !important;
      }

      .sol-feature-name {
        font-size: 0.95rem !important;
        font-weight: 700 !important;
        color: #0f2a3a !important;
        margin-bottom: 0.2rem !important;
      }

      .sol-feature-desc {
        font-size: 0.85rem !important;
        color: #64748b !important;
        line-height: 1.45 !important;
      }

      .sol-btn {
        width: 100% !important;
        justify-content: center !important;
        text-align: center !important;
        margin-top: 1rem !important;
        padding: 0.85rem 1.25rem !important;
        font-size: 0.95rem !important;
        border-radius: 100px !important;
      }

      /* Pricing Section Mobile */
      .pricing {
        padding: 3.5rem 1.25rem !important;
      }

      .pricing-grid {
        grid-template-columns: 1fr !important;
        gap: 1.5rem !important;
        max-width: 420px !important;
        margin: 0 auto !important;
      }

      .plan {
        padding: 2rem 1.5rem !important;
        border-radius: 24px !important;
      }

      .plan-savings-spacer {
        display: none !important;
      }

      .plan-features {
        margin-bottom: 1.5rem !important;
        flex: none !important;
      }

      .plan-trial-note {
        margin-top: 0.5rem !important;
      }

      /* CTA Banner Mobile Polish */
      .feature-banner {
        padding: 3.5rem 1.25rem !important;
      }

      .feature-banner-container {
        width: 100% !important;
        max-width: 100% !important;
        padding: 0 !important;
      }

      .feature-banner-text {
        text-align: center !important;
        align-items: center !important;
        padding: 0 !important;
      }

      .feature-banner-text h2 {
        font-size: 34px !important;
        line-height: 1.15 !important;
        letter-spacing: -2px !important;
        margin-bottom: 0.85rem !important;
      }

      .feature-banner-text h2 span {
        letter-spacing: -2px !important;
      }

      .feature-banner-text p {
        font-size: 0.95rem !important;
        line-height: 1.55 !important;
        margin-bottom: 1.75rem !important;
        padding: 0 0.5rem !important;
      }

      .feature-banner-text p br {
        display: none !important;
      }

      .feature-banner-actions {
        flex-direction: column !important;
        width: 100% !important;
        max-width: 320px !important;
        gap: 0.75rem !important;
        margin: 0 auto !important;
      }

      .btn-cta-dark,
      .btn-cta-cyan {
        width: 100% !important;
        justify-content: center !important;
        text-align: center !important;
        padding: 0.9rem 1.5rem !important;
        font-size: 0.98rem !important;
        border-radius: 100px !important;
        display: flex !important;
        align-items: center !important;
      }

      footer {
        padding: 3rem 1.25rem 2rem !important;
      }

      .footer-grid {
        grid-template-columns: 1fr !important;
        gap: 2rem !important;
        text-align: center !important;
      }

      .footer-brand,
      .footer-links-col {
        align-items: center !important;
        text-align: center !important;
      }
    }

    /* ── MODERN BACKDROP OVERLAY & SLIDING DRAWER MENU ── */
    .mobile-menu-overlay {
      position: fixed;
      inset: 0;
      background: rgba(11, 34, 48, 0.45);
      backdrop-filter: blur(6px);
      -webkit-backdrop-filter: blur(6px);
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.35s ease, visibility 0.35s ease;
      z-index: 1999;
    }

    .mobile-menu-overlay.active {
      opacity: 1;
      visibility: visible;
    }

    .mobile-menu {
      position: fixed;
      top: 0;
      right: -100%;
      width: 85vw;
      max-width: 320px;
      height: 100vh;
      height: 100dvh;
      background: rgba(255, 255, 255, 0.97);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      z-index: 2000;
      padding: 1.5rem 1.5rem 2rem;
      transition: transform 0.38s cubic-bezier(0.16, 1, 0.3, 1), right 0.38s cubic-bezier(0.16, 1, 0.3, 1);
      box-shadow: -10px 0 40px rgba(0, 0, 0, 0.15);
      display: flex;
      flex-direction: column;
      border-left: 1px solid rgba(226, 232, 240, 0.8);
      overflow-y: auto;
    }

    .mobile-menu.active {
      right: 0;
    }

    .mobile-menu-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding-bottom: 1.25rem;
      border-bottom: 1px solid rgba(226, 232, 240, 0.8);
      margin-bottom: 1.5rem;
    }

    .mobile-menu-header img {
      height: 26px;
      width: auto;
    }

    .mobile-menu-close {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: rgba(15, 42, 58, 0.06);
      border: none;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      color: var(--navy);
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .mobile-menu-close:hover,
    .mobile-menu-close:active {
      background: rgba(15, 42, 58, 0.12);
      transform: scale(1.05);
    }

    .mobile-nav-links {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      margin: 0;
      padding: 0;
    }

    .mobile-nav-links a {
      text-decoration: none;
      font-size: 1rem;
      font-weight: 600;
      color: var(--navy);
      font-family: 'Manrope', sans-serif;
      display: flex;
      align-items: center;
      padding: 0.75rem 1rem;
      border-radius: 12px;
      transition: all 0.2s ease;
    }

    .mobile-nav-links a:hover,
    .mobile-nav-links a:active {
      background: rgba(2, 181, 203, 0.08);
      color: #02b5cb;
      transform: translateX(4px);
    }

    .mobile-auth {
      margin-top: auto !important;
      padding-top: 1.5rem;
      border-top: 1px solid rgba(226, 232, 240, 0.8);
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
    }

    .plan-savings-spacer {
      height: 12px;
      margin-bottom: 3.5rem;
      display: block;
    }
  </style>
</head>

<body>

  {{-- Onboarding Carousel for Guest Users --}}
  @include('components.onboarding-carousel')

  <!-- NAV -->
  <nav>
    <a href="#" class="nav-logo">
      <div class="nav-logo-wrap">
        <img src="{{ asset('images/logo-viantryp-cyan.png') }}" alt="Viantryp" class="nav-logo-img logo-cyan">
        <img src="{{ asset('images/logo-viantryp-black.png') }}" alt="Viantryp" class="nav-logo-img logo-black">
      </div>
    </a>
    <ul class="nav-links">
      <li><a href="#como-funciona">Cómo funciona</a></li>
      <li><a href="#precios">Precios</a></li>
      <li><a href="{{ route('contact') }}">Contacto</a></li>
    </ul>
    <div class="nav-right">
      @auth
        <div class="nav-auth-container" style="display: flex; align-items: center; gap: 1.25rem;">
          <a href="{{ route('trips.index') }}" class="nav-login">Ir a Mis Viajes</a>

          <div class="user-profile-dropdown" style="position: relative;">
            <div id="profileTrigger" style="display: flex; align-items: center; gap: 0.3rem; cursor: pointer;">
              <span style="font-size: 12px; font-weight: 600; color: var(--navy);">
                {{ auth()->user()->name }}
              </span>
              <div
                style="width: 36px; height: 36px; border-radius: 50%; background-color: var(--teal); color: var(--white); display: flex; align-items: center; justify-content: center; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1rem; text-decoration: none; border: 2px solid var(--teal-light); transition: transform 0.2s; overflow: hidden;">
                @if(auth()->user()->avatar)
                  <img
                    src="{{ str_starts_with(auth()->user()->avatar, 'http') ? auth()->user()->avatar : asset('storage/' . auth()->user()->avatar) }}"
                    style="width: 100%; height: 100%; object-fit: cover;">
                @else
                  {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                @endif
              </div>
            </div>

            <div id="profileMenu" class="dropdown-menu-content"
              style="display: none; position: absolute; top: calc(100% + 10px); right: 0; background: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 180px; overflow: hidden; z-index: 1000; border: 1px solid var(--mid-gray); text-align: left;">
              <a href="{{ route('trips.index') }}" class="dropdown-item"
                style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: var(--text); text-decoration: none; font-size: 13px; font-weight: 500; transition: background 0.2s;">
                <i class="fas fa-suitcase-rolling" style="color: #64748b; font-size: 15px;"></i>
                Mis viajes
              </a>
              <div style="height: 1px; background: var(--mid-gray);"></div>
              <a href="{{ route('profile.index') }}" class="dropdown-item"
                style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: var(--text); text-decoration: none; font-size: 13px; font-weight: 500; transition: background 0.2s;">
                <i class="fas fa-user-circle" style="color: #64748b; font-size: 15px;"></i>
                Mi perfil
              </a>
              <div style="height: 1px; background: var(--mid-gray);"></div>
              <form method="POST" action="{{ route('logout') }}" id="logout-form" style="margin: 0;">
                @csrf
                <button type="submit" class="dropdown-item"
                  style="width: 100%; border: none; background: transparent; display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: #c0392b; cursor: pointer; text-align: left; font-size: 13px; font-weight: 500; transition: background 0.2s; font-family: 'DM Sans', sans-serif;">
                  <i class="fas fa-sign-out-alt" style="font-size: 15px;"></i>
                  Cerrar sesión
                </button>
              </form>
            </div>
          </div>
        </div>

        <script>
          (function () {
            const initMenu = () => {
              const trigger = document.getElementById('profileTrigger');
              const menu = document.getElementById('profileMenu');

              if (trigger && menu) {
                trigger.addEventListener('click', function (e) {
                  e.stopPropagation();
                  const isVisible = menu.style.display === 'block';
                  menu.style.display = isVisible ? 'none' : 'block';
                });
              }

              document.addEventListener('click', function (e) {
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
            if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initMenu);
            else initMenu();
          })();
        </script>
      @else
        <a href="{{ route('login') }}" class="nav-login">Iniciar sesión</a>
        <a href="{{ route('register') }}" class="nav-cta">Comenzar gratis</a>
      @endauth
      <button class="mobile-menu-toggle" id="mobileMenuBtn">
        <span></span><span></span><span></span>
      </button>
    </div>
  </nav>

  <!-- HERO -->
  <section class="hero" id="hero-section">
    <div class="hero-bg">
      <div class="hero-bg-glow-left"></div>
      <div class="hero-bg-glow-right"></div>
      <div class="hero-bg-dots"></div>
    </div>

    <div class="hero-container">
      <div class="hero-text-side">
        <h1 class="reveal-left d1">Diseña tus viajes<br>en cuestión<br>de <span class="cyan-highlight">minutos</span>
        </h1>
        <p class="reveal-left d2">Organiza rutas, vuelos y estancias en una plataforma simple e intuitiva.<span
            class="hero-subtext-desktop"> Ya sea para tu próximo viaje o para escalar tu negocio, Viantryp es donde tus
            itinerarios cobran vida.</span></p>
        <div class="hero-actions reveal-left d3">
          @auth
            <a href="{{ route('trips.index') }}" class="btn-pill-primary">Ir a mis viajes →</a>
          @else
            <a href="{{ route('register') }}" class="btn-pill-primary">Empezar Ahora</a>
            <a href="#demo" class="btn-pill-secondary">Ver Demo</a>
          @endauth
        </div>
      </div>

      <div class="feature-banner-visual">
        <div class="hero-hand-wrapper reveal-hand-entrance">
          <img src="{{ asset('images/hero-hand-mockup.png') }}" alt="Viantryp Mobile App" class="hero-hand-img">
        </div>
      </div>
    </div>
  </section>

  <!-- DEMO SECTION WITH STANDALONE MOCKUP -->
  <section class="demo-section" id="demo">
    <div class="container"
      style="max-width: 1080px; width: 100%; position: relative; z-index: 2; display: flex; flex-direction: column; justify-content: center;">
      <!-- Title & Text Outside Mockup -->
      <div class="reveal" style="text-align: center; margin-top: 0; margin-bottom: 1.8rem;">
        <div class="section-label"
          style="color: #4de2f4; font-size: 0.85rem; letter-spacing: 0.15em; font-weight: 800; margin-bottom: 0.2rem; text-transform: uppercase;">
          Demo Interactiva</div>
        <p
          style="color: rgba(255, 255, 255, 0.92); font-size: 13px; line-height: 1.4; max-width: 680px; margin: 0 auto; font-weight: 500;">
          Prueba nuestra interfaz: arrastra servicios, organizalos y descubre la facilidad de crear itinerarios en
          segundos.
        </p>
      </div>

      <!-- LAPTOP MOCKUP STANDALONE WITH SCROLL EXPAND -->
      <div class="laptop-wrap reveal d2 demo-laptop-expandable"
        style="transform: none; max-width: 1020px; width: 100%; margin: 0 auto; filter: drop-shadow(0 25px 50px rgba(0,0,0,0.45)); transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1), filter 0.6s ease;">
        <div class="l-screen" style="padding: 4px 4px 0 4px;">
          <div class="l-notch">
            <div class="l-cam"></div>
          </div>
          <div class="l-display" style="aspect-ratio: auto; height: clamp(520px, 68vh, 620px);">
            <div class="l-glare"></div>

            <div class="vt-root" style="height: 100%; border-radius: 0;">
              <div class="vt-topbar">
                <div class="vt-topbar-bg-decorators"></div>
                <div class="vt-topbar-left" style="display: flex; align-items: center; gap: 10px; z-index: 1;">
                  <img src="{{ asset('images/logo-viantryp.png') }}" alt="Viantryp"
                    style="height: 18px; width: auto; filter: brightness(0) invert(1);">
                </div>
                <div class="vt-topbar-center"
                  style="position: absolute; left: 50%; transform: translateX(-50%); z-index: 1;">
                  <span class="vt-demo-badge"
                    style="background: transparent; color: #ffffff; border: none; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; display: inline-flex; align-items: center; gap: 5px;">
                    Versión Demo
                  </span>
                </div>
                <div class="vt-topbar-actions" style="margin-left: auto; z-index: 1;">
                  <button class="vt-preview-btn" id="vtPreviewBtn">
                    <i class="fa-solid fa-eye"></i> <span>Vista previa</span>
                  </button>
                </div>
              </div>

              <div class="vt-body">
                <!-- SIDEBAR (EDITOR MODE) -->
                <div class="vt-sidebar">
                  <div class="vt-sidebar-scroll">
                    <div class="vt-sidebar-section">
                      <div class="vt-section-label">Servicios</div>
                      <div class="vt-element-grid">
                        <div class="vt-element-card type-flight" draggable="true" data-type="Vuelo"
                          onclick="vtAddElement(this.dataset.type)">
                          <div class="vt-el-drag-handle"><i class="fa-solid fa-ellipsis-vertical"></i><i
                              class="fa-solid fa-ellipsis-vertical"></i></div>
                          <div class="vt-el-icon"><i class="fa-solid fa-plane"></i></div>
                          <div class="vt-el-info">
                            <div class="vt-el-name">Vuelo</div>
                            <div class="vt-el-sub">Agregar vuelo</div>
                          </div>
                        </div>
                        <div class="vt-element-card type-alojamiento" draggable="true" data-type="Alojamiento"
                          onclick="vtAddElement(this.dataset.type)">
                          <div class="vt-el-drag-handle"><i class="fa-solid fa-ellipsis-vertical"></i><i
                              class="fa-solid fa-ellipsis-vertical"></i></div>
                          <div class="vt-el-icon"><i class="fa-solid fa-hotel"></i></div>
                          <div class="vt-el-info">
                            <div class="vt-el-name">Alojamiento</div>
                            <div class="vt-el-sub">Hotel u hospedaje</div>
                          </div>
                        </div>
                        <div class="vt-element-card type-actividad" draggable="true" data-type="Actividad"
                          onclick="vtAddElement(this.dataset.type)">
                          <div class="vt-el-drag-handle"><i class="fa-solid fa-ellipsis-vertical"></i><i
                              class="fa-solid fa-ellipsis-vertical"></i></div>
                          <div class="vt-el-icon"><i class="fa-solid fa-compass"></i></div>
                          <div class="vt-el-info">
                            <div class="vt-el-name">Actividad</div>
                            <div class="vt-el-sub">Tours o experiencias</div>
                          </div>
                        </div>
                        <div class="vt-element-card type-transporte" draggable="true" data-type="Traslado"
                          onclick="vtAddElement(this.dataset.type)">
                          <div class="vt-el-drag-handle"><i class="fa-solid fa-ellipsis-vertical"></i><i
                              class="fa-solid fa-ellipsis-vertical"></i></div>
                          <div class="vt-el-icon"><i class="fa-solid fa-car"></i></div>
                          <div class="vt-el-info">
                            <div class="vt-el-name">Traslado</div>
                            <div class="vt-el-sub">Bus, tren u otro</div>
                          </div>
                        </div>
                        <div class="vt-element-card type-comida" draggable="true" data-type="Comida"
                          onclick="vtAddElement(this.dataset.type)">
                          <div class="vt-el-drag-handle"><i class="fa-solid fa-ellipsis-vertical"></i><i
                              class="fa-solid fa-ellipsis-vertical"></i></div>
                          <div class="vt-el-icon"><i class="fa-solid fa-utensils"></i></div>
                          <div class="vt-el-info">
                            <div class="vt-el-name">Comida</div>
                            <div class="vt-el-sub">Restaurante y más</div>
                          </div>
                        </div>
                        <div class="vt-element-card type-documentos" draggable="true" data-type="Documentos"
                          onclick="vtAddElement(this.dataset.type)">
                          <div class="vt-el-drag-handle"><i class="fa-solid fa-ellipsis-vertical"></i><i
                              class="fa-solid fa-ellipsis-vertical"></i></div>
                          <div class="vt-el-icon"><i class="fa-solid fa-file-lines"></i></div>
                          <div class="vt-el-info">
                            <div class="vt-el-name">Documentos</div>
                            <div class="vt-el-sub">Pasaportes, PDF y más</div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="vt-sidebar-section">
                      <div class="vt-section-label">Diseño</div>
                      <div class="vt-element-grid">
                        <div class="vt-element-card type-titulo" draggable="true" data-type="Título"
                          onclick="vtAddElement(this.dataset.type)">
                          <div class="vt-el-drag-handle"><i class="fa-solid fa-ellipsis-vertical"></i><i
                              class="fa-solid fa-ellipsis-vertical"></i></div>
                          <div class="vt-el-icon" style="font-size:15px;font-family:'Poppins';font-weight:800">T</div>
                          <div class="vt-el-info">
                            <div class="vt-el-name">Título</div>
                            <div class="vt-el-sub">Encabezado</div>
                          </div>
                        </div>
                        <div class="vt-element-card type-texto" draggable="true" data-type="Texto"
                          onclick="vtAddElement(this.dataset.type)">
                          <div class="vt-el-drag-handle"><i class="fa-solid fa-ellipsis-vertical"></i><i
                              class="fa-solid fa-ellipsis-vertical"></i></div>
                          <div class="vt-el-icon" style="font-size:12px;font-family:'Poppins';">Aa</div>
                          <div class="vt-el-info">
                            <div class="vt-el-name">Texto</div>
                            <div class="vt-el-sub">Párrafo</div>
                          </div>
                        </div>
                        <div class="vt-element-card type-separador" draggable="true" data-type="Separador"
                          onclick="vtAddElement(this.dataset.type)">
                          <div class="vt-el-drag-handle"><i class="fa-solid fa-ellipsis-vertical"></i><i
                              class="fa-solid fa-ellipsis-vertical"></i></div>
                          <div class="vt-el-icon" style="font-size:9px;">— ✦ —</div>
                          <div class="vt-el-info">
                            <div class="vt-el-name">Separador</div>
                            <div class="vt-el-sub">Momento del día</div>
                          </div>
                        </div>
                        <div class="vt-element-card type-caja" draggable="true" data-type="Caja"
                          onclick="vtAddElement(this.dataset.type)">
                          <div class="vt-el-drag-handle"><i class="fa-solid fa-ellipsis-vertical"></i><i
                              class="fa-solid fa-ellipsis-vertical"></i></div>
                          <div class="vt-el-icon"><i class="fa-solid fa-lightbulb"></i></div>
                          <div class="vt-el-info">
                            <div class="vt-el-name">Nota</div>
                            <div class="vt-el-sub">Tip o recomendación</div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="vt-sidebar-section">
                      <div class="vt-section-label">Detalles</div>
                      <div class="vt-element-grid">
                        <div class="vt-element-card type-imagen" draggable="true" data-type="Imagen"
                          onclick="vtAddElement(this.dataset.type)">
                          <div class="vt-el-drag-handle"><i class="fa-solid fa-ellipsis-vertical"></i><i
                              class="fa-solid fa-ellipsis-vertical"></i></div>
                          <div class="vt-el-icon"><i class="fa-regular fa-image"></i></div>
                          <div class="vt-el-info">
                            <div class="vt-el-name">Imagen</div>
                            <div class="vt-el-sub">Foto o Unsplash</div>
                          </div>
                        </div>
                        <div class="vt-element-card type-gif" draggable="true" data-type="Gif"
                          onclick="vtAddElement(this.dataset.type)">
                          <div class="vt-el-drag-handle"><i class="fa-solid fa-ellipsis-vertical"></i><i
                              class="fa-solid fa-ellipsis-vertical"></i></div>
                          <div class="vt-el-icon" style="color:#ce3df3;background:#f9f0ff"><i
                              class="fa-solid fa-bolt"></i></div>
                          <div class="vt-el-info">
                            <div class="vt-el-name">GIF</div>
                            <div class="vt-el-sub">Buscar en Giphy</div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- TRAVELER SIDEBAR (VISIBLE ONLY IN PREVIEW) -->
                <div class="vt-preview-sidebar">
                  <div class="vt-preview-sidebar-label">ITINERARIO</div>
                  <div id="vtPreviewDaysList" class="vt-preview-days-list"></div>
                </div>

                <!-- MAIN CANVAS AREA -->
                <div class="vt-main-wrap">
                  <!-- PREVIEW HERO (PUBLIC LINK VIEW) -->
                  <div class="vt-preview-hero">
                    <div class="vt-preview-hero-inner">
                      <div class="vt-preview-hero-location"
                        style="font-size: 11px; font-weight: 600; color: rgba(255, 255, 255, 0.9); margin-bottom: 4px; display: flex; align-items: center; gap: 5px;">
                        <i class="fa-solid fa-location-dot" style="font-size: 10px; color: #4de2f4;"></i> Emiratos
                        Árabes Unidos | Dubai · 7 días de aventura
                      </div>
                      <div class="vt-preview-hero-title"
                        style="font-family: 'Manrope', 'Barlow', sans-serif; font-size: 22px; font-weight: 800; color: #ffffff; margin-bottom: 14px; text-shadow: 0 2px 6px rgba(0,0,0,0.4);">
                        Viaje a Dubai</div>
                      <div class="vt-preview-hero-stats"
                        style="display: grid; grid-template-columns: 1fr 1fr 1fr; background: rgba(10, 24, 38, 0.82); backdrop-filter: blur(12px); border-top: 1px solid rgba(255, 255, 255, 0.12); padding: 10px 0; border-radius: 0 0 12px 12px; margin: 0 -14px 0 -14px;">
                        <div class="stat" style="text-align: center; border-right: 1px solid rgba(255, 255, 255, 0.1);">
                          <div
                            style="font-size: 9px; font-weight: 700; color: rgba(255, 255, 255, 0.65); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 2px;">
                            <i class="fa-regular fa-calendar-days" style="margin-right: 3px;"></i> FECHAS
                          </div>
                          <strong style="font-size: 11px; font-weight: 700; color: #ffffff;">11 may → 17 may</strong>
                        </div>
                        <div class="stat" style="text-align: center; border-right: 1px solid rgba(255, 255, 255, 0.1);">
                          <div
                            style="font-size: 9px; font-weight: 700; color: rgba(255, 255, 255, 0.65); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 2px;">
                            <i class="fa-solid fa-user-group" style="margin-right: 3px;"></i> VIAJEROS
                          </div>
                          <strong style="font-size: 11px; font-weight: 700; color: #ffffff;">2 personas</strong>
                        </div>
                        <div class="stat" style="text-align: center;">
                          <div
                            style="font-size: 9px; font-weight: 700; color: rgba(255, 255, 255, 0.65); text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 2px;">
                            <i class="fa-solid fa-wallet" style="margin-right: 3px;"></i> TOTAL
                          </div>
                          <strong style="font-size: 11px; font-weight: 700; color: #ffffff;">$7.581 USD</strong>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="vt-main">
                    <div class="vt-toolbar">
                      <div class="vt-tabs-row" id="vtTabsContainer">
                        <button class="vt-tab active" id="vtTab0" onclick="vtSwitch(0)">Día 1 <span
                            class="vt-tab-x">✕</span></button>
                        <button class="vt-tab" id="vtTab1" onclick="vtSwitch(1)">Día 2 <span
                            class="vt-tab-x">✕</span></button>
                        <button class="vt-tab" id="vtTab2" onclick="vtSwitch(2)">Día 3 <span
                            class="vt-tab-x">✕</span></button>
                        <button class="vt-tab-add" onclick="vtAddDay()">+ Día</button>
                      </div>
                      <div class="vt-toolbar-spacer"></div>
                      <span class="vt-item-count" id="vtCount">0 elementos</span>
                    </div>
                    <div class="vt-canvas" id="vtCanvas" ondragover="vtHandleDragOver(event)"
                      ondragleave="vtHandleDragLeave(event)" ondrop="vtDrop(event)">
                      <div class="vt-canvas-inner">
                        <div class="vt-empty" id="vtEmpty" style="display:none">
                          <div class="vt-empty-icon"><i class="fa-solid fa-map-location-dot"></i></div>
                          <div class="vt-empty-title">Tu itinerario está vacío</div>
                          <div class="vt-empty-sub">Arrastra elementos desde el panel izquierdo para comenzar</div>
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
          </div> <!-- .l-display -->
        </div> <!-- .l-screen -->
        <div class="l-base">
          <div class="l-hinge"></div>
          <div class="l-deck">
            <div class="l-keyline"></div>
            <div class="l-thumb-indent"></div>
          </div>
        </div> <!-- .l-base -->
        <div class="l-shadow"></div>
      </div> <!-- .laptop-wrap -->
    </div> <!-- .container -->
  </section>

  <!-- HOW IT WORKS -->
  <section class="how" id="como-funciona">
    <div class="how-container">
      <div class="how-left-content reveal-right">
        <div class="how-overline">Proceso</div>
        <h2>Plasma tu gran viaje<br>en solo <span class="cyan-highlight-big">3 pasos</span></h2>

        <div class="how-phones-wrapper">
          <!-- Phone 1 (Left Screen - Image 1) -->
          <div class="how-phone-device phone-left">
            <div class="p-frame">
              <div class="p-island"></div>
              <div class="p-screen">
                <div class="p-glare"></div>
                <img src="{{ asset('images/how-phone-left.jpg') }}" alt="Viantryp Mobile Preview 1"
                  class="p-screen-img">
              </div>
            </div>
          </div>

          <!-- Phone 2 (Right Screen - Image 2) -->
          <div class="how-phone-device phone-right">
            <div class="p-frame">
              <div class="p-island"></div>
              <div class="p-screen">
                <div class="p-glare"></div>
                <img src="{{ asset('images/how-phone-right.jpg') }}" alt="Viantryp Mobile Preview 2"
                  class="p-screen-img" style="width: 110%; max-width: 110%;">
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="how-step-cards">
        <!-- Step 1 -->
        <div class="how-card reveal-right d1">
          <div class="how-card-num-side">
            <div class="how-card-arrow">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                stroke-linecap="round" stroke-linejoin="round">
                <line x1="7" y1="17" x2="17" y2="7"></line>
                <polyline points="7 7 17 7 17 17"></polyline>
              </svg>
            </div>
            <div class="how-card-num">1</div>
          </div>
          <div class="how-card-text-side">
            <h3>Construye el itinerario</h3>
            <p>Usa nuestro editor visual para estructurar tu viaje día a día. Arrastra destinos, rutas y fotos con total
              libertad</p>
          </div>
        </div>

        <!-- Step 2 -->
        <div class="how-card reveal-right d2">
          <div class="how-card-num-side">
            <div class="how-card-arrow">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                stroke-linecap="round" stroke-linejoin="round">
                <line x1="7" y1="17" x2="17" y2="7"></line>
                <polyline points="7 7 17 7 17 17"></polyline>
              </svg>
            </div>
            <div class="how-card-num">2</div>
          </div>
          <div class="how-card-text-side">
            <h3>Dale tu toque</h3>
            <p>Personaliza colores, añade documentos importantes o tu marca personal. Haz que cada itinerario cuente una
              historia única.</p>
          </div>
        </div>

        <!-- Step 3 -->
        <div class="how-card reveal-right d3">
          <div class="how-card-num-side">
            <div class="how-card-arrow">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                stroke-linecap="round" stroke-linejoin="round">
                <line x1="7" y1="17" x2="17" y2="7"></line>
                <polyline points="7 7 17 7 17 17"></polyline>
              </svg>
            </div>
            <div class="how-card-num">3</div>
          </div>
          <div class="how-card-text-side">
            <h3>Llévalo contigo</h3>
            <p>Comparte un solo enlace inteligente. Sin archivos pesados ni apps extra; toda la información accesible
              desde cualquier lugar.</p>
          </div>
        </div>
      </div>
    </div>
  </section>





  <!-- QUOTE -->
  <section class="quote-section">
    <svg class="quote-pattern-left" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
      <circle cx="40" cy="100" r="90" stroke="white" stroke-width="1.2" stroke-dasharray="4 2" />
      <circle cx="40" cy="100" r="130" stroke="white" stroke-width="1" />
      <circle cx="40" cy="100" r="55" stroke="white" stroke-width="1.2" />
      <circle cx="10" cy="80" r="4" fill="white" />
      <circle cx="85" cy="135" r="5" fill="white" />
      <line x1="10" y1="80" x2="85" y2="135" stroke="white" stroke-width="1" />
    </svg>

    <svg class="quote-pattern-right" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
      <circle cx="150" cy="150" r="45" stroke="white" stroke-width="1.5" />
      <circle cx="150" cy="150" r="85" stroke="white" stroke-width="1.5" />
      <circle cx="150" cy="150" r="125" stroke="white" stroke-width="1.5" />
      <circle cx="150" cy="150" r="9" fill="white" />
    </svg>

    <div class="quote-container">
      <div class="reveal">
        <h2 class="big-quote">"Transformamos un proceso que normalmente toma horas en una tarea que se completa en
          minutos."</h2>
        <div class="quote-author">Equipo Viantryp</div>
      </div>
    </div>
  </section>


  <!-- SOLUTIONS -->
  <section class="solutions" id="soluciones">
    <div class="container"
      style="max-width: 1180px; width: 100%; margin: 0 auto; display: flex; flex-direction: column; justify-content: center;">
      <div class="solutions-header reveal">
        <h2 class="solutions-title"><span>Viantryp:</span> Adáptalo a todo tipo de viaje</h2>
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
          <p class="sol-text">Crea rutas perfectas en minutos y lleva todo tu viaje en la palma de tu mano, siempre
            actualizado.</p>
          <ul class="sol-benefits">
            <li><i class="fas fa-check"></i> Planifica sin estrés manteniendo todo bajo control en un solo lienzo
              digital.</li>
            <li><i class="fas fa-check"></i> Disfruta de un diseño que evoluciona junto a tus ideas.</li>
            <li><i class="fas fa-check"></i> Actualiza tu viaje sin rehacer documentos.</li>
          </ul>
        </div>

        <div class="sol-features-right" id="sol-right">
          <div class="sol-feature-item">
            <div class="sol-feature-icon" style="color: #02b5cb;">
              <i class="fas fa-pencil-ruler"></i>
            </div>
            <div class="sol-feature-info">
              <div class="sol-feature-name">Editor visual <span>Drag & Drop</span></div>
              <div class="sol-feature-desc">Arrastra destinos y fotos para diseñar tu ruta ideal en segundos. Es tan
                fácil como jugar, pero con resultados profesionales.</div>
            </div>
          </div>
          <div class="sol-feature-item">
            <div class="sol-feature-icon" style="color: #1EAACE;">
              <i class="fas fa-link"></i>
            </div>
            <div class="sol-feature-info">
              <div class="sol-feature-name">Enlace interactivo <span>personal</span></div>
              <div class="sol-feature-desc">Lleva todo tu plan en un solo link. Si cambias de opinión sobre un lugar,
                actualízalo y ten tu ruta siempre al día en tu móvil.</div>
            </div>
          </div>
          <div class="sol-feature-item">
            <div class="sol-feature-icon" style="color: #0A2540;">
              <i class="fas fa-file-invoice"></i>
            </div>
            <div class="sol-feature-info">
              <div class="sol-feature-name">Toda tu documentación <span>a mano</span></div>
              <div class="sol-feature-desc">Guarda tus reservas y mapas directamente en el día que corresponden. Olvida
                buscar entre cientos de correos y capturas de pantalla.</div>
            </div>
          </div>
        </div>

        <a href="#precios" class="sol-btn">Explorar soluciones →</a>
      </div>
    </div>
  </section>


  <style>
    /* ── HAND HOLDING LAPTOP DEMO STAGE ── */
    .hand-laptop-stage {
      position: relative;
      width: 100%;
      max-width: 1040px;
      margin: 0 auto;
      border-radius: 12px;
    }

    .hand-laptop-bg {
      width: 100%;
      height: auto;
      display: block;
      filter: drop-shadow(0 30px 60px rgba(0, 0, 0, 0.45));
    }

    .hand-laptop-screen {
      position: absolute;
      top: 10.3%;
      left: 31.0%;
      width: 40.8%;
      height: 47.8%;
      border-radius: 4px 4px 0 0;
      overflow: hidden;
      background: #f5f7fa;
      box-shadow: inset 0 0 0 1px rgba(0, 0, 0, 0.3);
    }

    .hand-laptop-screen .mockup-inner {
      width: 100%;
      height: 100%;
      overflow: hidden;
    }

    .hand-laptop-screen .vt-root {
      height: 100%;
      border-radius: 0;
    }

    /* ── PRO EDITOR & PUBLIC LINK DEMO STYLES ── */
    .vt-root {
      font-family: 'Inter', 'Barlow', sans-serif;
      font-size: 13px;
      color: #1a2e2c;
      background: #f4f5f9;
      border-radius: 0;
      overflow: hidden;
      border: none;
      display: flex;
      flex-direction: column;
      height: 100%;
      text-align: left;
      position: relative;
    }

    .vt-topbar {
      background: #0f2a3a;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
      display: flex;
      align-items: center;
      padding: 0 14px;
      gap: 10px;
      height: 44px;
      flex-shrink: 0;
      position: relative;
      overflow: hidden;
    }

    .vt-topbar-bg-decorators {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
      pointer-events: none;
    }

    .vt-topbar-bg-decorators::before {
      content: '';
      position: absolute;
      top: 0;
      right: 90px;
      width: 120px;
      height: 300%;
      background: #1a9a8a;
      transform: skewX(-16deg);
      opacity: 0.08;
    }

    .vt-sidebar-toggle {
      color: rgba(255, 255, 255, 0.85);
      background: rgba(255, 255, 255, 0.1);
      border: none;
      border-radius: 6px;
      width: 28px;
      height: 28px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 11px;
      z-index: 1;
      transition: all 0.2s;
    }

    .vt-sidebar-toggle:hover {
      background: rgba(255, 255, 255, 0.2);
      color: #fff;
    }

    .vt-logo-wrap {
      display: flex;
      align-items: center;
      z-index: 1;
    }

    .vt-topbar-logo-img {
      height: 20px;
      width: auto;
      filter: brightness(0) invert(1);
    }

    .vt-topbar-div {
      width: 1px;
      height: 18px;
      background: rgba(255, 255, 255, 0.15);
      z-index: 1;
    }

    .vt-topbar-actions {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-left: auto;
      z-index: 1;
    }

    .vt-btn-save {
      cursor: pointer;
      background: rgba(255, 255, 255, 0.1);
      color: white;
      border: 1px solid rgba(255, 255, 255, 0.2);
      padding: 4px 12px;
      border-radius: 50px;
      font-size: 11px;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      transition: all 0.15s;
      font-family: 'Barlow', sans-serif;
    }

    .vt-btn-save:hover {
      background: rgba(255, 255, 255, 0.2);
      border-color: white;
    }

    .vt-preview-btn {
      background: rgba(255, 255, 255, 0.15);
      border: 1px solid rgba(255, 255, 255, 0.3);
      color: white;
      padding: 4px 14px;
      border-radius: 50px;
      font-size: 11px;
      font-weight: 600;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.2s;
      font-family: 'Barlow', sans-serif;
    }

    .vt-preview-btn:hover {
      background: #02b5cb !important;
      border-color: #02b5cb !important;
      box-shadow: 0 4px 12px rgba(2, 181, 203, 0.35);
      color: #ffffff !important;
    }

    .vt-body {
      display: flex;
      flex: 1;
      overflow: hidden;
      position: relative;
    }

    /* SIDEBAR EDITOR MODE */
    .vt-sidebar {
      width: 300px;
      background: white;
      border-right: 1px solid #e2e8ef;
      overflow-y: auto;
      flex-shrink: 0;
      padding-bottom: 12px;
    }

    .vt-sidebar::-webkit-scrollbar {
      width: 4px;
    }

    .vt-sidebar::-webkit-scrollbar-thumb {
      background: #e2e8ef;
      border-radius: 4px;
    }

    .vt-sidebar-section {
      padding: 0 4px;
    }

    .vt-section-label {
      font-family: 'Barlow Condensed', sans-serif;
      font-size: 10px;
      font-weight: 800;
      letter-spacing: 1px;
      text-transform: uppercase;
      color: #94a3b8;
      padding: 10px 10px 4px;
    }

    .vt-element-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 6px;
      padding: 0 6px;
    }

    .vt-element-card {
      background: #ffffff;
      border: 1px solid #e2e8ef;
      border-radius: 8px;
      padding: 6px;
      display: flex;
      align-items: center;
      gap: 6px;
      cursor: grab;
      position: relative;
      transition: all 0.15s ease;
      user-select: none;
    }

    .vt-element-card:hover {
      border-color: #02b5cb;
      background: #f0faf9;
      transform: translateY(-1px);
      box-shadow: 0 3px 8px rgba(2, 181, 203, 0.12);
    }

    .vt-element-card:active {
      cursor: grabbing;
      transform: scale(0.97);
    }

    .vt-el-drag-handle {
      position: absolute;
      top: 3px;
      right: 3px;
      display: flex;
      gap: 1px;
      color: #cbd5e1;
      font-size: 7px;
      opacity: 0;
      transition: opacity 0.15s;
    }

    .vt-element-card:hover .vt-el-drag-handle {
      opacity: 1;
    }

    .vt-el-icon {
      width: 26px;
      height: 26px;
      border-radius: 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      background: #f1f5f9;
      color: #475569;
      flex-shrink: 0;
    }

    /* SERVICE COLOR HARMONY */
    .type-vuelo .vt-el-icon,
    .type-flight .vt-el-icon,
    .vt-item-icon.type-vuelo,
    .vt-item-icon.type-flight {
      background: #e0f2fe;
      color: #0284c7;
    }

    .type-alojamiento .vt-el-icon,
    .vt-item-icon.type-alojamiento {
      background: #fef3c7;
      color: #d97706;
    }

    .type-actividad .vt-el-icon,
    .vt-item-icon.type-actividad {
      background: #dcfce7;
      color: #16a34a;
    }

    .type-traslado .vt-el-icon,
    .type-transporte .vt-el-icon,
    .vt-item-icon.type-traslado,
    .vt-item-icon.type-transporte {
      background: #f3e8ff;
      color: #9333ea;
    }

    .type-comida .vt-el-icon,
    .vt-item-icon.type-comida {
      background: #ffe4e6;
      color: #e11d48;
    }

    .type-documentos .vt-el-icon,
    .vt-item-icon.type-documentos {
      background: #e2e8f0;
      color: #475569;
    }

    .type-tour .vt-el-icon,
    .vt-item-icon.type-tour {
      background: #e0f2fe;
      color: #0284c7;
    }

    .type-titulo .vt-el-icon,
    .vt-item-icon.type-titulo,
    .vt-item-icon.type-título {
      background: #f1f5f9;
      color: #1e293b;
    }

    .type-texto .vt-el-icon,
    .vt-item-icon.type-texto {
      background: #f1f5f9;
      color: #475569;
    }

    .type-separador .vt-el-icon,
    .vt-item-icon.type-separador {
      background: #f8fafc;
      color: #64748b;
    }

    .type-caja .vt-el-icon,
    .vt-item-icon.type-caja {
      background: #fef9c3;
      color: #ca8a04;
    }

    .type-imagen .vt-el-icon,
    .vt-item-icon.type-imagen {
      background: #fef3c7;
      color: #d97706;
    }

    .type-gif .vt-el-icon,
    .vt-item-icon.type-gif {
      background: #f3e8ff;
      color: #ce3df3;
    }

    .vt-el-info {
      min-width: 0;
      text-align: left;
    }

    .vt-el-name {
      font-size: 10px;
      font-weight: 700;
      color: #1a2e2c;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      line-height: 1.2;
    }

    .vt-el-sub {
      font-size: 8px;
      color: #94a3b8;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      line-height: 1.2;
      margin-top: 1px;
    }

    /* PREVIEW SIDEBAR */
    .vt-preview-sidebar {
      display: none;
    }

    .vt-root.is-preview .vt-sidebar {
      display: none !important;
    }

    .vt-root.is-preview .vt-preview-sidebar {
      display: flex;
      flex-direction: column;
      width: 170px;
      background: #ffffff;
      border-right: 1px solid #eef2f6;
      flex-shrink: 0;
      padding: 12px 0;
    }

    .vt-preview-sidebar-label {
      font-size: 9px;
      font-weight: 800;
      color: #94a3b8;
      letter-spacing: 1px;
      padding: 6px 16px 10px;
    }

    .vt-preview-day-item {
      padding: 8px 16px;
      font-size: 12px;
      color: #64748b;
      cursor: pointer;
      transition: all 0.2s;
      border-left: 3px solid transparent;
    }

    .vt-preview-day-item:hover {
      background: #f8fafc;
      color: #0f2a3a;
    }

    .vt-preview-day-item.active {
      background: #e6f8fa;
      color: #02b5cb;
      border-left-color: #02b5cb;
      font-weight: 700;
    }

    /* CANVAS WORKSPACE */
    .vt-main-wrap {
      flex: 1;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      background: #f4f5f9;
      position: relative;
    }

    .vt-toolbar {
      background: white;
      border-bottom: 1px solid #e2e8ef;
      display: flex;
      align-items: center;
      gap: 6px;
      padding: 0 10px;
      height: 38px;
      flex-shrink: 0;
    }

    .vt-tabs-row {
      display: flex;
      gap: 4px;
      align-items: center;
    }

    .vt-tab {
      padding: 3px 10px;
      border-radius: 50px;
      font-size: 11px;
      font-weight: 600;
      cursor: pointer;
      color: #64748b;
      background: #f1f5f9;
      border: 1px solid transparent;
      transition: all 0.15s;
      white-space: nowrap;
      font-family: 'Barlow', sans-serif;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .vt-tab:hover {
      background: #e2e8f0;
      color: #1e293b;
    }

    .vt-tab.active {
      background: #02b5cb !important;
      color: #ffffff !important;
      font-weight: 700;
      border-color: #02b5cb !important;
    }

    .vt-tab-x {
      font-size: 10px;
      margin-left: 4px;
      padding: 1px 4px;
      border-radius: 50%;
      opacity: 0.7;
      cursor: pointer;
      transition: all 0.15s;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .vt-tab-x:hover {
      opacity: 1;
      background: rgba(255, 255, 255, 0.25);
      color: #ffffff;
    }

    .vt-tab:not(.active) .vt-tab-x:hover {
      background: rgba(239, 68, 68, 0.15);
      color: #ef4444;
    }

    .vt-tab-add {
      background: transparent;
      border: 1px dashed #cbd5e1;
      color: #64748b;
      padding: 3px 8px;
      border-radius: 50px;
      font-size: 10px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.15s;
    }

    .vt-tab-add:hover {
      background: #f1f5f9;
      border-color: #02b5cb;
      color: #02b5cb;
    }

    .vt-toolbar-spacer {
      flex: 1;
    }

    .vt-item-count {
      font-size: 11px;
      color: #94a3b8;
      font-weight: 500;
    }

    .vt-canvas {
      flex: 1;
      overflow-y: auto;
      padding: 14px;
      display: flex;
      flex-direction: column;
      align-items: center;
      transition: background 0.2s;
    }

    .vt-canvas.vt-over {
      background: #e6f4f1;
    }

    .vt-canvas::-webkit-scrollbar {
      width: 5px;
    }

    .vt-canvas::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 4px;
    }

    .vt-canvas-inner {
      width: 100%;
      max-width: 580px;
    }

    .vt-items {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .vt-item {
      background: white;
      border: 1px solid #e2e8ef;
      border-radius: 10px;
      padding: 8px 12px;
      display: flex;
      align-items: center;
      gap: 10px;
      position: relative;
      transition: all 0.15s;
      animation: vtSlide 0.2s ease;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }

    .vt-item:hover {
      border-color: #cbd5e1;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .vt-item-drag {
      color: #cbd5e1;
      font-size: 10px;
      cursor: grab;
    }

    .vt-item-icon {
      width: 32px;
      height: 32px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      flex-shrink: 0;
    }

    .vt-item-body {
      flex: 1;
      min-width: 0;
      text-align: left;
    }

    .vt-item-type {
      font-size: 8px;
      font-weight: 800;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      color: #94a3b8;
    }

    .vt-item-name {
      font-size: 12px;
      font-weight: 700;
      color: #1a2e2c;
    }

    .vt-item-detail {
      font-size: 10px;
      color: #64748b;
      margin-top: 1px;
    }

    .vt-item-actions {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      display: flex;
      gap: 4px;
      opacity: 0.3;
      transition: opacity 0.15s;
    }

    .vt-item:hover .vt-item-actions {
      opacity: 1;
    }

    .vt-item-btn {
      width: 20px;
      height: 20px;
      border-radius: 5px;
      background: #f1f5f9;
      border: none;
      cursor: pointer;
      font-size: 10px;
      color: #64748b;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.15s;
    }

    .vt-item-btn.del:hover {
      background: #fee2e2;
      color: #ef4444;
    }

    /* PREVIEW MODE STATES (PUBLIC LINK VIEW) */
    .vt-root.is-preview .vt-toolbar,
    .vt-root.is-preview .vt-drop-hint,
    .vt-root.is-preview .vt-item-drag,
    .vt-root.is-preview .vt-item-actions {
      display: none !important;
    }

    .vt-root.is-preview .vt-body {
      flex: 1 1 0% !important;
      min-height: 0 !important;
      overflow: hidden !important;
    }

    .vt-root.is-preview .vt-preview-sidebar {
      height: 100% !important;
      overflow-y: auto !important;
      scrollbar-width: thin;
    }

    .vt-root.is-preview .vt-main-wrap {
      background: #f8fafc;
      overflow-y: auto !important;
      overflow-x: hidden !important;
      -webkit-overflow-scrolling: touch !important;
      overscroll-behavior: contain !important;
      touch-action: pan-y !important;
      flex: 1 1 0% !important;
      min-height: 0 !important;
      height: 100% !important;
    }

    .vt-root.is-preview .vt-main-wrap::-webkit-scrollbar {
      width: 6px;
    }

    .vt-root.is-preview .vt-main-wrap::-webkit-scrollbar-track {
      background: transparent;
    }

    .vt-root.is-preview .vt-main-wrap::-webkit-scrollbar-thumb {
      background: rgba(15, 42, 58, 0.2);
      border-radius: 10px;
    }

    .vt-root.is-preview .vt-main-wrap::-webkit-scrollbar-thumb:hover {
      background: rgba(2, 181, 203, 0.6);
    }

    .vt-root.is-preview .vt-main {
      height: auto !important;
      min-height: 0 !important;
      flex: none !important;
      display: block !important;
      overflow: visible !important;
      background: transparent !important;
    }

    .vt-root.is-preview .vt-canvas {
      height: auto !important;
      min-height: min-content !important;
      flex: none !important;
      overflow: visible !important;
      padding: 10px 14px 60px !important;
      display: block !important;
    }

    .vt-root.is-preview .vt-canvas-inner {
      max-width: 620px;
      margin: 0 auto;
    }

    .vt-preview-hero {
      display: none;
    }

    .vt-root.is-preview .vt-preview-hero {
      display: block;
      width: 100%;
      padding: 12px 14px 4px;
      box-sizing: border-box;
    }

    .vt-preview-hero-inner {
      background: linear-gradient(180deg, rgba(10, 24, 38, 0.15) 0%, rgba(10, 24, 38, 0.88) 100%), url('https://images.unsplash.com/photo-1512453979798-5ea266f8880c?q=80&w=1200&auto=format&fit=crop');
      background-size: cover;
      background-position: center;
      border-radius: 12px;
      padding: 55px 14px 0 14px;
      min-height: 165px;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      color: white;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
      overflow: hidden;
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .vt-preview-hero-badge {
      display: inline-flex;
      align-items: center;
      background: #dcfce7;
      color: #15803d;
      font-size: 8px;
      font-weight: 800;
      padding: 3px 8px;
      border-radius: 50px;
      margin-bottom: 6px;
      letter-spacing: 0.5px;
    }

    .vt-preview-hero-title {
      font-family: 'Syne', sans-serif;
      font-size: 19px;
      font-weight: 800;
      margin-bottom: 10px;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .vt-preview-hero-stats {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(8px);
      border-radius: 8px;
      padding: 8px 0;
      color: #1e293b;
    }

    .vt-preview-hero-stats .stat {
      text-align: center;
      border-right: 1px solid #e2e8f0;
    }

    .vt-preview-hero-stats .stat:last-child {
      border-right: none;
    }

    .vt-preview-hero-stats .stat span {
      font-size: 7px;
      font-weight: 800;
      color: #94a3b8;
      display: block;
      letter-spacing: 0.5px;
    }

    .vt-preview-hero-stats .stat strong {
      font-size: 11px;
      font-weight: 700;
      color: #0f2a3a;
    }

    .vt-preview-day-heading {
      display: none;
    }

    .vt-root.is-preview .vt-preview-day-heading {
      display: block;
      font-family: 'Barlow Condensed', sans-serif;
      font-size: 18px;
      font-weight: 800;
      color: #0f2a3a;
      margin: 10px 0 12px;
      padding-bottom: 6px;
      border-bottom: 1.5px solid #e2e8ef;
    }

    .vt-root.is-preview .vt-item {
      background: white;
      border: 1px solid #e2e8ef;
      border-radius: 10px;
      padding: 0;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
      cursor: default;
      transform: none !important;
    }

    .vt-root.is-preview .vt-item:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
    }

    /* FLIGHT CARD RICH */
    .vt-item-flight-rich {
      padding: 20px;
      border-left: 4px solid #0284c7;
    }

    .flight-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }

    .flight-header span {
      font-size: 11px;
      font-weight: 700;
      color: #64748b;
    }

    .flight-badge {
      background: #0f172a;
      color: white;
      padding: 3px 8px;
      border-radius: 4px;
      font-size: 8px;
    }

    .flight-main {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin: 15px 0;
    }

    .flight-main .time {
      text-align: left;
    }

    .flight-main .time strong {
      font-size: 22px;
      color: var(--navy);
      display: block;
    }

    .flight-main .time span {
      font-size: 10px;
      color: #94a3b8;
      font-weight: 600;
    }

    .plane-icon {
      font-size: 18px;
      color: #cbd5e1;
      transform: rotate(90deg);
    }

    .flight-footer {
      border-top: 1.5px solid #f1f5f9;
      padding-top: 15px;
      font-size: 10px;
      color: #64748b;
      display: flex;
      gap: 8px;
      align-items: center;
    }

    /* HOTEL CARD RICH */
    .vt-item-hotel-rich {
      padding: 20px;
      border-left: 4px solid #f59e0b;
    }

    .hotel-title {
      font-size: 14px;
      font-weight: 800;
      color: var(--navy);
      margin-bottom: 5px;
    }

    .hotel-stars {
      color: #f59e0b;
      font-size: 8px;
      margin-bottom: 8px;
      letter-spacing: 2px;
    }

    .hotel-detail {
      font-size: 11px;
      color: #64748b;
      margin-bottom: 12px;
      line-height: 1.4;
    }

    .hotel-location {
      font-size: 9px;
      font-weight: 600;
      color: #94a3b8;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    /* GENERIC RICH */
    .vt-item-generic-rich {
      padding: 20px;
      border-left: 4px solid #3db898;
    }

    .generic-title {
      font-size: 13px;
      font-weight: 700;
      color: var(--navy);
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .generic-detail {
      font-size: 11px;
      color: #64748b;
      line-height: 1.4;
    }

    /* Transitions */
    .vt-sidebar,
    .vt-preview-sidebar,
    .vt-main-wrap,
    .vt-canvas {
      transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .vt-section-label {
      font-family: 'Barlow Condensed', sans-serif;
      font-size: 9px;
      font-weight: 800;
      letter-spacing: 1.3px;
      text-transform: uppercase;
      color: #94a3b8;
      padding: 13px 12px 5px;
    }

    .vt-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 5px;
      padding: 0 7px;
    }

    .vt-card {
      border: 1px solid #e8eef2;
      border-radius: 9px;
      padding: 9px 8px 8px;
      background: #fafcfc;
      cursor: grab;
      transition: all 0.15s;
      position: relative;
    }

    .vt-card:hover {
      border-color: #3db898;
      background: #f0faf6;
      transform: translateY(-1px);
      box-shadow: 0 3px 8px rgba(61, 184, 152, 0.15);
    }

    .vt-card:active {
      cursor: grabbing;
      transform: scale(0.97);
    }

    .vt-card-drag {
      position: absolute;
      top: 5px;
      right: 4px;
      display: flex;
      gap: 2px;
      opacity: 0;
      transition: opacity 0.15s;
    }

    .vt-card:hover .vt-card-drag {
      opacity: 1;
    }

    .vt-card-drag span {
      display: flex;
      flex-direction: column;
      gap: 2px;
    }

    .vt-card-drag i {
      display: block;
      width: 2px;
      height: 2px;
      background: #b0bec5;
      border-radius: 50%;
    }

    .vt-card-icon {
      width: 28px;
      height: 28px;
      border-radius: 7px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 13px;
      margin-bottom: 5px;
    }

    .vt-card-name {
      font-size: 11px;
      font-weight: 700;
      color: #1a2e2c;
      line-height: 1.2;
    }

    .vt-card-sub {
      font-size: 9px;
      color: #94a3b8;
      margin-top: 1px;
      line-height: 1.3;
    }

    .icon-flight {
      background: #e8f0fe;
      color: #4285f4;
    }

    .icon-hotel {
      background: #fff3e0;
      color: #f57c00;
    }

    .icon-car {
      background: #f3e5f5;
      color: #9c27b0;
    }

    .icon-act {
      background: #e8f5e9;
      color: #4caf50;
    }

    .icon-food {
      background: #fce4ec;
      color: #e91e63;
    }

    .icon-tour {
      background: #e3f2fd;
      color: #2196f3;
    }

    .icon-txt {
      background: #f5f5f5;
      color: #607d8b;
      font-family: 'Barlow Condensed';
      font-weight: 800;
      font-size: 12px;
    }

    .icon-title {
      background: #f5f5f5;
      color: #37474f;
      font-family: 'Barlow Condensed';
      font-weight: 900;
      font-size: 15px;
    }

    .icon-sep {
      background: #f5f5f5;
      color: #90a4ae;
      font-size: 8px;
    }

    .icon-box {
      background: #fce4ec;
      color: #e91e63;
    }

    .icon-img {
      background: #fff8e1;
      color: #ff8f00;
    }

    .icon-gif {
      background: #f3e5f5;
      color: #7b1fa2;
    }

    .vt-main-wrap {
      position: relative;
      flex: 1;
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }

    .vt-main {
      flex: 1;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      background: #f0f4f8;
    }

    .vt-toolbar {
      background: white;
      border-bottom: 1px solid #e2e8ef;
      display: flex;
      align-items: center;
      gap: 4px;
      padding: 0 12px;
      height: 40px;
      flex-shrink: 0;
    }

    .vt-tabs-row {
      display: flex;
      gap: 4px;
    }

    .vt-tab {
      padding: 4px 11px;
      border-radius: 7px;
      font-size: 11px;
      font-weight: 600;
      cursor: pointer;
      color: #1a2e2c;
      border: 1px solid transparent;
      transition: all 0.15s;
      white-space: nowrap;
      background: none;
      font-family: 'Barlow', sans-serif;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .vt-tab:hover {
      background: #f0f9f6;
      color: #3db898;
    }

    .vt-tab.active {
      background: linear-gradient(135deg, #3db898, #62d4b5);
      color: white;
      border-color: transparent;
    }

    .vt-tab-x {
      font-size: 9px;
      color: #b0bec5;
      cursor: pointer;
      line-height: 1;
    }

    .vt-tab.active .vt-tab-x {
      color: rgba(255, 255, 255, 0.6);
    }

    .vt-toolbar-spacer {
      flex: 1;
    }

    .vt-item-count {
      font-size: 11px;
      color: #94a3b8;
    }

    .vt-canvas {
      flex: 1;
      overflow-y: auto;
      padding: 16px;
      display: flex;
      flex-direction: column;
      align-items: center;
      transition: background 0.2s;
    }

    .vt-canvas.vt-over {
      background: #e8f5f1;
    }

    .vt-canvas::-webkit-scrollbar {
      width: 4px;
    }

    .vt-canvas::-webkit-scrollbar-thumb {
      background: #c5d5d0;
      border-radius: 4px;
    }

    .vt-canvas-inner {
      width: 100%;
      max-width: 600px;
    }

    .vt-empty {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 60px 20px;
      gap: 10px;
    }

    .vt-empty-icon {
      width: 52px;
      height: 52px;
      border-radius: 50%;
      background: #e8f5f1;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      margin-bottom: 4px;
    }

    .vt-empty-title {
      font-family: 'Barlow Condensed', sans-serif;
      font-size: 16px;
      font-weight: 700;
      color: #94a3b8;
    }

    .vt-empty-sub {
      font-size: 12px;
      color: #b0bec5;
      text-align: center;
      line-height: 1.5;
    }

    .vt-drop-hint {
      border: 2px dashed #b0ccc6;
      border-radius: 10px;
      padding: 12px 16px;
      text-align: center;
      color: #94a3b8;
      font-size: 11.5px;
      margin-top: 10px;
      display: none;
    }

    .vt-items {
      display: flex;
      flex-direction: column;
      border-radius: 10px;
      overflow: hidden;
    }

    .vt-item {
      background: white;
      border-bottom: 1px solid #f0f4f5;
      padding: 11px 14px;
      display: flex;
      align-items: flex-start;
      gap: 10px;
      position: relative;
      transition: background 0.12s;
      animation: vtSlide 0.22s ease;
    }

    .vt-item:first-child {
      border-radius: 10px 10px 0 0;
    }

    .vt-item:last-child {
      border-radius: 0 0 10px 10px;
      border-bottom: none;
    }

    .vt-item:only-child {
      border-radius: 10px;
    }

    .vt-item:hover {
      background: #fafcfc;
    }

    @keyframes vtSlide {
      from {
        opacity: 0;
        transform: translateY(-6px)
      }

      to {
        opacity: 1;
        transform: translateY(0)
      }
    }

    .vt-item-icon {
      width: 34px;
      height: 34px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
      flex-shrink: 0;
    }

    .vt-item-body {
      flex: 1;
      min-width: 0;
      text-align: left;
    }

    .vt-item-type {
      font-size: 9px;
      font-weight: 700;
      letter-spacing: 0.6px;
      text-transform: uppercase;
      color: #94a3b8;
      margin-bottom: 1px;
    }

    .vt-item-name {
      font-size: 13px;
      font-weight: 700;
      color: #1a2e2c;
    }

    .vt-item-detail {
      font-size: 11px;
      color: #7fa098;
      margin-top: 1px;
    }

    .vt-item-actions {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      display: flex;
      gap: 4px;
      opacity: 0.18;
      transition: opacity 0.2s;
    }

    .vt-item:hover .vt-item-actions {
      opacity: 0.7;
    }

    .vt-item-btn {
      width: 20px;
      height: 20px;
      border-radius: 5px;
      background: #eef1f3;
      border: none;
      cursor: pointer;
      font-size: 10px;
      color: #64748b;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.15s, color 0.15s;
    }

    .vt-item-btn.del:hover {
      background: #fde8ec;
      color: #e53e3e;
    }

    .vt-item-btn.edt:hover {
      background: #e8f5f1;
      color: #3db898;
    }

    .vt-toast {
      position: absolute;
      bottom: 14px;
      right: 14px;
      background: #0f2a3a;
      color: white;
      padding: 7px 14px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 500;
      opacity: 0;
      pointer-events: none;
      transition: all 0.25s;
      transform: translateY(6px);
      z-index: 10;
    }

    .vt-toast.show {
      opacity: 1;
      transform: translateY(0);
    }

    .vt-item.vt-dragging {
      opacity: 0.4;
      background: #f0f9f6;
      border: 1px dashed #3db898;
    }

    .vt-item.vt-drag-over {
      border-top: 2px solid #3db898;
    }

    /* ── PREMIUM HERO MOCKUP STYLES ── */
    .hero-mockup {
      max-width: 900px;
      margin: 0 auto;
      position: relative;
    }

    .mockup-frame {
      background: #fff;
      border-radius: 18px;
      overflow: hidden;
      box-shadow: 0 40px 100px rgba(0, 0, 0, 0.12), 0 10px 30px rgba(0, 0, 0, 0.06);
      border: 1px solid rgba(0, 0, 0, 0.05);
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

    .mockup-dot:nth-child(1) {
      background: #ff5f57;
      box-shadow: 0 0 6px rgba(255, 95, 87, 0.4);
    }

    .mockup-dot:nth-child(2) {
      background: #febc2e;
      box-shadow: 0 0 6px rgba(254, 188, 46, 0.3);
    }

    .mockup-dot:nth-child(3) {
      background: #28c840;
      box-shadow: 0 0 6px rgba(40, 200, 64, 0.3);
    }

    .mockup-inner {
      position: relative;
      background: #f5f7fa;
    }



    /* ── PREVIEW MOCKUP HEADER (MOBILE) ── */
    .vt-preview-header {
      background: white;
      border-radius: 16px;
      padding: 20px;
      margin: 15px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
      display: none;
      /* Only shown in preview mode via media query */
      grid-template-columns: repeat(3, 1fr);
      text-align: center;
      border: 1px solid #f0f2f5;
    }

    .vt-preview-header-item {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .vt-preview-header-label {
      font-size: 10px;
      font-weight: 800;
      color: #b0bcc8;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .vt-preview-header-value {
      font-size: 13px;
      font-weight: 800;
      color: #0d1f2d;
    }

    .vt-day-badge {
      background: #1a7a8a;
      color: white;
      padding: 6px 14px;
      border-radius: 100px;
      font-size: 11px;
      font-weight: 800;
      text-transform: uppercase;
      display: inline-block;
      margin-right: 12px;
      box-shadow: 0 4px 10px rgba(26, 122, 138, 0.2);
    }

    .vt-day-title {
      font-size: 14px;
      font-weight: 700;
      color: #0d1f2d;
      vertical-align: middle;
    }

    @media (max-width: 768px) {
      .pricing-grid {
        grid-template-columns: 1fr;
        padding: 25px 1rem
      }

      .pricing {
        padding: 4rem 1rem;
      }

      .plan-savings-spacer {
        display: none !important;
      }

      .solutions-title {
        font-size: 2rem;
      }

      .solutions-desc {
        font-size: 1rem;
        padding: 0 1rem;
      }

      .solutions-tabs {
        gap: 0.5rem;
      }

      .sol-tab {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
      }

      .solutions-card {
        padding: 2rem 1.5rem;
      }

      .sol-tagline {
        font-size: 1.5rem;
      }
    }

    /* ── MOCKUPS CONTAINER & HIGH-END DEVICES ── */
    .mockups-container {
      position: relative;
      height: 420px;
      width: 100%;
      max-width: 620px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto;
    }

    /* Laptop Mockup */
    .laptop-wrap {
      position: relative;
      width: 88%;
      max-width: 540px;
      z-index: 1;
      transform: translateX(-30px);
      filter: drop-shadow(0 25px 35px rgba(0, 0, 0, 0.3));
    }

    .l-screen {
      background: #11131a;
      border-radius: 18px 18px 4px 4px;
      padding: 4px 2px 3px 3px;
      border: 1.5px solid #2e313d;
      border-bottom: none;
      position: relative;
      box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.1), 0 25px 60px rgba(0, 0, 0, 0.35);
      overflow: hidden;
    }

    .l-notch {
      position: absolute;
      top: 3px;
      left: 50%;
      transform: translateX(-50%);
      width: 54px;
      height: 10px;
      background: #06070a;
      border-bottom-left-radius: 6px;
      border-bottom-right-radius: 6px;
      z-index: 15;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .l-cam {
      width: 4px;
      height: 4px;
      border-radius: 50%;
      background: #1e2638;
      box-shadow: inset 0 0 1px rgba(255, 255, 255, 0.5);
    }

    .l-display {
      width: 100%;
      aspect-ratio: 16 / 10;
      border-radius: 10px 10px 3px 3px;
      overflow: hidden;
      position: relative;
      background: #0a0b0e;
    }

    .l-display img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: top left;
      display: block;
    }

    .l-glare {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.02) 40%, transparent 60%);
      pointer-events: none;
      z-index: 5;
    }

    .l-base {
      position: relative;
      width: calc(100% + 36px);
      margin-left: -18px;
      z-index: 2;
    }

    .l-hinge {
      width: 74%;
      height: 4px;
      background: #07080b;
      margin: 0 auto;
      border-radius: 2px 2px 0 0;
    }

    .l-deck {
      height: 18px;
      background: linear-gradient(180deg, #30333f 0%, #1e2028 40%, #101116 100%);
      border-radius: 2px 2px 14px 14px;
      position: relative;
      box-shadow: inset 0 1px 1.5px rgba(255, 255, 255, 0.25), inset 0 -1.5px 3px rgba(0, 0, 0, 0.9), 0 8px 20px rgba(0, 0, 0, 0.4);
      border-top: 1px solid rgba(255, 255, 255, 0.18);
    }

    .l-keyline {
      position: absolute;
      top: 2px;
      left: 12%;
      right: 12%;
      height: 2px;
      background: rgba(0, 0, 0, 0.5);
      border-radius: 1px;
      box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.8), 0 0.5px 0 rgba(255, 255, 255, 0.08);
    }

    .l-thumb-indent {
      position: absolute;
      top: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 54px;
      height: 4px;
      background: #07080b;
      border-radius: 0 0 4px 4px;
      box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.95);
    }

    .l-shadow {
      position: absolute;
      bottom: -16px;
      left: 2%;
      right: 2%;
      height: 22px;
      background: radial-gradient(ellipse at center, rgba(0, 0, 0, 0.5) 0%, rgba(0, 0, 0, 0) 75%);
      pointer-events: none;
      z-index: -1;
    }

    /* Mobile Phone Overlay */
    .phone-wrap {
      position: absolute;
      right: 12px;
      bottom: -12px;
      width: 195px;
      z-index: 50 !important;
      filter: drop-shadow(0 20px 35px rgba(0, 0, 0, 0.45));
    }

    .hero-mockups-interactive .phone-wrap {
      z-index: 50 !important;
      transform: translateZ(100px);
    }

    .p-frame {
      background: #14151c;
      border-radius: 38px;
      padding: 3px;
      border: 2px solid #2e313e;
      box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.15), 0 30px 60px rgba(0, 0, 0, 0.55);
      position: relative;
      overflow: hidden;
    }

    .p-island {
      position: absolute;
      top: 7px;
      left: 50%;
      transform: translateX(-50%);
      width: 44px;
      height: 11px;
      background: #000;
      border-radius: 8px;
      z-index: 20;
    }

    .p-screen {
      background: #000;
      width: 100%;
      height: 100%;
      border-radius: 30px;
      overflow: hidden;
      position: relative;
      aspect-ratio: 9 / 19.5;
    }

    .p-screen img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: top center;
      display: block;
      border-radius: 30px;
    }

    .p-glare {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.12) 0%, rgba(255, 255, 255, 0.02) 40%, transparent 60%);
      pointer-events: none;
      z-index: 10;
    }

    @keyframes floatPhone {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-15px);
      }
    }

    @media (max-width: 900px) {
      .travel-grid {
        grid-template-columns: 1fr;
        text-align: center;
        gap: 0;
      }

      .travel-exp .section-desc {
        margin: 0 auto;
      }

      .mockups-container {
        height: 250px;
        margin-top: 1rem;
      }

      .phone-wrap {
        width: 112px;
        right: 0px;
        bottom: 0;
      }

      .laptop-wrap {
        width: 100%;
        transform: none;
        margin: 0 auto;
      }

      .hero-mockups-3d {
        transform: rotateY(-10deg) rotateX(10deg) scale(0.9);
        margin: 0 auto;
      }

      .phone-3d {
        width: 125px;
        right: 0px;
      }
    }

    @media (min-width: 769px) {
      .plan-savings-spacer {
        height: 70px;
        display: block;
      }
    }

    /* ── MOBILE DEMO EXPERIENCES (MOBILE PRO EDITOR & PREVIEW) ── */
    @media (max-width: 768px) {
      .demo-section {
        padding: 0.8rem 0.5rem 1.5rem !important;
      }

      .demo-section .container {
        padding: 0 6px !important;
      }

      .demo-section .reveal {
        margin-top: 0 !important;
        margin-bottom: 1.2rem !important;
      }

      /* Transform desktop mockup into clean mobile smartphone frame on phone screens */
      .laptop-wrap.demo-laptop-expandable {
        max-width: 385px !important;
        width: 100% !important;
        margin: 0 auto !important;
        border-radius: 36px !important;
        overflow: hidden !important;
        filter: drop-shadow(0 20px 45px rgba(0, 0, 0, 0.4)) !important;
      }

      .laptop-wrap.demo-laptop-expandable .l-base,
      .laptop-wrap.demo-laptop-expandable .l-shadow,
      .laptop-wrap.demo-laptop-expandable .l-notch {
        display: none !important;
      }

      .laptop-wrap.demo-laptop-expandable .l-screen {
        border-radius: 36px !important;
        border: 7px solid #0d1e2b !important;
        box-shadow: 0 18px 45px rgba(0, 0, 0, 0.45), inset 0 0 0 1px rgba(255, 255, 255, 0.12) !important;
        padding: 0 !important;
        background: #0f2a3a !important;
        overflow: hidden !important;
      }

      .laptop-wrap.demo-laptop-expandable .l-display {
        height: clamp(520px, 75vh, 640px) !important;
        border-radius: 28px !important;
        overflow: hidden !important;
        width: 100% !important;
      }

      .vt-root {
        height: 100% !important;
        border-radius: 28px !important;
        overflow: hidden !important;
        overscroll-behavior: contain !important;
      }

      /* Mobile Phone App Topbar Styling */
      .vt-topbar {
        height: 48px !important;
        padding: 0 12px !important;
        background: #0f2a3a !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-radius: 28px 28px 0 0 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: flex-start !important;
        gap: 6px !important;
        position: relative !important;
      }

      .vt-topbar-left {
        display: flex !important;
        align-items: center !important;
        gap: 6px !important;
      }

      .vt-topbar-left img {
        height: 15px !important;
      }

      .vt-topbar-center {
        position: static !important;
        transform: none !important;
        display: flex !important;
        align-items: center !important;
      }

      .vt-topbar-center::before {
        content: '|' !important;
        color: rgba(255, 255, 255, 0.35) !important;
        margin: 0 4px 0 2px !important;
        font-size: 11px !important;
        font-weight: 400 !important;
      }

      .vt-demo-badge {
        font-size: 9px !important;
        color: rgba(255, 255, 255, 0.9) !important;
        letter-spacing: 0.5px !important;
        white-space: nowrap !important;
      }

      .vt-topbar-actions {
        margin-left: auto !important;
      }

      .vt-preview-btn {
        padding: 4px 10px !important;
        font-size: 10.5px !important;
        border-radius: 50px !important;
        background: rgba(2, 181, 203, 0.22) !important;
        border: 1px solid #02b5cb !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
      }

      /* Editor mode on mobile: Narrow vertical icon sidebar on left, canvas on right */
      .vt-body {
        flex-direction: row !important;
        flex: 1 1 0% !important;
        height: calc(100% - 48px) !important;
        min-height: 0 !important;
        overflow: hidden !important;
      }

      .vt-sidebar {
        width: 52px !important;
        height: 100% !important;
        max-height: none !important;
        border-right: 1px solid #e2e8ef !important;
        border-bottom: none !important;
        padding: 6px 3px 24px !important;
        overflow-y: auto !important;
        -webkit-overflow-scrolling: touch !important;
        overscroll-behavior: contain !important;
        touch-action: pan-y !important;
        flex-shrink: 0 !important;
        background: #ffffff !important;
        scrollbar-width: none !important;
      }

      .vt-sidebar::-webkit-scrollbar {
        display: none !important;
      }

      .vt-sidebar-scroll {
        display: flex !important;
        flex-direction: column !important;
        gap: 6px !important;
        align-items: center !important;
        width: 100% !important;
      }

      .vt-sidebar-section {
        padding: 0 !important;
        width: 100% !important;
      }

      .vt-section-label {
        display: none !important;
      }

      .vt-element-grid {
        display: flex !important;
        flex-direction: column !important;
        padding: 0 !important;
        gap: 6px !important;
        align-items: center !important;
        width: 100% !important;
      }

      .vt-element-card {
        flex-shrink: 0 !important;
        width: 42px !important;
        height: 42px !important;
        padding: 0 !important;
        border-radius: 12px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border: 1px solid #e2e8ef !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03) !important;
        cursor: pointer !important;
        touch-action: manipulation !important;
        -webkit-tap-highlight-color: transparent !important;
        transition: transform 0.15s, border-color 0.15s !important;
      }

      .vt-element-card:active {
        transform: scale(0.88) !important;
        border-color: #02b5cb !important;
        background: #e0f2fe !important;
      }

      .vt-el-icon {
        width: 100% !important;
        height: 100% !important;
        border-radius: 11px !important;
        font-size: 15px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
      }

      .vt-el-info,
      .vt-el-drag-handle {
        display: none !important;
      }

      .vt-main-wrap {
        flex: 1 1 0% !important;
        min-width: 0 !important;
        height: 100% !important;
        display: flex !important;
        flex-direction: column !important;
        overflow: hidden !important;
      }

      .vt-main {
        flex: 1 1 0% !important;
        min-height: 0 !important;
        display: flex !important;
        flex-direction: column !important;
        overflow: hidden !important;
      }

      .vt-toolbar {
        height: 38px !important;
        padding: 0 8px !important;
        gap: 4px !important;
        flex-shrink: 0 !important;
      }

      .vt-tabs-row {
        overflow-x: auto !important;
        scrollbar-width: none !important;
        max-width: 100% !important;
        gap: 4px !important;
      }

      .vt-tabs-row::-webkit-scrollbar {
        display: none !important;
      }

      .vt-tab {
        padding: 3px 8px !important;
        font-size: 10px !important;
        white-space: nowrap !important;
      }

      .vt-tab-add {
        padding: 3px 6px !important;
        font-size: 9.5px !important;
      }

      .vt-item-count {
        display: none !important;
      }

      .vt-canvas {
        flex: 1 1 0% !important;
        min-height: 0 !important;
        overflow-y: auto !important;
        -webkit-overflow-scrolling: touch !important;
        overscroll-behavior: contain !important;
        touch-action: pan-y !important;
        padding: 8px !important;
      }

      .vt-canvas::-webkit-scrollbar {
        width: 4px !important;
      }

      .vt-canvas-inner {
        width: 100% !important;
        max-width: 100% !important;
      }

      .vt-item {
        padding: 8px 10px !important;
        gap: 8px !important;
        border-radius: 8px !important;
        touch-action: pan-y !important;
      }

      .vt-item-drag {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 20px !important;
        height: 20px !important;
        color: #94a3b8 !important;
        font-size: 13px !important;
        cursor: grab !important;
        touch-action: none !important;
        flex-shrink: 0 !important;
        opacity: 0.6 !important;
      }

      .vt-item.vt-dragging {
        opacity: 0.65 !important;
        background: #e0f2fe !important;
        border: 1.5px dashed #02b5cb !important;
        transform: scale(0.98) !important;
      }

      .vt-item.vt-drag-over {
        border-top: 2.5px solid #02b5cb !important;
      }

      .vt-item-name {
        font-size: 11px !important;
      }

      .vt-item-detail {
        font-size: 9.5px !important;
      }

      .vt-item-icon {
        width: 28px !important;
        height: 28px !important;
        font-size: 13px !important;
        border-radius: 6px !important;
      }

      /* Preview mode on mobile: Smooth vertical scrolling & horizontal days navbar */
      .vt-root.is-preview .vt-body {
        flex-direction: column !important;
        flex: 1 1 0% !important;
        height: calc(100% - 48px) !important;
        min-height: 0 !important;
        overflow: hidden !important;
      }

      .vt-root.is-preview .vt-preview-sidebar {
        width: 100% !important;
        height: 38px !important;
        min-height: 38px !important;
        flex-shrink: 0 !important;
        flex-direction: row !important;
        align-items: center !important;
        border-right: none !important;
        border-bottom: 1px solid #e2e8ef !important;
        padding: 4px 8px !important;
        overflow-x: auto !important;
        gap: 6px !important;
        scrollbar-width: none !important;
        background: #ffffff !important;
      }

      .vt-root.is-preview .vt-preview-sidebar::-webkit-scrollbar {
        display: none !important;
      }

      .vt-root.is-preview .vt-preview-sidebar-label {
        display: none !important;
      }

      .vt-preview-days-list {
        display: flex !important;
        flex-direction: row !important;
        gap: 6px !important;
        width: 100% !important;
      }

      .vt-preview-day-item {
        white-space: nowrap !important;
        padding: 4px 10px !important;
        font-size: 11px !important;
        border-radius: 50px !important;
        border-left: none !important;
      }

      .vt-preview-day-item.active {
        background: #02b5cb !important;
        color: #ffffff !important;
      }

      .vt-root.is-preview .vt-main-wrap {
        flex: 1 1 0% !important;
        min-height: 0 !important;
        height: 100% !important;
        max-height: 100% !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
        -webkit-overflow-scrolling: touch !important;
        overscroll-behavior: contain !important;
        touch-action: pan-y !important;
        background: #f8fafc !important;
      }

      .vt-root.is-preview .vt-main {
        height: auto !important;
        min-height: 0 !important;
        flex: none !important;
        display: block !important;
        overflow: visible !important;
        background: transparent !important;
      }

      .vt-root.is-preview .vt-canvas {
        overflow: visible !important;
        height: auto !important;
        min-height: min-content !important;
        display: block !important;
        padding: 6px 8px 60px !important;
      }

      .vt-root.is-preview .vt-canvas-inner {
        max-width: 100% !important;
        margin: 0 auto !important;
      }

      .vt-preview-hero {
        padding: 8px 8px 4px !important;
        box-sizing: border-box !important;
      }

      .vt-preview-hero-inner {
        padding: 30px 10px 0 10px !important;
        min-height: 130px !important;
        border-radius: 10px !important;
      }

      .vt-preview-hero-title {
        font-size: 17px !important;
        margin-bottom: 8px !important;
      }

      .vt-preview-hero-stats {
        padding: 6px 0 !important;
        margin: 0 -10px !important;
      }

      .vt-preview-hero-stats .stat div {
        font-size: 8px !important;
      }

      .vt-preview-hero-stats .stat strong {
        font-size: 10px !important;
      }
    }
  </style>

  <script>
    (function () {
      const VT_DEFAULTS = [
        [
          { type: 'Vuelo', icon: '✈️', bg: '#e0f2fe', name: 'Vuelo BCN → DXB', detail: 'Emirates EK-0383 · 14:30h', airline: 'Emirates Airlines', flight: 'EK 0383', times: '14:30 - 21:55' },
          { type: 'Alojamiento', icon: '🏨', bg: '#fef3c7', name: 'Atlantis The Palm', detail: 'Check-in 15:00 · Suite Lujo', stars: 5, location: 'Palm Jumeirah' },
        ],
        [
          { type: 'Actividad', icon: '🎯', bg: '#dcfce7', name: 'Safari en el Desierto', detail: 'Dune bashing + Cena · 16:00h', duration: '6 horas' },
          { type: 'Comida', icon: '🍽️', bg: '#ffe4e6', name: 'Almuerzo Dubai Mall', detail: 'Reserva confirmada · 13:30h' },
        ],
        [
          { type: 'Tour', icon: '🗺️', bg: '#e0f2fe', name: 'Burj Khalifa Top', detail: 'Piso 148 · Entrada 10:00h', ticket: 'BK-99231' },
          { type: 'Traslado', icon: '🚗', bg: '#f3e8ff', name: 'Traslado Hotel → DXB', detail: 'Privado · Salida 07:00h' },
        ],
      ];

      const VT_EXTRA = {
        Vuelo: [{ type: 'Vuelo', icon: '✈️', bg: '#e0f2fe', name: 'Vuelo BCN → DXB', detail: 'Emirates EK-0383', airline: 'Emirates', flight: 'EK 0383', times: '14:30 - 21:55' }],
        Alojamiento: [{ type: 'Alojamiento', icon: '🏨', bg: '#fef3c7', name: 'Atlantis The Palm', detail: 'Palm Jumeirah', location: 'Dubái' }],
        Traslado: [{ type: 'Traslado', icon: '🚗', bg: '#f3e8ff', name: 'Traslado privado', detail: 'Limousine · 30 min' }],
        Actividad: [{ type: 'Actividad', icon: '🎯', bg: '#dcfce7', name: 'Safari Desierto', detail: 'Dune bashing', duration: '6h' }],
        Comida: [{ type: 'Comida', icon: '🍽️', bg: '#ffe4e6', name: 'Cena Romántica', detail: 'Burj Al Arab · 20:00' }],
        Tour: [{ type: 'Tour', icon: '🗺️', bg: '#e0f2fe', name: 'Tour Ciudad', detail: 'Guía privado · 4h' }],
        Documentos: [{ type: 'Documentos', icon: '📄', bg: '#e2e8f0', name: 'Vouchers & Boletos', detail: 'Documentación confirmada' }],
        Texto: [{ type: 'Texto', icon: 'Aa', bg: '#f1f5f9', name: 'Nota importante', detail: 'Llevar pasaporte original' }],
        Título: [{ type: 'Título', icon: 'T', bg: '#f1f5f9', name: 'Día de llegada', detail: 'Bienvenidos a los Emiratos' }],
        Separador: [{ type: 'Separador', icon: '—✦—', bg: '#f8fafc', name: '— ✦ —', detail: '' }],
        Caja: [{ type: 'Caja', icon: '💡', bg: '#fef9c3', name: 'Info Clima', detail: '32°C - Soleado' }],
        Imagen: [{ type: 'Imagen', icon: '🖼️', bg: '#fef3c7', name: 'Dubái Skyline', detail: 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?q=80&w=1000&auto=format&fit=crop' }],
        Gif: [{ type: 'Gif', icon: '⚡', bg: '#f3e8ff', name: 'Viaje Animación', detail: 'https://media.giphy.com/media/M9O2r21j1p2fMNDS5S/giphy.gif' }],
      };

      const serviceColorMap = {
        'Vuelo': { bg: '#e0f2fe', color: '#0284c7', icon: '<i class="fa-solid fa-plane"></i>' },
        'Alojamiento': { bg: '#fef3c7', color: '#d97706', icon: '<i class="fa-solid fa-hotel"></i>' },
        'Actividad': { bg: '#dcfce7', color: '#16a34a', icon: '<i class="fa-solid fa-compass"></i>' },
        'Traslado': { bg: '#f3e8ff', color: '#9333ea', icon: '<i class="fa-solid fa-car"></i>' },
        'Comida': { bg: '#ffe4e6', color: '#e11d48', icon: '<i class="fa-solid fa-utensils"></i>' },
        'Documentos': { bg: '#e2e8f0', color: '#475569', icon: '<i class="fa-solid fa-file-lines"></i>' },
        'Tour': { bg: '#e0f2fe', color: '#0284c7', icon: '<i class="fa-solid fa-map-location-dot"></i>' },
        'Título': { bg: '#f1f5f9', color: '#1e293b', icon: '<span style="font-family:Poppins;font-weight:800;font-size:14px">T</span>' },
        'Texto': { bg: '#f1f5f9', color: '#475569', icon: '<span style="font-family:Poppins;font-weight:600;font-size:12px">Aa</span>' },
        'Separador': { bg: '#f8fafc', color: '#64748b', icon: '<span style="font-size:9px">— ✦ —</span>' },
        'Caja': { bg: '#fef9c3', color: '#ca8a04', icon: '<i class="fa-solid fa-lightbulb"></i>' },
        'Imagen': { bg: '#fef3c7', color: '#d97706', icon: '<i class="fa-regular fa-image"></i>' },
        'Gif': { bg: '#f3e8ff', color: '#ce3df3', icon: '<i class="fa-solid fa-bolt"></i>' }
      };

      let currentDay = 0;
      let uid = 2000;
      let vtDragType = null;
      let vtReorderId = null;
      let vtTargetId = null;
      let vtTimer = null;
      let vtExtraIdx = {};

      const days = [
        { id: 0, title: 'Día 1: Llegada y Bienvenida', items: VT_DEFAULTS[0].map(x => ({ ...x, id: uid++ })) },
        { id: 1, title: 'Día 2: Explorando Dubái', items: VT_DEFAULTS[1].map(x => ({ ...x, id: uid++ })) },
        { id: 2, title: 'Día 3: El Desierto', items: VT_DEFAULTS[2].map(x => ({ ...x, id: uid++ })) }
      ];

      window.vtRenderTabs = function () {
        const container = document.getElementById('vtTabsContainer');
        if (!container) return;

        let html = days.map((day, idx) => `
          <button class="vt-tab ${idx === currentDay ? 'active' : ''}" id="vtTab${idx}" onclick="vtSwitch(${idx})">
            Día ${idx + 1}
            <span class="vt-tab-x" onclick="vtDeleteDay(event, ${idx})" title="Eliminar día">✕</span>
          </button>
        `).join('');

        html += `<button class="vt-tab-add" onclick="vtAddDay()">+ Día</button>`;
        container.innerHTML = html;
      };

      window.vtSwitch = (n) => {
        if (n < 0 || n >= days.length) return;
        currentDay = n;
        vtRenderTabs();
        vtUpdatePreviewSidebar();
        vtRender();
      };

      window.vtAddDay = () => {
        const newDayNum = days.length + 1;
        days.push({
          id: days.length,
          title: `Día ${newDayNum}: Nueva Actividad`,
          items: []
        });
        currentDay = days.length - 1;
        vtRenderTabs();
        vtUpdatePreviewSidebar();
        vtRender();
        vtToast(`✓ Día ${newDayNum} añadido`);
      };

      window.vtDeleteDay = (e, idx) => {
        if (e) e.stopPropagation();
        if (days.length <= 1) {
          vtToast('Debes mantener al menos 1 día');
          return;
        }
        days.splice(idx, 1);
        if (currentDay >= days.length) {
          currentDay = days.length - 1;
        }
        vtRenderTabs();
        vtUpdatePreviewSidebar();
        vtRender();
        vtToast('✓ Día eliminado');
      };

      function vtUpdatePreviewSidebar() {
        const list = document.getElementById('vtPreviewDaysList');
        const header = document.getElementById('vtPreviewDayHeader');
        if (list) {
          list.innerHTML = days.map((day, idx) => `
          <div class="vt-preview-day-item ${idx === currentDay ? 'active' : ''}" onclick="vtSwitch(${idx})">
            Día ${idx + 1}
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
        const idx = vtExtraIdx[vtDragType] || 0;
        const tpl = pool[idx % pool.length] || { type: vtDragType, icon: '📌', bg: '#f5f5f5', name: vtDragType, detail: '' };
        vtExtraIdx[vtDragType] = idx + 1;
        days[currentDay].items.push({ ...tpl, id: uid++ });
        vtRender();
        vtToast('✓ ' + tpl.name + ' añadido');
        vtDragType = null;
      };

      window.vtRemove = (id) => {
        days[currentDay].items = days[currentDay].items.filter(x => x.id !== id);
        vtRender();
      };

      function bindTouchReorder(el, item) {
        let touchStartY = 0;
        let isTouchDragging = false;
        let dragTargetId = null;

        const handleTouchStart = (e) => {
          if (e.touches.length !== 1) return;
          if (e.target.closest('.vt-item-btn')) return;
          touchStartY = e.touches[0].clientY;
          vtReorderId = item.id;
          isTouchDragging = false;
          dragTargetId = null;
        };

        const handleTouchMove = (e) => {
          if (vtReorderId !== item.id) return;
          const currentY = e.touches[0].clientY;
          const diffY = Math.abs(currentY - touchStartY);

          if (diffY > 6) {
            isTouchDragging = true;
            if (e.cancelable) e.preventDefault();
            el.classList.add('vt-dragging');

            const elemBelow = document.elementFromPoint(e.touches[0].clientX, currentY);
            if (elemBelow) {
              const targetItem = elemBelow.closest('.vt-item');
              document.querySelectorAll('.vt-item').forEach(i => i.classList.remove('vt-drag-over'));
              if (targetItem && targetItem !== el) {
                targetItem.classList.add('vt-drag-over');
                const tId = parseInt(targetItem.dataset.id, 10);
                if (!isNaN(tId)) dragTargetId = tId;
              }
            }
          }
        };

        const handleTouchEnd = () => {
          if (vtReorderId === item.id) {
            el.classList.remove('vt-dragging');
            document.querySelectorAll('.vt-item').forEach(i => i.classList.remove('vt-drag-over'));

            if (isTouchDragging && dragTargetId !== null && dragTargetId !== vtReorderId) {
              const fromIdx = days[currentDay].items.findIndex(x => x.id === vtReorderId);
              const toIdx = days[currentDay].items.findIndex(x => x.id === dragTargetId);
              if (fromIdx !== -1 && toIdx !== -1 && fromIdx !== toIdx) {
                const moved = days[currentDay].items.splice(fromIdx, 1)[0];
                days[currentDay].items.splice(toIdx, 0, moved);
                vtRender();
                vtToast('✓ Orden actualizado');
              }
            }
            vtReorderId = null;
            dragTargetId = null;
            isTouchDragging = false;
          }
        };

        el.addEventListener('touchstart', handleTouchStart, { passive: true });
        el.addEventListener('touchmove', handleTouchMove, { passive: false });
        el.addEventListener('touchend', handleTouchEnd);
        el.addEventListener('touchcancel', handleTouchEnd);
      }

      function vtRender() {
        const list = document.getElementById('vtItems');
        const empty = document.getElementById('vtEmpty');
        const hint = document.getElementById('vtDropHint');
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
              el.dataset.id = item.id;
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

              bindTouchReorder(el, item);
            }

            if (isPreview) {
              if (item.id === items[0].id) {
                const dayLabel = document.createElement('div');
                dayLabel.innerHTML = `<span class="vt-day-badge">DÍA ${currentDay + 1}</span>`;
                dayLabel.style.margin = '10px 0 12px 4px';
                dayLabel.style.textAlign = 'left';
                list.appendChild(dayLabel);
              }
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
        const sInfo = serviceColorMap[item.type] || { bg: '#f1f5f9', color: '#02b5cb', icon: '<i class="fa-solid fa-circle-info"></i>' };

        el.innerHTML = `
        <div class="vt-item-drag"><i class="fa-solid fa-grip-vertical"></i></div>
        <div class="vt-item-icon" style="background:${sInfo.bg}; color:${sInfo.color}">${sInfo.icon}</div>
        <div class="vt-item-body">
          <div class="vt-item-type">${item.type}</div>
          <div class="vt-item-name">${item.name}</div>
          ${isVisual ? `<img src="${item.detail}" style="width:100%; border-radius:6px; margin-top:5px; height:auto; max-height:100px; object-fit:cover;">` : `<div class="vt-item-detail">${item.detail}</div>`}
        </div>
        <div class="vt-item-actions">
          <button class="vt-item-btn del" onclick="vtRemove(${item.id})" title="Eliminar">✕</button>
        </div>`;
      }

      let vtAddLock = false;
      window.vtAddElement = function (type) {
        if (!type || vtAddLock) return;
        vtAddLock = true;
        setTimeout(() => { vtAddLock = false; }, 250);

        // If currently in preview mode, switch back to editor mode
        const root = document.querySelector('.vt-root');
        if (root && root.classList.contains('is-preview')) {
          root.classList.remove('is-preview');
          const previewBtn = document.getElementById('vtPreviewBtn');
          if (previewBtn) {
            previewBtn.innerHTML = '<i class="fa-solid fa-eye"></i> <span>Vista previa</span>';
          }
          vtUpdatePreviewSidebar();
        }

        const pool = VT_EXTRA[type] || [];
        const idx = vtExtraIdx[type] || 0;
        const tpl = pool[idx % pool.length] || { type: type, icon: '📌', bg: '#f5f5f5', name: type, detail: '' };
        vtExtraIdx[type] = idx + 1;

        if (!days[currentDay]) currentDay = 0;
        days[currentDay].items.push({ ...tpl, id: uid++ });
        vtRender();
        vtToast('✓ ' + tpl.name + ' añadido');

        // Smooth scroll canvas down to newly added element
        setTimeout(() => {
          const canvas = document.getElementById('vtCanvas');
          if (canvas) {
            canvas.scrollTo({ top: canvas.scrollHeight, behavior: 'smooth' });
          }
        }, 50);
      };

      function renderRichCard(el, item) {
        const sInfo = serviceColorMap[item.type] || { bg: '#f1f5f9', color: '#02b5cb', icon: '<i class="fa-solid fa-circle-info"></i>' };

        if (item.type === 'Vuelo') {
          const flightTitle = item.name.startsWith('Vuelo') ? item.name : 'Vuelo ' + item.name;
          el.innerHTML = `
          <div class="vt-item-flight-rich" style="padding: 14px; background: white; border-radius: 12px; border: 1px solid #eef2f6; width: 100%; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
            <div style="display: flex; align-items: center; justify-content: space-between; color: ${sInfo.color}; font-weight: 700; font-size: 12px; margin-bottom: 12px;">
              <span style="display:flex; align-items:center; gap:6px;"><i class="fa-solid fa-plane"></i> ${flightTitle}</span>
              <span style="background: #0f2a3a; color: white; font-size: 9px; font-weight: 700; padding: 2px 8px; border-radius: 4px;">EK 0383</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
              <div style="text-align: left;">
                <div style="font-size: 18px; font-weight: 800; color: #0f2a3a;">14:30</div>
                <div style="font-size: 11px; font-weight: 700; color: #334155;">BCN (Barcelona)</div>
                <div style="font-size: 9px; color: #94a3b8;">14 de mayo</div>
              </div>
              <div style="text-align: center; color: #cbd5e1; padding: 0 10px;">
                <i class="fa-solid fa-plane-departure" style="font-size: 16px; color: ${sInfo.color};"></i>
                <div style="font-size: 8px; color: #94a3b8; margin-top:2px;">6h 25m</div>
              </div>
              <div style="text-align: right;">
                <div style="font-size: 18px; font-weight: 800; color: #0f2a3a;">21:55</div>
                <div style="font-size: 11px; font-weight: 700; color: #334155;">DXB (Dubái)</div>
                <div style="font-size: 9px; color: #94a3b8;">14 de mayo</div>
              </div>
            </div>
            <div style="border-top: 1px solid #f1f5f9; padding-top: 8px; margin-top: 8px; font-size: 10px; color: #64748b; display:flex; justify-content:space-between;">
              <span>Emirates Airlines</span>
              <span style="color:#16a34a; font-weight:600;"><i class="fa-solid fa-circle-check"></i> Confirmado</span>
            </div>
          </div>`;
        } else if (item.type === 'Alojamiento') {
          const hotelPhotos = item.photos || [
            'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=600&q=80'
          ];
          el.innerHTML = `
          <div style="padding: 14px; background: white; border-radius: 12px; border: 1px solid #eef2f6; width: 100%; text-align: left; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
             <div style="display: flex; gap: 12px; align-items: center; margin-bottom: 10px;">
               <div style="width: 42px; height: 42px; border-radius: 10px; background: ${sInfo.bg}; color:${sInfo.color}; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink:0;">${sInfo.icon}</div>
               <div style="flex: 1; text-align:left;">
                 <div style="font-size: 9px; font-weight: 800; color: ${sInfo.color}; text-transform: uppercase; letter-spacing: 0.6px;">Alojamiento</div>
                 <div style="font-size: 14px; font-weight: 800; color: #0f2a3a;">${item.name}</div>
                 <div style="font-size: 10px; color: #64748b; margin-top: 2px;">${item.location || 'Palm Jumeirah'} · <span style="color:#f59e0b;">★★★★★</span></div>
               </div>
               <span style="background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; font-size: 9px; font-weight: 700; padding: 3px 8px; border-radius: 50px;">Confirmado</span>
             </div>
             <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 6px; border-radius: 10px; overflow: hidden; height: 110px; margin-top: 10px;">
                <img src="${hotelPhotos[0]}" style="width:100%; height:100%; object-fit:cover; display:block;">
                <img src="${hotelPhotos[1]}" style="width:100%; height:100%; object-fit:cover; display:block;">
                <img src="${hotelPhotos[2]}" style="width:100%; height:100%; object-fit:cover; display:block;">
             </div>
          </div>`;
        } else {
          const isVisual = item.type === 'Imagen' || item.type === 'Gif';
          el.innerHTML = `
          <div style="padding: 12px; background: white; border-radius: 10px; border: 1px solid #eef2f6; width: 100%; display: flex; gap: 12px; align-items: center;">
             <div style="width: 38px; height: 38px; border-radius: 8px; background: ${sInfo.bg}; color:${sInfo.color}; display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink:0;">${sInfo.icon}</div>
             <div style="flex: 1; text-align:left;">
               <div style="font-size: 8px; font-weight: 800; color: ${sInfo.color}; text-transform: uppercase; letter-spacing: 0.5px;">${item.type}</div>
               <div style="font-size: 13px; font-weight: 700; color: #0f2a3a;">${item.name}</div>
               ${isVisual ? '' : `<div style="font-size: 10px; color: #64748b; margin-top: 1px;">${item.detail}</div>`}
               ${isVisual ? `<img src="${item.detail}" style="width:100%; max-height:140px; object-fit:cover; border-radius:6px; margin-top:6px; border: 1px solid #f1f5f9;">` : ''}
             </div>
          </div>`;
        }
      }

      window.vtToast = (msg) => {
        const el = document.getElementById('vtToastEl');
        if (!el) return;
        el.textContent = msg; el.classList.add('show');
        clearTimeout(vtTimer);
        vtTimer = setTimeout(() => el.classList.remove('show'), 2000);
      };

      // PREVIEW TOGGLE
      const vtPreviewBtn = document.getElementById('vtPreviewBtn');
      if (vtPreviewBtn) {
        vtPreviewBtn.addEventListener('click', () => {
          const root = document.querySelector('.vt-root');
          root.classList.toggle('is-preview');
          const isPreview = root.classList.contains('is-preview');
          vtPreviewBtn.innerHTML = isPreview
            ? '<i class="fa-solid fa-pen-to-square"></i> <span>Volver al editor</span>'
            : '<i class="fa-solid fa-eye"></i> <span>Vista previa</span>';
          vtUpdatePreviewSidebar();
          vtRender();
        });
      }

      window.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.vt-element-card, .vt-card').forEach(c => {
          c.addEventListener('dragstart', () => { vtDragType = c.dataset.type; c.style.opacity = '0.5'; });
          c.addEventListener('dragend', () => c.style.opacity = '1');
        });
        vtRenderTabs();
        vtUpdatePreviewSidebar();
        vtRender();
      });
    })();
  </script>




  <!-- PRICING -->
  <section class="pricing" id="precios">
    <div class="container">
      <div class="reveal" style="text-align:center;">
        <div class="section-label">Precios</div>
        <h2 class="section-title" style="margin-bottom: 2.5rem;">Planes simples y transparentes</h2>

        <div class="pricing-toggle-wrap reveal">
          <span class="toggle-label active" id="labelMonthly">Facturación Mensual</span>
          <div class="toggle-switch" id="priceToggle"></div>
          <span class="toggle-label" id="labelAnnual">
            Facturación Anual
            <span class="annual-discount-pill">Ahorra un 20%</span>
          </span>
        </div>
      </div>
      <div class="pricing-grid">
        <!-- Explorador -->
        <div class="plan reveal d1">
          <div class="plan-name">Explorador</div>
          <div class="plan-desc-special">Para probar y crear tu primer viaje</div>
          <div class="plan-price">
            <span class="currency">$</span>
            <span class="price-val" data-monthly="0" data-annual="0">0</span>
            <span class="period">/mes</span>
          </div>
          <div class="price-note" data-monthly="Sin tarjeta de crédito" data-annual="Plan gratuito">Sin tarjeta de
            crédito</div>
          <div class="plan-savings" style="opacity:0; pointer-events:none; visibility:hidden;">Savings Spacer</div>
          <div class="plan-sub" style="opacity:0; pointer-events:none; visibility:hidden;">Sub Spacer</div>
          <ul class="plan-features">
            <li><i class="fas fa-check"></i> 1 itinerario activo a la vez</li>
            <li><i class="fas fa-check"></i> Fotos de Unsplash & GIFs ilimitados</li>
            <li><i class="fas fa-check"></i> Personalización de colores y temas</li>
            <li><i class="fas fa-check"></i> Visualizador web interactivo</li>
            <li><i class="fas fa-check"></i> Búsqueda básica en Google Places</li>
            <li><i class="fas fa-check"></i> Copilot IA (3 consultas por viaje)</li>
          </ul>
          <a href="{{ route('register') }}" class="plan-btn">Empezar gratis</a>
        </div>

        <!-- Viajero Pro (DESTACADA) -->
        <div class="plan featured reveal d2" style="position: relative;">
          <div class="popular-badge"
            style="position: absolute; top: -14px; left: 50%; transform: translateX(-50%); background: #1EAACE; color: white; padding: 4px 16px; border-radius: 20px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 4px 12px rgba(30,170,206,0.3);">
            Más Popular</div>
          <div class="plan-name" style="margin-top: 6px;">Viajero Pro</div>
          <div class="plan-desc-special" style="color: rgba(255, 255, 255, 0.85);">Para parejas, amigos y viajeros
            frecuentes</div>
          <div class="plan-price">
            <span class="currency">$</span>
            <span class="price-val" data-monthly="9.99" data-annual="7.99">9.99</span>
            <span class="period">/mes</span>
          </div>
          <div class="price-note" data-monthly="Facturado mensualmente · 7 días de prueba gratis"
            data-annual="Facturado anualmente $95.88 USD · 7 días prueba gratis">Facturado mensualmente</div>
          <div class="plan-savings" style="opacity: 0;">Ahorras 20% en plan anual</div>
          <div class="plan-sub" style="color: rgba(255, 255, 255, 0.7);">Todo para tus viajes sin límites:</div>
          <ul class="plan-features">
            <li><i class="fas fa-check" style="color: #1EAACE;"></i> <strong>Itinerarios activos ilimitados</strong>
            </li>
            <li><i class="fas fa-check" style="color: #1EAACE;"></i> <strong>Hasta 2 colaboradores para editar en pareja
                o grupo</strong></li>
            <li><i class="fas fa-check" style="color: #1EAACE;"></i> Copilot IA Ilimitado</li>
            <li><i class="fas fa-check" style="color: #1EAACE;"></i> Exportación descargable en PDF</li>
            <li><i class="fas fa-check" style="color: #1EAACE;"></i> Google Places Ilimitado con Galería HD</li>
            <li><i class="fas fa-check" style="color: #1EAACE;"></i> Guardar y reutilizar tus propias plantillas</li>
            <li><i class="fas fa-check" style="color: #1EAACE;"></i> Sincronización directa con Google Calendar</li>
          </ul>
          <a href="{{ route('plans.redirect') }}" class="plan-btn btn-pro"
            style="background: #1EAACE; border-color: #1EAACE; color: white;">Probar 7 días gratis</a>
        </div>

        <!-- Negocios -->
        <div class="plan reveal d3">
          <div class="plan-name">Negocios</div>
          <div class="plan-desc-special">Para agencias de viajes, DMCs y asesores</div>
          <div class="plan-price">
            <span class="currency">$</span>
            <span class="price-val" data-monthly="29.99" data-annual="23.99">29.99</span>
            <span class="period">/mes</span>
          </div>
          <div class="price-note" data-monthly="Facturado mensualmente · 14 días de prueba gratis"
            data-annual="Facturado anualmente $287.88 USD · 14 días prueba gratis">Facturado mensualmente</div>
          <div class="plan-savings" style="opacity: 0;">Ahorras 20% en plan anual</div>
          <div class="plan-sub">Todo lo del plan Viajero Pro, más:</div>
          <ul class="plan-features">
            <li><i class="fas fa-check"></i> <strong>Colaboradores y editores ilimitados</strong></li>
            <li><i class="fas fa-check"></i> <strong>Marca Blanca: Logo propio de tu agencia en web y PDF</strong></li>
            <li><i class="fas fa-check"></i> Biblioteca de plantillas privadas para tus clientes</li>
            <li><i class="fas fa-check"></i> Ficha de contacto directo (WhatsApp, Email y Redes)</li>
            <li><i class="fas fa-check"></i> Soporte prioritario dedicado</li>
          </ul>
          <a href="{{ route('plans.redirect') }}" class="plan-btn btn-outline">Probar 14 días gratis</a>
        </div>
      </div>

      <div class="pricing-disclaimer reveal"
        style="text-align: center; margin-top: 2.5rem; margin-bottom: 1.5rem; font-size: 13px; color: #64748b; font-weight: 500;">
        * Todos los precios están expresados en USD (Dólares Estadounidenses)
      </div>

      <!-- ENTERPRISE PLAN -->
      <div class="enterprise-box reveal"
        style="margin-top: 2rem; background: #0f172a; border-radius: 24px; padding: 2.5rem; color: white; border: 1px solid rgba(255,255,255,0.1); position: relative; overflow: hidden;">
        <div
          style="position: absolute; top:0; right:0; width: 300px; height: 300px; background: radial-gradient(circle, rgba(26,122,138,0.1) 0%, transparent 70%); pointer-events: none;">
        </div>

        <div
          style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-start; gap: 2rem;">
          <div style="flex: 1; min-width: 300px;">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
              <h3 style="font-family: 'Syne', sans-serif; font-size: 24px; font-weight: 800;">Corporativo</h3>
            </div>
            <p style="color: rgba(255,255,255,0.5); font-size: 14px; margin-bottom: 1.5rem;">Grandes agencias · Tour
              operadores · DMCs con alto volumen</p>

            <div style="display: flex; flex-wrap: wrap; gap: 1rem 2rem; margin-bottom: 1.5rem;">
              <div
                style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 500; color: rgba(255,255,255,0.9);">
                <i class="fas fa-check" style="color: var(--teal); font-size: 11px;"></i> Dominio personalizado
              </div>
              <div
                style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 500; color: rgba(255,255,255,0.9);">
                <i class="fas fa-check" style="color: var(--teal); font-size: 11px;"></i> Soporte dedicado y SLA
              </div>
              <div
                style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 500; color: rgba(255,255,255,0.9);">
                <i class="fas fa-check" style="color: var(--teal); font-size: 11px;"></i> Integraciones API avanzadas
              </div>
              <div
                style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 500; color: rgba(255,255,255,0.9);">
                <i class="fas fa-check" style="color: var(--teal); font-size: 11px;"></i> Onboarding dedicado
              </div>
            </div>
          </div>

          <div style="flex-shrink: 0;">
            <a href="{{ route('contact') }}" class="plan-btn"
              style="border-color: rgba(255,255,255,0.2); color: white; padding: 1rem 2.5rem; border-radius: 12px; display: inline-flex; align-items: center; gap: 10px; text-decoration: none;">
              Hablar con ventas <i class="fas fa-arrow-up-right-from-square" style="font-size: 12px; opacity: 0.7;"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FINAL CTA FEATURE BANNER SECTION (MATCHING USER IMAGE) -->
  <section class="feature-banner" id="banner-section">
    <div class="feature-banner-container reveal">
      <div class="feature-banner-text">
        <h2>Todo tu viaje,<br><span>en un solo lugar</span></h2>
        <p>Una experiencia digital moderna y dinámica que transforma tus viajes en piezas únicas,<br>accesibles desde
          cualquier dispositivo.</p>

        <div class="feature-banner-actions">
          @auth
            <a href="{{ route('trips.index') }}" class="btn-cta-dark">Ir a mis viajes →</a>
            <a href="{{ route('contact') }}" class="btn-cta-cyan">Contáctanos</a>
          @else
            <a href="{{ route('register') }}" class="btn-cta-dark">Regístrate Ahora</a>
            <a href="{{ route('contact') }}" class="btn-cta-cyan">Contáctanos</a>
          @endauth
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer>
    <div class="footer-inner">
      <div class="footer-top">

        <!-- BRAND COL -->
        <div class="footer-brand">
          <div style="display:flex; align-items:center;">
            <img src="/images/logo-viantryp.png" alt="Viantryp"
              style="height: 30px; width: auto; filter: brightness(0) invert(1);">
          </div>
          <p class="footer-brand-desc">La plataforma que transforma itinerarios de viaje en experiencias digitales
            modernas.</p>
          <div class="footer-parte-de" style="margin-top: 2rem;">
            <h4
              style="font-size: 0.78rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: rgba(255,255,255,0.5); margin-bottom: 0.8rem;">
              PARTE DE</h4>
            <div>
              <img src="{{ asset('images/startub.png') }}" alt="StartUB! Universitat de Barcelona"
                style="height: 35px; filter: brightness(0) invert(1); opacity: 0.9;">
            </div>
          </div>
        </div>

        <!-- PRODUCTO -->
        <div class="footer-col">
          <div class="footer-col-title">Producto</div>
          <ul class="footer-col-links">
            <li><a href="#demo">Demo interactiva</a></li>
            <li><a href="#como-funciona">Funcionalidades</a></li>
            <li><a href="#precios">Precios</a></li>
            <li><a href="#soluciones">Soluciones</a></li>
          </ul>
        </div>

        <!-- LEGAL -->
        <div class="footer-col">
          <div class="footer-col-title">Legal</div>
          <ul class="footer-col-links">
            <li><a href="{{ route('terms') }}">Términos de uso</a></li>
            <li><a href="{{ route('privacy') }}">Privacidad</a></li>
            <li><a href="{{ route('gdpr') }}">RGPD</a></li>
            <li><a href="{{ route('security') }}">Seguridad</a></li>
            <li class="contact-desktop-li"
              style="margin-top: 0.5rem; padding-top: 0.8rem; border-top: 1px solid rgba(255,255,255,0.1);"><a
                href="{{ route('contact') }}">Contacto</a></li>
          </ul>
        </div>

        <!-- CONTACTO (MOBILE ONLY) -->
        <style>
          .contact-mobile-col {
            display: none;
          }

          .contact-desktop-li {
            display: list-item;
          }

          @media (max-width: 640px) {
            .contact-mobile-col {
              display: block;
              margin-top: 0;
              padding-top: 0;
            }

            .contact-desktop-li {
              display: none !important;
            }

            .contact-mobile-col .footer-col-title::after {
              display: none !important;
            }
          }
        </style>
        <div class="footer-col contact-mobile-col">
          <a href="{{ route('contact') }}" class="footer-col-title"
            style="text-decoration:none; display:flex;">Contacto</a>
        </div>

      </div><!-- /.footer-top -->

      <div class="footer-bottom">
        <div class="footer-copy">© 2026 Viantryp. Todos los derechos reservados.</div>
      </div>
    </div>
  </footer>

  <script>
    // NAV SCROLL STATE HANDLER (TRANSPARENT -> WHITE & LOGO COLOR TOGGLE)
    (function () {
      const nav = document.querySelector('nav');
      if (nav) {
        const handleNavScroll = () => {
          if (window.scrollY > 25) {
            nav.classList.add('scrolled');
          } else {
            nav.classList.remove('scrolled');
          }
        };
        window.addEventListener('scroll', handleNavScroll, { passive: true });
        handleNavScroll();
      }
    })();

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-hand-entrance').forEach(el => observer.observe(el));

    // LAPTOP SCROLL-EXPANSION ANIMATION (HERO -> DEMO)
    (function () {
      const demoSection = document.getElementById('demo');
      const demoLaptop = document.querySelector('.demo-laptop-expandable');
      const heroVisual = document.querySelector('.hero .feature-banner-visual');

      if (demoSection && demoLaptop) {
        window.addEventListener('scroll', () => {
          const rect = demoSection.getBoundingClientRect();
          const winHeight = window.innerHeight;

          if (rect.top <= winHeight && rect.bottom >= 0) {
            const progress = Math.min(1, Math.max(0, (winHeight - rect.top) / (winHeight * 0.85)));

            // Noticeable Scale / Zoom expansion (0.80 -> 1.02)
            const scaleVal = 0.80 + (0.22 * progress);
            const translateY = (1 - progress) * 55;
            demoLaptop.style.transform = `scale(${scaleVal}) translateY(${translateY}px)`;
            demoLaptop.style.boxShadow = `0 ${20 + progress * 20}px ${40 + progress * 30}px rgba(0, 0, 0, 0.5), 0 0 ${progress * 45}px rgba(77, 226, 244, ${progress * 0.35})`;

          }
        }, { passive: true });
      }
    })();

    document.querySelectorAll('.footer-col-title').forEach(title => {
      title.addEventListener('click', () => {
        if (window.innerWidth <= 640) {
          const col = title.parentElement;
          const wasActive = col.classList.contains('active');
          document.querySelectorAll('.footer-col').forEach(c => c.classList.remove('active'));
          if (!wasActive) col.classList.add('active');
        }
      });
    });

    // Pricing Toggle Logic
    (function () {
      const toggle = document.getElementById('priceToggle');
      const labelMonthly = document.getElementById('labelMonthly');
      const labelAnnual = document.getElementById('labelAnnual');
      const priceVals = document.querySelectorAll('.price-val');
      const priceNotes = document.querySelectorAll('.price-note');
      const savingsLabels = document.querySelectorAll('.plan-savings');

      const setAnnual = (isAnnual) => {
        if (toggle) toggle.classList.toggle('annual', isAnnual);
        if (labelAnnual) labelAnnual.classList.toggle('active', isAnnual);
        if (labelMonthly) labelMonthly.classList.toggle('active', !isAnnual);

        priceVals.forEach(v => {
          const target = isAnnual ? v.dataset.annual : v.dataset.monthly;
          if (target !== undefined) {
            v.style.opacity = '0';
            setTimeout(() => { v.textContent = target; v.style.opacity = '1'; }, 150);
          }
        });

        priceNotes.forEach(n => {
          const target = isAnnual ? n.dataset.annual : n.dataset.monthly;
          if (target !== undefined) {
            n.style.opacity = '0';
            setTimeout(() => { n.textContent = target; n.style.opacity = '1'; }, 150);
          }
        });

        savingsLabels.forEach(s => {
          s.style.opacity = isAnnual ? '1' : '0';
          s.style.transform = isAnnual ? 'translateY(0)' : 'translateY(5px)';
          s.style.pointerEvents = isAnnual ? 'auto' : 'none';
        });
      };

      if (toggle) {
        toggle.addEventListener('click', () => {
          setAnnual(!toggle.classList.contains('annual'));
        });
      }
      if (labelMonthly) labelMonthly.addEventListener('click', () => setAnnual(false));
      if (labelAnnual) labelAnnual.addEventListener('click', () => setAnnual(true));

      priceVals.forEach(v => v.style.transition = 'opacity 0.2s');
      priceNotes.forEach(n => n.style.transition = 'opacity 0.2s');
    })();

    // Solutions Tab Logic
    (function () {
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
            { title: 'Editor visual', span: 'Drag & Drop', desc: 'Arrastra destinos y fotos para diseñar tu ruta ideal en segundos. Es tan fácil como jugar, pero con resultados profesionales.', icon: 'fas fa-pencil-ruler', color: '#02b5cb' },
            { title: 'Enlace interactivo', span: 'personal', desc: 'Lleva todo tu plan en un solo link. Si cambias de opinión sobre un lugar, actualízalo y ten tu ruta siempre al día en tu móvil.', icon: 'fas fa-link', color: '#1EAACE' },
            { title: 'Toda tu documentación', span: 'a mano', desc: 'Guarda tus reservas y mapas directamente en el día que corresponden. Olvida buscar entre cientos de correos y capturas de pantalla.', icon: 'fas fa-file-invoice', color: '#0A2540' }
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
            { title: 'Marca Blanca', span: 'Total', desc: 'Elimina el logo de Viantryp y usa tu propia identidad. Presenta tus viajes bajo tu dominio y proyecta una imagen de gran operadora.', icon: 'fas fa-id-card', color: '#02b5cb' },
            { title: 'Propuestas interactivas', span: 'de lujo', desc: 'Envía enlaces elegantes que enamoran a tus clientes. Sustituye los PDFs pesados por una experiencia digital que cierra ventas.', icon: 'fas fa-desktop', color: '#1EAACE' },
            { title: 'Gestión operativa', span: '360°', desc: 'Vincula vouchers y seguros de viaje a cada servicio. Tu cliente tendrá todo el soporte organizado y accesible en un solo clic.', icon: 'fas fa-cog', color: '#0A2540' }
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
            { title: 'Planificación', span: 'colaborativa', desc: 'Invita a tus amigos o familia a editar juntos. Decidan las paradas en tiempo real y eviten los grupos de WhatsApp infinitos.', icon: 'fas fa-users', color: '#0A2540' },
            { title: 'Centro de control', span: 'grupal', desc: 'Un solo lugar para los tickets de todos. Adjunta los pases de abordar y reservas de grupo para que nadie se pierda nada.', icon: 'fas fa-th-large', color: '#1EAACE' },
            { title: 'Diseño visual', span: 'compartido', desc: 'Crea un itinerario que todos amen. Arrastra fotos de los destinos para que el grupo empiece a vivir el viaje antes de despegar.', icon: 'fas fa-image', color: '#02b5cb' }
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
            { title: 'Propuesta visual', span: 'de servicios', desc: 'Presenta cada servicio con identidad visual. Fotos de alta calidad y descripciones cautivadoras que venden por ti.', icon: 'fas fa-images', color: '#02b5cb' },
            { title: 'Logística de operación', span: 'en vivo', desc: 'Sincroniza el terreno al instante. Envía actualizaciones a guías y transportistas sobre el mismo itinerario, eliminando errores de comunicación.', icon: 'fas fa-map-marked-alt', color: '#1EAACE' },
            { title: 'Consolidador de servicios', span: '360°', desc: 'Agrupa servicios locales, traslados y experiencias en una sola ruta maestra profesional.', icon: 'fas fa-layer-group', color: '#0A2540' }
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
            { title: 'Logística de eventos', span: 'en tiempo real', desc: 'Gestiona agendas complejas para grupos grandes y mantén actualizada la información al instante.', icon: 'fas fa-calendar-alt', color: '#02b5cb' },
            { title: 'Colaboración', span: 'multiequipo', desc: 'Asigna roles y permisos. Deja que tus coordinadores de campo y oficina trabajen sobre el mismo lienzo con total seguridad.', icon: 'fas fa-user-friends', color: '#0A2540' },
            { title: 'Interfaz de marca', span: 'corporativa', desc: 'Profesionaliza la comunicación interna y externa de cada proyecto con los colores de la empresa o evento.', icon: 'fas fa-building', color: '#1EAACE' }
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

          // Transition content with smooth morphing
          contentBox.style.opacity = '0.25';
          contentBox.style.transform = 'translateY(12px) scale(0.985)';

          setTimeout(() => {
            const item = data[target];
            tagline.innerHTML = item.tagline;
            text.textContent = item.text;

            benefitsList.innerHTML = item.benefits.map(b =>
              `<li><i class="fas fa-check"></i> ${b}</li>`
            ).join('');

            if (featuresRight && item.features) {
              featuresRight.innerHTML = item.features.map((f, idx) => `
              <div class="sol-feature-item" style="animation: tabSlideInSlow 0.65s cubic-bezier(0.16, 1, 0.3, 1) ${idx * 0.14}s forwards;">
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
            contentBox.style.transform = 'translateY(0) scale(1)';
          }, 220);
        });
      });

      if (contentBox) {
        contentBox.style.transition = 'opacity 0.45s ease, transform 0.5s cubic-bezier(0.16, 1, 0.3, 1)';
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
  <!-- MOBILE MENU BACKDROP & DRAWER -->
  <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
  <div class="mobile-menu" id="mobileMenu">
    <div class="mobile-menu-header">
      <img src="{{ asset('images/logo-viantryp-black.png') }}" alt="Viantryp">
      <button class="mobile-menu-close" id="mobileMenuClose" aria-label="Cerrar menú">&times;</button>
    </div>

    <ul class="mobile-nav-links">
      <li><a href="#como-funciona"><i class="fas fa-layer-group" style="width: 22px; color: var(--teal);"></i> Cómo
          funciona</a></li>
      <li><a href="#precios"><i class="fas fa-tags" style="width: 22px; color: var(--teal);"></i> Precios y Planes</a>
      </li>
      <li><a href="{{ route('contact') }}"><i class="fas fa-envelope" style="width: 22px; color: var(--teal);"></i>
          Contacto</a></li>
    </ul>

    <div class="mobile-auth">
      @auth
        <div style="font-size: 13px; font-weight: 600; color: var(--text-soft); text-align: center; margin-bottom: 4px;">
          Hola, {{ auth()->user()->name }}
        </div>
        <a href="{{ route('trips.index') }}" class="nav-cta"
          style="width: 100%; display: flex; align-items: center; justify-content: center; text-align: center; padding: 12px; border-radius: 100px;">
          <i class="fas fa-suitcase-rolling" style="margin-right: 8px;"></i> Mis viajes
        </a>
        <form method="POST" action="{{ route('logout') }}" style="margin: 0; width: 100%;">
          @csrf
          <button type="submit"
            style="width: 100%; border: 1px solid rgba(192, 57, 43, 0.3); border-radius: 100px; background: rgba(192, 57, 43, 0.04); padding: 10px; color: #c0392b; cursor: pointer; text-align: center; font-size: 13px; font-weight: 600; font-family: 'Manrope', sans-serif;">
            <i class="fas fa-sign-out-alt" style="margin-right: 6px;"></i> Cerrar sesión
          </button>
        </form>
      @else
        <a href="{{ route('register') }}" class="nav-cta"
          style="width: 100%; display: flex; align-items: center; justify-content: center; text-align: center; padding: 12px; border-radius: 100px; font-weight: 700;">Comenzar
          gratis</a>
        <a href="{{ route('login') }}" class="nav-login"
          style="width: 100%; display: flex; align-items: center; justify-content: center; text-align: center; padding: 11px; border-radius: 100px; font-weight: 600;">Iniciar
          sesión</a>
      @endauth
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const btn = document.getElementById('mobileMenuBtn');
      const close = document.getElementById('mobileMenuClose');
      const menu = document.getElementById('mobileMenu');
      const overlay = document.getElementById('mobileMenuOverlay');

      const openMenu = () => {
        if (menu) menu.classList.add('active');
        if (overlay) overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
      };

      const closeMenu = () => {
        if (menu) menu.classList.remove('active');
        if (overlay) overlay.classList.remove('active');
        document.body.style.overflow = '';
      };

      if (btn) btn.addEventListener('click', openMenu);
      if (close) close.addEventListener('click', closeMenu);
      if (overlay) overlay.addEventListener('click', closeMenu);

      if (menu) {
        menu.querySelectorAll('a').forEach(link => {
          link.addEventListener('click', closeMenu);
        });
      }
    });
  </script>

  {{-- PWA: Modal instrucciones iOS --}}
  <div id="ios-install-modal" style="
    display: none;
    position: fixed;
    inset: 0;
    z-index: 99998;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(6px);
    align-items: flex-end;
    justify-content: center;
    padding: 0;
  ">
    <div style="
      background: white;
      border-radius: 28px 28px 0 0;
      padding: 2rem 1.5rem 2.5rem;
      width: 100%;
      max-width: 480px;
      text-align: center;
      animation: slideUp 0.35s ease;
    ">
      <div style="width: 44px; height: 4px; background: #ddd; border-radius: 4px; margin: 0 auto 1.5rem;"></div>
      <img src="{{ asset('icons/icon-96x96.png') }}"
        style="width: 72px; border-radius: 18px; margin-bottom: 1rem; box-shadow: 0 4px 16px rgba(0,0,0,0.15);"
        alt="Viantryp">
      <h3
        style="font-family: 'Barlow', sans-serif; font-size: 1.2rem; font-weight: 800; color: #0d2b3e; margin-bottom: 0.5rem;">
        Instalar Viantryp</h3>
      <p style="font-size: 0.9rem; color: #666; line-height: 1.5; margin-bottom: 1.75rem;">Sigue estos pasos para
        instalar la app en tu iPhone:</p>
      <div style="text-align: left; display: flex; flex-direction: column; gap: 1rem; margin-bottom: 2rem;">
        <div
          style="display: flex; align-items: center; gap: 1rem; background: #f7f9f7; padding: 0.9rem 1rem; border-radius: 12px;">
          <div style="font-size: 1.5rem; flex-shrink: 0;">1️⃣</div>
          <div>
            <div style="font-weight: 700; font-size: 0.88rem; color: #0d2b3e;">Toca el botón Compartir</div>
            <div style="font-size: 0.8rem; color: #888;">El ícono <strong>↑</strong> en la barra inferior de Safari
            </div>
          </div>
        </div>
        <div
          style="display: flex; align-items: center; gap: 1rem; background: #f7f9f7; padding: 0.9rem 1rem; border-radius: 12px;">
          <div style="font-size: 1.5rem; flex-shrink: 0;">2️⃣</div>
          <div>
            <div style="font-weight: 700; font-size: 0.88rem; color: #0d2b3e;">Selecciona "Agregar a pantalla de inicio"
            </div>
            <div style="font-size: 0.8rem; color: #888;">Desplázate hacia abajo en el menú compartir</div>
          </div>
        </div>
        <div
          style="display: flex; align-items: center; gap: 1rem; background: #f7f9f7; padding: 0.9rem 1rem; border-radius: 12px;">
          <div style="font-size: 1.5rem; flex-shrink: 0;">3️⃣</div>
          <div>
            <div style="font-weight: 700; font-size: 0.88rem; color: #0d2b3e;">Toca "Agregar"</div>
            <div style="font-size: 0.8rem; color: #888;">El ícono de Viantryp aparecerá en tu pantalla</div>
          </div>
        </div>
      </div>
      <button onclick="document.getElementById('ios-install-modal').style.display='none'" style="
        width: 100%;
        padding: 0.9rem;
        background: #0d2b3e;
        color: white;
        border: none;
        border-radius: 100px;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        font-family: 'Barlow', sans-serif;
      ">Entendido</button>
    </div>
  </div>

  {{-- PWA: Banner flotante en landing (Android) --}}
  <div id="landing-pwa-banner" style="
    display: none;
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 99999;
    background: linear-gradient(135deg, #0d2b3e 0%, #1a4a6e 100%);
    color: white;
    padding: 1rem 1.25rem;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 -4px 24px rgba(0,0,0,0.3);
    border-top: 1px solid rgba(255,255,255,0.1);
    font-family: 'Barlow', sans-serif;
  ">
    <img src="{{ asset('icons/icon-72x72.png') }}" alt="Viantryp"
      style="width: 48px; height: 48px; border-radius: 12px; flex-shrink: 0;">
    <div style="flex: 1; min-width: 0;">
      <div style="font-weight: 700; font-size: 0.95rem; margin-bottom: 0.15rem;">Instalar Viantryp</div>
      <div style="font-size: 0.8rem; opacity: 0.8;">Acceso rápido desde tu pantalla de inicio</div>
    </div>
    <div style="display: flex; gap: 0.5rem; flex-shrink: 0;">
      <button id="landing-banner-dismiss"
        style="background: rgba(255,255,255,0.15); border: none; color: white; padding: 0.5rem 0.75rem; border-radius: 8px; font-size: 0.82rem; cursor: pointer; font-family: inherit;">No</button>
      <button id="landing-banner-install"
        style="background: white; color: #0d2b3e; border: none; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 700; font-size: 0.82rem; cursor: pointer; font-family: inherit;">Instalar</button>
    </div>
  </div>

  <style>
    @keyframes slideUp {
      from {
        transform: translateY(100%);
      }

      to {
        transform: translateY(0);
      }
    }
  </style>

  {{-- PWA JS --}}
  <script>
    // Detección de iOS
    const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
    const isAndroid = /Android/.test(navigator.userAgent);
    const isMobile = isIOS || isAndroid || window.innerWidth < 768;
    const isInStandaloneMode = window.navigator.standalone || window.matchMedia('(display-mode: standalone)').matches;

    let deferredPwaPrompt = null;

    // Registrar Service Worker
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
          .then(reg => console.log('[Viantryp PWA] SW registrado:', reg.scope))
          .catch(err => console.warn('[Viantryp PWA] SW error:', err));
      });
    }

    // Capturar el evento de instalación de Android/Chrome
    window.addEventListener('beforeinstallprompt', (e) => {
      e.preventDefault();
      deferredPwaPrompt = e;
      // Mostrar el banner flotante si no fue descartado recientemente
      const dismissed = localStorage.getItem('pwa-landing-dismissed');
      const banner = document.getElementById('landing-pwa-banner');
      if (banner && (!dismissed || (Date.now() - parseInt(dismissed)) > 7 * 24 * 60 * 60 * 1000)) {
        banner.style.display = 'flex';
      }
    });

    // Mostrar el botón de instalar en la landing solo en móvil y si no está ya instalada
    window.addEventListener('load', () => {
      const installBtn = document.getElementById('landing-install-btn');
      if (installBtn && isMobile && !isInStandaloneMode) {
        installBtn.style.display = 'flex';
      }
    });

    // Función principal que se llama al tocar "Instalar App"
    function triggerPwaInstall() {
      if (isIOS) {
        // En iOS mostramos el modal de instrucciones
        const modal = document.getElementById('ios-install-modal');
        if (modal) modal.style.display = 'flex';
      } else if (deferredPwaPrompt) {
        // En Android con Chrome mostramos el diálogo nativo
        deferredPwaPrompt.prompt();
        deferredPwaPrompt.userChoice.then(choice => {
          console.log('[PWA] Resultado:', choice.outcome);
          deferredPwaPrompt = null;
          document.getElementById('landing-pwa-banner').style.display = 'none';
        });
      } else {
        // Fallback: mostrar instrucciones genéricas
        const modal = document.getElementById('ios-install-modal');
        if (modal) modal.style.display = 'flex';
      }
    }

    // Banner de Android: botones
    const bannerInstall = document.getElementById('landing-banner-install');
    const bannerDismiss = document.getElementById('landing-banner-dismiss');
    if (bannerInstall) {
      bannerInstall.addEventListener('click', () => {
        if (deferredPwaPrompt) {
          deferredPwaPrompt.prompt();
          deferredPwaPrompt.userChoice.then(() => {
            deferredPwaPrompt = null;
            document.getElementById('landing-pwa-banner').style.display = 'none';
          });
        }
      });
    }
    if (bannerDismiss) {
      bannerDismiss.addEventListener('click', () => {
        document.getElementById('landing-pwa-banner').style.display = 'none';
        localStorage.setItem('pwa-landing-dismissed', Date.now());
      });
    }



    // Cerrar modal iOS al tocar el fondo
    document.getElementById('ios-install-modal').addEventListener('click', function (e) {
      if (e.target === this) this.style.display = 'none';
    });
  </script>
</body>

</html>