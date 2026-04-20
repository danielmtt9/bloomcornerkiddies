# Story 5.4: Product Grid & Filters

Status: ready-for-dev

## Story

As a parent,
I want to browse and narrow products quickly,
so that relevant products are discoverable without page reloads.

## Acceptance Criteria

1. Products render in a responsive grid using the primary image and badge state.
2. Category filter tabs are horizontally scrollable on mobile.
3. Gender filter composes with category filtering.
4. Selecting a category matches products by membership in that category.
5. Sold-out products remain viewable but visibly marked.
6. Product images use native lazy loading.

## Tasks / Subtasks

- [ ] Task 1 — Build product grid rendering (AC: 1, 5, 6)
  - [ ] Render primary image, badge state, and card data.
  - [ ] Keep sold-out products visible but marked.
- [ ] Task 2 — Implement filter interactions (AC: 2, 3, 4)
  - [ ] Add mobile-scrollable category tabs.
  - [ ] Compose gender/category logic against multi-category data.
- [ ] Task 3 — Verify browse/filter behavior (AC: 1, 2, 3, 4, 5, 6)
  - [ ] Add smoke/test verification.
  - [ ] Record results in this story.

## Dev Notes

- Multi-category requirement source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:152)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

### Completion Notes List

### File List

### Change Log

- Story created from Lane A sprint plan.
