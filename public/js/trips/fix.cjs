const fs = require('fs');

// Read pro-editor.js
const editorJs = fs.readFileSync('c:/laragon/www/viantryp/public/js/trips/pro-editor.js', 'utf8');

// Extract HTML template block
const htmlStart = editorJs.indexOf('return `<!DOCTYPE html>');
let template = editorJs.substring(htmlStart + 24, editorJs.indexOf('</script>\n`', htmlStart));

const styleStart = template.indexOf('<style>');
const styleEnd = template.indexOf('</style>') + 8;
const styleSection = template.substring(styleStart, styleEnd);

const bodyStart = template.indexOf('<body>') + 6;
const bodyEnd = template.indexOf('<script>');
let bodySection = template.substring(bodyStart, bodyEnd).trim();

// Replace topbar
bodySection = bodySection.replace(
    '<div class="pv-topbar">\n  <div class="pv-logo">✦ itinerai</div>\n  <div class="pv-topbar-title">${title}</div>\n  <button class="pv-back-btn" onclick="window.close()"><i class="fa-solid fa-times"></i> Cerrar</button>\n</div>',
    '<div class="pv-topbar">\n  <div class="pv-logo" style="display:flex; align-items:center; gap:20px; background:none; -webkit-text-fill-color: initial;">\n      <img src="/images/logo-viantryp.png" alt="Viantryp" style="height:24px">\n      <img src="/images/LOGO GPS.png" alt="GPS" style="height:35px; object-fit:contain">\n  </div>\n  <div class="pv-topbar-title">${title}</div>\n</div>'
);

// Read pro-viewer.js
const viewerJs = fs.readFileSync('c:/laragon/www/viantryp/public/js/trips/pro-viewer.js', 'utf8');

// Find placeholder
const htmlAssignmentStart = viewerJs.indexOf('const html = `');
const htmlAssignmentEnd = viewerJs.indexOf('`;\n\n  document.body.innerHTML', htmlAssignmentStart) + 2;

// Reconstruct viewer JS
const newHtmlAssignment = 'const html = `\\n' + styleSection + '\\n' + bodySection + '\\n`;';

const newViewerJs = viewerJs.substring(0, htmlAssignmentStart) + newHtmlAssignment + viewerJs.substring(htmlAssignmentEnd);

fs.writeFileSync('c:/laragon/www/viantryp/public/js/trips/pro-viewer.js', newViewerJs);
console.log('Fixed html assignment');
