# Story 3.2: Product Create/Edit Form

Status: ready-for-dev

## Story

As a seller,
I want to create and edit complete product records,
so that product data entered in admin is sufficient to power storefront browsing and detail pages.

## Acceptance Criteria

1. Required fields include product name, gender, price, one or more categories, at least one size, and at least one photo.
2. Optional fields include brand, original price, description, TikTok URL, material, and season/occasion tag.
3. Category input supports selecting multiple categories from the categories table.
4. Edit form loads existing categories and preserves them on save.
5. Product create/update runs inside a DB transaction when related records are written.

## Tasks / Subtasks

- [ ] Task 1 — Build the create/edit form surface (AC: 1, 2, 3)
  - [ ] Implement required and optional inputs.
  - [ ] Use a multi-select or equivalent UX for `categories[]`.
- [ ] Task 2 — Persist products and category memberships (AC: 3, 4, 5)
  - [ ] Save products and `product_categories` transactionally.
  - [ ] Load existing values correctly in edit mode.
- [ ] Task 3 — Verify create/edit behavior (AC: 1, 2, 3, 4, 5)
  - [ ] Add coverage or smoke verification.
  - [ ] Record results in this story.

## Dev Notes

- Public/data impact source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:356)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

### Completion Notes List

### File List

### Change Log

- Story created from Lane A sprint plan.
