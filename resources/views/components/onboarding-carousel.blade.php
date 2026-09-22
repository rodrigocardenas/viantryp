{{-- Onboarding Carousel Component (App-Only Edition) --}}
@guest
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<style>
  /* Full Screen Overlay - Hidden by default for standard web browsers */
  .onboarding-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    height: 100dvh;
    background: #061521;
    z-index: 999999;
    display: none; /* Default hidden: Only activated in APP mode */
    justify-content: center;
    align-items: center;
    overflow: hidden;
    font-family: 'Barlow', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    color: #ffffff;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.35s ease, visibility 0.35s ease;
  }

  .onboarding-overlay.active {
    opacity: 1;
    visibility: visible;
  }

  /* Viewport Wrapper */
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

  /* Swiper Container */
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
    padding: 0;
    margin: 0;
  }

  /* Slide Color Fill matching image header tone */
  .slide-1 { background: #1b5a6c; }
  .slide-2 { background: #cfebf6; }
  .slide-3 { background: #d1e5ee; }
  .slide-4 { background: #3889a6; }

  /* Clean PNG Image Container - Smooth 3K Ultra-HD Rendering */
  .onboarding-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center top;
    display: block;
    user-select: none;
    -webkit-user-drag: none;
    image-rendering: smooth;
    image-rendering: high-quality;
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    transform: translateZ(0);
  }

  /* Bottom Controls & Action Overlay */
  .onboarding-bottom-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 30;
    padding: 16px 20px calc(env(safe-area-inset-bottom, 24px) + 8px) 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    background: linear-gradient(to top, rgba(5, 18, 28, 0.9) 0%, rgba(5, 18, 28, 0.45) 60%, rgba(5, 18, 28, 0) 100%);
    pointer-events: auto;
  }

  /* Pagination Dots */
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
    background: rgba(13, 43, 62, 0.45);
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

  /* Responsive Action Buttons Container for Slide 4 (Last Screen) */
  .onboarding-actions {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 10px;
    align-items: center;
    margin-bottom: 8px;
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

  /* Inicia Sesión White Button */
  .btn-login {
    width: 100%;
    padding: 13.5px 18px;
    border-radius: 14px;
    background: #ffffff;
    color: #061521;
    font-size: 14.5px;
    font-weight: 800;
    text-align: center;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.25);
    transition: all 0.2s ease;
    border: none;
    box-sizing: border-box;
  }

  .btn-login:hover, .btn-login:active {
    background: #f8fafc;
    transform: translateY(-1px);
    box-shadow: 0 8px 22px rgba(0, 0, 0, 0.35);
  }

  /* Brand Color #cbff0b Button */
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
    box-sizing: border-box;
  }

  .btn-register:hover, .btn-register:active {
    background: #b5e608;
    transform: translateY(-1px);
    box-shadow: 0 8px 24px rgba(203, 255, 11, 0.5);
  }
</style>

<div id="onboarding-wrapper" class="onboarding-overlay">
  <div class="onboarding-viewport">

    <!-- Swiper Carousel (4 Slides) -->
    <div class="swiper onboarding-swiper">
      <div class="swiper-wrapper">
        <!-- Slide 1 -->
        <div class="swiper-slide slide-1">
          <img src="{{ asset('images/onboarding/app1.png') }}?v=20260921_super_hd_4k" alt="Viantryp" class="onboarding-img" loading="eager">
        </div>

        <!-- Slide 2 -->
        <div class="swiper-slide slide-2">
          <img src="{{ asset('images/onboarding/app2.png') }}?v=20260921_super_hd_4k" alt="Diseña tus viajes en cuestión de minutos" class="onboarding-img" loading="eager">
        </div>

        <!-- Slide 3 -->
        <div class="swiper-slide slide-3">
          <img src="{{ asset('images/onboarding/app3.png') }}?v=20260921_super_hd_4k" alt="Plasma tu viaje en solo 3 pasos" class="onboarding-img" loading="eager">
        </div>

        <!-- Slide 4 -->
        <div class="swiper-slide slide-4">
          <img src="{{ asset('images/onboarding/app4.png') }}?v=20260921_super_hd_4k" alt="Todo lo que necesitas en un solo lugar" class="onboarding-img" loading="eager">
        </div>
      </div>
    </div>

    <!-- Bottom Controls & Action Overlay -->
    <div class="onboarding-bottom-overlay">
      <!-- 4 Pagination Dots -->
      <div class="swiper-pagination onboarding-pagination"></div>

      <!-- Action Buttons for Slide 4 (Last Screen) -->
      <div id="onboarding-ctas" class="onboarding-actions">
        <!-- 1. Inicia Sesión Button -->
        <a href="{{ route('login') }}" class="btn-login" onclick="markOnboardingSeen()">
          <span>Inicia Sesión</span>
        </a>

        <!-- 2. Corporate Green Button (#cbff0b) -->
        <a href="{{ route('register') }}" class="btn-register" onclick="markOnboardingSeen()">
          <span>Crear Cuenta</span>
        </a>
      </div>
    </div>

  </div>
</div>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
  let onboardingSwiper = null;

  function markOnboardingSeen() {
    try {
      localStorage.setItem('has_seen_onboarding', 'true');
    } catch (e) {
      console.warn('LocalStorage error:', e);
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const forceShow = urlParams.has('onboarding');
    const isSavedAppMode = localStorage.getItem('viantryp_app_mode') === '1' || 
                           document.documentElement.classList.contains('is-viantryp-app') ||
                           (document.body && document.body.classList.contains('is-viantryp-app'));
    const isStandalonePWA = (window.matchMedia && window.matchMedia('(display-mode: standalone)').matches) ||
                            (window.navigator && window.navigator.standalone === true);
    const isNativeApp = Boolean(window.isNativeApp || (window.Capacitor && window.Capacitor.isNativePlatform && window.Capacitor.isNativePlatform()));
    const isAppParam = urlParams.has('app') || urlParams.has('pwa') || forceShow || isSavedAppMode;

    const isAppEnvironment = isStandalonePWA || isNativeApp || isAppParam || isSavedAppMode;

    // The Onboarding Carousel is ALWAYS displayed for unauthenticated users in APP mode!
    if (isAppEnvironment) {
      const wrapper = document.getElementById('onboarding-wrapper');
      const ctas = document.getElementById('onboarding-ctas');

      if (wrapper) {
        wrapper.style.display = 'flex';
        wrapper.offsetHeight; // force reflow
        wrapper.classList.add('active');

        onboardingSwiper = new Swiper('.onboarding-swiper', {
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
          if (index === 3) { // 4th slide (last slide)
            ctas.classList.add('visible');
          } else {
            ctas.classList.remove('visible');
          }
        }
      }
    }
  });
</script>
@endguest
