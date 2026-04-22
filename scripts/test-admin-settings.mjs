#!/usr/bin/env node

import mysql from 'mysql2/promise';
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

function assert(condition, message) {
  if (!condition) throw new Error(message);
}

async function main() {
  const env = loadEnv(envPath);
  const connection = await mysql.createConnection({
    host: env.DB_HOST || 'localhost',
    user: env.DB_USER || '',
    password: env.DB_PASS || '',
    database: env.DB_NAME || '',
    port: parseInt(env.DB_PORT || '3306', 10),
  });

  try {
    await connection.beginTransaction();

    const settingsValues = [
      ['store_name', 'Bloom Corner Kiddies Test'],
      ['tagline', 'Placeholder tagline'],
      ['intro_text', 'Placeholder intro'],
      ['wa_number', '2349000000000'],
      ['telegram_link', 'https://t.me/placeholder-test'],
      ['delivery_info', 'Delivery test'],
      ['status_message', 'Back soon'],
      ['payment_info', 'Payment test'],
      ['seller_status', 'offline'],
      ['referral_discount_percent', null],
    ];

    for (const [key, value] of settingsValues) {
      await connection.execute(
        `INSERT INTO seller_config (\`key\`, value) VALUES (?, ?)
         ON DUPLICATE KEY UPDATE value = VALUES(value), updated_at = CURRENT_TIMESTAMP`,
        [key, value]
      );
    }

    const [configRows] = await connection.execute(
      `SELECT \`key\`, value FROM seller_config
       WHERE \`key\` IN ('store_name','telegram_link','seller_status','referral_discount_percent','status_message')
       ORDER BY \`key\` ASC`
    );
    const configMap = new Map(configRows.map((row) => [row.key, row.value]));
    assert(configMap.get('store_name') === 'Bloom Corner Kiddies Test', 'Expected updated store_name.');
    assert(configMap.get('seller_status') === 'offline', 'Expected seller_status to persist.');
    assert(configMap.get('referral_discount_percent') === null, 'Expected empty referral discount to store NULL.');

    await connection.execute(
      `INSERT INTO referral_codes (code, referrer_name, referrer_wa, total_referrals, status)
       VALUES ('BCK-SARAH001', 'Sarah', '2348000000001', 0, 'active')`
    );

    await connection.execute(
      `INSERT INTO referral_uses (code, new_buyer_name, new_buyer_wa, discount_percent)
       VALUES ('BCK-SARAH001', 'Ada', '2348111111111', 7)`
    );
    await connection.execute(
      `UPDATE referral_codes SET total_referrals = total_referrals + 1, status = 'inactive' WHERE code = 'BCK-SARAH001'`
    );

    const [[referralRow]] = await connection.execute(
      `SELECT code, total_referrals, status FROM referral_codes WHERE code = 'BCK-SARAH001'`
    );
    assert(referralRow.total_referrals === 1, 'Expected referral count increment.');
    assert(referralRow.status === 'inactive', 'Expected referral deactivate behavior.');

    const [[useRow]] = await connection.execute(
      `SELECT new_buyer_name, discount_percent FROM referral_uses WHERE code = 'BCK-SARAH001'`
    );
    assert(useRow.new_buyer_name === 'Ada', 'Expected redemption history row.');
    assert(Number(useRow.discount_percent) === 7, 'Expected discount snapshot in referral_uses.');

    await connection.rollback();
    console.log('PASS: admin settings/referrals DB smoke test');
  } catch (error) {
    try {
      await connection.rollback();
    } catch {}
    console.error(error.message);
    process.exit(1);
  } finally {
    await connection.end();
  }
}

main();
