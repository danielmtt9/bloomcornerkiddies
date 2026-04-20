# Story 3.1: Product List View

Status: ready-for-dev

## Story

As a seller,
I want to view all products in one manageable list,
so that I can quickly assess catalogue state and jump to the right action.

## Acceptance Criteria

1. `/admin/products.php` lists all products including hidden ones.
2. Each row shows primary thumbnail, name, price, assigned categories, availability status, and action buttons.
3. List is sorted by most recently updated first.
4. Add New Product action is prominent.

## Tasks / Subtasks

- [ ] Task 1 — Build the product list query and page shell (AC: 1, 2, 3, 4)
  - [ ] Query products with category aggregation and primary image.
  - [ ] Render the admin list view with required columns and actions.
- [ ] Task 2 — Wire sorting and empty/list states (AC: 1, 3, 4)
  - [ ] Default sort by most recently updated.
  - [ ] Keep the create action visible even with zero products.
- [ ] Task 3 — Verify list behavior (AC: 1, 2, 3, 4)
  - [ ] Add coverage or smoke verification for query/render behavior.
  - [ ] Record evidence in this story.

## Dev Notes

- Multi-category products must show assigned categories, not a single category field.
- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:340)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

### Completion Notes List

### File List

### Change Log

- Story created from Lane A sprint plan.
