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

    const [[girls]] = await connection.execute("SELECT id FROM categories WHERE slug = 'girls' LIMIT 1");
    const [[occasions]] = await connection.execute("SELECT id FROM categories WHERE slug = 'occasions' LIMIT 1");

    const [productInsert] = await connection.execute(
      `INSERT INTO products
        (name, gender, price, original_price, description, brand, material, season_tag, tiktok_url, is_available)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      ['Occasion Dress', 'girls', 12000, 15000, 'Pretty dress', 'MiniWear', 'Cotton', 'Party', 'https://example.test/tiktok', 0]
    );
    const productId = productInsert.insertId;

    await connection.execute(
      'INSERT INTO product_categories (product_id, category_id) VALUES (?, ?), (?, ?)',
      [productId, girls.id, productId, occasions.id]
    );
    await connection.execute(
      'INSERT INTO product_sizes (product_id, size_label, stock_qty, is_sold_out) VALUES (?, ?, ?, ?), (?, ?, ?, ?)',
      [productId, '3-4Y', 2, 0, productId, '4-5Y', 0, 1]
    );
    await connection.execute(
      'INSERT INTO product_images (product_id, file_path, sort_order) VALUES (?, ?, ?), (?, ?, ?)',
      [productId, 'uploads/products/demo/primary.jpg', 0, productId, 'uploads/products/demo/secondary.jpg', 1]
    );

    const [productList] = await connection.execute(
      `SELECT
          p.id,
          p.name,
          p.price,
          p.is_available,
          COALESCE(primary_image.file_path, '') AS primary_image,
          COALESCE(category_names.categories, '') AS categories,
          COALESCE(size_rows.size_summary, '') AS size_summary
       FROM products p
       LEFT JOIN (
         SELECT pi.product_id, pi.file_path
         FROM product_images pi
         INNER JOIN (
           SELECT product_id, MIN(sort_order) AS min_sort_order
           FROM product_images
           GROUP BY product_id
         ) mins ON mins.product_id = pi.product_id AND mins.min_sort_order = pi.sort_order
       ) primary_image ON primary_image.product_id = p.id
       LEFT JOIN (
         SELECT pc.product_id, GROUP_CONCAT(DISTINCT c.name ORDER BY c.sort_order SEPARATOR ', ') AS categories
         FROM product_categories pc
         INNER JOIN categories c ON c.id = pc.category_id
         GROUP BY pc.product_id
       ) category_names ON category_names.product_id = p.id
       LEFT JOIN (
         SELECT ps.product_id,
                GROUP_CONCAT(CONCAT(ps.size_label, ': ', ps.stock_qty, IF(ps.is_sold_out = 1 OR ps.stock_qty = 0, ' sold out', '')) ORDER BY ps.id SEPARATOR ', ') AS size_summary
         FROM product_sizes ps
         GROUP BY ps.product_id
       ) size_rows ON size_rows.product_id = p.id
       WHERE p.id = ?`,
      [productId]
    );

    assert(productList.length === 1, 'Expected product list query to return inserted product.');
    assert(productList[0].primary_image.includes('primary.jpg'), 'Expected primary thumbnail from lowest sort_order image.');
    assert(productList[0].categories.includes('Girls') && productList[0].categories.includes('Occasions'), 'Expected multi-category aggregation.');
    assert(productList[0].size_summary.includes('4-5Y: 0 sold out'), 'Expected sold-out stock summary in list query.');
    assert(Number(productList[0].is_available) === 0, 'Expected hidden product to remain queryable in admin list.');

    await connection.execute(
      'UPDATE products SET is_available = 1 WHERE id = ?',
      [productId]
    );
    const [[updatedVisibility]] = await connection.execute('SELECT is_available FROM products WHERE id = ?', [productId]);
    assert(Number(updatedVisibility.is_available) === 1, 'Expected product visibility toggle to persist.');

    await connection.execute('DELETE FROM products WHERE id = ?', [productId]);
    const [[remainingImageRows]] = await connection.execute('SELECT COUNT(*) AS count FROM product_images WHERE product_id = ?', [productId]);
    const [[remainingSizeRows]] = await connection.execute('SELECT COUNT(*) AS count FROM product_sizes WHERE product_id = ?', [productId]);
    const [[remainingCategoryRows]] = await connection.execute('SELECT COUNT(*) AS count FROM product_categories WHERE product_id = ?', [productId]);

    assert(Number(remainingImageRows.count) === 0, 'Expected delete cascade to remove product_images.');
    assert(Number(remainingSizeRows.count) === 0, 'Expected delete cascade to remove product_sizes.');
    assert(Number(remainingCategoryRows.count) === 0, 'Expected delete cascade to remove product_categories.');

    await connection.rollback();
    console.log('PASS: admin products DB smoke test');
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
