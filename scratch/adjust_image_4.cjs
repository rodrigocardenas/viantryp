const sharp = require('sharp');
const path = require('path');

const imgDir = path.join(__dirname, '../public/images/onboarding');

async function processImage4() {
  console.log('Processing Image 4 with 100% seamless original composition shift...');

  const origPath = path.join(imgDir, '4_orig.jpg');
  const meta = await sharp(origPath).metadata();

  // Extract left column background color (15px wide)
  const leftBg = await sharp(origPath)
    .extract({ left: 0, top: 0, width: 15, height: meta.height })
    .resize(48, meta.height, { fit: 'fill' })
    .toBuffer();

  // Extract body of image 4 (width: 528px)
  const body = await sharp(origPath)
    .extract({ left: 0, top: 0, width: 528, height: meta.height })
    .toBuffer();

  // Composite: Left background padding + Body shifted right by 48px
  const result = await sharp({
    create: {
      width: meta.width,
      height: meta.height,
      channels: 3,
      background: { r: 27, g: 123, b: 140 }
    }
  })
  .composite([
    { input: leftBg, left: 0, top: 0 },
    { input: body, left: 48, top: 0 }
  ])
  .jpeg({ quality: 96 })
  .toBuffer();

  await sharp(result).toFile(path.join(imgDir, '4.jpg'));
  console.log('Successfully updated 4.jpg with 100% seamless original composition!');
}

processImage4().catch(console.error);
