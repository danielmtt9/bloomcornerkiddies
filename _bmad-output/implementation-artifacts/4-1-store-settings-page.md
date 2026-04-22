# Story 4.1: Store Settings Page

Status: done

## Story

As a seller,
I want to manage storefront-facing business settings in admin,
so that the storefront reads from config data rather than hardcoded copy.

## Acceptance Criteria

1. `/admin/settings.php` loads and saves store name, tagline, intro text, WhatsApp number, Telegram link, delivery info, status message, and referral discount percent.
2. Empty referral discount stores NULL.
3. Placeholder text is acceptable for still-open business copy values.

## Tasks / Subtasks

- [x] Task 1 — Build the settings form surface (AC: 1, 3)
  - [x] Render all required `seller_config` fields.
  - [x] Preserve placeholder-safe behavior.
- [x] Task 2 — Implement save/update logic (AC: 1, 2)
  - [x] Persist values to `seller_config`.
  - [x] Normalize empty referral discount to NULL.
- [x] Task 3 — Verify settings behavior (AC: 1, 2, 3)
  - [x] Add smoke/test coverage.
  - [x] Record results in this story.

## Dev Notes

- seller_config keys source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:79)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

- `php -l public_html/admin/settings.php`
- `php -l public_html/includes/settings.php`
- `npm run test-admin-settings`
- `npm run test-admin-settings-db`

### Completion Notes List

- Replaced the placeholder settings screen with a full settings form that edits store name, tagline, intro text, WhatsApp number, Telegram link, delivery info, status message, payment info, seller status, and referral discount percent.
- Persisted all storefront-facing settings through `seller_config` instead of hardcoded copy.
- Normalized blank referral discount input to `NULL` so referral tracking can stay active without enforcing a discount.

### File List

- public_html/admin/settings.php
- public_html/includes/settings.php
- scripts/test-admin-settings.php
- scripts/test-admin-settings.mjs
- package.json
- _bmad-output/implementation-artifacts/4-1-store-settings-page.md

### Change Log

- Story created from Lane A sprint plan.
- Implemented the store settings page and seller_config persistence.
