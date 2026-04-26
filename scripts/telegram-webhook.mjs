#!/usr/bin/env node

import { existsSync, readFileSync } from 'fs';
import { dirname, resolve } from 'path';
import { fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const envPath = resolve(__dirname, '../.env');

function loadEnv(filePath) {
  if (!existsSync(filePath)) return {};
  const env = {};
  const lines = readFileSync(filePath, 'utf8').split('\n');
  for (const line of lines) {
    const trimmed = line.trim();
    if (!trimmed || trimmed.startsWith('#')) continue;
    const [key, ...rest] = trimmed.split('=');
    if (key) env[key.trim()] = rest.join('=').trim().replace(/^["']|["']$/g, '');
  }
  return env;
}

function usage() {
  console.log('Usage: node scripts/telegram-webhook.mjs <set|info|delete>');
}

function requireValue(name, value) {
  if (!value) {
    throw new Error(`Missing required value: ${name}`);
  }
}

async function callTelegramApi(token, method, params = {}) {
  const url = new URL(`https://api.telegram.org/bot${token}/${method}`);
  Object.entries(params).forEach(([key, value]) => {
    if (value !== undefined && value !== null && value !== '') {
      url.searchParams.set(key, String(value));
    }
  });

  const response = await fetch(url);
  const payload = await response.json();
  if (!response.ok || payload.ok !== true) {
    throw new Error(`Telegram API ${method} failed: ${JSON.stringify(payload)}`);
  }
  return payload.result;
}

async function main() {
  const action = process.argv[2];
  if (!action || !['set', 'info', 'delete'].includes(action)) {
    usage();
    process.exit(1);
  }

  const fileEnv = loadEnv(envPath);
  const env = { ...fileEnv, ...process.env };
  const token = env.TELEGRAM_BOT_TOKEN;
  const baseUrl = env.PUBLIC_BASE_URL;
  const secretToken = env.TELEGRAM_WEBHOOK_SECRET || '';

  requireValue('TELEGRAM_BOT_TOKEN', token);

  if (action === 'set') {
    requireValue('PUBLIC_BASE_URL', baseUrl);
    const webhookUrl = new URL('/telegram-webhook.php', baseUrl).toString();
    const result = await callTelegramApi(token, 'setWebhook', {
      url: webhookUrl,
      secret_token: secretToken || undefined,
      drop_pending_updates: 'false',
    });

    console.log('Webhook registered successfully.');
    console.log(JSON.stringify({ webhookUrl, result }, null, 2));
    return;
  }

  if (action === 'info') {
    const result = await callTelegramApi(token, 'getWebhookInfo');
    console.log('Webhook info:');
    console.log(JSON.stringify(result, null, 2));
    return;
  }

  if (action === 'delete') {
    const result = await callTelegramApi(token, 'deleteWebhook', {
      drop_pending_updates: 'false',
    });
    console.log('Webhook deleted successfully.');
    console.log(JSON.stringify(result, null, 2));
  }
}

main().catch((error) => {
  console.error(error.message);
  process.exit(1);
});
