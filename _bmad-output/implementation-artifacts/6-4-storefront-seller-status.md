# Story 6.4: Storefront Seller Status

Status: ready-for-dev

## Story

As a parent,
I want to understand reply expectations before messaging,
so that storefront status reads consistently across hero and product detail surfaces.

## Acceptance Criteria

1. Storefront maps `online`, `brb`, and `offline` into distinct visual states and copy.
2. Status updates are pulled from `/api/status.php`.
3. Product detail view shows the same status state used by the hero.

## Tasks / Subtasks

- [ ] Task 1 — Normalize storefront status presentation (AC: 1, 3)
  - [ ] Use consistent state mapping in hero and detail views.
  - [ ] Reuse one display contract.
- [ ] Task 2 — Wire status endpoint updates (AC: 2)
  - [ ] Pull from `/api/status.php`.
  - [ ] Keep polling/state behavior consistent.
- [ ] Task 3 — Verify status behavior (AC: 1, 2, 3)
  - [ ] Add smoke/test verification.
  - [ ] Record results in this story.

## Dev Notes

- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:711)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

### Completion Notes List

### File List

### Change Log

- Story created from Lane A sprint plan.
