# Story 3.5: Edit, Hide, Delete

Status: done

## Story

As a seller,
I want to maintain catalogue accuracy over time,
so that products can be updated, hidden, or permanently removed without corrupting related data.

## Acceptance Criteria

1. Seller can edit any product and save changes.
2. Seller can toggle product availability without deleting it.
3. Delete action requires explicit confirmation.
4. Deleting a product cascades safely to images, sizes, and product-category rows.

## Tasks / Subtasks

- [x] Task 1 — Finalize edit/update flows (AC: 1, 2)
  - [x] Support full update and visibility toggling.
  - [x] Keep related records aligned on save.
- [x] Task 2 — Implement safe delete flow (AC: 3, 4)
  - [x] Require confirmation before delete.
  - [x] Ensure deletes do not leave orphaned rows/files.
- [x] Task 3 — Verify lifecycle actions (AC: 1, 2, 3, 4)
  - [x] Add coverage or smoke verification.
  - [x] Record evidence in this story.

## Dev Notes

- Story source: [epics.md](/home/danielaroko/applications/bloomrocxx/_bmad-output/planning-artifacts/epics.md:424)

## Dev Agent Record

### Agent Model Used

gpt-5

### Debug Log References

- `php -l public_html/admin/delete.php`
- `npm run test-admin-products-db`

### Completion Notes List

- Edit flow now supports full record updates, existing image removal, and availability toggling without breaking related category or size rows.
- Added `/admin/delete.php` with explicit confirmation before permanent deletion.
- Delete verification confirms DB cascades remove `product_images`, `product_sizes`, and `product_categories`, while PHP cleanup removes stored image files after commit.

### File List

- public_html/admin/delete.php
- public_html/admin/edit.php
- public_html/includes/products.php
- scripts/test-admin-products.mjs
- package.json
- _bmad-output/implementation-artifacts/3-5-edit-hide-delete.md

### Change Log

- Story created from Lane A sprint plan.
- Implemented product lifecycle actions for edit, hide, and delete.
