# Deployment Guide — Bloom Corner Kiddies

**Stack:** PHP + MariaDB + Alpine.js | **Host:** Hostinger Shared Hosting
**CI/CD:** GitHub Actions (rsync over SSH on push to `main`)

---

## Server Layout (Hostinger)

```
/home/u[account]/              ← SSH home directory
├── .env                       ← ⛔ NOT in git — manually created
├── config.php                 ← ⛔ NOT in git — manually created
└── public_html/               ← ✅ Deployed by GitHub Actions
    ├── index.html             ← Storefront (Alpine.js SPA)
    ├── api/                   ← PHP JSON API endpoints
    ├── admin/                 ← Protected admin panel
    ├── telegram-webhook.php   ← Telegram bot webhook
    └── uploads/               ← ⛔ Product images, NOT in git
        └── products/
```

---

## One-Time Server Setup

### Step 1 — Enable SSH on Hostinger

1. Log in to [hPanel](https://hpanel.hostinger.com)
2. Go to **Hosting → Manage → SSH Access**
3. Enable SSH → set a password
4. Note your SSH host (e.g. `srv123.hstgr.io`) and username (e.g. `u123456789`)
5. Default Hostinger SSH port: **65002** (not 22!)

---

### Step 2 — Generate an SSH Key Pair for GitHub Actions

On your local machine:
```bash
ssh-keygen -t ed25519 -C "github-actions-bloomrocxx" -f ~/.ssh/bloomrocxx_deploy
# Creates: ~/.ssh/bloomrocxx_deploy (private) + ~/.ssh/bloomrocxx_deploy.pub (public)
```

Add the **public key** to Hostinger:
```bash
cat ~/.ssh/bloomrocxx_deploy.pub
```
→ Copy output → hPanel → **SSH Keys** → paste as Authorized Key

---

### Step 3 — Add GitHub Repository Secrets

Go to your GitHub repo → **Settings → Secrets and variables → Actions → New repository secret**

| Secret Name | Value |
|-------------|-------|
| `HOSTINGER_HOST` | Your server hostname (e.g. `srv123.hstgr.io`) |
| `HOSTINGER_USER` | Your Hostinger SSH username (e.g. `u123456789`) |
| `HOSTINGER_SSH_PRIVATE_KEY` | Contents of `~/.ssh/bloomrocxx_deploy` (private key) |
| `HOSTINGER_SSH_PORT` | `65002` (Hostinger default SSH port) |

---

### Step 4 — Create `.env` and `config.php` on the Server

SSH into your server:
```bash
ssh -p 65002 u[account]@your-server.hstgr.io
```

Create the `.env` file:
```bash
nano ~/.env
```
Paste the contents from `.env.example` and fill in all values:
```env
DB_HOST=localhost
DB_NAME=u123456789_bloomcorner
DB_USER=u123456789_admin
DB_PASS=your_strong_password

ADMIN_PASSWORD_HASH=  # see Step 5 below

TELEGRAM_BOT_TOKEN=your_bot_token_from_botfather
TELEGRAM_SELLER_CHAT_ID=your_chat_id_from_userinfobot

WA_NUMBER=2349049308656
TELEGRAM_LINK=https://t.me/+2349049308656

APP_ENV=production
```

Copy `config.php.example` to the server and rename it:
```bash
# On your local machine:
scp -P 65002 config.php.example u[account]@your-server.hstgr.io:~/config.php
```

---

### Step 5 — Generate Admin Password Hash

On the server (or local PHP):
```bash
php -r "echo password_hash('your_chosen_password', PASSWORD_BCRYPT) . PHP_EOL;"
```
Copy the output (starts with `$2y$`) and paste it as `ADMIN_PASSWORD_HASH` in your `.env` file.

---

### Step 6 — Set Up MariaDB Database

In hPanel → **Databases → MySQL Databases**:
1. Create database: `u[account]_bloomcorner`
2. Create user: `u[account]_admin` with a strong password
3. Assign user to database with ALL PRIVILEGES
4. Note `DB_HOST` from hPanel (usually `localhost`)

Then import the SQL schema from Epic 1 (to be created in development sprint).

---

### Step 7 — Register Telegram Webhook

After deploying the site, register the bot webhook:
```bash
curl "https://api.telegram.org/bot{YOUR_TOKEN}/setWebhook?url=https://your-domain.com/telegram-webhook.php"
```
Expected response: `{"ok":true,...}`

---

## CI/CD Flow

```
Developer pushes to main
        ↓
GitHub Actions triggers deploy.yml
        ↓
rsync deploys ./public_html/ → Hostinger public_html/
        ↓
Excludes: .env, config.php, uploads/, docs/, planning files
        ↓
✅ Deployment complete
```

**What gets deployed:** Everything in `public_html/` — PHP files, `index.html`, `api/`, `admin/`, `telegram-webhook.php`

**What stays on server only:** `.env`, `config.php`, `uploads/`

---

## Updating Environment Variables

Environment variables (`ADMIN_PASSWORD_HASH`, `TELEGRAM_BOT_TOKEN`, etc.) are stored on the server in `~/.env` — NOT in GitHub. To update them:

```bash
ssh -p 65002 u[account]@your-server.hstgr.io
nano ~/.env
# Edit values → save → PHP reads them instantly on next request
```

No redeploy needed for config changes.

---

## Manual Deploy (fallback — without GitHub Actions)

```bash
# From project root on your local machine:
rsync -avz --delete \
  --exclude='.git/' --exclude='.env' --exclude='uploads/' \
  -e "ssh -p 65002" \
  ./public_html/ \
  u[account]@your-server.hstgr.io:public_html/
```

---

## Troubleshooting

| Issue | Fix |
|-------|-----|
| `DB connection failed` | Check `.env` DB credentials, verify DB/user created in hPanel |
| `FATAL: .env file not found` | SSH in, create `~/.env` from `.env.example` |
| `config.php: No such file` | SCP `config.php.example` to server as `~/config.php` |
| Telegram webhook not working | Re-run setWebhook curl command after deploy |
| SSH connection refused | Verify port 65002, check SSH enabled in hPanel |
| GitHub Actions SSH auth failure | Verify private key in GitHub Secrets, public key in Hostinger SSH Keys |
| Uploads disappear after deploy | ✅ Expected — rsync excludes `uploads/` — images are server-only |
