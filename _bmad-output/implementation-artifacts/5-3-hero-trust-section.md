# Story 5.3: Hero & Trust Section

Status: done

## Story

As a parent,
I want to see trust signals immediately on arrival,
so that seller identity and availability are clear before browsing.

## Acceptance Criteria

1. Hero displays store name, tagline or placeholder, intro text, seller status, and direct WhatsApp/Telegram links.
2. Seller status loads on page load and updates via polling every 60 seconds.
3. Hero remains usable above the fold on a typical mobile viewport.

## Tasks / Subtasks

- [x] Task 1 — Build hero content and trust UI (AC: 1, 3)
  - [x] Render config-backed store identity and contact links.
  - [x] Keep the layout mobile-first.
- [x] Task 2 — Wire status polling into the hero (AC: 2)
  - [x] Load status on first render.
  - [x] Refresh on the documented polling interval.
- [x] Task 3 — Verify hero behavior (AC: 1, 2, 3)
  - [x] Add smoke/test verification.
  - [x] Record results in this story.

## Dev Notes

- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:582)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

- `npm run test-storefront-shell`
- `npm run test-storefront-api`

### Completion Notes List

- Hero now renders store identity from the status payload, including store name, tagline, intro text, seller status, and direct WhatsApp/Telegram links.
- Added trust cards and a mobile-first hero layout that stays usable above the fold.
- Status now loads on first render and refreshes every 60 seconds through `/api/status.php`.

### File List

- public_html/index.html
- public_html/includes/storefront.php
- public_html/api/status.php
- scripts/test-storefront-shell.mjs
- scripts/test-storefront-api.mjs
- package.json
- _bmad-output/implementation-artifacts/5-3-hero-trust-section.md

### Change Log

- Story created from Lane A sprint plan.
- Implemented the trust hero and live status polling behavior.
