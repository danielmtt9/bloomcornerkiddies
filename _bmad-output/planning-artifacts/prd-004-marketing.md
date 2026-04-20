---
stepsCompleted: [step-01-init, step-02-discovery, step-03-goals, step-04-features, step-05-requirements, step-06-acceptance]
inputDocuments:
  - _bmad-output/brainstorming/brainstorming-session-2026-04-19-2357.md
workflowType: prd
prdId: PRD-004
version: 1.2
status: draft
date: 2026-04-20
decisions:
  - referral_discount_percent: configurable_by_seller
  - referral_reward_type: accumulative_code_based_db_tracked
author: Danielaroko
---

# PRD-004: Marketing & Retention System
## Kids Clothes Conversational Commerce — bloomrocxx

**Author:** Danielaroko
**Date:** 2026-04-20
**Version:** 1.2
**Status:** Draft

---

## 1. Overview

### 1.1 Product Summary

The Marketing & Retention System covers all activities that bring parents to the storefront and bring past buyers back. Unlike typical e-commerce marketing, this store operates through **zero-cost, high-warmth channels** — WhatsApp broadcasts, parent group seeding, referral word-of-mouth, and personal follow-up CRM.

This PRD defines:
- WhatsApp broadcast list strategy and mechanics
- **Code-based referral system** with DB tracking and configurable discounts
- CRM follow-up framework ("Remember Me")
- VIP tier mechanics
- Social media content pipeline
- School calendar demand strategy

**Tech note:** The referral system is the one component of PRD-004 that requires software — a `referral_codes` and `referral_uses` database schema, plus admin panel management. All other PRD-004 features remain process-driven.

### 1.2 Marketing Philosophy

> "Every sale plants a seed. Water it at the right time and it grows two more."

This store grows through **trust networks** — parents referring parents in WhatsApp groups, school groups, and family chats. Paid advertising is optional and secondary. Relationship is the product.

### 1.3 Target Users

**New Parent (First-Time Buyer):** Arrives via referral, social media, or WhatsApp group link. Doesn't know the seller yet. Needs to see trust signals quickly.

**Returning Parent:** Has bought before. Knows the quality. Needs timely re-engagement at the right moment (child has grown, holiday coming, new stock arrived).

**Brand Ambassador Parent:** Loves the store, refers others organically. Needs to be recognized and rewarded.

---

## 2. Goals & Success Metrics

| Goal | Metric | Target / Benchmark |
|------|--------|-------------------|
| Referral acquisition | New customers who use a valid referral code | ≥ 30% of new buyers in month 3 |
| Repeat purchase rate | % of past buyers who purchase again within 3 months | ≥ 40% |
| Broadcast list growth | Active WA broadcast subscribers | 100 in month 1 → 500 in month 6 |
| WA Group penetration | Active parent WhatsApp groups with the store link | 5 groups by month 2 |
| "Remember Me" conversion | % of follow-up messages that result in a purchase | ≥ 20% |

---

## 3. User Stories

### 3.1 Referral System

| ID | As a... | I want to... | So that... | Priority |
|----|---------|--------------|------------|----------|
| US-73 | Happy parent | Receive my own unique referral code to share | I can track my referrals and earn rewards | P0 |
| US-74 | New buyer | Enter a referral code when ordering | I get a discount off my first order | P0 |
| US-75 | Seller | See all referral codes and how many successful referrals each has | I track who my top advocates are | P0 |
| US-76 | Power referrer | Earn accumulative rewards as my referral count grows | I'm rewarded proportionally for my advocacy | P0 |
| US-90 | Seller | Configure the referral discount % in admin | I can adjust the offer without developer help | P1 |
| US-91 | Seller | Enable or disable the referral system per campaign | I can turn it on/off flexibly | P1 |

### 3.2 WhatsApp Broadcast

| ID | As a... | I want to... | So that... | Priority |
|----|---------|--------------|------------|----------|
| US-77 | Subscribed parent | Receive new arrival announcements before the general public | I get first pick of new stock | P1 |
| US-78 | Subscribed parent | Receive broadcast messages that feel personal (not group-blast) | I feel valued, not spammed | P0 |
| US-79 | Seller | Send new stock photos + link to a curated list of interested parents | I reach warm leads instantly | P0 |

### 3.3 "Remember Me" CRM Follow-Up

| ID | As a... | I want to... | So that... | Priority |
|----|---------|--------------|------------|----------|
| US-80 | Past buyer | Receive a check-in message when my child has likely grown out of their last purchase | I re-order before the next season | P1 |
| US-81 | Past buyer | Receive a birthday message for my child | I feel remembered and valued | P1 |
| US-82 | Past buyer | Receive a seasonal message ("Summer is coming!") relevant to my child's size | I shop at exactly the right time | P1 |
| US-83 | First-time buyer | Hear from the seller again after my first order | I'm reminded the store exists when I need it | P1 |

### 3.4 VIP Tier

| ID | As a... | I want to... | So that... | Priority |
|----|---------|--------------|------------|----------|
| US-84 | Frequent buyer (3+ orders) | Receive VIP access to new stock before it goes public | I get the best selection | P1 |
| US-85 | VIP parent | Feel recognized and valued above regular customers | I stay loyal to this store | P1 |
| US-86 | Seller | Know who my VIP customers are and message them first | I reward loyalty and drive repeat sales | P1 |

### 3.5 Social & Content

| ID | As a... | I want to... | So that... | Priority |
|----|---------|--------------|------------|----------|
| US-87 | Seller | Post an "Outfit of the Week" consistently | I maintain social presence and drive link clicks | P1 |
| US-88 | New parent | See clothing on real kids before buying | I'm more confident purchasing | P1 |
| US-89 | Active parent | Share a beautiful digital card from the store to my WA group | I refer friends easily | P1 |

---

## 4. Functional Requirements

### 4.1 Referral System (Code-Based, DB-Tracked)

> **Architecture note:** The referral system is upgraded from a manual name-based system to a **database-backed, code-based system**. Each customer receives a unique referral code. Codes are managed in the admin panel, tracked accumlatively in MariaDB, and the discount % is seller-configured.

**FR-61 — Referral Code Generation**
- Each customer who completes their first order is eligible for a unique referral code
- Code format: `BCK-[NAME][3-digit-random]` e.g. `BCK-SARAH042` or `BCK-AMAKA719`
- Codes generated manually by seller from admin panel (PRD-003 FR-61A)
- Code stored in `referral_codes` table (see §4.1.1 DB Schema)
- Seller shares the code with the customer via WhatsApp after their first purchase

**FR-62 — Referral Code in WhatsApp Pre-Fill**
- The WhatsApp enquiry message template (PRD-002 FR-23) includes an optional referral code field:
```
Hi! 👋 I'm interested in:
*{product_name}*
Price: ₦{price}
[Referral code: ___________]    ← optional, fill in if you have one
```
- Storefront shows a banner or prompt: "Have a referral code? Add it to your message for a discount! 💛"

**FR-63 — Referral Code Redemption & Tracking**
- When a new buyer mentions a referral code in their WhatsApp message:
  1. Seller looks up the code in admin panel → finds the referrer
  2. Seller records the redemption in admin: marks code as used by new buyer
  3. System increments `referral_codes.total_referrals` and inserts row in `referral_uses`
  4. Discount applied to new buyer's order (`seller_config.referral_discount_percent`%)
  5. Referrer notified via WhatsApp: "[New Buyer Name] used your code! You’ve earned another referral reward 🎉"
- If discount % = 0 or NULL in `seller_config`, referral tracking still works but no discount is given

**FR-64 — Accumulative Referral Rewards**
- Referral rewards are **accumulative** — the referrer earns credit with every successful referral, not just the first
- Seller defines reward thresholds manually (e.g. "5 referrals = free item", "10 referrals = 20% off")
- Seller can view each customer’s total referral count in admin panel and decide when to reward
- No automated reward distribution — seller messages the referrer manually when a milestone is hit
- Reward history visible in admin: referrer name, WA, total referrals, date of each referral

**FR-65 — Referral Share Card (Design Deliverable)**
- A branded image (created in Canva) that parents can screenshot and share in WhatsApp groups
- Text: "Shop Bloom Corner Kiddies 💛 Use my code [BCK-SARAH042] for a discount!"
- **This is a design deliverable, not a software feature.** The seller creates a personalised card per customer using a Canva template.
- Canva template: one reusable design, seller types in the customer’s unique code before sending

**FR-66A — Referral Discount Configuration**
- Seller configures the referral discount % in Admin → Settings (`seller_config.referral_discount_percent`)
- Valid values: `NULL` (feature off — no discount) or any integer 1–100
- Seller can change the % at any time without developer help
- When set to NULL: referral codes still generate and track, but no discount message is shown to new buyers
- Displayed in admin as: “Referral Discount: [___]% (leave blank to disable discount)”

#### 4.1.1 Referral Database Schema

```sql
-- Referral codes issued to customers
CREATE TABLE referral_codes (
  id               INT AUTO_INCREMENT PRIMARY KEY,
  code             VARCHAR(50) UNIQUE NOT NULL,          -- e.g. BCK-SARAH042
  referrer_name    VARCHAR(255) NOT NULL,               -- customer’s name
  referrer_wa      VARCHAR(20) NOT NULL,                -- WA number (international, no +)
  total_referrals  INT DEFAULT 0,                       -- accumulative count of successful uses
  status           ENUM('active','inactive') DEFAULT 'active',
  created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Each individual use of a referral code
CREATE TABLE referral_uses (
  id               INT AUTO_INCREMENT PRIMARY KEY,
  code             VARCHAR(50) NOT NULL,                -- FK → referral_codes.code
  new_buyer_name   VARCHAR(255),                        -- the new buyer who used the code
  new_buyer_wa     VARCHAR(20),                         -- new buyer’s WhatsApp number
  discount_percent TINYINT DEFAULT 0,                   -- % applied at time of use (snapshot)
  created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (code) REFERENCES referral_codes(code) ON DELETE CASCADE
);
```

**`seller_config` additions:**
| Key | Type | Value |
|-----|------|-------|
| `referral_discount_percent` | TINYINT NULL | NULL = off; integer = active discount % |

### 4.2 WhatsApp Broadcast List

**FR-66 — Broadcast List Strategy**
- Use WhatsApp Business **Broadcast Lists** (not groups) — messages feel personal, not group-blast
- Build multiple lists by segment: "Girls 0-3Y", "Boys 3-7Y", "All Customers", "VIP"
- Parents who buy or enquire are asked: "Want to be notified when new stock arrives? 💛" → if yes, added to appropriate list

**FR-67 — Broadcast Message Templates**

New Stock Arrival:
```
Hi [First Name] 💛

New arrivals just dropped! 🎉

[Product 1] — ₦X,XXX
[Product 2] — ₦X,XXX
[Product 3] — ₦X,XXX

Browse all new items: [storefront URL]

Reply "INTERESTED" or ask me anything! 😊
```

Flash Sale:
```
Hi [First Name] 💛

FLASH SALE today only! ⚡
Up to 30% off selected items 🎀

[storefront URL]

Sale ends midnight tonight!
```

**FR-68 — Broadcast Frequency**
- New stock: when it arrives (not more than 2× per week to avoid fatigue)
- Flash sales: when applicable
- Seasonal: at school calendar events (resumption, end of term, Christmas, Eid, Easter)
- Maximum: 2 broadcasts per week

**FR-69 — UTM Tracking on Broadcast Links**
- All links in broadcasts use UTM parameters: `?utm_source=whatsapp&utm_medium=broadcast&utm_campaign={campaign_name}`
- Google Analytics 4 tracks which broadcasts drive actual storefront visits
- Seller reviews monthly to see which broadcasts perform best

### 4.3 "Remember Me" CRM System

**FR-70 — CRM Google Sheet Structure**

A simple Google Sheet maintained by the seller with these columns:

| Column | Values |
|--------|--------|
| Parent Name | First name + last name |
| WhatsApp Number | International format |
| Child Name | Optional |
| Child Age/Size | e.g. "4Y, Size 4" at time of purchase |
| Last Order Date | Date |
| Last Product Bought | Name |
| Next Follow-Up Date | Date (auto-calculated: +3 months from order) |
| Notes | VIP? Gift buyer? Specific preferences? |
| Referral By | Who referred them |
| Has Referred | Names of people they referred |

**FR-71 — Follow-Up Trigger Types**

| Trigger | When | Message Tone |
|---------|------|-------------|
| Size-Up Reminder | 3 months after last order | "Your little one must be growing! 🌱 New sizes just arrived..." |
| Birthday Outreach | 1 week before child's birthday (if recorded) | "Happy birthday week to your little one! 🎂 We have gorgeous birthday outfit pieces..." |
| Seasonal Reach-Out | School resumption, Christmas, Eid, March/April, July/August | "Summer is coming! ☀️ Does [child's name] need new light pieces?" |
| Post-First-Purchase | 2 weeks after first order | "Hope your little one is loving their new [item]! 💛 We just got more new pieces..." |
| New Drop For VIP | Before public broadcast | "[Name], you get first pick! 🌟 New stock drops publicly tomorrow — want to grab something first?" |

**FR-72 — Message Personalization Standard**
- Always use parent's first name in opening
- Reference child by name when known
- Reference previous purchase when relevant
- Maximum warmth, minimum sales pressure
- Each message personalized individually — NOT a mass template blast

**FR-73 — CRM Update Process**
- After every completed sale: seller adds/updates row in Google Sheet
- Every Monday: review "Next Follow-Up Date" column → message anyone due this week
- Time investment: ~30 minutes per week for 50 active customers

### 4.4 VIP Tier

**FR-74 — VIP Qualification**
- Automatic: parent who has completed 3+ orders becomes VIP
- Tracked in CRM sheet (Count of orders per parent)
- Seller sends manual VIP welcome message on their 3rd order:
```
"Hi [Name]! 🌟 You're officially one of our VIP customers!

This means you get first look at new stock before anyone else.
Just a tiny thank-you for trusting us with your little one's wardrobe 💛"
```

**FR-75 — VIP First-Look Broadcast**
- VIP parents receive new stock notification 24 hours before the general broadcast
- This list is separate in WA Business broadcast lists
- Creates genuine exclusivity — VIPs can buy before sizes sell out

**FR-76 — VIP Label in WA Business**
- All VIP customers tagged with "VIP" label in WA Business App
- Enables easy filtering when composing VIP-only broadcast

### 4.5 Social & Content Pipeline

**FR-77 — Outfit of the Week**
- Weekly content: one outfit photo posted to Instagram/TikTok/WhatsApp Status
- Photo: child wearing the item (with parent permission) OR product flat-lay on neutral background
- Caption: product name + price in ₦ + CTA: "Link in bio 👆" or "Message me to order 💛"
- Frequency: once per week minimum

**FR-78 — "Seen on Real Kids" Gallery**
- Storefront section (homepage or separate page) showing customer-submitted photos
- Parents opt-in: "Share a photo of your little one wearing [item] and we'll feature them! 💛"
- Photos collected via WhatsApp → seller uploads to storefront via admin panel
- Section shown only if ≥ 3 customer photos are available
- **Note:** This feature is listed as "Could Have" in PRD-001 and does not yet have a Functional Requirement written there. The FR will be added to PRD-001 when this feature is prioritized for a future sprint.

**FR-79 — School Calendar Strategy**

Key demand peaks in the Nigerian school calendar:

| Event | Timing | Marketing Action |
|-------|--------|-----------------|
| New Term Resumption | Jan, May, Sep | Broadcast "Back to School" → School Wear category |
| End of Term | Mar, Jul, Dec | Broadcast "Holiday Outfits" → Occasions + Girls/Boys |
| Christmas | Dec 20–26 | Flash sale + special occasion push |
| Eid ul-Fitr | Variable | Traditional/occasion outfits broadcast |
| Eid ul-Adha | Variable | Same as above |
| Easter | March/April | Party/special occasion items |
| Children's Day | May 27 | Full broadcast + special pricing |

**FR-80 — UTM Link Structure**
All storefront links shared in marketing use UTM parameters:

| Source | UTM Example |
|--------|-------------|
| WhatsApp Broadcast | `?utm_source=whatsapp&utm_medium=broadcast&utm_campaign=new-drop-apr` |
| Instagram Bio | `?utm_source=instagram&utm_medium=social&utm_campaign=bio` |
| TikTok | `?utm_source=tiktok&utm_medium=social&utm_campaign=bio` |
| Parent WA Group | `?utm_source=whatsapp&utm_medium=group&utm_campaign=referral` |
| Direct Share Card | `?utm_source=sharecard&utm_medium=referral` |

---

## 5. Operational Constraints

> **Note:** PRD-004 describes a process-and-content-driven marketing strategy. Traditional software NFRs do not directly apply. Instead, the following section defines operational constraints that govern how the marketing system operates.

### 5.1 Zero Technology Dependency

The marketing system operates entirely on free, existing tools:
- **WhatsApp Business App** (free, already used for sales)
- **Google Sheets** (free, accessible on phone)
- **Canva** (free tier, for share cards and promotional graphics)
- **Google Analytics 4** (free, one script tag on storefront)

No additional software licences, SaaS tools, or automation platforms required for MVP.

### 5.2 Seller Time Budget *(Guideline, Not Requirement)*

Expected weekly time investment at launch scale (~50 active customers):

> This is a planning guideline. Actual time may vary.

| Activity | Estimated Time Per Week | Frequency |
|----------|--------------|-----------|
| CRM follow-up messages | 30 min | Weekly |
| Broadcast composition | 20 min | Per broadcast (max 2×/week) |
| Outfit of the Week content | 30 min | Weekly |
| VIP management | 10 min | As needed |
| CRM sheet update | 5 min per sale | Per sale |

**Total marketing time:** ~1.5 hours/week at launch scale (50 active customers)

### 5.3 WhatsApp Broadcast Compliance

- WA Business broadcast lists: max 256 contacts per list
- Parents must have the seller's number saved in their contacts to receive broadcasts
- Ask every new customer: "Save my number first so I can send you new drops! 💛"
- Multiple lists can be created for scale

---

## 6. Message Templates Library

### 6.1 New Customer Post-Purchase

```
Hi [Name]! 💛

Thank you so much for your order! 🎀
Your [product] is all packed up and on its way.

I hope your little one loves it!

I’d love to share something with you — here’s your personal referral code:

⭐ *[BCK-NAME123]*

Share this code with any parent friend when they’re ordering — they’ll get a discount,
and every successful referral earns you rewards too! The more you share, the more you earn 🎉

[Store URL]

Talk soon 😊
```

### 6.2 3-Month Size-Up Follow-Up

```
Hi [Name]! 💛

Can't believe [child's name] is already [3 months older]! They must be growing so fast 🌱

I just got new stock in — some beautiful pieces that would be perfect for them now.

Would love to find something for your next season 😊
[storefront URL]

What sizes are you looking for now?
```

### 6.3 Birthday Outreach

```
Hi [Name]! 🎂

[Child's name]'s birthday is coming up so soon! 🎉

We have the most gorgeous birthday outfit pieces right now —
special occasion, party-ready, and SO beautiful 💛

Would you like me to find something extra special for the big day? 😊
[storefront URL]
```

### 6.4 VIP First-Look

```
[Name]! 🌟

New stock arrives publicly TOMORROW — but as one of our VIP customers,
you get first pick today 💛

[Product 1] — ₦X,XXX ← [image]
[Product 2] — ₦X,XXX ← [image]

These WILL sell out fast.
Reply here or click to the store: [storefront URL]

Thank you for always trusting us with your little one's wardrobe 💛
```

---

## 7. Acceptance Criteria

### AC-024: Referral Code Visible in WhatsApp Pre-Fill

```
GIVEN a parent taps "Order via WhatsApp" on any product
THEN the pre-filled message includes an optional referral code line:
  "[Referral code: ___________]"
AND the storefront shows a prompt: "Have a referral code? Add it to your message for a discount! 💛"
```

### AC-024B: Referral Code Redemption in Admin

```
GIVEN a new buyer provides referral code "BCK-SARAH042" in their WA message
WHEN the seller opens the admin panel and records the redemption
THEN referral_codes.total_referrals for BCK-SARAH042 increments by 1
AND a row is inserted in referral_uses with:
  - code = BCK-SARAH042
  - new_buyer_name and new_buyer_wa
  - discount_percent = current seller_config.referral_discount_percent
AND the admin shows the updated referral count for BCK-SARAH042
```

### AC-024C: Referral Discount % Configuration

```
GIVEN the seller sets referral_discount_percent = 15 in Admin → Settings
THEN new referral_uses records store discount_percent = 15
AND the seller can change this at any time without code changes

GIVEN the seller sets referral_discount_percent = NULL (blank)
THEN referral tracking continues to work
AND no discount amount is communicated to new buyers
```

### AC-025: UTM Links Trackable in GA4

```
GIVEN a parent visits the storefront via a UTM-tagged broadcast link
THEN Google Analytics 4 records:
  - Source: whatsapp
  - Medium: broadcast
  - Campaign: {campaign_name}
AND this data is visible in GA4 reports within 24 hours
```

### AC-026: CRM Sheet — Follow-Up Visibility

```
GIVEN the CRM Google Sheet has a "Next Follow-Up Date" column
WHEN the seller opens the sheet on Monday morning
THEN they can see all parents due for follow-up that week
AND the "Next Follow-Up Date" is pre-calculated as 3 months after "Last Order Date"
```

### AC-027: VIP Identification

```
GIVEN a parent has completed their 3rd order
THEN the seller identifies them in the CRM sheet (Order Count column = 3)
AND the seller manually sends the VIP welcome message
AND labels the parent "VIP" in WhatsApp Business
AND adds them to the VIP broadcast list
```

---

## 8. Open Questions

| # | Question | Impact | Priority | Status |
|---|----------|--------|----------|--------|
| OQ-19 | ~~What is the referral discount %?~~ | — | — | ✅ **Resolved: Configurable by seller in admin (NULL = off)** |
| OQ-20 | Should the referral prompt be a storefront banner or just in WA message pre-fills? | Storefront design | Medium | ⏳ Open |
| OQ-21 | What school zone is the primary customer base in? (Lagos, Abuja, PH?) | Calendar targeting | Medium | ⏳ Open |
| OQ-22 | Should the store have an Instagram account from day one? | Content pipeline | Medium | ⏳ Open |
| OQ-23 | Is TikTok a primary or secondary channel? | Content priority | Low | ⏳ Open |
| OQ-24 | ~~What is the referral reward structure?~~ | — | — | ✅ **Resolved: Accumulative, code-based, DB-tracked** |

---

## 9. Implementation Priority (MoSCoW)

### Must Have (Day 1 — Launch)
- WhatsApp Broadcast list setup (WA Business App)
- CRM Google Sheet template (basic columns)
- Post-purchase thank-you message template with unique referral code mention
- Referral code generation in admin panel
- `referral_codes` + `referral_uses` DB tables
- Referral code field in WA pre-fill message template
- Referral discount % config in admin settings

### Should Have (Month 1)
- UTM tracking links on all broadcasts
- Google Analytics 4 installed on storefront
- "Remember Me" 3-month follow-up in CRM calendar
- VIP tier tracking in CRM
- Storefront referral code prompt/banner

### Could Have (Month 2–3)
- "Seen on Real Kids" gallery on storefront
- Share card graphics (Canva — personalised per customer with their code)
- Birthday tracking in CRM
- School calendar broadcast schedule pre-planned
- Automated referral milestone notifications (e.g. "you’ve hit 5 referrals!")

### Won't Have (This Version)
- Automated drip campaigns (ManyChat, etc.)
- Paid Instagram/Facebook advertising management
- Email marketing
- Self-serve referral code lookup by customers

---

## 10. Dependencies

| Dependency | Type | Notes |
|------------|------|-------|
| PRD-001 (Storefront) | Downstream | UTM links require live storefront URL; referral code prompt displayed |
| PRD-003 (Admin Panel) | Upstream | Referral code management + discount config live in admin panel |
| MariaDB `referral_codes` | Data | Code-based referral system requires DB tables |
| MariaDB `referral_uses` | Data | Accumulative reward tracking requires uses table |
| Google Analytics 4 | External | Must be configured on storefront |
| WhatsApp Business App | External | Required for broadcast lists + labels |
| Google Sheets | External | CRM follow-up tracker (non-referral features) |
| Canva (free) | External | Share cards personalised per customer with their referral code |

---

## 11. Appendix: CRM Google Sheet Template

**Columns:**
`Parent Name | WA Number | Child Name | Child Age | Last Size Bought | Last Product | Last Order Date | Next Follow-Up | VIP? | Referred By | Has Referred (count) | Notes`

**Formulas:**
- `Next Follow-Up Date` = `=IF(G2<>"", EDATE(G2, 3), "")` (3 months after last order)
- `Has Referred Count` = manually incremented by seller

**Conditional Formatting:**
- Red: Follow-up date < today (overdue)
- Yellow: Follow-up date = this week
- Green: VIP rows

---

*PRD-004 of 4 — End of PRD Suite*
