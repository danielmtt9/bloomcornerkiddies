---
stepsCompleted: [step-01-init, step-02-discovery, step-03-goals, step-04-features, step-05-requirements, step-06-acceptance]
inputDocuments:
  - _bmad-output/brainstorming/brainstorming-session-2026-04-19-2357.md
  - _bmad-output/brainstorming/kidstore-mindmap.puml
workflowType: prd
prdId: PRD-001
version: 1.1
status: draft
date: 2026-04-20
decisions:
  - telegram_button: personal_account
  - save_for_later: whatsapp_saved_messages
  - photo_aspect_ratio: 4_5_portrait
author: Danielaroko
---

# PRD-001: Customer Storefront
## Kids Clothes Conversational Commerce — bloomrocxx

**Author:** Danielaroko
**Date:** 2026-04-20
**Version:** 1.1
**Status:** Draft
**Project:** bloomrocxx — Premium Kids Clothing Storefront (Nigeria)

---

## 1. Overview

### 1.1 Product Summary

A premium, trust-first, ultra-lightweight product catalogue website where Nigerian parents browse kids' clothing and are seamlessly handed off to WhatsApp or Telegram to complete their purchase. There is no shopping cart, no checkout, and no payment gateway on the storefront itself — the sales conversation happens personally.

The storefront positions the seller as a **Personal Stylist**, not a generic retailer.

### 1.2 Problem Statement

Nigerian parents buying kids' clothing online face two extremes:
- **Formal e-commerce** (complex checkout, no personal touch, trust issues, high abandonment)
- **Informal Instagram/WhatsApp selling** (no organized catalogue, hard to browse, chaotic DMs)

There is a gap for a **curated, trustworthy catalogue** that bridges both worlds: professional browsing experience + personal conversational purchase.

### 1.3 Target Users

**Primary:** Nigerian parents (primarily mothers), ages 25–45, purchasing kids' clothing for children aged 0–12 years.

**Device:** Predominantly mobile (Android smartphones, often mid-range devices, variable data quality).

**Context:** Browsing product catalogue while deciding what to buy. Often sharing links with a partner. Comparing sizes. Looking for trust signals before committing to a WhatsApp conversation.

### 1.4 Core Value Proposition

> *"Browse like a boutique. Buy like a friend."*

Parents get the visual confidence of a proper store with the personal warmth and trust of buying from someone they know.

### 1.5 Store Identity (Confirmed)

| Property | Value |
|----------|-------|
| **Store Name** | Bloom Corner Kiddies |
| **WhatsApp** | +234 904 930 8656 |
| **wa.me link** | `https://wa.me/2349049308656` |
| **Telegram** | `https://t.me/+2349049308656` |
| **Payment** | Bank transfer |
| **Photo ratio** | 4:5 portrait |
| **Tagline** | *(TBD — placeholder)* |

---

## 2. Goals & Success Metrics

### 2.1 Business Goals

| Goal | Metric | Target |
|------|--------|--------|
| Drive WhatsApp/Telegram conversations | % of product views that result in a WA/TG tap | ≥ 25% |
| Build parent trust before first contact | Bounce rate from product detail pages | < 40% |
| Fast mobile load | PageSpeed score (mobile) | ≥ 85 |
| Reduce "wrong size" enquiries | Use of built-in size guide | Measurable engagement |
| Enable product discoverability | Category filter usage | Measurable engagement |

### 2.2 User Goals

1. Quickly find clothing in the right category and size for their child
2. Understand price (in ₦), availability, and size stock at a glance
3. Feel confident the seller is legitimate and trustworthy before messaging
4. Easily initiate a purchase conversation without friction
5. Share a product link with a partner or family member for approval

### 2.3 Non-Goals (Out of Scope for PRD-001)

- Online checkout or payment processing on the storefront *(handled via WhatsApp/Telegram conversation)*
- Admin product management *(covered in PRD-003)*
- Marketing campaigns and referral systems *(covered in PRD-004)*
- Telegram bot logic *(covered in PRD-002)*

---

## 3. User Stories

### 3.1 Core Browsing

| ID | As a... | I want to... | So that... | Priority |
|----|---------|--------------|------------|----------|
| US-01 | Parent | See all available products at a glance | I can quickly find what I'm looking for | P0 |
| US-02 | Parent | Filter products by category (Girls, Boys, Newborn, etc.) | I only see relevant items | P0 |
| US-03 | Parent | Filter products by gender | I find items for my specific child | P0 |
| US-04 | Parent | See if an item is on sale at a glance | I don't miss discounts | P0 |
| US-05 | Parent | See a "Sold Out" badge clearly | I don't waste time on unavailable items | P0 |
| US-06 | Parent | See the price in Nigerian Naira (₦) without decimals | The price is clear and familiar | P0 |
| US-07 | Parent | Filter by season/occasion (optional) | I find Christmas or Eid outfits quickly | P1 |
| US-08 | Parent | Sort products by newest or price | I find what I want faster | P1 |

### 3.2 Product Detail

| ID | As a... | I want to... | So that... | Priority |
|----|---------|--------------|------------|----------|
| US-09 | Parent | Swipe through multiple photos of a product | I see the item from different angles | P0 |
| US-10 | Parent | See which sizes are in stock and which are sold out | I pick the right size before messaging | P0 |
| US-11 | Parent | See the exact price (with sale pricing if applicable) | I know the cost before committing | P0 |
| US-12 | Parent | Read a short description of the product | I understand material, fit, and occasion | P1 |
| US-13 | Parent | See the brand name if available | I can verify quality expectations | P1 |
| US-14 | Parent | See fabric/material information if provided | I know what my child will be wearing | P1 |
| US-15 | Parent | Watch a TikTok video of the item | I see it on a real child | P1 |
| US-16 | Parent | See the season/occasion tag if set | I confirm it's right for the event | P2 |
| US-17 | Parent | Consult a size guide | I pick the right size for my child's age/measurements | P1 |
| US-18 | Parent | See delivery information on the product page | I understand total cost before messaging | P1 |
| US-19 | Partner | Receive a shared product link and see the full detail | I can approve the purchase decision | P1 |

### 3.3 Purchase Handoff

| ID | As a... | I want to... | So that... | Priority |
|----|---------|--------------|------------|----------|
| US-20 | Parent | Tap "Order via WhatsApp" to start a purchase | I reach the seller instantly with product details pre-filled | P0 |
| US-21 | Parent | Tap "Order via Telegram" to start a purchase | I have an alternative channel | P0 |
| US-22 | Parent | The WhatsApp message is pre-filled with product info | I don't have to type product details manually | P0 |
| US-23 | Parent | "Notify Me" button when a product is sold out | I'm alerted when stock returns (via WhatsApp) | P1 |
| US-24 | Parent | "Save for Later" button to save product link | I can revisit later without losing the item | P1 |

### 3.4 Trust & Seller Presence

| ID | As a... | I want to... | So that... | Priority |
|----|---------|--------------|------------|----------|
| US-25 | Parent | See the seller's photo and a warm personal intro | I feel I'm buying from a real, trustworthy person | P0 |
| US-26 | Parent | See real parent testimonials/review screenshots | I trust the quality and service | P1 |
| US-27 | Parent | See a "Seen on Real Kids" gallery | I visualize the clothing on actual children | P2 |
| US-28 | Parent | See the seller's response time badge | I know how quickly I'll get a reply | P1 |
| US-29 | Parent | See the seller's online status (🟢/🟡/🔴) | I know if someone is available now | P1 |

---

## 4. Functional Requirements

### 4.1 Homepage / Product Catalogue

**FR-01 — Product Grid**
- Display products in a responsive card grid (2 columns mobile, 3+ columns desktop)
- Each card shows: primary photo, product name, price in ₦, gender + category tags, sale badge, sold-out badge, brand name (if set)
- Price format: `₦5,500` (integer, no decimals, comma separator for thousands)
- Sale price display: `~~₦8,000~~ ₦5,500` with red "SALE" badge overlay on photo

**FR-02 — Product Status Badges**
- "SALE" badge: shown when `original_price IS NOT NULL`; red/orange, overlaid top-left of photo
- "SOLD OUT" badge: shown when ALL sizes for a product have `stock_count = 0`; grey, overlaid top-left
- If both conditions apply, show "SOLD OUT" badge (takes priority)
- Products with `available = 0` are completely hidden from all views

**FR-03 — Category Filter Tabs**
- Horizontal scrollable filter tabs: All | Newborn 👶 | Baby 🍼 | Toddler 🧒 | Girls 👧 | Boys 👦 | School 🏫 | Occasions 🎉 | Pyjamas 🛏️ | Footwear 🩴
- Active tab is visually highlighted
- Filtering is client-side (no page reload)
- Tab shows product count per category (optional, P2)

**FR-04 — Gender Filter**
- Secondary filter: All | Girls | Boys | Unisex
- Composable with category filter (can filter Girls + School Wear simultaneously)
- Client-side, no page reload

**FR-05 — Season/Occasion Tag Filter**
- Optional filter (shown only if any products have season tags set)
- Dropdown or pill: "Christmas" | "Eid" | "School" | "Summer" etc.
- Populated dynamically from distinct values in product data

**FR-06 — Sort Order**
- Default: Newest first (`created_at DESC`)
- Options: Newest | Price: Low to High | Price: High to Low
- Client-side sort on loaded product data

**FR-07 — Trust Hero Section (Homepage)**
- Seller photo + warm personal intro text (1-2 sentences, set in seller_config)
- Response time badge (e.g., "Replies within 1 hour 💛")
- Seller online status dot (🟢 Online / 🟡 BRB / 🔴 Offline)
- Status polled from `/api/status.php` every 60 seconds; updates without page reload

### 4.2 Product Detail View

**FR-08 — Photo Gallery**
- Full-width swipeable/scrollable photo gallery
- All product photos use **4:5 portrait aspect ratio** — enforced at upload; storefront crops/letterboxes to maintain consistent card height
- Thumbnail dots or strip below (if multiple photos)
- At least 1 photo always present (enforced at admin upload — see PRD-003 FR-49)
- Native lazy loading on all images (`loading="lazy"`)
- Images served from `/uploads/products/{id}/`

**FR-09 — TikTok Video Integration**
- If `tiktok_url` is set: show "▶ Watch on TikTok 🎵" button/link
- Tapping opens the TikTok URL in a new tab/TikTok app
- If `tiktok_url` is null: button is completely hidden (not greyed out)

**FR-10 — Product Information Display**
- Product name (always shown)
- Price line: `₦5,500` normal OR `~~₦8,000~~ ₦5,500` if on sale
- Gender + Category tags (small pill badges)
- Brand name (shown only if `brand IS NOT NULL`)
- Material (shown only if `material IS NOT NULL`)
- Season/Occasion tag (shown only if `season_tag IS NOT NULL`)
- Short description paragraph (shown only if `description IS NOT NULL`)

**FR-11 — Size Selector**
- Display all sizes for the product as selectable chips/buttons
- Available size: highlighted, tappable, clicking selects it
- Sold-out size: greyed out, line-through label, not tappable, shows "Sold Out" tooltip
- Selected size: visually distinguished (border, fill)
- Size displayed in order of `sort_order` column
- If a size is selected, WhatsApp button includes the selected size in pre-filled message

**FR-12 — Delivery Information**
- Static section on product detail page
- Content pulled from `seller_config` key `delivery_info`
- Displayed as formatted text (e.g., "Lagos: ₦1,500 | Nationwide via GIG: ₦2,500–₦4,500")
- Always visible on product detail page

**FR-13 — WhatsApp Order Button**
- Always visible on product detail page
- Label: "📱 Order via WhatsApp"
- Generates `wa.me/{wa_number}?text={encoded_message}` URL
- Message template (size selected): `"Hi! 👋 I'd like to order:\n\n*{name}*\nBrand: {brand}\nSize: {size}\nPrice: ₦{price}{if sale: (was ₦{original})}\n\nPlease confirm availability. Thank you! 🙏"`
- Message template (no size selected): `"Hi! 👋 I'm interested in:\n\n*{name}*\nBrand: {brand}\nPrice: ₦{price}\nCategory: {category} · {gender}\n\nCan you help me choose? 🙏"`
- Brand line omitted from message if `brand IS NULL`

**FR-14 — Telegram Order Button**
- Label: "✈️ Order via Telegram"
- Links to seller's **personal Telegram account**: `https://t.me/{telegram_username}`
- `telegram_username` pulled from `seller_config.telegram_username`
- Opens Telegram app on mobile; Telegram Web on desktop
- No bot deep-link or `?start=` parameter — parent initiates a direct personal conversation
- *(Telegram bot for automated order intake, if added in future, is a separate channel — see PRD-002)*

**FR-15 — Save for Later Button**
- Label: "🔖 Save for Later"
- Tapping opens **WhatsApp saved messages** via `https://wa.me/` (no phone number = opens user's own saved messages chat)
- Pre-filled message contains product name, price in ₦, and the product's shareable URL (see FR-21)
- On devices where WhatsApp is not installed, button falls back to copying the product URL to clipboard with toast "Link copied! 📋"
- No user account or server-side persistence required

**FR-16 — Size Guide Accordion**
- Collapsible section below the order buttons
- Label: "📏 Size Guide ▼"
- Content: Age-to-size reference table (global, not per product)
- Includes note: "Not sure? Ask me!" → opens WhatsApp with pre-fill "Can you help me choose the right size? My child is [age]"

**FR-17 — Sold-Out Handling**
- When ALL sizes have `stock_count = 0`:
  - "SOLD OUT" badge on card
  - Size selector shows all sizes greyed out
  - WhatsApp/Telegram order buttons replaced with "🔔 Notify Me when back in stock"
  - Notify Me button pre-fills WA: `"Hi! 👋 Please notify me when this is back in stock:\n\n*{name}*\nSize: {selected_size}\n₦{price}\n\nThank you! 🙏"`
  - Individual size sold-out: that size is greyed, other sizes still orderable

**FR-21 — Shareable Product URLs**
- Each product detail view has a unique, bookmarkable URL: `/?product={id}` or `#product-{id}`
- When a parent shares or copies this URL, the recipient lands directly on that product's detail view
- URL is used in FR-15 (Save for Later), FR-13 (WhatsApp pre-fill optional enrichment), and PRD-004 referral share cards
- Alpine.js handles routing client-side; no server-side routing required

### 4.3 Performance Requirements

**FR-18 — Ultra-Lightweight Frontend**
- Total initial JavaScript payload: ≤ 15KB (Alpine.js CDN only)
- All CSS inline in `<style>` block in `index.html` — zero external stylesheet requests
- Alpine.js loaded from CDN (cached by browser after first visit)
- All images use native `loading="lazy"` attribute
- No build step required — pure static HTML file

**FR-19 — API Response**
- `GET /api/products.php` returns all available products as JSON
- Response includes products with `available = 1` only
- Response structure: `{ products: [...], categories: [...], total: N }`
- PHP sets `Cache-Control: max-age=60` header
- Response time target: < 500ms (shared hosting MariaDB query)

**FR-20 — Status Polling**
- `GET /api/status.php` returns seller status: `{ status: "online", message: "Replies within 1hr 💛" }`
- Storefront polls this endpoint every 60 seconds
- Status dot updates without page reload

---

## 5. Non-Functional Requirements

### 5.1 Performance

| Requirement | Target |
|-------------|--------|
| Mobile PageSpeed score | ≥ 85 |
| Time to First Contentful Paint (mobile, 4G) | < 2 seconds |
| Total page weight (HTML + inline CSS + Alpine.js) | < 100KB |
| Product images (per image) | Max 5MB upload; served optimized |

### 5.2 Compatibility

| Requirement | Detail |
|-------------|--------|
| Primary device | Android mobile browsers (Chrome for Android) |
| Minimum browser support | Chrome 80+, Safari 14+, Firefox 80+ |
| Screen sizes | 320px minimum width; fully responsive |
| WhatsApp link behaviour | Must open WA app on mobile; WA Web on desktop |

### 5.3 Accessibility

- Font size minimum: 14px body text, 16px for prices and product names
- Touch targets minimum: 44×44px (all buttons and size chips)
- Color contrast: WCAG AA compliant for all text on background
- "SOLD OUT" / "SALE" status communicated via text, not color alone

### 5.4 Hosting Constraints (Hostinger Shared)

- Static `index.html` served from `public_html/`
- No Node.js persistent processes
- API calls to PHP files in `public_html/api/`
- Image storage in `public_html/uploads/products/`
- MariaDB on same Hostinger account

### 5.5 Currency & Localisation

- All prices displayed in Nigerian Naira (₦)
- Price format: `₦X,XXX` (whole numbers, comma thousands separator)
- No internationalization required (single-market product)
- Language: English (Nigerian context)

---

## 6. Design Specifications

### 6.1 Visual Identity

| Element | Specification |
|---------|--------------|
| **Color palette** | Warm neutrals: cream (#FFF8F0), sage green (#8FAF7E), soft terracotta (#C8785A), warm white (#FAFAF8) |
| **Primary action color** | WhatsApp green (#25D366) for WA buttons |
| **Typography — Display** | Serif font (e.g. Playfair Display, Lora) for store name and hero |
| **Typography — Body** | Rounded sans-serif (e.g. DM Sans, Nunito) for product names, prices |
| **Tone** | Warm, boutique, premium. Not loud, not clinical. |
| **Border radius** | Rounded cards (12px), pill-shaped tags and size chips |
| **Shadows** | Soft, subtle box shadows on product cards |

### 6.2 Product Card Layout

```
┌─────────────────────────────┐
│  [PRIMARY PHOTO]            │
│  ╔═══════╗                  │  ← Badge: SALE (red) or SOLD OUT (grey)
│  ║  SALE ║                  │
│  ╚═══════╝                  │
├─────────────────────────────┤
│ 👧 Girls   🎉 Occasions      │  ← Gender + Category pill tags
│ Floral Summer Dress          │  ← Product name (semibold)
│ ~~₦8,000~~ ₦5,500           │  ← Price (strikethrough if sale)
│ Brand: Carter's              │  ← Brand (only if set)
│ [    View Details    ]       │  ← CTA button
└─────────────────────────────┘
```

### 6.3 Product Detail Layout (Mobile)

```
[← Back]

[PHOTO 1]  [PHOTO 2]  [PHOTO 3]   ← swipeable gallery
●  ○  ○                            ← position indicator dots

[▶ Watch on TikTok 🎵]            ← only if TikTok URL set

👧 Girls • Carter's               ← gender + brand (if set)
Floral Summer Dress
~~₦8,000~~ ₦5,500  [SALE]

Material: 100% Cotton             ← only if set
Summer 2025                       ← season tag, only if set

"A beautiful floral dress..."     ← description, only if set

── SELECT SIZE ─────────────────
[3-4Y: 3 left] [4-5Y: 5 left] [5-6Y: SOLD OUT]

── DELIVERY ─────────────────────
Lagos: ₦1,500
Nationwide (GIG): ₦2,500–₦4,500

[📱 Order via WhatsApp]
[✈️ Order via Telegram]
[🔖 Save for Later]

[📏 Size Guide ▼]

── TRUST SECTION ────────────────
[Parent testimonials]
[Quality promise badge]
```

### 6.4 Homepage Hero Section

```
┌─────────────────────────────────┐
│  [Seller photo, circle crop]    │
│  "Hi, I'm [Name]! I personally  │
│   curate every piece for your   │
│   little ones. 💛"              │
│                                 │
│  ● Replies within 1 hour        │
│  🟢 Online now                  │
└─────────────────────────────────┘
```

---

## 7. Technical Architecture

### 7.1 Technology Stack

| Layer | Technology | Justification |
|-------|-----------|---------------|
| Frontend | Static HTML + inline CSS + Alpine.js (15KB) | Ultra-lightweight, no build step, works on Hostinger |
| Data fetching | Vanilla JS `fetch()` API | No library needed for simple JSON GET |
| Reactivity | Alpine.js 3.x CDN | 15KB, declarative, no npm, perfect for filtering/show-hide |
| Hosting | Hostinger Shared Hosting | Already provisioned, zero extra cost |
| Backend API | PHP (Hostinger native) | Available on all Hostinger plans, zero config |
| Database | MariaDB (Hostinger hPanel) | Included with hosting, no extra setup |
| Images | Hostinger filesystem `/uploads/` | Simple, zero-cost, direct serve |
| HTTPS | Hostinger auto Let's Encrypt | Automatic, no configuration |

### 7.2 API Endpoints (Consumed by Storefront)

| Endpoint | Method | Response | Used for |
|----------|--------|----------|----------|
| `/api/products.php` | GET | Product JSON array | Load all products on page |
| `/api/products.php?category={slug}` | GET | Filtered products | Server-side category filter (fallback) |
| `/api/status.php` | GET | `{status, message}` | Seller status dot |
| `/api/categories.php` | GET | Categories array | Filter tab population |

### 7.3 Database Schema (Consumed)

*(Full schema defined in Product Schema section of brainstorming document)*

Tables read by storefront: `products`, `product_images`, `product_sizes`, `categories`, `seller_config`

### 7.4 File Structure

```
public_html/
├── index.html              ← Entire storefront (HTML + inline CSS + Alpine.js)
├── .htaccess               ← GZIP compression, URL rewrites, security headers
├── api/
│   ├── products.php        ← Returns product JSON
│   ├── status.php          ← Returns seller status
│   └── categories.php      ← Returns categories
└── uploads/
    └── products/
        └── {product_id}/
            ├── img-0.jpg   ← Primary photo (sort_order = 0)
            ├── img-1.jpg
            └── ...
```

---

## 8. Acceptance Criteria

### AC-001: Product Grid Loads

```
GIVEN the homepage loads
WHEN the page fetches /api/products.php
THEN products are displayed in a responsive card grid
AND each card shows: photo, name, price in ₦, gender tag, category tag
AND sold-out products show "SOLD OUT" badge
AND discounted products show "SALE" badge and strikethrough price
AND hidden products (available=0) do not appear
```

### AC-002: Category Filtering

```
GIVEN products are displayed
WHEN a parent taps a category filter tab (e.g. "Girls 👧")
THEN only products matching that category are shown
AND the active tab is visually highlighted
AND filtering happens without a page reload
```

### AC-003: Product Detail — Size Selector

```
GIVEN a parent opens a product detail view
THEN all sizes for that product are displayed as chips
AND sizes with stock_count > 0 are tappable and selectable
AND sizes with stock_count = 0 are greyed out with "Sold Out" label
AND exactly ONE size can be selected at a time
```

### AC-004: WhatsApp Button — With Size

```
GIVEN a parent selects a size on the product detail page
WHEN they tap "Order via WhatsApp"
THEN WhatsApp opens with a pre-filled message containing:
  - Product name (bold)
  - Brand name (if set)
  - Selected size
  - Current price in ₦
  - Sale note if discounted
  - Closing line
AND the message does NOT open in a new tab on mobile (uses wa.me deep link)
```

### AC-005: WhatsApp Button — No Size Selected

```
GIVEN a parent has NOT selected a size
WHEN they tap "Order via WhatsApp"
THEN WhatsApp opens with a pre-filled message containing:
  - Product name, price, category, gender
  - Prompt asking for child's age for size help
```

### AC-006: Sold-Out Product Handling

```
GIVEN all sizes of a product have stock_count = 0
THEN the product card shows "SOLD OUT" badge
AND on the detail page, all size chips are greyed out
AND the order buttons are replaced with "🔔 Notify Me when back in stock"
AND the Notify Me button generates a correct wa.me pre-filled message
```

### AC-007: Sale Price Display

```
GIVEN a product has original_price set (not null)
THEN the card shows strikethrough original price AND bold sale price
AND a red "SALE" badge is overlaid on the primary photo
AND the detail page shows the same strikethrough sale display
AND the WhatsApp pre-filled message mentions the sale price and original price
```

### AC-008: Optional Fields — Show/Hide

```
GIVEN a product has brand = NULL
THEN no brand line appears anywhere on card or detail page

GIVEN a product has brand = "Carter's"
THEN "Brand: Carter's" appears on both card and detail page

[Same rule applies for: material, tiktok_url, season_tag, description]
```

### AC-009: Seller Status Polling

```
GIVEN the homepage is open
WHEN /api/status.php returns {status: "online"}
THEN the status dot shows 🟢

WHEN /api/status.php returns {status: "offline"}
THEN the status dot shows 🔴

AND this check repeats every 60 seconds without page reload
```

### AC-010: Mobile Performance

```
GIVEN the storefront is loaded on a mobile device on a 4G connection
THEN Time to First Contentful Paint (FCP) is < 2 seconds
  as measured by Google PageSpeed Insights using "Mobile" mode
  simulating a mid-range Android device (Moto G4 profile in Chrome DevTools)
AND Mobile PageSpeed score is ≥ 85
  as measured by PageSpeed Insights at the production URL
AND the page is fully functional without enabling any mobile setting
AND all touch targets are ≥ 44×44px as measurable in Chrome DevTools Accessibility audit
AND horizontal scrolling does NOT occur at 320px viewport width
```

### AC-011: Shareable Product URL

```
GIVEN a parent navigates to a product's detail view
WHEN they copy the browser URL
THEN the URL contains a unique product identifier (e.g. /?product=42)
AND when a different user opens that URL
THEN they land directly on that product's detail view, not the homepage
```

### AC-012: Save for Later — WhatsApp Saved Messages

```
GIVEN a parent taps "🔖 Save for Later" on any product
THEN WhatsApp opens to the user's own saved messages chat
AND the pre-filled message contains:
  - Product name
  - Price in ₦
  - Shareable product URL
GIVEN WhatsApp is not installed on the device
THEN the product URL is copied to clipboard
AND a toast notification reads "Link copied! 📋"
```

---

## 9. Open Questions

| # | Question | Impact | Priority | Status |
|---|----------|--------|----------|--------|
| OQ-01 | What is the store name and tagline? | Affects homepage hero, page title, meta tags | High | ⏳ Open |
| OQ-02 | What is the WhatsApp Business number (international format)? | Required for all wa.me links | High | ⏳ Open |
| OQ-03 | What is the seller's Telegram username? | Required for Telegram button (`t.me/{username}`) | High | ⏳ Open |
| OQ-04 | What are the actual delivery prices/areas? | Shown on every product detail | High | ⏳ Open |
| OQ-05 | Should a product belong to ONE category or MULTIPLE categories? | Affects DB schema join table vs single FK | Medium | ⏳ Open |
| OQ-06 | ~~Should "Save for Later" copy URL to clipboard or use WA saved messages?~~ | — | — | ✅ Resolved: WA saved messages |
| OQ-07 | Should the homepage have a "New Arrivals" featured section at top? | Homepage layout decision | Medium | ⏳ Open |
| OQ-08 | ~~What photo aspect ratio should product images use?~~ | — | — | ✅ Resolved: 4:5 portrait |

---

## 10. Implementation Priority (MoSCoW)

### Must Have (MVP Launch)
- Product card grid with photos, name, price in ₦
- SALE and SOLD OUT badges
- Category filter tabs (client-side)
- Product detail page with size selector
- WhatsApp order button with pre-filled message
- Seller hero section with photo and status
- Delivery info display
- Mobile-first responsive layout
- Ultra-lightweight (≤15KB JS)

### Should Have (Shortly After Launch)
- Telegram order button
- TikTok video link
- "Notify Me" for sold-out items
- Gender + season filter
- Size guide accordion
- Parent testimonials / trust gallery

### Could Have (Future Sprints)
- Sort order controls
- Category product count badges
- "Related Products" row
- "Seen on Real Kids" gallery (FR to be written when prioritized)
- Persistent selected size across sessions

### Won't Have (This Version)
- Cart / checkout functionality
- User accounts / login
- Wishlist persistence (server-side)
- Product search / free text search
- Multi-currency support

---

## 11. Dependencies

| Dependency | Type | Required by |
|------------|------|-------------|
| PRD-003 (Admin Panel) | Upstream | Products must exist in DB before storefront displays them |
| MariaDB on Hostinger | Infrastructure | API must be connected to database |
| PHP on Hostinger | Infrastructure | All API endpoints |
| WhatsApp Business account | External | wa.me links require active WA number |
| Telegram account/bot | External | Telegram button links |
| Hostinger domain + SSL | Infrastructure | Public URL for parents to access |

---

## 12. Revision History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-04-19 | Danielaroko (John, PM) | Initial draft from brainstorming session |
| 1.1 | 2026-04-20 | Danielaroko (John, PM) | EP fixes: photo ratio 4:5, Telegram = personal account, Save for Later = WA saved messages, added FR-21 shareable URLs, added AC-011/AC-012, fixed AC-010 measurement method, resolved OQ-06 and OQ-08 |

---

*PRD-001 of 4 — Next: PRD-002 Conversational Commerce Handoff*
