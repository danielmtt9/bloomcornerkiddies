#!/usr/bin/env node
/**
 * migrate.mjs
 * -------------------------------------------------
 * Runs the Bloom Corner Kiddies database migrations.
 * Creates all 8 tables + seeds initial data.
 *
 * Usage:
 *   npm run migrate
 *   # or: node scripts/migrate.mjs
 *
 * Options:
 *   --fresh    Drop all tables and recreate (⚠️ destroys data)
 *   --seed-only  Only run seed data (tables must exist)
 */

import mysql from 'mysql2/promise';
import { readFileSync, existsSync } from 'fs';
import { resolve, dirname } from 'path';
import { fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const envPath   = resolve(__dirname, '../.env');
const sqlPath   = resolve(__dirname, '../database/schema.sql');

const FRESH     = process.argv.includes('--fresh');
const SEED_ONLY = process.argv.includes('--seed-only');

// ----- Load .env -----------------------------------------------
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

// ----- Split SQL into individual statements ---------------------
function parseSqlStatements(sql) {
  return sql
    .split(';')
    .map(s => s.trim())
    .filter(s => s.length > 0 && !s.startsWith('--') && !s.startsWith('/*'));
}

async function main() {
  console.log('\n🌸 Bloom Corner Kiddies — Database Migration\n');

  if (FRESH) {
    console.log('⚠️  --fresh flag detected: ALL EXISTING TABLES WILL BE DROPPED\n');
  }

  const env = loadEnv(envPath);

  const config = {
    host:     env.DB_HOST || 'localhost',
    user:     env.DB_USER || '',
    password: env.DB_PASS || '',
    database: env.DB_NAME || '',
    port:     parseInt(env.DB_PORT || '3306'),
    multipleStatements: true,
    connectTimeout: 10000,
  };

  if (!config.user || !config.database) {
    console.error('❌ Missing DB credentials in .env — fill in DB_HOST, DB_USER, DB_PASS, DB_NAME');
    process.exit(1);
  }

  if (!existsSync(sqlPath)) {
    console.error(`❌ Schema file not found: ${sqlPath}`);
    process.exit(1);
  }

  let connection;
  try {
    connection = await mysql.createConnection(config);
    console.log(`✅ Connected to ${config.database}@${config.host}\n`);

    const rawSql = readFileSync(sqlPath, 'utf8');

    if (FRESH) {
      // Drop tables in reverse FK order
      const drops = [
        'referral_uses', 'referral_codes',
        'bot_sessions',
        'product_sizes', 'product_images',
        'products',
        'categories',
        'seller_config',
      ];
      console.log('🗑️  Dropping existing tables...');
      for (const table of drops) {
        await connection.execute(`DROP TABLE IF EXISTS \`${table}\``);
        console.log(`   dropped: ${table}`);
      }
      console.log('');
    }

    // Split schema into individual statements
    const statements = parseSqlStatements(
      SEED_ONLY
        ? rawSql.split('-- SEED DATA')[1] || ''
        : rawSql
    );

    console.log(`📋 Running ${statements.length} SQL statements...\n`);

    let success = 0;
    let skipped = 0;

    for (const stmt of statements) {
      try {
        await connection.execute(stmt);
        success++;
        // Show table creations
        if (stmt.toUpperCase().startsWith('CREATE TABLE')) {
          const match = stmt.match(/CREATE TABLE.*?`?(\w+)`?/i);
          if (match) console.log(`   ✓ Created table: ${match[1]}`);
        }
      } catch (err) {
        if (err.code === 'ER_TABLE_EXISTS_ERROR') {
          skipped++;
        } else {
          console.warn(`   ⚠️  Statement skipped: ${err.message}`);
          console.warn(`      SQL: ${stmt.substring(0, 80)}...`);
        }
      }
    }

    console.log(`\n✅ Migration complete!`);
    console.log(`   Statements run:    ${success}`);
    console.log(`   Already existed:   ${skipped}`);

    // Verify tables
    const [tables] = await connection.execute(
      `SELECT TABLE_NAME FROM information_schema.TABLES
       WHERE TABLE_SCHEMA = ? ORDER BY TABLE_NAME`,
      [config.database]
    );
    console.log(`\n📋 Tables in database (${tables.length}):`);
    tables.forEach(t => console.log(`   ✓ ${t.TABLE_NAME}`));
    console.log('');

  } catch (err) {
    console.error('\n❌ Migration failed:', err.message);
    process.exit(1);
  } finally {
    if (connection) await connection.end();
  }
}

main().catch(console.error);
