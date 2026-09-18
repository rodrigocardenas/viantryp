const https = require('https');

const endpoints = [
  'https://pwabuilder-apiv2.azurewebsites.net/api/PWABuilder/android',
  'https://pwabuilder-generator.azurewebsites.net/api/publish/android',
  'https://pwabuilder-cloudbuilder.azurewebsites.net/api/publish/android',
  'https://pwabuilder.com/api/publish/android'
];

async function checkEndpoints() {
  for (const ep of endpoints) {
    try {
      const res = await fetch(ep, { method: 'OPTIONS' });
      console.log(`${ep} -> status: ${res.status}`);
    } catch (e) {
      console.log(`${ep} -> error: ${e.message}`);
    }
  }
}

checkEndpoints();
