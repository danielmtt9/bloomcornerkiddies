# Story 4.2: Seller Status Control

Status: done

## Story

As a seller,
I want to update live availability state,
so that storefront status indicators stay truthful.

## Acceptance Criteria

1. Admin provides status values `online`, `brb`, and `offline`.
2. Status changes persist to `seller_config.seller_status`.
3. Optional status message is saved alongside status changes.
4. Storefront can reflect the latest value within its polling interval.

## Tasks / Subtasks

- [x] Task 1 — Implement status controls in admin (AC: 1, 2, 3)
  - [x] Add status UI and status-message editing.
  - [x] Persist updates correctly.
- [x] Task 2 — Verify storefront-facing contract (AC: 4)
  - [x] Ensure updated values are available for polling/API use.
- [x] Task 3 — Add verification coverage (AC: 1, 2, 3, 4)
  - [x] Add smoke/test validation.
  - [x] Record results in this story.

## Dev Notes

- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:478)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

- `php -l public_html/admin/status.php`
- `npm run test-admin-settings`
- `npm run test-admin-settings-db`

### Completion Notes List

- Added seller status controls for `online`, `brb`, and `offline` on the settings screen plus a focused `/admin/status.php` handler for direct status updates.
- Status changes now persist to `seller_config.seller_status`, with status message saved alongside them.
- Storefront polling contract is satisfied because the latest status and message values now live in `seller_config`.

### File List

- public_html/admin/status.php
- public_html/admin/settings.php
- public_html/includes/settings.php
- scripts/test-admin-settings.php
- scripts/test-admin-settings.mjs
- package.json
- _bmad-output/implementation-artifacts/4-2-seller-status-control.md

### Change Log

- Story created from Lane A sprint plan.
- Implemented seller status controls and storefront-facing status persistence.
