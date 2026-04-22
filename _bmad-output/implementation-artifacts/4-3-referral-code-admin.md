# Story 4.3: Referral Code Admin

Status: done

## Story

As a seller,
I want to create, review, and record referral code usage,
so that referral activity is trackable from the admin panel.

## Acceptance Criteria

1. `/admin/referrals.php` lists referral codes sorted by referral count descending.
2. Seller can create a new code from referrer name + WhatsApp number.
3. Seller can open a code detail view to see redemption history.
4. Seller can record a redemption that inserts a `referral_uses` row, increments the referral count, and snapshots current `referral_discount_percent`.
5. Seller can deactivate a code without deleting history.

## Tasks / Subtasks

- [x] Task 1 — Build referral list and create flow (AC: 1, 2)
  - [x] Render referral list and creation UI.
  - [x] Enforce code uniqueness and generation rules.
- [x] Task 2 — Build detail and redemption flows (AC: 3, 4, 5)
  - [x] Show history and record redemptions.
  - [x] Support deactivate behavior without data loss.
- [x] Task 3 — Verify referral admin behavior (AC: 1, 2, 3, 4, 5)
  - [x] Add smoke/test coverage.
  - [x] Record results in this story.

## Dev Notes

- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:494)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

- `php -l public_html/admin/referrals.php`
- `npm run test-admin-settings`
- `npm run test-admin-settings-db`

### Completion Notes List

- Replaced the placeholder referrals screen with a full referral admin view that lists codes by referral count descending and supports new-code creation.
- Added referral detail rendering with redemption history and a redemption-recording flow that inserts `referral_uses`, increments `referral_codes.total_referrals`, and snapshots the current referral discount.
- Added activate/deactivate behavior without deleting historical referral rows.

### File List

- public_html/admin/referrals.php
- public_html/includes/settings.php
- scripts/test-admin-settings.php
- scripts/test-admin-settings.mjs
- package.json
- _bmad-output/implementation-artifacts/4-3-referral-code-admin.md

### Change Log

- Story created from Lane A sprint plan.
- Implemented referral list, detail, redemption, and deactivate flows.
