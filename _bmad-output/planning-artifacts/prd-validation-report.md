---
validationTarget: '_bmad-output/planning-artifacts/ (all 4 PRDs)'
validationDate: '2026-04-20'
inputDocuments:
  - _bmad-output/planning-artifacts/prd-001-storefront.md
  - _bmad-output/planning-artifacts/prd-002-handoff.md
  - _bmad-output/planning-artifacts/prd-003-admin.md
  - _bmad-output/planning-artifacts/prd-004-marketing.md
  - _bmad-output/brainstorming/brainstorming-session-2026-04-19-2357.md
validationStepsCompleted: [discovery, format-detection, density-validation, traceability, nfr-check, cross-prd-consistency, open-questions]
validationStatus: APPROVED_REMEDIATED
remediationDate: '2026-04-20'
remediationDecisions:
  - telegram_button: personal_account
  - save_for_later: whatsapp_saved_messages  
  - photo_aspect_ratio: 4_5_portrait
---

# PRD Validation Report — bloomrocxx

**PRDs Validated:** 4 (PRD-001 through PRD-004)
**Validation Date:** 2026-04-20
**Validator:** John (PM) — BMAD Validation Architect
**Standard Applied:** BMAD PRD Quality Standard v6.3.0

---

## Input Documents Loaded

| Document | Status |
|----------|--------|
| prd-001-storefront.md | ✅ Loaded |
| prd-002-handoff.md | ✅ Loaded |
| prd-003-admin.md | ✅ Loaded |
| prd-004-marketing.md | ✅ Loaded |
| brainstorming-session-2026-04-19-2357.md | ✅ Loaded (source truth) |

---

## Format Detection

### PRD-001: Customer Storefront

| BMAD Core Section | Status | Equivalent Found |
|------------------|--------|-----------------|
| Executive Summary | ✅ Present | §1 Overview |
| Success Criteria | ✅ Present | §2 Goals & Success Metrics |
| Product Scope | ⚠️ Partial | §10 MoSCoW (implicit scope) — no explicit Product Scope section |
| User Journeys | ✅ Present | §3 User Stories |
| Functional Requirements | ✅ Present | §4 Functional Requirements |
| Non-Functional Requirements | ✅ Present | §5 Non-Functional Requirements |

**Classification:** BMAD Variant — 5/6 core sections present
**Additional Sections:** Design Specifications, Technical Architecture, Acceptance Criteria, Open Questions, MoSCoW, Dependencies, Revision History — all valuable additions

---

### PRD-002: Conversational Commerce Handoff

| BMAD Core Section | Status | Equivalent Found |
|------------------|--------|-----------------|
| Executive Summary | ✅ Present | §1 Overview |
| Success Criteria | ✅ Present | §2 Goals & Success Metrics |
| Product Scope | ⚠️ Partial | No explicit scope section |
| User Journeys | ✅ Present | §3 User Stories |
| Functional Requirements | ✅ Present | §4 Functional Requirements |
| Non-Functional Requirements | ✅ Present | §5 Non-Functional Requirements |

**Classification:** BMAD Variant — 5/6 core sections present

---

### PRD-003: Product Admin Panel

| BMAD Core Section | Status | Equivalent Found |
|------------------|--------|-----------------|
| Executive Summary | ✅ Present | §1 Overview |
| Success Criteria | ✅ Present | §2 Goals & Success Metrics |
| Product Scope | ⚠️ Partial | No explicit scope section |
| User Journeys | ✅ Present | §3 User Stories |
| Functional Requirements | ✅ Present | §4 Functional Requirements |
| Non-Functional Requirements | ✅ Present | §5 Non-Functional Requirements |

**Classification:** BMAD Variant — 5/6 core sections present

---

### PRD-004: Marketing & Retention System

| BMAD Core Section | Status | Equivalent Found |
|------------------|--------|-----------------|
| Executive Summary | ✅ Present | §1 Overview |
| Success Criteria | ✅ Present | §2 Goals & Success Metrics |
| Product Scope | ⚠️ Partial | No explicit scope section |
| User Journeys | ✅ Present | §3 User Stories |
| Functional Requirements | ✅ Present | §4 Functional Requirements |
| Non-Functional Requirements | ✅ Present | §5 Non-Functional Requirements |

**Classification:** BMAD Variant — 5/6 core sections present

---

## Validation Findings

---

### CHECK 1: Information Density

**Standard:** Every sentence carries information weight. Zero fluff. Anti-patterns eliminated.

#### PRD-001 — PASS ✅ (minor findings)

✅ Requirements are direct and specific
✅ User stories follow clean "I want to / so that" format
⚠️ **FINDING V-001:** FR-07 contains "Persistent header on all admin pages" — this is an admin concern, not storefront, and appears to have been mis-placed in PRD-001. Should be in PRD-003.
⚠️ **FINDING V-002:** §1.3 "Context" paragraph is slightly conversational ("Often sharing links with a partner"). Acceptable but slightly narrative.

#### PRD-002 — PASS ✅

✅ Message templates are precise and complete
✅ FR definitions are crisp
✅ Bot flow is clearly sequenced
✅ No filler language detected

#### PRD-003 — PASS ✅

✅ Design philosophy note ("10pm on phone") is intentional and adds context — acceptable
✅ Security table is concise and implementable
✅ Form field table is precise and complete

#### PRD-004 — PASS WITH CONDITIONS ⚠️

✅ Strategy rationale is well-stated
⚠️ **FINDING V-003:** §5.2 "Seller Time Budget" — the table is a projection, not a requirement. Should be moved to an appendix or noted as a guideline, not a NFR.
⚠️ **FINDING V-004:** Several FRs in PRD-004 are process descriptions, not measurable capabilities. E.g. FR-73: "After every completed sale: seller adds/updates row in Google Sheet" — this is a manual process instruction, not a functional requirement. Should be documented as an **Operational Procedure**, not an FR.

---

### CHECK 2: Measurability of Requirements (SMART)

**Standard:** All FRs and NFRs must be specific, measurable, attainable, relevant, traceable.

#### PRD-001 Findings

✅ FR-01: Product grid — specific breakpoints and conditions defined
✅ FR-18: "≤ 15KB JS payload" — measurable
✅ FR-19: "< 500ms response time" — measurable
⚠️ **FINDING V-005:** FR-08 says "at least 1 photo always present (enforced at admin upload)" — measurable rule, but the enforcement mechanism belongs in PRD-003, not PRD-001. PRD-001 can state the display requirement; PRD-003 enforces the upload rule.
⚠️ **FINDING V-006:** AC-010 "Time to First Contentful Paint < 2 seconds" — measurable ✅, BUT no measurement method specified. Should add: "as measured by Google PageSpeed Insights or Lighthouse on a simulated mid-range Android device."
✅ NFR §5.1 performance table is well-specified
✅ NFR §5.3 accessibility — touch target 44×44px is measurable

#### PRD-002 Findings

✅ Success metrics table is measurable (%, conversion rates)
⚠️ **FINDING V-007:** FR-32 "Bot must respond to every message within 5 seconds (PHP execution limit)" — good start, but should add measurement: "verified via Telegram Bot API response latency logs."
⚠️ **FINDING V-008:** Goal "Telegram bot completion rate ≥ 60%" — no measurement method defined. How is "completion" defined in the data? Should specify: "completion = reaching Step 7 (order confirmation) in the bot flow."

#### PRD-003 Findings

✅ Success metrics are measurable (< 60 seconds, < 5 minutes)
✅ Security table is implementable and testable
⚠️ **FINDING V-009:** FR-41 "Session expires after 8 hours of inactivity" — measurable ✅, but "inactivity" not defined. Specify: "inactivity = no HTTP request received from the authenticated session within 8 hours."

#### PRD-004 Findings

❌ **FINDING V-010 (HIGH):** Most NFRs in PRD-004 §5.1 are time estimates ("30 min/week"), not system requirements. PRD-004 is a process-heavy PRD, which is appropriate, but the NFR section should be repurposed as "Operational Constraints" rather than traditional NFRs. This is the most significant structural issue.
✅ Success metrics table is measurable with targets

---

### CHECK 3: Traceability Chain

**Standard:** Vision → Success Criteria → User Journeys → Functional Requirements

#### PRD-001 Traceability — STRONG ✅

✅ Vision clearly stated: "Browse like a boutique. Buy like a friend."
✅ US-20 (WhatsApp order) → FR-13 (WhatsApp button generation) — direct trace
✅ US-06 (₦ price display) → FR-01 (price format ₦X,XXX) — direct trace
✅ US-29 (seller status) → FR-07, FR-20 (status polling) — direct trace
✅ Acceptance criteria (AC-001 through AC-010) trace back to FRs
⚠️ **FINDING V-011:** US-19 ("Partner receives shared product link") has no corresponding FR. The requirement to produce shareable product URLs is implied but not explicitly defined.

#### PRD-002 Traceability — STRONG ✅

✅ US-34 (seller receives context-rich message) → FR-22 (message template) — clear trace
✅ US-38 (order confirmation in Telegram) → FR-33 Step 7 (confirmation message) — traced
✅ US-43 (seller status before messaging) → references PRD-001/PRD-003 correctly
⚠️ **FINDING V-012:** US-35 ("Ask a Question" button) is listed P1 in user stories but has no FR in the functional requirements section. It disappears. Needs FR or marked as explicit "Won't Have."

#### PRD-003 Traceability — STRONG ✅

✅ US-53 (product list) → FR-45 (admin dashboard) — traced
✅ US-64 (update stock per size) → FR-56 (quick stock update) — traced
✅ US-68 (seller status toggle) → FR-57 (status toggle) — traced
⚠️ **FINDING V-013:** US-56 (photo reorder by drag or arrows) is P1 but FR-56 (Quick Stock Update) is labeled as the same number. **Potential numbering conflict.** Photo reorder has no dedicated FR — it appears in FR-51 "existing photos shown as thumbnails" but reorder logic is not specified.

#### PRD-004 Traceability — MODERATE ⚠️

✅ US-73 (referral mechanic) → FR-61 to FR-65 — fully traced
✅ US-77 (broadcast announcements) → FR-66 to FR-69 — traced
✅ US-80 (size-up reminder) → FR-71 trigger types — traced
❌ **FINDING V-014 (HIGH):** US-87 ("Outfit of the Week") → FR-77 is adequate, but US-88 ("See clothing on real kids") → FR-78 is present BUT there is no storefront FR in PRD-001 that defines where/how the "Seen on Real Kids" gallery displays. The gallery is listed as "Could Have" in PRD-001's MoSCoW but has no FR written for it. Creates a dangling reference.
❌ **FINDING V-015 (HIGH):** US-89 ("Share a beautiful digital card") → FR-65 describes the card as a Canva design asset. This is correct but the PRD should clarify this is NOT a software feature — it's a design deliverable outside the application scope.

---

### CHECK 4: FR Anti-Pattern Check

**Standard:** No subjective adjectives, implementation leakage, vague quantifiers, missing test criteria.

#### All PRDs Consolidated Findings

✅ No "easy to use", "intuitive", "user-friendly" detected
✅ No "fast" or "responsive" without metrics
⚠️ **FINDING V-016:** PRD-002 FR-29 "Away Message" — "Hi! Thanks for reaching out 💛 I'm not available right now..." — this is an example/template shown inline in the FR. It should be a template reference, not embedded in the requirement itself. Minor.
⚠️ **FINDING V-017:** PRD-001 FR-07 mentions "Persistent header" — leaks into admin territory. See V-001.
❌ **FINDING V-018 (MEDIUM):** PRD-003 FR-43 "Hash generated with `password_hash($plain, PASSWORD_BCRYPT)`" — this is **implementation leakage**. The FR should state: "Passwords stored using a one-way cryptographic hash with a minimum work factor of 10" — not name the specific PHP function. The function choice belongs in architecture/code.
⚠️ **FINDING V-019:** PRD-001 FR-07 references "same Hostinger account" as a constraint — this is a deployment implementation detail, not a functional requirement. Constraints should live in NFRs §5.4.

---

### CHECK 5: Non-Functional Requirements Quality

**Standard:** NFRs must be measurable with "The system shall [metric] [condition] [measurement method]"

#### PRD-001 NFR — GOOD ✅

✅ PageSpeed ≥ 85 — measurable
✅ FCP < 2s — measurable
⚠️ **FINDING V-020:** "Total page weight < 100KB" — good, but should add measurement method: "as measured by Chrome DevTools Network panel on cold load."
✅ Browser compatibility list is specific and actionable
✅ Accessibility touch target 44×44px is measurable

#### PRD-002 NFR — GOOD ✅

✅ WA link behaviour table is specific
✅ Message encoding constraints are technical and precise
⚠️ **FINDING V-021:** "Bot must respond to every message within 5 seconds" — missing measurement method. Add: "verified via Telegram getUpdates API or server response logs."

#### PRD-003 NFR — GOOD ✅

✅ Security table is implementable and specific
✅ Touch target 44px is measurable
⚠️ **FINDING V-022:** "Pages load within 2 seconds on average shared hosting load" — vague qualifier "average shared hosting load". Should specify: "under normal operating conditions (< 100 concurrent users on Hostinger shared plan)" or simply remove the qualifier if unverifiable.

#### PRD-004 NFR — NEEDS RESTRUCTURE ❌

❌ **FINDING V-010 (repeated):** §5.1 "Zero Technology Dependency" is a design constraint not a measurable NFR.
❌ §5.2 "Seller Time Budget" is an operational guideline, not an NFR.
✅ §5.3 WA Broadcast Compliance is appropriate as a constraint.

**Recommendation:** Rename PRD-004 §5 as "Operational Constraints" and move time budget to appendix.

---

### CHECK 6: Cross-PRD Consistency

**Standard:** No contradictions between PRDs, shared concepts defined once, dependencies are correct.

#### Consistency Findings

✅ Price format (₦ integer, no decimals) consistent across all 4 PRDs
✅ Database schema referenced consistently (MariaDB, same table names)
✅ Tech stack (PHP, Hostinger, Alpine.js) consistent
✅ WhatsApp approach (wa.me free links, no API) consistent
✅ The 9 categories are consistent (PRD-001 lists them, PRD-003 manages them)
✅ Session auth approach (PHP sessions) consistent

⚠️ **FINDING V-023:** PRD-001 FR-07 says "seller_config.wa_number" and PRD-002 FR-21 also references "seller_config.wa_number" — consistent ✅. But PRD-003 Settings page (FR-59) lists "WhatsApp Number" as an editable field but the column name isn't confirmed as `wa_number`. Should ensure PRD-003 explicitly uses the same key name as PRD-001/002.
⚠️ **FINDING V-024:** PRD-001 shows Telegram button linking to `https://t.me/{telegram_username}` (user account link), while PRD-002 FR-35 shows `https://t.me/{bot_username}?start={encoded_product_id}` (bot deep link). **These are different links** — one goes to a human Telegram account, the other to the bot. The PRDs need to align on which the storefront button links to. Decision required.
❌ **FINDING V-025 (HIGH):** PRD-001 mentions "Save for Later" copies URL to clipboard (FR-15 MVP). PRD-002 mentions WA saved messages approach for the same feature (FR-67 indirectly). No explicit contradiction but the feature lacks a single canonical definition across PRDs.
⚠️ **FINDING V-026:** PRD-001 AC-010 references "mid-range Android device" for performance measurement but no device specification is given. Should align with NFR §5.2 "Android smartphones, often mid-range" — add a specific test device profile (e.g., "Chrome DevTools Device Simulation: Moto G4 or equivalent").

---

### CHECK 7: Open Questions Completeness

**Standard:** All blocking open questions are captured and prioritized.

#### OQ Audit

✅ 24 open questions captured in prd-index.md
✅ Priority assigned (High/Medium/Low)
✅ High-priority OQs correctly identified

❌ **FINDING V-027 (HIGH — Missing OQ):** No open question captures: **"What is the product photo aspect ratio standard?"** This affects card grid layout consistency (all cards must be same height). Should be OQ-25.
❌ **FINDING V-028 (HIGH — Missing OQ):** No open question captures: **"Does Telegram button link to the bot or the seller's personal Telegram?"** This is the V-024 finding above. Should be OQ-26.
⚠️ **FINDING V-029 (Medium — Missing OQ):** No open question about: **"Which Nigerian payment methods will be communicated in the WhatsApp conversation?"** (Bank transfer? Mobile money? Both?) The PRD scope excludes payment processing but the handoff to WhatsApp needs to inform this. Should be OQ-27.

---

## Validation Summary

### Issue Triage

| ID | Severity | PRD | Issue | Recommendation |
|----|----------|-----|-------|---------------|
| V-001 | ⚠️ Low | PRD-001 | FR-07 admin content in storefront PRD | Remove from PRD-001, already in PRD-003 |
| V-002 | ⚠️ Low | PRD-001 | Slightly narrative context paragraph | Acceptable, minor tightening optional |
| V-003 | ⚠️ Low | PRD-004 | Time budget table is guideline, not NFR | Move to appendix |
| V-004 | ⚠️ Medium | PRD-004 | FRs describing manual seller processes | Rename as "Operational Procedures" |
| V-005 | ⚠️ Low | PRD-001 | Upload enforcement stated in Storefront PRD | Note as cross-reference; keep in PRD-003 |
| V-006 | ⚠️ Medium | PRD-001 | AC-010 missing measurement method | Add "as measured by PageSpeed Insights on simulated Moto G4" |
| V-007 | ⚠️ Low | PRD-002 | Bot 5s response missing measurement method | Add log measurement reference |
| V-008 | ⚠️ Medium | PRD-002 | "Completion" metric undefined | Define: completion = Step 7 reached |
| V-009 | ⚠️ Low | PRD-003 | "Inactivity" not defined | Add: "no HTTP request within 8 hours" |
| V-010 | ❌ High | PRD-004 | NFR section contains non-measurable items | Rename §5 as "Operational Constraints" |
| V-011 | ⚠️ Medium | PRD-001 | US-19 (shareable URL) has no FR | Add FR: "Each product detail page has a unique, shareable URL" |
| V-012 | ⚠️ Medium | PRD-002 | US-35 "Ask about sizing" button has no FR | Add FR or explicitly move to Won't Have |
| V-013 | ⚠️ Medium | PRD-003 | FR numbering conflict (FR-56 used twice) | Renumber photo reorder FR |
| V-014 | ❌ High | PRD-004 | "Seen on Real Kids" gallery — no FR in PRD-001 | Add FR to PRD-001 or remove US-88 from PRD-004 |
| V-015 | ❌ High | PRD-004 | US-89 "Share card" unclear if software or design | Explicitly note: "Design deliverable, not software feature" |
| V-016 | ⚠️ Low | PRD-002 | Template embedded in FR | Move to separate templates appendix (already done in PRD-002 §6) |
| V-017 | ⚠️ Low | PRD-001 | Visual/admin detail in storefront FR | Minor, acceptable |
| V-018 | ❌ Medium | PRD-003 | `password_hash()` PHP function named in FR | Replace with capability statement |
| V-019 | ⚠️ Low | PRD-001 | "Same Hostinger account" in FR | Move to NFR §5.4 |
| V-020 | ⚠️ Low | PRD-001 | Page weight NFR missing measurement method | Add Chrome DevTools reference |
| V-021 | ⚠️ Low | PRD-002 | Bot response time missing measurement method | Add log measurement reference |
| V-022 | ⚠️ Low | PRD-003 | "Average shared hosting load" is vague | Specify condition or remove qualifier |
| V-023 | ⚠️ Low | PRD-003 | config key alignment | Explicitly state `wa_number` as key in PRD-003 |
| V-024 | ❌ High | PRD-001/002 | Telegram button links to different targets | Decide: bot link or seller account link |
| V-025 | ❌ High | PRD-001/002 | "Save for Later" inconsistently defined | Write single canonical definition |
| V-026 | ⚠️ Low | PRD-001 | AC-010 device not specified | Add: "Moto G4 simulation in Chrome DevTools" |
| V-027 | ❌ High | All | Missing OQ: photo aspect ratio | Add OQ-25 to prd-index.md |
| V-028 | ❌ High | PRD-001/002 | Missing OQ: bot vs seller Telegram link | Add OQ-26 to prd-index.md |
| V-029 | ⚠️ Medium | PRD-002 | Missing OQ: payment method communication | Add OQ-27 to prd-index.md |

---

### Score by PRD

| PRD | Format | Density | Measurability | Traceability | Cross-PRD | Score |
|-----|--------|---------|--------------|-------------|-----------|-------|
| PRD-001 | BMAD Variant | ✅ PASS | ✅ PASS | ✅ STRONG | ⚠️ 2 issues | **88/100** |
| PRD-002 | BMAD Variant | ✅ PASS | ⚠️ Minor | ✅ STRONG | ❌ 2 issues | **82/100** |
| PRD-003 | BMAD Variant | ✅ PASS | ✅ PASS | ✅ STRONG | ⚠️ 1 issue | **85/100** |
| PRD-004 | BMAD Variant | ⚠️ Minor | ❌ Needs rework | ⚠️ 2 gaps | ⚠️ 1 issue | **74/100** |

**Suite Average: 82/100 — APPROVED WITH REMEDIATION**

---

## Overall Verdict

### ✅ APPROVED WITH REMEDIATION

The PRD suite is **well-structured, comprehensive, and suitable for downstream consumption** (UX Design, Architecture, Epics & Stories) with the following conditions:

#### Must Fix Before Proceeding to Epics (Blockers)
1. **V-024** — Decide: Telegram button = seller account OR bot deep link (affects PRD-001 + PRD-002)
2. **V-025** — Write canonical definition of "Save for Later" feature (affects PRD-001 + PRD-002)
3. **V-014** — Either add "Seen on Real Kids" gallery FR to PRD-001 or remove US-88 from PRD-004
4. **V-010** — Rename PRD-004 §5 Non-Functional Requirements to "Operational Constraints"
5. **V-027/V-028** — Add OQ-25 (photo aspect ratio) and OQ-26 (Telegram link target) to prd-index.md

#### Should Fix Before Development Starts (Non-Blocking)
6. **V-018** — Remove `password_hash($plain, PASSWORD_BCRYPT)` from PRD-003 FR-43
7. **V-011** — Add FR for unique shareable product URLs to PRD-001
8. **V-012** — Add FR or explicitly list US-35 under Won't Have in PRD-002
9. **V-008** — Define "completion" in Telegram bot success metric
10. **V-006** — Add measurement device to AC-010 (PageSpeed measurement method)

#### Nice to Fix (Low Priority)
- V-001, V-003, V-007, V-009, V-013, V-015, V-016, V-019, V-020, V-021, V-022, V-023, V-026

---

## Remediation Actions Required

### Action 1 — ✅ RESOLVED
> **Decision 1 — Telegram button:** Links to **seller's personal Telegram account** (`t.me/{telegram_username}`). PRD-001 FR-14 and PRD-002 FR-35 both updated.

### Action 2 — ✅ RESOLVED
> **Decision 2 — "Save for Later":** Opens **WhatsApp saved messages** via `wa.me` with no phone number. Falls back to clipboard copy if WhatsApp not installed. PRD-001 FR-15 updated. New AC-012 written.

### Action 3 — ✅ RESOLVED
> **Decision 3 — Photo aspect ratio:** All product photos use **4:5 portrait**. PRD-001 FR-08 and PRD-003 FR-49 both updated.

### Action 4 — ✅ COMPLETE (EP Applied)
All blocker and high-priority non-blocking findings patched in PRD v1.1.
All 4 PRDs updated to version 1.1. prd-index.md OQ tracker updated.

---

## ✅ Final Status: APPROVED

**Suite Version:** 1.1
**Validated & Patched:** 2026-04-20
**All Blockers:** Resolved ✅
**Ready for:** CE (Epics & Stories)

---

*Validation complete — bloomrocxx PRD Suite v1.0*
*Validated by John (PM Persona) using BMAD Validation Standard v6.3.0*
