# Story 4.3: Referral Code Admin

Status: ready-for-dev

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

- [ ] Task 1 — Build referral list and create flow (AC: 1, 2)
  - [ ] Render referral list and creation UI.
  - [ ] Enforce code uniqueness and generation rules.
- [ ] Task 2 — Build detail and redemption flows (AC: 3, 4, 5)
  - [ ] Show history and record redemptions.
  - [ ] Support deactivate behavior without data loss.
- [ ] Task 3 — Verify referral admin behavior (AC: 1, 2, 3, 4, 5)
  - [ ] Add smoke/test coverage.
  - [ ] Record results in this story.

## Dev Notes

- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:494)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

### Completion Notes List

### File List

### Change Log

- Story created from Lane A sprint plan.
