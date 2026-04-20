# Story 6.3: Telegram Link, Save for Later, Notify Me

Status: ready-for-dev

## Story

As a parent,
I want to use alternative handoff and bookmark actions,
so that I have a non-WA backup path and can retain product context for later.

## Acceptance Criteria

1. Telegram button links to the seller's personal account from `seller_config.telegram_link`.
2. Save for Later opens WhatsApp saved messages when available.
3. Save for Later falls back to clipboard copy with toast confirmation if WhatsApp cannot be used.
4. All-sizes-sold-out products replace primary order CTA with Notify Me template behavior.

## Tasks / Subtasks

- [ ] Task 1 — Implement Telegram personal-link behavior (AC: 1)
  - [ ] Pull the link from config.
  - [ ] Keep bot behavior out of MVP.
- [ ] Task 2 — Implement Save for Later and Notify Me (AC: 2, 3, 4)
  - [ ] Support WA saved messages path and clipboard fallback.
  - [ ] Replace CTA when all sizes are sold out.
- [ ] Task 3 — Verify alternative actions (AC: 1, 2, 3, 4)
  - [ ] Add smoke/test verification.
  - [ ] Record results in this story.

## Dev Notes

- Telegram scope boundary source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:37)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

### Completion Notes List

### File List

### Change Log

- Story created from Lane A sprint plan.
