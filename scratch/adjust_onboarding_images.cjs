const sharp = require('sharp');
const path = require('path');
const fs = require('fs');

const imgDir = path.join(__dirname, '../public/images/onboarding');

async function processImages() {
  console.log('Processing Onboarding Images (Seamless Composition)...');

  // --- PROCESS 2.jpg ---
  // Read from 2_orig.jpg - Shift content right by 48px to give comfortable, centered left margin
  const img2 = sharp(path.join(imgDir, '2_orig.jpg'));
  const meta2 = await img2.metadata();

  const leftBg2 = await sharp(path.join(imgDir, '2_orig.jpg'))
    .extract({ left: 0, top: 0, width: 15, height: meta2.height })
    .resize(48, meta2.height, { fit: 'fill' })
    .toBuffer();

  const body2 = await sharp(path.join(imgDir, '2_orig.jpg'))
    .extract({ left: 0, top: 0, width: 528, height: meta2.height })
    .toBuffer();

  await sharp({
    create: {
      width: meta2.width,
      height: meta2.height,
      channels: 3,
      background: { r: 225, g: 242, b: 246 }
    }
  })
  .composite([
    { input: leftBg2, left: 0, top: 0 },
    { input: body2, left: 48, top: 0 }
  ])
  .jpeg({ quality: 96 })
  .toFile(path.join(imgDir, '2.jpg'));

  console.log('Updated 2.jpg');

  // --- PROCESS 3.jpg ---
  // Read from 3_orig.jpg - Shift content right by 48px to give comfortable, centered left margin
  const img3 = sharp(path.join(imgDir, '3_orig.jpg'));
  const meta3 = await img3.metadata();

  const leftBg3 = await sharp(path.join(imgDir, '3_orig.jpg'))
    .extract({ left: 0, top: 0, width: 15, height: meta3.height })
    .resize(48, meta3.height, { fit: 'fill' })
    .toBuffer();

  const body3 = await sharp(path.join(imgDir, '3_orig.jpg'))
    .extract({ left: 0, top: 0, width: 528, height: meta3.height })
    .toBuffer();

  await sharp({
    create: {
      width: meta3.width,
      height: meta3.height,
      channels: 3,
      background: { r: 233, g: 244, b: 247 }
    }
  })
  .composite([
    { input: leftBg3, left: 0, top: 0 },
    { input: body3, left: 48, top: 0 }
  ])
  .jpeg({ quality: 96 })
  .toFile(path.join(imgDir, '3.jpg'));

  console.log('Updated 3.jpg');

  // --- PROCESS 4.jpg ---
  // Read from 4_orig.jpg - Shift content right by 48px to align text & phone away from left wall cleanly
  const img4 = sharp(path.join(imgDir, '4_orig.jpg'));
  const meta4 = await img4.metadata();

  const leftBg4 = await sharp(path.join(imgDir, '4_orig.jpg'))
    .extract({ left: 0, top: 0, width: 15, height: meta4.height })
    .resize(48, meta4.height, { fit: 'fill' })
    .toBuffer();

  const body4 = await sharp(path.join(imgDir, '4_orig.jpg'))
    .extract({ left: 0, top: 0, width: 528, height: meta4.height })
    .toBuffer();

  await sharp({
    create: {
      width: meta4.width,
      height: meta4.height,
      channels: 3,
      background: { r: 27, g: 123, b: 140 }
    }
  })
  .composite([
    { input: leftBg4, left: 0, top: 0 },
    { input: body4, left: 48, top: 0 }
  ])
  .jpeg({ quality: 96 })
  .toFile(path.join(imgDir, '4.jpg'));

  console.log('Updated 4.jpg');
}

processImages().catch(console.error);
