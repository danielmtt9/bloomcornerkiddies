#!/usr/bin/env node

import { readFileSync } from 'fs';

const html = readFileSync('public_html/index.html', 'utf8');

const checks = [
  ['Alpine CDN', html.includes('cdn.jsdelivr.net/npm/alpinejs')],
  ['meta description', html.includes('meta name="description"')],
  ['OG tags', html.includes('property="og:title"') && html.includes('property="og:description"')],
  ['GA4 placeholder', html.includes("gtag('config', 'G-PLACEHOLDER')")],
  ['320px overflow guard', html.includes('overflow-x: clip') || html.includes('overflow-x: hidden')],
  ['shareable routing', html.includes("url.searchParams.set('product'") && html.includes("restoreProductFromUrl()")],
  ['hero trust section', html.includes('Trusted Kidswear on WhatsApp') && html.includes('Live status')],
  ['filter UI', html.includes('Browse the Catalogue') && html.includes('filter-btn')],
];

for (const [label, pass] of checks) {
  if (!pass) {
    console.error(`Missing storefront shell contract: ${label}`);
    process.exit(1);
  }
}

console.log('PASS: storefront shell smoke test');
