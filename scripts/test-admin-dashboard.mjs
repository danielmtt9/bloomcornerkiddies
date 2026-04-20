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
  if (!condition) {
    throw new Error(message);
  }
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

    const [firstInsert] = await connection.execute(
      'INSERT INTO products (name, gender, price, description) VALUES (?, ?, ?, ?)',
      ['Dashboard Test Alpha', 'girls', 5500, 'Alpha']
    );
    const [secondInsert] = await connection.execute(
      'INSERT INTO products (name, gender, price, description) VALUES (?, ?, ?, ?)',
      ['Dashboard Test Beta', 'boys', 6500, 'Beta']
    );

    const productOneId = firstInsert.insertId;
    const productTwoId = secondInsert.insertId;

    const [categories] = await connection.execute(
      "SELECT id, slug FROM categories WHERE slug IN ('girls', 'boys') ORDER BY id ASC"
    );
    const categoryMap = new Map(categories.map((row) => [row.slug, row.id]));

    await connection.execute(
      'INSERT INTO product_categories (product_id, category_id) VALUES (?, ?), (?, ?)',
      [productOneId, categoryMap.get('girls'), productTwoId, categoryMap.get('boys')]
    );

    await connection.execute(
      'INSERT INTO product_sizes (product_id, size_label, stock_qty, is_sold_out) VALUES (?, ?, ?, ?), (?, ?, ?, ?)',
      [productOneId, '3-4Y', 2, 0, productTwoId, '5-6Y', 0, 1]
    );

    await connection.execute(
      'INSERT INTO referral_codes (code, referrer_name, referrer_wa) VALUES (?, ?, ?)',
      [`SPRINT2-CODE-${Date.now()}`, 'Dashboard Test', '2348000000000']
    );

    const [[productCountRow]] = await connection.execute('SELECT COUNT(*) AS total_products FROM products');
    const [[soldOutRow]] = await connection.execute(
      `SELECT COUNT(*) AS sold_out_products
       FROM products p
       WHERE EXISTS (
         SELECT 1 FROM product_sizes ps WHERE ps.product_id = p.id
       ) AND NOT EXISTS (
         SELECT 1 FROM product_sizes ps WHERE ps.product_id = p.id AND ps.stock_qty > 0
       )`
    );
    const [[referralRow]] = await connection.execute('SELECT COUNT(*) AS total_referral_codes FROM referral_codes');
    const [recentProducts] = await connection.execute(
      `SELECT
          p.id,
          p.name,
          p.gender,
          p.updated_at,
          COALESCE(GROUP_CONCAT(DISTINCT c.name ORDER BY c.sort_order SEPARATOR ', '), '') AS categories,
          COALESCE(stock_totals.total_stock, 0) AS total_stock
       FROM products p
       LEFT JOIN product_categories pc ON pc.product_id = p.id
       LEFT JOIN categories c ON c.id = pc.category_id
       LEFT JOIN (
         SELECT product_id, SUM(stock_qty) AS total_stock
         FROM product_sizes
         GROUP BY product_id
       ) stock_totals ON stock_totals.product_id = p.id
       GROUP BY p.id, p.name, p.gender, p.updated_at, stock_totals.total_stock
       ORDER BY p.updated_at DESC, p.id DESC
       LIMIT 5`
    );

    assert(productCountRow.total_products >= 2, 'Expected dashboard total_products to include inserted rows.');
    assert(soldOutRow.sold_out_products >= 1, 'Expected sold_out_products to include fully sold out product.');
    assert(referralRow.total_referral_codes >= 1, 'Expected total_referral_codes to include inserted row.');
    assert(recentProducts.length >= 2, 'Expected recently updated products to include inserted rows.');
    assert(recentProducts.some((row) => Number(row.total_stock) === 2), 'Expected recent products to include aggregated stock totals.');

    await connection.rollback();

    console.log('PASS: admin dashboard DB smoke test');
  } catch (error) {
    if (connection) {
      try {
        await connection.rollback();
      } catch {}
    }
    console.error(error.message);
    process.exit(1);
  } finally {
    await connection.end();
  }
}

main();
