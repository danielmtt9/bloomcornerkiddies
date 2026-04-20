---
stepsCompleted: [step-01-init, step-02-discovery, step-03-goals, step-04-features, step-05-requirements, step-06-acceptance]
inputDocuments:
  - _bmad-output/brainstorming/brainstorming-session-2026-04-19-2357.md
workflowType: prd
prdId: PRD-003
version: 1.1
status: draft
date: 2026-04-20
author: Danielaroko
---

# PRD-003: Product Admin Panel
## Kids Clothes Conversational Commerce — bloomrocxx

**Author:** Danielaroko
**Date:** 2026-04-20
**Version:** 1.1
**Status:** Draft

---

## 1. Overview

### 1.1 Product Summary

The Admin Panel is a password-protected web interface accessible at `/admin` on the same Hostinger domain. It allows the seller to add, edit, and remove products; manage per-size stock; upload product photos; toggle product and size availability; and control the seller's online status — all without touching code or a database GUI.

The admin panel is server-rendered PHP. No JavaScript framework. Designed to work reliably on the seller's phone at any hour.

### 1.2 Design Philosophy

> "The admin panel doesn't need to be beautiful. It needs to be **bulletproof on a phone at 10pm**."

- Plain HTML forms — no SPA, no React
- PHP session authentication — no JWT, no OAuth
- Form submit → page refresh — no AJAX required for MVP
- Everything works on low-end Android browsers

### 1.3 Target User

**The Seller (Danielaroko):** A single operator managing stock, photos, pricing, and availability daily. Needs to update products quickly after sales, add new stock from phone, and toggle online/offline without developer help.

---

## 2. Goals & Success Metrics

| Goal | Metric | Target |
|------|--------|--------|
| Product update speed | Time to mark a size as sold-out from phone | < 60 seconds |
| New product creation | Time to add a complete new product | < 5 minutes |
| Admin accessibility | Works on Chrome Android, low-end device | 100% functional |
| Security | No unauthorized access to admin routes | 0 breaches |

---

## 3. User Stories

### 3.1 Authentication

| ID | As a... | I want to... | So that... | Priority |
|----|---------|--------------|------------|----------|
| US-50 | Seller | Log in to the admin panel with a password | Only I can manage products | P0 |
| US-51 | Seller | Stay logged in for a session | I don't re-enter password every page | P0 |
| US-52 | Seller | Log out explicitly | My session is secured if I use a shared device | P0 |

### 3.2 Product Management

| ID | As a... | I want to... | So that... | Priority |
|----|---------|--------------|------------|----------|
| US-53 | Seller | See a list of all my products in admin | I get an overview of my catalogue | P0 |
| US-54 | Seller | Add a new product with all required fields | New stock appears on the storefront | P0 |
| US-55 | Seller | Upload multiple photos for a product | Parents see the item from multiple angles | P0 |
| US-56 | Seller | Re-order photos by drag or arrows | The best photo shows as the thumbnail | P1 |
| US-57 | Seller | Add sizes and set stock per size | Availability is tracked accurately | P0 |
| US-58 | Seller | Add a sale/discount price to any product | Parents see the discount automatically | P0 |
| US-59 | Seller | Edit any existing product | I correct mistakes or update prices | P0 |
| US-60 | Seller | Delete a product permanently | Discontinued items are removed | P0 |
| US-61 | Seller | Toggle a product as Hidden (available=0) | I hide it without deleting | P0 |
| US-62 | Seller | Add optional fields (material, brand, TikTok URL, season tag) | Storefront shows richer product info | P1 |
| US-63 | Seller | See stock count per size on the product list | I immediately see what's running low | P1 |

### 3.3 Inventory Management

| ID | As a... | I want to... | So that... | Priority |
|----|---------|--------------|------------|----------|
| US-64 | Seller | Update stock count for a specific size | Stock is accurate after a sale | P0 |
| US-65 | Seller | Set stock to 0 to mark a size as sold out | Sold-out badge appears automatically | P0 |
| US-66 | Seller | Add a new size to an existing product | I expand available options | P1 |
| US-67 | Seller | Remove a size from a product | I clean up sizes that are never restocked | P1 |

### 3.4 Seller Status

| ID | As a... | I want to... | So that... | Priority |
|----|---------|--------------|------------|----------|
| US-68 | Seller | Toggle my online status (Online/BRB/Offline) | Parents see my actual availability | P0 |
| US-69 | Seller | Set a custom status message | Parents know when I'll reply | P1 |

### 3.5 Store Settings

| ID | As a... | I want to... | So that... | Priority |
|----|---------|--------------|------------|----------|
| US-70 | Seller | Update store name, tagline, WA number | My store info stays current | P1 |
| US-71 | Seller | Update delivery information text | Parents always see accurate delivery costs | P1 |
| US-72 | Seller | Change admin password | I maintain security | P1 |

---

## 4. Functional Requirements

### 4.1 Authentication

**FR-40 — Login Page (`/admin/login.php`)**
- Plain HTML form: password field + submit button
- POSTs to `/admin/login.php`
- PHP compares submitted password against `ADMIN_PASSWORD_HASH` in `config.php` (bcrypt hash)
- On success: creates PHP session (`$_SESSION['authenticated'] = true`), redirects to `/admin/`
- On failure: shows "Incorrect password" error, does NOT reveal whether user exists
- No username field — single-operator system, password-only
- No rate limiting required for MVP (add in future if needed)

**FR-41 — Session Guard (All Admin Pages)**
- Every admin PHP file begins with:
```php
session_start();
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header('Location: /admin/login.php');
    exit;
}
```
- Session cookie: `HttpOnly`, `Secure` (Hostinger enforces HTTPS), `SameSite=Strict`
- Session expires after 8 hours of inactivity

**FR-42 — Logout (`/admin/logout.php`)**
- Destroys PHP session
- Redirects to `/admin/login.php`
- Link in admin header on every page

**FR-43 — Password Storage**
- Admin password stored using a **one-way cryptographic hash** with a minimum cost factor of 10 (bcrypt-equivalent hardness)
- Plain-text password is never stored or logged anywhere
- `config.php` stores only the hash value, not the original password
- `config.php` stored OUTSIDE `public_html/` (`../config.php`)
- Changing password requires generating a new hash and updating `config.php` (P1: admin settings page to automate this)

### 4.2 Admin Navigation

**FR-44 — Admin Header (All Pages)**
Persistent header on all admin pages:
```
[🏠 Products] [⚙️ Settings] [🟢 Online ▼] [👋 Logout]
```
- Status quick-toggle in header: tap to cycle Online → BRB → Offline → Online
- Active page highlighted in nav

**FR-45 — Admin Dashboard (`/admin/index.php`)**
- Table of all products (including hidden ones)
- Columns: Thumbnail, Name, Price, Category, Gender, Sizes (with stock counts), Status (Active/Hidden), Actions (Edit, Delete)
- Default sort: newest first
- Search/filter by name (PHP-side) — P1
- Quick action: click stock number to edit inline — P1

### 4.3 Add Product (`/admin/add.php`)

**FR-46 — Product Form — Required Fields**

| Field | Input Type | Validation |
|-------|-----------|-----------|
| Product Name | `<input type="text">` | Required, max 255 chars |
| Price (₦) | `<input type="number" min="1">` | Required, positive integer |
| Category | `<select>` (from categories table) | Required |
| Gender | Radio: Girls / Boys / Unisex | Required |
| Photos | `<input type="file" multiple accept="image/*">` | Required, at least 1 |
| Sizes + Stock | Custom Size Manager (see FR-48) | Required, at least 1 size with stock ≥ 0 |

**FR-47 — Product Form — Optional Fields**

| Field | Input Type | Notes |
|-------|-----------|-------|
| Original Price (₦) | `<input type="number">` | Leave blank = no sale. If filled, shows strikethrough on storefront |
| Description | `<textarea rows="3">` | Short paragraph |
| Material | `<input type="text">` | e.g. "100% Cotton" |
| Brand Name | `<input type="text">` | e.g. "Carter's" |
| TikTok URL | `<input type="url">` | Validates as URL |
| Season/Occasion Tag | `<input type="text">` | e.g. "Eid 2025" |

**FR-48 — Size Manager Component**

Within the product form, a dedicated Size Manager section:

**Quick-Add Size Buttons:**
```
Age Sizes: [0-3M] [3-6M] [6-12M] [1Y] [2Y] [3-4Y] [4-5Y] [5-6Y] [6-7Y] [7-8Y] [8-9Y] [9-10Y] [10-12Y]
Number Sizes: [2] [3] [4] [5] [6] [7] [8] [9] [10] [11] [12] [13] [14]
Custom: [___________] [+ Add]
```

- Clicking a size button adds a row to the size table below
- Clicking an already-added size button removes it
- Custom: type label + click Add

**Size Table (dynamically built):**
| Size | Stock Count | Remove |
|------|-------------|--------|
| 3-4Y | `<input type="number" min="0" value="1">` | [✕] |
| 4-5Y | `<input type="number" min="0" value="3">` | [✕] |

- Stock count input: whole number ≥ 0
- Stock = 0 is valid (marks that size as sold out from the start)
- Sorting: sizes appear in order added; reordering P2

**FR-49 — Photo Upload**
- `<input type="file" multiple accept="image/jpeg,image/png,image/webp">`
- Multiple files selectable in one dialog
- PHP `move_uploaded_file()` saves to `/public_html/uploads/products/{product_id}/`
- All uploaded images must conform to **4:5 portrait aspect ratio** — PHP crops or admin is warned to upload 4:5 photos; storefront enforces consistent display (see PRD-001 FR-08)
- Filename sanitized and timestamped to avoid conflicts
- First uploaded photo = primary (`sort_order = 0`)
- PHP validates: file type via MIME (not extension), file size ≤ 5MB per image
- If upload fails, product save is rolled back (transaction)

**FR-50 — Form Submit & DB Persistence**
- Form POST to `/admin/add.php`
- PHP wraps entire insert in MariaDB transaction:
  1. INSERT into `products`
  2. For each uploaded photo: move file + INSERT into `product_images`
  3. For each size row: INSERT into `product_sizes`
  4. COMMIT or ROLLBACK on any failure
- On success: redirect to `/admin/` with flash message "Product added! ✅"
- On failure: redisplay form with error message, preserve entered values

### 4.4 Edit Product (`/admin/edit.php?id={id}`)

**FR-51 — Pre-populated Edit Form**
- All fields from FR-46 and FR-47 pre-filled with existing product data
- Existing photos shown as thumbnails with individual "Remove" buttons
- New photos can be added (appended to existing)
- Existing sizes shown in size table with current stock counts
- New sizes can be added; existing sizes can have stock updated or be removed

**FR-52 — Available Toggle**
- Checkbox or toggle: "Show on storefront"
- Unchecked = `available = 0` — product completely hidden from storefront
- Default: checked (ON) for all new products

**FR-53 — Save Changes**
- POST to `/admin/edit.php`
- PHP updates `products` record, handles image changes, handles size changes
- Photo removal: deletes file from filesystem + removes DB record
- On success: redirect to `/admin/` with flash "Product updated! ✅"

### 4.5 Delete Product (`/admin/delete.php?id={id}`)

**FR-54 — Delete Confirmation**
- Clicking "Delete" from product list shows a confirmation step
- "Are you sure you want to permanently delete '{product_name}'? This cannot be undone."
- Two buttons: [Yes, Delete] | [Cancel]

**FR-55 — Delete Cascade**
- On confirm: PHP deletes from `products` (CASCADE deletes `product_images` and `product_sizes` records)
- Also deletes all image files from `/uploads/products/{product_id}/`
- On success: redirect to `/admin/` with flash "Product deleted."

### 4.6 Quick Stock Update (`/admin/stock.php`)

**FR-56 — Rapid Stock Update**
- From the product list page, each size shows its current stock count as a clickable number
- Clicking opens a minimal modal/inline form: just the size label + stock count input + Save button
- MVP: full edit page for stock updates (FR-56 is P1 improvement)

### 4.7 Seller Status

**FR-57 — Status Toggle**
- Available in admin header on every page
- Cycles: 🟢 Online → 🟡 BRB → 🔴 Offline → 🟢 Online
- POST to `/admin/status.php` → updates `seller_config` table
- Also available as a dedicated panel in Settings page

**FR-58 — Status Message**
- Editable text field for custom status message
- e.g., "Back at 6pm 💛" or "Replies within 2 hours"
- Shown on storefront alongside the status dot

### 4.8 Store Settings (`/admin/settings.php`)

**FR-59 — Store Info Settings**

| Setting | Field | DB Key | Notes |
|---------|-------|--------|-------|
| Store Name | `<input>` | `store_name` | Shown in page title, hero section |
| Tagline | `<input>` | `tagline` | Seller intro line |
| Seller Intro Text | `<textarea>` | `intro_text` | Homepage hero paragraph |
| WhatsApp Number | `<input>` | `wa_number` | International format, no + (e.g. 2348012345678) |
| Telegram Username | `<input>` | `telegram_username` | Without @ prefix (e.g. myshopname) |
| Delivery Info | `<textarea>` | `delivery_info` | Free text shown on product pages |
| Status Message | `<input>` | `status_message` | Response time / availability note |

**FR-60 — Change Admin Password**
- Three fields: Current Password, New Password, Confirm New Password
- PHP verifies current password before accepting change
- Updates `ADMIN_PASSWORD_HASH` — P1 (MVP: manual file edit)

### 4.9 Referral Code Management (`/admin/referrals.php`)

**FR-61A — Referral Code List View**
- Table of all referral codes in the system
- Columns: Code, Referrer Name, WA Number, Total Referrals, Created Date, Status (Active/Inactive)
- Default sort: highest total_referrals first (top advocates at top)
- Quick action: click "Deactivate" to set status = inactive without deleting history

**FR-61B — Generate New Referral Code**
- Form: Referrer Name + WA Number fields
- Seller submits → PHP auto-generates unique code `BCK-[FIRSTNAME][3-digit-random]` (uppercased)
- Validates uniqueness against `referral_codes` table (regenerates on collision)
- On success: code displayed prominently for seller to copy and send to customer via WA
- Flash: "New referral code created: BCK-SARAH042 ✅ Copy and share with Sarah!"

**FR-61C — Record Referral Redemption**
- From referral code detail view: a "Record Redemption" button
- Mini-form: New Buyer Name + New Buyer WA Number fields
- PHP inserts row into `referral_uses`; increments `referral_codes.total_referrals` by 1
- Discount % snapshot: reads current `seller_config.referral_discount_percent` at time of insertion
- Flash: "Redemption recorded! BCK-SARAH042 now has {N} referrals 🎉"

**FR-61D — Referral Code Detail View**
- Clicking a referral code opens a detail page
- Shows: code info, all redemptions (buyer name, WA, date, discount % applied)
- Shows total accumulative referral count
- Seller can see milestone progress (e.g., "5 referrals = free item") based on configured thresholds

**FR-61E — Referral Discount Config (in Settings)**
- Settings page (FR-59) includes:
  - Field: "Referral Discount %" input (integer or blank)
  - Stored as `seller_config.referral_discount_percent`
  - Blank/NULL = referral system active for tracking only (no discount offered)
  - Any integer 1–100 = that % applied to new buyer’s order when seller records redemption

---

## 5. Non-Functional Requirements

### 5.1 Security

| Requirement | Implementation |
|-------------|---------------|
| Authentication | PHP session with bcrypt password |
| Config file | Stored outside `public_html/` |
| SQL injection | All DB queries use PDO prepared statements |
| XSS | All output escaped with `htmlspecialchars()` |
| CSRF | MVP: same-origin form POSTs (no token); P1: add CSRF token |
| Direct file access | `.htaccess` blocks direct access to `config.php` and `admin/` directories |
| Image upload | MIME type validation + size limit enforced server-side |

### 5.2 Usability on Mobile

- All form inputs minimum height 44px (touch-friendly)
- Font size minimum 16px in form fields (prevents iOS auto-zoom)
- Photo upload tested on Chrome Android
- Size manager quick-add buttons are large enough to tap (min 44×44px)
- Form works without JavaScript (plain PHP POST fallback)

### 5.3 Performance

- Admin pages do NOT need to be fast (seller-only, not customer-facing)
- No CDN, no caching required for admin routes
- Target: pages load within 2 seconds on average shared hosting load

### 5.4 Hosting Constraints

- All admin PHP files in `/public_html/admin/`
- `config.php` in `/{project_root}/config.php` (one level above `public_html/`)
- Image files stored in `/public_html/uploads/products/{product_id}/`
- No Composer dependencies required for admin MVP (pure PHP)
- `.htaccess` restricts access: admin requires active session cookie

---

## 6. Admin File Structure

```
/ (project root, ABOVE public_html)
└── config.php                  ← DB credentials, admin password hash, WA/TG tokens

public_html/
├── admin/
│   ├── index.php               ← Product list dashboard
│   ├── login.php               ← Login form + handler
│   ├── logout.php              ← Session destroy
│   ├── add.php                 ← Add product form + handler
│   ├── edit.php                ← Edit product form + handler
│   ├── delete.php              ← Delete handler + confirmation
│   ├── stock.php               ← Quick stock update handler (P1)
│   ├── status.php              ← Seller status update handler
│   └── settings.php            ← Store-wide settings
└── uploads/
    └── products/
        └── {product_id}/
            ├── img-0.jpg
            └── img-1.jpg
```

---

## 7. Acceptance Criteria

### AC-017: Admin Login

```
GIVEN the seller navigates to /admin/
WHEN not authenticated
THEN they are redirected to /admin/login.php

GIVEN the seller enters the correct password
WHEN they submit the login form
THEN they are redirected to /admin/ and see the product dashboard
AND the session persists for subsequent page loads

GIVEN the seller enters an incorrect password
THEN the page reloads with "Incorrect password" error
AND no session is created
```

### AC-018: Add Product — Required Fields

```
GIVEN the seller is on /admin/add.php
WHEN they submit the form WITHOUT a photo
THEN the form rejects with "At least 1 photo required"
AND no product is created in the database

WHEN they submit the form WITHOUT any sizes
THEN the form rejects with "At least 1 size required"
AND no product is created

WHEN all required fields are filled and at least 1 photo + 1 size provided
THEN the product is created in the DB
AND the photo is saved to /uploads/products/{new_id}/
AND the seller is redirected to /admin/ with a success message
AND the product appears on the storefront within 60 seconds (cache expiry)
```

### AC-019: Sale Price Display

```
GIVEN a product is saved with Price=₦5,500 and Original Price=₦8,000
THEN on the storefront product card, the price shows as "~~₦8,000~~ ₦5,500"
AND a "SALE" badge appears on the product photo

GIVEN a product is saved with Price=₦5,500 and NO Original Price
THEN on the storefront the price shows as "₦5,500" only
AND no SALE badge appears
```

### AC-020: Size Sold-Out Toggle

```
GIVEN the seller edits a product and sets "3-4Y" stock to 0
WHEN the edit is saved
THEN on the storefront, the "3-4Y" chip is greyed out with "Sold Out"
AND other sizes with stock > 0 remain selectable

GIVEN ALL sizes are set to 0
THEN the product card shows "SOLD OUT" badge
AND order buttons are replaced with "Notify Me"
```

### AC-021: Product Hidden

```
GIVEN the seller unchecks "Show on storefront" for a product
THEN that product does NOT appear in /api/products.php response
AND the product does NOT appear on the storefront
AND the product DOES still appear in the admin product list (with "Hidden" badge)
```

### AC-022: Image Upload Security

```
GIVEN an attacker attempts to upload a PHP file as a product image
THEN the server rejects the file with a MIME type validation error
AND no file is saved to the server

GIVEN an attacker attempts to upload an image larger than 5MB
THEN the server rejects the file with a size error
```

### AC-023: Seller Status Toggle

```
GIVEN the seller taps the status toggle in admin header → sets to "BRB"
THEN seller_config.seller_status updates to "brb" in MariaDB
AND within 60 seconds, the storefront status dot changes to 🟡
```

---

## 8. Open Questions

| # | Question | Impact | Priority |
|---|----------|--------|----------|
| OQ-14 | Should admin show a "low stock" warning for sizes with stock ≤ 3? | Dashboard usability | Low |
| OQ-15 | Should photos be auto-resized/compressed on upload (requires PHP GD/Imagick)? | Image file sizes on Hostinger | Medium |
| OQ-16 | Should there be a "Duplicate Product" feature to quickly create variants? | Admin efficiency | Low |
| OQ-17 | Does Hostinger shared hosting have GD library enabled? (for image resize) | Image optimization | Medium |
| OQ-18 | Should admin be accessible from a mobile shortcut / PWA? | Seller convenience | Low |

---

## 9. Implementation Priority (MoSCoW)

### Must Have (MVP)
- Login/logout/session auth
- Product list dashboard
- Add product (required fields, photos, sizes)
- Edit/Delete product
- Toggle available/hidden
- Upload photos to filesystem
- Size manager with stock counts
- Seller status toggle

### Should Have (Sprint 2)
- Sale/discount price field
- All optional fields (brand, material, TikTok, season)
- Store settings page (WA number, delivery info, tagline)
- Quick inline stock update
- Photo deletion and reordering

### Could Have (Future)
- CSRF protection tokens
- Admin password change via UI
- Low stock warning indicators
- Product search/filter in admin
- Bulk status update (select multiple → hide all)

### Won't Have (This Version)
- Multi-admin user accounts
- Role-based access control
- Audit log of changes
- Product import via CSV

---

## 10. Dependencies

| Dependency | Type | Notes |
|------------|------|-------|
| PRD-001 (Storefront) | Downstream | All products added here appear on storefront |
| MariaDB + schema | Infrastructure | Products/sizes/images tables must exist |
| PHP on Hostinger | Infrastructure | All admin pages are PHP |
| Hostinger filesystem | Infrastructure | `/uploads/products/` must be writable |
| `config.php` | Config | Admin password hash + DB credentials |

---

*PRD-003 of 4 — Next: PRD-004 Marketing & Retention System*
