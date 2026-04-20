#!/usr/bin/env node
/**
 * generate-deploy-key.mjs
 * -------------------------------------------------
 * Generates an ed25519 SSH keypair for GitHub Actions
 * deployment to Hostinger.
 *
 * Usage:
 *   npm run deploy-key
 *   # or: node scripts/generate-deploy-key.mjs
 *
 * Output:
 *   keys/hostinger_deploy     ← private key (add to GitHub Secrets)
 *   keys/hostinger_deploy.pub ← public key (add to Hostinger SSH Keys)
 */

import { execSync } from 'child_process';
import { existsSync, mkdirSync, readFileSync } from 'fs';
import { resolve, dirname } from 'path';
import { fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const keysDir   = resolve(__dirname, '../keys');
const keyPath   = resolve(keysDir, 'hostinger_deploy');

async function main() {
  console.log('\n🔑 Bloom Corner Kiddies — SSH Deploy Key Generator\n');

  if (existsSync(keyPath)) {
    console.log('⚠️  Key already exists at keys/hostinger_deploy');
    console.log('   Delete it first if you want to regenerate.\n');
    showInstructions();
    return;
  }

  // Create keys/ directory
  mkdirSync(keysDir, { recursive: true });

  console.log('⏳ Generating ed25519 keypair...\n');

  try {
    execSync(
      `ssh-keygen -t ed25519 \
        -C "github-actions-bloomcornerkiddies" \
        -f "${keyPath}" \
        -N ""`,
      { stdio: 'inherit' }
    );
  } catch {
    // Fallback to RSA if ed25519 not available
    execSync(
      `ssh-keygen -t rsa -b 4096 \
        -C "github-actions-bloomcornerkiddies" \
        -f "${keyPath}" \
        -N ""`,
      { stdio: 'inherit' }
    );
  }

  console.log('\n✅ SSH keypair generated in keys/\n');
  console.log('─'.repeat(70));
  console.log('📄 PUBLIC KEY (add this to Hostinger → SSH Keys):');
  console.log('─'.repeat(70));
  console.log(readFileSync(`${keyPath}.pub`, 'utf8'));
  console.log('─'.repeat(70));

  showInstructions();
}

function showInstructions() {
  console.log('\n📋 Setup Instructions:\n');
  console.log('STEP 1 — Add public key to Hostinger:');
  console.log('   hPanel → Hosting → Manage → SSH Access → SSH Keys');
  console.log('   Paste the contents of keys/hostinger_deploy.pub\n');

  console.log('STEP 2 — Add private key to GitHub Secrets:');
  console.log('   GitHub Repo → Settings → Secrets → Actions → New Secret');
  console.log('   Name:  HOSTINGER_SSH_PRIVATE_KEY');
  console.log('   Value: Contents of keys/hostinger_deploy (the PRIVATE key)\n');

  console.log('STEP 3 — Add other GitHub Secrets:');
  console.log('   HOSTINGER_HOST        → your server hostname (e.g. srv123.hstgr.io)');
  console.log('   HOSTINGER_USER        → your SSH username (e.g. u123456789)');
  console.log('   HOSTINGER_SSH_PORT    → 65002 (Hostinger default)\n');

  console.log('⚠️  IMPORTANT: Never commit the keys/ directory to git!');
  console.log('   It is already in .gitignore\n');
}

main().catch(console.error);
