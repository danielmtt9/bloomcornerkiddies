#!/usr/bin/env node
/**
 * test-connection.mjs
 * -------------------------------------------------
 * Tests the MariaDB connection using credentials
 * from your local .env file.
 *
 * Usage:
 *   npm run test-db
 *   # or: node scripts/test-connection.mjs
 *
 * Requires .env in project root with DB_* variables.
 */

import mysql from 'mysql2/promise';
import { readFileSync, existsSync } from 'fs';
import { resolve, dirname } from 'path';
import { fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const envPath = resolve(__dirname, '../.env');

// ----- Load .env manually (no dotenv module needed) -----
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

async function main() {
  console.log('\n🔍 Bloom Corner Kiddies — DB Connection Test\n');

  const env = loadEnv(envPath);

  const config = {
    host:     env.DB_HOST     || process.env.DB_HOST     || 'localhost',
    user:     env.DB_USER     || process.env.DB_USER     || '',
    password: env.DB_PASS     || process.env.DB_PASS     || '',
    database: env.DB_NAME     || process.env.DB_NAME     || '',
    port:     parseInt(env.DB_PORT || process.env.DB_PORT || '3306'),
    ssl:      false,
    connectTimeout: 10000,
  };

  // Mask password for display
  console.log('📡 Connecting to:');
  console.log(`   Host:     ${config.host}`);
  console.log(`   Port:     ${config.port}`);
  console.log(`   Database: ${config.database}`);
  console.log(`   User:     ${config.user}`);
  console.log(`   Password: ${'*'.repeat(config.password.length || 8)}`);
  console.log('');

  if (!config.user || !config.database) {
    console.error('❌ Missing DB_USER or DB_NAME in .env — fill in credentials first');
    process.exit(1);
  }

  let connection;
  try {
    connection = await mysql.createConnection(config);
    console.log('✅ Connection established!\n');

    // Quick health check
    const [rows] = await connection.execute('SELECT VERSION() AS version, NOW() AS server_time');
    console.log(`   MariaDB/MySQL version: ${rows[0].version}`);
    console.log(`   Server time:           ${rows[0].server_time}`);

    // Check if schema already exists
    const [tables] = await connection.execute(
      `SELECT TABLE_NAME FROM information_schema.TABLES
       WHERE TABLE_SCHEMA = ? ORDER BY TABLE_NAME`,
      [config.database]
    );

    if (tables.length === 0) {
      console.log('\n📋 Database is empty — run migrations next: npm run migrate');
    } else {
      console.log(`\n📋 Tables found (${tables.length}):`);
      tables.forEach(t => console.log(`   ✓ ${t.TABLE_NAME}`));
    }

    console.log('\n✅ All good! Database is reachable.\n');

  } catch (err) {
    console.error('\n❌ Connection failed:');
    console.error(`   ${err.message}`);
    console.error('\n💡 Tips:');
    console.error('   • Check your .env DB_HOST, DB_USER, DB_PASS, DB_NAME values');
    console.error('   • On Hostinger: DB_HOST is usually "localhost" when on server');
    console.error('   • For remote access: enable Remote MySQL in hPanel first');
    console.error('   • Check DB user has correct privileges in hPanel → Databases\n');
    process.exit(1);
  } finally {
    if (connection) await connection.end();
  }
}

main().catch(console.error);
