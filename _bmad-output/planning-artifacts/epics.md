---
stepsCompleted: [step-01-validate-prerequisites]
inputDocuments:
  - _bmad-output/planning-artifacts/prd-001-storefront.md
  - _bmad-output/planning-artifacts/prd-002-handoff.md
  - _bmad-output/planning-artifacts/prd-003-admin.md
  - _bmad-output/planning-artifacts/prd-004-marketing.md
  - _bmad-output/planning-artifacts/prd-index.md
project: Bloom Corner Kiddies
version: 1.0
date: 2026-04-20
---

# Bloom Corner Kiddies — Epic Breakdown

## Overview

This document provides the complete epic and story breakdown for **Bloom Corner Kiddies**, decomposing requirements from PRD-001 through PRD-004 into implementable developer stories. Organized into 8 epics following the build order: foundation → admin → storefront → commerce → referral → growth.

**Store:** Bloom Corner Kiddies | **Stack:** PHP + MariaDB + Alpine.js | **Hosting:** Hostinger Shared

---

## Requirements Inventory

### Functional Requirements

FR-01: Product grid with 2-column mobile layout, product cards (photo, name, price, badges)
FR-02: Product status badges (New, Sale, Sold Out, On Sale) with visual distinction
FR-03: Category filter tabs (All, Girls, Boys, Babies, Unisex) — horizontal scrollable
FR-04: Gender filter (All, Girls, Boys) — stackable with category
FR-05: Season/Occasion tag filters — optional filter layer
FR-06: Sort order (default: newest first)
FR-07: Trust hero section on homepage (seller photo, name, intro, WhatsApp/Telegram, seller status dot)
FR-08: Product photo gallery — 4:5 portrait ratio, swipeable, lazy-loaded
FR-09: TikTok video embed on product detail (optional per product)
FR-10: Product information display (name, price, discount price, brand, description, gender/age/size info)
FR-11: Size selector (per-size availability, greyed-out sold-out sizes)
FR-12: Delivery information section on product detail
FR-13: WhatsApp Order button (pre-filled message — wa.me/2349049308656)
FR-14: Telegram Order button (personal account — t.me/+2349049308656)
FR-15: Save for Later button (WA saved messages, clipboard fallback)
FR-16: Size guide accordion (collapsible, with WA prompt for sizing help)
FR-17: Sold-out handling — show sold-out badge, Notify Me WA button
FR-18: Ultra-lightweight frontend — Alpine.js only, no build step, <50KB HTML+JS
FR-19: PHP API response format — JSON, product/category/status endpoints
FR-20: Seller status polling — every 60s, live dot update without page reload
FR-21 (Storefront): Shareable product URLs — /?product={id} for deep linking
FR-21 (Handoff): wa.me link generation — encoded pre-filled messages
FR-22: WA Order message template (size selected)
FR-23: WA Enquiry message template (no size selected)
FR-24: WA Size enquiry template button
FR-25: WA Notify Me template (sold out)
FR-26: WA Size guide help template
FR-27: Branded WhatsApp short link (marketing use)
FR-28: WA Business profile configuration
FR-29: WA away message
FR-30: WA Quick Replies (/available, /sizes, /delivery, /payment, /thanks)
FR-31: WA Business customer labels (New, Repeat, VIP, Awaiting Payment, Dispatched)
FR-32: Telegram bot setup (BotFather, token, webhook registration)
FR-33: Telegram bot order intake flow (7-step conversation)
FR-34: Seller notification via Telegram when bot order is complete
FR-35: Telegram button on storefront links to personal account (not bot)
FR-36: Gift order handling in Telegram bot
FR-37: Seller status display on storefront (🟢🟡🔴 dots)
FR-38: Status API endpoint — GET /api/status.php
FR-39: Status update by seller from admin panel
FR-40: Admin login page (/admin/login.php) with PHP session auth
FR-41: Session guard on all admin pages
FR-42: Admin logout
FR-43: Admin password stored as bcrypt-equivalent hash outside public_html
FR-44: Admin header navigation (all pages)
FR-45: Admin dashboard — stats overview (total products, sold-out count, recent updates)
FR-46: Product form — required fields (name, category, gender, price, sizes, photos)
FR-47: Product form — optional fields (brand, discount price, description, TikTok)
FR-48: Size manager component (add label, set stock qty, per-size sold-out toggle)
FR-49: Photo upload (multiple, 4:5 ratio enforced, MIME validated, ≤5MB each)
FR-50: Product form save → DB persistence (transaction, rollback on fail)
FR-51: Pre-populated edit form for existing products
FR-52: Available/unavailable toggle per product
FR-53: Save changes for product edits
FR-54: Delete confirmation dialog
FR-55: Delete cascade (product_images + product_sizes)
FR-56: Rapid stock update (inline qty/sold-out toggle from product list)
FR-57: Seller status toggle (Online → BRB → Offline)
FR-58: Status message text field
FR-59: Store info settings (store_name, tagline, wa_number, telegram_link, delivery_info, status_message)
FR-60: Admin password change UI
FR-61: Referral code generation (BCK-NAME format, unique, stored in referral_codes)
FR-61A: Referral code list view in admin
FR-61B: Generate new referral code UI
FR-61C: Record referral redemption UI
FR-61D: Referral code detail view (history, accumulative count)
FR-61E: Referral discount % config in admin settings
FR-62: Referral code field in WA pre-fill message
FR-63: Referral code redemption and DB tracking
FR-64: Accumulative referral rewards (DB-backed count)
FR-65: Referral share card (Canva design deliverable)
FR-66: WA Broadcast list strategy and management
FR-66A: Referral discount config in seller_config
FR-67: WA Broadcast message templates (new stock, flash sale)
FR-68: Broadcast frequency rules (max 2×/week)
FR-69: UTM tracking on all broadcast links
FR-70: CRM Google Sheet structure and columns
FR-71: Follow-up trigger types (size-up, birthday, seasonal, post-purchase, VIP)
FR-72: Message personalization standard (first name, child name, product reference)
FR-73: CRM update process (after every sale, Monday review)
FR-74: VIP qualification (3+ orders)
FR-75: VIP first-look broadcast (24h early)
FR-76: VIP label in WA Business
FR-77: Outfit of the Week content
FR-78: "Seen on Real Kids" gallery (Future sprint)
FR-79: School calendar strategy and broadcast schedule
FR-80: UTM link structure across all channels

### Non-Functional Requirements

NFR-01: Time to First Contentful Paint < 2s on mobile 4G (measured via Google PageSpeed Insights, Moto G4 profile)
NFR-02: Mobile PageSpeed score ≥ 85
NFR-03: Total frontend payload — HTML + Alpine.js ≤ 50KB (excluding images)
NFR-04: All touch targets ≥ 44×44px
NFR-05: No horizontal scrolling at 320px viewport width
NFR-06: Product images served with native lazy loading
NFR-07: Admin password stored as one-way cryptographic hash (bcrypt-equivalent, cost factor ≥ 10), never in plaintext
NFR-08: config.php stored OUTSIDE public_html — not web-accessible
NFR-09: All PHP file uploads validated by MIME type (not extension only)
NFR-10: Admin session expires on browser close (no "remember me" in MVP)
NFR-11: Telegram bot must respond to webhook POST within 5 seconds (PHP execution limit)
NFR-12: Telegram bot must be idempotent on webhook retry (no duplicate orders)
NFR-13: All WhatsApp messages URL-encoded (PHP urlencode()), emoji preserved
NFR-14: WA pre-fill message ≤ 4,096 characters (wa.me URL limit)
NFR-15: All prices displayed in whole Nigerian Naira (₦) — zero decimals
NFR-16: Seller status polling interval = 60 seconds (storefront)
NFR-17: Hostinger shared hosting constraints — PHP webhook only, no persistent Node.js
NFR-18: All product image uploads ≤ 5MB per file
NFR-19: DB operations on product save/delete use transactions with rollback on failure
NFR-20: referral_codes.code enforced UNIQUE in DB

### Additional Requirements (Technical / Architecture)

- **DB:** MariaDB with 8 tables — products, product_images, product_sizes, categories, seller_config, bot_sessions, referral_codes, referral_uses
- **Hosting:** Hostinger Shared — PHP native, no Node.js, no persistent processes
- **Frontend:** Single `index.html` + Alpine.js (CDN, 15KB) + inline CSS — zero build step
- **Admin:** Separate PHP files in `/admin/` directory, session-based auth
- **Config:** `config.php` stored one level ABOVE `public_html/` — reads from `.env` file in same directory; included via `require '../config.php'`
- **.env pattern:** `.env` file stored ABOVE `public_html/` on server — NEVER committed to git. `.env.example` committed as template. `config.php.example` committed as template.
- **Images:** Stored in `/public_html/uploads/products/{product_id}/` — served as static files — gitignored
- **API:** PHP files in `/api/` directory — return JSON — no framework
- **Telegram bot:** `telegram-webhook.php` in `public_html/` root — registered with Telegram via setWebhook
- **seller_config:** Key-value table — all store settings stored and retrieved from this table
- **CI/CD:** GitHub Actions (`.github/workflows/deploy.yml`) — rsync over SSH on push to `main` — deploys `public_html/` to Hostinger; excludes `.env`, `config.php`, `uploads/`
- **SSH port:** Hostinger non-standard port **65002** (not 22)
- **GitHub Secrets required:** `HOSTINGER_HOST`, `HOSTINGER_USER`, `HOSTINGER_SSH_PRIVATE_KEY`, `HOSTINGER_SSH_PORT`
- **Build order:** Infra setup → DB → Admin → Storefront → Commerce → Growth

### UX Design Requirements

UX-DR1: All product cards use 4:5 portrait aspect ratio — consistent grid height across all viewport widths
UX-DR2: Filter tabs horizontally scrollable on mobile — no wrapping, no truncation
UX-DR3: Seller status dot must update without page reload (Alpine.js reactive state)
UX-DR4: Product detail view accessed via URL fragment/query param (back button returns to grid position)
UX-DR5: WhatsApp/Telegram buttons visually distinct (green WA, blue TG) — primary CTAs above-the-fold on product detail
UX-DR6: Sold-out sizes greyed with strikethrough — never hidden (parent can see what's unavailable)
UX-DR7: Admin panel mobile-friendly — seller manages inventory from phone
UX-DR8: Toast notification for clipboard copy fallback on "Save for Later"
UX-DR9: Size selector shows available sizes as tappable chips — selected state visually clear
UX-DR10: Product photo gallery swipeable on mobile (CSS scroll-snap or Alpine.js gesture)

### FR Coverage Map

| Epic | Stories | FRs Covered |
|------|---------|-------------|
| **Epic 0** | 0.1–0.3 | Infrastructure: .env, config.php, GitHub Actions CI/CD, project structure |
| Epic 1: DB Foundation | 1.1–1.3 | All DB schema (products, images, sizes, categories, seller_config, bot_sessions, referral_codes, referral_uses) |
| Epic 2: Admin Auth & Shell | 2.1–2.3 | FR-40, FR-41, FR-42, FR-43, FR-44, FR-45 |
| Epic 3: Admin Product Management | 3.1–3.5 | FR-46–FR-56 |
| Epic 4: Admin Settings & Referrals | 4.1–4.3 | FR-57–FR-61E, FR-66A |
| Epic 5: Storefront Catalogue | 5.1–5.5 | FR-01–FR-09, FR-18, FR-19, FR-20, FR-21(storefront) |
| Epic 6: Product Detail & Commerce | 6.1–6.5 | FR-10–FR-17, FR-21–FR-27, FR-35, FR-37, FR-38, UX-DR5–UX-DR10 |
| Epic 7: Telegram Bot | 7.1–7.3 | FR-32–FR-34, FR-36, NFR-11, NFR-12 |
| Epic 8: Growth & Marketing | 8.1–8.4 | FR-62–FR-64, FR-66–FR-80 |

---

## Epic List

0. **Epic 0 — Infrastructure & DevOps** *(Run first — CI/CD, .env, project structure)*
1. **Epic 1 — Database Foundation** *(Run second — all other epics depend on this)*
2. **Epic 2 — Admin Panel Auth & Shell** *(Run third — needed to enter data)*
3. **Epic 3 — Admin Product Management** *(Run fourth — product CRUD)*
4. **Epic 4 — Admin Settings, Status & Referral Management** *(Run fifth)*
5. **Epic 5 — Storefront Catalogue & Filters** *(Run sixth — requires data from Epics 1–4)*
6. **Epic 6 — Product Detail, WhatsApp & Telegram Handoff** *(Run seventh — commerce layer)*
7. **Epic 7 — Telegram Bot (Standalone Order Channel)** *(Run eighth)*
8. **Epic 8 — Growth, Referral Tracking & Marketing Tools** *(Run ninth)*

---

## Epic 0: Infrastructure & DevOps

**Goal:** Establish the project structure, environment variable pattern, and automated deployment pipeline before writing any application code. Every subsequent epic depends on this being correct.

### Story 0.1: Project Structure & .env Pattern

As a developer,
I want the project directory structure created with .env and config.php patterns in place,
So that sensitive credentials are never committed to git and all PHP files have a consistent way to load config.

**Acceptance Criteria:**

**Given** the project root on Hostinger server (`/home/u[account]/`)
**Then** the directory structure is:
```
/home/u[account]/
├── .env                  ← created from .env.example (manually)
├── config.php            ← created from config.php.example (manually)
└── public_html/
    ├── index.html
    ├── api/
    ├── admin/
    ├── telegram-webhook.php
    └── uploads/products/
```
**And** `.env` contains all required keys (DB_*, ADMIN_PASSWORD_HASH, TELEGRAM_BOT_TOKEN, TELEGRAM_SELLER_CHAT_ID)
**And** `config.php` reads `.env` using PHP file parsing — no Composer required
**And** `config.php` exposes: `get_db()` (PDO singleton), `get_config($key)` (seller_config reader), and all constants
**And** accessing `https://your-domain.com/.env` returns 403 or 404 (not web-accessible)
**And** accessing `https://your-domain.com/config.php` returns 403 or 404 (file is NOT in public_html)

---

### Story 0.2: GitHub Repository & .gitignore

As a developer,
I want a GitHub repository with a correct .gitignore,
So that no secrets or server-only files are ever accidentally committed.

**Acceptance Criteria:**

**Given** a GitHub repository is created for the project
**When** the initial project files are committed
**Then** `.gitignore` is present and ignores: `.env`, `config.php`, `public_html/uploads/`
**And** `.env.example` IS committed (shows required vars without values)
**And** `config.php.example` IS committed (shows the PHP config pattern)
**And** running `git status` in a directory containing `.env` or `config.php` shows them as ignored
**And** the `main` branch is the production branch (deploys to Hostinger on push)

---

### Story 0.3: GitHub Actions CI/CD Pipeline

As a developer,
I want automated deployment to Hostinger on every push to main,
So that code changes are deployed without manual FTP/SCP steps.

**Acceptance Criteria:**

**Given** GitHub Secrets are set: `HOSTINGER_HOST`, `HOSTINGER_USER`, `HOSTINGER_SSH_PRIVATE_KEY`, `HOSTINGER_SSH_PORT` (65002)
**When** code is pushed to the `main` branch
**Then** GitHub Actions workflow `.github/workflows/deploy.yml` triggers automatically
**And** rsync deploys only the `public_html/` directory contents to Hostinger's `public_html/`
**And** the following are EXCLUDED from rsync: `.env`, `.github/`, `uploads/`, `*.md`, `_bmad-output/`, `docs/`
**And** the workflow completes with ✅ status in GitHub Actions tab
**And** manual trigger available via GitHub Actions UI (`workflow_dispatch`)
**And** only one deployment runs at a time (`concurrency: group: production-deploy`)

---

## Epic 1: Database Foundation

**Goal:** Create the complete MariaDB schema so all other epics can write and read data. This epic has no UI — it is pure SQL and config.

### Story 1.1: Database Schema — Products & Categories

As a developer,
I want the core product and category tables created in MariaDB,
So that products can be stored, retrieved, and filtered.

**Acceptance Criteria:**

**Given** access to the MariaDB database via Hostinger hPanel phpMyAdmin or CLI
**When** the migration SQL is executed
**Then** the following tables exist with correct columns and constraints:
- `categories` (id, name, slug, sort_order)
- `products` (id, name, category_id FK, gender ENUM, age_range, description, price INT, original_price INT NULL, brand NULL, tiktok_url NULL, is_available, is_featured, sort_order, created_at, updated_at)
- `product_images` (id, product_id FK, file_path, sort_order)
- `product_sizes` (id, product_id FK, size_label, stock_qty, is_sold_out)
**And** all foreign keys enforce CASCADE DELETE from products to product_images and product_sizes
**And** seed data inserts at least 3 categories (Girls, Boys, Babies)

---

### Story 1.2: Database Schema — Seller Config & Bot Sessions

As a developer,
I want the seller_config and bot_sessions tables created,
So that seller settings and Telegram bot state can be persisted.

**Acceptance Criteria:**

**Given** the product tables from Story 1.1 exist
**When** the migration SQL for config/bot tables is executed
**Then** the following tables exist:
- `seller_config` (id, key VARCHAR UNIQUE, value TEXT, updated_at)
**And** seed rows exist for: store_name='Bloom Corner Kiddies', wa_number='2349049308656', telegram_link='https://t.me/+2349049308656', payment_info='Bank transfer', seller_status='online', referral_discount_percent=NULL
- `bot_sessions` (chat_id BIGINT PK, step VARCHAR, product_id NULL, size_label NULL, buyer_name NULL, address NULL, is_gift TINYINT, gift_message NULL, updated_at)
**And** a helper PHP function `get_config($key)` is available to retrieve any seller_config value by key

---

### Story 1.3: Database Schema — Referral System

As a developer,
I want the referral_codes and referral_uses tables created,
So that the accumulative referral tracking system can operate.

**Acceptance Criteria:**

**Given** the previous DB tables exist
**When** the referral schema SQL is executed
**Then** the following tables exist:
- `referral_codes` (id, code VARCHAR(50) UNIQUE NOT NULL, referrer_name, referrer_wa, total_referrals INT DEFAULT 0, status ENUM active/inactive DEFAULT active, created_at)
- `referral_uses` (id, code FK → referral_codes.code ON DELETE CASCADE, new_buyer_name, new_buyer_wa, discount_percent TINYINT DEFAULT 0, created_at)
**And** `UNIQUE` constraint on `referral_codes.code` is verified (insert duplicate → error)
**And** deleting a referral_code cascades to delete all its referral_uses rows

---

## Epic 2: Admin Panel Auth & Shell

**Goal:** A secure, password-protected admin area accessible only to the seller. This is the front door to all admin features.

### Story 2.1: Admin Config & Login Page

As the seller,
I want a login page at /admin/login.php that accepts my password,
So that only I can access the admin panel.

**Acceptance Criteria:**

**Given** `config.php` is stored above `public_html/` and contains `ADMIN_PASSWORD_HASH`
**When** the seller navigates to `/admin/login.php`
**Then** a login form (password field only — no username) is displayed
**And** on correct password: PHP verifies against bcrypt hash, creates `$_SESSION['admin']`, redirects to `/admin/index.php`
**And** on incorrect password: error message displayed, no redirect, no session created
**And** `/admin/login.php` served over HTTPS (Hostinger Let's Encrypt — verify active)
**And** `config.php` is NOT accessible from a browser URL (returns 403 or 404)

---

### Story 2.2: Session Guard & Logout

As the seller,
I want every admin page to require an active session,
So that no admin page is accessible without logging in.

**Acceptance Criteria:**

**Given** a user visits any `/admin/*.php` page
**When** `$_SESSION['admin']` is not set
**Then** the user is immediately redirected to `/admin/login.php`
**And** a shared `session_guard.php` include handles this check on all admin pages
**And** `/admin/logout.php` destroys the session and redirects to `/admin/login.php`
**And** after logout, pressing browser Back does NOT restore admin access

---

### Story 2.3: Admin Shell — Header Navigation & Dashboard

As the seller,
I want a consistent navigation header and a dashboard overview page,
So that I can navigate efficiently between admin sections from any device.

**Acceptance Criteria:**

**Given** the seller is logged in and visits `/admin/index.php`
**Then** a dashboard page is shown with:
  - Total product count
  - Sold-out product count
  - Total referral codes issued
  - Last 5 recently updated products (name + updated_at)
**And** a shared admin header is present on every admin page with nav links to: Products | Referrals | Settings | Logout
**And** the header and dashboard are usable on mobile (min 375px width — no horizontal scroll)
**And** the admin interface uses a simple, clean CSS (no framework required — minimal inline or `admin.css`)

---

## Epic 3: Admin Product Management

**Goal:** Full CRUD for products — create, read, update, delete, with image upload and size management.

### Story 3.1: Product List View

As the seller,
I want to see all products listed in the admin panel,
So that I can manage my inventory at a glance.

**Acceptance Criteria:**

**Given** the seller is on `/admin/products.php`
**Then** all products are listed (product name, primary photo thumbnail, price, category, status: available/sold-out)
**And** each row has action buttons: Edit | Quick Stock Update | Delete
**And** list is sorted by most recently updated (updated_at DESC)
**And** page shows total product count
**And** "Add New Product" button is prominently shown

---

### Story 3.2: Add New Product Form

As the seller,
I want to add a new product via a form,
So that new stock appears on the storefront immediately after saving.

**Acceptance Criteria:**

**Given** the seller clicks "Add New Product"
**Then** a form is shown with required fields: Product Name, Category (dropdown), Gender (dropdown), Price (₦, integer), at least one Size (see Story 3.3)
**And** optional fields available: Brand, Original/Discount Price, Short Description, TikTok URL, Featured toggle
**And** photo upload accepts multiple JPEG/PNG/WebP files, validates MIME type, max 5MB each (Story 3.4)
**And** on submit: product inserted into DB, images saved to `/uploads/products/{id}/`, sizes saved to product_sizes
**And** the entire save is wrapped in a DB transaction — if image save fails, product insert is rolled back
**And** on success: redirect to product list with flash "Product added ✅"

---

### Story 3.3: Size Manager Component

As the seller,
I want to add multiple size options with stock quantities per product,
So that parents can see which sizes are available.

**Acceptance Criteria:**

**Given** the seller is on the Add/Edit product form
**When** they interact with the Size Manager section
**Then** they can add a size row: size label (text input e.g. "3-4Y", "S", "Medium") + stock qty (integer) + sold-out toggle
**And** multiple sizes can be added before saving
**And** removing a size from the form deletes it from the DB on save (edit mode)
**And** a size with stock_qty = 0 or is_sold_out = 1 renders as sold-out on the storefront
**And** the size manager works entirely with PHP + Alpine.js (no separate AJAX — sizes serialized and submitted with the main form)

---

### Story 3.4: Product Photo Upload

As the seller,
I want to upload multiple photos per product with the 4:5 ratio standard,
So that the storefront displays consistent, beautiful product imagery.

**Acceptance Criteria:**

**Given** the seller uploads photos on the product form
**When** files are submitted
**Then** PHP validates MIME type (not just extension) — only image/jpeg, image/png, image/webp accepted
**And** PHP validates each file ≤ 5MB — files exceeding limit are rejected with error message
**And** files are saved to `/public_html/uploads/products/{product_id}/` with timestamped names
**And** the first uploaded photo is stored with sort_order = 0 (primary photo)
**And** a warning note is shown: "Upload photos in 4:5 portrait format for best display results"
**And** if any file fails, the entire product save is rolled back (transaction)

---

### Story 3.5: Edit, Delete & Quick Stock Update

As the seller,
I want to edit existing products, delete them, and quickly update stock from the list view,
So that I can keep the catalogue accurate without navigating into each product.

**Acceptance Criteria:**

**Given** the seller clicks "Edit" on a product
**Then** a pre-populated form is shown with all current product data
**And** saving updates the product, sizes, and images in DB with updated_at = NOW()
**And** the seller can toggle is_available without opening the full edit form
**And** the seller can update per-size stock qty and sold-out status inline from the list (Quick Stock Update)
**And** clicking "Delete" shows a confirmation dialog: "Delete [Product Name]? This cannot be undone."
**And** confirming delete: removes product + cascades to product_images and product_sizes
**And** after delete: redirect to product list with flash "Product deleted"

---

## Epic 4: Admin Settings, Status & Referral Management

**Goal:** Seller configures store identity, manages online status, manages referral codes, and sets the referral discount.

### Story 4.1: Store Settings Page

As the seller,
I want a settings page to update store info and configure the referral discount,
So that the storefront and referral system use my correct details.

**Acceptance Criteria:**

**Given** the seller visits `/admin/settings.php`
**Then** a form is shown pre-populated from `seller_config` with fields: Store Name, Tagline, Intro Text, WhatsApp Number, Telegram Link, Delivery Info, Status Message, Referral Discount % (integer or blank)
**And** saving any field updates the matching row in `seller_config` → key/value pair
**And** "Referral Discount %" saved as NULL if left blank (feature off) or integer 1–100
**And** "Change Admin Password" section: Current Password + New Password + Confirm — verifies current hash before updating
**And** on save: flash "Settings saved ✅" — no page reload needed

---

### Story 4.2: Seller Online Status Toggle

As the seller,
I want to update my online status from the admin panel,
So that parents on the storefront see accurate availability.

**Acceptance Criteria:**

**Given** the seller is on any admin page (status toggle in header or settings)
**When** they tap the status toggle
**Then** status cycles: online → brb → offline → online
**And** the new status is saved to `seller_config` key `seller_status`
**And** the optional status message (e.g. "Back in 1 hour") is editable alongside the toggle
**And** the storefront reflects the new status within 60 seconds (via polling — see Epic 5)

---

### Story 4.3: Referral Code Management

As the seller,
I want to create referral codes for customers and record when codes are used,
So that I can track accumulative referrals and reward my top advocates.

**Acceptance Criteria:**

**Given** the seller visits `/admin/referrals.php`
**Then** a table shows all issued referral codes sorted by total_referrals DESC (top advocates first)
**And** each row shows: Code, Referrer Name, WA Number, Total Referrals, Status (Active/Inactive)
**And** "Generate New Code" form: Referrer Name + WA Number → PHP creates `BCK-[FIRSTNAME][3-digit-random]` unique code, inserts into referral_codes
**And** generated code is shown prominently after creation with copy prompt
**And** clicking a code opens its detail page showing all redemptions (buyer name, date, discount % applied)
**And** "Record Redemption" form: New Buyer Name + WA → inserts referral_uses row, increments referral_codes.total_referrals by 1, snapshots current seller_config.referral_discount_percent
**And** "Deactivate" sets status = inactive (code can no longer be actively promoted, but history preserved)

---

## Epic 5: Storefront Catalogue & Filters

**Goal:** The customer-facing product grid with filtering, seller status display, and ultra-lightweight performance.

### Story 5.1: API Layer — Products, Categories & Status

As a developer,
I want PHP API endpoints that return product data as JSON,
So that the Alpine.js storefront can dynamically load the catalogue.

**Acceptance Criteria:**

**Given** the API files exist in `/api/`
**When** `GET /api/products.php` is called
**Then** returns JSON array of all available products with: id, name, price, original_price, category_id, gender, brand, badges (new/sale/soldout), primary_image_url, sizes[]
**And** supports query params: `?category={id}`, `?gender={value}`, `?search={term}`
**And** `GET /api/categories.php` returns all categories (id, name, slug)
**And** `GET /api/status.php` returns `{"status": "online|brb|offline", "message": "...", "wa_number": "...", "telegram_link": "..."}`
**And** all responses include `Content-Type: application/json` header
**And** CORS headers set to allow same-origin requests only

---

### Story 5.2: Storefront Shell & Alpine.js Setup

As a developer,
I want the storefront `index.html` shell built with Alpine.js,
So that it renders correctly on first load with no build step required.

**Acceptance Criteria:**

**Given** `index.html` is deployed to Hostinger `public_html/`
**When** a parent loads the storefront on mobile (375px viewport)
**Then** Alpine.js loaded from CDN — total initial HTML+JS payload ≤ 50KB
**And** page title = "Bloom Corner Kiddies"
**And** meta description set for SEO
**And** Open Graph tags set (og:title, og:description, og:image)
**And** Google Analytics 4 gtag snippet present (with placeholder measurement ID)
**And** No horizontal scrollbar at 320px viewport width
**And** Time to First Contentful Paint < 2s on PageSpeed Insights mobile simulation

---

### Story 5.3: Homepage Hero & Trust Section

As a parent,
I want to see the seller's face, name, and online status when I arrive at the store,
So that I trust who I'm buying from before I browse.

**Acceptance Criteria:**

**Given** a parent lands on the homepage
**Then** a hero section displays: seller's profile photo (or placeholder), store name "Bloom Corner Kiddies", intro text (from seller_config.intro_text), seller status dot (🟢🟡🔴) with status message
**And** status dot loaded from `/api/status.php` on page load and updated every 60 seconds without page reload (Alpine.js setInterval)
**And** WhatsApp button (wa.me/2349049308656) and Telegram button (t.me/+2349049308656) both visible in hero
**And** all touch targets ≥ 44×44px
**And** hero section visible above-the-fold on 375px mobile without scrolling

---

### Story 5.4: Product Grid & Category Filters

As a parent,
I want to browse products in a filtered grid,
So that I can find items relevant to my child's gender, age, and category.

**Acceptance Criteria:**

**Given** products are loaded from `/api/products.php`
**When** the parent views the product grid
**Then** products displayed in 2-column grid, each card showing: 4:5 primary photo, product name, price (₦ integer, no decimals), badge (New/Sale/Sold Out)
**And** horizontally scrollable category filter tabs above grid (All Girls Boys Babies Unisex)
**And** gender filter row (All Girls Boys) stacked below category
**And** selecting a filter makes an API call with filter params and updates grid reactively (no page reload)
**And** "Sold Out" badge displayed on card, card still tappable (opens detail view)
**And** images use native `loading="lazy"` attribute

---

### Story 5.5: Shareable Product URLs & Navigation

As a parent,
I want product detail views to have unique URLs,
So that I can share a specific product link directly with others.

**Acceptance Criteria:**

**Given** a parent taps a product card
**Then** the URL updates to `/?product={id}` without a full page reload (Alpine.js handles routing)
**And** opening `/?product={id}` directly loads the storefront and immediately shows that product's detail view
**And** pressing the browser Back button returns the parent to the grid (not a blank page)
**And** the product URL works when copied and pasted into WhatsApp — recipient lands on the correct product

---

## Epic 6: Product Detail, WhatsApp & Telegram Handoff

**Goal:** The full product detail view and all commerce handoff logic — WhatsApp pre-filled messages, Telegram link, Save for Later, Notify Me.

### Story 6.1: Product Detail View

As a parent,
I want to see all product information on a detail view,
So that I have everything I need to make a confident purchase decision.

**Acceptance Criteria:**

**Given** a parent opens a product
**Then** the detail view shows: photo gallery (swipeable, 4:5 ratio, scroll-snap), product name, brand (if set), price in ₦ (integer), strike-through original price if on sale, short description, gender/age info, TikTok embed (if URL set)
**And** the size selector shows all sizes as tappable chips — available sizes tappable, sold-out sizes visually greyed with strikethrough (not hidden)
**And** delivery information section shows seller_config.delivery_info
**And** a size guide accordion is collapsible, contains sizing information and a WhatsApp "Ask about sizing" link
**And** back button/gesture returns parent to the grid at the same scroll position

---

### Story 6.2: WhatsApp Order & Enquiry Buttons

As a parent,
I want WhatsApp order and enquiry buttons with pre-filled messages,
So that I reach the seller instantly with my product details — no retyping.

**Acceptance Criteria:**

**Given** a parent is on a product detail view
**When** a size is selected and they tap "🛒 Order via WhatsApp"
**Then** WhatsApp opens to wa.me/2349049308656 with pre-filled message:
  "Hi! 👋 I'd like to order: *[Name]* Brand: [if set] Size: [size] Price: ₦[price] [if sale: was ₦original] Please confirm availability. Thank you! 🙏"
**When** no size is selected and they tap the button
**Then** the enquiry template is used instead (product name, price, category, child age prompt)
**And** if a referral code prompt is shown: message includes "[Referral code: ___________]" line
**And** message is URL-encoded via encodeURIComponent (client-side JS) — emoji preserved
**And** button is green, prominently placed, touch target ≥ 44×44px

---

### Story 6.3: Telegram, Save for Later & Notify Me Buttons

As a parent,
I want alternative action buttons — Telegram link, Save for Later, and Notify Me on sold-out items,
So that I have flexible options to engage with the seller or bookmark items.

**Acceptance Criteria:**

**Given** a parent is on a product detail view
**Then** "✈️ Order via Telegram" button links to `https://t.me/+2349049308656` (opens Telegram app on mobile, Telegram Web on desktop)
**And** "🔖 Save for Later" button opens `https://wa.me/` (no number = opens WA saved messages) with pre-filled: product name + ₦price + shareable product URL
**And** if WhatsApp is not detected (navigator.userAgent check), "Save for Later" falls back to `navigator.clipboard.writeText(productUrl)` with toast "Link copied! 📋"
**And** if ALL sizes are sold out: Order buttons replaced with "🔔 Notify Me" which opens WA with Notify Me template (product name, price)
**And** if only SOME sizes are sold out: sold-out sizes are greyed, other sizes still orderable normally

---

### Story 6.4: Seller Status on Storefront

As a parent,
I want to see the seller's online status so I know when to expect a reply,
So that I can set accurate expectations before messaging.

**Acceptance Criteria:**

**Given** `seller_config.seller_status = "online"`
**Then** green dot 🟢 + "Online now" shows in hero and on product detail
**Given** `seller_config.seller_status = "brb"`
**Then** yellow dot 🟡 + status_message content displayed
**Given** `seller_config.seller_status = "offline"`
**Then** red dot 🔴 + "Away — replies within a few hours" displayed
**And** the status updates reactively every 60 seconds via `/api/status.php` polling without page reload
**And** status dot visible without scrolling on product detail (in sticky header or below product name)

---

### Story 6.5: WhatsApp Business Configuration (Seller Setup)

As the seller,
I want my WhatsApp Business profile and quick replies configured,
So that every incoming order message arrives in a professional, organized context.

**Acceptance Criteria** *(manual setup — verified via WA Business App, not code):*

**Given** the seller opens WhatsApp Business App settings
**Then** Business Name = "Bloom Corner Kiddies", Category = "Kids Clothing", description + business hours set
**And** Away message configured: "Hi! Thanks for reaching out to Bloom Corner Kiddies 💛 I'm not available right now but I reply to every message. I'll be back shortly!"
**And** Quick Replies saved: /available, /sizes, /delivery, /payment (Bank transfer), /thanks
**And** Customer Labels created: New Customer, Repeat Buyer, VIP, Awaiting Payment, Order Dispatched

---

## Epic 7: Telegram Bot (Standalone Order Channel)

**Goal:** A PHP webhook-based Telegram bot that guides parents through a 7-step order intake conversation and notifies the seller.

### Story 7.1: Telegram Bot Setup & Webhook Registration

As a developer,
I want the Telegram bot created, webhook registered, and PHP handler deployed,
So that Telegram messages are delivered to the storefront server.

**Acceptance Criteria:**

**Given** the seller has created a bot via @BotFather and received a token
**When** the token is stored in `config.php` as `TELEGRAM_BOT_TOKEN`
**Then** `telegram-webhook.php` is deployed to `public_html/`
**And** the Telegram setWebhook API call is made: `https://api.telegram.org/bot{TOKEN}/setWebhook?url=https://yourdomain.com/telegram-webhook.php`
**And** webhook registration returns `{"ok":true}` (verified via Telegram API)
**And** `telegram-webhook.php` responds with HTTP 200 to every POST within 5 seconds (Telegram requirement)
**And** the file ignores non-POST requests gracefully (returns 200, no error)

---

### Story 7.2: Bot Order Intake Flow (Steps 1–7)

As a parent,
I want the Telegram bot to guide me through ordering step-by-step,
So that I can place an order without needing to type a full message.

**Acceptance Criteria:**

**Given** a parent messages the Telegram bot
**When** Step 1 is triggered (any new message or /start)
**Then** bot replies: "Hi! 👋 Welcome to Bloom Corner Kiddies. What would you like to order?"
**And** the bot progresses through all 7 steps sequentially: Welcome → Size → Name → Address → Gift Check → Gift Message (if yes) → Confirmation Summary
**And** conversation state (step, product context, collected data) stored in `bot_sessions` table keyed by `chat_id`
**And** the confirmation summary step shows all collected info and asks "Confirm? [YES / CHANGE]"
**And** "Step 7 reached" is the definition of a completed bot conversation (counted in success metrics)

---

### Story 7.3: Bot Order Completion & Seller Notification

As the seller,
I want to receive a Telegram notification with a complete order summary when a bot order is confirmed,
So that I can process the order immediately without asking for repeated details.

**Acceptance Criteria:**

**Given** a parent confirms their order in Step 7
**When** bot receives "YES"
**Then** bot replies: "✅ Order received! I'll be in touch shortly with payment details. 💛"
**And** seller receives a Telegram DM (or channel message) with formatted order summary:
  📦 Product name | 📏 Size | 💰 ₦Price | 👤 Buyer name | 📍 Address | 🎁 Gift flag + message (if applicable)
**And** bot conversation is idempotent — if Telegram retries the same webhook POST, the order is NOT duplicated (check for existing completed session in `bot_sessions`)
**And** the bot_sessions row is cleared or marked `step = 'complete'` after successful notification

---

## Epic 8: Growth, Referral Tracking & Marketing Tools

**Goal:** Client-side referral code prompt on storefront, UTM links, GA4 integration, and all growth marketing tools.

### Story 8.1: Referral Code Prompt on Storefront

As a parent,
I want to see a referral code prompt when browsing,
So that I know I can get a discount if I have a friend's code.

**Acceptance Criteria:**

**Given** a parent is browsing the storefront (homepage or product pages)
**When** `seller_config.referral_discount_percent` is NOT NULL (referral feature active)
**Then** a small banner or inline note shows: "Have a referral code? Add it to your WhatsApp message for a discount! 💛"
**And** the WhatsApp pre-fill messages (Order and Enquiry) include an optional line: "[Referral code: ___________]"
**When** `seller_config.referral_discount_percent` IS NULL
**Then** no referral prompt is shown (feature silently disabled)

---

### Story 8.2: Google Analytics 4 & UTM Link Implementation

As the seller,
I want Google Analytics 4 tracking and UTM links on all broadcast content,
So that I can measure which marketing activities drive storefront visits.

**Acceptance Criteria:**

**Given** the GA4 gtag.js snippet is in `index.html` `<head>`
**When** a parent visits any storefront page
**Then** a pageview event is recorded in GA4 (verified in GA4 Realtime reports)
**And** UTM parameters in the URL are captured by GA4 automatically (source, medium, campaign)
**And** a master UTM link reference document is created (Google Doc or Markdown file) for the seller with pre-built UTM links for: WA Broadcast, Instagram Bio, TikTok Bio, Parent WA Group sharing

---

### Story 8.3: Post-Purchase Referral Code Flow (Seller Process)

As the seller,
I want a defined process for issuing referral codes after first purchases,
So that I consistently grow my referral network.

**Acceptance Criteria** *(process + admin tool verification — not pure code):*

**Given** a new customer completes their first order
**When** the seller processes the order
**Then** seller creates a referral code in admin `/admin/referrals.php` using the buyer's first name
**And** generated code (e.g. BCK-AMAKA042) is displayed for copy
**And** seller sends the post-purchase WA message template (§6.1 in PRD-004) to the buyer including their code
**And** when a new buyer later mentions the code, seller records the redemption in admin — total_referrals increments
**And** the referral leaderboard in admin shows top advocates sorted by total_referrals DESC

---

### Story 8.4: CRM Google Sheet Setup & Marketing Templates

As the seller,
I want a configured CRM Google Sheet and a library of ready-to-use WA broadcast templates,
So that I can execute the marketing strategy from Day 1 without building anything new.

**Acceptance Criteria** *(deliverables — documents and templates, not code):*

**Given** the store launches
**Then** a Google Sheets CRM template exists with columns: Parent Name | WA Number | Child Name | Child Age | Last Size | Last Product | Last Order Date | Next Follow-Up (=EDATE(G,3)) | VIP? | Referral Code | Has Referred (count) | Notes
**And** conditional formatting: Red = overdue follow-up, Yellow = this week, Green = VIP rows
**And** a WA Template Library document contains ready-to-send messages for: Post-Purchase (with referral code), 3-Month Size-Up, Birthday, VIP First Look, New Stock Broadcast, Flash Sale
**And** a School Calendar broadcast schedule document lists the 7 annual demand peaks (Jan resumption, May resumption, Sep resumption, Christmas, Eid, Easter, Children's Day May 27)

---

*epics.md v1.0 — Bloom Corner Kiddies — 8 Epics | 26 Stories | Ready for Sprint Planning*
