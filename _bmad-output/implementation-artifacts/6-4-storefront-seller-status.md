# Story 6.4: Storefront Seller Status

Status: done

## Story

As a parent,
I want to understand reply expectations before messaging,
so that storefront status reads consistently across hero and product detail surfaces.

## Acceptance Criteria

1. Storefront maps `online`, `brb`, and `offline` into distinct visual states and copy.
2. Status updates are pulled from `/api/status.php`.
3. Product detail view shows the same status state used by the hero.

## Tasks / Subtasks

- [x] Task 1 — Normalize storefront status presentation (AC: 1, 3)
  - [x] Use consistent state mapping in hero and detail views.
  - [x] Reuse one display contract.
- [x] Task 2 — Wire status endpoint updates (AC: 2)
  - [x] Pull from `/api/status.php`.
  - [x] Keep polling/state behavior consistent.
- [x] Task 3 — Verify status behavior (AC: 1, 2, 3)
  - [x] Add smoke/test verification.
  - [x] Record results in this story.

## Dev Notes

- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:711)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

- `npm run test-storefront-shell`
- `npm run test-storefront-handoff`

### Completion Notes List

- Reused the status contract from `/api/status.php` across hero and detail surfaces so `online`, `brb`, and `offline` map consistently.
- Detail view now exposes the same seller status signal and message alongside delivery guidance.

### File List

- `public_html/index.html`
- `public_html/includes/storefront.php`
- `scripts/test-storefront-handoff.mjs`

### Change Log

- Story created from Lane A sprint plan.
- Story completed and verified for Sprint 6.
