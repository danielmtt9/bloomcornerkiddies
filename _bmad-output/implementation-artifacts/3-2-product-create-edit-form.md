# Story 3.2: Product Create/Edit Form

Status: done

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

- [x] Task 1 — Build the create/edit form surface (AC: 1, 2, 3)
  - [x] Implement required and optional inputs.
  - [x] Use a multi-select or equivalent UX for `categories[]`.
- [x] Task 2 — Persist products and category memberships (AC: 3, 4, 5)
  - [x] Save products and `product_categories` transactionally.
  - [x] Load existing values correctly in edit mode.
- [x] Task 3 — Verify create/edit behavior (AC: 1, 2, 3, 4, 5)
  - [x] Add coverage or smoke verification.
  - [x] Record results in this story.

## Dev Notes

- Public/data impact source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:356)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

- `php -l public_html/admin/add.php`
- `php -l public_html/admin/edit.php`
- `php -l public_html/admin/partials/product-form.php`
- `npm run test-admin-products`
- `npm run test-admin-products-db`

### Completion Notes List

- Added `/admin/add.php` and `/admin/edit.php` on top of a shared product form partial with required and optional fields aligned to the PRD.
- Category selection now uses `categories[]` multi-select backed by the `categories` table and preserves selection on edit.
- Product create/update flows now write `products`, `product_categories`, and `product_sizes` inside one transaction and rehydrate existing values for edit mode.

### File List

- public_html/admin/add.php
- public_html/admin/edit.php
- public_html/admin/partials/product-form.php
- public_html/includes/products.php
- scripts/test-admin-products.php
- scripts/test-admin-products.mjs
- package.json
- _bmad-output/implementation-artifacts/3-2-product-create-edit-form.md

### Change Log

- Story created from Lane A sprint plan.
- Implemented the create/edit product form and transactional category persistence.
