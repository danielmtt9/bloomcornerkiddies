#!/usr/bin/env node

import { readFileSync } from 'fs';

const html = readFileSync('public_html/index.html', 'utf8');
const api = readFileSync('public_html/includes/storefront.php', 'utf8');

const checks = [
  ['detail view fields', html.includes('Delivery Info') && html.includes('Open Size Guide') && html.includes('Watch on TikTok')],
  ['whatsapp handoff', html.includes('whatsappMessage(product') && html.includes('Order via WhatsApp')],
  ['notify me', html.includes('Notify Me When Back') && html.includes('notifyMeHref(activeProduct')],
  ['telegram personal link', html.includes('Order via Telegram') && html.includes("status.telegram_link || 'https://t.me/placeholder'")],
  ['save for later', html.includes('Save for Later') && html.includes('https://wa.me/?text=')],
  ['status consistency', html.includes('Seller Status') && html.includes('statusLabel(status.status)')],
  ['utm preservation', html.includes('utm_source') && html.includes('applyAttributionParams(url, preserved)')],
  ['ga4 placeholder retained', html.includes("gtag('config', 'G-PLACEHOLDER')")],
  ['delivery info payload', api.includes("'delivery_info' => get_config('delivery_info'")],
  ['gallery payload', api.includes("'image_urls' =>")],
];

for (const [label, pass] of checks) {
  if (!pass) {
    console.error(`Missing storefront handoff contract: ${label}`);
    process.exit(1);
  }
}

console.log('PASS: storefront handoff smoke test');
