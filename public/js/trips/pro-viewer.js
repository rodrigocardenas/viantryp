function buildPreviewHTML(data) {
  const { title, fechaInicio, fechaFin, precio, moneda, totalViajeros, hasPortada, hasCierre, showDefaultCierre, totalItems, numericTabs, days, dayDates, portadaAdultos, portadaNinos, portadaPhotoUrl, portadaItems, cierreItems, isPublicLink, csrfToken, tripId, userName, status, origin, themeColor, displayNameType, agencyLogo, agencyName, userFullName, googleClientId } = data;

  const statusMap = {
    'draft': { label: 'En Diseño', bg: '#e0f2fe', color: '#1d5fa8', bdr: '#bae6fd' },
    'sent': { label: 'Propuesta', bg: '#e8f8ff', color: '#0284c7', bdr: '#bae6fd' },
    'reserved': { label: 'Pagado Parcialmente', bg: '#dcfce7', color: '#15803d', bdr: '#bbf7d0' },
    'completed': { label: 'Pago Completo', bg: '#eef2f6', color: '#0f766e', bdr: '#cbd5e1' },
    'discarded': { label: 'Descartado', bg: '#fee2e2', color: '#b43030', bdr: '#fecaca' }
  };
  const sObj = statusMap[status] || statusMap['draft'];
  const statusBadgeHTML = `<div class="pv-status-badge" style="background:${sObj.bg}; color:${sObj.color}; border-color:${sObj.bdr}"><span class="pv-status-dot" style="background:${sObj.color}"></span> ${sObj.label.toUpperCase()}</div>`;

  const adjustColor = (hex, amt) => {
    let col = hex.replace('#', '');
    let r = parseInt(col.substring(0, 2), 16) + amt;
    let g = parseInt(col.substring(2, 4), 16) + amt;
    let b = parseInt(col.substring(4, 6), 16) + amt;
    r = Math.max(0, Math.min(255, r)).toString(16).padStart(2, '0');
    g = Math.max(0, Math.min(255, g)).toString(16).padStart(2, '0');
    b = Math.max(0, Math.min(255, b)).toString(16).padStart(2, '0');
    return '#' + r + g + b;
  };

  const themes = {
    'default': '#1a7f77',
    'ocean': '#1a5f8f',
    'gold': '#b08000',
    'sunset': '#c0552a',
    'blush': 'linear-gradient(135deg,#e07b9a,#f4a5bd)',
    'silver': 'linear-gradient(135deg,#6e7f80,#9aa8a9)',
    'mint': 'linear-gradient(135deg,#3db898,#62d4b5)',
    'lavender': 'linear-gradient(135deg,#9b72cf,#b39ddb)'
  };
  const currentTheme = themes[themeColor] || themes['default'];
  const isGradient = currentTheme.includes('gradient');

  const cierreGradients = {
    'default': 'linear-gradient(185deg, #0f172a, #1a7f77, #10a6b1)',
    'ocean': 'linear-gradient(185deg, #091a2a, #1a5f8f, #2a7fb9)',
    'gold': 'linear-gradient(185deg, #1a1400, #b08000, #d4a017)',
    'sunset': 'linear-gradient(185deg, #1a1005, #c0552a, #d35400)'
  };

  const accentBackground = isGradient ? currentTheme : (cierreGradients[themeColor] || `linear-gradient(23deg, ${adjustColor(currentTheme, -40)}, ${currentTheme})`);
  const primaryBlue = isGradient ? '#fff' : currentTheme;
  const accentLight = isGradient ? 'rgba(255,255,255,0.2)' : adjustColor(currentTheme, 180);

  const fmtDateShort = s => { if (!s) return ''; try { return new Date(s + 'T00:00:00').toLocaleDateString('es', { day: 'numeric', month: 'short' }) } catch { return s } };
  const fmtDateTime = s => { if (!s) return ''; try { const d = new Date(s); const day = d.toLocaleDateString('es', { weekday: 'long', day: 'numeric', month: 'long' }); const time = d.toLocaleTimeString('es', { hour: '2-digit', minute: '2-digit' }); return { day, time } } catch { return { day: s, time: '' } } };
  const fmtDateDetail = s => { if (!s) return ''; try { return new Date(s + 'T00:00:00').toLocaleDateString('es', { day: '2-digit', month: '2-digit', year: '2-digit' }) } catch { return s } };
  const fmtDayMonth = s => { if (!s) return ''; try { const d = new Date(s + 'T00:00:00'); return d.toLocaleDateString('es', { day: 'numeric', month: 'long' }); } catch { return s } };
  const fmtDayMonthWeekday = s => { if (!s) return ''; try { const dateStr = s.includes('T') || s.includes(' ') ? s : s + 'T00:00:00'; const d = new Date(dateStr); return d.toLocaleDateString('es', { weekday: 'long', day: 'numeric', month: 'long' }); } catch { return s; } };
  const getVideoEmbedUrl = url => {
    if (!url) return null;
    let match = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/);
    if (match && match[1]) return `https://www.youtube.com/embed/${match[1]}`;
    match = url.match(/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/(?:[^\/]*)\/videos\/|album\/(?:\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)/);
    if (match && match[1]) return `https://player.vimeo.com/video/${match[1]}`;
    return null;
  };
  const starsHTML = n => n ? Array.from({ length: 5 }, (_, i) => `<svg width="16" height="16" viewBox="0 0 24 24" fill="${i < n ? '#f59e0b' : '#d1d5db'}"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>`).join('') : '';
  const fixUrl = u => {
    if (!u || !window.shareToken) return u;

    // Si ya tiene el token, no lo duplicamos
    if (u.includes('token=')) return u;

    // Detectar si es una URL interna de descarga de documentos
    // Puede venir como /documents/X/download o como https://dominio.com/documents/X/download
    const isInternal = u.includes('/documents/') && u.includes('/download');

    if (isInternal) {
      return u + (u.includes('?') ? '&' : '?') + 'token=' + window.shareToken;
    }
    return u;
  };

  const cCarousel = (photo_url, icon) => {
    if (!photo_url) return `<div class="pv-hotel-photo-ph">${icon}</div>`;
    let urls = photo_url.split(',').filter(u => u.trim());
    urls = urls.slice(0, 3); // Limit to 3 photos for cost optimization
    if (urls.length === 1) return `<img src="${fixUrl(urls[0])}" style="width:100%;height:100%;object-fit:cover" loading="lazy" />`;
    const slides = urls.map((u, i) => `<div class="pv-carousel-slide" style="display:${i === 0 ? 'block' : 'none'};width:100%;height:100%;"><img src="${fixUrl(u)}" style="width:100%;height:100%;object-fit:cover" loading="lazy" /></div>`).join('');
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
        return `<div class="pv-imagen">${d.url ? `<img src="${fixUrl(d.url)}" alt="${d.caption || ''}">` : '<div class="pv-img-ph"><i class="fa-regular fa-image"></i></div>'}${d.caption ? `<div class="pv-caption">${d.caption}</div>` : ''}</div>`;
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

        // Use d.origen_city/d.destino_city directly if it exists, otherwise fallback to parsing origin
        const getCity = str => str ? (str.includes('(') ? str.split('(')[0].trim() : str.split(' -')[0].trim()) : '';
        const oriCity = d.origen_city || getCity(d.origen);
        const desCity = d.destino_city || getCity(d.destino);

        return `<div class="pv-card">
          <div class="pvc-section-label" style="color:var(--accent); display:flex; justify-content:space-between; align-items:center;">
             <span><i class="fa-solid fa-plane"></i> Vuelo ${oriCity && desCity ? oriCity + ' → ' + desCity : ''}</span>
             <div class="pv-flight-header-details" style="display:flex; align-items:center; gap:8px;">
               ${d.aerolinea ? `<span style="font-weight:400; opacity:0.8">${d.aerolinea}</span>` : ''}
               ${d.vuelo ? `<span style="background:var(--accent); color:#fff; padding:2px 8px; border-radius:6px; font-size:11px; font-weight:600; text-transform:uppercase;">${d.vuelo}</span>` : ''}
             </div>
          </div>
          <div class="pv-route-row pv-flight-route">
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
          <div class="pv-chips-row">
            ${d.clase ? `<span class="pv-chip"><i class="fa-solid fa-couch"></i> ${d.clase}</span>` : ''}
            ${d.precio ? `<span class="pv-chip"><i class="fa-solid fa-tag"></i> $${d.precio} USD</span>` : ''}
          </div>
          ${d.reserva ? `<div class="pv-notes-row" style="border-top:none; padding:10px 0; margin-top:8px;"><i class="fa-solid fa-ticket" style="margin-right:2px"></i> <b>Código de Reserva:</b> ${d.reserva}</div>` : ''}
          ${d.notas ? `<div class="pv-notes-row" style="color:#64748b;"><i class="fa-solid fa-circle-info"></i> ${d.notas}</div>` : ''}
          ${d.adjunto_url ? `
            <div style="margin-top:12px; padding-top:12px; border-top:1px solid var(--border);">
              <a href="${fixUrl(d.adjunto_url)}" target="_blank" style="display:inline-flex; align-items:center; gap:8px; padding:8px 20px; border:1px solid #d1d5db; border-radius:30px; background:#fff; color:#1f2937; text-decoration:none; font-weight:700; font-size:12.5px; box-shadow:0 1px 2px rgba(0,0,0,0.05);">
                <i class="fa-solid fa-paperclip"></i> ${d.adjunto_name || 'Ver adjunto'}
              </a>
            </div>
          ` : ''}
        </div>`;
      }

      // ─────────────────────────────────────────────────────
      // ── ALOJAMIENTO  (referencia: imagen 3) ──────────────
      // foto izq + info derecha, check-in/out, habitación, desayuno
      // ─────────────────────────────────────────────────────
      if (item.type === 'alojamiento') {
        const nights = d.checkin && d.checkout ? Math.round((new Date(d.checkout) - new Date(d.checkin)) / (1000 * 60 * 60 * 24)) : null;
        const cIn = d.checkin ? fmtDateTime(d.checkin.includes('T') || d.checkin.includes(' ') ? d.checkin : d.checkin + 'T15:00:00') : null;
        const cOut = d.checkout ? fmtDateTime(d.checkout.includes('T') || d.checkout.includes(' ') ? d.checkout : d.checkout + 'T12:00:00') : null;
        return `<div class="pv-card">
          <div class="pvc-section-label" style="color:var(--accent)"><i class="fa-solid fa-hotel"></i> Alojamiento</div>
          <div class="pv-hotel-layout">
            <div class="pv-hotel-photo-slot">${cCarousel(d.photo_url, '<i class="fa-solid fa-hotel"></i>')}</div>
            <div class="pv-hotel-info-col">
              <div class="pv-hotel-title-row">
                <div class="pv-hotel-name">
                  <a href="https://www.google.com/maps/search/?api=1&query=${encodeURIComponent((d.nombre || 'Hotel') + ' ' + (d.direccion || ''))}" target="_blank" class="pv-map-link">
                    ${d.nombre || 'Hotel'} <i class="fa-solid fa-up-right-from-square"></i>
                  </a>
                </div>
                ${d.stars ? `<div class="pv-stars-row">${starsHTML(d.stars)}<span class="pv-stars-score">(${Number.isInteger(d.stars) ? d.stars + '.0' : d.stars})</span></div>` : ''}
              </div>
              ${d.direccion ? `<div class="pv-hotel-addr"><i class="fa-solid fa-location-dot" style="color:var(--muted)"></i> ${d.direccion}</div>` : ''}
              <div class="pv-hotel-details">
                ${cIn ? `<div class="pv-hd-row"><span class="pv-hd-label">Check-in:</span> ${cIn.day}${cIn.time ? ' a las ' + cIn.time : ''}</div>` : ''}
                ${cOut ? `<div class="pv-hd-row"><span class="pv-hd-label">Check-out:</span> ${cOut.day}${cOut.time ? ' a las ' + cOut.time : ''}</div>` : ''}
                ${(nights || d.habitacion || d.alimentacion) ? `
                <div style="display:flex; flex-wrap:wrap; align-items:center; gap:14px; margin-top:2px;">
                  ${nights ? `<div class="pv-hd-icon-row"><i class="fa-solid fa-moon" style="width:14px;text-align:center;font-size:12px"></i> <span>${nights} noche${nights !== 1 ? 's' : ''}</span></div>` : ''}
                  ${d.habitacion ? `<div class="pv-hd-icon-row"><i class="fa-solid fa-bed" style="width:14px;text-align:center;font-size:12px"></i> <span>${d.habitacion}</span></div>` : ''}
                  ${d.alimentacion ? `<div class="pv-hd-icon-row"><i class="fa-solid fa-utensils" style="width:14px;text-align:center;font-size:12px"></i> <span>${d.alimentacion}</span></div>` : ''}
                </div>
                ` : ''}
              </div>
              <div class="pv-hotel-btns">
                ${d.website ? `<a href="${d.website}" target="_blank" class="pv-action-btn" style="text-decoration:none"><i class="fa-solid fa-globe"></i> Sitio web</a>` : ''}
                ${d.phone ? `<a href="tel:${d.phone}" class="pv-action-btn" style="text-decoration:none"><i class="fa-solid fa-phone"></i> ${d.phone}</a>` : ''}
                ${d.precio ? `<span class="pv-action-btn"><i class="fa-solid fa-tag"></i> $${d.precio} USD</span>` : ''}
              </div>
              ${d.reserva ? `<div class="pv-notes-row" style="border-top:none; padding:10px 0; margin-top:8px;"><i class="fa-solid fa-ticket" style="margin-right:2px"></i> <b>Código de Reserva:</b> ${d.reserva}</div>` : ''}
              ${d.notas ? `<div class="pv-notes-row" style="color:#64748b;"><i class="fa-solid fa-circle-info"></i> ${d.notas}</div>` : ''}
            </div>
          </div>
          ${d.adjunto_url ? `
            <div style="margin-top:12px; padding-top:12px; border-top:1px solid var(--border);">
              <a href="${fixUrl(d.adjunto_url)}" target="_blank" style="display:inline-flex; align-items:center; gap:8px; padding:8px 20px; border:1px solid #d1d5db; border-radius:30px; background:#fff; color:#1f2937; text-decoration:none; font-weight:700; font-size:12.5px; box-shadow:0 1px 2px rgba(0,0,0,0.05);">
                <i class="fa-solid fa-paperclip"></i> ${d.adjunto_name || 'Ver adjunto'}
              </a>
            </div>
          ` : ''}
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
          <div class="pvc-section-label" style="color:var(--accent); display:flex; justify-content:space-between; align-items:center;">
            <div style="display:flex; align-items:center; gap:8px;">${tIconHeader} ${tLabel}</div>
            ${d.proveedor ? `<span style="background:var(--accent); color:#fff; padding:2px 8px; border-radius:6px; font-size:11px; font-weight:600; text-transform:uppercase;">${d.proveedor}</span>` : ''}
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
          <div class="pv-chips-row">
            ${d.precio ? `<span class="pv-chip"><i class="fa-solid fa-tag"></i> $${d.precio} USD</span>` : ''}
          </div>
          ${d.reserva ? `<div class="pv-notes-row" style="border-top:none; padding:10px 0; margin-top:8px;"><i class="fa-solid fa-ticket" style="margin-right:2px"></i> <b>Código de Reserva:</b> ${d.reserva}</div>` : ''}
          ${d.notas ? `<div class="pv-notes-row" style="color:#64748b;"><i class="fa-solid fa-circle-info"></i> ${d.notas}</div>` : ''}
          ${d.adjunto_url ? `
            <div style="margin-top:12px; padding-top:12px; border-top:1px solid var(--border);">
              <a href="${fixUrl(d.adjunto_url)}" target="_blank" style="display:inline-flex; align-items:center; gap:8px; padding:8px 20px; border:1px solid #d1d5db; border-radius:30px; background:#fff; color:#1f2937; text-decoration:none; font-weight:700; font-size:12.5px; box-shadow:0 1px 2px rgba(0,0,0,0.05);">
                <i class="fa-solid fa-paperclip"></i> ${d.adjunto_name || 'Ver adjunto'}
              </a>
            </div>
          ` : ''}
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
          <div class="pvc-section-label" style="color:var(--accent)"><i class="fa-solid fa-bullseye"></i> Actividad</div>
          <div class="pv-media-layout">
            <div class="pv-media-photo-slot">${cCarousel(d.photo_url, '<i class="fa-solid fa-bullseye"></i>')}</div>
            <div class="pv-media-info-col">
              <div class="pv-media-name" style="font-weight:700; font-size:16px; margin-bottom:2px;">
                <a href="https://www.google.com/maps/search/?api=1&query=${encodeURIComponent((d.nombre || 'Actividad') + ' ' + (d.direccion || d.lugar || ''))}" target="_blank" class="pv-map-link">
                  ${d.nombre || 'Actividad'} <i class="fa-solid fa-up-right-from-square"></i>
                </a>
              </div>
              ${d.direccion || d.lugar ? `<div class="pv-media-addr" style="color:#666; font-weight:500; font-size:13px; margin-bottom:4px;"><i class="fa-solid fa-location-dot" style="color:var(--muted); margin-right:4px;"></i>${d.direccion || d.lugar}</div>` : ''}
              ${d.stars ? `<div class="pv-stars-row" style="margin-bottom:8px;">${starsHTML(d.stars)} <span class="pv-stars-score" style="font-size:12px; opacity:0.8;">(${Number.isInteger(d.stars) ? d.stars + '.0' : d.stars})</span></div>` : ''}
              
              ${timeRange ? `<div class="pv-media-time"><i class="fa-solid fa-clock"></i> ${timeRange.replace(' - ', ' - Duración : ')}</div>` : ''}
              ${d.descripcion ? `<div class="pv-media-desc">${d.descripcion}</div>` : ''}
              <div class="pv-hotel-btns" style="margin-top:10px">
                ${d.website ? `<a href="${d.website}" target="_blank" class="pv-action-btn"><i class="fa-solid fa-globe"></i> Sitio web</a>` : ''}
                ${d.phone ? `<a href="tel:${d.phone}" class="pv-action-btn"><i class="fa-solid fa-phone"></i> ${d.phone}</a>` : ''}
                ${d.precio ? `<span class="pv-action-btn"><i class="fa-solid fa-tag"></i> $${d.precio} USD</span>` : ''}
              </div>
              ${d.reserva ? `<div class="pv-notes-row" style="border-top:none; padding:10px 0; margin-top:8px;"><i class="fa-solid fa-ticket" style="margin-right:2px"></i> <b>Código de Reserva:</b> ${d.reserva}</div>` : ''}
              ${d.notas ? `<div class="pv-notes-row" style="color:#64748b;"><i class="fa-solid fa-circle-info"></i> ${d.notas}</div>` : ''}
            </div>
          </div>
          ${d.adjunto_url ? `
            <div style="margin-top:12px; padding-top:12px; border-top:1px solid var(--border);">
              <a href="${fixUrl(d.adjunto_url)}" target="_blank" style="display:inline-flex; align-items:center; gap:8px; padding:8px 20px; border:1px solid #d1d5db; border-radius:30px; background:#fff; color:#1f2937; text-decoration:none; font-weight:700; font-size:12.5px; box-shadow:0 1px 2px rgba(0,0,0,0.05);">
                <i class="fa-solid fa-paperclip"></i> ${d.adjunto_name || 'Ver adjunto'}
              </a>
            </div>
          ` : ''}
        </div>`;
      }

      if (item.type === 'comida') {
        const dt = d.fecha ? fmtDateTime(d.fecha) : { day: '', time: '' };
        return `<div class="pv-card">
          <div class="pvc-section-label" style="color:var(--accent)"><i class="fa-solid fa-utensils"></i> Comida${d.tipo ? ' · ' + d.tipo : ''}</div>
          <div class="pv-media-layout">
            <div class="pv-media-photo-slot">${cCarousel(d.photo_url, '<i class="fa-solid fa-utensils"></i>')}</div>
            <div class="pv-media-info-col">
              <div class="pv-media-title-row">
                <div class="pv-media-name">
                  <a href="https://www.google.com/maps/search/?api=1&query=${encodeURIComponent((d.restaurante || 'Restaurante') + ' ' + (d.direccion || d.ciudad || ''))}" target="_blank" class="pv-map-link">
                    ${d.restaurante || 'Restaurante'} <i class="fa-solid fa-up-right-from-square"></i>
                  </a>
                </div>
                ${d.stars ? `<div class="pv-stars-row">${starsHTML(d.stars)}<span class="pv-stars-score">(${Number.isInteger(d.stars) ? d.stars + '.0' : d.stars})</span></div>` : ''}
              </div>
              ${d.direccion || d.ciudad ? `<div class="pv-media-addr"><i class="fa-solid fa-location-dot" style="color:var(--muted)"></i> ${d.direccion || d.ciudad}</div>` : ''}
              ${dt.day ? `<div class="pv-media-time"><i class="fa-solid fa-clock"></i> ${dt.day}${dt.time ? ' · ' + dt.time : ''}</div>` : ''}
              <div class="pv-hotel-btns" style="margin-top:10px">
                ${d.website ? `<a href="${d.website}" target="_blank" class="pv-action-btn"><i class="fa-solid fa-globe"></i> Sitio web</a>` : ''}
                ${d.phone ? `<a href="tel:${d.phone}" class="pv-action-btn"><i class="fa-solid fa-phone"></i> ${d.phone}</a>` : ''}
                ${d.precio ? `<span class="pv-action-btn"><i class="fa-solid fa-tag"></i> $${d.precio} USD</span>` : ''}
                ${d.estado_reserva && d.estado_reserva !== 'No aplica' ? `<span class="pv-action-btn"><i class="fa-solid fa-calendar-check"></i> ${d.estado_reserva}</span>` : ''}
              </div>
              ${d.reserva ? `<div class="pv-notes-row" style="border-top:none; padding:10px 0; margin-top:8px;"><i class="fa-solid fa-ticket" style="margin-right:2px"></i> <b>Código de Reserva:</b> ${d.reserva}</div>` : ''}
              ${d.notas ? `<div class="pv-notes-row" style="color:#64748b;"><i class="fa-solid fa-circle-info"></i> ${d.notas}</div>` : ''}
            </div>
          </div>
          ${d.adjunto_url ? `
            <div style="margin-top:12px; padding-top:12px; border-top:1px solid var(--border);">
              <a href="${fixUrl(d.adjunto_url)}" target="_blank" style="display:inline-flex; align-items:center; gap:8px; padding:8px 20px; border:1px solid #d1d5db; border-radius:30px; background:#fff; color:#1f2937; text-decoration:none; font-weight:700; font-size:12.5px; box-shadow:0 1px 2px rgba(0,0,0,0.05);">
                <i class="fa-solid fa-paperclip"></i> ${d.adjunto_name || 'Ver adjunto'}
              </a>
            </div>
          ` : ''}
        </div>`;
      }

      // ─────────────────────────────────────────────────────
      // ── TOUR  (mismo layout media, color azul) ────────────
      // ─────────────────────────────────────────────────────
      if (item.type === 'tour') {
        const dt = d.fecha ? fmtDateTime(d.fecha) : { day: '', time: '' };
        const timeRange = dt.time ? (d.duracion ? dt.time + ' - ' + d.duracion : dt.time) : (d.duracion || '');
        return `<div class="pv-card">
          <div class="pvc-section-label" style="color:var(--accent)"><i class="fa-solid fa-map-location-dot"></i> Tour</div>
          <div class="pv-media-layout">
            <div class="pv-media-photo-slot">${cCarousel(d.url || d.photo_url, '<i class="fa-solid fa-map-location-dot"></i>')}</div>
            <div class="pv-media-info-col">
              <div class="pv-media-title-row">
                <div class="pv-media-name">
                  <a href="https://www.google.com/maps/search/?api=1&query=${encodeURIComponent((d.nombre || 'Tour') + ' ' + (d.operador || ''))}" target="_blank" class="pv-map-link">
                    ${d.nombre || 'Tour'} <i class="fa-solid fa-up-right-from-square"></i>
                  </a>
                </div>
              </div>
              ${d.operador ? `<div class="pv-media-addr"><i class="fa-solid fa-location-dot" style="color:var(--muted)"></i> ${d.operador}</div>` : ''}
              ${timeRange ? `<div class="pv-media-time"><i class="fa-solid fa-clock"></i> ${timeRange.includes(' - ') ? timeRange.replace(' - ', ' - Duración : ') : 'Duración : ' + timeRange}</div>` : ''}
              ${d.descripcion ? `<div class="pv-media-desc">${d.descripcion}</div>` : ''}
              <div class="pv-chips-row" style="margin-top:8px">
                ${d.personas ? `<span class="pv-chip"><i class="fa-solid fa-users"></i> ${d.personas} personas</span>` : ''}
                ${d.precio ? `<span class="pv-chip"><i class="fa-solid fa-tag"></i> $${d.precio} USD</span>` : ''}
              </div>
              ${d.reserva ? `<div class="pv-notes-row" style="border-top:none; padding:10px 0; margin-top:8px;"><i class="fa-solid fa-ticket" style="margin-right:2px"></i> <b>Código de Reserva:</b> ${d.reserva}</div>` : ''}
              ${d.notas ? `<div class="pv-notes-row" style="color:#64748b;"><i class="fa-solid fa-circle-info"></i> ${d.notas}</div>` : ''}
            </div>
          </div>
          ${d.adjunto_url ? `
            <div style="margin-top:12px; padding-top:12px; border-top:1px solid var(--border);">
              <a href="${fixUrl(d.adjunto_url)}" target="_blank" style="display:inline-flex; align-items:center; gap:8px; padding:8px 20px; border:1px solid #d1d5db; border-radius:30px; background:#fff; color:#1f2937; text-decoration:none; font-weight:700; font-size:12.5px; box-shadow:0 1px 2px rgba(0,0,0,0.05);">
                <i class="fa-solid fa-paperclip"></i> ${d.adjunto_name || 'Ver adjunto'}
              </a>
            </div>
          ` : ''}
        </div>`;
      }

      // ── DOCUMENTOS ──
      if (item.type === 'documents') {
        let docList = [];
        try {
          let raw = d.documents || d.files;
          if (typeof raw === 'string' && raw.trim().startsWith('[')) {
            docList = JSON.parse(raw);
          } else if (Array.isArray(raw)) {
            docList = raw;
          } else if (raw) {
            docList = [raw];
          }
        } catch (e) {
          console.error('Error parsing documents:', e);
        }

        return `<div class="pv-card">
          <div class="pvc-section-label" style="color:var(--accent)"><i class="fa-solid fa-folder-open"></i> ${d.documents_title || 'Documentos'}</div>
          ${d.documents_description ? `<div class="pv-media-desc" style="margin-bottom:12px">${d.documents_description}</div>` : ''}
          <div class="pv-docs-list" style="display:flex; flex-direction:column; gap:8px;">
            ${docList.map(doc => {
          const isObj = typeof doc === 'object' && doc !== null;
          const docId = isObj ? doc.id : doc;
          const docName = isObj ? (doc.original_name || doc.name) : 'Documento';
          const docUrl = (isObj && doc.url) ? doc.url : (origin + '/documents/' + docId + '/download');

          if (!docId && !isObj) return '';

          return `
                <a href="${fixUrl(docUrl)}" target="_blank" style="display:flex; align-items:center; justify-content:space-between; padding:10px 16px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; color:#1e293b; text-decoration:none; font-size:13px; transition:all 0.2s;">
                  <div style="display:flex; align-items:center; gap:10px;">
                    <i class="fa-solid fa-file-pdf" style="color:#ef4444; font-size:16px;"></i>
                    <span style="font-weight:600;">${docName}</span>
                  </div>
                  <i class="fa-solid fa-download" style="color:var(--muted); font-size:12px;"></i>
                </a>
              `;
        }).join('')}
            ${docList.length === 0 ? '<div style="font-size:12px; color:var(--muted); text-align:center; padding:10px;">No hay documentos adjuntos</div>' : ''}
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
      </div>
      <div class="pvday-items">${renderPreviewItems(items)}</div>
    </section>`;
  }).join('');

  const sidebarNav = numericTabs.map((tab, i) => {
    const dStr = dayDates && dayDates[tab.idx] ? dayDates[tab.idx] : '';
    const dateLabel = dStr ? fmtDayMonth(dStr) : tab.label;
    return `<a class="pvnav-link" href="#day-${tab.idx}"><span class="pvnav-num">Día ${i + 1}</span> ${dateLabel}</a>`;
  }).join('');

  const mobileCalendarNav = numericTabs.map((tab, i) => {
    const dStr = dayDates && dayDates[tab.idx] ? dayDates[tab.idx] : '';
    if (dStr) {
      try {
        const d = new Date(dStr + 'T00:00:00');
        const weekday = d.toLocaleDateString('es', { weekday: 'short' }).replace('.', '').toUpperCase();
        const dayNum = d.toLocaleDateString('es', { day: 'numeric' });
        const month = d.toLocaleDateString('es', { month: 'short' }).replace('.', '').toUpperCase();
        return `<a class="pvnav-link pv-mobile-cal-item" href="#day-${tab.idx}"><span class="pv-mobile-cal-weekday">${weekday}</span><span class="pv-mobile-cal-daynum">${dayNum}</span><span class="pv-mobile-cal-month">${month}</span></a>`;
      } catch (e) { }
    }
    return `<a class="pvnav-link pv-mobile-cal-item no-date" href="#day-${tab.idx}">
      <span class="pv-mobile-cal-daylbl">Día</span>
      <span class="pv-mobile-cal-daynum">${i + 1}</span>
    </a>`;
  }).join('');

  return `<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<base href="${origin || window.location.origin}/">
<title>Vista Previa | ${title}</title>
<link rel="icon" type="image/png" href="${origin || window.location.origin}/favicon.png">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
html{scroll-behavior:smooth}
:root{--accent:${currentTheme};--accent-bg:${accentBackground};--accent-light:${accentLight};--primary-blue:${primaryBlue};--text:#0f172a;--muted:#64748b;--dim:#94a3b8;--border:#e2e8f0;--bg:#f1f5f9;--surface:#fff;--radius:12px;--shadow:0 10px 30px rgba(0,0,0,.06)}
body{font-family:'Poppins',sans-serif;background:var(--bg);color:var(--text);min-height:100vh}

/* TOPBAR */
.pv-topbar{position:sticky;top:0;z-index:100;background:var(--accent-bg);border-bottom:none;padding:0 28px;height:52px;display:flex;align-items:center;gap:16px}
.pv-logo{font-family:'Poppins',sans-serif;font-weight:800;font-size:15px;color:#fff;flex-shrink:0}
.pv-topbar-title{font-family:'Poppins',sans-serif;font-size:14px;font-weight:700;color:#fff;flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.pv-back-btn{padding:6px 14px;border:1.5px solid rgba(255,255,255,0.2);border-radius:8px;font-size:12.5px;color:#fff;cursor:pointer;background:none;font-family:'Poppins',sans-serif;transition:all .14s;text-decoration:none;flex-shrink:0}
.pv-back-btn:hover{border-color:#38bdf8;color:#38bdf8;background:rgba(255,255,255,0.1)}

/* ─── PORTADA CARD (imagen referencia 1) ─── */
.pv-portada-wrap{max-width:1100px;margin:28px auto 0;padding:0 24px}
.pv-portada-card{background:var(--surface);border-radius:var(--radius);overflow:hidden;box-shadow:var(--shadow);border:1px solid var(--border)}
.pv-portada-img{width:100%;height:220px;object-fit:cover;display:block}
.pv-portada-img-placeholder{width:100%;height:220px;background:linear-gradient(139deg, #0f172a, #0e4c6a);display:flex;align-items:center;justify-content:center;font-size:52px;color:rgba(255,255,255,0.25)}
.pv-portada-title-row{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:18px 22px 14px}
.pv-portada-title{font-family:'Poppins',sans-serif;font-size:21px;font-weight:800;color:var(--text)}
.pv-status-badge{display:inline-flex;align-items:center;gap:6px;background:#fefce8;border:1.5px solid #fde047;border-radius:30px;padding:5px 13px;font-size:11px;font-weight:700;color:#854d0e;letter-spacing:.3px;white-space:nowrap;flex-shrink:0}
.pv-status-dot{width:7px;height:7px;border-radius:50%;background:#eab308}
.pv-portada-meta-row{display:grid;grid-template-columns:repeat(3,1fr);border-top:1px solid var(--border)}
.pv-portada-meta-cell{padding:16px 22px;text-align:center;border-right:1px solid var(--border)}
.pv-portada-meta-cell:last-child{border-right:none}
.pv-pm-label{font-size:10px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:var(--dim);margin-bottom:5px}
.pv-pm-value{font-size:15px;font-weight:700;color:var(--text)}
.pv-pm-value.highlight{color:#0f172a}

/* LAYOUT */
.pv-layout{display:grid;grid-template-columns:250px 1fr;max-width:1100px;margin:0 auto;padding:32px 24px 60px;align-items:start;gap:0}

/* SIDEBAR NAV */
.pv-nav{position:sticky;top:68px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;box-shadow:var(--shadow)}
.pv-nav-title{font-size:10px;font-weight:700;letter-spacing:1.4px;text-transform:uppercase;color:var(--dim);padding:14px 18px 11px;border-bottom:1px solid var(--border)}
.pvnav-link{display:flex;align-items:baseline;gap:8px;padding:9px 18px;font-size:13px;font-weight:500;color:var(--muted);text-decoration:none;transition:all .14s;border-left:3px solid transparent}
.pvnav-link:hover{background:#f8f9fb;color:var(--accent);border-left-color:var(--accent)}
.pvnav-link.active{background:#f0faf9;color:var(--accent);border-left-color:var(--accent);font-weight:600}
.pvnav-num{font-size:10px;font-weight:700;color:var(--dim);min-width:34px;letter-spacing:.4px}
.pvnav-link.active .pvnav-num{color:var(--accent)}
/* CONTENT */
.pv-content{padding-left:26px;display:flex;flex-direction:column;gap:36px}

/* DAY SECTION */
.pvday-header{display:flex;align-items:center;gap:12px;margin-bottom:16px;padding-bottom:12px;border-bottom:2px solid var(--border);position:sticky;top:62px;background:var(--bg);z-index:10;padding-top:8px}
.pvday-pill{background:var(--accent-bg);color:#fff;font-size:10px;font-weight:700;letter-spacing:.7px;text-transform:uppercase;padding:4px 11px;border-radius:20px}
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
.pv-texto ul { padding-left: 20px; margin: 10px 0; }
.pv-texto li { margin-bottom: 5px; }
.pv-texto b, .pv-texto strong { font-weight: 700; color: var(--text); }
.pv-texto i, .pv-texto em { font-style: italic; }
.pv-texto a { color: #0ea5e9; text-decoration: underline; }
.pv-texto p { margin-bottom: 8px; }
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
.pv-flight-code{background:var(--accent);color:#fff;font-size:11px;font-weight:700;padding:3px 9px;border-radius:6px;letter-spacing:.3px}

/* ─── CHIPS ─── */
.pv-chips-row{display:flex;flex-wrap:wrap;gap:7px}
.pv-chip{background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:7px 10px;font-size:10px;color:#0f172a;font-weight:500;display:inline-flex;align-items:center;gap:5px;box-shadow:0 1px 2px rgba(0,0,0,0.05)}
.pv-notes-row{margin-top:11px;font-size:12.5px;color:#202833;border-top:1px solid var(--border);padding-top:10px;line-height:1.5}

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
.pv-action-btn{padding:7px 10px;background:#fff;color:#0f172a;border:1px solid #e2e8f0;border-radius:20px;font-size:10px;font-weight:500;cursor:pointer;font-family:'Poppins',sans-serif;transition:all .14s;display:inline-flex;align-items:center;gap:5px;text-decoration:none;box-shadow:0 1px 2px rgba(0,0,0,0.05)}
.pv-action-btn:hover{background:#f8fafc;border-color:#cbd5e1}
.pv-attachment-btn{padding:7px 14px;background:none;color:#1c0909;border:1px solid #a6a1b6;border-radius:20px;font-size:12.5px;font-weight:600;cursor:pointer;font-family:'Poppins',sans-serif;transition:all .14s;display:inline-flex;align-items:center;gap:5px;text-decoration:none}
.pv-attachment-btn:hover{background:#f8fafc;border-color:#cbd5e1}

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
.pv-map-link{color:inherit;text-decoration:none;transition:color 0.2s;display:inline-flex;align-items:center;gap:6px}
.pv-map-link:hover{color:var(--accent);text-decoration:none}
.pv-map-link i{font-size:0.85em;opacity:0.7}

/* CIERRE */
.pv-cierre{background:var(--accent-bg);border-radius:var(--radius);padding:40px 32px;text-align:center;color:#fff;display:flex;flex-direction:column;align-items:center;gap:12px;box-shadow:var(--shadow)}
.pv-cierre-plane{font-size:50px;animation:float 3s ease-in-out infinite}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
.pv-cierre-badge{background:var(--accent);border:1px solid rgba(255,255,255,0.3);border-radius:20px;padding:5px 15px;font-size:11px;font-weight:700;color:#fff;letter-spacing:.8px;text-transform:uppercase}
.pv-cierre-title{font-family:'Poppins',sans-serif;font-size:24px;font-weight:800;color:#fff}
.pv-cierre-sub{font-size:13px;color:rgba(255,255,255,.5);max-width:480px;line-height:1.6}
.pv-cierre-stats{display:flex;gap:22px;margin-top:6px}
.pvcs{text-align:center}
.pvcs-n{font-family:'Poppins',sans-serif;font-size:24px;font-weight:800;color:var(--primary-blue)}
.pvcs-l{font-size:10px;color:rgba(255,255,255,.4);letter-spacing:1px;text-transform:uppercase;margin-top:2px}

/* PORTADA / CIERRE EXTRA ITEMS */
.pv-portada-extra-items{max-width:1100px;margin:12px auto 0;padding:0 24px;display:flex;flex-direction:column;gap:12px}
.pv-cierre-extra-items{display:flex;flex-direction:column;gap:12px;margin-top:16px}

/* MOBILE */
@media(max-width:760px){
  .pv-layout{grid-template-columns:1fr;padding:20px 16px 48px}
  .pv-nav{display:none}
  .pv-content{padding-left:0}
  .pv-topbar{padding:0 16px}
  .pvday-header{top:52px}
  .pv-portada-meta-row{grid-template-columns:repeat(3, 1fr)}
  .pv-portada-meta-cell{border-right:1px solid var(--border);border-bottom:none;text-align:center;padding:12px 6px}
  .pv-portada-meta-cell:last-child{border-right:none}
  .pv-portada-title-row{flex-direction:column;align-items:flex-start;gap:8px}
  .pv-portada-wrap{padding:0 14px}
  .pv-portada-extra-items{padding:0 14px}
  .pv-hotel-layout,.pv-media-layout{grid-template-columns:1fr}
  .pv-hotel-photo-slot,.pv-media-photo-slot{height:180px}
  .pv-route-time{font-size:18px}
  .pv-station-big{font-size:12px;max-width:120px}
  .pv-cierre{padding:28px 20px}
  .pv-cierre-title{font-size:18px}
  .public-preview-header{padding:0 10px !important;}
  .pv-flight-header-details{display:none !important;}
  .pv-chips-row{align-items:center;}
  .pv-flight-mobile-details{display:flex !important;}
  .pv-texto { font-size: 12px !important; }
}
@media(max-width:420px){
  .pv-topbar-title{display:none !important;}
  .pv-back-text{display:none !important;}
  .pv-topbar{justify-content:space-between;padding:0 16px}
  .pv-portada-title{font-size:16px}
  .pvday-title{font-size:14px}
  .pv-pm-value{font-size:12px}
  .pv-route-row{gap:6px}
  .pv-card{padding:14px 14px}
  .pv-route-row.pv-flight-route { display:flex; justify-content:space-between; align-items:flex-start; text-align:left; }
  .pv-route-row.pv-flight-route .pv-route-right { text-align:right; align-items:flex-end; }
}
/* Animations */
.pv-day{animation:fadeUp .35s ease both}
@keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:none}}
.pv-day:nth-child(1){animation-delay:.04s}.pv-day:nth-child(2){animation-delay:.08s}.pv-day:nth-child(3){animation-delay:.12s}.pv-day:nth-child(4){animation-delay:.16s}.pv-day:nth-child(5){animation-delay:.2s}
    @font-face {
        font-family: 'Dongra Script';
        src: url('${origin}/fonts/Dongra Script.ttf') format('truetype');
    }

    .public-preview-header {
        background: var(--accent-bg);
        position: sticky;
        top: 0;
        z-index: 100;
        padding: 0px 100px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 60px;
    }

    .gps-logo-text {
        color: #fff;
        font-weight: 600;
        font-size: 13px;
        line-height: 1;
    }

    .gps-logo-img {
        max-width: 140px;
        height: auto;
        max-height: 50px;
        object-fit: contain;
    }

    .pv-mobile-only-header {
        display: none;
    }

    .pv-cal-btn, .pv-map-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 10px 14px;
        background: #ffffff;
        color: #445c7d;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
        transition: all 0.2s ease;
    }
    .pv-cal-btn:hover, .pv-map-btn:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #2b3e57;
    }
    .pv-cal-btn:active, .pv-map-btn:active {
        transform: translateY(1px);
    }

    @media (max-width: 760px) {
        .pv-mobile-only-header {
            display: block;
            max-width: 1100px;
            margin: 16px auto 0;
            padding: 0 14px;
        }
        .pv-mobile-cal-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--muted);
            margin-bottom: 8px;
        }
        .pv-mobile-calendar-nav-wrap {
            width: 100%;
            overflow: hidden;
        }
        .pv-mobile-calendar-nav {
            position: relative;
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding: 4px 4px 12px;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
        }
        .pv-mobile-calendar-nav::-webkit-scrollbar {
            display: none;
        }
        .pv-mobile-calendar-nav {
            scrollbar-width: none;
        }
        .pv-mobile-cal-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 8px 12px;
            min-width: 60px;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
            flex-shrink: 0;
        }
        .pv-mobile-cal-item:active {
            transform: scale(0.96);
        }
        .pv-mobile-cal-item.active {
            background: var(--accent) !important;
            border-color: var(--accent) !important;
            box-shadow: 0 4px 12px var(--accent-light);
        }
        .pv-mobile-cal-weekday {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--muted);
        }
        .pv-mobile-cal-daynum {
            font-size: 16px;
            font-weight: 800;
            color: var(--text);
            line-height: 1.1;
            margin: 2px 0;
        }
        .pv-mobile-cal-month {
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--dim);
        }
        .pv-mobile-cal-daylbl {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--muted);
        }
        .pv-mobile-cal-item.active .pv-mobile-cal-weekday,
        .pv-mobile-cal-item.active .pv-mobile-cal-daynum,
        .pv-mobile-cal-item.active .pv-mobile-cal-month,
        .pv-mobile-cal-item.active .pv-mobile-cal-daylbl {
            color: #fff !important;
        }
        .pv-mobile-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: 12px;
        }
        .pv-cal-btn-mobile, .pv-map-btn-mobile {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 10px 12px;
            background: #ffffff;
            color: #445c7d;
            border: 1.5px solid #445c7d;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.2s ease;
        }
        .pv-cal-btn-mobile:hover, .pv-map-btn-mobile:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #2b3e57;
        }
        .pv-cal-btn-mobile:active, .pv-map-btn-mobile:active {
            transform: translateY(1px);
        }
    }

    .viantryp-map-tab-bar {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding: 10px 24px;
        border-bottom: 1px solid #f1f5f9;
        background: #f8fafc;
        flex-shrink: 0;
    }
    .viantryp-map-tab-bar::-webkit-scrollbar {
        display: none;
    }
    .viantryp-map-tab-bar {
        scrollbar-width: none;
    }
    .viantryp-map-tab {
        padding: 6px 14px;
        background: #ffffff;
        border: 1.5px solid var(--border);
        border-radius: 20px;
        color: #445c7d;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    .viantryp-map-tab:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }
    .viantryp-map-tab.active {
        background: var(--accent) !important;
        border-color: var(--accent) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px var(--accent-light);
    }
</style>
</head>
<body>
${isPublicLink ? `
<div class="public-preview-header">
    ${(displayNameType === 'agency' && (agencyLogo || agencyName))
        ? `<div style="display:flex;align-items:center;gap:12px;">
           ${agencyLogo ? `<img src="${agencyLogo}" alt="${agencyName}" class="gps-logo-img">` : ''}
           ${agencyName ? `<span class="gps-logo-text" style="font-size:13px;">${agencyName}</span>` : ''}
         </div>`
        : `<span class="gps-logo-text">${userFullName || userName}</span>`
      }
    <img src="${origin || ''}/images/logo-viantryp.png" alt="Viantryp Logo" class="viantryp-logo" style="width:80px;height:auto;filter:brightness(0) invert(1);object-fit:contain;">
</div>
` : `
<div class="pv-topbar">
  <div class="pv-logo" style="display:flex;align-items:center;">
    ${(displayNameType === 'agency' && (agencyLogo || agencyName))
      ? `<div style="display:flex;align-items:center;gap:12px;">
           ${agencyLogo ? `<img src="${agencyLogo}" alt="${agencyName}" class="gps-logo-img" style="max-height:30px;">` : ''}
           ${agencyName ? `<span class="gps-logo-text" style="font-size:13px;">${agencyName}</span>` : ''}
         </div>`
      : `<span class="gps-logo-text">${userFullName || userName}</span>`
    }
  </div>
  <div style="flex:1"></div>
  <div class="pv-topbar-actions" style="display:flex;gap:12px;">
      <button class="pv-share-btn" onclick="shareProTrip()" style="background:#fff;color:#0f172a;border:none;padding:6px 14px;border-radius:20px;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;"><i class="fa-solid fa-share-nodes"></i> <span class="pv-back-text">Compartir</span></button>
      <button class="pv-back-btn" onclick="window.close()" style="display:flex;align-items:center;gap:6px;background:none;border:none;color:#fff;font-size:13px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;"><i class="fa-solid fa-times" style="font-size:16px"></i> <span class="pv-back-text">Cerrar</span></button>
  </div>
</div>
`}

${hasPortada ? `
<div class="pv-portada-wrap">
  <div class="pv-portada-card">
    ${portadaPhotoUrl
        ? `<img class="pv-portada-img" src="${fixUrl(portadaPhotoUrl)}" alt="${title}">`
        : `<div class="pv-portada-img-placeholder"><i class="fa-solid fa-earth-americas"></i></div>`
      }
    <div class="pv-portada-title-row">
      <div class="pv-portada-title">${title}</div>
      ${statusBadgeHTML}
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
        <div class="pv-pm-value highlight">${precio ? '$' + Number(precio).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' USD' : '—'}</div>
      </div>
    </div>
  </div>
  ${portadaItems && portadaItems.length ? `<div class="pv-portada-extra-items">${renderPreviewItems(portadaItems)}</div>` : ''}
</div>
`: ''}

<div class="pv-mobile-only-header">
  <p class="pv-mobile-cal-title">Calendario de viaje</p>
  <div class="pv-mobile-calendar-nav-wrap">
    <div class="pv-mobile-calendar-nav">
      ${mobileCalendarNav}
    </div>
  </div>
  <div class="pv-mobile-actions">
    <button class="pv-cal-btn-mobile" onclick="openGoogleCalendarModal()">
      <i class="fa-solid fa-calendar-plus"></i>
      <span style="text-align: center; line-height: 1.2;">Agregar a Google Calendar</span>
    </button>
    <button class="pv-map-btn-mobile" onclick="openInteractiveMapModal()">
      <i class="fa-solid fa-map-location-dot"></i>
      <span style="text-align: center; line-height: 1.2;">Ver mapa<br>del viaje</span>
    </button>
  </div>
</div>

<div class="pv-layout">
  <aside class="pv-nav">
    <div class="pv-nav-title">Itinerario</div>
    ${sidebarNav}
    <div class="pv-nav-calendar-section" style="padding: 14px 18px 0; border-top: 1px solid var(--border);">
      <button class="pv-cal-btn" onclick="openGoogleCalendarModal()">
        <i class="fa-solid fa-calendar-plus" style="font-size:14px;"></i>
        Agregar a Google Calendar
      </button>
    </div>
    <div class="pv-nav-map-section" style="padding: 10px 18px 14px;">
      <button class="pv-map-btn" onclick="openInteractiveMapModal()">
        <i class="fa-solid fa-map-location-dot" style="font-size:14px;"></i>
        Ver mapa del viaje
      </button>
    </div>
  </aside>
  <main class="pv-content">
    ${daysHTML}
    ${hasCierre ? `
      ${cierreItems && cierreItems.length ? `<div class="pv-cierre-extra-items" style="margin-bottom:20px">${renderPreviewItems(cierreItems)}</div>` : ''}
      ${showDefaultCierre ? `
      <div class="pv-cierre">
        <div class="pv-cierre-title">${title}</div>
        <div class="pv-cierre-sub">Este itinerario fue creado por <b>${userName || window.viantrypUserName || 'Viantryp'}</b>.<br>¡Que tengas un viaje extraordinario!</div>
      </div>
      ` : ''}
    `: ''}
  </main>
</div>

<!-- Google Calendar Modal -->
<div id="viantrypCalendarModal" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(15,23,42,0.4);backdrop-filter:blur(8px);z-index:10000;display:none;align-items:center;justify-content:center;opacity:0;transition:opacity 0.25s ease;">
  <div style="background:#fff;border-radius:24px;width:90%;max-width:480px;padding:32px;box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);transform:translateY(20px);transition:transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);text-align:left;position:relative;border:1px solid #f1f5f9;font-family:'Poppins',sans-serif;">
    <button onclick="closeGoogleCalendarModal()" style="position:absolute;top:20px;right:20px;background:none;border:none;font-size:24px;color:#94a3b8;cursor:pointer;line-height:1;transition:color 0.2s;" onmouseover="this.style.color='#475569'" onmouseout="this.style.color='#94a3b8'">×</button>
    
    <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px;">
      <div>
        <h3 style="margin:0;font-size:18px;font-weight:700;color:#0f172a;">Agregar al Calendario</h3>
        <p style="margin:2px 0 0;font-size:12px;color:#64748b;">Elige cómo quieres añadir el viaje a tu agenda</p>
      </div>
    </div>

    <!-- Info text or errors if dates are missing -->
    <div id="calModalDatesWarning" style="display:none;background:#fffbeb;border:1px solid #fef3c7;border-radius:12px;padding:12px 14px;color:#b45309;font-size:12px;line-height:1.5;margin-bottom:20px;font-weight:500;">
      <i class="fa-solid fa-triangle-exclamation" style="margin-right:6px;"></i>
      Este viaje no tiene fechas de inicio definidas. Asígnale fechas en el editor para poder sincronizar las actividades en días específicos.
    </div>

    <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:24px;">
      <!-- Direct Sync Button Card -->
      <div id="googleDirectSyncCard" style="position:relative;border:1.5px solid #e2e8f0;border-radius:16px;padding:16px;cursor:not-allowed;transition:all 0.2s;opacity:0.55;pointer-events:none;">
        <span style="position:absolute;top:-10px;left:16px;background:#64748b;color:#fff;font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;padding:2px 8px;border-radius:20px;">Próximamente</span>
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:6px;">
          <svg style="width:20px;height:20px;" viewBox="0 0 24 24">
            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
          </svg>
          <span style="font-size:13.5px;font-weight:600;color:#1e293b;">Sincronizar directamente con Google</span>
        </div>
        <p style="margin:0;font-size:11.5px;color:#64748b;line-height:1.45;padding-left:32px;">Importa todos los eventos automáticamente en tu Google Calendar.</p>
        
        <!-- Client ID missing notice -->
        <div id="clientIdMissingNotice" style="display:none;margin-top:8px;font-size:11px;color:#94a3b8;font-style:italic;padding-left:32px;">
          Sincronización automática deshabilitada por falta de Google Client ID en el servidor.
        </div>
      </div>

      <!-- ICS Download Card -->
      <div style="border:1.5px solid #e2e8f0;border-radius:16px;padding:16px;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.borderColor='var(--accent)';this.style.background='#f8fafc';" onmouseout="this.style.borderColor='#e2e8f0';this.style.background='transparent';" onclick="triggerICSDownload()">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:6px;">
          <div style="font-size:18px;color:#64748b;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-file-arrow-down"></i></div>
          <span style="font-size:13.5px;font-weight:600;color:#1e293b;">Descargar archivo de calendario (.ics)</span>
        </div>
        <p style="margin:0;font-size:11.5px;color:#64748b;line-height:1.45;padding-left:30px;">Descarga el archivo estándar e impórtalo en Outlook, Apple Calendar o Google Calendar.</p>
      </div>
    </div>

    <!-- Sync progress bar/feedback inside the modal -->
    <div id="syncProgressContainer" style="display:none;background:#f8fafc;border:1px solid #e2e8f0;border-radius:16px;padding:16px;margin-bottom:20px;">
      <div style="display:flex;justify-content:between;align-items:center;margin-bottom:8px;font-size:12px;font-weight:600;color:#334155;">
        <span id="syncProgressText">Conectando...</span>
        <span id="syncProgressPercent" style="margin-left:auto;color:var(--accent);">0%</span>
      </div>
      <div style="width:100%;height:6px;background:#e2e8f0;border-radius:3px;overflow:hidden;">
        <div id="syncProgressBar" style="width:0%;height:100%;background:var(--accent);transition:width 0.2s ease;"></div>
      </div>
    </div>

    <button onclick="closeGoogleCalendarModal()" style="width:100%;padding:12px;border-radius:12px;border:none;background:#f1f5f9;color:#475569;font-weight:600;font-size:13px;cursor:pointer;transition:all 0.2s;font-family:inherit;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">Cancelar</button>
  </div>
</div>

<!-- Interactive Map Modal -->
<div id="viantrypMapModal" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(15,23,42,0.4);backdrop-filter:blur(8px);z-index:10000;display:none;align-items:center;justify-content:center;opacity:0;transition:opacity 0.25s ease;font-family:\'Poppins\',sans-serif;">
  <div style="background:#fff;border-radius:24px;width:95%;max-width:1100px;height:85vh;box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);transform:translateY(20px);transition:transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);display:flex;flex-direction:column;position:relative;border:1px solid #f1f5f9;overflow:hidden;">
    
    <!-- Modal Header -->
    <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
      <div style="display:flex;align-items:center;gap:12px;">
      <div>
          <h3 style="margin:0;font-size:16px;font-weight:700;color:#0f172a;">Mapa del Viaje</h3>
          <p style="margin:2px 0 0;font-size:11px;color:#64748b;">Visualiza los puntos y el itinerario de tu viaje</p>
        </div>
      </div>
      <button onclick="closeInteractiveMapModal()" style="margin-left:auto;background:#f1f5f9;border:none;width:32px;height:32px;border-radius:50%;font-size:18px;color:#64748b;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all 0.2s;" onmouseover="this.style.background=\'#e2e8f0\';this.style.color=\'#0f172a\'" onmouseout="this.style.background=\'#f1f5f9\';this.style.color=\'#64748b\'">✕</button>
    </div>

    <!-- Day Selector Tabs Bar -->
    <div id="viantrypMapDaySelector" class="viantryp-map-tab-bar"></div>

    <!-- Modal Body (Split Layout) -->
    <div style="flex:1;display:flex;overflow:hidden;position:relative;" id="mapModalSplitBody">
      
      <!-- Loader Overlay -->
      <div id="mapModalLoader" style="position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(255,255,255,0.9);z-index:100;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px;">
        <div style="width:40px;height:40px;border:4px solid #f1f5f9;border-top-color:var(--accent);border-radius:50%;animation:mapSpinner 1s linear infinite;"></div>
        <div style="text-align:center;">
          <div id="mapModalLoaderText" style="font-size:13px;font-weight:600;color:#1e293b;">Cargando mapa interactivo...</div>
          <div id="mapModalLoaderProgress" style="font-size:11px;color:#64748b;margin-top:4px;">Iniciando componentes...</div>
        </div>
      </div>
      
      <!-- Left Sidebar (Locations List) -->
      <div id="mapModalSidebar" style="width:300px;border-right:1px solid #f1f5f9;overflow-y:auto;background:#f8fafc;padding:16px;display:flex;flex-direction:column;gap:12px;flex-shrink:0;">
        <!-- Filled dynamically -->
      </div>
      
      <!-- Right Map Canvas -->
      <div id="viantrypMapCanvas" style="flex:1;height:100%;background:#f1f5f9;position:relative;z-index:1;">
        <!-- Leaflet Map Container -->
      </div>
    </div>
  </div>
</div>

<style>
@keyframes mapSpinner {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
.map-sidebar-day-title {
  font-size: 11px;
  font-weight: 800;
  text-transform: uppercase;
  color: #94a3b8;
  letter-spacing: 0.8px;
  margin: 12px 0 6px;
  padding-bottom: 4px;
  border-bottom: 1px solid #e2e8f0;
}
.map-sidebar-day-title:first-child {
  margin-top: 0;
}
.map-sidebar-item {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 10px 12px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 10px;
  transition: all 0.2s;
}
.map-sidebar-item:hover {
  border-color: var(--accent);
  background: #f0faf9;
  transform: translateY(-1px);
}
.map-sidebar-item-icon {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  flex-shrink: 0;
}
.map-sidebar-item-info {
  flex: 1;
  min-width: 0;
}
.map-sidebar-item-title {
  font-size: 12px;
  font-weight: 600;
  color: #1e293b;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.map-sidebar-item-addr {
  font-size: 10px;
  color: #64748b;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  margin-top: 2px;
}
.viantryp-custom-marker {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  border: 2px solid #fff;
  color: #fff;
  box-shadow: 0 4px 10px rgba(0,0,0,0.25);
  transition: all 0.2s;
}
.viantryp-custom-marker:hover {
  transform: scale(1.15);
  z-index: 1000 !important;
}
.viantryp-map-popup-card {
  font-family: \'Poppins\', sans-serif;
  padding: 4px;
}
.viantryp-map-popup-type {
  font-size: 9px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 2px;
}
.viantryp-map-popup-title {
  font-size: 12px;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 2px;
}
.viantryp-map-popup-addr {
  font-size: 10px;
  color: #64748b;
  margin-bottom: 4px;
}
.viantryp-map-popup-meta {
  font-size: 9.5px;
  color: #94a3b8;
  display: flex;
  gap: 8px;
}
@media (max-width: 768px) {
  #mapModalSplitBody {
    flex-direction: column-reverse;
  }
  #mapModalSidebar {
    width: 100% !important;
    height: 180px !important;
    border-right: none !important;
    border-top: 1px solid #f1f5f9 !important;
  }
}
</style>

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

const obs=new IntersectionObserver(entries=>{entries.forEach(e=>{if(e.isIntersecting){const id=e.target.id;links.forEach(l=>{const isActive=l.getAttribute('href')==='#'+id;l.classList.toggle('active',isActive);if(isActive&&l.classList.contains('pv-mobile-cal-item')){const container=document.querySelector('.pv-mobile-calendar-nav');if(container){const containerWidth=container.clientWidth;const itemLeft=l.offsetLeft;const itemWidth=l.clientWidth;container.scrollTo({left:itemLeft-(containerWidth/2)+(itemWidth/2),behavior:'smooth'});}}})}})},{threshold:.25,rootMargin:'-60px 0px -40% 0px'});
sections.forEach(s=>obs.observe(s));

// Trip Data context for calendar
const tripData = ${JSON.stringify(data).replace(/</g, '\\x3c')};

// Load GAPI and GSI
function loadGapiAndGsi(callback) {
  if (window.google && window.google.accounts) {
    callback();
    return;
  }
  const script = document.createElement('script');
  script.src = 'https://accounts.google.com/gsi/client';
  script.async = true;
  script.defer = true;
  script.onload = () => {
    callback();
  };
  document.head.appendChild(script);
}

function getGoogleAccessToken(clientId, callback) {
  loadGapiAndGsi(() => {
    try {
      const tokenClient = google.accounts.oauth2.initTokenClient({
        client_id: clientId,
        scope: 'https://www.googleapis.com/auth/calendar.events',
        callback: (response) => {
          if (response.error !== undefined) {
            console.error('Error obtaining Google access token:', response);
            alert('Error al obtener acceso a Google Calendar: ' + response.error);
            return;
          }
          callback(response.access_token);
        },
      });
      tokenClient.requestAccessToken({ prompt: 'consent' });
    } catch (e) {
      console.error('Error initializing Google GIS Client:', e);
      alert('Error de configuración de Google OAuth. Por favor verifica el Client ID.');
    }
  });
}

async function addEventToGoogleCalendar(accessToken, eventData) {
  const url = 'https://www.googleapis.com/calendar/v3/calendars/primary/events';
  const response = await fetch(url, {
    method: 'POST',
    headers: {
      'Authorization': 'Bearer ' + accessToken,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(eventData)
  });
  if (!response.ok) {
    const errorText = await response.text();
    throw new Error('Google API Error: ' + errorText);
  }
  return await response.json();
}

function extractEventsForCalendar(data) {
  try {
    const events = [];
    if (!data) {
      return { error: 'No hay datos del viaje.' };
    }
    const title = data.title || 'Itinerario';
    const days = data.days || {};
    const dayDates = data.dayDates || [];
    const numericTabs = data.numericTabs || [];

    if (!dayDates || dayDates.length === 0 || !dayDates[0]) {
      return { error: 'El viaje no tiene una fecha de inicio configurada.' };
    }

    if (!numericTabs || !Array.isArray(numericTabs)) {
      return { error: 'El viaje no tiene días válidos configurados.' };
    }

    const daysObj = days || {};

    numericTabs.forEach((tab) => {
      if (!tab) return;
      const dayIndex = tab.idx;
      if (dayIndex === undefined) return;
      const dayDate = dayDates[dayIndex];
      if (!dayDate) return;

      const items = daysObj[dayIndex] || [];
      if (!Array.isArray(items)) return;

      items.forEach(item => {
        if (!item || !item.data) return;
        const d = item.data;

        let event = {
          summary: '',
          description: '',
          location: '',
          start: {},
          end: {}
        };

        const parseTimeAndDate = (dateOrTimeVal, defaultTime = '09:00') => {
          if (dateOrTimeVal === undefined || dateOrTimeVal === null || dateOrTimeVal === '') {
            return { date: dayDate, time: defaultTime, isTimeSpecified: false };
          }
          const dateOrTimeStr = String(dateOrTimeVal).trim();
          if (dateOrTimeStr.includes('T')) {
            const parts = dateOrTimeStr.split('T');
            return { date: parts[0], time: parts[1].substring(0, 5), isTimeSpecified: true };
          }
          if (dateOrTimeStr.includes(' ')) {
            const parts = dateOrTimeStr.split(/\s+/);
            if (parts[0].includes('-') || parts[0].includes('/')) {
              return { date: parts[0], time: parts[1].substring(0, 5), isTimeSpecified: true };
            } else {
              return { date: dayDate, time: parts[0].substring(0, 5), isTimeSpecified: true };
            }
          }
          if (dateOrTimeStr.includes(':')) {
            return { date: dayDate, time: dateOrTimeStr.substring(0, 5), isTimeSpecified: true };
          }
          if (dateOrTimeStr.includes('-') || dateOrTimeStr.includes('/')) {
            return { date: dateOrTimeStr, time: defaultTime, isTimeSpecified: false };
          }
          return { date: dayDate, time: defaultTime, isTimeSpecified: false };
        };

        const addDuration = (dateTimeStr, durationVal) => {
          const dt = new Date(dateTimeStr);
          let durationMin = 60;
          if (durationVal !== undefined && durationVal !== null && durationVal !== '') {
            const durationStr = String(durationVal).trim();
            const hoursMatch = durationStr.match(/(\d+)\s*h/i);
            const minsMatch = durationStr.match(/(\d+)\s*m/i);
            if (hoursMatch || minsMatch) {
              durationMin = 0;
              if (hoursMatch) durationMin += parseInt(hoursMatch[1]) * 60;
              if (minsMatch) durationMin += parseInt(minsMatch[1]);
            } else if (durationStr.includes(':')) {
              const parts = durationStr.split(':');
              durationMin = (parseInt(parts[0]) || 0) * 60 + (parseInt(parts[1]) || 0);
            } else if (parseInt(durationStr)) {
              durationMin = parseInt(durationStr) * 60;
            }
          }
          dt.setMinutes(dt.getMinutes() + durationMin);
          const pad = (n) => String(n).padStart(2, '0');
          return dt.getFullYear() + '-' + pad(dt.getMonth() + 1) + '-' + pad(dt.getDate()) + 'T' + pad(dt.getHours()) + ':' + pad(dt.getMinutes()) + ':00';
        };

        if (item.type === 'flight') {
          const sal = parseTimeAndDate(d.salida, '10:00');
          const lle = parseTimeAndDate(d.llegada, '12:00');
          const startISO = sal.date + 'T' + sal.time + ':00';
          const endISO = lle.date + 'T' + lle.time + ':00';

          event.summary = '✈️ Vuelo: ' + (d.origen_city || d.origen || 'Origen') + ' → ' + (d.destino_city || d.destino || 'Destino');
          event.description = 'Aerolínea: ' + (d.aerolinea || '—') + '\\nNúmero de Vuelo: ' + (d.vuelo || '—') + '\\nReserva: ' + (d.reserva || '—') + '\\nNotas: ' + (d.notes || '—');
          event.location = (d.origen || '') + ' a ' + (d.destino || '');
          event.start = { dateTime: startISO };
          event.end = { dateTime: endISO };
        }
        else if (item.type === 'alojamiento') {
          const cIn = parseTimeAndDate(d.checkin, '15:00');
          const cOut = parseTimeAndDate(d.checkout, '11:00');
          
          event.summary = '🏨 Alojamiento: ' + (d.nombre || 'Hotel');
          event.description = 'Habitación: ' + (d.habitacion || '—') + '\\nRégimen: ' + (d.alimentacion || '—') + '\\nReserva: ' + (d.reserva || '—') + '\\nNotas: ' + (d.notes || '—');
          event.location = d.direccion || '';
          
          if (cIn.isTimeSpecified || cOut.isTimeSpecified) {
            event.start = { dateTime: cIn.date + 'T' + cIn.time + ':00' };
            event.end = { dateTime: cOut.date + 'T' + cOut.time + ':00' };
          } else {
            event.start = { date: cIn.date };
            let endDate = cOut.date;
            if (endDate === cIn.date) {
              const nextDay = new Date(cIn.date + 'T00:00:00');
              nextDay.setDate(nextDay.getDate() + 1);
              const pad = (n) => String(n).padStart(2, '0');
              endDate = nextDay.getFullYear() + '-' + pad(nextDay.getMonth() + 1) + '-' + pad(nextDay.getDate());
            }
            event.end = { date: endDate };
          }
        }
        else if (item.type === 'transporte') {
          const sal = parseTimeAndDate(d.salida || d.fecha, '09:00');
          const lle = parseTimeAndDate(d.llegada, '10:00');
          const startISO = sal.date + 'T' + sal.time + ':00';
          const endISO = lle.date + 'T' + lle.time + ':00';

          event.summary = '🚆 ' + (d.tipo || 'Transporte') + ': ' + (d.origen || 'Origen') + ' → ' + (d.destino || 'Destino');
          event.description = 'Proveedor: ' + (d.proveedor || '—') + '\\nReserva: ' + (d.reserva || '—') + '\\nNotas: ' + (d.notes || '—');
          event.location = (d.origen || '') + ' a ' + (d.destino || '');
          event.start = { dateTime: startISO };
          event.end = { dateTime: endISO };
        }
        else if (item.type === 'actividad') {
          const dt = parseTimeAndDate(d.fecha, '10:00');
          const startISO = dt.date + 'T' + dt.time + ':00';
          const endISO = addDuration(startISO, d.duracion);

          event.summary = '🎯 Actividad: ' + (d.nombre || 'Actividad');
          event.description = 'Duración: ' + (d.duracion || '—') + '\\nNotas: ' + (d.notes || '—');
          event.location = d.direccion || d.lugar || '';
          event.start = { dateTime: startISO };
          event.end = { dateTime: endISO };
        }
        else if (item.type === 'comida') {
          const dt = parseTimeAndDate(d.fecha, '13:00');
          const startISO = dt.date + 'T' + dt.time + ':00';
          const endISO = addDuration(startISO, '1.5h');

          event.summary = '🍴 Comida: ' + (d.restaurante || 'Restaurante') + (d.tipo ? ' (' + d.tipo + ')' : '');
          event.description = 'Estado de Reserva: ' + (d.estado_reserva || '—') + '\\nNotas: ' + (d.notes || '—');
          event.location = d.direccion || d.ciudad || '';
          event.start = { dateTime: startISO };
          event.end = { dateTime: endISO };
        }
        else if (item.type === 'tour') {
          const dt = parseTimeAndDate(d.fecha, '09:00');
          const startISO = dt.date + 'T' + dt.time + ':00';
          const endISO = addDuration(startISO, d.duracion || '3h');

          event.summary = '🗺️ Tour: ' + (d.nombre || 'Tour');
          event.description = 'Operador: ' + (d.operador || '—') + '\\nDuración: ' + (d.duracion || '—') + '\\nReserva: ' + (d.reserva || '—') + '\\nNotas: ' + (d.notes || '—');
          event.location = d.operador || '';
          event.start = { dateTime: startISO };
          event.end = { dateTime: endISO };
        }

        if (event.summary) {
          const userTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone || 'UTC';
          if (event.start.dateTime) {
            event.start.timeZone = userTimeZone;
            event.end.timeZone = userTimeZone;
          }
          events.push(event);
        }
      });
    });

    return { events };
  } catch (err) {
    console.error('Error parsing calendar events:', err);
    return { error: 'Error interno al procesar los eventos del calendario: ' + err.message };
  }
}

function downloadICSFile(tripTitle, events) {
  try {
    let icsContent = [
      'BEGIN:VCALENDAR',
      'VERSION:2.0',
      'PRODID:-//Viantryp//Itinerary Calendar//ES',
      'CALSCALE:GREGORIAN',
      'METHOD:PUBLISH'
    ];

    const formatICSDate = (dtObj) => {
      if (dtObj.date) return dtObj.date.replace(/-/g, '');
      if (dtObj.dateTime) return dtObj.dateTime.replace(/[-:]/g, '').substring(0, 15);
      return '';
    };

    events.forEach((ev, idx) => {
      const isAllDay = !!ev.start.date;
      const dtStartStr = formatICSDate(ev.start);
      const dtEndStr = formatICSDate(ev.end);
      
      icsContent.push('BEGIN:VEVENT');
      icsContent.push('UID:viantryp-' + idx + '-' + Date.now() + '@viantryp.com');
      
      const nowStr = new Date().toISOString().replace(/[-:]/g, '').split('.')[0] + 'Z';
      icsContent.push('DTSTAMP:' + nowStr);
      
      if (isAllDay) {
        icsContent.push('DTSTART;VALUE=DATE:' + dtStartStr);
        icsContent.push('DTEND;VALUE=DATE:' + dtEndStr);
      } else {
        icsContent.push('DTSTART:' + dtStartStr);
        icsContent.push('DTEND:' + dtEndStr);
      }
      
      const escapeText = (t) => t ? t.replace(/\\\\/g, '\\\\\\\\').replace(/,/g, '\\\\,').replace(/;/g, '\\\\;').replace(/\\n/g, '\\\\n') : '';
      
      icsContent.push('SUMMARY:' + escapeText(ev.summary));
      if (ev.description) icsContent.push('DESCRIPTION:' + escapeText(ev.description));
      if (ev.location) icsContent.push('LOCATION:' + escapeText(ev.location));
      icsContent.push('END:VEVENT');
    });

    icsContent.push('END:VCALENDAR');

    const fullContent = icsContent.join('\\r\\n');
    const blob = new Blob([fullContent], { type: 'text/calendar;charset=utf-8' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    const safeTitle = (tripTitle || 'viaje').toLowerCase().replace(/[^a-z0-9]/g, '_');
    link.download = safeTitle + '_itinerario.ics';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
  } catch (err) {
    console.error('Error in downloadICSFile:', err);
    alert('Error al descargar el archivo ICS: ' + err.message);
  }
}

window.openGoogleCalendarModal = function() {
  try {
    const m = document.getElementById('viantrypCalendarModal');
    if (!m) {
      alert('No se pudo encontrar el contenedor del modal del calendario.');
      return;
    }
    
    const dateCheck = extractEventsForCalendar(tripData);
    const datesWarning = document.getElementById('calModalDatesWarning');
    const googleBtnCard = document.getElementById('googleDirectSyncCard');
    
    if (dateCheck.error) {
      if (datesWarning) datesWarning.style.display = 'block';
    } else {
      if (datesWarning) datesWarning.style.display = 'none';
    }

    const clientIdNotice = document.getElementById('clientIdMissingNotice');
    if (!tripData.googleClientId) {
      if (clientIdNotice) clientIdNotice.style.display = 'block';
      if (googleBtnCard) {
        googleBtnCard.style.opacity = '0.5';
        googleBtnCard.style.pointerEvents = 'none';
      }
    } else {
      if (clientIdNotice) clientIdNotice.style.display = 'none';
      if (googleBtnCard) {
        googleBtnCard.style.opacity = '1';
        googleBtnCard.style.pointerEvents = 'auto';
      }
    }

    m.style.display = 'flex';
    setTimeout(() => {
      m.style.opacity = '1';
      const innerDiv = m.querySelector('div');
      if (innerDiv) {
        innerDiv.style.transform = 'translateY(0)';
      }
    }, 10);
  } catch (err) {
    console.error('Error in openGoogleCalendarModal:', err);
    alert('Error al intentar abrir el modal del calendario: ' + err.message);
  }
};

window.closeGoogleCalendarModal = function() {
  try {
    const m = document.getElementById('viantrypCalendarModal');
    if (!m) return;
    m.style.opacity = '0';
    const innerDiv = m.querySelector('div');
    if (innerDiv) {
      innerDiv.style.transform = 'translateY(20px)';
    }
    setTimeout(() => { m.style.display = 'none'; }, 250);
  } catch (err) {
    console.error('Error in closeGoogleCalendarModal:', err);
  }
};

window.triggerICSDownload = function() {
  try {
    const parsed = extractEventsForCalendar(tripData);
    if (parsed.error) {
      alert(parsed.error);
      return;
    }
    const events = parsed.events || [];
    if (events.length === 0) {
      alert('No hay actividades válidas para agregar en este viaje.');
      return;
    }
    downloadICSFile(tripData.title, events);
    closeGoogleCalendarModal();
  } catch (err) {
    console.error('Error in triggerICSDownload:', err);
    alert('Error al generar la descarga del calendario: ' + err.message);
  }
};

window.syncWithGoogleCalendar = function() {
  try {
    if (!tripData.googleClientId) {
      alert('La sincronización de Google Calendar no está disponible.');
      return;
    }

    const parsed = extractEventsForCalendar(tripData);
    if (parsed.error) {
      alert(parsed.error);
      return;
    }

    const events = parsed.events;
    if (!events || events.length === 0) {
      alert('No hay actividades para agregar en este viaje.');
      return;
    }

    const progressCont = document.getElementById('syncProgressContainer');
    const progressText = document.getElementById('syncProgressText');
    const progressPercent = document.getElementById('syncProgressPercent');
    const progressBar = document.getElementById('syncProgressBar');

    getGoogleAccessToken(tripData.googleClientId, async (accessToken) => {
      try {
        if (!accessToken) {
          alert('No se obtuvo el token de acceso de Google.');
          return;
        }

        if (progressCont) progressCont.style.display = 'block';
        if (progressText) progressText.innerText = 'Sincronizando actividades...';
        
        let successCount = 0;
        
        for (let i = 0; i < events.length; i++) {
          const ev = events[i];
          if (progressText) progressText.innerText = 'Agregando evento ' + (i + 1) + ' de ' + events.length + '...';
          
          const percent = Math.round(((i + 1) / events.length) * 100);
          if (progressPercent) progressPercent.innerText = percent + '%';
          if (progressBar) progressBar.style.width = percent + '%';

          try {
            await addEventToGoogleCalendar(accessToken, ev);
            successCount++;
          } catch (err) {
            console.error('Error adding event to Google Calendar:', err);
          }
        }

        if (progressText) progressText.innerText = '¡Sincronizado! ' + successCount + ' de ' + events.length + ' agregados.';
        setTimeout(() => {
          alert('Se sincronizaron con éxito ' + successCount + ' actividades en tu Google Calendar.');
          closeGoogleCalendarModal();
          if (progressCont) progressCont.style.display = 'none';
          if (progressBar) progressBar.style.width = '0%';
        }, 1000);
      } catch (innerErr) {
        console.error('Error during calendar sync iteration:', innerErr);
        alert('Error durante la sincronización: ' + innerErr.message);
      }
    });
  } catch (err) {
    console.error('Error in syncWithGoogleCalendar:', err);
    alert('Error al iniciar la sincronización de Google: ' + err.message);
  }
};

// Map script logic
let viantrypMapInstance = null;
let viantrypMapMarkers = [];
let viantrypMapPolyline = null;
let mapGeocodedPoints = [];
let activeMapDayIndex = -1;

const mapMarkerTypes = {
  flight: { color: '#3b82f6', icon: 'fa-plane' },
  alojamiento: { color: '#0d9488', icon: 'fa-hotel' },
  transporte: { color: '#10b981', icon: 'fa-bus' },
  actividad: { color: '#8b5cf6', icon: 'fa-bullseye' },
  comida: { color: '#f97316', icon: 'fa-utensils' },
  tour: { color: '#ef4444', icon: 'fa-route' }
};

function loadLeaflet(callback) {
  if (window.L) {
    callback();
    return;
  }
  const link = document.createElement('link');
  link.rel = 'stylesheet';
  link.href = 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css';
  document.head.appendChild(link);

  const script = document.createElement('script');
  script.src = 'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js';
  script.onload = () => {
    callback();
  };
  document.head.appendChild(script);
}

async function geocodeAddress(address) {
  if (!address || !address.trim()) return null;
  const cleanAddr = address.trim();
  const cacheKey = 'vt_geo_' + btoa(unescape(encodeURIComponent(cleanAddr)));
  
  const cached = localStorage.getItem(cacheKey);
  if (cached) {
    try {
      return JSON.parse(cached);
    } catch (e) {}
  }
  
  const queryList = [cleanAddr];
  
  // 1. Extraer IATA en paréntesis, ej. "Madrid Barajas (MAD)"
  const parenMatch = cleanAddr.match(/\\(([^)]+)\\)/);
  if (parenMatch) {
    const code = parenMatch[1].trim();
    if (code.length === 3 && /^[A-Z]{3}$/i.test(code)) {
      queryList.push('Aeropuerto ' + code.toUpperCase());
      queryList.push(code.toUpperCase() + ' Airport');
    }
  }
  
  // 2. Extraer partes por guiones o barras diagonales (común en aeropuertos, ej. "BOG - Aeropuerto El Dorado")
  if (cleanAddr.includes('-') || cleanAddr.includes('/')) {
    const separators = /[-/]/;
    const parts = cleanAddr.split(separators).map(p => p.trim());
    parts.forEach(part => {
      if (part.length === 3 && /^[A-Z]{3}$/i.test(part)) {
        queryList.push('Aeropuerto ' + part.toUpperCase());
        queryList.push(part.toUpperCase() + ' Airport');
      } else if (part.length > 3) {
        queryList.push(part);
        // Si no dice aeropuerto ni estación, agregar versión con aeropuerto
        const lower = part.toLowerCase();
        if (!lower.includes('aeropuerto') && !lower.includes('airport') && !lower.includes('estacion') && !lower.includes('station')) {
          queryList.push('Aeropuerto ' + part);
        }
      }
    });
  }
  
  // 3. Si es un código IATA de 3 letras directo
  if (cleanAddr.length === 3 && /^[A-Z]{3}$/i.test(cleanAddr)) {
    queryList.unshift('Aeropuerto ' + cleanAddr.toUpperCase());
    queryList.push(cleanAddr.toUpperCase() + ' Airport');
  }

  // 4. Si contiene comas (ej. direcciones estructuradas de Google)
  if (cleanAddr.includes(',')) {
    const parts = cleanAddr.split(',');
    if (parts.length > 1) {
      queryList.push(parts.slice(-2).join(',').trim());
      queryList.push(parts[parts.length - 1].trim());
    }
  }
  
  // 5. Limpieza general de paréntesis como fallback
  if (cleanAddr.includes('(')) {
    const outside = cleanAddr.replace(/\\(.*?\\)/g, '').trim();
    if (outside) queryList.push(outside);
  }

  // Filtrar duplicados y vacíos
  const uniqueQueries = [...new Set(queryList.map(q => q.trim()).filter(q => q.length > 0))];

  for (const query of uniqueQueries) {
    try {
      const response = await fetch('https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(query), {
        headers: {
          'Accept-Language': 'es'
        }
      });
      if (response.ok) {
        const results = await response.json();
        if (results && results.length > 0) {
          const coords = {
            lat: parseFloat(results[0].lat),
            lon: parseFloat(results[0].lon)
          };
          localStorage.setItem(cacheKey, JSON.stringify(coords));
          return coords;
        }
      }
    } catch (err) {
      console.error('Error in Nominatim geocoding:', err);
    }
    // Breve pausa para cumplir políticas de uso de Nominatim
    await new Promise(r => setTimeout(r, 200));
  }
  return null;
}

function extractMapPoints(data) {
  const points = [];
  const { days, dayDates, numericTabs } = data;
  if (!numericTabs) return points;

  numericTabs.forEach((tab, tabIdx) => {
    const dayIndex = tab.idx;
    const dayLabel = 'Día ' + (tabIdx + 1);
    const items = days[dayIndex] || [];
    if (!Array.isArray(items)) return;

    items.forEach(item => {
      if (!item || !item.data) return;
      const d = item.data;

      let name = '';
      let address = '';
      let type = item.type;
      let timeStr = '';

      if (item.type === 'flight') {
        if (d.origen) {
          points.push({
            name: (d.origen_city || d.origen) + ' (Origen)',
            address: d.origen,
            type: 'flight',
            dayLabel: dayLabel,
            dayIndex: tabIdx,
            time: d.salida ? d.salida.split('T')[1] || d.salida : ''
          });
        }
        if (d.destino) {
          points.push({
            name: (d.destino_city || d.destino) + ' (Destino)',
            address: d.destino,
            type: 'flight',
            dayLabel: dayLabel,
            dayIndex: tabIdx,
            time: d.llegada ? d.llegada.split('T')[1] || d.llegada : ''
          });
        }
        return;
      }
      else if (item.type === 'alojamiento') {
        name = d.nombre || 'Alojamiento';
        const parts = [];
        if (d.direccion) parts.push(d.direccion);
        if (d.ciudad) parts.push(d.ciudad);
        address = parts.length > 0 ? parts.join(', ') : '';
        timeStr = d.checkin ? 'Check-in: ' + d.checkin : '';
      }
      else if (item.type === 'transporte') {
        if (d.origen) {
          points.push({
            name: d.origen + ' (Origen)',
            address: d.origen_address || d.origen,
            type: 'transporte',
            dayLabel: dayLabel,
            dayIndex: tabIdx,
            time: d.salida || d.fecha || ''
          });
        }
        if (d.destino) {
          points.push({
            name: d.destino + ' (Destino)',
            address: d.destino_address || d.destino,
            type: 'transporte',
            dayLabel: dayLabel,
            dayIndex: tabIdx,
            time: d.llegada || ''
          });
        }
        return;
      }
      else if (item.type === 'actividad') {
        name = d.nombre || 'Actividad';
        const parts = [];
        if (d.direccion) parts.push(d.direccion);
        if (d.lugar) parts.push(d.lugar);
        address = parts.length > 0 ? parts.join(', ') : '';
        timeStr = d.fecha || '';
      }
      else if (item.type === 'comida') {
        name = d.restaurante || 'Comida';
        const parts = [];
        if (d.direccion) parts.push(d.direccion);
        if (d.ciudad) parts.push(d.ciudad);
        address = parts.length > 0 ? parts.join(', ') : '';
        timeStr = d.fecha || '';
      }
      else if (item.type === 'tour') {
        name = d.nombre || 'Tour';
        const parts = [];
        if (d.direccion) parts.push(d.direccion);
        if (d.operador) parts.push(d.operador);
        address = parts.length > 0 ? parts.join(', ') : '';
        timeStr = d.fecha || '';
      }

      if (address && address.trim()) {
        points.push({
          name: name,
          address: address,
          type: type,
          dayLabel: dayLabel,
          dayIndex: tabIdx,
          time: timeStr
        });
      }
    });
  });
  return points;
}

function populateMapSidebar(points) {
  const sidebar = document.getElementById('mapModalSidebar');
  if (!sidebar) return;
  sidebar.innerHTML = '';

  if (points.length === 0) {
    sidebar.innerHTML = '<div style="font-size:12px; color:var(--muted); text-align:center; padding:20px;">Sin locaciones registradas en este día</div>';
    return;
  }

  let currentDay = '';

  points.forEach((pt) => {
    if (pt.dayLabel !== currentDay) {
      currentDay = pt.dayLabel;
      const title = document.createElement('div');
      title.className = 'map-sidebar-day-title';
      
      let dayTitle = currentDay;
      const tab = tripData.numericTabs[pt.dayIndex];
      const dStr = (tab && tripData.dayDates && tripData.dayDates[tab.idx]) ? tripData.dayDates[tab.idx] : '';
      if (dStr) {
        try {
          const d = new Date(dStr + 'T00:00:00');
          const weekday = d.toLocaleDateString('es', { weekday: 'long' }).toLowerCase();
          const dayNum = d.toLocaleDateString('es', { day: 'numeric' });
          const month = d.toLocaleDateString('es', { month: 'long' }).toLowerCase();
          dayTitle = currentDay + ' - ' + weekday + ', ' + dayNum + ' de ' + month;
        } catch (e) {
          console.error('Error formatting date in sidebar header:', e);
        }
      }
      title.innerText = dayTitle;
      sidebar.appendChild(title);
    }

    const item = document.createElement('div');
    item.className = 'map-sidebar-item';
    const cfg = mapMarkerTypes[pt.type] || { color: '#64748b', icon: 'fa-location-dot' };
    
    item.innerHTML = [
      '<div class="map-sidebar-item-icon" style="background:' + cfg.color + '15; color:' + cfg.color + ';">',
        '<i class="fa-solid ' + cfg.icon + '"></i>',
      '</div>',
      '<div class="map-sidebar-item-info">',
        '<div class="map-sidebar-item-title">' + pt.name + '</div>',
        '<div class="map-sidebar-item-addr">' + pt.address + '</div>',
      '</div>'
    ].join('');

    item.onclick = () => {
      if (viantrypMapInstance) {
        viantrypMapInstance.flyTo([pt.lat, pt.lon], 15, { duration: 1.5 });
        viantrypMapInstance.eachLayer(layer => {
          if (layer instanceof L.Marker) {
            const latlng = layer.getLatLng();
            if (Math.abs(latlng.lat - pt.lat) < 0.0001 && Math.abs(latlng.lng - pt.lon) < 0.0001) {
              layer.openPopup();
            }
          }
        });
      }
    };
    sidebar.appendChild(item);
  });
}

function redrawMapPoints(points) {
  viantrypMapMarkers.forEach(m => m.remove());
  viantrypMapMarkers = [];

  if (viantrypMapPolyline) {
    viantrypMapPolyline.remove();
    viantrypMapPolyline = null;
  }

  const markerBounds = [];
  const routeCoords = [];

  points.forEach((pt) => {
    const latlng = [pt.lat, pt.lon];
    markerBounds.push(latlng);
    routeCoords.push(latlng);

    const cfg = mapMarkerTypes[pt.type] || { color: '#64748b', icon: 'fa-location-dot' };

    const customIcon = L.divIcon({
      html: [
        '<div class="viantryp-custom-marker" style="background:' + cfg.color + ';">',
          '<i class="fa-solid ' + cfg.icon + '"></i>',
        '</div>'
      ].join(''),
      className: 'leaflet-custom-marker-wrapper',
      iconSize: [30, 30],
      iconAnchor: [15, 15],
      popupAnchor: [0, -15]
    });

    const popupContent = [
      '<div class="viantryp-map-popup-card">',
        '<div class="viantryp-map-popup-type" style="color:' + cfg.color + '; font-weight:700; font-size:9px; text-transform:uppercase;">' + pt.type + '</div>',
        '<div class="viantryp-map-popup-title" style="font-weight:700; font-size:13px; color:#0f172a;">' + pt.name + '</div>',
        '<div class="viantryp-map-popup-addr" style="font-size:10px; color:#64748b;">' + pt.address + '</div>',
        '<div class="viantryp-map-popup-meta" style="font-size:9px; color:#94a3b8; display:flex; gap:8px; margin-top:4px;">',
          '<span><i class="fa-regular fa-calendar"></i> ' + pt.dayLabel + '</span>',
          pt.time ? '<span><i class="fa-regular fa-clock"></i> ' + pt.time + '</span>' : '',
        '</div>',
      '</div>'
    ].join('');

    const marker = L.marker(latlng, { icon: customIcon })
      .bindPopup(popupContent)
      .addTo(viantrypMapInstance);
      
    viantrypMapMarkers.push(marker);
  });

  if (routeCoords.length > 1) {
    viantrypMapPolyline = L.polyline(routeCoords, {
      color: 'var(--accent)',
      weight: 3,
      dashArray: '6, 8',
      opacity: 0.85
    }).addTo(viantrypMapInstance);
  }

  if (markerBounds.length > 0) {
    viantrypMapInstance.fitBounds(markerBounds, {
      padding: [50, 50],
      maxZoom: 15
    });
  }
}

function renderLeafletMap(points) {
  viantrypMapInstance = L.map('viantrypMapCanvas', {
    zoomControl: true,
    scrollWheelZoom: true
  });

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap'
  }).addTo(viantrypMapInstance);

  redrawMapPoints(points);
}

function filterMapData(dayIdx) {
  activeMapDayIndex = dayIdx;
  
  const filteredPoints = dayIdx === -1 
    ? mapGeocodedPoints 
    : mapGeocodedPoints.filter(pt => pt.dayIndex === dayIdx);
    
  populateMapSidebar(filteredPoints);
  
  if (viantrypMapInstance) {
    redrawMapPoints(filteredPoints);
  }
}

window.openInteractiveMapModal = function() {
  try {
    const m = document.getElementById('viantrypMapModal');
    if (!m) {
      alert('No se encontró el modal del mapa en el DOM.');
      return;
    }

    m.style.display = 'flex';
    setTimeout(() => {
      m.style.opacity = '1';
      const innerDiv = m.querySelector('div');
      if (innerDiv) {
        innerDiv.style.transform = 'translateY(0)';
      }
    }, 10);

    const loader = document.getElementById('mapModalLoader');
    const loaderText = document.getElementById('mapModalLoaderText');
    const loaderProgress = document.getElementById('mapModalLoaderProgress');
    
    if (loader) loader.style.display = 'flex';
    if (loaderText) loaderText.innerText = 'Cargando mapa...';
    if (loaderProgress) loaderProgress.innerText = 'Cargando librerías...';

    loadLeaflet(async () => {
      try {
        if (loaderText) loaderText.innerText = 'Procesando locaciones...';
        const points = extractMapPoints(tripData);

        if (points.length === 0) {
          if (loader) loader.style.display = 'none';
          alert('Este viaje no tiene direcciones registradas para mostrar en el mapa.');
          closeInteractiveMapModal();
          return;
        }

        if (loaderText) loaderText.innerText = 'Geolocalizando puntos del viaje...';
        const geocodedPoints = [];
        for (let i = 0; i < points.length; i++) {
          const pt = points[i];
          if (loaderProgress) {
            loaderProgress.innerText = 'Localizando ' + (i + 1) + ' de ' + points.length + ': ' + pt.name;
          }
          const coords = await geocodeAddress(pt.address);
          if (coords) {
            geocodedPoints.push({
              ...pt,
              lat: coords.lat,
              lon: coords.lon
            });
          }
        }

        if (geocodedPoints.length === 0) {
          if (loader) loader.style.display = 'none';
          alert('No se pudo localizar ninguna dirección en el mapa. Por favor verifica que las direcciones ingresadas existan.');
          closeInteractiveMapModal();
          return;
        }

        mapGeocodedPoints = geocodedPoints;
        activeMapDayIndex = -1;

        const tabSelector = document.getElementById('viantrypMapDaySelector');
        if (tabSelector) {
          tabSelector.innerHTML = '';
          
          const genTab = document.createElement('button');
          genTab.className = 'viantryp-map-tab active';
          genTab.innerText = 'Vista General';
          genTab.onclick = () => {
            document.querySelectorAll('.viantryp-map-tab').forEach(t => t.classList.remove('active'));
            genTab.classList.add('active');
            filterMapData(-1);
          };
          tabSelector.appendChild(genTab);
          
          tripData.numericTabs.forEach((tab, i) => {
            const dayTab = document.createElement('button');
            dayTab.className = 'viantryp-map-tab';
            
            let label = 'Día ' + (i + 1);
            const dStr = tripData.dayDates && tripData.dayDates[tab.idx] ? tripData.dayDates[tab.idx] : '';
            if (dStr) {
              try {
                const d = new Date(dStr + 'T00:00:00');
                const dayNum = d.toLocaleDateString('es', { day: 'numeric' });
                const month = d.toLocaleDateString('es', { month: 'short' }).replace('.', '');
                const cap = s => s.charAt(0).toUpperCase() + s.slice(1);
                label = dayNum + ' ' + cap(month);
              } catch (e) {
                console.error('Error formatting date in map tab:', e);
              }
            }
            
            dayTab.innerText = label;
            dayTab.onclick = () => {
              document.querySelectorAll('.viantryp-map-tab').forEach(t => t.classList.remove('active'));
              dayTab.classList.add('active');
              filterMapData(i);
            };
            tabSelector.appendChild(dayTab);
          });
        }

        populateMapSidebar(geocodedPoints);

        setTimeout(() => {
          try {
            renderLeafletMap(geocodedPoints);
            if (loader) loader.style.display = 'none';
          } catch (mapErr) {
            console.error('Error al renderizar mapa Leaflet:', mapErr);
            alert('Error al renderizar el mapa interactivo: ' + mapErr.message);
          }
        }, 300);

      } catch (innerErr) {
        console.error('Error in Leaflet initializer callback:', innerErr);
        alert('Error al inicializar mapa: ' + innerErr.message);
      }
    });

  } catch (err) {
    console.error('Error in openInteractiveMapModal:', err);
    alert('Error al abrir el mapa interactivo: ' + err.message);
  }
};

window.closeInteractiveMapModal = function() {
  try {
    const m = document.getElementById('viantrypMapModal');
    if (!m) return;
    m.style.opacity = '0';
    const innerDiv = m.querySelector('div');
    if (innerDiv) {
      innerDiv.style.transform = 'translateY(20px)';
    }
    setTimeout(() => { 
      m.style.display = 'none'; 
      if (viantrypMapInstance) {
        viantrypMapInstance.remove();
        viantrypMapInstance = null;
      }
      viantrypMapMarkers = [];
      viantrypMapPolyline = null;
      mapGeocodedPoints = [];
    }, 250);
  } catch (err) {
    console.error('Error in closeInteractiveMapModal:', err);
  }
};
</script>
${!isPublicLink && tripId ? `
<script>
  window.shareProTrip = async function() {
    const btn = document.querySelector('.pv-share-btn');
    const origText = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Generando...';
    btn.disabled = true;
    
    // El objeto data contiene isPublicLink falso, pero para guardar lo ponemos por defecto.
    const proStateObj = ${JSON.stringify(data).replace(/</g, '\\x3c')};
    
    try {
      const baseUrl = '${data.origin || ''}';
      const res = await fetch(baseUrl + '/trips/${tripId}/save-pro-state', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '${csrfToken}',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ pro_state: proStateObj })
      });
      
      if (!res.ok) {
        let msg = res.statusText;
        try { const d = await res.json(); msg = d.message || msg; } catch(e) {}
        throw new Error('HTTP ' + res.status + '\\n' + msg);
      }
      
      const json = await res.json();
      if (json.success) {
        
        // Función para mostrar nuestro modal estético
        const showShareModal = (url) => {
          const m = document.createElement('div');
          m.id = 'viantrypShareModal';
          m.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.4);backdrop-filter:blur(6px);z-index:9999;display:flex;align-items:center;justify-content:center;opacity:0;transition:opacity 0.2s;';
          m.innerHTML = \`
            <div style="background:#fff;border-radius:28px;width:95%;max-width:400px;padding:40px 32px;box-shadow:0 25px 60px rgba(0,0,0,0.2);transform:translateY(30px);transition:transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);text-align:center;">
              <div style="width:72px;height:72px;background:var(--accent-light);color:var(--accent);border-radius:24px;display:flex;align-items:center;justify-content:center;font-size:32px;margin:0 auto 24px;transform:rotate(-10deg);">
                <i class="fa-solid fa-paper-plane"></i>
              </div>
              <h3 style="margin:0 0 12px;font-family:\'Barlow\',sans-serif;font-size:24px;font-weight:700;color:#1a2e2c;">¡Itinerario listo!</h3>
              <p style="margin:0 0 32px;font-size:15px;color:#64748b;line-height:1.6;font-family:\'Barlow\',sans-serif;">Comparte este enlace con tu cliente para que tenga su itinerario de viaje en linea.</p>
              
              <div style="background:#f8fafc;border-radius:16px;padding:14px 18px;display:flex;align-items:center;gap:12px;margin-bottom:32px;border:1.5px solid #eef2f6;">
                <input type="text" value="\${url}" readonly style="flex:1;background:transparent;border:none;outline:none;font-size:14px;color:#334155;text-overflow:ellipsis;font-family:\'Barlow\',sans-serif;" id="shareUrlInput">
                <button id="copyShareModalBtn" style="background:var(--accent);border:none;color:#fff;cursor:pointer;width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;transition:0.3s;box-shadow:0 4px 12px var(--accent-light);"><i class="fa-regular fa-copy"></i></button>
              </div>
              
              <button id="closeShareModalBtn" style="width:100%;padding:16px;border-radius:16px;border:none;background:var(--accent);color:#fff;font-weight:700;font-size:15px;cursor:pointer;transition:all 0.3s;font-family:\'Barlow\',sans-serif;box-shadow:0 8px 20px var(--accent-light);">Cerrar</button>
            </div>
          \`;
          document.body.appendChild(m);
          
          document.getElementById('closeShareModalBtn').onclick = () => {
            m.style.opacity = '0';
            setTimeout(() => m.remove(), 200);
          };
          
          document.getElementById('copyShareModalBtn').onclick = function() {
            const inp = document.getElementById('shareUrlInput');
            inp.select();
            document.execCommand('copy');
            const b = this;
            const o = b.innerHTML;
            b.innerHTML = '<i class="fa-solid fa-check"></i>';
            b.style.background = '#10b981';
            b.style.boxShadow = '0 4px 12px rgba(16, 185, 129, 0.3)';
            setTimeout(() => { 
                b.innerHTML = o; 
                b.style.background = 'var(--accent)'; 
                b.style.boxShadow = '0 4px 12px var(--accent-light)';
            }, 2000);
          };
          setTimeout(() => { m.style.opacity = '1'; m.querySelector('div').style.transform = 'translateY(0)'; }, 10);
        };

        const copySuccess = () => {
          btn.innerHTML = '<i class="fa-solid fa-check"></i> ¡Copiado!';
          setTimeout(() => { btn.innerHTML = origText; btn.disabled = false; }, 3000);
          showShareModal(json.share_url);
        };

        if (navigator.clipboard && navigator.clipboard.writeText) {
          navigator.clipboard.writeText(json.share_url).then(copySuccess).catch(() => {
            showShareModal(json.share_url);
            btn.innerHTML = origText; btn.disabled = false;
          });
        } else {
          const input = document.createElement('input');
          input.value = json.share_url;
          document.body.appendChild(input);
          input.select();
          try {
            document.execCommand('copy');
            copySuccess();
          } catch(e) {
            showShareModal(json.share_url);
            btn.innerHTML = origText; btn.disabled = false;
          }
          document.body.removeChild(input);
        }
      } else {
        alert('Error: ' + json.message);
        btn.innerHTML = origText; btn.disabled = false;
      }
    } catch (err) {
      alert('Detalles del error al guardar:\\n' + err.message);
      btn.innerHTML = origText; btn.disabled = false;
    }
  };
</script>
` : ''}
</body>
</html>`;
}
