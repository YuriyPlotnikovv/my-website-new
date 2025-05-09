const fs = require('fs');
const path = require('path');

const filePath = path.join(process.env.GITHUB_WORKSPACE, 'core', 'apiKeys.php');
const ownerId = process.env.OWNER_ID;
const albumId = process.env.ALBUM_ID;
const vkApiKey = process.env.VK_API_KEY;
const gitHubApiKey = process.env.GIT_API_KEY;

if (!ownerId || !albumId || !vkApiKey || !gitHubApiKey) {
  console.error('API keys are not set.');
  process.exit(1);
}

const content = `<?php\n\n$ownerId = '${ownerId}';\n$albumId = '${albumId}';\n$vkApiKey = '${vkApiKey}';\n\n$gitHubApiKey = '${gitHubApiKey}';\n`;

fs.writeFileSync(filePath, content, 'utf8');
console.log('API keys have been generated.');
