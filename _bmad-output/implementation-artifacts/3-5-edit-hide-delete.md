# Story 3.5: Edit, Hide, Delete

Status: ready-for-dev

## Story

As a seller,
I want to maintain catalogue accuracy over time,
so that products can be updated, hidden, or permanently removed without corrupting related data.

## Acceptance Criteria

1. Seller can edit any product and save changes.
2. Seller can toggle product availability without deleting it.
3. Delete action requires explicit confirmation.
4. Deleting a product cascades safely to images, sizes, and product-category rows.

## Tasks / Subtasks

- [ ] Task 1 — Finalize edit/update flows (AC: 1, 2)
  - [ ] Support full update and visibility toggling.
  - [ ] Keep related records aligned on save.
- [ ] Task 2 — Implement safe delete flow (AC: 3, 4)
  - [ ] Require confirmation before delete.
  - [ ] Ensure deletes do not leave orphaned rows/files.
- [ ] Task 3 — Verify lifecycle actions (AC: 1, 2, 3, 4)
  - [ ] Add coverage or smoke verification.
  - [ ] Record evidence in this story.

## Dev Notes

- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:424)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

### Completion Notes List

### File List

### Change Log

- Story created from Lane A sprint plan.
