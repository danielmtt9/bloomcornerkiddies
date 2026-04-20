# Story 4.1: Store Settings Page

Status: ready-for-dev

## Story

As a seller,
I want to manage storefront-facing business settings in admin,
so that the storefront reads from config data rather than hardcoded copy.

## Acceptance Criteria

1. `/admin/settings.php` loads and saves store name, tagline, intro text, WhatsApp number, Telegram link, delivery info, status message, and referral discount percent.
2. Empty referral discount stores NULL.
3. Placeholder text is acceptable for still-open business copy values.

## Tasks / Subtasks

- [ ] Task 1 — Build the settings form surface (AC: 1, 3)
  - [ ] Render all required `seller_config` fields.
  - [ ] Preserve placeholder-safe behavior.
- [ ] Task 2 — Implement save/update logic (AC: 1, 2)
  - [ ] Persist values to `seller_config`.
  - [ ] Normalize empty referral discount to NULL.
- [ ] Task 3 — Verify settings behavior (AC: 1, 2, 3)
  - [ ] Add smoke/test coverage.
  - [ ] Record results in this story.

## Dev Notes

- seller_config keys source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:79)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

### Completion Notes List

### File List

### Change Log

- Story created from Lane A sprint plan.
