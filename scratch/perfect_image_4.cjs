const sharp = require('sharp');
const path = require('path');

const imgDir = path.join(__dirname, '../public/images/onboarding');

async function processImage4() {
  console.log('Rendering 100% borderless title text "Todo lo que necesitas en un solo lugar"...');

  const origPath = path.join(imgDir, '4_orig.jpg');
  const meta = await sharp(origPath).metadata();
  const width = meta.width;   // 576
  const height = meta.height; // 1024

  // 1. Phone section from 4_orig.jpg (y: 65 to 685 -> top elevated phone)
  const phoneSection = await sharp(origPath)
    .extract({ left: 0, top: 65, width: width, height: 620 })
    .toBuffer();

  // 2. Pure background gradient from clean bottom area of 4_orig.jpg (y: 920 to 1020)
  const pureBg = await sharp(origPath)
    .extract({ left: 0, top: 920, width: width, height: 100 })
    .resize(width, 424, { fit: 'fill' })
    .toBuffer();

  // 3. Base image: Phone placed right near top (top: 0), clean bottom background starting at y: 600
  const baseSeamless = await sharp({
    create: {
      width: width,
      height: height,
      channels: 3,
      background: { r: 238, g: 246, b: 248 }
    }
  })
  .composite([
    { input: phoneSection, left: 0, top: 0 },
    { input: pureBg, left: 0, top: 600 }
  ])
  .jpeg({ quality: 96 })
  .toBuffer();

  // 4. Extract ONLY main title "Todo lo que necesitas / en un solo lugar" (x: 46 to 506 -> width 460px, y: 705 to 830 -> height 125px)
  const textRaw = await sharp(origPath)
    .extract({ left: 46, top: 705, width: 460, height: 125 })
    .toBuffer();

  // Scale down title text to 430px width for generous symmetric margins
  const textScaled = await sharp(textRaw)
    .resize(430, Math.round(125 * (430/460)), { fit: 'contain' })
    .toBuffer();

  // Threshold background pixels (light cyan/white) to pure white (255) for 100% transparent multiply blend
  const { data, info } = await sharp(textScaled).raw().toBuffer({ resolveWithObject: true });
  for (let i = 0; i < data.length; i += info.channels) {
    const r = data[i], g = data[i+1], b = data[i+2];
    if (r > 150 && g > 185 && b > 195) {
      data[i] = 255;
      data[i+1] = 255;
      data[i+2] = 255;
    }
  }

  const cleanedTitle = await sharp(data, {
    raw: { width: info.width, height: info.height, channels: info.channels }
  })
  .jpeg({ quality: 96 })
  .toBuffer();

  const textLeft = Math.round((width - 430) / 2); // 73px margin on both sides!

  // Composite ONLY title text centered at y: 655px
  const finalImage = await sharp(baseSeamless)
    .composite([
      { input: cleanedTitle, left: textLeft, top: 655, blend: 'multiply' }
    ])
    .jpeg({ quality: 96 })
    .toBuffer();

  await sharp(finalImage).toFile(path.join(imgDir, '4.jpg'));
  console.log('Successfully generated Image 4 with full letter s and borderless title text!');
}

processImage4().catch(console.error);
