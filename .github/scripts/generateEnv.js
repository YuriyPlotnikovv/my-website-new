const fs = require('fs');
const path = require('path');

const filePath = path.join(process.env.GITHUB_WORKSPACE, '.env');

const secrets = {
  OWNER_ID: process.env.OWNER_ID,
  ALBUM_ID: process.env.ALBUM_ID,
  VK_API_KEY: process.env.VK_API_KEY,
  GIT_API_KEY: process.env.GIT_API_KEY,
  CAPTCHA_PUBLIC_API_KEY: process.env.CAPTCHA_PUBLIC_API_KEY,
  CAPTCHA_PRIVATE_API_KEY: process.env.CAPTCHA_PRIVATE_API_KEY,
  SMTP_PASSWORD: process.env.SMTP_PASSWORD,
};

for (const [name, value] of Object.entries(secrets)) {
  if (!value) {
    console.error(`Secret ${name} is not set.`);
    process.exit(1);
  }
}

const quote = (name, value) => {
  if (String(value).includes("'")) {
    console.error(`Value of ${name} contains a single quote, which cannot be encoded in .env.`);
    process.exit(1);
  }

  return `'${value}'`;
};

const content = [
  '# API-ключи внешних сервисов',
  `OWNER_ID=${quote('OWNER_ID', secrets.OWNER_ID)}`,
  `ALBUM_ID=${quote('ALBUM_ID', secrets.ALBUM_ID)}`,
  `VK_API_KEY=${quote('VK_API_KEY', secrets.VK_API_KEY)}`,
  `GIT_API_KEY=${quote('GIT_API_KEY', secrets.GIT_API_KEY)}`,
  `CAPTCHA_PUBLIC_API_KEY=${quote('CAPTCHA_PUBLIC_API_KEY', secrets.CAPTCHA_PUBLIC_API_KEY)}`,
  `CAPTCHA_PRIVATE_API_KEY=${quote('CAPTCHA_PRIVATE_API_KEY', secrets.CAPTCHA_PRIVATE_API_KEY)}`,
  '',
  '# SMTP-сервер отправки писем (Beget)',
  `SMTP_HOST=${quote('SMTP_HOST', 'smtp.beget.com')}`,
  `SMTP_PORT=${quote('SMTP_PORT', '465')}`,
  `SMTP_USER=${quote('SMTP_USER', 'noreply@yuriyplotnikovv.ru')}`,
  `SMTP_PASSWORD=${quote('SMTP_PASSWORD', secrets.SMTP_PASSWORD)}`,
  '',
  '# Параметры письма с формы обратной связи',
  `MAIL_FROM_EMAIL=${quote('MAIL_FROM_EMAIL', 'noreply@yuriyplotnikovv.ru')}`,
  `MAIL_FROM_NAME=${quote('MAIL_FROM_NAME', 'Yuriyplotnikovv.ru')}`,
  `MAIL_TO=${quote('MAIL_TO', 'yuriy.plotnikovv@yandex.ru')}`,
  '',
].join('\n');

fs.writeFileSync(filePath, content, 'utf8');
console.log('.env has been generated.');
