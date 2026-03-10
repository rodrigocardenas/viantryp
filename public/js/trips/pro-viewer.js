// PRO Viewer Engine
document.addEventListener('DOMContentLoaded', () => {
  if (window.proState) {
    renderProViewer(window.proState);
  } else {
    document.getElementById('preview-root').innerHTML = '<div style="padding:40px; text-align:center; color:#64748b">No se encontraron datos para este itinerario.</div>';
  }
});

function renderProViewer(data) {
  const { title, fechaInicio, fechaFin, precio, moneda, totalViajeros, hasPortada, hasCierre, showDefaultCierre, days, dayDates, portadaAdultos, portadaNinos, portadaPhotoUrl, portadaItems, cierreItems } = data;

  // Helpers
  const fmtDateShort = s => { if (!s) return ''; try { return new Date(s + 'T00:00:00').toLocaleDateString('es', { day: 'numeric', month: 'short' }) } catch { return s } };
  const fmtDateTime = s => { if (!s) return ''; try { const d = new Date(s); const day = d.toLocaleDateString('es', { weekday: 'long', day: 'numeric', month: 'long' }); const time = d.toLocaleTimeString('es', { hour: '2-digit', minute: '2-digit' }); return { day, time } } catch { return { day: s, time: '' } } };
  const fmtDayMonth = s => { if (!s) return ''; try { const d = new Date(s + 'T00:00:00'); return d.toLocaleDateString('es', { day: 'numeric', month: 'long' }); } catch { return s } };
  const starsHTML = n => n ? Array.from({ length: 5 }, (_, i) => `<svg width="16" height="16" viewBox="0 0 24 24" fill="${i < n ? '#f59e0b' : '#d1d5db'}"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>`).join('') : '';

  const getVideoEmbedUrl = url => {
    if (!url) return null;
    let match = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/);
    if (match && match[1]) return `https://www.youtube.com/embed/${match[1]}`;
    match = url.match(/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/(?:[^\/]*)\/videos\/|album\/(?:\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)/);
    if (match && match[1]) return `https://player.vimeo.com/video/${match[1]}`;
    return null;
  };

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
    if (!items || !items.length) return '';
    return items.map(item => {
      const d = item.data;
      if (item.type === 'separador')
        return `<div class="pv-sep"><div class="pvs-line"></div>${d.etiqueta ? `<span class="pvs-label">${d.etiqueta}</span>` : ''}<div class="pvs-line"></div></div>`;
      if (item.type === 'titulo')
        return `<div class="pv-titulo"><div class="pvt-text">${d.emoji ? d.emoji + ' ' : ''}${d.texto || 'Título'}</div>${d.subtitulo ? `<div class="pvt-sub">${d.subtitulo}</div>` : ''}</div>`;
      if (item.type === 'texto')
        return `<div class="pv-texto" style="text-align:${(d.alineacion || 'Izquierda').toLowerCase()}">${d.contenido || ''}</div>`;
      if (item.type === 'imagen') {
        const hasImg = d.url && d.url.startsWith('http');
        return `<div class="pv-imagen">${hasImg ? `<img src="${d.url}" alt="${d.caption || ''}">` : '<div class="pv-img-ph"><i class="fa-regular fa-image"></i></div>'}${d.caption ? `<div class="pv-caption">${d.caption}</div>` : ''}</div>`;
      }
      if (item.type === 'caja') {
        const bg = d.color_fondo || '#7c6fef';
        return `<div class="pv-caja" style="background:${bg}10;border-left:4px solid ${bg}"><div class="pvc-caja-icon">${d.icono || '💡'}</div><div><div class="pvc-caja-title">${d.titulo || ''}</div><div class="pvc-caja-content">${d.contenido || ''}</div></div></div>`;
      }
      if (item.type === 'gif') {
        return `<div class="pv-imagen" style="box-shadow:none;border-radius:10px"><img src="${d.url}" style="width:100%;border-radius:10px">${d.caption ? `<div class="pv-caption">${d.caption}</div>` : ''}</div>`;
      }
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
                    <div class="pv-route-end"><div class="pv-route-time">${sal.time || '—'}</div><div class="pv-route-station">${d.origen || 'Origen'}</div>${sal.day ? `<div class="pv-route-sub">${sal.day}</div>` : ''}</div>
                    <div class="pv-route-mid"><svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" style="color:var(--muted)"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg></div>
                    <div class="pv-route-end pv-route-right"><div class="pv-route-time">${lle.time || '—'}</div><div class="pv-route-station">${d.destino || 'Destino'}</div>${lle.day ? `<div class="pv-route-sub">${lle.day}</div>` : ''}</div>
                  </div>
                  ${d.clase || d.precio ? `<div class="pv-chips-row">${d.clase ? `<span class="pv-chip"><i class="fa-solid fa-chair"></i> ${d.clase}</span>` : ''}${d.precio ? `<span class="pv-chip">💰 $${d.precio} ${moneda || 'COP'}</span>` : ''}</div>` : ''}
                  ${d.notas ? `<div class="pv-notes-row"><i class="fa-sharp fa-light fa-circle-exclamation"></i> ${d.notas}</div>` : ''}
                </div>`;
      }
      if (item.type === 'alojamiento') {
        const nights = d.checkin && d.checkout ? Math.round((new Date(d.checkout) - new Date(d.checkin)) / (1000 * 60 * 60 * 24)) : null;
        return `<div class="pv-card">
                  <div class="pvc-section-label" style="color:#0e7aad"><i class="fa-solid fa-hotel"></i> Alojamiento</div>
                  <div class="pv-hotel-layout">
                    <div class="pv-hotel-photo-slot">${cCarousel(d.photo_url, '<i class="fa-solid fa-hotel"></i>')}</div>
                    <div class="pv-hotel-info-col">
                      <div class="pv-hotel-title-row">
                        <div class="pv-hotel-name">${d.nombre || 'Hotel'}</div>
                        ${d.stars ? `<div class="pv-stars-row">${starsHTML(d.stars)}</div>` : ''}
                      </div>
                      ${d.direccion ? `<div class="pv-hotel-addr"><i class="fa-solid fa-location-dot"></i> ${d.direccion}</div>` : ''}
                      <div class="pv-hotel-details">
                        ${d.checkin ? `<div class="pv-hd-row"><span class="pv-hd-label">Check-in:</span> ${d.checkin}</div>` : ''}
                        ${d.checkout ? `<div class="pv-hd-row"><span class="pv-hd-label">Check-out:</span> ${d.checkout}</div>` : ''}
                        ${nights ? `<div class="pv-hd-row">${nights} noche${nights !== 1 ? 's' : ''}</div>` : ''}
                        ${d.habitacion ? `<div class="pv-hd-icon-row"><i class="fa-solid fa-bed"></i> ${d.habitacion}</div>` : ''}
                      </div>
                      <div class="pv-hotel-btns">
                        ${d.website ? `<a href="${d.website}" target="_blank" class="pv-action-btn">🌐 Sitio web</a>` : ''}
                        ${d.phone ? `<a href="tel:${d.phone}" class="pv-action-btn">📞 ${d.phone}</a>` : ''}
                      </div>
                      ${d.notas ? `<div class="pv-notes-row">${d.notas}</div>` : ''}
                    </div>
                  </div>
                </div>`;
      }
      if (item.type === 'transporte') {
        const sal = d.salida || d.fecha ? fmtDateTime(d.salida || d.fecha) : { day: '', time: '' };
        const lle = d.llegada ? fmtDateTime(d.llegada) : { day: '', time: '' };
        return `<div class="pv-card">
                  <div class="pvc-section-label" style="color:#0e7aad">${getTransportIcon(d.tipo, 16)} ${d.tipo || 'Transporte'}</div>
                  <div class="pv-route-row">
                    <div class="pv-route-end"><div class="pv-route-time">${sal.time || '—'}</div><div class="pv-route-station">${d.origen || 'Origen'}</div>${sal.day ? `<div class="pv-route-sub">${sal.day}</div>` : ''}</div>
                    <div class="pv-route-mid">${getTransportIcon(d.tipo, 22)}</div>
                    <div class="pv-route-end pv-route-right"><div class="pv-route-time">${lle.time || '—'}</div><div class="pv-route-station">${d.destino || 'Destino'}</div>${lle.day ? `<div class="pv-route-sub">${lle.day}</div>` : ''}</div>
                  </div>
                  ${d.notas ? `<div class="pv-notes-row">${d.notas}</div>` : ''}
                </div>`;
      }
      if (item.type === 'actividad' || item.type === 'comida' || item.type === 'tour') {
        const dt = d.fecha ? fmtDateTime(d.fecha) : { day: '', time: '' };
        const icon = item.type === 'comida' ? 'fa-utensils' : (item.type === 'tour' ? 'fa-map-location-dot' : 'fa-bullseye');
        return `<div class="pv-card">
                  <div class="pvc-section-label" style="color:#0e7aad"><i class="fa-solid ${icon}"></i> ${item.type.charAt(0).toUpperCase() + item.type.slice(1)}</div>
                  <div class="pv-media-layout">
                    <div class="pv-media-photo-slot">${cCarousel(d.photo_url, `<i class="fa-solid ${icon}"></i>`)}</div>
                    <div class="pv-media-info-col">
                      <div class="pv-media-name">${d.nombre || d.restaurante || 'Elemento'}</div>
                      ${d.direccion || d.lugar ? `<div class="pv-media-addr"><i class="fa-solid fa-location-dot"></i> ${d.direccion || d.lugar}</div>` : ''}
                      ${dt.time ? `<div class="pv-media-time"><i class="fa-solid fa-clock"></i> ${dt.time}</div>` : ''}
                      ${d.descripcion ? `<div class="pv-media-desc">${d.descripcion}</div>` : ''}
                      <div class="pv-hotel-btns" style="margin-top:10px">
                        ${d.website ? `<a href="${d.website}" target="_blank" class="pv-action-btn">🌐 Sitio web</a>` : ''}
                        ${d.precio ? `<span class="pv-action-btn pv-action-btn-blue">💰 $${d.precio} ${moneda || 'COP'}</span>` : ''}
                      </div>
                    </div>
                  </div>
                </div>`;
      }
      return '';
    }).join('');
  }

  // Process Days
  const numericTabs = days.map((_, i) => ({ label: `Día ${i + 1}`, idx: i })).filter(t => days[t.idx] && days[t.idx].length > 0 || dayDates[t.idx]);

  const daysHTML = numericTabs.map((tab, i) => {
    const items = days[tab.idx] || [];
    const dateStr = dayDates && dayDates[tab.idx] ? dayDates[tab.idx] : '';
    const dayTitle = dateStr
      ? new Date(dateStr + 'T00:00:00').toLocaleDateString('es', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }).replace(/^\w/, c => c.toUpperCase())
      : tab.label;
    return `<section class="pv-day" id="day-${tab.idx}" style="margin-bottom:40px">
          <div class="pvday-header" style="display:flex; align-items:center; gap:12px; margin-bottom:20px; border-bottom:2px solid #e2e8f0; padding-bottom:10px">
            <div class="pvday-pill" style="background:#0e7aad; color:white; padding:4px 12px; border-radius:20px; font-size:11px; font-weight:700">Día ${i + 1}</div>
            <div class="pvday-title" style="font-size:20px; font-weight:800; color:#0f172a; flex:1">${dayTitle}</div>
          </div>
          <div class="pvday-items" style="display:flex; flex-direction:column; gap:15px">${renderPreviewItems(items)}</div>
        </section>`;
  }).join('');

  const sidebarNav = numericTabs.map((tab, i) => {
    const dStr = dayDates && dayDates[tab.idx] ? dayDates[tab.idx] : '';
    const dateLabel = dStr ? fmtDayMonth(dStr) : tab.label;
    return `<a class="pvnav-link" href="#day-${tab.idx}" style="display:block; padding:10px 15px; text-decoration:none; color:#64748b; font-size:13px; border-left:3px solid transparent; transition:0.2s">
            <span style="font-weight:700; color:#94a3b8; font-size:10px; display:block; text-transform:uppercase; margin-bottom:2px">Día ${i + 1}</span>
            ${dateLabel}
        </a>`;
  }).join('');

  // Final Assembly
  const html = `
    <style>
        .pv-card { background: white; border-radius: 12px; padding: 20px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); }
        .pvc-section-label { font-size: 11px; font-weight: 800; text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.5px; }
        .pv-route-row { display: flex; align-items: center; justify-content: space-between; margin: 15px 0; }
        .pv-route-time { font-size: 24px; font-weight: 800; color: #0f172a; }
        .pv-route-station { font-size: 14px; font-weight: 700; color: #1e293b; }
        .pv-route-sub { font-size: 11px; color: #64748b; }
        .pv-chips-row { display: flex; gap: 8px; margin-top: 15px; flex-wrap: wrap; }
        .pv-chip { background: #f1f5f9; padding: 4px 10px; border-radius: 6px; font-size: 12px; color: #475569; font-weight: 500; }
        .pv-hotel-layout, .pv-media-layout { display: grid; grid-template-columns: 200px 1fr; gap: 20px; }
        .pv-hotel-photo-slot, .pv-media-photo-slot { height: 180px; border-radius: 8px; overflow: hidden; background: #f1f5f9; }
        .pv-hotel-photo-ph, .pv-media-photo-ph { height: 100%; display: flex; align-items: center; justify-content: center; font-size: 32px; color: #cbd5e1; }
        .pv-action-btn { background: #0e7aad; color: white; border-radius: 20px; padding: 6px 15px; font-size: 12px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; }
        .pv-sep { display: flex; align-items: center; gap: 10px; margin: 20px 0; }
        .pvs-line { flex: 1; height: 1px; background: #e2e8f0; }
        .pvs-label { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; }
        .pv-titulo { margin-bottom: 20px; }
        .pvt-text { font-size: 20px; font-weight: 800; color: #0f172a; }
        .pvt-sub { font-size: 14px; color: #64748b; margin-top: 4px; }
        .pv-texto { color: #475569; line-height: 1.6; font-size: 14px; margin-bottom: 20px; }
        .pv-imagen { margin-bottom: 20px; border-radius: 12px; overflow: hidden; }
        .pv-imagen img { width: 100%; display: block; }
        .pv-caption { padding: 10px; text-align: center; font-size: 12px; color: #64748b; background: #f8fafc; }
        .pv-caja { background: #f8fafc; border-left: 4px solid #0e7aad; padding: 15px; border-radius: 0 8px 8px 0; display: flex; gap: 12px; margin-bottom: 20px; }
        .pv-carousel { height: 100%; position: relative; }
        .pv-carousel-slide img { width: 100%; height: 100%; object-fit: cover; }
        
        @media (max-width: 768px) {
            .pv-hotel-layout, .pv-media-layout { grid-template-columns: 1fr; }
            .pv-layout { grid-template-columns: 1fr; }
            .pv-nav { display: none; }
        }
    </style>

    <div class="pv-portada-section" style="max-width: 900px; margin: 0 auto 40px;">
        ${hasPortada ? `
            <div class="pv-portada-card" style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); border: 1px solid #e2e8f0">
                ${portadaPhotoUrl ? `<img src="${portadaPhotoUrl}" style="width:100%; height:300px; object-fit:cover">` : `<div style="height:200px; background:linear-gradient(135deg, #1e293b, #0f172a); display:flex; align-items:center; justify-content:center; color:white; font-size:40px"><i class="fa-solid fa-earth-americas"></i></div>`}
                <div style="padding:25px">
                    <h1 style="font-size:28px; font-weight:800; color:#0f172a; margin:0 0 15px">${title}</h1>
                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap:20px; border-top:1px solid #f1f5f9; padding-top:20px">
                        <div>
                            <div style="font-size:10px; font-weight:800; color:#94a3b8; text-transform:uppercase; margin-bottom:5px">Fechas</div>
                            <div style="font-size:14px; font-weight:700; color:#1e293b">${fechaInicio && fechaFin ? fmtDateShort(fechaInicio) + ' — ' + fmtDateShort(fechaFin) : 'Por definir'}</div>
                        </div>
                        <div>
                            <div style="font-size:10px; font-weight:800; color:#94a3b8; text-transform:uppercase; margin-bottom:5px">Viajeros</div>
                            <div style="font-size:14px; font-weight:700; color:#1e293b">${totalViajeros || '—'} pax</div>
                        </div>
                        <div>
                            <div style="font-size:10px; font-weight:800; color:#94a3b8; text-transform:uppercase; margin-bottom:5px">Total</div>
                            <div style="font-size:16px; font-weight:800; color:#0e7aad">${precio ? moneda + ' $' + Number(precio).toLocaleString('es') : '—'}</div>
                        </div>
                    </div>
                </div>
            </div>
            ${portadaItems && portadaItems.length ? `<div style="margin-top:20px">${renderPreviewItems(portadaItems)}</div>` : ''}
        ` : ''}
    </div>

    <div class="pv-layout" style="display: grid; grid-template-columns: 240px 1fr; gap: 40px; max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <aside class="pv-nav" style="position: sticky; top: 100px; height: fit-content; background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 10px 0; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05);">
            <div style="padding: 10px 15px 5px; font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Itinerario</div>
            ${sidebarNav}
        </aside>
        <main class="pv-content">
            ${daysHTML}
            
            ${hasCierre ? `
                <div class="pv-cierre" style="background: #0f172a; border-radius: 16px; padding: 40px; text-align: center; color: white; margin-top: 40px">
                    <div style="font-size: 40px; margin-bottom: 20px">✈️</div>
                    ${showDefaultCierre ? `
                        <div style="background: rgba(14, 165, 233, 0.2); border: 1px solid #0ea5e9; border-radius: 20px; padding: 4px 15px; font-size: 11px; font-weight: 700; display: inline-block; color: #38bdf8; margin-bottom: 15px">¡ITINERARIO COMPLETO!</div>
                        <h2 style="font-size: 24px; font-weight: 800; margin-bottom: 10px">${title}</h2>
                        <p style="color: #94a3b8; font-size: 14px">Este itinerario fue creado por <b>${window.viantrypUserName || 'Viantryp'}</b>.<br>¡Que tengas un viaje extraordinario!</p>
                    ` : ''}
                    ${cierreItems && cierreItems.length ? `<div style="margin-top:20px; text-align: left">${renderPreviewItems(cierreItems)}</div>` : ''}
                </div>
            ` : ''}
        </main>
    </div>
    `;

  document.getElementById('preview-root').innerHTML = html;

  // Active Link Logic
  window.addEventListener('scroll', () => {
    const sections = document.querySelectorAll('.pv-day');
    const scrollPos = window.scrollY + 150;
    sections.forEach(s => {
      if (scrollPos >= s.offsetTop && scrollPos < s.offsetTop + s.offsetHeight) {
        document.querySelectorAll('.pvnav-link').forEach(l => l.style.borderLeftColor = 'transparent');
        const activeLink = document.querySelector(`.pvnav-link[href="#${s.id}"]`);
        if (activeLink) activeLink.style.borderLeftColor = '#0e7aad';
      }
    });
  });
}
