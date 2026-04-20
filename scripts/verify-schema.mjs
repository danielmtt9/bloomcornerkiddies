#!/usr/bin/env node

import mysql from 'mysql2/promise';
import { readFileSync, existsSync } from 'fs';
import { resolve, dirname } from 'path';
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
  if (!condition) {
    throw new Error(message);
  }
}

async function main() {
  const env = loadEnv(envPath);
  const config = {
    host: env.DB_HOST || 'localhost',
    user: env.DB_USER || '',
    password: env.DB_PASS || '',
    database: env.DB_NAME || '',
    port: parseInt(env.DB_PORT || '3306', 10),
  };

  const connection = await mysql.createConnection(config);

  try {
    const [tableRows] = await connection.execute(
      `SELECT TABLE_NAME
       FROM information_schema.TABLES
       WHERE TABLE_SCHEMA = ?
       ORDER BY TABLE_NAME`,
      [config.database]
    );

    const tables = new Set(tableRows.map((row) => row.TABLE_NAME));
    const expectedTables = [
      'categories',
      'product_categories',
      'product_images',
      'product_sizes',
      'products',
      'referral_codes',
      'referral_uses',
      'seller_config',
    ];

    for (const table of expectedTables) {
      assert(tables.has(table), `Missing expected table: ${table}`);
    }

    assert(!tables.has('bot_sessions'), 'Deferred table bot_sessions should not exist in Lane A Sprint 1');

    const [productColumns] = await connection.execute(
      `SELECT COLUMN_NAME
       FROM information_schema.COLUMNS
       WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'products'`,
      [config.database]
    );
    const productColumnNames = new Set(productColumns.map((row) => row.COLUMN_NAME));
    assert(!productColumnNames.has('category_id'), 'products.category_id should not exist');

    const [pcIndexes] = await connection.execute(
      `SHOW INDEX FROM product_categories`
    );
    const pairIndex = pcIndexes.filter((row) => row.Key_name === 'PRIMARY');
    assert(pairIndex.length === 2, 'product_categories must enforce one row per (product_id, category_id)');

    const [constraints] = await connection.execute(
      `SELECT CONSTRAINT_NAME, DELETE_RULE
       FROM information_schema.REFERENTIAL_CONSTRAINTS
       WHERE CONSTRAINT_SCHEMA = ?
         AND CONSTRAINT_NAME IN (
           'fk_product_images_product',
           'fk_product_sizes_product',
           'fk_product_categories_product'
         )`,
      [config.database]
    );
    const deleteRules = Object.fromEntries(
      constraints.map((row) => [row.CONSTRAINT_NAME, row.DELETE_RULE])
    );
    assert(deleteRules.fk_product_images_product === 'CASCADE', 'product_images FK must cascade on delete');
    assert(deleteRules.fk_product_sizes_product === 'CASCADE', 'product_sizes FK must cascade on delete');
    assert(deleteRules.fk_product_categories_product === 'CASCADE', 'product_categories FK must cascade on delete');

    const [categoryRows] = await connection.execute(
      `SELECT slug
       FROM categories
       ORDER BY sort_order ASC, id ASC`
    );
    const categorySlugs = categoryRows.map((row) => row.slug);
    const expectedCategorySlugs = [
      'newborn',
      'baby',
      'toddler',
      'girls',
      'boys',
      'school',
      'occasions',
      'pyjamas',
      'footwear',
    ];
    assert(
      expectedCategorySlugs.every((slug) => categorySlugs.includes(slug)),
      `Seeded categories incomplete. Found: ${categorySlugs.join(', ')}`
    );

    const [configRows] = await connection.execute(
      `SELECT \`key\`, value
       FROM seller_config
       ORDER BY \`key\` ASC`
    );
    const configMap = new Map(configRows.map((row) => [row.key, row.value]));
    const expectedKeys = [
      'store_name',
      'tagline',
      'intro_text',
      'wa_number',
      'telegram_link',
      'delivery_info',
      'status_message',
      'payment_info',
      'seller_status',
      'referral_discount_percent',
    ];

    for (const key of expectedKeys) {
      assert(configMap.has(key), `Missing seller_config key: ${key}`);
    }

    assert(configMap.get('telegram_link') === 'https://t.me/placeholder', 'telegram_link seed should remain placeholder');

    console.log('✅ Schema verification passed.');
    console.log(`   Tables: ${expectedTables.join(', ')}`);
    console.log(`   Categories: ${categorySlugs.join(', ')}`);
    console.log(`   seller_config keys: ${expectedKeys.join(', ')}`);
  } finally {
    await connection.end();
  }
}

main().catch((error) => {
  console.error(`❌ Schema verification failed: ${error.message}`);
  process.exit(1);
});
