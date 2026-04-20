---
workflowType: master-backlog
version: 2.1
date: 2026-04-20
status: approved_for_developer_handoff
supersedes:
  - _bmad-output/planning-artifacts/epics.md v1.0
inputDocuments:
  - _bmad-output/planning-artifacts/prd-001-storefront.md
  - _bmad-output/planning-artifacts/prd-002-handoff.md
  - _bmad-output/planning-artifacts/prd-003-admin.md
  - _bmad-output/planning-artifacts/prd-004-marketing.md
  - _bmad-output/planning-artifacts/prd-index.md
decisions:
  - product_category_model: multi_category
  - telegram_storefront_link: personal_account
  - telegram_bot_scope: deferred_from_mvp
  - business_copy_placeholders_allowed: true
---

# Bloom Corner Kiddies — Master Backlog

## Overview

This document is the implementation handoff source of truth for **Bloom Corner Kiddies**.
It replaces the previous mixed-purpose epic breakdown with a lane-based backlog so the next developer can tell, at a glance, which work is:

- ready for immediate implementation
- valid engineering but intentionally deferred
- operational, content, or setup work that should not block software delivery

**Store:** Bloom Corner Kiddies  
**Stack:** PHP + MariaDB + Alpine.js  
**Hosting:** Hostinger Shared  
**Implementation Principle:** build the software MVP first, keep launch-ops work separate

---

## Locked Decisions

- Products are **multi-category**
- Storefront Telegram button links to the seller's **personal Telegram account**
- Telegram bot engineering is **deferred from the first developer handoff**
- Placeholder business copy is acceptable for development if config keys and defaults are defined

---

## Lane Model

### Lane A — Developer MVP Backlog

Immediate implementation scope. Every story in Lane A must be executable by a developer without needing additional product decisions.

### Lane B — Deferred Engineering Backlog

Real engineering work, but intentionally postponed or blocked by unresolved product/operational choices. These stories should not be included in the first MVP build commitment.

### Lane C — Operational / Content Backlog

Manual setup, marketing operations, templates, docs, and design assets. These are part of launch preparation, not software implementation.

---

## Shared Interface Decisions

### Data Model

- Products may belong to **multiple categories**
- Category assignment uses a join table:
  - `categories`
  - `product_categories`
- `products.category_id` is **not used**

### API Shape

- Product payloads return `categories[]`, not `category_id`
- Category filters match a product if it belongs to the selected category
- Status endpoint remains separate at `/api/status.php`

### seller_config Keys

The MVP backlog assumes these keys exist and are seeded:

- `store_name`
- `tagline`
- `intro_text`
- `wa_number`
- `telegram_link`
- `delivery_info`
- `status_message`
- `payment_info`
- `seller_status`
- `referral_discount_percent`

Placeholder defaults are allowed for:

- `tagline`
- `intro_text`
- `delivery_info`
- `status_message`

---

## Backlog Summary

| Lane | Epic | Scope | Execution Status |
|------|------|-------|------------------|
| A | A0 | Infrastructure & Configuration | Ready |
| A | A1 | Database & Data Model | Ready |
| A | A2 | Admin Auth & Shell | Ready |
| A | A3 | Admin Product & Inventory Management | Ready |
| A | A4 | Admin Settings & Referral Admin | Ready |
| A | A5 | Storefront Catalogue & Filters | Ready |
| A | A6 | Product Detail & Commerce Handoff | Ready |
| B | B1 | Deferred Telegram Bot | Deferred |
| C | C1 | Operational Setup & Marketing Assets | Non-dev |

---

## Lane A Execution Order

Lane A is approved for implementation in the exact order below. The developer should not choose a different starting point unless a blocking technical issue forces resequencing.

### First Story To Start

**Start with `Story A0.1 — Environment & Config Bootstrap`.**

Reason:
- every other MVP story depends on shared configuration or DB access
- it establishes the config contract the rest of the codebase will read from
- it reduces rework in migrations, admin auth, API endpoints, and storefront wiring

### Sprint Sequence

| Sprint | Goal | Stories | Exit Criteria |
|--------|------|---------|---------------|
| Sprint 1 | Foundation and DB readiness | A0.1, A0.2, A0.3, A1.1, A1.2, A1.3 | repo/config/deploy are stable; schema and seeds run cleanly; DB helpers are reusable |
| Sprint 2 | Admin access and shell | A2.1, A2.2, A2.3 | seller can log in, log out, and navigate a working admin shell on mobile |
| Sprint 3 | Product management core | A3.1, A3.2, A3.3, A3.4, A3.5 | seller can create, edit, stock-manage, hide, and delete multi-category products with photos |
| Sprint 4 | Settings and referral admin | A4.1, A4.2, A4.3, A4.4 | store settings, seller status, referral admin, and password change all work end-to-end |
| Sprint 5 | Storefront browse experience | A5.1, A5.2, A5.3, A5.4, A5.5 | storefront can load products/categories/status, render trust hero, filter catalogue, and open shareable product URLs |
| Sprint 6 | Commerce handoff | A6.1, A6.2, A6.3, A6.4, A6.5 | product detail, WhatsApp handoff, Telegram personal link, save-for-later, notify-me, and analytics-ready routing all work |

### Story-by-Story Order

1. A0.1 — Environment & Config Bootstrap
2. A0.3 — Shared PHP Bootstrap Helpers
3. A0.2 — Repository Safety & Deployment Pipeline
4. A1.1 — Core Commerce Tables
5. A1.2 — Store Settings & Referral Tables
6. A1.3 — Migration & Seed Execution Path
7. A2.1 — Admin Login
8. A2.2 — Session Guard & Logout
9. A2.3 — Admin Layout & Dashboard
10. A3.1 — Product List View
11. A3.2 — Product Create/Edit Form
12. A3.3 — Size Manager & Quick Stock Updates
13. A3.4 — Product Image Uploads
14. A3.5 — Edit, Hide, Delete
15. A4.1 — Store Settings Page
16. A4.2 — Seller Status Control
17. A4.3 — Referral Code Admin
18. A4.4 — Admin Password Change
19. A5.1 — Storefront Product & Category API
20. A5.2 — Storefront Shell
21. A5.3 — Hero & Trust Section
22. A5.4 — Product Grid & Filters
23. A5.5 — Shareable Product Routing
24. A6.1 — Product Detail View
25. A6.2 — WhatsApp Order & Enquiry Handoff
26. A6.3 — Telegram Link, Save for Later, Notify Me
27. A6.4 — Storefront Seller Status
28. A6.5 — GA4 Snippet & UTM-Compatible Link Handling

### Sequencing Rules

- Do not start any Lane B story before all Lane A sprint commitments are complete or explicitly re-scoped.
- Do not pull Lane C work into a development sprint as a substitute for blocked engineering work.
- Within a sprint, a developer may parallelize only when write scopes do not overlap and the dependency order above is still respected.
- Multi-category support is not optional MVP polish; it must be implemented at the schema, admin, API, and storefront layers in the same execution stream.
- Placeholder business copy is acceptable during Sprints 1–6, but missing config keys are not.

### MVP Completion Gate

Lane A MVP implementation is complete only when all of the following are true:

- the DB schema, seeds, and config contract are stable
- seller can fully manage products from admin without manual DB edits
- storefront can browse and deep-link products using multi-category data
- WhatsApp handoff works with prefilled product context
- Telegram remains a simple personal-contact link only
- no MVP flow depends on deferred bot engineering or operational launch tasks

---

# Lane A — Developer MVP Backlog

## Epic A0 — Infrastructure & Configuration

**Goal:** Establish the server-side project structure, secure configuration pattern, and deployment automation required for all MVP application work.

### Story A0.1 — Environment & Config Bootstrap

**Persona:** Developer  
**Goal:** set up the root-level environment/config pattern  
**Success Outcome:** secrets stay out of git and every PHP entrypoint can load shared configuration consistently

**Dependencies:** none

**Public Interface / Data Impact:**
- root `.env` contains all required runtime keys
- root `config.php` exposes `get_db()` and `get_config($key, $default = '')`

**Acceptance Criteria:**
- Given the Hostinger server root, then `.env` and `config.php` live one level above `public_html/`
- And `.env.example` and `config.php.example` are committed templates
- And `config.php` loads `.env` without Composer
- And `config.php` defines all required constants for DB, auth, Telegram placeholder token, and store settings access
- And browser requests to `.env` or `config.php` return 403 or 404 because these files are not in `public_html/`

**Done Means:**
- local and server environments use the same config contract
- no tracked file contains live secrets

### Story A0.2 — Repository Safety & Deployment Pipeline

**Persona:** Developer  
**Goal:** enforce safe repository defaults and automated deployment  
**Success Outcome:** production deploys from `main` without pushing secrets or uploads

**Dependencies:** A0.1

**Acceptance Criteria:**
- `.gitignore` excludes `.env`, `config.php`, and `public_html/uploads/`
- GitHub Actions deploys only `public_html/` contents to Hostinger on pushes to `main`
- rsync excludes `.env`, docs, planning artifacts, uploads, and CI metadata
- deployment supports manual trigger and single-flight concurrency

**Done Means:**
- production deployment is reproducible
- secrets and generated assets do not leak into the repo

### Story A0.3 — Shared PHP Bootstrap Helpers

**Persona:** Developer  
**Goal:** provide common includes/helpers used across admin, API, and storefront PHP files  
**Success Outcome:** application code does not duplicate config/bootstrap logic

**Dependencies:** A0.1

**Acceptance Criteria:**
- shared include(s) exist for session start, auth guard, JSON responses, and flash/session helpers where needed
- DB access flows through one PDO singleton
- application code can read `seller_config` through a shared helper rather than raw repeated queries

**Done Means:**
- new PHP pages can be added without re-implementing bootstrap logic

---

## Epic A1 — Database & Data Model

**Goal:** create the MariaDB schema and seed data needed by the storefront, admin panel, and referral logic.

### Story A1.1 — Core Commerce Tables

**Persona:** Developer  
**Goal:** create the core product, image, size, category, and product-category schema  
**Success Outcome:** products can be stored once and categorized many-to-many

**Dependencies:** A0.1

**Public Interface / Data Impact:**
- `products` no longer stores `category_id`
- category membership moves to `product_categories(product_id, category_id)`

**Acceptance Criteria:**
- The following tables exist with keys and constraints:
  - `products`
  - `product_images`
  - `product_sizes`
  - `categories`
  - `product_categories`
- `product_categories` enforces one row per `(product_id, category_id)`
- deleting a product cascades to `product_images`, `product_sizes`, and `product_categories`
- categories are seeded with the storefront category set used by filters

**Done Means:**
- a product can belong to one or many categories
- the developer does not have to revisit schema design later to support multi-category

### Story A1.2 — Store Settings & Referral Tables

**Persona:** Developer  
**Goal:** create seller config and referral tracking tables  
**Success Outcome:** admin settings and referral history can be persisted without hardcoded values

**Dependencies:** A1.1

**Acceptance Criteria:**
- `seller_config`, `referral_codes`, and `referral_uses` tables exist
- `seller_config` is seeded with the full key set:
  - `store_name`
  - `tagline`
  - `intro_text`
  - `wa_number`
  - `telegram_link`
  - `delivery_info`
  - `status_message`
  - `payment_info`
  - `seller_status`
  - `referral_discount_percent`
- placeholder defaults are inserted for open business-copy values
- `referral_codes.code` is unique
- `referral_uses` snapshots discount percent at redemption time

**Done Means:**
- later stories can read every required config value without inventing missing keys

### Story A1.3 — Migration & Seed Execution Path

**Persona:** Developer  
**Goal:** provide a repeatable migration/seed path for local and production setup  
**Success Outcome:** schema setup is deterministic and verifiable

**Dependencies:** A1.1, A1.2

**Acceptance Criteria:**
- migration script(s) create all MVP tables in correct dependency order
- seed script(s) insert baseline categories and seller_config rows safely
- local DB test script can confirm connectivity and list existing tables

**Done Means:**
- a fresh environment can be brought to MVP schema state with a single documented flow

---

## Epic A2 — Admin Auth & Shell

**Goal:** create a secure, mobile-usable admin shell that only the seller can access.

### Story A2.1 — Admin Login

**Persona:** Seller  
**Goal:** log in using one password  
**Success Outcome:** only the seller can enter the admin area

**Dependencies:** A0.1, A1.2

**Acceptance Criteria:**
- `/admin/login.php` displays a password-only login form
- submitted password is verified against `ADMIN_PASSWORD_HASH`
- successful login creates an authenticated PHP session and redirects to `/admin/index.php`
- failed login shows an error and creates no session
- login route works only over HTTPS in production

**Done Means:**
- unauthorized users cannot reach protected admin pages by URL alone

### Story A2.2 — Session Guard & Logout

**Persona:** Seller  
**Goal:** keep all admin routes protected by one shared guard  
**Success Outcome:** the admin area behaves as one secure application, not disconnected pages

**Dependencies:** A2.1

**Acceptance Criteria:**
- every protected admin page uses a shared session/auth guard
- unauthenticated requests redirect to `/admin/login.php`
- `/admin/logout.php` destroys the session and redirects to login
- back-button access after logout does not restore admin content

**Done Means:**
- auth behavior is consistent across all admin routes

### Story A2.3 — Admin Layout & Dashboard

**Persona:** Seller  
**Goal:** navigate the admin area from a phone without friction  
**Success Outcome:** the admin panel has a reusable shell and a useful landing page

**Dependencies:** A2.2, A1.1, A1.2

**Acceptance Criteria:**
- shared admin header appears on all admin pages
- nav links include Products, Referrals, Settings, and Logout
- dashboard shows:
  - total products
  - sold-out product count
  - total referral codes
  - recently updated products
- layout is usable on a 375px-wide mobile screen with no horizontal scrolling

**Done Means:**
- future admin pages can plug into one consistent shell

---

## Epic A3 — Admin Product & Inventory Management

**Goal:** let the seller create, update, organize, and remove products with multi-category assignment, size-level stock, and image uploads.

### Story A3.1 — Product List View

**Persona:** Seller  
**Goal:** view all products in one manageable list  
**Success Outcome:** the seller can quickly assess catalogue state and jump to the right action

**Dependencies:** A2.3, A1.1

**Acceptance Criteria:**
- `/admin/products.php` lists all products including hidden ones
- each row shows:
  - primary thumbnail
  - name
  - price
  - assigned categories
  - availability status
  - action buttons
- list is sorted by most recently updated first
- Add New Product action is prominent

**Done Means:**
- seller can reach edit, delete, and stock actions from one list page

### Story A3.2 — Product Create/Edit Form

**Persona:** Seller  
**Goal:** create and edit complete product records  
**Success Outcome:** product data entered in admin is sufficient to power storefront browsing and detail pages

**Dependencies:** A3.1, A1.1

**Public Interface / Data Impact:**
- admin form supports `categories[]`
- product save persists many-to-many category membership via `product_categories`

**Acceptance Criteria:**
- required fields include:
  - product name
  - gender
  - price
  - one or more categories
  - at least one size
  - at least one photo
- optional fields include brand, original price, description, TikTok URL, material, and season/occasion tag
- category input supports selecting multiple categories from the categories table
- edit form loads existing categories and preserves them on save
- product create/update runs inside a DB transaction when related records are written

**Done Means:**
- developer does not need a later schema/UI change to support multi-category products

### Story A3.3 — Size Manager & Quick Stock Updates

**Persona:** Seller  
**Goal:** manage size-level stock quickly  
**Success Outcome:** storefront availability reflects real stock without slow admin workflows

**Dependencies:** A3.2

**Acceptance Criteria:**
- add/edit form supports multiple size rows with quantity and sold-out state
- size rows can be added and removed before save
- product list offers a quick stock update path for size quantities and sold-out toggles
- storefront-ready sold-out state is derived from size stock and/or explicit sold-out flag

**Done Means:**
- seller can mark stock changes from phone in under a minute

### Story A3.4 — Product Image Uploads

**Persona:** Seller  
**Goal:** upload multiple product images safely  
**Success Outcome:** storefront can show consistent 4:5 images with one primary thumbnail

**Dependencies:** A3.2

**Acceptance Criteria:**
- photo upload accepts JPEG, PNG, and WebP only
- file validation uses MIME type and a 5MB max per image
- files are saved under `/public_html/uploads/products/{product_id}/`
- first image is stored as primary image (`sort_order = 0`)
- save operation rolls back if image persistence fails
- UI communicates the 4:5 portrait requirement clearly

**Done Means:**
- every product can display a consistent primary image and gallery

### Story A3.5 — Edit, Hide, Delete

**Persona:** Seller  
**Goal:** maintain catalogue accuracy over time  
**Success Outcome:** products can be updated, hidden, or permanently removed without corrupting related data

**Dependencies:** A3.2, A3.4

**Acceptance Criteria:**
- seller can edit any product and save changes
- seller can toggle product availability without deleting it
- delete action requires explicit confirmation
- deleting a product cascades safely to images, sizes, and product-category rows

**Done Means:**
- stale products do not linger on the storefront and related records are not orphaned

---

## Epic A4 — Admin Settings & Referral Admin

**Goal:** let the seller manage store identity, live status, referral code administration, and admin password changes from the panel.

### Story A4.1 — Store Settings Page

**Persona:** Seller  
**Goal:** manage storefront-facing business settings in admin  
**Success Outcome:** the storefront reads from config data rather than hardcoded copy

**Dependencies:** A2.3, A1.2

**Acceptance Criteria:**
- `/admin/settings.php` loads and saves:
  - store name
  - tagline
  - intro text
  - WhatsApp number
  - Telegram link
  - delivery info
  - status message
  - referral discount percent
- empty referral discount stores NULL
- placeholder text is acceptable for still-open business copy values

**Done Means:**
- seller-facing copy and contact info can change without code edits

### Story A4.2 — Seller Status Control

**Persona:** Seller  
**Goal:** update live availability state  
**Success Outcome:** storefront status indicators stay truthful

**Dependencies:** A4.1

**Acceptance Criteria:**
- admin provides status values `online`, `brb`, and `offline`
- status changes persist to `seller_config.seller_status`
- optional status message is saved alongside status changes
- storefront can reflect the latest value within its polling interval

**Done Means:**
- parents see a status state that is maintained by the seller, not hardcoded

### Story A4.3 — Referral Code Admin

**Persona:** Seller  
**Goal:** create, review, and record referral code usage  
**Success Outcome:** referral activity is trackable from the admin panel

**Dependencies:** A1.2, A2.3

**Acceptance Criteria:**
- `/admin/referrals.php` lists referral codes sorted by referral count descending
- seller can create a new code from referrer name + WhatsApp number
- seller can open a code detail view to see redemption history
- seller can record a redemption, which:
  - inserts a `referral_uses` row
  - increments the referral count
  - snapshots current `referral_discount_percent`
- seller can deactivate a code without deleting history

**Done Means:**
- referral tracking works as an internal admin system before any automation is added

### Story A4.4 — Admin Password Change

**Persona:** Seller  
**Goal:** change the admin password without editing server files manually  
**Success Outcome:** admin security maintenance is practical for a single operator

**Dependencies:** A2.1, A4.1

**Acceptance Criteria:**
- settings page includes current password, new password, and confirm fields
- current password must verify before updating
- new password is stored as a one-way cryptographic hash
- plain-text password is never persisted or displayed

**Done Means:**
- password maintenance no longer depends on a separate manual hash-generation step

---

## Epic A5 — Storefront Catalogue & Filters

**Goal:** build the lightweight storefront shell, catalogue API, trust hero, and filtering UX that lets parents browse the catalogue confidently on mobile.

### Story A5.1 — Storefront Product & Category API

**Persona:** Developer  
**Goal:** expose storefront-ready JSON endpoints  
**Success Outcome:** Alpine.js storefront logic can load products, categories, and seller status without embedded data duplication

**Dependencies:** A1.1, A1.2

**Public Interface / Data Impact:**
- `/api/products.php` returns `categories[]`
- `/api/categories.php` returns category metadata for filter tabs
- `/api/status.php` returns seller status payload

**Acceptance Criteria:**
- `/api/products.php` returns available products with:
  - id
  - name
  - price
  - original_price
  - brand
  - primary_image_url
  - sizes[]
  - categories[]
  - badge state
- category filtering is supported by query parameter and joins through `product_categories`
- `/api/categories.php` returns the category set used by the storefront
- `/api/status.php` returns status, message, wa number, and Telegram link
- all API responses are JSON with correct content type

**Done Means:**
- storefront does not need to infer category relationships from a legacy single-category field

### Story A5.2 — Storefront Shell

**Persona:** Parent  
**Goal:** load the storefront quickly on mobile  
**Success Outcome:** first paint is fast and the shell is usable without a build pipeline

**Dependencies:** A5.1

**Acceptance Criteria:**
- `index.html` loads Alpine.js from CDN
- page title and meta description are set
- OG tags are present
- GA4 snippet placeholder is present
- layout has no horizontal scrolling at 320px width
- initial HTML + JS footprint stays within the defined lightweight target

**Done Means:**
- the storefront shell is production-shaped before the catalogue interactions are layered in

### Story A5.3 — Hero & Trust Section

**Persona:** Parent  
**Goal:** see trust signals immediately on arrival  
**Success Outcome:** seller identity and availability are clear before browsing

**Dependencies:** A5.2, A1.2

**Acceptance Criteria:**
- hero displays store name, tagline or placeholder, intro text, seller status, and direct WhatsApp/Telegram links
- seller status loads on page load and updates via polling every 60 seconds
- hero remains usable above the fold on a typical mobile viewport

**Done Means:**
- the storefront feels like a trust-first catalogue, not a generic product grid

### Story A5.4 — Product Grid & Filters

**Persona:** Parent  
**Goal:** browse and narrow products quickly  
**Success Outcome:** relevant products are discoverable without page reloads

**Dependencies:** A5.1, A5.2

**Acceptance Criteria:**
- products render in a responsive grid using the primary image and badge state
- category filter tabs are horizontally scrollable on mobile
- gender filter composes with category filtering
- selecting a category matches products by membership in that category
- sold-out products remain viewable but visibly marked
- product images use native lazy loading

**Done Means:**
- multi-category products behave consistently across filter, card, and API layers

### Story A5.5 — Shareable Product Routing

**Persona:** Parent  
**Goal:** open and share a specific product URL  
**Success Outcome:** a copied product link lands directly on the intended product detail view

**Dependencies:** A5.4

**Acceptance Criteria:**
- tapping a product updates the URL to `/?product={id}` without a full page reload
- loading `/?product={id}` opens the correct product detail view directly
- browser back returns the user to the grid rather than a blank state
- copied product URLs remain stable when shared

**Done Means:**
- partner approval and conversational sharing work off one canonical product URL

---

## Epic A6 — Product Detail & Commerce Handoff

**Goal:** provide the product detail experience and messaging handoff behavior that convert browsing into WhatsApp or Telegram conversations.

### Story A6.1 — Product Detail View

**Persona:** Parent  
**Goal:** see enough product detail to make a buying decision  
**Success Outcome:** a parent can inspect the item, sizes, pricing, and delivery info before messaging

**Dependencies:** A5.5, A3.2, A3.4

**Acceptance Criteria:**
- detail view shows gallery, name, price, optional sale price, description, brand, material, season tag, and TikTok link if present
- size chips show available and sold-out states clearly
- delivery info is pulled from `seller_config.delivery_info`
- size guide is collapsible and accessible from the detail view

**Done Means:**
- detail pages contain the information needed for conversational purchasing without adding checkout complexity

### Story A6.2 — WhatsApp Order & Enquiry Handoff

**Persona:** Parent  
**Goal:** start a WhatsApp conversation with prefilled product context  
**Success Outcome:** the parent does not need to retype item details

**Dependencies:** A6.1, A4.1

**Acceptance Criteria:**
- selected-size orders open `wa.me` with an encoded order template
- no-size state uses an enquiry template instead
- message template includes optional referral code line
- emoji and special characters are preserved by proper URL encoding
- WhatsApp CTA is visually prominent on mobile

**Done Means:**
- WhatsApp is the primary commerce handoff and works for both direct order intent and enquiry intent

### Story A6.3 — Telegram Link, Save for Later, Notify Me

**Persona:** Parent  
**Goal:** use alternative handoff and bookmark actions  
**Success Outcome:** the user has a non-WA backup path and can retain product context for later

**Dependencies:** A6.1

**Acceptance Criteria:**
- Telegram button links to the seller's personal account from `seller_config.telegram_link`
- Save for Later opens WhatsApp saved messages when available
- Save for Later falls back to clipboard copy with toast confirmation if WhatsApp cannot be used
- all-sizes-sold-out products replace primary order CTA with Notify Me template behavior

**Done Means:**
- Telegram is present as a simple personal-contact path without leaking bot scope into MVP

### Story A6.4 — Storefront Seller Status

**Persona:** Parent  
**Goal:** understand reply expectations before messaging  
**Success Outcome:** storefront status reads consistently across hero and product detail surfaces

**Dependencies:** A5.3, A4.2

**Acceptance Criteria:**
- storefront maps `online`, `brb`, and `offline` into distinct visual states and copy
- status updates are pulled from `/api/status.php`
- product detail view shows the same status state used by the hero

**Done Means:**
- reply-expectation UX is driven by real config values, not duplicated hardcoded labels

### Story A6.5 — GA4 Snippet & UTM-Compatible Link Handling

**Persona:** Seller  
**Goal:** capture analytics-ready pageviews and preserve attribution data  
**Success Outcome:** marketing traffic can be measured later without retrofitting the storefront shell

**Dependencies:** A5.2

**Acceptance Criteria:**
- GA4 snippet placeholder exists in the storefront shell
- storefront routes preserve UTM parameters when product detail state is opened client-side
- product/share links do not strip known attribution parameters during navigation

**Done Means:**
- the MVP storefront is ready for analytics configuration without mixing in manual reporting work

---

# Lane B — Deferred Engineering Backlog

## Epic B1 — Deferred Telegram Bot

**Goal:** capture the engineering work needed for a future Telegram bot without contaminating the MVP developer scope.

**Epic Status:** Deferred from first developer handoff

**Why Deferred:**
- storefront Telegram contact is already satisfied by the seller's personal Telegram link
- seller notification routing for completed bot orders is intentionally unresolved
- MVP does not require a bot to launch the catalogue + WhatsApp flow

### Story B1.1 — Telegram Bot Webhook Setup

**Persona:** Developer  
**Goal:** create and register `telegram-webhook.php` for Telegram POST delivery  
**Blocked By:** final confirmation that the bot rollout is in scope for an implementation window

**Acceptance Criteria (for future use):**
- webhook endpoint handles Telegram POST payloads and returns HTTP 200 quickly
- webhook registration is performed with the configured bot token
- non-POST requests fail gracefully

### Story B1.2 — Telegram Conversational Order Flow

**Persona:** Parent  
**Goal:** complete a guided order conversation inside Telegram  
**Blocked By:** bot scope being scheduled into a later phase

**Acceptance Criteria (for future use):**
- bot stores per-chat session state
- bot collects product, size, buyer name, address, and gift flow data
- completion is explicitly defined as reaching confirmation step

### Story B1.3 — Seller Notification Routing

**Persona:** Seller  
**Goal:** receive completed Telegram bot orders at a defined destination  
**Blocked By:** unresolved destination choice

**Current Placeholder:**
- destination may later be Telegram DM or a private channel
- MVP developer must not choose this behavior during first implementation

### Story B1.4 — Optional Image Processing Enhancements

**Persona:** Developer  
**Goal:** add image resize/crop automation if Hostinger environment support is confirmed  
**Blocked By:** environment confirmation for PHP image tooling and future performance priorities

---

# Lane C — Operational / Content Backlog

## Epic C1 — Operational Setup & Marketing Assets

**Goal:** keep launch-ops work visible without presenting it as software implementation.

**Epic Status:** Non-dev operational backlog

### Story C1.1 — WhatsApp Business Profile Setup

**Owner:** Seller / Operator  
**Outcome:** WhatsApp Business profile, away message, quick replies, and customer labels are configured manually

### Story C1.2 — GA4 Property Verification

**Owner:** Seller / Operator  
**Outcome:** GA4 property exists, measurement ID is available, and pageviews are confirmed in Realtime after deployment

### Story C1.3 — CRM Google Sheet Template

**Owner:** Seller / Operations  
**Outcome:** Google Sheet exists with agreed columns, formulas, and conditional formatting

### Story C1.4 — Broadcast & Follow-Up Template Library

**Owner:** Seller / Marketing  
**Outcome:** ready-to-send message templates exist for post-purchase, size-up, birthday, VIP, new stock, and flash sale use cases

### Story C1.5 — Referral Operating Process

**Owner:** Seller / Operations  
**Outcome:** seller has a repeatable process for creating codes after first purchase and recording redemptions in admin

### Story C1.6 — School Calendar & Campaign Planning

**Owner:** Seller / Marketing  
**Outcome:** demand peaks and campaign windows are documented for yearly planning

### Story C1.7 — Referral Share Card Asset

**Owner:** Seller / Design  
**Outcome:** Canva or equivalent static share card exists for referral promotion

---

## Developer Handoff Notes

- Lane A is the only backlog lane that should be committed into the first MVP implementation schedule.
- Lane B is intentionally excluded from MVP commitment even though the work is technically valid.
- Lane C should be tracked in launch planning, not assigned to the software developer as coding work.
- Placeholder copy is acceptable during development as long as:
  - config keys exist
  - placeholders are obvious
  - no code path depends on missing business text

---

## Acceptance Checklist For This Artifact

- Lane A contains only buildable developer stories
- Multi-category behavior is reflected in schema, admin form, API payload, and filtering stories
- Telegram bot scope is isolated to Lane B
- Operational and content work is isolated to Lane C
- `seller_config` is fully normalized across all MVP stories
- No Lane A story requires the implementer to make unresolved product decisions

---

*Master backlog v2.0 — Bloom Corner Kiddies — implementation handoff artifact*
