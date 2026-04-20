# Story 1.1: Core Commerce Tables

Status: ready-for-dev

## Story

As a developer,
I want the core product, image, size, category, and product-category schema created,
so that products can be stored once and categorized many-to-many.

## Acceptance Criteria

1. `products`, `product_images`, `product_sizes`, `categories`, and `product_categories` exist with correct keys and constraints.
2. `product_categories` enforces one row per `(product_id, category_id)`.
3. Deleting a product cascades to `product_images`, `product_sizes`, and `product_categories`.
4. Categories are seeded with the storefront category set used by filters.

## Tasks / Subtasks

- [ ] Task 1 — Define the multi-category schema (AC: 1, 2, 3)
  - [ ] Remove any single-category dependency from the DB design.
  - [ ] Define FKs and cascade behavior.
- [ ] Task 2 — Implement schema and seeds (AC: 1, 2, 3, 4)
  - [ ] Add/update SQL migrations for the core tables.
  - [ ] Seed the agreed storefront categories.
- [ ] Task 3 — Validate schema behavior (AC: 1, 2, 3, 4)
  - [ ] Verify unique pair enforcement and cascade expectations.
  - [ ] Record test/migration evidence in this story.

## Dev Notes

- Multi-category decision source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:61)
- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:191)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

### Completion Notes List

### File List

### Change Log

- Story created from Lane A sprint plan.
