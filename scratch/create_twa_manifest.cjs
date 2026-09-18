const fs = require('fs');
const path = require('path');

const twaManifest = {
  "packageId": "com.viantryp.app",
  "name": "Viantryp",
  "launcherName": "Viantryp",
  "display": "standalone",
  "themeColor": "#0D2B3E",
  "navigationColor": "#0D2B3E",
  "navigationColorDark": "#0D2B3E",
  "navigationBarColor": "#0D2B3E",
  "navigationBarColorDark": "#0D2B3E",
  "backgroundColor": "#0D2B3E",
  "enableNotifications": true,
  "startUrl": "/?app=1",
  "iconUrl": "https://www.viantryp.com/icons/icon-512x512.png",
  "maskableIconUrl": "https://www.viantryp.com/icons/icon-512x512.png",
  "appVersionName": "1.0.0",
  "appVersionCode": 1,
  "signingKey": {
    "path": "c:\\laragon\\www\\viantryp\\viantryp-release-key.keystore",
    "alias": "viantryp-key"
  },
  "generatorApp": "bubblewrap-cli",
  "webManifestUrl": "https://www.viantryp.com/manifest.json",
  "fallbackType": "customtabs",
  "features": {
    "locationDelegation": {
      "enabled": true
    }
  },
  "alphaDependencies": {
    "enabled": false
  },
  "enableSiteSettingsShortcut": true,
  "isChromeOSOnly": false,
  "isMetaQuest": false,
  "host": "www.viantryp.com"
};

const buildDir = path.join(__dirname, '..', 'android-twa');
if (!fs.existsSync(buildDir)) {
  fs.mkdirSync(buildDir, { recursive: true });
}

fs.writeFileSync(path.join(buildDir, 'twa-manifest.json'), JSON.stringify(twaManifest, null, 2));
console.log('twa-manifest.json creado con éxito en android-twa/twa-manifest.json');
