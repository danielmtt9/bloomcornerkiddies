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

    await connection.execute(
      'UPDATE seller_config SET value = ? WHERE `key` = ?',
      ['online', 'seller_status']
    );
    await connection.execute(
      'UPDATE seller_config SET value = ? WHERE `key` = ?',
      ['Replies within one hour', 'status_message']
    );
    await connection.execute(
      'UPDATE seller_config SET value = ? WHERE `key` = ?',
      ['https://t.me/test-link', 'telegram_link']
    );

    const [productInsert] = await connection.execute(
      `INSERT INTO products
        (name, gender, price, original_price, brand, description, material, season_tag, tiktok_url, is_available)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      ['Filter Dress', 'girls', 9800, 12000, 'Tiny Bloom', 'Test product', 'Cotton', 'Occasion', 'https://example.test/video', 1]
    );
    const productId = productInsert.insertId;

    const [[girls]] = await connection.execute("SELECT id, slug FROM categories WHERE slug = 'girls' LIMIT 1");
    const [[occasions]] = await connection.execute("SELECT id, slug FROM categories WHERE slug = 'occasions' LIMIT 1");

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
      [productId, 'uploads/products/test/primary.jpg', 0, productId, 'uploads/products/test/secondary.jpg', 1]
    );

    const [products] = await connection.execute(
      `SELECT
          p.id,
          p.name,
          p.price,
          p.original_price,
          p.brand,
          p.gender,
          p.is_available,
          primary_image.file_path AS primary_image_path,
          image_rows.images_json,
          size_rows.sizes_json,
          category_rows.categories_json,
          stock_rows.has_in_stock
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
         SELECT
           pi.product_id,
           CONCAT('[', GROUP_CONCAT(JSON_OBJECT('file_path', pi.file_path, 'sort_order', pi.sort_order) ORDER BY pi.sort_order, pi.id SEPARATOR ','), ']') AS images_json
         FROM product_images pi
         GROUP BY pi.product_id
       ) image_rows ON image_rows.product_id = p.id
       LEFT JOIN (
         SELECT
           ps.product_id,
           CONCAT('[', GROUP_CONCAT(JSON_OBJECT('size_label', ps.size_label, 'stock_qty', ps.stock_qty, 'is_sold_out', IF(ps.is_sold_out = 1 OR ps.stock_qty = 0, true, false)) ORDER BY ps.id SEPARATOR ','), ']') AS sizes_json
         FROM product_sizes ps
         GROUP BY ps.product_id
       ) size_rows ON size_rows.product_id = p.id
       LEFT JOIN (
         SELECT
           pc.product_id,
           CONCAT('[', GROUP_CONCAT(JSON_OBJECT('id', c.id, 'name', c.name, 'slug', c.slug) ORDER BY c.sort_order SEPARATOR ','), ']') AS categories_json
         FROM product_categories pc
         INNER JOIN categories c ON c.id = pc.category_id
         GROUP BY pc.product_id
       ) category_rows ON category_rows.product_id = p.id
       LEFT JOIN (
         SELECT product_id, MAX(CASE WHEN stock_qty > 0 AND is_sold_out = 0 THEN 1 ELSE 0 END) AS has_in_stock
         FROM product_sizes
         GROUP BY product_id
       ) stock_rows ON stock_rows.product_id = p.id
       WHERE p.id = ?`,
      [productId]
    );

    assert(products.length === 1, 'Expected storefront products query to return seeded product.');
    const product = products[0];
    const images = JSON.parse(product.images_json);
    const sizes = JSON.parse(product.sizes_json);
    const categories = JSON.parse(product.categories_json);
    assert(product.primary_image_path.includes('primary.jpg'), 'Expected primary_image_url source path.');
    assert(Array.isArray(images) && images.length === 2, 'Expected gallery images payload.');
    assert(Array.isArray(sizes) && sizes.length === 2, 'Expected sizes[] payload.');
    assert(Array.isArray(categories) && categories.some((row) => row.slug === 'girls') && categories.some((row) => row.slug === 'occasions'), 'Expected categories[] payload with multi-category membership.');
    assert(Number(product.has_in_stock) === 1, 'Expected badge stock state to reflect in-stock sizes.');

    const [filtered] = await connection.execute(
      `SELECT COUNT(*) AS count
       FROM products p
       INNER JOIN product_categories pc ON pc.product_id = p.id
       INNER JOIN categories c ON c.id = pc.category_id
       WHERE p.is_available = 1 AND c.slug = ? AND p.id = ?`,
      ['occasions', productId]
    );
    assert(Number(filtered[0].count) === 1, 'Expected category filtering through product_categories.');

    const [statusRows] = await connection.execute(
      `SELECT \`key\`, value
       FROM seller_config
       WHERE \`key\` IN ('seller_status', 'status_message', 'wa_number', 'telegram_link', 'delivery_info')
       ORDER BY \`key\` ASC`
    );
    const statusMap = new Map(statusRows.map((row) => [row.key, row.value]));
    assert(statusMap.get('seller_status') === 'online', 'Expected status payload to read seller_status.');
    assert(statusMap.get('status_message') === 'Replies within one hour', 'Expected status payload to include message.');
    assert(statusMap.get('telegram_link') === 'https://t.me/test-link', 'Expected status payload to include Telegram link.');
    assert(statusMap.has('delivery_info'), 'Expected status payload source to include delivery info.');

    const [categoryRows] = await connection.execute(
      'SELECT slug FROM categories ORDER BY sort_order ASC'
    );
    assert(categoryRows.length >= 9, 'Expected categories endpoint source data.');

    const productApi = readFileSync('public_html/api/products.php', 'utf8');
    const categoryApi = readFileSync('public_html/api/categories.php', 'utf8');
    const statusApi = readFileSync('public_html/api/status.php', 'utf8');
    assert(productApi.includes('app_json('), 'Expected products API to return JSON.');
    assert(categoryApi.includes('app_json('), 'Expected categories API to return JSON.');
    assert(statusApi.includes('app_json('), 'Expected status API to return JSON.');

    await connection.rollback();
    console.log('PASS: storefront API smoke test');
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
