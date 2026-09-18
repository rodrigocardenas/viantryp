<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
  <title>Viantryp - Bienvenida</title>
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
  <link rel="manifest" href="{{ asset('manifest.json') }}">
  <meta name="theme-color" content="#0d2b3e">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  
  <!-- Fonts & Swiper CSS -->
  <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      -webkit-tap-highlight-color: transparent;
    }

    html, body {
      width: 100%;
      height: 100%;
      height: 100dvh;
      overflow: hidden;
      background: #061521;
      font-family: 'Barlow', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      color: #ffffff;
    }

    .onboarding-container {
      width: 100vw;
      height: 100vh;
      height: 100dvh;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
    }

    .onboarding-viewport {
      width: 100%;
      height: 100%;
      max-width: 480px;
      position: relative;
      overflow: hidden;
      background: #000000;
      display: flex;
      flex-direction: column;
    }

    @media (min-width: 640px) {
      .onboarding-viewport {
        height: 92vh;
        max-height: 860px;
        border-radius: 32px;
        border: 1px solid rgba(255, 255, 255, 0.15);
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.7);
      }
    }

    .swiper.onboarding-swiper {
      width: 100%;
      height: 100%;
      position: absolute;
      top: 0;
      left: 0;
      z-index: 10;
    }

    .swiper-wrapper {
      width: 100%;
      height: 100%;
    }

    .swiper-slide {
      width: 100%;
      height: 100%;
      position: relative;
      overflow: hidden;
    }

    .slide-1 { background: #166475; }
    .slide-2 { background: #e1f2f6; }
    .slide-3 { background: #e9f4f7; }

    .onboarding-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center;
      display: block;
      user-select: none;
      -webkit-user-drag: none;
    }

    .onboarding-bottom-overlay {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      z-index: 30;
      padding: 16px 20px env(safe-area-inset-bottom, 24px) 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 12px;
      background: linear-gradient(to top, rgba(5, 18, 28, 0.95) 0%, rgba(5, 18, 28, 0.5) 60%, rgba(5, 18, 28, 0) 100%);
      pointer-events: auto;
    }

    .swiper-pagination.onboarding-pagination {
      position: relative !important;
      bottom: auto !important;
      left: auto !important;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 8px;
      margin-bottom: 2px;
    }

    .swiper-pagination-bullet {
      width: 8px;
      height: 8px;
      background: rgba(255, 255, 255, 0.45);
      opacity: 1;
      border-radius: 50%;
      transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
      margin: 0 !important;
    }

    .swiper-pagination-bullet-active {
      width: 24px;
      border-radius: 12px;
      background: #cbff0b;
      box-shadow: 0 0 10px rgba(203, 255, 11, 0.85);
    }

    .onboarding-actions {
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: 10px;
      align-items: center;
      opacity: 0;
      max-height: 0;
      overflow: hidden;
      transform: translateY(20px);
      transition: opacity 0.35s ease, transform 0.35s ease, max-height 0.35s ease;
      pointer-events: none;
    }

    .onboarding-actions.visible {
      opacity: 1;
      max-height: 220px;
      transform: translateY(0);
      pointer-events: auto;
    }

    .btn-google {
      width: 100%;
      padding: 13.5px 18px;
      border-radius: 14px;
      background: #ffffff;
      color: #1f2937;
      font-size: 14.5px;
      font-weight: 700;
      text-align: center;
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.25);
      transition: all 0.2s ease;
      border: none;
    }

    .btn-google:hover, .btn-google:active {
      background: #f8fafc;
      transform: translateY(-1px);
    }

    .btn-google svg {
      width: 19px;
      height: 19px;
      flex-shrink: 0;
    }

    .btn-register {
      width: 100%;
      padding: 13.5px 18px;
      border-radius: 14px;
      background: #cbff0b;
      color: #061521;
      font-size: 14.5px;
      font-weight: 800;
      text-align: center;
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      box-shadow: 0 6px 20px rgba(203, 255, 11, 0.35);
      transition: all 0.2s ease;
      border: none;
    }

    .btn-register:hover, .btn-register:active {
      background: #b5e608;
      transform: translateY(-1px);
    }

    .login-link {
      color: rgba(255, 255, 255, 0.95);
      font-size: 13.5px;
      font-weight: 500;
      text-decoration: none;
      margin-top: 2px;
      text-shadow: 0 1px 4px rgba(0,0,0,0.5);
    }

    .login-link u {
      text-underline-offset: 3px;
      font-weight: 700;
      color: #ffffff;
    }

    .login-link:hover {
      color: #cbff0b;
    }
  </style>
</head>
<body>

<div class="onboarding-container">
  <div class="onboarding-viewport">

    <!-- Swiper Carousel (3 Slides) -->
    <div class="swiper onboarding-swiper">
      <div class="swiper-wrapper">
        <div class="swiper-slide slide-1">
          <img src="{{ asset('images/onboarding/1.jpg') }}" alt="Viantryp" class="onboarding-img" loading="eager">
        </div>
        <div class="swiper-slide slide-2">
          <img src="{{ asset('images/onboarding/2.jpg') }}" alt="Diseña tus viajes" class="onboarding-img" loading="eager">
        </div>
        <div class="swiper-slide slide-3">
          <img src="{{ asset('images/onboarding/3.jpg') }}" alt="Plasma tu viaje en 3 pasos" class="onboarding-img" loading="eager">
        </div>
      </div>
    </div>

    <!-- Bottom Controls & Action Overlay -->
    <div class="onboarding-bottom-overlay">
      <div class="swiper-pagination onboarding-pagination"></div>

      <div id="onboarding-ctas" class="onboarding-actions">
        <a href="{{ route('auth.google') }}" class="btn-google">
          <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335"/>
          </svg>
          <span>Continuar con Google</span>
        </a>

        <a href="{{ route('register') }}" class="btn-register">
          <span>Crear Cuenta</span>
        </a>

        <a href="{{ route('login') }}" class="login-link">
          ¿Ya tienes cuenta? <u>Inicia Sesión</u>
        </a>
      </div>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const ctas = document.getElementById('onboarding-ctas');
    
    const onboardingSwiper = new Swiper('.onboarding-swiper', {
      direction: 'horizontal',
      loop: false,
      speed: 350,
      grabCursor: true,
      pagination: {
        el: '.onboarding-pagination',
        clickable: true,
      },
      on: {
        init: function() {
          updateCtaVisibility(this.activeIndex);
        },
        slideChange: function() {
          updateCtaVisibility(this.activeIndex);
        }
      }
    });

    function updateCtaVisibility(index) {
      if (index === 2) {
        ctas.classList.add('visible');
      } else {
        ctas.classList.remove('visible');
      }
    }
  });
</script>

</body>
</html>
