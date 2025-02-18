const fs = require('fs');
const path = require('path');

const ownerId = process.env.OWNER_ID;
const albumId = process.env.ALBUM_ID;
const accessToken = process.env.ACCESS_TOKEN;

if (!ownerId || !albumId || !accessToken) {
  console.error('API keys are not set.');
  process.exit(1);
}

const content = `<?php\n\n$ownerId = '${ownerId}';\n$albumId = '${albumId}';\n$accessToken = '${accessToken}';\n`;

const filePath = path.join(__dirname, 'core', 'apiKeys.php');

fs.writeFileSync(filePath, content, 'utf8');
console.log('API keys have been generated.');
