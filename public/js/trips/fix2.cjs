const fs = require('fs');

// Read pro-editor.js
const editorJs = fs.readFileSync('c:/laragon/www/viantryp/public/js/trips/pro-editor.js', 'utf8');

const buildPreviewStr = 'function buildPreviewHTML(data) {';
const startIndex = editorJs.indexOf(buildPreviewStr);
const str2 = 'return `<!DOCTYPE html>';
const endIndex = editorJs.indexOf(str2, startIndex);

const logicSection = editorJs.substring(startIndex + buildPreviewStr.length, endIndex).trim();

const htmlStart = editorJs.indexOf(str2, endIndex);
const htmlEnd = editorJs.indexOf('</script>\n`', htmlStart);
const template = editorJs.substring(htmlStart + 24, htmlEnd);

const styleStart = template.indexOf('<style>');
const styleEnd = template.indexOf('</style>') + 8;
const styleSection = template.substring(styleStart, styleEnd);

const bodyStart = template.indexOf('<body>') + 6;
const bodyEnd = template.indexOf('<script>');
let bodySection = template.substring(bodyStart, bodyEnd).trim();

bodySection = bodySection.replace(
    '<div class="pv-topbar">\n  <div class="pv-logo">✦ itinerai</div>\n  <div class="pv-topbar-title">${title}</div>\n  <button class="pv-back-btn" onclick="window.close()"><i class="fa-solid fa-times"></i> Cerrar</button>\n</div>',
    '<div class="pv-topbar">\n  <div class="pv-logo" style="display:flex; align-items:center; gap:20px; background:none; -webkit-text-fill-color: initial;">\n      <img src="/images/logo-viantryp.png" alt="Viantryp" style="height:24px">\n      <img src="/images/LOGO GPS.png" alt="GPS" style="height:35px; object-fit:contain">\n  </div>\n  <div class="pv-topbar-title">${title}</div>\n</div>'
);

const htmlTemplateString = '`\\n' + styleSection.replace(/`/g, '\\`').replace(/\\/g, '\\\\').replace(/\$/g, '\\$') + '\\n' + bodySection.replace(/`/g, '\\`').replace(/\\/g, '\\\\').replace(/\$/g, '\\$') + '\\n`';

const newViewerJs = `// PRO Viewer Engine (Public Enlace)
document.addEventListener('DOMContentLoaded', () => {
  if (window.proState) {
    renderProViewer(window.proState);
  } else {
    document.body.innerHTML = '<div style="padding:40px; text-align:center; color:#64748b">No se encontraron datos para este itinerario.</div>';
  }
});

function renderProViewer(data) {
  ${logicSection}

  const html = \`${styleSection}
${bodySection}\`;

  document.body.innerHTML = html;

  // Wait for DOM to update
  setTimeout(() => {
    const links = document.querySelectorAll('#preview-root .pvnav-link, .pvnav-link');
    const sections = document.querySelectorAll('#preview-root .pv-day, .pv-day');

    // Smooth scroll with offset for sticky topbar
    links.forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        const target = document.querySelector(link.getAttribute('href'));
        if (!target) return;
        const topbar = document.querySelector('.pv-topbar');
        const offset = Math.min(72, topbar ? topbar.offsetHeight + 20 : 72);
        const top = target.getBoundingClientRect().top + window.scrollY - offset;
        window.scrollTo({ top, behavior: 'smooth' });
      });
    });

    const obs = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          const id = e.target.id;
          links.forEach(l => {
            l.classList.toggle('active', l.getAttribute('href') === '#' + id);
          });
        }
      });
    }, { threshold: .25, rootMargin: '-60px 0px -40% 0px' });
    
    sections.forEach(s => obs.observe(s));
  }, 100);
}
`;

fs.writeFileSync('c:/laragon/www/viantryp/public/js/trips/pro-viewer.js', newViewerJs);
console.log('Fixed html assignment length:', newViewerJs.length);
