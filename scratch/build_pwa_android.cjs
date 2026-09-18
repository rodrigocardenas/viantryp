const fs = require('fs');
const path = require('path');

async function generateAndroidPackage() {
  console.log('Iniciando solicitud a PWABuilder Cloud Service para Viantryp...');
  
  const payload = {
    appUrl: 'https://www.viantryp.com/trips?app=1',
    manifestUrl: 'https://www.viantryp.com/manifest.json',
    name: 'Viantryp',
    shortName: 'Viantryp',
    packageId: 'com.viantryp.app',
    launcherName: 'Viantryp',
    themeColor: '#0d2b3e',
    navigationColor: '#0d2b3e',
    backgroundColor: '#0d2b3e',
    enableNotifications: true,
    startUrl: '/trips?app=1',
    manifest: {
      name: 'Viantryp',
      short_name: 'Viantryp',
      start_url: '/trips?app=1',
      display: 'standalone',
      background_color: '#0d2b3e',
      theme_color: '#0d2b3e',
      icons: [
        { src: 'https://www.viantryp.com/icons/icon-512x512.png', sizes: '512x512', type: 'image/png' }
      ]
    }
  };

  try {
    const response = await fetch('https://pwabuilder-cloudbuilder.azurewebsites.net/api/publish/android', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });

    console.log('Status code:', response.status);
    if (response.ok) {
      const arrayBuffer = await response.arrayBuffer();
      const buffer = Buffer.from(arrayBuffer);
      const outputPath = path.join(__dirname, 'Viantryp_Android_Package.zip');
      fs.writeFileSync(outputPath, buffer);
      console.log('¡Paquete descargado con éxito en:', outputPath);
    } else {
      const text = await response.text();
      console.log('Respuesta PWABuilder:', text);
    }
  } catch (err) {
    console.error('Error al conectar con PWABuilder API:', err);
  }
}

generateAndroidPackage();
