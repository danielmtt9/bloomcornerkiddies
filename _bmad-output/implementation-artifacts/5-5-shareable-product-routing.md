# Story 5.5: Shareable Product Routing

Status: ready-for-dev

## Story

As a parent,
I want to open and share a specific product URL,
so that a copied product link lands directly on the intended product detail view.

## Acceptance Criteria

1. Tapping a product updates the URL to `/?product={id}` without a full page reload.
2. Loading `/?product={id}` opens the correct product detail view directly.
3. Browser back returns the user to the grid rather than a blank state.
4. Copied product URLs remain stable when shared.

## Tasks / Subtasks

- [ ] Task 1 — Implement product-detail routing state (AC: 1, 2)
  - [ ] Update URL on product open.
  - [ ] Restore the correct product on direct load.
- [ ] Task 2 — Implement back/share-safe navigation behavior (AC: 3, 4)
  - [ ] Preserve grid return behavior.
  - [ ] Keep URLs stable and shareable.
- [ ] Task 3 — Verify routing behavior (AC: 1, 2, 3, 4)
  - [ ] Add smoke/test verification.
  - [ ] Record results in this story.

## Dev Notes

- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:608)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

### Completion Notes List

### File List

### Change Log

- Story created from Lane A sprint plan.
