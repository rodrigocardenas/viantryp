const fs = require('fs');

// 1. Read pro-editor.js
const editorJs = fs.readFileSync('c:/laragon/www/viantryp/public/js/trips/pro-editor.js', 'utf8');

// 2. Extract buildPreviewHTML body
const startMarker = 'function buildPreviewHTML(data) {';
const startIndex = editorJs.indexOf(startMarker);
const endMarker = 'return `<!DOCTYPE html>';
const endIndex = editorJs.indexOf(endMarker, startIndex);

const logicSection = editorJs.substring(startIndex + startMarker.length, endIndex).trim();

// 3. Extract the CSS and HTML template
const htmlStart = editorJs.indexOf('return `<!DOCTYPE html>', endIndex) + 23;
const htmlEnd = editorJs.indexOf('</script>\n`', htmlStart);
let fullHtml = editorJs.substring(htmlStart, htmlEnd);

const styleStart = fullHtml.indexOf('<style>');
const styleEnd = fullHtml.indexOf('</style>') + 8;
const styleSection = fullHtml.substring(styleStart, styleEnd);

const bodyStart = fullHtml.indexOf('<body>') + 6;
const bodyEnd = fullHtml.indexOf('<script>');
let bodySection = fullHtml.substring(bodyStart, bodyEnd).trim();

// 4. Modify the topbar to include Viantryp and GPS logos, and remove Cerrar button
bodySection = bodySection.replace(
  '<div class="pv-topbar">\n  <div class="pv-logo">✦ itinerai</div>\n  <div class="pv-topbar-title">${title}</div>\n  <button class="pv-back-btn" onclick="window.close()"><i class="fa-solid fa-times"></i> Cerrar</button>\n</div>',
  '<div class="pv-topbar">\n  <div class="pv-logo" style="display:flex; align-items:center; gap:20px; background:none; -webkit-text-fill-color: initial;">\n      <img src="/images/logo-viantryp.png" alt="Viantryp" style="height:24px">\n      <img src="/images/LOGO GPS.png" alt="GPS" style="height:35px; object-fit:contain">\n  </div>\n  <div class="pv-topbar-title">${title}</div>\n</div>'
);

// We must construct the JS file logic so that it evaluates htmlTemplateString at runtime inside renderProViewer.
// By using a template literal string in JS that contains the exact contents of styleSection and bodySection,
// the template interpolations (like \${title} inside bodySection) will evaluate correctly within renderProViewer scope!

const htmlTemplateString = '`\\n' + styleSection + '\\n' + bodySection + '\\n`';

const newProViewerJs = `// PRO Viewer Engine (Public Enlace)
document.addEventListener('DOMContentLoaded', () => {
  if (window.proState) {
    renderProViewer(window.proState);
  } else {
    document.body.innerHTML = '<div style="padding:40px; text-align:center; color:#64748b">No se encontraron datos para este itinerario.</div>';
  }
});

function renderProViewer(data) {
  ${logicSection}

  const html = ${htmlTemplateString};

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

fs.writeFileSync('c:/laragon/www/viantryp/public/js/trips/pro-viewer.js', newProViewerJs);

console.log('Regenerated pro-viewer.js successfully.');
