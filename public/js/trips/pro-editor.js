// STATE
function fixUrl(u) {
  if (!u) return '';
  if (typeof u !== 'string') return '';
  if (u.startsWith('http://') || u.startsWith('https://') || u.startsWith('data:') || u.startsWith('blob:')) return u;
  if (u.startsWith('/')) return window.location.origin + u;
  return window.location.origin + '/' + u;
}
window.fixUrl = fixUrl;

window.openProUpgradeInlineModal = function(featureTitle, featureDesc) {
  try {
    let modal = document.getElementById('viantrypInlineUpgradeModal');
    if (!modal) {
      modal = document.createElement('div');
      modal.id = 'viantrypInlineUpgradeModal';
      modal.style.cssText = 'position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(15,23,42,0.85); backdrop-filter:blur(8px); -webkit-backdrop-filter:blur(8px); z-index:9999999 !important; display:flex; align-items:center; justify-content:center; padding:16px 12px; box-sizing:border-box; font-family:\'Manrope\', sans-serif; overflow-y:auto;';
      
      const appUrl = (typeof origin !== 'undefined' && origin) ? origin : window.location.origin;
      modal.innerHTML = `
        <div class="viantryp-inline-card" style="background:#ffffff; border-radius:24px; max-width:460px; width:100%; max-height:calc(100vh - 32px); overflow-y:auto; padding:32px 24px; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); text-align:center; position:relative; box-sizing:border-box; font-family:\'Manrope\', sans-serif; margin:auto; -webkit-overflow-scrolling:touch;">
          <button onclick="document.getElementById('viantrypInlineUpgradeModal').style.display='none'" style="position:absolute; top:16px; right:16px; background:#f1f5f9; border:none; width:34px; height:34px; border-radius:50%; font-size:20px; color:#64748b; cursor:pointer; display:flex; align-items:center; justify-content:center; line-height:1; transition:0.2s;">&times;</button>
          <div style="width:56px; height:56px; background:#eff6ff; border-radius:18px; display:inline-flex; align-items:center; justify-content:center; margin-bottom:16px; color:#1eaace; font-size:24px;">
            <i class="fa-solid fa-crown"></i>
          </div>
          <h3 id="viantrypInlineModalTitle" style="font-family:\'Manrope\', sans-serif; font-size:21px; font-weight:800; color:#0f172a; margin:0 0 8px 0; letter-spacing:-0.02em; line-height:1.3;">Función Exclusiva Viajero Pro</h3>
          <p id="viantrypInlineModalDesc" style="font-family:\'Manrope\', sans-serif; font-size:13.5px; color:#64748b; margin:0 0 20px 0; line-height:1.55; font-weight:500;">Actualiza tu plan para desbloquear todas las ventajas.</p>
          
          <div id="viantrypInlineBenefitsBox" style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:16px; padding:16px; margin-bottom:24px; text-align:left; font-family:\'Manrope\', sans-serif;">
            <div id="viantrypInlineBenefitsTitle" style="font-weight:800; font-size:11.5px; color:#1e293b; margin-bottom:10px; text-transform:uppercase; letter-spacing:0.5px;">Beneficios del Plan Viajero Pro:</div>
            <div id="viantrypInlineBenefitsList">
              <div style="display:flex; align-items:center; gap:10px; font-size:13px; color:#334155; margin-bottom:8px; font-weight:600;">
                <i class="fa-solid fa-circle-check" style="color:#1eaace;"></i> <span>20 itinerarios activos</span>
              </div>
              <div style="display:flex; align-items:center; gap:10px; font-size:13px; color:#334155; margin-bottom:8px; font-weight:600;">
                <i class="fa-solid fa-circle-check" style="color:#1eaace;"></i> <span>2 colaboradores de edición de viaje</span>
              </div>
              <div style="display:flex; align-items:center; gap:10px; font-size:13px; color:#334155; margin-bottom:8px; font-weight:600;">
                <i class="fa-solid fa-circle-check" style="color:#1eaace;"></i> <span>50 consultas en Google Places activas</span>
              </div>
              <div style="display:flex; align-items:center; gap:10px; font-size:13px; color:#334155; margin-bottom:8px; font-weight:600;">
                <i class="fa-solid fa-circle-check" style="color:#1eaace;"></i> <span>20 archivos adjuntos por itinerario</span>
              </div>
              <div style="display:flex; align-items:center; gap:10px; font-size:13px; color:#334155; font-weight:600;">
                <i class="fa-solid fa-circle-check" style="color:#1eaace;"></i> <span>Exportación de PDF</span>
              </div>
            </div>
          </div>

          <div style="display:flex; flex-direction:column; gap:10px; font-family:\'Manrope\', sans-serif;">
            <button id="viantrypInlineCtaBtn" onclick="if(typeof openUpgradeModal === 'function'){ document.getElementById('viantrypInlineUpgradeModal').style.display='none'; openUpgradeModal(true); } else { window.location.href='${appUrl}/profile?tab=subscription'; }" style="display:block; width:100%; padding:14px; background:#1eaace; color:#ffffff; font-weight:700; font-size:14px; border-radius:14px; border:none; cursor:pointer; text-decoration:none; box-sizing:border-box; transition:all 0.2s; font-family:\'Manrope\', sans-serif;">
              Mejorar Mi Plan Ahora →
            </button>
            <button onclick="document.getElementById('viantrypInlineUpgradeModal').style.display='none'" style="background:none; border:none; color:#64748b; font-size:13px; font-weight:600; cursor:pointer; padding:8px; font-family:\'Manrope\', sans-serif;">
              Quizás más tarde
            </button>
          </div>
        </div>
      `;
      document.body.appendChild(modal);
    }

    const currentPlan = (typeof window.viantrypUserPlan === 'string' && window.viantrypUserPlan) ? window.viantrypUserPlan.toLowerCase() : 'básico';
    const isProPlanUser = (currentPlan === 'avanzado' || currentPlan === 'viajero pro');

    const titleEl = document.getElementById('viantrypInlineModalTitle');
    const descEl = document.getElementById('viantrypInlineModalDesc');
    const benTitleEl = document.getElementById('viantrypInlineBenefitsTitle');
    const benListEl = document.getElementById('viantrypInlineBenefitsList');
    const ctaBtn = document.getElementById('viantrypInlineCtaBtn');

    if (isProPlanUser) {
      if (titleEl) titleEl.textContent = featureTitle || 'Límite del Plan Viajero Pro Alcanzado';
      if (descEl) descEl.textContent = featureDesc || 'Has alcanzado el límite de tu Plan Viajero Pro. Actualiza a Plan Negocios para obtener herramientas ilimitadas.';
      if (benTitleEl) benTitleEl.textContent = 'Beneficios del Plan Negocios:';
      if (benListEl) {
        benListEl.innerHTML = `
          <div style="display:flex; align-items:center; gap:10px; font-size:13px; color:#334155; margin-bottom:8px; font-weight:600;">
            <i class="fa-solid fa-circle-check" style="color:#1eaace;"></i> <span>Itinerarios activos ilimitados</span>
          </div>
          <div style="display:flex; align-items:center; gap:10px; font-size:13px; color:#334155; margin-bottom:8px; font-weight:600;">
            <i class="fa-solid fa-circle-check" style="color:#1eaace;"></i> <span>Colaboradores de edición ilimitados</span>
          </div>
          <div style="display:flex; align-items:center; gap:10px; font-size:13px; color:#334155; margin-bottom:8px; font-weight:600;">
            <i class="fa-solid fa-circle-check" style="color:#1eaace;"></i> <span>Consultas en Google Places ilimitadas</span>
          </div>
          <div style="display:flex; align-items:center; gap:10px; font-size:13px; color:#334155; margin-bottom:8px; font-weight:600;">
            <i class="fa-solid fa-circle-check" style="color:#1eaace;"></i> <span>Archivos adjuntos ilimitados</span>
          </div>
          <div style="display:flex; align-items:center; gap:10px; font-size:13px; color:#334155; font-weight:600;">
            <i class="fa-solid fa-circle-check" style="color:#1eaace;"></i> <span>Marca Blanca con Logo de Agencia</span>
          </div>
        `;
      }
      if (ctaBtn) ctaBtn.textContent = 'Mejorar a Plan Negocios →';
    } else {
      if (titleEl) titleEl.textContent = featureTitle || 'Función Exclusiva Viajero Pro';
      if (descEl) descEl.textContent = featureDesc || 'Actualiza tu plan para desbloquear todas las ventajas.';
      if (benTitleEl) benTitleEl.textContent = 'Beneficios del Plan Viajero Pro:';
      if (benListEl) {
        benListEl.innerHTML = `
          <div style="display:flex; align-items:center; gap:10px; font-size:13px; color:#334155; margin-bottom:8px; font-weight:600;">
            <i class="fa-solid fa-circle-check" style="color:#1eaace;"></i> <span>20 itinerarios activos</span>
          </div>
          <div style="display:flex; align-items:center; gap:10px; font-size:13px; color:#334155; margin-bottom:8px; font-weight:600;">
            <i class="fa-solid fa-circle-check" style="color:#1eaace;"></i> <span>2 colaboradores de edición de viaje</span>
          </div>
          <div style="display:flex; align-items:center; gap:10px; font-size:13px; color:#334155; margin-bottom:8px; font-weight:600;">
            <i class="fa-solid fa-circle-check" style="color:#1eaace;"></i> <span>50 consultas en Google Places activas</span>
          </div>
          <div style="display:flex; align-items:center; gap:10px; font-size:13px; color:#334155; margin-bottom:8px; font-weight:600;">
            <i class="fa-solid fa-circle-check" style="color:#1eaace;"></i> <span>20 archivos adjuntos por itinerario</span>
          </div>
          <div style="display:flex; align-items:center; gap:10px; font-size:13px; color:#334155; font-weight:600;">
            <i class="fa-solid fa-circle-check" style="color:#1eaace;"></i> <span>Exportación de PDF</span>
          </div>
        `;
      }
      if (ctaBtn) ctaBtn.textContent = 'Mejorar Mi Plan Ahora →';
    }

    modal.style.display = 'flex';
  } catch (e) {
    console.error('Error in openProUpgradeInlineModal:', e);
  }
};

function parseVideoEmbed(url) {
  if (!url || typeof url !== 'string') return { valid: false };
  const u = url.trim();

  // YouTube Standard
  let m = u.match(/(?:youtube\.com\/(?:watch\?v=|embed\/|v\/)|youtu\.be\/)([^"&?\/\s]{11})/i);
  if (m && m[1]) {
    return {
      valid: true,
      platform: 'youtube',
      format: 'horizontal',
      aspect: '16/9',
      embedUrl: `https://www.youtube-nocookie.com/embed/${m[1]}?rel=0`
    };
  }

  // YouTube Shorts
  m = u.match(/youtube\.com\/shorts\/([^"&?\/\s]{11})/i);
  if (m && m[1]) {
    return {
      valid: true,
      platform: 'youtube-shorts',
      format: 'vertical',
      aspect: '9/16',
      embedUrl: `https://www.youtube-nocookie.com/embed/${m[1]}?rel=0`
    };
  }

  // Vimeo
  m = u.match(/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/(?:[^\/]*)\/videos\/|album\/(?:\d+)\/video\/|video\/|)(\d+)/i);
  if (m && m[1]) {
    return {
      valid: true,
      platform: 'vimeo',
      format: 'horizontal',
      aspect: '16/9',
      embedUrl: `https://player.vimeo.com/video/${m[1]}?dnt=1`
    };
  }

  // TikTok
  m = u.match(/tiktok\.com\/(?:@[^\/]+\/video\/|v\/|embed\/v2\/)(\d+)/i);
  if (m && m[1]) {
    return {
      valid: true,
      platform: 'tiktok',
      format: 'vertical',
      aspect: '9/16',
      embedUrl: `https://www.tiktok.com/embed/v2/${m[1]}`
    };
  }

  // Instagram Reel / Post
  m = u.match(/instagram\.com\/(?:reel|p)\/([a-zA-Z0-9_-]+)/i);
  if (m && m[1]) {
    return {
      valid: true,
      platform: 'instagram',
      format: 'vertical',
      aspect: '9/16',
      embedUrl: `https://www.instagram.com/reel/${m[1]}/embed/`
    };
  }

  // Direct Video (mp4/webm/mov/m4v)
  if (/\.(mp4|webm|mov|m4v)(\?.*)?$/i.test(u)) {
    return {
      valid: true,
      platform: 'direct',
      format: 'horizontal',
      aspect: '16/9',
      videoUrl: u
    };
  }

  return { valid: false };
}
window.parseVideoEmbed = parseVideoEmbed;

let days = [[]];
let dayDates = ['']; // per-day date strings (yyyy-mm-dd)
let portadaItems = [];
let cierreItems = [];
let currentDay = 'portada';
let dayCount = 1, numericDayCount = 1, nextDayNumber = 2; // nextDayNumber always increments up
let dragType = null, dragLabel = null, dragSourceIndex = null, dragSourceContainer = null;
let pendingType = null, editingIndex = null, starRating = 0;
let dragTabSourceIndex = null;
let portadaAdultos = 2, portadaNinos = 0;
let portadaPhotoUrl = '';
let isPriceManual = false;
let hidePriceInPublic = false;
let hideTravelersInPublic = false;
let portadaSubtitle = '';
const GIPHY_API_KEY = 'ga2U6DfG1RcG9EESPkiPph7sMM0uhrdy';
let selectedUnsplashUrl = null, unsplashTarget = 'portada';
let selectedGiphyUrl = null, giphyTarget = 'canvas';
let confirmCallback = null;
let unsavedChanges = false;
let currentPhotoTargetInput = null;
let lastAutoCalculatedSum = 0;

// --- HERO & EDITORIAL CONTROLS ---
function togglePhotoMenu(e) {
  e && e.stopPropagation();
  const menu = document.getElementById('portadaPhotoMenu');
  if (menu) menu.classList.toggle('open');
}
function closePhotoMenu() {
  const menu = document.getElementById('portadaPhotoMenu');
  if (menu) menu.classList.remove('open');
}

function autoResizeTextarea(el) {
  if (!el) return;
  el.style.height = 'auto';
  el.style.height = Math.max(26, el.scrollHeight) + 'px';
  portadaSubtitle = el.value;
  unsavedChanges = true;
  autoSaveProTrip();
}

// --- FECHAS & POPOVER ---
function toggleDatesPopover(e) {
  e && e.stopPropagation();
  closeTravelersPopover();
  closePhotoMenu();
  const pop = document.getElementById('popoverDates');
  if (pop) pop.classList.toggle('open');
}
function closeDatesPopover() {
  const pop = document.getElementById('popoverDates');
  if (pop) pop.classList.remove('open');
}

function clearTripDates() {
  const pi = document.getElementById('portadaFechaInicio');
  const pf = document.getElementById('portadaFechaFin');
  if (pi) pi.value = '';
  if (pf) pf.value = '';
  updatePortadaDatesUI();
  closeDatesPopover();
  unsavedChanges = true;
  autoSaveProTrip();
}

function applyTripDates() {
  const pi = document.getElementById('portadaFechaInicio');
  const pf = document.getElementById('portadaFechaFin');
  const startDate = pi ? pi.value : '';
  if (startDate) {
    for (let i = 0; i < days.length; i++) {
      if (!dayDates[i]) {
        dayDates[i] = addDaysToDate(startDate, i);
      }
    }
    days.forEach((dayItems, idx) => {
      if (dayItems) sortDayItemsChronologically(dayItems, idx);
    });
    renderTabs();
    renderCanvas();
  }
  updatePortadaDatesUI();
  closeDatesPopover();
  unsavedChanges = true;
  autoSaveProTrip();
}

function formatDateRange(startStr, endStr) {
  if (!startStr && !endStr) return 'Sin fechas';
  const months = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];

  const parseDatePart = (str) => {
    if (!str) return null;
    const parts = str.split('-');
    if (parts.length < 3) return null;
    return { y: parts[0], m: parseInt(parts[1], 10) - 1, d: parseInt(parts[2], 10) };
  };

  const s = parseDatePart(startStr);
  const e = parseDatePart(endStr);

  if (s && e) {
    if (s.y === e.y) {
      if (s.m === e.m) {
        return `${s.d} ➔ ${e.d} ${months[e.m]}`;
      }
      return `${s.d} ${months[s.m]} ➔ ${e.d} ${months[e.m]}`;
    }
    return `${s.d} ${months[s.m]} ${s.y} ➔ ${e.d} ${months[e.m]} ${e.y}`;
  }
  if (s) return `Desde ${s.d} ${months[s.m]}`;
  if (e) return `Hasta ${e.d} ${months[e.m]}`;
  return 'Sin fechas';
}

function calculateNights(startStr, endStr) {
  if (!startStr || !endStr) return 'Sin programar';
  const d1 = new Date(startStr + 'T00:00:00');
  const d2 = new Date(endStr + 'T00:00:00');
  const diffTime = d2 - d1;
  const diffDays = Math.round(diffTime / (1000 * 60 * 60 * 24));
  if (isNaN(diffDays) || diffDays < 0) return 'Sin programar';
  if (diffDays === 0) return 'Mismo día (0 noches)';
  if (diffDays === 1) return '1 noche';
  return `${diffDays} noches`;
}

function calculateTripDurationTag(startStr, endStr) {
  if (!startStr || !endStr) return 'Aventura por programar';
  const d1 = new Date(startStr + 'T00:00:00');
  const d2 = new Date(endStr + 'T00:00:00');
  const diffTime = d2 - d1;
  const diffDays = Math.round(diffTime / (1000 * 60 * 60 * 24)) + 1;
  if (isNaN(diffDays) || diffDays <= 0) return 'Aventura por programar';
  if (diffDays === 1) return '1 día de aventura';
  return `${diffDays} días de aventura`;
}

function updatePortadaDatesUI() {
  const pi = document.getElementById('portadaFechaInicio');
  const pf = document.getElementById('portadaFechaFin');
  const s = pi ? pi.value : '';
  const e = pf ? pf.value : '';

  const rangeEl = document.getElementById('displayDateRange');
  const nightsEl = document.getElementById('displayNightsCount');
  const durationBadge = document.getElementById('portadaDurationBadge');

  if (rangeEl) rangeEl.textContent = formatDateRange(s, e);
  if (nightsEl) nightsEl.textContent = calculateNights(s, e);
  if (durationBadge) durationBadge.textContent = calculateTripDurationTag(s, e);
}

// --- VIAJEROS & POPOVER ---
function toggleTravelersPopover(e) {
  e && e.stopPropagation();
  closeDatesPopover();
  closePhotoMenu();
  const pop = document.getElementById('popoverTravelers');
  if (pop) pop.classList.toggle('open');
}
function closeTravelersPopover() {
  const pop = document.getElementById('popoverTravelers');
  if (pop) pop.classList.remove('open');
}

function updatePortadaTravelersUI() {
  const total = portadaAdultos + portadaNinos;
  const mainEl = document.getElementById('displayTravelersMain');
  const subEl = document.getElementById('displayTravelersSub');
  const totalEl = document.getElementById('portadaTotal');

  if (mainEl) {
    mainEl.textContent = `${portadaAdultos} ${portadaAdultos === 1 ? 'adulto' : 'adultos'}`;
  }
  if (subEl) {
    subEl.textContent = `${portadaNinos} ${portadaNinos === 1 ? 'niño' : 'niños'} (${total} en total)`;
  }
  if (totalEl) totalEl.textContent = total;

  updatePortadaPriceUI();
}

function changePortadaCount(type, d) {
  if (type === 'adultos') {
    portadaAdultos = Math.max(1, portadaAdultos + d);
    const adEl = document.getElementById('portadaAdultos');
    if (adEl) adEl.textContent = portadaAdultos;
  } else {
    portadaNinos = Math.max(0, portadaNinos + d);
    const niEl = document.getElementById('portadaNinos');
    if (niEl) niEl.textContent = portadaNinos;
  }
  updatePortadaTravelersUI();
  unsavedChanges = true;
  autoSaveProTrip();
}

function handlePortadaUpload(e) {
  const f = e.target.files[0];
  if (!f) return;
  if (f.size > 5 * 1024 * 1024) { showToast('⚠️', 'La imagen no puede superar 5MB'); return; }
  const r = new FileReader();
  r.onload = ev => {
    portadaPhotoUrl = ev.target.result;
    setPortadaPhoto(ev.target.result);
  };
  r.readAsDataURL(f);
}

function setPortadaPhoto(url) {
  portadaPhotoUrl = url;
  const img = document.getElementById('portadaHeroImg');
  if (img) {
    img.src = url;
    img.classList.add('visible');
  }
  const hero = document.getElementById('portadaHero');
  if (hero) hero.classList.add('has-image');

  const btnLabel = document.getElementById('portadaPhotoBtnLabel');
  if (btnLabel) btnLabel.textContent = 'Cambiar foto';
  const removeBtn = document.getElementById('photoMenuRemoveBtn');
  if (removeBtn) removeBtn.style.display = 'flex';
  const divider = document.getElementById('photoMenuDivider');
  if (divider) divider.style.display = 'block';

  unsavedChanges = true;
  autoSaveProTrip();
}

function clearPortadaPhoto(e) {
  e && e.stopPropagation();
  portadaPhotoUrl = '';
  const img = document.getElementById('portadaHeroImg');
  if (img) {
    img.src = '';
    img.classList.remove('visible');
  }
  const hero = document.getElementById('portadaHero');
  if (hero) hero.classList.remove('has-image');

  const btnLabel = document.getElementById('portadaPhotoBtnLabel');
  if (btnLabel) btnLabel.textContent = 'Agregar foto';
  const removeBtn = document.getElementById('photoMenuRemoveBtn');
  if (removeBtn) removeBtn.style.display = 'none';
  const divider = document.getElementById('photoMenuDivider');
  if (divider) divider.style.display = 'none';

  unsavedChanges = true;
  autoSaveProTrip();
}

function isUserPremium() {
  const userPlan = (typeof window.viantrypUserPlan === 'string' && window.viantrypUserPlan) ? window.viantrypUserPlan.toLowerCase() : 'básico';
  const isTrial = window.viantrypIsTrialActive === true;
  if (isTrial) return true;
  return ['avanzado', 'colaborativo', 'corporativo', 'viajero pro', 'negocios'].includes(userPlan);
}

function countUnsplashPhotosInEditor() {
  let count = 0;
  if (typeof portadaPhotoUrl === 'string' && portadaPhotoUrl.toLowerCase().includes('unsplash')) {
    count++;
  }
  const checkItem = (item) => {
    if (!item) return;
    const url = (item.photo_url || item.image || (item.data && item.data.url) || item.url || '').toLowerCase();
    const type = (item.type || '').toLowerCase();
    if (url.includes('unsplash') || type === 'unsplash') {
      count++;
    }
  };
  if (Array.isArray(portadaItems)) portadaItems.forEach(checkItem);
  if (Array.isArray(cierreItems)) cierreItems.forEach(checkItem);
  if (typeof days === 'object' && days !== null) {
    Object.values(days).forEach(day => {
      if (Array.isArray(day)) day.forEach(checkItem);
    });
  }
  return count;
}

function countGifsInEditor() {
  let count = 0;
  if (typeof portadaPhotoUrl === 'string') {
    const pUrl = portadaPhotoUrl.toLowerCase();
    if (pUrl.includes('giphy') || pUrl.includes('.gif')) {
      count++;
    }
  }
  const checkItem = (item) => {
    if (!item) return;
    const url = (item.photo_url || item.image || (item.data && item.data.url) || item.url || '').toLowerCase();
    const type = (item.type || '').toLowerCase();
    if (url.includes('giphy') || url.includes('.gif') || type === 'giphy') {
      count++;
    }
  };
  if (Array.isArray(portadaItems)) portadaItems.forEach(checkItem);
  if (Array.isArray(cierreItems)) cierreItems.forEach(checkItem);
  if (typeof days === 'object' && days !== null) {
    Object.values(days).forEach(day => {
      if (Array.isArray(day)) day.forEach(checkItem);
    });
  }
  return count;
}

function checkUnsplashLimit() {
  return true;
}

function checkGiphyLimit() {
  return true;
}

// UNSPLASH
function openUnsplash(target = 'portada', targetInput = null) {
  if (!checkUnsplashLimit()) return;
  unsplashTarget = target;
  currentPhotoTargetInput = targetInput;
  selectedUnsplashUrl = null;
  document.getElementById('unsplashSelectBtn').disabled = true;
  document.getElementById('unsplashOverlay').classList.add('open');
  const grid = document.getElementById('unsplashGrid');
  grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:25px 20px;color:var(--text-dim);font-size:12px">Escribe algo para encontrar miles de imágenes...</div>';

  // Sugerencias iniciales
  const tripParam = window.tripId ? `&trip_id=${window.tripId}` : '';
  fetch(`/api/unsplash/search?query=travel&per_page=15${tripParam}`)
    .then(res => {
      if (res.status === 403) {
        return res.json().then(errData => {
          closeUnsplash();
          if (typeof openUpgradeModal === 'function') openUpgradeModal();
          throw new Error(errData.message || 'Límite alcanzado');
        });
      }
      return res.json();
    })
    .then(data => {
      if (data && data.success && data.images && data.images.length > 0) {
        renderUnsplashGrid(data.images);
      }
    })
    .catch(err => console.warn('Unsplash init search warning:', err));
}
function closeUnsplash() { document.getElementById('unsplashOverlay').classList.remove('open'); selectedUnsplashUrl = null }
function searchUnsplash() {
  const query = document.getElementById('unsplashSearch').value.trim();
  if (!query) {
    showToast('⚠️', 'Escribe algo para buscar');
    return;
  }
  const grid = document.getElementById('unsplashGrid');
  grid.innerHTML = '<div class="unsplash-loading"><div class="spinner"></div> Buscando imágenes...</div>';

  const tripParam = window.tripId ? `&trip_id=${window.tripId}` : '';
  fetch(`/api/unsplash/search?query=${encodeURIComponent(query)}&per_page=15${tripParam}`)
    .then(res => {
      if (res.status === 403) {
        return res.json().then(errData => {
          closeUnsplash();
          if (typeof openUpgradeModal === 'function') openUpgradeModal();
          throw new Error(errData.message || 'Límite alcanzado');
        });
      }
      return res.json();
    })
    .then(data => {
      if (data && data.success && data.images && data.images.length > 0) {
        renderUnsplashGrid(data.images);
      } else {
        grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--text-dim)">No se encontraron imágenes para esta búsqueda.</div>';
      }
    })
    .catch(err => {
      console.error('Unsplash error:', err);
      if (!err.message || !err.message.includes('Límite alcanzado')) {
        grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:40px;color:#f0567a">Error de conexión con Unsplash.</div>';
      }
    });
}
function renderUnsplashGrid(imgs) {
  const g = document.getElementById('unsplashGrid'); g.innerHTML = '';
  imgs.forEach(imgData => {
    const urlTip = imgData.url_thumb || imgData.url || imgData;
    const urlFull = imgData.url_full || urlTip;
    const img = document.createElement('img');
    img.className = 'unsplash-img';
    img.src = urlTip;
    img.loading = 'lazy';
    img.addEventListener('click', () => {
      g.querySelectorAll('.unsplash-img').forEach(i => i.classList.remove('selected'));
      img.classList.add('selected');
      selectedUnsplashUrl = urlFull;
      const btn = document.getElementById('unsplashSelectBtn');
      if (btn) btn.disabled = false;
    });
    img.addEventListener('dblclick', () => {
      selectedUnsplashUrl = urlFull;
      confirmUnsplash();
    });
    g.appendChild(img);
  });
}
function confirmUnsplash() {
  if (!checkUnsplashLimit()) {
    closeUnsplash();
    return;
  }
  if (selectedUnsplashUrl) {
    if (unsplashTarget === 'portada') {
      setPortadaPhoto(selectedUnsplashUrl);
      showToast('🌅', 'Foto aplicada');
    } else if (unsplashTarget === 'gallery_photo' && currentPhotoTargetInput) {
      let photos = [];
      try {
        if (Array.isArray(currentPhotoTargetInput.value)) photos = currentPhotoTargetInput.value;
        else if (currentPhotoTargetInput.value && currentPhotoTargetInput.value.startsWith('[')) photos = JSON.parse(currentPhotoTargetInput.value);
        else photos = currentPhotoTargetInput.value ? currentPhotoTargetInput.value.split(',').map(s => s.trim()).filter(Boolean) : [];
      } catch {
        photos = currentPhotoTargetInput.value ? currentPhotoTargetInput.value.split(',').map(s => s.trim()).filter(Boolean) : [];
      }
      if (photos.length < 5) {
        photos.push(selectedUnsplashUrl);
        currentPhotoTargetInput.value = JSON.stringify(photos);
        currentPhotoTargetInput.dispatchEvent(new Event('input', { bubbles: true }));
        currentPhotoTargetInput.dispatchEvent(new Event('change', { bubbles: true }));
        showToast('📸', `Foto ${photos.length}/5 añadida`);
      } else {
        showToast('⚠️', 'Máximo 5 fotos alcanzado');
      }
    } else if ((unsplashTarget === 'image_photo' || unsplashTarget === 'item_image') && currentPhotoTargetInput) {
      currentPhotoTargetInput.value = selectedUnsplashUrl;
      currentPhotoTargetInput.dispatchEvent(new Event('input', { bubbles: true }));
      currentPhotoTargetInput.dispatchEvent(new Event('change', { bubbles: true }));
      showToast('📸', 'Foto seleccionada');
    } else {
      const targetInp = currentPhotoTargetInput || (modalBody ? (modalBody.querySelector('input[data-key="url"]') || modalBody.querySelector('input[data-key="photo_url"]')) : null);
      if (targetInp) {
        if (targetInp.dataset.key === 'photo_url') {
          let urls = targetInp.value ? targetInp.value.split(',').map(s => s.trim()).filter(Boolean) : [];
          if (urls.length < 3) {
            urls.push(selectedUnsplashUrl);
            targetInp.value = urls.join(',');
            showToast('📸', `Foto añadida (${urls.length}/3)`);
          } else {
            showToast('⚠️', 'Máximo 3 fotos alcanzado. Elimina una para añadir otra.');
          }
        } else {
          targetInp.value = selectedUnsplashUrl;
          showToast('📸', 'Foto seleccionada');
        }
        targetInp.dispatchEvent(new Event('input', { bubbles: true }));
        targetInp.dispatchEvent(new Event('change', { bubbles: true }));
        const box = targetInp.closest('.img-picker-box') || (targetInp.parentElement && targetInp.parentElement.querySelector('.img-picker-box')) || (modalBody ? modalBody.querySelector('.img-picker-box') : null);
        if (box) {
          box.classList.add('has-preview');
          const pImg = box.querySelector('.img-picker-preview-img');
          const pWrap = box.querySelector('.img-picker-preview-wrap');
          const aRow = box.querySelector('.img-picker-actions');
          if (pImg) pImg.src = fixUrl(selectedUnsplashUrl);
          if (pWrap) pWrap.style.display = 'flex';
          if (aRow) aRow.style.display = 'none';
        }
      } else if (unsplashTarget === 'canvas') {
        const arr = currentDay === 'portada' ? portadaItems : currentDay === 'cierre' ? cierreItems : days[currentDay];
        arr.push({ type: 'imagen', data: { url: selectedUnsplashUrl, tamano: 'Mediano (100% x 380 px)' } });
        renderCanvas();
        showToast('🖼️', 'Imagen agregada');
      }
    }
  }
  closeUnsplash();
  autoSaveProTrip();
}

// GIPHY
let currentGiphyTargetInput = null;
function openGiphy(target = 'item_gif', targetInput = null) {
  if (!checkGiphyLimit()) return;
  giphyTarget = target;
  currentGiphyTargetInput = targetInput;
  selectedGiphyUrl = null;
  document.getElementById('giphySelectBtn').disabled = true;
  document.getElementById('giphyOverlay').classList.add('open');
  const grid = document.getElementById('giphyGrid');
  grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:25px 20px;color:var(--text-dim);font-size:12px">Busca el GIF perfecto para tu viaje...</div>';

  // Trending inicial
  fetch(`https://api.giphy.com/v1/gifs/trending?api_key=${GIPHY_API_KEY}&limit=15&rating=g`)
    .then(res => res.json())
    .then(json => {
      if (json.data && json.data.length > 0) renderGiphyGrid(json.data);
    });
}
function closeGiphy() {
  document.getElementById('giphyOverlay')?.classList.remove('open');
  selectedGiphyUrl = null;
  const btn = document.getElementById('giphySelectBtn');
  if (btn) btn.disabled = true;
}
function searchGiphy() {
  const query = document.getElementById('giphySearch').value.trim();
  if (!query) { showToast('⚠️', 'Escribe algo para buscar'); return; }
  const grid = document.getElementById('giphyGrid');
  grid.innerHTML = '<div class="unsplash-loading"><div class="spinner"></div> Buscando GIFs...</div>';

  fetch(`https://api.giphy.com/v1/gifs/search?api_key=${GIPHY_API_KEY}&q=${encodeURIComponent(query)}&limit=15&rating=g`)
    .then(res => res.json())
    .then(json => {
      if (json.data && json.data.length > 0) {
        renderGiphyGrid(json.data);
      } else {
        grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--text-dim)">No se encontraron GIFs.</div>';
      }
    });
}
function renderGiphyGrid(gifs) {
  const g = document.getElementById('giphyGrid'); g.innerHTML = '';
  gifs.forEach(gif => {
    const urlTip = gif.images.fixed_height_small.url;
    const urlFull = gif.images.original.url;
    const img = document.createElement('img');
    img.className = 'unsplash-img';
    img.src = urlTip;
    img.onclick = () => {
      g.querySelectorAll('.unsplash-img').forEach(i => i.classList.remove('selected'));
      img.classList.add('selected');
      selectedGiphyUrl = urlFull;
      const btn = document.getElementById('giphySelectBtn');
      if (btn) btn.disabled = false;
    };
    img.ondblclick = () => {
      selectedGiphyUrl = urlFull;
      confirmGiphy();
    };
    g.appendChild(img);
  });
}
function confirmGiphy() {
  if (!checkGiphyLimit()) {
    closeGiphy();
    return;
  }
  if (selectedGiphyUrl) {
    const targetInp = currentGiphyTargetInput || (modalBody ? modalBody.querySelector('input[data-key="url"]') : null);
    if (targetInp && (giphyTarget === 'item_gif' || (modalOverlay && modalOverlay.classList.contains('open')))) {
      targetInp.value = selectedGiphyUrl;
      targetInp.dispatchEvent(new Event('input', { bubbles: true }));
      targetInp.dispatchEvent(new Event('change', { bubbles: true }));
      const box = targetInp.closest('.img-picker-box') || (targetInp.parentElement && targetInp.parentElement.querySelector('.img-picker-box')) || (modalBody ? modalBody.querySelector('.img-picker-box') : null);
      if (box) {
        box.classList.add('has-preview');
        const pImg = box.querySelector('.img-picker-preview-img');
        const pWrap = box.querySelector('.img-picker-preview-wrap');
        const aRow = box.querySelector('.img-picker-actions');
        if (pImg) pImg.src = fixUrl(selectedGiphyUrl);
        if (pWrap) pWrap.style.display = 'flex';
        if (aRow) aRow.style.display = 'none';
      }
      showToast('⚡', 'GIF seleccionado');
    } else {
      const arr = currentDay === 'portada' ? portadaItems : currentDay === 'cierre' ? cierreItems : days[currentDay];
      arr.push({ type: 'gif', data: { url: selectedGiphyUrl, tamano: 'Mediano (100% x 380 px)' } });
      renderCanvas();
      showToast('<i class="fa-solid fa-bolt"></i>', 'GIF agregado');
    }
  }
  closeGiphy();
  autoSaveProTrip();
}

document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('giphySearch')?.addEventListener('keydown', e => { if (e.key === 'Enter') searchGiphy() });
  document.getElementById('unsplashSearch')?.addEventListener('keydown', e => { if (e.key === 'Enter') searchUnsplash() });

  // Sync title input with autosave
  const titleInput = document.getElementById('portadaTitle');
  if (titleInput) {
    titleInput.addEventListener('input', () => {
      unsavedChanges = true;
      autoSaveProTrip();
    });
  }

  // Real-time price formatting
  const priceInput = document.getElementById('portadaPrecio');
  if (priceInput) {
    priceInput.addEventListener('keypress', allowPriceKeys);
    priceInput.addEventListener('input', (e) => {
      formatPriceInput(e);
      unsavedChanges = true;
      autoSaveProTrip();
    });
  }
});
function hideCierreCard() {
  const card = document.getElementById('cierreCardMain');
  const placeholder = document.getElementById('cierreCardPlaceholder');
  if (card && placeholder) {
    card.style.display = 'none';
    placeholder.style.display = 'flex';
    showToast('🗑', 'Tarjeta de cierre ocultada');
  }
}
function showCierreCard() {
  const card = document.getElementById('cierreCardMain');
  const placeholder = document.getElementById('cierreCardPlaceholder');
  if (card && placeholder) {
    card.style.display = 'block';
    placeholder.style.display = 'none';
    showToast('✨', 'Diseño de cierre restaurado');
  }
}

// CONFIGS
const C = {
  flight: { icon: '<i class="fa-solid fa-plane"></i>', label: 'Vuelo', color: '#0ea5e9', bg: '#e0f2fe', fields: [{ k: 'origen', l: 'Ciudad origen', t: 'text', ph: 'Cód. IATA o ciudad', airportApi: true }, { k: 'destino', l: 'Ciudad destino', t: 'text', ph: 'Cód. IATA o ciudad', airportApi: true }, { k: 'aerolinea', l: 'Aerolínea', t: 'text', ph: 'Air France', airlineApi: true }, { k: 'vuelo', l: 'No. de vuelo', t: 'text', ph: 'AF9474' }, { k: 'salida', l: 'Salida', t: 'datetime-local' }, { k: 'llegada', l: 'Llegada', t: 'datetime-local' }, { k: 'clase', l: 'Clase', t: 'select', ph: 'Selecciona...', opts: ['Económica', 'Ejecutiva', 'Primera'] }, { k: 'precio', l: 'Precio', t: 'number', ph: '800' }, { k: 'reserva', l: 'Código reserva', t: 'text', ph: 'VLO-12345' }, { k: 'adjunto', l: 'Archivo adjunto', t: 'file-upload', fw: true }, { k: 'notas', l: 'Notas', t: 'textarea', ph: 'Info adicional...' }] },
  alojamiento: { icon: '<i class="fa-solid fa-hotel"></i>', label: 'Alojamiento', color: '#f0567a', bg: '#fde8ee', fields: [{ k: 'tipo_alojamiento', l: 'Tipo de Alojamiento', t: 'select', opts: ['Hotel', 'Airbnb u otro'], fw: true }, { k: 'nombre', l: 'Nombre del hotel', t: 'text', ph: 'Hotel Luxe París', fw: true, hasInfo: true }, { k: 'direccion', l: 'Dirección', t: 'text', ph: 'Escribe o busca en Google Maps...', group: 'google', fw: true, hasPin: true }, { k: 'phone', l: 'Teléfono', t: 'text', ph: '+1 234...', group: 'google' }, { k: 'website', l: 'Sitio Web', t: 'text', ph: 'https://...', group: 'google' }, { k: 'stars', l: 'Calificación', t: 'stars', group: 'google' }, { k: 'photo_url', l: 'Foto seleccionada', t: 'text', ph: 'https://...', group: 'google' }, { k: 'checkin', l: 'Check-in', t: 'datetime-local' }, { k: 'checkout', l: 'Check-out', t: 'datetime-local' }, { k: 'habitacion', l: 'Tipo habitación', t: 'select', ph: 'Selecciona...', opts: ['Sencilla', 'Doble', 'Triple', 'Suite', 'Alojamiento entero', 'Habitaciones mixtas'] }, { k: 'alimentacion', l: 'Alimentación', t: 'select', ph: 'Selecciona...', opts: ['Solo alojamiento', 'Desayuno incluido', 'Media pensión', 'Pensión completa', 'Todo incluido'] }, { k: 'reserva', l: 'Código reserva', t: 'text', ph: 'ALJ-12345' }, { k: 'adjunto', l: 'Archivo adjunto', t: 'file-upload', fw: true }, { k: 'precio', l: 'Precio', t: 'number', ph: '150' }, { k: 'notas', l: 'Notas', t: 'textarea', ph: 'Desayuno incluido...' }] },
  transporte: { icon: '<i class="fa-solid fa-car"></i>', label: 'Transporte', color: '#22c87a', bg: '#d1fae8', fields: [{ k: 'tipo', l: 'Tipo', t: 'select', opts: ['Auto de alquiler', 'Taxi/Uber', 'Tren', 'Bus', 'Ferry', 'Moto'] }, { k: 'proveedor', l: 'Proveedor', t: 'text', ph: 'Hertz, Renfe...' }, { k: 'origen', l: 'Desde', t: 'text', ph: 'Aeropuerto CDG' }, { k: 'destino', l: 'Hasta', t: 'text', ph: 'Hotel Centro' }, { k: 'salida', l: 'Salida', t: 'datetime-local' }, { k: 'llegada', l: 'Llegada', t: 'datetime-local' }, { k: 'precio', l: 'Precio', t: 'number', ph: '50' }, { k: 'reserva', l: 'Código reserva', t: 'text', ph: 'TRL-12345' }, { k: 'adjunto', l: 'Archivo adjunto', t: 'file-upload', fw: true }, { k: 'notas', l: 'Notas', t: 'textarea', ph: 'Confirmación...' }] },
  actividad: { icon: '<i class="fa-solid fa-compass"></i>', label: 'Actividad', color: '#f59e0b', bg: '#fef3c7', fields: [{ k: 'nombre', l: 'Nombre actividad', t: 'text', ph: 'Cena con vista, Tour privado...', fw: true }, { k: 'direccion', l: 'Lugar (Google Maps) · opcional', t: 'text', ph: 'Torre Eiffel, Museo del Louvre...', fw: true, hasInfo: true }, { k: 'stars', l: 'Calificación', t: 'stars', group: 'google' }, { k: 'photo_url', l: 'Foto seleccionada', t: 'text', ph: 'https://...', group: 'google' }, { k: 'descripcion', l: 'Descripción', t: 'textarea', ph: 'Descripción...', fw: true }, { k: 'website', l: 'Link de la actividad · opcional', t: 'text', ph: 'https://...', fw: true }, { k: 'fecha', l: 'Fecha y hora', t: 'datetime-local' }, { k: 'duracion', l: 'Duración', t: 'select', opts: ['1h', '2h', '3h', '4h', 'Medio día', 'Día completo'] }, { k: 'reserva', l: 'Código reserva', t: 'text', ph: 'ACT-12345' }, { k: 'adjunto', l: 'Archivo adjunto', t: 'file-upload', fw: true }, { k: 'precio', l: 'Precio', t: 'number', ph: '25' }, { k: 'notas', l: 'Notas', t: 'textarea', ph: 'Info adicional...' }] },
  comida: { icon: '<i class="fa-solid fa-utensils"></i>', label: 'Comida', color: '#f96b3a', bg: '#ffe8e0', fields: [{ k: 'restaurante', l: 'Restaurante', t: 'text', ph: 'Le Jules Verne', fw: true, hasInfo: true }, { k: 'direccion', l: 'Dirección', t: 'text', ph: 'Avenida...', group: 'google', fw: true }, { k: 'phone', l: 'Teléfono', t: 'text', ph: '+1 234...', group: 'google' }, { k: 'website', l: 'Sitio Web', t: 'text', ph: 'https://...', group: 'google' }, { k: 'stars', l: 'Calificación', t: 'stars', group: 'google' }, { k: 'photo_url', l: 'Foto seleccionada', t: 'text', ph: 'https://...', group: 'google' }, { k: 'tipo', l: 'Tipo', t: 'select', opts: ['Desayuno', 'Almuerzo', 'Cena', 'Brunch', 'Snack'] }, { k: 'fecha', l: 'Fecha y hora', t: 'datetime-local' }, { k: 'reserva', l: 'Código reserva', t: 'text', ph: 'RES-12345' }, { k: 'adjunto', l: 'Archivo adjunto', t: 'file-upload', fw: true }, { k: 'precio', l: 'Precio', t: 'number', ph: '80' }, { k: 'notas', l: 'Notas', t: 'textarea', ph: 'Menú degustación...' }] },
  tour: { icon: '<i class="fa-solid fa-map-location-dot"></i>', label: 'Tour', color: '#8b5cf6', bg: '#f5f3ff', fields: [{ k: 'nombre', l: 'Nombre del tour', t: 'text', ph: 'Tour Versalles' }, { k: 'operador', l: 'Operador', t: 'text', ph: 'Get Your Guide' }, { k: 'fecha', l: 'Fecha y hora', t: 'datetime-local' }, { k: 'duracion', l: 'Duración', t: 'select', opts: ['2h', '4h', 'Medio día', 'Día completo', '2 días', '3+ días'] }, { k: 'personas', l: 'No. personas', t: 'number', ph: '2' }, { k: 'reserva', l: 'Código reserva', t: 'text', ph: 'TOU-12345' }, { k: 'adjunto', l: 'Archivo adjunto', t: 'file-upload', fw: true }, { k: 'precio', l: 'Precio', t: 'number', ph: '120' }, { k: 'photo_url', l: 'Foto seleccionada', t: 'text', ph: 'https://...', fw: true }, { k: 'descripcion', l: 'Descripción', t: 'textarea', ph: 'Incluye entrada, guía...' }, { k: 'notas', l: 'Notas', t: 'textarea', ph: 'Info adicional...' }] },
  texto: { icon: '<i class="fa-solid fa-font"></i>', label: 'Texto', modalTitle: 'Texto', color: '#64748b', bg: '#f1f5f9', fields: [{ k: 'contenido', l: 'Contenido', t: 'richtext', ph: 'Escribe aquí...', fw: true }] },
  titulo: { icon: '✦', label: 'Título', modalTitle: 'Título', color: '#1a1a2e', bg: '#f0f1f7', fields: [{ k: 'texto', l: 'Texto del título', t: 'text', ph: 'Ej: Día 1 — Llegada a París', fw: true }] },
  separador: {
    icon: '—',
    label: 'Separador',
    modalTitle: 'Separador',
    color: '#94a3b8',
    bg: '#f1f5f9',
    fields: [
      { k: 'quick_chips', l: 'Momentos del día', t: 'separator-chips', fw: true },
      { k: 'etiqueta', l: 'O escribe una etiqueta personalizada (opcional)', t: 'text', ph: 'Ej: Almuerzo libre, Madrugada, Check-out...', fw: true }
    ]
  },
  imagen: {
    icon: '<i class="fa-regular fa-image"></i>',
    label: 'Imagen',
    modalTitle: 'Imagen',
    color: 'var(--primary-blue)',
    bg: '#e0f2fe',
    fields: [
      { k: 'url', l: 'Imagen', t: 'image-picker', fw: true },
      { k: 'tamano', l: 'Tamaño de visualización', t: 'select', opts: ['Mediano (100% x 380 px)'], fw: true }
    ]
  },
  gif: {
    icon: '<i class="fa-solid fa-bolt"></i>',
    label: 'GIF',
    modalTitle: 'GIF',
    color: '#ce3df3',
    bg: '#f9f0ff',
    fields: [
      { k: 'url', l: 'GIF', t: 'gif-picker', fw: true },
      { k: 'tamano', l: 'Tamaño de visualización', t: 'select', opts: ['Mediano (100% x 380 px)'], fw: true }
    ]
  },
  galeria: {
    icon: '<i class="fa-solid fa-images"></i>',
    label: 'Galería',
    modalTitle: 'Galería de fotos',
    color: '#0284c7',
    bg: '#e0f2fe',
    fields: [
      { k: 'photos', l: 'Fotos (máximo 5)', t: 'gallery-picker', fw: true },
      { k: 'tamano', l: 'Tamaño de visualización', t: 'select', opts: ['Mediano (100% x 380 px)'], fw: true }
    ]
  },
  ubicacion: {
    icon: '<i class="fa-solid fa-location-dot"></i>',
    label: 'Ubicación',
    modalTitle: 'Agregar Ubicación',
    color: '#0ea5e9',
    bg: '#e0f2fe',
    fields: [
      { k: 'nombre', l: 'Lugar o atracción', t: 'text', ph: 'Buscar lugar, atracción o dirección...', fw: true },
      { k: 'direccion', l: 'Dirección completa', t: 'text', ph: 'Dirección...', fw: true },
      { k: 'nota', l: 'Nota o indicación de llegada (opcional)', t: 'text', ph: 'Ej: Entrada por la puerta sur...', fw: true },
      { k: 'maps_url', l: 'Maps URL', t: 'text', ph: '', hidden: true }
    ]
  },
  caja: {
    icon: '<i class="fa-solid fa-lightbulb"></i>',
    label: 'Nota',
    modalTitle: 'Nota',
    color: '#f59e0b',
    bg: '#fef3c7',
    fields: [
      {
        k: 'icono',
        l: 'Tipo de Nota',
        t: 'icon-selector',
        fw: true,
        opts: [
          { icon: '💡', label: 'Tip', color: '#f59e0b' },
          { icon: '⚠️', label: 'Aviso importante', color: '#f43f5e' },
          { icon: '🎟️', label: 'Reserva / Ticket', color: '#0ea5e9' },
          { icon: '📌', label: 'Nota clave', color: '#8b5cf6' }
        ]
      },
      {
        k: 'titulo',
        l: 'Título',
        t: 'text',
        fw: true,
        ph: 'Ej: Llevar calzado cómodo o reservar con anticipación...'
      },
      {
        k: 'contenido',
        l: 'Detalles',
        t: 'textarea',
        fw: true,
        rows: 3,
        ph: 'Escribe recomendaciones, detalles de vestimenta o contexto de este día...'
      },
      {
        k: 'color_fondo',
        l: 'Color de Fondo',
        t: 'color-picker',
        fw: true,
        opts: ['#f59e0b', '#f43f5e', '#0ea5e9', '#8b5cf6', '#1eaace', '#64748b']
      }
    ]
  },
  documents: { icon: '<i class="fa-solid fa-file-lines"></i>', label: 'Documentos', color: '#0ea5d8', bg: '#e0f7ff', fields: [{ k: 'documents_title', l: 'Título de la Sección', t: 'text', ph: 'Ej: Documentos de Viaje, Vouchers, etc.', fw: true }, { k: 'documents_description', l: 'Descripción o Instrucciones', t: 'textarea', ph: 'Ej: Aquí puedes descargar tus documentos importantes...', fw: true }, { k: 'documents', l: 'Documentos (Máx. 5 archivos, 5MB c/u)', t: 'multi-file-upload', fw: true }] }
};

// DRAG
const dragGhost = document.getElementById('dragGhost');
const canvasItems = document.getElementById('canvasItems');
const emptyState = document.getElementById('emptyState');

document.querySelectorAll('.element-card').forEach(card => {
  card.addEventListener('dragstart', e => {
    dragType = card.dataset.type;
    dragLabel = card.dataset.label;
    dragSourceIndex = null;
    card.classList.add('dragging');
    e.dataTransfer.effectAllowed = 'copy';
    e.dataTransfer.setDragImage(new Image(), 0, 0);
    const cfg = C[dragType];
    if (cfg && cfg.icon && document.getElementById('ghostIcon')) {
      document.getElementById('ghostIcon').innerHTML = cfg.icon;
    }
    if (document.getElementById('ghostLabel')) {
      document.getElementById('ghostLabel').textContent = dragLabel || '';
    }
    if (dragGhost) {
      if (e.clientX && e.clientY) {
        dragGhost.style.left = (e.clientX + 12) + 'px';
        dragGhost.style.top = (e.clientY - 18) + 'px';
      }
      dragGhost.style.opacity = '1';
    }
  });
  card.addEventListener('dragend', () => {
    card.classList.remove('dragging');
    if (dragGhost) dragGhost.style.opacity = '0';
    clearDropIndicators();
  });
  card.addEventListener('dblclick', () => {
    if (typeof currentDay === 'number' || currentDay === 'portada' || currentDay === 'cierre') {
      openModal(card.dataset.type);
    } else {
      showToast('⚠️', 'Selecciona un día primero');
    }
  });

  // Mobile: Single tap to add (since dblclick is not intuitive on touch)
  card.addEventListener('click', (e) => {
    if (window.innerWidth < 992) {
      if (typeof currentDay === 'number' || currentDay === 'portada' || currentDay === 'cierre') {
        openModal(card.dataset.type);
      } else {
        showToast('⚠️', 'Selecciona un día primero');
      }
    }
  });
});

document.addEventListener('dragover', e => {
  e.preventDefault();
  if (dragGhost) {
    dragGhost.style.left = (e.clientX + 12) + 'px';
    dragGhost.style.top = (e.clientY - 18) + 'px';
  }
});

canvasItems.addEventListener('dragover', e => {
  e.preventDefault();
  e.stopPropagation();
  if (dragSourceIndex !== null && dragSourceContainer === 'canvasItems') {
    const cl = getClosest([...canvasItems.querySelectorAll('.canvas-item')], e.clientY);
    showDropInd(cl.index, cl.before);
  }
  if (emptyState) emptyState.classList.add('drag-over');
});

canvasItems.addEventListener('dragleave', e => {
  if (!canvasItems.contains(e.relatedTarget)) {
    clearDropIndicators();
    if (emptyState) emptyState.classList.remove('drag-over');
  }
});

if (emptyState) {
  emptyState.addEventListener('dragover', e => { e.preventDefault(); emptyState.classList.add('drag-over'); });
  emptyState.addEventListener('dragleave', () => emptyState.classList.remove('drag-over'));
  emptyState.addEventListener('drop', e => {
    e.preventDefault();
    if (emptyState) emptyState.classList.remove('drag-over');
    if (dragGhost) dragGhost.style.opacity = '0';
    if (dragType && (typeof currentDay === 'number' || currentDay === 'portada' || currentDay === 'cierre')) {
      const typeToOpen = dragType;
      dragType = null;
      openModal(typeToOpen);
    }
  });
}

canvasItems.addEventListener('drop', e => {
  e.preventDefault();
  clearDropIndicators();
  if (emptyState) emptyState.classList.remove('drag-over');
  if (dragGhost) dragGhost.style.opacity = '0';
  if (dragSourceIndex !== null && dragSourceContainer === 'canvasItems') {
    const items = [...canvasItems.querySelectorAll('.canvas-item')];
    const cl = getClosest(items, e.clientY);
    let to = cl.before ? cl.index : cl.index + 1;
    if (to > dragSourceIndex) to--;
    const arr = days[currentDay];
    const [moved] = arr.splice(dragSourceIndex, 1);
    arr.splice(to, 0, moved);
    renderCanvas();
    autoSaveProTrip();
    dragSourceIndex = null; dragSourceContainer = null;
    return;
  }
  if (dragType && (typeof currentDay === 'number' || currentDay === 'portada' || currentDay === 'cierre')) {
    const typeToOpen = dragType;
    dragType = null;
    openModal(typeToOpen);
  }
});

function getClosest(items, y) { let minD = Infinity, index = items.length, before = false; items.forEach((item, i) => { const r = item.getBoundingClientRect(); const mid = r.top + r.height / 2; const d = Math.abs(y - mid); if (d < minD) { minD = d; index = i; before = y < mid } }); return { index, before } }
function showDropInd(index, before) { clearDropIndicators(); const items = [...canvasItems.querySelectorAll('.canvas-item')]; const ind = document.createElement('div'); ind.className = 'drop-indicator visible'; if (!items.length) canvasItems.appendChild(ind); else if (before && items[index]) canvasItems.insertBefore(ind, items[index]); else if (items[index]) items[index].insertAdjacentElement('afterend', ind); else canvasItems.appendChild(ind) }
function clearDropIndicators() { canvasItems.querySelectorAll('.drop-indicator').forEach(d => d.remove()) }

// Drag-drop + reorder for portada/cierre item containers
function setupContainerDrag(containerId) {
  const cont = document.getElementById(containerId);
  if (!cont) return;

  function getItemsArr() {
    if (containerId === 'portadaItems') return portadaItems;
    if (containerId === 'cierreItems') return cierreItems;
    return [];
  }

  function getClosestInCont(y) {
    const items = [...cont.querySelectorAll('.canvas-item')];
    let minD = Infinity, index = items.length, before = false;
    items.forEach((item, i) => { const r = item.getBoundingClientRect(); const mid = r.top + r.height / 2; const d = Math.abs(y - mid); if (d < minD) { minD = d; index = i; before = y < mid } });
    return { index, before };
  }
  function showContDropInd(index, before) {
    cont.querySelectorAll('.drop-indicator').forEach(d => d.remove());
    const items = [...cont.querySelectorAll('.canvas-item')];
    const ind = document.createElement('div'); ind.className = 'drop-indicator visible';
    if (!items.length) cont.appendChild(ind);
    else if (before && items[index]) cont.insertBefore(ind, items[index]);
    else if (items[index]) items[index].insertAdjacentElement('afterend', ind);
    else cont.appendChild(ind);
  }

  cont.addEventListener('dragover', e => {
    e.preventDefault(); e.stopPropagation();
    if (dragSourceIndex !== null && dragSourceContainer === containerId) {
      const cl = getClosestInCont(e.clientY); showContDropInd(cl.index, cl.before);
    }
  });
  cont.addEventListener('dragleave', e => {
    if (!cont.contains(e.relatedTarget)) cont.querySelectorAll('.drop-indicator').forEach(d => d.remove());
  });
  cont.addEventListener('drop', e => {
    e.preventDefault(); e.stopPropagation();
    cont.querySelectorAll('.drop-indicator').forEach(d => d.remove());
    if (dragGhost) dragGhost.style.opacity = '0';
    if (dragSourceIndex !== null && dragSourceContainer === containerId) {
      const items = [...cont.querySelectorAll('.canvas-item')];
      const itemsArr = getItemsArr();
      const cl = getClosestInCont(e.clientY);
      let to = cl.before ? cl.index : cl.index + 1;
      if (to > dragSourceIndex) to--;
      const [moved] = itemsArr.splice(dragSourceIndex, 1);
      itemsArr.splice(to, 0, moved);
      renderCanvas();
      autoSaveProTrip();
      dragSourceIndex = null; dragSourceContainer = null; return;
    }
    if (dragType) {
      const typeToOpen = dragType;
      dragType = null;
      openModal(typeToOpen);
    }
  });
}
setupContainerDrag('portadaItems');
setupContainerDrag('cierreItems');

['portadaCanvas', 'cierreCanvas'].forEach(cid => {
  const el = document.getElementById(cid);
  if (!el) return;
  el.addEventListener('dragover', e => { e.preventDefault() });
  el.addEventListener('drop', e => {
    e.preventDefault();
    if (dragGhost) dragGhost.style.opacity = '0';
    if (dragType) {
      const typeToOpen = dragType;
      dragType = null;
      openModal(typeToOpen);
    }
  });
});

['portadaDropHint', 'cierreDropHint'].forEach(hid => {
  const hEl = document.getElementById(hid);
  if (!hEl) return;
  hEl.addEventListener('dragover', e => { e.preventDefault(); e.stopPropagation(); hEl.style.borderColor = 'var(--accent)'; hEl.style.background = 'var(--accent-light)'; });
  hEl.addEventListener('dragleave', () => { hEl.style.borderColor = 'var(--border)'; hEl.style.background = ''; });
  hEl.addEventListener('drop', e => {
    e.preventDefault(); e.stopPropagation();
    hEl.style.borderColor = 'var(--border)'; hEl.style.background = '';
    if (dragGhost) dragGhost.style.opacity = '0';
    if (dragType) {
      const typeToOpen = dragType;
      dragType = null;
      openModal(typeToOpen);
    }
  });
});

// Make entire canvas scrollable area a drop zone for regular days
const canvasEl = document.getElementById('canvas');
if (canvasEl) {
  canvasEl.addEventListener('dragover', e => { e.preventDefault(); const hint = document.getElementById('dropHint'); if (hint && hint.style.display !== 'none') hint.style.borderColor = 'var(--accent)'; });
  canvasEl.addEventListener('dragleave', e => { if (!canvasEl.contains(e.relatedTarget)) { const hint = document.getElementById('dropHint'); if (hint) hint.style.borderColor = 'var(--border)'; } });
  canvasEl.addEventListener('drop', e => {
    // Only fire if not already handled by canvasItems
    if (e.target.closest('#canvasItems') || e.target.closest('#portadaCanvas') || e.target.closest('#cierreCanvas')) return;
    e.preventDefault();
    const hint = document.getElementById('dropHint'); if (hint) hint.style.borderColor = 'var(--border)';
    if (dragGhost) dragGhost.style.opacity = '0';
    if (dragType && (typeof currentDay === 'number' || currentDay === 'portada' || currentDay === 'cierre')) {
      const typeToOpen = dragType;
      dragType = null;
      openModal(typeToOpen);
    }
  });
}

// dropHint itself is also a drop zone
const dropHintEl = document.getElementById('dropHint');
if (dropHintEl) {
  dropHintEl.addEventListener('dragover', e => { e.preventDefault(); e.stopPropagation(); dropHintEl.style.borderColor = 'var(--accent)'; dropHintEl.style.background = 'var(--accent-light)'; });
  dropHintEl.addEventListener('dragleave', () => { dropHintEl.style.borderColor = 'var(--border)'; dropHintEl.style.background = ''; });
  dropHintEl.addEventListener('drop', e => {
    e.preventDefault(); e.stopPropagation();
    dropHintEl.style.borderColor = 'var(--border)'; dropHintEl.style.background = '';
    if (dragGhost) dragGhost.style.opacity = '0';
    if (dragType && (typeof currentDay === 'number' || currentDay === 'portada' || currentDay === 'cierre')) {
      const typeToOpen = dragType;
      dragType = null;
      openModal(typeToOpen);
    }
  });
}

// TABS
document.getElementById('dayTabs').addEventListener('click', e => {
  if (e.target.closest('.day-tab-delete') || e.target.closest('#addDayBtn')) return;
  const tab = e.target.closest('.day-tab');
  if (!tab) return;
  const dayVal = tab.dataset.day;
  currentDay = dayVal === 'portada' ? 'portada' : dayVal === 'cierre' ? 'cierre' : parseInt(dayVal);
  document.querySelectorAll('.day-tab').forEach(t => t.classList.remove('active'));
  tab.classList.add('active');
  renderCanvas();
});

// Day Tabs Drag-and-Drop
const tabsCont = document.getElementById('dayTabs');
tabsCont.addEventListener('dragstart', e => {
  const tab = e.target.closest('.day-tab:not(.portada-tab):not(.cierre-tab)');
  if (!tab) { e.preventDefault(); return; }
  dragTabSourceIndex = parseInt(tab.dataset.day);
  e.dataTransfer.effectAllowed = 'move';
  e.dataTransfer.setData('text/plain', dragTabSourceIndex);
  setTimeout(() => tab.style.opacity = '0.4', 0);
});
tabsCont.addEventListener('dragend', e => {
  const tab = e.target.closest('.day-tab');
  if (tab) tab.style.opacity = '';
  dragTabSourceIndex = null;
  tabsCont.querySelectorAll('.day-tab').forEach(t => t.style.border = '');
});
tabsCont.addEventListener('dragover', e => {
  e.preventDefault();
  const tab = e.target.closest('.day-tab:not(.portada-tab):not(.cierre-tab)');
  tabsCont.querySelectorAll('.day-tab').forEach(t => t.style.borderLeft = '');
  if (tab && dragTabSourceIndex !== null && parseInt(tab.dataset.day) !== dragTabSourceIndex) {
    tab.style.borderLeft = '2px solid var(--accent)';
  }
});
tabsCont.addEventListener('dragleave', e => {
  // Clear border from target when leaving the container or moving to another tab
  if (e.target === tabsCont) {
    tabsCont.querySelectorAll('.day-tab').forEach(t => t.style.borderLeft = '');
  }
});
tabsCont.addEventListener('drop', e => {
  e.preventDefault();
  const targetTab = e.target.closest('.day-tab');
  if (!targetTab) return;

  // Case A: Reordering Tabs
  if (dragTabSourceIndex !== null) {
    if (targetTab.classList.contains('portada-tab') || targetTab.classList.contains('cierre-tab')) return;
    const to = parseInt(targetTab.dataset.day);
    if (to === dragTabSourceIndex) return;

    const movedDay = days.splice(dragTabSourceIndex, 1)[0];
    days.splice(to, 0, movedDay);

    const movedDate = dayDates.splice(dragTabSourceIndex, 1)[0];
    dayDates.splice(to, 0, movedDate);

    if (currentDay === dragTabSourceIndex) {
      currentDay = to;
    } else if (typeof currentDay === 'number') {
      if (dragTabSourceIndex < currentDay && to >= currentDay) currentDay--;
      else if (dragTabSourceIndex > currentDay && to <= currentDay) currentDay++;
    }

    unsavedChanges = true;
    renderTabs();
    renderCanvas();
    autoSaveProTrip();
    return;
  }

  // Case B: Moving Item to Different Day
  if (dragSourceIndex !== null) {
    const targetDayVal = targetTab.dataset.day;
    const targetDay = targetDayVal === 'portada' ? 'portada' : targetDayVal === 'cierre' ? 'cierre' : parseInt(targetDayVal);

    // Determine source array
    let sourceArr;
    if (dragSourceContainer === 'portadaItems') sourceArr = portadaItems;
    else if (dragSourceContainer === 'cierreItems') sourceArr = cierreItems;
    else sourceArr = days[currentDay];

    // Determine target array
    let targetArr;
    if (targetDay === 'portada') targetArr = portadaItems;
    else if (targetDay === 'cierre') targetArr = cierreItems;
    else targetArr = days[targetDay];

    if (sourceArr === targetArr) return; // Dropped on same day tab

    const [moved] = sourceArr.splice(dragSourceIndex, 1);
    targetArr.push(moved);

    currentDay = targetDay; // Switch to the day where the item was dropped
    unsavedChanges = true;
    renderTabs();
    renderCanvas();
    autoSaveProTrip();
    showToast('<i class="fa-solid fa-arrow-right-arrow-left"></i>', 'Elemento movido de día');
  }
});



// DELETE SECTION (portada/cierre)
function confirmDeleteSection(type, e) {
  e && e.stopPropagation();
  const label = type === 'portada' ? 'Portada' : 'Cierre';
  openConfirm('¿Eliminar ' + label + '?', 'Se eliminará esta sección del itinerario. Si la vuelves a agregar, aparecerá en blanco.', () => deleteSection(type));
}
function deleteSection(type) {
  // Logic to switch currentDay before renderTabs
  if (currentDay === type) {
    currentDay = 0; // go to day 1
  }
  unsavedChanges = true;
  renderTabs();
  renderCanvas();
  autoSaveProTrip();
  showToast('<i class="fa-solid fa-trash-can"></i>', (type === 'portada' ? 'Portada' : 'Cierre') + ' eliminado');
}

// DELETE DAY
function confirmDeleteCurrentDay(e) {
  e && e.stopPropagation();
  if (typeof currentDay === 'number') {
    confirmDeleteDay(currentDay, e);
  }
}
function confirmDeleteDay(dayIdx, e) {
  e && e.stopPropagation();
  if (days.length <= 1) return showToast('⚠️', 'No puedes eliminar el único día');
  openConfirm('¿Eliminar Día ' + (dayIdx + 1) + '?', 'Se eliminarán todos los elementos de este día.', () => deleteDay(dayIdx));
}
function deleteDay(dayIdx) {
  if (days.length <= 1) return showToast('⚠️', 'No puedes eliminar el único día');
  days.splice(dayIdx, 1);
  dayDates.splice(dayIdx, 1);

  if (currentDay === dayIdx) {
    currentDay = Math.max(0, dayIdx - 1);
  } else if (typeof currentDay === 'number' && currentDay > dayIdx) {
    currentDay--;
  }

  unsavedChanges = true;
  renderTabs();
  renderCanvas();
  autoSaveProTrip();
  showToast('<i class="fa-solid fa-trash-can"></i>', 'Día eliminado');
}

function renderTabs() {
  const container = document.getElementById('dayTabs');
  if (!container) return;

  // Conserve active status if possible
  const activeDay = currentDay;

  container.innerHTML = '';

  let html = '';

  // Portada
  html += `<button class="day-tab portada-tab ${currentDay === 'portada' ? 'active' : ''}" data-day="portada"><span class="day-tab-label"><i class="fa-solid fa-sun" style="margin-right:4px"></i> Portada</span><span class="day-tab-delete portada-cierre-delete" onclick="confirmDeleteSection('portada',event)" title="Eliminar portada"><i class="fa-solid fa-trash-can"></i></span></button>`;

  // Days
  days.forEach((_, i) => {
    const dateStr = dayDates[i] ? fmtDateTab(dayDates[i]) : ('Día ' + (i + 1));
    html += `<button class="day-tab ${currentDay === i ? 'active' : ''}" data-day="${i}" draggable="true" style="cursor:grab">
      <span class="day-tab-label"><span style="display:inline-flex; gap:1px; margin-right:7px; opacity:0.4; font-size:10px;"><i class="fa-solid fa-ellipsis-vertical"></i><i class="fa-solid fa-ellipsis-vertical"></i></span>${dateStr}</span>
      <span class="day-tab-delete" onclick="confirmDeleteDay(${i},event)" title="Eliminar día"><i class="fa-solid fa-xmark"></i></span>
    </button>`;
  });

  // Inline Add Day Button
  html += `<button class="add-day-btn" id="addDayBtn" type="button" style="margin: 0 4px; padding: 4px 10px;"><i class="fa-solid fa-plus"></i> Día</button>`;

  // Cierre
  html += `<button class="day-tab cierre-tab ${currentDay === 'cierre' ? 'active' : ''}" data-day="cierre"><span class="day-tab-label"><i class="fa-solid fa-moon" style="margin-right:4px"></i> Cierre</span><span class="day-tab-delete portada-cierre-delete" onclick="confirmDeleteSection('cierre',event)" title="Eliminar cierre"><i class="fa-solid fa-trash-can"></i></span></button>`;

  container.innerHTML = html;

  // Bind click listener to the dynamically created Add Day button
  const newAddDayBtn = container.querySelector('#addDayBtn');
  if (newAddDayBtn) {
    newAddDayBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      days.push([]);
      let nextDate = '';
      if (days.length > 1) {
        const prevDate = dayDates[days.length - 2];
        if (prevDate) {
          nextDate = addDaysToDate(prevDate, 1);
        } else {
          const pi = document.getElementById('portadaFechaInicio');
          if (pi && pi.value) {
            nextDate = addDaysToDate(pi.value, days.length - 1);
          }
        }
      } else {
        const pi = document.getElementById('portadaFechaInicio');
        if (pi && pi.value) nextDate = pi.value;
      }
      dayDates.push(nextDate);
      currentDay = days.length - 1;
      unsavedChanges = true;
      renderTabs();
      renderCanvas();
      autoSaveProTrip();
      showToast('📅', 'Día ' + days.length + ' agregado');
    });
  }

  // Important: Ad-hoc drop listeners for all tabs to support moving items
  container.querySelectorAll('.day-tab:not(#addDayBtn)').forEach(tab => {
    tab.addEventListener('dragover', e => {
      if (dragSourceIndex !== null) {
        e.preventDefault();
        tab.style.boxShadow = 'inset 0 0 0 2px var(--accent)';
      }
    });
    tab.addEventListener('dragleave', () => {
      tab.style.boxShadow = '';
    });
    tab.addEventListener('drop', () => {
      tab.style.boxShadow = '';
    });
  });
}

function resetViajeros() {
  portadaAdultos = 0; portadaNinos = 0;
  document.getElementById('portadaAdultos').textContent = '0';
  document.getElementById('portadaNinos').textContent = '0';
  document.getElementById('portadaTotal').textContent = '0';
}

// DAY DATE
function saveDayDate() {
  if (typeof currentDay !== 'number') return;
  dayDates[currentDay] = document.getElementById('dayDateInput').value;
  const noDate = document.getElementById('dayNoDate');
  noDate.style.display = dayDates[currentDay] ? 'none' : '';
  unsavedChanges = true;
  renderTabs();
  autoSaveProTrip();
}

// CONFIRM
function openConfirm(title, msg, cb) { confirmCallback = cb; document.getElementById('confirmTitle').textContent = title; document.getElementById('confirmMsg').textContent = msg; document.getElementById('confirmOverlay').classList.add('open') }

function showUnsavedChangesModal() {
  openConfirm(
    '¿Salir sin guardar?',
    'Tienes cambios sin guardar. Si sales ahora, los cambios no guardados se perderán.',
    () => {
      unsavedChanges = false;
      window.location.href = '/trips';
    }
  );
}
function confirmExit() {
  if (unsavedChanges) {
    showUnsavedChangesModal();
  } else {
    window.location.href = '/trips';
  }
}

window.addEventListener('beforeunload', (e) => {
  if (unsavedChanges) {
    e.preventDefault();
    e.returnValue = '';
  }
});
function closeConfirm() { document.getElementById('confirmOverlay').classList.remove('open'); confirmCallback = null }
document.getElementById('confirmOkBtn').addEventListener('click', () => { if (confirmCallback) confirmCallback(); closeConfirm() });
document.getElementById('confirmOverlay').addEventListener('click', e => { if (e.target === document.getElementById('confirmOverlay')) closeConfirm() });

// RENDER
function renderCanvas() {
  updatePortadaPriceFromServices();
  const portadaCanvas = document.getElementById('portadaCanvas');
  const cierreCanvas = document.getElementById('cierreCanvas');
  const regularCanvas = document.getElementById('regularCanvas');
  const daySubbar = document.getElementById('daySubbar');
  portadaCanvas.style.display = 'none'; cierreCanvas.style.display = 'none'; regularCanvas.style.display = 'none';
  daySubbar.classList.add('hidden');

  if (currentDay === 'portada') {
    portadaCanvas.style.display = 'flex';
    // Render portada extra items
    const pItems = document.getElementById('portadaItems');
    pItems.innerHTML = '';
    portadaItems.forEach((item, idx) => pItems.appendChild(buildItem(item, idx)));
    return;
  }
  if (currentDay === 'cierre') {
    cierreCanvas.style.display = 'flex';
    document.getElementById('cierreTitleDisplay').textContent = document.getElementById('portadaTitle').value || 'Tu viaje';
    // Render cierre extra items
    const cItems = document.getElementById('cierreItems');
    cItems.innerHTML = '';
    cierreItems.forEach((item, idx) => cItems.appendChild(buildItem(item, idx)));
    return;
  }

  // Numeric day — show subbar
  daySubbar.classList.remove('hidden');
  const tabEl = document.querySelector(`.day-tab[data-day="${currentDay}"]`);
  const tabLabel = tabEl ? tabEl.querySelector('.day-tab-label')?.textContent || ('Día ' + (currentDay + 1)) : ('Día ' + (currentDay + 1));
  document.getElementById('daySubbarLabel').textContent = tabLabel.toUpperCase();
  const dateVal = dayDates[currentDay] || '';
  document.getElementById('dayDateInput').value = dateVal;
  document.getElementById('dayNoDate').style.display = dateVal ? 'none' : '';

  regularCanvas.style.display = 'block';
  if (typeof currentDay === 'number' && days[currentDay]) {
    sortDayItemsChronologically(days[currentDay], currentDay);
  }
  const items = days[currentDay] || [];
  canvasItems.innerHTML = '';
  emptyState.classList.toggle('hidden', items.length > 0);
  document.getElementById('dropHint').style.display = items.length > 0 ? 'block' : 'none';

  items.forEach((item, idx) => canvasItems.appendChild(buildItem(item, idx)));
}

// HELPER FUNCTIONS FOR CHRONOLOGICAL SORT & TIME DISPLAY
function getItemDateTime(item) {
  if (!item || !item.data) return null;
  const d = item.data;
  switch (item.type) {
    case 'flight': return d.salida || null;
    case 'alojamiento': return d.checkin || null;
    case 'transporte': return d.salida || null;
    case 'actividad': return d.fecha || null;
    case 'comida': return d.fecha || null;
    case 'tour': return d.fecha || null;
    default: return null;
  }
}

function getItemTimeStr(item) {
  const dt = getItemDateTime(item);
  if (!dt) return '';
  const parts = dt.split('T');
  if (parts.length > 1) {
    return parts[1].substring(0, 5); // "14:00"
  }
  const spaceParts = dt.split(' ');
  if (spaceParts.length > 1 && spaceParts[1].includes(':')) {
    return spaceParts[1].substring(0, 5);
  }
  return '';
}

function updateDateTimePart(dtStr, newDate) {
  if (!dtStr || !newDate) return dtStr;
  let timePart = '00:00';
  if (dtStr.includes('T')) {
    timePart = dtStr.split('T')[1];
    return newDate + 'T' + timePart;
  } else if (dtStr.includes(' ')) {
    timePart = dtStr.split(' ')[1];
    return newDate + ' ' + timePart;
  } else if (dtStr.includes(':')) {
    return newDate + 'T' + dtStr;
  }
  return newDate + 'T' + dtStr;
}

function getItemTimeOnly(dt) {
  if (!dt) return '99:99';
  let timePart = '';
  if (dt.includes('T')) {
    timePart = dt.split('T')[1];
  } else if (dt.includes(' ')) {
    timePart = dt.split(' ')[1];
  } else if (dt.includes(':')) {
    timePart = dt;
  }
  if (timePart && timePart.includes(':')) {
    return timePart.substring(0, 5); // "HH:MM"
  }
  return '99:99';
}

function sortDayItemsChronologically(arr, dayIdx) {
  if (!arr || arr.length <= 1) return;
  const targetDate = (typeof dayIdx === 'number' && dayDates[dayIdx]) ? dayDates[dayIdx] : null;
  const itemsWithDates = [];
  const indices = [];
  arr.forEach((item, idx) => {
    if (targetDate && item && item.data) {
      const d = item.data;
      if (item.type === 'flight' && d.salida) d.salida = updateDateTimePart(d.salida, targetDate);
      if (item.type === 'alojamiento' && d.checkin) d.checkin = updateDateTimePart(d.checkin, targetDate);
      if (item.type === 'transporte' && d.salida) d.salida = updateDateTimePart(d.salida, targetDate);
      if (item.type === 'actividad' && d.fecha) d.fecha = updateDateTimePart(d.fecha, targetDate);
      if (item.type === 'comida' && d.fecha) d.fecha = updateDateTimePart(d.fecha, targetDate);
      if (item.type === 'tour' && d.fecha) d.fecha = updateDateTimePart(d.fecha, targetDate);
    }
    const dt = getItemDateTime(item);
    if (dt) {
      itemsWithDates.push({ item, dt });
      indices.push(idx);
    }
  });
  itemsWithDates.sort((a, b) => {
    const timeA = getItemTimeOnly(a.dt);
    const timeB = getItemTimeOnly(b.dt);
    return timeA.localeCompare(timeB);
  });
  itemsWithDates.forEach((wrapped, idx) => {
    const originalIdx = indices[idx];
    arr[originalIdx] = wrapped.item;
  });
}

function calculateTripServicesSummary() {
  let total = 0;
  let count = 0;
  function addPrice(p) {
    if (!p) return;
    const clean = unformatNumber(p);
    const price = parseFloat(clean);
    if (!isNaN(price) && price > 0) {
      total += price;
      count++;
    }
  }
  if (typeof days !== 'undefined' && days) {
    days.forEach(dayItems => {
      if (dayItems) {
        dayItems.forEach(item => {
          if (item && item.data && item.data.precio) addPrice(item.data.precio);
        });
      }
    });
  }
  if (typeof portadaItems !== 'undefined' && portadaItems) {
    portadaItems.forEach(item => {
      if (item && item.data && item.data.precio) addPrice(item.data.precio);
    });
  }
  if (typeof cierreItems !== 'undefined' && cierreItems) {
    cierreItems.forEach(item => {
      if (item && item.data && item.data.precio) addPrice(item.data.precio);
    });
  }
  return { sum: total, count };
}

function calculateTripServicesTotal() {
  return calculateTripServicesSummary().sum;
}

function updatePortadaPriceFromServices() {
  const priceInput = document.getElementById('portadaPrecio');
  if (!priceInput) return;

  const { sum, count } = calculateTripServicesSummary();

  if (!isPriceManual) {
    if (sum > 0) {
      priceInput.value = formatNumber(sum);
    } else if (sum === 0) {
      priceInput.value = '';
    }
  }
  lastAutoCalculatedSum = sum;
  updatePortadaPriceUI();
}

function updatePortadaPriceUI() {
  const priceInput = document.getElementById('portadaPrecio');
  if (!priceInput) return;

  const { sum, count } = calculateTripServicesSummary();
  const currentValNum = parseFloat(unformatNumber(priceInput.value)) || 0;

  const perPersonEl = document.getElementById('pricePerPerson');
  const manualNotice = document.getElementById('priceManualNotice');

  if (isPriceManual) {
    if (manualNotice) manualNotice.style.display = 'inline-flex';
    if (perPersonEl) perPersonEl.style.display = 'none';
  } else {
    if (manualNotice) manualNotice.style.display = 'none';
    if (perPersonEl) perPersonEl.style.display = 'inline-block';
  }

  // Costo estimado por persona
  const totalTravelers = (portadaAdultos + portadaNinos) || 1;
  const perPerson = currentValNum > 0 ? Math.round(currentValNum / totalTravelers) : 0;
  if (perPersonEl) {
    perPersonEl.textContent = perPerson > 0 ? `≈ $${formatNumber(perPerson)} / persona` : '≈ $0 / persona';
  }
}

function handlePricePencilClick(e) {
  e && e.stopPropagation();
  const priceInput = document.getElementById('portadaPrecio');
  if (priceInput) {
    priceInput.focus();
    priceInput.select();
  }
}

function handlePriceFocus() {
  // Mantener foco limpio
}

function handlePriceManualEdit() {
  isPriceManual = true;
  updatePortadaPriceUI();
  unsavedChanges = true;
  autoSaveProTrip();
}

function handleCurrencyChange() {
  unsavedChanges = true;
  autoSaveProTrip();
}

function restoreAutoCalculatedPrice() {
  isPriceManual = false;
  const { sum } = calculateTripServicesSummary();
  const priceInput = document.getElementById('portadaPrecio');
  if (priceInput) {
    priceInput.value = sum > 0 ? formatNumber(sum) : '';
  }
  updatePortadaPriceUI();
  showToast('⚡', 'Suma automática de servicios restaurada');
  unsavedChanges = true;
  autoSaveProTrip();
}

function togglePriceVisibility() {
  hidePriceInPublic = !hidePriceInPublic;
  updatePriceVisibilityUI();
  showToast(hidePriceInPublic ? '👁️‍🗨️' : '👁️', hidePriceInPublic ? 'Precio oculto en vista compartida' : 'Precio visible en vista compartida');
  unsavedChanges = true;
  autoSaveProTrip();
}

function updatePriceVisibilityUI() {
  const icon = document.getElementById('iconPriceVisibility');
  const btn = document.getElementById('btnTogglePriceVisibility');
  if (icon) {
    icon.className = hidePriceInPublic ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
    icon.style.color = hidePriceInPublic ? '#f87171' : 'rgba(255,255,255,0.85)';
  }
  if (btn) {
    btn.title = hidePriceInPublic ? 'Precio actualmente oculto para clientes (click para mostrar)' : 'Precio actualmente visible para clientes (click para ocultar)';
  }
}

function toggleTravelersVisibility() {
  hideTravelersInPublic = !hideTravelersInPublic;
  updateTravelersVisibilityUI();
  showToast(hideTravelersInPublic ? '👁️‍🗨️' : '👁️', hideTravelersInPublic ? 'Viajeros ocultos en vista compartida' : 'Viajeros visibles en vista compartida');
  unsavedChanges = true;
  autoSaveProTrip();
}

function updateTravelersVisibilityUI() {
  const icon = document.getElementById('iconTravelersVisibility');
  const btn = document.getElementById('btnToggleTravelersVisibility');
  if (icon) {
    icon.className = hideTravelersInPublic ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
    icon.style.color = hideTravelersInPublic ? '#f87171' : 'rgba(255,255,255,0.85)';
  }
  if (btn) {
    btn.title = hideTravelersInPublic ? 'Viajeros actualmente ocultos para clientes (click para mostrar)' : 'Viajeros actualmente visibles para clientes (click para ocultar)';
  }
}

function formatReviewsCount(count) {
  if (!count && count !== 0) return '';
  const num = parseInt(count);
  if (isNaN(num) || num <= 0) return '';
  if (num >= 1000000) return (num / 1000000).toFixed(1).replace(/\.0$/, '') + 'M';
  if (num >= 1000) return (num / 1000).toFixed(1).replace(/\.0$/, '') + 'k';
  return num.toString();
}

function updateGoogleRatingInModal(sr, rating, reviews) {
  if (!sr) return;
  const rNum = parseFloat(rating);
  const rounded = (!isNaN(rNum) && rNum > 0) ? Math.round(rNum) : 0;
  sr.querySelectorAll('.star').forEach((st, idx) => st.classList.toggle('active', idx < rounded));

  const hid = sr.querySelector('input[data-key="stars"]') || sr.querySelector('input[type="hidden"]');
  if (hid) hid.value = (!isNaN(rNum) && rNum > 0) ? rating : '';

  const hidReviews = sr.querySelector('input[data-key="user_ratings_total"]');
  if (hidReviews && typeof reviews !== 'undefined') hidReviews.value = reviews || '';

  const currentReviews = hidReviews ? hidReviews.value : (reviews || '');
  const badge = sr.querySelector('.modal-google-rating-text');
  if (badge) {
    if (!isNaN(rNum) && rNum > 0) {
      badge.style.display = 'inline-flex';
      const sStr = rNum % 1 === 0 ? rNum.toFixed(1) : rNum.toString();
      const rFmt = currentReviews ? formatReviewsCount(currentReviews) : '';
      badge.textContent = `⭐ ${sStr}/5 (${rFmt ? rFmt + ' opiniones en Google' : 'opiniones en Google'})`;
    } else {
      badge.style.display = 'none';
      badge.textContent = '';
    }
  }
}

window.getItemInnerHtml = function(item) {
  if (!item) return '';
  const type = item.type || 'actividad';
  const cfg = C[type] || { icon: '<i class="fa-solid fa-compass"></i>', label: 'Elemento', color: '#64748b', bg: '#f1f5f9' };
  const d = item.data || {};

  if (type === 'separador') {
    const lbl = d.etiqueta || '';
    return `<div class="item-inner">
      <div class="sep-line"></div>
      ${lbl ? `<span class="sep-dot"></span><span style="font-size:11.5px;font-weight:600;color:var(--text-muted);white-space:nowrap;padding:0 6px;">${lbl}</span><span class="sep-dot"></span>` : '<span class="sep-dot"></span>'}
      <div class="sep-line"></div>
    </div>`;
  }
  if (type === 'titulo') {
    return `<div class="item-inner" style="flex-direction:column;gap:3px;padding:18px 20px"><div class="titulo-text">${d.texto || item.title || 'Título'}</div></div>`;
  }
  if (type === 'texto') {
    return `<div class="item-inner" style="flex-direction:column;gap:5px;padding:14px 16px"><div class="texto-content">${d.contenido || item.title || 'Texto...'}</div></div>`;
  }
  if (type === 'imagen') {
    let photos = [];
    try {
      if (Array.isArray(d.url)) photos = d.url;
      else if (Array.isArray(d.photos)) photos = d.photos;
      else if (typeof d.url === 'string' && d.url.startsWith('[')) photos = JSON.parse(d.url);
      else if (typeof d.photos === 'string' && d.photos.startsWith('[')) photos = JSON.parse(d.photos);
      else if (d.url) photos = d.url.split(',').map(s => s.trim()).filter(Boolean);
      else if (d.photos) photos = d.photos.split(',').map(s => s.trim()).filter(Boolean);
    } catch {
      photos = d.url ? [d.url] : (d.photos ? [d.photos] : []);
    }
    const photoUrl = photos[0] || '';
    const hasImg = Boolean(photoUrl);
    const imgHtml = hasImg
      ? `<img src="${fixUrl(photoUrl)}" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:8px;">`
      : `<div style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;color:var(--text-dim);"><i class="fa-regular fa-image" style="font-size:26px;"></i><span style="font-size:11px;color:var(--text-muted);">Sin imagen seleccionada</span></div>`;
    return `<div class="item-inner" style="flex-direction:column;gap:8px;padding:12px;">
      <div class="imagen-preview" style="width:100%;height:148px;border-radius:8px;background:var(--surface2);display:flex;align-items:center;justify-content:center;overflow:hidden;">
        ${imgHtml}
      </div>
      <div style="display:flex;justify-content:space-between;align-items:center;font-size:11.5px;color:var(--text-muted);">
        <span><i class="fa-regular fa-image" style="color:var(--primary-blue)"></i> Imagen</span>
        <span class="item-chip">${d.tamano || 'Mediano'}</span>
      </div>
    </div>`;
  }
  if (type === 'caja') {
    const bg = d.color_fondo || '#f59e0b';
    const icon = d.icono || '💡';
    return `<div class="item-inner" style="gap:12px;align-items:flex-start;padding:12px 14px;">
      <div style="font-size:22px;line-height:1;margin-top:2px;flex-shrink:0;">${icon}</div>
      <div style="flex:1">
        <div class="item-title" style="font-size:14px;font-weight:700;color:var(--text);">${d.titulo || item.title || 'Tip Destacado'}</div>
        <div class="texto-content" style="margin-top:4px;font-size:13px;color:var(--text-muted);">${d.contenido || ''}</div>
      </div>
    </div>`;
  }
  if (type === 'gif') {
    const url = d.url || '';
    const hasImg = Boolean(url);
    const imgHtml = hasImg
      ? `<img src="${fixUrl(url)}" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:8px;" onerror="this.onerror=null;this.parentElement.innerHTML='<div style=\\\'display:flex;align-items:center;justify-content:center;height:100%;font-size:24px;color:#ce3df3;\\\'>⚡</div>';">`
      : `<div style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;color:#ce3df3;"><i class="fa-solid fa-bolt" style="font-size:26px;"></i><span style="font-size:11px;color:var(--text-muted);">Sin GIF seleccionado</span></div>`;
    return `<div class="item-inner" style="flex-direction:column;gap:8px;padding:12px;">
      <div class="imagen-preview" style="width:100%;height:148px;border-radius:8px;background:var(--surface2);display:flex;align-items:center;justify-content:center;overflow:hidden;">
        ${imgHtml}
      </div>
      <div style="display:flex;justify-content:space-between;align-items:center;font-size:11.5px;color:var(--text-muted);">
        <span><i class="fa-solid fa-bolt" style="color:#ce3df3"></i> GIF</span>
        <span class="item-chip">${d.tamano || 'Mediano'}</span>
      </div>
    </div>`;
  }
  if (type === 'galeria') {
    let photos = [];
    try {
      if (Array.isArray(d.photos)) photos = d.photos;
      else if (typeof d.photos === 'string' && d.photos.startsWith('[')) photos = JSON.parse(d.photos);
      else if (d.photos) photos = d.photos.split(',').map(s => s.trim()).filter(Boolean);
    } catch {
      photos = d.photos ? d.photos.split(',').filter(Boolean) : [];
    }
    const count = photos.length;
    const thumbsHtml = count > 0
      ? `<div style="display:grid;grid-template-columns:repeat(${Math.min(count, 5)}, 1fr);gap:6px;width:100%;height:100px;border-radius:8px;overflow:hidden;">
          ${photos.slice(0, 5).map(u => `<img src="${fixUrl(u)}" style="width:100%;height:100%;object-fit:cover;">`).join('')}
         </div>`
      : `<div style="padding:24px;text-align:center;color:var(--text-muted);font-size:13px;border:1.5px dashed var(--border);border-radius:10px;"><i class="fa-solid fa-images" style="font-size:24px;color:var(--text-dim);margin-bottom:4px;display:block;"></i> Galería de fotos vacía</div>`;

    return `<div class="item-inner" style="flex-direction:column;gap:8px;padding:12px">
      ${thumbsHtml}
      <div style="display:flex;justify-content:space-between;align-items:center;font-size:11.5px;color:var(--text-muted);">
        <span><i class="fa-solid fa-images" style="color:var(--primary-blue)"></i> Galería (${count}/5 fotos)</span>
        <span class="item-chip">${d.tamano || 'Mediano'}</span>
      </div>
    </div>`;
  }
  if (type === 'ubicacion') {
    const name = d.nombre || item.title || 'Ubicación';
    const addr = d.direccion || item.location_query || '';
    const note = d.nota || item.notes || '';
    const mapsUrl = d.maps_url || (name || addr ? `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent((name ? name + ', ' : '') + addr)}` : 'https://maps.google.com');

    return `<div class="item-inner" style="padding:14px 16px;gap:12px;align-items:center;background:var(--surface);border-radius:12px;border:1px solid var(--border);">
      <div style="width:40px;height:40px;border-radius:50%;background:#e0f2fe;color:#0284c7;display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0;">
        <i class="fa-solid fa-location-dot"></i>
      </div>
      <div style="flex:1;min-width:0;">
        <div style="font-weight:700;font-size:14px;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${name}</div>
        ${addr ? `<div style="font-size:12px;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-top:1px;">${addr}</div>` : ''}
        ${note ? `<div style="font-size:11.5px;color:#0284c7;margin-top:3px;font-style:italic;"><i class="fa-regular fa-comment-dots"></i> ${note}</div>` : ''}
      </div>
      <a href="${mapsUrl}" target="_blank" onclick="event.stopPropagation();" style="display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:20px;color:#0f172a;font-size:11.5px;font-weight:600;text-decoration:none;white-space:nowrap;transition:all 0.15s;">
        Ver en Maps <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:10px;"></i>
      </a>
    </div>`;
  }

  let title = '', chips = [], sub = [];
  switch (type) {
    case 'flight':
      const getCity = str => str ? (str.includes('(') ? str.split('(')[0].trim() : str.split(' -')[0].trim()) : '';
      title = (d.origen && d.destino) ? `${d.origen_city || getCity(d.origen)} → ${d.destino_city || getCity(d.destino)}` : (item.title || 'Vuelo');
      if (d.salida) sub.push('<i class="fa-solid fa-plane-departure"></i> ' + (typeof fmtDT === 'function' ? fmtDT(d.salida) : d.salida));
      if (d.llegada) sub.push('<i class="fa-solid fa-plane-arrival"></i> ' + (typeof fmtDT === 'function' ? fmtDT(d.llegada) : d.llegada));
      if (d.aerolinea) chips.push(d.aerolinea);
      if (d.vuelo) chips.push(d.vuelo);
      if (d.clase) chips.push(d.clase);
      if (d.precio) chips.push('$' + (typeof formatNumber === 'function' ? formatNumber(d.precio) : d.precio) + ' USD');
      if (d.reserva) chips.push('Reserva: ' + d.reserva);
      break;
    case 'alojamiento':
      title = d.nombre || item.title || 'Alojamiento';
      if (d.checkin) sub.push('<i class="fa-solid fa-right-to-bracket"></i> ' + (typeof fmtDT === 'function' ? fmtDT(d.checkin.includes('T') ? d.checkin : d.checkin + 'T15:00:00') : d.checkin));
      if (d.checkout) sub.push('<i class="fa-solid fa-right-from-bracket"></i> ' + (typeof fmtDT === 'function' ? fmtDT(d.checkout.includes('T') ? d.checkout : d.checkout + 'T12:00:00') : d.checkout));
      if (d.direccion) sub.push('<i class="fa-solid fa-location-dot"></i> ' + d.direccion);
      if (d.stars) {
        const sNum = parseFloat(d.stars);
        if (!isNaN(sNum) && sNum > 0) {
          const sStr = sNum % 1 === 0 ? sNum.toFixed(1) : sNum.toString();
          const rCount = d.user_ratings_total ? formatReviewsCount(d.user_ratings_total) : '';
          chips.push(`⭐ ${sStr}/5 (${rCount ? rCount + ' opiniones en Google' : 'opiniones en Google'})`);
        }
      }
      if (d.alimentacion) chips.push('<i class="fa-solid fa-utensils"></i> ' + d.alimentacion);
      if (d.habitacion) chips.push('<i class="fa-solid fa-bed"></i> ' + d.habitacion);
      if (d.precio) chips.push('$' + (typeof formatNumber === 'function' ? formatNumber(d.precio) : d.precio) + ' USD');
      if (d.reserva) chips.push('Reserva: ' + d.reserva);
      break;
    case 'transporte':
      title = (d.origen && d.destino) ? `${d.origen} → ${d.destino}` : (d.tipo || item.title || 'Transporte');
      if (d.tipo) sub.push('<i class="fa-solid fa-car"></i> ' + d.tipo);
      if (d.salida) sub.push('<i class="fa-solid fa-clock"></i> ' + (typeof fmtDT === 'function' ? fmtDT(d.salida) : d.salida));
      if (d.llegada) sub.push('<i class="fa-regular fa-clock"></i> ' + (typeof fmtDT === 'function' ? fmtDT(d.llegada) : d.llegada));
      if (d.proveedor) chips.push(d.proveedor);
      if (d.precio) chips.push('$' + (typeof formatNumber === 'function' ? formatNumber(d.precio) : d.precio) + ' USD');
      if (d.reserva) chips.push('Reserva: ' + d.reserva);
      break;
    case 'actividad':
      title = d.nombre || item.title || 'Actividad';
      if (d.direccion || item.location_query) sub.push('<i class="fa-solid fa-location-dot"></i> ' + (d.direccion || item.location_query));
      if (d.stars) {
        const sNum = parseFloat(d.stars);
        if (!isNaN(sNum) && sNum > 0) {
          const sStr = sNum % 1 === 0 ? sNum.toFixed(1) : sNum.toString();
          const rCount = d.user_ratings_total ? formatReviewsCount(d.user_ratings_total) : '';
          chips.push(`⭐ ${sStr}/5 (${rCount ? rCount + ' opiniones en Google' : 'opiniones en Google'})`);
        }
      }
      if (d.fecha) sub.push('<i class="fa-regular fa-clock"></i> ' + (typeof fmtDT === 'function' ? fmtDT(d.fecha) : d.fecha));
      if (d.duracion) chips.push('<i class="fa-solid fa-stopwatch"></i> ' + d.duracion);
      if (d.precio) chips.push('$' + (typeof formatNumber === 'function' ? formatNumber(d.precio) : d.precio) + ' USD');
      if (d.reserva || item.notes) chips.push('Reserva: ' + (d.reserva || item.notes));
      break;
    case 'comida':
      title = d.restaurante || item.title || 'Comida';
      if (d.direccion || item.location_query) sub.push('<i class="fa-solid fa-location-dot"></i> ' + (d.direccion || item.location_query));
      if (d.stars) {
        const sNum = parseFloat(d.stars);
        if (!isNaN(sNum) && sNum > 0) {
          const sStr = sNum % 1 === 0 ? sNum.toFixed(1) : sNum.toString();
          const rCount = d.user_ratings_total ? formatReviewsCount(d.user_ratings_total) : '';
          chips.push(`⭐ ${sStr}/5 (${rCount ? rCount + ' opiniones en Google' : 'opiniones en Google'})`);
        }
      }
      if (d.fecha) sub.push('<i class="fa-regular fa-clock"></i> ' + (typeof fmtDT === 'function' ? fmtDT(d.fecha) : d.fecha));
      if (d.tipo) chips.push('<i class="fa-solid fa-utensils"></i> ' + d.tipo);
      if (d.precio) chips.push('$' + (typeof formatNumber === 'function' ? formatNumber(d.precio) : d.precio) + ' USD');
      if (d.reserva || item.notes) chips.push('Reserva: ' + (d.reserva || item.notes));
      break;
    case 'tour':
      title = d.nombre || item.title || 'Tour';
      if (d.operador) sub.push('<i class="fa-solid fa-building"></i> ' + d.operador);
      if (d.stars) {
        const sNum = parseFloat(d.stars);
        if (!isNaN(sNum) && sNum > 0) {
          const sStr = sNum % 1 === 0 ? sNum.toFixed(1) : sNum.toString();
          const rCount = d.user_ratings_total ? formatReviewsCount(d.user_ratings_total) : '';
          chips.push(`⭐ ${sStr}/5 (${rCount ? rCount + ' opiniones en Google' : 'opiniones en Google'})`);
        }
      }
      if (d.fecha) sub.push('<i class="fa-regular fa-clock"></i> ' + (typeof fmtDT === 'function' ? fmtDT(d.fecha) : d.fecha));
      if (d.duracion) chips.push('<i class="fa-solid fa-stopwatch"></i> ' + d.duracion);
      if (d.personas) chips.push('<i class="fa-solid fa-users"></i> ' + d.personas);
      if (d.precio) chips.push('$' + (typeof formatNumber === 'function' ? formatNumber(d.precio) : d.precio) + ' USD');
      if (d.reserva || item.notes) chips.push('Reserva: ' + (d.reserva || item.notes));
      break;
    default:
      title = item.title || d.nombre || d.titulo || 'Elemento';
      if (d.direccion || item.location_query) sub.push('<i class="fa-solid fa-location-dot"></i> ' + (d.direccion || item.location_query));
      if (item.start_time || d.fecha || d.salida) sub.push('<i class="fa-regular fa-clock"></i> ' + (item.start_time || (typeof fmtDT === 'function' ? fmtDT(d.fecha || d.salida) : (d.fecha || d.salida))));
      if (d.reserva || item.notes) chips.push('Reserva: ' + (d.reserva || item.notes));
      break;
  }

  const docs = type === 'documents' ? (d.documents ? (typeof d.documents === 'string' ? JSON.parse(d.documents) : d.documents) : []) : [];
  let attachFooter = '';
  if (type === 'documents' && docs.length > 0) {
    attachFooter = `<div class="item-attach-footer"><i class="fa-solid fa-paperclip" style="font-size:10px;"></i> ${docs.length} archivo(s)</div>`;
  } else if (d.adjunto_url || d.adjunto) {
    attachFooter = `<div class="item-attach-footer"><i class="fa-solid fa-paperclip" style="font-size:10px;"></i> 1 archivo</div>`;
  }

  const showThumb = type === 'tour';
  const firstPhoto = (d && d.photo_url) ? d.photo_url.split(',')[0].trim() : '';
  const photoThumb = (showThumb && firstPhoto) ? `<div class="item-card-photo" style="width:50px;height:50px;border-radius:8px;overflow:hidden;flex-shrink:0;border:1px solid var(--border);margin-left:8px;box-shadow:var(--shadow-sm);"><img src="${firstPhoto}" style="width:100%;height:100%;object-fit:cover;" /></div>` : '';

  const timeStr = (typeof getItemTimeStr === 'function') ? getItemTimeStr(item) : (item.start_time ? `🕒 ${item.start_time}` : '');
  const timeHtml = timeStr ? `<div class="item-time-label">${timeStr}</div>` : '';

  return `<div class="item-inner">
    ${timeHtml}
    <div class="item-accent-bar" style="background:${cfg.color}"></div>
    <div class="item-icon" style="background:${cfg.bg}">${cfg.icon}</div>
    <div class="item-content">
      <div class="item-type-label" style="color:${cfg.color}">${cfg.label}</div>
      <div class="item-title">${title}</div>
      <div class="item-subtitle" style="display:flex; flex-direction:column; align-items:flex-start; gap:6px;">
        ${sub.length ? `<div style="display:flex; flex-wrap:wrap; align-items:center; justify-content:flex-start; gap:8px;">${sub.map(s => `<span>${s}</span>`).join('')}</div>` : ''}
        ${chips.length ? `<div style="display:flex; flex-wrap:wrap; align-items:center; justify-content:flex-start; gap:8px;">${chips.map(c => `<span class="item-chip">${c}</span>`).join('')}</div>` : ''}
      </div>
      <div style="font-size:12px;color:var(--text-muted);margin-top:6px;display:flex;flex-wrap:wrap;align-items:center;gap:12px;">
        ${d.notas ? `<div style="display:flex;align-items:center;gap:4px;"><i class="fa-solid fa-circle-info" style="font-size:10px;opacity:0.7"></i> ${d.notas}</div>` : ''}
        ${attachFooter}
      </div>
    </div>
    ${photoThumb}
  </div>`;
};

function buildItem(item, idx) {
  const el = document.createElement('div');
  el.className = `canvas-item tipo-${item.type}`; el.dataset.index = idx;

  el.innerHTML = window.getItemInnerHtml(item) + `
  <div class="item-actions" style="position:absolute;right:12px;top:12px;">
    <button class="item-action-btn" onclick="editItem(${idx})"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
    <button class="item-action-btn" onclick="duplicateItem(${idx})" title="Duplicar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button>
    <button class="item-action-btn delete" onclick="deleteItem(${idx})"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg></button>
  </div>`;

  if (['titulo', 'texto', 'imagen', 'gif', 'galeria', 'ubicacion'].includes(item.type)) {
    el.style.position = 'relative';
    el.addEventListener('mouseenter', () => { const act = el.querySelector('.item-actions'); if (act) act.style.opacity = '1'; });
    el.addEventListener('mouseleave', () => { const act = el.querySelector('.item-actions'); if (act) act.style.opacity = '0'; });
  }

  setupReorder(el, idx);
  return el;
}
function setupReorder(el, idx) {
  el.setAttribute('draggable', 'true');
  el.addEventListener('dragstart', e => {
    if (e.target.closest('button')) return;
    dragSourceIndex = idx;
    dragSourceContainer = currentDay === 'portada' ? 'portadaItems' : currentDay === 'cierre' ? 'cierreItems' : 'canvasItems';
    dragType = null;
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setDragImage(new Image(), 0, 0);
    dragGhost.style.opacity = '0';
    setTimeout(() => el.classList.add('dragging-item'), 0);
  });
  el.addEventListener('dragend', () => {
    el.classList.remove('dragging-item');
    clearDropIndicators();
    document.querySelectorAll('#portadaItems .drop-indicator,#cierreItems .drop-indicator').forEach(d => d.remove());
    dragSourceIndex = null; dragSourceContainer = null;
  });
}
function fmtDT(s) {
  if (!s || typeof s !== 'string') return s || '';
  const str = s.trim();
  if (!str) return '';
  if (/^\d{1,2}:\d{2}(:\d{2})?$/.test(str)) return str.substring(0, 5);
  try {
    const cleanStr = str.includes(' ') && !str.includes('T') ? str.replace(' ', 'T') : str;
    const d = new Date(cleanStr);
    if (isNaN(d.getTime())) return str;
    const datePart = d.toLocaleDateString('es', { day: '2-digit', month: 'short' });
    const timePart = d.toLocaleTimeString('es', { hour: '2-digit', minute: '2-digit' });
    return `${datePart} ${timePart}`;
  } catch { return str; }
}
function formatNumber(val) {
  if (val === null || val === undefined || val === '') return '';
  let str = val.toString().trim();
  if (str === '') return '';

  let integerPart = '';
  let decimalPart = undefined;

  if (str.includes(',')) {
    const parts = str.split(',');
    integerPart = parts[0].replace(/[^0-9]/g, '');
    decimalPart = parts[1] ? parts[1].replace(/[^0-9]/g, '').substring(0, 2) : '';
  } else if (str.includes('.')) {
    const lastDot = str.lastIndexOf('.');
    const afterDot = str.substring(lastDot + 1);
    if (afterDot.length <= 2 && /^\d*$/.test(afterDot)) {
      integerPart = str.substring(0, lastDot).replace(/[^0-9]/g, '');
      decimalPart = afterDot.replace(/[^0-9]/g, '');
    } else {
      integerPart = str.replace(/[^0-9]/g, '');
    }
  } else {
    integerPart = str.replace(/[^0-9]/g, '');
  }

  integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

  if (decimalPart !== undefined && decimalPart !== '') {
    return integerPart + ',' + decimalPart;
  }
  return integerPart;
}

function unformatNumber(val) {
  if (val === null || val === undefined) return '';
  const str = val.toString().trim();
  if (!str) return '';

  if (str.includes(',')) {
    const parts = str.split(',');
    const cleanInt = parts[0].replace(/[^0-9]/g, '');
    const cleanDec = parts[1] ? parts[1].replace(/[^0-9]/g, '').substring(0, 2) : '';
    return cleanDec ? `${cleanInt}.${cleanDec}` : cleanInt;
  }

  if (str.includes('.')) {
    const lastDot = str.lastIndexOf('.');
    const afterDot = str.substring(lastDot + 1);
    if (afterDot.length <= 2 && /^\d*$/.test(afterDot)) {
      const cleanInt = str.substring(0, lastDot).replace(/[^0-9]/g, '');
      const cleanDec = afterDot.replace(/[^0-9]/g, '');
      return cleanDec ? `${cleanInt}.${cleanDec}` : cleanInt;
    }
    return str.replace(/[^0-9]/g, '');
  }

  return str.replace(/[^0-9]/g, '');
}

function allowPriceKeys(e) {
  const allowedKeys = ['Backspace', 'Delete', 'Tab', 'Enter', 'ArrowLeft', 'ArrowRight', 'Home', 'End'];
  if (allowedKeys.includes(e.key)) return;
  if (!/[0-9.,]/.test(e.key)) {
    e.preventDefault();
  }
}

function formatPriceInput(e) {
  const inp = e.target || e;
  let val = inp.value;
  if (!val) return;

  if (!val.includes(',') && val.includes('.')) {
    const lastDot = val.lastIndexOf('.');
    const afterDot = val.substring(lastDot + 1);
    if (afterDot.length <= 2 && /^\d*$/.test(afterDot)) {
      val = val.substring(0, lastDot) + ',' + afterDot;
    }
  }

  if (val.includes(',')) {
    const parts = val.split(',');
    const intStr = parts[0].replace(/[^0-9]/g, '');
    const decStr = parts.slice(1).join('').replace(/[^0-9]/g, '').substring(0, 2);
    const formattedInt = intStr.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    inp.value = formattedInt + ',' + decStr;
  } else {
    const intStr = val.replace(/[^0-9]/g, '');
    const formattedInt = intStr.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    inp.value = formattedInt;
  }
}
function fmtDate(s) {
  if (!s || typeof s !== 'string') return s || '';
  try {
    const cleanStr = s.includes('T') ? s.split('T')[0] : s;
    const d = new Date(cleanStr + 'T00:00:00');
    if (isNaN(d.getTime())) return s;
    return d.toLocaleDateString('es', { day: 'numeric', month: 'long', year: 'numeric' });
  } catch { return s; }
}

function fmtDateTab(s) {
  if (!s || typeof s !== 'string') return s || '';
  try {
    const cleanStr = s.includes('T') ? s.split('T')[0] : s;
    const d = new Date(cleanStr + 'T00:00:00');
    if (isNaN(d.getTime())) return s;
    return d.toLocaleDateString('es', { day: 'numeric', month: 'short' });
  } catch { return s; }
}
function addDaysToDate(dateStr, daysToAdd) {
  if (!dateStr) return '';
  try {
    const d = new Date(dateStr + 'T00:00:00');
    d.setDate(d.getDate() + daysToAdd);
    return d.toISOString().split('T')[0];
  } catch {
    return dateStr;
  }
}
function editItem(idx) {
  const arr = currentDay === 'portada' ? portadaItems : currentDay === 'cierre' ? cierreItems : days[currentDay];
  openModal(arr[idx].type, idx);
}
function deleteItem(idx) {
  openConfirm('¿Eliminar elemento?', 'Esta acción no se puede deshacer.', () => {
    if (currentDay === 'portada') portadaItems.splice(idx, 1);
    else if (currentDay === 'cierre') cierreItems.splice(idx, 1);
    else days[currentDay].splice(idx, 1);
    unsavedChanges = true;
    renderCanvas();
    autoSaveProTrip();
    showToast('<i class="fa-solid fa-trash-can"></i>', 'Elemento eliminado');
  });
}
function duplicateItem(idx) {
  const arr = currentDay === 'portada' ? portadaItems : currentDay === 'cierre' ? cierreItems : days[currentDay];
  if (!arr[idx]) return;
  const clone = JSON.parse(JSON.stringify(arr[idx]));
  arr.splice(idx + 1, 0, clone);
  unsavedChanges = true;
  renderCanvas();
  autoSaveProTrip();
  showToast('<i class="fa-solid fa-copy"></i>', 'Elemento duplicado');
}

// MODAL
const modalOverlay = document.getElementById('modalOverlay');
const modalBody = document.getElementById('modalBody');

function createInfoSpan(helpText, isUpgrade = false) {
  const infoSpan = document.createElement('span');
  infoSpan.className = 'info-icon' + (isUpgrade ? ' info-basic' : '');
  let innerHTML = `
    <i class="fa-solid fa-circle-info"></i>
    <div class="info-popover">
      <p>${helpText}</p>
  `;

  if (isUpgrade) {
    innerHTML += `
      <button type="button" class="btn-upgrade-popover" onclick="window.location.href='/profile?tab=subscription'">Mejora tu plan</button>
    `;
  }

  innerHTML += `</div>`;
  infoSpan.innerHTML = innerHTML;

  infoSpan.onclick = (e) => {
    e.stopPropagation();
    infoSpan.classList.toggle('active');
  };
  return infoSpan;
}

function addPhotoFallback(container, type, showHelp = true, photoInp = null, appendToContainer = true) {
  const googleTypes = ['alojamiento', 'actividad', 'comida', 'tour'];
  if (!googleTypes.includes(type)) return null;

  if (showHelp) {
    const helpText = document.createElement('div');
    helpText.className = 'photo-fallback-help';
    helpText.style = 'margin-top:5px; margin-bottom:8px; font-size:12px; color:var(--text-muted); font-style:italic; line-height:1.4;';
    helpText.textContent = 'Si no deseas usar la imagen predeterminada de Google Maps, puedes usar una imagen de Unsplash o subir la tuya.';
    container.appendChild(helpText);
  }

  const btnGroup = document.createElement('div');
  btnGroup.style = 'display:flex; gap:10px; margin-bottom:20px;';

  if (!photoInp) photoInp = container.querySelector('input[data-key="photo_url"]');

  const uBtn = document.createElement('button');
  uBtn.className = 'btn-secondary';
  uBtn.type = 'button';
  uBtn.style = 'flex:1; font-size:12px; height:34px; display:flex; align-items:center; justify-content:center; gap:8px;';
  uBtn.innerHTML = '<i class="fa-brands fa-unsplash"></i> Unsplash (máx. 3)';
  uBtn.onclick = () => openUnsplash('item_photo', photoInp);

  const upBtn = document.createElement('button');
  upBtn.className = 'btn-secondary';
  upBtn.type = 'button';
  upBtn.style = 'flex:1; font-size:12px; height:34px; display:flex; align-items:center; justify-content:center; gap:8px;';
  upBtn.innerHTML = '<i class="fa-solid fa-upload"></i> Subir foto (máx. 3)';
  const fileInp = document.createElement('input');
  fileInp.type = 'file'; fileInp.accept = 'image/*'; fileInp.multiple = true; fileInp.style.display = 'none';
  fileInp.onchange = (e) => handleItemPhotoUpload(e, photoInp);
  upBtn.onclick = () => fileInp.click();

  btnGroup.appendChild(uBtn);
  btnGroup.appendChild(upBtn);

  if (appendToContainer) {
    container.appendChild(btnGroup);
  }
  return btnGroup;
}
window.copilotEditingIndex = null;

function getGooglePlacesUsageCount() {
  let count = 0;
  const countInArray = (arr) => {
    if (!Array.isArray(arr)) return;
    arr.forEach(item => {
      if (item && item.data) {
        if (item.data._google_place_used || item.data.place_id || (item.data.photo_url && item.data.photo_url.includes('/storage/places/'))) {
          count++;
        }
      }
    });
  };

  if (typeof days === 'object' && days) {
    Object.values(days).forEach(dayItems => countInArray(dayItems));
  }
  if (typeof portadaItems !== 'undefined') countInArray(portadaItems);
  if (typeof cierreItems !== 'undefined') countInArray(cierreItems);

  return count;
}


function getPlanUsageCounts() {
  let unsplashCount = 0;
  let giphyCount = 0;
  let googlePlacesCount = 0;

  const checkItem = (item) => {
    if (!item || !item.data) return;
    const url = (item.data.photo_url || item.data.image || '').toLowerCase();
    const type = (item.data.type || '').toLowerCase();
    
    if (item.data._google_place_used || item.data.place_id || url.includes('/storage/places/')) {
      googlePlacesCount++;
    }

    if (url.includes('unsplash') || type === 'unsplash') {
      unsplashCount++;
    }

    if (url.includes('giphy') || url.includes('.gif') || type === 'giphy') {
      giphyCount++;
    }
  };

  const checkArray = (arr) => {
    if (!Array.isArray(arr)) return;
    arr.forEach(checkItem);
  };

  if (typeof days === 'object' && days) {
    Object.values(days).forEach(checkArray);
  }
  if (typeof portadaItems !== 'undefined') checkArray(portadaItems);
  if (typeof cierreItems !== 'undefined') checkArray(cierreItems);

  if (typeof proState !== 'undefined' && proState && proState.portadaPhotoUrl) {
    const url = (proState.portadaPhotoUrl || '').toLowerCase();
    if (url.includes('unsplash')) unsplashCount++;
    if (url.includes('giphy') || url.includes('.gif')) giphyCount++;
  }

  return { unsplashCount, giphyCount, googlePlacesCount };
}


function checkGooglePlacesLimit() {
  const userPlan = (typeof window.viantrypUserPlan === 'string' && window.viantrypUserPlan) ? window.viantrypUserPlan.toLowerCase() : 'básico';
  const isTrial = window.viantrypIsTrialActive === true;

  if (['colaborativo', 'corporativo', 'negocios'].includes(userPlan)) {
    return true;
  }

  if (editingIndex !== null) {
    const arr = (currentDay === 'portada') ? portadaItems : ((currentDay === 'cierre') ? cierreItems : (days[currentDay] || []));
    const existItem = arr[editingIndex];
    if (existItem && existItem.data && (existItem.data._google_place_used || existItem.data.place_id || (existItem.data.photo_url && existItem.data.photo_url.includes('/storage/places/')))) {
      return true;
    }
  }

  const counts = getPlanUsageCounts();
  const limit = (userPlan === 'avanzado' || userPlan === 'viajero pro' || isTrial) ? 50 : 5;

  if (counts.googlePlacesCount >= limit) {
    if (typeof window.openProUpgradeInlineModal === 'function') {
      if (limit === 5) {
        window.openProUpgradeInlineModal('Límite de Google Places Alcanzado', 'Has alcanzado el límite de 5 búsquedas de Google Places por itinerario para el Plan Básico. Actualiza a Viajero Pro para hasta 50 búsquedas.');
      } else {
        window.openProUpgradeInlineModal('Límite de Google Places Alcanzado', 'Has alcanzado el límite de 50 búsquedas de Google Places por itinerario para tu Plan Viajero Pro.');
      }
    } else if (typeof openUpgradeModal === 'function') {
      openUpgradeModal();
    }
    return false;
  }
  return true;
}

function openModal(type, editIdx = null, customData = null) {
  if (typeof currentDay !== 'number' && currentDay !== 'portada' && currentDay !== 'cierre' && customData === null) return;

  // Clean up any orphaned Google Places autocomplete containers from previous modal sessions
  document.querySelectorAll('.pac-container').forEach(el => el.remove());

  const isPremium = typeof window.viantrypUserPlan !== 'undefined' && window.viantrypUserPlan !== 'básico';

  pendingType = type; editingIndex = editIdx; starRating = 0;
  const arr = (currentDay === 'portada') ? portadaItems : ((currentDay === 'cierre') ? cierreItems : (days[currentDay] || []));
  const cfg = C[type] || C['actividad'];
  const existData = customData || (editIdx !== null && arr[editIdx] ? arr[editIdx].data : {});
  document.getElementById('modalIcon').innerHTML = cfg.icon; document.getElementById('modalIcon').style.background = cfg.bg;
  const modalName = cfg.modalTitle || cfg.label;
  document.getElementById('modalTitle').textContent = ((editIdx !== null || customData !== null) ? 'Editar ' : 'Agregar ') + modalName;
  const subEl = document.getElementById('modalSubtitle');
  if (subEl) subEl.textContent = '';
  modalBody.innerHTML = '';
  const fields = cfg.fields;
  let currentGroup = null;
  let currentTarget = modalBody;

  for (let i = 0; i < fields.length; i++) {
    const f = fields[i];

    if (f.group === 'google' && currentGroup !== 'google') {
      const gbox = document.createElement('div');
      gbox.className = 'field-group-box';
      modalBody.appendChild(gbox);
      currentTarget = gbox;
      currentGroup = 'google';
    } else if (!f.group && currentGroup === 'google') {
      // Transition out of google box: Append photo fallback here
      addPhotoFallback(modalBody, type);
      currentTarget = modalBody;
      currentGroup = null;
    }

    const next = fields[i + 1];
    let fieldEl;
    if (f.t === 'textarea' || f.t === 'color-picker' || f.t === 'richtext' || f.t === 'stars' || f.t === 'separator-chips' || f.t === 'icon-selector' || f.t === 'image-picker' || f.t === 'gif-picker' || f.t === 'gallery-picker' || f.fw) {
      if (type === 'tour' && f.k === 'photo_url') {
        fieldEl = buildField(f, existData);
        const label = fieldEl.querySelector('.form-label');
        const photoInp = fieldEl.querySelector('input');
        const btns = addPhotoFallback(fieldEl, type, false, photoInp, false);
        if (label && btns) {
          label.parentNode.insertBefore(btns, label.nextSibling);
        }
      } else {
        fieldEl = buildField(f, existData);
      }

      // Google places info icons
      if (f.hasInfo) {
        const lbl = fieldEl.querySelector('.form-label');
        if (lbl) {
          let pText = 'Tu plan añade automáticamente datos de Google Maps al recuadro inferior al escribir ';
          if (type === 'alojamiento') pText += 'el nombre del hotel.';
          else if (type === 'actividad') pText += 'el lugar de la actividad.';
          else if (type === 'comida') pText += 'el nombre del restaurante.';
          else pText += 'en este campo.';
          lbl.appendChild(createInfoSpan(pText));
        }
      }

      currentTarget.appendChild(fieldEl);
    } else if (next && !next.fw && next.t !== 'textarea' && next.t !== 'color-picker' && next.t !== 'richtext' && next.t !== 'stars' && next.t !== 'separator-chips' && next.t !== 'icon-selector' && next.group === f.group) {
      const row = document.createElement('div');
      row.className = 'form-row';

      const f1 = buildField(f, existData);
      if (f.hasInfo) {
        const lbl = f1.querySelector('.form-label');
        if (lbl) {
          let pText = 'Tu plan añade automáticamente datos de Google Maps al recuadro inferior al escribir ';
          if (type === 'alojamiento') pText += 'el nombre del hotel.';
          else if (type === 'actividad') pText += 'el lugar de la actividad.';
          else if (type === 'comida') pText += 'el nombre del restaurante.';
          lbl.appendChild(createInfoSpan(pText));
        }
      }

      const f2 = buildField(next, existData);
      if (next.hasInfo) {
        const lbl = f2.querySelector('.form-label');
        if (lbl) {
          let pText = 'Tu plan añade automáticamente datos de Google Maps al recuadro inferior al escribir ';
          if (type === 'alojamiento') pText += 'el nombre del hotel.';
          else if (type === 'actividad') pText += 'el lugar de la actividad.';
          else if (type === 'comida') pText += 'el nombre del restaurante.';
          lbl.appendChild(createInfoSpan(pText));
        }
      }

      row.appendChild(f1);
      row.appendChild(f2);
      currentTarget.appendChild(row);
      i++;
    } else {
      fieldEl = buildField(f, existData);
      if (f.hasInfo) {
        const lbl = fieldEl.querySelector('.form-label');
        if (lbl) {
          let pText = 'Tu plan añade automáticamente datos de Google Maps al recuadro inferior al escribir ';
          if (type === 'alojamiento') pText += 'el nombre del hotel.';
          else if (type === 'actividad') pText += 'el lugar de la actividad.';
          else if (type === 'comida') pText += 'el nombre del restaurante.';
          lbl.appendChild(createInfoSpan(pText));
        }
      }
      currentTarget.appendChild(fieldEl);
    }
  }

  // Safety: handle group end if it was at the last field
  if (currentGroup === 'google' || type === 'tour') {
    addPhotoFallback(modalBody, type);
  }

  modalOverlay.classList.add('open');

  const closeAllPacContainers = () => {
    document.querySelectorAll('.pac-container').forEach(el => {
      el.style.display = 'none';
    });
  };

  setTimeout(() => {
    const f = modalBody.querySelector('input,textarea,select'); if (f) f.focus();

    // Google Places Autocomplete for Ubicacion
    if (type === 'ubicacion' && window.google && window.google.maps && window.google.maps.places) {
      const nameInp = modalBody.querySelector('input[data-key="nombre"]');
      const addrInp = modalBody.querySelector('input[data-key="direccion"]');
      const mapsUrlInp = modalBody.querySelector('input[data-key="maps_url"]');

      const updateFallbackMapsUrl = () => {
        if (!mapsUrlInp) return;
        const aVal = addrInp ? addrInp.value.trim() : '';
        const nVal = nameInp ? nameInp.value.trim() : '';
        const q = aVal || nVal;
        if (q) {
          mapsUrlInp.value = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(q)}`;
        }
      };

      if (nameInp) {
        const autocomplete = new window.google.maps.places.Autocomplete(nameInp, {});

        nameInp.addEventListener('blur', () => setTimeout(closeAllPacContainers, 200));
        nameInp.addEventListener('input', (e) => {
          if (e && !e.isTrusted) return;
          updateFallbackMapsUrl();
        });

        autocomplete.addListener('place_changed', () => {
          closeAllPacContainers();
          const place = autocomplete.getPlace();
          if (!place) return;
          if (!checkGooglePlacesLimit()) {
            nameInp.value = '';
            return;
          }
          nameInp.dataset.googlePlaceUsed = 'true';
          if (place.name) nameInp.value = place.name;
          if (addrInp && place.formatted_address) {
            addrInp.value = place.formatted_address;
            addrInp.dispatchEvent(new Event('input', { bubbles: true }));
          }
          let mapsUrl = place.url || '';
          if (!mapsUrl && place.formatted_address) {
            mapsUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(place.formatted_address)}`;
          } else if (!mapsUrl && place.geometry && place.geometry.location) {
            mapsUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(place.name || '')}&query_place_id=${place.place_id || ''}`;
          }
          if (mapsUrlInp) mapsUrlInp.value = mapsUrl;
        });
      }

      if (addrInp) {
        const autocompleteAddr = new window.google.maps.places.Autocomplete(addrInp, { types: ['geocode'] });

        addrInp.addEventListener('blur', () => setTimeout(closeAllPacContainers, 200));
        addrInp.addEventListener('input', (e) => {
          if (e && !e.isTrusted) return;
          updateFallbackMapsUrl();
        });

        autocompleteAddr.addListener('place_changed', () => {
          closeAllPacContainers();
          const place = autocompleteAddr.getPlace();
          if (!place) return;
          if (!checkGooglePlacesLimit()) {
            addrInp.value = '';
            return;
          }
          activeAddrInp.dataset.googlePlaceUsed = 'true';
          if (place.formatted_address) {
            addrInp.value = place.formatted_address;
            addrInp.dispatchEvent(new Event('input', { bubbles: true }));
          }
          let mapsUrl = place.url || '';
          if (!mapsUrl && place.formatted_address) {
            mapsUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(place.formatted_address)}`;
          }
          if (mapsUrlInp) mapsUrlInp.value = mapsUrl;
        });
      }
    }

    // Google Places Autocomplete API
    const googleTypes = ['actividad', 'comida'];
    if (googleTypes.includes(type) && window.google && window.google.maps && window.google.maps.places) {
      const keyMap = { actividad: 'direccion', comida: 'restaurante' };
      const nameInp = modalBody.querySelector('input[data-key="' + keyMap[type] + '"]');
      if (nameInp) {
        const autocomplete = new window.google.maps.places.Autocomplete(nameInp, { types: ['establishment'] });

        nameInp.addEventListener('blur', () => setTimeout(closeAllPacContainers, 200));
        nameInp.addEventListener('input', (e) => {
          if (e && !e.isTrusted) return;
          delete nameInp.dataset.lat;
          delete nameInp.dataset.lng;
        });

        autocomplete.addListener('place_changed', () => {
          closeAllPacContainers();
          setTimeout(closeAllPacContainers, 50);
          setTimeout(closeAllPacContainers, 150);
          const place = autocomplete.getPlace();
          if (!place || !place.place_id) return;
          if (!checkGooglePlacesLimit()) {
            nameInp.value = '';
            return;
          }
          nameInp.dataset.googlePlaceUsed = 'true';

          if (place.name) {
            nameInp.value = place.name;
          }

          if (place.geometry && place.geometry.location) {
            nameInp.dataset.lat = place.geometry.location.lat();
            nameInp.dataset.lng = place.geometry.location.lng();
          }

          nameInp.blur();
          closeAllPacContainers();
          setTimeout(closeAllPacContainers, 80);
          setTimeout(closeAllPacContainers, 200);

          const setVal = (k, v) => { const el = modalBody.querySelector('input[data-key="' + k + '"]'); if (el) { el.value = v; el.dispatchEvent(new Event('input', { bubbles: true })); } };
          if (place.formatted_address && type !== 'actividad') setVal('direccion', place.formatted_address);
          if (place.formatted_phone_number) setVal('phone', place.formatted_phone_number);
          if (place.website) setVal('website', place.website);

          // Fetch permanent photo URLs from our server
          const previewCont = modalBody.querySelector('.photo-preview-container');
          if (previewCont) {
            previewCont.innerHTML = `
              <div class="photo-loading-spinner" style="border: 1.5px dashed var(--border); border-radius: 10px; padding: 20px; text-align: center; color: var(--text-muted); font-size: 13px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; background: var(--surface2);">
                <div class="spinner" style="width: 24px; height: 24px; border: 3px solid rgba(20, 184, 166, 0.1); border-top-color: #14b8a6; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                <span>Buscando foto del lugar...</span>
              </div>
            `;
          }

          const tripParam = window.tripId ? `&trip_id=${window.tripId}` : '';
          fetch(`/api/places/details?place_id=${place.place_id}${tripParam}`)
            .then(res => res.json())
            .then(data => {
              if (data.photos && data.photos.length > 0) {
                const urls = data.photos.slice(0, 3).map(p => p.url).join(',');
                setVal('photo_url', urls);
              } else {
                const inp = modalBody.querySelector('input[data-key="photo_url"]');
                if (inp) inp.dispatchEvent(new Event('input', { bubbles: true }));
              }
              if (data.rating) {
                starRating = data.rating;
                const sr = modalBody.querySelector('.star-rating');
                if (sr) {
                  updateGoogleRatingInModal(sr, data.rating, data.user_ratings_total);
                }
              }
            })
            .catch(err => {
              console.error('Error fetching place details:', err);
              const inp = modalBody.querySelector('input[data-key="photo_url"]');
              if (inp) inp.dispatchEvent(new Event('input', { bubbles: true }));
            });

          if (place.rating) {
            starRating = place.rating;
            const sr = modalBody.querySelector('.star-rating');
            if (sr) {
              updateGoogleRatingInModal(sr, place.rating, place.user_ratings_total);
            }
          }
        });
      }
    }

    // Google Places Autocomplete for Transporte (Desde / Hasta)
    if (type === 'transporte' && window.google && window.google.maps && window.google.maps.places) {
      ['origen', 'destino'].forEach(key => {
        const inp = modalBody.querySelector('input[data-key="' + key + '"]');
        if (inp) {
          const autocomplete = new window.google.maps.places.Autocomplete(inp, {});

          inp.addEventListener('blur', () => setTimeout(closeAllPacContainers, 200));
          inp.addEventListener('input', (e) => {
            if (e && !e.isTrusted) return;
            delete inp.dataset.address;
            delete inp.dataset.lat;
            delete inp.dataset.lng;
          });

          autocomplete.addListener('place_changed', () => {
            closeAllPacContainers();
            const place = autocomplete.getPlace();
            if (!place) return;
            if (!checkGooglePlacesLimit()) {
              inp.value = '';
              return;
            }
            inp.dataset.googlePlaceUsed = 'true';

            // Show friendly name in the input box, save full geocodable address in dataset
            if (place.name) {
              inp.value = place.name;
            } else if (place.formatted_address) {
              inp.value = place.formatted_address;
            }

            if (place.formatted_address) {
              inp.dataset.address = place.formatted_address;
            } else if (place.name) {
              inp.dataset.address = place.name;
            }

            if (place.geometry && place.geometry.location) {
              inp.dataset.lat = place.geometry.location.lat();
              inp.dataset.lng = place.geometry.location.lng();
            }
          });
        }
      });
    }

    // Add Alojamiento tipo toggle logic
    if (type === 'alojamiento') {
      const selectTipo = modalBody.querySelector('select[data-key="tipo_alojamiento"]');
      if (selectTipo) {
        let manualAddressRevealed = false;

        const handleTipoChange = () => {
          const val = selectTipo.value || 'Hotel';
          const isHotel = val === 'Hotel';

          // Clean up any existing Google autocomplete containers before recreating inputs
          closeAllPacContainers();
          document.querySelectorAll('.pac-container').forEach(el => el.remove());

          // Update Nombre label
          const nameInp = modalBody.querySelector('input[data-key="nombre"]');
          if (nameInp) {
            const parent = nameInp.closest('.form-group');
            const lbl = parent ? parent.querySelector('.form-label') : null;
            if (lbl) {
              const infoIcon = lbl.querySelector('.info-icon');
              lbl.innerHTML = isHotel ? 'Nombre del hotel' : 'Nombre del alojamiento';
              if (isHotel && infoIcon) {
                lbl.appendChild(infoIcon);
              }
            }

            // Ensure fallback link button exists below Nombre del hotel
            let fallbackCont = parent ? parent.querySelector('.hotel-manual-address-wrap') : null;
            if (!fallbackCont && parent) {
              fallbackCont = document.createElement('div');
              fallbackCont.className = 'hotel-manual-address-wrap';
              fallbackCont.innerHTML = `
                <button type="button" class="btn-manual-address-toggle" id="hotelManualAddressBtn">
                  <i class="fa-solid fa-location-dot"></i> ¿No lo encuentras? Ingresa la dirección manualmente
                </button>
              `;
              parent.appendChild(fallbackCont);

              const manualBtn = fallbackCont.querySelector('#hotelManualAddressBtn');
              if (manualBtn) {
                manualBtn.onclick = (e) => {
                  e.preventDefault();
                  manualAddressRevealed = true;
                  const currentAddrInp = modalBody.querySelector('input[data-key="direccion"]');
                  const currentAddrFg = currentAddrInp ? currentAddrInp.closest('.form-group') : null;
                  if (currentAddrFg) currentAddrFg.style.display = '';
                  if (fallbackCont) fallbackCont.style.display = 'none';
                  if (currentAddrInp) currentAddrInp.focus();
                };
              }
            }
          }

          const fallbackCont = modalBody.querySelector('.hotel-manual-address-wrap');

          // Toggle visibility of specific fields inside the google group box
          const gBox = modalBody.querySelector('.field-group-box');
          if (gBox) {
            if (isHotel) {
              gBox.style.background = '';
              gBox.style.border = '';
              gBox.style.padding = '';
              gBox.style.borderRadius = '';
              gBox.style.margin = '';
            } else {
              gBox.style.background = 'transparent';
              gBox.style.border = 'none';
              gBox.style.padding = '0';
              gBox.style.borderRadius = '0';
              gBox.style.margin = '0';
            }

            // Hide/show Phone, Website, Calificación (Stars)
            const fieldsToToggle = ['phone', 'website', 'stars'];
            fieldsToToggle.forEach(k => {
              const fieldEl = gBox.querySelector(`[data-key="${k}"]`) || gBox.querySelector(`.star-rating input[data-key="${k}"]`);
              if (fieldEl) {
                const fg = fieldEl.closest('.form-group') || fieldEl.closest('.form-row');
                if (fg) fg.style.display = isHotel ? '' : 'none';
              }
            });

            // Toggle Dirección field visibility in Hotel vs Airbnb
            const addrField = gBox.querySelector('input[data-key="direccion"]');
            const addrFg = addrField ? addrField.closest('.form-group') : null;
            if (addrFg) {
              if (isHotel) {
                const hasAddrVal = addrField && addrField.value && addrField.value.trim().length > 0;
                if (manualAddressRevealed || hasAddrVal) {
                  addrFg.style.display = '';
                  if (fallbackCont) fallbackCont.style.display = 'none';
                } else {
                  addrFg.style.display = 'none';
                  if (fallbackCont) fallbackCont.style.display = 'flex';
                }
              } else {
                addrFg.style.display = '';
                if (fallbackCont) fallbackCont.style.display = 'none';
              }
            }

            // Toggle infoIcon/help text inside google box if any
            const infoSpan = gBox.querySelector('.info-icon');
            if (infoSpan) {
              infoSpan.style.display = isHotel ? '' : 'none';
            }
            const helpText = gBox.querySelector('div[style*="margin-top:5px"]');
            if (helpText) {
              helpText.style.display = isHotel ? '' : 'none';
            }
          }

          // Update photo fallback help text dynamically
          const helpText = modalBody.querySelector('.photo-fallback-help');
          if (helpText) {
            if (isHotel) {
              helpText.textContent = 'Si no deseas usar la imagen predeterminada de Google Maps, puedes usar una imagen de Unsplash o subir la tuya.';
            } else {
              helpText.textContent = 'Adjunta una imagen del alojamiento o elige una desde Unsplash.';
            }
          }

          // Setup Google Autocomplete dynamically (exact match to traslados)
          if (window.google && window.google.maps && window.google.maps.places) {
            const addrInp = modalBody.querySelector('input[data-key="direccion"]');

            // Helper to clone input to strip old autocomplete event listeners
            const cleanInput = (inp) => {
              if (!inp) return null;
              const cleanInp = inp.cloneNode(true);
              cleanInp.value = inp.value;
              inp.parentNode.replaceChild(cleanInp, inp);
              return cleanInp;
            };

            const activeNameInp = nameInp ? cleanInput(nameInp) : null;
            const activeAddrInp = addrInp ? cleanInput(addrInp) : null;

            // Google Places address autocomplete for Dirección (enabled for BOTH Hotel and Airbnb branches)
            if (activeAddrInp) {
              const autocompleteDireccion = new window.google.maps.places.Autocomplete(activeAddrInp, {});

              activeAddrInp.addEventListener('blur', () => setTimeout(closeAllPacContainers, 200));

              autocompleteDireccion.addListener('place_changed', () => {
                closeAllPacContainers();
                const place = autocompleteDireccion.getPlace();
                if (!place) return;
                if (!checkGooglePlacesLimit()) {
                  activeAddrInp.value = '';
                  return;
                }
                activeAddrInp.dataset.googlePlaceUsed = 'true';

                if (place.formatted_address) {
                  activeAddrInp.value = place.formatted_address;
                } else if (place.name) {
                  activeAddrInp.value = place.name;
                }
                activeAddrInp.dispatchEvent(new Event('input', { bubbles: true }));
              });
            }

            // Google Places hotel establishment autocomplete for Nombre (Hotel branch)
            if (isHotel && activeNameInp) {
              const autocompleteNombre = new window.google.maps.places.Autocomplete(activeNameInp, { types: ['establishment'] });

              activeNameInp.addEventListener('blur', () => setTimeout(closeAllPacContainers, 200));
              activeNameInp.addEventListener('input', (e) => {
                if (e && !e.isTrusted) return;
                delete activeNameInp.dataset.lat;
                delete activeNameInp.dataset.lng;
              });

              autocompleteNombre.addListener('place_changed', () => {
                closeAllPacContainers();
                const place = autocompleteNombre.getPlace();
                if (!place || !place.place_id) return;
                if (!checkGooglePlacesLimit()) {
                  activeNameInp.value = '';
                  return;
                }
                activeNameInp.dataset.googlePlaceUsed = 'true';

                if (place.name) activeNameInp.value = place.name;

                if (place.geometry && place.geometry.location) {
                  activeNameInp.dataset.lat = place.geometry.location.lat();
                  activeNameInp.dataset.lng = place.geometry.location.lng();
                }

                const setVal = (k, v) => {
                  const el = modalBody.querySelector('input[data-key="' + k + '"]');
                  if (el) {
                    el.value = v;
                    el.dispatchEvent(new Event('input', { bubbles: true }));
                  }
                };
                if (place.formatted_address) {
                  setVal('direccion', place.formatted_address);
                  const currentAddrInp = modalBody.querySelector('input[data-key="direccion"]');
                  const currentAddrFg = currentAddrInp ? currentAddrInp.closest('.form-group') : null;
                  if (currentAddrFg) currentAddrFg.style.display = '';
                  const currentFallback = modalBody.querySelector('.hotel-manual-address-wrap');
                  if (currentFallback) currentFallback.style.display = 'none';
                }
                if (place.formatted_phone_number) setVal('phone', place.formatted_phone_number);
                if (place.website) setVal('website', place.website);

                // Fetch permanent photo URLs from our server
                const previewCont = modalBody.querySelector('.photo-preview-container');
                if (previewCont) {
                  previewCont.innerHTML = `
                    <div class="photo-loading-spinner" style="border: 1.5px dashed var(--border); border-radius: 10px; padding: 20px; text-align: center; color: var(--text-muted); font-size: 13px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; background: var(--surface2);">
                      <div class="spinner" style="width: 24px; height: 24px; border: 3px solid rgba(20, 184, 166, 0.1); border-top-color: #14b8a6; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                      <span>Buscando foto del lugar...</span>
                    </div>
                  `;
                }

                const tripParam = window.tripId ? `&trip_id=${window.tripId}` : '';
                fetch(`/api/places/details?place_id=${place.place_id}${tripParam}`)
                  .then(res => res.json())
                  .then(data => {
                    if (data.photos && data.photos.length > 0) {
                      const urls = data.photos.slice(0, 3).map(p => p.url).join(',');
                      setVal('photo_url', urls);
                    } else {
                      const inp = modalBody.querySelector('input[data-key="photo_url"]');
                      if (inp) inp.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                    if (data.rating) {
                      starRating = data.rating;
                      const sr = modalBody.querySelector('.star-rating');
                      if (sr) {
                        updateGoogleRatingInModal(sr, data.rating, data.user_ratings_total);
                      }
                    }
                  })
                  .catch(err => {
                    console.error('Error fetching place details:', err);
                    const inp = modalBody.querySelector('input[data-key="photo_url"]');
                    if (inp) inp.dispatchEvent(new Event('input', { bubbles: true }));
                  });

                if (place.rating) {
                  starRating = place.rating;
                  const sr = modalBody.querySelector('.star-rating');
                  if (sr) {
                    updateGoogleRatingInModal(sr, place.rating, place.user_ratings_total);
                  }
                }
              });
            }
          }
        };

        selectTipo.addEventListener('change', handleTipoChange);
        handleTipoChange(); // run immediately
      }
    }

    // Add Actividad optional place & dynamic section logic
    if (type === 'actividad') {
      const addrInp = modalBody.querySelector('input[data-key="direccion"]');
      const starsInp = modalBody.querySelector('.star-rating input[data-key="stars"]') || modalBody.querySelector('[data-key="stars"]');
      const starsFg = starsInp ? (starsInp.closest('.form-group') || starsInp.closest('.star-rating')) : null;
      if (starsFg) {
        starsFg.classList.add('activity-stars-wrapper');
      }

      // Add "Esta actividad no tiene un lugar específico" checkbox under the "Lugar" field
      if (addrInp) {
        const addrFg = addrInp.closest('.form-group');
        let noPlaceWrap = addrFg ? addrFg.querySelector('.activity-no-place-wrap') : null;
        if (!noPlaceWrap && addrFg) {
          noPlaceWrap = document.createElement('div');
          noPlaceWrap.className = 'activity-no-place-wrap';
          noPlaceWrap.innerHTML = `
            <label class="activity-no-place-label" for="activityNoPlaceCheckbox">
              <input type="checkbox" id="activityNoPlaceCheckbox" class="activity-no-place-checkbox">
              <span>Esta actividad no tiene un lugar específico</span>
            </label>
          `;
          addrFg.appendChild(noPlaceWrap);
        }

        const noPlaceCheckbox = addrFg ? addrFg.querySelector('#activityNoPlaceCheckbox') : null;

        const updateActivityPlaceState = (source = 'auto') => {
          const hasPlace = addrInp && addrInp.value && addrInp.value.trim().length > 0;

          if (hasPlace) {
            if (noPlaceCheckbox && noPlaceCheckbox.checked) {
              noPlaceCheckbox.checked = false;
            }
            if (addrInp) {
              addrInp.disabled = false;
              addrInp.classList.remove('is-disabled');
            }
            if (starsFg) {
              starsFg.classList.remove('is-hidden');
            }
          } else {
            if (source === 'checkbox-check') {
              if (addrInp) {
                addrInp.value = '';
                addrInp.disabled = true;
                addrInp.classList.add('is-disabled');
                delete addrInp.dataset.lat;
                delete addrInp.dataset.lng;
                delete addrInp.dataset.googlePlaceUsed;
              }
            } else if (source === 'checkbox-uncheck') {
              if (addrInp) {
                addrInp.disabled = false;
                addrInp.classList.remove('is-disabled');
              }
            }
            if (starsFg) {
              starsFg.classList.add('is-hidden');
            }
          }
        };

        if (noPlaceCheckbox) {
          noPlaceCheckbox.addEventListener('change', () => {
            if (noPlaceCheckbox.checked) {
              updateActivityPlaceState('checkbox-check');
            } else {
              updateActivityPlaceState('checkbox-uncheck');
              addrInp.focus();
            }
          });
        }

        addrInp.addEventListener('input', () => {
          updateActivityPlaceState('input');
        });

        addrInp.addEventListener('change', () => {
          updateActivityPlaceState('input');
        });

        addrInp.addEventListener('blur', () => {
          updateActivityPlaceState('input');
          setTimeout(closeAllPacContainers, 150);
        });

        // Run initial evaluation
        updateActivityPlaceState('init');
      }
    }

    // Add Comida manual address toggle & optional place logic
    if (type === 'comida') {
      const restInp = modalBody.querySelector('input[data-key="restaurante"]');
      const gBox = modalBody.querySelector('.field-group-box');
      const addrInp = gBox ? gBox.querySelector('input[data-key="direccion"]') : null;
      const phoneInp = gBox ? gBox.querySelector('input[data-key="phone"]') : null;
      const webInp = gBox ? gBox.querySelector('input[data-key="website"]') : null;
      const starsInp = gBox ? (gBox.querySelector('.star-rating input[data-key="stars"]') || gBox.querySelector('[data-key="stars"]')) : null;

      const addrFg = addrInp ? addrInp.closest('.form-group') : null;
      const phoneWebRow = (phoneInp && phoneInp.closest('.form-row')) || (phoneInp ? phoneInp.closest('.form-group') : null);
      const starsFg = starsInp ? (starsInp.closest('.form-group') || starsInp.closest('.star-rating')) : null;

      if (addrFg) addrFg.classList.add('comida-collapsible-field');
      if (phoneWebRow) phoneWebRow.classList.add('comida-collapsible-field');
      if (starsFg) starsFg.classList.add('comida-collapsible-field');

      let manualRevealed = (existData && (existData.direccion || existData.phone || existData.website)) ? true : false;

      if (restInp) {
        const restFg = restInp.closest('.form-group');

        // Fallback link: "¿No lo encuentras? Ingresa los datos manualmente"
        let fallbackCont = restFg ? restFg.querySelector('.comida-manual-address-wrap') : null;
        if (!fallbackCont && restFg) {
          fallbackCont = document.createElement('div');
          fallbackCont.className = 'comida-manual-address-wrap';
          fallbackCont.innerHTML = `
            <button type="button" class="btn-manual-address-toggle" id="comidaManualAddressBtn">
              <i class="fa-solid fa-location-dot"></i> ¿No lo encuentras? Ingresa los datos manualmente
            </button>
          `;
          restFg.appendChild(fallbackCont);

          const manualBtn = fallbackCont.querySelector('#comidaManualAddressBtn');
          if (manualBtn) {
            manualBtn.onclick = (e) => {
              e.preventDefault();
              manualRevealed = true;
              updateComidaPlaceState('manual-btn');
              if (addrInp) addrInp.focus();
            };
          }
        }

        // Checkbox: "Esta comida no tiene un lugar específico"
        let noPlaceWrap = restFg ? restFg.querySelector('.comida-no-place-wrap') : null;
        if (!noPlaceWrap && restFg) {
          noPlaceWrap = document.createElement('div');
          noPlaceWrap.className = 'comida-no-place-wrap';
          noPlaceWrap.innerHTML = `
            <label class="comida-no-place-label" for="comidaNoPlaceCheckbox">
              <input type="checkbox" id="comidaNoPlaceCheckbox" class="comida-no-place-checkbox">
              <span>Esta comida no tiene un lugar específico</span>
            </label>
          `;
          restFg.appendChild(noPlaceWrap);
        }

        const noPlaceCheckbox = restFg ? restFg.querySelector('#comidaNoPlaceCheckbox') : null;

        const updateComidaPlaceState = (source = 'auto') => {
          const isNoPlace = noPlaceCheckbox && noPlaceCheckbox.checked;
          const hasRest = restInp && restInp.value && restInp.value.trim().length > 0;
          const hasAddr = addrInp && addrInp.value && addrInp.value.trim().length > 0;

          if (isNoPlace) {
            if (source === 'checkbox-check') {
              restInp.value = '';
              restInp.disabled = true;
              restInp.classList.add('is-disabled');
              delete restInp.dataset.lat;
              delete restInp.dataset.lng;
              delete restInp.dataset.googlePlaceUsed;
            }
            if (fallbackCont) fallbackCont.style.display = 'none';
            if (addrFg) addrFg.classList.add('is-hidden');
            if (phoneWebRow) phoneWebRow.classList.add('is-hidden');
            if (starsFg) starsFg.classList.add('is-hidden');
          } else {
            restInp.disabled = false;
            restInp.classList.remove('is-disabled');

            if (hasRest) {
              if (fallbackCont) fallbackCont.style.display = 'none';
              if (addrFg) addrFg.classList.remove('is-hidden');
              if (phoneWebRow) phoneWebRow.classList.remove('is-hidden');
              if (starsFg) starsFg.classList.remove('is-hidden');
            } else if (manualRevealed || hasAddr) {
              if (fallbackCont) fallbackCont.style.display = 'none';
              if (addrFg) addrFg.classList.remove('is-hidden');
              if (phoneWebRow) phoneWebRow.classList.remove('is-hidden');
              if (starsFg) starsFg.classList.add('is-hidden');
            } else {
              if (fallbackCont) fallbackCont.style.display = 'flex';
              if (addrFg) addrFg.classList.add('is-hidden');
              if (phoneWebRow) phoneWebRow.classList.add('is-hidden');
              if (starsFg) starsFg.classList.add('is-hidden');
            }
          }
        };

        if (noPlaceCheckbox) {
          noPlaceCheckbox.addEventListener('change', () => {
            if (noPlaceCheckbox.checked) {
              updateComidaPlaceState('checkbox-check');
            } else {
              updateComidaPlaceState('checkbox-uncheck');
              restInp.focus();
            }
          });
        }

        restInp.addEventListener('input', () => {
          updateComidaPlaceState('input');
        });

        restInp.addEventListener('change', () => {
          updateComidaPlaceState('input');
        });

        restInp.addEventListener('blur', () => {
          updateComidaPlaceState('input');
          setTimeout(closeAllPacContainers, 150);
        });

        // Setup address autocomplete for Dirección in Comida when revealed
        if (addrInp && window.google && window.google.maps && window.google.maps.places) {
          const autocompleteComidaDireccion = new window.google.maps.places.Autocomplete(addrInp, {});
          addrInp.addEventListener('blur', () => setTimeout(closeAllPacContainers, 150));
          autocompleteComidaDireccion.addListener('place_changed', () => {
            closeAllPacContainers();
            setTimeout(closeAllPacContainers, 50);
            setTimeout(closeAllPacContainers, 150);
            const place = autocompleteComidaDireccion.getPlace();
            if (!place) return;
            if (!checkGooglePlacesLimit()) {
              addrInp.value = '';
              return;
            }
            addrInp.dataset.googlePlaceUsed = 'true';
            if (place.formatted_address) {
              addrInp.value = place.formatted_address;
            } else if (place.name) {
              addrInp.value = place.name;
            }
            if (place.geometry && place.geometry.location) {
              addrInp.dataset.lat = place.geometry.location.lat();
              addrInp.dataset.lng = place.geometry.location.lng();
            }
            addrInp.blur();
            closeAllPacContainers();
            setTimeout(closeAllPacContainers, 80);
            updateComidaPlaceState('addr-place');
          });
        }

        // Run initial evaluation
        updateComidaPlaceState('init');
      }
    }
  }, 220);
}
function handleItemPhotoUpload(e, targetInp) {
  const files = Array.from(e.target.files || []);
  if (!files.length) return;

  let currentUrls = targetInp.value ? targetInp.value.split(',').map(s => s.trim()).filter(Boolean) : [];
  const available = 3 - currentUrls.length;
  if (available <= 0) {
    showToast('⚠️', 'Máximo 3 fotos alcanzado. Elimina una para añadir otra.');
    return;
  }

  const toUpload = files.slice(0, available);
  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  const tripId = window.tripId;

  // Visual loading state
  const previewCont = targetInp.parentNode.querySelector('.photo-preview-container');
  if (previewCont) {
    previewCont.innerHTML = `
      <div class="photo-loading-spinner" style="border: 1.5px dashed var(--border); border-radius: 10px; padding: 20px; text-align: center; color: var(--text-muted); font-size: 13px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; background: var(--surface2);">
        <div class="spinner" style="width: 24px; height: 24px; border: 3px solid rgba(20, 184, 166, 0.1); border-top-color: #14b8a6; border-radius: 50%; animation: spin 1s linear infinite;"></div>
        <span>Subiendo foto(s)...</span>
      </div>
    `;
  }

  const uploadPromises = toUpload.map(f => {
    if (f.size > 5 * 1024 * 1024) {
      showToast('⚠️', `${f.name} supera los 5MB`);
      return Promise.resolve(null);
    }
    const formData = new FormData();
    formData.append('file', f);
    return fetch(`/trips/${tripId}/upload-attachment`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
      body: formData
    }).then(r => r.json()).catch(() => null);
  });

  Promise.all(uploadPromises).then(results => {
    results.forEach(res => {
      if (res && res.success && res.url) {
        currentUrls.push(res.url);
      }
    });
    targetInp.value = currentUrls.slice(0, 3).join(',');
    targetInp.dispatchEvent(new Event('input', { bubbles: true }));
    showToast('✅', `Fotos actualizadas (${targetInp.value.split(',').filter(Boolean).length}/3)`);
  });
}
function buildField(field, data) {
  const fg = document.createElement('div'); fg.className = 'form-group';
  if (field.hidden) {
    fg.style.display = 'none';
  }
  const lbl = document.createElement('label'); lbl.className = 'form-label';
  lbl.textContent = field.l;
  fg.appendChild(lbl);

  let val = data[field.k] || '';
  let dayDate = (typeof currentDay === 'number' && dayDates[currentDay]) ? dayDates[currentDay] : '';
  if (!dayDate && typeof currentDay === 'number') {
    const pi = document.getElementById('portadaFechaInicio');
    if (pi && pi.value) {
      dayDate = addDaysToDate(pi.value, currentDay);
    }
  }

  if (field.t === 'datetime-local') {
    if (val) {
      val = formatToDatetimeLocal(val, '', '09:00', dayDate);
      if (!val && dayDate) {
        val = dayDate + 'T09:00';
      }
    } else if (dayDate) {
      if (field.k === 'llegada' || field.k === 'checkout') {
        val = '';
      } else if (field.k === 'checkin') {
        val = dayDate + 'T15:00';
      } else {
        val = dayDate + 'T00:00';
      }
    }
  } else if (!val && dayDate && field.t === 'date') {
    val = dayDate;
  }

  if (field.t === 'stars') {
    const sr = document.createElement('div');
    sr.className = 'star-rating';
    const init = parseFloat(val) || 0;

    // Hidden input to store value for saveElement
    const hid = document.createElement('input');
    hid.type = 'hidden';
    hid.dataset.key = field.k;
    hid.value = init || '';
    sr.appendChild(hid);

    const hidReviews = document.createElement('input');
    hidReviews.type = 'hidden';
    hidReviews.dataset.key = 'user_ratings_total';
    hidReviews.value = data.user_ratings_total || '';
    sr.appendChild(hidReviews);

    const badge = document.createElement('div');
    badge.className = 'modal-google-rating-text';
    sr.appendChild(badge);

    updateGoogleRatingInModal(sr, init, data.user_ratings_total);
    fg.appendChild(sr);
  }
  else if (field.t === 'separator-chips') {
    const wrap = document.createElement('div');
    wrap.className = 'sep-chips-container';

    const chips = [
      { label: '🌅 Mañana', val: '🌅 Mañana' },
      { label: '☀️ Tarde', val: '☀️ Tarde' },
      { label: '🌙 Noche', val: '🌙 Noche' },
      { label: '➖ Solo línea', val: '' }
    ];

    chips.forEach(chip => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'sep-chip-btn' + (data['etiqueta'] === chip.val || (chip.val === '' && !data['etiqueta']) ? ' active' : '');
      btn.dataset.val = chip.val;
      btn.textContent = chip.label;
      btn.onclick = () => {
        const inp = modalBody.querySelector('input[data-key="etiqueta"]');
        if (inp) {
          inp.value = chip.val;
          inp.dispatchEvent(new Event('input', { bubbles: true }));
          inp.focus();
        }
        wrap.querySelectorAll('.sep-chip-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
      };
      wrap.appendChild(btn);
    });
    fg.appendChild(wrap);
  }
  else if (field.t === 'icon-selector') {
    const wrap = document.createElement('div');
    wrap.className = 'icon-selector-grid';

    const hiddenInp = document.createElement('input');
    hiddenInp.type = 'hidden';
    hiddenInp.dataset.key = field.k;
    hiddenInp.value = val || (field.opts && field.opts[0] ? field.opts[0].icon : '💡');
    fg.appendChild(hiddenInp);

    (field.opts || []).forEach(opt => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'icon-chip-card' + (hiddenInp.value === opt.icon ? ' active' : '');
      btn.innerHTML = `<span class="icc-emoji">${opt.icon}</span> <span class="icc-label">${opt.label}</span>`;
      btn.onclick = () => {
        hiddenInp.value = opt.icon;
        wrap.querySelectorAll('.icon-chip-card').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        // Preseleccionar automáticamente el color sugerido si existe
        if (opt.color) {
          const colorRow = modalBody.querySelector('.color-row');
          if (colorRow) {
            const targetSwatch = colorRow.querySelector(`.color-swatch[data-color="${opt.color}"]`);
            if (targetSwatch) {
              colorRow.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('selected'));
              targetSwatch.classList.add('selected');
            }
          }
        }
      };
      wrap.appendChild(btn);
    });
    fg.appendChild(wrap);
  }
  else if (field.t === 'image-picker') {
    const wrap = document.createElement('div');
    wrap.className = 'gallery-picker-box img-picker-box';

    let photo = val || '';
    if (typeof photo === 'string' && photo.startsWith('[')) {
      try {
        const arr = JSON.parse(photo);
        photo = Array.isArray(arr) ? (arr[0] || '') : '';
      } catch {}
    } else if (Array.isArray(photo)) {
      photo = photo[0] || '';
    }

    const hiddenInp = document.createElement('input');
    hiddenInp.type = 'hidden';
    hiddenInp.dataset.key = field.k;
    hiddenInp.value = photo;
    wrap.appendChild(hiddenInp);

    const fileInp = document.createElement('input');
    fileInp.type = 'file';
    fileInp.accept = 'image/jpeg,image/png,image/webp,image/gif';
    fileInp.style.display = 'none';
    wrap.appendChild(fileInp);

    const countLabel = document.createElement('div');
    countLabel.style.cssText = 'font-size:12px;font-weight:600;color:var(--text-muted);margin-bottom:8px;display:flex;justify-content:space-between;align-items:center;';

    const grid = document.createElement('div');
    grid.className = 'gallery-picker-grid';
    grid.style.cssText = 'display:grid;grid-template-columns:repeat(auto-fill, minmax(130px, 1fr));gap:8px;margin-bottom:12px;';

    const renderPhotos = () => {
      grid.innerHTML = '';
      if (photo) {
        const item = document.createElement('div');
        item.style.cssText = 'position:relative;width:100%;height:100px;border-radius:8px;overflow:hidden;border:1px solid var(--border);background:#000;';
        item.innerHTML = `
          <img src="${fixUrl(photo)}" style="width:100%;height:100%;object-fit:cover;display:block;">
          <button type="button" style="position:absolute;top:4px;right:4px;background:rgba(239,68,68,0.9);color:#fff;border:none;border-radius:50%;width:24px;height:24px;display:flex;align-items:center;justify-content:center;font-size:11px;cursor:pointer;" title="Eliminar foto"><i class="fa-solid fa-trash"></i></button>
        `;
        item.querySelector('button').onclick = () => {
          photo = '';
          hiddenInp.value = '';
          hiddenInp.dispatchEvent(new Event('input', { bubbles: true }));
          hiddenInp.dispatchEvent(new Event('change', { bubbles: true }));
          renderPhotos();
          updateButtons();
        };
        grid.appendChild(item);
      } else {
        grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:18px 8px;color:var(--text-muted);font-size:12px;border:1.5px dashed var(--border);border-radius:10px;"><i class="fa-regular fa-image" style="font-size:26px;color:var(--text-dim);margin-bottom:6px;display:block;"></i> Elige una foto de Unsplash o sube un archivo desde tu dispositivo</div>';
      }
    };

    const actionsRow = document.createElement('div');
    actionsRow.className = 'img-picker-actions';

    const unsplashBtn = document.createElement('button');
    unsplashBtn.type = 'button';
    unsplashBtn.className = 'img-picker-btn img-picker-unsplash';
    unsplashBtn.innerHTML = '<i class="fa-brands fa-unsplash" style="font-size:15px;"></i> Buscar en Unsplash';
    unsplashBtn.onclick = () => {
      openUnsplash('image_photo', hiddenInp);
    };

    const uploadBtn = document.createElement('button');
    uploadBtn.type = 'button';
    uploadBtn.className = 'img-picker-btn img-picker-upload';
    uploadBtn.innerHTML = '<i class="fa-solid fa-cloud-arrow-up" style="font-size:15px;"></i> Cargar archivo';
    uploadBtn.onclick = () => {
      fileInp.click();
    };

    const updateButtons = () => {
      countLabel.innerHTML = `<span>Foto seleccionada</span><span style="color:${photo ? 'var(--primary-blue)' : 'var(--text-muted)'}">${photo ? '1 / 1' : '0 / 1'}</span>`;
      if (photo) {
        unsplashBtn.innerHTML = '<i class="fa-brands fa-unsplash" style="font-size:15px;"></i> Cambiar desde Unsplash';
        uploadBtn.innerHTML = '<i class="fa-solid fa-cloud-arrow-up" style="font-size:15px;"></i> Cambiar desde archivo';
      } else {
        unsplashBtn.innerHTML = '<i class="fa-brands fa-unsplash" style="font-size:15px;"></i> Buscar en Unsplash';
        uploadBtn.innerHTML = '<i class="fa-solid fa-cloud-arrow-up" style="font-size:15px;"></i> Cargar archivo';
      }
    };

    fileInp.onchange = async (e) => {
      const files = Array.from(e.target.files || []);
      if (!files.length) return;
      const f = files[0];
      if (f.size > 5 * 1024 * 1024) {
        showToast('⚠️', `${f.name} supera los 5 MB`);
        return;
      }
      const formData = new FormData();
      formData.append('file', f);
      const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

      uploadBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Subiendo...';
      uploadBtn.disabled = true;

      try {
        const res = await fetch(`/trips/${window.tripId}/upload-attachment`, {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
          body: formData
        }).then(r => {
          if (r.status === 403) {
            return r.json().then(data => {
              if (data.error_code === 'LIMIT_REACHED') {
                if (typeof window.openProUpgradeInlineModal === 'function') {
                  window.openProUpgradeInlineModal('Límite de Archivos Adjuntos Alcanzado', 'Has alcanzado el límite de 5 archivos adjuntos por itinerario de tu Plan Básico. Actualiza a Viajero Pro para adjuntos y documentos ilimitados.');
                } else if (typeof openUpgradeModal === 'function') {
                  openUpgradeModal();
                }
              }
              showToast('⚠️', data.message || 'Límite alcanzado');
              return null;
            });
          }
          return r.json();
        });

        if (res && res.success && res.url) {
          photo = res.url;
          hiddenInp.value = res.url;
          renderPhotos();
          updateButtons();
          showToast('✅', 'Imagen subida correctamente');
        } else if (res && !res.success) {
          showToast('⚠️', res.message || 'Error al subir');
        }
      } catch (err) {
        console.error(err);
        showToast('⚠️', 'Error de conexión');
      } finally {
        uploadBtn.disabled = false;
        fileInp.value = '';
        updateButtons();
      }
    };

    hiddenInp.addEventListener('input', () => {
      let raw = hiddenInp.value || '';
      if (typeof raw === 'string' && raw.startsWith('[')) {
        try {
          const arr = JSON.parse(raw);
          photo = Array.isArray(arr) ? (arr[0] || '') : '';
        } catch {
          photo = raw;
        }
      } else {
        photo = raw;
      }
      renderPhotos();
      updateButtons();
    });

    renderPhotos();
    updateButtons();

    wrap.appendChild(countLabel);
    wrap.appendChild(grid);
    actionsRow.appendChild(unsplashBtn);
    actionsRow.appendChild(uploadBtn);
    wrap.appendChild(actionsRow);
    fg.appendChild(wrap);
  }
  else if (field.t === 'gif-picker') {
    const wrap = document.createElement('div');
    wrap.className = 'img-picker-box' + (val ? ' has-preview' : '');

    const hiddenInp = document.createElement('input');
    hiddenInp.type = 'hidden';
    hiddenInp.dataset.key = field.k;
    hiddenInp.value = val || '';
    wrap.appendChild(hiddenInp);

    const previewWrap = document.createElement('div');
    previewWrap.className = 'img-picker-preview-wrap';
    previewWrap.style.display = val ? 'flex' : 'none';

    const previewImg = document.createElement('img');
    previewImg.className = 'img-picker-preview-img';
    previewImg.style.objectFit = 'contain';
    previewImg.src = val ? fixUrl(val) : '';
    previewWrap.appendChild(previewImg);

    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'img-picker-remove-btn';
    removeBtn.innerHTML = '<i class="fa-solid fa-trash"></i> Cambiar GIF';
    removeBtn.onclick = () => {
      hiddenInp.value = '';
      previewImg.src = '';
      wrap.classList.remove('has-preview');
      previewWrap.style.display = 'none';
      actionsRow.style.display = 'flex';
    };
    previewWrap.appendChild(removeBtn);

    const actionsRow = document.createElement('div');
    actionsRow.className = 'img-picker-actions';
    actionsRow.style.display = val ? 'none' : 'flex';
    actionsRow.style.justifyContent = 'center';
    actionsRow.style.alignItems = 'center';
    actionsRow.style.padding = '12px 0';

    const giphyBtn = document.createElement('button');
    giphyBtn.type = 'button';
    giphyBtn.className = 'img-picker-btn img-picker-unsplash';
    giphyBtn.style.margin = '0 auto';
    giphyBtn.innerHTML = '<i class="fa-solid fa-bolt" style="font-size:15px;color:#eab308;"></i> Buscar en Giphy';
    giphyBtn.onclick = () => {
      openGiphy('item_gif', hiddenInp);
    };

    hiddenInp.addEventListener('input', () => {
      if (hiddenInp.value) {
        previewImg.src = fixUrl(hiddenInp.value);
        wrap.classList.add('has-preview');
        previewWrap.style.display = 'flex';
        actionsRow.style.display = 'none';
      }
    });

    actionsRow.appendChild(giphyBtn);

    wrap.appendChild(previewWrap);
    wrap.appendChild(actionsRow);
    fg.appendChild(wrap);
  }
  else if (field.t === 'gallery-picker') {
    const wrap = document.createElement('div');
    wrap.className = 'gallery-picker-box';

    let photos = [];
    try {
      if (Array.isArray(val)) photos = val;
      else if (val) photos = typeof val === 'string' && val.startsWith('[') ? JSON.parse(val) : val.split(',').map(s => s.trim()).filter(Boolean);
    } catch {
      photos = val ? val.split(',').map(s => s.trim()).filter(Boolean) : [];
    }

    const hiddenInp = document.createElement('input');
    hiddenInp.type = 'hidden';
    hiddenInp.dataset.key = field.k;
    hiddenInp.value = JSON.stringify(photos);
    wrap.appendChild(hiddenInp);

    const fileInp = document.createElement('input');
    fileInp.type = 'file';
    fileInp.accept = 'image/jpeg,image/png,image/webp,image/gif';
    fileInp.multiple = true;
    fileInp.style.display = 'none';
    wrap.appendChild(fileInp);

    const countLabel = document.createElement('div');
    countLabel.style.cssText = 'font-size:12px;font-weight:600;color:var(--text-muted);margin-bottom:8px;display:flex;justify-content:space-between;align-items:center;';

    const grid = document.createElement('div');
    grid.className = 'gallery-picker-grid';
    grid.style.cssText = 'display:grid;grid-template-columns:repeat(auto-fill, minmax(85px, 1fr));gap:8px;margin-bottom:12px;';

    const renderPhotos = () => {
      grid.innerHTML = '';
      photos.forEach((url, pIdx) => {
        const item = document.createElement('div');
        item.style.cssText = 'position:relative;width:100%;height:80px;border-radius:8px;overflow:hidden;border:1px solid var(--border);background:#000;';
        item.innerHTML = `
          <img src="${fixUrl(url)}" style="width:100%;height:100%;object-fit:cover;display:block;">
          <span style="position:absolute;bottom:4px;left:4px;background:rgba(0,0,0,0.6);color:#fff;font-size:10px;font-weight:700;padding:1px 5px;border-radius:4px;">${pIdx + 1}</span>
          <button type="button" style="position:absolute;top:4px;right:4px;background:rgba(239,68,68,0.9);color:#fff;border:none;border-radius:50%;width:22px;height:22px;display:flex;align-items:center;justify-content:center;font-size:10px;cursor:pointer;" title="Eliminar foto"><i class="fa-solid fa-trash"></i></button>
        `;
        item.querySelector('button').onclick = () => {
          photos.splice(pIdx, 1);
          hiddenInp.value = JSON.stringify(photos);
          renderPhotos();
          updateButtons();
        };
        grid.appendChild(item);
      });
      if (photos.length === 0) {
        grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:18px 8px;color:var(--text-muted);font-size:12px;border:1.5px dashed var(--border);border-radius:10px;"><i class="fa-solid fa-images" style="font-size:24px;color:var(--text-dim);margin-bottom:6px;display:block;"></i> Añade hasta 5 fotos para crear tu carrusel</div>';
      }
    };

    const actionsRow = document.createElement('div');
    actionsRow.className = 'img-picker-actions';

    const unsplashBtn = document.createElement('button');
    unsplashBtn.type = 'button';
    unsplashBtn.className = 'img-picker-btn img-picker-unsplash';
    unsplashBtn.innerHTML = '<i class="fa-brands fa-unsplash" style="font-size:15px;"></i> Buscar en Unsplash';
    unsplashBtn.onclick = () => {
      if (photos.length >= 5) {
        showToast('⚠️', 'Ya has añadido el máximo de 5 fotos');
        return;
      }
      openUnsplash('gallery_photo', hiddenInp);
    };

    const uploadBtn = document.createElement('button');
    uploadBtn.type = 'button';
    uploadBtn.className = 'img-picker-btn img-picker-upload';
    uploadBtn.innerHTML = '<i class="fa-solid fa-cloud-arrow-up" style="font-size:15px;"></i> Cargar fotos';
    uploadBtn.onclick = () => {
      if (photos.length >= 5) {
        showToast('⚠️', 'Ya has añadido el máximo de 5 fotos');
        return;
      }
      fileInp.click();
    };

    const updateButtons = () => {
      countLabel.innerHTML = `<span>Fotos del carrusel</span><span style="color:${photos.length >= 5 ? '#ef4444' : 'var(--primary-blue)'}">${photos.length} / 5</span>`;
      if (photos.length >= 5) {
        unsplashBtn.disabled = true;
        uploadBtn.disabled = true;
        unsplashBtn.style.opacity = '0.5';
        uploadBtn.style.opacity = '0.5';
      } else {
        unsplashBtn.disabled = false;
        uploadBtn.disabled = false;
        unsplashBtn.style.opacity = '1';
        uploadBtn.style.opacity = '1';
      }
    };

    fileInp.onchange = async (e) => {
      const files = Array.from(e.target.files || []);
      if (!files.length) return;
      const available = 5 - photos.length;
      if (available <= 0) {
        showToast('⚠️', 'Máximo 5 fotos alcanzado');
        return;
      }
      const toUpload = files.slice(0, available);
      const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

      uploadBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Subiendo...';
      uploadBtn.disabled = true;

      for (const f of toUpload) {
        if (f.size > 5 * 1024 * 1024) {
          showToast('⚠️', `${f.name} supera los 5 MB`);
          continue;
        }
        const formData = new FormData();
        formData.append('file', f);
        try {
          const res = await fetch(`/trips/${window.tripId}/upload-attachment`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: formData
          }).then(r => {
            if (r.status === 403) {
              return r.json().then(data => {
                if (data.error_code === 'LIMIT_REACHED') {
                  if (typeof window.openProUpgradeInlineModal === 'function') {
                    window.openProUpgradeInlineModal('Límite de Archivos Adjuntos Alcanzado', 'Has alcanzado el límite de 5 archivos adjuntos por itinerario de tu Plan Básico. Actualiza a Viajero Pro para adjuntos y documentos ilimitados.');
                  } else if (typeof openUpgradeModal === 'function') {
                    openUpgradeModal();
                  }
                }
                showToast('⚠️', data.message || 'Límite alcanzado');
                return null;
              });
            }
            return r.json();
          });
          if (res.success && res.url) {
            photos.push(res.url);
          }
        } catch (err) {
          console.error(err);
        }
      }

      uploadBtn.innerHTML = '<i class="fa-solid fa-cloud-arrow-up" style="font-size:15px;"></i> Cargar fotos';
      uploadBtn.disabled = false;
      fileInp.value = '';
      hiddenInp.value = JSON.stringify(photos);
      renderPhotos();
      updateButtons();
      showToast('✅', `Fotos actualizadas (${photos.length}/5)`);
    };

    hiddenInp.addEventListener('input', () => {
      try {
        if (Array.isArray(hiddenInp.value)) photos = hiddenInp.value;
        else photos = JSON.parse(hiddenInp.value || '[]');
      } catch {
        photos = hiddenInp.value ? hiddenInp.value.split(',').filter(Boolean) : [];
      }
      renderPhotos();
      updateButtons();
    });

    actionsRow.appendChild(unsplashBtn);
    actionsRow.appendChild(uploadBtn);

    wrap.appendChild(countLabel);
    wrap.appendChild(grid);
    wrap.appendChild(actionsRow);
    fg.appendChild(wrap);
    renderPhotos();
    updateButtons();
  }
  else if (field.t === 'textarea') {
    const ta = document.createElement('textarea');
    ta.className = 'form-textarea';
    ta.placeholder = field.ph || '';
    ta.value = val;
    ta.dataset.key = field.k;
    if (field.rows) {
      ta.rows = field.rows;
      ta.style.minHeight = '72px';
    }
    fg.appendChild(ta);
  }
  else if (field.t === 'richtext') {
    const wrap = document.createElement('div'); wrap.className = 'rte-container';
    wrap.innerHTML = `
      <div class="rte-toolbar">
        <button type="button" class="rte-btn" onclick="execRTE('bold')" title="Negrita"><i class="fa-solid fa-bold"></i></button>
        <button type="button" class="rte-btn" onclick="execRTE('italic')" title="Cursiva"><i class="fa-solid fa-italic"></i></button>
        <button type="button" class="rte-btn" onclick="execRTE('insertUnorderedList')" title="Viñetas"><i class="fa-solid fa-list-ul"></i></button>
        <button type="button" class="rte-btn" onclick="execRTE('createLink')" title="Enlace"><i class="fa-solid fa-link"></i></button>
        <button type="button" class="rte-btn" onclick="execRTE('unlink')" title="Quitar enlace"><i class="fa-solid fa-link-slash"></i></button>
      </div>
      <div class="rte-editor" contenteditable="true" data-key="${field.k}" data-placeholder="${field.ph || ''}">${val || ''}</div>
    `;
    fg.appendChild(wrap);
  }
  else if (field.t === 'select') { const sel = document.createElement('select'); sel.className = 'form-select'; sel.dataset.key = field.k; if (field.ph) { const op = document.createElement('option'); op.value = ''; op.textContent = field.ph; op.selected = !val; sel.appendChild(op); } const currentVal = val || (field.k === 'tamano' ? 'Mediano' : (field.opts && field.opts[0] ? field.opts[0] : '')); field.opts.forEach(opt => { const o = document.createElement('option'); o.value = opt; o.textContent = opt; if (opt === currentVal) o.selected = true; sel.appendChild(o) }); fg.appendChild(sel) }
  else if (field.t === 'color-picker') { const row = document.createElement('div'); row.className = 'color-row'; const selectedColor = data[field.k] || (field.opts ? field.opts[0] : '#f59e0b'); field.opts.forEach((color, ci) => { const sw = document.createElement('div'); sw.className = 'color-swatch' + (selectedColor === color ? ' selected' : ''); sw.style.background = color; sw.dataset.color = color; sw.dataset.key = field.k; sw.addEventListener('click', () => { row.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('selected')); sw.classList.add('selected') }); row.appendChild(sw) }); fg.appendChild(row) }
  else if (field.t === 'file-upload') {
    const wrap = document.createElement('div');
    wrap.style = 'border:1.5px dashed var(--border);border-radius:10px;padding:12px;display:flex;align-items:center;gap:10px;background:var(--surface)';

    const hiddenUrl = document.createElement('input'); hiddenUrl.type = 'hidden'; hiddenUrl.dataset.key = field.k + '_url'; hiddenUrl.value = data[field.k + '_url'] || '';
    const hiddenName = document.createElement('input'); hiddenName.type = 'hidden'; hiddenName.dataset.key = field.k + '_name'; hiddenName.value = data[field.k + '_name'] || '';

    const infoCol = document.createElement('div'); infoCol.style = 'flex:1;overflow:hidden;';
    const statusText = document.createElement('div'); statusText.style = 'font-size:12.5px;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;';

    if (data[field.k + '_url']) {
      statusText.innerHTML = `<i class="fa-solid fa-paperclip"></i> <a href="${data[field.k + '_url']}" target="_blank" style="color:#0ea5d8;text-decoration:none;">${data[field.k + '_name'] || 'Archivo subido'}</a>`;
    } else {
      statusText.innerHTML = '<i class="fa-solid fa-cloud-arrow-up"></i> PDF, Imagen o Word · <span style="font-weight:700">máx. 5 MB</span>';
    }
    infoCol.appendChild(statusText);

    const fileInp = document.createElement('input'); fileInp.type = 'file'; fileInp.accept = '.pdf,.jpg,.jpeg,.png,.webp,.doc,.docx,.txt'; fileInp.style.display = 'none';
    const btn = document.createElement('button'); btn.type = 'button'; btn.className = 'btn-secondary'; btn.style = 'font-size:12px;padding:6px 12px;border-radius:6px;'; btn.textContent = 'Explorar';

    // Removal button
    const removeBtn = document.createElement('button'); removeBtn.type = 'button'; removeBtn.style = 'background:none;border:none;color:#ef4444;cursor:pointer;padding:4px;display:' + (data[field.k + '_url'] ? 'block' : 'none'); removeBtn.innerHTML = '<i class="fa-solid fa-trash"></i>';
    removeBtn.onclick = () => {
      const prevUrl = hiddenUrl.value;
      hiddenUrl.value = ''; hiddenName.value = ''; statusText.innerHTML = '<i class="fa-solid fa-cloud-arrow-up"></i> Ningún archivo seleccionado'; removeBtn.style.display = 'none'; fileInp.value = '';

      // Server side deletion
      const match = prevUrl.match(/\/documents\/(\d+)\/download/);
      const docId = match ? match[1] : null;
      if (docId) {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        fetch(`/documents/${docId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf } }).catch(e => console.error(e));
      }
    };

    btn.onclick = () => fileInp.click();
    fileInp.onchange = (e) => {
      const f = e.target.files[0]; if (!f) return;
      if (f.size > 5 * 1024 * 1024) { showToast('⚠️', 'El archivo no puede superar 5MB'); return; }

      const formData = new FormData(); formData.append('file', f);
      const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

      btn.textContent = 'Subiendo...'; btn.disabled = true;
      fetch(`/trips/${window.tripId}/upload-attachment`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        body: formData
      })
        .then(res => {
          if (res.status === 403) {
            return res.json().then(data => {
              if (data.error_code === 'LIMIT_REACHED') {
                if (typeof window.openProUpgradeInlineModal === 'function') {
                  window.openProUpgradeInlineModal('Límite de Archivos Adjuntos Alcanzado', 'Has alcanzado el límite de 5 archivos adjuntos por itinerario de tu Plan Básico. Actualiza a Viajero Pro para adjuntos y documentos ilimitados.');
                } else if (typeof openUpgradeModal === 'function') {
                  openUpgradeModal();
                }
              }
              throw new Error(data.message || 'Has alcanzado el límite de 5 archivos adjuntos.');
            });
          }
          return res.json();
        })
        .then(res => {
          btn.textContent = 'Explorar'; btn.disabled = false;
          if (res.success) {
            hiddenUrl.value = res.url; hiddenName.value = res.original_name;
            statusText.innerHTML = `<i class="fa-solid fa-paperclip"></i> <a href="${res.url}" target="_blank" style="color:#0ea5d8;text-decoration:none;">${res.original_name}</a>`;
            removeBtn.style.display = 'block';
            showToast('✅', 'Archivo adjunto');
          } else {
            statusText.innerHTML = '<i class="fa-solid fa-cloud-arrow-up"></i> PDF, Imagen o Word · <span style="font-weight:700">máx. 5 MB</span>';
            showToast('⚠️', res.message || 'Error al subir');
          }
        })
        .catch(() => { btn.textContent = 'Explorar'; btn.disabled = false; showToast('⚠️', 'Error de conexión'); });
    };

    wrap.appendChild(infoCol);
    wrap.appendChild(removeBtn);
    wrap.appendChild(btn);
    wrap.appendChild(fileInp);
    wrap.appendChild(hiddenUrl);
    wrap.appendChild(hiddenName);
    fg.appendChild(wrap);
  }
  else if (field.t === 'multi-file-upload') {
    const wrap = document.createElement('div');
    wrap.className = 'multi-file-upload-container';
    wrap.style = 'border:1.5px dashed var(--border);border-radius:10px;padding:15px;background:var(--surface);';

    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.dataset.key = field.k;
    hiddenInput.value = val || '[]';
    wrap.appendChild(hiddenInput);

    const listCont = document.createElement('div');
    listCont.className = 'uploaded-files-list';
    listCont.style = 'margin-bottom:12px;display:flex;flex-direction:column;gap:8px;';
    wrap.appendChild(listCont);

    const renderFileList = () => {
      const files = JSON.parse(hiddenInput.value);
      listCont.innerHTML = '';
      if (files.length === 0) {
        listCont.innerHTML = '<div style="font-size:12px;color:var(--text-dim);text-align:center;padding:10px;">No hay archivos seleccionados</div>';
      }
      files.forEach((file, idx) => {
        const item = document.createElement('div');
        item.style = 'display:flex;align-items:center;gap:10px;padding:8px 12px;background:#fff;border:1px solid var(--border);border-radius:8px;font-size:13px;';
        item.innerHTML = `
          <i class="fa-solid fa-file" style="color:var(--accent);"></i>
          <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${file.original_name || file.name}</span>
          <button type="button" style="background:none;border:none;color:#ef4444;cursor:pointer;padding:4px;"><i class="fa-solid fa-trash"></i></button>
        `;
        item.querySelector('button').onclick = () => {
          files.splice(idx, 1);
          hiddenInput.value = JSON.stringify(files);
          renderFileList();
          // Optionally delete from server if it has an ID
          const docId = file.id;
          if (docId) {
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            fetch(`/documents/${docId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf } }).catch(e => console.error(e));
          }
        };
        listCont.appendChild(item);
      });
    };

    const fileInp = document.createElement('input');
    fileInp.type = 'file';
    fileInp.multiple = true;
    fileInp.accept = '.pdf,.jpg,.jpeg,.png,.webp,.doc,.docx,.txt';
    fileInp.style.display = 'none';

    const addBtn = document.createElement('button');
    addBtn.type = 'button';
    addBtn.className = 'btn-secondary';
    addBtn.style = 'width:100%;display:flex;align-items:center;justify-content:center;gap:8px;font-size:13px;padding:10px;border-radius:8px;';
    addBtn.innerHTML = '<i class="fa-solid fa-plus"></i> Añadir archivos';
    addBtn.onclick = () => fileInp.click();

    fileInp.onchange = (e) => {
      const filesToUpload = Array.from(e.target.files);
      if (filesToUpload.length === 0) return;

      const currentFiles = JSON.parse(hiddenInput.value);
      if (currentFiles.length + filesToUpload.length > 5) {
        showToast('⚠️', 'Máximo 5 archivos por sección');
        return;
      }

      addBtn.disabled = true;
      addBtn.innerHTML = '<div class="spinner" style="width:14px;height:14px;border-width:2px;"></div> Subiendo...';

      const uploadPromises = filesToUpload.map(f => {
        if (f.size > 5 * 1024 * 1024) return Promise.resolve({ error: `El archivo ${f.name} supera los 5MB` });
        const formData = new FormData();
        formData.append('file', f);
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        return fetch(`/trips/${window.tripId}/upload-attachment`, {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
          body: formData
        }).then(res => {
          if (res.status === 403) {
            return res.json().then(data => {
              if (data.error_code === 'LIMIT_REACHED') {
                if (typeof window.openProUpgradeInlineModal === 'function') {
                  window.openProUpgradeInlineModal('Límite de Archivos Adjuntos Alcanzado', 'Has alcanzado el límite de 5 archivos adjuntos por itinerario de tu Plan Básico. Actualiza a Viajero Pro para adjuntos y documentos ilimitados.');
                } else if (typeof openUpgradeModal === 'function') {
                  openUpgradeModal();
                }
              }
              return { error: data.message || 'Límite de 5 archivos adjuntos alcanzado.' };
            });
          }
          return res.json();
        });
      });

      Promise.all(uploadPromises).then(results => {
        const currentFiles = JSON.parse(hiddenInput.value);
        results.forEach(res => {
          if (res.success) {
            currentFiles.push({
              id: res.url.match(/\/documents\/(\d+)\/download/)?.[1],
              url: res.url,
              original_name: res.original_name
            });
          } else if (res.error) {
            showToast('⚠️', res.error);
          }
        });
        hiddenInput.value = JSON.stringify(currentFiles);
        renderFileList();
        addBtn.disabled = false;
        addBtn.innerHTML = '<i class="fa-solid fa-plus"></i> Añadir archivos';
        showToast('✅', 'Archivos actualizados');
      }).catch(err => {
        console.error(err);
        addBtn.disabled = false;
        addBtn.innerHTML = '<i class="fa-solid fa-plus"></i> Añadir archivos';
        showToast('⚠️', 'Error al subir archivos');
      });
    };

    wrap.appendChild(addBtn);
    wrap.appendChild(fileInp);
    fg.appendChild(wrap);
    renderFileList();
  }
  else {
    let inp;
    if (field.k === 'photo_url') {
      inp = document.createElement('input');
      inp.type = 'text';
      inp.style.display = 'none';
      inp.value = val;
      inp.dataset.key = field.k;
      fg.appendChild(inp);

      const previewCont = document.createElement('div');
      previewCont.className = 'photo-preview-container';
      previewCont.style = 'margin-top: 8px; margin-bottom: 8px;';
      fg.appendChild(previewCont);

      const renderThumbs = () => {
        previewCont.innerHTML = '';
        const currentVal = inp.value || '';
        const urls = currentVal.split(',').filter(u => u.trim());

        if (urls.length === 0) {
          previewCont.innerHTML = `
            <div class="photo-placeholder" style="border: 1.5px dashed var(--border); border-radius: 10px; padding: 20px; text-align: center; color: var(--text-muted); font-size: 13px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; background: var(--surface2);">
              <i class="fa-regular fa-image" style="font-size: 24px; opacity: 0.6;"></i>
              <span>Sin fotos seleccionadas (máximo 3)</span>
            </div>
          `;
        } else {
          const list = document.createElement('div');
          list.style = 'display: flex; flex-wrap: wrap; gap: 10px;';

          const countHeader = document.createElement('div');
          countHeader.style = 'width: 100%; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 2px; display: flex; justify-content: space-between; align-items: center;';
          countHeader.innerHTML = `<span>Fotos asignadas</span><span style="color:${urls.length >= 3 ? '#ef4444' : 'var(--primary-blue)'}">${urls.length} / 3</span>`;
          previewCont.appendChild(countHeader);

          urls.forEach((url, index) => {
            const thumb = document.createElement('div');
            thumb.style = 'position: relative; width: 80px; height: 80px; border-radius: 10px; overflow: hidden; border: 1.5px solid var(--border); box-shadow: var(--shadow-sm); transition: transform 0.2s;';
            thumb.onmouseenter = () => thumb.style.transform = 'scale(1.03)';
            thumb.onmouseleave = () => thumb.style.transform = 'scale(1)';

            const img = document.createElement('img');
            img.src = url;
            img.style = 'width: 100%; height: 100%; object-fit: cover;';
            thumb.appendChild(img);

            const delBtn = document.createElement('button');
            delBtn.type = 'button';
            delBtn.style = 'position: absolute; top: 4px; right: 4px; width: 18px; height: 18px; border-radius: 50%; background: rgba(15, 23, 42, 0.6); border: none; color: white; display: flex; align-items: center; justify-content: center; font-size: 9px; cursor: pointer; transition: all 0.2s; backdrop-filter: blur(4px);';
            delBtn.innerHTML = '<i class="fa-solid fa-xmark"></i>';
            delBtn.title = 'Eliminar foto';
            delBtn.onmouseenter = () => { delBtn.style.background = '#ef4444'; delBtn.style.transform = 'scale(1.1)'; };
            delBtn.onmouseleave = () => { delBtn.style.background = 'rgba(15, 23, 42, 0.6)'; delBtn.style.transform = 'scale(1)'; };
            delBtn.onclick = (e) => {
              e.stopPropagation();
              const newUrls = urls.filter((_, idx) => idx !== index);
              inp.value = newUrls.join(',');
              inp.dispatchEvent(new Event('input', { bubbles: true }));
            };
            thumb.appendChild(delBtn);
            list.appendChild(thumb);
          });
          previewCont.appendChild(list);
        }
      };

      renderThumbs();
      inp.addEventListener('input', renderThumbs);
    }
    else {
      inp = document.createElement('input');
      inp.className = 'form-input';
      inp.type = (field.k === 'precio') ? 'text' : (field.t || 'text');
      inp.placeholder = field.ph || '';
      inp.value = (field.k === 'precio') ? formatNumber(val) : val;
      inp.dataset.key = field.k;
      if (data && data[field.k + '_city']) inp.dataset.city = data[field.k + '_city'];
      if (data && data[field.k + '_address']) inp.dataset.address = data[field.k + '_address'];
      if (data && data[field.k + '_lat']) inp.dataset.lat = data[field.k + '_lat'];
      if (data && data[field.k + '_lng']) inp.dataset.lng = data[field.k + '_lng'];

      if (field.hasPin) {
        inp.classList.add('has-pin-icon');
      }
      fg.appendChild(inp);

      // Block invalid keys and sanitize input interactively
      const isPriceField = field.k === 'precio';
      const isIntegerField = field.k === 'personas' || field.k === 'stars' || (field.t === 'number' && !isPriceField);

      if (isPriceField) {
        inp.addEventListener('keypress', allowPriceKeys);
        inp.addEventListener('input', formatPriceInput);
      } else if (isIntegerField) {
        inp.addEventListener('keypress', e => {
          const allowed = ['Backspace', 'Delete', 'Tab', 'Enter', 'ArrowLeft', 'ArrowRight', 'Home', 'End'];
          if (!/[0-9]/.test(e.key) && !allowed.includes(e.key)) {
            e.preventDefault();
          }
        });
        inp.addEventListener('input', () => {
          inp.value = inp.value.replace(/[^0-9]/g, '');
        });
      } else if (field.t === 'tel' || field.k === 'phone') {
        inp.addEventListener('keypress', e => {
          if (!/[0-9+\s\-]/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete' && e.key !== 'Tab' && e.key !== 'Enter') {
            e.preventDefault();
          }
        });
        inp.addEventListener('input', () => {
          inp.value = inp.value.replace(/[^0-9+\s\-]/g, '');
        });
      } else if (field.t === 'email' || field.k === 'email' || field.k === 'correo') {
        inp.type = 'email';
      }
    }

    if (field.airportApi || field.airlineApi) {
      const drop = document.createElement('div'); drop.className = 'api-autocomplete-drop'; drop.style = 'position:absolute; background:#fff; border:1px solid #ccc; border-radius:4px; max-height:200px; overflow-y:auto; z-index:100; display:none; width:100%; box-shadow:0 4px 6px rgba(0,0,0,0.1); margin-top:2px;';
      fg.style.position = 'relative';
      fg.appendChild(drop);
      let timeout;
      inp.addEventListener('input', e => {
        delete inp.dataset.city;
        delete inp.dataset.lat;
        delete inp.dataset.lng;
        clearTimeout(timeout);
        const q = e.target.value.trim();
        if (q.length < 3) { drop.style.display = 'none'; return; }
        timeout = setTimeout(() => {
          const endpoint = field.airportApi ? '/api/airports' : '/api/airlines';
          fetch(`${endpoint}?q=${encodeURIComponent(q)}`)
            .then(res => res.json())
            .then(data => {
              drop.innerHTML = '';
              if (!data.length) { drop.style.display = 'none'; return; }
              data.forEach(it => {
                const item = document.createElement('div');
                item.style = 'padding:8px 12px; cursor:pointer; font-size:14px; border-bottom:1px solid #eee; display:flex; flex-direction:column; gap:2px;';
                item.innerHTML = `<strong>${it.text}</strong><span style="font-size:12px;color:#666">${it.city || ''}${it.city && it.country ? ', ' : ''}${it.country || ''}</span>`;
                item.onmouseenter = () => item.style.background = '#f5f5f5';
                item.onmouseleave = () => item.style.background = '#transparent';
                item.onclick = () => {
                  inp.value = it.text;
                  if (it.city) inp.dataset.city = it.city;
                  if (it.latitude) inp.dataset.lat = it.latitude;
                  if (it.longitude) inp.dataset.lng = it.longitude;
                  drop.style.display = 'none';
                };
                drop.appendChild(item);
              });
              drop.style.display = 'block';
            });
        }, 150);
      });
      document.addEventListener('click', ev => { if (!fg.contains(ev.target)) drop.style.display = 'none'; });
    }
  }
  return fg;
}
function closeModal() {
  document.querySelectorAll('.pac-container').forEach(el => el.remove());
  modalOverlay.classList.remove('open');
  editingIndex = null;
  window.copilotEditingIndex = null;
}
document.getElementById('modalClose').addEventListener('click', closeModal);
document.getElementById('modalCancel').addEventListener('click', closeModal);
// modalOverlay.addEventListener('click', e => { if (e.target === modalOverlay) closeModal() });
document.getElementById('modalSave').addEventListener('click', () => {
  const data = {};

  modalBody.querySelectorAll('[data-key]').forEach(el => {
    const key = el.dataset.key;
    if (el.classList.contains('rte-editor')) data[key] = el.innerHTML;
    else data[key] = el.value !== undefined ? el.value : '';
    if (el.dataset.city) data[key + '_city'] = el.dataset.city;
    if (el.dataset.address) data[key + '_address'] = el.dataset.address;
    if (el.dataset.lat) data[key + '_lat'] = el.dataset.lat;
    if (el.dataset.lng) data[key + '_lng'] = el.dataset.lng;
  });

  modalBody.querySelectorAll('.color-swatch.selected').forEach(sw => { data[sw.dataset.key] = sw.dataset.color });
  if (starRating > 0 && !data.stars) data.stars = starRating;
  if (modalBody.querySelector('[data-google-place-used="true"]') || (data.photo_url && data.photo_url.includes('/storage/places/')) || data.place_id) {
    data._google_place_used = true;
  }

  if (window.copilotEditingIndex !== null && typeof window.onCopilotItemSaved === 'function') {
    window.onCopilotItemSaved(data, pendingType);
    closeModal();
    return;
  }

  const type = editingIndex !== null ? (currentDay === 'portada' ? portadaItems : currentDay === 'cierre' ? cierreItems : days[currentDay])[editingIndex].type : pendingType;
  const item = { type, data };
  const arr = currentDay === 'portada' ? portadaItems : currentDay === 'cierre' ? cierreItems : days[currentDay];
  if (editingIndex !== null) { arr[editingIndex] = item; showToast('<i class="fa-solid fa-pencil"></i>', 'Elemento actualizado') }
  else { arr.push(item); showToast('<i class="fa-solid fa-check"></i>', 'Elemento agregado') }
  unsavedChanges = true;
  renderCanvas(); closeModal();
  autoSaveProTrip();
});

// TOAST
// TOAST
let toastTimeout = null;
function showToast(icon, msg) {
  const t = document.getElementById('toast');
  const iconEl = document.getElementById('toastIcon');
  const msgEl = document.getElementById('toastMsg');
  if (!t || !iconEl || !msgEl) return;

  iconEl.innerHTML = icon;
  msgEl.textContent = msg;

  t.classList.add('show');
  clearTimeout(toastTimeout);
  toastTimeout = setTimeout(() => {
    t.classList.remove('show');
  }, 2500);
}

// ============================================================
// VISTA PREVIA — genera HTML y lo muestra en modal inmersivo
// ============================================================
let currentPreviewHTML = '';

async function openPreview() {
  const loadingOverlay = document.getElementById('proPreviewLoadingOverlay');
  if (loadingOverlay) {
    loadingOverlay.style.display = 'flex';
    requestAnimationFrame(() => loadingOverlay.classList.add('is-active'));
  }

  const hideLoading = () => {
    if (loadingOverlay) {
      loadingOverlay.classList.remove('is-active');
      setTimeout(() => {
        if (!loadingOverlay.classList.contains('is-active')) {
          loadingOverlay.style.display = 'none';
        }
      }, 250);
    }
  };

  try {
    // 1. Guardar siempre los cambios antes de abrir la vista previa
    if (window.tripId && typeof performProSave === 'function') {
      if (typeof autoSaveTimer !== 'undefined' && autoSaveTimer) {
        clearTimeout(autoSaveTimer);
      }
      const saveResult = await performProSave(false);
      if (!saveResult) {
        console.warn('Advertencia: El guardado no se confirmó exitosamente.');
      }
    }

    const title = document.getElementById('portadaTitle')?.value || document.getElementById('itineraryNameInput')?.value || 'Mi Itinerario';
    const destination = document.getElementById('portadaDestino')?.value || '';
    const portadaSubtitle = document.getElementById('portadaSubtitle')?.value || '';
    const fechaInicio = document.getElementById('portadaFechaInicio')?.value || '';
    const fechaFin = document.getElementById('portadaFechaFin')?.value || '';
    const precio = (typeof unformatNumber === 'function') ? unformatNumber(document.getElementById('portadaPrecio')?.value || '') : (document.getElementById('portadaPrecio')?.value || '');
    const moneda = document.getElementById('portadaMoneda')?.value || 'USD';
    const totalViajeros = (typeof portadaAdultos !== 'undefined' ? portadaAdultos : 2) + (typeof portadaNinos !== 'undefined' ? portadaNinos : 0);
    const hasPortada = !!document.querySelector('.day-tab.portada-tab');
    const hasCierre = !!document.querySelector('.day-tab.cierre-tab');
    const closureCard = document.getElementById('cierreCardMain');
    const showDefaultCierre = closureCard && closureCard.style.display !== 'none';
    const totalItems = (typeof days !== 'undefined' && Array.isArray(days)) ? days.reduce((s, d) => s + (d ? d.length : 0), 0) : 0;

    // Build day tabs info
    const numericTabs = [...document.querySelectorAll('.day-tab:not(.portada-tab):not(.cierre-tab)')].map(t => ({ label: t.querySelector('.day-tab-label')?.textContent || t.textContent.trim(), idx: parseInt(t.dataset.day) }));

    // Build preview HTML
    const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';
    
    if (typeof buildPreviewHTML !== 'function') {
      hideLoading();
      alert('La función para generar la vista previa no está cargada correctamente.');
      return;
    }

    currentPreviewHTML = buildPreviewHTML({
      title, destination, portadaSubtitle, 
      hidePriceInPublic: typeof hidePriceInPublic !== 'undefined' ? hidePriceInPublic : false, 
      hideTravelersInPublic: typeof hideTravelersInPublic !== 'undefined' ? hideTravelersInPublic : false, 
      fechaInicio, fechaFin, precio, moneda, totalViajeros, hasPortada, hasCierre, showDefaultCierre, totalItems, numericTabs, 
      days: typeof days !== 'undefined' ? days : [[]], 
      dayDates: typeof dayDates !== 'undefined' ? dayDates : [''], 
      portadaAdultos: typeof portadaAdultos !== 'undefined' ? portadaAdultos : 2, 
      portadaNinos: typeof portadaNinos !== 'undefined' ? portadaNinos : 0, 
      portadaPhotoUrl: typeof portadaPhotoUrl !== 'undefined' ? portadaPhotoUrl : '', 
      portadaItems: typeof portadaItems !== 'undefined' ? portadaItems : [], 
      cierreItems: typeof cierreItems !== 'undefined' ? cierreItems : [],
      isPublicLink: false,
      csrfToken: csrfToken,
      tripId: window.tripId || '',
      shareToken: window.tripShareToken || '',
      userName: window.viantrypUserName || '',
      origin: window.location.origin,
      status: window.proStatus,
      themeColor: window.viantrypThemeColor || 'default',
      displayNameType: window.viantrypDisplayNameType || 'personal',
      agencyLogo: window.viantrypAgencyLogo || '',
      agencyName: window.viantrypAgencyName || '',
      userFullName: window.viantrypUserFullName || '',
      userPlan: window.viantrypUserPlan || 'básico',
      isTrialActive: !!window.viantrypIsTrialActive,
      googleClientId: window.viantrypGoogleClientId || ''
    });

    const modal = document.getElementById('proPreviewModal');
    const iframe = document.getElementById('proPreviewIframe');

    if (modal && iframe) {
      modal.style.display = 'flex';
      iframe.srcdoc = currentPreviewHTML;
      hideLoading();
      if (typeof showToast === 'function') showToast('<i class="fa-regular fa-eye"></i>', 'Vista previa lista');
    } else {
      const blob = new Blob([currentPreviewHTML], { type: 'text/html' });
      const url = URL.createObjectURL(blob);
      const win = window.open(url, '_blank');
      if (!win || win.closed || typeof win.closed === 'undefined') {
        window.location.href = url;
      }
      hideLoading();
      if (typeof showToast === 'function') showToast('<i class="fa-regular fa-eye"></i>', 'Vista previa lista');
    }
  } catch (err) {
    hideLoading();
    console.error('Error in openPreview:', err);
    alert('Ocurrió un error al abrir la vista previa: ' + err.message);
  }
}

function closeProPreviewModal() {
  const modal = document.getElementById('proPreviewModal');
  const iframe = document.getElementById('proPreviewIframe');
  if (modal) {
    modal.style.display = 'none';
  }
  if (iframe) {
    iframe.srcdoc = '';
  }
}

function openPreviewInNewTab() {
  if (!currentPreviewHTML) return;
  const blob = new Blob([currentPreviewHTML], { type: 'text/html' });
  const url = URL.createObjectURL(blob);
  window.open(url, '_blank');
}

// ESC key and Android hardware back button handler for preview modal
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    const modal = document.getElementById('proPreviewModal');
    if (modal && modal.style.display !== 'none') {
      closeProPreviewModal();
    }
  }
});

if (window.Capacitor && window.Capacitor.Plugins && window.Capacitor.Plugins.App) {
  try {
    window.Capacitor.Plugins.App.addListener('backButton', () => {
      const modal = document.getElementById('proPreviewModal');
      if (modal && modal.style.display !== 'none') {
        closeProPreviewModal();
      }
    });
  } catch(e) {}
}

window.openPreview = openPreview;
window.closeProPreviewModal = closeProPreviewModal;
window.openPreviewInNewTab = openPreviewInNewTab;

// buildPreviewHTML() has been moved to pro-viewer.js

// Initial Load
if (window.proState) {
  const s = window.proState;
  if (s.dayDates) dayDates = s.dayDates;
  if (s.days) {
    days = s.days;
    days.forEach((dayItems, idx) => {
      if (dayItems) sortDayItemsChronologically(dayItems, idx);
    });
  }
  if (s.portadaItems) portadaItems = s.portadaItems;
  if (s.cierreItems) cierreItems = s.cierreItems;
  if (s.portadaAdultos !== undefined) portadaAdultos = s.portadaAdultos;
  if (s.portadaNinos !== undefined) portadaNinos = s.portadaNinos;
  if (s.portadaPhotoUrl !== undefined) portadaPhotoUrl = s.portadaPhotoUrl;
  if (s.isPriceManual !== undefined) isPriceManual = s.isPriceManual;
  if (s.hidePriceInPublic !== undefined) hidePriceInPublic = s.hidePriceInPublic;
  if (s.hideTravelersInPublic !== undefined) hideTravelersInPublic = s.hideTravelersInPublic;
  if (s.portadaSubtitle !== undefined) portadaSubtitle = s.portadaSubtitle;

  // Ensure days array has at least 1 day if empty, or match numeric tabs
  if (!days || days.length === 0) days = [[]];
  if (!dayDates || dayDates.length === 0) dayDates = [''];
  while (dayDates.length < days.length) {
    dayDates.push('');
  }

  // Adjust day counts
  numericDayCount = days.length;
  dayCount = numericDayCount;
  nextDayNumber = dayCount + 1;

  // Set UI elements
  document.addEventListener('DOMContentLoaded', () => {
    if (portadaPhotoUrl) setPortadaPhoto(portadaPhotoUrl);
    const adEl = document.getElementById('portadaAdultos');
    const niEl = document.getElementById('portadaNinos');
    if (adEl) adEl.textContent = portadaAdultos;
    if (niEl) niEl.textContent = portadaNinos;
    updatePortadaTravelersUI();

    const pi = document.getElementById('portadaFechaInicio');
    if (pi) {
      if (s.fechaInicio) {
        pi.value = s.fechaInicio;
      } else if (window.tripStartDate && !pi.value) {
        pi.value = window.tripStartDate;
      }
      const startDate = pi.value;
      if (startDate) {
        for (let i = 0; i < days.length; i++) {
          if (!dayDates[i]) {
            dayDates[i] = addDaysToDate(startDate, i);
          }
        }
      }
      pi.addEventListener('change', () => {
        const startDate = pi.value;
        if (startDate) {
          for (let i = 0; i < days.length; i++) {
            dayDates[i] = addDaysToDate(startDate, i);
          }
          days.forEach((dayItems, idx) => {
            if (dayItems) sortDayItemsChronologically(dayItems, idx);
          });
          renderTabs();
          renderCanvas();
        }
        updatePortadaDatesUI();
      });
    }
    const pf = document.getElementById('portadaFechaFin');
    if (pf) {
      if (s.fechaFin) {
        pf.value = s.fechaFin;
      } else if (window.tripEndDate && !pf.value) {
        pf.value = window.tripEndDate;
      }
      pf.addEventListener('change', () => updatePortadaDatesUI());
    }
    updatePortadaDatesUI();

    const pp = document.getElementById('portadaPrecio');
    if (pp) {
      if (s.precio !== undefined && s.precio !== null && isPriceManual) {
        pp.value = formatNumber(s.precio);
      }
      pp.addEventListener('keypress', allowPriceKeys);
      pp.addEventListener('input', (e) => {
        formatPriceInput(e);
        handlePriceManualEdit();
      });
    }
    const pm = document.getElementById('portadaMoneda');
    if (pm && s.moneda) pm.value = s.moneda;
    if (pm) pm.addEventListener('change', handleCurrencyChange);

    const titleInp = document.getElementById('portadaTitle');
    if (titleInp && s.title) titleInp.value = s.title;

    const destInp = document.getElementById('portadaDestino');
    if (destInp) {
      if (s.destination) {
        destInp.value = s.destination;
      } else if (window.tripDestination && !destInp.value) {
        destInp.value = window.tripDestination;
      }
      destInp.addEventListener('input', () => updatePortadaDatesUI());
    }

    const subInp = document.getElementById('portadaSubtitle');
    if (subInp) {
      if (s.portadaSubtitle) {
        subInp.value = s.portadaSubtitle;
        autoResizeTextarea(subInp);
      }
    }

    updatePriceVisibilityUI();

    // Listeners para inputs de cabecera
    const headerInputs = [pi, pf, titleInp, destInp];
    headerInputs.forEach(inp => {
      if (inp) inp.addEventListener('input', () => autoSaveProTrip());
    });

    lastAutoCalculatedSum = calculateTripServicesTotal();
    renderTabs();
    renderCanvas();
    updatePortadaPriceUI();
  });
} else {
  document.addEventListener('DOMContentLoaded', () => {
    const pi = document.getElementById('portadaFechaInicio');
    if (pi) {
      if (window.tripStartDate && !pi.value) {
        pi.value = window.tripStartDate;
      }
      const startDate = pi.value;
      if (startDate) {
        for (let i = 0; i < days.length; i++) {
          if (!dayDates[i]) {
            dayDates[i] = addDaysToDate(startDate, i);
          }
        }
      }
      pi.addEventListener('change', () => {
        const startDate = pi.value;
        if (startDate) {
          for (let i = 0; i < days.length; i++) {
            dayDates[i] = addDaysToDate(startDate, i);
          }
          days.forEach((dayItems, idx) => {
            if (dayItems) sortDayItemsChronologically(dayItems, idx);
          });
          renderTabs();
          renderCanvas();
        }
        updatePortadaDatesUI();
      });
    }
    const pf = document.getElementById('portadaFechaFin');
    if (pf) {
      if (window.tripEndDate && !pf.value) {
        pf.value = window.tripEndDate;
      }
      pf.addEventListener('change', () => updatePortadaDatesUI());
    }
    updatePortadaDatesUI();
    updatePortadaTravelersUI();

    const pp = document.getElementById('portadaPrecio');
    if (pp) {
      pp.addEventListener('keypress', allowPriceKeys);
      pp.addEventListener('input', (e) => {
        formatPriceInput(e);
        handlePriceManualEdit();
      });
    }
    const pm = document.getElementById('portadaMoneda');
    if (pm) pm.addEventListener('change', handleCurrencyChange);

    const titleInp = document.getElementById('portadaTitle');
    const destInp = document.getElementById('portadaDestino');
    if (destInp) {
      if (window.tripDestination && !destInp.value) {
        destInp.value = window.tripDestination;
      }
      destInp.addEventListener('input', () => updatePortadaDatesUI());
    }

    updatePriceVisibilityUI();

    // Listeners para inputs de cabecera
    const headerInputs = [pi, pf, titleInp, destInp];
    headerInputs.forEach(inp => {
      if (inp) inp.addEventListener('input', () => autoSaveProTrip());
    });

    renderTabs();
    renderCanvas();
    updatePortadaPriceUI();
  });
}

// Global click-outside detection for popovers & photo menu
document.addEventListener('click', (e) => {
  const popDates = document.getElementById('popoverDates');
  const btnDates = document.getElementById('btnEditDates');
  const valDates = document.getElementById('displayDateRange');
  if (popDates && popDates.classList.contains('open')) {
    if (!popDates.contains(e.target) && !btnDates?.contains(e.target) && !valDates?.contains(e.target)) {
      closeDatesPopover();
    }
  }

  const popTravelers = document.getElementById('popoverTravelers');
  const btnTravelers = document.getElementById('btnEditTravelers');
  const valTravelers = document.getElementById('displayTravelersMain');
  if (popTravelers && popTravelers.classList.contains('open')) {
    if (!popTravelers.contains(e.target) && !btnTravelers?.contains(e.target) && !valTravelers?.contains(e.target)) {
      closeTravelersPopover();
    }
  }

  const photoMenu = document.getElementById('portadaPhotoMenu');
  const photoBtn = document.getElementById('portadaPhotoActionBtn');
  if (photoMenu && photoMenu.classList.contains('open')) {
    if (!photoMenu.contains(e.target) && !photoBtn?.contains(e.target)) {
      closePhotoMenu();
    }
  }
});

function toggleSidebar() {
  const sb = document.querySelector('.sidebar');
  const ov = document.getElementById('sidebarOverlay');
  sb.classList.toggle('open');
  ov.classList.toggle('open');
}

// ── EXTENSIÓN: Guardado Automático ──
let autoSaveTimer = null;
function autoSaveProTrip() {
  if (!window.tripId) return;
  unsavedChanges = true;

  clearTimeout(autoSaveTimer);
  autoSaveTimer = setTimeout(async () => {
    await performProSave(true);
  }, 1500); // 1.5 seconds debounce
}

async function manualSaveProTrip() {
  if (!window.tripId) return;
  showToast('<i class="fa-solid fa-spinner fa-spin"></i>', 'Guardando viaje...');
  await performProSave(false);
}

async function performProSave(isSilent = true) {
  const title = document.getElementById('portadaTitle') ? document.getElementById('portadaTitle').value : 'Sin título';
  const destination = document.getElementById('portadaDestino') ? document.getElementById('portadaDestino').value.trim() : '';
  const subtitle = document.getElementById('portadaSubtitle') ? document.getElementById('portadaSubtitle').value.trim() : '';
  const fechaInicio = document.getElementById('portadaFechaInicio') ? document.getElementById('portadaFechaInicio').value : null;
  const fechaFin = document.getElementById('portadaFechaFin') ? document.getElementById('portadaFechaFin').value : null;
  const precio = document.getElementById('portadaPrecio') ? unformatNumber(document.getElementById('portadaPrecio').value) : null;
  const moneda = document.getElementById('portadaMoneda') ? document.getElementById('portadaMoneda').value : 'USD';
  const totalViajeros = portadaAdultos + portadaNinos;

  const hasPortada = !!document.getElementById('portadaCanvas');
  const hasCierre = !!document.getElementById('cierreCanvas');
  const closureCard = document.getElementById('cierreCardMain');
  const showDefaultCierre = closureCard && closureCard.style.display !== 'none';
  const totalItems = days.reduce((s, d) => s + (d ? d.length : 0), 0);
  const numericTabs = [...document.querySelectorAll('.day-tab:not(.portada-tab):not(.cierre-tab)')].map(t => ({ label: t.querySelector('.day-tab-label')?.textContent || t.textContent.trim(), idx: parseInt(t.dataset.day) }));

  if (days && dayDates) {
    days.forEach((dayItems, idx) => {
      if (dayItems) sortDayItemsChronologically(dayItems, idx);
    });
  }

  const proStateObj = {
    title,
    destination,
    portadaSubtitle: subtitle,
    fechaInicio,
    fechaFin,
    precio,
    moneda,
    isPriceManual,
    hidePriceInPublic,
    hideTravelersInPublic,
    totalViajeros,
    hasPortada,
    hasCierre,
    showDefaultCierre,
    totalItems,
    numericTabs,
    days,
    dayDates,
    portadaAdultos,
    portadaNinos,
    portadaPhotoUrl,
    portadaItems,
    cierreItems,
    isPublicLink: false,
    status: window.proStatus,
    origin: window.location.origin
  };

  try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';
    const baseUrl = window.location.origin;
    const response = await fetch(baseUrl + '/trips/' + window.tripId + '/save-pro-state', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      },
      body: JSON.stringify({ pro_state: proStateObj })
    });

    const resData = await response.json().catch(() => ({}));
    if (response.ok && resData.success !== false) {
      unsavedChanges = false;
      console.log('Viaje PRO guardado correctamente.');
      showToast('✅', '¡Viaje guardado correctamente!');
      return true;
    } else {
      if (resData.error_code === 'LIMIT_REACHED') {
        if (typeof window.openProUpgradeInlineModal === 'function') {
          window.openProUpgradeInlineModal('Límite del Plan Básico Alcanzado', resData.message || 'Has alcanzado el límite de tu plan Básico.');
        } else if (typeof openUpgradeModal === 'function') {
          openUpgradeModal();
        }
        if (!isSilent) showToast('⚠️', resData.message || 'Has alcanzado el límite de tu plan.');
        return false;
      }
      console.error('Error al guardar el viaje PRO');
      if (!isSilent) showToast('❌', resData.message || 'Error al guardar el viaje');
      return false;
    }
  } catch (e) {
    console.error('Error en el guardado:', e);
    if (!isSilent) showToast('❌', 'Error de conexión al guardar');
    return false;
  }
}
function execRTE(cmd) {
  if (cmd === 'createLink') {
    const url = prompt('Ingresa la URL del enlace:');
    if (url) document.execCommand(cmd, false, url);
  } else {
    document.execCommand(cmd, false, null);
  }
  const editor = document.querySelector('.rte-editor');
  if (editor) editor.focus();
}

// Expose Pro Editor globals to window
window.editItem = editItem;
window.deleteItem = deleteItem;
window.duplicateItem = duplicateItem;
window.openModal = openModal;
window.closeModal = closeModal;
window.openUnsplash = openUnsplash;
window.closeUnsplash = closeUnsplash;
window.confirmUnsplash = confirmUnsplash;
window.openGiphy = openGiphy;
window.closeGiphy = closeGiphy;
window.confirmGiphy = confirmGiphy;
window.searchGiphy = searchGiphy;
window.searchUnsplash = searchUnsplash;

// ============================================================
// VIANTRYP COPILOT AI INTEGRATION
// ============================================================
function normalizeCopilotAction(action) {
  if (!action) return null;

  const rawType = (action.type || '').toLowerCase().trim();
  const rawData = action.data || {};

  // Map type synonyms to valid keys in config C
  let type = 'actividad';
  if (['hotel', 'alojamiento', 'lodging', 'hospedaje'].includes(rawType)) {
    type = 'alojamiento';
  } else if (['flight', 'vuelo', 'avion', 'plane'].includes(rawType)) {
    type = 'flight';
  } else if (['activity', 'actividad', 'tour', 'visita'].includes(rawType)) {
    type = (rawType === 'tour') ? 'tour' : 'actividad';
  } else if (['transport', 'transporte', 'transfer', 'traslado'].includes(rawType)) {
    type = 'transporte';
  } else if (['comida', 'restaurante', 'restaurant', 'cena', 'almuerzo', 'desayuno'].includes(rawType)) {
    type = 'comida';
  } else if (['note', 'nota', 'caja', 'tip'].includes(rawType)) {
    type = 'caja';
  } else if (['ubicacion', 'location', 'lugar', 'place'].includes(rawType)) {
    type = 'ubicacion';
  } else if (['texto', 'text', 'parrafo', 'paragraph'].includes(rawType)) {
    type = 'texto';
  } else if (['titulo', 'title'].includes(rawType)) {
    type = 'titulo';
  }

  // Normalize data keys according to type
  const d = { ...rawData };

  if (type === 'alojamiento') {
    d.nombre = d.nombre || d.hotel_name || d.title || action.title || 'Alojamiento';
    d.direccion = d.direccion || d.address || d.location || '';
    d.checkin = d.checkin || d.check_in || '';
    d.checkout = d.checkout || d.check_out || '';
    d.reserva = d.reserva || d.confirmation_code || d.codigo_reserva || '';
    d.precio = d.precio || d.price || '';
    d.tipo_alojamiento = d.tipo_alojamiento || 'Hotel';
    d.habitacion = d.habitacion || d.room_type || '';
    d.alimentacion = d.alimentacion || '';
    d.notas = d.notas || d.notes || d.description || '';
  } else if (type === 'flight') {
    d.origen = d.origen || d.departure_airport || d.from || '';
    d.destino = d.destino || d.arrival_airport || d.to || '';
    d.aerolinea = d.aerolinea || d.airline || '';
    d.vuelo = d.vuelo || d.flight_number || '';
    d.salida = d.salida || d.departure_time || '';
    d.llegada = d.llegada || d.arrival_time || '';
    d.reserva = d.reserva || d.confirmation_code || d.codigo_reserva || '';
    d.precio = d.precio || d.price || '';
    d.clase = d.clase || 'Económica';
    d.notas = d.notas || d.notes || '';
  } else if (type === 'actividad') {
    d.nombre = d.nombre || d.activity_title || d.title || action.title || 'Actividad';
    d.direccion = d.direccion || d.location || d.address || '';
    d.fecha = d.fecha || d.time || d.departure_time || '';
    d.duracion = d.duracion || d.duration || '';
    d.descripcion = d.descripcion || d.description || '';
    d.reserva = d.reserva || d.confirmation_code || '';
    d.precio = d.precio || d.price || '';
  } else if (type === 'transporte') {
    d.tipo = d.tipo || d.transport_type || 'Transporte';
    d.proveedor = d.proveedor || d.company || '';
    d.origen = d.origen || d.pickup_location || d.from || '';
    d.destino = d.destino || d.destination || d.to || '';
    d.salida = d.salida || d.departure_time || '';
    d.llegada = d.llegada || d.arrival_time || '';
    d.reserva = d.reserva || d.confirmation_code || '';
    d.precio = d.precio || d.price || '';
  } else if (type === 'comida') {
    d.restaurante = d.restaurante || d.restaurant_name || d.name || d.title || action.title || 'Restaurante';
    d.tipo = d.tipo || 'Cena';
    d.direccion = d.direccion || d.address || d.location || '';
    d.fecha = d.fecha || d.time || '';
    d.reserva = d.reserva || d.confirmation_code || '';
    d.precio = d.precio || d.price || '';
  } else if (type === 'caja') {
    d.titulo = d.titulo || d.note_title || d.title || action.title || 'Nota';
    d.contenido = d.contenido || d.content || d.description || '';
    d.icono = d.icono || '💡';
    d.color_fondo = d.color_fondo || '#f59e0b';
  }

  // Preserve attachments if present
  if (rawData.attachment_url) d.attachment_url = rawData.attachment_url;
  if (rawData.attachment_name) d.attachment_name = rawData.attachment_name;
  if (rawData.document_id) d.document_id = rawData.document_id;

  return {
    type,
    day: action.day || 1,
    data: d
  };
}

function formatToDatetimeLocal(dateVal, timeVal, defaultTime = '09:00', fallbackDate = '') {
  let dStr = '';
  let tStr = '';

  const clean = (str) => (typeof str === 'string' ? str.trim() : '');
  const dVal = clean(dateVal);
  const tVal = clean(timeVal);
  const fVal = clean(fallbackDate);

  if (dVal.includes('T') || dVal.includes(' ')) {
    const parts = dVal.replace(' ', 'T').split('T');
    dStr = parts[0];
    tStr = parts[1] ? parts[1].substring(0, 5) : '';
  } else if (/^\d{4}-\d{2}-\d{2}$/.test(dVal)) {
    dStr = dVal;
  } else if (/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(dVal)) {
    const p = dVal.split('/');
    dStr = `${p[2]}-${p[1].padStart(2, '0')}-${p[0].padStart(2, '0')}`;
  } else if (/^\d{1,2}:\d{2}/.test(dVal)) {
    const p = dVal.substring(0, 5).split(':');
    tStr = `${p[0].padStart(2, '0')}:${p[1]}`;
  }

  if (tVal) {
    if (tVal.includes('T') || tVal.includes(' ')) {
      const parts = tVal.replace(' ', 'T').split('T');
      if (!dStr && /^\d{4}-\d{2}-\d{2}$/.test(parts[0])) dStr = parts[0];
      if (parts[1]) tStr = parts[1].substring(0, 5);
    } else if (/^\d{1,2}:\d{2}/.test(tVal)) {
      const p = tVal.substring(0, 5).split(':');
      tStr = `${p[0].padStart(2, '0')}:${p[1]}`;
    }
  }

  if (!dStr && fVal) {
    if (fVal.includes('T') || fVal.includes(' ')) {
      dStr = fVal.replace(' ', 'T').split('T')[0];
    } else if (/^\d{4}-\d{2}-\d{2}$/.test(fVal)) {
      dStr = fVal;
    }
  }

  if (!dStr) return '';
  if (!tStr) tStr = defaultTime;

  return `${dStr}T${tStr}`;
}

window.ViantrypCopilot = window.ViantrypCopilot || {};

window.ViantrypCopilot.onApplyAction = function (action) {
  if (!action) return;
  window.ViantrypCopilot.onApplyBatchActions([action]);
};

window.ViantrypCopilot.onApplyBatchActions = function (items) {
  if (!Array.isArray(items) || items.length === 0) return;

  // Sort items chronologically by start_date and start_time
  const sortedItems = [...items].sort((a, b) => {
    const dA = a.start_date || (a.data && a.data.start_date) || '9999-99-99';
    const dB = b.start_date || (b.data && b.data.start_date) || '9999-99-99';
    if (dA !== dB) return dA.localeCompare(dB);
    const tA = a.start_time || '99:99';
    const tB = b.start_time || '99:99';
    return tA.localeCompare(tB);
  });

  let firstTargetDayIndex = null;
  let addedCount = 0;

  sortedItems.forEach(action => {
    const norm = normalizeCopilotAction(action);
    if (!norm) return;

    let targetDayIndex = null;
    const targetDate = action.start_date || (action.data && action.data.start_date);

    if (targetDate && typeof targetDate === 'string' && targetDate.length >= 10) {
      const cleanDate = targetDate.substring(0, 10);
      if (!Array.isArray(dayDates)) window.dayDates = [];

      const foundIdx = dayDates.indexOf(cleanDate);
      if (foundIdx !== -1) {
        targetDayIndex = foundIdx;
      } else {
        // Insert cleanDate into dayDates at its proper chronological position without adding filler days
        let insertIdx = dayDates.findIndex(d => d && d > cleanDate);
        if (insertIdx === -1) insertIdx = dayDates.length;

        dayDates.splice(insertIdx, 0, cleanDate);
        days.splice(insertIdx, 0, []);
        targetDayIndex = insertIdx;

        // Shift firstTargetDayIndex if inserting before it
        if (firstTargetDayIndex !== null && insertIdx <= firstTargetDayIndex) {
          firstTargetDayIndex++;
        }
      }
    } else {
      // Fallback if no date is present: use action.day or norm.day
      const dayNum = (typeof action.day === 'number' && action.day >= 1) 
        ? action.day 
        : ((typeof norm.day === 'number' && norm.day >= 1) ? norm.day : 1);
      targetDayIndex = Math.max(0, dayNum - 1);

      if (targetDayIndex >= days.length) {
        while (days.length <= targetDayIndex) {
          days.push([]);
          let nextDate = '';
          if (days.length > 1) {
            const prevDate = dayDates[days.length - 2];
            if (prevDate) nextDate = addDaysToDate(prevDate, 1);
          } else {
            const pi = document.getElementById('portadaFechaInicio');
            if (pi && pi.value) nextDate = pi.value;
          }
          dayDates.push(nextDate);
        }
      }
    }

    if (!days[targetDayIndex]) {
      days[targetDayIndex] = [];
    }

    // Set properly formatted datetime-local fields for all item types
    const itemDate = (targetDayIndex !== null && dayDates[targetDayIndex]) ? dayDates[targetDayIndex] : (targetDate || '');
    const startDate = action.start_date || (action.data && action.data.start_date) || itemDate;
    const endDate = action.end_date || (action.data && action.data.end_date) || startDate;

    const startTime = action.start_time || (action.data && (action.data.start_time || action.data.salida || action.data.checkin || action.data.fecha)) || '';
    const endTime = action.end_time || (action.data && (action.data.end_time || action.data.llegada || action.data.checkout)) || '';

    if (norm.type === 'flight') {
      norm.data.salida = formatToDatetimeLocal(norm.data.salida, startTime, '10:00', startDate);
      norm.data.llegada = formatToDatetimeLocal(norm.data.llegada, endTime, '14:00', endDate);
    } else if (norm.type === 'alojamiento') {
      norm.data.checkin = formatToDatetimeLocal(norm.data.checkin, startTime, '15:00', startDate);
      norm.data.checkout = formatToDatetimeLocal(norm.data.checkout, endTime, '11:00', endDate);
    } else if (norm.type === 'transporte') {
      norm.data.salida = formatToDatetimeLocal(norm.data.salida, startTime, '09:00', startDate);
      norm.data.llegada = formatToDatetimeLocal(norm.data.llegada, endTime, '11:00', endDate);
    } else if (norm.type === 'actividad') {
      norm.data.fecha = formatToDatetimeLocal(norm.data.fecha, startTime, '10:00', startDate);
    } else if (norm.type === 'comida') {
      norm.data.fecha = formatToDatetimeLocal(norm.data.fecha, startTime, '13:00', startDate);
    } else if (norm.type === 'tour') {
      norm.data.fecha = formatToDatetimeLocal(norm.data.fecha, startTime, '09:00', startDate);
    }

    const item = {
      type: norm.type,
      data: norm.data
    };

    days[targetDayIndex].push(item);
    addedCount++;

    if (firstTargetDayIndex === null) {
      firstTargetDayIndex = targetDayIndex;
    }
  });

  if (addedCount === 0) return;

  unsavedChanges = true;

  // Switch active view to the first modified day
  if (firstTargetDayIndex !== null) {
    currentDay = firstTargetDayIndex;
  }

  // Render updated tabs toolbar and canvas
  if (typeof renderTabs === 'function') {
    renderTabs();
  }
  if (typeof renderCanvas === 'function') {
    renderCanvas();
  }
  if (typeof autoSaveProTrip === 'function') {
    autoSaveProTrip();
  }

  // Scroll to active tab in the toolbar
  const targetTab = document.querySelector(`.day-tab[data-day="${firstTargetDayIndex}"]`) || document.querySelector('.canvas-toolbar .day-tab.active');
  if (targetTab) {
    targetTab.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
  }

  // Toast notification
  if (typeof showToast === 'function') {
    const targetDateLabel = dayDates[firstTargetDayIndex] ? ` (${dayDates[firstTargetDayIndex]})` : '';
    showToast('<i class="fa-solid fa-cloud-arrow-up"></i>', `¡${addedCount} elementos agregados al Día ${firstTargetDayIndex + 1}${targetDateLabel}!`);
  }

  // Apply visual pulse/glow to canvas container
  const canvasEl = document.getElementById('canvas-container') || document.querySelector('.canvas-items-wrapper');
  if (canvasEl) {
    canvasEl.classList.add('animate-pulse');
    setTimeout(() => canvasEl.classList.remove('animate-pulse'), 1800);
  }
};


