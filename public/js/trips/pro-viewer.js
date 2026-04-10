function buildPreviewHTML(data) {
  const { title, fechaInicio, fechaFin, precio, moneda, totalViajeros, hasPortada, hasCierre, showDefaultCierre, totalItems, numericTabs, days, dayDates, portadaAdultos, portadaNinos, portadaPhotoUrl, portadaItems, cierreItems, isPublicLink, csrfToken, tripId, userName, status, origin, themeColor, displayNameType, agencyLogo, agencyName, userFullName } = data;

  const statusMap = {
    'draft': { label: 'En Diseño', bg: '#e0f2fe', color: '#1d5fa8', bdr: '#bae6fd' },
    'sent': { label: 'Propuesta', bg: '#e8f8ff', color: '#0284c7', bdr: '#bae6fd' },
    'reserved': { label: 'Reservado', bg: '#dcfce7', color: '#15803d', bdr: '#bbf7d0' },
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
        const oriCity = d.origen_city || (d.origen ? d.origen.split(' (')[0] : '');
        const desCity = d.destino_city || (d.destino ? d.destino.split(' (')[0] : '');

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
            ${d.precio ? `<span class="pv-chip"><i class="fa-solid fa-tag"></i> $${d.precio} ${moneda}</span>` : ''}
            <div class="pv-flight-mobile-details" style="display:none; align-items:center; gap:8px;">
              ${d.aerolinea ? `<span style="font-size:12px; font-weight:500; color:var(--muted); opacity:0.8;">${d.aerolinea}</span>` : ''}
              ${d.vuelo ? `<span style="background:var(--accent); color:#fff; padding:3px 8px; border-radius:6px; font-size:11px; font-weight:600; text-transform:uppercase;">${d.vuelo}</span>` : ''}
            </div>
          </div>
          ${d.reserva ? `<div class="pv-notes-row" style="border-top:none;padding-top:0;margin-top:8px"><i class="fa-solid fa-ticket" style="margin-right:2px"></i> <b>Código de Reserva:</b> ${d.reserva}</div>` : ''}
          ${d.notas ? `<div class="pv-notes-row"><i class="fa-solid fa-circle-info"></i> ${d.notas}</div>` : ''}
          ${d.adjunto_url ? `<div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--border);"><a href="${fixUrl(d.adjunto_url)}" target="_blank" class="pv-attachment-btn"><i class="fa-solid fa-paperclip" style="margin-right:4px"></i> ${d.adjunto_name || 'Ver adjunto'}</a></div>` : ''}
        </div>`;
      }

      // ─────────────────────────────────────────────────────
      // ── ALOJAMIENTO  (referencia: imagen 3) ──────────────
      // foto izq + info derecha, check-in/out, habitación, desayuno
      // ─────────────────────────────────────────────────────
      if (item.type === 'alojamiento') {
        const nights = d.checkin && d.checkout ? Math.round((new Date(d.checkout) - new Date(d.checkin)) / (1000 * 60 * 60 * 24)) : null;
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
                ${d.checkin ? `<div class="pv-hd-row"><span class="pv-hd-label">Check-in:</span> ${fmtDayMonthWeekday(d.checkin)} - 15:00</div>` : ''}
                ${d.checkout ? `<div class="pv-hd-row"><span class="pv-hd-label">Check-out:</span> ${fmtDayMonthWeekday(d.checkout)} - 11:00</div>` : ''}
                ${nights ? `<div class="pv-hd-row">${nights} noche${nights !== 1 ? 's' : ''}</div>` : ''}
                ${d.habitacion ? `<div class="pv-hd-icon-row"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7v11m0-6h18m0-5v11M5 7h14a2 2 0 0 1 2 2v2H3V9a2 2 0 0 1 2-2z"/></svg> ${d.habitacion}</div>` : ''}
                ${d.alimentacion ? `<div class="pv-hd-icon-row" style="display:flex;align-items:center;gap:6px"><i class="fa-solid fa-utensils" style="width:14px;text-align:center;font-size:12px"></i> <span>${d.alimentacion}</span></div>` : ''}
              </div>
              <div class="pv-hotel-btns">
                ${d.website ? `<a href="${d.website}" target="_blank" class="pv-action-btn" style="text-decoration:none"><i class="fa-solid fa-globe"></i> Sitio web</a>` : ''}
                ${d.phone ? `<a href="tel:${d.phone}" class="pv-action-btn" style="text-decoration:none"><i class="fa-solid fa-phone"></i> ${d.phone}</a>` : ''}
                ${d.precio ? `<span class="pv-action-btn"><i class="fa-solid fa-tag"></i> $${d.precio} ${moneda}</span>` : ''}
              </div>
              ${d.reserva ? `<div class="pv-notes-row" style="border-top:none;padding-top:0;margin-top:8px"><i class="fa-solid fa-ticket" style="margin-right:2px"></i> <b>Código de Reserva:</b> ${d.reserva}</div>` : ''}
              ${d.notas ? `<div class="pv-notes-row"><i class="fa-solid fa-circle-info"></i> ${d.notas}</div>` : ''}
              ${d.adjunto_url ? `<div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--border);"><a href="${fixUrl(d.adjunto_url)}" target="_blank" class="pv-attachment-btn"><i class="fa-solid fa-paperclip" style="margin-right:4px"></i> ${d.adjunto_name || 'Ver adjunto'}</a></div>` : ''}
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
          ${d.precio ? `<div class="pv-chips-row"><span class="pv-chip"><i class="fa-solid fa-tag"></i> $${d.precio} ${moneda}</span></div>` : ''}
          ${d.reserva ? `<div class="pv-notes-row" style="border-top:none;padding-top:0;margin-top:8px"><i class="fa-solid fa-ticket" style="margin-right:2px"></i> <b>Código de Reserva:</b> ${d.reserva}</div>` : ''}
          ${d.notas ? `<div class="pv-notes-row"><i class="fa-solid fa-circle-info"></i> ${d.notas}</div>` : ''}
          ${d.adjunto_url ? `<div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--border);"><a href="${fixUrl(d.adjunto_url)}" target="_blank" class="pv-attachment-btn"><i class="fa-solid fa-paperclip" style="margin-right:4px"></i> ${d.adjunto_name || 'Ver adjunto'}</a></div>` : ''}
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
                ${d.precio ? `<span class="pv-action-btn"><i class="fa-solid fa-tag"></i> $${d.precio} ${moneda}</span>` : ''}
              </div>
              ${d.reserva ? `<div class="pv-notes-row" style="border-top:none;padding-top:0;margin-top:8px"><i class="fa-solid fa-ticket" style="margin-right:2px"></i> <b>Código de Reserva:</b> ${d.reserva}</div>` : ''}
              ${d.notas ? `<div class="pv-notes-row"><i class="fa-solid fa-circle-info"></i> ${d.notas}</div>` : ''}
              ${d.adjunto_url ? `<div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--border);"><a href="${fixUrl(d.adjunto_url)}" target="_blank" class="pv-attachment-btn"><i class="fa-solid fa-paperclip" style="margin-right:4px"></i> ${d.adjunto_name || 'Ver adjunto'}</a></div>` : ''}
            </div>
          </div>
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
                ${d.precio ? `<span class="pv-action-btn"><i class="fa-solid fa-tag"></i> $${d.precio} ${moneda}</span>` : ''}
                ${d.estado_reserva && d.estado_reserva !== 'No aplica' ? `<span class="pv-action-btn"><i class="fa-solid fa-calendar-check"></i> ${d.estado_reserva}</span>` : ''}
              </div>
              ${d.reserva ? `<div class="pv-notes-row" style="border-top:none;padding-top:0;margin-top:8px"><i class="fa-solid fa-ticket" style="margin-right:2px"></i> <b>Código de Reserva:</b> ${d.reserva}</div>` : ''}
              ${d.notas ? `<div class="pv-notes-row"><i class="fa-solid fa-circle-info"></i> ${d.notas}</div>` : ''}
              ${d.adjunto_url ? `<div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--border);"><a href="${fixUrl(d.adjunto_url)}" target="_blank" class="pv-attachment-btn"><i class="fa-solid fa-paperclip" style="margin-right:4px"></i> ${d.adjunto_name || 'Ver adjunto'}</a></div>` : ''}
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
                ${d.precio ? `<span class="pv-chip"><i class="fa-solid fa-tag"></i> $${d.precio} ${moneda}</span>` : ''}
              </div>
              ${d.reserva ? `<div class="pv-notes-row" style="border-top:none;padding-top:0;margin-top:8px"><i class="fa-solid fa-ticket" style="margin-right:2px"></i> <b>Código de Reserva:</b> ${d.reserva}</div>` : ''}
              ${d.notas ? `<div class="pv-notes-row"><i class="fa-solid fa-circle-info"></i> ${d.notas}</div>` : ''}
              ${d.adjunto_url ? `<div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--border);"><a href="${fixUrl(d.adjunto_url)}" target="_blank" class="pv-attachment-btn"><i class="fa-solid fa-paperclip" style="margin-right:4px"></i> ${d.adjunto_name || 'Ver adjunto'}</a></div>` : ''}
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
.pv-portada-wrap{max-width:900px;margin:28px auto 0;padding:0 24px}
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
.pv-portada-extra-items{max-width:900px;margin:12px auto 0;padding:0 24px;display:flex;flex-direction:column;gap:12px}
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
        <div class="pv-pm-value highlight">${precio ? moneda + ' $' + Number(precio).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '—'}</div>
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
  </aside>
  <main class="pv-content">
    ${daysHTML}
    ${hasCierre ? `
      ${showDefaultCierre ? `
      <div class="pv-cierre">
        <div class="pv-cierre-plane"><i class="fa-solid fa-plane"></i></div>
        <div class="pv-cierre-badge">¡ITINERARIO COMPLETO!</div>
        <div class="pv-cierre-title">${title}</div>
        <div class="pv-cierre-sub">Este itinerario fue creado por <b>${userName || window.viantrypUserName || 'Viantryp'}</b>.<br>¡Que tengas un viaje extraordinario!</div>
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
