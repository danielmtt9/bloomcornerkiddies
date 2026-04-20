# Story 3.1: Product List View

Status: done

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

- [x] Task 1 — Build the product list query and page shell (AC: 1, 2, 3, 4)
  - [x] Query products with category aggregation and primary image.
  - [x] Render the admin list view with required columns and actions.
- [x] Task 2 — Wire sorting and empty/list states (AC: 1, 3, 4)
  - [x] Default sort by most recently updated.
  - [x] Keep the create action visible even with zero products.
- [x] Task 3 — Verify list behavior (AC: 1, 2, 3, 4)
  - [x] Add coverage or smoke verification for query/render behavior.
  - [x] Record evidence in this story.

## Dev Notes

- Multi-category products must show assigned categories, not a single category field.
- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:340)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

- `php -l public_html/admin/products.php`
- `npm run test-admin-products`
- `npm run test-admin-products-db`

### Completion Notes List

- Added `/admin/products.php` as the catalogue list surface with primary thumbnail, price, aggregated categories, availability badge, and action links.
- Product list query now keeps hidden products visible in admin and sorts by `updated_at DESC, id DESC`.
- Empty-state rendering still keeps the create action prominent with a first-product CTA.

### File List

- public_html/admin/products.php
- public_html/includes/products.php
- scripts/test-admin-products.php
- scripts/test-admin-products.mjs
- package.json
- _bmad-output/implementation-artifacts/3-1-product-list-view.md

### Change Log

- Story created from Lane A sprint plan.
- Implemented the product list view and verified list-query behavior.
