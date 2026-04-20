#!/usr/bin/env node
/**
 * generate-hash.mjs
 * -------------------------------------------------
 * Generates a bcrypt password hash for the admin
 * panel. Paste the output into your .env file as
 * ADMIN_PASSWORD_HASH.
 *
 * Usage:
 *   npm run hash
 *   # or: node scripts/generate-hash.mjs
 *   # or: node scripts/generate-hash.mjs mypassword
 */

import bcrypt from 'bcrypt';
import { createInterface } from 'readline';

const BCRYPT_ROUNDS = 12; // Cost factor — bcrypt default is 10, 12 is stronger

async function main() {
  let password = process.argv[2]; // optional CLI arg

  if (!password) {
    // Interactive prompt if no CLI arg
    const rl = createInterface({ input: process.stdin, output: process.stdout });
    password = await new Promise((resolve) => {
      rl.question('Enter admin password: ', (ans) => {
        rl.close();
        resolve(ans.trim());
      });
    });
  }

  if (!password || password.length < 6) {
    console.error('❌ Password must be at least 6 characters');
    process.exit(1);
  }

  console.log('\n⏳ Generating bcrypt hash (this takes a moment)...\n');
  const hash = await bcrypt.hash(password, BCRYPT_ROUNDS);

  console.log('✅ Hash generated!\n');
  console.log('─'.repeat(70));
  console.log(hash);
  console.log('─'.repeat(70));
  console.log('\n📋 Next steps:');
  console.log('  1. Copy the hash above');
  console.log('  2. Open your .env file on the server');
  console.log('  3. Set: ADMIN_PASSWORD_HASH=' + hash);
  console.log('  4. Save the file — no server restart needed\n');
}

main().catch((err) => {
  console.error('Error:', err.message);
  process.exit(1);
});
