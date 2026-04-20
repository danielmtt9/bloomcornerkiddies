# Story 3.4: Product Image Uploads

Status: done

## Story

As a seller,
I want to upload multiple product images safely,
so that the storefront can show consistent 4:5 images with one primary thumbnail.

## Acceptance Criteria

1. Photo upload accepts JPEG, PNG, and WebP only.
2. File validation uses MIME type and a 5MB max per image.
3. Files are saved under `/public_html/uploads/products/{product_id}/`.
4. First image is stored as primary image (`sort_order = 0`).
5. Save operation rolls back if image persistence fails.
6. UI communicates the 4:5 portrait requirement clearly.

## Tasks / Subtasks

- [x] Task 1 — Implement upload validation and storage (AC: 1, 2, 3, 4)
  - [x] Validate MIME and size.
  - [x] Persist files and image rows in the correct path/order.
- [x] Task 2 — Integrate uploads into transactional product save (AC: 4, 5)
  - [x] Ensure product save fails cleanly if upload persistence fails.
  - [x] Keep primary image behavior deterministic.
- [x] Task 3 — Verify upload behavior (AC: 1, 2, 3, 4, 5, 6)
  - [x] Add smoke/test coverage.
  - [x] Record results in this story.

## Dev Notes

- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:403)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

- `php -l public_html/includes/products.php`
- `npm run test-admin-products`

### Completion Notes List

- Added image upload validation for MIME type and 5MB size limit using `finfo` rather than file extension checks.
- Product image persistence now writes under `/public_html/uploads/products/{product_id}/` with deterministic `sort_order`, preserving the first image as primary.
- Save flow cleans up moved files when transactional persistence fails, and the UI now states the 4:5 portrait requirement clearly.

### File List

- public_html/includes/products.php
- public_html/admin/partials/product-form.php
- scripts/test-admin-products.php
- package.json
- _bmad-output/implementation-artifacts/3-4-product-image-uploads.md

### Change Log

- Story created from Lane A sprint plan.
- Implemented validated multi-image uploads with transactional cleanup behavior.
