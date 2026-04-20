# Story 5.3: Hero & Trust Section

Status: ready-for-dev

## Story

As a parent,
I want to see trust signals immediately on arrival,
so that seller identity and availability are clear before browsing.

## Acceptance Criteria

1. Hero displays store name, tagline or placeholder, intro text, seller status, and direct WhatsApp/Telegram links.
2. Seller status loads on page load and updates via polling every 60 seconds.
3. Hero remains usable above the fold on a typical mobile viewport.

## Tasks / Subtasks

- [ ] Task 1 — Build hero content and trust UI (AC: 1, 3)
  - [ ] Render config-backed store identity and contact links.
  - [ ] Keep the layout mobile-first.
- [ ] Task 2 — Wire status polling into the hero (AC: 2)
  - [ ] Load status on first render.
  - [ ] Refresh on the documented polling interval.
- [ ] Task 3 — Verify hero behavior (AC: 1, 2, 3)
  - [ ] Add smoke/test verification.
  - [ ] Record results in this story.

## Dev Notes

- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:582)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

### Completion Notes List

### File List

### Change Log

- Story created from Lane A sprint plan.
