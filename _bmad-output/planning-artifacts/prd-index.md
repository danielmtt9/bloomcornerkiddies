---
workflowType: prd-index
version: 1.3
date: 2026-04-20
author: Danielaroko
project: bloomrocxx
status: APPROVED
---

# PRD Suite — bloomrocxx
## Kids Clothes Conversational Commerce (Nigeria)

**Author:** Danielaroko
**Date:** 2026-04-20
**Version:** 1.3
**Status:** ✅ FULLY APPROVED — Zero Open Blockers — Ready for CE (Epics & Stories)

---

## Project Summary

A premium, trust-first kids' clothing storefront for the Nigerian market. Parents browse a curated visual catalogue and are handed off to WhatsApp or Telegram to complete purchases through personal conversation. No cart, no checkout. Hosted on Hostinger (PHP + MariaDB). All prices in Nigerian Naira (₦).

**Store Name:** Bloom Corner Kiddies
**Contact Number:** +2349049308656 (WhatsApp Business + Telegram)
**wa.me link:** `https://wa.me/2349049308656`
**Telegram link:** `https://t.me/+2349049308656`
**Payment:** Bank transfer (confirmed)

---

## PRD Index

| PRD | Title | Focus | Status | File |
|-----|-------|-------|--------|------|
| **PRD-001** | Customer Storefront | Product catalogue, filtering, product detail page, trust signals, mobile UX | ✅ Draft v1.1 | [prd-001-storefront.md](./prd-001-storefront.md) |
| **PRD-002** | Conversational Commerce Handoff | WhatsApp pre-filled messages, Telegram personal account handoff, Telegram bot standalone, sold-out notify, seller status | ✅ Draft v1.1 | [prd-002-handoff.md](./prd-002-handoff.md) |
| **PRD-003** | Product Admin Panel | Password-protected admin, product CRUD, image upload (4:5 ratio), inventory, settings | ✅ Draft v1.1 | [prd-003-admin.md](./prd-003-admin.md) |
| **PRD-004** | Marketing & Retention System | WA broadcast, referral system, CRM follow-ups, VIP tier, social content | ✅ Draft v1.1 | [prd-004-marketing.md](./prd-004-marketing.md) |

---

## Technology Stack (All PRDs)

| Layer | Technology |
|-------|-----------|
| Hosting | Hostinger Shared Hosting |
| Frontend | Static HTML + Alpine.js (15KB) + inline CSS |
| Backend | PHP (Hostinger native) |
| Database | MariaDB (Hostinger hPanel, included free) |
| Images | Hostinger filesystem `/uploads/products/` |
| WhatsApp | `wa.me` pre-filled links (free, no API) |
| Telegram Bot | PHP webhook (`/telegram-webhook.php`) |
| Analytics | Google Analytics 4 + UTM parameters |
| HTTPS | Let's Encrypt (auto by Hostinger) |
| CRM | Google Sheets (free) |
| Currency | Nigerian Naira (₦) — no decimals |

---

## Database Tables (All PRDs)

| Table | Used By |
|-------|---------|
| `products` | PRD-001, PRD-002, PRD-003 |
| `product_images` | PRD-001, PRD-003 |
| `product_sizes` | PRD-001, PRD-002, PRD-003 |
| `categories` | PRD-001, PRD-003 |
| `seller_config` | PRD-001, PRD-002, PRD-003, PRD-004 |
| `bot_sessions` | PRD-002 (Telegram bot state) |
| `referral_codes` | PRD-003, PRD-004 (referral tracking) |
| `referral_uses` | PRD-003, PRD-004 (per-use history + discount snapshot) |

---

## Seller Config Known Values

These are the confirmed `seller_config` values for use during development and setup:

| `seller_config` Key | Value | Status |
|---------------------|-------|--------|
| `store_name` | `Bloom Corner Kiddies` | ✅ Confirmed |
| `tagline` | TBD — placeholder for now | ⏳ Open |
| `wa_number` | `2349049308656` | ✅ Confirmed |
| `telegram_username` | `+2349049308656` *(phone-based Telegram link)* | ✅ Confirmed |
| `telegram_link` | `https://t.me/+2349049308656` | ✅ Confirmed |
| `delivery_info` | TBD — placeholder delivery text | ⏳ Open |
| `status_message` | TBD | ⏳ Open |
| `payment_info` | Bank transfer | ✅ Confirmed |
| `seller_status` | `online` *(default)* | ✅ Default |
| `referral_discount_percent` | NULL *(configurable; NULL = tracking only, no discount)* | ✅ Confirmed |

> **Note on Telegram:** The link `https://t.me/+2349049308656` opens a Telegram chat with the seller via their phone number. If the seller later creates a Telegram username (e.g. `@bloomcornerkiddies`), the link should be updated to `https://t.me/bloomcornerkiddies` for a cleaner URL.

---

## Open Questions Tracker (All PRDs)

| ID | Question | PRD | Priority | Status |
|----|----------|-----|----------|--------|
| OQ-01 | ~~Store name and tagline?~~ | PRD-001 | — | ✅ **Resolved: Bloom Corner Kiddies** *(tagline TBD)* |
| OQ-02 | ~~WhatsApp Business number?~~ | PRD-001, 002 | — | ✅ **Resolved: 2349049308656** |
| OQ-03 | ~~Seller's personal Telegram link?~~ | PRD-001, 002 | — | ✅ **Resolved: `t.me/+2349049308656`** |
| OQ-04 | Actual delivery prices/areas? | PRD-001 | 🔴 High | ⏳ **Placeholder** — update before launch |
| OQ-05 | Single or multi-category per product? | PRD-001 | 🟡 Medium | ⏳ Open |
| OQ-06 | ~~"Save for Later" — clipboard or WA saved messages?~~ | PRD-001 | — | ✅ **Resolved: WA saved messages** |
| OQ-07 | Homepage "New Arrivals" featured section? | PRD-001 | 🟡 Medium | ⏳ Open |
| OQ-08 | ~~Photo aspect ratio?~~ | PRD-001 | — | ✅ **Resolved: 4:5 portrait** |
| OQ-09 | ~~WhatsApp Business number (confirmed)?~~ | PRD-002 | — | ✅ **Resolved: 2349049308656** |
| OQ-10 | ~~Seller's personal Telegram link?~~ | PRD-002 | — | ✅ **Resolved: `t.me/+2349049308656`** |
| OQ-11 | Seller Telegram DM or private channel for bot orders? | PRD-002 | 🟡 Medium | ⏳ Open |
| OQ-12 | ~~"Ask a Question" as separate button?~~ | PRD-002 | — | ✅ **Resolved: Won't Have this version** |
| OQ-13 | English only for Telegram bot? | PRD-002 | 🟢 Low | ⏳ Open |
| OQ-14 | Low-stock warning at ≤3 in admin? | PRD-003 | 🟢 Low | ⏳ Open |
| OQ-15 | Auto-resize photos on upload (PHP GD)? | PRD-003 | 🟡 Medium | ⏳ Open |
| OQ-16 | "Duplicate Product" feature? | PRD-003 | 🟢 Low | ⏳ Open |
| OQ-17 | Hostinger GD library enabled for image processing? | PRD-003 | 🟡 Medium | ⏳ Open |
| OQ-18 | Admin as PWA / phone shortcut? | PRD-003 | 🟢 Low | ⏳ Open |
| OQ-19 | ~~Referral discount — 10% or different %?~~ | PRD-004 | — | ✅ **Resolved: Configurable by seller (NULL = off)** |
| OQ-20 | Referral prompt location on storefront? | PRD-004 | 🟡 Medium | ⏳ Open |
| OQ-21 | Primary customer city/zone (Lagos, Abuja, PH)? | PRD-004 | 🟡 Medium | ⏳ Open |
| OQ-22 | Instagram account handle? | PRD-004 | 🟡 Medium | ⏳ Open |
| OQ-23 | TikTok — primary or secondary channel? | PRD-004 | 🟢 Low | ⏳ Open |
| OQ-24 | ~~Referral reward — perpetual 10% or one-time?~~ | PRD-004 | — | ✅ **Resolved: Accumulative, code-based, DB-tracked** |
| OQ-25 | ~~Telegram button — bot or personal account?~~ | PRD-001, 002 | — | ✅ **Resolved: Personal account** |
| OQ-26 | ~~Payment methods?~~ | PRD-002 | — | ✅ **Resolved: Bank transfer** |


---

## Build Order Recommendation

```
Phase 1 — Foundation (Week 1-2)
  ┌─────────────────────────────────────────────┐
  │ PRD-003: Admin Panel                        │
  │  → MariaDB schema creation                  │
  │  → PHP auth + product CRUD                  │
  │  → Image upload                             │
  │  → Size manager                             │
  └────────────────────┬────────────────────────┘
                       ↓
Phase 2 — Customer-Facing (Week 2-3)
  ┌─────────────────────────────────────────────┐
  │ PRD-001: Customer Storefront                │
  │  → Static HTML + Alpine.js                  │
  │  → PHP product API                          │
  │  → Product cards + detail page              │
  │  → Filters + size selector                  │
  └────────────────────┬────────────────────────┘
                       ↓
Phase 3 — Commerce (Week 3-4)
  ┌─────────────────────────────────────────────┐
  │ PRD-002: Conversational Handoff             │
  │  → WhatsApp wa.me link generation           │
  │  → Pre-filled message templates             │
  │  → Seller status API + polling              │
  │  → Telegram bot webhook (PHP)               │
  └────────────────────┬────────────────────────┘
                       ↓
Phase 4 — Growth (Month 2+)
  ┌─────────────────────────────────────────────┐
  │ PRD-004: Marketing & Retention              │
  │  → Google Analytics 4 integration          │
  │  → CRM Google Sheet setup                  │
  │  → Referral process + templates            │
  │  → WA Broadcast list setup                 │
  └─────────────────────────────────────────────┘
```

---

## Brainstorming Source

All PRDs derived from the brainstorming session:
- **File:** `_bmad-output/brainstorming/brainstorming-session-2026-04-19-2357.md`
- **Ideas generated:** 175 across 8 domains
- **Mind map:** `_bmad-output/brainstorming/kidstore-mindmap.png`

---

*PRD Suite version 1.3 — Status: ✅ FULLY APPROVED — Zero blocker OQs — Ready for CE (Epics & Stories)*
