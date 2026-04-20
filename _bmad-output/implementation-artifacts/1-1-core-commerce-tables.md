# Story 1.1: Core Commerce Tables

Status: done

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

- [x] Task 1 — Define the multi-category schema (AC: 1, 2, 3)
  - [x] Remove any single-category dependency from the DB design.
  - [x] Define FKs and cascade behavior.
- [x] Task 2 — Implement schema and seeds (AC: 1, 2, 3, 4)
  - [x] Add/update SQL migrations for the core tables.
  - [x] Seed the agreed storefront categories.
- [x] Task 3 — Validate schema behavior (AC: 1, 2, 3, 4)
  - [x] Verify unique pair enforcement and cascade expectations.
  - [x] Record test/migration evidence in this story.

## Dev Notes

- Multi-category decision source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:61)
- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:191)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

- `npm run migrate -- --fresh`
- `npm run test-schema`
- `npm run test-db`

### Completion Notes List

- Replaced the legacy single-category `products.category_id` design with a many-to-many `product_categories` join table keyed by `(product_id, category_id)`.
- Added cascade-on-delete relationships from `products` to `product_images`, `product_sizes`, and `product_categories`.
- Seeded the storefront category set used by filters: `newborn`, `baby`, `toddler`, `girls`, `boys`, `school`, `occasions`, `pyjamas`, and `footwear`.
- Kept deferred Telegram bot persistence out of Sprint 1 by removing `bot_sessions` from the active MVP schema.

### File List

- database/schema.sql
- scripts/migrate.mjs
- scripts/test-connection.mjs
- scripts/verify-schema.mjs
- package.json
- _bmad-output/implementation-artifacts/1-1-core-commerce-tables.md

### Change Log

- Story created from Lane A sprint plan.
- Implemented the multi-category core commerce schema and verified the fresh migration against the live DB.
