/**
 * Viantryp Pull-to-Refresh Module
 * Smooth native-feeling swipe down to refresh for mobile / APK / PWA views.
 */
(function() {
  // Only activate on touch-capable devices
  if (!('ontouchstart' in window) && !navigator.maxTouchPoints) return;

  let startY = 0;
  let currentY = 0;
  let isPulling = false;
  let isRefreshing = false;
  let ptrContainer = null;
  let ptrSpinner = null;
  let ptrIcon = null;

  const PULL_THRESHOLD = 65;
  const MAX_PULL = 100;

  function initPtrUI() {
    if (ptrContainer) return;

    ptrContainer = document.createElement('div');
    ptrContainer.id = 'vt-ptr-indicator';
    ptrContainer.setAttribute('aria-hidden', 'true');
    ptrContainer.style.cssText = `
      position: fixed;
      top: -60px;
      left: 50%;
      transform: translate3d(-50%, 0, 0);
      width: 44px;
      height: 44px;
      border-radius: 50%;
      background: #ffffff;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.18), 0 1px 4px rgba(0, 0, 0, 0.08);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999998;
      pointer-events: none;
      transition: transform 0.25s cubic-bezier(0.2, 0.8, 0.2, 1), opacity 0.2s ease;
      opacity: 0;
    `;

    ptrContainer.innerHTML = `
      <div id="vt-ptr-icon-wrap" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; color: #1eaace; transition: transform 0.1s linear;">
        <svg id="vt-ptr-svg-arrow" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 5v14M19 12l-7 7-7-7"/>
        </svg>
        <svg id="vt-ptr-svg-spinner" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display: none; animation: vtPtrSpin 0.8s linear infinite;">
          <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
        </svg>
      </div>
    `;

    if (!document.getElementById('vt-ptr-style')) {
      const style = document.createElement('style');
      style.id = 'vt-ptr-style';
      style.textContent = `
        @keyframes vtPtrSpin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
      `;
      document.head.appendChild(style);
    }

    document.body.appendChild(ptrContainer);
    ptrIcon = ptrContainer.querySelector('#vt-ptr-svg-arrow');
    ptrSpinner = ptrContainer.querySelector('#vt-ptr-svg-spinner');
  }

  function getScrollTop() {
    return window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
  }

  function isInsideScrollableModal(target) {
    let el = target;
    while (el && el !== document.body && el !== document.documentElement) {
      if (el.classList && (el.classList.contains('modal') || el.classList.contains('canvas-wrap') || el.classList.contains('pro-preview-overlay') || el.classList.contains('driver-popover'))) {
        if (el.scrollTop > 0) return true;
      }
      el = el.parentElement;
    }
    return false;
  }

  function onTouchStart(e) {
    if (isRefreshing) return;
    if (e.touches.length !== 1) return;

    if (getScrollTop() > 2) return;
    if (isInsideScrollableModal(e.target)) return;

    startY = e.touches[0].clientY;
    currentY = startY;
    isPulling = false;
  }

  function onTouchMove(e) {
    if (isRefreshing || startY === 0) return;
    if (e.touches.length !== 1) return;

    const y = e.touches[0].clientY;
    const diff = y - startY;

    if (getScrollTop() > 2) {
      startY = 0;
      if (isPulling) resetPtr();
      return;
    }

    if (diff > 8) {
      initPtrUI();
      isPulling = true;

      const pullDist = Math.min(MAX_PULL, diff * 0.42);
      const progress = Math.min(1, pullDist / PULL_THRESHOLD);

      ptrContainer.style.opacity = Math.min(1, progress * 1.3);
      ptrContainer.style.transform = `translate3d(-50%, ${pullDist + 20}px, 0)`;

      const rotation = progress * 180;
      ptrIcon.style.transform = `rotate(${rotation}deg)`;

      if (pullDist >= PULL_THRESHOLD) {
        ptrIcon.style.color = '#10b981';
      } else {
        ptrIcon.style.color = '#1eaace';
      }
    }
  }

  async function onTouchEnd() {
    if (!isPulling || isRefreshing) {
      startY = 0;
      return;
    }

    const diff = (currentY || startY) - startY;
    const pullDist = Math.min(MAX_PULL, diff * 0.42);

    if (pullDist >= PULL_THRESHOLD) {
      isRefreshing = true;
      try {
        if (navigator.vibrate) navigator.vibrate(15);
      } catch(e) {}

      ptrIcon.style.display = 'none';
      ptrSpinner.style.display = 'block';
      ptrContainer.style.transform = `translate3d(-50%, ${PULL_THRESHOLD + 15}px, 0)`;

      if (typeof window.performProSave === 'function' && window.tripId) {
        try {
          await window.performProSave(true);
        } catch(e) {}
      }

      setTimeout(() => {
        window.location.reload();
      }, 300);
    } else {
      resetPtr();
    }

    startY = 0;
  }

  function resetPtr() {
    if (!ptrContainer) return;
    ptrContainer.style.opacity = '0';
    ptrContainer.style.transform = 'translate3d(-50%, 0, 0)';
    setTimeout(() => {
      if (ptrIcon) {
        ptrIcon.style.display = 'block';
        ptrIcon.style.transform = 'rotate(0deg)';
        ptrIcon.style.color = '#1eaace';
      }
      if (ptrSpinner) ptrSpinner.style.display = 'none';
      isPulling = false;
      isRefreshing = false;
    }, 250);
  }

  document.addEventListener('touchstart', onTouchStart, { passive: true });
  document.addEventListener('touchmove', (e) => {
    currentY = e.touches[0].clientY;
    onTouchMove(e);
  }, { passive: true });
  document.addEventListener('touchend', onTouchEnd, { passive: true });
  document.addEventListener('touchcancel', resetPtr, { passive: true });
})();
