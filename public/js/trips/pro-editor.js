// STATE
let days = [[], [], []];
let dayDates = ['', '', '']; // per-day date strings (yyyy-mm-dd)
let portadaItems = [];
let cierreItems = [];
let currentDay = 'portada';
let dayCount = 3, numericDayCount = 3, nextDayNumber = 4; // nextDayNumber always increments up
let dragType = null, dragLabel = null, dragSourceIndex = null, dragSourceContainer = null;
let pendingType = null, editingIndex = null, starRating = 0;
let portadaAdultos = 2, portadaNinos = 0;
let portadaPhotoUrl = '';
const GIPHY_API_KEY = 'ga2U6DfG1RcG9EESPkiPph7sMM0uhrdy';
let selectedUnsplashUrl = null, unsplashTarget = 'portada';
let selectedGiphyUrl = null, giphyTarget = 'canvas';
let confirmCallback = null;

// PORTADA
function changePortadaCount(type, d) {
  if (type === 'adultos') { portadaAdultos = Math.max(0, portadaAdultos + d); document.getElementById('portadaAdultos').textContent = portadaAdultos }
  else { portadaNinos = Math.max(0, portadaNinos + d); document.getElementById('portadaNinos').textContent = portadaNinos }
  document.getElementById('portadaTotal').textContent = portadaAdultos + portadaNinos;
}
function handlePortadaUpload(e) { const f = e.target.files[0]; if (!f) return; const r = new FileReader(); r.onload = ev => { portadaPhotoUrl = ev.target.result; setPortadaPhoto(ev.target.result) }; r.readAsDataURL(f) }
function setPortadaPhoto(url) {
  portadaPhotoUrl = url;
  const img = document.getElementById('portadaHeroImg');
  img.src = url; img.classList.add('visible'); document.getElementById('portadaHero').classList.add('has-image');
}
function clearPortadaPhoto(e) {
  e && e.stopPropagation(); portadaPhotoUrl = '';
  const img = document.getElementById('portadaHeroImg');
  img.src = ''; img.classList.remove('visible'); document.getElementById('portadaHero').classList.remove('has-image');
}

// UNSPLASH
function openUnsplash(target = 'portada') {
  unsplashTarget = target;
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
    } else if (unsplashTarget === 'tour') {
      const inp = modalBody.querySelector('input[data-key="photo_url"]');
      if (inp) {
        inp.value = selectedUnsplashUrl;
        showToast('📸', 'Foto de tour aplicada');
      }
    }
  }
  closeUnsplash();
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
}

document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('giphySearch')?.addEventListener('keydown', e => { if (e.key === 'Enter') searchGiphy() });
  document.getElementById('unsplashSearch')?.addEventListener('keydown', e => { if (e.key === 'Enter') searchUnsplash() });
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
  flight: { icon: '<i class="fa-solid fa-plane"></i>', label: 'Vuelo', color: 'var(--primary-blue)', bg: '#e0f2fe', fields: [{ k: 'origen', l: 'Ciudad origen', t: 'text', ph: 'Cód. IATA o ciudad', airportApi: true }, { k: 'destino', l: 'Ciudad destino', t: 'text', ph: 'Cód. IATA o ciudad', airportApi: true }, { k: 'aerolinea', l: 'Aerolínea', t: 'text', ph: 'Air France' }, { k: 'vuelo', l: 'No. de vuelo', t: 'text', ph: 'AF9474' }, { k: 'salida', l: 'Salida', t: 'datetime-local' }, { k: 'llegada', l: 'Llegada', t: 'datetime-local' }, { k: 'clase', l: 'Clase', t: 'select', opts: ['Económica', 'Ejecutiva', 'Primera'] }, { k: 'precio', l: 'Precio (USD)', t: 'text', ph: '800' }, { k: 'notas', l: 'Notas', t: 'textarea', ph: 'Info adicional...' }] },
  alojamiento: { icon: '<i class="fa-solid fa-hotel"></i>', label: 'Alojamiento', color: '#f0567a', bg: '#fde8ee', hasStars: true, fields: [{ k: 'nombre', l: 'Nombre del hotel', t: 'text', ph: 'Hotel Luxe París', fw: true }, { k: 'checkin', l: 'Check-in', t: 'date' }, { k: 'checkout', l: 'Check-out', t: 'date' }, { k: 'habitacion', l: 'Tipo habitación', t: 'select', ph: 'Selecciona...', opts: ['Sencilla', 'Doble', 'Suite', 'Familiar'] }, { k: 'alimentacion', l: 'Alimentación', t: 'select', ph: 'Selecciona...', opts: ['Solo alojamiento', 'Desayuno incluido', 'Media pensión', 'Pensión completa', 'Todo incluido'] }, { k: 'phone', l: 'Teléfono', t: 'text', ph: '+1 234...' }, { k: 'website', l: 'Sitio Web', t: 'text', ph: 'https://...' }, { k: 'direccion', l: 'Dirección', t: 'text', ph: 'Avenida...', fw: true }, { k: 'photo_url', l: 'URL de foto', t: 'text', ph: 'https://...', fw: true }, { k: 'notas', l: 'Notas', t: 'textarea', ph: 'Desayuno incluido...' }] },
  transporte: { icon: '<i class="fa-solid fa-car"></i>', label: 'Transporte', color: '#22c87a', bg: '#d1fae8', fields: [{ k: 'tipo', l: 'Tipo', t: 'select', opts: ['Auto de alquiler', 'Taxi/Uber', 'Tren', 'Bus', 'Ferry', 'Moto'] }, { k: 'proveedor', l: 'Proveedor', t: 'text', ph: 'Hertz, Renfe...' }, { k: 'origen', l: 'Desde', t: 'text', ph: 'Aeropuerto CDG' }, { k: 'destino', l: 'Hasta', t: 'text', ph: 'Hotel Centro' }, { k: 'salida', l: 'Salida', t: 'datetime-local' }, { k: 'llegada', l: 'Llegada', t: 'datetime-local' }, { k: 'precio', l: 'Precio (USD)', t: 'text', ph: '50' }, { k: 'notas', l: 'Notas', t: 'textarea', ph: 'Confirmación...' }] },
  actividad: { icon: '<i class="fa-solid fa-bullseye"></i>', label: 'Actividad', color: '#f59e0b', bg: '#fef3c7', hasStars: true, fields: [{ k: 'nombre', l: 'Nombre actividad', t: 'text', ph: 'Cena con vista, Tour privado...', fw: true }, { k: 'direccion', l: 'Lugar (Google Maps)', t: 'text', ph: 'Torre Eiffel, Museo del Louvre...', fw: true }, { k: 'fecha', l: 'Fecha y hora', t: 'datetime-local' }, { k: 'duracion', l: 'Duración', t: 'select', opts: ['1h', '2h', '3h', '4h', 'Medio día', 'Día completo'] }, { k: 'phone', l: 'Teléfono', t: 'text', ph: '+1 234...' }, { k: 'website', l: 'Sitio Web', t: 'text', ph: 'https://...' }, { k: 'photo_url', l: 'URL de foto', t: 'text', ph: 'https://...', fw: true }, { k: 'precio', l: 'Precio (USD)', t: 'text', ph: '25' }, { k: 'reserva', l: 'Código reserva', t: 'text', ph: 'ACT-12345' }, { k: 'descripcion', l: 'Descripción', t: 'textarea', ph: 'Descripción...' }] },
  comida: { icon: '<i class="fa-solid fa-utensils"></i>', label: 'Comida', color: '#f96b3a', bg: '#ffe8e0', hasStars: true, fields: [{ k: 'restaurante', l: 'Restaurante', t: 'text', ph: 'Le Jules Verne', fw: true }, { k: 'tipo', l: 'Tipo', t: 'select', opts: ['Desayuno', 'Almuerzo', 'Cena', 'Brunch', 'Snack'] }, { k: 'fecha', l: 'Fecha y hora', t: 'datetime-local' }, { k: 'phone', l: 'Teléfono', t: 'text', ph: '+1 234...' }, { k: 'website', l: 'Sitio Web', t: 'text', ph: 'https://...' }, { k: 'direccion', l: 'Dirección', t: 'text', ph: 'Avenida...', fw: true }, { k: 'photo_url', l: 'URL de foto', t: 'text', ph: 'https://...', fw: true }, { k: 'reserva', l: 'Reservación', t: 'select', opts: ['Sí, confirmada', 'Pendiente', 'No aplica'] }, { k: 'precio', l: 'Presupuesto (USD)', t: 'text', ph: '80' }, { k: 'notas', l: 'Notas', t: 'textarea', ph: 'Menú degustación...' }] },
  tour: { icon: '<i class="fa-solid fa-map-location-dot"></i>', label: 'Tour', color: '#8b5cf6', bg: '#f5f3ff', fields: [{ k: 'nombre', l: 'Nombre del tour', t: 'text', ph: 'Tour Versalles' }, { k: 'operador', l: 'Operador', t: 'text', ph: 'Get Your Guide' }, { k: 'fecha', l: 'Fecha y hora', t: 'datetime-local' }, { k: 'duracion', l: 'Duración', t: 'select', opts: ['2h', '4h', 'Medio día', 'Día completo', '2 días', '3+ días'] }, { k: 'personas', l: 'No. personas', t: 'text', ph: '2' }, { k: 'photo_url', l: 'URL de foto', t: 'text', ph: 'https://...', fw: true }, { k: 'precio', l: 'Precio total (USD)', t: 'text', ph: '120' }, { k: 'descripcion', l: 'Descripción', t: 'textarea', ph: 'Incluye entrada, guía...' }] },
  texto: { icon: '<i class="fa-solid fa-font"></i>', label: 'Caja de texto', color: '#64748b', bg: '#f1f5f9', fields: [{ k: 'contenido', l: 'Contenido', t: 'textarea', ph: 'Escribe aquí...' }, { k: 'alineacion', l: 'Alineación', t: 'select', opts: ['Izquierda', 'Centro', 'Derecha'] }] },
  titulo: { icon: '✦', label: 'Título', color: '#1a1a2e', bg: '#f0f1f7', fields: [{ k: 'texto', l: 'Texto del título', t: 'text', ph: 'Día 1 — Llegada a París' }, { k: 'subtitulo', l: 'Subtítulo (opcional)', t: 'text', ph: 'Una ciudad de luz...' }, { k: 'emoji', l: 'Emoji decorativo', t: 'text', ph: '🗼' }] },
  separador: { icon: '—', label: 'Separador', color: '#94a3b8', bg: '#f1f5f9', fields: [{ k: 'estilo', l: 'Estilo', t: 'select', opts: ['Línea simple', 'Línea con diamante', 'Punteado', 'Gradiente'] }, { k: 'etiqueta', l: 'Etiqueta (opcional)', t: 'text', ph: 'Mañana' }] },
  imagen: { icon: '<i class="fa-regular fa-image"></i>', label: 'Imagen', color: 'var(--primary-blue)', bg: '#e0f2fe', fields: [{ k: 'url', l: 'URL de imagen', t: 'text', ph: 'https://...' }, { k: 'caption', l: 'Pie de foto', t: 'text', ph: 'Torre Eiffel al atardecer' }, { k: 'tamano', l: 'Tamaño', t: 'select', opts: ['Pequeño', 'Mediano', 'Grande', 'Completo'] }] },
  gif: { icon: '<i class="fa-solid fa-bolt"></i>', label: 'GIF', color: '#ce3df3', bg: '#f9f0ff', fields: [{ k: 'url', l: 'URL del GIF', t: 'text', ph: 'https://...', fw: true }, { k: 'caption', l: 'Pie de GIF', t: 'text', ph: '¡Increíble!' }] },
  caja: { icon: '<i class="fa-solid fa-palette"></i>', label: 'Caja con fondo', color: '#22c87a', bg: '#d1fae8', fields: [{ k: 'titulo', l: 'Título', t: 'text', ph: 'Tip importante' }, { k: 'icono', l: 'Emoji / Icono', t: 'text', ph: '💡' }, { k: 'contenido', l: 'Contenido', t: 'textarea', ph: 'Información relevante...' }, { k: 'color_fondo', l: 'Color de fondo', t: 'color-picker', opts: ['var(--primary-blue)', '#f0567a', '#22c87a', '#f59e0b', '#0ea5d8', '#f96b3a'] }] }
};

// DRAG
const dragGhost = document.getElementById('dragGhost');
const canvasItems = document.getElementById('canvasItems');
const emptyState = document.getElementById('emptyState');
document.querySelectorAll('.element-card').forEach(card => {
  card.addEventListener('dragstart', e => { dragType = card.dataset.type; dragLabel = card.dataset.label; dragSourceIndex = null; card.classList.add('dragging'); e.dataTransfer.effectAllowed = 'copy'; e.dataTransfer.setDragImage(new Image(), 0, 0); const cfg = C[dragType]; document.getElementById('ghostIcon').textContent = cfg.icon; document.getElementById('ghostLabel').textContent = dragLabel; dragGhost.style.opacity = '1' });
  card.addEventListener('dragend', () => { card.classList.remove('dragging'); dragGhost.style.opacity = '0'; clearDropIndicators() });
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
function setupContainerDrag(containerId, itemsArr) {
  const cont = document.getElementById(containerId);

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
      const cl = getClosestInCont(e.clientY);
      let to = cl.before ? cl.index : cl.index + 1;
      if (to > dragSourceIndex) to--;
      const [moved] = itemsArr.splice(dragSourceIndex, 1);
      itemsArr.splice(to, 0, moved);
      renderCanvas(); dragSourceIndex = null; dragSourceContainer = null; return;
    }
    if (dragType) openModal(dragType);
  });
}
setupContainerDrag('portadaItems', portadaItems);
setupContainerDrag('cierreItems', cierreItems);
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

document.getElementById('addDayBtn').addEventListener('click', () => {
  days.push([]); dayDates.push('');
  const dayIdx = days.length - 1;
  // Count existing numeric tabs to get next display number
  const existingNums = [...document.querySelectorAll('.day-tab:not(.portada-tab):not(.cierre-tab)')].length;
  const newDayNum = existingNums + 1;
  const cierreTab = document.querySelector('.day-tab.cierre-tab');
  const tab = document.createElement('button');
  tab.className = 'day-tab'; tab.dataset.day = dayIdx;
  tab.innerHTML = `<span class="day-tab-label">Día ${newDayNum}</span><span class="day-tab-delete" onclick="confirmDeleteDay(${dayIdx},event)" title="Eliminar día"><i class="fa-solid fa-times"></i></span>`;
  if (cierreTab) document.getElementById('dayTabs').insertBefore(tab, cierreTab);
  else document.getElementById('dayTabs').appendChild(tab);
  currentDay = dayIdx;
  document.querySelectorAll('.day-tab').forEach(t => t.classList.remove('active'));
  tab.classList.add('active');
  renderCanvas();
  showToast('📅', 'Día ' + newDayNum + ' agregado');
});

document.getElementById('addSectionBtn').addEventListener('click', e => { e.stopPropagation(); document.getElementById('sectionDropdown').classList.toggle('open') });
document.addEventListener('click', () => document.getElementById('sectionDropdown').classList.remove('open'));

function addSection(type) {
  document.getElementById('sectionDropdown').classList.remove('open');
  const tabs = document.getElementById('dayTabs');
  if (type === 'portada') {
    if (document.querySelector('.day-tab.portada-tab')) return showToast('⚠️', 'La portada ya existe');
    const tab = document.createElement('button');
    tab.className = 'day-tab portada-tab'; tab.dataset.day = 'portada';
    tab.innerHTML = '<span class="day-tab-label"><i class="fa-solid fa-sun"></i> Portada</span><span class="day-tab-delete portada-cierre-delete" onclick="confirmDeleteSection(\'portada\',event)" title="Eliminar portada"><i class="fa-solid fa-times"></i></span>';
    tabs.insertBefore(tab, tabs.firstChild);
    currentDay = 'portada';
    document.querySelectorAll('.day-tab').forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    renderCanvas(); showToast('🌅', 'Portada agregada');
  } else {
    if (document.querySelector('.day-tab.cierre-tab')) return showToast('⚠️', 'El cierre ya existe');
    const tab = document.createElement('button');
    tab.className = 'day-tab cierre-tab'; tab.dataset.day = 'cierre';
    tab.innerHTML = '<span class="day-tab-label"><i class="fa-solid fa-moon"></i> Cierre</span><span class="day-tab-delete portada-cierre-delete" onclick="confirmDeleteSection(\'cierre\',event)" title="Eliminar cierre"><i class="fa-solid fa-times"></i></span>';
    tabs.appendChild(tab);
    currentDay = 'cierre';
    document.querySelectorAll('.day-tab').forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
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
  const tab = document.querySelector(`.day-tab.${type}-tab`);
  if (tab) tab.remove();
  // Navigate to first available day
  const firstTab = document.querySelector('.day-tab');
  document.querySelectorAll('.day-tab').forEach(t => t.classList.remove('active'));
  if (firstTab) {
    firstTab.classList.add('active');
    const dv = firstTab.dataset.day;
    currentDay = dv === 'portada' ? 'portada' : dv === 'cierre' ? 'cierre' : parseInt(dv);
  }
  renderCanvas();
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
  const tab = document.querySelector(`.day-tab[data-day="${dayIdx}"]`);
  if (tab) tab.remove();
  // Re-index data-day and re-label all numeric tabs sequentially
  const numericTabs = [...document.querySelectorAll('.day-tab:not(.portada-tab):not(.cierre-tab)')];
  numericTabs.forEach((t, i) => {
    t.dataset.day = i;
    const lbl = t.querySelector('.day-tab-label');
    if (lbl) lbl.textContent = 'Día ' + (i + 1);
    // Rebind delete button
    const btn = t.querySelector('.day-tab-delete');
    if (btn) btn.setAttribute('onclick', `confirmDeleteDay(${i},event)`);
  });
  const firstNumericTab = numericTabs[0];
  document.querySelectorAll('.day-tab').forEach(t => t.classList.remove('active'));
  if (firstNumericTab) { firstNumericTab.classList.add('active'); currentDay = 0 }
  renderCanvas(); showToast('<i class="fa-solid fa-trash-can"></i>', 'Día eliminado');
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
}

// CONFIRM
function openConfirm(title, msg, cb) { confirmCallback = cb; document.getElementById('confirmTitle').textContent = title; document.getElementById('confirmMsg').textContent = msg; document.getElementById('confirmOverlay').classList.add('open') }

function showUnsavedChangesModal() {
  openConfirm(
    '¿Salir sin guardar?',
    'Tienes cambios sin guardar. Si sales ahora, los cambios no guardados se perderán.',
    () => { window.location.href = '/trips'; }
  );
}
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
    document.getElementById('itemCount').textContent = 'Portada del viaje'; return;
  }
  if (currentDay === 'cierre') {
    cierreCanvas.style.display = 'flex';
    document.getElementById('cierreTitleDisplay').textContent = document.getElementById('portadaTitle').value || 'Tu viaje';
    // Render cierre extra items
    const cItems = document.getElementById('cierreItems');
    cItems.innerHTML = '';
    cierreItems.forEach((item, idx) => cItems.appendChild(buildItem(item, idx)));
    document.getElementById('itemCount').textContent = 'Cierre del itinerario'; return;
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
  document.getElementById('itemCount').textContent = items.length + (items.length === 1 ? ' elemento' : ' elementos');
  items.forEach((item, idx) => canvasItems.appendChild(buildItem(item, idx)));
}

function buildItem(item, idx) {
  const cfg = C[item.type]; const el = document.createElement('div');
  el.className = `canvas-item tipo-${item.type}`; el.dataset.index = idx;
  if (item.type === 'separador') { const lbl = item.data.etiqueta || ''; el.innerHTML = `<div class="item-inner"><div class="sep-line"></div>${lbl ? `<span class="sep-dot"></span><span style="font-size:11px;color:var(--text-dim);white-space:nowrap">${lbl}</span><span class="sep-dot"></span>` : '<span class="sep-dot"></span>'}<div class="sep-line"></div><div class="item-actions" style="margin-left:8px"><button class="item-action-btn" onclick="editItem(${idx})">✏</button><button class="item-action-btn delete" onclick="deleteItem(${idx})">✕</button></div></div>`; setupReorder(el, idx); return el }
  if (item.type === 'titulo') { el.style.position = 'relative'; el.innerHTML = `<div class="item-inner" style="flex-direction:column;gap:3px;padding:18px 20px"><div class="titulo-text">${item.data.emoji ? item.data.emoji + ' ' : ''}${item.data.texto || 'Título'}</div>${item.data.subtitulo ? `<div style="font-size:13px;color:var(--text-muted)">${item.data.subtitulo}</div>` : ''}</div><div class="item-actions" style="position:absolute;right:12px;top:12px;opacity:0;transition:opacity .18s"><button class="item-action-btn" onclick="editItem(${idx})">✏</button><button class="item-action-btn delete" onclick="deleteItem(${idx})">✕</button></div>`; el.addEventListener('mouseenter', () => el.querySelector('.item-actions').style.opacity = '1'); el.addEventListener('mouseleave', () => el.querySelector('.item-actions').style.opacity = '0'); setupReorder(el, idx); return el }
  if (item.type === 'texto') { el.style.position = 'relative'; el.innerHTML = `<div class="item-inner" style="flex-direction:column;gap:5px;padding:14px 16px"><div class="texto-content" style="text-align:${(item.data.alineacion || 'Izquierda').toLowerCase()}">${item.data.contenido || 'Texto...'}</div></div><div class="item-actions" style="position:absolute;right:12px;top:12px;opacity:0;transition:opacity .18s"><button class="item-action-btn" onclick="editItem(${idx})">✏</button><button class="item-action-btn delete" onclick="deleteItem(${idx})">✕</button></div>`; el.addEventListener('mouseenter', () => el.querySelector('.item-actions').style.opacity = '1'); el.addEventListener('mouseleave', () => el.querySelector('.item-actions').style.opacity = '0'); setupReorder(el, idx); return el }
  if (item.type === 'imagen') { const hasImg = item.data.url && item.data.url.startsWith('http'); el.style.position = 'relative'; el.innerHTML = `<div class="item-inner" style="flex-direction:column;gap:9px;padding:11px"><div class="imagen-preview">${hasImg ? `<img src="${item.data.url}" alt="">` : '🖼️'}</div>${item.data.caption ? `<div style="font-size:12px;color:var(--text-muted);text-align:center">${item.data.caption}</div>` : ''}</div><div class="item-actions" style="position:absolute;right:12px;top:12px;opacity:0;transition:opacity .18s"><button class="item-action-btn" onclick="editItem(${idx})">✏</button><button class="item-action-btn delete" onclick="deleteItem(${idx})">✕</button></div>`; el.addEventListener('mouseenter', () => el.querySelector('.item-actions').style.opacity = '1'); el.addEventListener('mouseleave', () => el.querySelector('.item-actions').style.opacity = '0'); setupReorder(el, idx); return el }
  if (item.type === 'caja') { const bg = item.data.color_fondo || '#7c6fef'; el.style.background = bg + '12'; el.style.borderColor = bg + '40'; el.innerHTML = `<div class="item-inner" style="gap:11px"><div style="font-size:25px;margin-top:2px">${item.data.icono || '💡'}</div><div style="flex:1"><div class="item-title">${item.data.titulo || 'Caja con fondo'}</div><div class="texto-content" style="margin-top:3px">${item.data.contenido || ''}</div></div><div class="item-actions"><button class="item-action-btn" onclick="editItem(${idx})">✏</button><button class="item-action-btn delete" onclick="deleteItem(${idx})">✕</button></div></div>`; setupReorder(el, idx); return el }
  if (item.type === 'gif') {
    const hasImg = item.data.url && item.data.url.startsWith('http');
    el.style.position = 'relative';
    el.innerHTML = `<div class="item-inner" style="flex-direction:column;gap:9px;padding:11px">
      <div class="imagen-preview">${hasImg ? `<img src="${item.data.url}" alt="">` : '<i class="fa-solid fa-bolt"></i>'}</div>
      ${item.data.caption ? `<div style="font-size:12px;color:var(--text-muted);text-align:center">${item.data.caption}</div>` : ''}
    </div>
    <div class="item-actions" style="position:absolute;right:12px;top:12px;opacity:0;transition:opacity .18s">
      <button class="item-action-btn" onclick="editItem(${idx})">✏</button>
      <button class="item-action-btn delete" onclick="deleteItem(${idx})">✕</button>
    </div>`;
    el.addEventListener('mouseenter', () => el.querySelector('.item-actions').style.opacity = '1');
    el.addEventListener('mouseleave', () => el.querySelector('.item-actions').style.opacity = '0');
    setupReorder(el, idx); return el;
  }
  const d = item.data; let title = '', chips = [], sub = [];
  switch (item.type) { case 'flight': title = (d.origen && d.destino) ? `${d.origen} → ${d.destino}` : 'Vuelo'; if (d.aerolinea) chips.push(d.aerolinea); if (d.vuelo) chips.push(d.vuelo); if (d.clase) chips.push(d.clase); if (d.salida) sub.push('🕐 ' + fmtDT(d.salida)); if (d.precio) chips.push('$' + d.precio); break; case 'alojamiento': title = d.nombre || 'Alojamiento'; if (d.direccion) sub.push('📍 ' + d.direccion); if (d.checkin) chips.push('In: ' + d.checkin); if (d.checkout) chips.push('Out: ' + d.checkout); if (d.habitacion) chips.push(d.habitacion); if (d.alimentacion) chips.push('🍽️ ' + d.alimentacion); if (d.stars) sub.push('⭐ ' + (Number.isInteger(d.stars) ? d.stars + '.0' : d.stars)); break; case 'transporte': title = (d.origen && d.destino) ? `${d.origen} → ${d.destino}` : (d.tipo || 'Transporte'); if (d.tipo) chips.push(d.tipo); if (d.proveedor) chips.push(d.proveedor); if (d.fecha) sub.push('🕐 ' + fmtDT(d.fecha)); if (d.precio) chips.push('$' + d.precio); break; case 'actividad': title = d.nombre || 'Actividad'; if (d.lugar) sub.push('📍 ' + d.lugar); if (d.duracion) chips.push('⏱ ' + d.duracion); if (d.fecha) chips.push(fmtDT(d.fecha)); if (d.precio) chips.push('$' + d.precio); break; case 'comida': title = d.restaurante || 'Comida'; if (d.ciudad) sub.push('📍 ' + d.ciudad); if (d.tipo) chips.push(d.tipo); if (d.fecha) chips.push(fmtDT(d.fecha)); if (d.precio) chips.push('$' + d.precio); if (d.stars) sub.push('⭐ ' + (Number.isInteger(d.stars) ? d.stars + '.0' : d.stars)); break; case 'tour': title = d.nombre || 'Tour'; if (d.operador) sub.push('🏢 ' + d.operador); if (d.duracion) chips.push('⏱ ' + d.duracion); if (d.personas) chips.push('👥 ' + d.personas); if (d.precio) chips.push('$' + d.precio); break }
  el.innerHTML = `<div class="item-inner"><div class="item-accent-bar" style="background:${cfg.color}"></div><div class="item-icon" style="background:${cfg.bg}">${cfg.icon}</div><div class="item-content"><div class="item-type-label" style="color:${cfg.color}">${cfg.label}</div><div class="item-title">${title}</div><div class="item-subtitle">${sub.map(s => `<span>${s}</span>`).join('')}${chips.map(c => `<span class="item-chip">${c}</span>`).join('')}</div></div><div class="item-actions"><button class="item-action-btn" onclick="editItem(${idx})">✏</button><button class="item-action-btn delete" onclick="deleteItem(${idx})">✕</button></div></div>`;
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
function fmtDate(s) { if (!s) return ''; try { return new Date(s + 'T00:00:00').toLocaleDateString('es', { day: 'numeric', month: 'long', year: 'numeric' }) } catch { return s } }
function editItem(idx) {
  const arr = currentDay === 'portada' ? portadaItems : currentDay === 'cierre' ? cierreItems : days[currentDay];
  openModal(arr[idx].type, idx);
}
function deleteItem(idx) {
  if (currentDay === 'portada') portadaItems.splice(idx, 1);
  else if (currentDay === 'cierre') cierreItems.splice(idx, 1);
  else days[currentDay].splice(idx, 1);
  renderCanvas(); showToast('<i class="fa-solid fa-trash-can"></i>', 'Elemento eliminado');
}

// MODAL
const modalOverlay = document.getElementById('modalOverlay');
const modalBody = document.getElementById('modalBody');
function openModal(type, editIdx = null) {
  if (typeof currentDay !== 'number' && currentDay !== 'portada' && currentDay !== 'cierre') return;

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
  for (let i = 0; i < fields.length; i++) { const f = fields[i], next = fields[i + 1]; if (f.t === 'textarea' || f.t === 'color-picker' || f.fw) { modalBody.appendChild(buildField(f, existData)) } else if (next && next.t !== 'textarea' && next.t !== 'color-picker' && !next.fw) { const row = document.createElement('div'); row.className = 'form-row'; row.appendChild(buildField(f, existData)); row.appendChild(buildField(next, existData)); modalBody.appendChild(row); i++ } else { modalBody.appendChild(buildField(f, existData)) } }
  if (cfg.hasStars) { const sg = document.createElement('div'); sg.className = 'form-group'; sg.innerHTML = '<label class="form-label">Calificación</label>'; const sr = document.createElement('div'); sr.className = 'star-rating'; const init = existData.stars || 0; for (let s = 1; s <= 5; s++) { const star = document.createElement('span'); star.className = 'star' + (s <= init ? ' active' : ''); star.textContent = '★'; star.dataset.val = s; star.addEventListener('click', () => { starRating = parseInt(star.dataset.val); sr.querySelectorAll('.star').forEach((st, idx) => st.classList.toggle('active', idx < starRating)) }); sr.appendChild(star) } starRating = init; sg.appendChild(sr); modalBody.appendChild(sg) }
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

          if (place.photos && place.photos.length > 0) {
            const urls = place.photos.slice(0, 5).map(p => p.getUrl({ maxWidth: 400, maxHeight: 400 })).join(',');
            setVal('photo_url', urls);
          }

          if (place.rating) {
            starRating = place.rating;
            const sr = modalBody.querySelector('.star-rating');
            if (sr) {
              sr.querySelectorAll('.star').forEach((st, idx) => st.classList.toggle('active', idx < Math.round(starRating)));
            }
          }
        });
      }
    }
  }, 220);
}
function buildField(field, data) {
  const fg = document.createElement('div'); fg.className = 'form-group';
  const lbl = document.createElement('label'); lbl.className = 'form-label'; lbl.textContent = field.l; fg.appendChild(lbl);
  const val = data[field.k] || '';
  if (field.t === 'textarea') { const ta = document.createElement('textarea'); ta.className = 'form-textarea'; ta.placeholder = field.ph || ''; ta.value = val; ta.dataset.key = field.k; fg.appendChild(ta) }
  else if (field.t === 'select') { const sel = document.createElement('select'); sel.className = 'form-select'; sel.dataset.key = field.k; if (field.ph) { const op = document.createElement('option'); op.value = ''; op.textContent = field.ph; op.disabled = true; op.selected = !val; sel.appendChild(op); } field.opts.forEach(opt => { const o = document.createElement('option'); o.value = opt; o.textContent = opt; if (opt === val) o.selected = true; sel.appendChild(o) }); fg.appendChild(sel) }
  else if (field.t === 'color-picker') { const row = document.createElement('div'); row.className = 'color-row'; field.opts.forEach((color, ci) => { const sw = document.createElement('div'); sw.className = 'color-swatch' + (data[field.k] === color || (!data[field.k] && ci === 0) ? ' selected' : ''); sw.style.background = color; sw.dataset.color = color; sw.dataset.key = field.k; sw.addEventListener('click', () => { row.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('selected')); sw.classList.add('selected') }); row.appendChild(sw) }); fg.appendChild(row) }
  else {
    const inp = document.createElement('input'); inp.className = 'form-input'; inp.type = field.t || 'text'; inp.placeholder = field.ph || ''; inp.value = val; inp.dataset.key = field.k; fg.appendChild(inp);

    // Phase 36: Unsplash button for Tour photo_url
    if (field.k === 'photo_url' && pendingType === 'tour') {
      const btn = document.createElement('button');
      btn.className = 'btn-secondary';
      btn.style = 'margin-top:8px; width:100%; font-size:12px; height:32px; display:flex; align-items:center; justify-content:center; gap:6px;';
      btn.innerHTML = '<i class="fa-brands fa-unsplash"></i> Buscar en Unsplash';
      btn.type = 'button';
      btn.onclick = () => openUnsplash('tour');
      fg.appendChild(btn);
    }

    if (field.airportApi) {
      const drop = document.createElement('div'); drop.className = 'api-autocomplete-drop'; drop.style = 'position:absolute; background:#fff; border:1px solid #ccc; border-radius:4px; max-height:200px; overflow-y:auto; z-index:100; display:none; width:100%; box-shadow:0 4px 6px rgba(0,0,0,0.1); margin-top:2px;';
      fg.style.position = 'relative';
      fg.appendChild(drop);
      let timeout;
      inp.addEventListener('input', e => {
        clearTimeout(timeout);
        const q = e.target.value.trim();
        if (q.length < 3) { drop.style.display = 'none'; return; }
        timeout = setTimeout(() => {
          fetch(`/api/airports?q=${encodeURIComponent(q)}`)
            .then(res => res.json())
            .then(data => {
              drop.innerHTML = '';
              if (!data.length) { drop.style.display = 'none'; return; }
              data.forEach(it => {
                const item = document.createElement('div');
                item.style = 'padding:8px 12px; cursor:pointer; font-size:14px; border-bottom:1px solid #eee; display:flex; flex-direction:column; gap:2px;';
                item.innerHTML = `<strong>${it.text}</strong><span style="font-size:12px;color:#666">${it.city || ''}${it.country ? ', ' + it.country : ''}</span>`;
                item.onmouseenter = () => item.style.background = '#f5f5f5';
                item.onmouseleave = () => item.style.background = '#transparent';
                item.onclick = () => {
                  inp.value = it.text;
                  inp.dataset.city = it.city || '';
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
modalOverlay.addEventListener('click', e => { if (e.target === modalOverlay) closeModal() });
document.getElementById('modalSave').addEventListener('click', () => {
  const data = {};
  modalBody.querySelectorAll('[data-key]').forEach(el => {
    data[el.dataset.key] = el.value;
    if (el.dataset.city) data[el.dataset.key + '_city'] = el.dataset.city;
  });
  modalBody.querySelectorAll('.color-swatch.selected').forEach(sw => { data[sw.dataset.key] = sw.dataset.color });
  if (starRating > 0) data.stars = starRating;
  const type = editingIndex !== null ? (currentDay === 'portada' ? portadaItems : currentDay === 'cierre' ? cierreItems : days[currentDay])[editingIndex].type : pendingType;
  const item = { type, data };
  const arr = currentDay === 'portada' ? portadaItems : currentDay === 'cierre' ? cierreItems : days[currentDay];
  if (editingIndex !== null) { arr[editingIndex] = item; showToast('<i class="fa-solid fa-pencil"></i>', 'Elemento actualizado') }
  else { arr.push(item); showToast('<i class="fa-solid fa-check"></i>', 'Elemento agregado') }
  renderCanvas(); closeModal();
});

// TOAST
function showToast(icon, msg) { const t = document.getElementById('toast'); document.getElementById('toastIcon').innerHTML = icon; document.getElementById('toastMsg').textContent = msg; t.classList.add('show'); setTimeout(() => t.classList.remove('show'), 2200) }

// ============================================================
// VISTA PREVIA — genera HTML y lo abre en nueva pestaña
// ============================================================
function openPreview() {
  const title = document.getElementById('portadaTitle').value || document.getElementById('itineraryNameInput').value || 'Mi Itinerario';
  const fechaInicio = document.getElementById('portadaFechaInicio').value;
  const fechaFin = document.getElementById('portadaFechaFin').value;
  const precio = document.getElementById('portadaPrecio').value;
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
  const previewHTML = buildPreviewHTML({ title, fechaInicio, fechaFin, precio, moneda, totalViajeros, hasPortada, hasCierre, showDefaultCierre, totalItems, numericTabs, days, dayDates, portadaAdultos, portadaNinos, portadaPhotoUrl, portadaItems, cierreItems });
  const blob = new Blob([previewHTML], { type: 'text/html' });
  const url = URL.createObjectURL(blob);
  window.open(url, '_blank');
  showToast('<i class="fa-regular fa-eye"></i>', 'Vista previa abierta');
}

function buildPreviewHTML(data) {
  const { title, fechaInicio, fechaFin, precio, moneda, totalViajeros, hasPortada, hasCierre, showDefaultCierre, totalItems, numericTabs, days, dayDates, portadaAdultos, portadaNinos, portadaPhotoUrl, portadaItems, cierreItems } = data;

  const fmtDateShort = s => { if (!s) return ''; try { return new Date(s + 'T00:00:00').toLocaleDateString('es', { day: 'numeric', month: 'short' }) } catch { return s } };
  const fmtDateTime = s => { if (!s) return ''; try { const d = new Date(s); const day = d.toLocaleDateString('es', { weekday: 'long', day: 'numeric', month: 'long' }); const time = d.toLocaleTimeString('es', { hour: '2-digit', minute: '2-digit' }); return { day, time } } catch { return { day: s, time: '' } } };
  const fmtDateDetail = s => { if (!s) return ''; try { return new Date(s + 'T00:00:00').toLocaleDateString('es', { day: '2-digit', month: '2-digit', year: '2-digit' }) } catch { return s } };
  const fmtDayMonth = s => { if (!s) return ''; try { const d = new Date(s + 'T00:00:00'); return d.toLocaleDateString('es', { day: 'numeric', month: 'long' }); } catch { return s } };
  const getVideoEmbedUrl = url => {
    if (!url) return null;
    let match = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/);
    if (match && match[1]) return `https://www.youtube.com/embed/${match[1]}`;
    match = url.match(/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/(?:[^\/]*)\/videos\/|album\/(?:\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)/);
    if (match && match[1]) return `https://player.vimeo.com/video/${match[1]}`;
    return null;
  };
  const starsHTML = n => n ? Array.from({ length: 5 }, (_, i) => `<svg width="16" height="16" viewBox="0 0 24 24" fill="${i < n ? '#f59e0b' : '#d1d5db'}"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>`).join('') : '';
  const cCarousel = (photo_url, icon) => {
    if (!photo_url) return `<div class="pv-hotel-photo-ph">${icon}</div>`;
    const urls = photo_url.split(',').filter(u => u.trim());
    if (urls.length === 1) return `<img src="${urls[0]}" style="width:100%;height:100%;object-fit:cover" />`;
    const slides = urls.map((u, i) => `<div class="pv-carousel-slide" style="display:${i === 0 ? 'block' : 'none'};width:100%;height:100%;"><img src="${u}" style="width:100%;height:100%;object-fit:cover" /></div>`).join('');
    const dots = urls.map((u, i) => `<span class="pv-carousel-dot" style="display:inline-block;width:6px;height:6px;border-radius:50%;background:${i === 0 ? '#fff' : 'rgba(255,255,255,0.5)'};margin:0 2px;cursor:pointer;" onclick="const p=this.closest('.pv-carousel');p.querySelectorAll('.pv-carousel-slide').forEach(s=>s.style.display='none');p.querySelectorAll('.pv-carousel-slide')[${i}].style.display='block';p.querySelectorAll('.pv-carousel-dot').forEach(d=>d.style.background='rgba(255,255,255,0.5)');this.style.background='#fff';event.preventDefault();"></span>`).join('');
    return `<div class="pv-carousel" style="position:relative;width:100%;height:100%;overflow:hidden;border-radius:inherit;z-index:1;">
          ${slides}
          <div class="pv-carousel-nav" style="position:absolute;top:50%;left:0;right:0;transform:translateY(-50%);display:flex;justify-content:space-between;padding:0 5px;z-index:2;pointer-events:none;">
            <button type="button" style="background:rgba(0,0,0,0.5);color:white;border:none;border-radius:50%;width:24px;height:24px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:10px;pointer-events:auto;" onclick="const p=this.closest('.pv-carousel');const s=p.querySelectorAll('.pv-carousel-slide');const d=p.querySelectorAll('.pv-carousel-dot');let idx=[...s].findIndex(el=>el.style.display==='block');s[idx].style.display='none';d[idx].style.background='rgba(255,255,255,0.5)';idx=(idx-1+s.length)%s.length;s[idx].style.display='block';d[idx].style.background='#fff';event.preventDefault();"><i class="fa-solid fa-chevron-left"></i></button>
            <button type="button" style="background:rgba(0,0,0,0.5);color:white;border:none;border-radius:50%;width:24px;height:24px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:10px;pointer-events:auto;" onclick="const p=this.closest('.pv-carousel');const s=p.querySelectorAll('.pv-carousel-slide');const d=p.querySelectorAll('.pv-carousel-dot');let idx=[...s].findIndex(el=>el.style.display==='block');s[idx].style.display='none';d[idx].style.background='rgba(255,255,255,0.5)';idx=(idx+1)%s.length;s[idx].style.display='block';d[idx].style.background='#fff';event.preventDefault();"><i class="fa-solid fa-chevron-right"></i></button>
          </div>
          <div class="pv-carousel-dots" style="position:absolute;bottom:8px;left:0;right:0;text-align:center;z-index:2;">${dots}</div>
        </div>`;
  };
  const transportIconSVG = `<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="color:inherit"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42.99L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>`;
  const trainIconSVG = `<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="color:inherit"><path d="M12 2c-4 0-8 .5-8 4v9.5C4 17.43 5.57 19 7.5 19L6 20.5v.5h12v-.5L16.5 19c1.93 0 3.5-1.57 3.5-3.5V6c0-3.5-3.58-4-8-4zM7.5 17c-.83 0-1.5-.67-1.5-1.5S6.67 14 7.5 14s1.5.67 1.5 1.5S8.33 17 7.5 17zm3.5-7H6V6h5v4zm2 0V6h5v4h-5zm3.5 7c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/></svg>`;
  const busIconSVG = `<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="color:inherit"><path d="M4 16c0 .88.39 1.67 1 2.22V20c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h8v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1.78c.61-.55 1-1.34 1-2.22V6c0-3.5-3.58-4-8-4s-8 .5-8 4v10zm3.5 1c-.83 0-1.5-.67-1.5-1.5S6.67 14 7.5 14s1.5.67 1.5 1.5S8.33 17 7.5 17zm9 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm1.5-6H6V6h12v5z"/></svg>`;

  function getTransportIcon(tipo, sz = 20) {
    const s = `width="${sz}" height="${sz}"`;
    if (!tipo) return transportIconSVG.replace(/width="\d+" height="\d+"/, s);
    if (tipo === 'Tren') return trainIconSVG.replace(/width="\d+" height="\d+"/, s);
    if (tipo === 'Bus') return busIconSVG.replace(/width="\d+" height="\d+"/, s);
    const icons = {
      'Ferry': '<i class="fa-solid fa-ship"></i>',
      'Auto de alquiler': '<i class="fa-solid fa-car"></i>',
      'Taxi/Uber': '<i class="fa-solid fa-taxi"></i>',
      'Moto': '<i class="fa-solid fa-motorcycle"></i>'
    };
    return icons[tipo] ? `<span style="font-size:${sz + 2}px">${icons[tipo]}</span>` : transportIconSVG.replace(/width="\d+" height="\d+"/, s);
  }

  function renderPreviewItems(items) {
    if (!items || !items.length) return '<div class="pv-empty">Sin elementos en este día</div>';
    return items.map(item => {
      const d = item.data;

      // ── SEPARADOR ──
      if (item.type === 'separador')
        return `<div class="pv-sep"><div class="pvs-line"></div>${d.etiqueta ? `<span class="pvs-label">${d.etiqueta}</span>` : ''}<div class="pvs-line"></div></div>`;

      // ── TÍTULO ──
      if (item.type === 'titulo')
        return `<div class="pv-titulo"><div class="pvt-text">${d.emoji ? d.emoji + ' ' : ''}${d.texto || 'Título'}</div>${d.subtitulo ? `<div class="pvt-sub">${d.subtitulo}</div>` : ''}</div>`;

      // ── TEXTO ──
      if (item.type === 'texto')
        return `<div class="pv-texto" style="text-align:${(d.alineacion || 'Izquierda').toLowerCase()}">${d.contenido || ''}</div>`;

      // ── IMAGEN ──
      if (item.type === 'imagen') {
        const hasImg = d.url && d.url.startsWith('http');
        return `<div class="pv-imagen">${hasImg ? `<img src="${d.url}" alt="${d.caption || ''}">` : '<div class="pv-img-ph"><i class="fa-regular fa-image"></i></div>'}${d.caption ? `<div class="pv-caption">${d.caption}</div>` : ''}</div>`;
      }

      // ── CAJA CON FONDO ──
      if (item.type === 'caja') {
        const bg = d.color_fondo || '#7c6fef';
        return `<div class="pv-caja" style="background:${bg}10;border-left:4px solid ${bg}"><div class="pvc-caja-icon">${d.icono || '💡'}</div><div><div class="pvc-caja-title">${d.titulo || ''}</div><div class="pvc-caja-content">${d.contenido || ''}</div></div></div>`;
      }

      // ── GIF ──
      if (item.type === 'gif') {
        return `<div class="pv-imagen" style="box-shadow:none;border-radius:10px">
          <img src="${d.url}" style="width:100%;border-radius:10px">
          ${d.caption ? `<div class="pv-caption">${d.caption}</div>` : ''}
        </div>`;
      }

      // ── VIDEO ──
      if (item.type === 'video') {
        const embedUrl = getVideoEmbedUrl(d.url);
        return `<div class="pv-card">
          <div class="pvc-section-label" style="color:#ef4444"><i class="fa-solid fa-circle-play"></i> Video</div>
          <div class="pvc-main-title" style="margin-bottom:12px">${d.titulo || 'Video'}</div>
          ${embedUrl ? `
            <div class="pv-video-container" style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:10px;background:#000;">
              <iframe src="${embedUrl}" style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe>
            </div>
          ` : d.url ? `
            <div style="margin-top:8px">
              <a href="${d.url}" target="_blank" class="pv-action-btn" style="background:#ef4444"><i class="fa-solid fa-link"></i> Ver contenido externo</a>
            </div>
          ` : ''}
          ${d.descripcion ? `<div class="pv-media-desc" style="margin-top:10px">${d.descripcion}</div>` : ''}
        </div>`;
      }

      // ─────────────────────────────────────────────────────
      // ── VUELO  (referencia: imagen 2) ────────────────────
      // ─────────────────────────────────────────────────────
      if (item.type === 'flight') {
        const sal = d.salida ? fmtDateTime(d.salida) : { day: '', time: '' };
        const lle = d.llegada ? fmtDateTime(d.llegada) : { day: '', time: '' };
        const oriCity = d.origen_city || d.origen || 'Origen';
        const desCity = d.destino_city || d.destino || 'Destino';
        return `<div class="pv-card">
          <div class="pvc-section-label" style="color:#0e7aad; display:flex; justify-content:space-between; align-items:center;">
             <span><i class="fa-solid fa-plane"></i> Vuelo ${oriCity} → ${desCity}</span>
             <div style="display:flex; align-items:center; gap:8px;">
               ${d.aerolinea ? `<span style="font-weight:400; opacity:0.8">${d.aerolinea}</span>` : ''}
               ${d.vuelo ? `<span style="background:#0e7aad; color:#fff; padding:2px 8px; border-radius:6px; font-size:11px; font-weight:600; text-transform:uppercase;">${d.vuelo}</span>` : ''}
             </div>
          </div>
          <div class="pv-route-row">
            <div class="pv-route-end">
              <div class="pv-route-time">${sal.time || '—'}</div>
              <div class="pv-route-station">${d.origen || 'Origen'}</div>
              ${sal.day ? `<div class="pv-route-sub">${sal.day}</div>` : ''}
            </div>
            <div class="pv-route-mid">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" style="color:var(--muted)"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>
            </div>
            <div class="pv-route-end pv-route-right">
              <div class="pv-route-time">${lle.time || '—'}</div>
              <div class="pv-route-station">${d.destino || 'Destino'}</div>
              ${lle.day ? `<div class="pv-route-sub">${lle.day}</div>` : ''}
            </div>
          </div>
          ${d.clase || d.precio ? `<div class="pv-chips-row">${d.clase ? `<span class="pv-chip"><i class="fa-solid fa-chair"></i> ${d.clase}</span>` : ''}${d.precio ? `<span class="pv-chip">💰 $${d.precio} ${moneda}</span>` : ''}</div>` : ''}
          ${d.notas ? `<div class="pv-notes-row"><i class="fa-sharp fa-light fa-circle-exclamation"></i> ${d.notas}</div>` : ''}
        </div>`;
      }

      // ─────────────────────────────────────────────────────
      // ── ALOJAMIENTO  (referencia: imagen 3) ──────────────
      // foto izq + info derecha, check-in/out, habitación, desayuno
      // ─────────────────────────────────────────────────────
      if (item.type === 'alojamiento') {
        const nights = d.checkin && d.checkout ? Math.round((new Date(d.checkout) - new Date(d.checkin)) / (1000 * 60 * 60 * 24)) : null;
        return `<div class="pv-card">
          <div class="pvc-section-label" style="color:#0e7aad"><i class="fa-solid fa-hotel"></i> Alojamiento</div>
          <div class="pv-hotel-layout">
            <div class="pv-hotel-photo-slot">${cCarousel(d.photo_url, '<i class="fa-solid fa-hotel"></i>')}</div>
            <div class="pv-hotel-info-col">
              <div class="pv-hotel-title-row">
                <div class="pv-hotel-name">${d.nombre || 'Hotel'}</div>
                ${d.stars ? `<div class="pv-stars-row">${starsHTML(d.stars)}<span class="pv-stars-score">(${Number.isInteger(d.stars) ? d.stars + '.0' : d.stars})</span></div>` : ''}
              </div>
              ${d.direccion ? `<div class="pv-hotel-addr"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="flex-shrink:0;margin-top:1px"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg> ${d.direccion}</div>` : ''}
              <div class="pv-hotel-details">
                ${d.checkin ? `<div class="pv-hd-row"><span class="pv-hd-label">Check-in:</span> ${d.checkin} - 15:00</div>` : ''}
                ${d.checkout ? `<div class="pv-hd-row"><span class="pv-hd-label">Check-out:</span> ${d.checkout} - 11:00</div>` : ''}
                ${nights ? `<div class="pv-hd-row">${nights} noche${nights !== 1 ? 's' : ''}</div>` : ''}
                ${d.habitacion ? `<div class="pv-hd-icon-row"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7v11m0-6h18m0-5v11M5 7h14a2 2 0 0 1 2 2v2H3V9a2 2 0 0 1 2-2z"/></svg> ${d.habitacion}</div>` : ''}
                ${d.alimentacion ? `<div class="pv-hd-icon-row" style="display:flex;align-items:center;gap:6px"><i class="fa-solid fa-utensils" style="width:14px;text-align:center;font-size:12px"></i> <span>${d.alimentacion}</span></div>` : ''}
              </div>
              <div class="pv-hotel-btns">
                ${d.website ? `<a href="${d.website}" target="_blank" class="pv-action-btn" style="text-decoration:none">🌐 Sitio web</a>` : ''}
                ${d.phone ? `<a href="tel:${d.phone}" class="pv-action-btn" style="text-decoration:none">📞 ${d.phone}</a>` : ''}
              </div>
              ${d.notas ? `<div class="pv-notes-row"><i class="fa-sharp fa-light fa-circle-exclamation"></i> ${d.notas}</div>` : ''}
            </div>
          </div>
        </div>`;
      }

      // ─────────────────────────────────────────────────────
      // ── TRANSPORTE  (referencia: imagen 5) ───────────────
      // estilo tipo-tren: hora grande izquierda/derecha, estación debajo
      // ─────────────────────────────────────────────────────
      if (item.type === 'transporte') {
        const sal = d.salida || d.fecha ? fmtDateTime(d.salida || d.fecha) : { day: '', time: '' };
        const lle = d.llegada ? fmtDateTime(d.llegada) : { day: '', time: '' };
        const tLabel = d.tipo ? 'Transporte en ' + d.tipo : 'Transporte';
        const tIconHeader = getTransportIcon(d.tipo, 16);
        const tIconRoute = getTransportIcon(d.tipo, 22);
        return `<div class="pv-card">
          <div class="pvc-section-label" style="color:#0e7aad; display:flex; justify-content:space-between; align-items:center;">
            <div style="display:flex; align-items:center; gap:8px;">${tIconHeader} ${tLabel}</div>
            ${d.proveedor ? `<span style="background:#0e7aad; color:#fff; padding:2px 8px; border-radius:6px; font-size:11px; font-weight:600; text-transform:uppercase;">${d.proveedor}</span>` : ''}
          </div>
          <div class="pv-route-row">
            <div class="pv-route-end">
              <div class="pv-route-time">${sal.time || '—'}</div>
              <div class="pv-route-station pv-station-big">${d.origen || 'Origen'}</div>
              ${sal.day ? `<div class="pv-route-sub">${sal.day}</div>` : ''}
            </div>
            <div class="pv-route-mid" style="color:var(--muted)">${tIconRoute}</div>
            <div class="pv-route-end pv-route-right">
              <div class="pv-route-time">${lle.time || '—'}</div>
              <div class="pv-route-station pv-station-big">${d.destino || 'Destino'}</div>
              ${lle.day ? `<div class="pv-route-sub">${lle.day}</div>` : ''}
            </div>
          </div>
          ${d.precio ? `<div class="pv-chips-row"><span class="pv-chip">💰 $${d.precio} ${moneda}</span></div>` : ''}
          ${d.notas ? `<div class="pv-notes-row"><i class="fa-sharp fa-light fa-circle-exclamation"></i> ${d.notas}</div>` : ''}
        </div>`;
      }

      // ─────────────────────────────────────────────────────
      // ── ACTIVIDAD  (referencia: imagen 4) ────────────────
      // foto izq + nombre, lugar, rating, hora, descripción
      // ─────────────────────────────────────────────────────
      if (item.type === 'actividad') {
        const dt = d.fecha ? fmtDateTime(d.fecha) : { day: '', time: '' };
        const timeRange = dt.time ? (d.duracion ? dt.time + ' - ' + d.duracion : dt.time) : '';
        return `<div class="pv-card">
          <div class="pvc-section-label" style="color:#0e7aad"><i class="fa-solid fa-bullseye"></i> Actividad</div>
          <div class="pv-media-layout">
            <div class="pv-media-photo-slot">${cCarousel(d.photo_url, '<i class="fa-solid fa-bullseye"></i>')}</div>
            <div class="pv-media-info-col">
              <div class="pv-media-name" style="font-weight:700; font-size:16px; margin-bottom:2px;">${d.nombre || 'Actividad'}</div>
              ${d.direccion || d.lugar ? `<div class="pv-media-addr" style="color:#666; font-weight:500; font-size:13px; margin-bottom:4px;"><i class="fa-solid fa-location-dot" style="color:#f59e0b; margin-right:4px;"></i>${d.direccion || d.lugar}</div>` : ''}
              ${d.stars ? `<div class="pv-stars-row" style="margin-bottom:8px;">${starsHTML(d.stars)} <span class="pv-stars-score" style="font-size:12px; opacity:0.8;">(${Number.isInteger(d.stars) ? d.stars + '.0' : d.stars})</span></div>` : ''}
              
              ${timeRange ? `<div class="pv-media-time"><i class="fa-solid fa-clock"></i> ${timeRange}</div>` : ''}
              ${d.descripcion ? `<div class="pv-media-desc">${d.descripcion}</div>` : ''}
              <div class="pv-hotel-btns" style="margin-top:10px">
                ${d.website ? `<a href="${d.website}" target="_blank" class="pv-action-btn">🌐 Sitio web</a>` : ''}
                ${d.phone ? `<a href="tel:${d.phone}" class="pv-action-btn">📞 ${d.phone}</a>` : ''}
                ${d.precio ? `<span class="pv-action-btn pv-action-btn-blue">💰 $${d.precio} ${moneda}</span>` : ''}
              </div>
              ${d.reserva ? `<div class="pv-notes-row" style="border-top:none;padding-top:0;margin-top:8px"><i class="fa-solid fa-ticket"></i> Reserva: ${d.reserva}</div>` : ''}
            </div>
          </div>
        </div>`;
      }

      if (item.type === 'comida') {
        const dt = d.fecha ? fmtDateTime(d.fecha) : { day: '', time: '' };
        return `<div class="pv-card">
          <div class="pvc-section-label" style="color:#0e7aad"><i class="fa-solid fa-utensils"></i> Comida${d.tipo ? ' · ' + d.tipo : ''}</div>
          <div class="pv-media-layout">
            <div class="pv-media-photo-slot">${cCarousel(d.photo_url, '<i class="fa-solid fa-utensils"></i>')}</div>
            <div class="pv-media-info-col">
              <div class="pv-media-title-row">
                <div class="pv-media-name">${d.restaurante || 'Restaurante'}</div>
                ${d.stars ? `<div class="pv-stars-row">${starsHTML(d.stars)}<span class="pv-stars-score">(${Number.isInteger(d.stars) ? d.stars + '.0' : d.stars})</span></div>` : ''}
              </div>
              ${d.direccion || d.ciudad ? `<div class="pv-media-addr"><i class="fa-solid fa-location-dot"></i> ${d.direccion || d.ciudad}</div>` : ''}
              ${dt.time ? `<div class="pv-media-time"><i class="fa-solid fa-clock"></i> ${dt.time}${dt.day ? ' · ' + dt.day : ''}</div>` : ''}
              <div class="pv-hotel-btns" style="margin-top:10px">
                ${d.website ? `<a href="${d.website}" target="_blank" class="pv-action-btn">🌐 Sitio web</a>` : ''}
                ${d.phone ? `<a href="tel:${d.phone}" class="pv-action-btn">📞 ${d.phone}</a>` : ''}
                ${d.precio ? `<span class="pv-action-btn pv-action-btn-blue">💰 $${d.precio} ${moneda}</span>` : ''}
                ${d.reserva && d.reserva !== 'No aplica' ? `<span class="pv-action-btn" style="background:#f1f5f9;color:var(--text);border:1px solid var(--border)"><i class="fa-solid fa-calendar-check"></i> ${d.reserva}</span>` : ''}
              </div>
              ${d.notas ? `<div class="pv-notes-row"><i class="fa-sharp fa-light fa-circle-exclamation"></i> ${d.notas}</div>` : ''}
            </div>
          </div>
        </div>`;
      }

      // ─────────────────────────────────────────────────────
      // ── TOUR  (mismo layout media, color azul) ────────────
      // ─────────────────────────────────────────────────────
      if (item.type === 'tour') {
        const dt = d.fecha ? fmtDateTime(d.fecha) : { day: '', time: '' };
        const timeRange = dt.time ? (d.duracion ? dt.time + ' - ' + d.duracion : dt.time) : (d.duracion || '');
        return `<div class="pv-card">
          <div class="pvc-section-label" style="color:#0e7aad"><i class="fa-solid fa-map-location-dot"></i> Tour</div>
          <div class="pv-media-layout">
            <div class="pv-media-photo-slot"><div class="pv-media-photo-ph" style="background:linear-gradient(135deg,#d9f5ff,#bae6fd)"><i class="fa-solid fa-map-location-dot"></i></div></div>
            <div class="pv-media-info-col">
              <div class="pv-media-title-row">
                <div class="pv-media-name">${d.nombre || 'Tour'}</div>
              </div>
              ${d.operador ? `<div class="pv-media-addr">🏢 ${d.operador}</div>` : ''}
              ${timeRange ? `<div class="pv-media-time"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> ${timeRange}</div>` : ''}
              ${d.descripcion ? `<div class="pv-media-desc">${d.descripcion}</div>` : ''}
              <div class="pv-chips-row" style="margin-top:8px">
                ${d.personas ? `<span class="pv-chip"><i class="fa-solid fa-users"></i> ${d.personas} personas</span>` : ''}
                ${d.precio ? `<span class="pv-chip">💰 $${d.precio} ${moneda}</span>` : ''}
              </div>
              <div class="pv-hotel-btns" style="margin-top:12px">
                <button class="pv-action-btn">🌐 Sitio web</button>
              </div>
            </div>
          </div>
        </div>`;
      }

      return '';
    }).join('');
  }

  const daysHTML = numericTabs.map((tab, i) => {
    const items = days[tab.idx] || [];
    const dateStr = dayDates && dayDates[tab.idx] ? dayDates[tab.idx] : '';
    const dayTitle = dateStr
      ? new Date(dateStr + 'T00:00:00').toLocaleDateString('es', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }).replace(/^\w/, c => c.toUpperCase())
      : tab.label;
    return `<section class="pv-day" id="day-${tab.idx}">
      <div class="pvday-header">
        <div class="pvday-pill">Día ${i + 1}</div>
        <div class="pvday-title">${dayTitle}</div>
        <div class="pvday-count">${items.length} elemento${items.length !== 1 ? 's' : ''}</div>
      </div>
      <div class="pvday-items">${renderPreviewItems(items)}</div>
    </section>`;
  }).join('');

  const sidebarNav = numericTabs.map((tab, i) => {
    const dStr = dayDates && dayDates[tab.idx] ? dayDates[tab.idx] : '';
    const dateLabel = dStr ? fmtDayMonth(dStr) : tab.label;
    return `<a class="pvnav-link" href="#day-${tab.idx}"><span class="pvnav-num">Día ${i + 1}</span> ${dateLabel}</a>`;
  }).join('');

  return `<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>${title} · itinerai</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
html{scroll-behavior:smooth}
:root{--accent:var(--primary-blue);--text:#0f172a;--muted:#64748b;--dim:#94a3b8;--border:#e2e8f0;--bg:#f1f5f9;--surface:#fff;--radius:12px;--shadow:0 10px 30px rgba(0,0,0,.06)}
body{font-family:'Poppins',sans-serif;background:var(--bg);color:var(--text);min-height:100vh}

/* TOPBAR */
.pv-topbar{position:sticky;top:0;z-index:100;background:rgba(255,255,255,.96);backdrop-filter:blur(12px);border-bottom:1px solid var(--border);padding:0 28px;height:52px;display:flex;align-items:center;gap:16px}
.pv-logo{font-family:'Poppins',sans-serif;font-weight:800;font-size:15px;background:linear-gradient(135deg,var(--primary-blue),#38bdf8);-webkit-background-clip:text;-webkit-text-fill-color:transparent;flex-shrink:0}
.pv-topbar-title{font-family:'Poppins',sans-serif;font-size:14px;font-weight:700;color:var(--text);flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.pv-back-btn{padding:6px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:12.5px;color:var(--muted);cursor:pointer;background:none;font-family:'Poppins',sans-serif;transition:all .14s;text-decoration:none;flex-shrink:0}
.pv-back-btn:hover{border-color:var(--accent);color:var(--accent)}

/* ─── PORTADA CARD (imagen referencia 1) ─── */
.pv-portada-wrap{max-width:900px;margin:28px auto 0;padding:0 24px}
.pv-portada-card{background:var(--surface);border-radius:var(--radius);overflow:hidden;box-shadow:var(--shadow);border:1px solid var(--border)}
.pv-portada-img{width:100%;height:220px;object-fit:cover;display:block}
.pv-portada-img-placeholder{width:100%;height:220px;background:linear-gradient(135deg,#1a1a2e,#2d2044);display:flex;align-items:center;justify-content:center;font-size:52px;color:rgba(255,255,255,0.25)}
.pv-portada-title-row{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:18px 22px 14px}
.pv-portada-title{font-family:'Poppins',sans-serif;font-size:21px;font-weight:800;color:var(--text)}
.pv-status-badge{display:inline-flex;align-items:center;gap:6px;background:#fefce8;border:1.5px solid #fde047;border-radius:30px;padding:5px 13px;font-size:11px;font-weight:700;color:#854d0e;letter-spacing:.3px;white-space:nowrap;flex-shrink:0}
.pv-status-dot{width:7px;height:7px;border-radius:50%;background:#eab308}
.pv-portada-meta-row{display:grid;grid-template-columns:repeat(3,1fr);border-top:1px solid var(--border)}
.pv-portada-meta-cell{padding:16px 22px;text-align:center;border-right:1px solid var(--border)}
.pv-portada-meta-cell:last-child{border-right:none}
.pv-pm-label{font-size:10px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:var(--dim);margin-bottom:5px}
.pv-pm-value{font-size:15px;font-weight:700;color:var(--text)}
.pv-pm-value.highlight{color:#0e7aad}

/* LAYOUT */
.pv-layout{display:grid;grid-template-columns:250px 1fr;max-width:1100px;margin:0 auto;padding:32px 24px 60px;align-items:start;gap:0}

/* SIDEBAR NAV */
.pv-nav{position:sticky;top:68px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;box-shadow:var(--shadow)}
.pv-nav-title{font-size:10px;font-weight:700;letter-spacing:1.4px;text-transform:uppercase;color:var(--dim);padding:14px 18px 11px;border-bottom:1px solid var(--border)}
.pvnav-link{display:flex;align-items:baseline;gap:8px;padding:9px 18px;font-size:13px;font-weight:500;color:var(--muted);text-decoration:none;transition:all .14s;border-left:3px solid transparent}
.pvnav-link:hover{background:#f8f9fb;color:#0e7aad;border-left-color:#0e7aad}
.pvnav-link.active{background:#eff6ff;color:#0e7aad;border-left-color:#0e7aad;font-weight:600}
.pvnav-num{font-size:10px;font-weight:700;color:var(--dim);min-width:34px;letter-spacing:.4px}
.pvnav-link.active .pvnav-num{color:#0e7aad}
.pv-nav-stats{padding:12px 18px;border-top:1px solid var(--border)}
.pvstat{display:flex;justify-content:space-between;padding:4px 0}
.pvstat-label{font-size:12px;color:var(--muted)}
.pvstat-val{font-size:12px;font-weight:700;color:var(--text)}

/* CONTENT */
.pv-content{padding-left:26px;display:flex;flex-direction:column;gap:36px}

/* DAY SECTION */
.pvday-header{display:flex;align-items:center;gap:12px;margin-bottom:16px;padding-bottom:12px;border-bottom:2px solid var(--border);position:sticky;top:62px;background:var(--bg);z-index:10;padding-top:8px}
.pvday-pill{background:#0e7aad;color:#fff;font-size:10px;font-weight:700;letter-spacing:.7px;text-transform:uppercase;padding:4px 11px;border-radius:20px}
.pvday-title{font-family:'Poppins',sans-serif;font-size:18px;font-weight:800;color:var(--text);flex:1}
.pvday-count{font-size:11px;color:var(--dim);background:var(--border);border-radius:10px;padding:3px 10px}
.pvday-items{display:flex;flex-direction:column;gap:12px}

/* ─── BASE CARD ─── */
.pv-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:18px 20px;box-shadow:var(--shadow)}
.pvc-section-label{font-size:13px;font-weight:700;margin-bottom:14px}

/* EMPTY / DESIGN ITEMS */
.pv-empty{padding:24px;text-align:center;color:var(--dim);font-size:13px;border:2px dashed var(--border);border-radius:var(--radius)}
.pv-sep{display:flex;align-items:center;gap:10px;padding:4px 0}
.pvs-line{flex:1;height:1px;background:var(--border)}
.pvs-label{font-size:11px;color:var(--dim);padding:0 6px;white-space:nowrap}
.pv-titulo{padding:18px 20px;background:var(--surface);border-radius:var(--radius);border:1px solid var(--border);box-shadow:var(--shadow)}
.pvt-text{font-family:'Poppins',sans-serif;font-size:19px;font-weight:800;color:var(--text)}
.pvt-sub{font-size:13px;color:var(--muted);margin-top:4px}
.pv-texto{padding:14px 18px;background:var(--surface);border-radius:var(--radius);border:1px solid var(--border);font-size:14px;color:var(--muted);line-height:1.7;box-shadow:var(--shadow)}
.pv-imagen{border-radius:var(--radius);overflow:hidden;border:1px solid var(--border);box-shadow:var(--shadow)}
.pv-imagen img{width:100%;max-height:400px;object-fit:cover;display:block}
.pv-img-ph{height:150px;display:flex;align-items:center;justify-content:center;font-size:34px;background:var(--surface)}
.pv-caption{padding:9px 14px;font-size:12px;color:var(--muted);text-align:center;border-top:1px solid var(--border);background:var(--surface)}
.pv-caja{display:flex;gap:13px;align-items:flex-start;padding:15px 18px;border-radius:var(--radius);background:var(--surface);box-shadow:var(--shadow)}
.pvc-caja-icon{font-size:20px;flex-shrink:0;margin-top:1px}
.pvc-caja-title{font-size:14px;font-weight:700;margin-bottom:3px;color:var(--text)}
.pvc-caja-content{font-size:13px;color:var(--muted);line-height:1.55}

/* ─── RUTA (VUELO / TRANSPORTE) ─── */
.pv-route-row{display:grid;grid-template-columns:1fr 44px 1fr;align-items:center;gap:10px;margin:6px 0 14px}
.pv-route-end{display:flex;flex-direction:column;gap:2px}
.pv-route-right{text-align:right;align-items:flex-end}
.pv-route-time{font-family:'Poppins',sans-serif;font-size:24px;font-weight:800;color:var(--text);letter-spacing:-.5px}
.pv-route-station{font-size:14px;font-weight:700;color:var(--text);line-height:1.3}
.pv-station-big{font-size:13px;font-weight:700;max-width:160px;line-height:1.3}
.pv-route-sub{font-size:11.5px;color:var(--dim);margin-top:1px}
.pv-route-mid{display:flex;align-items:center;justify-content:center}
.pv-airline-row{display:flex;align-items:center;gap:8px;margin-bottom:8px}
.pv-airline-name{font-size:13px;font-weight:600;color:var(--muted)}
.pv-flight-code{background:#0ea5d8;color:#fff;font-size:11px;font-weight:700;padding:3px 9px;border-radius:6px;letter-spacing:.3px}

/* ─── CHIPS ─── */
.pv-chips-row{display:flex;flex-wrap:wrap;gap:7px}
.pv-chip{background:#f1f5f9;border:1px solid var(--border);border-radius:8px;padding:4px 10px;font-size:12px;color:var(--muted);font-weight:500}
.pv-notes-row{margin-top:11px;font-size:12.5px;color:var(--dim);border-top:1px solid var(--border);padding-top:10px;line-height:1.5}

/* ─── HOTEL / ACTIVIDAD / COMIDA / TOUR – layout media ─── */
.pv-hotel-layout{display:grid;grid-template-columns:230px 1fr;gap:16px;align-items:start}
.pv-hotel-photo-slot{border-radius:10px;overflow:hidden;height:240px}
.pv-hotel-photo-ph{width:100%;height:100%;background:linear-gradient(135deg,#e0e7ff,#c7d2fe);display:flex;align-items:center;justify-content:center;font-size:38px}
.pv-hotel-info-col{display:flex;flex-direction:column;gap:0}
.pv-hotel-title-row{margin-bottom:4px}
.pv-hotel-name{font-size:17px;font-weight:700;color:var(--text);line-height:1.2;margin-bottom:4px}
.pv-hotel-addr{font-size:12px;color:var(--muted);display:flex;align-items:flex-start;gap:4px;margin-bottom:10px}
.pv-stars-row{display:flex;align-items:center;gap:2px;margin-bottom:10px}
.pv-stars-score{font-size:12.5px;color:var(--muted);margin-left:4px;font-weight:600}
.pv-hotel-details{display:flex;flex-direction:column;gap:5px;margin-bottom:12px}
.pv-hd-row{font-size:13px;color:var(--text)}
.pv-hd-row .pv-hd-label{font-weight:600}
.pv-hd-icon-row{font-size:13px;color:var(--muted);display:flex;align-items:center;gap:5px}
.pv-hotel-btns{display:flex;gap:8px;flex-wrap:wrap;margin-top:4px}
.pv-action-btn{padding:7px 14px;background:#0e7aad;color:#fff;border:none;border-radius:20px;font-size:12.5px;font-weight:600;cursor:pointer;font-family:'Poppins',sans-serif;transition:opacity .14s;display:inline-flex;align-items:center;gap:5px;text-decoration:none}
.pv-action-btn:hover{opacity:.88}
.pv-action-btn-blue{background:#0e7aad}

/* ACTIVIDAD/COMIDA/TOUR media layout */
.pv-media-layout{display:grid;grid-template-columns:230px 1fr;gap:16px;align-items:start}
.pv-media-photo-slot{border-radius:10px;overflow:hidden;height:240px}
.pv-media-photo-ph{width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:38px}
.pv-media-info-col{display:flex;flex-direction:column}
.pv-media-title-row{margin-bottom:4px}
.pv-media-name{font-size:17px;font-weight:700;color:var(--text);line-height:1.2;margin-bottom:5px}
.pv-media-addr{font-size:12px;color:var(--muted);display:flex;align-items:flex-start;gap:4px;margin-bottom:7px}
.pv-media-time{font-size:13px;color:var(--muted);display:flex;align-items:center;gap:5px;margin-bottom:6px;font-weight:500}
.pv-media-desc{font-size:12.5px;color:var(--muted);line-height:1.6;margin-top:6px}

/* CIERRE */
.pv-cierre{background:linear-gradient(135deg,#0f172a,#1e1b4b,#0c2340);border-radius:var(--radius);padding:40px 32px;text-align:center;color:#fff;display:flex;flex-direction:column;align-items:center;gap:12px;box-shadow:var(--shadow)}
.pv-cierre-plane{font-size:50px;animation:float 3s ease-in-out infinite}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
.pv-cierre-badge{background:var(--accent-light);border:1px solid rgba(14,165,233,.4);border-radius:20px;padding:5px 15px;font-size:11px;font-weight:700;color:var(--primary-blue);letter-spacing:.8px;text-transform:uppercase}
.pv-cierre-title{font-family:'Poppins',sans-serif;font-size:24px;font-weight:800;color:#fff}
.pv-cierre-sub{font-size:13px;color:rgba(255,255,255,.5);max-width:360px;line-height:1.6}
.pv-cierre-stats{display:flex;gap:22px;margin-top:6px}
.pvcs{text-align:center}
.pvcs-n{font-family:'Poppins',sans-serif;font-size:24px;font-weight:800;color:var(--primary-blue)}
.pvcs-l{font-size:10px;color:rgba(255,255,255,.4);letter-spacing:1px;text-transform:uppercase;margin-top:2px}

/* PORTADA / CIERRE EXTRA ITEMS */
.pv-portada-extra-items{max-width:900px;margin:12px auto 0;padding:0 24px;display:flex;flex-direction:column;gap:12px}
.pv-cierre-extra-items{display:flex;flex-direction:column;gap:12px;margin-top:16px}

/* MOBILE */
@media(max-width:760px){
  .pv-layout{grid-template-columns:1fr;padding:20px 16px 48px}
  .pv-nav{display:none}
  .pv-content{padding-left:0}
  .pv-topbar{padding:0 16px}
  .pvday-header{top:52px}
  .pv-portada-meta-row{grid-template-columns:1fr}
  .pv-portada-meta-cell{border-right:none;border-bottom:1px solid var(--border);text-align:left;padding:12px 18px}
  .pv-portada-meta-cell:last-child{border-bottom:none}
  .pv-portada-wrap{padding:0 14px}
  .pv-portada-extra-items{padding:0 14px}
  .pv-hotel-layout,.pv-media-layout{grid-template-columns:1fr}
  .pv-hotel-photo-slot,.pv-media-photo-slot{height:180px}
  .pv-route-time{font-size:18px}
  .pv-station-big{font-size:12px;max-width:120px}
  .pv-cierre{padding:28px 20px}
  .pv-cierre-title{font-size:18px}
}
@media(max-width:420px){
  .pv-portada-title{font-size:16px}
  .pv-route-row{gap:6px}
  .pv-card{padding:14px 14px}
}
/* Animations */
.pv-day{animation:fadeUp .35s ease both}
@keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:none}}
.pv-day:nth-child(1){animation-delay:.04s}.pv-day:nth-child(2){animation-delay:.08s}.pv-day:nth-child(3){animation-delay:.12s}.pv-day:nth-child(4){animation-delay:.16s}.pv-day:nth-child(5){animation-delay:.2s}
</style>
</head>
<body>
<div class="pv-topbar">
  <div class="pv-logo">✦ itinerai</div>
  <div class="pv-topbar-title">${title}</div>
  <button class="pv-back-btn" onclick="window.close()"><i class="fa-solid fa-times"></i> Cerrar</button>
</div>

${hasPortada ? `
<div class="pv-portada-wrap">
  <div class="pv-portada-card">
    ${portadaPhotoUrl
        ? `<img class="pv-portada-img" src="${portadaPhotoUrl}" alt="${title}">`
        : `<div class="pv-portada-img-placeholder"><i class="fa-solid fa-earth-americas"></i></div>`
      }
    <div class="pv-portada-title-row">
      <div class="pv-portada-title">${title}</div>
      <div class="pv-status-badge"><span class="pv-status-dot"></span> RESERVADO</div>
    </div>
    <div class="pv-portada-meta-row">
      <div class="pv-portada-meta-cell">
        <div class="pv-pm-label">Fechas</div>
        <div class="pv-pm-value">${fechaInicio && fechaFin ? fmtDateShort(fechaInicio) + ' — ' + fmtDateShort(fechaFin) : (fechaInicio ? 'Desde ' + fmtDateShort(fechaInicio) : 'Por definir')}</div>
      </div>
      <div class="pv-portada-meta-cell">
        <div class="pv-pm-label">Viajeros</div>
        <div class="pv-pm-value">${totalViajeros > 0 ? totalViajeros + ' persona' + (totalViajeros !== 1 ? 's' : '') : '—'}</div>
      </div>
      <div class="pv-portada-meta-cell">
        <div class="pv-pm-label">Total</div>
        <div class="pv-pm-value highlight">${precio ? moneda + ' $' + Number(precio).toLocaleString('es', { minimumFractionDigits: 2 }) : '—'}</div>
      </div>
    </div>
  </div>
  ${portadaItems && portadaItems.length ? `<div class="pv-portada-extra-items">${renderPreviewItems(portadaItems)}</div>` : ''}
</div>
`: ''}

<div class="pv-layout">
  <aside class="pv-nav">
    <div class="pv-nav-title">Itinerario</div>
    ${sidebarNav}
    <div class="pv-nav-stats">
      ${(portadaAdultos > 0 || portadaNinos > 0) ? `
        <div class="pvstat">
          <span class="pvstat-label"><i class="fa-solid fa-users"></i> Viajeros</span>
          <span class="pvstat-val">
            ${portadaAdultos > 0 ? `${portadaAdultos} adulto${portadaAdultos > 1 ? 's' : ''}` : ''}
            ${portadaAdultos > 0 && portadaNinos > 0 ? ' + ' : ''}
            ${portadaNinos > 0 ? `${portadaNinos} niño${portadaNinos > 1 ? 's' : ''}` : ''}
          </span>
        </div>
      ` : ''}
      ${precio ? `<div class="pvstat"><span class="pvstat-label"><i class="fa-solid fa-sack-dollar"></i> Total viaje</span><span class="pvstat-val">${moneda} $${Number(precio).toLocaleString('es')}</span></div>` : ''}
      <div class="pvstat"><span class="pvstat-label"><i class="fa-regular fa-calendar-days"></i> Días</span><span class="pvstat-val">${numericTabs.length}</span></div>
    </div>
  </aside>
  <main class="pv-content">
    ${daysHTML}
    ${hasCierre ? `
      ${showDefaultCierre ? `
      <div class="pv-cierre">
        <div class="pv-cierre-plane"><i class="fa-solid fa-plane"></i></div>
        <div class="pv-cierre-badge">¡ITINERARIO COMPLETO!</div>
        <div class="pv-cierre-title">${title}</div>
        <div class="pv-cierre-sub">Este itinerario fue creado por <b>${window.viantrypUserName || 'Viantryp'}</b>.<br>¡Que tengas un viaje extraordinario!</div>
      </div>
      ` : ''}
    ${cierreItems && cierreItems.length ? `<div class="pv-cierre-extra-items">${renderPreviewItems(cierreItems)}</div>` : ''}
    `: ''}
  </main>
</div>
<script>
const links=document.querySelectorAll('.pvnav-link');
const sections=document.querySelectorAll('.pv-day');

// Smooth scroll with offset for sticky topbar
links.forEach(link=>{
  link.addEventListener('click',e=>{
    e.preventDefault();
    const target=document.querySelector(link.getAttribute('href'));
    if(!target)return;
    const offset=72;
    const top=target.getBoundingClientRect().top+window.scrollY-offset;
    window.scrollTo({top,behavior:'smooth'});
  });
});

const obs=new IntersectionObserver(entries=>{entries.forEach(e=>{if(e.isIntersecting){const id=e.target.id;links.forEach(l=>{l.classList.toggle('active',l.getAttribute('href')==='#'+id)})}})},{threshold:.25,rootMargin:'-60px 0px -40% 0px'});
sections.forEach(s=>obs.observe(s));
</script>
</body>
</html>`;
}

renderCanvas();

function toggleSidebar() {
  const sb = document.querySelector('.sidebar');
  const ov = document.getElementById('sidebarOverlay');
  sb.classList.toggle('open');
  ov.classList.toggle('open');
}