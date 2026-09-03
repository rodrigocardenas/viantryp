const sharp = require('sharp');
const path = require('path');

const imgDir = path.join(__dirname, '../public/images/onboarding');

async function processImage4() {
  console.log('Recomposing Image 4 cleanly with full subtitle visibility...');

  const origPath = path.join(imgDir, '4_orig.jpg');
  const meta = await sharp(origPath).metadata();

  // 1. Shift body of 4_orig.jpg (y: 0 to 900) up by 65px and right by 48px
  const phoneAndText = await sharp(origPath)
    .extract({ left: 0, top: 0, width: 528, height: 900 })
    .toBuffer();

  const leftBg = await sharp(origPath)
    .extract({ left: 0, top: 0, width: 15, height: 900 })
    .resize(48, 900, { fit: 'fill' })
    .toBuffer();

  // Clean soft white bottom background for lower screen space
  const bottomWhiteBg = await sharp({
    create: {
      width: meta.width,
      height: 300,
      channels: 3,
      background: { r: 238, g: 246, b: 248 }
    }
  }).jpeg().toBuffer();

  const result = await sharp({
    create: {
      width: meta.width,
      height: meta.height,
      channels: 3,
      background: { r: 27, g: 123, b: 140 }
    }
  })
  .composite([
    { input: bottomWhiteBg, left: 0, top: 760 },
    { input: leftBg, left: 0, top: -65 },
    { input: phoneAndText, left: 48, top: -65 }
  ])
  .jpeg({ quality: 96 })
  .toBuffer();

  await sharp(result).toFile(path.join(imgDir, '4.jpg'));
  console.log('Successfully updated 4.jpg!');
}

processImage4().catch(console.error);
