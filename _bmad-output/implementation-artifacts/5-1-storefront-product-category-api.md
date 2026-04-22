# Story 5.1: Storefront Product & Category API

Status: done

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

- [x] Task 1 — Implement products/categories/status endpoints (AC: 1, 2, 3, 4, 5)
  - [x] Build query logic for multi-category product payloads.
  - [x] Return clean JSON responses with correct headers.
- [x] Task 2 — Implement category filtering behavior (AC: 2)
  - [x] Filter products by category membership via `product_categories`.
  - [x] Keep payload shape stable with `categories[]`.
- [x] Task 3 — Verify API behavior (AC: 1, 2, 3, 4, 5)
  - [x] Add endpoint-level tests or smoke verification.
  - [x] Record evidence in this story.

## Dev Notes

- API shape source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:70)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

- `php -l public_html/includes/storefront.php`
- `php -l public_html/api/products.php`
- `php -l public_html/api/categories.php`
- `php -l public_html/api/status.php`
- `npm run test-storefront-api`

### Completion Notes List

- Added shared storefront query helpers in `public_html/includes/storefront.php`.
- Implemented `/api/products.php`, `/api/categories.php`, and `/api/status.php` with JSON responses through `app_json()`.
- Product payload now exposes `sizes[]`, `categories[]`, `primary_image_url`, and badge state for available products only.
- Category filtering now joins through `product_categories` and keeps the `categories[]` payload shape stable.

### File List

- public_html/includes/storefront.php
- public_html/api/products.php
- public_html/api/categories.php
- public_html/api/status.php
- scripts/test-storefront-api.mjs
- package.json
- _bmad-output/implementation-artifacts/5-1-storefront-product-category-api.md

### Change Log

- Story created from Lane A sprint plan.
- Implemented the storefront JSON API and multi-category filtering contract.
