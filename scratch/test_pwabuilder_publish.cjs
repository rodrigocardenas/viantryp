const fs = require('fs');

async function testPWABuilderPublish() {
  console.log('Probando https://pwabuilder.com/api/publish/android...');
  
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
    startUrl: '/trips?app=1'
  };

  try {
    const res = await fetch('https://pwabuilder.com/api/publish/android', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });

    console.log('Status:', res.status);
    console.log('Headers:', res.headers.get('content-type'));
    if (res.ok) {
      const buf = Buffer.from(await res.arrayBuffer());
      fs.writeFileSync('scratch/Viantryp_Android_Package.zip', buf);
      console.log('¡Éxito! Paquete guardado en scratch/Viantryp_Android_Package.zip (Tamaño:', buf.length, 'bytes)');
    } else {
      const text = await res.text();
      console.log('Error payload:', text.substring(0, 500));
    }
  } catch (err) {
    console.error('Error:', err);
  }
}

testPWABuilderPublish();
