// STATE
let days = [[], [], []];
let dayDates = ['', '', '']; // per-day date strings (yyyy-mm-dd)
let portadaItems = [];
let cierreItems = [];
let currentDay = 'portada';
let dayCount = 3, numericDayCount = 3, nextDayNumber = 4; // nextDayNumber always increments up
let dragType = null, dragLabel = null, dragSourceIndex = null, dragSourceContainer = null;
let pendingType = null, editingIndex = null, starRating = 0;
let dragTabSourceIndex = null;
let portadaAdultos = 2, portadaNinos = 0;
let portadaPhotoUrl = '';
const GIPHY_API_KEY = 'ga2U6DfG1RcG9EESPkiPph7sMM0uhrdy';
let selectedUnsplashUrl = null, unsplashTarget = 'portada';
let selectedGiphyUrl = null, giphyTarget = 'canvas';
let confirmCallback = null;
let unsavedChanges = false;
let currentPhotoTargetInput = null;

// PORTADA
function changePortadaCount(type, d) {
  if (type === 'adultos') { portadaAdultos = Math.max(0, portadaAdultos + d); document.getElementById('portadaAdultos').textContent = portadaAdultos }
  else { portadaNinos = Math.max(0, portadaNinos + d); document.getElementById('portadaNinos').textContent = portadaNinos }
  document.getElementById('portadaTotal').textContent = portadaAdultos + portadaNinos;
  autoSaveProTrip();
}
function handlePortadaUpload(e) {
  const f = e.target.files[0];
  if (!f) return;
  if (f.size > 5 * 1024 * 1024) { showToast('⚠️', 'La imagen no puede superar 5MB'); return; }
  const r = new FileReader(); r.onload = ev => { portadaPhotoUrl = ev.target.result; setPortadaPhoto(ev.target.result) }; r.readAsDataURL(f)
}
function setPortadaPhoto(url) {
  portadaPhotoUrl = url;
  const img = document.getElementById('portadaHeroImg');
  img.src = url; img.classList.add('visible'); document.getElementById('portadaHero').classList.add('has-image');
  autoSaveProTrip();
}
function clearPortadaPhoto(e) {
  e && e.stopPropagation(); portadaPhotoUrl = '';
  const img = document.getElementById('portadaHeroImg');
  img.src = ''; img.classList.remove('visible'); document.getElementById('portadaHero').classList.remove('has-image');
  autoSaveProTrip();
}

// UNSPLASH
function openUnsplash(target = 'portada', targetInput = null) {
  unsplashTarget = target;
  currentPhotoTargetInput = targetInput;
  selectedUnsplashUrl = null;
  document.getElementById('unsplashSelectBtn').disabled = true;
  document.getElementById('unsplashOverlay').classList.add('open');
  const grid = document.getElementById('unsplashGrid');
  grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:25px 20px;color:var(--text-dim);font-size:12px">Escribe algo para encontrar miles de imágenes...</div>';

  // Sugerencias iniciales
  fetch(`/api/unsplash/search?query=travel&per_page=15`)
    .then(res => res.json())
    .then(data => {
      if (data.success && data.images && data.images.length > 0) {
        renderUnsplashGrid(data.images);
      }
    });
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

  fetch(`/api/unsplash/search?query=${encodeURIComponent(query)}&per_page=15`)
    .then(res => res.json())
    .then(data => {
      if (data.success && data.images && data.images.length > 0) {
        renderUnsplashGrid(data.images);
      } else {
        grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--text-dim)">No se encontraron imágenes para esta búsqueda.</div>';
      }
    })
    .catch(err => {
      console.error('Unsplash error:', err);
      grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:40px;color:#f0567a">Error de conexión con Unsplash.</div>';
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
      document.getElementById('unsplashSelectBtn').disabled = false
    });
    g.appendChild(img)
  });
}
function confirmUnsplash() {
  if (selectedUnsplashUrl) {
    if (unsplashTarget === 'portada') {
      setPortadaPhoto(selectedUnsplashUrl);
      showToast('🌅', 'Foto aplicada');
    } else if (unsplashTarget === 'canvas') {
      const arr = currentDay === 'portada' ? portadaItems : currentDay === 'cierre' ? cierreItems : days[currentDay];
      arr.push({ type: 'imagen', data: { url: selectedUnsplashUrl, caption: '', tamano: 'Mediano' } });
      renderCanvas();
      showToast('🖼️', 'Imagen agregada');
      const inp = modalBody.querySelector('input[data-key="photo_url"]');
      if (inp) {
        inp.value = selectedUnsplashUrl;
        showToast('📸', 'Foto de tour aplicada');
      }
    } else if (unsplashTarget === 'item_photo' && currentPhotoTargetInput) {
      currentPhotoTargetInput.value = selectedUnsplashUrl;
      showToast('📸', 'Foto aplicada');
    }
  }
  closeUnsplash();
  autoSaveProTrip();
}

// GIPHY
function openGiphy(target = 'canvas') {
  giphyTarget = target;
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
function closeGiphy() { document.getElementById('giphyOverlay').classList.remove('open'); selectedGiphyUrl = null }
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
      document.getElementById('giphySelectBtn').disabled = false;
    };
    g.appendChild(img);
  });
}
function confirmGiphy() {
  if (selectedGiphyUrl) {
    if (giphyTarget === 'canvas') {
      const arr = currentDay === 'portada' ? portadaItems : currentDay === 'cierre' ? cierreItems : days[currentDay];
      arr.push({ type: 'gif', data: { url: selectedGiphyUrl, caption: '' } });
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
    priceInput.addEventListener('input', (e) => {
      let val = e.target.value.replace(/[^0-9,]/g, ''); // Solo dígitos y una coma
      const parts = val.split(',');
      if (parts.length > 2) val = parts[0] + ',' + parts.slice(1).join('');

      const cleanParts = val.split(',');
      cleanParts[0] = cleanParts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
      const finalVal = cleanParts.join(',');

      e.target.value = finalVal;
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
document.getElementById('unsplashSearch').addEventListener('keydown', e => { if (e.key === 'Enter') searchUnsplash() });

// CONFIGS
const C = {
  flight: { icon: '<i class="fa-solid fa-plane"></i>', label: 'Vuelo', color: 'var(--primary-blue)', bg: '#e0f2fe', fields: [{ k: 'origen', l: 'Ciudad origen', t: 'text', ph: 'Cód. IATA o ciudad', airportApi: true }, { k: 'destino', l: 'Ciudad destino', t: 'text', ph: 'Cód. IATA o ciudad', airportApi: true }, { k: 'aerolinea', l: 'Aerolínea', t: 'text', ph: 'Air France', airlineApi: true }, { k: 'vuelo', l: 'No. de vuelo', t: 'text', ph: 'AF9474' }, { k: 'salida', l: 'Salida', t: 'datetime-local' }, { k: 'llegada', l: 'Llegada', t: 'datetime-local' }, { k: 'clase', l: 'Clase', t: 'select', ph: 'Selecciona...', opts: ['Económica', 'Ejecutiva', 'Primera'] }, { k: 'precio', l: 'Precio', t: 'number', ph: '800' }, { k: 'reserva', l: 'Código reserva', t: 'text', ph: 'VLO-12345' }, { k: 'adjunto', l: 'Archivo adjunto', t: 'file-upload', fw: true }, { k: 'notas', l: 'Notas', t: 'textarea', ph: 'Info adicional...' }] },
  alojamiento: { icon: '<i class="fa-solid fa-hotel"></i>', label: 'Alojamiento', color: '#f0567a', bg: '#fde8ee', fields: [{ k: 'nombre', l: 'Nombre del hotel', t: 'text', ph: 'Hotel Luxe París', fw: true, hasInfo: true }, { k: 'direccion', l: 'Dirección', t: 'text', ph: 'Avenida...', group: 'google', fw: true }, { k: 'phone', l: 'Teléfono', t: 'text', ph: '+1 234...', group: 'google' }, { k: 'website', l: 'Sitio Web', t: 'text', ph: 'https://...', group: 'google' }, { k: 'stars', l: 'Calificación', t: 'stars', group: 'google' }, { k: 'photo_url', l: 'URL de foto', t: 'text', ph: 'https://...', group: 'google' }, { k: 'checkin', l: 'Check-in', t: 'date' }, { k: 'checkout', l: 'Check-out', t: 'date' }, { k: 'habitacion', l: 'Tipo habitación', t: 'select', ph: 'Selecciona...', opts: ['Sencilla', 'Doble', 'Suite', 'Familiar'] }, { k: 'alimentacion', l: 'Alimentación', t: 'select', ph: 'Selecciona...', opts: ['Solo alojamiento', 'Desayuno incluido', 'Media pensión', 'Pensión completa', 'Todo incluido'] }, { k: 'reserva', l: 'Código reserva', t: 'text', ph: 'ALJ-12345' }, { k: 'adjunto', l: 'Archivo adjunto', t: 'file-upload', fw: true }, { k: 'precio', l: 'Precio', t: 'number', ph: '150' }, { k: 'notas', l: 'Notas', t: 'textarea', ph: 'Desayuno incluido...' }] },
  transporte: { icon: '<i class="fa-solid fa-car"></i>', label: 'Transporte', color: '#22c87a', bg: '#d1fae8', fields: [{ k: 'tipo', l: 'Tipo', t: 'select', opts: ['Auto de alquiler', 'Taxi/Uber', 'Tren', 'Bus', 'Ferry', 'Moto'] }, { k: 'proveedor', l: 'Proveedor', t: 'text', ph: 'Hertz, Renfe...' }, { k: 'origen', l: 'Desde', t: 'text', ph: 'Aeropuerto CDG' }, { k: 'destino', l: 'Hasta', t: 'text', ph: 'Hotel Centro' }, { k: 'salida', l: 'Salida', t: 'datetime-local' }, { k: 'llegada', l: 'Llegada', t: 'datetime-local' }, { k: 'precio', l: 'Precio', t: 'number', ph: '50' }, { k: 'reserva', l: 'Código reserva', t: 'text', ph: 'TRL-12345' }, { k: 'adjunto', l: 'Archivo adjunto', t: 'file-upload', fw: true }, { k: 'notas', l: 'Notas', t: 'textarea', ph: 'Confirmación...' }] },
  actividad: { icon: '<i class="fa-solid fa-bullseye"></i>', label: 'Actividad', color: '#f59e0b', bg: '#fef3c7', fields: [{ k: 'direccion', l: 'Lugar (Google Maps)', t: 'text', ph: 'Torre Eiffel, Museo del Louvre...', fw: true, hasInfo: true }, { k: 'phone', l: 'Teléfono', t: 'text', ph: '+1 234...', group: 'google' }, { k: 'website', l: 'Sitio Web', t: 'text', ph: 'https://...', group: 'google' }, { k: 'stars', l: 'Calificación', t: 'stars', group: 'google' }, { k: 'photo_url', l: 'URL de foto', t: 'text', ph: 'https://...', group: 'google' }, { k: 'nombre', l: 'Nombre actividad', t: 'text', ph: 'Cena con vista, Tour privado...', fw: true }, { k: 'fecha', l: 'Fecha y hora', t: 'datetime-local' }, { k: 'duracion', l: 'Duración', t: 'select', opts: ['1h', '2h', '3h', '4h', 'Medio día', 'Día completo'] }, { k: 'reserva', l: 'Código reserva', t: 'text', ph: 'ACT-12345' }, { k: 'adjunto', l: 'Archivo adjunto', t: 'file-upload', fw: true }, { k: 'precio', l: 'Precio', t: 'number', ph: '25' }, { k: 'descripcion', l: 'Descripción', t: 'textarea', ph: 'Descripción...' }, { k: 'notas', l: 'Notas', t: 'textarea', ph: 'Info adicional...' }] },
  comida: { icon: '<i class="fa-solid fa-utensils"></i>', label: 'Comida', color: '#f96b3a', bg: '#ffe8e0', fields: [{ k: 'restaurante', l: 'Restaurante', t: 'text', ph: 'Le Jules Verne', fw: true, hasInfo: true }, { k: 'direccion', l: 'Dirección', t: 'text', ph: 'Avenida...', group: 'google', fw: true }, { k: 'phone', l: 'Teléfono', t: 'text', ph: '+1 234...', group: 'google' }, { k: 'website', l: 'Sitio Web', t: 'text', ph: 'https://...', group: 'google' }, { k: 'stars', l: 'Calificación', t: 'stars', group: 'google' }, { k: 'photo_url', l: 'URL de foto', t: 'text', ph: 'https://...', group: 'google' }, { k: 'tipo', l: 'Tipo', t: 'select', opts: ['Desayuno', 'Almuerzo', 'Cena', 'Brunch', 'Snack'] }, { k: 'fecha', l: 'Fecha y hora', t: 'datetime-local' }, { k: 'reserva', l: 'Código reserva', t: 'text', ph: 'RES-12345' }, { k: 'adjunto', l: 'Archivo adjunto', t: 'file-upload', fw: true }, { k: 'precio', l: 'Precio', t: 'number', ph: '80' }, { k: 'notas', l: 'Notas', t: 'textarea', ph: 'Menú degustación...' }] },
  tour: { icon: '<i class="fa-solid fa-map-location-dot"></i>', label: 'Tour', color: '#8b5cf6', bg: '#f5f3ff', fields: [{ k: 'nombre', l: 'Nombre del tour', t: 'text', ph: 'Tour Versalles' }, { k: 'operador', l: 'Operador', t: 'text', ph: 'Get Your Guide' }, { k: 'fecha', l: 'Fecha y hora', t: 'datetime-local' }, { k: 'duracion', l: 'Duración', t: 'select', opts: ['2h', '4h', 'Medio día', 'Día completo', '2 días', '3+ días'] }, { k: 'personas', l: 'No. personas', t: 'text', ph: '2' }, { k: 'reserva', l: 'Código reserva', t: 'text', ph: 'TOU-12345' }, { k: 'adjunto', l: 'Archivo adjunto', t: 'file-upload', fw: true }, { k: 'precio', l: 'Precio', t: 'number', ph: '120' }, { k: 'photo_url', l: 'URL de foto', t: 'text', ph: 'https://...', fw: true }, { k: 'descripcion', l: 'Descripción', t: 'textarea', ph: 'Incluye entrada, guía...' }, { k: 'notas', l: 'Notas', t: 'textarea', ph: 'Info adicional...' }] },
  texto: { icon: '<i class="fa-solid fa-font"></i>', label: 'Caja de texto', color: '#64748b', bg: '#f1f5f9', fields: [{ k: 'contenido', l: 'Contenido', t: 'richtext', ph: 'Escribe aquí...' }, { k: 'alineacion', l: 'Alineación', t: 'select', opts: ['Izquierda', 'Centro', 'Derecha'] }] },
  titulo: { icon: '✦', label: 'Título', color: '#1a1a2e', bg: '#f0f1f7', fields: [{ k: 'texto', l: 'Texto del título', t: 'text', ph: 'Día 1 — Llegada a París' }, { k: 'subtitulo', l: 'Subtítulo (opcional)', t: 'text', ph: 'Una ciudad de luz...' }] },
  separador: { icon: '—', label: 'Separador', color: '#94a3b8', bg: '#f1f5f9', fields: [{ k: 'estilo', l: 'Estilo', t: 'select', opts: ['Línea simple', 'Línea con diamante', 'Punteado', 'Gradiente'] }, { k: 'etiqueta', l: 'Etiqueta (opcional)', t: 'text', ph: 'Mañana' }] },
  imagen: { icon: '<i class="fa-regular fa-image"></i>', label: 'Imagen', color: 'var(--primary-blue)', bg: '#e0f2fe', fields: [{ k: 'url', l: 'URL de imagen', t: 'text', ph: 'https://...' }, { k: 'caption', l: 'Pie de foto', t: 'text', ph: 'Torre Eiffel al atardecer' }, { k: 'tamano', l: 'Tamaño', t: 'select', opts: ['Pequeño', 'Mediano', 'Grande', 'Completo'] }] },
  gif: { icon: '<i class="fa-solid fa-bolt"></i>', label: 'GIF', color: '#ce3df3', bg: '#f9f0ff', fields: [{ k: 'url', l: 'URL del GIF', t: 'text', ph: 'https://...', fw: true }, { k: 'caption', l: 'Pie de GIF', t: 'text', ph: '¡Increíble!' }] },
  caja: { icon: '<i class="fa-solid fa-palette"></i>', label: 'Caja con fondo', color: '#22c87a', bg: '#d1fae8', fields: [{ k: 'titulo', l: 'Título', t: 'text', ph: 'Tip importante' }, { k: 'contenido', l: 'Contenido', t: 'textarea', ph: 'Información relevante...' }, { k: 'color_fondo', l: 'Color de fondo', t: 'color-picker', opts: ['var(--primary-blue)', '#f0567a', '#22c87a', '#f59e0b', '#0ea5d8', '#f96b3a'] }] },
  documents: { icon: '<i class="fa-solid fa-file-lines"></i>', label: 'Documentos', color: '#0ea5d8', bg: '#e0f7ff', fields: [{ k: 'documents_title', l: 'Título de la Sección', t: 'text', ph: 'Ej: Documentos de Viaje, Vouchers, etc.', fw: true }, { k: 'documents_description', l: 'Descripción o Instrucciones', t: 'textarea', ph: 'Ej: Aquí puedes descargar tus documentos importantes...', fw: true }, { k: 'documents', l: 'Documentos (Máx. 5 archivos, 5MB c/u)', t: 'multi-file-upload', fw: true }] }
};

// DRAG
const dragGhost = document.getElementById('dragGhost');
const canvasItems = document.getElementById('canvasItems');
const emptyState = document.getElementById('emptyState');
document.querySelectorAll('.element-card').forEach(card => {
  card.addEventListener('dragstart', e => { dragType = card.dataset.type; dragLabel = card.dataset.label; dragSourceIndex = null; card.classList.add('dragging'); e.dataTransfer.effectAllowed = 'copy'; e.dataTransfer.setDragImage(new Image(), 0, 0); const cfg = C[dragType]; document.getElementById('ghostIcon').textContent = cfg.icon; document.getElementById('ghostLabel').textContent = dragLabel; dragGhost.style.opacity = '1' });
  card.addEventListener('dragend', () => { card.classList.remove('dragging'); dragGhost.style.opacity = '0'; clearDropIndicators() });
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
        // Sidebar is persistent now, no need to toggle
      } else {
        showToast('⚠️', 'Selecciona un día primero');
      }
    }
  });
});
document.addEventListener('dragover', e => { e.preventDefault(); dragGhost.style.left = (e.clientX + 12) + 'px'; dragGhost.style.top = (e.clientY - 18) + 'px' });
canvasItems.addEventListener('dragover', e => { e.preventDefault(); e.stopPropagation(); if (dragSourceIndex !== null && dragSourceContainer === 'canvasItems') { const cl = getClosest([...canvasItems.querySelectorAll('.canvas-item')], e.clientY); showDropInd(cl.index, cl.before) } emptyState.classList.add('drag-over') });
canvasItems.addEventListener('dragleave', e => { if (!canvasItems.contains(e.relatedTarget)) { clearDropIndicators(); emptyState.classList.remove('drag-over') } });
emptyState.addEventListener('dragover', e => { e.preventDefault(); emptyState.classList.add('drag-over') });
emptyState.addEventListener('dragleave', () => emptyState.classList.remove('drag-over'));
emptyState.addEventListener('drop', e => { e.preventDefault(); emptyState.classList.remove('drag-over'); if (dragType && (typeof currentDay === 'number' || currentDay === 'portada' || currentDay === 'cierre')) openModal(dragType) });
canvasItems.addEventListener('drop', e => {
  e.preventDefault(); clearDropIndicators(); emptyState.classList.remove('drag-over');
  if (dragSourceIndex !== null && dragSourceContainer === 'canvasItems') { const items = [...canvasItems.querySelectorAll('.canvas-item')]; const cl = getClosest(items, e.clientY); let to = cl.before ? cl.index : cl.index + 1; if (to > dragSourceIndex) to--; const arr = days[currentDay]; const [moved] = arr.splice(dragSourceIndex, 1); arr.splice(to, 0, moved); renderCanvas(); dragSourceIndex = null; dragSourceContainer = null; return }
  if (dragType && (typeof currentDay === 'number' || currentDay === 'portada' || currentDay === 'cierre')) openModal(dragType);
});
function getClosest(items, y) { let minD = Infinity, index = items.length, before = false; items.forEach((item, i) => { const r = item.getBoundingClientRect(); const mid = r.top + r.height / 2; const d = Math.abs(y - mid); if (d < minD) { minD = d; index = i; before = y < mid } }); return { index, before } }
function showDropInd(index, before) { clearDropIndicators(); const items = [...canvasItems.querySelectorAll('.canvas-item')]; const ind = document.createElement('div'); ind.className = 'drop-indicator visible'; if (!items.length) canvasItems.appendChild(ind); else if (before && items[index]) canvasItems.insertBefore(ind, items[index]); else if (items[index]) items[index].insertAdjacentElement('afterend', ind); else canvasItems.appendChild(ind) }
function clearDropIndicators() { canvasItems.querySelectorAll('.drop-indicator').forEach(d => d.remove()) }

// Drag-drop + reorder for portada/cierre item containers
function setupContainerDrag(containerId) {
  const cont = document.getElementById(containerId);

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
    if (dragType) openModal(dragType);
  });
}
setupContainerDrag('portadaItems');
setupContainerDrag('cierreItems');
['portadaCanvas', 'cierreCanvas'].forEach(cid => {
  document.getElementById(cid).addEventListener('dragover', e => { e.preventDefault() });
  document.getElementById(cid).addEventListener('drop', e => { e.preventDefault(); if (dragType) openModal(dragType) });
});
['portadaDropHint', 'cierreDropHint'].forEach(hid => {
  const hEl = document.getElementById(hid);
  hEl.addEventListener('dragover', e => { e.preventDefault(); e.stopPropagation(); hEl.style.borderColor = 'var(--accent)'; hEl.style.background = 'var(--accent-light)'; });
  hEl.addEventListener('dragleave', () => { hEl.style.borderColor = 'var(--border)'; hEl.style.background = ''; });
  hEl.addEventListener('drop', e => { e.preventDefault(); e.stopPropagation(); hEl.style.borderColor = 'var(--border)'; hEl.style.background = ''; if (dragType) openModal(dragType); });
});

// Make entire canvas scrollable area a drop zone for regular days
const canvasEl = document.getElementById('canvas');
canvasEl.addEventListener('dragover', e => { e.preventDefault(); const hint = document.getElementById('dropHint'); if (hint && hint.style.display !== 'none') hint.style.borderColor = 'var(--accent)'; });
canvasEl.addEventListener('dragleave', e => { if (!canvasEl.contains(e.relatedTarget)) { const hint = document.getElementById('dropHint'); if (hint) hint.style.borderColor = 'var(--border)'; } });
canvasEl.addEventListener('drop', e => {
  // Only fire if not already handled by canvasItems
  if (e.target.closest('#canvasItems') || e.target.closest('#portadaCanvas') || e.target.closest('#cierreCanvas')) return;
  e.preventDefault();
  const hint = document.getElementById('dropHint'); if (hint) hint.style.borderColor = 'var(--border)';
  if (dragType && (typeof currentDay === 'number' || currentDay === 'portada' || currentDay === 'cierre')) openModal(dragType);
});

// dropHint itself is also a drop zone
const dropHintEl = document.getElementById('dropHint');
dropHintEl.addEventListener('dragover', e => { e.preventDefault(); e.stopPropagation(); dropHintEl.style.borderColor = 'var(--accent)'; dropHintEl.style.background = 'var(--accent-light)'; });
dropHintEl.addEventListener('dragleave', () => { dropHintEl.style.borderColor = 'var(--border)'; dropHintEl.style.background = ''; });
dropHintEl.addEventListener('drop', e => { e.preventDefault(); e.stopPropagation(); dropHintEl.style.borderColor = 'var(--border)'; dropHintEl.style.background = ''; if (dragType && typeof currentDay === 'number') openModal(dragType); });

// TABS
document.getElementById('dayTabs').addEventListener('click', e => {
  const tab = e.target.closest('.day-tab');
  if (!tab) return;
  if (e.target.classList.contains('day-tab-delete')) return;
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

document.getElementById('addDayBtn').addEventListener('click', () => {
  days.push([]); dayDates.push('');
  currentDay = days.length - 1;
  unsavedChanges = true;
  renderTabs();
  renderCanvas();
  autoSaveProTrip();
  showToast('📅', 'Día ' + days.length + ' agregado');
});

document.getElementById('addSectionBtn').addEventListener('click', e => { e.stopPropagation(); document.getElementById('sectionDropdown').classList.toggle('open') });
document.addEventListener('click', () => document.getElementById('sectionDropdown').classList.remove('open'));

function addSection(type) {
  document.getElementById('sectionDropdown').classList.remove('open');
  if (type === 'portada') {
    if (document.querySelector('.portada-tab')) return showToast('⚠️', 'La portada ya existe');
    currentDay = 'portada';
    renderTabs();
    renderCanvas(); showToast('🌅', 'Portada agregada');
  } else {
    if (document.querySelector('.cierre-tab')) return showToast('⚠️', 'El cierre ya existe');
    currentDay = 'cierre';
    renderTabs();
    renderCanvas(); showToast('✨', 'Cierre agregado');
  }
}

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
function confirmDeleteDay(dayIdx, e) {
  e && e.stopPropagation();
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

  // Portada
  if (document.querySelector('.portada-tab') || currentDay === 'portada' || (window.proState && window.proState.portadaPhotoUrl !== undefined)) { // Always show if we have data or it's active
    // Actually let's just show it if it's in the state as existing
  }

  // Re-render based on current state
  // This is a bit complex to do perfectly without a full reactive system, 
  // so let's just make sure the initial labs match the days array.

  let html = '';

  // Portada (always present for now to avoid complexity, or check if it was intended to be deleted)
  html += `<button class="day-tab portada-tab ${currentDay === 'portada' ? 'active' : ''}" data-day="portada"><span class="day-tab-label"><i class="fa-solid fa-sun" style="margin-right:4px"></i> Portada</span></button>`;

  // Days
  days.forEach((_, i) => {
    const dateStr = dayDates[i] ? fmtDateTab(dayDates[i]) : ('Día ' + (i + 1));
    html += `<button class="day-tab ${currentDay === i ? 'active' : ''}" data-day="${i}" draggable="true" style="cursor:grab">
      <span class="day-tab-label"><span style="display:inline-flex; gap:1px; margin-right:7px; opacity:0.4; font-size:10px;"><i class="fa-solid fa-ellipsis-vertical"></i><i class="fa-solid fa-ellipsis-vertical"></i></span>${dateStr}</span>
      <span class="day-tab-delete" onclick="confirmDeleteDay(${i},event)" title="Eliminar día"><i class="fa-solid fa-times"></i></span>
    </button>`;
  });

  // Cierre
  html += `<button class="day-tab cierre-tab ${currentDay === 'cierre' ? 'active' : ''}" data-day="cierre"><span class="day-tab-label"><i class="fa-solid fa-moon" style="margin-right:4px"></i> Cierre</span></button>`;

  container.innerHTML = html;

  // Important: Ad-hoc drop listeners for all tabs to support moving items
  container.querySelectorAll('.day-tab').forEach(tab => {
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
  const items = days[currentDay] || [];
  canvasItems.innerHTML = '';
  emptyState.classList.toggle('hidden', items.length > 0);
  document.getElementById('dropHint').style.display = items.length > 0 ? 'block' : 'none';

  items.forEach((item, idx) => canvasItems.appendChild(buildItem(item, idx)));
}

function buildItem(item, idx) {
  const cfg = C[item.type]; const el = document.createElement('div');
  el.className = `canvas-item tipo-${item.type}`; el.dataset.index = idx;
  if (item.type === 'separador') { const lbl = item.data.etiqueta || ''; el.innerHTML = `<div class="item-inner"><div class="sep-line"></div>${lbl ? `<span class="sep-dot"></span><span style="font-size:11px;color:var(--text-dim);white-space:nowrap">${lbl}</span><span class="sep-dot"></span>` : '<span class="sep-dot"></span>'}<div class="sep-line"></div><div class="item-actions" style="margin-left:8px"><button class="item-action-btn" onclick="editItem(${idx})"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button><button class="item-action-btn" onclick="duplicateItem(${idx})" title="Duplicar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button><button class="item-action-btn delete" onclick="deleteItem(${idx})"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg></button></div></div>`; setupReorder(el, idx); return el }
  if (item.type === 'titulo') { el.style.position = 'relative'; el.innerHTML = `<div class="item-inner" style="flex-direction:column;gap:3px;padding:18px 20px"><div class="titulo-text">${item.data.texto || 'Título'}</div>${item.data.subtitulo ? `<div style="font-size:13px;color:var(--text-muted)">${item.data.subtitulo}</div>` : ''}</div><div class="item-actions" style="position:absolute;right:12px;top:12px;opacity:0;transition:opacity .18s"><button class="item-action-btn" onclick="editItem(${idx})"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button><button class="item-action-btn" onclick="duplicateItem(${idx})" title="Duplicar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button><button class="item-action-btn delete" onclick="deleteItem(${idx})"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg></button></div>`; el.addEventListener('mouseenter', () => el.querySelector('.item-actions').style.opacity = '1'); el.addEventListener('mouseleave', () => el.querySelector('.item-actions').style.opacity = '0'); setupReorder(el, idx); return el }
  if (item.type === 'texto') { el.style.position = 'relative'; el.innerHTML = `<div class="item-inner" style="flex-direction:column;gap:5px;padding:14px 16px"><div class="texto-content" style="text-align:${(item.data.alineacion || 'Izquierda').toLowerCase()}">${item.data.contenido || 'Texto...'}</div></div><div class="item-actions" style="position:absolute;right:12px;top:12px;opacity:0;transition:opacity .18s"><button class="item-action-btn" onclick="editItem(${idx})"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button><button class="item-action-btn" onclick="duplicateItem(${idx})" title="Duplicar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button><button class="item-action-btn delete" onclick="deleteItem(${idx})"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg></button></div>`; el.addEventListener('mouseenter', () => el.querySelector('.item-actions').style.opacity = '1'); el.addEventListener('mouseleave', () => el.querySelector('.item-actions').style.opacity = '0'); setupReorder(el, idx); return el }
  if (item.type === 'imagen') { const hasImg = item.data.url && item.data.url.startsWith('http'); el.style.position = 'relative'; el.innerHTML = `<div class="item-inner" style="flex-direction:column;gap:9px;padding:11px"><div class="imagen-preview">${hasImg ? `<img src="${item.data.url}" alt="">` : '🖼️'}</div>${item.data.caption ? `<div style="font-size:12px;color:var(--text-muted);text-align:center">${item.data.caption}</div>` : ''}</div><div class="item-actions" style="position:absolute;right:12px;top:12px;opacity:0;transition:opacity .18s"><button class="item-action-btn" onclick="editItem(${idx})"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button><button class="item-action-btn" onclick="duplicateItem(${idx})" title="Duplicar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button><button class="item-action-btn delete" onclick="deleteItem(${idx})"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg></button></div>`; el.addEventListener('mouseenter', () => el.querySelector('.item-actions').style.opacity = '1'); el.addEventListener('mouseleave', () => el.querySelector('.item-actions').style.opacity = '0'); setupReorder(el, idx); return el }
  if (item.type === 'caja') {
    const bg = item.data.color_fondo || '#7c6fef';
    el.style.background = bg + '12'; el.style.borderColor = bg + '40';
    el.innerHTML = `<div class="item-inner" style="gap:11px"><div style="flex:1"><div class="item-title">${item.data.titulo || 'Caja con fondo'}</div><div class="texto-content" style="margin-top:3px">${item.data.contenido || ''}</div></div><div class="item-actions"><button class="item-action-btn" onclick="editItem(${idx})"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button><button class="item-action-btn" onclick="duplicateItem(${idx})" title="Duplicar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button><button class="item-action-btn delete" onclick="deleteItem(${idx})"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg></button></div></div>`;
    setupReorder(el, idx); return el
  }
  if (item.type === 'gif') {
    const hasImg = item.data.url && item.data.url.startsWith('http');
    el.style.position = 'relative';
    el.innerHTML = `<div class="item-inner" style="flex-direction:column;gap:9px;padding:11px">
      <div class="imagen-preview">${hasImg ? `<img src="${item.data.url}" alt="">` : '<i class="fa-solid fa-bolt"></i>'}</div>
      ${item.data.caption ? `<div style="font-size:12px;color:var(--text-muted);text-align:center">${item.data.caption}</div>` : ''}
    </div>
    <div class="item-actions" style="position:absolute;right:12px;top:12px;opacity:0;transition:opacity .18s">
      <button class="item-action-btn" onclick="editItem(${idx})"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
      <button class="item-action-btn" onclick="duplicateItem(${idx})" title="Duplicar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button>
      <button class="item-action-btn delete" onclick="deleteItem(${idx})"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg></button>
    </div>`;
    el.addEventListener('mouseenter', () => el.querySelector('.item-actions').style.opacity = '1');
    el.addEventListener('mouseleave', () => el.querySelector('.item-actions').style.opacity = '0');
    setupReorder(el, idx); return el;
  }
  const d = item.data; let title = '', chips = [], sub = [];
  switch (item.type) {
    case 'flight':
      const getCity = str => str ? (str.includes('(') ? str.split('(')[0].trim() : str.split(' -')[0].trim()) : '';
      title = (d.origen && d.destino) ? `${d.origen_city || getCity(d.origen)} → ${d.destino_city || getCity(d.destino)}` : 'Vuelo';
      if (d.salida) sub.push('<i class="fa-solid fa-plane-departure"></i> ' + fmtDT(d.salida));
      if (d.llegada) sub.push('<i class="fa-solid fa-plane-arrival"></i> ' + fmtDT(d.llegada));
      if (d.aerolinea) chips.push(d.aerolinea);
      if (d.vuelo) chips.push(d.vuelo);
      if (d.clase) chips.push(d.clase);
      if (d.precio) chips.push('$' + d.precio);
      break;
    case 'alojamiento':
      title = d.nombre || 'Alojamiento';
      if (d.direccion) sub.push('<i class="fa-solid fa-location-dot"></i> ' + d.direccion);
      if (d.checkin) chips.push('<i class="fa-solid fa-right-to-bracket"></i> ' + fmtDT(d.checkin.includes('T') ? d.checkin : d.checkin + 'T15:00:00'));
      if (d.checkout) chips.push('<i class="fa-solid fa-right-from-bracket"></i> ' + fmtDT(d.checkout.includes('T') ? d.checkout : d.checkout + 'T12:00:00'));
      if (d.habitacion) chips.push('<i class="fa-solid fa-bed"></i> ' + d.habitacion);
      if (d.alimentacion) chips.push('<i class="fa-solid fa-utensils"></i> ' + d.alimentacion);
      if (d.precio) chips.push('$' + d.precio);
      break;
    case 'transporte':
      title = (d.origen && d.destino) ? `${d.origen} → ${d.destino}` : (d.tipo || 'Transporte');
      if (d.tipo) sub.push('<i class="fa-solid fa-car"></i> ' + d.tipo);
      if (d.salida) sub.push('<i class="fa-solid fa-clock"></i> ' + fmtDT(d.salida));
      if (d.llegada) sub.push('<i class="fa-regular fa-clock"></i> ' + fmtDT(d.llegada));
      if (d.proveedor) chips.push(d.proveedor);
      if (d.precio) chips.push('$' + d.precio);
      break;
    case 'actividad':
      title = d.nombre || 'Actividad';
      if (d.direccion) sub.push('<i class="fa-solid fa-location-dot"></i> ' + d.direccion);
      if (d.fecha) sub.push('<i class="fa-regular fa-clock"></i> ' + fmtDT(d.fecha));
      if (d.duracion) chips.push('<i class="fa-solid fa-stopwatch"></i> ' + d.duracion);
      if (d.precio) chips.push('$' + d.precio);
      break;
    case 'comida':
      title = d.restaurante || 'Comida';
      if (d.direccion) sub.push('<i class="fa-solid fa-location-dot"></i> ' + d.direccion);
      if (d.fecha) sub.push('<i class="fa-regular fa-clock"></i> ' + fmtDT(d.fecha));
      if (d.tipo) chips.push('<i class="fa-solid fa-utensils"></i> ' + d.tipo);
      if (d.precio) chips.push('$' + d.precio);
      break;
    case 'tour':
      title = d.nombre || 'Tour';
      if (d.operador) sub.push('<i class="fa-solid fa-building"></i> ' + d.operador);
      if (d.fecha) sub.push('<i class="fa-regular fa-clock"></i> ' + fmtDT(d.fecha));
      if (d.duracion) chips.push('<i class="fa-solid fa-stopwatch"></i> ' + d.duracion);
      if (d.personas) chips.push('<i class="fa-solid fa-users"></i> ' + d.personas);
      if (d.precio) chips.push('$' + d.precio);
      break;
    case 'documents':
      title = d.documents_title || 'Documentos';
      if (d.documents_description) sub.push(d.documents_description);
      const docs = d.documents ? (typeof d.documents === 'string' ? JSON.parse(d.documents) : d.documents) : [];
      if (docs.length > 0) {
        chips.push(`<i class="fa-solid fa-paperclip"></i> ${docs.length} archivo(s)`);
      }
      break;
  }
  if (d.adjunto) chips.push('<i class="fa-solid fa-paperclip"></i> 1');
  el.innerHTML = `<div class="item-inner"><div class="item-accent-bar" style="background:${cfg.color}"></div><div class="item-icon" style="background:${cfg.bg}">${cfg.icon}</div><div class="item-content"><div class="item-type-label" style="color:${cfg.color}">${cfg.label}</div><div class="item-title">${title}</div><div class="item-subtitle" style="display:flex; flex-direction:column; align-items:flex-start; gap:6px;">${sub.length ? `<div style="display:flex; flex-wrap:wrap; align-items:center; justify-content:flex-start; gap:8px;">${sub.map(s => `<span>${s}</span>`).join('')}</div>` : ''}${chips.length ? `<div style="display:flex; flex-wrap:wrap; align-items:center; justify-content:flex-start; gap:8px;">${chips.map(c => `<span class="item-chip">${c}</span>`).join('')}</div>` : ''}</div>${d.notas ? `<div style="font-size:12px;color:var(--text-muted);margin-top:6px;display:flex;align-items:center;justify-content:flex-start;gap:4px;"><i class="fa-solid fa-circle-info" style="font-size:10px;opacity:0.7"></i> ${d.notas}</div>` : ''}</div><div class="item-actions"><button class="item-action-btn" onclick="editItem(${idx})"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button><button class="item-action-btn" onclick="duplicateItem(${idx})" title="Duplicar"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button><button class="item-action-btn delete" onclick="deleteItem(${idx})"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg></button></div></div>`;
  setupReorder(el, idx); return el;
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
function fmtDT(s) { if (!s) return ''; try { const d = new Date(s); return d.toLocaleDateString('es', { day: '2-digit', month: 'short' }) + ' ' + d.toLocaleTimeString('es', { hour: '2-digit', minute: '2-digit' }) } catch { return s } }
function formatNumber(val) {
  if (!val && val !== 0) return '';
  const parts = val.toString().split('.');
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  return parts.join(',');
}
function unformatNumber(val) {
  if (!val) return '';
  return val.toString().replace(/\./g, '').replace(/,/g, '.');
}
function fmtDate(s) { if (!s) return ''; try { return new Date(s + 'T00:00:00').toLocaleDateString('es', { day: 'numeric', month: 'long', year: 'numeric' }) } catch { return s } }
function fmtDateTab(s) { if (!s) return ''; try { const d = new Date(s + 'T00:00:00'); return d.toLocaleDateString('es', { day: 'numeric', month: 'short' }) } catch { return s } }
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

  const isPremium = typeof window.viantrypUserPlan !== 'undefined' && window.viantrypUserPlan !== 'básico';

  if (showHelp) {
    const helpText = document.createElement('div');
    helpText.style = 'margin-top:5px; margin-bottom:8px; font-size:12px; color:var(--text-muted); font-style:italic; line-height:1.4;';

    helpText.textContent = isPremium
      ? 'Si no deseas usar la imagen predeterminada de Google Maps, puedes usar una imagen de Unsplash o subir la tuya.'
      : 'Tu plan no incluye foto automática: completa la URL eligiendo una imagen desde Unsplash o subiendo una propia.';

    container.appendChild(helpText);
  }

  const btnGroup = document.createElement('div');
  btnGroup.style = 'display:flex; gap:10px; margin-bottom:20px;';

  if (!photoInp) photoInp = container.querySelector('input[data-key="photo_url"]');

  const uBtn = document.createElement('button');
  uBtn.className = 'btn-secondary';
  uBtn.type = 'button';
  uBtn.style = 'flex:1; font-size:12px; height:34px; display:flex; align-items:center; justify-content:center; gap:8px;';
  uBtn.innerHTML = '<i class="fa-brands fa-unsplash"></i> Unsplash';
  uBtn.onclick = () => openUnsplash('item_photo', photoInp);

  const upBtn = document.createElement('button');
  upBtn.className = 'btn-secondary';
  upBtn.type = 'button';
  upBtn.style = 'flex:1; font-size:12px; height:34px; display:flex; align-items:center; justify-content:center; gap:8px;';
  upBtn.innerHTML = '<i class="fa-solid fa-upload"></i> Subir foto';
  const fileInp = document.createElement('input');
  fileInp.type = 'file'; fileInp.accept = 'image/*'; fileInp.style.display = 'none';
  fileInp.onchange = (e) => handleItemPhotoUpload(e, photoInp);
  upBtn.onclick = () => fileInp.click();

  btnGroup.appendChild(uBtn);
  btnGroup.appendChild(upBtn);

  if (appendToContainer) {
    container.appendChild(btnGroup);
  }
  return btnGroup;
}
function openModal(type, editIdx = null) {
  if (typeof currentDay !== 'number' && currentDay !== 'portada' && currentDay !== 'cierre') return;

  const isPremium = typeof window.viantrypUserPlan !== 'undefined' && window.viantrypUserPlan !== 'básico';

  // Phase 36: Direct Unsplash for new images
  if (type === 'imagen' && editIdx === null) {
    openUnsplash('canvas');
    return;
  }
  // Phase 38: Direct Giphy for new GIFs
  if (type === 'gif' && editIdx === null) {
    openGiphy('canvas');
    return;
  }

  pendingType = type; editingIndex = editIdx; starRating = 0;
  const arr = currentDay === 'portada' ? portadaItems : currentDay === 'cierre' ? cierreItems : days[currentDay];
  const cfg = C[type]; const existData = editIdx !== null ? arr[editIdx].data : {};
  document.getElementById('modalIcon').innerHTML = cfg.icon; document.getElementById('modalIcon').style.background = cfg.bg;
  document.getElementById('modalTitle').textContent = (editIdx !== null ? 'Editar ' : 'Agregar ') + cfg.label;
  document.getElementById('modalSubtitle').textContent = editIdx !== null ? 'Modifica los datos' : 'Completa los datos del elemento';
  modalBody.innerHTML = '';
  const fields = cfg.fields;
  let currentGroup = null;
  let currentTarget = modalBody;

  for (let i = 0; i < fields.length; i++) {
    const f = fields[i];

    if (f.group === 'google' && currentGroup !== 'google') {
      const gbox = document.createElement('div');
      gbox.className = 'field-group-box';

      if (!isPremium) {
        let helpText = 'Si tienes un <strong>plan esencial o superior</strong>, estos campos se rellenarán automáticamente con Google Maps.';
        if (type === 'alojamiento') {
          helpText = 'Si tienes un <strong>plan esencial o superior</strong>, estos campos se rellenarán automáticamente con Google Maps al escribir el nombre del hotel.';
        } else if (type === 'actividad') {
          helpText = 'Si tienes un <strong>plan esencial o superior</strong>, estos campos se rellenarán automáticamente con Google Maps al escribir el lugar de la actividad.';
        } else if (type === 'comida') {
          helpText = 'Si tienes un <strong>plan esencial o superior</strong>, estos campos se rellenarán automáticamente con Google Maps al escribir el nombre del restaurante.';
        }
        gbox.appendChild(createInfoSpan(helpText, true));
      }

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
    if (f.t === 'textarea' || f.t === 'color-picker' || f.t === 'richtext' || f.t === 'stars' || f.fw) {
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

      // Premium info icons
      if (isPremium && f.hasInfo) {
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
    } else if (next && !next.fw && next.t !== 'textarea' && next.t !== 'color-picker' && next.t !== 'richtext' && next.t !== 'stars' && next.group === f.group) {
      const row = document.createElement('div');
      row.className = 'form-row';

      const f1 = buildField(f, existData);
      if (isPremium && f.hasInfo) {
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
      if (isPremium && next.hasInfo) {
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
      if (isPremium && f.hasInfo) {
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

  setTimeout(() => {
    const f = modalBody.querySelector('input,textarea,select'); if (f) f.focus();

    // Google Places Autocomplete API
    const googleTypes = ['alojamiento', 'actividad', 'comida'];
    if (googleTypes.includes(type) && window.google && window.google.maps && window.google.maps.places) {
      const keyMap = { alojamiento: 'nombre', actividad: 'direccion', comida: 'restaurante' };
      const nameInp = modalBody.querySelector('input[data-key="' + keyMap[type] + '"]');
      if (nameInp) {
        const autocomplete = new window.google.maps.places.Autocomplete(nameInp, { types: ['establishment'] });

        // Fix z-index for pac-container
        nameInp.addEventListener('input', () => { setTimeout(() => { document.querySelectorAll('.pac-container').forEach(c => { c.style.zIndex = '1000000'; c.style.position = 'fixed'; const rect = nameInp.getBoundingClientRect(); c.style.top = rect.bottom + 'px'; c.style.left = rect.left + 'px'; c.style.width = rect.width + 'px'; }); }, 50); });

        autocomplete.addListener('place_changed', () => {
          const place = autocomplete.getPlace();
          if (!place || !place.place_id) return;

          if (place.name) nameInp.value = place.name;

          const setVal = (k, v) => { const el = modalBody.querySelector('input[data-key="' + k + '"]'); if (el) { el.value = v; } };
          if (place.formatted_address && type !== 'actividad') setVal('direccion', place.formatted_address);
          if (place.formatted_phone_number) setVal('phone', place.formatted_phone_number);
          if (place.website) setVal('website', place.website);

          // Fetch permanent photo URLs from our server
          fetch(`/api/places/details?place_id=${place.place_id}`)
            .then(res => res.json())
            .then(data => {
              if (data.photos && data.photos.length > 0) {
                const urls = data.photos.slice(0, 3).map(p => p.url).join(',');
                setVal('photo_url', urls);
              }
            })
            .catch(err => console.error('Error fetching place details:', err));

          if (place.rating) {
            starRating = place.rating;
            const sr = modalBody.querySelector('.star-rating');
            if (sr) {
              const rounded = Math.round(starRating);
              sr.querySelectorAll('.star').forEach((st, idx) => st.classList.toggle('active', idx < rounded));
              const hid = sr.querySelector('input[type="hidden"]');
              if (hid) hid.value = rounded;
            }
          }
        });
      }
    }
  }, 220);
}
function handleItemPhotoUpload(e, targetInp) {
  const f = e.target.files[0];
  if (!f) return;
  if (f.size > 5 * 1024 * 1024) { showToast('⚠️', 'La imagen no puede superar 5MB'); return; }

  const originalContent = targetInp.value;

  // If there was a previous uploaded file, delete it to free quota
  const getDocIdFromUrl = (url) => {
    if (!url) return null;
    const match = url.match(/\/documents\/(\d+)\/download/);
    return match ? match[1] : null;
  };
  const prevDocId = getDocIdFromUrl(originalContent);
  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  if (prevDocId) {
    fetch(`/documents/${prevDocId}`, {
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
    }).catch(e => console.error('Error deleting previous photo:', e));
  }

  targetInp.value = 'Subiendo...';
  targetInp.disabled = true;

  const formData = new FormData();
  formData.append('file', f);

  const tripId = window.tripId;
  fetch(`/trips/${tripId}/upload-attachment`, {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
    body: formData
  })
    .then(res => res.json())
    .then(res => {
      targetInp.disabled = false;
      if (res.success) {
        targetInp.value = res.url;
        showToast('✅', 'Foto subida');
      } else if (res.error_code === 'LIMIT_REACHED') {
        targetInp.value = originalContent;
        if (typeof openUpgradeModal === 'function') {
          openUpgradeModal();
        } else {
          showToast('⚠️', res.message || 'Límite alcanzado');
        }
      } else {
        targetInp.value = originalContent;
        showToast('⚠️', res.message || 'Error al subir');
      }
    })
    .catch(err => {
      targetInp.disabled = false;
      targetInp.value = originalContent;
      console.error(err);
      showToast('⚠️', 'Error de conexión');
    });
}
function buildField(field, data) {
  const fg = document.createElement('div'); fg.className = 'form-group';
  const lbl = document.createElement('label'); lbl.className = 'form-label';
  lbl.textContent = field.l;
  fg.appendChild(lbl);

  const val = data[field.k] || '';

  if (field.t === 'stars') {
    const sr = document.createElement('div');
    sr.className = 'star-rating';
    const init = parseInt(val) || 0;

    // Hidden input to store value for saveElement
    const hid = document.createElement('input');
    hid.type = 'hidden';
    hid.dataset.key = field.k;
    hid.value = init;
    sr.appendChild(hid);

    for (let s = 1; s <= 5; s++) {
      const star = document.createElement('span');
      star.className = 'star' + (s <= init ? ' active' : '');
      star.textContent = '★';
      star.dataset.val = s;
      star.addEventListener('click', () => {
        const rating = parseInt(star.dataset.val);
        hid.value = rating;
        sr.querySelectorAll('.star').forEach((st, idx) => st.classList.toggle('active', idx < rating));
      });
      sr.appendChild(star);
    }
    fg.appendChild(sr);
  }
  else if (field.t === 'textarea') { const ta = document.createElement('textarea'); ta.className = 'form-textarea'; ta.placeholder = field.ph || ''; ta.value = val; ta.dataset.key = field.k; fg.appendChild(ta) }
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
      <div class="rte-editor" contenteditable="true" data-key="${field.k}">${val || ''}</div>
    `;
    fg.appendChild(wrap);
  }
  else if (field.t === 'select') { const sel = document.createElement('select'); sel.className = 'form-select'; sel.dataset.key = field.k; if (field.ph) { const op = document.createElement('option'); op.value = ''; op.textContent = field.ph; op.selected = !val; sel.appendChild(op); } field.opts.forEach(opt => { const o = document.createElement('option'); o.value = opt; o.textContent = opt; if (opt === val) o.selected = true; sel.appendChild(o) }); fg.appendChild(sel) }
  else if (field.t === 'color-picker') { const row = document.createElement('div'); row.className = 'color-row'; field.opts.forEach((color, ci) => { const sw = document.createElement('div'); sw.className = 'color-swatch' + (data[field.k] === color || (!data[field.k] && ci === 0) ? ' selected' : ''); sw.style.background = color; sw.dataset.color = color; sw.dataset.key = field.k; sw.addEventListener('click', () => { row.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('selected')); sw.classList.add('selected') }); row.appendChild(sw) }); fg.appendChild(row) }
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
        .then(res => res.json())
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
        }).then(res => res.json());
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
    const inp = document.createElement('input'); inp.className = 'form-input'; inp.type = field.t || 'text'; inp.placeholder = field.ph || ''; inp.value = val; inp.dataset.key = field.k; fg.appendChild(inp);

    if (field.airportApi || field.airlineApi) {
      const drop = document.createElement('div'); drop.className = 'api-autocomplete-drop'; drop.style = 'position:absolute; background:#fff; border:1px solid #ccc; border-radius:4px; max-height:200px; overflow-y:auto; z-index:100; display:none; width:100%; box-shadow:0 4px 6px rgba(0,0,0,0.1); margin-top:2px;';
      fg.style.position = 'relative';
      fg.appendChild(drop);
      let timeout;
      inp.addEventListener('input', e => {
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
function closeModal() { modalOverlay.classList.remove('open'); editingIndex = null }
document.getElementById('modalClose').addEventListener('click', closeModal);
document.getElementById('modalCancel').addEventListener('click', closeModal);
// modalOverlay.addEventListener('click', e => { if (e.target === modalOverlay) closeModal() });
document.getElementById('modalSave').addEventListener('click', () => {
  const data = {};
  modalBody.querySelectorAll('[data-key]').forEach(el => {
    if (el.classList.contains('rte-editor')) data[el.dataset.key] = el.innerHTML;
    else data[el.dataset.key] = el.value;
    if (el.dataset.city) data[el.dataset.key + '_city'] = el.dataset.city;
  });
  modalBody.querySelectorAll('.color-swatch.selected').forEach(sw => { data[sw.dataset.key] = sw.dataset.color });
  if (starRating > 0) data.stars = starRating;
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
// VISTA PREVIA — genera HTML y lo abre en nueva pestaña
// ============================================================
function openPreview() {
  const title = document.getElementById('portadaTitle').value || document.getElementById('itineraryNameInput').value || 'Mi Itinerario';
  const fechaInicio = document.getElementById('portadaFechaInicio').value;
  const fechaFin = document.getElementById('portadaFechaFin').value;
  const precio = unformatNumber(document.getElementById('portadaPrecio').value);
  const moneda = document.getElementById('portadaMoneda').value;
  const totalViajeros = portadaAdultos + portadaNinos;
  const hasPortada = !!document.querySelector('.day-tab.portada-tab');
  const hasCierre = !!document.querySelector('.day-tab.cierre-tab');
  const closureCard = document.getElementById('cierreCardMain');
  const showDefaultCierre = closureCard && closureCard.style.display !== 'none';
  const totalItems = days.reduce((s, d) => s + (d ? d.length : 0), 0);

  // Build day tabs info
  const numericTabs = [...document.querySelectorAll('.day-tab:not(.portada-tab):not(.cierre-tab)')].map(t => ({ label: t.querySelector('.day-tab-label')?.textContent || t.textContent.trim(), idx: parseInt(t.dataset.day) }));

  // Build preview HTML
  const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';
  const previewHTML = buildPreviewHTML({
    title, fechaInicio, fechaFin, precio, moneda, totalViajeros, hasPortada, hasCierre, showDefaultCierre, totalItems, numericTabs, days, dayDates, portadaAdultos, portadaNinos, portadaPhotoUrl, portadaItems, cierreItems,
    isPublicLink: false,
    csrfToken: csrfToken,
    tripId: window.tripId || '',
    userName: window.viantrypUserName || '',
    origin: window.location.origin,
    status: window.proStatus,
    themeColor: window.viantrypThemeColor || 'default',
    displayNameType: window.viantrypDisplayNameType || 'personal',
    agencyLogo: window.viantrypAgencyLogo || '',
    agencyName: window.viantrypAgencyName || '',
    userFullName: window.viantrypUserFullName || ''
  });
  const blob = new Blob([previewHTML], { type: 'text/html' });
  const url = URL.createObjectURL(blob);
  window.open(url, '_blank');
  showToast('<i class="fa-regular fa-eye"></i>', 'Vista previa abierta');
}

// buildPreviewHTML() has been moved to pro-viewer.js

// Initial Load
if (window.proState) {
  const s = window.proState;
  if (s.days) days = s.days;
  if (s.dayDates) dayDates = s.dayDates;
  if (s.portadaItems) portadaItems = s.portadaItems;
  if (s.cierreItems) cierreItems = s.cierreItems;
  if (s.portadaAdultos !== undefined) portadaAdultos = s.portadaAdultos;
  if (s.portadaNinos !== undefined) portadaNinos = s.portadaNinos;
  if (s.portadaPhotoUrl !== undefined) portadaPhotoUrl = s.portadaPhotoUrl;

  // Ensure days array has at least 1 day if empty, or match numeric tabs
  if (!days || days.length === 0) days = [[]];
  if (!dayDates || dayDates.length === 0) dayDates = [''];

  // Adjust day counts
  numericDayCount = days.length;
  dayCount = numericDayCount;
  nextDayNumber = dayCount + 1;

  // Set UI elements
  document.addEventListener('DOMContentLoaded', () => {
    if (portadaPhotoUrl) setPortadaPhoto(portadaPhotoUrl);
    document.getElementById('portadaAdultos').textContent = portadaAdultos;
    document.getElementById('portadaNinos').textContent = portadaNinos;
    document.getElementById('portadaTotal').textContent = portadaAdultos + portadaNinos;

    const pi = document.getElementById('portadaFechaInicio');
    if (pi && s.fechaInicio) pi.value = s.fechaInicio;
    const pf = document.getElementById('portadaFechaFin');
    if (pf && s.fechaFin) pf.value = s.fechaFin;
    const pp = document.getElementById('portadaPrecio');
    if (pp && s.precio) pp.value = formatNumber(s.precio);
    const pm = document.getElementById('portadaMoneda');
    if (pm && s.moneda) pm.value = s.moneda;

    const titleInp = document.getElementById('portadaTitle');
    if (titleInp && s.title) titleInp.value = s.title;

    // Listeners para inputs de cabecera
    const headerInputs = [pi, pf, pp, pm, titleInp];
    headerInputs.forEach(inp => {
      if (inp) inp.addEventListener('input', () => autoSaveProTrip());
    });

    renderTabs();
    renderCanvas();
  });
} else {
  document.addEventListener('DOMContentLoaded', () => {
    renderTabs();
    renderCanvas();
  });
}

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

  const proStateObj = { title, fechaInicio, fechaFin, precio, moneda, totalViajeros, hasPortada, hasCierre, showDefaultCierre, totalItems, numericTabs, days, dayDates, portadaAdultos, portadaNinos, portadaPhotoUrl, portadaItems, cierreItems, isPublicLink: false, status: window.proStatus, origin: window.location.origin };

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

    if (response.ok) {
      unsavedChanges = false;
      console.log('Viaje PRO guardado correctamente.');
      showToast('✅', '¡Viaje guardado correctamente!');
      return true;
    } else {
      console.error('Error al guardar el viaje PRO');
      if (!isSilent) showToast('❌', 'Error al guardar el viaje');
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
