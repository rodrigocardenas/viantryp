const fs = require('fs');

async function testGenerate() {
  const endpoints = [
    'https://pwabuilder-android.azurewebsites.net/generateAppPackage',
    'https://pwabuilder-cloudbuilder.azurewebsites.net/generateAppPackage',
    'https://pwabuilder.com/api/android/generateAppPackage'
  ];

  const payload = {
    additionalTrustedOrigins: [],
    appVersion: "1.0.0.0",
    appVersionCode: 1,
    backgroundColor: "#0d2b3e",
    display: "standalone",
    enableNotifications: true,
    enableSiteSettingsShortcut: true,
    fallbackType: "customtabs",
    host: "https://www.viantryp.com",
    iconUrl: "https://www.viantryp.com/icons/icon-512x512.png",
    name: "Viantryp",
    packageId: "com.viantryp.app",
    shortName: "Viantryp",
    startUrl: "/trips?app=1",
    themeColor: "#0d2b3e"
  };

  for (const ep of endpoints) {
    console.log('Testing endpoint:', ep);
    try {
      const res = await fetch(ep, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      console.log('Status:', res.status);
      if (res.ok) {
        const buf = Buffer.from(await res.arrayBuffer());
        fs.writeFileSync('scratch/Viantryp_Android_Package.zip', buf);
        console.log('¡ÉXITO ROTUNDO! Guardado en scratch/Viantryp_Android_Package.zip (Tamaño:', buf.length, 'bytes)');
        break;
      } else {
        const text = await res.text();
        console.log('Response body:', text.substring(0, 300));
      }
    } catch (e) {
      console.log('Error:', e.message);
    }
  }
}

testGenerate();
