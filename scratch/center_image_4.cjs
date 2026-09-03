const sharp = require('sharp');
const path = require('path');

const imgDir = path.join(__dirname, '../public/images/onboarding');

async function processImage4() {
  console.log('Fixing letter s cutoff and lowering text position for Image 4...');

  const origPath = path.join(imgDir, '4_orig.jpg');
  const meta = await sharp(origPath).metadata();
  const canvasWidth = meta.width; // 576
  const canvasHeight = meta.height; // 1024

  // 1. Phone section from 4_orig.jpg (top: 0 to 680) - KEEP POSITION EXACTLY AS IS
  const phoneSection = await sharp(origPath)
    .extract({ left: 0, top: 0, width: canvasWidth, height: 680 })
    .toBuffer();

  // 2. Extract full title text width (x: 35 to 540 -> width 505px to include letter 's' completely!)
  const textRaw = await sharp(origPath)
    .extract({ left: 35, top: 710, width: 505, height: 180 })
    .toBuffer();

  // Scale down text proportionally to width 440px so letter 's' is crisp and margins are 68px on both sides
  const textScaled = await sharp(textRaw)
    .resize(440, Math.round(180 * (440/505)), { fit: 'contain' })
    .toBuffer();

  // Clean background around text
  const { data, info } = await sharp(textScaled).raw().toBuffer({ resolveWithObject: true });
  for (let i = 0; i < data.length; i += info.channels) {
    const r = data[i], g = data[i+1], b = data[i+2];
    if (r > 165 && g > 195 && b > 205) {
      data[i] = 255;
      data[i+1] = 255;
      data[i+2] = 255;
    }
  }

  const cleanedText = await sharp(data, {
    raw: { width: info.width, height: info.height, channels: info.channels }
  }).png().toBuffer();

  const scaledMeta = await sharp(cleanedText).metadata();
  const textLeft = Math.round((canvasWidth - scaledMeta.width) / 2); // Perfectly centered horizontally!

  // 3. Clean soft white bottom background patch for lower area
  const bottomBg = await sharp({
    create: {
      width: canvasWidth,
      height: 380,
      channels: 3,
      background: { r: 238, g: 246, b: 248 }
    }
  }).jpeg().toBuffer();

  // Composite:
  // - Phone section at top: -60px (phone bottom edge at 620px)
  // - Clean bottom background patch at top: 620px
  // - Lowered & centered text at top: 660px (giving a clean gap below the phone graphic!)
  const result = await sharp({
    create: {
      width: canvasWidth,
      height: canvasHeight,
      channels: 3,
      background: { r: 238, g: 246, b: 248 }
    }
  })
  .composite([
    { input: bottomBg, left: 0, top: 620 },
    { input: phoneSection, left: 0, top: -60 },
    { input: cleanedText, left: textLeft, top: 660, blend: 'multiply' }
  ])
  .jpeg({ quality: 96 })
  .toBuffer();

  await sharp(result).toFile(path.join(imgDir, '4.jpg'));
  console.log('Successfully updated 4.jpg with full letter s and lowered text position!');
}

processImage4().catch(console.error);
