# Story 5.1: Storefront Product & Category API

Status: ready-for-dev

## Story

As a developer,
I want to expose storefront-ready JSON endpoints,
so that Alpine.js storefront logic can load products, categories, and seller status without embedded data duplication.

## Acceptance Criteria

1. `/api/products.php` returns available products with `id`, `name`, `price`, `original_price`, `brand`, `primary_image_url`, `sizes[]`, `categories[]`, and badge state.
2. Category filtering is supported by query parameter and joins through `product_categories`.
3. `/api/categories.php` returns the category set used by the storefront.
4. `/api/status.php` returns status, message, WA number, and Telegram link.
5. All API responses are JSON with correct content type.

## Tasks / Subtasks

- [ ] Task 1 — Implement products/categories/status endpoints (AC: 1, 2, 3, 4, 5)
  - [ ] Build query logic for multi-category product payloads.
  - [ ] Return clean JSON responses with correct headers.
- [ ] Task 2 — Implement category filtering behavior (AC: 2)
  - [ ] Filter products by category membership via `product_categories`.
  - [ ] Keep payload shape stable with `categories[]`.
- [ ] Task 3 — Verify API behavior (AC: 1, 2, 3, 4, 5)
  - [ ] Add endpoint-level tests or smoke verification.
  - [ ] Record evidence in this story.

## Dev Notes

- API shape source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:70)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

### Completion Notes List

### File List

### Change Log

- Story created from Lane A sprint plan.
