---
stepsCompleted: [step-01-init, step-02-discovery, step-03-goals, step-04-features, step-05-requirements, step-06-acceptance]
inputDocuments:
  - _bmad-output/brainstorming/brainstorming-session-2026-04-19-2357.md
workflowType: prd
prdId: PRD-002
version: 1.1
status: draft
date: 2026-04-20
decisions:
  - telegram_button: personal_account
  - save_for_later: whatsapp_saved_messages
author: Danielaroko
---

# PRD-002: Conversational Commerce Handoff
## Kids Clothes Conversational Commerce — bloomrocxx

**Author:** Danielaroko
**Date:** 2026-04-20
**Version:** 1.1
**Status:** Draft

---

## 1. Overview

### 1.1 Product Summary

The Conversational Commerce Handoff is the bridge between storefront browsing and personal purchase. Every product in the storefront links to either WhatsApp or Telegram, passing full product context (name, size, price in ₦) in a pre-filled message. The seller completes the sale through personal conversation — no cart, no checkout on the site.

This PRD covers:
- WhatsApp `wa.me` link generation and pre-filled message templates
- WhatsApp Business App configuration and usage
- Telegram bot (PHP webhook) for automated order intake as a standalone channel
- Telegram button on storefront links to **seller's personal Telegram** (not the bot)
- Sold-out "Notify Me" flows
- Seller online status communication

### 1.2 Problem Statement

When a parent clicks "buy", a cold handoff — no product context, no pre-filled info — creates friction and drop-off. Parents don't want to retype product details; sellers don't want to hunt for what was seen.

The handoff must feel **instant, warm, and pre-loaded** — like the storefront already introduced the product to the seller on the parent's behalf.

### 1.3 Target Users

**Parent (Buyer):** Wants to reach the seller immediately with minimal typing. Expects to be recognized as having shown interest in a specific item.

**Seller (Danielaroko):** Wants every incoming WhatsApp/Telegram message to arrive with full product context so they can respond intelligently within seconds.

### 1.4 Confirmed Seller Config

| Property | Value |
|----------|-------|
| **Store Name** | Bloom Corner Kiddies |
| **WA Number** | `2349049308656` |
| **wa.me link** | `https://wa.me/2349049308656` |
| **Telegram link** | `https://t.me/+2349049308656` |
| **Payment** | Bank transfer (seller provides account details via WA) |
| **Delivery info** | TBD — placeholder, confirm before launch |

---

## 2. Goals & Success Metrics

| Goal | Metric | Target |
|------|--------|--------|
| Frictionless handoff | % of WA button taps that result in sent messages | ≥ 80% |
| Pre-filled accuracy | All pre-filled messages contain correct product name, size, ₦ price | 100% |
| Telegram bot completion rate | % of Telegram bot conversations where Step 7 (order confirmation sent) is reached | ≥ 60% |
| Notify Me capture rate | % of sold-out product views that produce a WA notify message | Measurable |

---

## 3. User Stories

### 3.1 WhatsApp Handoff

| ID | As a... | I want to... | So that... | Priority |
|----|---------|--------------|------------|----------|
| US-30 | Parent | Tap "Order via WhatsApp" and have the product details pre-filled | I don't have to retype what I want | P0 |
| US-31 | Parent | My selected size included in the WA pre-fill | The seller knows exactly what I want | P0 |
| US-32 | Parent | The WA message to feel warm and personal, not robotic | The conversation starts naturally | P1 |
| US-33 | Parent | WA button opens WhatsApp app on mobile (not browser) | Zero friction tap-to-chat | P0 |
| US-34 | Seller | Receive a message that immediately tells me: what, size, price | I respond intelligently in < 10 seconds | P0 |
| US-35 | Parent | "Ask a Question" button with different pre-fill | I can enquire without committing to order | Won’t Have |

### 3.2 Telegram Bot

| ID | As a... | I want to... | So that... | Priority |
|----|---------|--------------|------------|----------|
| US-36 | Parent | An alternative to WhatsApp for ordering | I use my preferred messaging app | P1 |
| US-37 | Parent | Telegram bot to collect my order details (name, size, address) | I complete the order without back-and-forth | P1 |
| US-38 | Parent | Receive an order confirmation in Telegram | I know my order was received | P1 |
| US-39 | Seller | Receive a clean order summary in Telegram DM | I process the order without confusion | P1 |
| US-40 | Parent | Bot ask if this is a gift and offer a gift message | I request premium gift packaging | P2 |

### 3.3 Sold-Out & Notifications

| ID | As a... | I want to... | So that... | Priority |
|----|---------|--------------|------------|----------|
| US-41 | Parent | "Notify Me" button on sold-out products | I'm alerted via WhatsApp when stock returns | P1 |
| US-42 | Seller | Notify messages clearly state which product/size parent is waiting for | I proactively message the right parent when stock arrives | P1 |

### 3.4 Seller Status & Availability

| ID | As a... | I want to... | So that... | Priority |
|----|---------|--------------|------------|----------|
| US-43 | Parent | See seller's online status before messaging | I know if I'll get a quick reply | P1 |
| US-44 | Seller | Toggle my status from admin panel | Parents see accurate availability | P1 |
| US-45 | Parent | See estimated response time | I set correct expectations | P1 |

---

## 4. Functional Requirements

### 4.1 WhatsApp Pre-Filled Message System

**FR-21 — wa.me Link Generation**
- All WhatsApp buttons use `https://wa.me/{phone}?text={encoded_message}` format
- `{phone}` = WhatsApp Business number from `seller_config.wa_number` (international format, no +, no spaces — e.g. `2348012345678`)
- `{encoded_message}` = URL-encoded message string
- No WhatsApp Business API required — standard `wa.me` links are free and sufficient
- On mobile: opens WhatsApp app directly
- On desktop: opens web.whatsapp.com

**FR-22 — Message Template: Order (Size Selected)**
```
Hi! 👋 I'd like to order:

*{product_name}*
{if brand}Brand: {brand}{/if}
Size: {selected_size}
Price: ₦{price}{if on_sale} (was ₦{original_price}){/if}

Please confirm availability. Thank you! 🙏
```

**FR-23 — Message Template: Enquiry (No Size Selected)**
```
Hi! 👋 I'm interested in:

*{product_name}*
{if brand}Brand: {brand}{/if}
Price: ₦{price}
Category: {category} · {gender}

Can you help me choose the right size? My child is [age]. 🙏
```

**FR-24 — Message Template: Size Enquiry Button**
- Optional second button on detail page: "📏 Ask about sizing"
- Pre-fill:
```
Hi! 👋 I'd like help choosing the right size for:

*{product_name}*
Price: ₦{price}

My child is [age / height / weight]. What size would you recommend? 🙏
```

**FR-25 — Message Template: Notify Me (Sold Out)**
```
Hi! 👋 Please notify me when this item is back in stock:

*{product_name}*
{if size_selected}Size: {size}{/if}
Price: ₦{price}

Thank you! 🙏
```

**FR-26 — Message Template: Size Guide Help**
- Triggered from "Not sure of size? Ask me!" inside the size guide accordion
```
Hi! 👋 Can you help me pick the right size for:

*{product_name}*

My child is [age]. 🙏
```

**FR-27 — Branded WhatsApp Link**
- Use WA.Link (`wa.link/storeshortname`) or custom short link as public-facing URL (hides raw phone number)
- Raw `wa.me/{number}` used in all generated links on the storefront
- Short branded link used in marketing materials (PRD-004)

### 4.2 WhatsApp Business App Configuration

**FR-28 — WA Business Profile**
- Business name, category ("Kids Clothing"), and description set in WA Business App
- Profile photo = seller's professional photo (same as homepage hero)
- Business hours set to reflect actual response availability

**FR-29 — Away Message**
- Automatic away message when seller is offline:
  > "Hi! Thanks for reaching out to Bloom Corner Kiddies 💛 I’m not available right now but I reply to every message. I’ll be back shortly!"
- Away message activated during non-business hours

**FR-30 — Quick Replies (WA Business)**
- Pre-configured quick reply templates for common responses:
  - `/available` → "Yes! This item is available in your size 🎉 Shall we confirm your order?"
  - `/sizes` → Paste size guide
  - `/delivery` → "Delivery info: Lagos ₦1,500 | Nationwide via GIG ₦2,500–₦4,500" *(update with actual rates)*
  - `/payment` → "Payment is by **bank transfer** 🏦 I’ll send you the account details when we confirm your order! 💛"
  - `/thanks` → "Thank you so much for your order! 🎀 Your little one is going to look amazing. I’ll keep you updated on dispatch 💛"

**FR-31 — Customer Labels (WA Business)**
- Tag customers in WA Business: "New Customer", "Repeat Buyer", "VIP", "Awaiting Payment", "Order Dispatched"

### 4.3 Telegram Bot (PHP Webhook)

**FR-32 — Telegram Bot Setup**
- Bot created via @BotFather on Telegram
- Bot token stored in `config.php` (`TELEGRAM_BOT_TOKEN`)
- Webhook registered: `https://yourdomain.com/telegram-webhook.php`
- Telegram sends all bot messages as POST to the webhook PHP file

**FR-33 — Order Intake Flow**
The bot runs a sequential conversation to collect order details:

```
Step 1: Welcome
Bot: "Hi! 👋 Welcome to [Store Name]. What would you like to order?"
     [User arrives from storefront with product pre-context if deep-linked]

Step 2: Size (if not pre-filled)
Bot: "What size would you like?"
     [Shows available sizes for the product as buttons/options]

Step 3: Name
Bot: "What's your name? 😊"

Step 4: Delivery Address
Bot: "Great [name]! Where should we deliver to?"

Step 5: Gift Check
Bot: "Is this a gift? 🎁"
     [YES] → "Would you like to include a gift message?"
     [NO]  → Proceed to confirmation

Step 6: Confirmation
Bot: "Here's your order summary:
     ━━━━━━━━━━━━━━━
     📦 {product_name}
     📏 Size: {size}
     💰 ₦{price}
     👤 {name}
     📍 {address}
     {if gift}🎁 Gift message: {message}{/if}
     ━━━━━━━━━━━━━━━
     Confirm? [YES / CHANGE]"

Step 7: Complete
Bot: "✅ Order received! I'll be in touch shortly with payment details. 💛"
     [Seller receives this summary as a Telegram notification]
```

**FR-34 — Seller Notification via Telegram**
- When order is completed in bot flow, seller receives a formatted message in their personal Telegram DM (or a dedicated channel)
- Message contains: product, size, name, address, gift flag, price in ₦
- Seller can mark as processed from Telegram

**FR-35 — Telegram Button — Personal Account Link**
- The storefront "Order via Telegram" button links to the **seller's personal Telegram account**: `https://t.me/{seller_telegram_username}`
- `seller_telegram_username` pulled from `seller_config.telegram_username`
- No bot deep-link or `?start=` parameter from storefront
- Parent initiates a natural personal conversation directly with the seller
- Telegram bot (FR-32 to FR-34) is a **standalone channel** — parents who discover the bot independently can use it, but the storefront button does not route there
- *(Future: A dedicated "Use Order Bot" link could be added as a separate P2 feature)*

**FR-36 — Gift Order Handling**
- If parent selects "Yes, it's a gift":
  - Bot asks for gift message (optional)
  - Order summary flags as 🎁 GIFT
  - Seller knows to use premium packaging (if offered)

### 4.4 Seller Online Status

**FR-37 — Status Display on Storefront**
- Status dot shown on homepage hero and product detail pages
- 🟢 "Online now" (status = `online`)
- 🟡 "Back soon" with custom message (status = `brb`)
- 🔴 "Away – replies within a few hours" (status = `offline`)

**FR-38 — Status API**
- `GET /api/status.php` returns:
```json
{
  "status": "online",
  "message": "Replies within 1 hour 💛"
}
```
- Storefront polls every 60 seconds, dot updates without page reload

**FR-39 — Status Update by Seller**
- Seller updates status via Admin Panel (PRD-003)
- One-tap toggle: Online → BRB → Offline → Online
- Change saves to `seller_config` table in MariaDB

---

## 5. Non-Functional Requirements

### 5.1 WhatsApp Link Behaviour

| Scenario | Expected Behaviour |
|----------|--------------------|
| Tap on Android (WhatsApp installed) | Opens WA app directly to chat |
| Tap on iPhone (WhatsApp installed) | Opens WA app directly to chat |
| Open on desktop browser | Opens web.whatsapp.com |
| WhatsApp not installed | Redirects to wa.me website with download prompt |

### 5.2 Telegram Bot Reliability

- Webhook mode (not polling) — no persistent server process required
- Telegram retries failed webhook deliveries for 24 hours
- Bot must respond to every webhook POST within 5 seconds; measured via Telegram API response logs on the web server
- If PHP errors: Telegram retries — bot must be idempotent on retry
- Bot conversation state managed via Telegram `chat_id` + MariaDB `bot_sessions` table

### 5.3 Pre-Filled Message Encoding

- All special characters in messages URL-encoded (PHP `urlencode()`)
- Emoji preserved in URL encoding
- Bold formatting: `*text*` (WhatsApp markdown, not HTML)
- Maximum message length: 4,096 characters (wa.me URL limit) — product info stays well within this

### 5.4 Hostinger Constraints

- Telegram bot: PHP webhook only (no Node.js persistent process)
- WhatsApp: `wa.me` links only (no WA Business API — no persistent server required)
- Bot session state: stored in `bot_sessions` MariaDB table

---

## 6. Database Schema Additions

### Table: `bot_sessions` (Telegram bot conversation state)

```sql
CREATE TABLE bot_sessions (
  chat_id      BIGINT        PRIMARY KEY,      -- Telegram chat ID
  step         VARCHAR(50)   DEFAULT 'start',  -- current conversation step
  product_id   INT           NULL,             -- product being ordered
  size_label   VARCHAR(20)   NULL,             -- selected size
  buyer_name   VARCHAR(255)  NULL,
  address      TEXT          NULL,
  is_gift      TINYINT(1)    DEFAULT 0,
  gift_message TEXT          NULL,
  updated_at   TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

---

## 7. Acceptance Criteria

### AC-011: WhatsApp Button Opens Pre-Filled Message

```
GIVEN a parent selects "3-4Y" size on Floral Summer Dress (₦5,500, was ₦8,000)
WHEN they tap "Order via WhatsApp"
THEN WhatsApp app opens (mobile) or web.whatsapp.com (desktop)
AND the chat is addressed to the seller's WA number
AND the pre-filled message reads:
  "Hi! 👋 I'd like to order:
   *Floral Summer Dress*
   Brand: Carter's
   Size: 3-4Y
   Price: ₦5,500 (was ₦8,000)
   Please confirm availability. Thank you! 🙏"
```

### AC-012: WhatsApp Without Size Selection

```
GIVEN a parent taps "Order via WhatsApp" without selecting a size
THEN the pre-filled message contains:
  - Product name
  - Price in ₦
  - Category and gender
  - Prompt for child's age
AND does NOT include "Size: undefined" or any blank field
```

### AC-013: Notify Me — Sold Out

```
GIVEN a product has all sizes sold out
AND parent taps "🔔 Notify Me"
THEN WhatsApp opens with pre-filled message:
  "Hi! 👋 Please notify me when this item is back in stock:
   *{product_name}*
   Price: ₦{price}
   Thank you! 🙏"
```

### AC-T04: Telegram Bot — Complete Flow

```
GIVEN a parent messages the Telegram bot
WHEN they complete Steps 1–7 sequentially (size, name, address, gift check, confirmation)
THEN "Step 7 reached" is the point counted as a completed bot conversation
AND bot sends a confirmation message to the parent
AND seller receives a Telegram notification with:
  - Product name and size
  - Buyer name and delivery address
  - Gift flag (if applicable)
  - Price in ₦
```

### AC-T05: Telegram Bot — Resilience

```
GIVEN the bot conversation PHP file throws an uncaught error
WHEN Telegram retries the webhook
THEN the bot handles the retry gracefully without duplicating the order
AND the parent sees a neutral response rather than an unhandled error
```

### AC-T06: Seller Status Display

```
GIVEN seller_config.seller_status = "online"
THEN homepage shows 🟢 green dot + "Online now"

GIVEN seller_config.seller_status = "offline"
THEN homepage shows 🔴 red dot + status message from seller_config.status_message

GIVEN seller_config.seller_status changes to "brb" while page is open
THEN within 60 seconds, status dot updates to 🟡 without page reload
```

---

## 8. Open Questions

| # | Question | Impact | Priority | Status |
|---|----------|--------|----------|--------|
| OQ-09 | What is the seller's WhatsApp Business number? | All wa.me links | Critical | ⏳ Open |
| OQ-10 | What is the seller's personal Telegram username? | Telegram button on storefront (`t.me/{username}`) | High | ⏳ Open |
| OQ-11 | Should the Telegram bot notify seller DM or a private channel? | FR-34 implementation | Medium | ⏳ Open |
| OQ-12 | ~~Should "Ask a Question" be a separate button?~~ | — | — | ✅ Resolved: Won't Have this version |
| OQ-13 | What languages does the bot need to support? (English only for now?) | Bot complexity | Low | ⏳ Open |

---

## 9. Implementation Priority (MoSCoW)

### Must Have (MVP)
- WhatsApp pre-filled message generation (FR-21 to FR-23)
- wa.me link integration on all product detail pages
- Notify Me WA message for sold-out items
- Seller online status API + display

### Should Have (Sprint 2)
- Telegram bot webhook with order intake flow
- Quick replies configured in WA Business App

### Could Have (Future)
- "Ask about sizing" dedicated WA button
- Gift order handling in Telegram bot
- WA Business API broadcast lists (upgrade from free tier)
- Telegram bot deep-link from storefront as secondary "Use Order Bot" button (P2)

### Won't Have (This Version)
- WhatsApp Business API (requires approval + monthly fee)
- Automated payment link generation
- Order tracking system
- "Ask a Question" dedicated storefront button (US-35)

---

## 10. Dependencies

| Dependency | Type | Notes |
|------------|------|-------|
| PRD-001 (Storefront) | Peer | WA/TG buttons live on product detail pages |
| PRD-003 (Admin Panel) | Upstream | Seller status toggle lives in admin |
| WhatsApp Business App | External | Seller must activate free WA Business account |
| Telegram @BotFather | External | Bot must be created and webhook registered |
| `config.php` | Config | WA number + TG token stored here |
| MariaDB `bot_sessions` | Data | Telegram bot state persistence |

---

*PRD-002 of 4 — Next: PRD-003 Product Admin Panel*
