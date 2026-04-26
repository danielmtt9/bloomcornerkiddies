# Deployment Guide — Bloom Corner Kiddies

**Stack:** PHP + MariaDB + Alpine.js
**Host:** Hostinger Shared — bloomcornerkiddies.com
**Account:** u746391188
**CI/CD:** GitHub Actions (rsync over SSH on push to `main`)
**Repo:** https://github.com/danielmtt9/bloomcornerkiddies.git

---

## Server Layout (Hostinger)

```
/home/u746391188/              ← SSH home directory (your account)
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
3. Enable SSH access if not already enabled
4. SSH details:
   - **Host:** *(check hPanel → SSH Access for your server hostname)*
   - **Username:** `u746391188`
   - **Port:** `65002`

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
| `HOSTINGER_HOST` | SSH hostname from hPanel → SSH Access (e.g. `srv123.hstgr.io`) |
| `HOSTINGER_USER` | `u746391188` |
| `HOSTINGER_SSH_PRIVATE_KEY` | Contents of `keys/hostinger_deploy` (private key) |
| `HOSTINGER_SSH_PORT` | `65002` |

---

### Step 4 — Create `.env` and `config.php` on the Server

SSH into your server:
```bash
ssh -p 65002 u[account]@your-server.hstgr.io
```

SSH into your server:
```bash
ssh -p 65002 u746391188@YOUR_SERVER_HOST
```

Create the `.env` file:
```bash
cat > ~/.env << 'EOF'
DB_HOST=localhost
DB_NAME=u746391188_bloomcorner
DB_USER=u746391188_bloomcorner
DB_PASS=YOUR_DB_PASSWORD_FROM_HPANEL
DB_PORT=3306

ADMIN_PASSWORD_HASH=PASTE_OUTPUT_OF_npm_run_hash

TELEGRAM_BOT_TOKEN=YOUR_BOT_TOKEN
TELEGRAM_SELLER_CHAT_ID=YOUR_CHAT_ID
TELEGRAM_WEBHOOK_SECRET=YOUR_OPTIONAL_SECRET_TOKEN
TELEGRAM_ORDER_DESTINATION_MODE=disabled
TELEGRAM_SELLER_CHANNEL_ID=

WA_NUMBER=2349049308656
TELEGRAM_LINK=https://t.me/+2349049308656

APP_ENV=production
PUBLIC_BASE_URL=https://bloomcornerkiddies.com
PRODUCT_IMAGE_PROCESSING_MODE=off
EOF
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

**1. MySQL Database:**
- **Database:** `u746391188_bloomcorner` ✅ (already created)
- **User:** `u746391188_bloomcorner` ✅ (already created)
- **Password:** *(set when you created in hPanel — find it in hPanel → Databases)*
- **Host:** `localhost` (when connecting from the server itself)

**2. For remote migrations (running from your laptop):**
```
hPanel → MySQL Databases → Remote MySQL → Add your IP address
DB_HOST = your server's IP or hostname
```

**3. Run migrations:**
```bash
# Update .env locally with DB_HOST=server_IP and DB_PASS=real_password
npm run test-db    # verify connection
npm run migrate    # create all 8 tables + seed data
```

---

### Step 7 — Register Telegram Webhook

After deploying the site, register the bot webhook:
```bash
npm run telegram:webhook:set
```
Expected response includes `"ok": true`.

Verify webhook status:
```bash
npm run telegram:webhook:info
```

Optional rollback:
```bash
npm run telegram:webhook:delete
```

Configure seller notification routing for confirmed Telegram orders:
- `TELEGRAM_ORDER_DESTINATION_MODE=dm` to notify `TELEGRAM_SELLER_CHAT_ID`
- `TELEGRAM_ORDER_DESTINATION_MODE=channel` to notify `TELEGRAM_SELLER_CHANNEL_ID`
- `TELEGRAM_ORDER_DESTINATION_MODE=disabled` to keep notifications off until routing is finalized

Optional product image processing mode:
- `PRODUCT_IMAGE_PROCESSING_MODE=off` (default) keeps raw uploads
- `PRODUCT_IMAGE_PROCESSING_MODE=auto` crops uploads to a 4:5 frame when GD is available
- `PRODUCT_IMAGE_PROCESSING_MODE=required` enforces processing and fails upload when GD is unavailable

Manual fallback command:
```bash
curl "https://api.telegram.org/bot{YOUR_TOKEN}/setWebhook?url=https://bloomcornerkiddies.com/telegram-webhook.php&secret_token={YOUR_OPTIONAL_SECRET}"
```

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
| Telegram webhook not working | Re-run `npm run telegram:webhook:set`, then inspect `npm run telegram:webhook:info` |
| SSH connection refused | Verify port 65002, check SSH enabled in hPanel |
| GitHub Actions SSH auth failure | Verify private key in GitHub Secrets, public key in Hostinger SSH Keys |
| Uploads disappear after deploy | ✅ Expected — rsync excludes `uploads/` — images are server-only |
