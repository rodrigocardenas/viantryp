const sharp = require('sharp');
const path = require('path');

const srcIcon = 'C:\\Users\\jcama\\.gemini\\antigravity-ide\\brain\\9f69dac9-7bd4-42ac-a586-a450f9954f56\\.user_uploaded\\media_1788471458162.png';
const publicDir = 'c:\\laragon\\www\\viantryp\\public';

async function generateIcons() {
  console.log('Generating updated Viantryp app icons...');

  // Favicon
  await sharp(srcIcon).resize(512, 512).png().toFile(path.join(publicDir, 'favicon.png'));
  await sharp(srcIcon).resize(512, 512).png().toFile(path.join(publicDir, 'images/logo-icon.png'));

  // PWA icons
  const sizes = [72, 96, 128, 144, 152, 192, 384, 512];
  for (const size of sizes) {
    await sharp(srcIcon)
      .resize(size, size)
      .png()
      .toFile(path.join(publicDir, `icons/icon-${size}x${size}.png`));
  }

  console.log('Successfully updated all app icons and favicons!');
}

generateIcons().catch(console.error);
